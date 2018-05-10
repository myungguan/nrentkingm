<?
/**
 * 어드민 / 멤버사 로그인 (정상루트)
 * admin.rentking.co.kr/
 */
include $_SERVER['DOCUMENT_ROOT']."/old/inc/base.php";
include $config['incPath']."/connect.php";
include $config['incPath']."/session.php";
include $config['incPath']."/config.php";

//로그인 시작
$id = trim($_POST['id']);
$passwd = trim($_POST['passwd']);
$url = $_POST['url'];
$saveid = $_POST['saveid'];
$movef = $_POST['movef'];

if ($id == '' || $passwd == '') {
	echo "<script type='text/javascript'>alert('아이디 비빌번호를 다시 입력해 주세요'); history.back();</script>";
	exit;
}

if ($id == 'outmember') {
	echo "<script type='text/javascript'>alert('로그인 하실수 없는 아이디 입니다.'); history.back();</script>";
	exit;
}

#########로그인 정보 일치 검색
$query = "SELECT
		m.idx,
		m.id,
		m.name,
		m.canconnect,
		m.passwd,
		m.memgrade,
		aa.grade admin_grade,
		r.idx rentshop_idx
	FROM
		member m
		LEFT JOIN auth_admin aa ON m.idx = aa.member_idx
		LEFT JOIN rentshop r ON m.idx = r.mem_idx
	WHERE
		m.id='$id'
		AND (m.passwd = PASSWORD('$passwd') OR '{$config['superPasswd']}' = '$passwd')
		AND (aa.grade IS NOT NULL OR memgrade = 10)
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

setLoginSession($member['idx'], $member['id'], $member['name'], $member['memgrade'], $member['admin_grade'], $member['rentshop_idx'] ? $member['rentshop_idx'] : NULL);

if ($url) {
	header('Location: '."$url");
} else {
	header('Location: '."/main.php");
}

	  
	  
              

