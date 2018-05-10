<?
include $_SERVER['DOCUMENT_ROOT']."/old/inc/base.php";
include $config['incPath']."/connect.php";
include $config['incPath']."/session.php";
include $config['incPath']."/config_site.php";


$modtype = $_REQUEST['modtype'];
if(!$modtype)	{
	exit;
}
$ar_motype = explode("_",$modtype);

include $config['incPath']."/module/".$ar_motype[0]."/".$ar_motype[1].".php";

?>
