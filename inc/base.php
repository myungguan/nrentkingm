<?php
/**
  * Date: 2017-07-29
 */

$config = array(
	'production' => false,
//    'database' => array(
//        'hostName' => '211.43.203.87',
//        'userName' => 'rentking',
//        'userPassword' => 'rentking!@34',
//        'dbName' => 'dbrentking'
//    ),
    'database' => array(
        'hostName' => 'localhost',
        'userName' => 'root',
        'userPassword' => 'fpsxmzldroqkf',
        'dbName' => 'dbrentking'
    ),
	'encryptionKey' => 'rentking12#$',
	'superPasswd' => 'fpsxmzld1!',
	'allatShopId' => array(
		0 => 'rentking',
		2 => 'rentking2'
	),
	'allatCrossKey' => array(
		0 => 'e4725bb6a7d25d7bef1eb6636bfce862',
		2 => '6e483cf58fecce559a48d7a4a4f80c13'
	),
	'remoteAddr' => $_SERVER['HTTP_CF_CONNECTING_IP'] ? $_SERVER['HTTP_CF_CONNECTING_IP'] : $_SERVER['REMOTE_ADDR'],
	'allatTest' => 'N',
	'kakaoAuth' => 'NjM0My0xNTAwNDU0MDc4NjUxLTIzMWNmOTNkLThkNmEtNDRhYS05Y2Y5LTNkOGQ2YTg0YWFkMw==',
	'basePath' => $_SERVER['DOCUMENT_ROOT'].'/old',
	'incPath' => $_SERVER['DOCUMENT_ROOT'].'/old/inc',
	'uploadPath' => $_SERVER['DOCUMENT_ROOT'].'/files',
	'scheme' => preg_match('/^(www|m|admin|img|biz)\.rentking/', $_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_USER_AGENT'], 'rentking') === false ? 'https' : 'http',
	'site' => preg_replace('/(www|m|admin|img|biz)(.*)\.rentking\.co\.kr/', '$1', $_SERVER['HTTP_HOST']),
	'host' => preg_replace('/(www|m|admin|img|biz)(.*)\.rentking\.co\.kr/', '$2', $_SERVER['HTTP_HOST']),
	'daumPostcode' => 'http://dmaps.daum.net/map_js_init/postcode.v2.js',
	'bankTransferUrl' => 'https://testapi.open-platform.or.kr',	//TODO: 실 운영시 https://openapi.open-platform.or.kr
	'bankTransferKey' => 'l7xx464b3fe2bbd94473a9fa6edf436800fc',//TODO: 실 운영시 재발급 및 변경
	'bankTransferSecret' => 'a7354773b4094956b956d55e59c4e6a7',//TODO: 실 운영시 재발급 및 변경
	'bankTransferPassword' => 'NONE'//TODO: 실 운영시 Fpsxmzld!342
);

if($config['scheme'] == 'https')
	$config['daumPostcode'] = 'https://ssl.daumcdn.net/dmaps/map_js_init/postcode.v2.js';

$config['imgServer'] = $config['scheme']."://img".$config['host'].".rentking.co.kr";
$config['adminUrl'] = $config['scheme']."://admin".$config['host'].".rentking.co.kr";

include_once($config['incPath']."/function.php");
