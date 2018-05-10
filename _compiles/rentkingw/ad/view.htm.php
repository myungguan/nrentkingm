<?php /* Template_ 2.2.8 2017/11/15 17:26:26 D:\Documents\projects\rentking\trunk\www\old\sites\tpls\rentkingw\ad\view.htm 000001697 */ ?>
<!DOCTYPE html>
<html lang="ko">
<head>
<?php $this->print_("head",$TPL_SCP,1);?>

	<style>
		.boardView table { width:100%; }
		.boardView table th { background-color: #f8f8f8;width:100px;border-bottom: 1px solid #dadada;padding:10px 0 10px 10px;height:25px; }
		.boardView table td { border-bottom: 1px solid #dadada;padding:10px 0 10px 10px;height:25px; }

	</style>
</head>
<body>
<?php $this->print_("header",$TPL_SCP,1);?>

	<div id="contentWrap" style="padding-top:50px;">
		<table class="list" summary="">
			<tbody>
			<tr>
				<th scope="row">제목</th>
				<td><?php echo $TPL_VAR["item"]["title"]?></td>
			</tr>
			<tr class="date">
				<th scope="row">작성일</th>
				<td><?php echo $TPL_VAR["item"]["dt_create"]?></td>
			</tr>
			<tr>
				<th scope="row">링크URL</th>
				<td><a href="<?php echo $TPL_VAR["item"]["link"]?>" target="_blank"><?php echo $TPL_VAR["item"]["link"]?></a></td>
			</tr>
			<tr>
				<th scope="row">이미지</th>
				<td>
					<a href="<?php echo $TPL_VAR["global"]["imgserver"]?>download.php?idx=<?php echo $TPL_VAR["item"]["file_idx"]?>">다운로드: <?php echo $TPL_VAR["item"]["name"]?></a><br />
					<img src="<?php echo $TPL_VAR["global"]["imgserver"]?>/files<?php echo $TPL_VAR["item"]["path"]?>" style="max-width:100%;margin-top:10px;" />
				</td>
			</tr>
			</tbody>
		</table>
		<div class="btnArea">
			<a href="/ad/list.php" class="btn-point">목록</a>
		</div><!--//btnWrap-->
	</div>
<?php $this->print_("footer",$TPL_SCP,1);?>

</body>
</html>