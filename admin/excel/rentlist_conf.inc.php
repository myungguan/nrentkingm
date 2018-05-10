<?
/**
 * 어드민 > 차량관리 > 업체별등록차량현황 > 엑셀
 * admin.rentking.co.kr/car.php?code=rentshoplist
 * 업체별등록차량현황 리스트 엑셀
 */
$excel_filename = "rentlist.xlsx";

//컬럼 형식
$header = array(
	'NO' => 'string',
	'지점/멤버사' => 'string',
	'총대수' => 'string',
	'등록대수' => 'string',
	'소형' => 'string',
	'중형' => 'string',
	'대형' => 'string',
	'SUV' => 'string',
	'승합' => 'string',
	'수입' => 'string',
	'계' => 'string'
);

//컬럼 제목
$column_map = array(
	'NO' => 'data1',
	'지점/멤버사' => 'data2',
	'총대수' => 'data3',
	'등록대수' => 'data4',
	'소형' => 'data5',
	'중형' => 'data6',
	'대형' => 'data7',
	'SUV' => 'data8',
	'승합' => 'data9',
	'수입' => 'data10',
	'계' => 'data11'
);

//데이터 쿼리 세션에서 쿼리 가져옴
if ($_SESSION['sql_excel']) {
	$q = $_SESSION['sql_excel'];
	$r = mysql_query($q);

	//데이터 가져오기 및 재가공
	while ($row = mysql_fetch_array($r)) {
		unset($ar_now);
		$qs = "SELECT vehicles.*,grade_idx FROM vehicles INNER JOIN vehicle_models ON vehicle_models.idx=vehicles.model_idx WHERE rentshop_idx='{$row['idx']}' AND dt_delete IS NULL";
		$rs = mysql_query($qs);
		$total = 0;
		while ($rows = mysql_fetch_array($rs)) {
			if (!$ar_now[$rows['grade_idx']]) {
				$ar_now[$rows['grade_idx']] = 0;
			}
			$ar_now[$rows['grade_idx']]++;
			$total++;
		}

		$indata['data1'] = $row['idx'];
		$indata['data2'] = $row['nickname'];
		$indata['data3'] = $row['totalcar'];
		$indata['data4'] = $total;
		$indata['data5'] = $rows1[0];

		for ($i = 0; $i < sizeof($ar_ttype2_idx); $i++) {
			$f = "data" . (6 + $i);
			$indata[$f] = $ar_now[$ar_ttype2_idx[$i]];
		}
		$indata['data11'] = $total;
		$data[] = $indata;
	}
}
