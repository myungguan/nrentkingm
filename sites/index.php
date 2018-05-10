<?
include $_SERVER['DOCUMENT_ROOT']."/old/inc/base.php";
include $config['incPath']."/connect.php";
include $config['incPath']."/session.php";
include $config['incPath']."/config_site.php";

require_once($config['incPath']."/Mobile_Detect.php");

$detect = new Mobile_Detect();
if ($detect->isMobile()) {
	$isApp = false;
	$versionStatus = true;
	$appVersion = array(1, 0, 0);
	$platform = '';

	$ua = $_SERVER['HTTP_USER_AGENT'];

	$pos = strpos($ua, 'rentking');

	if ($pos !== false) {
		$isApp = true;
		$rentking = explode('/', substr($ua, $pos, strlen($ua) - $pos));
		if (count($rentking) > 1) {
			$appVersion = explode('.', $rentking[1]);
		}
	}

	if (stripos($ua, 'android') !== false) {
		$platform = 'android';
	}

	if (stripos($ua, 'iphone') !== false ||
		stripos($ua, 'ipad') !== false ||
		stripos($ua, 'ipod') !== false) {
		$platform = 'ios';
	}

	$query = "SELECT * FROM app WHERE platform='$platform' ORDER BY dt_release DESC LIMIT 0,1";
	$result = mysql_query($query);
	if (mysql_num_rows($result) > 0) {
		$row = mysql_fetch_assoc($result);

		$version = explode('.', $row['version']);
		$forceUpdate = $row['force_update'];

		if ($forceUpdate == 'Y') {
			if ($version[0] > $appVersion[0]) {
				$versionStatus = false;
			} else if ($version[0] == $appVersion[0] && $version[1] > $appVersion[1]) {
				$versionStatus = false;
			} else if ($version[0] == $appVersion[0] && $version[1] == $appVersion[1] && $version[2] > $appVersion[2]) {
				$versionStatus = false;
			}
		}
	}

	if ($isApp && !$versionStatus) { ?>
		<? if ($platform == 'android') { ?>
			<script type="text/javascript">
				alert("업데이트를 위하여 앱 마켓으로 이동합니다.");
				location.replace('market://details?id=kr.co.rentking');
			</script>
			<? exit;
		} ?>

		<? if ($platform == 'ios') { ?>
			<script type="text/javascript">
				alert("업데이트를 위하여 앱 마켓으로 이동합니다.");
				location.replace('itms-apps://itunes.apple.com/app/id1131927380');
			</script>
			<? exit;
		} ?>
	<? }
}

$page['type'] = "2";
$page['title'] = "렌트킹";

$q = "SELECT count(idx),sum(totalcar) FROM rentshop WHERE isok='Y'";
$r = mysql_query($q);
$row = mysql_fetch_row($r);
$page['rentshop'] = $row[0];
$page['renttcar'] = $row[1];

$q = "
	SELECT
		count(v.idx)
	FROM vehicles v
		LEFT JOIN rentshop r ON v.rentshop_idx = r.idx
		LEFT JOIN vehicle_price vp ON v.price_idx = vp.idx
		LEFT JOIN vehicle_price_longterm vpl ON v.price_longterm_idx = vpl.idx
	WHERE
		v.dt_delete IS NULL
		AND v.status = 'S'
		AND r.isok = 'Y'
		AND ( (vpl.price_longterm1 > 0 AND vpl.price_longterm2 > 0 AND vpl.price_longterm3 > 0 AND vpl.price_longterm_insu_exem > 0)
		  OR ((vp.price_insu0_check = 'Y' OR vp.price_insu1_check = 'Y' OR vp.price_insu2_check = 'Y' OR vp.price_insu3_check = 'Y') AND vp.price_hour > 0 AND vp.price > 0 AND vp.price_holiday > 0 AND vp.price_discount2 > 0 AND vp.price_discount3 > 0 AND vp.price_discount4 > 0))
";
$r = mysql_query($q);
$row = mysql_fetch_row($r);
$page['rentcar'] = $row[0];

$q = "SELECT
		COUNT(*)
	FROM (
		SELECT
			model_idx
		FROM vehicles v
			LEFT JOIN rentshop r ON v.rentshop_idx = r.idx
		WHERE
			v.dt_delete IS NULL
			AND r.isok = 'Y'
		GROUP BY
			model_idx
	) t";
$r = mysql_query($q);
$row = mysql_fetch_row($r);
$page['model'] = $row[0];


$page['description'] = true;

// 2017-10-01 eventKbcard001 이벤트
if(date('Y-m-d') >= '2017-09-01' && date('Y-m-d') < '2017-10-01') {
	$page['description'] = false;
	$page['eventKbcard001'] = true;
}

//2017-11-01 eventSamsungcard001 이벤트
if(date('Y-m-d H:i') >= '2017-10-01 09:00' && date('Y-m-d') < '2017-11-01') {
	$page['description'] = false;
	$page['eventSamsungcard001'] = true;
}

//2017-12-01 event201711 이벤트
if(date('Y-m-d H:i') >= '2017-11-01 09:00' && date('Y-m-d') < '2017-12-01') {
	$page['description'] = false;
	$page['event201711'] = true;
}

//2017-12-01 event201712 이벤트
if(date('Y-m-d H:i') >= '2017-12-01 09:00' && date('Y-m-d') < '2018-01-01') {
    $page['description'] = false;
    $page['event201712'] = true;
}

//2018-01-01 event201801 이벤트
if(date('Y-m-d H:i') >= '2018-01-01 09:00' && date('Y-m-d') < '2018-02-01' ) {
    $page['description'] = false;
    $page['event201801'] = true;
}

//2018-02-01 event201802 이벤트
if(date('Y-m-d H:i') >= '2018-02-01 09:00' && date('Y-m-d') < '2018-03-01' ) {
    $page['description'] = false;
    $page['event201802'] = true;
}

//2018-03-01 event201803 이벤트
if(date('Y-m-d H:i') >= '2018-03-01 09:00' && date('Y-m-d') < '2018-03-31' ) {
    $page['description'] = false;
    $page['event201803'] = true;
}

//2018-04-01 event201804 이벤트
if(date('Y-m-d H:i') >= '2018-04-01 00:00' && date('Y-m-d') < '2018-04-30' ) {
    $page['description'] = false;
    $page['event201804'] = true;
}

//applink 2018.02부터 노출
//다시 비활성화 2018.02.26 #1600
//if(date('Y-m-d H:i') >= '2018-02-01 09:00') {
//    $page['applink'] = true;
//}



$tpls->assign('page', $page);
$tpls->print_('mains');
