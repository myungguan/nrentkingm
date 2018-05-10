<?
/**
 * ajax 설정
 */
include $_SERVER['DOCUMENT_ROOT']."/old/inc/base.php";
include $config['incPath']."/connect.php";
include $config['incPath']."/session.php";
include $config['incPath']."/config.php";

//임시로추가
header("Expires: 0");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");


$modtype = $_REQUEST['modtype'];
$han = $_REQUEST['han'];
if (!$modtype || !$han) {
	$redata['res'] = 'error';
	$redata['resmsg'] = '잘못된접근입니다.';

	$result = json_encode($redata);
	header('Content-Type:application/json; charset=utf-8');
	echo $result;
	exit;
}
$ar_motype = explode("_", $modtype);

include $config['incPath']."/module/{$ar_motype[0]}/{$ar_motype[1]}.php";
