<?php
declare(strict_types=1);
const STORE=__DIR__.'/storage';
// Output credentials are encrypted at rest so the dashboard can display copy-ready URLs.
// Keep the storage directory private and preserve its key when migrating the installation.
function output_secret_key():string {
 $path=STORE.'/output.key';
 if(!is_file($path)){
  $key=random_bytes(32);
  if(file_put_contents($path,$key,LOCK_EX)===false)throw new Exception('Cannot create encryption key');
  @chmod($path,0600);
 }
 $key=file_get_contents($path);
 if($key===false||strlen($key)!==32)throw new Exception('Output encryption key invalid');
 return $key;
}
function output_encrypt(string $plain):string {
 if(!function_exists('openssl_encrypt'))throw new Exception('OpenSSL PHP extension is required');
 $iv=random_bytes(12);$tag='';
 $cipher=openssl_encrypt($plain,'aes-256-gcm',output_secret_key(),OPENSSL_RAW_DATA,$iv,$tag);
 if($cipher===false)throw new Exception('Could not encrypt output password');
 return base64_encode($iv.$tag.$cipher);
}
function output_decrypt(string $encoded):string {
 if(!$encoded)return '';
 $bytes=base64_decode($encoded,true);
 if($bytes===false||strlen($bytes)<29)return '';
 $plain=openssl_decrypt(substr($bytes,28),'aes-256-gcm',output_secret_key(),OPENSSL_RAW_DATA,substr($bytes,0,12),substr($bytes,12,16));
 return $plain===false?'':$plain;
}
function data():array { $p=STORE.'/data.json'; $d=is_file($p)?json_decode(file_get_contents($p),true):null;return is_array($d)?$d:['users'=>[],'workspaces'=>['master'=>['sources'=>[],'epg'=>[],'channels'=>[],'settings'=>[],'groups'=>[]]],'settings'=>[]]; }
function save(array $d):void {if(!is_dir(STORE))mkdir(STORE,0700,true);$p=STORE.'/data.json';$f=fopen($p.'.tmp','wb');if(!$f)throw new Exception('Storage unavailable');flock($f,LOCK_EX);fwrite($f,json_encode($d,JSON_UNESCAPED_SLASHES|JSON_INVALID_UTF8_SUBSTITUTE));fflush($f);flock($f,LOCK_UN);fclose($f);rename($p.'.tmp',$p);}
function init():void {
 if(!is_dir(STORE))mkdir(STORE,0700,true);
 if(!is_file(STORE.'/data.json'))save(data());
 // One-time v6 output credential preset for existing installations.
 // This does not alter the administrator login password or erase sources.
 $d=data();$changed=false;
 if(empty($d['settings']['v6_output_preset_applied'])){
  foreach($d['users'] as &$u){
   if(($u['role']??'')==='admin'){
    $u['output_user']='admin';
    $u['output_hash']=password_hash('admin',PASSWORD_DEFAULT);
    $u['output_encrypted']=output_encrypt('admin');
    $changed=true;
   }
  }
  unset($u);
  if($changed){$d['settings']['v6_output_preset_applied']=true;save($d);}
 }
}
function esc($s):string{return htmlspecialchars((string)$s,ENT_QUOTES|ENT_SUBSTITUTE,'UTF-8');}
// Keep dashboard sessions alive across browser restarts on shared hosting.
// Session cookie and server-side session retention are both extended to 30 days.
function start_dashboard_session():void {
 if(session_status()===PHP_SESSION_ACTIVE)return;
 $lifetime=30*24*60*60;
 ini_set('session.gc_maxlifetime',(string)$lifetime);
 ini_set('session.use_strict_mode','1');
 ini_set('session.use_only_cookies','1');
 $secure=!empty($_SERVER['HTTPS'])&&strtolower((string)$_SERVER['HTTPS'])!=='off';
 session_set_cookie_params(['lifetime'=>$lifetime,'path'=>'/','secure'=>$secure,'httponly'=>true,'samesite'=>'Lax']);
 session_start();
}
function auth():array {return $_SESSION['user']??[];}
function require_auth():void {if(!auth()){if((($_POST['ajax']??'')==='1')||(defined('MERGER_JSON_ENDPOINT')&&MERGER_JSON_ENDPOINT)){http_response_code(401);header('Content-Type: application/json; charset=utf-8');header('Cache-Control: no-store');echo json_encode(['ok'=>false,'error'=>'Session expired. Sign in again.']);exit;}header('Location: index.php');exit;}}
function csrf():string {return $_SESSION['csrf']??=bin2hex(random_bytes(24));}
function check_csrf():void {if(!hash_equals(csrf(),(string)($_POST['csrf']??'')))throw new Exception('Invalid form token');}
function wsid():string {$u=auth();return ($u['role']??'')==='admin'&&isset($_GET['ws'])&&preg_match('/^[a-zA-Z0-9_-]{1,40}$/',$_GET['ws'])?$_GET['ws']:($u['ws']??'master');}
function &workspace(array &$d,string $id):array {if(!isset($d['workspaces'][$id]))$d['workspaces'][$id]=['sources'=>[],'epg'=>[],'channels'=>[],'settings'=>[],'groups'=>[]];return $d['workspaces'][$id];}
function source_fetch(string $url,int $max=25000000):string {if(!preg_match('~^https?://~i',$url))throw new Exception('Only HTTP(S) URLs are allowed');$parts=parse_url($url);$host=$parts['host']??'';if(!$host||filter_var($host,FILTER_VALIDATE_IP)){if(!filter_var($host,FILTER_VALIDATE_IP,FILTER_FLAG_NO_PRIV_RANGE|FILTER_FLAG_NO_RES_RANGE))throw new Exception('Private/local addresses are blocked');}else{$ips=gethostbynamel($host);if(!$ips)throw new Exception('DNS lookup failed');foreach($ips as $ip)if(!filter_var($ip,FILTER_VALIDATE_IP,FILTER_FLAG_NO_PRIV_RANGE|FILTER_FLAG_NO_RES_RANGE))throw new Exception('Private/local addresses are blocked');}
 $ch=curl_init($url);$buf='';curl_setopt_array($ch,[CURLOPT_FOLLOWLOCATION=>false,CURLOPT_TIMEOUT=>40,CURLOPT_CONNECTTIMEOUT=>10,CURLOPT_USERAGENT=>'IPTV-Smart-Merger-Hostinger/0.1',CURLOPT_RETURNTRANSFER=>false,CURLOPT_WRITEFUNCTION=>function($c,$chunk)use(&$buf,$max){if(strlen($buf)+strlen($chunk)>$max)return 0;$buf.=$chunk;return strlen($chunk);},CURLOPT_PROTOCOLS=>CURLPROTO_HTTP|CURLPROTO_HTTPS]);$ok=curl_exec($ch);$status=curl_getinfo($ch,CURLINFO_RESPONSE_CODE);$err=curl_error($ch);curl_close($ch);if(!$ok||$status>=400||$status<200)throw new Exception('Download failed (HTTP '.$status.'): '.$err);return $buf;}
function parse_m3u(string $raw,array $source):array {$out=[];$info=null;$raw=preg_replace('/^\xEF\xBB\xBF/','',$raw);foreach(preg_split('/\r\n|\n|\r/',$raw) as $line){$line=trim($line);if(str_starts_with($line,'#EXTINF:')){$attrs=[];preg_match_all('/([\w-]+)\s*=\s*"([^"]*)"/',$line,$m,PREG_SET_ORDER);foreach($m as $v)$attrs[strtolower($v[1])]=$v[2];$comma=strpos($line,',');$name=$comma!==false?trim(substr($line,$comma+1)):($attrs['tvg-name']??'Unknown');$group=$attrs['group-title']??'Uncategorized';// Only apply strict language filtering to free presets, never to user-managed sources.
$filter=trim((string)($source['language_filter']??''));
if($filter!=='' && empty($source['trusted_language_feed'])){
 $langs=preg_split('/[,;\s]+/',strtolower((string)($attrs['tvg-language']??$attrs['language']??'')));
 $allowed=['en','eng','english','fr','fra','fre','french','français','francais'];
 if(trim((string)($attrs['tvg-language']??$attrs['language']??''))!=='' && !array_intersect($langs,$allowed)){$info=null;continue;}
}
$info=['name'=>$name?:'Unknown','group'=>($source['prefix']?trim($source['prefix']).' - ':'').$group,'tvg_id'=>$attrs['tvg-id']??'','logo'=>$attrs['tvg-logo']??'','source_id'=>$source['id'],'url'=>'','alternates'=>[]];}elseif($line!==''&&!str_starts_with($line,'#')&&$info){$info['url']=$line;$out[]=$info;$info=null;}}return $out;}
// Xtream source importer, adapted from the API flow in m3u-editor XtreamService.
// Import live channels only. Stream URLs remain provider-direct (no proxy).
function xt_source_base(string $raw):string {
 $raw=trim($raw);if(!preg_match('~^https?://~i',$raw))throw new Exception('Xtream server must start with http:// or https://');
 $parts=parse_url($raw);if(!$parts||empty($parts['host'])||isset($parts['user'])||isset($parts['pass']))throw new Exception('Invalid Xtream server URL');
 $path=preg_replace('~/(player_api\.php|get\.php)/?$~i','',rtrim($parts['path']??'','/'));
 return rtrim($parts['scheme'].'://'.$parts['host'].(isset($parts['port'])?':'.$parts['port']:'').$path,'/');
}
function xt_sync_epg_sources(array &$w):int {
 $added=0;$w['epg']??=[];
 $suppressed=array_fill_keys(array_map('strval',$w['settings']['suppressed_xtream_epg_sources']??[]),true);
 foreach($w['sources']??[] as $src){
  if(isset($suppressed[(string)($src['id']??'')])||($src['type']??'')!=='xtream'||empty($src['xt_user'])||empty($src['xt_pass_enc']))continue;
  $found=false;foreach($w['epg'] as $epg){if(($epg['xt_source_id']??'')===($src['id']??'')){$found=true;break;}}
  if($found)continue;
  try{$url=xt_source_base((string)$src['url']).'/xmltv.php?'.http_build_query(['username'=>(string)$src['xt_user'],'password'=>output_decrypt((string)$src['xt_pass_enc'])],'','&',PHP_QUERY_RFC3986);}catch(Throwable $e){continue;}
  foreach($w['epg'] as $epg){if(($epg['url']??'')===$url){$found=true;break;}}
  if($found)continue;
  $w['epg'][]=['id'=>bin2hex(random_bytes(5)),'name'=>(string)($src['name']??'Xtream').' · Xtream EPG','url'=>$url,'enabled'=>true,'xt_source_id'=>(string)$src['id'],'type'=>'xmltv'];$added++;
 }
 return $added;
}
/** Fetch an Xtream JSON response, following at most four same-host redirects.
 * Credentials are never forwarded to a different host. */
function xt_api_fetch_redirects(string $url,int $max=25000000):string {
 $origin=parse_url($url);$originHost=strtolower((string)($origin['host']??''));
 if($originHost==='')throw new Exception('Invalid Xtream URL');
 $seen=[];
 for($hop=0;$hop<=4;$hop++){
  if(isset($seen[$url]))throw new Exception('Xtream redirect loop');$seen[$url]=true;
  $parts=parse_url($url);$scheme=strtolower((string)($parts['scheme']??''));$host=strtolower((string)($parts['host']??''));
  if(!in_array($scheme,['http','https'],true)||$host!==$originHost||isset($parts['user'])||isset($parts['pass']))throw new Exception('Unsafe Xtream redirect destination');
  if(($origin['scheme']??'')==='https'&&$scheme!=='https')throw new Exception('Xtream HTTPS downgrade blocked');
  $ips=filter_var($host,FILTER_VALIDATE_IP)?[$host]:gethostbynamel($host);
  if(!$ips)throw new Exception('Xtream DNS lookup failed');
  foreach($ips as $ip)if(!filter_var($ip,FILTER_VALIDATE_IP,FILTER_FLAG_NO_PRIV_RANGE|FILTER_FLAG_NO_RES_RANGE))throw new Exception('Private/local Xtream address blocked');
  $body='';$location='';$port=(int)($parts['port']??($scheme==='https'?443:80));
  $ch=curl_init($url);
  curl_setopt_array($ch,[CURLOPT_FOLLOWLOCATION=>false,CURLOPT_TIMEOUT=>40,CURLOPT_CONNECTTIMEOUT=>10,CURLOPT_RETURNTRANSFER=>false,
   CURLOPT_USERAGENT=>'IPTV-Smart-Merger-Hostinger/0.1',CURLOPT_PROTOCOLS=>CURLPROTO_HTTP|CURLPROTO_HTTPS,
   CURLOPT_RESOLVE=>[$host.':'.$port.':'.$ips[0]],
   CURLOPT_HEADERFUNCTION=>function($c,$line)use(&$location){if(stripos($line,'Location:')===0)$location=trim(substr($line,9));return strlen($line);},
   CURLOPT_WRITEFUNCTION=>function($c,$chunk)use(&$body,$max){if(strlen($body)+strlen($chunk)>$max)return 0;$body.=$chunk;return strlen($chunk);}]);
  $ok=curl_exec($ch);$status=(int)curl_getinfo($ch,CURLINFO_RESPONSE_CODE);$err=curl_error($ch);curl_close($ch);
  if(!$ok)throw new Exception('Xtream request failed (HTTP '.$status.'): '.$err);
  if(in_array($status,[301,302,303,307,308],true)){
   if($hop===4)throw new Exception('Too many Xtream redirects');
   if($location==='')throw new Exception('Xtream redirect missing Location');
   if(preg_match('~^https?://~i',$location))$next=$location;
   elseif(str_starts_with($location,'//'))$next=$scheme.':'.$location;
   else{
    $path=(string)($parts['path']??'/');
    if(str_starts_with($location,'/'))$nextPath=$location;
    elseif(str_starts_with($location,'?'))$nextPath=$path.$location;
    else{$basePath=substr($path,0,strrpos($path,'/')+1);$nextPath=$basePath.$location;}
    $next=$scheme.'://'.$host.(isset($parts['port'])?':'.$port:'').$nextPath;
   }
   $url=$next;continue;
  }
  if($status<200||$status>=300)throw new Exception('Xtream request returned HTTP '.$status);
  return $body;
 }
 throw new Exception('Xtream redirect limit reached');
}
function xt_api(array $source,string $action='',array $extra=[]):array {
 $base=xt_source_base($source['url']);$user=(string)($source['xt_user']??'');
 $pass=output_decrypt((string)($source['xt_pass_enc']??''));
 if($user===''||$pass==='')throw new Exception('Xtream credentials missing');
 $params=array_merge(['username'=>$user,'password'=>$pass],$action!==''?['action'=>$action]:[],$extra);
 $url=$base.'/player_api.php?'.http_build_query($params,'','&',PHP_QUERY_RFC3986);
 $error='';
 for($attempt=0;$attempt<3;$attempt++){
  try{
   // Reuse the existing SSRF protection and bounded HTTP download.
   $raw=xt_api_fetch_redirects($url,25000000);
   $json=json_decode($raw,true,512,JSON_INVALID_UTF8_SUBSTITUTE);
   if(!is_array($json))throw new Exception('Provider returned invalid JSON');
   return $json;
  }catch(Throwable $e){$error=$e->getMessage();if($attempt<2)usleep(200000);}
 }
 throw new Exception('Xtream API request failed: '.$error);
}
function xtream_import(array $source):array {
 $auth=xt_api($source);
 if(!isset($auth['user_info'])||!in_array((string)($auth['user_info']['auth']??'0'),['1'],true))throw new Exception('Xtream authentication rejected');
 if(strtolower((string)($auth['user_info']['status']??'active'))!=='active')throw new Exception('Xtream account is not active');
 $categories=xt_api($source,'get_live_categories');
 if(!array_is_list($categories))throw new Exception('Invalid Xtream categories response');
 $names=[];foreach($categories as $category){if(!is_array($category))continue;$id=(string)($category['category_id']??'');if($id!=='')$names[$id]=(string)($category['category_name']??'Uncategorized');}
 // Some providers fail on the unfiltered get_live_streams call, so fetch per category.
 $groups=$names?:['0'=>'Uncategorized'];$out=[];$seen=[];$failures=0;
 foreach($groups as $categoryId=>$groupName){
  try{$rows=xt_api($source,'get_live_streams',['category_id'=>(string)$categoryId]);}
  catch(Throwable $e){$failures++;continue;}
  if(!array_is_list($rows)){$failures++;continue;}
  foreach($rows as $r){
   if(!is_array($r))continue;$id=(string)($r['stream_id']??'');
   if(!preg_match('/^[0-9]+$/',$id)||isset($seen[$id]))continue;
   $seen[$id]=true;$cat=(string)($r['category_id']??$categoryId);
   $group=$names[$cat]??$groupName;
   $extension=strtolower((string)($source['xt_format']??'ts'))==='m3u8'?'m3u8':'ts';
   $url=xt_source_base((string)$source['url']).'/live/'.rawurlencode((string)$source['xt_user']).'/'.rawurlencode(output_decrypt((string)$source['xt_pass_enc'])).'/'.$id.'.'.$extension;
   $out[]=['name'=>(string)($r['name']??'Channel '.$id),'group'=>(!empty($source['prefix'])?trim($source['prefix']).' - ':'').($group?:'Uncategorized'),
    'tvg_id'=>(string)($r['epg_channel_id']??''),'logo'=>(string)($r['stream_icon']??''),
    'source_id'=>$source['id'],'url'=>$url,'alternates'=>[]];
  }
 }
 if($failures)throw new Exception('Provider category requests failed ('.$failures.'); previous channels retained');
 if(!$out)throw new Exception('Xtream returned no live streams; previous channels retained');
 return $out;
}
// VOD and series are independent catalogs; live channel IDs are never changed.
function xt_media_import(array $source,string $kind):array {
 $catAction=$kind==='vod'?'get_vod_categories':'get_series_categories';
 $itemAction=$kind==='vod'?'get_vod_streams':'get_series';
 $cats=xt_api($source,$catAction);if(!array_is_list($cats))throw new Exception('Invalid '.$kind.' categories');
 $names=[];foreach($cats as $c)if(is_array($c)&&isset($c['category_id']))$names[(string)$c['category_id']]=(string)($c['category_name']??'Uncategorized');
 $out=[];$seen=[];$failed=0;
 foreach($names as $catId=>$name){
  try{$rows=xt_api($source,$itemAction,['category_id'=>$catId]);if(!array_is_list($rows))throw new Exception('Invalid catalog');}
  catch(Throwable $e){$failed++;continue;}
  foreach($rows as $r){if(!is_array($r))continue;$id=(string)($r[$kind==='vod'?'stream_id':'series_id']??'');if(!preg_match('/^[0-9]+$/',$id)||isset($seen[$id]))continue;$seen[$id]=true;
   $group=$names[(string)($r['category_id']??$catId)]??$name;
   $out[]=['provider_id'=>$id,'source_id'=>$source['id'],'name'=>(string)($r['name']??$kind.' '.$id),'group'=>(!empty($source['prefix'])?trim($source['prefix']).' - ':'').$group,
    'icon'=>(string)($r[$kind==='vod'?'stream_icon':'cover']??''),'container_extension'=>(string)($r['container_extension']??'mp4'),'rating'=>(string)($r['rating']??''),'plot'=>(string)($r['plot']??''),'added'=>(string)($r['added']??'0')];
  }
 }
 if($failed)throw new Exception($kind.' category requests failed: '.$failed);
 return $out;
}
function xt_media_catalog(array &$w,array $source):void {
 foreach(['vod','series'] as $kind){
  $key=$kind==='vod'?'movies':'series';
  try{$items=xt_media_import($source,$kind);
   $w[$key]=array_values(array_merge(array_filter($w[$key]??[],fn($r)=>($r['source_id']??'')!==$source['id']),$items));
   $w['settings']['media_'.$kind.'_status']='OK: '.count($items);
  }catch(Throwable $e){$w['settings']['media_'.$kind.'_status']='Error: '.$e->getMessage();}
 }
}
function xc_media_categories(array $w,string $key):array {
 $names=[];foreach($w[$key]??[] as $r)$names[main_category(output_source_group($w,$r))]=true;
 $out=[];foreach(array_keys($names) as $name)$out[]=['category_id'=>(string)(count($out)+1),'category_name'=>$name,'parent_id'=>0];return $out;
}
function xc_media_rows(array $w,string $key):array {
 $groups=[];foreach(xc_media_categories($w,$key) as $c)$groups[$c['category_name']]=$c['category_id'];
 $out=[];foreach($w[$key]??[] as $i=>$r){$id=$i+1;
  if($key==='movies')$out[]=['num'=>$id,'name'=>$r['name'],'stream_type'=>'movie','stream_id'=>$id,'stream_icon'=>$r['icon'],'rating'=>$r['rating'],'added'=>$r['added'],'category_id'=>$groups[main_category(output_source_group($w,$r))]??'1','container_extension'=>$r['container_extension'],'direct_source'=>''];
  else $out[]=['num'=>$id,'name'=>$r['name'],'series_id'=>$id,'cover'=>$r['icon'],'plot'=>$r['plot'],'rating'=>$r['rating'],'category_id'=>$groups[main_category(output_source_group($w,$r))]??'1','last_modified'=>$r['added']];
 }return $out;
}
function xc_media_source(array $w,array $row):?array {
 foreach($w['sources']??[] as $source)if(($source['id']??'')===($row['source_id']??null)&&($source['type']??'')==='xtream')return $source;
 return null;
}
function xc_media_url(array $w,string $key,int $id,?string $extension=null):?string {
 $row=$w[$key][$id-1]??null;if(!$row)return null;$src=xc_media_source($w,$row);if(!$src)return null;
 $pass=output_decrypt((string)($src['xt_pass_enc']??''));if(!$pass)return null;
 $ext=preg_replace('/[^a-zA-Z0-9]/','',(string)($extension??$row['container_extension']??'mp4'));
 return xt_source_base($src['url']).'/'.($key==='movies'?'movie':'series').'/'.rawurlencode($src['xt_user']).'/'.rawurlencode($pass).'/'.$row['provider_id'].($ext?'.'.$ext:'');
}
// Xtream players expect numeric episode IDs. Store a server-side lookup instead of
// exposing opaque alphanumeric IDs that some clients refuse to parse.
function xc_episode_map_path():string {return STORE.'/episode-id-map.json';}
function xc_episode_numeric_id(string $sourceId,string $providerId,string $ext):string {
 $hash=hash_hmac('sha256',$sourceId.':'.$providerId.':'.$ext,output_secret_key());
 return (string)(1 + (hexdec(substr($hash,0,8)) % 2147483646)); // signed 32-bit player-compatible ID
}
function xc_episode_map_store(array $records):void {
 if(!$records)return;
 $path=xc_episode_map_path();$fp=fopen($path.'.lock','c');if(!$fp)throw new RuntimeException('Episode mapping lock unavailable');
 try{
  if(!flock($fp,LOCK_EX))throw new RuntimeException('Episode mapping lock failed');
  $map=is_file($path)?json_decode((string)file_get_contents($path),true):[];
  if(!is_array($map))$map=[];
  foreach($records as $id=>$record)$map[(string)$id]=$record;
  $tmp=$path.'.'.bin2hex(random_bytes(5)).'.tmp';
  if(file_put_contents($tmp,json_encode($map,JSON_INVALID_UTF8_SUBSTITUTE|JSON_UNESCAPED_SLASHES),LOCK_EX)===false)throw new RuntimeException('Episode mapping save failed');
  rename($tmp,$path);
 }finally{flock($fp,LOCK_UN);fclose($fp);}
}
function xc_series_info(array $w,int $id):array {
 $row=$w['series'][$id-1]??null;if(!$row)return ['info'=>[],'seasons'=>[],'episodes'=>(object)[]];
 $src=xc_media_source($w,$row);if(!$src)return ['info'=>[],'seasons'=>[],'episodes'=>(object)[]];
 $info=xt_api($src,'get_series_info',['series_id'=>$row['provider_id']]);
 if(!isset($info['episodes'])||!is_array($info['episodes']))throw new RuntimeException('Provider series response contains no episodes');
 $episodes=[];$mapping=[];
 foreach($info['episodes'] as $season=>$items){
  if(!is_array($items))continue;
  foreach($items as $episode){
   if(!is_array($episode))continue;
   $providerId=(string)($episode['id']??'');if(!preg_match('/^[0-9]+$/',$providerId))continue;
   $ext=preg_replace('/[^a-zA-Z0-9]/','',(string)($episode['container_extension']??'mp4'));
   if($ext==='')$ext='mp4';
   $numericId=xc_episode_numeric_id((string)$src['id'],$providerId,$ext);
   // Resolve the rare 31-bit hash collision without overwriting an episode.
   while(isset($mapping[$numericId]) && ($mapping[$numericId]['provider_id']!==$providerId || $mapping[$numericId]['ext']!==$ext)){
    $numericId=(string)(1 + (((int)$numericId) % 2147483646));
   }
   $mapping[$numericId]=['source_id'=>(string)$src['id'],'provider_id'=>$providerId,'ext'=>$ext];
   $episode['id']=(int)$numericId;
   $episode['container_extension']=$ext;
   $episodes[(string)$season][]=$episode;
  }
 }
 xc_episode_map_store($mapping);
 $info['episodes']=$episodes?:new stdClass();
 if(!isset($info['info'])||!is_array($info['info']))$info['info']=[];
 if(!isset($info['seasons'])||!is_array($info['seasons']))$info['seasons']=[];
 return $info;
}
function xc_episode_url(array $w,string $id):?string {
 if(!preg_match('/^[0-9]+$/',$id))return null;
 $path=xc_episode_map_path();$map=is_file($path)?json_decode((string)file_get_contents($path),true):[];
 $record=is_array($map)?($map[$id]??null):null;if(!is_array($record))return null;
 $src=null;
 foreach($w['sources']??[] as $source){
  if(($source['id']??'')===($record['source_id']??'')&&($source['type']??'')==='xtream'&&!empty($source['enabled'])){$src=$source;break;}
 }
 if(!$src)return null;
 $pass=output_decrypt((string)($src['xt_pass_enc']??''));if($pass==='')return null;
 return xt_source_base($src['url']).'/series/'.rawurlencode((string)$src['xt_user']).'/'.rawurlencode($pass).'/'.$record['provider_id'].'.'.$record['ext'];
}
function merge_channels(array $all):array {$out=[];$keys=[];foreach($all as $item){$url=trim($item['url']);$id=strtolower(trim($item['tvg_id']));$name=preg_replace('/[^a-z0-9]/','',strtolower($item['name']));$key=$id&&$name?'id:'.$id.'|'.$name:'url:'.$url;if(isset($keys['url:'.$url]))$key=$keys['url:'.$url];if(isset($keys[$key])){$i=$keys[$key];if($out[$i]['url']!==$url&&!in_array($url,$out[$i]['alternates'],true))$out[$i]['alternates'][]=$url;}else{$i=count($out);$keys[$key]=$i;$out[]=$item;}$keys['url:'.$url]=$i;}return $out;}
function refresh(array &$w):array {$all=[];$results=[];usort($w['sources'],fn($a,$b)=>($a['priority']??1)<=>($b['priority']??1));foreach($w['sources'] as &$s){if(empty($s['enabled']))continue;try{$parsed=($s['type']??'m3u')==='xtream'?xtream_import($s):parse_m3u(source_fetch($s['url']),$s);$all=array_merge($all,$parsed);$s['status']='OK: '.count($parsed).' channels';$s['updated']=date('c');$results[]=$s['name'].': '.count($parsed);}catch(Throwable $e){$s['status']='Error: '.$e->getMessage();$results[]=$s['name'].': failed';foreach($w['channels'] as $oldChannel)if(($oldChannel['source_id']??'')===$s['id'])$all[]=$oldChannel;}}unset($s);if($all)$w['channels']=merge_channels($all);foreach($w['sources'] as $src)if(!empty($src['enabled'])&&($src['type']??'')==='xtream')xt_media_catalog($w,$src);$w['settings']['last_refresh']=date('c');return $results;}
// v21 resumable refresh. Staging is separate from published data.json.
function refresh_job_path():string{return STORE.'/refresh-job.json';}
function refresh_job_read():?array {$p=refresh_job_path();$v=is_file($p)?json_decode((string)file_get_contents($p),true):null;return is_array($v)?$v:null;}
function refresh_job_write(array $job):void {$p=refresh_job_path();$tmp=$p.'.tmp';if(file_put_contents($tmp,json_encode($job,JSON_INVALID_UTF8_SUBSTITUTE|JSON_UNESCAPED_SLASHES),LOCK_EX)===false)throw new Exception('Cannot save refresh job');rename($tmp,$p);}
// Cancellation marker is written without waiting for an in-flight network request.
function refresh_job_cancel_path(string $id):string{return STORE.'/refresh-cancel-'.preg_replace('/[^a-f0-9]/','',$id).'.flag';}
function refresh_job_cancelled(array $job):bool{return is_file(refresh_job_cancel_path((string)($job['id']??'')));}
function refresh_job_stop():array {
 $j=refresh_job_read();if(!$j||($j['state']??'')!=='running')return refresh_job_status($j);
 file_put_contents(refresh_job_cancel_path($j['id']),'stop',LOCK_EX);
 // Do not overwrite the job here: an active worker may be saving its previous step.
 return array_merge(refresh_job_status($j),['state'=>'stopping','stage'=>'Stop requested; waiting for current request']);
}
function refresh_job_start(array $w,string $mode='all',string $only=''):array {
 if(!in_array($mode,['all','live','movies','series'],true))throw new Exception('Invalid refresh mode');
 $old=refresh_job_read();if($old&&($old['state']??'')==='running'&&!refresh_job_cancelled($old))throw new Exception('Refresh already running');
 $sources=array_values(array_filter($w['sources']??[],fn($s)=>!empty($s['enabled'])&&($only===''||$s['id']===$only)));
 if(!$sources)throw new Exception('No enabled sources selected');
 $tasks=[];foreach($sources as $src){$types=($src['type']??'m3u')==='xtream'?($mode==='all'?['live','movies','series']:[$mode]):($mode==='all'||$mode==='live'?['m3u']:[]);foreach($types as $type)$tasks[]=['source'=>$src,'kind'=>$type];}
 if(!$tasks)throw new Exception('No matching sources');
 $job=['id'=>bin2hex(random_bytes(8)),'state'=>'running','started'=>time(),'updated'=>time(),'tasks'=>$tasks,'task_index'=>0,'stage'=>'Starting','category_index'=>0,'categories'=>null,'rows'=>[],'seen'=>[],'errors'=>[],'completed'=>0,'items'=>['live'=>0,'movies'=>0,'series'=>0],'last_error'=>'','current_category'=>'','current_source'=>'','current_kind'=>'','mode'=>$mode,'only'=>$only,'source_outcomes'=>[],'removed_sources'=>[],'live_changed'=>false];
 refresh_job_write($job);return $job;
}
function refresh_job_status(?array $j):array {
 if(!$j)return ['state'=>'idle','stage'=>'Not started','task'=>0,'tasks'=>0,'category'=>0,'categories'=>0,'items'=>['live'=>0,'movies'=>0,'series'=>0],'errors'=>[]];
 return ['state'=>$j['state'],'stage'=>$j['stage'],'task'=>min(count($j['tasks']),$j['task_index']+1),'tasks'=>count($j['tasks']),'category'=>$j['category_index'],'categories'=>count($j['categories']??[]),'items'=>$j['items'],'current_count'=>count($j['rows']??[]),'current_category'=>$j['current_category']??'','current_source'=>$j['current_source']??'','current_kind'=>$j['current_kind']??'','errors'=>array_slice($j['errors'],-3),'updated'=>$j['updated'],'removed_sources'=>$j['removed_sources']??[]];
}
function refresh_job_step(array &$w):array {
 $lock=fopen(STORE.'/refresh-job.lock','c');if(!$lock||!flock($lock,LOCK_EX|LOCK_NB))return refresh_job_status(refresh_job_read());
 try{
  $j=refresh_job_read();if(!$j||$j['state']!=='running')return refresh_job_status($j);
  if(refresh_job_cancelled($j)){$j['state']='stopped';$j['stage']='Stopped by user; previous published catalog retained';$j['updated']=time();refresh_job_write($j);return refresh_job_status($j);}
  if($j['task_index']>=count($j['tasks'])){$j['state']='done';$j['stage']='Complete';refresh_job_write($j);return refresh_job_status($j);}
  $task=$j['tasks'][$j['task_index']];$src=$task['source'];$kind=$task['kind'];$sid=$src['id'];$j['current_source']=(string)($src['name']??'Source');$j['current_kind']=$kind;
  try{
   if($kind==='m3u'){$rows=parse_m3u(source_fetch($src['url']),$src);if(!$rows)throw new Exception('Empty M3U playlist');$j['rows']=$rows;$j['categories']=[];$j['category_index']=0;}
   elseif($j['categories']===null){
    if($kind==='live'){
     $auth=xt_api($src);if((string)($auth['user_info']['auth']??'0')!=='1')throw new Exception('Xtream authentication failed');
    }
    $action=['live'=>'get_live_categories','movies'=>'get_vod_categories','series'=>'get_series_categories'][$kind];
    $categories=xt_api($src,$action);if(!array_is_list($categories))throw new Exception('Invalid categories');
    $j['categories']=array_values(array_filter($categories,fn($c)=>is_array($c)&&isset($c['category_id'])));
    $j['category_index']=0;$j['rows']=[];$j['seen']=[];$j['current_category']='';
    if(!$j['categories'])throw new Exception('No categories returned; keeping previous catalog');
   }else{
    $category=$j['categories'][$j['category_index']]??null;
    if($category){
     $catid=(string)$category['category_id'];$catname=(string)($category['category_name']??'Uncategorized');$j['current_category']=$catname;
     $action=['live'=>'get_live_streams','movies'=>'get_vod_streams','series'=>'get_series'][$kind];
     $records=xt_api($src,$action,['category_id'=>$catid]);if(!array_is_list($records))throw new Exception('Invalid category response');
     $prefix=trim((string)($src['prefix']??''));$group=($prefix!==''?$prefix.' - ':'').$catname;
     foreach($records as $r){if(!is_array($r))continue;$field=$kind==='series'?'series_id':'stream_id';$id=(string)($r[$field]??'');if(!preg_match('/^[0-9]+$/',$id)||isset($j['seen'][$id]))continue;$j['seen'][$id]=true;
      if($kind==='live'){
       $ext=($src['xt_format']??'ts')==='m3u8'?'m3u8':'ts';$url=xt_source_base($src['url']).'/live/'.rawurlencode($src['xt_user']).'/'.rawurlencode(output_decrypt($src['xt_pass_enc'])).'/'.$id.'.'.$ext;
       $j['rows'][]=['name'=>(string)($r['name']??'Channel '.$id),'group'=>$group,'tvg_id'=>(string)($r['epg_channel_id']??''),'logo'=>(string)($r['stream_icon']??''),'source_id'=>$sid,'url'=>$url,'alternates'=>[]];
      }else{
       $j['rows'][]=['provider_id'=>$id,'source_id'=>$sid,'name'=>(string)($r['name']??$kind.' '.$id),'group'=>$group,'icon'=>(string)($r[$kind==='movies'?'stream_icon':'cover']??''),'container_extension'=>(string)($r['container_extension']??'mp4'),'rating'=>(string)($r['rating']??''),'plot'=>(string)($r['plot']??''),'added'=>(string)($r['added']??'0')];
      }
     }
     $j['category_index']++;
    }
   }
   $done=$kind==='m3u'||($j['categories']!==null&&$j['category_index']>=count($j['categories']));
   if($done){
    if(refresh_job_cancelled($j))throw new Exception('Refresh stopped by user');
    if(!$j['rows'])throw new Exception('Empty result; previous catalog retained');
    $key=in_array($kind,['m3u','live'],true)?'channels':($kind==='movies'?'movies':'series');
    $others=array_values(array_filter($w[$key]??[],fn($row)=>($row['source_id']??'')!==$sid));
    $w[$key]=$key==='channels'?merge_channels(array_merge($others,$j['rows'])):array_merge($others,$j['rows']);
    $j['items'][$kind==='m3u'?'live':$kind]+=count($j['rows']);
    foreach($w['sources'] as &$existing)if($existing['id']===$sid){$existing['status']='OK: '.count($j['rows']).' '.($kind==='m3u'?'channels':$kind);$existing['results'][$kind]=['state'=>'ok','count'=>count($j['rows']),'at'=>date('c'),'message'=>'Imported successfully'];$existing['updated']=date('c');}unset($existing);
    if($key==='channels')$j['live_changed']=true;
    $j['source_outcomes'][$sid][$kind]='ok';
    $j['completed']++;$j['task_index']++;$j['categories']=null;$j['category_index']=0;$j['rows']=[];$j['seen']=[];$j['current_category']='';
    $w['settings']['last_refresh']=date('c');
   }
   $j['stage']=$done?'Published '.($src['name']??'source').' / '.$kind:($src['name']??'source').' · '.$kind.' · category '.$j['category_index'].'/'.count($j['categories']??[]).' · '.count($j['rows']).' found';
  }catch(Throwable $e){
   $j['last_error']=$e->getMessage();$j['errors'][]=($src['name']??'source').' / '.$kind.': '.$e->getMessage();
   // No partial publication on failure; previously published content stays intact.
   foreach($w['sources'] as &$existing)if($existing['id']===$sid){$existing['status']='Refresh error: '.$kind;$existing['results'][$kind]=['state'=>'error','count'=>null,'at'=>date('c'),'message'=>substr($e->getMessage(),0,250)];}unset($existing);
   $j['source_outcomes'][$sid][$kind]='error';
   $j['task_index']++;$j['categories']=null;$j['category_index']=0;$j['rows']=[];$j['seen']=[];$j['current_category']='';$j['stage']='Error; previous catalog retained';
  }
  if($j['task_index']>=count($j['tasks'])){
   $j['state']=count($j['errors'])?'done_with_errors':'done';$j['stage']='Refresh finished';
   // Only full, all-source runs qualify; one working M3U confirms connectivity.
   // Require failure on two consecutive qualifying runs to avoid transient deletion.
   if(($j['mode']??'')==='all'&&($j['only']??'')===''){
    $working=0;foreach($w['sources'] as $source){
     if(($source['type']??'m3u')==='m3u'&&($j['source_outcomes'][$source['id']]['m3u']??'')==='ok')$working++;
    }
    if($working>0){
     $removed=[];
     foreach($w['sources'] as &$source){
      if(($source['type']??'m3u')!=='m3u'||empty($source['enabled']))continue;
      $outcome=$j['source_outcomes'][$source['id']]['m3u']??'';
      if($outcome==='ok'){$source['consecutive_failed_refreshes']=0;continue;}
      if($outcome!=='error')continue;
      $source['consecutive_failed_refreshes']=(int)($source['consecutive_failed_refreshes']??0)+1;
      if($source['consecutive_failed_refreshes']>=2)$removed[]=['id'=>$source['id'],'name'=>$source['name'],'url'=>$source['url'],'reason'=>$source['results']['m3u']['message']??'Import failed'];
     }unset($source);
     if($removed){
      $pending=$w['settings']['pending_source_deletions']??[];
      $byId=[];foreach($pending as $entry)if(!empty($entry['id']))$byId[(string)$entry['id']]=$entry;
      foreach($removed as $entry){
       $entry['failures']=2;$entry['flagged_at']=date('c');
       $byId[(string)$entry['id']]=$entry;
      }
      $w['settings']['pending_source_deletions']=array_values($byId);
      $j['removed_sources']=[];
      $j['stage']='Refresh finished · '.count($removed).' source(s) awaiting deletion approval';
     }
    }else{$j['stage']='Refresh finished · no working M3U sources; auto-removal skipped (possible network outage)';}
   }
  }
  // After the last task, stage a fresh scrub from the complete current catalog.
  // The previous published snapshot remains available until the new scrub completes.
  if(in_array($j['state'],['done','done_with_errors'],true)&&!refresh_job_cancelled($j)
     &&!empty($j['live_changed'])&&!empty($w['settings']['scrub_auto'])){
   scrub_start($w);
   $j['stage'].=' · automatic scrub started';
  }
  if(refresh_job_cancelled($j)){$j['state']='stopped';$j['stage']='Stopped by user; previous published catalog retained';}
  $j['updated']=time();if((refresh_job_read()['id']??'')===$j['id'])refresh_job_write($j);return refresh_job_status($j);
 }finally{flock($lock,LOCK_UN);fclose($lock);}
}
function feed_auth(array $d):?string {$user=(string)($_GET['username']??'');$pass=(string)($_GET['password']??'');foreach($d['users'] as $u)if(($u['active']??true)&&hash_equals((string)($u['output_user']??''),$user)&&password_verify($pass,$u['output_hash']??''))return $u['ws'];return null;}
// Passthrough: no EPG download, merge, filtering or local XML parsing.
// Lightweight delivery telemetry. A redirect means handed to player, not confirmed downloaded.
function epg_delivery_event(string $kind,string $state,string $detail=''):void {
 try {
  if(!is_dir(STORE))mkdir(STORE,0700,true);
  $p=STORE.'/epg-delivery.json';$lock=fopen(STORE.'/epg-delivery.lock','c');if(!$lock)return;
  if(!flock($lock,LOCK_EX)){fclose($lock);return;}
  $d=is_file($p)?json_decode((string)file_get_contents($p),true):[];if(!is_array($d))$d=[];
  $d['last_kind']=$kind;$d['state']=$state;$d['last_at']=time();$d['detail']=substr($detail,0,160);
  $key=$kind.'_requests';$d[$key]=(int)($d[$key]??0)+1;
  $d['last_'.$kind.'_at']=time();
  if($kind==='xtream' && $state==='request_received')$d['last_xtream_request_at']=time();
  if($state==='listings_returned'){$d['last_programmes_at']=time();$d['last_programmes_count']=(int)$detail;}
  $tmp=$p.'.'.getmypid().'.tmp';file_put_contents($tmp,json_encode($d));rename($tmp,$p);
  flock($lock,LOCK_UN);fclose($lock);
 }catch(Throwable $ignored){}
}
function epg_delivery_status():array {
 $p=STORE.'/epg-delivery.json';$d=is_file($p)?json_decode((string)file_get_contents($p),true):[];
 return is_array($d)?$d:[];
}
function player_epg_url():string {
 return xc_base().'/xmltv.php?'.http_build_query(['username'=>(string)($_GET['username']??''),'password'=>(string)($_GET['password']??'')],'','&',PHP_QUERY_RFC3986);
}
function player_epg_source(array $w):?array {
 foreach($w['epg']??[] as $e)if(!empty($e['enabled'])&&!empty($e['xt_source_id']))return $e;
 foreach($w['epg']??[] as $e)if(!empty($e['enabled'])&&!empty($e['url']))return $e;
 return null;
}
function player_epg_listings(array $w,int $streamId,int $limit=100,string $action='get_short_epg'):array {
 $channel=$w['channels'][$streamId-1]??null;
 if(!$channel){epg_delivery_event('xtream_result','unknown_stream',(string)$streamId);return ['epg_listings'=>[]];}
 $src=null;foreach($w['sources']??[] as $s)if(($s['id']??'')===($channel['source_id']??'')&&($s['type']??'')==='xtream'){$src=$s;break;}
 if(!$src){epg_delivery_event('xtream','no_provider','M3U channel has no Xtream API');return ['epg_listings'=>[]];} // Public M3U feeds do not expose Xtream EPG APIs.
 $url=(string)($channel['url']??'');
 if(!preg_match('~/live/[^/]+/[^/]+/([0-9]+)(?:\.[a-z0-9]+)?(?:\?.*)?$~i',$url,$m)){epg_delivery_event('xtream_result','unmapped_stream',(string)$streamId);return ['epg_listings'=>[]];}
 $providerId=(int)$m[1];
 try {
  $result=xt_api($src,$action==='get_simple_data_table'?'get_simple_data_table':'get_short_epg',['stream_id'=>$providerId,'limit'=>max(1,min(500,$limit))]);
  if(isset($result['epg_listings'])&&is_array($result['epg_listings'])){epg_delivery_event('xtream',count($result['epg_listings'])?'listings_returned':'empty_response',(string)count($result['epg_listings']));return $result;}epg_delivery_event('xtream','empty_response');return ['epg_listings'=>[]];
 }catch(Throwable $e){epg_delivery_event('xtream','provider_error');return ['epg_listings'=>[]];}
}
function m3u(array $w):string {$s='#EXTM3U url-tvg="'.htmlspecialchars(player_epg_url(),ENT_QUOTES|ENT_XML1,'UTF-8').'" x-tvg-url="'.htmlspecialchars(player_epg_url(),ENT_QUOTES|ENT_XML1,'UTF-8').'"'."\n";foreach(output_channel_rows($w) as $item){$c=$item['channel'];$group=output_group_label($w,$c);$name=str_replace(["\r","\n",'"'],[' ',' ',''], output_channel_label($w,$c));$s.='#EXTINF:-1 tvg-id="'.str_replace('"','',$c['tvg_id']).'" tvg-logo="'.str_replace('"','',$c['logo']).'" group-title="'.str_replace('"','',$group).'",'.$name."\n".$c['url']."\n";}return $s;}
// v57: bounded-memory XMLTV processing. Each feed is downloaded to a temporary file,
// parsed with XMLReader, and filtered to the playlist's exact tvg-id values.
// No entire compressed or uncompressed guide is held in PHP memory.
function epg_safe_url(string $url):void {
 if(!preg_match('~^https?://~i',$url))throw new Exception('Only HTTP(S) EPG URLs allowed');
 $host=(string)(parse_url($url,PHP_URL_HOST)??'');
 if($host===''||str_contains($url,'@'))throw new Exception('Invalid EPG URL');
 $ips=filter_var($host,FILTER_VALIDATE_IP)?[$host]:gethostbynamel($host);
 if(!$ips)throw new Exception('EPG DNS resolution failed');
 foreach($ips as $ip)if(!filter_var($ip,FILTER_VALIDATE_IP,FILTER_FLAG_NO_PRIV_RANGE|FILTER_FLAG_NO_RES_RANGE))throw new Exception('Private EPG destinations blocked');
}
// Conditional GET is performed on the same download request, avoiding an extra HEAD call.
// A 304 is only accepted if a previously validated fragment is still present.
function epg_download_file(string $url,string $target,int $max=524288000,array $validators=[],bool $hasCache=false,bool $allow247Redirect=false):array {
 $origin=parse_url($url);$originHost=strtolower((string)($origin['host']??''));
 if($originHost==='')throw new Exception('Invalid EPG source URL');
 $seen=[];
 for($hop=0;$hop<=5;$hop++){
  epg_safe_url($url);
  if(isset($seen[$url]))throw new Exception('EPG redirect loop');$seen[$url]=true;
  $parts=parse_url($url);$scheme=strtolower((string)($parts['scheme']??''));
  // Permit public HTTPS EPG redirects, as older versions did, but do not
  // forward provider login credentials to a different host.
  $currentHost=strtolower((string)($parts['host']??''));
  if(strtolower((string)($origin['scheme']??''))==='https'&&$scheme!=='https')throw new Exception('EPG HTTPS downgrade blocked');
  if($currentHost!==$originHost){
   // Explicit opt-in: only 247 XMLTV from vod003.xyz to cleanshorts.today.
   // This may transmit Xtream credentials to the redirect destination.
   $trusted247Hop=$allow247Redirect && $originHost==='vod003.xyz' && $currentHost==='cleanshorts.today';
   if($scheme!=='https')throw new Exception('Cross-host EPG redirect must use HTTPS');
   $q=[];parse_str((string)($parts['query']??''),$q);
   if(!$trusted247Hop){
    foreach(['username','password','user','pass','token','auth','api_key','apikey'] as $key){
     foreach($q as $k=>$v)if(strtolower((string)$k)===$key)throw new Exception('EPG redirect carries credentials to another host; blocked');
    }
   }
   $originalQuery=[];parse_str((string)($origin['query']??''),$originalQuery);
   foreach(['username','password','user','pass'] as $key){
    $secret=(string)($originalQuery[$key]??'');
    if(!$trusted247Hop&&$secret!==''&&str_contains(rawurldecode($url),$secret))throw new Exception('EPG redirect contains provider credentials; blocked');
   }
  }
  $fp=fopen($target,'wb');if(!$fp)throw new Exception('EPG temporary file unavailable');
  $bytes=0;$headers=[];$responseHeaders=[];$location='';
  if($hasCache && $currentHost===$originHost){
   if(!empty($validators['etag'])&&preg_match('/^[\x20-\x7e]{1,200}$/D',(string)$validators['etag']))$headers[]='If-None-Match: '.$validators['etag'];
   if(!empty($validators['last_modified'])&&preg_match('/^[\x20-\x7e]{1,200}$/D',(string)$validators['last_modified']))$headers[]='If-Modified-Since: '.$validators['last_modified'];
  }
  $ch=curl_init($url);
  curl_setopt_array($ch,[CURLOPT_FOLLOWLOCATION=>false,CURLOPT_CONNECTTIMEOUT=>15,CURLOPT_TIMEOUT=>150,CURLOPT_USERAGENT=>'IPTV-Smart-Merger/73',CURLOPT_PROTOCOLS=>CURLPROTO_HTTP|CURLPROTO_HTTPS,CURLOPT_HTTPHEADER=>$headers,
   CURLOPT_HEADERFUNCTION=>static function($ch,$line)use(&$responseHeaders,&$location){
    if(preg_match('/^HTTP\//i',$line)){$responseHeaders=[];$location='';}
    elseif(preg_match('/^location:\s*(.+)\r?\n?$/i',$line,$m))$location=trim($m[1]);
    elseif(preg_match('/^(etag|last-modified):\s*(.+)\r?\n?$/i',$line,$m))$responseHeaders[strtolower($m[1])]=trim($m[2]);
    return strlen($line);
   },
   CURLOPT_WRITEFUNCTION=>static function($ch,$chunk)use($fp,&$bytes,$max){$n=strlen($chunk);$bytes+=$n;if($bytes>$max)return 0;return fwrite($fp,$chunk);}]);
  try{$ok=curl_exec($ch);$http=(int)curl_getinfo($ch,CURLINFO_RESPONSE_CODE);$err=curl_error($ch);}
  finally{curl_close($ch);fclose($fp);}
  if(in_array($http,[301,302,303,307,308],true)&&$ok){
   if($hop===5)throw new Exception('Too many EPG redirects');
   if($location==='')throw new Exception('EPG redirect missing Location');
   $next=scrub_redirect_url($url,$location);
   if($next===null)throw new Exception('Invalid EPG redirect Location');
   $url=$next;continue;
  }
  if($http===304&&$hasCache&&$ok)return ['unchanged'=>true,'headers'=>$responseHeaders];
  if(!$ok||$http<200||$http>=300)throw new Exception('EPG HTTP '.$http.($bytes>$max?' (download exceeds 500 MB)':'').' '.substr($err,0,120));
  if($bytes<16)throw new Exception('EPG response empty');
  return ['unchanged'=>false,'headers'=>$responseHeaders];
 }
 throw new Exception('EPG redirect limit reached');
}
function epg_prepare_xml(string $download,string $xml):void {
 $head=file_get_contents($download,false,null,0,2);if($head!=="\x1f\x8b"){if(!copy($download,$xml))throw new Exception('Cannot stage XMLTV');return;}
 $in=gzopen($download,'rb');$out=fopen($xml,'wb');if(!$in||!$out)throw new Exception('Cannot open compressed EPG');
 $size=0;$limit=2147483648;try{while(!gzeof($in)){$buf=gzread($in,65536);if($buf===false)throw new Exception('EPG gzip decode failed');$size+=strlen($buf);if($size>$limit)throw new Exception('Uncompressed guide exceeds 2 GB safety limit');if($buf!==''&&fwrite($out,$buf)!==strlen($buf))throw new Exception('EPG disk full');}}finally{gzclose($in);fclose($out);}
}
function epg_filter_source(array $source,array $ids,string $target):array {
 if(!class_exists('XMLReader')||!class_exists('XMLWriter'))throw new Exception('PHP XMLReader and XMLWriter extensions required');
 $base=STORE.'/epg-'.bin2hex(random_bytes(7));$download=$base.'.download';$xml=$base.'.xml';$temp=$target.'.tmp';$counts=['channels'=>0,'programmes'=>0];
 try{
  $cachePresent=is_file($target)&&filesize($target)>50;
  $downloadInfo=epg_download_file((string)$source['url'],$download,524288000,((string)($source['http_validators_url']??'')===(string)$source['url']?(array)($source['http_validators']??[]):[]),$cachePresent,
   (strtolower((string)(parse_url((string)$source['url'],PHP_URL_HOST)??''))==='vod003.xyz' && (strtolower((string)($source['type']??''))==='xtream' || stripos((string)($source['name']??''),'247')!==false)));
  if($downloadInfo['unchanged'])return ['channels'=>0,'programmes'=>0,'unchanged'=>true,'validators'=>(array)($source['http_validators']??[])];
  epg_prepare_xml($download,$xml);
  $reader=new XMLReader();if(!$reader->open($xml,null,LIBXML_NONET|LIBXML_NOERROR|LIBXML_NOWARNING))throw new Exception('Cannot parse XMLTV');
  $writer=new XMLWriter();if(!$writer->openURI($temp))throw new Exception('Cannot write EPG fragment');
  $writer->startDocument('1.0','UTF-8');$writer->startElement('tv');
  $root=false;
  while($reader->read()){
   if($reader->nodeType!==XMLReader::ELEMENT)continue;
   if(!$root){if($reader->name!=='tv')throw new Exception('Invalid XMLTV root');$root=true;continue;}
   if($reader->depth!==1||!in_array($reader->name,['channel','programme'],true))continue;
   $kind=$reader->name;$id=(string)$reader->getAttribute($kind==='programme'?'channel':'id');
   if($id!==''){
    $fragment=$reader->readOuterXML();if($fragment===false||$fragment==='')throw new Exception('Cannot read XMLTV element');if(!$writer->writeRaw($fragment))throw new Exception('Cannot write XMLTV element');$counts[$kind==='channel'?'channels':'programmes']++;
   }
  }
  $reader->close();if(!$root)throw new Exception('Missing XMLTV root');$writer->endElement();$writer->endDocument();$writer->flush();
  if($counts['programmes']===0)throw new Exception('EPG contains no programmes; previous source fragment retained');if(!rename($temp,$target))throw new Exception('Cannot publish EPG fragment');return $counts+['unchanged'=>false,'validators'=>['etag'=>(string)($downloadInfo['headers']['etag']??''),'last_modified'=>(string)($downloadInfo['headers']['last-modified']??'')]];
 }finally{foreach([$download,$xml,$temp] as $f)if(is_file($f))@unlink($f);}
}
function epg_fragment_path(string $id):string {return STORE.'/epg-cache-'.hash('sha256',$id).'.xml';}
function epg_output_path(string $ws):string {return STORE.'/epg-merged-'.hash('sha256',$ws).'.xml';}
// A guide is publishable only when the most recent completed build recorded real programmes.
// Older installations without a publication marker must rebuild rather than serve an unverified file.
function epg_published_guide(array $w,string $ws):?array {
 $meta=$w['settings']['epg_published']??null;
 if(!is_array($meta)||(int)($meta['programmes']??0)<1||(int)($meta['channels']??0)<1)return null;
 $path=epg_output_path($ws).'.gz';
 if(!is_file($path)||filesize($path)<1024)return null;
 if((int)($meta['bytes']??0)!==filesize($path))return null;
 $f=@fopen($path,'rb');if(!$f)return null;
 $magic=fread($f,2);fclose($f);
 return $magic==="\x1f\x8b"?$meta:null;
}


function epg_publish_fragments(array $w,string $ws="master"):array {
 $target=epg_output_path($ws);$tmp=$target.'.tmp';$out=fopen($tmp,'wb');if(!$out)throw new Exception('Cannot stage combined EPG');
 $seenChannels=[];$counts=['channels'=>0,'programmes'=>0];
 try{
  fwrite($out,"<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<tv generator-info-name=\"IPTV Smart Merger\">\n");
  foreach($w['epg']??[] as $source){if(empty($source['enabled']))continue;$file=epg_fragment_path((string)$source['id']);if(!is_file($file))continue;
   $r=new XMLReader();if(!$r->open($file,null,LIBXML_NONET|LIBXML_NOERROR|LIBXML_NOWARNING))continue;
   try{while($r->read()){
    if($r->nodeType!==XMLReader::ELEMENT||$r->depth!==1||!in_array($r->name,['channel','programme'],true))continue;
    $type=$r->name;$id=(string)$r->getAttribute($type==='channel'?'id':'channel');
    if($id==='')continue;
    if($type==='channel'){if(isset($seenChannels[$id]))continue;$seenChannels[$id]=true;}
    // Full XMLTV passthrough: retain all programmes, including unmatched channel IDs.
    $value=$r->readOuterXML();if($value===false||$value==='')throw new Exception('Cannot read merged XMLTV element');$written=fwrite($out,$value."\n");if($written===false||$written!==strlen($value)+1)throw new Exception('EPG disk full');$counts[$type==='channel'?'channels':'programmes']++;
   }}finally{$r->close();}
  }
  if($counts['programmes']===0)throw new Exception('No EPG programmes found; keeping previous combined guide');
  fwrite($out,"</tv>\n");fflush($out);
 }catch(Throwable $e){fclose($out);@unlink($tmp);throw $e;}
 fclose($out);
 // Compress the staged XML first. Existing published guide remains intact on failure.
 $gzTemp=$target.'.gz.tmp';$input=fopen($tmp,'rb');$gz=gzopen($gzTemp,'wb6');
 if(!$input||!$gz){if($input)fclose($input);if($gz)gzclose($gz);@unlink($gzTemp);throw new Exception('Cannot compress EPG');}
 try{while(!feof($input)){$buf=fread($input,65536);if($buf===false)throw new Exception('Cannot read merged EPG');if($buf!==''&&gzwrite($gz,$buf)===false)throw new Exception('Cannot compress EPG');}}finally{fclose($input);gzclose($gz);}
 if(!is_file($gzTemp)||filesize($gzTemp)<1024)throw new Exception('Compressed guide unexpectedly small; refusing to publish');
 if(!rename($gzTemp,$target.'.gz'))throw new Exception('Cannot publish compressed EPG');
 // Plain XML is used only for coverage calculation; gzip is the player-facing guide.
 if(!rename($tmp,$target))throw new Exception('Cannot publish plain XMLTV');return $counts;
}
// Diagnostic only: count exact playlist TVG-ID matches in the next 24 hours.
// This result NEVER filters, removes, or modifies the full XMLTV guide delivered to players.
// Stored at publish time: never rescan a large guide for every player request.
function epg_measure_coverage(array $w,string $ws):array {
 $ids=array_fill_keys(array_filter(array_map('strval',array_column($w['channels']??[],'tvg_id'))),true);
 $total=count($ids);$covered=[];$now=time();$until=$now+86400;
 $path=epg_output_path($ws);if(is_file($path)&&class_exists('XMLReader')){
  $r=new XMLReader();if($r->open($path,null,LIBXML_NONET|LIBXML_NOERROR|LIBXML_NOWARNING)){
   try{while($r->read()){
    if($r->nodeType!==XMLReader::ELEMENT||$r->name!=='programme'||$r->depth!==1)continue;
    $id=(string)$r->getAttribute('channel');if(!isset($ids[$id])||isset($covered[$id]))continue;
    $start=(string)$r->getAttribute('start');$stop=(string)$r->getAttribute('stop');
    $startTime=strtotime($start);$stopTime=strtotime($stop);
    if($startTime!==false&&$startTime<$until&&($stopTime!==false?$stopTime>$now:$startTime>=$now))$covered[$id]=true;
   }}finally{$r->close();}
  }
 }
 return ['covered'=>count($covered),'total'=>$total,'percent'=>$total?round(count($covered)*100/$total,1):0,'window_hours'=>24,'checked'=>time()];
}
function epg_refresh_reason(array $w,string $ws):string {
 $settings=$w['settings']??[];$last=(int)($settings['last_epg_refresh']??0);
 if(!epg_published_guide($w,$ws)||$last===0)return 'missing or invalid combined guide';
 $minutes=720; // v70 smart refresh interval: 12 hours
 if(time()-$last >= $minutes*60)return '12-hour age threshold';
 $coverage=$settings['epg_coverage']??[];
 if(!empty($coverage['checked'])&&(float)($coverage['percent']??100)<70 && time()-$last>=3600)return 'upcoming programme coverage below 70%';
 return '';
}
// Persistent EPG queue. Run one source per cron invocation; no browser session required.
function epg_queue_start(array &$w):array {
 $enabled=array_values(array_map(static fn($e)=>(string)$e['id'],array_filter($w['epg']??[],static fn($e)=>!empty($e['enabled']))));
 if(!$enabled)throw new Exception('No enabled EPG sources');
 $w['settings']['epg_job']=['state'=>'running','reason'=>(string)($w['settings']['epg_refresh_reason']??'manual refresh'),'ids'=>$enabled,'index'=>0,'ok'=>0,'failed'=>0,'started'=>time(),'updated'=>time()];
 return $w['settings']['epg_job'];
}
// Incremental publication: one cached XMLTV fragment per HTTP request.
function epg_publish_step(array &$w,array &$j,string $ws):void {
 $target=epg_output_path($ws);$tmp=$target.'.tmp';$gzTemp=$target.'.gz.tmp';
 $ids=$j['ids']??[];$phase=(int)($j['publish_index']??0);
 if(empty($j['publishing'])){
  foreach([$tmp,$gzTemp] as $file)if(is_file($file))@unlink($file);
  if(file_put_contents($tmp,"<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<tv generator-info-name=\"IPTV Smart Merger\">\n")===false)throw new Exception('Cannot stage EPG');
  $j['publishing']=true;$j['publish_index']=0;$j['publish_counts']=['channels'=>0,'programmes'=>0];$phase=0;
 }
 if($phase<count($ids)){
  $file=epg_fragment_path((string)$ids[$phase]);
  if(is_file($file)){
   $out=fopen($tmp,'ab');if(!$out)throw new Exception('Cannot append EPG');
   $r=new XMLReader();
   try{
    if(!$r->open($file,null,LIBXML_NONET|LIBXML_NOERROR|LIBXML_NOWARNING))throw new Exception('Cannot open cached EPG fragment');
    while($r->read()){
     if($r->nodeType!==XMLReader::ELEMENT||$r->depth!==1||!in_array($r->name,['channel','programme'],true))continue;
     $type=$r->name;$id=(string)$r->getAttribute($type==='channel'?'id':'channel');if($id==='')continue;
     $value=$r->readOuterXML();if($value===false||$value==='')throw new Exception('Cannot read EPG element');
     $line=$value."\n";if(fwrite($out,$line)!==strlen($line))throw new Exception('EPG storage write failed');
     $j['publish_counts'][$type==='channel'?'channels':'programmes']++;
    }
   }finally{$r->close();fclose($out);}
  }
  $j['publish_index']=$phase+1;$j['updated']=time();return;
 }
 if((int)($j['publish_counts']['programmes']??0)<1)throw new Exception('No EPG programmes found; existing guide retained');
 if(file_put_contents($tmp,"</tv>\n",FILE_APPEND)===false)throw new Exception('Cannot close EPG');
 // Compression is a separate request; never replace the old guide before success.
 $j['publish_index']=$phase+1;$j['updated']=time();
}
function epg_publish_compress(array &$w,array &$j,string $ws):void {
 $target=epg_output_path($ws);$tmp=$target.'.tmp';$gzTemp=$target.'.gz.tmp';
 $in=fopen($tmp,'rb');$out=gzopen($gzTemp,'wb6');if(!$in||!$out)throw new Exception('Cannot compress staged EPG');
 try{while(!feof($in)){$buf=fread($in,65536);if($buf===false)throw new Exception('Cannot read staged EPG');if($buf!==''&&gzwrite($out,$buf)===false)throw new Exception('EPG compression failed');}}finally{fclose($in);gzclose($out);}
 if(!is_file($gzTemp)||filesize($gzTemp)<1024)throw new Exception('Compressed guide too small');
 if(!rename($gzTemp,$target.'.gz'))throw new Exception('Cannot publish compressed guide');
 if(!rename($tmp,$target))throw new Exception('Cannot publish guide XML');
 $stats=$j['publish_counts'];$j['state']='completed';$j['counts']=$stats;
 $w['settings']['last_epg_refresh']=time();$w['settings']['epg_published']=['channels'=>$stats['channels'],'programmes'=>$stats['programmes'],'bytes'=>filesize($target.'.gz'),'published'=>time()];
 $w['settings']['epg_coverage']=['covered'=>0,'total'=>count($w['channels']??[]),'percent'=>0,'checked'=>0,'pending'=>true];
 $w['settings']['epg_refresh_due']=false;$w['settings']['epg_status']='Updated · '.$stats['programmes'].' programmes (coverage pending)';
}
function epg_queue_step(array &$w,string $ws="master"):array {
 $j=$w['settings']['epg_job']??[];
 if(($j['state']??'')!=='running')return $j;
 $ids=$j['ids']??[];$position=(int)($j['index']??0);
 if($position<count($ids)){
  $sourceId=$ids[$position];$idx=null;
  foreach($w['epg']??[] as $i=>$e)if((string)($e['id']??'')===$sourceId){$idx=$i;break;}
  if($idx!==null){$r=epg_refresh_one($w,$idx);if(!empty($r['ok']))$j['ok']++;elseif(empty($r['skipped']))$j['failed']++;}
  $j['index']=$position+1;$j['updated']=time();
 }else{
  try{if((int)($j['publish_index']??0)<=count($ids))epg_publish_step($w,$j,$ws);else epg_publish_compress($w,$j,$ws);}
  catch(Throwable $e){$j['state']='failed';$j['error']=preg_replace('~https?://[^\\s]+~','[URL hidden]',$e->getMessage());}
  $j['updated']=time();
 }
 return $w['settings']['epg_job']=$j;
}
function epg_refresh_one(array &$w,int $index):array {
 $ids=array_fill_keys(array_filter(array_column($w['channels']??[],'tvg_id')),true);
 if(!isset($w['epg'][$index])||empty($w['epg'][$index]['enabled']))return ['skipped'=>true];
 $e=&$w['epg'][$index];$pending=$w['settings']['pending_epg_deletions']??[];
 try{$counts=epg_filter_source($e,$ids,epg_fragment_path((string)$e['id']));$e['consecutive_failed_refreshes']=0;$e['http_validators']=$counts['validators']??[];$e['http_validators_url']=(string)$e['url'];$e['last_epg_check']=time();$e['status']=!empty($counts['unchanged'])?'Unchanged (HTTP 304) · '.gmdate('c'):'OK · '.gmdate('c').' · '.$counts['programmes'].' programmes parsed';$pending=array_values(array_filter($pending,fn($p)=>($p['id']??'')!==$e['id']));$result=['ok'=>true,'name'=>$e['name'],'counts'=>$counts];}
 catch(Throwable $ex){$n=(int)($e['consecutive_failed_refreshes']??0)+1;$e['consecutive_failed_refreshes']=$n;$reason=preg_replace('~https?://[^\s]+~','[URL hidden]',$ex->getMessage());$e['status']='Failed · '.gmdate('c').' · '.$reason;
  if($n>=2){$entry=['id'=>$e['id'],'name'=>$e['name'],'reason'=>$reason,'failures'=>$n];$pending=array_values(array_filter($pending,fn($p)=>($p['id']??'')!==$e['id']));$pending[]=$entry;}$result=['ok'=>false,'name'=>$e['name'],'error'=>$reason];}
 unset($e);$w['settings']['pending_epg_deletions']=$pending;return $result;
}
function xmlmerge(array $w):string {if(!class_exists('DOMDocument'))throw new Exception('PHP DOM extension required');$out=new DOMDocument('1.0','UTF-8');$root=$out->createElement('tv');$out->appendChild($root);$seen=[];$ids=array_fill_keys(array_filter(array_column($w['channels'],'tvg_id')),true);foreach($w['epg'] as $s){if(empty($s['enabled']))continue;try{$raw=source_fetch($s['url'],30000000);if(substr($raw,0,2)==="\x1f\x8b")$raw=gzdecode($raw);$in=new DOMDocument();$old=libxml_use_internal_errors(true);$ok=$in->loadXML($raw,LIBXML_NONET|LIBXML_NOERROR|LIBXML_NOWARNING);libxml_use_internal_errors($old);if(!$ok||$in->documentElement->nodeName!=='tv')continue;foreach($in->documentElement->childNodes as $node){if(!$node instanceof DOMElement)continue;$id=$node->getAttribute($node->tagName==='programme'?'channel':'id');if($ids&&!isset($ids[$id]))continue;if($node->tagName==='channel'){if(isset($seen[$id]))continue;$seen[$id]=true;}if(in_array($node->tagName,['channel','programme'],true))$root->appendChild($out->importNode($node,true));}}catch(Throwable $e){continue;}}return $out->saveXML();}

function xmlmerge_tracked(array &$w):string {
 // Track each enabled EPG feed only during explicit or scheduled EPG refreshes.
 // Player requests use the untracked reader and cannot increment failure counts.
 if(!class_exists('DOMDocument'))throw new Exception('PHP DOM extension required');
 $out=new DOMDocument('1.0','UTF-8');$root=$out->createElement('tv');$out->appendChild($root);
 $seen=[];$ids=array_fill_keys(array_filter(array_column($w['channels']??[],'tvg_id')),true);
 $pending=$w['settings']['pending_epg_deletions']??[];
 foreach($w['epg'] as &$e){if(empty($e['enabled']))continue;
  try{$raw=source_fetch($e['url'],30000000);if(substr($raw,0,2)==="\x1f\x8b"){$raw=gzdecode($raw);if($raw===false)throw new Exception('Invalid gzip XMLTV');}
   $in=new DOMDocument();$old=libxml_use_internal_errors(true);
   try{$ok=$in->loadXML($raw,LIBXML_NONET|LIBXML_NOERROR|LIBXML_NOWARNING);}finally{libxml_use_internal_errors($old);}
   if(!$ok||!$in->documentElement||$in->documentElement->nodeName!=='tv')throw new Exception('Invalid XMLTV document');
   $e['consecutive_failed_refreshes']=0;$e['status']='OK · '.gmdate('c');
   $pending=array_values(array_filter($pending,fn($p)=>($p['id']??'')!==$e['id']));
   foreach($in->documentElement->childNodes as $node){if(!$node instanceof DOMElement)continue;
    $id=$node->getAttribute($node->tagName==='programme'?'channel':'id');
    if($ids&&!isset($ids[$id]))continue;
    if($node->tagName==='channel'){if(isset($seen[$id]))continue;$seen[$id]=true;}
    if(in_array($node->tagName,['channel','programme'],true))$root->appendChild($out->importNode($node,true));}
  }catch(Throwable $ex){$n=(int)($e['consecutive_failed_refreshes']??0)+1;$e['consecutive_failed_refreshes']=$n;
   $reason=preg_replace('~https?://[^\s]+~','[URL hidden]',$ex->getMessage());$e['status']='Failed · '.gmdate('c').' · '.$reason;
   if($n>=2){$entry=['id'=>$e['id'],'name'=>$e['name'],'reason'=>$reason,'failures'=>$n];$found=false;
    foreach($pending as &$p)if(($p['id']??'')===$e['id']){$p=$entry;$found=true;break;}unset($p);
    if(!$found)$pending[]=$entry;}
  }
 }unset($e);$w['settings']['pending_epg_deletions']=$pending;
 return $out->saveXML();
}
function schedule_defaults(array &$w):void { $w['settings']['auto_refresh_daily']??=true; $w['settings']['player_refresh_12h']??=true; $w['settings']['m3u_minutes']??=1440; $w['settings']['epg_minutes']??=720; $w['settings']['scrub_auto']??=true; $w['settings']['scrub_batch']??=10; $w['settings']['scrub']??=['state'=>'idle','index'=>0,'checked'=>0,'total'=>0,'started'=>0,'errors'=>0]; }
// Scrub staging is separate from data.json so IPTV feeds never read in-progress results.
function scrub_file():string{return STORE.'/scrub-staging.json';}
function scrub_snapshot(array $w):array {return array_map(fn($c)=>['url'=>(string)($c['url']??''),'alternates'=>$c['alternates']??[]],$w['channels']);}
function scrub_start(array &$w):void {schedule_defaults($w);$channels=scrub_snapshot($w);$job=['fingerprint'=>hash('sha256',json_encode($channels)),'channels'=>$channels,'catalog'=>$w['channels'],'results'=>[],'index'=>0];file_put_contents(scrub_file().'.tmp',json_encode($job),LOCK_EX);rename(scrub_file().'.tmp',scrub_file());$w['settings']['scrub']=['state'=>'running','index'=>0,'checked'=>0,'total'=>count($channels),'started'=>time(),'errors'=>0];}
// A HEAD-only reachability check. Never interpret a failed HEAD as proof of a dead stream.
// Each redirect hop is validated before requesting it; private/reserved hosts are blocked.
function scrub_safe_host(string $url):bool {
 if(!preg_match('~^https?://~i',$url))return false;
 $host=parse_url($url,PHP_URL_HOST);
 if(!$host||parse_url($url,PHP_URL_USER)!==null||parse_url($url,PHP_URL_PASS)!==null)return false;
 $ips=filter_var($host,FILTER_VALIDATE_IP)?[$host]:gethostbynamel($host);
 if(!$ips)return false;
 foreach($ips as $ip)if(!filter_var($ip,FILTER_VALIDATE_IP,FILTER_FLAG_NO_PRIV_RANGE|FILTER_FLAG_NO_RES_RANGE))return false;
 return true;
}
function scrub_redirect_url(string $base,string $location):?string {
 $location=trim($location);
 if($location===''||preg_match('/[\x00-\x1f\x7f]/',$location))return null;
 if(preg_match('~^https?://~i',$location))return $location;
 if(str_starts_with($location,'//'))return parse_url($base,PHP_URL_SCHEME).':'.$location;
 if(preg_match('~^[a-z][a-z0-9+.-]*:~i',$location))return null;
 $parts=parse_url($base);if(!$parts||empty($parts['host']))return null;
 $origin=$parts['scheme'].'://'.$parts['host'].(isset($parts['port'])?':'.$parts['port']:'');
 if(str_starts_with($location,'/'))return $origin.$location;
 $path=$parts['path']??'/';$dir=substr($path,0,strrpos($path,'/')+1);
 return $origin.$dir.$location;
}
function scrub_probe_batch(array $urls):array {
 $states=[];$results=[];
 foreach($urls as $key=>$url){$states[$key]=['url'=>$url,'hops'=>0,'seen'=>[]];$results[$key]='unknown';}
 for($round=0;$round<5&&$states;$round++){
  $multi=curl_multi_init();$handles=[];$next=[];
  foreach($states as $key=>$st){
   $url=$st['url'];
   if(!scrub_safe_host($url)||isset($st['seen'][$url])){$results[$key]='unknown';continue;}
   $st['seen'][$url]=true;
   $ch=curl_init($url);$location='';
   curl_setopt_array($ch,[CURLOPT_NOBODY=>true,CURLOPT_RETURNTRANSFER=>true,
    CURLOPT_FOLLOWLOCATION=>false,CURLOPT_TIMEOUT=>4,CURLOPT_CONNECTTIMEOUT=>2,
    CURLOPT_PROTOCOLS=>CURLPROTO_HTTP|CURLPROTO_HTTPS,
    CURLOPT_HEADERFUNCTION=>static function($ch,$header)use(&$location){if(strncasecmp($header,'Location:',9)===0)$location=trim(substr($header,9));return strlen($header);}
   ]);
   curl_multi_add_handle($multi,$ch);$handles[$key]=[$ch,$st,&$location];
  }
  do{$status=curl_multi_exec($multi,$running);if($running)curl_multi_select($multi,0.25);}while($running&&$status===CURLM_OK);
  foreach($handles as $key=>$entry){
   [$ch,$st]=$entry;$location=$entry[2];$code=(int)curl_getinfo($ch,CURLINFO_RESPONSE_CODE);
   if($code>=200&&$code<300)$results[$key]='ok';
   elseif($code>=300&&$code<400&&$location!==''){
    $target=scrub_redirect_url($st['url'],$location);
    if($target!==null&&$round<4){$st['url']=$target;$st['hops']++;$next[$key]=$st;}
    else $results[$key]='unknown';
   }elseif($code===404||$code===410)$results[$key]='missing';
   else $results[$key]='unknown';
   curl_multi_remove_handle($multi,$ch);curl_close($ch);
  }
  curl_multi_close($multi);$states=$next;
 }
 return $results;
}
function scrub_probe(array $urls):string {
 foreach($urls as $url){if(!is_string($url))continue;$result=scrub_probe_batch([0=>$url]);if(($result[0]??'unknown')==='ok')return 'ok';}
 return 'unknown';
}
function scrub_step(array &$w):void {
 schedule_defaults($w);$state=&$w['settings']['scrub'];if($state['state']!=='running')return;
 $lock=fopen(STORE.'/scrub.lock','c');if(!$lock||!flock($lock,LOCK_EX|LOCK_NB))return;
 try{
  $path=scrub_file();$job=is_file($path)?json_decode(file_get_contents($path),true):null;
  if(!is_array($job)||!isset($job['channels'])){$state['state']='stopped';return;}
  if(hash('sha256',json_encode(scrub_snapshot($w)))!==$job['fingerprint']){$state['state']='stopped';return;}
  $total=count($job['channels']);$batch=max(1,min(50,(int)$w['settings']['scrub_batch']));
  $start=(int)$job['index'];$end=min($total,$start+$batch);
  for($offset=$start;$offset<$end;$offset+=10){
   $urls=[];for($i=$offset;$i<min($end,$offset+10);$i++)$urls[$i]=(string)($job['channels'][$i]['url']??'');
   foreach(scrub_probe_batch($urls) as $i=>$result)$job['results'][$i]=$result;
  }
  $job['index']=$end;$state['index']=$end;$state['checked']=$end;$state['total']=$total;
  $state['errors']=count(array_filter($job['results'],fn($v)=>$v!=='ok'));
  if($end>=$total){
   $state['state']='done';$w['settings']['scrub_last_completed']=time();
   $w['settings']['scrub_last_results']=$job['results'];
   // Atomic publication: only a fully completed scrub may change player-facing Live TV.
   // HEAD failures and uncertain responses are excluded; VOD/series are never scrubbed.
   if(!isset($job['catalog'])||count($job['catalog'])!==$total){$state['state']='stopped';return;}
   $verified=[];foreach($job['catalog'] as $i=>$channel){if(($job['results'][$i]??'')==='ok')$verified[]=$channel;}
   $w['published_channels']=$verified;
   $w['settings']['scrub_published_count']=count($verified);
   $w['settings']['scrub_last_published']=time();
  }
  file_put_contents($path.'.tmp',json_encode($job),LOCK_EX);rename($path.'.tmp',$path);
 }finally{flock($lock,LOCK_UN);fclose($lock);}
}
function scheduled_run(array &$w):array {schedule_defaults($w);$now=time();$result=[];$last=strtotime($w['settings']['last_refresh']??'')?:0;if($now-$last>=max(5,(int)$w['settings']['m3u_minutes'])*60){if(!refresh_job_read()||!in_array((refresh_job_read()['state']??''),['running'],true)){try{refresh_job_start($w);}catch(Throwable $e){$result=['Refresh start failed: '.$e->getMessage()];}}} $lastE=(int)($w['settings']['last_epg_refresh']??0);if($now-$lastE>=max(5,(int)$w['settings']['epg_minutes'])*60){$w['settings']['epg_refresh_due']=true;$w['settings']['epg_status']='Refresh due · next XMLTV player request can advance the EPG build';}scrub_step($w);return $result;}


// Before the first completed scrub, serve the fully imported source catalog.
// Once a scrub has completed, always serve its last published snapshot,
// including an intentionally empty verified result. Never expose scrub staging.
function player_live_channels(array $w):array {
 $published=array_key_exists('published_channels',$w)?$w['published_channels']:($w['channels']??[]);
 // Never serve orphaned rows from removed sources, or outdated rows from
 // a source that has successfully been reimported. Retain last verified rows
 // for sources whose latest refresh failed, until their next successful import.
 $sources=[];foreach($w['sources']??[] as $src)$sources[(string)$src['id']]=$src;
 $current=[];foreach($w['channels']??[] as $row){
  $sid=(string)($row['source_id']??'');$url=(string)($row['url']??'');
  $current[$sid."\0".$url]=true;
 }
 return array_values(array_filter($published,static function($row)use($sources,$current){
  $sid=(string)($row['source_id']??'');
  if(!isset($sources[$sid])||empty($sources[$sid]['enabled']))return false;
  return isset($current[$sid."\0".(string)($row['url']??'')]);
 }));
}

// Xtream Codes-compatible live TV output. Stream IDs are 1-based catalog positions.
function output_workspace():array {
 $d=data(); $ws=feed_auth($d);
 if($ws===null){http_response_code(401);throw new RuntimeException('Invalid output credentials');}
 $w=$d['workspaces'][$ws]??['channels'=>[],'groups'=>[],'epg'=>[]];
 // Fallback to imported channels only until the first completed scrub.
 $w['channels']=player_live_channels($w);
 return $w;
}
// Collapse semicolon-separated tag combinations into their first/main category.
// Normalize output only: original channel records and scrub cache stay untouched.
function main_category(string $group):string {
 $group=trim($group);
 if(str_contains($group,';'))$group=trim(explode(';',$group,2)[0]);
 return $group!==''?$group:'Uncategorized';
}
// Derive the displayed group from the current source setting, not the old imported label.
// Handles imported rows AND the last completed scrub snapshot without changing either.
function output_source_group(array $w,array $row):string {
 $group=trim((string)($row['group']??'Uncategorized'));
 $sid=(string)($row['source_id']??'');
 foreach($w['sources']??[] as $source){
  if((string)($source['id']??'')!==$sid)continue;
  $prefix=trim((string)($source['prefix']??''));
  $old=array_values(array_filter(array_map('trim',(array)($source['prefix_history']??[])),fn($v)=>$v!==''));
  if($prefix!=='')$old[]=$prefix;
  // Backward compatibility for sources edited before this fix: their imported
  // groups can still contain FRENCH/ENGLISH even though source now says FR/EN.
  if(strcasecmp($prefix,'FR')===0)$old=array_merge($old,['FRENCH','FRANÇAIS','FRANCAIS']);
  if(strcasecmp($prefix,'EN')===0)$old=array_merge($old,['ENGLISH','ANGLAIS']);
  usort($old,fn($a,$b)=>strlen($b)<=>strlen($a));
  foreach($old as $candidate){
   $start=$candidate.' - ';
   if(strncasecmp($group,$start,strlen($start))===0){$group=trim(substr($group,strlen($start)));break;}
  }
  return ($prefix!==''?$prefix.' - ':'').($group!==''?$group:'Uncategorized');
 }
 return $group;
}
// Display-only normalization: cache, source prefixes, URLs and EPG IDs are untouched.
function output_normalize_label(string $label,string $style):string {
 $label=trim(preg_replace('/\s+/u',' ',$label)??$label);
 if($style==='off')return $label;
 // Split on explicit separators, preserving a two-part structure where present.
 $parts=preg_split('/\s*[|–—-]\s*/u',$label);
 if(!is_array($parts))$parts=[$label];
 $parts=array_values(array_filter(array_map('trim',$parts),static fn($p)=>$p!==''));
 if(!$parts)return $label;
 $case=str_starts_with($style,'upper_')?'upper':'title'; // Sentence formats use capitalized words as requested
 foreach($parts as &$part){
  if($case==='upper')$part=function_exists('mb_strtoupper')?mb_strtoupper($part,'UTF-8'):strtoupper($part);
  elseif($case==='lower')$part=function_exists('mb_strtolower')?mb_strtolower($part,'UTF-8'):strtolower($part);
  else $part=function_exists('mb_convert_case')?mb_convert_case($part,MB_CASE_TITLE,'UTF-8'):ucwords(strtolower($part));
 }unset($part);
 $separator=str_ends_with($style,'_dash')?' - ':(str_ends_with($style,'_pipe')?' | ':' ');
 return implode($separator,$parts);
}
function output_normalization_style(array $w,string $kind):string {
 $style=(string)($w['settings']['normalize_'.$kind.'_style']??(!empty($w['settings']['normalize_'.$kind])?'title_space':'off'));
 if(str_starts_with($style,'lower_'))$style='sentence_'.substr($style,6); // migrate old lower-case selection
 return in_array($style,['off','title_space','upper_space','sentence_space','title_dash','upper_dash','sentence_dash','title_pipe','upper_pipe','sentence_pipe'],true)?$style:'off';
}
function output_group_label(array $w,array $c):string {
 return output_normalize_label(xc_group($w,$c),output_normalization_style($w,'groups'));
}
function output_channel_label(array $w,array $c):string {
 return output_normalize_label((string)($c['name']??'Unknown'),output_normalization_style($w,'channels'));
}
// Sorting only changes presentation order; original stream IDs remain stable.
function output_channel_rows(array $w):array {
 $rows=[];foreach($w['channels']??[] as $i=>$c)$rows[]=['index'=>$i,'channel'=>$c];
 $gs=($w['settings']['sort_groups']??'default')==='az';
 $cs=($w['settings']['sort_channels']??'default')==='az';
 if($gs||$cs)usort($rows,static function($a,$b)use($w,$gs,$cs){
  if($gs){$cmp=strnatcasecmp(output_group_label($w,$a['channel']),output_group_label($w,$b['channel']));if($cmp)return $cmp;}
  if($cs){$cmp=strnatcasecmp(output_channel_label($w,$a['channel']),output_channel_label($w,$b['channel']));if($cmp)return $cmp;}
  return $a['index']<=>$b['index'];
 });
 return $rows;
}
function xc_group(array $w,array $c):string {
 $g=output_source_group($w,$c);$seen=[];
 while(isset($w['groups'][$g])&&!isset($seen[$g])){$seen[$g]=true;$g=(string)$w['groups'][$g];}
 $g=main_category($g);
 // Existing group renames targeting the normalized category still apply.
 $seen=[];while(isset($w['groups'][$g])&&!isset($seen[$g])){$seen[$g]=true;$g=main_category((string)$w['groups'][$g]);}
 return $g;
}
function xc_categories(array $w):array {
 $out=[];$seen=[];
 foreach(output_channel_rows($w) as $item){$g=output_group_label($w,$item['channel']);if(!isset($seen[$g])){$seen[$g]=true;$out[]=$g;}}
 if(($w['settings']['sort_groups']??'default')==='az')usort($out,'strnatcasecmp');
 return array_map(static fn($g,$i)=>['category_id'=>(string)($i+1),'category_name'=>$g,'parent_id'=>0],$out,array_keys($out));
}
function xc_streams(array $w):array {
 $categories=[];foreach(xc_categories($w) as $cat)$categories[$cat['category_name']]=$cat['category_id'];
 $out=[];
 foreach(output_channel_rows($w) as $item){$i=$item['index'];$c=$item['channel'];$group=output_group_label($w,$c);$out[]=['num'=>$i+1,'name'=>output_channel_label($w,$c),'stream_type'=>'live','stream_id'=>$i+1,'stream_icon'=>(string)($c['logo']??''),'epg_channel_id'=>(string)($c['tvg_id']??''),'added'=>'0','category_id'=>(string)($categories[$group]??1),'custom_sid'=>'','tv_archive'=>0,'direct_source'=>xc_base().'/stream/'.($i+1).'?'.http_build_query(['username'=>(string)($_GET['username']??''),'password'=>(string)($_GET['password']??'')]),'tv_archive_duration'=>0,'is_adult'=>0,'container_extension'=>'ts'];}
 return $out;
}
function xc_base():string {
 $https=(!empty($_SERVER['HTTPS'])&&$_SERVER['HTTPS']!=='off')||($_SERVER['HTTP_X_FORWARDED_PROTO']??'')==='https';
 $host=$_SERVER['HTTP_HOST']??'localhost';$host=preg_replace('/[^a-zA-Z0-9.:-]/','',$host);
 $folder=rtrim(str_replace('\\','/',dirname($_SERVER['SCRIPT_NAME']??'/')), '/');
 return ($https?'https':'http').'://'.$host.($folder==='.'?'':$folder);
}
function xc_response($payload):void {header('Content-Type: application/json; charset=utf-8');header('Cache-Control: no-store');echo json_encode($payload,JSON_INVALID_UTF8_SUBSTITUTE|JSON_UNESCAPED_SLASHES);}
function xc_auth_details(array $w):array {
 $username=(string)($_GET['username']??'');$password=(string)($_GET['password']??'');
 $base=xc_base();$parts=parse_url($base);$scheme=$parts['scheme']??'https';$host=$parts['host']??'localhost';$port=$parts['port']??($scheme==='https'?443:80);
 return ['user_info'=>['username'=>$username,'password'=>$password,'message'=>'IPTV Smart Merger','auth'=>1,'status'=>'Active','exp_date'=>(string)(time()+315360000),'is_trial'=>'0','active_cons'=>'0','created_at'=>'0','max_connections'=>'1','allowed_output_formats'=>['m3u8','ts']],'server_info'=>['url'=>$base,'port'=>(string)$port,'https_port'=>'443','server_protocol'=>$scheme,'rtmp_port'=>'0','timezone'=>'America/Toronto','timestamp_now'=>time(),'time_now'=>date('Y-m-d H:i:s')]];
}
function xc_stream_url(array $w,int $id):?string {
 if($id<1||!isset($w['channels'][$id-1]))return null;
 $url=(string)($w['channels'][$id-1]['url']??'');
 return preg_match('~^https?://~i',$url)?$url:null;
}

// Only inspect a trusted publisher's public M3U directory. Discovery never activates sources.
function discovery_candidates():array {
 $url='https://api.github.com/repos/BuddyChewChew/app-m3u-generator/contents/playlists';
 $raw=source_fetch($url,1200000);$rows=json_decode($raw,true);
 if(!is_array($rows)||!array_is_list($rows))throw new Exception('Discovery catalog unavailable');
 $out=[];
 foreach($rows as $item){
  $name=(string)($item['name']??'');$download=(string)($item['download_url']??'');
  if(($item['type']??'')!=='file'||!preg_match('/\.m3u8?$/i',$name))continue;
  // Country-scoped English/French candidates; global multilingual feeds are excluded.
  if(!preg_match('/(?:_|-)(ca|us|gb|uk|fr)(?:\.m3u8?)$/i',$name))continue;
  if(!str_starts_with($download,'https://raw.githubusercontent.com/BuddyChewChew/app-m3u-generator/'))continue;
  $out[]=['name'=>preg_replace('/\.m3u8?$/i','',$name),'url'=>$download];
 }
 return array_slice($out,0,120);
}
function discovery_run(array &$w):array {
 $now=time();$w['settings']['discovery_last']=$now;
 $known=[];foreach($w['sources'] as $s)$known[rtrim((string)$s['url'],'/')]=true;
 foreach($w['pending_sources']??[] as $s)$known[rtrim((string)$s['url'],'/')]=true;
 foreach($w['rejected_sources']??[] as $u)$known[rtrim((string)$u,'/')]=true;
 $found=0;
 try{
  foreach(discovery_candidates() as $candidate){
   $url=$candidate['url'];if(isset($known[rtrim($url,'/')]))continue;
   // Validate at most two new feeds per pass to avoid long PHP requests.
   if($found>=2)break;
   try{$raw=source_fetch($url,5000000);if(!str_contains(substr($raw,0,500),'#EXTM3U')||!str_contains($raw,'#EXTINF:'))continue;
    $count=substr_count($raw,'#EXTINF:');if($count<1)continue;
    $w['pending_sources'][]=['id'=>bin2hex(random_bytes(6)),'name'=>$candidate['name'],'url'=>$url,'channels'=>$count,'language'=>'English/French region (not verified per channel)','checked_at'=>date('c')];$known[rtrim($url,'/')]=true;$found++;
   }catch(Throwable $ignored){}
  }
  $w['settings']['discovery_status']='Checked catalog; '.$found.' new feeds pending';
 }catch(Throwable $e){$w['settings']['discovery_status']='Discovery error: '.$e->getMessage();}
 return ['found'=>$found,'status'=>$w['settings']['discovery_status']];
}

// Queue EPG work after the player response, never before channel/API output.
function player_epg_register(string $ws):void { /* EPG builds run in dashboard batches, never inside player requests. */ }

// Player-driven, request-bounded refresh. Never perform provider downloads before the
// playlist/API response is delivered. With no cron, each later player request advances
// at most one step; no promise of progress when requests stop.
function player_refresh_register(string $ws):void {
 if($ws===''||!preg_match('/^[a-zA-Z0-9_-]{1,40}$/',$ws))return;
 register_shutdown_function(static function()use($ws):void {
  try {
   if(function_exists('fastcgi_finish_request'))@fastcgi_finish_request();
   else {if(ob_get_level()>0)@ob_flush();@flush();}
   @ignore_user_abort(true);
   @set_time_limit(50);
   $lock=fopen(STORE.'/player-refresh.lock','c');
   if(!$lock||!flock($lock,LOCK_EX|LOCK_NB))return;
   try {
    $now=time();$stamp=STORE.'/player-refresh-step.txt';
    $last=(int)(is_file($stamp)?file_get_contents($stamp):0);
    if($now-$last<20)return;
    file_put_contents($stamp,(string)$now,LOCK_EX);
    $d=data();if(!isset($d['workspaces'][$ws]))return;
    $w=&$d['workspaces'][$ws];schedule_defaults($w);
    if(empty($w['settings']['player_refresh_12h']))return;
    $j=refresh_job_read();
    if(!$j||($j['state']??'')!=='running'){
     $completed=strtotime((string)($w['settings']['last_refresh']??''))?:0;
     $attempt=(int)($w['settings']['player_refresh_last_attempt']??0);
     if($now-max($completed,$attempt)<43200)return;
     $w['settings']['player_refresh_last_attempt']=$now;
     save($d);
     try{$j=refresh_job_start($w,'all','');}catch(Throwable $e){return;}
    }
    if(($j['state']??'')==='running'){
     refresh_job_step($w);
     // Reload the latest data before merging the workspace changes would require
     // a workspace transaction; the refresh worker is the only intended writer.
     save($d);
    }
    // Advance an automatic scrub in the same bounded request when refresh has ended.
    elseif(!empty($w['settings']['scrub_auto'])){scrub_step($w);save($d);}
   }finally{flock($lock,LOCK_UN);fclose($lock);}
  }catch(Throwable $ignored){}
 });
}
