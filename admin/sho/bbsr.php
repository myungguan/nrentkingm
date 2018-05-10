<?
$idx = $_REQUEST['idx'];
$page = $_REQUEST['page'];
$mode = $_REQUEST['mode'];
$key = $_REQUEST['key'];
$keyword = $_REQUEST['keyword'];
$sboardid = $_REQUEST['boardid'];
$sdate = $_REQUEST['sdate'];
$edate = $_REQUEST['edate'];
$sisdel = $_REQUEST['sisdel'];
if (!$sisdel) {
	$sisdel = "N";
}

$tableName = 'notices';
if($sboardid == '이벤트') {
	$tableName = 'events';
}else if($sboardid == '렌트킹뉴스') {
    $tableName = 'rentking_news';
}
$q = "	
		SELECT
			t.*,
			m.name
		FROM $tableName t
		INNER JOIN member m ON m.idx = t.member_idx
		WHERE t.idx = $idx 
			 ";
$ar_data = mysql_fetch_array(mysql_query($q));


$mode = $_REQUEST['mode'];
if ($mode == 'd') {
	$q = "UPDATE $tableName SET dt_delete=NOW() WHERE idx='$idx'";
	$r = mysql_query($q);
	if (!$r) {
		echo "<Script>alert('삭제에 실패하였습니다.'); history.back(); </script>";
		exit;
	}
	echo "<script>alert('삭제하였습니다.'); location.replace('$PHP_SELF?code=bbs'); </script>";
	exit;
}

?>
<style>
	#memos img { max-width:70%; }
</style>
<div class="content">
	<div class="h3_wrap">
		<h3>* 게시물 내용</h3>
	</div>

	<table class="detailTable2">
		<tr>
			<th>작성자</th>
			<td><? if ($ar_data[member_idx] != '0') { ?>
				<a href="javascript:MM_openBrWindow('help.php?code=view&idx=<?=$ar_data['idx'];?>','member<?=$ar_data['idx'];?>','scrollbars=yes,width=1150,height=900,top=0,left=0');"><? }else{ ?>
					<a href="javascript:alert('비회원입니다');"><? } ?><?=$ar_data['name'];?></a></td>
		</tr>
		<tr>
			<th>제 목</th>
			<td><?=$ar_data[title];?></td>
		</tr>
		<tr>
			<th>내용</th>
			<td style="padding:5px;" id="memos">
				<?=$ar_data[content]?>
			</td>
		</tr>
	</table>
	<div class="btn_wrap btn_center btn_bottom">
		<!--2017.03.06 DonYoung - START -->
		<span class="greenBtn"><a href="<?=$PHP_SELF;?>?code=bbs">목록으로</a></span>
		<span class="greenBtn"><a href="<?=$PHP_SELF;?>?code=bbsm&idx=<?=$idx;?>&boardid=<?=$sboardid;?>">수정</a></span>
		<span class="greenBtn" onclick="delok('삭제하시겠습니까?','<?=$PHP_SELF;?>?code=<?=$code;?>&idx=<?=$idx;?>&boardid=<?=$sboardid;?>&mode=d');">삭제</span>
		<!--2017.03.06 DonYoung - END -->
	</div>
</div>
