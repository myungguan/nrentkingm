<?php
require_once $config['incPath'].'/mysql-compat/include.php';

##### 데이터베이스에 연결한다.
$connect = mysql_connect($config['database']['hostName'],$config['database']['userName'],$config['database']['userPassword']);
if(!$connect) {
   echo mysql_error();
   exit;
}

##### 작업대상 데이터베이스를 선택한다.
$db = mysql_select_db($config['database']['dbName']);
if(!$db) {
   echo mysql_error();
   exit;
}

mysql_set_charset('utf8');
mysql_query('SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode,\'STRICT_TRANS_TABLES\',\'\'))');
