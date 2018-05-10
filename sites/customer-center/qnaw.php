<?
include $_SERVER['DOCUMENT_ROOT']."/old/inc/base.php";
include $config['incPath']."/connect.php";
include $config['incPath']."/session.php";
include $config['incPath']."/config_site.php";
include $config['incPath']."/access_site.php";

$goods_idx = $_REQUEST['goods_idx'];
$backmode = $_REQUEST['backmode'];

$mode = $_REQUEST['mode'];

if($mode=='w')
{
	$subject = mysql_escape_string($_POST['subject']);
	$memo = mysql_real_escape_string($_POST['memo']);

	$query = "INSERT INTO qna(member_idx, subject, memo, dt_create, pid) VALUES ($g_memidx, '$subject', '$memo', NOW(), '$pid')";
	$r = mysql_query($query);
	if(!$r)
	{
		echo mysql_error();
		echo "<Script>alert('게시물작성에 실패하였습니다.'); history.back(); </script>";
		exit;
	}
	$upindex = mysql_insert_id();

	$kakao = [
		'subject' => $subject,
		'qna' => 'http://admin.rentking.co.kr/sho.php?code=qnar&idx='.$upindex
	];
	$phone = '01035010075';
	if(!$config['production'])
		$phone = '01034460336';
	sendKakao($config['kakaoAuth'], $phone, 'CUS0202', $kakao);

	echo "<Script>alert('작성 되었습니다.'); location.replace('qnalist.php?sb=$sb'); </script>";
	exit;
}

$tpls->assign('npage', $npage);
$tpls->assign('cart', $cart);
$tpls->assign('qna', $qna);

$n_page['type'] = "1";
$n_page['title'] = "Q&amp;A";

$tpls->assign('page', $n_page);
$tpls->print_('mains');
?>
