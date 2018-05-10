<?
/**
 * 어드민 > 예약관리 > 리스트 > 상세조회팝업
 * admin.rentking.co.kr/popup/orderview.php?idx=510
 * 회원 정보 및 예약 관련 팝업
 */
include $_SERVER['DOCUMENT_ROOT']."/old/inc/base.php";
include $config['incPath']."/connect.php";
include $config['incPath']."/session.php";
include $config['incPath']."/config.php";

$idx = $_REQUEST['idx'];
$mode = $_REQUEST['mode'];
$command = $_REQUEST['command'];

if (!$idx) {
	echo "<script>alert('잘못된 접근입니다.'); window.close(); </script>";
	exit;
}

if($mode == 'ajax') {
	$data = array('result' => false);
	if($command == 'disapprovalCancel') {
		$query = "UPDATE payments SET dan = 1, pdan='' WHERE idx = $idx";
		if(mysql_query($query))
			$data['result'] = true;

		$query = "INSERT INTO comments(type, type_idx, public, comment, member_idx, dt_create, dt_update) VALUES 
			('payment', $idx, 'Y', '취소반려', $g_memidx, NOW(), NOW())";
		//TODO: 알림톡 보내기
	}
	
	echo json_encode($data);
	exit;
}

include "../adminhead.php";

$query = "SELECT
		CASE WHEN t.payment IS NULL THEN 0 ELSE t.payment END - CASE WHEN t.payment_return IS NULL THEN 0 ELSE t.payment_return END total_payment,
		t.*
	FROM (
		SELECT
			(SELECT SUM(account) FROM payment_accounts WHERE payment_idx = m.idx AND tbtype = 'I') payment,
			(SELECT SUM(account) FROM payment_accounts WHERE payment_idx = m.idx AND tbtype = 'O') payment_return,
			m.*,
			mt.vehicle_idx,
			mt.retype,
			mt.pricetype,
			mt.ddata1,
			mt.ddata2,
			mt.rtype,
			mt.sdate,
			mt.edate,
			mt.account,
			mt.account1,
			mt.account2,
			mt.preaccount,
			mt.insu,
			mt.insuac,
			mt.insu_exem,
			mt.insu_limit,
			mt.distance_limit,
			mt.distance_additional_price,
			mt.addr,
			mt.raddr,
			mt.extend extendyn,
			m_extend.idx extendmarket,
			lv.user_agent,
			CASE 
				WHEN m.promotion IS NOT NULL THEN SUBSTRING_INDEX(m.promotion, '||', 1)
				ELSE ''
			END promotion_name,
			c.name coupon_name,
			(SELECT
					 GROUP_CONCAT(CONCAT(op_name, ': ', op_data) SEPARATOR ', ')
				 FROM
					 vehicle_opt
				 WHERE
					 vehicle_idx = mt.vehicle_idx
					 AND op_data <> '') car_opt
		FROM payments m
			LEFT JOIN reservation mt ON m.reservation_idx = mt.idx
			LEFT JOIN payments m_extend ON m_extend.extend_payment_idx = m.idx
			LEFT JOIN member_coupons cm ON cm.payment_idx = m.idx
			LEFT JOIN coupons c ON cm.coupon_idx = c.idx
			LEFT JOIN log_visit lv ON m.session_id = lv.session_id
		WHERE
			m.idx={$idx}
		) t";
$ar_data = mysql_fetch_assoc(mysql_query($query));
$ar_mem = sel_query_all("member", " WHERE idx='{$ar_data['member_idx']}'");
$query = "
	SELECT
		v.*,
		vs.name modelname,
		c.sname fuel,
		vp.price_insu1_exem,
		vp.price_insu2_exem,
		vp.price_insu3_exem,
		vpl.price_longterm_insu_exem
	FROM vehicles v
		LEFT JOIN vehicle_price vp ON vp.idx = v.price_idx
		LEFT JOIN vehicle_price_longterm vpl ON vpl.idx = v.price_longterm_idx
		LEFT JOIN vehicle_models vs ON v.model_idx = vs.idx
		LEFT JOIN codes c ON v.fuel_idx = c.idx
	WHERE
		v.idx = {$ar_data['vehicle_idx']}
	
";
$ar_car = mysql_fetch_assoc(mysql_query($query));

$query = "
	SELECT
		r.*
	FROM
		rentshop r 
	WHERE
		r.idx = {$ar_car['rentshop_idx']}
";
$ar_com = mysql_fetch_assoc(mysql_query($query));
?>
</head>
<body>
<div id="pop_contents">
	<form id="ajaxForm" action="/popup/orderview.php">
		<input type="hidden" name="idx" value="<?=$idx ?>" />
		<input type="hidden" name="mode" value="ajax" />
		<input type="hidden" name="command" value="" />
	</form>
	<h3 style="margin:0 0 5px;">* 회원(운전자)정보</h3>
	<table class="detailTable2">
		<tr>
			<th>성명</th>
			<td>
				<?if(in_array($_SESSION['admin_grade'], [9])) {?>
					<a href="#<?=$ar_mem['idx']?>" onclick="MM_openBrWindow('/popup/memview.php?idx=<?=$ar_mem['idx'];?>','member<?=$ar_mem['idx'];?>','scrollbars=yes,width=1150,height=700,top=0,left=0');return false;""><?= $ar_mem['name']; ?> (<?=$ar_mem['sex'] == 'M' ? '남' : '여' ?>)</a>
				<?}else{?>
					<?= $ar_mem['name']; ?> (<?=$ar_mem['sex'] == 'M' ? '남' : '여' ?>)
				<?}?>

			</td>
			<th>아이디</th>
			<td><?= $ar_mem['id']; ?></td>
			<th>휴대폰번호</th>
			<td><?= phone_number_format($ar_mem['cp']) ?></td>
			<th>생년월일</th>
			<td><?= $ar_mem['birth']; ?></td>
		</tr>
		<?
		$ar_lince = NULL;
		if($ar_mem['driver_license_idx']) {
			$ar_lince = sel_query_all("driver_license", " WHERE idx='{$ar_mem['driver_license_idx']}'");
		}
		?>
        <tr>
            <th>면허종류</th>
            <td><?= $ar_lince ? $ar_lin[$ar_lince['kinds']] : ''; ?></td>
            <th>면허번호</th>
            <td><?= $ar_lince ? $ar_lince['nums'] : ''; ?></td>
            <th>적성검사 만료일</th>
            <td><?= $ar_lince ? $ar_lince['date1'] : ''; ?></td>
            <th>발급일</th>
            <td><?= $ar_lince ? $ar_lince['date2'] : ''; ?></td>
        </tr>
	</table>
	<? if ($ar_data['driver_license_idx']) {
		$ar_lince2 = sel_query_all("driver_license", " WHERE idx='{$ar_data['driver_license_idx']}'");
		?>
		<h3 style="margin:20px 0 5px;">* 제2운전자정보</h3>
		<table class="detailTable2" style="margin-bottom:10px;">
			<tr>
				<th>성명</th>
				<td><?= $ar_lince2['name']; ?></td>
				<th>연락처</th>
				<td colspan="5"><?= phone_number_format($ar_lince2['cp']) ?></td>
				<!--
				<th>생년월일</th>
				<td colspan="3"><?= $ar_lince2['birth']; ?></td>
				-->
			</tr>
			<tr>
				<th>면허종류</th>
				<td><?= $ar_lin[$ar_lince2['kinds']]; ?></td>
				<th>면허번호</th>
				<td><?= $ar_lince2['nums']; ?></td>
				<th>적성검사 만료일</th>
				<td><?= $ar_lince2['date1']; ?></td>
				<th>발급일</th>
				<td><?= $ar_lince2['date2']; ?></td>
			</tr>
		</table>
	<?}?>
	<div style="margin:20px 0 5px;">
		<h3 style="margin:0;display:inline-block;">* 대여정보</h3>
		<div class="btn_wrap btn_top btn_right" style="float:right;margin:0;">
			<? if($ar_data['extend_payment_idx']) {?>
				<a href="#<?=$ar_data['extend_payment_idx']?>" onclick="MM_openBrWindow('/popup/orderview.php?idx=<?=$ar_data['extend_payment_idx']?>','order<?=$ar_data['extend_payment_idx']?>','scrollbars=yes,width=1150,height=700,top=0,left=0');return false;">연장예약이 있습니다.</a>
			<?} else {?>
				<? if ($ar_data['dan'] == 1 && $_SESSION['member_grade'] == '10') { ?>
					취소요청은 렌트킹과 유선통화로 부탁드립니다.
				<? } ?>
				<? if ($ar_data['dan'] == 2 && ($_SESSION['member_grade'] == '10') ) { ?>
					<span class="btn_white_xs btn_navy btn_top" style="margin-bottom:0;"><a href="javascript:MM_openBrWindow('/popup/returnok.php?idx=<?= $idx; ?>','add','width=600,height=600,scrollbars=yes');">반납처리</a></span>
				<? } ?>
				<? if ($ar_data['dan'] == 4 && in_array($_SESSION['admin_grade'], [9]) ) { ?>
					<span class="btn_white_xs btn_navy btn_top" style="margin-bottom:0;"><a href="#disapprovalCancel" class="disapprovalCancel">취소반려</a></span>
				<? } ?>
				<? if ($ar_data['dan'] != 5 && in_array($_SESSION['admin_grade'], [9])) { ?>
					<span class="btn_white_xs btn_navy btn_top" style="margin-bottom:0;"><a href="javascript:MM_openBrWindow('/popup/cancelok.php?idx=<?= $idx; ?>','add','width=600,height=600,scrollbars=yes');">예약취소 및 환불</a></span>
				<? } ?>

			<?}?>
		</div>
	</div>
	<table class="detailTable2">
		<tr>
			<th>예약번호</th>
			<td><?= $ar_data['idx']; ?> -
				<?
				if(strpos($ar_data['user_agent'], 'rentking/') !== FALSE) {
					echo "렌트킹(APP)";
				} else {
					if ($ar_data['pid'] == 'W') {
						echo "렌트킹(WEB)";
					} else if($ar_data['pid'] == 'M') {
						echo "렌트킹(MOBILE)";
					}
				}
				?>
			</td>
			<th>예약일</th>
			<td><?= $ar_data['dt_create']; ?></td>
			<th>진행상태</th>
			<td>
				<? get_marketdan($ar_data['dan'], $ar_data['extend_payment_idx'], $ar_data['extendyn']); ?>
				<?=$ar_data['dan'] == 3 ? $ar_data['dt_return'] : ''?>
				<?=$ar_data['dan'] == 4 ? $ar_data['dt_cancelr'] : ''?>
				<?=$ar_data['dan'] == 5 ? $ar_data['dt_cancel'] : ''?>
			</td>
		</tr>
		<tr>
			<th>대여방법</th>
			<td>
				<?=$ar_data['ptype'] == '2' ? '지점방문' : '배달' ?>
				<?if($ar_data['extendyn'] == 'Y') {?>
					(<a href="#<?=$ar_data['extendmarket'] ?>" onclick="MM_openBrWindow('/popup/orderview.php?idx=<?=$ar_data['extendmarket'] ?>','order<?=$ar_data['extendmarket'] ?>','scrollbars=yes,width=1150,height=700,top=0,left=0');return false;">연장</a>)
				<?}?>
			</td>
			<th>픽업일시</th>
			<td><?= $ar_data['sdate']; ?></td>
			<th>반납일시</th>
			<td><?= $ar_data['edate'] ?></td>
		</tr>
		<tr>
			<th>대여지점</th>
			<td class="changeCar rentshop">
				<?= $ar_com['name']; ?>(<?= $ar_com['affiliate']; ?>)<br />
				제휴담당자: <?=phone_number_format($ar_com['dcp2']) ?>
			</td>
			<th>대여구분</th>
			<td><? if ($ar_data['retype'] == '1') {
					echo "단기대여";
				} else {
					echo "장기대여";
				} ?></td>
			<th>대여일수</th>
			<td><? if ($ar_data['retype'] == '1') {
					echo $ar_data['ddata1'] . "일 " . $ar_data['ddata2'] . "시간";
				} else {
					echo $ar_data['ddata1'] . "개월 " . $ar_data['ddata2'] . "일";
				} ?></td>
		</tr>
		<?if ($ar_data['ptype'] == '1') {?>
			<tr>
				<th>픽업위치</th>
				<td colspan='5'><?= $ar_data['addr']; ?></td>
			</tr>
			<tr>
				<th>반납위치</th>
				<td colspan='5'><? if ($ar_data['rtype'] == '1') {
						echo "픽업위치와동일";
					} else {
						echo $ar_data['raddr'];
					} ?></td>
			</tr>
		<? } else {?>
			<tr>
				<th>검색위치</th>
				<td colspan='5'><?= $ar_data['addr']; ?></td>
			</tr>
		<?} ?>

		<tr>
			<th>대여차종</th>
			<td class="uiChangeCar" data-order="<?=$ar_data['reservation_idx'] ?>" data-sdate="<?= $ar_data['sdate']; ?>" data-edate="<?= $ar_data['edate']; ?>" <?if (!$_SESSION['admin_grade']) {?>data-rentshop="<?=$_SESSION['renthsop_idx']?>"<?}?>>
				<? if ($ar_data['dan'] <= 2 && (in_array($_SESSION['admin_grade'], [9]) || $_SESSION['member_grade'] == '10')) { ?>
					<a href="#changeCar" class="changeCar modelname"><?= $ar_car['modelname'] ?> (<?=$ar_car['fuel'] ?>)</a>
					<div class="searchCar">
						<input type="text" name="carnum" placeholder="차량번호" maxlength="7">
						<ul class="autocomplete"></ul>
					</div>
				<?} else {?>
					<?= $ar_car['modelname'] ?> (<?=$ar_car['fuel'] ?>)
				<?}?>

			</td>
			<th>차량번호</th>
			<td class="changeCar carnum">
				<?if(in_array($_SESSION['admin_grade'], [9])) {?>
					<a href="#<?= $ar_car['carnum']; ?>" class="changeCar carnum" onclick="window.open('/car.php?code=rentshoplist_s1&searchKeyword=<?= $ar_car['carnum']; ?>', '_blank');return false;"><?= $ar_car['carnum']; ?></a>
				<?} else {?>
					<?if($_SESSION['member_grade'] == '10') {?>
					<a href="#<?= $ar_car['carnum']; ?>" class="changeCar carnum" onclick="window.open('/scar.php?code=list&&searchKeyword=<?= $ar_car['carnum']; ?>', '_blank');return false;"><?= $ar_car['carnum']; ?></a>
					<?} else {?>
						<?= $ar_car['carnum']; ?>
					<?}?>
				<?}?>
			</td>
			<th>출고일</th>
			<td class="changeCar outdate"><?= $ar_car['outdate']; ?></td>
		</tr>
		<tr>
			<th>차량대여금액</th>
			<td>
				<?
				if ($ar_data['retype'] == '1') {
					echo number_format($ar_data['account']) . "원";
				} else {
					echo number_format($ar_data['account1']) . "원/월";
					if ($ar_data['ddata2'] != 0) {
						echo " , " . number_format($ar_data['account2']) . "원/" . $ar_data['ddata2'] . "일";
					}
				}
				?>
			</td>
			<th>자차보험</th>
			<td>
				<? if ($ar_data['pricetype'] == '2' || $ar_data['insu'] >= 1) { ?>
					<?if($ar_data['pricetype'] == 1 && $ar_data['insu'] > 0) {?>
						<?= number_format($ar_data['insuac']) ?>원
					<?}?>
					(고객부담금: <?=number_format($ar_data['insu_exem'])?>원/건)
				<?} else {?>
					자차미포함
				<?}?>
				<? if($ar_data['insu_limit']) {?>
					<br />자차보상한도: <?=number_format($ar_data['insu_limit'])?>
				<?}?>
			</td>
			<th>배달료</th>
			<td><?= number_format($ar_data['delac']) ?>원</td>
		</tr>
		<tr>
			<th>총금액</th>
			<td><?= number_format($ar_data['account'] + $ar_data['delac'] + $ar_data['insuac']) ?>원
				<?
				if ($ar_data['retype'] == '2') {
					echo "[보증금 : " . number_format($ar_data['preaccount']) . "원]";
				}
				?>
			</td>
			<th>할인액</th>
			<td>
				<?if($ar_data['discount'] > 0) {?>
					<?= number_format($ar_data['discount']) ?>원
					(<?= $ar_data['promotion'] ? promotion($ar_data['promotion_name']) : $ar_data['coupon_name'] ?>)
				<?}?>

			</td>
			<th>포인트사용액</th>
			<td>
				<?if($ar_data['usepoint'] > 0) {?>
					<?= number_format($ar_data['usepoint']) ?>원
				<?}?>
			</td>
		</tr>
		<tr>
			<?
			if ($ar_data['addaccount'] != 0) {
				?>
				<th>실결제</th>
				<td style="font-weight:bold;color:red"><?= number_format($ar_data['payment_account'] - $ar_data['discount']) ?>
					원
				</td>
				<th>커미션</th>
				<td colspan="5" style="font-weight:bold;color:red"><?= number_format($ar_data['addaccount']) ?>원</td>
				<?
			} else {
				?>
				<th>총결제금액</th>
				<td colspan="7" style="font-weight:bold;color:red"><?= number_format($ar_data['total_payment']) ?>원</td>
				<?
			}
			?>
		</tr>
		<tr>
			<th>보험정보</th>
			<td colspan='7' class="changeCar insu">
				대인: <?= $ar_car['insu_per']; ?> / 대물: <?= $ar_car['insu_goods']; ?> / 자손: <?= $ar_car['insu_self']; ?>
			</td>
		</tr>
		<tr>
			<th>차량옵션</th>
			<td colspan='7' class="changeCar opt"><?= $ar_data['car_opt'] ?></td>
		</tr>
		<?if($ar_data['distance_limit']) {?>
			<tr>
				<th>주행거리제한</th>
				<td colspan="7"><?=number_format($ar_data['distance_limit'])?>&#13214;/월 (추가: <?=number_format($ar_data['distance_additional_price'])?>원/&#13214;)</td>
			</tr>
		<?}?>
	</table>
	<? if (in_array($_SESSION['admin_grade'], [9])) { ?>
		<h3 style="margin:20px 0 5px;">* 결제정보</h3>
		<table class="listTableColor">
			<thead>
			<tr>
				<th>NO</th>
				<th>구분</th>
				<th>결제수단</th>
				<th>금액</th>
				<th>날짜</th>
				<th>정산예정일</th>
				<th>정산일시</th>
			</tr>
			</thead>
			<tbody>
			<?
			$q = "
				SELECT
					pa.*,
					at.dt_create account_transfer_dt_create
				FROM payment_accounts pa
				LEFT JOIN account_transfer at ON pa.account_transfer_idx = at.idx 
				WHERE pa.payment_idx='$idx' 
				ORDER BY pa.dt_create DESC";
			$r = mysql_query($q);
			$cou = mysql_num_rows($r);
			while ($row = mysql_fetch_array($r)) {
				?>
				<tr>
					<td><?= $cou; ?></td>
					<td><?= $row['tbtype'] == 'I' ? '입금' : '환불' ?></td>
					<td><? if ($row['buymethod'] == 'C') {
							echo "신용카드";
						} else if ($row['buymethod'] == 'F') {
							echo '정기결제';
						} ?></td>
					<td style='text-align:right'><?= number_format($row['account']); ?>원</td>
					<td><?= $row['dt_create']; ?></td>
					<td><?= $row['dt_settlement'] ?></td>
					<td><?= $row['account_transfer_dt_create'] ?></td>
				</tr>
				<?
				$cou--;
			}
			?>
		</table>
	<? } ?>
	<form id="regiform" name="regiform" method="post">
		<input type='hidden' name='idx' value='<?= $idx; ?>'>
		<input type='hidden' name='mode' value='memow'>
		<h3 style="margin:20px 0 5px;">* 댓글작성</h3>
		<table class="detailTable2">
			<?if($_SESSION['member_grade'] == '10') {?>
				<input type="hidden" name="public" value="Y" />
			<?} else {?>
				<tr>
					<th>멤버사 공개</th>
					<td><label><input type="checkbox" name="public" value="Y" /> 공개</label></td>
				</tr>
			<?}?>
			
			<tr>
				<th>내용</th>
				<td><textarea name='comment' style="width:700px;height:80px;ime-mode:active"></textarea></td>
			</tr>
		</table>
		<div class="btn_wrap btn_center btn_bottom">
			<span class="btn_green"><a href="javascript:handle_memo('<?= $idx; ?>','write');">작성하기</a></span>
		</div>
	</form><!-- // .form[name="regiform"] -->

	<h3 style="margin:20px 0 5px;">* 댓글</h3>
	<table class="listTableColor">
		<colgroup>
			<col width="55px"/>
			<col width=""/>
			<col width="120px"/>
			<col width="180px"/>
		</colgroup>
		<thead>
		<tr>
			<th>번호</th>
			<th>내용</th>
			<th>작성자</th>
			<th>작성일시</th>
		</tr>
		</thead>
		<tbody id="memolist">
		</tbody>
	</table>
</div><!-- // .content -->

<script type="text/javascript">
	function get_reservation_memo(idx, mods) {
		$.getJSON('/mo/proajax.php?XDEBUG_SESSION_START&modtype=market_common&han=get_reservation_memo&&idx=' + idx + '&mods=' + mods, function (result) {
			if (result.res == 'ok') {
				var html = '';
				$(result.data).each(function (index, item) {

					html += '<tr>';
					html += '<td class="first">' + item.nums + '</td>';
					html += '<td style="text-align:left;padding-left:4px;">';
					html += item.comment;
					html += '</td>';
					html += '<td>' + item.member_name + '</td>';
					html += '<td>' + item.dt_create + '</td>';
					html += '</tr>';
				});
				$('#memolist').html(html);
			}
			else {
				alert(result.resmsg);
			}

		});
	}

	function handle_memo(idx, mo) {
		if (mo == 'write') {
			if (!document.regiform.comment.value) {
				alert('내용입력');
				document.regiform.comment.focus();
				return;
			}

			var param = $("#regiform").serialize();
			$.getJSON('/mo/proajax.php?modtype=market_common&han=set_writememo&' + param, function (result) {

				if (result.res == 'ok') {
					alert('완료되었습니다');
					location.replace('../popup/orderview.php?idx=' + idx);
				}
				else {
					alert(result.resmsg);
				}
			});
		}

		if (mo == 'mod') {

			if (!document.mod_form.memo.value) {
				alert('내용입력');
				document.mod_form.memo.focus();
				return;
			}

			if (!confirm('수정하시겠습니까?')) {
				return;
			}
			var param = $("#mod_form").serialize();

			$.getJSON('/adminx/ajaxmo/market.php?act=set_memo_mod&' + param, function (result) {
				if (result.res == 'ok') {
					alert('완료되었습니다');
					get_reservation_memo('<?=$idx;?>', '');
				}
				else {
					alert(result.resmsg);
				}
			});
		}
		if (mo == 'ischeck') {
			if (confirm('변경하시겠습니까?')) {
				$.getJSON('/adminx/ajaxmo/market.php?act=set_memo_mod&mode=ischeck&idx=' + idx, function (result) {
					if (result.res == 'ok') {
						alert('완료되었습니다');
						get_reservation_memo('<?=$idx;?>', '');
					}
					else {
						alert(result.resmsg);
					}
				});
			}
		}
		if (mo == 'isouts' || mo == 'isins') {
			MM_openBrWindow('./popups/mod_memo_result.php?idx=' + idx + '&modes=' + mo, 'mmod', 'width=500,height=300,scrollbars=yes');
		}
	}

	$(function () {
		get_reservation_memo('<?=$idx;?>', '');

		$(document)
			.on('click', '.disapprovalCancel', function(e) {
				e.preventDefault();

				if(confirm('반려하시겠습니까?')) {
					var $ajaxForm = $('#ajaxForm');
					$ajaxForm.find('input[name="command"]').val('disapprovalCancel');

					$.getJSON($ajaxForm.attr('action'), $ajaxForm.serializeArray(), function(data) {
						if(data['result']) {
							alert('반려되었습니다.');
							location.reload();
						} else {
							alert('처리되지 않았습니다.')
						}
					});
				}
			})
	});


</script>
</body>