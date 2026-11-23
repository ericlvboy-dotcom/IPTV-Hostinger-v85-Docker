<?php
require __DIR__.'/lib.php';
start_dashboard_session();init();
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');
header('X-Content-Type-Options: nosniff');
function diagnostic_reply(array $payload,int $status=200):void {http_response_code($status);echo json_encode($payload,JSON_INVALID_UTF8_SUBSTITUTE|JSON_UNESCAPED_SLASHES);exit;}
function diagnostic_error(Throwable $e):string {
 $message=$e->getMessage();
 // Never expose credentials, provider URLs, query strings or response bodies.
 if(str_contains($message,'credentials missing'))return 'Source credentials missing';
 if(str_contains($message,'invalid JSON'))return 'Provider returned invalid JSON';
 if(str_contains($message,'no episodes'))return 'Provider response has no episodes array';
 if(str_contains($message,'HTTP 401')||str_contains($message,'HTTP 403'))return 'Provider rejected API request (HTTP 401/403)';
 if(str_contains($message,'HTTP 404'))return 'Provider API endpoint not found (HTTP 404)';
 if(str_contains($message,'timed out')||str_contains($message,'timeout'))return 'Provider request timed out';
 return 'Provider request failed (details hidden to protect credentials)';
}
// Inspect one provider response without returning raw content, URLs or credentials.
function series_http_probe(array $source,string $seriesId):array {
 $base=xt_source_base((string)$source['url']);
 $host=(string)(parse_url($base,PHP_URL_HOST)??'');
 if($host==='')return ['status'=>'failed','reason'=>'Invalid provider host'];
 if(filter_var($host,FILTER_VALIDATE_IP)){$ips=[$host];}else{$ips=gethostbynamel($host)?:[];}
 if(!$ips)return ['status'=>'failed','reason'=>'Provider DNS lookup failed'];
 foreach($ips as $ip)if(!filter_var($ip,FILTER_VALIDATE_IP,FILTER_FLAG_NO_PRIV_RANGE|FILTER_FLAG_NO_RES_RANGE))return ['status'=>'failed','reason'=>'Unsafe provider address blocked'];
 $user=(string)($source['xt_user']??'');$pass=output_decrypt((string)($source['xt_pass_enc']??''));
 if($user===''||$pass==='')return ['status'=>'failed','reason'=>'Source credentials missing'];
 $url=$base.'/player_api.php?'.http_build_query(['username'=>$user,'password'=>$pass,'action'=>'get_series_info','series_id'=>$seriesId],'','&',PHP_QUERY_RFC3986);
 $body='';$limit=25000000;$overflow=false;
 $ch=curl_init($url);
 curl_setopt_array($ch,[CURLOPT_FOLLOWLOCATION=>false,CURLOPT_TIMEOUT=>40,CURLOPT_CONNECTTIMEOUT=>10,CURLOPT_RETURNTRANSFER=>false,CURLOPT_PROTOCOLS=>CURLPROTO_HTTP|CURLPROTO_HTTPS,CURLOPT_USERAGENT=>'IPTV-Smart-Merger-Hostinger/0.1',CURLOPT_WRITEFUNCTION=>static function($handle,$chunk)use(&$body,$limit,&$overflow){if(strlen($body)+strlen($chunk)>$limit){$overflow=true;return 0;}$body.=$chunk;return strlen($chunk);}]);
 $ok=curl_exec($ch);$http=(int)curl_getinfo($ch,CURLINFO_RESPONSE_CODE);$type=(string)(curl_getinfo($ch,CURLINFO_CONTENT_TYPE)?:'');$errno=curl_errno($ch);curl_close($ch);
 $decoded=json_decode($body,true,512,JSON_INVALID_UTF8_SUBSTITUTE);$jsonError=json_last_error_msg();
 $trim=ltrim($body);$kind='other';
 if($trim==='')$kind='empty';elseif(preg_match('/^<(?:!doctype\s+html|html|head|body|\?xml)/i',$trim))$kind='html_or_xml';elseif(str_starts_with($trim,'{')||str_starts_with($trim,'['))$kind='json_like';elseif(str_starts_with($trim,'\x1f\x8b'))$kind='gzip_binary';elseif(str_starts_with($trim,'#EXTM3U'))$kind='m3u_playlist';
 // This is a synthetic preview of the response structure, never provider-supplied text.
 $preview=['html_or_xml'=>'<HTML/XML document>','json_like'=>'<JSON-shaped response>','empty'=>'<empty response>','gzip_binary'=>'<compressed binary>','m3u_playlist'=>'<M3U playlist>','other'=>'<unrecognized response format>'][$kind];
 $result=['http_status'=>$http,'content_type'=>substr(preg_replace('/[^a-zA-Z0-9\/;=+._ -]/','',$type),0,100),'response_bytes'=>strlen($body),'response_kind'=>$kind,'safe_preview'=>$preview,'valid_json'=>json_last_error()===JSON_ERROR_NONE,'json_top_level_type'=>get_debug_type($decoded),'json_error'=>json_last_error()===JSON_ERROR_NONE?null:$jsonError,'curl_error_code'=>$errno,'size_limit_exceeded'=>$overflow];
 if(is_array($decoded)){$result['top_level_keys']=array_slice(array_map('strval',array_keys($decoded)),0,12);$result['episodes_present']=isset($decoded['episodes']);}
 if(!$ok)$result['transport_status']=$overflow?'response_exceeded_25mb_limit':'request_failed';
 return $result;
}
try {
 if($_SERVER['REQUEST_METHOD']!=='POST')diagnostic_reply(['ok'=>false,'error'=>'POST required'],405);
 require_auth();check_csrf();
 $d=data();$w=workspace($d,wsid());$id=(string)($_POST['source_id']??'');
 $source=null;foreach($w['sources']??[] as $candidate)if(($candidate['id']??'')===$id&&($candidate['type']??'')==='xtream'){$source=$candidate;break;}
 if(!$source)diagnostic_reply(['ok'=>false,'error'=>'Xtream source not found'],404);
 $result=['ok'=>true,'source_name'=>$source['name']??'Xtream source','checks'=>[]];
 foreach(['get_series_categories','get_series'] as $action){
  try{
   $data=xt_api($source,$action);
   $result['checks'][$action]=['status'=>'ok','count'=>count($data)];
   if($action==='get_series')$series=$data;
  }catch(Throwable $e){$result['checks'][$action]=['status'=>'failed','reason'=>diagnostic_error($e)];}
 }
 $providerId='';
 foreach(($w['series']??[]) as $entry){if(($entry['source_id']??'')===$id&&isset($entry['provider_id'])){$providerId=(string)$entry['provider_id'];break;}}
 if($providerId===''&&isset($series)&&is_array($series))foreach($series as $entry){if(is_array($entry)&&isset($entry['series_id'])){$providerId=(string)$entry['series_id'];break;}}
 if($providerId==='')$result['checks']['get_series_info']=['status'=>'not_tested','reason'=>'No series ID available. Refresh this source’s series catalog.'];
 else{
  try{
   $info=xt_api($source,'get_series_info',['series_id'=>$providerId]);
   $eps=$info['episodes']??null;$seasonCount=0;$episodeCount=0;$firstEpisodeKeys=[];
   if(is_array($eps))foreach($eps as $season=>$items){if(!is_array($items))continue;$seasonCount++;$episodeCount+=count($items);if(!$firstEpisodeKeys&&isset($items[0])&&is_array($items[0]))$firstEpisodeKeys=array_slice(array_keys($items[0]),0,20);}
   $result['checks']['get_series_info']=['status'=>is_array($eps)?'ok':'unexpected_structure','has_info'=>isset($info['info'])&&is_array($info['info']),'has_seasons'=>isset($info['seasons'])&&is_array($info['seasons']),'episodes_type'=>get_debug_type($eps),'season_count'=>$seasonCount,'episode_count'=>$episodeCount,'top_level_keys'=>array_slice(array_keys($info),0,20),'first_episode_fields'=>$firstEpisodeKeys];
  }catch(Throwable $e){$result['checks']['get_series_info']=['status'=>'failed','reason'=>diagnostic_error($e)];}
  try{$result['checks']['get_series_info_http_diagnostic']=series_http_probe($source,$providerId);}catch(Throwable $e){$result['checks']['get_series_info_http_diagnostic']=['status'=>'failed','reason'=>'HTTP diagnostic unavailable'];}
 }
 diagnostic_reply($result);
}catch(Throwable $e){diagnostic_reply(['ok'=>false,'error'=>'Diagnostic request failed. Check your login and try again.'],400);}
