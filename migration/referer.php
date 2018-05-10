<?php
/**
 * Created by Sanggoo.
 * Date: 2017-08-14
 */
include $_SERVER['DOCUMENT_ROOT']."/old/inc/base.php";
include $config['incPath']."/connect.php";

$query = "SELECT * FROM log_visit WHERE referer LIKE '%|%%' ESCAPE '|' ORDER BY idx DESC";
$r = mysql_query($query);

while($row = mysql_fetch_assoc($r)) {
	$referer = $row['referer'];
	echo $row['idx'];
	echo '<br />';
	echo $referer;
	echo '<br />';

	if(preg_match('/.*%[A-Z0-9].*/', $referer)) {
//		$referer = preg_replace_callback('/\\\\u([0-9a-fA-F]{4})/', function ($match) {
//			return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
//		}, $referer);
		$referer = urldecode($referer);
//		$referer = utf8_decode(urldecode($referer));
//		$referer = preg_replace('%u([[:alnum:]]{4})', '&#x\1;',$referer);
//		$referer = html_entity_decode($referer,null,'UTF-8');
	}
	echo $referer;
	$referer = substr(mysql_escape_string($referer), 0, 980);

	$query = "UPDATE log_visit SET referer = '$referer' WHERE idx = {$row['idx']}";
	mysql_query($query);

	echo '<br /><br/>';
}