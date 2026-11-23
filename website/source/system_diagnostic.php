<?php
require __DIR__.'/lib.php';
start_dashboard_session();init();
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');
header('X-Content-Type-Options: nosniff');
function diag_out($v,int $status=200):void {http_response_code($status);echo json_encode($v,JSON_PRETTY_PRINT|JSON_INVALID_UTF8_SUBSTITUTE|JSON_UNESCAPED_SLASHES);exit;}
try{
 require_auth();if($_SERVER['REQUEST_METHOD']!=='POST')diag_out(['ok'=>false,'error'=>'POST required'],405);check_csrf();
 $mode=(string)($_POST['mode']??'all');$allowed=['all','sources','xtream','epg','cache','refresh','storage','player'];if(!in_array($mode,$allowed,true))diag_out(['ok'=>false,'error'=>'Invalid test'],400);
 $d=data();$w=workspace($d,wsid());$checks=[];
 $want=static fn(string $name):bool=>$mode==='all'||$mode===$name;
 if($want('sources')){
  $src=$w['sources']??[];$enabled=array_values(array_filter($src,fn($s)=>!empty($s['enabled'])));
  $failed=[];foreach($enabled as $s){$results=$s['results']??[];foreach($results as $kind=>$v){if(($v['status']??'')==='error'||($v['status']??'')==='failed')$failed[]=['source'=>(string)($s['name']??'Unnamed'),'section'=>(string)$kind];}}
  $checks['sources']=['status'=>$failed?'warning':'ok','configured'=>count($src),'enabled'=>count($enabled),'xtream'=>count(array_filter($src,fn($s)=>($s['type']??'')==='xtream')),'m3u'=>count(array_filter($src,fn($s)=>($s['type']??'m3u')==='m3u')),'recorded_failures'=>array_slice($failed,0,25),'note'=>'Read-only status check; does not download or test every playlist'];
 }
 if($want('xtream')){
  $xt=array_values(array_filter($w['sources']??[],fn($s)=>($s['type']??'')==='xtream'&&!empty($s['enabled'])));
  $checks['xtream']=['status'=>$xt?'ok':'not_configured','enabled_sources'=>count($xt),'cached_movies'=>count($w['movies']??[]),'cached_series'=>count($w['series']??[]),'note'=>'Catalog/cache check only. Use Test TV Series API for provider seasons and episodes'];
 }
 if($want('epg')){
  $epg=$w['epg']??[];$checks['epg']=['status'=>count($epg)?'ok':'not_configured','configured'=>count($epg),'enabled'=>count(array_filter($epg,fn($s)=>!empty($s['enabled']))),'cached_xml_exists'=>is_file(STORE.'/epg.xml'),'cached_xml_bytes'=>is_file(STORE.'/epg.xml')?filesize(STORE.'/epg.xml'):0,'note'=>'Does not download or expose EPG URLs'];
 }
 if($want('cache')){
  $imported=$w['channels']??[];$published=$w['published_channels']??[];$active=array_fill_keys(array_column($w['sources']??[],'id'),true);$orphan=0;foreach($imported as $c)if(!isset($active[$c['source_id']??'']))$orphan++;
  $checks['cache']=['status'=>$orphan?'warning':'ok','imported_live'=>count($imported),'published_live'=>count($published),'orphaned_imported_channels'=>$orphan,'scrub_state'=>$w['settings']['scrub']['state']??'idle','last_scrub_completed'=>$w['settings']['scrub_last_completed']??null,'note'=>'Read-only cache consistency check'];
 }
 if($want('refresh')){
  $j=refresh_job_read();$checks['refresh']=['status'=>($j['state']??'idle')==='running'?'running':'ok','job_state'=>$j['state']??'idle','job_stage'=>$j['stage']??null,'last_refresh'=>$w['settings']['last_refresh']??null,'player_12h_enabled'=>(bool)($w['settings']['player_refresh_12h']??true),'dashboard_24h_enabled'=>(bool)($w['settings']['auto_refresh_daily']??true),'auto_scrub'=>(bool)($w['settings']['scrub_auto']??true),'note'=>'Does not start or advance refresh jobs'];
 }
 if($want('storage')){
  $checks['storage']=['status'=>is_writable(STORE)?'ok':'failed','storage_writable'=>is_writable(STORE),'data_file_exists'=>is_file(STORE.'/data.json'),'data_file_writable'=>is_file(STORE.'/data.json')?is_writable(STORE.'/data.json'):false,'php_curl'=>extension_loaded('curl'),'php_json'=>extension_loaded('json'),'php_dom'=>extension_loaded('dom')];
 }
 if($want('player')){
  $checks['player']=['status'=>'ok','player_api_exists'=>is_file(__DIR__.'/player_api.php'),'m3u_endpoint_exists'=>is_file(__DIR__.'/get.php'),'live_endpoint_exists'=>is_file(__DIR__.'/live.php'),'media_endpoint_exists'=>is_file(__DIR__.'/media.php'),'note'=>'Checks endpoint files, not authenticated HTTP playback'];
 }
 diag_out(['ok'=>true,'report_type'=>$mode,'generated_at'=>gmdate('c'),'checks'=>$checks]);
}catch(Throwable $e){diag_out(['ok'=>false,'error'=>'Diagnostic could not complete. Verify your login and server logs.'],400);}
