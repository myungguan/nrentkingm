<?
/**
 * 어드민 > 고객관리 > 회원 > 엑셀
 * admin.rentking.co.kr/settlement.php?code=list
 * 회원 리스트 엑셀 부분
 */
$excel_filename = '정산내역_'.$_REQUEST['dt'].'_'.$_REQUEST['rentshop'].'.xlsx';

//컬럼 형식
$header = array(
	'예약번호' => 'string',
	'대여일시' => 'string',
	'반납일시' => 'string',
	'차종' => 'string',
	'결제금액' => 'string',
	'할인액' => 'string',
	'총금액' => 'string',
	'수수료' => 'string',
	'정산예정금액' => 'string',
	'정산일시' => 'string',
	'상태' => 'string'
);

//컬럼 제목
$column_map = array(
	'예약번호' => 'data1',
	'대여일시' => 'data2',
	'반납일시' => 'data3',
	'차종' => 'data4',
	'결제금액' => 'data5',
	'할인액' => 'data6',
	'총금액' => 'data7',
	'수수료' => 'dataCommission',
	'정산예정금액' => 'data8',
	'정산일시' => 'data9',
	'상태' => 'data10'
);

//데이터 쿼리 세션에서 쿼리 가져옴
if ($_SESSION['sql_excel']) {
	$q = $_SESSION['sql_excel'];
	$r = mysql_query($q);

	//데이터 가져오기 및 재가공
	while ($row = mysql_fetch_array($r)) {
		$indata['data1'] = $row['payment_idx'];
		$indata['data2'] = $row['sdate'];
		$indata['data3'] = $row['edate'];
		$indata['data4'] = $row['name'].'['.$row['carnum'].']';
		$indata['data5'] = number_format($row['account']);
		$indata['data6'] = number_format($row['discount']);
		$indata['data7'] = number_format($row['total']);
		$indata['dataCommission'] = number_format($row['commission']);
		$indata['data8'] = number_format($row['settlement']);
		$indata['data9'] = $row['dt_create'];
		$indata['data10'] = $row['account_transfer_idx'] ? '정산완료' : '';

		$data[] = $indata;
	}
}