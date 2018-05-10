<?php
/**
 * admin.rentking.co.kr/sho.php?code=faqm
 * 관리자페이지 > 사이트관리 > 자주하는질문 > 수정
 * 자주하는 질문 수정 페이지
 */
$idx = $_REQUEST['idx'];
$ar_data = sel_query_all("faq"," where idx='$idx'");
$mode = $_REQUEST['mode'];
if($mode=='w')
{
	$content = stripslashes($_REQUEST['content']);

	$value['title'] = $_POST['title'];
	$value['content'] = addslashes($content);
	$value['dt_update'] = date("Y-m-d H:i:s",time());
	$r = update("faq",$value," where idx='$idx'");
	unset($value);

	echo "<Script>alert('수정 하였습니다.'); location.replace('sho.php?code=faqm&idx=$idx'); </script>";
	exit;
}
?>
<script language="javascript">
function saveContent() {
	$('#writeform').submit();
}

function foch() {
	var content = $('#content').val();
	if($.trim(content).length < 1) {
		alert('내용을 입력하세요');
		return false;
	} else {
		if(confirm('FAQ를 수정하시겠습니까?'))
			return true;
	}
	return false;
}
</script>

<div class="content">
<div class="form_wrap">
<form id="writeform" name="writeform" action="sho.php?code=<?=$code;?>" method="post" onsubmit="return foch();">
<input type='hidden' name='mode' value='w'>
<input type='hidden' name='idx' value='<?=$idx;?>'>
<table class="detailTable2">
<colgroup>
	<col width="15%;">
</colgroup>

<tr>
<th>제목</td>
<td><input name="title" type="text" size="60" class='boxes' value='<?=$ar_data['title'];?>'>
</td>
</tr>
<tr>
<th>내용</td>
<td>
	<textarea name="content" cols="30" rows="10" id="content" style="width:100%;"><?=$ar_data['content']?></textarea>
</td>
</tr>
</table>
<div class="btn_wrap btn_center btn_bottom">
	<span class="btn_green"><a href="javascript:saveContent();">수정하기</a></span>
</div>
</form>
</div><!-- // .form_wrap -->
</div><!-- // .content -->