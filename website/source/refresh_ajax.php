<?php
// Return JSON rather than PHP warning markup on fatal refresh failures.
ini_set('display_errors','0');
ini_set('log_errors','1');
ob_start();
register_shutdown_function(static function ():void {
    $error=error_get_last();
    if($error && in_array($error['type'],[E_ERROR,E_PARSE,E_CORE_ERROR,E_COMPILE_ERROR,E_USER_ERROR],true)){
        while(ob_get_level()>0)ob_end_clean();
        http_response_code(500);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['error'=>'Refresh server error; check PHP container logs']);
    }
});

require __DIR__.'/lib.php';start_dashboard_session();init();header('Content-Type: application/json; charset=utf-8');header('Cache-Control: no-store');
if(!auth()){http_response_code(401);echo json_encode(['error'=>'Sign in required']);exit;}
if($_SERVER['REQUEST_METHOD']!=='POST'||!hash_equals(csrf(),(string)($_POST['csrf']??''))){http_response_code(403);echo json_encode(['error'=>'Invalid request']);exit;}
try{$d=data();$w=&workspace($d,wsid());$j=refresh_job_read();
// Dashboard-driven daily refresh: no Hostinger cron needed. Only start on a polling request.
if(($_POST['action']??'')==='step' && !empty($w['settings']['auto_refresh_daily']) && (!$j || ($j['state']??'')!=='running')){
 $lastCompleted=strtotime((string)($w['settings']['last_refresh']??''))?:0;
 $lastAttempt=(int)($w['settings']['auto_refresh_last_attempt']??0);
 if(time()-max($lastCompleted,$lastAttempt)>=86400){
  // Persist attempt before network work so a failed start cannot loop every 2.5 seconds.
  $w['settings']['auto_refresh_last_attempt']=time();save($d);
  try{$j=refresh_job_start($w,'all','');}catch(Throwable $ignored){$j=refresh_job_read();}
 }
}
if(($_POST['action']??'')==='stop'){$status=refresh_job_stop();}elseif($j&&$j['state']==='running'){$status=refresh_job_step($w);save($d);}else $status=refresh_job_status($j);echo json_encode($status,JSON_INVALID_UTF8_SUBSTITUTE);}catch(Throwable $e){http_response_code(500);echo json_encode(['error'=>$e->getMessage()]);}
