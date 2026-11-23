<?php
require __DIR__.'/lib.php';start_dashboard_session();init();require_auth();
header('Content-Type: text/plain; charset=utf-8');header('Cache-Control: no-store');
$d=data();$w=workspace($d,wsid());$u=null;
foreach($d['users'] as $row){if(($row['name']??'')===(auth()['name']??'')){$u=$row;break;}}
$ready=$u&&!empty($u['output_user'])&&!empty($u['output_hash']);
echo "Xtream output diagnostic\n";
echo 'Output credentials configured: '.($ready?'YES':'NO')."\n";
echo 'Merged channels: '.count($w['channels']??[])."\n";
echo 'Live categories: '.count(xc_categories($w))."\n";
echo 'Live streams: '.count(xc_streams($w))."\n";
echo 'API file present: '.(is_file(__DIR__.'/player_api.php')?'YES':'NO')."\n";
echo "\nIf output credentials are configured and streams > 0, use the Xtream Server URL and output credentials from the dashboard (not the admin login).\n";
