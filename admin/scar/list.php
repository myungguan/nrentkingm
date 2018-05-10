<?
/**
 * admin.rentking.co.kr/scar.php?code=list
 * 멤버사 > 차량관리 > 지점차량관리
 */
$mode = $_REQUEST['mode'];
if ($mode == 'delete') {
	$idx = $_REQUEST['del_idx'];
	$deleteQuery = "UPDATE vehicles SET dt_delete = NOW() WHERE idx={$idx}";
	$deleteResult = mysql_query($deleteQuery);
	if ($deleteResult) {
		echo "<script>alert('삭제 완료');</script>";
	} else
		echo "<script>alert('삭제에 실패했습니다. 다시 시도해주세요.');</script>";
}

if ($mode == 'changePrice') {
	$price_type = $_REQUEST['price_type'];
	$price_title = $_REQUEST['price_title'];
	$check_idx = $_REQUEST['check_idx'];

	$priceChangeQuery = "UPDATE 
			vehicles 
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
}

$searchType = $_REQUEST['searchType'];
$searchKeyword = trim($_REQUEST['searchKeyword']);
$searchYear = $_REQUEST['searchYear'];

$ar_rent = sel_query_all("rentshop", " WHERE idx={$_SESSION['rentshop_idx']}");
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

$q = "SELECT
		[FIELD] 
	FROM vehicles v 
		LEFT JOIN vehicle_price vp ON v.price_idx = vp.idx
		LEFT JOIN vehicle_price_longterm vpl ON v.price_longterm_idx = vpl.idx 
		LEFT JOIN vehicle_models vs ON v.model_idx = vs.idx
		LEFT JOIN vehicle_price_extra vpe ON v.price_extra_idx = vpe.idx
		LEFT JOIN codes c_fuel ON v.fuel_idx = c_fuel.idx
	WHERE 
		v.rentshop_idx='{$ar_rent['idx']}'
		AND v.dt_delete IS NULL";

$toYear = date("Y");

if ($searchKeyword) {
	$q .= " AND (v.carnum like '%".mysql_escape_string($searchKeyword)."%' OR vs.name like '%".mysql_escape_string($searchKeyword)."%')";
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
	vpe.title price_extra_title"
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
<form name="listform" id="listform" method="get" class="btn_right" enctype="multipart/form-data" action="scar.php">
	<input type="hidden" name="code" value="<?= $code ?>" />
	<input type="hidden" name="mode" value="" />
	<div style="width:100%;height:40px;margin-bottom:10px;">
		<div style="float:left;">
			<input type="text" name="searchKeyword" value="<?= $searchKeyword ?>" class="" style="margin-right: 20px;" placeholder="차량모델, 차량번호">
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

		<div style="float:right;">
			<input type="hidden" name="checkIdx[]" id="checkIdxList"/>
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
			<a href="/scar.php?code=regi"><span class="greenBtn">차량등록</span></a>
		</div>
	</div>
</form>

	<table class="listTableColor">
		<thead>
		<tr>
			<th><input type="checkbox" name="all_check_idx"/></th>
			<th>No.</th>
			<th>모델(연료)</th>
			<th>차량번호</th>
			<th>출고일</th>
			<th>색상</th>
			<!--
			<th>옵션</th>
			-->
			<th>기본요금</th>
			<th>1개월요금</th>
			<th>단기요금</th>
			<th>장기요금</th>
			<th>특별요금</th>
			<th>상태</th>
			<th>검색노출</th>
			<th></th>
		</tr>
		</thead>
		<tbody>
		<?
		for ($is = 0; $is < count($data); $is++) {
			$row = $data[$is];
			?>
			<tr>
				<td><input type="checkbox" name="check_idx[]" id="check_idx" value="<?= $row['idx'] ?>"/></td>
				<td><?= $row['idx']; ?></td>
				<td style="text-align:left;padding-left:5px;"><a href="/scar.php?code=mod&idx=<?= $row['idx']; ?>&page=<?=$page?>&searchKeyword=<?=$searchKeyword?>"><?= $row['modelname']; ?>(<?= $row['fuel_name'] ?>)</a></td>
				<td><a href="/scar.php?code=mod&idx=<?= $row['idx']; ?>&page=<?=$page?>&searchKeyword=<?=$searchKeyword?>"><?= $row['carnum']; ?></a></td>
				<td><?= $row['outdate']; ?></td>
				<td><?= $row['color']; ?></td>
				<!--
				<td onclick="location.href='/scar.php?code=mod&idx=<?= $row['idx']; ?>'">
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
				<td><?= $row['price_managed'] == 'Y' ? $row['price_title'] : '직접입력' ?></td>
				<td><?= $row['price_longterm_managed'] == 'Y' ? $row['price_longterm_title'] : '직접입력' ?></td>
				<td><?= $row['price_extra_idx'] == 0 ? '선택안함' : $row['price_extra_title'] ?></td>
				<td><? get_marketdan($row['status']); ?></td>
				<td><input type="checkbox" value="<?=$row['idx']?>" name="onsale" <?=$row['onsale'] == 'Y' ? 'checked' : '' ?> /> </td>
				<td>
					<a href="#delete<?=$row['idx'] ?>" class="blackBtn_small" onclick="MM_openBrWindow('/popup/caroff.php?vehicle_idx=<?=$row['idx'];?>','caroff<?=$row['idx'];?>','scrollbars=yes,width=1150,height=700,top=0,left=0');return false;">휴차설정</a>
					<a href="/reserve.php?code=list&searchkeyword=<?=$row['carnum']?>" class="blackBtn_small" target="_blank">예약내역</a>
					<a href="#delete<?=$row['idx']?>" class="blackBtn_small btnDelete" data-idx="<?=$row['idx']?> ">삭제</a>
				</td>
			</tr>
		<? } ?>
		</tbody>
	</table>
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

				$.getJSON('/mo/proajax.php?modtype=car_common&han=getpricetitle&rentshop_idx=' + <?=$ar_rent['idx']?> +'&price_type=' + price_type, function (result) {
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
			.on('click', '.btnDelete', function (e) {
				e.preventDefault();
				var $btn = $(this);
				var $listform = $('form[name="listform"]');

				if(confirm('삭제 하시겠습니까?')) {
					$listform.find('input[name="mode"]').val('delete');
					$listform.append('<input type="hidden" name="del_idx" value="' + $btn.data('idx') + '" />');
					$listform.attr('method', 'post');
					$listform.submit();
				}
			})
			.on('click', 'input[name="onsale"]', function(e) {
				$.getJSON('/mo/proajax.php?modtype=car_common&han=changeonsale', {idx:$(this).val(), onsale: this.checked ? 'Y' : 'N'});

			})
		;
		$('select[name="price_type"]').trigger('change');
	});
</script>