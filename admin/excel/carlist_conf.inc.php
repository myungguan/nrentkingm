<?
/**
 * 어드민 > 차량관리 > 차종별 등록현황 > 엑셀
 * admin.rentking.co.kr/car.php?code=carlist
 * 차종별 등록현황 리스트 엑셀 부분
 */
$excel_filename = "carlist.xlsx";

//컬럼 형식
$header = array(
	'No.' => 'string',
	'차량모델' => 'string',
	'등록대수' => 'string',
	'예약중' => 'string',
	'대여중' => 'string',
	'대기중' => 'string',
	'계' => 'string'
);

//컬럼 제목
$column_map = array(
	'No.' => 'data1',
	'차량모델' => 'data2',
	'등록대수' => 'data3',
	'예약중' => 'data4',
	'대여중' => 'data5',
	'대기중' => 'data6',
	'계' => 'data7'
);

//데이터 쿼리 세션에서 쿼리 가져옴
if ($_SESSION['sql_excel']) {
	$q = $_SESSION['sql_excel'];
	$r = mysql_query($q);

	//데이터 가져오기 및 재가공
	while ($row = mysql_fetch_array($r)) {

		$qs = "SELECT * FROM vehicles WHERE model_idx='{$row['idx']}' AND dt_delete IS NULL";
		$rs = mysql_query($qs);
		$isits = mysql_num_rows($rs);

		$qs = "SELECT m.idx FROM payments m
				LEFT JOIN reservation mt ON m.reservation_idx = mt.idx
				LEFT JOIN vehicles v ON mt.vehicle_idx = v.idx
			WHERE m.dan='1' AND v.model_idx='{$row['idx']}'";
		$rs = mysql_query($qs);
		$t1 = mysql_num_rows($rs);

		$qs = "SELECT idx FROM payments m
				LEFT JOIN reservation mt ON m.reservation_idx = mt.idx
				LEFT JOIN vehicles v ON mt.vehicle_idx = v.idx
			WHERE m.dan='2' AND v.model_idx='{$row['idx']}'";
		$rs = mysql_query($qs);
		$t2 = mysql_num_rows($rs);

		$indata['data1'] = $row['idx'];
		$indata['data2'] = $row['name'];
		$indata['data3'] = $isits;
		$indata['data4'] = $t1;
		$indata['data5'] = $t2;
		$indata['data6'] = ($isits - $t1 - $t2);
		$indata['data7'] = $isits;
		$data[] = $indata;
	}
}