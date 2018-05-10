<?
/**
 * 어드민 > 고객관리 > 커넥터 > 엑셀
 * admin.rentking.co.kr/member.php?code=salesdealer
 * 커넥터 정보 리스트 엑셀
 */
$excel_filename = "saledealer.xlsx";

//컬럼 형식
$header = array(
	'No.'=>'string',
	'성명'=>'string',
	'아이디'=>'string',
	'핸드폰'=>'string',
	'가입일'=>'string',
	'가입처'=>'string',
	'보유포인트'=>'string',
	'생년월일'=>'string',
	'성별'=>'string'
);

//컬럼 제목
$column_map = array(
	'No.'=>'data1',
	'성명'=>'data2',
	'아이디'=>'data3',
	'핸드폰'=>'data5',
	'가입일'=>'data9',
	'가입처'=>'data10',
	'보유포인트'=>'data11',
	'생년월일'=>'data12',
	'성별'=>'data13'
);

//데이터 쿼리 세션에서 쿼리 가져옴
if ( $_SESSION['sql_excel'] ) {
	$q = $_SESSION['sql_excel'];
	$r = mysql_query($q);

	//데이터 가져오기 및 재가공
	while ($row = mysql_fetch_array($r)) {
		$indata['data1'] = $row['idx'];
		$indata['data2'] = $row['name'];
		$indata['data3'] = $row['id'];
		$indata['data5'] = $row['cp'];
		$indata['data9'] = $row['signdate'];
		if ($row['pid'] == 'A') {
			$indata['data10'] = "관리자 등록";
		} else if ($row['pid'] == 'W') {
			$indata['data10'] = "홈페이지 가입";
		} else {
			$indata['data10'] = "모바일 가입";
		}
		$indata['data11'] = $row['memberpoint'];
		$indata['data12'] = $row['birth'];

		if($row['sex']=='M') { $indata['data13'] = "남"; } else { $indata['data13'] = "여";	}

		$data[] = $indata;
	}
}