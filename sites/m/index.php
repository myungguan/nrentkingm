<?
include $_SERVER['DOCUMENT_ROOT']."/old/inc/base.php";
include $config['incPath']."/connect.php";
include $config['incPath']."/session.php";
include $config['incPath']."/config_site.php";


$i = $_REQUEST['i'];

$ar_i = explode("_",$i);

$query = "
	SELECT
		m.*,
		mt.addr,
		mt.raddr,
		mt.vehicle_idx,
		v.rentshop_idx
	FROM payments m
		LEFT JOIN reservation mt ON m.reservation_idx = mt.idx
		LEFT JOIN vehicles v ON mt.vehicle_idx = v.idx
	WHERE
		m.idx = {$ar_i[1]}
";
$ar_data = mysql_fetch_assoc(mysql_query($query));
$addr = '';

if($ar_data['ptype']=='1')	{
	if($ar_i[0]=='p')	{
		$addr = $ar_data['addr'];
		$page['title'] = "배달주소안내";
	} else if($ar_i[0]=='r')	{
		$addr = $ar_data['raddr'];
		$page['title'] = "반납주소안내";
	}
} else {
	$ar_car = sel_query_all("vehicles"," WHERE idx='{$ar_data['vehicle_idx']}'");
	$ar_rent = sel_query_all("rentshop"," WHERE idx='{$ar_car['rentshop_idx']}'");

	$addr = $ar_rent['addr1']." ".$ar_rent['addr2'];

	if($ar_i[0]=='p')	{
		$page['title'] = "픽업지점안내";
	}
	if($ar_i[0]=='r')	{
		$page['title'] = "반납지점안내";
	}
}

$datas['addr'] = trim($addr);

$page['type'] = "2";


$tpls->assign('datas',$datas);
$tpls->assign('page', $page);
$tpls->print_('mains');
?>
