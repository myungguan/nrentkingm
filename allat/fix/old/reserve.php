<?
include $_SERVER['DOCUMENT_ROOT']."/old/inc/base.php";
include $config['incPath']."/connect.php";
include $config['incPath']."/session.php";
include $config['incPath']."/config_site.php";
include $config['incPath']."/access.php";

$_page[type] = "1";
$_page[title] = "결제하기";


$tpls->assign('rentshop', $ar_rentshop);
$tpls->assign('page', $_page);
$tpls->print_('mains');
?>