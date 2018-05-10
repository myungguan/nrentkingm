<?
include $_SERVER['DOCUMENT_ROOT']."/old/inc/base.php";
include $config['incPath']."/connect.php";
include $config['incPath']."/session.php";
include $config['incPath']."/config_site.php";

session_destroy();
if(isset($_COOKIE['autoKey'])) {
	setcookie('autoKey', null, -1, '/', 'rentking.co.kr');
}

$url = $_GET['url'];

if(!$url)
{	header('Location: '."/");}
else
{	header('Location: '."$url");}
?>
