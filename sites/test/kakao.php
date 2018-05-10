<?php
/**
 * Created by Sanggoo.
 * Date: 2017-07-21
 */

include $_SERVER['DOCUMENT_ROOT']."/old/inc/base.php";
include $config['incPath']."/connect.php";

$data = array(
	'name' => date('YmdHis'),
);

echo sendKakao($config['kakaoAuth'], '01034460336', 'CUS0102', $data);