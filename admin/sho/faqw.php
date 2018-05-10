<?
/**
 * admin.rentking.co.kr/
 */
$mode = $_REQUEST['mode'];
if($mode=='w')
{
	$content = stripslashes($_REQUEST['content']);
	$value['title'] = mysql_escape_string($_POST['title']);
	$value['content'] = addslashes($content);
	$value['dt_create'] = date("Y-m-d H:i:s",time());
	$value['dt_update'] = date("Y-m-d H:i:s",time());
	$r = insert("faq",$value);

	$idx = mysql_insert_id();

	echo "<Script>alert('등록 하였습니다.'); location.replace('sho.php?code=faq'); </script>";
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
		if(confirm('FAQ를 등록하시겠습니까?'))
			return true;
	}
	return false;
}
</script>
<div class="content">

<div class="form_wrap">
<form name="writeform" id="writeform" action="sho.php?code=<?=$code;?>" method="post" onsubmit="return foch();">
<input type='hidden' name='mode' value='w'>


<table class="detailTable2">
<colgroup>
	<col width="15%;">
</colgroup>
<tr>
<th>제목</th>
<Td><input name="title" type="text" size="60" class='boxes'>
</td>
</tr>
<tr>
<th>내용</th>
<Td>
	<textarea name="content" cols="30" rows="10" id="content" style="width:100%;"></textarea>
</td>
</tr>
</table>
<div class="btn_wrap btn_center btn_bottom">
	<span class="btn_green"><a href="javascript:saveContent();">등록하기</a></span>
</div>

</div>
</form>

</div>