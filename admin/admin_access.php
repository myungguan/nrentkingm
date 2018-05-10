<?
$url = urlencode($_SERVER['REQUEST_URI']);

if(!$_SESSION['member_idx'])
{
	echo "<script>location.replace('/index.php?url=$url');</script>";
	exit;

}
if(!$_SESSION['admin_grade'] && $_SESSION['member_grade']!='10')
{
	echo "<script>alert('권한이 없습니다'); location.replace('/index.php?url=$url'); </script>";
	exit;
}