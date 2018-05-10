<?
include $_SERVER['DOCUMENT_ROOT']."/old/inc/base.php";
include $config['incPath']."/connect.php";
include $config['incPath']."/session.php";
include $config['incPath']."/config_site.php";

$boardid = $_REQUEST['boardid'];
$page = $_REQUEST['page'];
$idx = $_REQUEST['idx'];

if($boardid == 'notice') {
	$tableName = 'notices';
	$title = '공지사항';
}else if($boardid == 'event'){
    $tableName = 'events';
    $title = '이벤트';
}else if($boardid == 'rentking-news'){
    $tableName = 'rentking_news';
    $title = '렌트킹뉴스';
}

$ar_data = sel_query_all("$tableName", " WHERE idx='{$idx}'");

$value['read_count'] = $ar_data['read_count'] + 1;
update("$tableName", $value, " WHERE idx='$idx'");

$bbs = $ar_data;
$memo = $ar_data['memo'];

$bbs['boardid'] = $boardid;
$bbs['memo'] = $memo;
$bbs['listlink'] = "/bbs/list.php?boardid={$boardid}&page={$page}";

$npage['loca'] = "bbs/default/view";

$topimg = $ar_tops['tops'];
$tpls->assign('topimg', $topimg);

$tpls->define(array(
	'mains' => $npage[loca] . '.htm'
	/*
	'header' => './layout/head.htm', 
		'top' => './layout/top1.htm', 
		'down' => './layout/down1.htm',*/
), '', $compile_dir, $template_dir);

$tpls->assign('npage', $npage);
$tpls->assign('global', $tem_global);
$tpls->assign('bbs', $bbs);
$tpls->assign('bbsconf', $bbsconf);
$tpls->assign('goods', $goods);

$_page['type'] = "1";
$_page['title'] = $title;

$tpls->assign('page', $_page);
$tpls->print_('mains');
?>
