<?
$sessionLifeTime = ini_get('session.gc_maxlifetime');
if($config['remoteAddr'] == '127.0.0.1' || $config['remoteAddr'] == '218.55.101.75' || $config['remoteAddr'] == '183.96.202.249') {
	$sessionLifeTime = 7200;
}

ini_set('session.gc_probability', 1);
ini_set('session.gc_divisor', 1);
ini_set('session.gc_maxlifetime', $sessionLifeTime);
session_set_cookie_params(0, "/", 'rentking.co.kr');
session_start();

//자동로그인
if(!isset($_SESSION['member_id']) && isset($_COOKIE['autoKey'])) {
	$auth = $_COOKIE['autoKey'];
	$auth = explode('|', decrypt($auth, $config['encryptionKey']));
	if(count($auth) >= 2) {
		$query = "SELECT
				m.idx,
				m.id,
				m.name,
				m.canconnect,
				m.passwd,
				m.memgrade,
				aa.grade admin_grade
			FROM
				member m
				LEFT JOIN auth_admin aa ON m.idx = aa.member_idx
			WHERE
				m.idx = {$auth[0]}
				AND m.remember_token = '{$auth[1]}'
				AND m.id <> 'outmember'
			LIMIT 0, 1";
		$r = mysql_query($query);

		if(mysql_num_rows($r) > 0) {
			$member = mysql_fetch_assoc($r);
			setLoginSession($member['idx'], $member['id'], $member['name'], $member['memgrade'], $member['admin_grade']);

			setcookie('autoKey', $_COOKIE['autoKey'], time() + (60 * 60 * 24 * 365), '/', 'rentking.co.kr');
		} else {
//			setcookie('autoKey', null, -1, '/', 'rentking.co.kr');
		}
	} else {
//		setcookie('autoKey', null, -1, '/', 'rentking.co.kr');
	}

}

//if(substr($_SERVER['HTTP_HOST'], 0, 5) != 'admin')
//	logVisit();
//
?>
