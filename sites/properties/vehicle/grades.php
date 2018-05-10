<?
include $_SERVER['DOCUMENT_ROOT']."/old/inc/base.php";
include $config['incPath']."/connect.php";
include $config['incPath']."/session.php";

$quota = 1;
//TODO: 인증

$query = "SELECT idx, sname value FROM codes WHERE ttype=2 AND dt_delete IS NULL";
$r = mysql_query($query);
$data = array("status" => 200);
$list = [];
while($row = mysql_fetch_assoc($r)) {
	$list[] = $row;
}
$data['list'] = $list;

//TODO: quota 기록

header('Content-Type:application/json; charset=utf-8');
echo json_encode($data);