<?
include $_SERVER['DOCUMENT_ROOT']."/old/inc/base.php";
include $config['incPath']."/connect.php";
include $config['incPath']."/session.php";
include $config['incPath']."/config_site.php";

$query = "SELECT a.*, f.down FROM banners a LEFT JOIN files f ON f.article_type='banner' AND f.article_idx = a.idx WHERE a.dt_delete IS NULL ORDER BY a.idx DESC";
$result = mysql_query($query);
$list = [];
while($row = mysql_fetch_array($result)) {
	$list[] = $row;
}

$tpls->assign('list', $list);

$tpls->print_('mains');
?>
