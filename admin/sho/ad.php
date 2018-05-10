<?php
	$query = "SELECT a.*, f.down FROM banners a LEFT JOIN files f ON f.article_type='banner' AND f.article_idx = a.idx WHERE a.dt_delete IS NULL ORDER BY a.idx DESC";
	$list = mysql_query($query);
	$total = mysql_num_rows($list);
?>
<div style='margin-top:10px;'>
	<form id="search" name="search" action="/sho.php?code=<?= $code; ?>" method="post">
		<input type="submit" style="display:none;"/>
	</form>
</div><!-- // .form_wrap -->

<div class="h3_wrap">
	<div class="btn_wrap btn_right">
		<span class="redBtn"><a href="/sho.php?code=<?= $code; ?>w">업로드</a></span>
	</div>
</div>

<div>
	<table class="listTableColor">
		<thead>
		<tr>
			<th style="width:100px;">번호</th>
			<th>제목</th>
			<th>링크</th>
			<th style="width:160px;">게시일</th>
			<th style="width:100px;">다운로드</th>
		</tr>
		</thead>
		<tbody>
		<?if($total < 1) {?>
			<tr>
				<td colspan="5">데이터가 없습니다.</td>
			</tr>
		<?} else {?>
			<?while($row = mysql_fetch_array($list)) {?>
				<tr>
					<td><?=$row['idx'] ?></td>
					<td style="text-align:left;padding:0 5px;"><a href="/sho.php?code=<?= $code; ?>w&idx=<?=$row['idx']?>"><?=$row['title'] ?></a></td>
					<td style="text-align:left;padding:0 5px;"><a href="<?=$row['link']?>" target="_blank"><?=$row['link']?></a></td>
					<td><?=$row['dt_create']?></td>
					<td><?=number_format((int)$row['down']) ?></td>
				</tr>
			<?}?>
		<?}?>
		</tbody>
	</table>
</div><!-- // .list_wrap -->