<?php
$mode = $_REQUEST['mode'];

if($mode == 'w') {
	if($_FILES['main_w'] && $_FILES['main_w']['error'] == 0 && $_FILES['main_w']['name'] != '') {
		$file = fileUpload('main_w', 'config', 1, 'image.main.w');
		$query = "INSERT INTO config (type, value) VALUES ('image.main.w', '${file['path']}') ON DUPLICATE KEY UPDATE value='{$file['path']}'";
		mysql_query($query);
	}

	if($_FILES['main_m'] && $_FILES['main_m']['error'] == 0 && $_FILES['main_m']['name'] != '') {
		$file = fileUpload('main_m', 'config', 1, 'image.main.m');
		$query = "INSERT INTO config (type, value) VALUES ('image.main.m', '${file['path']}') ON DUPLICATE KEY UPDATE value='{$file['path']}'";
		mysql_query($query);
	}
	?>
	<script type="text/javascript">
		alert('저장되었습니다.');
		location.href='/sho.php?code=main';
	</script>
	<?
}

$query = "SELECT * FROM config WHERE type IN ('image.main.w', 'image.main.m')";
$r = mysql_query($query);
$image = [];
while($row = mysql_fetch_assoc($r)) {
	$image[$row['type']] = $row['value'];
}
?>

<form method="post" enctype="multipart/form-data" action="/sho.php?code=<?=$code;?>">
	<input type="hidden" name="mode" value="w" />

	<div>
		<div style="width:885px;float:left;margin-right:10px;">
			<img src="<?=$config['imgServer']?><?=$image['image.main.w'] ?>" style="width:100%;">
			<input type="file" name="main_w" />
		</div>
		<div style="width:320px;float:left;">
			<img src="<?=$config['imgServer']?><?=$image['image.main.m'] ?>" style="width:100%;">
			<input type="file" name="main_m" />
		</div>
		<div style="float:none;clear:both;"></div>
	</div>

	<div style="text-align:center;">
		<button type="submit" class="greenBtn">저장</button>
	</div>
</form>
