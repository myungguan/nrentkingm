<?
/**
 * 어드민 > 차량관리 > 차종별등록현황 > 리스트 > 리스트 > 리스트 / 상세조회
 * 어드민 > 차량관리 > 업체별등록차량현황 > 상세조회(해당 업체 등록모델리스트) > 상세조회(해당 업체 등록모델에 대한 상세 차량 리스트) > 해당 차량에 대한 세부내용
 * admin.rentking.co.kr/car.php?code=carview&idx=2735
 * 차량 상세 정보 및 요금 페이지
 */
$idx = $_REQUEST['idx'];
$mode = $_REQUEST['mode'];

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
		v.idx={$idx}";
$item = mysql_fetch_array(mysql_query($query));
?>

<script type="text/javascript" src="../js/price.js?20170417030725"></script>
<div class="firstTable">
	<div style="width:50%;box-sizing:border-box;">
		<div class="subTitle" style="margin-left:0;">차량일반정보</div>
		<table class="detailTable" style="display:table;width:100%;">
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
				<td><?=$item['outdate'] ?></td>
			</tr>
			<tr>
				<th>차량번호</th>
				<td><?=$item['carnum'];?></td>
			</tr>
			<tr>
				<th>주행거리</th>
				<td><?=$item['rundistan'];?></td>
			</tr>
			<tr>
				<th>색상</th>
				<td><?=$item['color']?></td>
			</tr>
			<tr>
				<th>금연여부</th>
				<td><?if($item['nosmoke'] == 'Y') { echo 'Y'; } else { echo 'N'; } ?></td>
			</tr>
			</tbody>
		</table>
	</div>

	<div style="width:50%;box-sizing:border-box;padding-left:5px;">
		<div class="subTitle" style="margin-left:0;">기본제공옵션</div>
		<table class="detailTable_gray" style="display:table;width:100%;">
			<tbody>
			<?
			$q = "SELECT * FROM vehicle_opt_std WHERE dt_delete IS NULL ORDER BY idx ASC";
			$r = mysql_query($q);
			while($row = mysql_fetch_array($r))	{
				$ar_opt = sel_query_all("vehicle_opt"," WHERE vehicle_idx='{$idx}' AND op_idx='{$row['idx']}'");
				?>
				<tr>
					<th><?=$row['itemname'];?></th>
					<td><?=$ar_opt['op_data'] ? $ar_opt['op_data'] : '없음'?></td>
				</tr>
			<?}?>
			</tbody>
		</table>
	</div>
</div>

<div class="subTitle" style="margin-left:0;margin-top:20px;">
	단기요금
	<input type="hidden" name="price_managed" value="Y" />
</div>
<? include $_SERVER['DOCUMENT_ROOT']."/old/admin/scar/price_table.php"; ?>

<div class="subTitle" style="margin-left:0;margin-top:20px;">
	장기요금
	<input type="hidden" name="price_longterm_managed" value="Y" />
</div>
<? include $_SERVER['DOCUMENT_ROOT']."/old/admin/scar/price_table_longterm.php"; ?>

<div class="subTitle" style="margin-left:0;margin-top:20px;">보험사항</div>
<table class="listTable3">
	<thead>
	<tr>
		<th>구분</th>
		<th>입력</th>
	</tr>
	</thead>
	<tbody>
	<tr>
		<th>대인</th>
		<td>무한</td>

	</tr>
	<tr>
		<th>대물</th>
		<td><?=number_format($item['insu_goods'])?> 원</td>

	</tr>
	<tr>
		<th>자손</th>
		<td><?=number_format($item['insu_self'])?> 원</td>

	</tr>
	<tr>
		<th>연령제한</th>
		<td>
			만 <?=$item['rentage'] ?>세 이상
		</td>
	</tr>
	<tr>
		<th>먼허제한</th>
		<td>
			만 <?=number_format($item['license_limit']) ?> 년 이상
		</td>
	</tr>
	</tbody>
</table>

<div class="topBtn" style='text-align:center;'>
	<a href="javascript:history.back();"><span class="greenBtn">목록</span></a>
</div>
