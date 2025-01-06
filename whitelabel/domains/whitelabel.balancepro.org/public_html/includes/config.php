<?php 
/*$host = "mysql-balancepro-20191213.cjf7xkcjtoge.us-west-2.rds.amazonaws.com";
//$host = "balancepro-1-24-2023.cjf7xkcjtoge.us-west-2.rds.amazonaws.com";
$user = "mysqlbalancepro";
$pass = "Int3l123!";
//$user = "mysqlbalancepro";
//$pass = "Balance123";
//$database = "staging_balancepro_20200201";
$database = "balancepro_dev_29_09_2023";*/

$host = 'balancepro-1-14-24.cjf7xkcjtoge.us-west-2.rds.amazonaws.com';
$dbuser = 'balancepro';
$pass = 'Int3l1234!';
$database = 'staging3';


//Existing Balance Database
$MS_PROTOCOL='dblib';
$MS_DB_HOST='4.71.115.149';
$MS_DB_DATABASE1='PersonalFinance';
$MS_DB_DATABASE2='CallCenter';
$MS_DB_DATABASE3='InBalance';
$MS_DB_USERNAME='roidna';
$MS_DB_PASSWORD='V2%6!DKz)K]8DLf>';

/*$secret = '6LeqP_kjAAAAALeiob0IoJm6iSlFh1tT8tYWvUv-';
$site_key='6LeqP_kjAAAAAHxeSjIHpwtPP4dwVmspJ66iDaDP';*/
$secret ='';
$site_key ='';
$base_url='http://'.$_SERVER['HTTP_HOST'].'/';

//$smtp['Host'] = 'mail.smtp2go.com';
//$smtp['Username'] = 'dxekeramail1.com';
//$smtp['Password'] = 'dnk0MjQ1YnUwcmMw';
//$smtp['Port'] = 2525;
//$mailsendfrom = "no-reply@devxekera.com";

$smtp['Host'] = 'mail.smtp2go.com';
$smtp['Username'] = 'xekera.com';
$smtp['Password'] = 'f5WUqk2b1WbYB7x4';
$smtp['Port'] = 587;
$mailsendfrom = "no-reply@devxekera.com";

/*
$smtp['Host'] = 'mail.smtp2go.com';
$smtp['Username'] = 'swati.y@xekera.com';
$smtp['Password'] = 'Balance@123#';
$smtp['Port'] = 587;
$mailsendfrom = "no-reply@devxekera.com";
*/
//constants for quiz

$pastquiz_days = 90;

$success_rate = 80;

$quiz_sites_24 = array('vacu.staging.balancepro.org');


//$adminmail = 'eolisky@navigatornetworks.com';
//$superadminmail = 'marsal@xekera.com';
$parent_url='https://staging.balancepro.org';

$conn = new mysqli($host, $dbuser, $pass, $database);
$conn->set_charset("utf8");
return $conn;
//mssql_connect($MS_DB_HOST, $MS_DB_USERNAME, $MS_DB_PASSWORD, $MS_DB_DATABASE);
$dsn1 = "mysql:dbname=$MS_DB_DATABASE1;host=$MS_DB_HOST";
$dbh_pdo1 = new PDO($dsn1, $MS_DB_USERNAME, $MS_DB_PASSWORD);


$dsn2 = "mysql:dbname=$MS_DB_DATABASE2;host=$MS_DB_HOST";
$dbh_pdo2 = new PDO($dsn2, $MS_DB_USERNAME, $MS_DB_PASSWORD);


$dsn3 = "mysql:dbname=$MS_DB_DATABASE3;host=$MS_DB_HOST";
$dbh_pdo3 = new PDO($dsn3, $MS_DB_USERNAME, $MS_DB_PASSWORD);


?>


