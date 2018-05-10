<?php
/**
 * Created by Sanggoo.
 * Date: 2017-03-27
 */
include $_SERVER['DOCUMENT_ROOT']."/old/inc/base.php";

$safeIp = [
	"127.0.0.1",
	"218.55.101.75",
	"183.96.202.249"
];

if($config['production'] && in_array($config['remoteAddr'], $safeIp)) {
	exec('find ' . $_SERVER['DOCUMENT_ROOT'] . '/ -type d -exec chmod 755 {} +' , $output);
	exec('find ' . $_SERVER['DOCUMENT_ROOT'] . '/ -type f -exec chmod 644 {} +' , $output);
	$time = time();
	?><a href="/touch.php?<?=$time ?>">touch?<?=$time?></a><?
}


?>
