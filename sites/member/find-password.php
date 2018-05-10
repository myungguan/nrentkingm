<?
include $_SERVER['DOCUMENT_ROOT']."/old/inc/base.php";
include $config['incPath']."/connect.php";
include $config['incPath']."/session.php";
include $config['incPath']."/config_site.php";

if($_SESSION['member_idx'])	{
	header('Location: '."/");
	exit;
}
$mode = $_REQUEST['mode'];
$cou = 0;
if($mode=='w')
{
	$result = "Y";
	$id = $_REQUEST['id'];
	$name = $_REQUEST['name'];
	$email = $_REQUEST['email'];
	$cp = $_REQUEST['cp'];
	$find_chk = $_REQUEST['find_chk'];

	if($find_chk=='1')
	{	$q = "SELECT * FROM member WHERE id='$id' AND name='$name' AND id='$email'";	}
	else
	{	$q = "SELECT * FROM member WHERE id='$id' AND name='$name' AND Replace(cp,'-','') = '$cp'";	}

	$r = mysql_query($q);
	$isit = mysql_num_rows($r);
	$find['resultcount'] = $isit;

	if($isit!=0)
	{
		$row = mysql_fetch_array($r);

		$value['passwd'] = substr($row['id'],0,2).substr(time(),3,4);
		update("member",$value," where idx='{$row['idx']}'");
		$findrs['id'] = $row['id'];
		$findrs['name'] = $row['name'];
		$findrs['passwd'] = $value['passwd'];
		unset($value);
	}
}

$find['result'] = $result;

$tpls->assign('findrs', $findrs);
$tpls->assign('find', $find);

$page['type'] = "1";
$page['title'] = "비밀번호찾기";

$tpls->assign('page', $page);
$tpls->print_('mains');
?>