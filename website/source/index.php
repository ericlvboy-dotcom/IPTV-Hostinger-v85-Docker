<?php
require __DIR__.'/lib.php';
function free_presets():array{return [
 ['IPTV-org English', 'https://iptv-org.github.io/iptv/languages/eng.m3u', 'ENGLISH', 1],
 ['IPTV-org French', 'https://iptv-org.github.io/iptv/languages/fra.m3u', 'FRENCH', 1],
 ['Pluto TV CA', 'https://raw.githubusercontent.com/BuddyChewChew/app-m3u-generator/main/playlists/plutotv_ca.m3u', 'PLUTO TV', 0],
 ['Pluto TV US', 'https://raw.githubusercontent.com/BuddyChewChew/app-m3u-generator/main/playlists/plutotv_us.m3u', 'PLUTO TV', 0],
 ['Pluto TV GB', 'https://raw.githubusercontent.com/BuddyChewChew/app-m3u-generator/main/playlists/plutotv_gb.m3u', 'PLUTO TV', 0],
 ['Pluto TV FR', 'https://raw.githubusercontent.com/BuddyChewChew/app-m3u-generator/main/playlists/plutotv_fr.m3u', 'PLUTO TV', 0],
 ['Samsung TV Plus CA', 'https://raw.githubusercontent.com/BuddyChewChew/app-m3u-generator/main/playlists/samsungtvplus_ca.m3u', 'SAMSUNG TV PLUS', 0],
 ['Samsung TV Plus US', 'https://raw.githubusercontent.com/BuddyChewChew/app-m3u-generator/main/playlists/samsungtvplus_us.m3u', 'SAMSUNG TV PLUS', 0],
 ['Samsung TV Plus GB', 'https://raw.githubusercontent.com/BuddyChewChew/app-m3u-generator/main/playlists/samsungtvplus_gb.m3u', 'SAMSUNG TV PLUS', 0],
 ['Samsung TV Plus FR', 'https://raw.githubusercontent.com/BuddyChewChew/app-m3u-generator/main/playlists/samsungtvplus_fr.m3u', 'SAMSUNG TV PLUS', 0],
 ['Plex CA', 'https://raw.githubusercontent.com/BuddyChewChew/app-m3u-generator/main/playlists/plex_ca.m3u', 'PLEX', 0],
 ['Plex US', 'https://raw.githubusercontent.com/BuddyChewChew/app-m3u-generator/main/playlists/plex_us.m3u', 'PLEX', 0],
 ['Plex GB', 'https://raw.githubusercontent.com/BuddyChewChew/app-m3u-generator/main/playlists/plex_gb.m3u', 'PLEX', 0],
 ['Plex FR', 'https://raw.githubusercontent.com/BuddyChewChew/app-m3u-generator/main/playlists/plex_fr.m3u', 'PLEX', 0],
 ['Roku', 'https://raw.githubusercontent.com/BuddyChewChew/app-m3u-generator/main/playlists/roku_all.m3u', 'ROKU', 0],
 ['Tubi', 'https://raw.githubusercontent.com/BuddyChewChew/app-m3u-generator/main/playlists/tubi_all.m3u', 'TUBI', 0],
 ['LG Channels Canada', 'https://www.apsattv.com/calg.m3u', 'LG CHANNELS CANADA', 0],
 ['LG Channels USA', 'https://www.apsattv.com/uslg.m3u', 'LG CHANNELS USA', 0],
 ['LG Channels UK', 'https://www.apsattv.com/gblg.m3u', 'LG CHANNELS UK', 0],
 ['LG Channels France', 'https://www.apsattv.com/frlg.m3u', 'LG CHANNELS FRANCE', 0],
 ['Local Now', 'https://www.apsattv.com/localnow.m3u', 'LOCAL NOW', 0],
 ['Xumo', 'https://www.apsattv.com/xumo.m3u', 'XUMO', 0],
 ['TCL TV Plus', 'https://www.apsattv.com/tclplus.m3u', 'TCL TV PLUS', 0],
 ['Cineverse', 'https://www.apsattv.com/cineverse.m3u', 'CINEVERSE', 0],
 ['Whale TV Plus USA', 'https://www.apsattv.com/whaletvplus_us.m3u', 'WHALE TV PLUS USA', 0],
 ['Whale TV Plus France', 'https://www.apsattv.com/whaletvplus_fr.m3u', 'WHALE TV PLUS FRANCE', 0],
];}

function epg_presets():array{return [
 ['EPG.pw Australia','https://epg.pw/xmltv/epg_AU.xml.gz'],
 ['EPG.pw Brazil','https://epg.pw/xmltv/epg_BR.xml.gz'],
 ['EPG.pw Canada','https://epg.pw/xmltv/epg_CA.xml.gz'],
 ['EPG.pw China','https://epg.pw/xmltv/epg_CN.xml.gz'],
 ['EPG.pw Germany','https://epg.pw/xmltv/epg_DE.xml.gz'],
 ['EPG.pw France','https://epg.pw/xmltv/epg_FR.xml.gz'],
 ['EPG.pw United Kingdom','https://epg.pw/xmltv/epg_GB.xml.gz'],
 ['EPG.pw Hong Kong','https://epg.pw/xmltv/epg_HK.xml.gz'],
 ['EPG.pw Indonesia','https://epg.pw/xmltv/epg_ID.xml.gz'],
 ['EPG.pw India','https://epg.pw/xmltv/epg_IN.xml.gz'],
 ['EPG.pw Japan','https://epg.pw/xmltv/epg_JP.xml.gz'],
 ['EPG.pw Malaysia','https://epg.pw/xmltv/epg_MY.xml.gz'],
 ['EPG.pw New Zealand','https://epg.pw/xmltv/epg_NZ.xml.gz'],
 ['EPG.pw Philippines','https://epg.pw/xmltv/epg_PH.xml.gz'],
 ['EPG.pw Russia','https://epg.pw/xmltv/epg_RU.xml.gz'],
 ['EPG.pw Singapore','https://epg.pw/xmltv/epg_SG.xml.gz'],
 ['EPG.pw Taiwan','https://epg.pw/xmltv/epg_TW.xml.gz'],
 ['EPG.pw United States','https://epg.pw/xmltv/epg_US.xml.gz'],
 ['EPG.pw Vietnam','https://epg.pw/xmltv/epg_VN.xml.gz'],
 ['EPG.pw South Africa','https://epg.pw/xmltv/epg_ZA.xml.gz'],
 ['EPG.pw Worldwide','https://epg.pw/xmltv/epg.xml.gz'],
 ['EPG.pw Worldwide ALL','https://epg.pw/xmltv/epg.xml.gz'],
 ['EPG.pw Worldwide Lite','https://epg.pw/xmltv/epg_lite.xml.gz'],
 ['EPGShare01 ALL Sources','https://epgshare01.online/epgshare01/epg_ripper_ALL_SOURCES1.xml.gz'],
 ['EPGShare01 Canada','https://epgshare01.online/epgshare01/epg_ripper_CA2.xml.gz'],
 ['EPGShare01 USA','https://epgshare01.online/epgshare01/epg_ripper_US2.xml.gz'],
 ['EPGShare01 USA Locals','https://epgshare01.online/epgshare01/epg_ripper_US_LOCALS1.xml.gz'],
 ['EPGShare01 United Kingdom','https://epgshare01.online/epgshare01/epg_ripper_UK1.xml.gz'],
 ['EPGShare01 Belgium','https://epgshare01.online/epgshare01/epg_ripper_BE2.xml.gz'],
 ['EPGShare01 Switzerland','https://epgshare01.online/epgshare01/epg_ripper_CH1.xml.gz'],
 ['EPGShare01 beIN Sports','https://epgshare01.online/epgshare01/epg_ripper_BEIN1.xml.gz'],
 ['Open-EPG Canada','https://www.open-epg.com/files/canada.xml.gz'],
 ['Open-EPG USA 1','https://www.open-epg.com/files/unitedstates1.xml.gz'],
 ['Open-EPG USA 2','https://www.open-epg.com/files/unitedstates2.xml.gz'],
 ['Open-EPG USA 3','https://www.open-epg.com/files/unitedstates3.xml.gz'],
 ['Open-EPG USA 4','https://www.open-epg.com/files/unitedstates4.xml.gz'],
 ['Open-EPG USA 5','https://www.open-epg.com/files/unitedstates5.xml.gz'],
 ['Open-EPG USA 6','https://www.open-epg.com/files/unitedstates6.xml.gz'],
 ['Open-EPG USA 7','https://www.open-epg.com/files/unitedstates7.xml.gz'],
 ['Open-EPG USA 8','https://www.open-epg.com/files/unitedstates8.xml.gz'],
 ['Open-EPG USA 9','https://www.open-epg.com/files/unitedstates9.xml.gz'],
 ['Open-EPG USA 10','https://www.open-epg.com/files/unitedstates10.xml.gz'],
 ['Open-EPG USA 11','https://www.open-epg.com/files/unitedstates11.xml.gz'],
 ['Open-EPG UK','https://www.open-epg.com/files/unitedkingdom1.xml'],
 ['XMLTV France French TNT','https://xmltvfr.fr/xmltv/xmltv_tnt.xml.gz'],
 ['XMLTV France France','https://xmltvfr.fr/xmltv/xmltv_fr.xml.gz'],
 ['XMLTV France Complete','https://xmltvfr.fr/xmltv/xmltv.xml.gz'],
 ['i.mjh.nz PlutoTV CA','https://i.mjh.nz/PlutoTV/ca.xml.gz'],
 ['i.mjh.nz PlutoTV US','https://i.mjh.nz/PlutoTV/us.xml.gz'],
 ['i.mjh.nz PlutoTV FR','https://i.mjh.nz/PlutoTV/fr.xml'],
 ['i.mjh.nz SamsungTVPlus CA','https://i.mjh.nz/SamsungTVPlus/ca.xml.gz'],
 ['i.mjh.nz SamsungTVPlus US','https://i.mjh.nz/SamsungTVPlus/us.xml.gz'],
 ['i.mjh.nz SamsungTVPlus FR','https://i.mjh.nz/SamsungTVPlus/fr.xml'],
 ['i.mjh.nz Plex US','https://i.mjh.nz/Plex/us.xml.gz'],
 ['Teleonline International','https://teleonline.github.io/epg/epg.xml.gz'],
 ['UK Freeview','https://raw.githubusercontent.com/dp247/Freeview-EPG/master/epg.xml'],
 ['Airtel','https://raw.githubusercontent.com/jasonramg/iptv-epg/main/epg/airtel.xml.gz'],
 ['JioTV','https://raw.githubusercontent.com/jasonramg/iptv-epg/main/epg/jiotv.xml.gz'],
 ['Tata Play','https://raw.githubusercontent.com/jasonramg/iptv-epg/main/epg/tataplay.xml.gz'],
];}

start_dashboard_session();init();
// A stale browser tab must not submit a token from a previous login/session.
if(($_GET['csrf_token']??'')==='1'){
 header('Content-Type: application/json; charset=utf-8');header('Cache-Control: no-store, private');
 header('X-Content-Type-Options: nosniff');
 if(!auth()){http_response_code(401);echo json_encode(['error'=>'Sign in required']);exit;}
 echo json_encode(['csrf'=>csrf()]);exit;
}
$d=data();$msg='';$postError=false;
try{if($_SERVER['REQUEST_METHOD']==='POST'){check_csrf();$action=$_POST['action']??'';
if($action==='setup'&&!count($d['users'])){$name=trim($_POST['username']??'admin');$pass=$_POST['password']??'';if($pass==='')throw new Exception('Enter a password');$d['users'][]=['id'=>bin2hex(random_bytes(6)),'name'=>$name,'hash'=>password_hash($pass,PASSWORD_DEFAULT),'role'=>'admin','ws'=>'master','active'=>true,'output_user'=>'','output_hash'=>''];save($d);$msg='Admin created. Sign in.';}
elseif($action==='login'){foreach($d['users'] as $u)if(($u['active']??true)&&hash_equals($u['name'],(string)($_POST['username']??''))&&password_verify((string)($_POST['password']??''),$u['hash'])){session_regenerate_id(true);$_SESSION['user']=['name'=>$u['name'],'role'=>$u['role'],'ws'=>$u['ws']];header('Location: index.php');exit;}throw new Exception('Invalid login');}
elseif($action==='logout'){$_SESSION=[];session_destroy();header('Location: index.php');exit;}
else{require_auth();$ws=wsid();$w=&workspace($d,$ws);switch($action){case 'add_source':case 'add_epg':$key=$action==='add_epg'?'epg':'sources';$url=trim($_POST['url']??'');if(!preg_match('~^https?://~i',$url))throw new Exception('Enter an HTTP(S) URL');
$type=$action==='add_source'&&($_POST['source_type']??'m3u')==='xtream'?'xtream':'m3u';
$record=['id'=>bin2hex(random_bytes(5)),'name'=>trim($_POST['name']??'Source'),'url'=>$type==='xtream'?xt_source_base($url):$url,'priority'=>(int)($_POST['priority']??1),'prefix'=>trim($_POST['prefix']??''),'enabled'=>true,'status'=>'Not refreshed','type'=>$type];
if($type==='xtream'){$user=trim((string)($_POST['xt_user']??''));$pass=(string)($_POST['xt_pass']??'');if($user===''||$pass==='')throw new Exception('Xtream username and password are required');$record['xt_user']=$user;$record['xt_pass_enc']=output_encrypt($pass);$record['xt_format']=($_POST['xt_format']??'ts')==='m3u8'?'m3u8':'ts';}
$w[$key][]=$record;
if($action==='add_source'&&$type==='xtream'){
 $epgUrl=xt_source_base($record['url']).'/xmltv.php?'.http_build_query(['username'=>$user,'password'=>$pass],'','&',PHP_QUERY_RFC3986);
 $already=false;foreach($w['epg'] as $epgItem){if(($epgItem['xt_source_id']??'')===$record['id']||($epgItem['url']??'')===$epgUrl){$already=true;break;}}
 if(!$already)$w['epg'][]=['id'=>bin2hex(random_bytes(5)),'name'=>$record['name'].' · Xtream EPG','url'=>$epgUrl,'enabled'=>true,'xt_source_id'=>$record['id'],'type'=>'xmltv'];
}
break;
case 'install_epg_presets':
 $selected=(array)($_POST['epg_preset']??[]);$catalog=epg_presets();$added=0;
 foreach($selected as $index){if(!ctype_digit((string)$index)||!isset($catalog[(int)$index]))continue;
  [$name,$url]=$catalog[(int)$index];$exists=false;
  foreach($w['epg'] as $e)if(rtrim((string)($e['url']??''),'/')===rtrim($url,'/')){$exists=true;break;}
  if($exists)continue;
  $w['epg'][]=['id'=>bin2hex(random_bytes(5)),'name'=>$name,'url'=>$url,'enabled'=>true,'type'=>'xmltv','preset'=>true,'status'=>'Not checked','consecutive_failed_refreshes'=>0];$added++;
 }
 $msg=$added.' EPG preset(s) added; existing EPG sources preserved';break;
case 'keep_pending_epg':case 'delete_pending_epg':case 'delete_all_pending_epg':
 if(auth()['role']!=='admin')throw new Exception('Admin only');
 $pending=$w['settings']['pending_epg_deletions']??[];$ids=[];
 if($action==='delete_all_pending_epg'){
  if(($_POST['confirm']??'')!=='DELETE_ALL')throw new Exception('Confirmation required');
  $ids=array_column($pending,'id');
 }else{$id=(string)($_POST['id']??'');foreach($pending as $e)if(($e['id']??'')===$id)$ids[]=$id;if(!$ids)throw new Exception('Pending EPG not found');}
 if($action==='keep_pending_epg'){foreach($w['epg'] as &$e){if(in_array($e['id'],$ids,true)){$e['consecutive_failed_refreshes']=0;$e['status']='Kept by user';}}unset($e);}
 else {foreach($w['epg'] as $entry)if(in_array($entry['id']??'', $ids,true)&&!empty($entry['xt_source_id']))$w['settings']['suppressed_xtream_epg_sources']=array_values(array_unique(array_merge($w['settings']['suppressed_xtream_epg_sources']??[],[(string)$entry['xt_source_id']] )));$w['epg']=array_values(array_filter($w['epg'],fn($e)=>!in_array($e['id'],$ids,true)));}
 $w['settings']['pending_epg_deletions']=array_values(array_filter($pending,fn($e)=>!in_array($e['id'],$ids,true)));
 $msg=$action==='keep_pending_epg'?'EPG kept; failure counter reset':'Selected EPG sources deleted by approval';break;
case 'delete_pending_dead':case 'keep_pending_dead':case 'delete_all_pending_dead':
 if(auth()['role']!=='admin')throw new Exception('Admin only');
 $pending=$w['settings']['pending_source_deletions']??[];
 $id=(string)($_POST['id']??'');
 if($action==='delete_all_pending_dead'){
  if(($_POST['confirm']??'')!=='DELETE_ALL')throw new Exception('Delete All confirmation required');
  $ids=array_values(array_unique(array_filter(array_map(fn($e)=>(string)($e['id']??''),$pending))));
 }else{
  $ids=[];foreach($pending as $e)if(($e['id']??'')===$id)$ids[]=$id;
  if(!$ids)throw new Exception('Pending source not found');
 }
 if($action==='keep_pending_dead'){
  foreach($w['sources'] as &$src)if(in_array($src['id'],$ids,true))$src['consecutive_failed_refreshes']=0;
  unset($src);$msg='Source kept; failure counter reset';
 }else{
  $activeIds=[];foreach($w['sources'] as $src)if(in_array($src['id'],$ids,true))$activeIds[]=$src['id'];
  $w['sources']=array_values(array_filter($w['sources'],fn($src)=>!in_array($src['id'],$activeIds,true)));
  $w['epg']=array_values(array_filter($w['epg'],fn($e)=>!in_array((string)($e['xt_source_id']??''),$activeIds,true)));
  foreach(['channels','published_channels','movies','series'] as $cacheKey)
   if(isset($w[$cacheKey]))$w[$cacheKey]=array_values(array_filter($w[$cacheKey],fn($row)=>!in_array((string)($row['source_id']??''),$activeIds,true)));
  $w['settings']['auto_removed_sources']=array_slice(array_merge(array_values(array_filter($pending,fn($e)=>in_array((string)($e['id']??''),$ids,true))),$w['settings']['auto_removed_sources']??[]),0,100);
  $msg=count($activeIds).' source(s) deleted by approval';
 }
 $w['settings']['pending_source_deletions']=array_values(array_filter($pending,fn($e)=>!in_array((string)($e['id']??''),$ids,true)));
 break;
case 'discover_sources':$result=discovery_run($w);$msg=$result['status'];break;
case 'approve_pending':case 'reject_pending':
 $pid=(string)($_POST['id']??'');$pending=$w['pending_sources']??[];$matched=null;
 foreach($pending as $entry)if(($entry['id']??'')===$pid){$matched=$entry;break;}
 if(!$matched)throw new Exception('Pending source not found');
 $w['pending_sources']=array_values(array_filter($pending,fn($entry)=>($entry['id']??'')!==$pid));
 if($action==='reject_pending'){$w['rejected_sources'][]=$matched['url'];$msg='Source rejected';}
 else{
  $exists=false;foreach($w['sources'] as $existing)if(rtrim($existing['url'],'/')===rtrim($matched['url'],'/')){$exists=true;break;}
  if(!$exists)$w['sources'][]=['id'=>bin2hex(random_bytes(5)),'name'=>$matched['name'],'url'=>$matched['url'],'priority'=>1,'prefix'=>'','enabled'=>true,'status'=>'Not refreshed','type'=>'m3u','preset'=>true,'language_filter'=>'eng,fra'];
  $msg=$exists?'Source already installed':'Source approved and enabled';
 }break;
case 'install_presets':
// Add missing presets only; retain existing preset and manual source settings.
$added=0;foreach(free_presets() as [$name,$url,$prefix,$trusted]){$exists=false;foreach($w['sources'] as $src)if(rtrim((string)$src['url'],'/')===rtrim($url,'/')){$exists=true;break;}if($exists)continue;
$w['sources'][]=['id'=>bin2hex(random_bytes(5)),'name'=>$name,'url'=>$url,'priority'=>1,'prefix'=>$prefix,'enabled'=>true,'status'=>'Not refreshed','type'=>'m3u','preset'=>true,'language_filter'=>'eng,fra','trusted_language_feed'=>(bool)$trusted];$added++;}
$msg=$added.' missing presets added. Existing manual and preset sources preserved';break;
case 'edit_prefix_only':
$id=(string)($_POST['id']??'');$prefix=trim((string)($_POST['prefix']??''));
if(strlen($prefix)>80)throw new Exception('Prefix must be 80 characters or fewer');
$found=false;
foreach($w['sources'] as &$src){
 if(($src['id']??'')!==$id)continue;
 $old=trim((string)($src['prefix']??''));
 if($old!==''&&$old!==$prefix)$src['prefix_history']=array_values(array_unique(array_merge($src['prefix_history']??[],[$old])));
 $src['prefix']=$prefix;$found=true;break;
}unset($src);
if(!$found)throw new Exception('Source not found');
$msg='Prefix saved. Update playlist in IPTV player to see it';break;
case 'edit_source':
$id=(string)($_POST['id']??'');$name=trim((string)($_POST['name']??''));$url=trim((string)($_POST['url']??''));$prefix=trim((string)($_POST['prefix']??''));
if($name===''||!filter_var($url,FILTER_VALIDATE_URL)||!in_array(strtolower((string)parse_url($url,PHP_URL_SCHEME)),['http','https'],true))throw new Exception('Valid source name and HTTP(S) URL required');
$found=false;foreach($w['sources'] as &$src){if($src['id']!==$id)continue;if(($src['type']??'m3u')==='xtream'){
 $src['url']=xt_source_base($url);
 $newUser=trim((string)($_POST['xt_user']??''));
 $newPass=(string)($_POST['xt_pass']??'');
 if($newUser!=='')$src['xt_user']=$newUser;
 if($newPass!=='')$src['xt_pass_enc']=output_encrypt($newPass);
 $src['xt_format']=($_POST['xt_format']??'ts')==='m3u8'?'m3u8':'ts';
 foreach($w['epg'] as &$epgItem)if(($epgItem['xt_source_id']??'')===$id){
 $epgItem['url']=xt_source_base($src['url']).'/xmltv.php?'.http_build_query(['username'=>$src['xt_user'],'password'=>output_decrypt($src['xt_pass_enc'])],'','&',PHP_QUERY_RFC3986);
 $epgItem['name']=$name.' · Xtream EPG';
 }unset($epgItem);
 }else{$src['url']=$url;}
 // Keep historical prefixes so already-imported and scrub-published rows can be relabelled without reimporting.
 $previousPrefix=trim((string)($src['prefix']??''));
 if($previousPrefix!=='' && $previousPrefix!==$prefix){
  $src['prefix_history']=array_values(array_unique(array_merge($src['prefix_history']??[],[$previousPrefix])));
 }
 $src['name']=$name;if(!empty($src['preset']))$src['trusted_language_feed']=in_array($url,array_column(array_filter(free_presets(),fn($p)=>$p[3]),1),true);$src['prefix']=$prefix;$src['priority']=max(1,(int)($_POST['priority']??1));$src['status']='Not refreshed';$found=true;break;}unset($src);if(!$found)throw new Exception('Source not found');break;
case 'edit_epg':
 $id=(string)($_POST['id']??'');$name=trim((string)($_POST['name']??''));$url=trim((string)($_POST['url']??''));
 if($name==='')throw new Exception('EPG name is required');
 if(!preg_match('~^https?://~i',$url))throw new Exception('Enter an HTTP(S) XMLTV URL');
 foreach($w['epg'] as $other)if(($other['id']??'')!==$id && rtrim((string)($other['url']??''),'/')===rtrim($url,'/'))throw new Exception('This EPG URL is already installed');
 $found=false;foreach($w['epg'] as &$epg){if(($epg['id']??'')!==$id)continue;
 if(!empty($epg['xt_source_id']) && $url!==($epg['url']??''))throw new Exception('Xtream EPG URL is managed by its playlist source');
 $epg['name']=$name;if($url!==($epg['url']??'')){$epg['url']=$url;$epg['status']='Not checked';$epg['consecutive_failed_refreshes']=0;}
 $found=true;break;}unset($epg);if(!$found)throw new Exception('EPG source not found');$msg='EPG source updated';break;
case 'delete_source':case 'delete_epg':$key=$action==='delete_epg'?'epg':'sources';$removedId=(string)($_POST['id']??'');
 if($action==='delete_epg'){foreach($w['epg'] as $entry)if((string)($entry['id']??'')===$removedId&&!empty($entry['xt_source_id'])){$w['settings']['suppressed_xtream_epg_sources']=array_values(array_unique(array_merge($w['settings']['suppressed_xtream_epg_sources']??[],[(string)$entry['xt_source_id']])));break;}
 $w['settings']['pending_epg_deletions']=array_values(array_filter($w['settings']['pending_epg_deletions']??[],fn($e)=>(string)($e['id']??'')!==$removedId));}
 $w[$key]=array_values(array_filter($w[$key],fn($s)=>$s['id']!==$removedId));if($action==='delete_source')$w['epg']=array_values(array_filter($w['epg'],fn($e)=>($e['xt_source_id']??'')!==$removedId));if($action==='delete_source'){foreach(['channels','published_channels','movies','series'] as $cacheKey)if(isset($w[$cacheKey]))$w[$cacheKey]=array_values(array_filter($w[$cacheKey],fn($row)=>(string)($row['source_id']??'')!==$removedId));}break;
case 'toggle_source':case 'toggle_epg':$key=$action==='toggle_epg'?'epg':'sources';foreach($w[$key] as &$s)if($s['id']===($_POST['id']??''))$s['enabled']=!$s['enabled'];unset($s);break;
case 'refresh':refresh_job_start($w,(string)($_POST['refresh_mode']??'all'),(string)($_POST['id']??''));$msg='Refresh queued';break;
case 'schedule':schedule_defaults($w);$w['settings']['auto_refresh_daily']=isset($_POST['auto_refresh_daily']);$w['settings']['player_refresh_12h']=isset($_POST['player_refresh_12h']);$w['settings']['m3u_minutes']=max(5,min(10080,(int)($_POST['m3u_minutes']??360)));$w['settings']['epg_minutes']=max(5,min(10080,(int)($_POST['epg_minutes']??720)));$w['settings']['scrub_auto']=isset($_POST['scrub_auto']);$batch=(int)($_POST['scrub_batch']??10);$w['settings']['scrub_batch']=in_array($batch,[3,5,10,25,50],true)?$batch:10;break;
case 'scrub_start':scrub_start($w);break;
case 'scrub_stop':schedule_defaults($w);$w['settings']['scrub']['state']='stopped';break;
case 'scrub_step':scrub_step($w);break;
case 'epg_refresh':epg_queue_start($w);$msg='EPG build queued. Keep dashboard open to complete it.';break;
case 'rename_channel':$old=trim($_POST['old']??'');$new=trim($_POST['new']??'');if(!$old||!$new)throw new Exception('Enter both channel names');foreach($w['channels'] as &$c)if($c['name']===$old)$c['name']=$new;unset($c);break;
case 'group':
 $from=trim($_POST['from']??'');$to=trim($_POST['to']??'');
 if($from===''||$to===''||$from===$to)throw new Exception('Choose two different groups');
 $available=array_unique(array_column($w['channels'],'group'));
 if(!in_array($from,$available,true)&&!isset($w['groups'][$from]))throw new Exception('Source group not found');
 $w['groups'][$from]=$to;break;
case 'group_merge':
 $target=trim($_POST['target']??'');$selected=(array)($_POST['groups']??[]);
 if(!$target||!$selected)throw new Exception('Choose groups and destination');
 $available=array_unique(array_column($w['channels'],'group'));
 foreach($selected as $g){if(!is_string($g)||!in_array($g,$available,true)||$g===$target)continue;$w['groups'][$g]=$target;}
 break;
case 'group_reset':
 $from=(string)($_POST['from']??'');unset($w['groups'][$from]);break;
case 'group_reset_all':$w['groups']=[];break;
case 'output_password':$pass=$_POST['output_password']??'';if($pass==='')throw new Exception('Output password cannot be empty');foreach($d['users'] as &$u)if($u['name']===auth()['name']){$u['output_user']=trim($_POST['output_user']??$u['name']);$u['output_hash']=password_hash($pass,PASSWORD_DEFAULT);$u['output_encrypted']=output_encrypt($pass);}unset($u);break;
case 'output_organization':
 $styles=['off','title_space','upper_space','sentence_space','title_dash','upper_dash','sentence_dash','title_pipe','upper_pipe','sentence_pipe'];
 foreach(['groups','channels'] as $kind){
  $style=(string)($_POST['normalize_'.$kind.'_style']??'off');
  $w['settings']['normalize_'.$kind.'_style']=in_array($style,$styles,true)?$style:'off';
  $w['settings']['normalize_'.$kind]=$w['settings']['normalize_'.$kind.'_style']!=='off';
 }
 $w['settings']['sort_groups']=($_POST['sort_groups']??'default')==='az'?'az':'default';
 $w['settings']['sort_channels']=($_POST['sort_channels']??'default')==='az'?'az':'default';
 $msg='Output organization saved. Update playlist in your IPTV player.';break;
case 'clear_channel_scrub_cache':
 if(auth()['role']!=='admin')throw new Exception('Admin only');
 if(($_POST['confirm_clear']??'')!=='CLEAR')throw new Exception('Confirmation required');
 $refresh=refresh_job_read();
 if(($refresh['state']??'')==='running')throw new Exception('Stop the running source refresh before clearing cache');
 $w['channels']=[];
 unset($w['published_channels']);
 schedule_defaults($w);
 $w['settings']['scrub']=['state'=>'idle','index'=>0,'checked'=>0,'total'=>0,'started'=>0,'errors'=>0];
 foreach(['scrub_last_completed','scrub_last_results','scrub_published_count','scrub_last_published'] as $key)unset($w['settings'][$key]);
 // Invalidate the staged scrub and refresh job, without touching sources or approval lists.
 foreach([scrub_file(),refresh_job_path()] as $cachePath){if(is_file($cachePath)&&!@unlink($cachePath))throw new Exception('Could not clear cache file: '.basename($cachePath));}
 $msg='Imported Live TV and completed scrub cache cleared. Refresh sources, then run a complete scrub.';
 break;
case 'backup':header('Content-Type: application/json');header('Content-Disposition: attachment; filename="merger-backup.json"');echo json_encode($d,JSON_PRETTY_PRINT);exit;
case 'restore':if(auth()['role']!=='admin')throw new Exception('Admin only');$file=$_FILES['backup']['tmp_name']??'';$incoming=$file?json_decode(file_get_contents($file),true):null;if(!is_array($incoming)||!isset($incoming['users'],$incoming['workspaces']))throw new Exception('Invalid Hostinger-format backup');$d=$incoming;break;
}save($d);if(!$msg)$msg='Saved';}}}catch(Throwable $e){$msg=$e->getMessage();$postError=true;
 if($msg==='Invalid form token'){http_response_code(419);$msg='Your session changed. Please retry the action.';}
}
if($_SERVER['REQUEST_METHOD']==='POST' && ($_POST['ajax']??'')==='1'){
 header('Content-Type: application/json; charset=utf-8');
 header('Cache-Control: no-store');
 echo json_encode(['ok'=>!$postError,'message'=>$msg],JSON_UNESCAPED_SLASHES);
 exit;
}
$logged=(bool)auth();$ws=$logged?wsid():'master';if($logged){$active=&workspace($d,$ws);if(xt_sync_epg_sources($active))save($d);unset($active);}$w=$d['workspaces'][$ws]??['sources'=>[],'epg'=>[],'channels'=>[],'groups'=>[],'settings'=>[]];$base=dirname($_SERVER['SCRIPT_NAME']);$base=rtrim($base,'/');
$scheme=(!empty($_SERVER['HTTPS'])&&$_SERVER['HTTPS']!=='off')?'https':'http';
$host=(string)($_SERVER['HTTP_HOST']??'yourdomain.com');
// Do not trust a hostile Host header for URLs shown in the admin interface.
if(!preg_match('/^[a-zA-Z0-9.:-]+$/',$host))$host='yourdomain.com';
$server=$scheme.'://'.$host.$base;
$outputUser='';$outputPassword='';
if($logged){foreach($d['users'] as $u){if($u['name']===auth()['name']){$outputUser=(string)($u['output_user']??'');$outputPassword=output_decrypt((string)($u['output_encrypted']??''));break;}}}
$ready=$outputUser!==''&&$outputPassword!=='';
$m3uUrl=$ready?$server.'/get.php?'.http_build_query(['username'=>$outputUser,'password'=>$outputPassword,'type'=>'m3u_plus','output'=>'ts'],'','&',PHP_QUERY_RFC3986):'';
$epgUrl=$ready?$server.'/xmltv.php?'.http_build_query(['username'=>$outputUser,'password'=>$outputPassword],'','&',PHP_QUERY_RFC3986):'';

?><!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>IPTV Smart Merger</title><link rel="stylesheet" href="assets/style.css?v=21-batched-refresh"><style>
.source-summary{display:flex!important;align-items:center;justify-content:space-between;gap:8px;flex-wrap:wrap;min-width:0}
.source-title{display:flex;flex-direction:column;gap:3px;min-width:0;overflow-wrap:anywhere}
.source-summary-results{display:flex;flex-wrap:wrap;gap:5px;align-items:center;margin-left:auto;padding-right:7px}
.source-chip{display:inline-flex;align-items:center;padding:4px 7px;border:1px solid #6d7379;border-radius:7px;font-size:12px;font-weight:650;white-space:nowrap;color:#d9dce0}
.source-chip-prefix{border-color:#f58a24;color:#ffad5c;background:transparent;cursor:pointer;font:inherit;font-size:12px;font-weight:650}.source-chip-prefix:hover,.source-chip-prefix:focus-visible{background:#382719;outline:1px solid #f58a24}.source-chip-ok{border-color:#398b65;color:#8ce6ae}.source-chip-error{border-color:#b54b4b;color:#ff9e9e}.source-chip-pending{border-color:#636971;color:#b9bec5}
@media(max-width:640px){.source-summary{align-items:flex-start}.source-title{width:100%}.source-summary-results{width:100%;margin-left:0;justify-content:flex-start}}
</style></head><body><main><header><div><small>HOSTINGER EDITION • XTREAM BUILD</small><h1>IPTV Smart Merger</h1><p>Smoky charcoal • Orange outline controls • Direct stream passthrough</p></div><?php if($logged):?><form method="post"><input type="hidden" name="csrf" value="<?=csrf()?>"><button name="action" value="logout">Sign out</button></form><?php endif;?></header><?php if($msg):?><div class="notice"><?=esc($msg)?></div><?php endif;?>
<?php if(!$logged):?><section class="card"><h2><?=count($d['users'])?'Sign in':'First-time setup'?></h2><form method="post" class="stack"><input type="hidden" name="csrf" value="<?=csrf()?>"><label>Username<input name="username" value="admin" required></label><label>Password<input type="password" name="password" required></label><button name="action" value="<?=count($d['users'])?'login':'setup'?>"><?=count($d['users'])?'Sign in':'Create administrator'?></button></form></section>
<?php else:
schedule_defaults($w);
$counts=[];foreach($w['channels'] as $c){$g=(string)($c['group']??'Uncategorized');$counts[$g]=($counts[$g]??0)+1;}
ksort($counts,SORT_NATURAL|SORT_FLAG_CASE);
$outputCounts=[];foreach($w['channels'] as $c){$g=xc_group($w,$c);$outputCounts[$g]=($outputCounts[$g]??0)+1;}
ksort($outputCounts,SORT_NATURAL|SORT_FLAG_CASE);
$scr=$w['settings']['scrub'];$pct=$scr['total']?round(100*$scr['checked']/$scr['total']):0;
$elapsed=max(1,time()-(int)$scr['started']);$eta=$scr['checked']?ceil(($scr['total']-$scr['checked'])*$elapsed/$scr['checked']):null;
?>
<div class="stats compact"><article><strong><?=count($w['sources'])?></strong><span>Sources</span></article><article><strong><?=count($w['channels'])?></strong><span>Live TV</span></article><article><strong><?=count($w['movies']??[])?></strong><span>Movies</span></article><article><strong><?=count($w['series']??[])?></strong><span>TV Shows</span></article><article><strong><?=count($outputCounts)?></strong><span>Groups</span></article></div>
<div class="workflow">
<details class="card step" open><summary><b>1 · Playlists</b><small><?=count($w['sources'])?> sources · <?=count($w['movies']??[])?> movies · <?=count($w['series']??[])?> series</small></summary>
<div class="step-body">
<div class="actions"><strong>All sources · <?=count($w['sources'])?></strong></div>
<p class="muted">Regular playlists, FAST presets and Xtream Codes are managed together here. Expand any source to edit, refresh, enable/disable or delete it.</p>
<?php foreach($w['sources'] as $s):?>
<?php
$kindLabels=(($s['type']??'m3u')==='xtream')?['live'=>'TV','movies'=>'Movies','series'=>'Shows']:['m3u'=>'TV'];
$sourceBadges=[];
foreach($kindLabels as $key=>$label){
 $result=$s['results'][$key]??null;
 if($result){
  $state=$result['state']??'pending';
  $sourceBadges[]=['text'=>$label.': '.($state==='ok'?number_format((int)($result['count']??0)):($state==='error'?'FAILED':'Pending')),'state'=>$state];
 }else{
  $fallback=(string)($s['status']??'');
  if($key==='m3u' && preg_match('/^OK:\s*([0-9]+)\s*channels/i',$fallback,$m))$sourceBadges[]=['text'=>$label.': '.number_format((int)$m[1]),'state'=>'ok'];
  elseif($key==='m3u' && (str_starts_with($fallback,'Error:')||str_starts_with($fallback,'Refresh error:')))$sourceBadges[]=['text'=>$label.': FAILED','state'=>'error'];
  else $sourceBadges[]=['text'=>$label.': Not checked','state'=>'pending'];
 }
}
?>
<details class="item"><summary class="source-summary"><span class="source-title"><b><?=esc($s['name'])?></b><small><?=!empty($s['preset'])?'FAST preset · ':''?><?=($s['type']??'m3u')==='xtream'?'Xtream':'M3U'?><?=empty($s['enabled'])?' · Disabled':''?></small></span><span class="source-summary-results"><button type="button" class="source-chip source-chip-prefix quick-prefix" data-source-id="<?=esc($s['id'])?>" data-prefix="<?=esc((string)($s['prefix']??''))?>" title="Tap to edit only this prefix" aria-label="Edit prefix for <?=esc($s['name'])?>">Prefix: <span class="prefix-value"><?=esc(trim((string)($s['prefix']??''))!==''?(string)$s['prefix']:'None')?></span> ✎</button><?php foreach($sourceBadges as $badge):?><span class="source-chip source-chip-<?=esc($badge['state'])?>"><?=esc($badge['text'])?></span><?php endforeach;?></span></summary>
<div class="source-results" style="display:flex;flex-wrap:wrap;gap:8px;margin:10px 0">
<?php foreach((($s['type']??'m3u')==='xtream'?['live'=>'Live TV','movies'=>'Movies','series'=>'TV Shows']:['m3u'=>'Live TV']) as $rk=>$rl):
$rr=$s['results'][$rk]??null;$state=$rr['state']??'pending';?>
<div style="border:1px solid <?= $state==='ok'?'#378c62':($state==='error'?'#b84b4b':'#666') ?>;border-radius:8px;padding:8px 11px;min-width:110px">
<strong><?=esc($rl)?></strong><br>
<span><?= $state==='ok'?'✓ '.number_format((int)$rr['count']).' imported':($state==='error'?'✕ Failed':'— Not checked') ?></span>
<?php if($rr):?><br><small><?=esc($rr['at']??'')?></small><?php if($state==='error'):?><br><small style="overflow-wrap:anywhere"><?=esc($rr['message']??'')?></small><?php endif; endif;?>
</div><?php endforeach;?></div>
<small class="url"><?=esc($s['url'])?></small>
<form method="post" class="actions"><input type="hidden" name="csrf" value="<?=csrf()?>"><input type="hidden" name="id" value="<?=esc($s['id'])?>"><button name="action" value="refresh">Refresh</button><button name="action" value="toggle_source"><?=empty($s['enabled'])?'Enable':'Disable'?></button><button name="action" value="delete_source" onclick="return confirm('Delete source?')">Delete</button></form>
<details class="item"><summary>Edit source</summary><form method="post" class="stack"><input type="hidden" name="csrf" value="<?=csrf()?>"><input type="hidden" name="id" value="<?=esc($s['id'])?>">
<label>Name<input name="name" value="<?=esc($s['name'])?>" required></label>
<label><?=($s['type']??'m3u')==='xtream'?'Xtream server':'Playlist URL'?><input name="url" type="url" value="<?=esc($s['url'])?>" required></label>
<?php if(($s['type']??'m3u')==='xtream'):?>
<label>Xtream username (leave blank to keep)<input name="xt_user" autocomplete="off" placeholder="Keep existing username"></label>
<label>Xtream password (leave blank to keep)<input name="xt_pass" type="password" autocomplete="new-password" placeholder="Keep existing password"></label>
<label>Stream format<select name="xt_format"><option value="ts" <?=($s['xt_format']??'ts')==='ts'?'selected':''?>>MPEG-TS</option><option value="m3u8" <?=($s['xt_format']??'ts')==='m3u8'?'selected':''?>>HLS</option></select></label>
<?php endif;?>
<div class="two"><label>Prefix<input name="prefix" value="<?=esc($s['prefix']??'')?>"></label><label>Priority<input type="number" min="1" name="priority" value="<?=esc($s['priority']??1)?>"></label></div>
<button name="action" value="edit_source">Save changes</button></form></details></details>
<?php endforeach;?>
<details class="item" open><summary>Pending Sources · <?=count($w['pending_sources']??[])?></summary>
<p class="muted">New feeds are never enabled until you approve them. Discovery checks trusted public playlist files and validates M3U structure; it does not guarantee playback or language for every channel.</p>
<div class="actions"><form method="post"><input type="hidden" name="csrf" value="<?=csrf()?>"><button name="action" value="discover_sources">Discover Now</button></form><small><?=esc($w['settings']['discovery_status']??'Not checked yet')?></small></div>
<?php foreach($w['pending_sources']??[] as $pending):?><div class="item"><b><?=esc($pending['name'])?></b><small> · <?=number_format((int)$pending['channels'])?> entries · <?=esc($pending['language'])?></small><p class="url"><?=esc($pending['url'])?></p><div class="actions"><form method="post"><input type="hidden" name="csrf" value="<?=csrf()?>"><input type="hidden" name="id" value="<?=esc($pending['id'])?>"><button name="action" value="approve_pending">Approve</button><button class="danger" name="action" value="reject_pending">Reject</button></form></div></div><?php endforeach;?>
<?php if(empty($w['pending_sources'])):?><p class="muted">No new feeds awaiting approval.</p><?php endif;?></details>
<details class="item"><summary>Preset catalog / installed status · <?=count(free_presets())?> available</summary>
<p class="muted">A manually added URL counts as installed. It appears only once in All Sources above, under the name you gave it.</p>
<?php foreach(free_presets() as [$pn,$pu,$pp,$pt]): $match=null;foreach($w['sources'] as $ps){if(rtrim((string)($ps['url']??''),'/')===rtrim($pu,'/')){$match=$ps;break;}} ?>
<div class="item" style="padding:8px;margin:5px 0"><b><?=esc($pn)?></b> · <?= $match?(!empty($match['enabled'])?'Installed / enabled':'Installed / disabled'):'Not installed' ?><?php if($match):?><small> · <?=empty($match['preset'])?'Manually added as '.esc($match['name']):'Preset source'?></small><?php endif;?></div>
<?php endforeach;?><p class="muted">Installs available English/French-focused feeds into the unified source list above. Existing manual sources stay unchanged. Reinstalling presets replaces prior presets.</p><form method="post" class="actions"><input type="hidden" name="csrf" value="<?=csrf()?>"><button name="action" value="install_presets">Install FAST presets (enabled)</button></form></details>
<form method="post" class="actions"><input type="hidden" name="csrf" value="<?=csrf()?>"><button name="action" value="refresh">Refresh all sources</button><select name="refresh_mode" aria-label="Refresh content"><option value="all">Live + Movies + Series</option><option value="live">Live only</option><option value="movies">Movies only</option><option value="series">Series only</option></select></form>
<div class="actions"><button type="button" id="refresh-force-stop" class="danger">Stop refresh now</button></div>
<div class="refresh-status" id="refresh-status" aria-live="polite"><div id="refresh-stage">Refresh idle</div><progress id="refresh-progress" value="0" max="100"></progress><small id="refresh-counts">Live 0 · Movies 0 · Shows 0</small><small id="refresh-errors"></small></div>
<details class="item"><summary>Add playlist</summary><form method="post" class="stack"><input type="hidden" name="csrf" value="<?=csrf()?>"><label>Name<input name="name" required></label><label>Source type<select name="source_type" id="source-type"><option value="m3u">M3U URL</option><option value="xtream">Xtream Codes API</option></select></label><label id="source-url-label">Playlist URL / Xtream server<input type="url" name="url" required placeholder="https://provider.example:port"></label><div id="xtream-fields" hidden><label>Xtream username<input name="xt_user" autocomplete="off"></label><label>Xtream password<input name="xt_pass" type="password" autocomplete="new-password"></label><label>Stream format<select name="xt_format"><option value="ts">MPEG-TS (.ts)</option><option value="m3u8">HLS (.m3u8)</option></select></label></div><div class="two"><label>Group prefix<input name="prefix" placeholder="SOURCE"></label><label>Priority<input type="number" name="priority" value="1" min="1"></label></div><button name="action" value="add_source">Add source</button></form></details>
</div></details>
<details class="card step"><summary><b>2 · Organize groups</b><small><?=count($counts)?> provider groups → <?=count($outputCounts)?> output groups</small></summary><div class="step-body">
<p class="muted">Choose groups from the real playlist. Channel counts show what each group contains before merging. Rules survive refreshes.</p>
<details class="item" open><summary>Merge provider groups</summary><form method="post" class="stack" id="group-merge-form"><input type="hidden" name="csrf" value="<?=csrf()?>">
<details class="group-dropdown" id="group-dropdown"><summary><span id="group-selection-label">Select provider groups</span><small id="group-selection-count">0 selected</small></summary><div class="group-dropdown-body"><label class="group-search-label">Search groups<input id="group-search" type="search" placeholder="Search groups…" autocomplete="off"></label><div class="group-picker" id="group-picker" role="group" aria-label="Available provider groups"><?php foreach($counts as $g=>$n):?><label class="group-choice" data-search="<?=esc(strtolower($g))?>"><input type="checkbox" name="groups[]" value="<?=esc($g)?>"><span><?=esc($g)?></span><small><?=esc($n)?> ch</small></label><?php endforeach;?></div><div class="group-dropdown-footer"><small id="group-match-count"><?=count($counts)?> groups available</small><button type="button" id="group-picker-done">Done</button></div></div></details>
<div class="destination-picker" id="destination-picker"><label>Merge selected into</label><input type="hidden" name="target" id="group-target" value=""><details id="destination-dropdown" class="group-dropdown"><summary><span id="destination-label">Choose destination</span><small id="destination-selected-count">Tap to search</small></summary><div class="group-dropdown-body"><label class="group-search-label">Search destination groups<input type="search" id="destination-search" placeholder="Type to find a group…" autocomplete="off"></label><div class="destination-list" id="destination-list" role="group" aria-label="Destination groups"><?php foreach($outputCounts as $g=>$n):?><button type="button" class="destination-option" data-value="<?=esc($g)?>" data-search="<?=esc(mb_strtolower($g))?>"><span><?=esc($g)?></span><small><?=esc($n)?> ch</small></button><?php endforeach;?></div><div class="group-dropdown-footer"><small id="destination-match-count"><?=count($outputCounts)?> groups</small><button type="button" id="destination-clear">Clear</button></div></div></details></div>
<details class="item group-preview-details"><summary>Merge preview</summary><p class="muted" id="group-preview">Select groups and destination to preview.</p></details><button name="action" value="group_merge" onclick="return confirm('Save selected group merges?')">Save merge</button></form></details>
<details class="item"><summary>Rename a group</summary><form method="post" class="stack"><input type="hidden" name="csrf" value="<?=csrf()?>"><label>Current group<select name="from" required><?php foreach($outputCounts as $g=>$n):?><option value="<?=esc($g)?>"><?=esc($g)?> (<?=esc($n)?>)</option><?php endforeach;?></select></label><label>New name<input name="to" required maxlength="160"></label><button name="action" value="group">Save rename</button></form></details>
<details class="item"><summary>Output groups (<?=count($outputCounts)?>)</summary><div class="group-picker"><?php foreach($outputCounts as $g=>$n):?><div class="group-choice"><span><?=esc($g)?></span><small><?=esc($n)?> ch</small></div><?php endforeach;?></div></details>
<details class="item"><summary>Saved rules (<?=count($w['groups'])?>)</summary><?php foreach($w['groups'] as $a=>$b):?><div class="rule"><span><?=esc($a)?> → <?=esc($b)?></span><form method="post"><input type="hidden" name="csrf" value="<?=csrf()?>"><input type="hidden" name="from" value="<?=esc($a)?>"><button name="action" value="group_reset">Undo</button></form></div><?php endforeach;?><?php if($w['groups']):?><form method="post"><input type="hidden" name="csrf" value="<?=csrf()?>"><button name="action" value="group_reset_all" onclick="return confirm('Reset all group rules?')">Reset all rules</button></form><?php endif;?></details>
</div></details>
<details class="card step"><summary><b>3 · Scrub channels</b><small>Parallel checks · Published playlist stays available</small></summary><div class="step-body">
<p><strong>Published Live TV:</strong> <?=count(player_live_channels($w))?> channels (<?=array_key_exists('published_channels',$w)?'last completed scrub':'original imported source fallback — awaiting first completed scrub'?>). Imported Live TV: <?=count($w['channels']??[])?>. Movies and TV Shows are not scrubbed.</p>
<p id="scrub-status"><strong><?=esc(strtoupper($scr['state']))?></strong> · <?=esc($scr['checked'])?> / <?=esc($scr['total'])?> checked · <?=esc($pct)?>% · ETA <?= $scr['state']==='running'&&$eta!==null?esc(ceil($eta/60)).' min':'—' ?></p>
<progress id="scrub-progress" value="<?=esc($pct)?>" max="100"></progress><small id="scrub-errors"><?=esc($scr['errors'])?> streams flagged for review. HEAD checks may be rejected by working providers.</small>
<form method="post" class="actions"><input type="hidden" name="csrf" value="<?=csrf()?>"><button name="action" value="scrub_start">Start scrub</button><button name="action" value="scrub_stop">Stop</button></form>
<details class="item"><summary>Scrub & schedule settings</summary><form method="post" class="stack"><input type="hidden" name="csrf" value="<?=csrf()?>"><label>Streams per batch<select name="scrub_batch"><?php foreach([3,5,10,25,50] as $size):?><option value="<?=$size?>" <?=(int)$w['settings']['scrub_batch']===$size?'selected':''?>><?=$size?></option><?php endforeach;?></select></label><label class="checkline"><input type="checkbox" name="player_refresh_12h" <?=!empty($w['settings']['player_refresh_12h'])?'checked':''?>>Allow IPTV player requests to trigger a source refresh every 12 hours</label><small>No Hostinger cron needed. A player request starts or advances one refresh step; additional requests are needed to complete large refreshes. The existing playlist is served first.</small><label class="checkline"><input type="checkbox" name="auto_refresh_daily" <?=!empty($w['settings']['auto_refresh_daily'])?'checked':''?>>Automatically refresh enabled sources every 24 hours while dashboard is open</label><small>No Hostinger cron required. Runs only while the dashboard is being used.</small><label>M3U refresh (minutes)<input type="number" name="m3u_minutes" min="5" value="<?=esc($w['settings']['m3u_minutes'])?>"></label><label class="checkline"><input type="checkbox" name="scrub_auto" <?=$w['settings']['scrub_auto']?'checked':''?>>Auto scrub after refresh</label><button name="action" value="schedule">Save settings</button></form></details>
<small>Staging results are not published until completion. HTTP HEAD follows safe redirects. Failed checks are review flags, not automatic removals.</small></div></details>
<details class="card step"><summary><b>4 · TV guide (EPG)</b><small><?=count($w['epg'])?> XMLTV sources</small></summary><div class="step-body">
<p class="muted">Merged XMLTV: all enabled EPG feeds are downloaded one at a time, matched to your playlist tvg-id values, deduplicated and cached as one compressed guide. TiviMate receives the combined guide. Stream playback stays direct passthrough. Keep this page open during a build; no cron required.</p>
<div class="item"><b>Combined EPG builder</b><p class="muted">Builds one source per request to reduce shared-hosting timeouts. Refresh again after playlist channel IDs change. Existing cached guide remains available during a rebuild.</p><div class="actions"><button type="button" id="epg-build-start">Build / refresh combined EPG</button><button type="button" id="epg-build-resume">Resume build</button></div><p id="epg-build-progress">Checking merged guide…</p><progress id="epg-build-bar" value="0" max="100" style="width:100%"></progress></div>
<div class="item" id="epg-delivery-panel"><b>EPG delivery activity</b><div id="epg-delivery-live" style="margin-top:8px">Checking player requests…</div><small>Requests are counted only when the player contacts this merger. When a merged guide is cached, it is served from this website. Before the first successful build, the existing provider redirect is used. If TiviMate uses a previously cached direct EPG URL, no new request will appear. Xtream requests and returned listings are logged separately.</small></div>
<details class="item" open><summary><b>All EPG sources · <?=count($w['epg'])?></b></summary>
<?php if(!$w['epg']):?><p class="muted">No EPG sources installed. Add a preset or XMLTV URL below.</p><?php endif;?>
<?php foreach($w['epg'] as $s):
 $epgKind=!empty($s['xt_source_id'])?'Xtream EPG':(!empty($s['preset'])?'EPG preset':'Manual XMLTV');
 $epgStatus=(string)($s['status']??'Not checked');$failures=(int)($s['consecutive_failed_refreshes']??0);
 $epgDisabled=empty($s['enabled']);
?><details class="item" style="margin:10px 0">
<summary><b><?=esc($s['name'])?></b> <small><?=esc($epgKind)?> · <?=$epgDisabled?'Disabled':esc($epgStatus)?><?=$failures?' · '.$failures.' failure(s)':''?></small></summary>
<div class="stack" style="padding-top:10px">
<div class="source-results" style="display:flex;flex-wrap:wrap;gap:8px">
<div style="border:1px solid <?=$epgDisabled?'#777':($failures?'#b84b4b':'#378c62')?>;border-radius:8px;padding:8px 11px"><strong>XMLTV Guide</strong><br><small><?=esc($epgDisabled?'Disabled':$epgStatus)?></small></div>
<div style="border:1px solid #666;border-radius:8px;padding:8px 11px"><strong>Source type</strong><br><small><?=esc($epgKind)?></small></div>
</div>
<small class="url" style="overflow-wrap:anywhere"><?=esc(preg_replace('~([?&](?:password|pass|token|key|username|user)=)[^&]*~i','$1[hidden]',(string)($s['url']??'')))?></small>
<div class="actions"><form method="post"><input type="hidden" name="csrf" value="<?=csrf()?>"><input type="hidden" name="id" value="<?=esc($s['id'])?>"><button name="action" value="toggle_epg"><?=$epgDisabled?'Enable':'Disable'?></button><button name="action" value="delete_epg" class="danger" data-confirm="Delete this EPG source?">Delete</button></form></div>
<details class="item"><summary>Edit EPG source</summary><form method="post" class="stack"><input type="hidden" name="csrf" value="<?=csrf()?>"><input type="hidden" name="id" value="<?=esc($s['id'])?>"><label>Name<input name="name" required value="<?=esc($s['name'])?>"></label><label>XMLTV URL<input name="url" type="url" required value="<?=esc($s['url'])?>" <?=!empty($s['xt_source_id'])?'readonly':''?>></label><?php if(!empty($s['xt_source_id'])):?><small>Change this provider URL through its Xtream playlist source.</small><?php endif;?><button name="action" value="edit_epg">Save changes</button></form></details>
</div></details>
<?php endforeach;?></details>
<div class="actions"><button type="button" id="epg-status-check">Refresh delivery status</button></div>
<details class="item"><summary>EPG preset catalog · <?=count(epg_presets())?> feeds</summary>
<p class="muted">Select only the guides you need. Existing EPG sources are preserved, duplicate URLs are skipped. URLs are candidate feeds and may be offline; none is installed automatically.</p>
<form method="post" class="stack"><input type="hidden" name="csrf" value="<?=csrf()?>">
<div style="max-height:340px;overflow:auto;border:1px solid #555;border-radius:8px;padding:10px">
<?php foreach(epg_presets() as $i=>$preset): $installed=false;foreach($w['epg'] as $existing)if(rtrim((string)($existing['url']??''),'/')===rtrim($preset[1],'/')){$installed=true;break;} ?>
<label class="checkline" style="display:flex;gap:8px;margin:7px 0"><input type="checkbox" name="epg_preset[]" value="<?=$i?>" <?=$installed?'disabled':''?>><span><?=esc($preset[0])?> <?=$installed?'· Installed':''?><small class="url" style="display:block;overflow-wrap:anywhere"><?=esc($preset[1])?></small></span></label>
<?php endforeach;?></div><button name="action" value="install_epg_presets">Add selected EPG presets</button></form></details>
<details class="item"><summary>Pending EPG Deletions · <?=count($w['settings']['pending_epg_deletions']??[])?></summary>
<p class="muted">Sources previously flagged after two consecutive failed EPG checks remain here for your review. No automatic deletion. Keep resets the failure counter.</p>
<?php $pendingEpg=$w['settings']['pending_epg_deletions']??[];foreach($pendingEpg as $e):?><div class="item"><b><?=esc($e['name']??'EPG')?></b><p class="muted"><?=esc($e['reason']??'Refresh failed')?></p><div class="actions"><form method="post"><input type="hidden" name="csrf" value="<?=csrf()?>"><input type="hidden" name="id" value="<?=esc($e['id'])?>"><button name="action" value="keep_pending_epg">Keep EPG</button></form><form method="post" ><input type="hidden" name="csrf" value="<?=csrf()?>"><input type="hidden" name="id" value="<?=esc($e['id'])?>"><button class="danger" name="action" value="delete_pending_epg" data-confirm="Delete this EPG source?">Delete</button></form></div></div><?php endforeach;?>
<?php if(!$pendingEpg):?><p class="muted">No EPG sources awaiting deletion approval.</p><?php else:?><form method="post" ><input type="hidden" name="csrf" value="<?=csrf()?>"><input type="hidden" name="confirm" value="DELETE_ALL"><button class="danger" name="action" value="delete_all_pending_epg" data-confirm="Delete all flagged EPG sources?">Delete All Flagged EPG (<?=count($pendingEpg)?>)</button></form><?php endif;?></details>
<details class="item"><summary>Add EPG source</summary><form method="post" class="stack"><input type="hidden" name="csrf" value="<?=csrf()?>"><label>Name<input name="name" required></label><label>XMLTV URL<input name="url" type="url" required></label><button name="action" value="add_epg">Add EPG</button></form></details>
</div></details>
<details class="card step"><summary><b>5 · IPTV player connection</b><small><?= $ready?'Xtream & M3U credentials ready':'Set output credentials' ?></small></summary><div class="step-body">
<div class="output-list"><?php foreach([['M3U Plus URL',$m3uUrl],['Xtream server',$server],['Xtream username',$outputUser],['Xtream password',$outputPassword],['EPG URL',$epgUrl]] as $item):?><div class="output-field"><label><?=esc($item[0])?></label><div class="output-row"><input readonly value="<?=esc($item[1])?>" aria-label="<?=esc($item[0])?>"><button type="button" class="copy-button" data-copy="<?=esc($item[1])?>" <?=$item[1]===''?'disabled':''?>>Copy</button></div></div><?php endforeach;?></div>
<details class="item"><summary>Change output credentials</summary><form method="post" class="stack"><input type="hidden" name="csrf" value="<?=csrf()?>"><label>Output username<input name="output_user" value="<?=esc($outputUser)?>" required></label><label>New password<input type="password" name="output_password" required></label><button name="action" value="output_password">Save credentials</button></form></details>
<p class="muted"><a href="api_diagnostic.php" target="_blank" rel="noopener">Test Xtream API</a></p></div></details>
<details class="card step"><summary><b>6 · Maintenance</b><small>Backup and restore</small></summary><div class="step-body">
<details class="item" open><summary>Pending Deletions · <?=count($w['settings']['pending_source_deletions']??[])?></summary>
<p class="muted">M3U sources that failed two consecutive qualifying full refreshes. Nothing is deleted automatically. Review each source or delete all flagged sources after confirmation.</p>
<?php $deadPending=$w['settings']['pending_source_deletions']??[];foreach($deadPending as $dead):?>
<div class="item"><b><?=esc($dead['name']??'Unknown source')?></b><small> · <?=esc((string)($dead['failures']??2))?> failures</small>
<p class="url"><?=esc($dead['url']??'')?></p><p class="muted"><?=esc($dead['reason']??'Import failed')?></p>
<div class="actions"><form method="post" onsubmit="return confirm('Delete this flagged source and its cached channels?');"><input type="hidden" name="csrf" value="<?=csrf()?>"><input type="hidden" name="id" value="<?=esc($dead['id'])?>"><button class="danger" name="action" value="delete_pending_dead">Delete</button></form>
<form method="post"><input type="hidden" name="csrf" value="<?=csrf()?>"><input type="hidden" name="id" value="<?=esc($dead['id'])?>"><button name="action" value="keep_pending_dead">Keep Source</button></form></div></div>
<?php endforeach;?>
<?php if(!$deadPending):?><p class="muted">No sources awaiting deletion approval.</p><?php else:?>
<form method="post" onsubmit="return confirm('DELETE ALL <?=count($deadPending)?> pending dead sources? This also removes their cached channels.');"><input type="hidden" name="csrf" value="<?=csrf()?>"><input type="hidden" name="confirm" value="DELETE_ALL"><button class="danger" name="action" value="delete_all_pending_dead">Delete All (<?=count($deadPending)?>)</button></form>
<?php endif;?></details>
<details class="item"><summary>Advanced Diagnostics</summary>
<p class="muted">Read-only system checks. Results hide credentials, tokens and private URLs. Tests do not refresh sources or clear caches.</p>
<label>Diagnostic test<select id="system-diag-mode"><option value="all">Run All Diagnostics</option><option value="sources">M3U &amp; source status</option><option value="xtream">Xtream catalogs</option><option value="epg">EPG</option><option value="cache">Cache &amp; scrub</option><option value="refresh">Automatic refresh</option><option value="storage">Storage &amp; PHP</option><option value="player">Player API endpoints</option></select></label>
<div class="actions"><button type="button" id="system-diag-run">Run Diagnostics</button><button type="button" id="system-diag-copy">Copy Results</button><button type="button" id="system-diag-clear">Clear Results</button></div>
<pre id="system-diag-result" style="white-space:pre-wrap;overflow-wrap:anywhere;max-height:420px;overflow:auto" aria-live="polite">No test run yet.</pre>
<details class="item"><summary>Test TV Series API</summary>
<p class="muted">Tests the original Xtream provider directly from this Hostinger server. Checks series categories, catalog, seasons and episodes. Credentials and episode URLs are never displayed or stored in the report. This does not refresh sources or alter caches.</p>
<label>Xtream source<select id="series-diag-source"><option value="">Choose a source</option><?php foreach($w['sources'] as $diagSource)if(($diagSource['type']??'')==='xtream'):?><option value="<?=esc($diagSource['id'])?>"><?=esc($diagSource['name'])?></option><?php endif;?></select></label>
<div class="actions"><button type="button" id="series-diag-run">Test Series API</button><button type="button" id="series-diag-copy">Copy Results</button></div>
<pre id="series-diag-result" style="white-space:pre-wrap;overflow-wrap:anywhere;max-height:360px;overflow:auto" aria-live="polite">No test run yet.</pre>
</details></details>
<details class="item"><summary>Group &amp; channel normalization and sorting</summary>
<p class="muted">Choose exactly how every group and channel name is displayed. Case and separators are standardized in the player output only; stored cache, prefixes, EPG IDs and URLs remain unchanged. Existing saved group rules apply first.</p>
<form method="post" class="stack"><input type="hidden" name="csrf" value="<?=csrf()?>">
<?php $formatOptions=['off'=>'Original (no normalization)','title_space'=>'Group Name','upper_space'=>'GROUP NAME','sentence_space'=>'Sentence case — Group Name','title_dash'=>'Group - Name','upper_dash'=>'GROUP - NAME','sentence_dash'=>'Sentence case with dash — Group - Name','title_pipe'=>'Group | Name','upper_pipe'=>'GROUP | NAME','sentence_pipe'=>'Sentence case with pipe — Group | Name'];foreach(['groups'=>'Group name format','channels'=>'Channel name format'] as $kind=>$title):?>
<label><?=esc($title)?><select name="normalize_<?=esc($kind)?>_style"><?php foreach($formatOptions as $value=>$example):?><option value="<?=esc($value)?>" <?=output_normalization_style($w,$kind)===$value?'selected':''?>><?=esc($example)?></option><?php endforeach;?></select></label>
<?php endforeach;?>
<label>Group order<select name="sort_groups"><option value="default">Default (source order)</option><option value="az" <?=($w['settings']['sort_groups']??'default')==='az'?'selected':''?>>A to Z</option></select></label>
<label>Channel order<select name="sort_channels"><option value="default">Default (source order)</option><option value="az" <?=($w['settings']['sort_channels']??'default')==='az'?'selected':''?>>A to Z</option></select></label>
<button name="action" value="output_organization">Save organization settings</button></form>
<p class="muted">No refresh or scrub required. Reload the playlist in your IPTV player. Channel sorting is applied within groups when group A–Z is selected.</p></details>
<form method="post"><input type="hidden" name="csrf" value="<?=csrf()?>"><button name="action" value="backup">Download backup</button></form><details class="item"><summary>Clear Live TV &amp; scrub cache (testing)</summary>
<p class="muted">Deletes imported Live TV channels and the last completed scrub publication. Keeps sources, prefixes, Xtream movies/series, EPG, pending approvals, and group rules. Live TV may be empty until refreshed and scrubbed again. Download a backup first.</p>
<form method="post" class="stack" onsubmit="return confirm('Clear imported Live TV and published scrub cache? Live TV may be empty until a new refresh and scrub finish.');"><input type="hidden" name="csrf" value="<?=csrf()?>"><input type="hidden" name="confirm_clear" value="CLEAR"><button name="action" value="clear_channel_scrub_cache">Clear Channel &amp; Scrub Cache</button></form></details>
<?php if(auth()['role']==='admin'):?><details class="item"><summary>Restore backup</summary><form method="post" enctype="multipart/form-data" class="stack"><input type="hidden" name="csrf" value="<?=csrf()?>"><input type="file" name="backup" accept=".json" required><button name="action" value="restore" onclick="return confirm('Replace current configuration?')">Restore</button></form></details><?php endif;?></div></details>
</div><footer>Direct stream passthrough · Xtream v9 routing preserved</footer>
<?php endif;?></main><script>
window.mergerCsrf=<?=json_encode(csrf())?>;
window.mergerLastCsrfSync=0;
window.mergerSyncCsrf=async function(){
 const r=await fetch('index.php?csrf_token=1',{credentials:'same-origin',cache:'no-store'});
 if(!r.ok)throw Error('Session expired. Sign in again and reload this page.');
 const data=await r.json();if(!data.csrf)throw Error('Could not refresh form token');
 window.mergerCsrf=data.csrf;
 window.mergerLastCsrfSync=Date.now();
 document.querySelectorAll('input[name="csrf"]').forEach(input=>input.value=data.csrf);
 return data.csrf;
};
window.addEventListener("focus",()=>{if(Date.now()-window.mergerLastCsrfSync>60000)window.mergerSyncCsrf().catch(()=>{});});
document.addEventListener("visibilitychange",()=>{if(!document.hidden)window.mergerSyncCsrf().catch(()=>{});});
</script><script>
(function(){const sel=document.getElementById('source-type'), fields=document.getElementById('xtream-fields');if(!sel||!fields)return;function update(){const xt=sel.value==='xtream';fields.hidden=!xt;fields.querySelectorAll('input').forEach(el=>el.required=xt);}sel.addEventListener('change',update);update();})();

// Read-only system diagnostic controls and copyable JSON report.
document.getElementById('system-diag-run')?.addEventListener('click',async()=>{
 const btn=document.getElementById('system-diag-run'),out=document.getElementById('system-diag-result');btn.disabled=true;out.textContent='Running diagnostics…';
 try{const body=new URLSearchParams({csrf:window.mergerCsrf,mode:document.getElementById('system-diag-mode').value});
 const response=await fetch('system_diagnostic.php',{method:'POST',credentials:'same-origin',cache:'no-store',headers:{'Content-Type':'application/x-www-form-urlencoded'},body});
 const result=await response.json();out.textContent=JSON.stringify(result,null,2);
 }catch(e){out.textContent='Diagnostics failed. Check your login or server logs.';}finally{btn.disabled=false;}
});
async function copyDiagnostic(id,button){const value=document.getElementById(id)?.textContent||'';if(!value||value==='No test run yet.')return;
 try{if(navigator.clipboard&&window.isSecureContext)await navigator.clipboard.writeText(value);else{const el=document.createElement('textarea');el.value=value;document.body.appendChild(el);el.select();if(!document.execCommand('copy'))throw Error('Copy failed');el.remove();}const old=button.textContent;button.textContent='Copied!';setTimeout(()=>button.textContent=old,1500);}catch(e){window.prompt('Select and copy results:',value);}}
document.getElementById('system-diag-copy')?.addEventListener('click',e=>copyDiagnostic('system-diag-result',e.currentTarget));
document.getElementById('system-diag-clear')?.addEventListener('click',()=>document.getElementById('system-diag-result').textContent='No test run yet.');
// Safe provider-side Series API diagnostic. Never display raw URLs or credentials.
document.getElementById('series-diag-run')?.addEventListener('click',async()=>{
 const source=document.getElementById('series-diag-source').value;
 const out=document.getElementById('series-diag-result');const button=document.getElementById('series-diag-run');
 if(!source){out.textContent='Select an Xtream source first.';return;}
 button.disabled=true;out.textContent='Testing provider API…';
 try{
  const body=new URLSearchParams({csrf:window.mergerCsrf,source_id:source});
  const response=await fetch('series_diagnostic.php',{method:'POST',credentials:'same-origin',cache:'no-store',headers:{'Content-Type':'application/x-www-form-urlencoded'},body});
  const result=await response.json();out.textContent=JSON.stringify(result,null,2);
 }catch(e){out.textContent='Diagnostic request failed. Check browser/server logs.';}
 finally{button.disabled=false;}
});
document.getElementById('series-diag-copy')?.addEventListener('click',e=>copyDiagnostic('series-diag-result',e.currentTarget));
// Quick prefix editing: no source expansion, no reimport and no page reload.
document.addEventListener('click',async event=>{
 const button=event.target.closest('.quick-prefix');if(!button)return;
 event.preventDefault();event.stopPropagation();
 if(button.disabled)return;
 const previous=button.dataset.prefix||'';
 const answer=window.prompt('Edit only this source prefix (blank = none):',previous);
 if(answer===null||answer.trim()===previous)return;
 const next=answer.trim();if(next.length>80){window.alert('Prefix must be 80 characters or fewer');return;}
 button.disabled=true;
 try{
  const body=new URLSearchParams({csrf:window.mergerCsrf,ajax:'1',action:'edit_prefix_only',id:button.dataset.sourceId,prefix:next});
  const response=await fetch('index.php',{method:'POST',credentials:'same-origin',cache:'no-store',body});
  const result=await response.json();if(!response.ok||!result.ok)throw Error(result.message||'Could not save prefix');
  button.dataset.prefix=next;button.querySelector('.prefix-value').textContent=next||'None';
  const source=button.closest('details.item');const fullEdit=source?.querySelector('form input[name="prefix"]');if(fullEdit)fullEdit.value=next;
 }catch(error){window.alert('Prefix not saved: '+error.message);}
 finally{button.disabled=false;}
});
document.querySelectorAll('.copy-button').forEach(button=>button.addEventListener('click',async()=>{
 const value=button.dataset.copy;if(!value)return;
 try{if(navigator.clipboard&&window.isSecureContext){await navigator.clipboard.writeText(value);}else{
 const input=button.parentElement.querySelector('input');input.focus();input.select();if(!document.execCommand('copy'))throw Error('Copy not supported');}}
 catch(e){const input=button.parentElement.querySelector('input');input.focus();input.select();return;}
 const old=button.textContent;button.textContent='Copied!';setTimeout(()=>button.textContent=old,1500);
}));
// Only an intentional click on a workflow heading can close its panel.
// Avoid asynchronous <details> toggle handlers: they can fire after an AJAX
// response or a DOM replacement and close the section unexpectedly.
const workflowSteps=[...document.querySelectorAll('.workflow > details.step')];
const savedStep=sessionStorage.getItem('merger-step');
if(savedStep!==null && workflowSteps[Number(savedStep)]){
 workflowSteps.forEach((step,i)=>{step.open=i===Number(savedStep);});
}
workflowSteps.forEach((step,i)=>{
 const heading=step.querySelector(':scope > summary');
 heading?.addEventListener('click',()=>{
  // Native <details> handles the clicked section. Close other sections only
  // when the user is opening this one, not on form submits or status updates.
  if(!step.open){
   workflowSteps.forEach((other,j)=>{if(j!==i)other.open=false;});
   sessionStorage.setItem('merger-step',String(i));
  }else{
   sessionStorage.removeItem('merger-step');
  }
 });
});
const groupSearch=document.getElementById('group-search');
const groupForm=document.getElementById('group-merge-form');
const groupDropdown=document.getElementById('group-dropdown');
const groupPicker=document.getElementById('group-picker');
function updateGroupPicker(){
 if(!groupForm)return;
 const selected=[...groupForm.querySelectorAll('input[name="groups[]"]:checked')].map(x=>x.value);
 const target=document.getElementById('group-target').value;
 const label=document.getElementById('group-selection-label');
 label.textContent=selected.length===1?selected[0]:selected.length?selected.length+' groups selected':'Select provider groups';
 document.getElementById('group-selection-count').textContent=selected.length+' selected';
 document.getElementById('group-preview').textContent=selected.length?selected.join(' + ')+' → '+(target||'Choose destination'):'Select source groups first';
 const destinationButtons=document.querySelectorAll('#destination-list .destination-option');
 destinationButtons.forEach(button=>{button.disabled=selected.includes(button.dataset.value);});
 if(selected.includes(target)){
  document.getElementById('group-target').value='';
  document.getElementById('destination-label').textContent='Choose destination';
  document.getElementById('destination-selected-count').textContent='Tap to search';
 }
}
if(groupSearch){
 groupSearch.addEventListener('input',()=>{
  const query=groupSearch.value.trim().toLocaleLowerCase();let shown=0;
  groupPicker.querySelectorAll('.group-choice').forEach(row=>{
   const match=row.dataset.search.includes(query);row.hidden=!match;if(match)shown++;
  });
  document.getElementById('group-match-count').textContent=shown+' matching groups';
 });
 document.getElementById('group-picker-done').addEventListener('click',()=>{groupDropdown.open=false;});
 groupForm.addEventListener('change',updateGroupPicker);
 updateGroupPicker();
}
const destinationDropdown=document.getElementById('destination-dropdown');
const destinationSearch=document.getElementById('destination-search');
const destinationList=document.getElementById('destination-list');
function filterDestinations(){
 const query=destinationSearch.value.trim().toLocaleLowerCase();let shown=0;
 destinationList.querySelectorAll('.destination-option').forEach(button=>{
  const match=button.dataset.search.includes(query)&&!button.disabled;
  button.hidden=!match;if(match)shown++;
 });
 document.getElementById('destination-match-count').textContent=shown+' matching groups';
}
if(destinationDropdown){
 destinationDropdown.addEventListener('toggle',()=>{if(destinationDropdown.open){filterDestinations();}});
 destinationSearch.addEventListener('input',filterDestinations);
 destinationList.addEventListener('click',event=>{
  const button=event.target.closest('.destination-option');if(!button||button.disabled)return;
  document.getElementById('group-target').value=button.dataset.value;
  document.getElementById('destination-label').textContent=button.dataset.value;
  document.getElementById('destination-selected-count').textContent='Selected';
  destinationDropdown.open=false;updateGroupPicker();
 });
 document.getElementById('destination-clear').addEventListener('click',()=>{
  document.getElementById('group-target').value='';
  document.getElementById('destination-label').textContent='Choose destination';
  document.getElementById('destination-selected-count').textContent='Tap to search';
  destinationSearch.value='';filterDestinations();updateGroupPicker();destinationDropdown.open=false;
 });
 groupForm.addEventListener('change',filterDestinations);
}
// Submit dashboard changes without navigating away or resetting the scroll position.
// Only the affected workflow section is refreshed, with the same expanded controls.
const workflow=document.querySelector('.workflow');
if(workflow){
 const toast=document.createElement('div');toast.className='ajax-toast';toast.setAttribute('role','status');toast.setAttribute('aria-live','polite');document.body.appendChild(toast);
 let toastTimer;
 function notice(message,error=false){toast.textContent=message;toast.classList.toggle('error',error);toast.classList.add('visible');clearTimeout(toastTimer);toastTimer=setTimeout(()=>toast.classList.remove('visible'),2500);}
 function rememberDetails(root){return [...root.querySelectorAll('details')].map((d,i)=>d.open?i:null).filter(x=>x!==null);}
 function restoreDetails(root,indices){const nodes=[...root.querySelectorAll('details')];nodes.forEach((d,i)=>d.open=indices.includes(i));}
 workflow.addEventListener('submit',async event=>{
  const form=event.target;if(!(form instanceof HTMLFormElement)||form.method.toLowerCase()!=='post'||form.enctype==='multipart/form-data')return;
  const submitter=event.submitter;const action=submitter?.value||'';
  if(['backup','restore','logout'].includes(action))return;
  event.preventDefault();
  if(submitter?.dataset.confirm && !confirm(submitter.dataset.confirm))return;
  const step=form.closest('.step');const index=step?[...workflow.children].indexOf(step):-1;
  const open=step?rememberDetails(step):[];
  const previousY=window.scrollY;
  const beforeTop=step?.getBoundingClientRect().top??0;
  const fd=new FormData(form);if(submitter?.name)fd.set(submitter.name,submitter.value);fd.set('ajax','1');
  if(submitter)submitter.disabled=true;
  try{
   await window.mergerSyncCsrf();
   fd.set('csrf',window.mergerCsrf);
   const response=await fetch('index.php',{method:'POST',body:fd,credentials:'same-origin',cache:'no-store'});
   const raw=await response.text();let result;
   try{result=JSON.parse(raw);}catch(_){throw Error('Server returned an invalid response (HTTP '+response.status+'). Check PHP error logs.');}
   if(!response.ok||!result.ok)throw Error(result.message||result.error||'Save failed');
   notice(result.message||'Saved');if(action==='refresh')refreshTick();
   if(['scrub_start','scrub_stop'].includes(action) && typeof scrubTick==='function') scrubTick();
   // Update only the changed section, never the entire page.
   if(step&&['add_source','add_epg','delete_source','delete_epg','delete_pending_epg','delete_all_pending_epg','keep_pending_epg','toggle_source','toggle_epg','epg_refresh','output_password'].includes(action)){
    const page=await fetch('index.php',{credentials:'same-origin',cache:'no-store'});
    const doc=new DOMParser().parseFromString(await page.text(),'text/html');
    if(!doc.querySelector('.workflow'))throw Error('Session expired or dashboard unavailable. Sign in again; your action may have saved.');
    const fresh=doc.querySelectorAll('.workflow > .step')[index];
    if(!fresh)throw Error('Saved, but the EPG list could not reload. Refresh the page.');
    if(fresh){restoreDetails(fresh,open);fresh.open=step.open;step.replaceWith(fresh);
     const shift=fresh.getBoundingClientRect().top-beforeTop;
     window.scrollTo({top:Math.max(0,previousY+shift),behavior:'instant'});
     // Reinitialize controls after a section replacement without navigating.
    }
   }
  }catch(err){notice(err.message||'Request failed',true);}
  finally{if(submitter&&submitter.isConnected)submitter.disabled=false;}
 });
}
// Lightweight polling: no full-page HTML fetch or section replacement.
let refreshBusy=false;
async function refreshTick(){
 if(refreshBusy||document.hidden)return;refreshBusy=true;
 try{
  const r=await fetch('refresh_ajax.php',{method:'POST',credentials:'same-origin',cache:'no-store',headers:{'Content-Type':'application/x-www-form-urlencoded'},body:new URLSearchParams({csrf:window.mergerCsrf,action:'step'})});
  const x=await r.json();if(!r.ok||x.error)throw Error(x.error||'HTTP '+r.status);
  const total=x.tasks||0;const categoryTotal=x.categories||0;
  const pct=total?Math.min(100,Math.round(((Math.max(0,x.task-1)+(categoryTotal?x.category/categoryTotal:0))/total)*100)):0;
  document.getElementById('refresh-stage').textContent=x.stage+' · '+(total?x.task+'/'+total+' tasks':'');
  document.getElementById('refresh-progress').value=x.state==='done'||x.state==='done_with_errors'?100:pct;
  const counts={live:x.items?.live||0,movies:x.items?.movies||0,series:x.items?.series||0};
  if(x.state==='running'&&x.current_kind){counts[x.current_kind==='m3u'?'live':x.current_kind]=(counts[x.current_kind==='m3u'?'live':x.current_kind]||0)+(x.current_count||0);}
  document.getElementById('refresh-counts').textContent='Found so far: Live '+counts.live+' · Movies '+counts.movies+' · Shows '+counts.series+(x.state==='running'?' · '+(x.current_source||'')+' / '+(x.current_kind||'')+(x.current_category?' / '+x.current_category:''):'');
  document.getElementById('refresh-errors').textContent=(x.errors||[]).slice(-1).join('');
 }catch(e){document.getElementById('refresh-errors').textContent='Progress unavailable: '+e.message;}
 finally{refreshBusy=false;}
}
document.getElementById('refresh-force-stop')?.addEventListener('click',async()=>{
 const btn=document.getElementById('refresh-force-stop');btn.disabled=true;
 try{const r=await fetch('refresh_ajax.php',{method:'POST',credentials:'same-origin',cache:'no-store',headers:{'Content-Type':'application/x-www-form-urlencoded'},body:new URLSearchParams({csrf:window.mergerCsrf,action:'stop'})});const x=await r.json();if(!r.ok||x.error)throw Error(x.error||'Stop failed');document.getElementById('refresh-stage').textContent=x.stage||'Stop requested';}
 catch(e){document.getElementById('refresh-errors').textContent=e.message;}
 finally{btn.disabled=false;}
});
refreshTick();setInterval(refreshTick,2500);
// Automatically advance the scrubber while this dashboard is open.
// Short requests avoid full page reloads and preserve scroll position.
const scrubStatus=document.getElementById('scrub-status');
if(scrubStatus){
 let busy=false;
 async function scrubTick(){
  if(busy||document.hidden)return;
  busy=true;
  try{
   const res=await fetch('scrub_ajax.php',{method:'POST',headers:{'Content-Type':'application/x-www-form-urlencoded'},body:new URLSearchParams({csrf:window.mergerCsrf,action:'step'}),credentials:'same-origin',cache:'no-store'});
   if(!res.ok)throw Error('HTTP '+res.status);
   const x=await res.json();if(x.error)throw Error(x.error);
   const pct=x.total?Math.round(x.checked*100/x.total):0;
   scrubStatus.textContent=x.state.toUpperCase()+' · '+x.checked+' / '+x.total+' checked · '+pct+'% · ETA '+(x.eta_seconds===null?'—':Math.ceil(x.eta_seconds/60)+' min');
   document.getElementById('scrub-progress').value=pct;
   document.getElementById('scrub-errors').textContent=x.errors+' streams flagged for review. HEAD checks may be rejected by working providers.';
  }catch(e){scrubStatus.textContent='Scrubber update failed: '+e.message;}
  finally{busy=false;}
 }
 scrubTick();setInterval(scrubTick,4500);
}
</script><?php if($logged && time()-(int)($w['settings']['discovery_last']??0)>=86400):?>
<script>window.addEventListener('load',async()=>{try{await window.mergerSyncCsrf();}catch(_){return;}const f=new URLSearchParams({csrf:window.mergerCsrf,action:'discover_sources'});fetch('index.php',{method:'POST',body:f,credentials:'same-origin',cache:'no-store'}).catch(()=>{});});</script>
<?php endif;?>
<script>
(function(){
const target=document.getElementById('epg-delivery-live'),btn=document.getElementById('epg-status-check');if(!target)return;
async function refreshDelivery(){try{const r=await fetch('epg_delivery_status.php',{credentials:'same-origin',cache:'no-store'});const raw=await r.text();let x;try{x=JSON.parse(raw);}catch(_){throw Error('Server returned HTML (HTTP '+r.status+')');}if(!r.ok||!x.ok)throw Error(x.error||'Status unavailable');const d=x.delivery||{};const at=d.last_at?new Date(d.last_at*1000).toLocaleString():'Never';const programmes=d.last_programmes_at?new Date(d.last_programmes_at*1000).toLocaleString():'Never';const labels={merged_served:'Combined XMLTV guide served',request_received:'Xtream EPG request received',unknown_stream:'EPG stream ID not found',unmapped_stream:'Cannot map channel to provider',redirect_sent:'Guide URL passed to IPTV player',redirect_failed:'Guide redirect failed',no_source:'No active guide',listings_returned:'Xtream programs returned',empty_response:'Xtream returned no programs',provider_error:'Xtream provider request failed',no_provider:'No Xtream EPG for channel'};target.textContent='Active passthrough: '+(x.active_source?.name||'None')+' · Enabled sources: '+x.enabled_count+' · Last activity: '+(labels[d.state]||'Waiting for IPTV player')+' · '+at+' · XMLTV requests: '+(d.xmltv_requests||0)+' · Xtream EPG requests: '+(d.xtream_requests||0)+' · Xtream results/errors: '+(d.xtream_result_requests||0)+' · Last confirmed Xtream listings: '+(d.last_programmes_count??'None')+' ('+programmes+')'+' · Smart EPG: '+(x.smart_refresh?.job?.state||'idle')+' · Coverage (next 24h): '+(x.smart_refresh?.coverage?.percent??'Not measured')+'% ('+(x.smart_refresh?.coverage?.covered??0)+'/'+(x.smart_refresh?.coverage?.total??0)+' channels)'+' · Last combined update: '+(x.smart_refresh?.last_success?new Date(x.smart_refresh.last_success*1000).toLocaleString():'Never')+' · Next age check: '+(x.smart_refresh?.last_success?new Date(x.smart_refresh.next_check*1000).toLocaleString():'After first build')+' · Refresh reason: '+(x.smart_refresh?.reason||'Guide is fresh');}catch(e){target.textContent='EPG delivery status: '+e.message;}}
btn?.addEventListener('click',refreshDelivery);refreshDelivery();setInterval(refreshDelivery,15000);
})();;
</script>
</body></html>

<script>
(()=>{
const start=document.getElementById('epg-build-start'),resume=document.getElementById('epg-build-resume'),status=document.getElementById('epg-build-progress'),bar=document.getElementById('epg-build-bar');if(!start)return;
let busy=false,active=false;
async function call(action){const body=new URLSearchParams({action,csrf:window.mergerCsrf});const r=await fetch('epg_ajax.php',{method:'POST',credentials:'same-origin',body,cache:'no-store'});const raw=await r.text();let x;try{x=JSON.parse(raw);}catch(_){throw Error('Server returned HTML instead of JSON (HTTP '+r.status+'). Check Hostinger PHP errors or sign in again.');}if(!r.ok||!x.ok)throw Error(x.error||'EPG build failed (HTTP '+r.status+')');return x;}
function display(x){const j=x.job||{},n=j.total||0;bar.value=n?Math.round(100*(j.index||0)/n):0;status.textContent='State: '+(j.state||'idle')+' · '+(j.index||0)+' / '+n+' sources · '+(j.ok||0)+' succeeded · '+(j.failed||0)+' failed'+(j.counts?' · '+j.counts.programmes+' programmes, '+j.counts.channels+' channels':'')+(j.publish_index!=null&&j.state==='running'?' · Publishing '+Math.min(j.publish_index,j.total||0)+' / '+(j.total||0)+' fragments'+(j.publish_index>(j.total||0)?' · compressing':''):'')+(x.cached?' · Cached guide available':' · No merged guide yet')+(j.error?' · '+j.error:'');if(j.state==='completed'||j.state==='failed')active=false;}
async function loop(){if(busy||!active||document.hidden)return;busy=true;try{const x=await call('build_step');display(x);}catch(e){active=false;status.textContent='Build paused: '+e.message;}finally{busy=false;}if(active)setTimeout(loop,400);}
start.addEventListener('click',async()=>{if(busy)return;busy=true;try{display(await call('build_start'));active=true;}catch(e){status.textContent=e.message;}finally{busy=false;}if(active)loop();});
resume.addEventListener('click',async()=>{if(busy)return;busy=true;try{const x=await call('build_status');display(x);active=x.job?.state==='running';}catch(e){status.textContent=e.message;}finally{busy=false;}if(active)loop();});
document.addEventListener('visibilitychange',()=>{if(!document.hidden&&active)loop();});
call('build_status').then(x=>{display(x);if(x.job?.state==='running'){active=true;loop();}}).catch(e=>status.textContent=e.message);
})();
</script>
