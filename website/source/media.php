<?php
require __DIR__.'/lib.php';init();
try{$w=output_workspace();$type=(string)($_GET['kind']??'');$id=(int)($_GET['id']??0);
 if($type==='movie')$url=xc_media_url($w,'movies',$id);
 elseif($type==='episode')$url=xc_episode_url($w,(string)($_GET['id']??''));
 else $url=null;
 if(!$url){http_response_code(404);exit('Media not found');}
 header('Cache-Control: no-store');header('Location: '.$url,true,302);
}catch(Throwable $e){http_response_code(401);exit('Unauthorized');}
