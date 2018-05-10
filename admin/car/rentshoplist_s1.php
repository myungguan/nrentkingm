<?
/**
 * 어드민 > 차량관리 >업체별등록차량현황 > 상세조회(해당 업체 차량모델리스트)
 * admin.rentking.co.kr/car.php?code=rentshoplist_s1&idx=122
 * 해당 업체 등록 차량 리스트 페이지
 */
$idx = $_REQUEST['idx'];
$mode = $_REQUEST['mode'];

if ($mode == 'changePrice') {
	$price_type = $_REQUEST['price_type'];
	$price_title = $_REQUEST['price_title'];
	$check_idx = $_REQUEST['check_idx'];

	$priceChangeQuery = "
		UPDATE 
			vehicle 
		SET 
			{$price_type}_idx ={$price_title} ,
			dt_update = NOW() 
		WHERE 
			dt_delete IS NULL
			AND idx in(";

	for ($i = 0; $i < count($check_idx); $i++) {
		if ($i == count($check_idx) - 1)
			$priceChangeQuery .= "{$check_idx[$i]})";
		else
			$priceChangeQuery .= "{$check_idx[$i]},";
	}

	$priceChangeResult = mysql_query($priceChangeQuery);
	if ($priceChangeResult) {
		echo "<script>alert('요금 변경을 완료했습니다.');</script>";
	} else
		echo "<script>alert('요금 변경에 실패했습니다. 다시 시도해주세요.') </script>";
}else if($mode == 'tmp') {
    foreach($_REQUEST as $key => $value){
        if(strpos($key, 'tmp_') !== false){
            $vehicle_idx = explode('_',$key)[1];
            $tmp_model_idx = $value;

            $tmpQuery = "
		UPDATE 
			vehicles
		SET 
			tmp_model_idx = '$tmp_model_idx'
		WHERE 
			dt_delete IS NULL
			AND idx = '$vehicle_idx'";

            mysql_query($tmpQuery);
        }

    }
}

$searchType = $_REQUEST['searchType'];
$searchKeyword = trim($_REQUEST['searchKeyword']);
$searchYear = $_REQUEST['searchYear'];

$numper = $_REQUEST['numper'] ? $_REQUEST['numper'] : 25;

$page_per_block = 10;
$page = $_GET['page'] ? $_GET['page'] : 1;

/* 정렬 기본 */
if (!$sortcol)
	$sortcol = "v.dt_create";

if (!$sortby)
	$sortby = "desc";

/* //정렬 기본 */


//HTTP QUERY STRING
$keyword = trim($keyword);
$key = $_REQUEST['key'];
$qArr['numper'] = $numper;
$qArr['page'] = $page;
$qArr['code'] = $code;
$qArr['sortcol'] = $sortcol;
$qArr['sortby'] = $sortby;
$qArr['searchKeyword'] = $searchKeyword;
$qArr['searchYear'] = $searchYear;
$qArr['idx'] = $idx;

$q = "SELECT
		[FIELD] 
	FROM vehicles v 
		LEFT JOIN vehicle_price vp ON v.price_idx = vp.idx
		LEFT JOIN vehicle_price_longterm vpl ON v.price_longterm_idx = vpl.idx 
		LEFT JOIN vehicle_models vs ON v.model_idx = vs.idx
		LEFT JOIN vehicle_price_extra vpe ON v.price_extra_idx = vpe.idx
		LEFT JOIN codes c_fuel ON v.fuel_idx = c_fuel.idx
		LEFT JOIN rentshop r ON v.rentshop_idx = r.idx
	WHERE 
		v.dt_delete IS NULL";

if($idx)
	$q .= " AND v.rentshop_idx = $idx";

$toYear = date("Y");

if ($searchKeyword) {
	$q .= " AND (v.carnum like '%".mysql_escape_string($searchKeyword)."%' OR vs.name like '%".mysql_escape_string($searchKeyword)."%' OR r.name LIKE '%".mysql_escape_string($searchKeyword)."%')";
}
if ($searchYear) {
	if ($searchYear <= ($toYear - 3))
		$q = $q . " AND LEFT(v.outdate,4)<='{$searchYear}'";
	else
		$q = $q . " AND LEFT(v.outdate,4) = '{$searchYear}'";
}

//카운터쿼리
$sql = str_replace("[FIELD]", "COUNT(v.idx)", $q);
$r = mysql_query($sql);
$total_record = mysql_result($r, 0, 0);
mysql_free_result($r);

if ($total_record == 0) {
	$first = 0;
	$last = 0;
} else {
	$first = $numper * ($page - 1);
	$last = $numper * $page;
}

//데이터쿼리
$_sql = str_replace("[FIELD]", "
	v.*,
	vp.price,
	vpl.price_longterm1,
	vs.name modelname,
	c_fuel.sname fuel_name,
	vp.title price_title,
	vp.managed price_managed,
	vpl.title price_longterm_title,
	vpl.managed price_longterm_managed,
	vpe.title price_extra_title,
	r.name,
	r.affiliate,
	r.idx rentshop_idx
	"
	, $q);

$_tArr = explode(",", $sortcol);
if (is_array($_tArr) && count($_tArr)) {
	foreach ($_tArr as $v) {
		$orderbyArr[] = "{$v} {$sortby}";
	}
	$orderby = implode(", ", $orderbyArr);
}

$sql_order = " ORDER BY {$orderby}";
$sql_limit = " LIMIT $first, $numper";
$sql = $_sql . $sql_order . $sql_limit;

$r = mysql_query($sql);
while ($row = mysql_fetch_array($r)) {
	$data[] = $row;
}

//엑셀쿼리
$sql_excel = $_sql . $sql_order;
$_SESSION['sql_excel'] = $sql_excel;

?>

<span class="subTitle">검색된 차량은 총 <?= number_format($total_record); ?>대 입니다.</span>
<form name="listform" id="listform" method="get" class="btn_right" enctype="multipart/form-data" action="car.php">
	<input type="hidden" name="code" value="<?= $code ?>" />
	<input type="hidden" name="mode" value="" />
	<input type="hidden" name="idx" value="<?=$idx?>" />
	<div style="width:100%;height:40px;margin-bottom:10px;">
		<div style="float:left;">
			<input type="text" name="searchKeyword" value="<?= $searchKeyword ?>" class="" style="margin-right: 20px;width:180px;" placeholder="회사명,차량모델, 차량번호">
			출고일
			<select name="searchYear">
				<option value="0">선택안함</option>
				<?
				for ($i = 3; $i >= 0; $i--) { ?>
					<option value="<?= $toYear - $i ?>" <? if ($searchYear == ($toYear - $i)) {
						echo "selected";
					} ?>><? if ($i == 3) echo "~" ?><?= $toYear - $i ?></option>
					<?
				}
				?>
			</select>
			<span class="greenBtn btn_submit" data-form="#listform">검색하기</span>
		</div>

		<?if($idx) {?>
		<div style="float:right;">
			선택된 차량의
			<select name="price_type" id="priceType">
				<option value="price" <? if ($price_type == 'price' || !$price_type) echo "selected"; ?>>단기요금</option>
				<option value="price_longterm" <? if ($price_type == 'price_longterm') echo "selected"; ?>>장기요금</option>
				<option value="price_extra" <? if ($price_type == 'price_extra') echo "selected"; ?>>특별요금</option>
			</select>
			<span id="priceTitleDetail" style="visibility:hidden;">
				을
				<select name="price_title" id="priceTitle">
				</select>
				(으)로
				<span class="greenBtn" id="btnChangePrice">변경</span>
			</span>
		</div>
		<?}?>
	</div>
</form>
<form name="tmp_form" method="post">
    <input type="hidden" name="mode" value="tmp"/>
	<table class="listTableColor">
		<thead>
		<tr>
			<?if($idx) {?><th><input type="checkbox" name="all_check_idx"/></th><?}?>
			<th>No.</th>
			<th>회사</th>
			<th>차량모델</th>
			<th>차량번호</th>
			<th>출고일</th>
			<th>색상</th>
			<th>기본요금</th>
			<th>1개월요금</th>
			<th>특별요금</th>
			<th>상태</th>
			<?if(in_array($_SESSION['admin_grade'], [9])) {?>
				<th>검색노출</th>
				<th></th>
			<?}?>
            <th><button type="submit" style="line-height:20px;height:23px;" class="greenBtn">모델idx 저장</button></th>
		</tr>
		</thead>
		<tbody>
		<?
		for ($is = 0; $is < count($data); $is++) {
			$row = $data[$is];
			?>
			<tr>
				<?if($idx) {?><td><input type="checkbox" name="check_idx[]" id="check_idx" value="<?= $row['idx'] ?>"/></td><?}?>
				<td><?= $row['idx']; ?></td>
				<td style="text-align:left;padding-left:5px;"><a href="/mem.php?code=rentcarm&idx=<?=$row['rentshop_idx']?>" target="_blank"><?= $row['name']; ?>(<?=$row['affiliate']?>)</a></td>
				<td style="text-align:left;padding-left:5px;"><a href="/car.php?code=carview&idx=<?=$row['idx'];?>" target="_blank"><?= $row['modelname']; ?>(<?= $row['fuel_name'] ?>)</a></td>
				<td><a href="/car.php?code=carview&idx=<?=$row['idx'];?>" target="_blank"><?= $row['carnum']; ?></a></td>
				<td><?= $row['outdate']; ?></td>
				<td><?= $row['color']; ?></td>
				<!--
				<td>
					<?
				$qs = "SELECT * FROM vehicle_opt WHERE vehicle_idx={$row['idx']}";
				$rs = mysql_query($qs);
				$cos = 0;
				while ($rows = mysql_fetch_array($rs)) {
					if ($rows['op_data'] != '' && $rows['op_data'] != '없음') {
						if ($cos != 0) {
							echo "<br />";
						}
						echo $rows['op_name'] . ":" . $rows['op_data'];
						$cos++;
					}
				}
				?>
				</td>
				-->
				<td style="text-align:right;padding-right:5px;"><?= number_format($row['price']); ?></td>
				<td style="text-align:right;padding-right:5px;"><?= number_format($row['price_longterm1']); ?></td>
				<td><?= $row['price_extra_title'] ?></td>
				<!--
				<td style="text-align:left;padding:0 5px;">
					<?= $row['price_managed'] == 'Y' ? $row['price_title'] : '직접입력' ?><br />
					<?= $row['price_longterm_managed'] == 'Y' ? $row['price_longterm_title'] : '직접입력' ?><br />
					<?= $row['price_extra_idx'] == 0 ? '선택안함' : $row['price_extra_title'] ?>
				</td>
				-->
				<td><? get_marketdan($row['status']); ?></td>
				<?if(in_array($_SESSION['admin_grade'], [9])) {?>
				<td><input type="checkbox" value="<?=$row['idx']?>" name="onsale" <?=$row['onsale'] == 'Y' ? 'checked' : '' ?> /> </td>
				<td>
					<a href="#<?=$row['idx'];?>" class="blackBtn_small" style="white-space:nowrap;" onclick="MM_openBrWindow('/popup/caroff.php?vehicle_idx=<?=$row['idx'];?>','caroff<?=$row['idx'];?>','scrollbars=yes,width=1150,height=700,top=0,left=0');">휴차설정</a>
					<a href="/reserve.php?code=list&searchkeyword=<?=$row['carnum']?>" class="blackBtn_small" target="_blank" style="white-space:nowrap;">예약내역</a>
				</td>
				<?}?>
                <td><input type="text" name="tmp_<?=$row['idx']?>" value="<?=$row['tmp_model_idx']?>"></td>
			</tr>
		<? } ?>
		</tbody>
	</table>
</form>
	<div class="paging">
		<?= paging_admin($page, $total_record, $numper, $page_per_block, $qArr); ?>
	</div>

<script>
	$(function () {
		$(document)
			.on('change', 'select[name="price_type"]', function (e) {
				var price_type = $(this).val();
				var $priceTitle = $("#priceTitle");
				var $priceTitleDetail = $('#priceTitleDetail');
				$priceTitleDetail.css({visibility: 'hidden'});

				$.getJSON('/mo/proajax.php?modtype=car_common&han=getpricetitle&rentshop_idx=' + <?=$idx?> +'&price_type=' + price_type, function (result) {
					if (result.res == 'ok') {
						$priceTitle.html('');
						var str = '';
						if (price_type == 'price' || price_type == 'price_longterm') {
							str = '<option value="1">비노출</option>';
						} else {
							str = '<option value="0">선택안함</option>';
						}

						$(result.data).each(function (index, item) {
							str = str + '<option value="' + item.idx + '">' + item.title + '</option>';
						});

						$priceTitle.html(str);
						$priceTitleDetail.css({visibility: 'visible'});
					}
					else {
						alert(result.res);
					}
				});
			})
			.on('click', 'input[name="all_check_idx"]', function (e) {
				var $checkIdx = $('input[name="check_idx[]"]');

				var isChecked = $('input[name="all_check_idx"]')[0].checked;
				$checkIdx.each(function () {
					this.checked = isChecked;
				});
			})
			.on('click', 'input[name="check_idx[]"]', function (e) {
				var $checkIdx = $('input[name="check_idx[]"]');
				var $checkIdxChecked = $('input[name="check_idx[]"]:checked');

				$('input[name="all_check_idx"]')[0].checked = $checkIdx.length == $checkIdxChecked.length;
			})
			.on('click', '#btnChangePrice', function (e) {
				e.preventDefault();
				var $listform = $('form[name="listform"]');

				var $checkIdxChecked = $('input[name="check_idx[]"]:checked');
				if ($checkIdxChecked.length < 1) {
					alert('차량모델을 선택해주세요.');
					return false;
				} else {
					for(var i = 0; i < $checkIdxChecked.length; i++) {
						$('<input type="hidden" name="check_idx[]" value="' + $checkIdxChecked[i].value + '" checked />').appendTo($listform);
					}
				}
				$listform.find('input[name="mode"]').val('changePrice');
				$listform.attr('method', 'post');
				$listform.submit();
			})
			.on('click', 'input[name="onsale"]', function(e) {
				$.getJSON('/mo/proajax.php?modtype=car_common&han=changeonsale', {idx:$(this).val(), onsale: this.checked ? 'Y' : 'N'});

			})
		;
		$('select[name="price_type"]').trigger('change');
	});
</script>