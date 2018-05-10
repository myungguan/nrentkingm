<?
$url = urlencode($_SERVER['REQUEST_URI']);

if(!$_SESSION['member_idx'])
{
	header('Location: '."/member/login.php?url=$url&memcode=2");
	exit;
}
?>
	
