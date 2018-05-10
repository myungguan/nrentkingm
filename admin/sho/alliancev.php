<?
$idx = $_REQUEST['idx'];

if ($mode == 'w') {
	$comment = mysql_escape_string($_POST['comment']);
	$query = "INSERT INTO comments(type, type_idx, comment, member_idx, dt_create, dt_update) VALUES 
		('alliance', $idx, '$comment', {$_SESSION['member_idx']}, NOW(), NOW())";
	mysql_query($query);

	echo "<script>alert('등록 완료'); location.replace('/sho.php?code=$code&idx=$idx'); </script>";
	exit;
}

$ar_data = sel_query_all("alliance", " WHERE idx='$idx'");
$ar_comments = [];
$query = "SELECT
		c.*,
		m.name
	FROM
		comments c
		LEFT JOIN member m ON c.member_idx = m.idx
	WHERE
		c.type = 'alliance'
		AND c.type_idx = $idx
	ORDER BY dt_update DESC";
$r = mysql_query($query);
while($row = mysql_fetch_assoc($r)) {
	$ar_comments[] = $row;
}
?>
<script>
	function foch(f) {
		var re = check_form(f);
		if (re) {
			return confirm('등록 하시겠습니까?');
		}
		else {
			return false;
		}

	}
</script>
<form name="regiform" id="regiform" action="/sho.php?code=<?=$code;?>" method="post" onsubmit="return foch('regiform');" enctype="multipart/form-data">
	<input type='hidden' name='mode' value='w'>
	<input type='hidden' name='idx' value='<?=$idx;?>'>
	<table class="detailTable2">
		<tr>
			<th>작성일</th>
			<td><?=$ar_data['dt_create'];?></td>
		</tr>
		<tr>
			<th>법인명(단체명)</th>
			<td><?=$ar_data['company'];?></td>
		</tr>
		<tr>
			<th>지역</th>
			<td><?=$ar_data['location'];?></td>
		</tr>
		<tr>
			<th>담당자</th>
			<td><?=$ar_data['manager'];?></td>
		</tr>
		<tr>
			<th>연락처</th>
			<td><?=$ar_data['contact'];?></td>
		</tr>
		<tr>
			<th>이메일</th>
			<td><?=$ar_data['email'];?></td>
		</tr>
		<tr>
			<th>내용</th>
			<td style="padding:5px;"><?=nl2br($ar_data['content']);?></td>
		</tr>
		<tr>
			<th>관리자메모</th>
			<td><textarea name="comment" style="width:600px;height:100px;"></textarea></td>
		</tr>
		<?foreach($ar_comments as $comment) {?>
			<tr>
				<th><?=$comment['name']?><br />[<?=$comment['dt_update']?>]</th>
				<td><?=nl2br($comment['comment']) ?></td>
			</tr>
		<?}?>
	</table>
	<div class="topBtn" style='text-align:center;'>
		<span class="greenBtn"><a href="/sho.php?code=alliance">리스트</a></span>
		<span class="greenBtn btn_submit" data-form="#regiform"><a href="javascript:">관리자메모등록</a></span>
	</div>
</form>