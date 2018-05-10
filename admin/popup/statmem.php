<?
/**
 * 어드민 > 고객관리 > 고객 > 회원가입통계(팝업)
 * admin.rentking.co.kr/popup/statmem.php
 */
include $_SERVER['DOCUMENT_ROOT']."/old/inc/base.php";
include $config['incPath']."/connect.php";
include $config['incPath']."/session.php";
include $config['incPath']."/config.php";
include "../adminhead.php";

if (!$sdate) {
	$sdate = date("Y-m-d", strtotime("-7days"));
}
if (!$edate) {
	$edate = date("Y-m-d");
}
?>
<div id="pop_contents">
	<form id="search" name="search" action="../popup/statmem.php?code=<?= $code; ?>" method="post">
		<table class="detailTable2">
			<tbody>
			<tr>
				<th>가입일</th>
				<td>
					<input type='text' name='sdate' id='sdates' size='10' value='<?= $sdate; ?>' class="datePicker" data-parent="body" readonly> ~ <input type='text' name='edate' id='edates' size='10' value='<?= $edate; ?>' class="datePicker" data-parent="body" readonly>
				</td>
			</tr>
			</tbody>
		</table>
		<div class="btn_wrap btn_center btn_bottom">
			<span class="greenBtn btn_submit" data-form="#search"><a href="javascript:">검색하기</a></span>
		</div>
		<input type="submit" style="display:none;">
	</form>

	<div style='margin-bottom:20px;'>
		<div class="h3_wrap">
			<h3>- 기간별회원가입현황</h3>
		</div>
		<div style='background-color:#FFFFFF;'>
			<div id="chart1" style="width:100%;height:300px;"></div>
		</div>
	</div>
	<div class="list_wrap order_list">
		<table class="listTableColor">
			<thead>
			<tr>
				<th>날짜</th>
				<th>PC가입</th>
				<th>모바일</th>
				<th>합계</th>
			</tr>
			</thead>
			<tbody>
			<?
			$categories = "";
			$data1_1 = "";
			$data1_2 = "";
			$data2_1 = "";
			$data2_2 = "";
			$cou = 0;

			$ar_sdate = explode("-", $sdate);
			$ar_edate = explode("-", $edate);
			$sdate_t = mktime(0, 0, 1, $ar_sdate[1], $ar_sdate[2], $ar_sdate[0]);
			$edate_t = mktime(0, 0, 1, $ar_edate[1], $ar_edate[2], $ar_edate[0]);

			$nums = ($edate_t - $sdate_t) / 86400;

			for ($i = 0; $i <= $nums; $i++) {
				$show_date = date("Y-m-d", ($sdate_t + 86400 * $i));
				$co = "";
				if (!($cou % 2)) $co = "gray";

				$q = "SELECT * FROM member WHERE memgrade='100' AND signdate BETWEEN '{$show_date} 00:00:00' AND '{$show_date} 23:59:59'";
				$r = mysql_query($q);
				while ($row = mysql_fetch_array($r)) {
					$ar_member[1][$show_date] = 0;
					$ar_member[2][$show_date] = $ar_member[2][$show_date] + 1;
					$ar_member['total'][$show_date] = $ar_member['total'][$show_date] + 1;
				}
				?>
				<tr class="<?= $co; ?>">
					<td class="first"><?= $show_date; ?></td>
					<td><?= number_format($ar_member[1][$show_date]); ?>명</td>
					<td><?= number_format($ar_member[2][$show_date]); ?>명</td>
					<td><?= number_format($ar_member['total'][$show_date]); ?>명</td>
				</tr>

				<?
				//그래프데이터가공영역
				if ($i != 0) {
					$categories = $categories . ",";
					$data1_1 = $data1_1 . ",";
					$data1_2 = $data1_2 . ",";
					$data2_1 = $data2_1 . ",";
				}
				if (!$ar_member[1][$show_date]) {
					$ar_member[1][$show_date] = 0;
				}
				if (!$ar_member[2][$show_date]) {
					$ar_member[2][$show_date] = 0;
				}
				if (!$ar_member['total'][$show_date]) {
					$ar_member['total'][$show_date] = 0;
				}

				$categories = $categories . "'{$show_date}'";
				$data1_1 = $data1_1 . $ar_member[1][$show_date];
				$data1_2 = $data1_2 . $ar_member[2][$show_date];
				$data2_1 = $data2_1 . $ar_member['total'][$show_date];
				//그래프데이터가공영역
				$cou++;
			}
			?>
			<tr>
				<td class="first">합계</td>
				<td><?= number_format(array_sum($ar_member[1])); ?>명</td>
				<td><?= number_format(array_sum($ar_member[2])); ?>명</td>
				<td><?= number_format(array_sum($ar_member['total'])); ?>명</td>
			</tr>
			</tbody>
		</table>
	</div>
</div><!-- // .content -->
<script>
	$(function () {
		$('#chart1').highcharts({
			title: {
				text: ''
			},
			xAxis: {
				categories: [<?=$categories;?>]
			},
			yAxis: {
				title: {
					text: '가입수'
				},
				plotLines: [{
					value: 0,
					width: 1,
					color: '#808080'
				}]
			},
			tooltip: {
				valueSuffix: '명'
			},
			legend: {
				layout: 'vertical',
				align: 'right',
				verticalAlign: 'middle',
				borderWidth: 0
			},
			series: [{
				name: '가입',
				data: [<?=$data1_1;?>]
			}, {
				name: '가입',
				data: [<?=$data1_2;?>]
			}]
		});
	});
</script>