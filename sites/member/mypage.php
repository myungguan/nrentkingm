<?
/**
 * 사이트 > 로그인 > 마이페이지
 * www.rentking.co.kr/member/mypage.php
 * 내정보 확인 페이지
 */
include $_SERVER['DOCUMENT_ROOT']."/old/inc/base.php";
include $config['incPath']."/connect.php";
include $config['incPath']."/session.php";
include $config['incPath']."/config_site.php";
include $config['incPath']."/access.php";

$page['type'] = "2";
$page['title'] = "마이페이지";

$mode = $_REQUEST['mode'];

$ar_member = sel_query_all('member', "WHERE idx = $g_memidx");
$ar_lince = array();
if($ar_member['driver_license_idx']) {
	$ar_lince = sel_query_all("driver_license", " WHERE idx='{$ar_member['driver_license_idx']}'");
}


$tpls->assign('member', $ar_member);
$tpls->assign('lince', $ar_lince);
$tpls->assign('re', $re);
$tpls->assign('page', $page);
$tpls->print_('mains');
?>