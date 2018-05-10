<?
/**
 * 엑셀 다운
 */

/** Set Memory Limit 1.0 */
ini_set('memory_limit', '-1');

include $_SERVER['DOCUMENT_ROOT']."/old/inc/base.php";
include $config['incPath']."/connect.php";
include $config['incPath']."/session.php";
include $config['incPath']."/config.php";

/** https://github.com/mk-j/PHP_XLSXWriter */
require_once $config['incPath']."/PHP_XLSXWriter/xlsxwriter.class.php";

if ($act) {
	$_act_file = "{$act}_conf.inc.php";
	if (is_file($_act_file)) {
		include_once $_act_file;

		if (!$excel_filename) {
			echo "Error - excel filename not set!";
			exit;
		}
	} else {
		echo "Error - act file not found!!";
		exit;
	}
} else {
	echo "Error - act parameter not found!";
	exit;
}

if (is_array($column_map) && count($column_map) == count($header)) {
	if (count($data)) {
		foreach ($data as $row) {
			unset($_temp);
			foreach ($column_map as $k) {
				$_temp[] = $row[$k];
			}
			$write_data[] = $_temp;
		}
	} else {
		$write_data[] = array('검색된 데이터가 없습니다.');
	}
} else {
	$write_data[] = array('다운로드 항목 설정이 잘못 되었습니다. 관리자 문의');
}

$ie = isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false || strpos($_SERVER['HTTP_USER_AGENT'], 'Trident') !== false);
$fileName = XLSXWriter::sanitize_filename($excel_filename);
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header('Content-Transfer-Encoding: binary');
header('Set-Cookie: fileDownload=true; path=/');
if(!$ie) {
	header('Cache-Control: no-cache, must-revalidate');
	header('Pragma: no-cache');
} else {
	$fileName = iconv('utf-8', 'euc-kr', $fileName);
	header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	header('Pragma: public');
}
header('Content-disposition: attachment; filename="' . $fileName . '"');


$writer = new XLSXWriter();
$writer->setAuthor('bizhost');
$writer->writeSheet($write_data, 'Sheet1', $header);
$writer->writeToStdOut();
exit;