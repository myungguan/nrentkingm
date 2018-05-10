<?
if($_REQUEST['mode']=='w')
{
	$r = mysql_query("INSERT INTO banners (title, link, dt_create) VALUES ('{$_POST['title']}', '{$_POST['link']}', NOW())");
	if(!$r)
	{
		echo mysql_error();
		echo "<script type='text/javascript'>alert('게시물 작성에 실패하였습니다.'); history.back(); </script>";
		exit;
	}
	$idx = mysql_insert_id();

	fileUpload('file', 'banner', $idx);

	echo "<script type='text/javascript'>alert('게시물이 작성되었습니다'); location.replace('/sho.php?code=ad'); </script>";
	exit;
} else if($_REQUEST['mode'] == 'm') {
	$idx = $_REQUEST['idx'];

	$r = mysql_query("UPDATE banners SET title='{$_POST['title']}', link='{$_POST['link']}' WHERE idx={$_POST['idx']}");
	if(!$r)
	{
		echo mysql_error();
		echo "<script type='text/javascript'>alert('게시물 수정에 실패하였습니다.'); history.back(); </script>";
		exit;
	}

	fileUpload('file', 'banner', $idx, '', $_POST['file_idx']);
	echo "<script type='text/javascript'>alert('게시물이 수정되었습니다'); location.replace('/sho.php?code=ad'); </script>";
}
$mode = 'w';
if(isset($_REQUEST['idx'])) {
	$mode = 'm';
	$item = mysql_fetch_assoc(mysql_query("SELECT a.*, f.idx file_idx, f.name, f.path, f.type, f.size, f.down FROM banners a LEFT JOIN files f ON f.article_type = 'banner' AND f.article_idx = a.idx WHERE a.idx={$_REQUEST['idx']}"));
}
?>
<script type="text/javascript">
	function registerBanner() {
		var $writeForm = $('#writeform');

		var $title = $writeForm.find('input[name="title"]');
		if($.trim($title.val()).length < 1) {
			alert('제목을 입력하세요');
			$title.focus();
			return;
		}

		var $file = $writeForm.find('input[name="file"]');
		var $mode = $writeForm.find('input[name="mode"]');
		if($file[0].files.length == 0 && $mode.val() == 'w') {
			alert('파일을 입력하세요');
			$file.focus();
			return;
		}
		$writeForm.submit();
	}
</script>
<form name="writeform" id='writeform' action="sho.php?code=<?= $code; ?>" method="post" enctype="multipart/form-data">
	<input type='hidden' name='mode' value='<?=$mode?>' />
	<? if($mode == 'm') {?>
	<input type="hidden" name="idx" value="<?=$item['idx']?>" />
	<?}?>
	<div style='margin-top:10px;'>
		<table class="detailTable2">
			<tr>
				<th style="width:100px;">제목</th>
				<td><input type="text" name="title" size="60" maxlength="100" class="required" value="<?=$item['title'] ?>"></td>
			</tr>
			<tr>
				<th>링크URL</th>
				<td><input type="text" name="link" size="60" maxlength="250" value="<?=$item['link']?>" /> </td>
			</tr>
			<tr>
				<th>이미지</th>
				<td class="image-wrap">
					<input type="file" name="file" />
					<? if($mode == 'm') {?>
						<br /><img src="<?=$config['imgServer']?><?=$item['path']?>" style="max-width:100%" />
						<br /><a href="<?=$config['imgServer']?>/download.php?idx=<?=$item['file_idx']?>"><?=$item['name']?></a>
						<br /><input type="hidden" name="file_idx" value="<?=$item['file_idx']?>" />
					<?}?>
				</td>
			</tr>
		</table>

		<div class="btn_wrap btn_center" style="margin-top:20px;">
			<span class="greenBtn"><a href="/sho.php?code=ad">취소</a></span>
			<span class="greenBtn"><a href="javascript:registerBanner();">등록/수정</a></span>
		</div>
	</div><!-- // .content -->
</form><!-- // form[name="writeform"] -->