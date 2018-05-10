<?
/**
 * 어드민 > 고객관리 > 회원 > 엑셀
 * admin.rentking.co.kr/settlement.php?code=list
 * 회원 리스트 엑셀 부분
 */
$excel_filename = "정산내역_링크프라이스_".date('YmdHis').".xlsx";

//컬럼 형식
$header = array(
	'예약번호' => 'string',
	'LPINFO' => 'string',
	'주문코드' => 'string',
	'상품코드' => 'string',
	'고객명' => 'string',
	'예약구분' => 'string',
	'예약일시' => 'string',
	'픽업일시' => 'string',
	'반납일시' => 'string',
	'대여지점' => 'string',
	'상태' => 'string',
	'결제금액' => 'string'
);

//컬럼 제목
$column_map = array(
	'예약번호' => 'payment_idx',
	'LPINFO' => 'lpinfo',
	'주문코드' => 'order_code',
	'상품코드' => 'product_code',
	'고객명' => 'mem_name',
	'예약구분' => 'retype',
	'예약일시' => 'dt_create',
	'픽업일시' => 'sdate',
	'반납일시' => 'edate',
	'대여지점' => 'rentshop',
	'상태' => 'status',
	'결제금액' => 'price'
);

//데이터 쿼리 세션에서 쿼리 가져옴
if ($_SESSION['sql_excel']) {
	$q = $_SESSION['sql_excel'];
	$r = mysql_query($q);

	//데이터 가져오기 및 재가공
	while ($row = mysql_fetch_array($r)) {
		$indata['payment_idx'] = $row['payment_idx'];
		$indata['lpinfo'] = $row['lpinfo'];
		$indata['order_code'] = $row['order_code'];
		$indata['product_code'] = $row['product_code'];
		$indata['mem_name'] = $row['mem_name'];
		$indata['retype'] = $row['retype'];
		$indata['dt_create'] = $row['dt_create'];
		$indata['sdate'] = $row['sdate'];
		$indata['edate'] = $row['edate'];
		$indata['rentshop'] = $row['rentshop'];
		$indata['status'] = $row['status'];
		$indata['price'] = number_format($row['price']);

		$data[] = $indata;
	}
}