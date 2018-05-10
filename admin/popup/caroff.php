<?
/**
 * 멤버사 > 차량관리 > 등록차량 일정수정 > 리스트 내 휴차설정(팝업)
 * admin.rentking.co.kr/popup/caroff.php?vehicle_idx=2714
 * 멤버사 휴차 일정 설정 팝업
 */
include $_SERVER['DOCUMENT_ROOT']."/old/inc/base.php";
include $config['incPath']."/connect.php";
include $config['incPath']."/session.php";
include $config['incPath']."/config.php";
include "../adminhead.php";

$vehicle_idx = $_REQUEST['vehicle_idx'];
$mode = $_REQUEST['mode'];

if (!$vehicle_idx) {
	echo "<script type='text/javascript'>alert('잘못된 접근입니다.'); window.close(); </script>";
	exit;
}

if ($mode == 'w') {
	$value['vehicle_idx'] = $vehicle_idx;
	$value['offsdate'] = $sdate;
	$value['offedate'] = $edate;
	$value['wdate'] = date("Y-m-d H:i:s");
	insert("vehicle_off", $value);

	echo "<script type='text/javascript'>alert('휴차기간 설정이 완료되었습니다'); location.replace('../popup/caroff.php?vehicle_idx={$vehicle_idx}'); </script>";
	exit;
}
if ($mode == 'd') {
	$idx = $_REQUEST['idx'];
	mysql_query("DELETE FROM vehicle_off WHERE idx='{$idx}'");
	echo "<script type='text/javascript'>alert('삭제완료'); location.replace('../popup/caroff.php?vehicle_idx={$vehicle_idx}'); </script>";
	exit;
}

$ar_car = sel_query_all("vehicles", " WHERE idx='{$vehicle_idx}'");
$ar_model = sel_query_all("vehicle_models", " WHERE idx='{$ar_car['model_idx']}'");

?>
<script language="javascript" type="text/javascript">
	function offch() {
		if ($("#sdates").val() == '' || $("#edates").val() == '') {
			alert('휴차기간을 입력하세요');
			return false;
		}
		var answer = confirm('휴차설정을 하시겠습니까?');
		if (answer == true) {
			return true;
		}
		else {
			return false;
		}
	}

</script>

<div id="pop_contents">
	<span class="subTitle">* 선택차량</span>
	<table class="detailTable2">
		<tr>
			<th>차종</th>
			<td><?= $ar_model['name']; ?></td>
			<th>차량번호</th>
			<td><?= $ar_car['carnum']; ?></td>
		</tr>
	</table>
	<div class="row-fluid">
		<div class="span6">
			<?
			$year = $_REQUEST['year'];
			$month = $_REQUEST['month'];
			$day = $_REQUEST['day'];

			if (!$year) {
				$year = date("Y");
			}

			if (!$month) {
				$month = date("m");
			}

			if (!$day) {
				$day = date('d');
			}

			$d_day = "{$year}-{$month}-{$day}";

			$now = strtotime($d_day);
			$next = strtotime('+1 months', $now);
			if(date('d', $now) != date('d', $next)) {
				$next = strtotime('-' . date('d', $next) . ' days', $next);
			}

			$last = strtotime('-1 months', $now);
			if(date('d', $now) != date('d', $last)) {
				$last = strtotime('-' . date('d', $last) . ' days', $last);
			}

			$last_mday = date('d', $last);
			$last_mmonth = date("m", $last);
			$last_myear = date("Y", $last);

			$next_mday = date('d', $next);
			$next_mmonth = date("m", $next);
			$next_myear = date("Y", $next);

			$b_month_link = "<a href='../popup/caroff.php?year={$last_myear}&month={$last_mmonth}&day={$last_mday}&vehicle_idx={$vehicle_idx}'>";
			$n_month_link = "<a href='../popup/caroff.php?year={$next_myear}&month={$next_mmonth}&day={$next_mday}&vehicle_idx={$vehicle_idx}'>";
			?>
			<div style="clear:both;padding-top:20px;width:100%;">
				<table class="listTableColor">
					<colgroup>
						<col style="width:14.2%"/>
						<col style="width:14.2%"/>
						<col style="width:14.2%"/>
						<col style="width:14.2%"/>
						<col style="width:14.2%"/>
						<col style="width:14.5%"/>
						<col style="width:14.5%"/>
					</colgroup>
					<tr>
						<td colspan="2" valign="bottom">
							<span style="color:#fe4848">예약일</span> <span style="color:#4f79b9">휴차일</span>
						</td>
						<td colspan="3" style="padding:0px;text-align:center"/>
						<a><?= $b_month_link ?>이전달</a>　<span><?= $year ?>.</span><span><?= $month ?></span>　<a><?= $n_month_link ?>다음달</a>
						<td colspan="2" valign="bottom"/>
					</tr>
					<tr style="height:39px;" align="center">
						<th>일</th>
						<th>월</th>
						<th>화</th>
						<th>수</th>
						<th>목</th>
						<th>금</th>
						<th>토</th>
					</tr>
					<tr>
						<?
						# 달의 첫날의 요일과, 마지막 날을 구한다.
						$firstyoil = date("w", mktime(0, 0, 0, number_format($month), 1, $year));
						$lastday = date("t", mktime(0, 0, 0, number_format($month), 1, $year));


						# 달력 앞부분의 공간을 출력
						for ($space = 0; $space < $firstyoil; $space++) {
							echo("<td style='border:solid 1px #aaa;border-top:solid 1px #555;'>&nbsp;</td>");
						}

						# 날짜를 출력하는 루틴 시작
						for ($date = 1; $date <= $lastday; $date++) {

							# 일요일이 되면 테이블의 다음 라인으로 넘긴다
							if ($date > 1) {
								if (!date("w", mktime(0, 0, 0, number_format($month), $date, $year))) {
									echo("</tr>");
									echo("<tr>");
								}
							}

							if (strlen($date) == 1) {
								$checkday = "0" . $date;
							} else {
								$checkday = $date;
							}

							$this_day = "{$year}-{$month}-{$checkday}";

							$before_day = date('Y-m-d', strtotime($this_day . " -1 day"));
							$after_day = date('Y-m-d', strtotime($this_day . " +1 day"));

							if (number_format($month) == date("n") && $date == date("j")) {
								echo("<td>");
							} else {
								echo("<td>");
							}
							echo "<p>{$checkday}</p>";

							$q = "SELECT * FROM vehicle_off WHERE vehicle_idx='$vehicle_idx' AND offsdate<='{$this_day}' AND offedate>='{$this_day}'";
							$r = mysql_query($q);
							$isit = mysql_num_rows($r);

							if ($isit != 0) {
								echo "<p style='width:100%;height:2px;background-color:#4f79b9;'></p>";
							}

							$q = "SELECT m.* FROM payments m LEFT JOIN reservation mt ON m.reservation_idx = mt.idx WHERE mt.vehicle_idx='$vehicle_idx' AND mt.sdate<='$this_day' AND mt.edate>='$this_day' AND m.dan IN ('1','2','3')";
							$r = mysql_query($q);
							$isit = mysql_num_rows($r);

							if ($isit != 0) {
								echo "<p style='width:100%;height:2px;background-color:#fe4848;'></p>";
							}
							echo("</td>");
						}

						# 달력 뒷부분의 공간을 출력
						$firstyoil = date("w", mktime(0, 0, 0, number_format($month), $lastday, $year));
						for ($space = $firstyoil; $space < 6; $space++) {
							echo("<td style='border:solid 1px #aaa;'>&nbsp;</td>");
						}
						?>
					</tr>
				</table>
			</div>

		</div>
		<div class="span6">
			<form name="regiform" id="regiform" method="post" onsubmit="return offch();">
				<input type='hidden' name='mode' value='w'>
				<input type='hidden' name='vehicle_idx' value='<?= $vehicle_idx; ?>'>
				<span class="subTitle">* 휴차등록</span>
				<table class="detailTable2">
					<tbody>
					<tr>
						<th>휴차기간</th>
						<td>
							<input type='text' name='sdate' id='sdates' class="datePicker" data-parent="body" readonly> ~ <input type='text' name='edate' id='edates' class="datePicker" data-parent="body" readonly>
						</td>
					</tr>
					</tbody>
				</table>
				<div class="btn_wrap btn_center btn_bottom">
					<span class="greenBtn btn_submit" data-form="#regiform"><a href="javascript:">설정하기</a></span>
				</div>
			</form>
			<span class="subTitle">* 설정된휴차기간</span>
			<table class="listTableColor">
				<thead>
				<tr>
					<th>휴차시작일</th>
					<th>휴차종료일</th>
					<th></th>
				</tr>
				</thead>
				<tbody>
				<?
				$q = "SELECT * FROM vehicle_off WHERE vehicle_idx='{$vehicle_idx}' ORDER BY idx ASC";
				$r = mysql_query($q);
				while ($row = mysql_fetch_array($r)) {
					?>
					<tr>
						<td><?= $row['offsdate']; ?></td>
						<td><?= $row['offedate']; ?></td>
						<td><span class="blackBtn"
								  onclick="delok('삭제하시겠습니까?','../popup/caroff.php?code=<?= $code; ?>&mode=d&idx=<?= $row['idx']; ?>&vehicle_idx=<?= $vehicle_idx; ?>');">삭제</span>
						</td>
					</tr>
					<?
				}
				?>
				</tbody>
			</table>
		</div>
	</div>
	<div style="text-align:right;">
		<button class="greenBtn" id="popupClose">닫기</button>
	</div>
</div><!-- // .content -->
<script type="text/javascript">
	$(function() {
		$(document).on("click", "#popupClose", function(e){
			e.preventDefault();

			window.close();
		});
	})
</script>