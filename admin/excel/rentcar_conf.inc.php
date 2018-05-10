<?
/**
 * 어드민 > 고객관리 > 멤버사 > 엑셀
 * admin.rentking.co.kr/member.php?code=rentcar
 * 멤버사 리스트 엑셀 부분
 */
$excel_filename = "rentcar.xlsx";

//컬럼 형식
$header = array(
	'No.' => 'string',
	'회사명(지점)' => 'string',
	'지역(시/구)' => 'string',
	'대표자' => 'string',
	'전화번호' => 'string',
	'보유' => 'string',
	'등록' => 'string',
	'인증상태' => 'string',
	'등록일' => 'string',
	'은행명' => 'string',
	'계좌번호' => 'string',
	'예금주' => 'string',
	'제휴담당자' => 'string',
	'제휴담당자 Mobile' => 'string',
	'경리담당자' => 'string',
	'경리담당자 Mobile' => 'string'
);

//컬럼 제목
$column_map = array(
	'No.' => 'data1',
	'회사명(지점)' => 'data2',
	'지역(시/구)' => 'data3',
	'대표자' => 'data4',
	'전화번호' => 'data5',
	'보유' => 'data61',
	'등록' => 'data62',
	'인증상태' => 'data7',
	'등록일' => 'data8',
	'은행명' => 'data9',
	'계좌번호' => 'data10',
	'예금주' => 'data11',
	'제휴담당자' => 'data12',
	'제휴담당자 Mobile' => 'data13',
	'경리담당자' => 'data14',
	'경리담당자 Mobile' => 'data15'
);

//데이터 쿼리 세션에서 쿼리 가져옴
if ($_SESSION['sql_excel']) {
	$q = $_SESSION['sql_excel'];
	$r = mysql_query($q);

	//데이터 가져오기 및 재가공
	while ($row = mysql_fetch_array($r)) {

		$indata['data1'] = $row['idx'];
		$indata['data2'] = "{$row['name']}({$row['affiliate']})";
		$indata['data3'] = $row['addr1'];
		$indata['data4'] = $row['dname1'];
		$indata['data5'] = $row['dphone1'];
		$indata['data61'] = $row['totalcar'];
		$indata['data62'] = $row['isit'];
		$indata['data7'] = $row['isok'] == 'Y' ? 'ON' : 'OFF';
		$indata['data8'] = $row['signdate'];
		$indata['data9'] = $row['bank'];
		$indata['data10'] = $row['bankaccount'];
		$indata['data11'] = $row['bankname'];
		$indata['data12'] = $row['dname2'];
		$indata['data13'] = $row['dcp2'];
		$indata['data14'] = $row['dname3'];
		$indata['data15'] = $row['dcp3'];

		$data[] = $indata;
	}
}