<?php

/* Database config *
$host = 'mysql-balancepro-20191213.cjf7xkcjtoge.us-west-2.rds.amazonaws.com';
$user = 'balancepro';
$pass = '"V{4PLmv6X@]$euxE;T8`';
$database = 'wp_balance_20200201'; */


$host = 'balancepro-1-14-24.cjf7xkcjtoge.us-west-2.rds.amazonaws.com';
$user = 'balancepro';
$pass = 'Int3l1234!';
$database = 'dev';

/* End config */

$dsn = 'mysql:host='.$host.';dbname='.$database.";charset=UTF8";
$db = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
?>
