<?php
require __DIR__.'/lib.php';init();
// The server only redirects to the original provider; it never relays stream bytes.
try{$w=output_workspace();$id=filter_var($_GET['stream_id']??null,FILTER_VALIDATE_INT);$url=$id?xc_stream_url($w,$id):null;if(!$url){http_response_code(404);exit('Stream not found');}header('Cache-Control: no-store');header('Location: '.$url,true,302);}catch(Throwable $e){http_response_code(401);echo 'Unauthorized';}
