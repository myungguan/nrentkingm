<?
/**
 * admin.rentking.co.kr/scar.php?code=mod&idx=
 * 멤버사 > 차량관리 > 지점차량관리 > 수정
 */
$idx = $_REQUEST['idx'];
$ar_rent = sel_query_all("rentshop"," WHERE idx={$_SESSION['rentshop_idx']}");
$mode = $_REQUEST['mode'];
if($mode=='w')	{
	$price_managed = $_REQUEST['price_managed'];
	$price_longterm_managed = $_REQUEST['price_longterm_managed'];
	
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
	$value['price_extra_idx'] = $_REQUEST['price_extra_idx'];
	$value['dt_update'] = date("Y-m-d H:i:s");

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
		if($price_idx > 0) {
			$query = "
				UPDATE vehicle_price
				SET
					price_discount2='$price_discount2',
					price_discount3='$price_discount3',
					price_discount4='$price_discount4',
					price='$price',
					price_hour='$price_hour',
					price_holiday='$price_holiday',
					price_del1='$price_del1',
					price_del2='$price_del2',
					price_del3='$price_del3',
					price_del4='$price_del4',
					price_insu0_check='$price_insu0_check',
					price_insu1_check='$price_insu1_check',
					price_insu1_1='$price_insu1_1',
					price_insu1_2='$price_insu1_2',
					price_insu1_3='$price_insu1_3',
					price_insu1_4='$price_insu1_4',
					price_insu1_exem='$price_insu1_exem',
					price_insu1_limit='$price_insu1_limit',
					price_insu2_check='$price_insu2_check',
					price_insu2_1='$price_insu2_1',
					price_insu2_2='$price_insu2_2',
					price_insu2_3='$price_insu2_3',
					price_insu2_4='$price_insu2_4',
					price_insu2_exem='$price_insu2_exem',
					price_insu2_limit='$price_insu2_limit',
					price_insu3_check='$price_insu3_check',
					price_insu3_1='$price_insu3_1',
					price_insu3_2='$price_insu3_2',
					price_insu3_3='$price_insu3_3',
					price_insu3_4='$price_insu3_4',
					price_insu3_exem='$price_insu3_exem',
					price_insu3_limit='$price_insu3_limit',
					price_net='$price_net'
				WHERE
					idx = $price_idx
			";
			mysql_query($query);
		} else {
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

		if($price_longterm_idx > 0) {
			$query = "
				UPDATE vehicle_price_longterm
				SET
					price_longterm1='$price_longterm1',
					price_longterm2='$price_longterm2',
					price_longterm3='$price_longterm3',
					price_longterm_deposit1='$price_longterm_deposit1',
					price_longterm_deposit2='$price_longterm_deposit2',
					price_longterm_deposit3='$price_longterm_deposit3',
					price_longterm_insu_exem='$price_longterm_insu_exem',
					price_longterm_insu_limit='$price_longterm_insu_limit',
					distance_limit='$distance_limit',
					distance_additional_price='$distance_additional_price',
					price_longterm_net='$price_longterm_net'
				WHERE
					idx = $price_longterm_idx
			";
			mysql_query($query);
		} else {
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
	}

	update("vehicles",$value," WHERE idx='$idx'");
	unset($value);

	for($i=0;$i<sizeof($caropt_idx);$i++)	{
		
		$q = "SELECT * FROM vehicle_opt WHERE vehicle_idx='$idx' AND op_idx='".$caropt_idx[$i]."'";
		$r = mysql_query($q);
		$isit = mysql_num_rows($r);

		if($isit==0)	{
		
			$value['vehicle_idx'] = $idx;
			$value['op_idx'] = $caropt_idx[$i];
			$value['op_name'] = $caropt_name[$i];
			$value['op_data'] = $catopt[$i];
			insert("vehicle_opt",$value);
			unset($value);
		}
		else{
			$row = mysql_fetch_array($r);

			$value['op_idx'] = $caropt_idx[$i];
			$value['op_name'] = $caropt_name[$i];
			$value['op_data'] = $catopt[$i];
			update("vehicle_opt",$value," WHERE idx='{$row['idx']}'");
			unset($value);
		}
	}

	echo "<script>alert('수정완료'); location.replace('".$_SERVER['HTTP_REFERER']."'); </script>";
	exit;
}

$query = "
	SELECT
		r.idx rent_idx,
		vp.managed price_managed,
		vpl.managed price_longterm_managed,
		vp.*,
		vpl.*,
		v.*
	FROM vehicles v
		LEFT JOIN rentshop r ON v.rentshop_idx = r.idx
		LEFT JOIN vehicle_price vp ON v.price_idx = vp.idx
		LEFT JOIN vehicle_price_longterm vpl ON v.price_longterm_idx = vpl.idx
	WHERE
		v.idx=$idx
";
$item = mysql_fetch_array(mysql_query($query));
?>
<form name="regiform" id="regiform" action="/scar.php?code=<?=$code;?>&page=<?=$page?>&searchKeyword=<?=$searchKeyword?>" method="post" enctype="multipart/form-data">
	<input type="hidden" name="mode" value="w" />
	<input type="hidden" name="idx" value="<?=$idx;?>" />

	<div class="firstTable">
		<div style="width:50%;box-sizing:border-box;">
			<div class="subTitle" style="margin-left:0;">차량일반정보</div>
			<table class="detailTable2" style="display:table;width:100%;">
				<tbody>
				<tr>
					<th>제조사</th>
					<td>
						<?
						$ar_model = sel_query_all("vehicle_models"," WHERE idx='{$item['model_idx']}'");
						echo $ar_ttype1_sname[$ar_model['company_idx']];
						?>
					</td>
				</tr>
				<tr>
					<th>차종</th>
					<td>
						<?
						echo $ar_ttype2_sname[$ar_model['grade_idx']];
						?>
					</td>
				</tr>
				<tr>
					<th>모델</th>
					<td><?=$ar_model['name'];?></td>
				</tr>
				<tr>
					<th>연료</th>
					<td><?=$ar_ttype3_sname[$item['fuel_idx']];?></td>
				</tr>
				<tr>
					<th>출고일</th>
					<td>
					<select name="outdate" valch="yes" msg="출고일 년도를">
					<option value=''>출고일선택</option>
					<?
					for($i=15;$i>=-1;$i--)	{
						$year = date("Y")-$i;

						$se = "";
						if($item['outdate']==$year)	{
							$se = "selected";
						}
						echo "<option value='$year' $se>$year</option>";
					}
					?>
					</td>
				</tr>
				<tr>
					<th>차량번호</th>
					<td><input type="text" name="carnum" valch="yes" msg="차량번호" value="<?=$item['carnum'];?>" class="mWt200" placeholder="차량번호는 띄지 말고 써주세요" maxlength="7" data-checked="<?=$item['carnum'];?>" data-idx="<?=$item['idx'] ?>">
						<button class="blackBtn_small" id="checkCarnum" style="white-space:nowrap;vertical-align:top;display:none;">중복확인</button></td>
				</tr>
				<tr>
					<th>주행거리</th>
					<td><input type="text" name="rundistan" valch="yes" msg="마일리지" value='<?=$item['rundistan'];?>' class="mWt200"></td>
				</tr>
				<tr>
					<th>색상</th>
					<td>
						<select name='color' valch='yes' msg='색상'>
						<option value=''>색상선택</option>
							<?
							for($i=0;$i<sizeof($ar_ttype4_idx);$i++)	{
								$se = "";
								if($item['color']==$ar_ttype4_sname[$ar_ttype4_idx[$i]])	{
									$se = "selected";
								}
								echo "<option value='".$ar_ttype4_sname[$ar_ttype4_idx[$i]]."' $se>".$ar_ttype4_sname[$ar_ttype4_idx[$i]]."</option>";
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
			<div class="subTitle" style="margin-left:0;">기본제공옵션</div>
			<table class="detailTable2" style="display:table;width:100%;">
			<tbody>
			<?
			$q = "SELECT * FROM vehicle_opt_std WHERE dt_delete IS NULL order by idx asc";
			$r = mysql_query($q);
			while($row = mysql_fetch_array($r))	{
				$ar_opt = sel_query_all("vehicle_opt"," WHERE vehicle_idx='$idx' AND op_idx='{$row['idx']}'");
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
								$se = "";
								if($ar_opt['op_data']==$ar_optd[$i])	{
									$se = "selected";
								}

								echo "<option value='".$ar_optd[$i]."' $se>".$ar_optd[$i]."</option>";
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
			<option value="<?=$item['price_managed'] != 'Y' ? $item['price_idx'] : -1 ?>">직접입력</option>
			<option value="1" <?= $item['price_idx'] == 1 ? 'selected' : '' ?>>비노출</option>
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
			<option value="<?=$item['price_longterm_managed'] != 'Y' ? $item['price_longterm_idx'] : -1 ?>">직접입력</option>
			<option value="1" <?= $item['price_longterm_idx'] == 1 ? 'selected' : '' ?>>비노출</option>
			<?
			$r = mysql_query("SELECT * FROM vehicle_price_longterm WHERE dt_delete IS NULL AND managed='Y' AND rentshop_idx={$ar_rent['idx']}");
			while($row = mysql_fetch_array($r)) {?>
				<option value="<?=$row['idx']?>" <?=$row['idx'] == $item['price_longterm_idx'] ? 'selected' : '' ?>><?=$row['title']?></option>
			<?}?>
		</select>
	</div>
	<? include "price_table_longterm.php"; ?>

	<div class="subTitle" style="margin-left:0;margin-top:20px;">보험사항</div>
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
			<td>무한<input type="hidden" name='insu_per' value='<?=$item['insu_per'];?>' readonly></td>

		</tr>
		<tr>
			<th>대물</th>
			<td><input type="text" name='insu_goods' class="inputNumber" value='<?=$item['insu_goods'];?>'> 원</td>

		</tr>
		<tr>
			<th>자손</th>
			<td><input type="text" name='insu_self' class="inputNumber" value='<?=$item['insu_self'];?>' > 원</td>

		</tr>
		<tr>
			<th>연령제한</th>
			<td>
				<input type="radio" name="rentage" value='21' id="rentage21" <? if($item['rentage']=='21') { echo "checked";	}?>><label for="rentage21">만 21세 이상</label>
				<input type="radio" name="rentage" value='26' id="rentage26" <? if($item['rentage']=='26') { echo "checked";	}?>><label for="rentage26">만 26세 이상</label>
				<input type="radio" name="rentage" value='30' id="rentage30" <? if($item['rentage']=='30') { echo "checked";	}?>><label for="rentage30">만 30세 이상</label>
				<input type="radio" name="rentage" value='32' id="rentage32" <? if($item['rentage']=='32') { echo "checked";	}?>><label for="rentage32">만 32세 이상</label>
			</td>
		</tr>
		<tr>
			<th>먼허제한</th>
			<td>
				만 <input type="text" style="text-align:right;width:50px;" class="mWt80 inputNumber" name="license_limit" value="<?=$item['license_limit']?>" /> 년 이상
			</td>
		</tr>
		</tbody>
	</table>

	<div class="topBtn" style='text-align:center;'>
		<a href="javascript:" class="btn_submit" data-form="#regiform"><span class="greenBtn">저장</span></a>
		<a href="/scar.php?code=list&page=<?=$page?>&searchKeyword=<?=$searchKeyword?>"><span class="greenBtn">목록</span></a>
	</div>

</form>
<script type="text/javascript" src="/js/price.js?20170417030334"></script>
<script type="text/javascript">
	$(function() {
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
	});
</script>
