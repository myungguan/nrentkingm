<?
include $_SERVER['DOCUMENT_ROOT']."/old/inc/base.php";
include $config['incPath']."/connect.php";
include $config['incPath']."/session.php";
include $config['incPath']."/config_site.php";
include $config['incPath']."/access_site.php";

$idx = $_REQUEST['idx'];
if(!is_numeric($idx))
{ exit;	}

$ar_data = sel_query_all("qna"," where idx='$idx'");

if($ar_data[issec]=='Y' && $ar_data[btype]=='2')
{
	if($ar_data[member_idx]!=0)
	{
		if(!$_SESSION['member_idx'])
		{	include "$dir/inc/access.php";	}
		if($ar_data[member_idx]!=$memindex)
		{
			echo "<script> alert('작성자만 조회 가능합니다'); location.replace('qnalist.php'); </script>";
			exit;
		}
	}
	else
	{
		$passwds = $_REQUEST['passwds'];
		if(!$passwds)
		{
			if(!$_SESSION['member_idx'])
			{
				echo "<script>alert(' 비회원으로 작성한 글입니다. 비밀번호 입력후 조회가 가능합니다.'); location.replace('qnapasswd.php?idx=$idx');  </script>";
				exit;
			}
			else
			{
				echo "<script>alert('비회원으로 작성한 글입니다. 비밀번호 입력후 조회가 가능합니다.'); location.replace('qnapasswd.php?idx=$idx'); </script>";
				exit;
			}
		}
		else
		{
			if($passwds!=$ar_data[passwds])
			{
				echo "<script> alert('비밀번호가 맞지 않습니다'); location.replace('qnalist.php'); </script>";	exit;
			}
		}
	}
}

$qna = $ar_data;

$qna['memo'] = nl2br($ar_data['memo']);
$qna['resultmemo'] = nl2br($ar_data['resultmemo']);
$qna['listlink'] = "qnalist.php";
$qna['modlink'] = "qnam.php?idx=$idx";
$qna['dellink'] = "qnadel.php?idx=$idx";

$n_page[type] = "1";
$n_page[title] = "Q&amp;A";

$tpls->assign('npage', $npage);
$tpls->assign('global', $tem_global);
$tpls->assign('qna', $qna);

$tpls->assign('page', $n_page);
$tpls->print_('mains');
?>
