<?
include $_SERVER['DOCUMENT_ROOT']."/old/inc/base.php";
include $config['incPath']."/connect.php";
include $config['incPath']."/session.php";
include $config['incPath']."/config_site.php";

if($_SESSION['member_idx'])	{
	header('Location: '."/");
	exit;
}

$page['type'] = "1";
$page['title'] = '회원가입';


$page['mtype'] = isset($_REQUEST['mtype']) ? $_REQUEST['mtype'] : 'p';
$page['id'] = $_REQUEST['id'];
$page['name'] = $_REQUEST['name'];
$page['email'] = $_REQUEST['email'];
$page['channel'] = $_REQUEST['channel'];

$tpls->assign('page', $page);
$tpls->print_('mains');
?>
