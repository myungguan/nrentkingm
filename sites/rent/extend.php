<?
include $_SERVER['DOCUMENT_ROOT']."/old/inc/base.php";
include $config['incPath']."/connect.php";
include $config['incPath']."/session.php";
include $config['incPath']."/config_site.php";

$idx = $_REQUEST['idx'];

$query = "SELECT
		r.s1time1, r.s1time2,
		r.s2time1, r.s2time2,
		r.d1time1, r.d1time2,
		r.d2time1, r.d2time2,
		mt.edate,
		mt.vehicle_idx,
		mt.addr, mt.raddr
	FROM payments m
		LEFT JOIN reservation mt ON m.reservation_idx = mt.idx
		LEFT JOIN vehicles v ON mt.vehicle_idx = v.idx
		LEFT JOIN rentshop r ON v.rentshop_idx = r.idx
	WHERE
		m.idx = $idx
";
$order = mysql_fetch_assoc(mysql_query($query));
?>
<form action="/rent/reservation.php" class="extend-form">
    <input type="hidden" name="payment_idx" value="<?=$idx?>"/>
	<input type="hidden" name="vehicle_idx" value="<?=$order['vehicle_idx']?>" />
	<input type="hidden" name="insu" />
	<input type="hidden" name="addr" value="<?=$order['addr'] ?>" />
	<input type="hidden" name="raddr" value="<?=$order['raddr'] ?>" />
	<input type="hidden" name="extend" value="Y" />
	<div class="rentshop">
		<p style="margin:0;"><span class="text-bold title">지점영업시간:</span> <span class="weekday"><?=$order['s1time1'] ?>~<?=$order['s1time2'] ?>(평일),</span> <span class="weekend"><?=$order['s2time1'] ?>~<?=$order['s2time2'] ?>(휴일)</span></p>
		<p style="margin:0;"><span class="text-bold title">지점배달시간:</span> <span class="weekday"><?=$order['d1time1'] ?>~<?=$order['d1time2'] ?>(평일),</span> <span class="weekend"><?=$order['d2time1'] ?>~<?=$order['d2time2'] ?>(휴일)</span></p>
	</div>
	<div class="date mt-15">
		<span class="title">반납일시</span>
		<span class="input-wrap">
			<input type="text" name="sdate" id="sdateExtend" class="dateTimePicker" placeholder="픽업일시 / 시간" value="<?=substr($order['edate'], 0, 16) ?>" readonly
				data-end="#edateExtend"
				data-min-date="<?=substr($order['edate'], 0, 16) ?>"
				style="position:absolute;left:-1000px;top:0;">
			<input type="text" name="edate" id="edateExtend" class="dateTimePicker" placeholder="반납일시 / 시간" value="<?=substr($order['edate'], 0, 16) ?>" readonly
				data-template="1w,1M,2M,3M,6M,1y"
				data-start="#sdateExtend"
				data-min-date-origin="#sdateExtend"
				data-min-date="0h"
				data-max-date-origin="#sdateExtend"
				data-max-date="1y">
		</span><button type="button" class="btn-point search">조회하기</button>
	</div>
	<div class="extend-result mt-15"></div>
</form>

<script type="text/javascript">
	$(function() {
		var $extendForm = $('.extend-form:not(.init)');
		var $dateTimePicker = $extendForm.find('input.dateTimePicker');
		if(checkDevice().isMobile) {
			$dateTimePicker.data('parent', 'body');
		}
		$dateTimePicker.trigger('init');
		$extendForm.find('button.search').on('click', function(e) {
			e.preventDefault();

			var $extendResult = $extendForm.find('.extend-result');

			$extendResult.html('');
			$.get('/rent/extend_search.php', $extendForm.serializeArray(), function(html) {
				$extendResult.html(html);
				$extendForm.parents('.pop-wrap').trigger('reposition');
			})
		});

		$extendForm.on('click', '.extend-item a.reservation', function(e) {
			e.preventDefault();
			var $reservation = $(this);
			var insu = $reservation.data('insu');

			if(typeof insu !== 'undefined') {
				$extendForm.find('input[name="insu"]').val(insu);
			} else {
				$extendForm.find('input[name="insu"]').val('');
			}

			$.getJSON($extendForm.attr('action'), $extendForm.serializeArray())
				.done(function(data) {
					if(data['num'] < 1) {
						alert(data['err']);
						return;
					}

					location.href = '/rent/payment.php?idx=' + data.idx;
				})
				.complete(function() {
					$extendForm.data('running', false);
				});
		});

		$extendForm.addClass('init');

	});


</script>


