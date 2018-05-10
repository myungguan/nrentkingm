<?php
$mode = $_REQUEST['mode'];
$idx = $_REQUEST['idx'];
$keyword = $_REQUEST['keyword'];
$page = $_REQUEST['page'];
$isanswer = $_REQUEST['isanswer'];
$fid = $_REQUEST['fid'];
$query = "SELECT q.*, m.name mem_name, m.id mem_id, m.cp mem_cp, ad.name ad_name
	FROM
		qna q
		LEFT JOIN member m ON q.member_idx = m.idx
		LEFT JOIN member ad ON q.admin_idx = ad.idx
	WHERE
		q.idx = $idx";
$ar_data = mysql_fetch_assoc(mysql_query($query));
if ($mode == 'd') {
	$q = "UPDATE qna SET dt_delete=NOW() WHERE idx='$idx'";
	$r = mysql_query($q);
	if (!$r) {
		echo "<Script>alert('삭제에 실패하였습니다.'); history.back(); </script>";
		exit;
	}

	echo "<script>alert('삭제하였습니다.'); location.replace('sho.php?code=qna&page=$page&key=$key&keyword=$keyword&sb=$sb&isanswer=$isanswer&sre=$sre&fid=$fid'); </script>";
	exit;

}

if ($mode == 'w') {
	$admin_idx = $_POST['admin_idx'];
	$ansewr = mysql_escape_string($_POST['answer']);

	echo $ansewr;
	$query = "UPDATE qna SET admin_idx=$admin_idx, dt_answer=NOW(), answer='$ansewr' WHERE idx=$idx";
	mysql_query($query);

	//문자 발송
	$kakao = array(
		'name' => $ar_data['mem_name'],
	);

    if(!$config['production']){
        $ar_data['mem_cp'] = '01034460336';
    }

	sendKakao($config['kakaoAuth'], $ar_data['mem_cp'], 'CUS0102', $kakao);

	echo "<script>alert('답변하였습니다.'); location.replace('sho.php?code=qna&page=$page&keyword=$keyword&isanswer=$isanswer'); </script>";
	exit;

}
?>
<?php

?>
<!-- 장바구니 -->
<br>
<script language="javascript">
	function delok(url) {
		answer = confirm('삭제하시겠습니까?');
		if (answer == true) {
			location.href = url;
		}
	}
</script>
<script language="javascript">
	function foch() {
		if (!document.wform.answer.value) {
			alert('답변내용을 작성 하세요');
			document.wform.answer.focus();
			return false;
		}
		return true;
	}

	function imgview(img) {
		window.open("imgviewer.php?img=" + img, "img", 'width=150,height=150,status=no,top=0,left=0,scrollbars=yes');
	}
</script>


<span class="subTitle">* <?=$ar_data['mem_name'];?>고객 질문내용</span>
<?php
	$pop = "<a href=\"javascript:MM_openBrWindow('/popup/memview.php?idx={$ar_data['member_idx']}','member{$ar_data['member_idx']}','scrollbars=yes,width=1150,height=700,top=0,left=0');\">";
?>
<table class="detailTable2">

	<tr>
		<th>작성자</th>
		<td><?=$pop;?><?=$ar_data['mem_name'];?>(<?=$ar_data['mem_id']?>)</a></td>
	</tr>
	<tr>
		<th>제 목</th>
		<td><?=$ar_data['subject'];?></td>
	</tr>

	<tr>
		<th>작성일</th>
		<td><?=$ar_data['dt_create'];?></td>
	</tr>
	<tr>
		<th>연락처</th>
		<td><?=phone_number_format($ar_data['mem_cp'])?></td>
	</tr>

	<tr>
		<th>내용</th>
		<td style="padding:5px;">
			<?=nl2br($ar_data['memo']);?>
		</td>
	</tr>
</table>
<div class="btn_wrap btn_center btn_bottom">
	<span class="btn_green"><a href="/sho.php?code=qna&keyword=<?=$keyword;?>&page=<?=$page;?>&isanswer=<?=$isanswer;?>">목록으로</a></span>
	<span class="btn_green"><a href="javascript:delok('/sho.php?code=<?=$code;?>&idx=<?=$idx;?>&page=<?=$page;?>&keyword=<?=$keyword;?>&mode=d&isanswer=<?=$isanswer;?>');">삭제</a></span>
</div>


<span class="subTitle">* 등록된 답변내용입니다.</span>

<form id="wform" name="wform" action="sho.php?code=<?=$code;?>" method="post" onsubmit="return foch();">
	<input type='hidden' name='mode' value='w'>
	<input type='hidden' name='idx' value='<?=$idx;?>'>
	<input type='hidden' name='page' value='<?=$page;?>'>
	<input type='hidden' name='keyword' value='<?=$keyword;?>'>
	<input type='hidden' name='isanswer' value='<?=$isanswer;?>'>
	<input type='hidden' name='fid' value='<?=$fid;?>'>

	<table class="detailTable2">
		<tr>
			<th>답변자</th>
			<td>
				<?=$_SESSION['member_name'] ?>
				<input type="hidden" name="admin_idx" value="<?=$_SESSION['member_idx']?>" />
			</td>
		</tr>
		<? if ($ar_data['result'] == 'Y') {?>
		<tr>
			<th>답변일</th>
			<td><?=$ar_data['dt_result']?></td>
		</tr>
		<?}?>
		<tr>
			<th>답변내용</th>
			<td>
				<textarea name=answer id="answer" cols='90' rows='10' style="ime-mode:active"/><?=$ar_data['answer'];?></textarea>
			</td>
		</tr>
	</table>
	<div class="btn_wrap btn_center btn_bottom">
		<span class="btn_green btn_submit" data-form="#wform"><a href="javascript:">답변하기</a></span>
	</div>
</form>
