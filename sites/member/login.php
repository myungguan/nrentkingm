<?
include $_SERVER['DOCUMENT_ROOT']."/old/inc/base.php";
include $config['incPath']."/connect.php";
include $config['incPath']."/session.php";
include $config['incPath']."/config_site.php";

if($_SERVER['HTTP_REFERER'] != '' && strpos($_SERVER['HTTP_REFERER'], 'rentking.co.kr') === FALSE) {
	header('Location: /');
}

if($_SESSION['member_idx'])	{
	echo "<script>alert('이미 로그인 되어 있습니다'); location.href='/'; </script>";
	exit;
}


$page[type] = "1";
$page[title] = "로그인";

$tpls->assign('page', $page);
$tpls->print_('mains');
?>
