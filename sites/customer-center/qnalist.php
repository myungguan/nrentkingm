<?
include $_SERVER['DOCUMENT_ROOT']."/old/inc/base.php";
include $config['incPath']."/connect.php";
include $config['incPath']."/session.php";
include $config['incPath']."/config_site.php";
include $config['incPath']."/access_site.php";

$fid = 1;
$key = $_REQUEST['key'];

$page_per_block = 5;

$reply_limit = 6;

$page = $_GET['page'];
if (!$page) {
	$page = 1;
}

$q = "select count(idx) from qna where dt_delete IS NULL and member_idx='$g_memidx'";
$r = mysql_query($q);
$total_record = mysql_result($r, 0, 0);
mysql_free_result($r);

$num_per_page = 10;
$total_page = ceil($total_record / $num_per_page);

if ($total_record == 0) {
	$first = 0;
	$last = 0;
} else {
	$first = $num_per_page * ($page - 1);
	$last = $num_per_page * $page;
}

$q = "select * from qna where dt_delete IS NULL and member_idx='$g_memidx'";
$q = $q . " order by dt_create desc limit $first, $num_per_page";
$r = mysql_query($q);
$cou = 0;

$article_num = $total_record - (($page - 1) * $num_per_page);
while ($r && $row = mysql_fetch_array($r)) {
	$qnalist[$cou][no] = $article_num;
	$qnalist[$cou][index] = $row[idx];
	$qnalist[$cou][dt_create] = substr($row[dt_create], 0, 10);
	$qnalist[$cou][subject] = $row[subject];
	$qnalist[$cou][dt_answer] = $row[dt_answer];
	$article_num --;
	$cou ++;
}

$orderby = urlencode($orderby);
############### 게시물 페이지 이동
$total_block = ceil($total_page / $page_per_block);
$block = ceil($page / $page_per_block);

$first_page = ($block - 1) * $page_per_block;
$last_page = $block * $page_per_block;

if ($total_block <= $block) {
	$last_page = $total_page;
}


$paging[total_record] = $total_record;
$paging[nowpage] = $page;
$paging[block] = $block;
$paging[totalblock] = $total_block;

if ($block > 1) {
	$before_page = $first_page;
	$paging[superfirst] = "$PHP_SELF?page=$before_page&sb=$sb&goods_idx=$goods_idx";
}

if ($total_record != 0) {
	if (($page - 1) < 1) {
		$paging[first] = "javascript:alert('처음페이지입니다.');";
	} else {
		$k = ($page - 1);
		$paging[first] = "$PHP_SELF?page=$k&sb=$sb&goods_idx=$goods_idx";
	}
}

for ($direct_page = $first_page + 1; $direct_page <= $last_page; $direct_page ++) {
	$loop_paging[] = array(
		'links' => "$PHP_SELF?page=$direct_page&sb=$sb&goods_idx=$goods_idx",
		'page' => $direct_page
	);
}


if ($total_record != 0) {
	if (($page + 1) > $total_page) {
		$paging[last] = "javascript:alert('마지막페이지 입니다.');";
	} else {
		$k = ($page + 1);
		$paging[last] = "$PHP_SELF?page=$k&sb=$sb&goods_idx=$goods_idx";
	}
}

if ($block < $total_block) {
	$daum_page = $last_page + 1;
	$paging[superlast] = "$PHP_SELF?page=$daum_page&sb=$sb&goods_idx=$goods_idx";
}

//$qnaconf = $ar_qna_config;
//$qnaconf[sb] = $sb;
//$qnaconf[sbname] = "질문과답변";

//$qnaconf[searchform_start] = "<form name='bbssearch' id='bbssearch' action='$PHP_SELF?sb=$sb' method='post'>";
//$qnaconf[searchform_end] = "</form>";

//if (!$keyword) {
//	$qnaconf[keych2] = "checked";
//	$qnaconf[keychs2] = "selected";
//} else {
//	$qnaconf[keyword] = $keyword;
//	if ($key == 'mem_id') {
//		$qnaconf[keych1] = "checked";
//		$qnaconf[keychs1] = "selected";
//	}
//
//	if ($key == 'subject') {
//		$qnaconf[keych2] = "checked";
//		$qnaconf[keychs2] = "selected";
//	}
//
//	if ($key == 'memo') {
//		$qnaconf[keych3] = "checked";
//		$qnaconf[keychs3] = "selected";
//	}
//}
$tpls->assign('goods', $bbs);
$tpls->assign('paging', $paging);
$tpls->assign('loop_paging', $loop_paging);
$tpls->assign('npage', $npage);
$tpls->assign('global', $tem_global);
$tpls->assign('bestlist', $bestlist);
$tpls->assign('qnalist', $qnalist);
//$tpls->assign('qnaconf', $qnaconf);


$n_page[type] = "2";
$n_page[title] = "Q&amp;A";

$tpls->assign('page', $n_page);
$tpls->print_('mains');
?>
