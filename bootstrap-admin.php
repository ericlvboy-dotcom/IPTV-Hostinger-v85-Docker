<?php
declare(strict_types=1);
require '/var/www/html/lib.php';
$d=data();
if (!count($d['users'] ?? [])) {
  $d['users'][] = [
    'id'=>bin2hex(random_bytes(6)),
    'name'=>'admin',
    'hash'=>password_hash('admin', PASSWORD_DEFAULT),
    'role'=>'admin',
    'ws'=>'master',
    'active'=>true,
    'output_user'=>'',
    'output_hash'=>''
  ];
  save($d);
  echo "Created admin login.\n";
} else {
  echo "Existing users found; no passwords changed.\n";
}
