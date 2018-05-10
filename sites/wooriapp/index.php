<?php
/**
 * Created by Sanggoo.
 * Date: 2017-08-11
 * 우리카드 랜딩페이지
 */

include $_SERVER['DOCUMENT_ROOT']."/old/inc/base.php";
include $config['incPath']."/connect.php";
include $config['incPath']."/session.php";

//TODO: 우리APP 확인
$_SESSION['promotion'] = 'wooricard||5%';
header('Location: /');
exit;