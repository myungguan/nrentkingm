<?
/**
 * 어드민 > 차량관리 > 신규 차량모델 등록 || 어드민 > 차량관리 > 모델관리 > 등록하기
 * admin.rentking.co.kr/car.php?code=regi
 * 차량 모델 등록 페이지
 */
$idx = $_REQUEST['idx'];
$query = "SELECT * FROM codes WHERE ttype=4 AND dt_delete IS NULL";
$colors = [];
$r = mysql_query($query);
while($row = mysql_fetch_assoc($r)) {
	$colors[] = $row;
}

if ($idx) {
	$query = "SELECT vs.*,
			image_default.path image_default_path,
			(
				SELECT GROUP_CONCAT(c.idx)
				FROM vehicle_model_fuel_codes vsfc2
				LEFT JOIN codes c ON vsfc2.code_idx = c.idx
				WHERE vsfc2.vehicle_model_idx = vs.idx
			) concat_fuel";

	foreach($colors as $color) {
		$query .= ", image_color{$color['idx']}.path image_color{$color['idx']}_path";
	}
	$query .= " FROM
			vehicle_models vs
			LEFT JOIN files image_default ON image_default.article_type = 'car' AND image_default.article_idx = $idx AND image_default.article_info = 'default'";

	foreach($colors as $color) {
		$query .= "LEFT JOIN files image_color{$color['idx']} ON image_color{$color['idx']}.article_type = 'car' AND image_color{$color['idx']}.article_idx = $idx AND image_color{$color['idx']}.article_info= '{$color['sname']}' ";
	}

	$query .="WHERE vs.idx = $idx";
	$ar_data = mysql_fetch_assoc(mysql_query($query));
}
$mode = $_REQUEST['mode'];
if ($mode == 'w') {
	$value['name'] = mysql_escape_string(trim($_REQUEST['name']));
	$value['company_idx'] = $_REQUEST['company_idx'];
	$value['grade_idx'] = $_REQUEST['grade_idx'];
	$fuels = $_REQUEST['fuel'];
	$fuel = '';
	$count = 0;
	$value['memcou'] = mysql_escape_string($_REQUEST['memcou']);
	if (!$ar_data) {
		$value['dt_create'] = date("Y-m-d H:i:s");
	} else {
		$value['dt_update'] = date("Y-m-d H:i:s");
	}


	if ($ar_data['idx']) {
		update("vehicle_models", $value, " WHERE idx='{$idx}'");

		$query = "DELETE FROM vehicle_model_fuel_codes WHERE vehicle_model_idx = $idx";
		mysql_query($query);
	} else {
		insert("vehicle_models", $value);
		$idx= mysql_insert_id();
	}

	foreach($fuels as $f) {
		$query = "INSERT INTO vehicle_model_fuel_codes(code_idx, vehicle_model_idx, dt_create)
						VALUES('$f', '$idx', NOW())";
		mysql_query($query);
	}

	if($_FILES['default'] && $_FILES['default']['error'] == 0 && $_FILES['default']['name'] != '') {
		mysql_query("UPDATE files SET article_idx = 0 WHERE article_type='car' AND  article_idx = $idx AND article_info = 'default'");
		fileUpload('default', 'car', $idx, 'default');
	}

	foreach($colors as $color) {
		if($_FILES['color'.$color['idx']] && $_FILES['color'.$color['idx']]['error'] == 0 && $_FILES['color'.$color['idx']]['name'] != '') {
			mysql_query("UPDATE files SET article_idx = 0 WHERE article_type='car' AND  article_idx = $idx AND article_info = '{$color['sname']}'");
			fileUpload('color'.$color['idx'], 'car', $idx, $color['sname']);
		}
	}

	echo "<script type='text/javascript'>alert('완료되었습니다'); location.replace('".$_SERVER['HTTP_REFERER']."'); </script>";
	exit;
}
?>
<script>
function foch(f) {
	var re = check_form(f);
	if(re) {
		return !!confirm('차종을 등록 하시겠습니까?');
	} else {
		return false;
	}
}
</script>
<div style="margin-top:20px;">
	<p>* 모든 항목을 입력해야 합니다.</p>
	<form name="regiform" id="regiform" method="post" enctype="multipart/form-data" action="../car.php?code=<?=$code;?>" onsubmit="return foch('regiform');">
	<input type="hidden" name="idx" value="<?=$idx;?>">
	<input type='hidden' name='mode' value='w'>
	<table class="detailTable2">
		<colgroup>
			<col style="width:100px;" />
			<col />
		</colgroup>
	<tbody>
	<tr>
		<th>제조사</th>
	    <td>
			<select name="company_idx" valch="yes" msg="제조사">
			<option value=''>제조사선택</option>
			<?
			for($i=0;$i<sizeof($ar_ttype1_idx);$i++)	{
				$se = "";
				if($ar_data['company_idx']==$ar_ttype1_idx[$i])	{
					$se = "selected";
				}
				echo "<option value='{$ar_ttype1_idx[$i]}' $se>{$ar_ttype1_sname[$ar_ttype1_idx[$i]]}</option>";
			}
			?>
			</select>
	     </td>
	</tr>
	<tr>
		<th>차량등급</th>
	    <td>
			<select name="grade_idx" valch="yes" msg="차량등급">
			<option value=''>등급선택</option>
			<?
			$q = "SELECT * FROM codes WHERE ttype='2' AND dt_delete IS NULL ORDER BY idx ASC";
			$r = mysql_query($q);
			while($row = mysql_fetch_array($r))	{
				$se = "";
				if($ar_data['grade_idx']==$row['idx'])	{
					$se = "selected";
				}
				echo "<option value='{$row['idx']}' $se>{$row['sname']}</option>";
			}
			?>
			</select>
	     </td>
	</tr>
	<tr>
		<th>모델명</th>
		<td><input type="text" maxlength="50" name="name" value="<?=$ar_data['name'];?>" valch="yes" msg="모델명"></td>
	</tr>
	<tr>
		<th>연료타입</th>
	    <td>
			<?
			$q = "SELECT * FROM codes WHERE ttype='3' AND dt_delete IS NULL ORDER BY idx ASC";
			$r = mysql_query($q);
			while($row = mysql_fetch_array($r))	{
				$se = "";
				if(strpos($ar_data['concat_fuel'], $row['idx']) !== FALSE)	{
					$se = "checked";
				}

				?>
				<label style="margin-right:5px;"><input type="checkbox" name="fuel[]" value="<?=$row['idx']?>" <?=$se ?>> <?=$row['sname'] ?></label>
				<?
			}
			?>
	     </td>
	</tr>
	<tr>
		<th>승차인원</th>
		<td><input type="text" maxlength="3" name="memcou" value="<?=$ar_data['memcou'];?>" valch="yes" msg="승차인원" isnum="yes"></td>
	</tr>
	<tr>
		<th>이미지</th>
		<td>
			<ul class="car-image-list">
				<li>
					<div class="title">기본이미지</div>
                    <?if($ar_data['image_default_path']!='') {?>
                        <a href="<?=$config['imgServer'].'/'?><?=$ar_data['image_default_path'];?>" target="_blank">
                            <img src="<?=$config['imgServer'].'/'?><?=$ar_data['image_default_path'];?>" style="width:100%;">
                        </a>
                    <?}else{?>
                        <div class="image" style="background:url(https://via.placeholder.com/720x450/ddd) no-repeat center;"></div>
                    <?}?>
					<div class="input">
						<input type='file' name='default'>
					</div>
				</li>
				<? foreach($colors as $color) {?>
					<li>
						<div class="title"><?=$color['sname'] ?></div>
                        <?if($ar_data['image_color'.$color['idx'].'_path']!='') {?>
                            <a href="<?=$config['imgServer'].'/'?><?=$ar_data['image_color'.$color['idx'].'_path'];?>" target="_blank">
                                <img src="<?=$config['imgServer'].'/'?><?=$ar_data['image_color'.$color['idx'].'_path'];?>" style="width:100%;">
                                <!--<div class="image" style="background:url(<?/*=$config['imgServer'].'/'*/?><?/*=$ar_data['image_color'.$color['idx'].'_path'];*/?>) no-repeat center;"></div>-->
                            </a>
                        <?}else{?>
                            <div class="image" style="background:url(https://via.placeholder.com/720x450/ddd) no-repeat center;"></div>
                        <?}?>
						<div class="input">
							<input type='file' name='color<?=$color['idx']?>'>
						</div>
					</li>
				<?}?>
			</ul>
		</td>
	</tr>
	</tbody>
	</table>
	<div class="topBtn functions">
		<span class="btn_submit" data-form="#regiform"><a href="javascript:" class="redBtn"><? if($ar_data['idx']){?>수정<?}else{?>등록<?}?></a></span>
		<a href="/car.php?code=plist&page=<?=$page?>&company_idx=<?=$company_idx?>&grade_idx=<?=$grade_idx?>&fuel_idx=<?=$fuel_idx?>&name=<?=$name?>" class="redBtn">목록으로</a>
	</div>
	</form>
</div>	
