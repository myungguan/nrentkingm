<?
include $_SERVER['DOCUMENT_ROOT']."/old/inc/base.php";
include $config['incPath']."/connect.php";
include $config['incPath']."/session.php";
include $config['incPath']."/config_site.php";
include $config['incPath']."/access.php";

$mode = $_REQUEST['mode'];
if($mode == 'r') {
	$QUERY_STRING = $_SERVER["QUERY_STRING"];
	$PHP_SELF = $_SERVER["PHP_SELF"];
	if($QUERY_STRING) {
		$url = $PHP_SELF . "?" . $QUERY_STRING;
	} else {
		$url = $PHP_SELF;
	}

	$url = urlencode($url);

	if(!$_SESSION['member_id'])
	{
		echo "<script> alert('로그인 후 쿠폰 발급이 가능합니다.'); location.replace('/member/login.php?url=/member/coupon.php');</script>";
		exit;
	}

	$nums = $_REQUEST['nums'];

	$coupon_idx = null;

	$ar_serial = null;
	if(isset($nums)) {
		$n = mysql_escape_string($nums);
		$query = "SELECT * FROM coupons WHERE type='SE' AND (serialnum='$n' OR REPLACE(serialnum, '-', '') = '$n') AND dt_delete IS NULL ORDER BY idx DESC LIMIT 0, 1";
		$r = mysql_query($query);

		if(mysql_num_rows($r) < 1) {
			$query = "SELECT * FROM coupon_serials WHERE (serialnum='$n' OR REPLACE(serialnum, '-', '') = '$n') AND dt_use IS NULL ORDER BY idx LIMIT 0, 1";
			$r = mysql_query($query);

			if(mysql_num_rows($r) > 0) {
				$ar_serial = mysql_fetch_assoc($r);
				$coupon_idx = $ar_serial['coupon_idx'];
			}

		} else {
			$coupon_idx = mysql_fetch_assoc($r)['idx'];
		}
	}

	if(!$coupon_idx) {
		echo "<script>alert('등록가능한 쿠폰을 찾을 수 없습니다.'); location.replace('/member/coupon.php'); </script>";
		exit;
	}

	$ar_coupon = sel_query_all("coupons"," where idx='$coupon_idx'");


	if(!$ar_coupon)
	{
//		echo "<script>alert('잘못된 정보입니다'); location.replace('/member/coupon.php'); </script>";
		exit;
	}

	if($ar_coupon['dt_delete'])
	{
		echo "<script>alert('발급가능한 쿠폰이 아닙니다.'); location.replace('/member/coupon.php'); </script>";
		exit;
	}

	if($ar_coupon['dt_publish_start']>date("Y-m-d H:i:s") || $ar_coupon['dt_publish_end']<date("Y-m-d H:i:s"))
	{
		echo "<script>alert('발급가능한 날짜가 아닙니다.'); location.replace('/member/coupon.php'); </script>";
		exit;
	}

    //같은 쿠폰 종류일경우 여러번 사용 불가능한 오류 발생
//    $q = "select * from member_coupons where member_idx='$g_memidx' and coupon_idx='$coupon_idx'";
//    $r = mysql_query($q);
//	$isit = mysql_num_rows($r);
//
//	if($isit!=0)
//	{
//		echo "<script>alert('이미 쿠폰을 받으셨습니다.'); location.replace('/member/coupon.php'); </script>";
//		exit;
//	}

	if($ar_serial) {
		$value = array();
		$value['dt_use'] = date('Y-m-d H:i:s');
		update('coupon_serials', $value, " WHERE idx={$ar_serial['idx']}");
	}
	make_coupon($ar_coupon,$g_memidx);

	echo "<script>alert('{$ar_coupon['name']} 이 발급되었습니다');location.replace('/member/coupon.php'); </script>";
	exit;
}

$q = "SELECT
		cm.*,
		c.name,
		c.dt_publish_start,
		c.dt_publish_end,
		cm.dt_create,
		LEFT(cm.sdate, 16) sdate,
		LEFT(cm.edate, 16) edate,
		f.path
	FROM
		member_coupons cm
		LEFT JOIN coupons c ON cm.coupon_idx = c.idx
		LEFT JOIN files f ON c.idx = f.article_idx AND f.article_type='coupon'
	WHERE
		cm.member_idx = '$g_memidx' AND
		cm.sdate<='".date("Y-m-d H:i:s")."' AND 
		cm.edate>='".date("Y-m-d H:i:s")."' AND 
		cm.dt_use IS NULL";
$r = mysql_query($q);
$clist = [];
while($row = mysql_Fetch_array($r))	{

	$row['usecars'] = "전차종사용가능";

	$row['userentshop'] = "전지점사용가능";

	$clist[] = $row;
}


$page['type'] = "2";
$page['title'] = "쿠폰북";
$tpls->assign('clist', $clist);
$tpls->assign('page', $page);
$tpls->print_('mains');
?>
