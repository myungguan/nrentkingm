<?
/**
 * admin.rentking.co.kr/sho.php?code=eastv&idx=5
 * 관리자페이지 > 사이트관리 > 견적문의 > 조회
 * 견적문의 세부 조회 페이지
 */

$idx = $_REQUEST['idx'];

if ($mode == 'w') {
	$comment = mysql_escape_string($_POST['comment']);
	$query = "INSERT INTO comments(type, type_idx, comment, member_idx, dt_create, dt_update) VALUES 
		('estimates', $idx, '$comment', {$_SESSION['member_idx']}, NOW(), NOW())";
	mysql_query($query);

	echo "<script>alert('등록완료'); location.replace('sho.php?code=$code&idx=$idx'); </script>";
	exit;
}

$ar_data = sel_query_all("estimates", " WHERE idx='$idx'");
$ar_comments = [];
$query = "SELECT
		c.*,
		m.name
	FROM
		comments c
		LEFT JOIN member m ON c.member_idx = m.idx
	WHERE
		c.type = 'estimates'
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
			if (confirm('등록 하시겠습니까?')) {
				return true;
			}
			else {
				return false;
			}
		}
		else {
			return false;
		}
	}
</script>
<form name="regiform" id="regiform" action="sho.php?code=<?=$code;?>" method="post" onsubmit="return foch('regiform');" enctype="multipart/form-data">
	<input type='hidden' name='mode' value='w'>
	<input type='hidden' name='idx' value='<?=$idx;?>'>
	<table class="detailTable2">
		<tr>
			<th>작성일</th>
			<td><?=$ar_data['dt_create'];?></td>
		</tr>
		<tr>
			<th>종류</th>
			<td><?=$ar_data['kinds'];?></td>
		</tr>
		<tr>
			<th>방문여부</th>
			<td><?=$ar_data['isdeli'];?></td>
		</tr>
		<tr>
			<th>이용기간</th>
			<td><?=$ar_data['date1'];?> ~ <?=$ar_data['date2'];?></td>
		</tr>
		<tr>
			<th>희망모델</th>
			<td><?=$ar_data['models'];?></td>
		</tr>

		<tr>
			<th>이름</th>
			<td><?=$ar_data['name'];?></td>
		</tr>
		<tr>
			<th>핸드폰</th>
			<td><?=$ar_data['cp'];?></td>
		</tr>
		<tr>
			<th>전화번호</th>
			<td><?=$ar_data['phone'];?></td>
		</tr>
		<tr>
			<th>EMAIL</th>
			<td><?=$ar_data['email'];?></td>
		</tr>
		<tr>
			<th>희망픽업위치</th>
			<td><?=$ar_data['paddr'];?></td>
		</tr>
		<tr>
			<th>희망반납위치</th>
			<td><?=$ar_data['raddr'];?></td>
		</tr>
		<tr>
			<th>내용</th>
			<td style="padding:5px;"><?=nl2br($ar_data['memo']);?></td>
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
		<span class="greenBtn btn_submit" data-form="#regiform"><a href="javascript:">관리자메모등록</a></span>
	</div>
</form>