<?php
require __DIR__.'/lib.php';init();
try {
 $w=output_workspace();$dForPlayer=data();$playerWs=feed_auth($dForPlayer);if($playerWs!==null){player_refresh_register($playerWs);player_epg_register($playerWs);}$action=(string)($_GET['action']??'');
 switch($action){
 case 'get_live_categories':$result=xc_categories($w);break;
 case 'get_live_streams':$result=xc_streams($w);if(isset($_GET['category_id']))$result=array_values(array_filter($result,fn($c)=>$c['category_id']===(string)$_GET['category_id']));break;
 case 'get_live_info':$id=(int)($_GET['stream_id']??0);$result=['info'=>null,'movie_data'=>[]];foreach(xc_streams($w) as $item){if((int)$item['stream_id']===$id){$result=['info'=>$item,'movie_data'=>[]];break;}}break;
 case 'get_vod_categories':$result=xc_media_categories($w,'movies');break;
 case 'get_vod_streams':$result=xc_media_rows($w,'movies');if(isset($_GET['category_id']))$result=array_values(array_filter($result,fn($r)=>$r['category_id']===(string)$_GET['category_id']));break;
 case 'get_series_categories':$result=xc_media_categories($w,'series');break;
 case 'get_series':$result=xc_media_rows($w,'series');if(isset($_GET['category_id']))$result=array_values(array_filter($result,fn($r)=>$r['category_id']===(string)$_GET['category_id']));break;
 case 'get_short_epg':case 'get_simple_data_table':case 'get_simple_date_table':epg_delivery_event('xtream','request_received',$action.' stream '.(int)($_GET['stream_id']??0));$result=player_epg_listings($w,(int)($_GET['stream_id']??0),(int)($_GET['limit']??($_GET['action']==='get_short_epg'?10:200)),($action==='get_simple_date_table'?'get_simple_data_table':$action));break;
 case 'get_vod_info':
  $id=(int)($_GET['vod_id']??0);$r=$w['movies'][$id-1]??null;
  $result=$r?['info'=>['name'=>$r['name'],'movie_image'=>$r['icon'],'plot'=>$r['plot'],'rating'=>$r['rating']],'movie_data'=>['stream_id'=>$id,'name'=>$r['name'],'container_extension'=>$r['container_extension']]]:[];break;
 case 'get_series_info':$result=xc_series_info($w,(int)($_GET['series_id']??0));break;
 default:$result=xc_auth_details($w);
 }
 xc_response($result);
} catch(Throwable $e){http_response_code(200);if(($_GET['action']??'')==='get_series_info')xc_response(['info'=>[],'seasons'=>[],'episodes'=>new stdClass(),'error'=>'Series details temporarily unavailable']);else xc_response(['user_info'=>['auth'=>0,'status'=>'Disabled'],'error'=>'Xtream request failed']);}
