<?php
declare(strict_types=1);
$root='/var/www/html';
$epg=$root.'/epg_delivery_status.php';
if(!is_file($epg)) {fwrite(STDERR,"EPG status file missing\n");exit(1);}
$s=file_get_contents($epg);
$s=str_replace('$w=workspace(data(),wsid());','$store=data();$w=workspace($store,wsid());',$s,$count);
if($count)file_put_contents($epg,$s);
$refresh=$root.'/refresh_ajax.php';
if(!is_file($refresh)){fwrite(STDERR,"Refresh file missing\n");exit(1);}
$s=file_get_contents($refresh);
if(strpos($s,'MERGER_REFRESH_JSON_GUARD')===false){
 $s=str_replace("<?php","<?php\n// MERGER_REFRESH_JSON_GUARD\nini_set('display_errors','0');\nini_set('log_errors','1');\n", $s);
 file_put_contents($refresh,$s);
}
echo "EPG status reference bug corrected; refresh JSON warnings redirected to PHP logs.\n";
