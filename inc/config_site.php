<?
$_nip = (isset($_SERVER['HTTP_CF_CONNECTING_IP']) ? $_SERVER['HTTP_CF_CONNECTING_IP'] : $_SERVER['REMOTE_ADDR']);

$QUERY_STRING = $_SERVER["QUERY_STRING"];
$PHP_SELF = $_SERVER["PHP_SELF"];
$PHP_SELF = str_replace("/old/sites","",$PHP_SELF);
$SERVER_NAME = $_SERVER["SERVER_NAME"];

if($QUERY_STRING)	{
	$_nowurl = $PHP_SELF . "?" . $QUERY_STRING; 
}
else	{
	$_nowurl = $PHP_SELF; 
}

$pid = 'W';
$design_layout = 'rentkingw';
$fid = 1;

if(strpos($_SERVER['HTTP_HOST'], 'admin') === false && strpos($_SERVER['HTTP_HOST'], 'img') === false) {
	require_once("Mobile_Detect.php");
	$detect = new Mobile_Detect();
	$ismobile = $detect->isMobile();

	if($ismobile) {
		$pid = 'M';
		$design_layout = 'rentkingm';
		if(strpos($_SERVER['HTTP_HOST'], 'www') !== false && strpos($_SERVER['HTTP_HOST'], 'www') == 0)	{
			$host = str_replace('www.', 'm.', $_SERVER['HTTP_HOST']);
			header("Location: {$config['scheme']}://".$host.$_SERVER['REQUEST_URI']);
			exit;
		}
	} else {
		if((strpos($_SERVER['HTTP_HOST'], 'www') === false || strpos($_SERVER['HTTP_HOST'], 'www') !== 0))	{
			$host = str_replace('m.', 'www.', $_SERVER['HTTP_HOST']);
			header("Location: {$config['scheme']}://".$host.$_SERVER['REQUEST_URI']);
			exit;
		}
	}
}



if($_SESSION['member_idx'])	{
	$g_memidx = $_SESSION['member_idx'];
	$g_memid = $_SESSION['member_id'];
	$g_memname = $_SESSION['member_name'];

	$ar_init_member = sel_query_all("member"," where idx='$g_memidx'");
}

include_once($config['incPath']."/Template_.class.php");
include_once($config['incPath']."/config.templete.php");
?>
