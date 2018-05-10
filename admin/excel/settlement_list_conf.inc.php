<?
/**
 * 어드민 > 고객관리 > 회원 > 엑셀
 * admin.rentking.co.kr/settlement.php?code=list
 * 회원 리스트 엑셀 부분
 */
$excel_filename = "정산내역_".date('YmdHis').".xlsx";

//컬럼 형식
$header = array(
	'정산예정일' => 'string',
	'지점수' => 'string',
	'예약건수' => 'string',
	'결제금액' => 'string',
	'할인액' => 'string',
	'총금액' => 'string',
	'수수료' => 'string',
	'정산예정금액' => 'string',
	'정산완료금액' => 'string',
	'미정산금액' => 'string',
	'상태' => 'string'
);

//컬럼 제목
$column_map = array(
	'정산예정일' => 'data1',
	'지점수' => 'data2',
	'예약건수' => 'data3',
	'결제금액' => 'data4',
	'할인액' => 'data5',
	'총금액' => 'data6',
	'수수료' => 'dataCommission',
	'정산예정금액' => 'data7',
	'정산완료금액' => 'data8',
	'미정산금액' => 'data9',
	'상태' => 'data10'
);

//데이터 쿼리 세션에서 쿼리 가져옴
if ($_SESSION['sql_excel']) {
	$q = $_SESSION['sql_excel'];
	$r = mysql_query($q);

	//데이터 가져오기 및 재가공
	while ($row = mysql_fetch_array($r)) {
		$indata['data1'] = $row['dt'];
		$indata['data2'] = number_format($row['rentshop']);
		$indata['data3'] = number_format($row['count']);
		$indata['data4'] = number_format($row['account']);
		$indata['data5'] = number_format($row['discount']);
		$indata['data6'] = number_format($row['total']);
		$indata['dataCommission'] = number_format($row['commission']);
		$indata['data7'] = number_format($row['settlement']);
		$indata['data8'] = number_format($row['settlement_finish']);
		$indata['data9'] = number_format($row['settlement']-$row['settlement_finish']);
		$indata['data10'] = $row['settlement'] == $row['settlement_finish'] ? '정산완료' : '';

		$data[] = $indata;
	}
}