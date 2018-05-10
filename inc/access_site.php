<?
$QUERY_STRING = $_SERVER["QUERY_STRING"];
$PHP_SELF = $_SERVER["PHP_SELF"];
if($QUERY_STRING)
	{ $url = $PHP_SELF . "?" . $QUERY_STRING; }
else
	{ $url = $PHP_SELF; }

if(!$_SESSION['member_idx'])
{
	$url = preg_replace("/^\/old\/sites/", "", $url);
	$_SESSION['redirect'] = $url;
	$url = urlencode($url);
	echo "<script>location.replace('/member/login.php?url=$url');</script>";
	exit;
}
?>
	
