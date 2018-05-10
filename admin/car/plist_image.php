<?
/**
 * 어드민 > 차량관리 > 등록차량 이미지관리
 * admin.rentking.co.kr/car.php?code=regi
 * 차량 모델 등록 페이지
 */

$query = "SELECT
	t.*, f.path, CASE WHEN f.path IS NULL THEN 0 ELSE 1 END ord
FROM (
		 SELECT
			 vs.idx, vs.name, CASE WHEN v.color = '기타' THEN 'default' ELSE v.color END color
		 FROM vehicles v
			 LEFT JOIN vehicle_models vs ON v.model_idx = vs.idx
		 WHERE
			 v.dt_delete IS NULL
	 ) t
	LEFT JOIN files f ON f.article_type = 'car' AND f.article_idx = t.idx AND f.article_info = t.color
GROUP BY name, color
ORDER BY ord, name, color";
$r = mysql_query($query);
$list = [];
$noImage = 0;
while($row = mysql_fetch_assoc($r)) {
	$list[] = $row;
	if(!$row['path'])	$noImage++;
}
?>
<span class="subTitle"><?= number_format(count($list)); ?>건이 검색되었습니다.<br /><?=number_format($noImage) ?>건이 이미지가 등록되지 않았습니다.</span>
<ul class="car-image-list">
	<? foreach($list as $item) {?>
		<li style="width:298px;">
			<div class="title"><a href="/car.php?code=regi&idx=<?=$item['idx']?>" target="_blank"><?=$item['name'] ?>(<?=$item['color'] == 'default' ? '기타' : $item['color'] ?>)</a></div>
			<?if($item['path']) {?>
				<a class="image" href="<?=$config['imgServer'].'/'.$item['path']?>" target="_blank" style="display:block;height:182px;background:url(<?=$config['imgServer'].'/'.$row['path']?>) no-repeat center;"></a>
			<?} else {?>
				<span class="image" style="display:block;height:182px;background:url(https://via.placeholder.com/720x450/ddd) no-repeat center;"></span>
			<?}?>
		</li>
	<?}?>
</ul>
