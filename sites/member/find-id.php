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
	$name = $_REQUEST['name'];
	$email = $_REQUEST['email'];
	$cp = $_REQUEST['cp'];
	$find_chk = $_REQUEST['find_chk'];

	if($find_chk=='1')
	{	$q = "SELECT * FROM member WHERE id <> 'outmember' AND name='$name' AND id='$email'";	}
	else
	{	$q = "SELECT * FROM member WHERE id <> 'outmember' AND name='$name' AND Replace(cp,'-','') = '$cp'";	}

	$r = mysql_query($q);
	while($row = mysql_fetch_array($r))
	{
		$findrs[$cou]['id'] = $row['id'];
		$findrs[$cou]['signdate'] = $row['signdate'];
		$cou++;
	}
	$find['resultcount'] = $cou;
}

$find['result'] = $result;

$tpls->assign('findrs', $findrs);
$tpls->assign('find', $find);
$page['type'] = "1";
$page['title'] = "아이디찾기";

$tpls->assign('page', $page);
$tpls->print_('mains');
?>