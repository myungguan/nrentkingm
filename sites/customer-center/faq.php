<?
include $_SERVER['DOCUMENT_ROOT']."/old/inc/base.php";
include $config['incPath']."/connect.php";
include $config['incPath']."/session.php";
include $config['incPath']."/config_site.php";

$page['type'] = "2";
$page['title'] = "자주묻는질문";

$mod = $_REQUEST['mode'];
$idx = $_REQUEST['idx'];
if($mod == 'plusReadCount' && isset($idx)){

	$ar_data = sel_query_all('faq', " WHERE idx='{$idx}'");

	$value['read_count'] = $ar_data['read_count'] + 1;
	update('faq', $value, " WHERE idx='$idx'");
	exit;
}

$q = "SELECT * FROM faq WHERE dt_delete IS NULL ";
$r = mysql_query($q);
while($row = mysql_fetch_array($r))	{
	$indata1[] = $row;
}
$keyword = $_REQUEST['keyword'];
$q = "SELECT * FROM faq WHERE dt_delete IS NULL ";
if($keyword)	{
	$q = $q. " and title like '%$keyword%' OR content like '%$keyword%'";
}
$q .= " ORDER BY read_count DESC, idx DESC";
$r = mysql_query($q);
while($row = mysql_fetch_array($r))	{
	$row['content'] = nl2br($row['content']);
	$indata[] = $row;
}
$tpls->assign('keyword', $keyword);
$tpls->assign('faqbest', $indata1);
$tpls->assign('faq', $indata);
$tpls->assign('page', $page);
$tpls->print_('mains');
?>
