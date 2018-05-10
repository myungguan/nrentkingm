<?
/**
 * admin.rentking.co.kr/scar.php?code=price
 * 멤버사 > 차량관리 > 단기요금관리
 */
$mode = $_REQUEST['mode'];
if($mode=='d')	{
	mysql_query("UPDATE vehicle_price SET dt_delete = NOW() WHERE idx = {$_REQUEST['idx']}");

	echo "<Script>alert('삭제완료'); location.replace('/scar.php?code=$code'); </script>";
	exit;
}
$ar_rent = sel_query_all("rentshop"," WHERE idx={$_SESSION['rentshop_idx']}");

$query = "
	SELECT
		vp.*,
		(SELECT COUNT(1) FROM vehicles v WHERE v.dt_delete IS NULL AND v.price_idx = vp.idx) car
	FROM
		vehicle_price vp
	WHERE
		vp.rentshop_idx = {$ar_rent['idx']}
		AND vp.managed = 'Y'
		AND vp.dt_delete IS nULL
	ORDER BY
		car DESC
";

$r = mysql_query($query);
$total_record = mysql_num_rows($r);
?>
<div class="topBtn">
	<a href="/scar.php?code=pricer"><span class="greenBtn">단기요금 등록</span></a>
</div>
<span class="subTitle">등록된 요금 : 총 <?=number_format($total_record);?></span>
<div>
	<table class="listTableColor">
		<thead>
			<tr>
				<th>No.</th>
				<th>제목</th>
				<th>기본요금</th>
				<th>사용차량</th>
				<th>등록일</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
		<?if($total_record < 1) {?>
			<tr>
				<td colspan="6">데이터가 없습니다.</td>
			</tr>
		<?}?>
		<?
		$num = $total_record;
		while($row = mysql_fetch_array($r)) {?>
			<tr>
				<td><?=$num--?></td>
				<td><a href="/scar.php?code=pricem&idx=<?=$row['idx']?>"><?=$row['title']?></a></td>
				<td style="text-align:right;"><?=number_format($row['price']) ?></td>
				<td><?=number_format($row['car']) ?></td>
				<td><?=$row['dt_create']?></td>
				<td><a class="blackBtn" onclick="delok('삭제하시겠습니까?','/scar.php?code=<?=$code;?>&mode=d&idx=<?=$row['idx'];?>');">삭제</a></td>
			</tr>
		<?}?>
		</tbody>
	</table>
</div>
