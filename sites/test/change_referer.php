<?php
/**
 * Created by Sanggoo.
 * Date: 2017-08-06
 */

include $_SERVER['DOCUMENT_ROOT']."/old/inc/base.php";
include $config['incPath']."/connect.php";

$query = "SELECT * FROM log_visit WHERE dt >= CURDATE() AND referer LIKE '%|%%' ESCAPE '|'";
$r = mysql_query($query);

while($row = mysql_fetch_assoc($r)) {
	$referer = mysql_escape_string(urldecode($row['referer']));
	$query = "UPDATE log_visit SET referer = '$referer' WHERE idx= {$row['idx']}";
	mysql_query($query);
	echo $referer."<br />\n";
}