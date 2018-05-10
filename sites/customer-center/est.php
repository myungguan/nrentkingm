<?
/**
 * www.rentking.co.kr/customer-center/est.php
 * 프론트 > 견적문의
 * 프론트 견적문의 작성 페이지
 */

include $_SERVER['DOCUMENT_ROOT']."/old/inc/base.php";
include $config['incPath']."/connect.php";
include $config['incPath']."/session.php";
include $config['incPath']."/config_site.php";

$page['type'] = "2";
$page['title'] = "견적문의";

$mode = $_REQUEST['mode'];
if($mode=='w')	{

	$value[kinds] = $_REQUEST['kinds'];
	$value[models] = mysql_escape_string($_REQUEST['models']);
	$value[isdeli] = $_REQUEST['isdeli'];
	$value[date1] = $_REQUEST['date1'];
	$value[date2] = $_REQUEST['date2'];
	$value[name] = mysql_escape_string($_REQUEST['name']);
	$value[cp] = mysql_escape_string($_REQUEST['cp']);
	$value[email] = mysql_escape_string($_REQUEST['email']);
	$value[phone] = mysql_escape_string($_REQUEST['phone']);
	$value[paddr] = mysql_escape_string($_REQUEST['paddr']);
	$value[raddr] = mysql_escape_string($_REQUEST['raddr']);
	$value[memo] = mysql_real_escape_string($_REQUEST['memo']);
	$value[dt_create]= date("Y-m-d H:i:s");
	$value[wip] = $_nip;
	insert("estimates",$value);

	echo "<script>alert('견적문의가 완료되었습니다. 빠른시간에 답변드리도록 하겠습니다'); location.replace('$PHP_SELF'); </script>";
	exit;
}

$tpls->assign('page', $page);
$tpls->print_('mains');
?>
