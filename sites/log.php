<?php
/**
 * Created by Sanggoo.
 * Date: 2017-08-06
 */
include $_SERVER['DOCUMENT_ROOT']."/old/inc/base.php";
include $config['incPath']."/connect.php";
include $config['incPath']."/session.php";

$referer = $_REQUEST['referer'];
$url = $_REQUEST['url'];

logVisit($referer, $url);
logPageview($url);

