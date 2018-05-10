<?
/**
 * NFUpload - 플래시 기반의 업로드 프로그래스바가 지원되는 멀티업로드 프로그램
 *
 * 라이센스 : 프리웨어 (개인/회사 구분없이 무료로 사용가능)
 * 제작사 : 패스코리아넷 (http://passkorea.net/)
 *
 * 배포시 주의사항 : 제작사와 라이센스 정보를 삭제하시면 안됩니다.
 */

// [NFUpload] 파일 업로드 처리
require_once('nfupload_conf.inc.php');        // NFUpload Config

// [NFUpload] 디버그 기록 남기기
if ($__NFUpload['debug']) {
	$__NFUpload['debug_msg'] .= date("Y-m-d H:i:s") . " | " . ($_SERVER['HTTP_CF_CONNECTING_IP'] ? $_SERVER['HTTP_CF_CONNECTING_IP'] : $_SERVER['REMOTE_ADDR']) . "\n";
	ob_start();
}    // if()

$__NFUpload['result'] = false;
if (isset($_FILES['upfile'])) {
	if (is_uploaded_file($_FILES['upfile']['tmp_name'])) {
		$_FILES['upfile']['name'] = stripslashes($_FILES['upfile']['name']);

		// 웹페이지가 UTF-8 환경이 아닌 일반적인 EUC-KR 일경우의 변환조치
		if (strtolower($__NFUpload['charset']) != 'utf-8')
			$_FILES['upfile']['name'] = iconv('utf-8//IGNORE', 'cp949', $_FILES['upfile']['name']);

		// 업로드 파일 확장자 체크
		if ($__NFUpload['limit_ext'] == '' || ($__NFUpload['limit_ext'] != '' && !preg_match('/[.]+(' . str_replace(';', '|', $__NFUpload['limit_ext']) . ')+/i', $_FILES['upfile']['name']))) {
			// 업로드 용량 체크
			if ($_FILES['upfile']['size'] <= $__NFUpload['max_size'] * 1024) {
				// 업로드 디렉토리로 파일 복사
				if ($_POST['RenameFile'])        // 업로드용 고유 파일명이 있을 때.
				{
					$__NFUpload['result'] = move_uploaded_file($_FILES['upfile']['tmp_name'], $__NFUpload['dir'] . DIRECTORY_SEPARATOR . $_POST['RenameFile']);
				} else {
					// 파일 중복여부 검사.(사용자 정의 루틴 추가 가능,  덮어씌울지, 생략할지 등)
					//if (file_exists($__NFUpload['dir'].DIRECTORY_SEPARATOR.$_FILES['upfile']['name'])) { // action }

					$__NFUpload['result'] = move_uploaded_file($_FILES['upfile']['tmp_name'], $__NFUpload['dir'] . DIRECTORY_SEPARATOR . $_FILES['upfile']['name']);
				}    // if()

				if ($__NFUpload['debug'])
					$__NFUpload['debug_msg'] .= $__NFUpload['result'] ? "[OK]업로드 성공!!!" : "[Error]업로드 실패 - move_uploaded_file()";
			} else {
				if ($__NFUpload['debug'])
					$__NFUpload['debug_msg'] .= "[Error]업로드 실패 - 업로드가능한 파일의 크기를 초과하였습니다. =>  \$__NFUpload['max_size'] : " . number_format($__NFUpload['max_size'] * 1024);
			}    // if()
		} else {
			if ($__NFUpload['debug'])
				$__NFUpload['debug_msg'] .= "[Error]업로드 실패 - 다음 확장자를 가진 파일은 업로드할 수 없도록 제한되어 있습니다. =>  \$__NFUpload['limit_ext'] : " . $__NFUpload['limit_ext'];
		}    // if()
	} else {
		if ($__NFUpload['debug'])
			$__NFUpload['debug_msg'] .= "[Error]업로드 실패 - is_uploaded_file()";
	}    // if()
} else {
	if ($__NFUpload['debug'])
		$__NFUpload['debug_msg'] .= "[Error]업로드 실패 - php.ini 의 post_max_size 값이 업로드할 크기보다 적게 설정되었을 때 주로 발생하는 문제입니다.";
}    // if()

// [NFUpload] 디버그 기록 남기기
if ($__NFUpload['debug']) {
	$__NFUpload['debug_msg'] .= "\n";
	#echo "\$_COOKIE"."\n";
	#var_dump($_COOKIE);
	echo "\$_FILES" . "\n";
	var_dump($_FILES);
	echo "\$_POST" . "\n";
	var_dump($_POST);
	$__NFUpload['debug_msg'] .= ob_get_contents();
	$__NFUpload['debug_msg'] .= "\n\n";
	ob_end_clean();

	$log_file = $__NFUpload['dir'] . DIRECTORY_SEPARATOR . '__nfupload_debug.txt';
	$fp = fopen($log_file, 'a');
	fwrite($fp, $__NFUpload['debug_msg']);
	fclose($fp);
}    // if()

// [NFUpload] 파일 실패를 알림(I/O error 표시)
if (!$__NFUpload['result'])
	header("HTTP/1.0 500 Not found");
