<?php
define('MERGER_JSON_ENDPOINT',true);require __DIR__.'/lib.php';start_dashboard_session();init();header('Content-Type: application/json; charset=utf-8');
try{
 require_auth();if($_SERVER['REQUEST_METHOD']!=='POST')throw new Exception('POST required');check_csrf();
 $d=data();$ws=wsid();$w=&workspace($d,$ws);$action=(string)($_POST['action']??'');
 if($action==='build_start'||$action==='build_step'||$action==='build_status'){
  $lock=fopen(STORE.'/epg-build-'.hash('sha256',$ws).'.lock','c');if(!$lock||!flock($lock,LOCK_EX|LOCK_NB))throw new Exception('Another EPG build step is active');
  try{
   $d=data();$w=&workspace($d,$ws);
   if($action==='build_start'){$job=epg_queue_start($w);save($d);}
   elseif($action==='build_step'){$job=epg_queue_step($w,$ws);save($d);}
   else $job=$w['settings']['epg_job']??['state'=>'idle'];
   $total=count($job['ids']??[]);$pos=min($total,(int)($job['index']??0));
   echo json_encode(['ok'=>true,'job'=>['state'=>$job['state']??'idle','index'=>$pos,'total'=>$total,'ok'=>$job['ok']??0,'failed'=>$job['failed']??0,'counts'=>$job['counts']??null,'error'=>$job['error']??null,'updated'=>$job['updated']??null,'publish_index'=>$job['publish_index']??null,'publish_total'=>$total+2],'cached'=>(bool)epg_published_guide($w,$ws),'published'=>$w['settings']['epg_published']??null]);
  }finally{flock($lock,LOCK_UN);fclose($lock);}exit;
 }
 if($action==='queue_start'){$result=epg_queue_start($w);save($d);echo json_encode(['ok'=>true,'job'=>$result]);}
 elseif($action==='queue_status'){echo json_encode(['ok'=>true,'job'=>$w['settings']['epg_job']??['state'=>'idle']]);}
 elseif($action==='step'){$i=filter_var($_POST['index']??null,FILTER_VALIDATE_INT);if($i===false||$i<0||$i>=count($w['epg']))throw new Exception('Invalid EPG index');$result=epg_refresh_one($w,$i);save($d);echo json_encode(['ok'=>true,'result'=>$result]);}
 elseif($action==='finish'){$stats=epg_publish_fragments($w,$ws);$w['settings']['last_epg_refresh']=time();$w['settings']['epg_published']=['channels'=>$stats['channels'],'programmes'=>$stats['programmes'],'bytes'=>filesize(epg_output_path($ws).'.gz'),'published'=>time()];$w['settings']['epg_refresh_due']=false;$w['settings']['epg_status']='Updated · '.$stats['programmes'].' programmes';save($d);echo json_encode(['ok'=>true,'counts'=>$stats]);}
 else throw new Exception('Unknown EPG action');
}catch(Throwable $e){http_response_code(400);echo json_encode(['ok'=>false,'error'=>$e->getMessage()]);}
