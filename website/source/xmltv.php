<?php
require __DIR__.'/lib.php';init();
try{
 $w=output_workspace();$d=data();$ws=feed_auth($d);if($ws===null)throw new Exception('Unauthorized');
 require_once __DIR__.'/epg_request_worker.php';epg_request_worker($ws);
 // Prefer the MultiClient-built cache when validated; retain legacy PHP guide as fallback.
 $multiBase=STORE.'/epg-multiclient-'.hash('sha256',$ws).'.xml';
 $multiMetaFile=$multiBase.'.json';
 if(is_file($multiMetaFile)&&is_file($multiBase.'.gz')){
  $multiMeta=json_decode((string)@file_get_contents($multiMetaFile),true);
  $multiSize=@filesize($multiBase.'.gz');
  if(is_array($multiMeta)&&($multiMeta['channels']??0)>0&&($multiMeta['programmes']??0)>0&&$multiSize>1024&&$multiSize===(int)($multiMeta['bytes']??0)){
   $multiFile=$multiBase.'.gz';
   $fp=@fopen($multiFile,'rb');$magic=$fp?fread($fp,2):'';if($fp)fclose($fp);
   if($magic===chr(31).chr(139)){
    epg_delivery_event('xmltv','multiclient_served');
    header('Content-Type: application/xml; charset=utf-8');
    header('Content-Encoding: gzip');
    header('Vary: Accept-Encoding');
    header('Content-Disposition: attachment; filename="xmltv.xml.gz"');
    header('Cache-Control: private, max-age=300');
    header('Content-Length: '.$multiSize);
    readfile($multiFile);exit;
   }
  }
 }
 $path=epg_output_path($ws);$gzip=$path.'.gz';
 if(epg_published_guide($d['workspaces'][$ws]??[],$ws)){
  epg_delivery_event('xmltv','merged_served');header('Content-Type: application/xml; charset=utf-8');header('Content-Encoding: gzip');header('Vary: Accept-Encoding');header('Content-Disposition: attachment; filename="xmltv.xml.gz"');header('Cache-Control: private, max-age=300');header('Content-Length: '.filesize($gzip));readfile($gzip);exit;
 }
 epg_delivery_event('xmltv','merged_missing');http_response_code(503);header('Content-Type: text/plain; charset=utf-8');header('Retry-After: 300');exit('Combined EPG not ready or invalid. Run Build / refresh combined EPG in the dashboard.');
}catch(Throwable $e){epg_delivery_event('xmltv','redirect_failed');http_response_code(401);header('Content-Type: text/plain');echo 'EPG unavailable or unauthorized';}
