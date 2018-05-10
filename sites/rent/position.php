<?
include $_SERVER['DOCUMENT_ROOT']."/old/inc/base.php";
include $config['incPath']."/connect.php";
include $config['incPath']."/session.php";
include $config['incPath']."/config_site.php";

$page['type'] = "2";
$page['title'] = "대여위치 확인";
$idx = $_REQUEST['idx'];

$query = "
	SELECT
		m.*,
		mt.addr,
		mt.vehicle_idx,
		v.rentshop_idx,
		X(r.latlng) lat, 
		Y(r.latlng) lng,
		r.addr1,
		r.addr2,
		r.name rentshop_name,
		r.affiliate,
		r.s1time1,
		r.s1time2,
		r.s2time1,
		r.s2time2
	FROM payments m
		LEFT JOIN reservation mt ON m.reservation_idx = mt.idx
		LEFT JOIN vehicles v ON mt.vehicle_idx = v.idx
		LEFT JOIN rentshop r ON v.rentshop_idx = r.idx
	WHERE
		m.idx = $idx
";

$order = mysql_fetch_assoc(mysql_query($query));

$tpls->assign('order',json_encode($order));
$tpls->assign('page', $page);
$tpls->print_('mains');
?>
