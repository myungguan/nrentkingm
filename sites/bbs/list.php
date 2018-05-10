<?
/**
 * 프론트 > 이벤트, 고객센터(공지사항), 렌트킹뉴스 리스트
 * www.rentking.co.kr/bbs/list.php?boardid=event
 * www.rentking.co.kr/bbs/list.php?boardid=notice
 * www.rentking.co.kr/bbs/list.php?boardid=rentking_news
 * 이벤트, 고객센터(공지사항) 게시글 리스트
 */
include $_SERVER['DOCUMENT_ROOT']."/old/inc/base.php";
include $config['incPath']."/connect.php";
include $config['incPath']."/session.php";
include $config['incPath']."/config_site.php";

$boardid = $_REQUEST['boardid'];
$req_page = $_REQUEST['page'];

$num_per_page = 20;
$reply_limit = 6;
$page_per_block = 0;

$req_page = $_GET['page'];
if (!$req_page) {
	$req_page = 1;
}

if($boardid == 'notice') {
    $_page['title'] = "공지사항";
	$q = "
	SELECT 
		COUNT(idx)
	FROM
		notices
	WHERE 
	dt_delete IS NULL
	AND front_yn = 'Y' 
	ORDER BY dt_create DESC";
}else if($boardid == 'event') {
    $_page['title'] = "이벤트";
	$q = "
	SELECT 
		COUNT(idx)
	FROM
		events
	WHERE 
	dt_delete IS NULL
	ORDER BY dt_create DESC";
}else if($boardid == 'rentking-news') {
    $_page['title'] = "렌트킹뉴스";
    $q = "
	SELECT 
		COUNT(idx)
	FROM
		rentking_news
	WHERE 
	dt_delete IS NULL
	ORDER BY dt_create DESC";
}

$r = mysql_query($q);
$total_record = mysql_result($r, 0, 0);
mysql_free_result($r);

$total_page = ceil($total_record / $num_per_page);

if ($total_record == 0) {
	$first = 0;
	$last = 0;
} else {
	$first = $num_per_page * ($req_page - 1);
	$last = $num_per_page * $req_page;
}

if($boardid == 'notice') {
	$q = "
	SELECT 
		idx,
		title,
		dt_create
	FROM
		notices
	WHERE 
	dt_delete IS NULL
	AND front_yn = 'Y' 
	ORDER BY dt_create DESC";
}else if($boardid == 'event') {
	$q = "
	SELECT 
		idx,
		title,
		dt_create
	FROM
		events
	WHERE 
	dt_delete IS NULL
	ORDER BY dt_create DESC";
}else if($boardid == 'rentking-news') {
    $q = "
	SELECT 
		idx,
		title,
		dt_create
	FROM
		rentking_news
	WHERE 
	dt_delete IS NULL
	ORDER BY dt_create DESC";
}
$q = $q . " LIMIT {$first}, {$num_per_page}";
$r = mysql_query($q);
$article_num = $total_record - (($req_page - 1) * $num_per_page);
$cou = 0;
while ($row = mysql_fetch_array($r)) {
	$row['title'] = strip_tags($row['title']);

	$bbs2[$cou]['num'] = $article_num;
	$bbs2[$cou]['readlinks'] = "read.php?idx={$row['idx']}&page={$req_page}&boardid={$boardid}";
	$bbs2[$cou]['title'] = $row['title'];
	$bbs2[$cou]['dt_create'] = $row['dt_create'];

	$cou++;
	$article_num--;
}

$bbs['boardid'] = $boardid;

############### 게시물 페이지 이동
if ($page_per_block > 0) {
	$total_block = ceil($total_page / $page_per_block);
	$block = ceil($req_page / $page_per_block);
}

$first_page = ($block - 1) * $page_per_block;
$last_page = $block * $page_per_block;

if ($total_block <= $block) {
	$last_page = $total_page;
}

$paging['total_record'] = $total_record;
$paging['nowpage'] = $req_page;
$paging['block'] = $block;
$paging['totalblock'] = $total_block;

if ($block > 1) {
	$before_page = $first_page;
	$paging['superfirst'] = "../bbs/list.php?page={$before_page}&boardid={$boardid}";
}

if ($total_record != 0) {
	if (($req_page - 1) < 1) {
		$paging['first'] = "javascript:alert('처음페이지입니다.');";
	} else {
		$k = ($req_page - 1);
		$paging['first'] = "../bbs/list.php?page={$k}&boardid={$boardid}";
	}
}

for ($direct_page = $first_page + 1; $direct_page <= $last_page; $direct_page++) {
	$loop_paging[] = array('links' => "../bbs/list.php?page={$direct_page}&boardid={$boardid}", 'page' => $direct_page);
}

if ($total_record != 0) {
	if (($req_page + 1) > $total_page) {
		$paging['last'] = "javascript:alert('마지막페이지 입니다.');";
	} else {
		$k = ($req_page + 1);
		$paging['last'] = "../bbs/list.php?page={$k}&boardid={$boardid}";
	}
}

if ($block < $total_block) {
	$daum_page = $last_page + 1;
	$paging['superlast'] = "../bbs/list.php?page={$daum_page}&boardid={$boardid}";
}

$npage['loca'] = "bbs/default/list";

$npage['skins'] = $design_layout;
$tpls->define(array(
	'mains' => $npage['loca'] . '.htm'
	/*
	'header' => './layout/head.htm', 
		'top' => './layout/top1.htm', 
		'down' => './layout/down1.htm',*/

), '', $compile_dir, $template_dir);

$npage['dateshow'] = $dateshow;

$topimg = $ar_tops['tops'];
$tpls->assign('topimg', $topimg);
$tpls->assign('paging', $paging);
$tpls->assign('loop_paging', $loop_paging);

$tpls->assign('npage', $npage);
$tpls->assign('global', $tem_global);
$tpls->assign('bbs', $bbs);

$tpls->assign('bbs2', $bbs2);

$_page['type'] = "2";

$tpls->assign('page', $_page);

$tpls->print_('mains');
?>