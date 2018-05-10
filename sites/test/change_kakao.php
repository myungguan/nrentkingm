<?php
/**
 * Created by Sanggoo.
 * Date: 2017-08-06
 */

include $_SERVER['DOCUMENT_ROOT']."/old/inc/base.php";
include $config['incPath']."/connect.php";

$query = "SELECT * FROM kakao_log WHERE msg LIKE '%|%%' ESCAPE '|'";
$r = mysql_query($query);

while($row = mysql_fetch_assoc($r)) {
	$msg = mysql_escape_string(urldecode($row['msg']));
	$query = "UPDATE kakao_log SET msg = '$msg', failed_msg = '$msg' WHERE idx= {$row['idx']}";
	mysql_query($query);
}