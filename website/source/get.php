<?php
require __DIR__.'/lib.php';init();
try{$w=output_workspace();$dForPlayer=data();$playerWs=feed_auth($dForPlayer);if($playerWs!==null){player_refresh_register($playerWs);player_epg_register($playerWs);}$type=strtolower((string)($_GET['type']??'m3u_plus'));if(!in_array($type,['m3u','m3u_plus'],true)){http_response_code(400);exit('Unsupported output type');}
header('Content-Type: audio/x-mpegurl; charset=utf-8');header('Content-Disposition: attachment; filename="merged.m3u"');
$base=xc_base();$u=rawurlencode((string)$_GET['username']);$p=rawurlencode((string)$_GET['password']);
echo '#EXTM3U url-tvg="'.htmlspecialchars(player_epg_url(),ENT_QUOTES|ENT_XML1,'UTF-8').'" x-tvg-url="'.htmlspecialchars(player_epg_url(),ENT_QUOTES|ENT_XML1,'UTF-8').'"'."\n";
foreach(output_channel_rows($w) as $item){$i=$item['index'];$c=$item['channel'];$name=str_replace(["\r","\n",'"'],[' ',' ',''],output_channel_label($w,$c));$group=str_replace('"','',output_group_label($w,$c));$id=str_replace('"','',(string)($c['tvg_id']??''));$logo=str_replace('"','',(string)($c['logo']??''));echo '#EXTINF:-1 tvg-id="'.$id.'" tvg-logo="'.$logo.'" group-title="'.$group.'",'.$name."\n".$base.'/live/'.$u.'/'.$p.'/'.($i+1).".ts\n";}
}catch(Throwable $e){http_response_code(401);echo 'Unauthorized';}
