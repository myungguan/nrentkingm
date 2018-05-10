<?
/**
 * 어드민 > 고객관리 > 회원 > 엑셀
 * admin.rentking.co.kr/settlement.php?code=list
 * 회원 리스트 엑셀 부분
 */
$dt = $_REQUEST['dt'];
$rentshop = $_REQUEST['rentshop'];
$rentshopInfo = [];

if($rentshop) {
	$query = "SELECT r.idx, CONCAT(r.name, '(', r.affiliate, ')') name, r.bank, r.bankaccount, r.bankname FROM rentshop r WHERE r.idx = $rentshop";
	$rentshopInfo = mysql_fetch_assoc(mysql_query($query));
}


$excel_filename = "정산내역_".($dt ? $dt : ($rentshopInfo['name']."(".$rentshopInfo['affiliate'].")")).".xlsx";

//컬럼 형식
$header = [];

if($dt) {
	$header['회사명(지점)'] = 'string';
	$header['은행명(은행코드)'] = 'string';
	$header['계좌번호'] = 'string';
	$header['예금주'] = 'string';
	$header['사업자등록번호'] = 'string';
	$header['제휴담당자'] = 'string';
	$header['경리담당자'] = 'string';
}
if($rentshop) {
	$header['정산예정일'] = 'string';
}
$header['예약건수'] = 'string';
$header['결제금액'] = 'string';
$header['할인액'] = 'string';
$header['총금액'] = 'string';
$header['수수료'] = 'string';
$header['정산예정금액'] = 'string';
$header['정산완료금액'] = 'string';
$header['미정산금액'] = 'string';
$header['상태'] = 'string';

//컬럼 제목
$column_map = [];
if($dt) {
	$column_map['회사명(지점)'] = 'data1';
	$column_map['은행명(은행코드)'] = 'data2';
	$column_map['계좌번호'] = 'data3';
	$column_map['예금주'] = 'data4';
	$column_map['사업자등록번호'] = 'dataBusinessNum';
	$column_map['제휴담당자'] = 'dataContact2';
	$column_map['경리담당자'] = 'dataContact3';
}
if($rentshop) {
	$column_map['정산예정일'] = 'dataDt';
}
$column_map['예약건수'] = 'data5';
$column_map['결제금액'] = 'data6';
$column_map['할인액'] = 'data7';
$column_map['총금액'] = 'data8';
$column_map['수수료'] = 'dataCommission';
$column_map['정산예정금액'] = 'data9';
$column_map['정산완료금액'] = 'data10';
$column_map['미정산금액'] = 'data11';
$column_map['상태'] = 'data12';

//데이터 쿼리 세션에서 쿼리 가져옴
if ($_SESSION['sql_excel']) {
	$q = $_SESSION['sql_excel'];
	$r = mysql_query($q);

	//데이터 가져오기 및 재가공
	while ($row = mysql_fetch_array($r)) {
		$indata['data1'] = $row['rentshop'];
		$indata['data2'] = getBankName($row['bank'], true);
		$indata['data3'] = $row['bankaccount'];
		$indata['data4'] = $row['bankname'];
		$indata['dataBusinessNum'] = $row['businessnum'];
		$indata['dataContact2'] = $row['dname2'] ? $row['dname2'].' ('.phone_number_format($row['dcp2']).')' : '';
		$indata['dataContact3'] = $row['dname3'] ? $row['dname3'].' ('.phone_number_format($row['dcp3']).')' : '';
		$indata['dataDt'] = $row['dt'];
		$indata['data5'] = number_format($row['count']);
		$indata['data6'] = number_format($row['account']);
		$indata['data7'] = number_format($row['discount']);
		$indata['data8'] = number_format($row['total']);
		$indata['dataCommission'] = number_format($row['commission']);
		$indata['data9'] = number_format($row['settlement']);
		$indata['data10'] = number_format($row['settlement_finish']);
		$indata['data11'] = number_format($row['settlement']-$row['settlement_finish']);
		$indata['data12'] = $row['settlement'] == $row['settlement_finish'] ? '정산완료' : '';

		$data[] = $indata;
	}
}