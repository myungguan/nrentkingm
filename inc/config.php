<?
/**
 * 환경설정
 */
extract($_REQUEST, EXTR_OVERWRITE);

$nowdate = time();
$nip = ($_SERVER['HTTP_CF_CONNECTING_IP'] ? $_SERVER['HTTP_CF_CONNECTING_IP'] : $_SERVER['REMOTE_ADDR']);

$QUERY_STRING = $_SERVER["QUERY_STRING"];
$PHP_SELF = $_SERVER["PHP_SELF"];
$PHP_SELF = str_replace("/newwww/admin","",$PHP_SELF);
$SERVER_NAME = $_SERVER["SERVER_NAME"];

if($QUERY_STRING)	{
	$_nowurl = $PHP_SELF . "?" . $QUERY_STRING;
}
else	{
	$_nowurl = $PHP_SELF;
}

$g_memid = null;
$ar_init_member = null;
if($_SESSION['member_idx'])	{
	$g_memidx = $_SESSION['member_idx'];
	$g_memid = $_SESSION['member_id'];
	$g_memname = $_SESSION['member_name'];

	$ar_init_member = sel_query_all("member"," where idx='$g_memidx'");
	if($ar_init_member['memgrade']=='10')	{
		$query = "SELECT
				r.*,
				m.id owner_id,
				m.name owner_name
			FROM rentshop r
				LEFT JOIN member m ON r.owner_idx = m.idx
			WHERE r.idx = {$_SESSION['rentshop_idx']}";
		$r = mysql_query($query);
		$ar_rshop = mysql_fetch_assoc($r);
	}
}

$ar_lin = array("FN"=>"1종보통","FL"=>"1종대형","SN"=>"2종보통");
$ar_retype = array("1"=>"단기","2"=>"장기");

$q = "SELECT * FROM codes WHERE ttype='2' AND dt_delete IS NULL ORDER BY idx ASC";
$r = mysql_query($q);
while($row = mysql_fetch_array($r))	{
	$ar_ttype2_idx[] = $row['idx'];
	$ar_ttype2_sname[$row['idx']] = $row['sname'];
}

$q = "SELECT * FROM codes WHERE ttype='1' AND dt_delete IS NULL ORDER BY idx ASC";
$r = mysql_query($q);
while($row = mysql_fetch_array($r))	{
	$ar_ttype1_idx[] = $row['idx'];
	$ar_ttype1_sname[$row['idx']] = $row['sname'];
}

$q = "SELECT * FROM codes WHERE ttype='3' AND dt_delete IS NULL ORDER BY idx ASC";
$r = mysql_query($q);
while($row = mysql_fetch_array($r))	{
	$ar_ttype3_idx[] = $row['idx'];
	$ar_ttype3_sname[$row['idx']] = $row['sname'];
}

$q = "SELECT * FROM codes WHERE ttype='4' AND dt_delete IS NULL ORDER BY idx ASC";
$r = mysql_query($q);
while($row = mysql_fetch_array($r))	{
	$ar_ttype4_idx[] = $row['idx'];
	$ar_ttype4_sname[$row['idx']] = $row['sname'];
}

$admin_menu['mem.php'] = '고객관리';
$admin_menu['reserve.php'] = '예약관리';
$admin_menu['car.php'] = '차량관리';
$admin_menu['sho.php'] = '사이트관리';
//$admin_menu['stat.php'] = '매출관리';
$admin_menu['stimate.php'] = '견적관리';
//$admin_menu['design.php'] = '디자인관리';
$admin_menu['site.php'] = '통계관리';


$ar_gen_search_value = array("subject","mem_name","memo");
$ar_gen_search_text = array("제목","작성자","메모");