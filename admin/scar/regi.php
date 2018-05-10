<?
/**
 * admin.rentking.co.kr/scar.php?code=regi
 * 멤버사 > 차량관리 > 지점차량관리 > 등록
 */
$ar_rent = sel_query_all("rentshop"," WHERE idx={$_SESSION['rentshop_idx']}");
$mode = $_REQUEST['mode'];
if($mode=='w')	{
	$price_managed = $_REQUEST['price_managed'];
	$price_longterm_managed = $_REQUEST['price_longterm_managed'];

	$value['rentshop_idx'] = $ar_rent['idx'];
	$value['model_idx'] = $_REQUEST['model_idx'];
	$value['fuel_idx'] = $_REQUEST['fuel_idx'];
	$value['outdate'] = $_REQUEST['outdate'];
	$value['carnum'] = $_REQUEST['carnum'];
	$value['rundistan'] = $_REQUEST['rundistan'];
	$value['color'] = $_REQUEST['color'];
	$value['nosmoke'] = $_REQUEST['nosmoke'];
	$value['insu_per'] = $_REQUEST['insu_per'];
	$value['insu_goods'] = intval(str_replace(',', '', $_REQUEST['insu_goods']));
	$value['insu_self'] = intval(str_replace(',', '', $_REQUEST['insu_self']));
	$value['rentage'] = $_REQUEST['rentage'];
	$value['license_limit'] = intval(str_replace(',', '', $_REQUEST['license_limit']));
	$value['dt_create'] = date("Y-m-d H:i:s");
	$value['dt_update'] = date("Y-m-d H:i:s");
	$value['price_extra_idx'] = $_REQUEST['price_extra_idx'];

	if($price_managed == 'Y') {
		$value['price_idx'] = $_REQUEST['price_idx'];
	} else {
		$price_idx = $_REQUEST['price_idx'];

		$price_discount2 = floatval($_REQUEST['price_discount2'])/100;
		$price_discount3 = floatval($_REQUEST['price_discount3'])/100;
		$price_discount4 = floatval($_REQUEST['price_discount4'])/100;
		$price = intval(str_replace(',', '', $_REQUEST['price']));
		$price_hour = intval(str_replace(',', '', $_REQUEST['price_hour']));
		$price_holiday = intval(str_replace(',', '', $_REQUEST['price_holiday']));
		$price_del1 = intval(str_replace(',', '', $_REQUEST['price_del1']));
		$price_del2 = intval(str_replace(',', '', $_REQUEST['price_del2']));
		$price_del3 = intval(str_replace(',', '', $_REQUEST['price_del3']));
		$price_del4 = intval(str_replace(',', '', $_REQUEST['price_del4']));
		$price_insu0_check = $_REQUEST['price_insu0_check'] == 'Y' ? 'Y' : 'N';
		$price_insu1_check = $_REQUEST['price_insu1_check'] == 'Y' ? 'Y' : 'N';
		$price_insu1_1 = intval(str_replace(',', '', $_REQUEST['price_insu1_1']));
		$price_insu1_2 = intval(str_replace(',', '', $_REQUEST['price_insu1_2']));
		$price_insu1_3 = intval(str_replace(',', '', $_REQUEST['price_insu1_3']));
		$price_insu1_4 = intval(str_replace(',', '', $_REQUEST['price_insu1_4']));
		$price_insu1_exem = intval(str_replace(',', '', $_REQUEST['price_insu1_exem']));
		$price_insu1_limit = intval(str_replace(',', '', $_REQUEST['price_insu1_limit']));
		$price_insu2_check = $_REQUEST['price_insu2_check'] == 'Y' ? 'Y' : 'N';
		$price_insu2_1 = intval(str_replace(',', '', $_REQUEST['price_insu2_1']));
		$price_insu2_2 = intval(str_replace(',', '', $_REQUEST['price_insu2_2']));
		$price_insu2_3 = intval(str_replace(',', '', $_REQUEST['price_insu2_3']));
		$price_insu2_4 = intval(str_replace(',', '', $_REQUEST['price_insu2_4']));
		$price_insu2_exem = intval(str_replace(',', '', $_REQUEST['price_insu2_exem']));
		$price_insu2_limit = intval(str_replace(',', '', $_REQUEST['price_insu2_limit']));
		$price_insu3_check = $_REQUEST['price_insu3_check'] == 'Y' ? 'Y' : 'N';
		$price_insu3_1 = intval(str_replace(',', '', $_REQUEST['price_insu3_1']));
		$price_insu3_2 = intval(str_replace(',', '', $_REQUEST['price_insu3_2']));
		$price_insu3_3 = intval(str_replace(',', '', $_REQUEST['price_insu3_3']));
		$price_insu3_4 = intval(str_replace(',', '', $_REQUEST['price_insu3_4']));
		$price_insu3_exem = intval(str_replace(',', '', $_REQUEST['price_insu3_exem']));
		$price_insu3_limit = intval(str_replace(',', '', $_REQUEST['price_insu3_limit']));
		$price_net = floatval(str_replace(',', '', $_REQUEST['price_net'])) / 100;

		$query = "
			INSERT INTO
				vehicle_price (
					member_idx, 
					rentshop_idx,
					price_discount2,
					price_discount3,
					price_discount4,
					price,
					price_hour,
					price_holiday,
					price_del1,
					price_del2,
					price_del3,
					price_del4,
					price_insu0_check,
					price_insu1_check,
					price_insu1_1,
					price_insu1_2,
					price_insu1_3,
					price_insu1_4,
					price_insu1_exem,
					price_insu1_limit,
					price_insu2_check,
					price_insu2_1,
					price_insu2_2,
					price_insu2_3,
					price_insu2_4,
					price_insu2_exem,
					price_insu2_limit,
					price_insu3_check,
					price_insu3_1,
					price_insu3_2,
					price_insu3_3,
					price_insu3_4,
					price_insu3_exem,
					price_insu3_limit,
					price_net,
					dt_create
				) VALUES (
					'$g_memidx',
					'{$ar_rent['idx']}',
					'$price_discount2',
					'$price_discount3',
					'$price_discount4',
					'$price',
					'$price_hour',
					'$price_holiday',
					'$price_del1',
					'$price_del2',
					'$price_del3',
					'$price_del4',
					'$price_insu0_check',
					'$price_insu1_check',
					'$price_insu1_1',
					'$price_insu1_2',
					'$price_insu1_3',
					'$price_insu1_4',
					'$price_insu1_exem',
					'$price_insu1_limit',
					'$price_insu2_check',
					'$price_insu2_1',
					'$price_insu2_2',
					'$price_insu2_3',
					'$price_insu2_4',
					'$price_insu2_exem',
					'$price_insu2_limit',
					'$price_insu3_check',
					'$price_insu3_1',
					'$price_insu3_2',
					'$price_insu3_3',
					'$price_insu3_4',
					'$price_insu3_exem',
					'$price_insu3_limit',
					'$price_net',
					NOW()
				)
		";
		mysql_query($query);
		$value['price_idx'] = mysql_insert_id();
	}

	if($price_longterm_managed == 'Y') {
		$value['price_longterm_idx'] = $_REQUEST['price_longterm_idx'];
	} else {
		$price_longterm_idx = $_REQUEST['price_longterm_idx'];
		$price_longterm1 = intval(str_replace(',', '', $_REQUEST['price_longterm1']));
		$price_longterm2 = intval(str_replace(',', '', $_REQUEST['price_longterm2']));
		$price_longterm3 = intval(str_replace(',', '', $_REQUEST['price_longterm3']));
		$price_longterm_deposit1 = intval(str_replace(',', '', $_REQUEST['price_longterm_deposit1']));
		$price_longterm_deposit2 = intval(str_replace(',', '', $_REQUEST['price_longterm_deposit2']));
		$price_longterm_deposit3 = intval(str_replace(',', '', $_REQUEST['price_longterm_deposit3']));
		$price_longterm_insu_exem = intval(str_replace(',', '', $_REQUEST['price_longterm_insu_exem']));
		$price_longterm_insu_limit = intval(str_replace(',', '', $_REQUEST['price_longterm_insu_limit']));
		$distance_limit = intval(str_replace(',', '', $_REQUEST['distance_limit']));
		$distance_additional_price = intval(str_replace(',', '', $_REQUEST['distance_additional_price']));
		$price_longterm_net = floatval(str_replace(',', '', $_REQUEST['price_longterm_net'])) / 100;

		$query = "
			INSERT INTO
				vehicle_price_longterm (
					member_idx, 
					rentshop_idx,
					price_longterm1,
					price_longterm2,
					price_longterm3,
					price_longterm_deposit1,
					price_longterm_deposit2,
					price_longterm_deposit3,
					price_longterm_insu_exem,
					price_longterm_insu_limit,
					distance_limit,
					distance_additional_price,
					price_longterm_net,
					dt_create
				) VALUES (
					'$g_memidx',
					'{$ar_rent['idx']}',
					'$price_longterm1',
					'$price_longterm2',
					'$price_longterm3',
					'$price_longterm_deposit1',
					'$price_longterm_deposit2',
					'$price_longterm_deposit3',
					'$price_longterm_insu_exem',
					'$price_longterm_insu_limit',
					'$distance_limit',
					'$distance_additional_price',
					'$price_longterm_net',
					NOW()
				)
		";
		mysql_query($query);
		$value['price_longterm_idx'] = mysql_insert_id();
	}

	insert("vehicles",$value);
	unset($value);
	$idx = mysql_insert_id();
	
	for($i=0;$i<sizeof($caropt_idx);$i++)	{
		$value['vehicle_idx'] = $idx;
		$value['op_idx'] = $caropt_idx[$i];
		$value['op_name'] = $caropt_name[$i];
		$value['op_data'] = $catopt[$i];
		insert("vehicle_opt",$value);
		unset($value);
	}



	echo "<script>alert('등록완료'); location.replace('/scar.php?code=$code'); </script>";
	exit;
}
?>
<form name="regiform" id="regiform" action="/scar.php?code=<?=$code;?>" method="post" enctype="multipart/form-data">
	<input type='hidden' name='mode' value='w'>
	<div style="margin-top:10px;">
		<label>차량정보[선택] </label>
		<select id='caridx'>
			<option value=''>차량번호선택</option>
			<?
			$q = "SELECT * FROM vehicles WHERE rentshop_idx='{$ar_rent['idx']}' ORDER BY carnum";
			$r = mysql_query($q);
			while($row = mysql_fetch_array($r))	{
				echo "<option value='{$row['carnum']}'>{$row['carnum']}</option>";
			}
			?>
		</select>
		<a href="#loadCar" id="loadCar"><span class="blackBtn">불러오기</span></a>


		<label>차량정보[입력] </label>
		<input type='text' name='searchcarnum' id='searchcarnum' placeholder='차량변호를 기입하세요'>
		<a href="#loadCar2" id="loadCar2"><span class="blackBtn">불러오기</span></a>
	</div>

	<div class="firstTable">
		<div style="width:50%;box-sizing:border-box;">
			<span class="subTitle" style="margin-left:0;">차량일반정보</span>
			<table class="detailTable2" style="display:table;width:100%;">
				<tbody>
				<tr>
					<th>제조사</th>
					<td>
						<select name="company_idx" id="company_idx" valch="yes" msg="제조사" class="mWt200">
							<option value="">제조사 선택</option>
							<?
							for($i=0;$i<sizeof($ar_ttype1_idx);$i++)	{
								echo "<option value='".$ar_ttype1_idx[$i]."'>".$ar_ttype1_sname[$ar_ttype1_idx[$i]]."</option>";
							}
							?>
						</select>
					</td>
				</tr>
				<tr>
					<th>차종</th>
					<td>
						<select name="grade_idx" id="grade_idx" valch="yes" msg="등급" class="mWt200">
							<option value="">차종선택</option>
							<?
							for($i=0;$i<sizeof($ar_ttype2_idx);$i++)	{
								echo "<option value='".$ar_ttype2_idx[$i]."'>".$ar_ttype2_sname[$ar_ttype2_idx[$i]]."</option>";
							}
							?>
						</select>
					</td>
				</tr>
				<tr>
					<th>모델</th>
					<td>
						<select name="model_idx" id="model_idx" valch="yes" msg="차량모델" class="mWt200">
							<option value="">모델선택</option>
						</select>
					</td>
				</tr>
				<tr>
					<th>연료</th>
					<td>
						<select name="fuel_idx" id="fuel_idx" valch="yes" msg="연료" class="mWt200">
						</select>
					</td>
				</tr>
				<tr>
					<th>출고일</th>
					<td>
						<select name="outdate" valch="yes" msg="출고일 년도를">
					<option value=''>출고일선택</option>
					<?
					for($i=15;$i>=-1;$i--)	{
						$year = date("Y")-$i;
						echo "<option value='$year'>$year</option>";
					}
					?>
					</td>
				</tr>
				<tr>
					<th>차량번호</th>
					<td>
						<input type="text" name="carnum" valch="yes" msg="차량번호" class="mWt200" placeholder="차량번호는 띄지 말고 써주세요" maxlength="7" data-checked="">
					</td>
				</tr>
				<tr>
					<th>주행거리</th>
					<td><input type="text" name="rundistan" valch="yes" msg="마일리지" class="mWt200"></td>
				</tr>
				<tr>
					<th>색상</th>
					<td>
						<select name='color' valch='yes' msg='색상'>
						<option value=''>색상선택</option>
							<?
							for($i=0;$i<sizeof($ar_ttype4_idx);$i++) {
								echo "<option value='".$ar_ttype4_sname[$ar_ttype4_idx[$i]]."'>".$ar_ttype4_sname[$ar_ttype4_idx[$i]]."</option>";
							}
							?>
						</select>
					</td>
				</tr>
				<tr>
					<th>금연여부</th>
					<td><label><input type='checkbox' name='nosmoke' value='Y' <?= $item['nosmoke']=='Y' ? "checked" : ""?>>금연차</label></td>
				</tr>
				</tbody>
			</table>
		</div>

		<div style="width:50%;box-sizing:border-box;padding-left:5px;">
			<span class="subTitle" style="margin-left:0;">기본제공옵션</span>
			<table class="detailTable2" style="display:table;width:100%;">
			<tbody>
			<?
			$q = "SELECT * FROM vehicle_opt_std WHERE dt_delete IS NULL order by idx asc";
			$r = mysql_query($q);
			while($row = mysql_fetch_array($r))	{

			?>
			<tr>
				<th><?=$row['itemname'];?></th>
				<td>
					<input type='hidden' name='caropt_idx[]' value='<?=$row['idx'];?>'>
					<input type='hidden' name='caropt_name[]' value='<?=$row['itemname'];?>'>
					<select name="catopt[]" class="mWt150">
						<?
						if($row['ishave']=='N')	{
							echo "<option value=''>없음</option>";
						}
						unset($ar_optd);
						$ar_optd = explode("|R|",$row['bases']);
						for($i=0;$i<sizeof($ar_optd);$i++)	{
							if($ar_optd[$i]!='')	{
								echo "<option value='".$ar_optd[$i]."'>".$ar_optd[$i]."</option>";
							}
						}
						?>

					</select>
				</td>
			</tr>
			<?}?>
			</tbody>
		</table>
		</div>
	</div>

	<div class="subTitle" style="margin-left:0;margin-top:20px;">
		단기요금
		<input type="hidden" name="price_managed" value="<?=$item['price_managed'] ?>" />
		<select name="price_idx" id="priceIdx" style="margin-right:20px;">
			<option value="-1">직접입력</option>
			<option value="1">비노출</option>
			<?
			$r = mysql_query("SELECT * FROM vehicle_price WHERE dt_delete IS NULL AND managed='Y' AND rentshop_idx={$ar_rent['idx']}");
			while($row = mysql_fetch_array($r)) {?>
				<option value="<?=$row['idx']?>" <?=$row['idx'] == $item['price_idx'] ? 'selected' : '' ?>><?=$row['title']?></option>
			<?}?>
		</select>

		특별요금
		<select name="price_extra_idx" id="priceExtraIdx">
			<option value="0">선택안함</option>
			<?
			$priceExtraListQuery = "
									SELECT 
										vpe.idx,
										vpe.title
									FROM 
										vehicle_price_extra vpe 
									WHERE 
										vpe.rentshop_idx = {$ar_rent['idx']} 
										AND vpe.dt_delete IS NULL";
			$priceExtraListResult = mysql_query($priceExtraListQuery);
			while ($row = mysql_fetch_array($priceExtraListResult)) { ?>
				<option value="<?=$row['idx']?>" <?=$row['idx'] == $item['price_extra_idx'] ? 'selected' : ''?>><?=$row['title']?></option>
			<?}
			?>
		</select>
	</div>
	<? include "price_table.php"; ?>

	<div class="subTitle" style="margin-left:0;margin-top:20px;">
		장기요금
		<input type="hidden" name="price_longterm_managed" value="<?=$item['price_longterm_managed'] ?>" />
		<select name="price_longterm_idx" id="priceLongtermIdx">
			<option value="-1">직접입력</option>
			<option value="1">비노출</option>
			<?
			$r = mysql_query("SELECT * FROM vehicle_price_longterm WHERE dt_delete IS NULL AND managed='Y' AND rentshop_idx={$ar_rent['idx']}");
			while($row = mysql_fetch_array($r)) {?>
				<option value="<?=$row['idx']?>" <?=$row['idx'] == $item['price_longterm_idx'] ? 'selected' : '' ?>><?=$row['title']?></option>
			<?}?>
		</select>
	</div>
	<? include "price_table_longterm.php"; ?>

	<span class="subTitle" style="margin-left:0;margin-top:20px;">보험사항</span>
	<table class="detailTable2">
		<thead>
		<tr>
			<th>구분</th>
			<th>입력</th>
		</tr>
		</thead>
		<tbody>
		<tr>
			<th>대인</th>
			<td>무한<input type="hidden" name='insu_per' value="무한" readonly></td>
		</tr>
		<tr>
			<th>대물</th>
			<td><input type="text" name='insu_goods' class="inputNumber"> 원</td>

		</tr>
		<tr>
			<th>자손</th>
			<td><input type="text" name='insu_self' class="inputNumber"> 원</td>
		</tr>
		<tr>
			<th>연령제한</th>
			<td colspan="2">
				<input type="radio" name="rentage" value='21' id="rentage21"><label for="rentage21">만 21세 이상</label>
				<input type="radio" name="rentage" value='26' id="rentage26"><label for="rentage26">만 26세 이상</label>
				<input type="radio" name="rentage" value='30' id="rentage30"><label for="rentage30">만 30세 이상</label>
				<input type="radio" name="rentage" value='32' id="rentage32"><label for="rentage32">만 32세 이상</label>
			</td>
		</tr>
		<tr>
			<th>먼허제한</th>
			<td>
				만 <input type="text" class="mWt80 inputNumber" name="license_limit" value="0" /> 년 이상
			</td>
		</tr>
		</tbody>
	</table>

	<div class="topBtn" style='text-align:center;'>
		<span class="greenBtn btn_submit" data-form="#regiform"><a href="javascript:">등록</a></span>
		<a href="/scar.php?code=list"><span class="greenBtn">목록</span></a>
	</div>

</form>
<script type="text/javascript" src="/js/price.js?20170417030357"></script>
<script type="text/javascript">
	var fuelList = {};
	<?
	$query = "SELECT * FROM codes WHERE ttype=3 AND dt_delete IS NULL";
	$r = mysql_query($query);
	while($row = mysql_fetch_assoc($r)) {?>
	fuelList['<?=$row['idx'] ?>'] = '<?=$row['sname'] ?>';
	<?}?>
	$(function() {
		function loadCar(carnum) {
			$.getJSON('/mo/proajax.php?modtype=car_common&han=loadcar&carnum='+encodeURIComponent(carnum), function(result){
				if(typeof result['res'] !== 'undefined' && result['res']=='ok')	{
					var $form = $('#regiform');
					var vehicle = result['vehicle'];
					var model = result['model'];
					var price = result['price'];
					var priceLongterm = result['price_longterm'];

					var companyIdx = model['company_idx'];
					var gradeIdx = model['grade_idx'];

					$('#company_idx').val(companyIdx);
					$('#grade_idx').val(gradeIdx);

					getModel(companyIdx, gradeIdx, function() {
						$('#model_idx').val(model['idx']).trigger('change');
					});

					$form.find('input[name="price_managed"]').val(price['managed']);
					if(price['managed'] == 'Y') {
						$('#priceIdx').val(price['idx']);
					}
					$form.find('input[name="price_discount2"]').val(parseFloat(price['price_discount2'])*100);
					$form.find('input[name="price_discount3"]').val(parseFloat(price['price_discount3'])*100);
					$form.find('input[name="price_discount4"]').val(parseFloat(price['price_discount4'])*100);
					$form.find('input[name="price"]').val(price['price']);
					$form.find('input[name="price_hour"]').val(price['price_hour']);
					$form.find('input[name="price_holiday"]').val(price['price_holiday']);

					$form.find('input[name="price_del1"]').val(price['price_del1']);
					$form.find('input[name="price_del2"]').val(price['price_del2']);
					$form.find('input[name="price_del3"]').val(price['price_del3']);
					$form.find('input[name="price_del4"]').val(price['price_del4']);

					if(price['price_insu0_check'] == 'Y') {
						$form.find('input[name="price_insu0_check"]')[0].checked = true;
					}

					if(price['price_insu1_check'] == 'Y') {
						$form.find('input[name="price_insu1_check"]')[0].checked = true;
					}
					$form.find('input[name="price_insu1_1"]').val(price['price_insu1_1']);
					$form.find('input[name="price_insu1_2"]').val(price['price_insu1_2']);
					$form.find('input[name="price_insu1_3"]').val(price['price_insu1_3']);
					$form.find('input[name="price_insu1_4"]').val(price['price_insu1_4']);
					$form.find('input[name="price_insu1_exem"]').val(price['price_insu1_exem']);

					if(price['price_insu2_check'] == 'Y') {
						$form.find('input[name="price_insu2_check"]')[0].checked = true;
					}
					$form.find('input[name="price_insu2_1"]').val(price['price_insu2_1']);
					$form.find('input[name="price_insu2_2"]').val(price['price_insu2_2']);
					$form.find('input[name="price_insu2_3"]').val(price['price_insu2_3']);
					$form.find('input[name="price_insu2_4"]').val(price['price_insu2_4']);
					$form.find('input[name="price_insu2_exem"]').val(price['price_insu2_exem']);

					if(price['price_insu3_check'] == 'Y') {
						$form.find('input[name="price_insu3_check"]')[0].checked = true;
					}
					$form.find('input[name="price_insu3_1"]').val(price['price_insu3_1']);
					$form.find('input[name="price_insu3_2"]').val(price['price_insu3_2']);
					$form.find('input[name="price_insu3_3"]').val(price['price_insu3_3']);
					$form.find('input[name="price_insu3_4"]').val(price['price_insu3_4']);
					$form.find('input[name="price_insu3_exem"]').val(price['price_insu3_exem']);

					$form.find('input[name="price_net"]').val(parseFloat(price['price_net']) * 100);

					$form.find('input[name="price_longterm_managed"]').val(priceLongterm['managed']);
					if(priceLongterm['managed'] == 'Y') {
						$('#priceLongtermIdx').val(priceLongterm['idx']);
					}

					$form.find('input[name="price_longterm1"]').val(priceLongterm['price_longterm1']);
					$form.find('input[name="price_longterm2"]').val(priceLongterm['price_longterm2']);
					$form.find('input[name="price_longterm3"]').val(priceLongterm['price_longterm3']);

					$form.find('input[name="price_longterm_deposit1"]').val(priceLongterm['price_longterm_deposit1']);
					$form.find('input[name="price_longterm_deposit2"]').val(priceLongterm['price_longterm_deposit2']);
					$form.find('input[name="price_longterm_deposit3"]').val(priceLongterm['price_longterm_deposit3']);

					$form.find('input[name="price_longterm_insu_exem"]').val(priceLongterm['price_longterm_insu_exem']);

					$form.find('input[name="price_longterm_net"]').val(parseFloat(priceLongterm['price_longterm_net']) * 100);

					$form.find('input[name="insu_per"]').val(vehicle['insu_per']);
					$form.find('input[name="insu_goods"]').val(vehicle['insu_goods']);
					$form.find('input[name="insu_self"]').val(vehicle['insu_self']);
					$form.find('input[name="rentage"][value="'+vehicle['rentage']+'"]')[0].checked = true;
					$form.find('input[name="license_limit"]').val(vehicle['license_limit']);


					$form.find('.inputNumber').trigger('format');
					calculatePrice();
				}
				else	{
					alert('차량정보를 불러올 수 없습니다.');
				}
			});
		}
		function getModel(companyIdx, gradeIdx, callback) {
			if(companyIdx != '' && gradeIdx != '')	{
				var param = "company_idx="+companyIdx+"&grade_idx="+gradeIdx;

				$.getJSON('/mo/proajax.php?modtype=car_common&han=getmodel&'+param, function(result){
					if(result['res']=='ok')	{
						var $modelIdx = $("#model_idx");
						$modelIdx.html('');

						var str ='<option value="">모델선택</option>';
						$(result.data).each(function(index,item)	{
							str = str + '<option value="'+item.idx+'" data-fuel="'+item.fuel+'">'+item.name+'</option>';
						});

						$modelIdx.html(str);

						if(typeof callback === 'function') {
							callback();
						}
					}
					else	{
						alert('모델을 불러올 수 없습니다.');
					}
				});
			}
		}
		$(document)
			.on('submit', '#regiform', function() {
				var $carnum = $('input[name="carnum"]');

				if(!$carnum.val().match(/[0-9]{2}.[0-9]{4}/)) {
					alert('차량번호는 "11호1111" 형식으로 입력하세요');
					$carnum.focus();
					return false;
				}

				var checked = $carnum.data('checked');
				if(typeof checked === 'undefined' || (checked.match(/[0-9]{2}.[0-9]{4}/) && checked != $carnum.val())) {
					alert('차량번호 중복체크가 안됐습니다.');
					$carnum.focus();
					return false;
				}

				if(checked == 'ERR001') {
					alert('이미 등록된 챠량번호입니다.');
					$carnum.focus();
					return false;
				}

				if(check_form($(this).attr('id')) && checkPriceTable() && checkPriceLongtermTable())	{
					return confirm('저장 하시겠습니까?');
				} else {
					return false;
				}
			})
			.on('input', 'input[name="carnum"]', function(e) {
				var $carnum = $(this);
				var carnum = $carnum.val();
				var checked = $carnum.data('checked');

				if(carnum.match(/[0-9]{2}.[0-9]{4}/) && checked != carnum) {
					$.getJSON('/mo/proajax.php?modtype=car_common&han=getcarbycarnum', {carnum: carnum}, function(data) {
						if(typeof data === 'object') {
							if(data.length < 1) {
								$carnum.data('checked', carnum)
							} else {
								$carnum.data('checked', 'ERR001')
							}
						} else {
							alert('중복체크 실패');
						}
					})
				}
			})
			.on('change', '#company_idx, #grade_idx', function() {
				getModel($('#company_idx').val(), $('#grade_idx').val());
			})
			.on('change', '#model_idx', function() {
				var $fuelIdx = $('#fuel_idx');
				var $modelIdx = $(this);
				var fuels = $modelIdx.find('option:selected').data('fuel');
				fuels = (fuels+'').split('|');
				var html = '';
				for(var idx in fuels) {
					html += '<option class="fuel" value="' + fuels[idx] + '">' + fuelList[fuels[idx]+''] + '</option>';
				}
				$fuelIdx.html(html);
			})
			.on('click', '#loadCar', function(e) {
				e.preventDefault();

				var carnum = $('#caridx').val();
				if(carnum!='')	{
					loadCar(carnum);
				}
			})
			.on('click', '#loadCar2', function(e) {
				e.preventDefault();

				var carnum = $.trim($("#searchcarnum").val());
				if(carnum == '') {
					alert('차량번호를 입력하세요');
					return;
				}
				loadCar(carnum)
			})
	});
</script>

		