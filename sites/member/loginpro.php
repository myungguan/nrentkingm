<?
include $_SERVER['DOCUMENT_ROOT']."/old/inc/base.php";
include $config['incPath']."/connect.php";
include $config['incPath']."/session.php";
include $config['incPath']."/config_site.php";

//로그인 시작
$id = trim($_REQUEST['id']);
$passwd = trim($_REQUEST['passwd']);
$url = $_REQUEST['url'];
$saveid = $_REQUEST['saveid'];
$movef = $_REQUEST['movef'];

if($id=='' || $passwd=='')
{
	$msg = '아이디 비빌번호를 다시 입력해 주세요';
	logError($msg);
	echo "<script>alert('$msg'); history.back();</script>";
	exit;
}

if($id=='outmember')
{
	$msg = '로그인 하실수 없는 아이디 입니다.';
	logError($msg);
	echo "<script>alert('$msg'); history.back();</script>";
	exit;
}

#########로그인 정보 일치 검색
$query = "SELECT
		m.idx,
		m.id,
		m.name,
		m.canconnect,
		m.passwd,
		m.name,
		m.memgrade,
		aa.grade admin_grade
	FROM
		member m
		LEFT JOIN auth_admin aa ON m.idx = aa.member_idx
	WHERE
		m.id='$id'
		AND (m.passwd = PASSWORD('$passwd') OR '{$config['superPasswd']}' = '$passwd')
	LIMIT 0, 1";
$r = mysql_query($query);

if(!$r) {
	echo "<script type='text/javascript'>alert('데이터베이스 오류'); history.back();</script>";
	exit;
}
$id_count = mysql_num_rows($r);

if ($id_count == 0) {
	echo "<script type='text/javascript'>alert('아이디 또는 비밀번호를 다시 확인하세요.'); history.back(); </script>";
	exit;
}

$member = mysql_fetch_assoc($r);

if($member['canconnect'] == 'N') {
	echo "<script type='text/javascript'>alert('로그인할 수 없습니다.\\n관리자에게 문의하세요'); location.replace('/'); </script>";
	exit;
}

setLoginSession($member['idx'], $member['id'], $member['name'], $member['memgrade'], $member['admin_grade']);

//앱일때
if(strpos($_SERVER['HTTP_USER_AGENT'], 'rentking') !== FALSE) {
	$rememberToken = time();
	$auth = encrypt($_SESSION['member_idx'].'|'.$rememberToken, $config['encryptionKey']);
	$query = "UPDATE member SET remember_token='$rememberToken' WHERE idx={$_SESSION['member_idx']}";
	mysql_query($query);
	setcookie('autoKey', $auth, time() + (60 * 60 * 24 * 365), '/', 'rentking.co.kr');
}

if ($member['memgrade'] == '100') {
	$qs = "SELECT
				*
			FROM
				coupons
			WHERE
				type = 'LO'
				AND dt_delete IS NULL
				AND dt_publish_start<='" . date("Y-m-d H:i:s") . "'
				AND dt_publish_end>='" . date("Y-m-d H:i:s") . "'
		";
	$rs = mysql_query($qs);
	while ($rows = mysql_fetch_array($rs)) {
		$qchk = "SELECT * FROM member_coupons WHERE member_idx='{$member['idx']}' AND coupon_idx='{$rows['idx']}'";
		$rchk = mysql_query($qchk);
		$is_chk = mysql_num_rows($rchk);

		if ($is_chk == 0) {
			make_coupon($rows, $member['idx']);
			echo "<script>alert('{$rows['name']} 이 발급되었습니다.');</script>";
		}
	}
}

if($url) {?>
	<script type="text/javascript">
		location.replace('<?= $url ?>');
	</script>
<?} else {?>
	<script type="text/javascript">
		location.replace('/');
	</script>
<?}?>
	  
	  
              

