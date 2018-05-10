<?
/**
* NFUpload - 플래시 기반의 업로드 프로그래스바가 지원되는 멀티업로드 프로그램
*
* 라이센스 : 프리웨어 (개인/회사 구분없이 무료로 사용가능)
* 제작사 : 패스코리아넷 (http://passkorea.net/)
*
* 배포시 주의사항 : 제작사와 라이센스 정보를 삭제하시면 안됩니다.
*/

$__NFUpload = array();

// 업로드할 파일이 저장될 디렉토리.  퍼미션을 777로 조절 필요.  ex) chmod -R 777 data
$__NFUpload['dir'] = './data';

// 최대 업로드 용량 - 파일 하나당(단위 KB).  ex) 10240 => 10MB
$__NFUpload['max_size'] = 51200;

// 최대 업로드 용량 - 전체 파일(단위 KB).  ex) 10240 => 10MB
$__NFUpload['max_size_total'] = 51200;

// 최대 파일갯수 제한.
$__NFUpload['max_count'] = 10;

// 업로드시 중복파일 덮어씌우기 여부.
#$__NFUpload['file_overwrite'] = true;		// 원본파일명 유지, 덮어씌우기 모드.
#$__NFUpload['limit_ext'] = 'php;php3;php4;html;htm;inc;js';		// 업로드를 제한할 파일 확장자명.
$__NFUpload['file_overwrite'] = false;		// 고유파일명으로 변환.  자동중복방지, 파일확장자 제한 불필요.  (권장)
$__NFUpload['limit_ext'] = '';		// 업로드를 제한할 파일 확장자명.

// 한글 인코딩 방법.
$__NFUpload['charset'] = 'euc-kr';		// PHP iconv 모듈이 반드시 있어야 함!
#$__NFUpload['charset'] = 'utf-8';		// 웹페이지가 utf-8로 제작된 경우에 한함.

// 디버깅 모드 사용여부.(업로드 문제시 원인 분석가능)
$__NFUpload['debug'] = false;