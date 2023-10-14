<?php
$config = parse_ini_file('/var/www/html/heqc-online/api/to-do.ini');
$conn = mysqli_connect($config['dbhost'], $config['username'], $config['password']);
mysqli_select_db($conn, $config['db']);
//$conn = mysqli_connect(['localhost'], ['root'], ['']);
//mysqli_select_db($conn, ['heqcsupport']);