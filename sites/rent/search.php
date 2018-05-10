<?
/**
 * 차량 검색 페이지
 * www.rentking.co.kr/rent/search.php
 */

include $_SERVER['DOCUMENT_ROOT']."/old/inc/base.php";
include $config['incPath']."/connect.php";
include $config['incPath']."/session.php";
include $config['incPath']."/config_site.php";

if($_SERVER['HTTP_REFERER'] != '' && strpos($_SERVER['HTTP_REFERER'], 'rentking.co.kr') === FALSE) {
	header('Location: /');
}

$page['type'] = "2";
$page['title'] = "차량 검색";

$ptype = $_REQUEST['ptype'];
$sdate = $_REQUEST['sdate'];
$edate = $_REQUEST['edate'];
$grade = $_REQUEST['grade_idx'];

$defaultSearch = getDefaultSearch();

if(!$ptype)	{
	$ptype = $defaultSearch['ptype'];
}

if(!$sdate || strlen($edate) != 16)	{
	$sdate = $defaultSearch['sdate'];
}

if(!$edate || strlen($edate) != 16)	{
	$edate = $defaultSearch['edate'];
}

$addr = $defaultSearch['addr'];

setcookie('defaultSearch', $ptype.'|'.$sdate.'|'.$edate.'|'.$grade.'|'.$addr, 0, '/', 'rentking.co.kr');
$tem_global['ptype'] = $ptype;
$tem_global['mindate'] = getTime($sdate) >= getTime($defaultSearch['mindate']) ? $defaultSearch['mindate'] : $sdate;
$tem_global['sdate'] = $sdate;
$tem_global['edate'] = $edate;
$tem_global['grade_idx'] = $grade;
$tem_global['addr'] = $addr;
$tem_global['isSearch'] = true;

$data = array();
$data['ptype'] = $ptype;
$data['sdate'] = $sdate;
$data['edate'] = $edate;
$data['grade_idx'] = $grade;
$data['retype'] = 1;

$days = calcDays($sdate, $edate);
$months = $days['months'];
if($months > 0) {
	$data['retype'] = 2;
}

$searchsdate = date("Y-m-d", strtotime( date( "Y-m-d", strtotime( date("Y-m-d") ) ) . "-6 month" ) );
$searchedate = date("Y-m-d");

//모델
$modelname = getModelnameGeneral();
$query = "SELECT
		$modelname model,
		COUNT(1) total,
		SUM(CASE WHEN mt.retype = 1 THEN 1 ELSE 0 END) total_short,
		SUM(CASE WHEN mt.retype = 1 THEN 0 ELSE 1 END) total_long
	FROM payments m
		LEFT JOIN reservation mt ON m.reservation_idx = mt.idx
		LEFT JOIN vehicles v ON mt.vehicle_idx = v.idx
		LEFT JOIN vehicle_models vs ON v.model_idx = vs.idx
		LEFT JOIN codes c ON vs.company_idx = c.idx
	WHERE
		m.dan < 4
		AND m.dt_create >= '$searchsdate 00:00:00' AND m.dt_create <= '$searchedate 23:59:59'
	GROUP BY model
	ORDER BY total DESC
	LIMIT 0, 12";
$result = mysql_query($query);
$model = array();
while($row = mysql_fetch_array($result)) {
	$model[] = $row;
}
$data['model'] = $model;

//연료
$query = "SELECT * FROM codes WHERE ttype = 3 AND dt_delete IS NULL";
$result = mysql_query($query);
$fuel = array();
while($row = mysql_fetch_array($result)) {
	$fuel[] = $row;
}
$data['fuel'] = $fuel;

//출고일
$year = array();
$currentYear = date('Y') - 3;
for($i = 0; $i < 4; $i++) {
	$year[] = $currentYear + $i;
}
$data['year'] = $year;

//차량색상
$query = "SELECT
		v.color,
		COUNT(1) total,
		SUM(CASE WHEN mt.retype = 1 THEN 1 ELSE 0 END) total_short,
		SUM(CASE WHEN mt.retype = 1 THEN 0 ELSE 1 END) total_long
	FROM payments m
		LEFT JOIN reservation mt ON m.reservation_idx = mt.idx
		LEFT JOIN vehicles v ON mt.vehicle_idx = v.idx
	WHERE
		m.dan < 4
		AND m.dt_create >= '$searchsdate 00:00:00' AND m.dt_create <= '$searchedate 23:59:59'
		AND v.color <> '기타'
	GROUP BY v.color
	ORDER BY total DESC
	LIMIT 0,6";
$result = mysql_query($query);
$color = array();
while($row = mysql_fetch_array($result)) {
	$color[] = $row;
}
$data['color'] = $color;

//제조사
$query = "SELECT
		c.sname company_name,
		vs.company_idx,
		COUNT(1) total,
		SUM(CASE WHEN mt.retype = 1 THEN 1 ELSE 0 END) total_short,
		SUM(CASE WHEN mt.retype = 1 THEN 0 ELSE 1 END) total_long
	FROM payments m
		LEFT JOIN reservation mt ON m.reservation_idx = mt.idx
		LEFT JOIN vehicles v ON mt.vehicle_idx = v.idx
		LEFT JOIN vehicle_models vs ON v.model_idx = vs.idx
		LEFT JOIN codes c ON vs.company_idx = c.idx
	WHERE
		m.dan < 4
		AND c.idx IS NOT NULL
		AND m.dt_create >= '$searchsdate 00:00:00' AND m.dt_create <= '$searchedate 23:59:59'
	GROUP BY vs.company_idx
	ORDER BY total DESC
	LIMIT 0, 12";
$result = mysql_query($query);
$company = array();
while($row = mysql_fetch_array($result)) {
	$company[] = $row;
}
$data['company'] = $company;

$tpls->assign('data', $data);
$tpls->assign('global', $tem_global);
$tpls->assign('page', $page);
$tpls->print_('mains');
?>
