<?
/**
 * 사이트 > 회원가입
 * www.rentking.co.kr/member/join.php
 * 회원가입 페이지
 */

/* 크로스타겟(모바일광고) 스크립트 설치(180214) */
include_once($_SERVER['DOCUMENT_ROOT']."/old/inc/plugins/progressmedia_cts.php");

$mtype = $_REQUEST['mem_type'];

if (trim($_REQUEST['passwd']) == '' && $_REQUEST['channel'] == '' && $_REQUEST['ch_id'] == '') {
	exit;
}

$value['name'] = $_REQUEST['name'];
$value['id'] = $_REQUEST['id'];
$id = $value['id'];

$q = "SELECT * FROM member WHERE id='{$id}'";
$r = mysql_query($q);
$isit = mysql_num_rows($r);

if ($isit != 0) {
	$msg = '이미 가입된 아이디입니다';
	logError($msg);
	echo "<script>alert('$msg'); history.back(); </script>";
	exit;
}

$value['join_ip'] = ($_SERVER['HTTP_CF_CONNECTING_IP'] ? $_SERVER['HTTP_CF_CONNECTING_IP'] : $_SERVER['REMOTE_ADDR']);
$value['join_session_id'] = logSessionId();
$value['join_session_id_real'] = session_id();
$value['passwd'] = $_REQUEST['passwd'];
$passwd = $_REQUEST['passwd'];
$value['birth'] = $_REQUEST['birth'];
$value['sex'] = $_REQUEST['sex'];
$value['cp'] = $_REQUEST['cp'];
$value['mailser'] = $_REQUEST['mailser'];
$value['smsser'] = $_REQUEST['smsser'];
$value['pushser'] = $_REQUEST['pushser'];
$value['signdate'] = date("Y-m-d H:i:s");
$value['pid'] = $pid;
$value['social_type'] = $_REQUEST['channel'];
$value['social_code'] = $_REQUEST['ch_id'];


if ($mtype == 'p') {
	$value['memgrade'] = "100";
	$memg = "100";
}
if ($mtype == 'c') {
	$value['memgrade'] = "99";
	$memg = "99";
}
if ($mtype == 'd') {
	$value['memgrade'] = "12";
	$memg = "12";
}
$value['canconnect'] = "Y";
$r = insert("member", $value);
if (!$r) {
	$msg = '회원가입실패 하였습니다. 다시 시도해주세요.';
	logError($msg);
	echo "<script>alert('$msg');</script>";
	exit;
}

$redirect = isset($_SESSION['redirect']) ? $_SESSION['redirect'] : null;
$idx = mysql_insert_id();
### 세션 생성

############ 세션 변수에 등록
setLoginSession($idx, $id, $value['name'], $value['memgrade']);

//앱일때
if(strpos($_SERVER['HTTP_USER_AGENT'], 'rentking') !== FALSE) {
	$rememberToken = time();
	$auth = encrypt($_SESSION['member_idx'].'|'.$rememberToken, $config['encryptionKey']);
	$query = "UPDATE member SET remember_token='$rememberToken' WHERE idx={$_SESSION['member_idx']}";
	mysql_query($query);
	setcookie('autoKey', $auth, time() + (60 * 60 * 24 * 365), '/', 'rentking.co.kr');
}

if ($memg == '100') {
	$qs = "SELECT
				*
			FROM
				coupons
			WHERE
				type IN ('LO', 'SI')
				AND dt_delete IS NULL
				AND dt_publish_start<='" . date("Y-m-d H:i:s") . "'
				AND dt_publish_end>='" . date("Y-m-d H:i:s") . "'
		";
	$rs = mysql_query($qs);
	while ($rows = mysql_fetch_array($rs)) {
		$qchk = "SELECT * FROM member_coupons WHERE member_idx='{$idx}' AND coupon_idx='{$rows['idx']}'";
		$rchk = mysql_query($qchk);
		$is_chk = mysql_num_rows($rchk);

		if ($is_chk == 0) {
			make_coupon($rows, $idx);
			echo "<script>alert('{$rows['name']} 이 발급되었습니다.');</script>";
		}
	}
}

?>
<script type="text/javascript">
	//<![CDATA[
	var SearchNetworkClickConversionDctSv="type=2,orderID=,amount=";
	var SearchNetworkClickConversionAccountID="345";
	if(location.protocol!="file:"){
		document.write(unescape("%3Cscript%20type%3D%22text/javas"+"cript%22%20src%3D%22"+(location.protocol=="https:"?"https":"http")+"%3A//sccl.dreamad.co.kr/NCDC_V2.js%22%3E%3C/script%3E"));
	}
	//]]>
</script>
<!-- 타겟팅 게이츠 트래킹 스크립트: 마케팅팀 요청(180305) -->
<!-- WIDERPLANET PURCHASE SCRIPT START 2018.2.28 -->
<div id="wp_tg_cts" style="display:none;"></div>
<script type="text/java-script">
var wptg_tagscript_vars = wptg_tagscript_vars || [];
wptg_tagscript_vars.push(
(function() {
return {
wp_hcuid:"", /*고객넘버 등 Unique ID (ex. 로그인 ID, 고객넘버 등 )를 암호화하여 대입.
주의 : 로그인 하지 않은 사용자는 어떠한 값도 대입하지 않습니다./
ti:"39960", /*광고주 코드 /
ty:"PurchaseComplete", /*트래킹태그 타입 */
device:"web", /*디바이스 종류 (web 또는 mobile)/
items:[{i:"비용상담 ", /*전환 식별 코드 (한글 , 영어 , 번호 , 공백 허용 )*/
t:"비용상담 ", /*전환명 (한글 , 영어 , 번호 , 공백 허용 )*/
p:"1", /*전환가격 (전환 가격이 없을경우 1로 설정 )*/
q:"1" /*전환수량 (전환 수량이 고정적으로 1개 이하일 경우 1로 설정 )*/
}]
};
}));
</script>
<script type="text/java-script" src="//cdn-aitg.widerplanet.com/js/wp_astg_4.0.js"></script>
<!-- // WIDERPLANET PURCHASE SCRIPT END 2018.2.28 -->
<script type="text/javascript">
    ex2cts.push('track', 'pageview_registrationsuceess');
	alert('회원가입이 완료되었습니다.');
	location.replace('<?= $redirect ? $redirect : '/' ?>');
</script>