<?
/**
 * 어드민 / 멤버사 로그아웃
 * admin.rentking.co.kr/
 */
include $_SERVER['DOCUMENT_ROOT']."/old/inc/base.php";
include $config['incPath']."/connect.php";
include $config['incPath']."/session.php";
include $config['incPath']."/config_site.php";

session_destroy();

$url = $_GET['url'];

if (!$url) {
	echo "<script type='text/javascript'>location.replace('/'); </script>";
} else {
	echo "<script type='text/javascript'>location.replace('{$url}'); </script>";
}