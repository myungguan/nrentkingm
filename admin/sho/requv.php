<?
$idx = $_REQUEST['idx'];
$ar_data = sel_query_all("requ"," WHERE idx='$idx'");
if($mode=='w')	{
	$value[adminmemo] = $_POST['adminmemo'];
	update("requ",$value," WHERE idx='$idx'");

	echo "<script>alert('수정완료'); location.replace('$PHP_SELF?code=$code&idx=$idx'); </script>";
	exit;
}
?>
<script>
function foch(f)	{
	var re = check_form(f);
	if(re)	{
		answer = confirm('수정 하시겠습니까?');
		if(answer==true)	{
			return true;
		}
		else	{
			return false;
		}
	}
	else	{
		return false;
	}
	
}
</script>
<form name="regiform" id="regiform" action="<?=$PHP_SELF;?>?code=<?=$code;?>" method="post" onsubmit="return foch('regiform');" enctype="multipart/form-data">
<input type='hidden' name='mode' value='w'>
<input type='hidden' name='idx' value='<?=$idx;?>'>
<table class="detailTable2">
<tr>
<th>작성일</th>
<td><?=$ar_data[wdate];?></td>
</tr>
<tr>
<th>E-MAIL</th>
<td><?=$ar_data[email];?></td>
</tr>
<tr>
<th>전화번호</th>
<td><?=$ar_data[phone];?></td>
</tr>
<tr>
<th>제목</th>
<td><?=$ar_data[subject];?></td>
</tr>
<tr>
<th>내용</th>
<td style="padding:5px;"><?=nl2br($ar_data[memo]);?></td>
</tr>
<tr>
<th>관리자메모</th>
<td><textarea name="adminmemo" style="width:600px;height:200px;"><?=$ar_data[adminmemo];?></textarea></td>
</tr>
</table>
<div class="topBtn" style='text-align:center;'>
	<span class="greenBtn btn_submit" data-form="#regiform"><a href="javascript:">관리자메모수정</a></span>
</div>
</form>