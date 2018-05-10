<?
/**
 * 어드민 > 예약관리 > 엑셀
 * admin.rentking.co.kr/reserve.php?code=list
 * 예약관리 리스트 엑셀
 */
$excel_filename = "reserve.xlsx";

//컬럼 형식
$header = array(
	'예약번호' => 'string',
	'구분' => 'string',
	'예약일시' => 'string',
	'예약자' => 'string',
	'휴대폰' => 'string',
	'차종' => 'string',
	'차량번호' => 'string',
	'대여일시' => 'string',
	'반납일시' => 'string',
	'회사명(지점)' => 'string',
	'배반차위치' => 'string',
	'총금액' => 'string'
);

//컬럼 제목
$column_map = array(
	'예약번호' => 'data2',
	'구분' => 'data3',
	'예약일시' => 'data4',
	'예약자' => 'data5',
	'휴대폰' => 'data6',
	'차종' => 'data7',
	'차량번호' => 'data77',
	'대여일시' => 'data8',
	'반납일시' => 'data9',
	'회사명(지점)' => 'data10',
	'배반차위치' => 'data11',
	'총금액' => 'data13'
);

//데이터 쿼리 세션에서 쿼리 가져옴
if ($_SESSION['sql_excel']) {
	$q = $_SESSION['sql_excel'];
	$r = mysql_query($q);

	//데이터 가져오기 및 재가공
	while ($row = mysql_fetch_array($r)) {
		$indata['data2'] = $row['idx'];
		$indata['data3'] = $row['pid'] . $ar_retype[$row['retype']];
		$indata['data4'] = $row['dt_create'];
		$indata['data5'] = $row['mem_name'];
		$indata['data6'] = $row['cp'];
		$indata['data7'] = $row['modelname'];
		$indata['data77'] = $row['carnum'];
		$indata['data8'] = date("y.m.d H:i", strtotime($row['sdate']));
		$indata['data9'] = date("y.m.d H:i", strtotime($row['edate']));
		$indata['data10'] = $row['name'].'('.$row['affiliate'].')';
		if ($row['ptype'] == '2') {
			$indata['data11'] = "지점대여";
		} else {
			$indata['data11'] = $row['addr'];
		}
		$indata['data13'] = number_format($row['total_account']);

		$data[] = $indata;
	}
}