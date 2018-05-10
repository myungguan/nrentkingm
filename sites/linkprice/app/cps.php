<?php
/**
 * Created by Sanggoo.
 * Date: 2017-03-10
 */

$lpinfo = $_REQUEST["lpinfo"];
$rd = $_REQUEST["rd"];

include $_SERVER['DOCUMENT_ROOT']."/old/inc/base.php";
include $config['incPath']."/connect.php";
include $config['incPath']."/session.php";

Header("P3P:CP=\"NOI DEVa TAIa OUR BUS UNI\"");
if(strpos($_SERVER['HTTP_USER_AGENT'], 'rentking') !== false) {
	//ilikeclick 쿠키 제거
	if (isset($_COOKIE ['c_ValueFromClick'])) {
		$SITE_URL = "http://" . $_SERVER['HTTP_HOST'];
		$temp_dns = strtolower( $SITE_URL );
		if ( substr( $temp_dns, 7, 3 ) == "www" ) {
			$temp_dns = substr( $temp_dns, 10 );
		} else {
			$temp_dns = "." . substr( $temp_dns, 7 );
		}

		list( $temp_dns ) = explode( "/", $temp_dns );
		list( $DNS_NAME ) = explode( ":", $temp_dns );
		unset( $_COOKIE['c_ValueFromClick'] );
		setcookie( 'c_ValueFromClick', null, - 1, "/", $DNS_NAME );
	}

	SetCookie("LPINFO", $lpinfo, time() + ($rd * 24 * 60 * 60), "/", ".rentking.co.kr");
}

Header("Location: " . "http://".$_SERVER["HTTP_HOST"]);