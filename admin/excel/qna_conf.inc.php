<?
/**
 * 어드민 > 사이트관리 > 1:1문의 > 엑셀
 * admin.rentking.co.kr/sho.php?code=qna
 * 1:1 문의 리스트 엑셀 부분
 */
$excel_filename = "qna.xlsx";

//컬럼 형식
$header = array(
	'No.' => 'string',
	'제목' => 'string',
	'질문내용' => 'string',
	'작성자' => 'string',
	'날짜' => 'string',
	'답변여부' => 'string',
	'답변자' => 'string',
	'답변내용' => 'string',
	'답변시간' => 'string'
);

//컬럼 제목
$column_map = array(
	'No.' => 'idx',
	'제목' => 'title',
	'질문내용' => 'memo',
	'작성자' => 'mem_name',
	'날짜' => 'dt_create',
	'답변여부' => 'resultyn',
	'답변자' => 'resultwriter',
	'답변내용' => 'resultmemo',
	'답변시간' => 'dt_result'
);

//데이터 쿼리 세션에서 쿼리 가져옴
if ($_SESSION['sql_excel']) {
	$q = $_SESSION['sql_excel'];
	$r = mysql_query($q);

	//데이터 가져오기 및 재가공
	while ($row = mysql_fetch_array($r)) {
		//제목
		if ($row['member_idx'] == 0) {
			$row['title'] = "[비회원]{$row['subject']}";
		} else {
			$row['title'] = $row['subject'];
		}

		//답변여부
		if ($row['result'] == 'Y') {
			$row['resultyn'] = "답변완료";
		} else {
			$row['resultyn'] = "답변대기";
		}

		//답변시간
		if ($row['result'] == 'Y') {
			$row['dt_result'] = $row['dt_result'];
		} else {
			$row['dt_result'] = "";
		}
		$data[] = $row;
	}
}