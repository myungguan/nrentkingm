<?
include $_SERVER['DOCUMENT_ROOT']."/old/inc/base.php";
include $config['incPath']."/connect.php";
include $config['incPath']."/session.php";
include $config['incPath']."/config_site.php";

$sdate = $_REQUEST['sdate'];
$edate = $_REQUEST['edate'];
$vehicle_idx = $_REQUEST['vehicle_idx'];
$insu = $_REQUEST['insu'];
$addr = $_REQUEST['addr'];
$raddr = $_REQUEST['raddr'];
$extend = $_REQUEST['extend'];
$payment_idx = $_REQUEST['payment_idx'];

$retype = 1;
//기존의 장기고객일 경우 연장시 장기로 검색되도록 함
if(isset($payment_idx)){
    $query = "SELECT
			r.retype
		FROM payments p
			LEFT JOIN reservation r ON p.reservation_idx = r.idx
		WHERE
			p.idx = '$payment_idx'
			AND p.member_idx = '$g_memidx'";
    $r = mysql_query($query);
    $payments = mysql_fetch_assoc($r);
    $retype = $payments['retype'];
}

$hours = calcHours($sdate, $edate);
if($hours['rent_hour'] <= 0) {
    echo '<span class="text-error text-bold">연장은 최소 30분부터 가능합니다.</span>';
    exit;
}
$ddata1 = floor($hours['rent_hour'] / 24);
$ddata2 = fmod($hours['rent_hour'], 24);

$days = calcDays($sdate, $edate);
if($retype == 2 || $days['months'] > 0) {
	$retype = 2;
	$ddata1 = $days['months'];
	$ddata2 = $days['days'];
}

$rtype = 1;
if(strcmp($addr, $raddr) != 0) {
	$rtype = 2;
}

if($extend != 'Y')
	$extend = 'N';

$extend_order = NULL;
if($extend == 'Y') {
	$query = "SELECT
			m.*,
			r.s1time1, r.s1time2,
			r.s2time1, r.s2time2,
			r.d1time1, r.d1time2,
			r.d2time2, r.d2time2
		FROM payments m
			LEFT JOIN reservation mt ON m.reservation_idx = mt.idx
			LEFT JOIN vehicles v ON mt.vehicle_idx = v.idx
			LEFT JOIN rentshop r ON v.rentshop_idx = r.idx
		WHERE
			mt.edate = '{$sdate}'
			AND mt.vehicle_idx = $vehicle_idx
			AND m.member_idx = $g_memidx
		ORDER BY m.idx DESC
		limit 1";
	$r = mysql_query($query);
	if(mysql_num_rows($r) != 1) {
		echo '<span class="text-error text-bold">잘못된 예약정보 입니다.</span>';
		exit;
	}

	$extend_order = mysql_fetch_assoc($r);
}

$etime = substr($edate, 11, 5);
$day = date("N",getTime($edate));
if(
	($day <= 5
		&& (
			($extend_order['ptype'] == 1 && ($etime < $extend_order['d1time1'] || $etime > $extend_order['d1time2']))
			|| ($extend_order['ptype'] == 2 && ($etime < $extend_order['s1time1'] || $etime > $extend_order['s1time2']))
		)
	)
	|| ($day > 5
		&& (
			($extend_order['ptype'] == 1 && ($etime < $extend_order['d2time1'] || $etime > $extend_order['d2time2']))
			|| ($extend_order['ptype'] == 2 && ($etime < $extend_order['s2time1'] || $etime > $extend_order['s2time2']))
		)
	)
) {
	echo '<span class="text-error text-bold">지점 영업/배달 시간을 벗어나 연장할 수 없습니다.</span>';
	exit;
}

$search = getSearchInfo($memberType, $sdate, $edate, $retype, $addr, $raddr, null, $vehicle_idx, $extend_order);

$q = str_replace('[FIELD]', 't.*', $search['query']);
$result = mysql_query($q);

if(mysql_num_rows($result) < 1) {
	echo '<span class="text-error text-bold">연장할 수 없습니다.<br />고객센터(1661-3313)에 문의해주세요.</span>';
	exit;
}

$car = mysql_fetch_assoc($result);

?>

<div class="extend-item">
	<div class="car"><span class="name"><?=$car['modelname']?></span> <span class="fuel">(<?=$car['fuel_sname']?>)</span></div>
	<div class="detail">
		<div class="insurance">
			<ul>
				<li>대인 <span class="value"><?=$car['insu_per']?></span></li>
				<li>대물 <span class="value"><?=number_format($car['insu_goods'])?>원</span></li>
				<li>자손 <span class="value"><?=number_format($car['insu_self'])?>원</span></li>
				<?if($car['pricetype'] == 2) {?>
					<li>자차포함 <span class="value">고객부담금 <?=number_format($car['price_longterm_insu_exem'])?>원/건</span></li>
				<?}?>
			</ul>
		</div>
	</div>
	<ul class="button-area">
		<? if($retype == 1) { ?>
			<?if($car['pricetype'] == 1) {?>
				<?if($car['price_insu0_check'] == 'Y') {?>
					<li>
						<a href="#<?=$car['idx']?>" class="reservation">
							<div class="title">연장하기</div>
							<div class="description">
								<span class="con">자차 미포함</span>
								<span class="price"><span><?=number_format($car['charge'])?></span>원</span>
							</div>
						</a>
					</li>
				<?}?>
				<?if($car['price_insu1_check'] == 'Y') {?>
					<li>
						<a href="#<?=$car['idx']?>" class="reservation" data-insu="1">
							<div class="title">연장하기</div>
							<div class="description">
								<span class="con">자차포함(고객부담금: <?=number_format($car['price_insu1_exem'])?>원/건)</span>
								<span class="price"><span><?=number_format($car['charge']+$car['price_insu1'])?></span>원</span>
							</div>
						</a>
					</li>
				<?}?>
				<?if($car['price_insu2_check'] == 'Y') {?>
					<li>
						<a href="#<?=$car['idx']?>" class="reservation" data-insu="2">
							<div class="title">연장하기</div>
							<div class="description">
								<span class="con">자차포함(고객부담금: <?=number_format($car['price_insu2_exem'])?>원/건)</span>
								<span class="price"><span><?=number_format($car['charge']+$car['price_insu2'])?></span>원</span>
							</div>
						</a>
					</li>
				<?}?>
				<?if($car['price_insu3_check'] == 'Y') {?>
					<li>
						<a href="#<?=$car['idx']?>" class="reservation" data-insu="3">
							<div class="title">연장하기</div>
							<div class="description">
								<span class="con">자차포함(고객부담금: <?=number_format($car['price_insu3_exem'])?>원/건)</span>
								<span class="price"><span><?=number_format($car['charge']+$car['price_insu3'])?></span>원</span>
							</div>
						</a>
					</li>
				<?}?>
			<?} else {?>
				<li>
					<a href="#<?=$car['idx']?>" class="reservation">
						<div class="title">연장하기</div>
						<div class="description">
							<span class="con">자차포함(고객부담금: <?=number_format($car['price_longterm_insu_exem'])?>원/건)</span>
							<span class="price"><span><?=number_format($car['charge'])?></span>원</span>
						</div>
					</a>
				</li>
			<?}?>
		<? } else { ?>
			<li>
				<a href="#<?=$car['idx']?>" class="reservation">
					<div class="title">연장하기</div>
					<div class="description">
						<span class="con">월대여료</span>
						<span class="price"><span><?=number_format($car['moncharge'])?></span>원 X <?=$ddata1 ?>회</span>
					</div>
					<div class="description">
						<span class="con">마지막 월 대여료</span>
						<span class="price"><span><?=number_format($car['leftcharge'])?></span>원</span>
					</div>
					<div class="description">
						<span class="con">전체 대여료</span>
						<span class="price"><span><?=number_format($car['charge'])?></span>원</span>
					</div>
				</a>
			</li>
		<? } ?>
	</ul>
</div>

