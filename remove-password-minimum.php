<?php
$file = '/var/www/html/index.php';
if (!is_file($file)) { fwrite(STDERR, "index.php missing\n"); exit(1); }
$s = file_get_contents($file);
$old = "if(strlen(\$pass)<12)throw new Exception('Use at least 12 characters');";
$new = "if(\$pass==='')throw new Exception('Enter a password');";
$s = str_replace($old, $new, $s, $n);
$s = str_replace('required minlength="12"', 'required', $s, $m);
if ($n === 0 && strpos($s, $new) === false) { fwrite(STDERR, "Password check not found\n"); exit(1); }
file_put_contents($file, $s);
echo "Password minimum removed; HTML fields updated.\n";
