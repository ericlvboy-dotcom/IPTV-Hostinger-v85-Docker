<?php
define('MERGER_JSON_ENDPOINT',true);require __DIR__.'/lib.php';start_dashboard_session();init();
header('Content-Type: application/json; charset=utf-8');header('Cache-Control: no-store');
try{
 require_auth();if($_SERVER['REQUEST_METHOD']!=='POST')throw new Exception('POST required');check_csrf();
 $d=data();$w=&workspace($d,wsid());schedule_defaults($w);
 if(($_POST['action']??'')==='step'&&$w['settings']['scrub']['state']==='running'){scrub_step($w);save($d);}
 $s=$w['settings']['scrub'];$elapsed=max(1,time()-(int)($s['started']??time()));$checked=(int)$s['checked'];$total=(int)$s['total'];
 echo json_encode(['state'=>$s['state'],'checked'=>$checked,'total'=>$total,'errors'=>(int)$s['errors'],'message'=>'Browser batches advance while this page is open; cron advances batches when closed','eta_seconds'=>$checked&&$s['state']==='running'?(int)ceil(($total-$checked)*$elapsed/$checked):null]);
}catch(Throwable $e){http_response_code(400);echo json_encode(['error'=>$e->getMessage()]);}
