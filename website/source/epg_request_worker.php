<?php
// A player XMLTV request can advance a persisted EPG build without a dashboard.
// It cannot guarantee completion without subsequent requests or a scheduler.
function epg_request_worker(string $ws):void {
 if(!function_exists('fastcgi_finish_request'))return; // Never hold an IPTV response open for large downloads.
 register_shutdown_function(static function()use($ws):void {
  // Finish the response before any network I/O or XML parsing.
  if(function_exists('fastcgi_finish_request'))@fastcgi_finish_request();
  @ignore_user_abort(true);
  $lock=@fopen(STORE.'/epg-build-'.hash('sha256',$ws).'.lock','c');
  if(!$lock)return;
  if(!@flock($lock,LOCK_EX|LOCK_NB)){fclose($lock);return;}
  try{
   $d=data();if(!isset($d['workspaces'][$ws]))return;
   $w=&$d['workspaces'][$ws];
   $job=$w['settings']['epg_job']??[];
   $state=(string)($job['state']??'idle');
   $reason=epg_refresh_reason($w,$ws);
   // Cooldown prevents repeated failed or low-coverage builds from looping.
   if($state==='failed'&&time()-(int)($job['updated']??0)<3600)return;
   if($state!=='running'){
    if($reason==='')return;
    $w['settings']['epg_refresh_reason']=$reason;
    try{epg_queue_start($w);}catch(Throwable $e){return;}
   }
   epg_queue_step($w,$ws);save($d);
  }catch(Throwable $e){error_log('EPG request worker: '.substr($e->getMessage(),0,120));}
  finally{@flock($lock,LOCK_UN);fclose($lock);}
 });
}
