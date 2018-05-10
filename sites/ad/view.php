<?
include $_SERVER['DOCUMENT_ROOT']."/old/inc/base.php";
include $config['incPath']."/connect.php";
include $config['incPath']."/session.php";
include $config['incPath']."/config_site.php";

$item = mysql_fetch_assoc(mysql_query("SELECT a.*, f.idx file_idx, f.name, f.path, f.type, f.size, f.down FROM banners a LEFT JOIN files f ON f.article_type = 'banner' AND f.article_idx = a.idx WHERE a.idx={$_REQUEST['idx']}"));

$tpls->assign('item', $item);

$tpls->print_('mains');
?>
