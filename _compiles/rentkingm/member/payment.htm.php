<?php /* Template_ 2.2.8 2017/12/28 16:47:16 /home/rentking/dev/rentking/old/sites/tpls/rentkingm/member/payment.htm 000006765 */ 
$TPL_olist_1=empty($TPL_VAR["olist"])||!is_array($TPL_VAR["olist"])?0:count($TPL_VAR["olist"]);?>
<!DOCTYPE html>
<html lang="ko">
<head>
<?php $this->print_("head",$TPL_SCP,1);?>

</head>
<body>
<?php $this->print_("header",$TPL_SCP,1);?>

    <div id="contentWrap">
<?php if($TPL_olist_1){foreach($TPL_VAR["olist"] as $TPL_V1){?>
		<div class="form-box orderitem dan<?php echo $TPL_V1["dan"]?> <?php if($TPL_V1["dan"]!='1'){?>mb-20<?php }?> ">
			<a href="#<?php echo $TPL_V1["order_idx"]?>" class="handle">
				<h3 class="header"><?php echo $TPL_V1["modelname"]?> (<?php echo $TPL_V1["fuel_sname"]?>)</h3>
				<div class="form-field only-text info">
					대여기간: <span class="date"><?php echo substr($TPL_V1["sdate"], 0, 16)?> ~ <?php echo substr($TPL_V1["edate"], 0, 16)?></span><br />
					결제금액: <?php echo number_format($TPL_V1["payment_account"])?>원<br />
					지점: <?php echo $TPL_V1["rentshop_name"]?>(<?php echo $TPL_V1["affiliate"]?>, <?php echo phone_number_format($TPL_V1["rentshop_dphone2"])?>)<br />
					<span class="text-muted">(<?php echo $TPL_V1["rentshop_addr1"]?> <?php echo $TPL_V1["rentshop_addr2"]?>)</span>
				</div>

				<h3 class="add-info">대여정보</h3>
				<div class="form-field row-80-a add-info only-text">
					<div class="col">대여방법</div>
					<div class="col text-right"><?php if($TPL_V1["ptype"]== 1){?>배달대여<?php }else{?>지점방문<?php }?></div>
				</div>
				<!--
				<div class="form-field row-80-a add-info only-text">
					<div class="col">지점</div>
					<div class="col text-right"><?php echo $TPL_V1["rentshop_name"]?>(<?php echo $TPL_V1["affiliate"]?>)<br /><?php echo $TPL_V1["rentshop_addr1"]?> <?php echo $TPL_V1["rentshop_addr2"]?></div>
				</div>
				-->
<?php if($TPL_V1["ptype"]== 1){?>
				<div class="form-field row-80-a add-info only-text">
					<div class="col">픽업위치</div>
					<div class="col text-right"><?php echo $TPL_V1["addr"]?></div>
				</div>
				<div class="form-field row-80-a add-info only-text">
					<div class="col">반납위치</div>
					<div class="col text-right"><?php echo $TPL_V1["raddr"]?></div>
				</div>
<?php }?>

				<h3 class="add-info">상세정보</h3>
				<div class="form-field row-80-a add-info only-text">
					<div class="col">차량옵션</div>
					<div class="col text-right"><?php echo $TPL_V1["opt"]?></div>
				</div>
<?php if($TPL_V1["distance_limit"]){?>
				<div class="form-field row-80-a add-info only-text">
					<div class="col">주행거리제한</div>
					<div class="col text-right"><?php echo number_format($TPL_V1["distance_limit"])?>&#13214;/월 (추가: <?php echo number_format($TPL_V1["distance_additional_price"])?>원/&#13214;)</div>
				</div>
<?php }?>
				<div class="form-field row-80-a add-info only-text">
					<div class="col">운전자나이</div>
					<div class="col text-right">만 <?php echo $TPL_V1["car_rentage"]?>세 이상</div>
				</div>
				<div class="form-field row-80-a add-info only-text">
					<div class="col">면허취득</div>
					<div class="col text-right">만 <?php echo $TPL_V1["car_license_limit"]?>년 이상</div>
				</div>
				<div class="form-field row-80-a add-info only-text">
					<div class="col">자차보험</div>
					<div class="col text-right">
<?php if($TPL_V1["pricetype"]== 2||$TPL_V1["insu"]>= 1){?>
							고객부담금(<?php echo number_format($TPL_V1["insu_exem"])?>원/건)
<?php }else{?>
							자차미포함
<?php }?>
					</div>
				</div>
<?php if($TPL_V1["insu_limit"]){?>
				<div class="form-field row-80-a add-info only-text">
					<div class="col">자차보상한도</div>
					<div class="col text-right">
						<?php echo number_format($TPL_V1["insu_limit"])?>원/건
					</div>
				</div>
<?php }?>
				<div class="form-field row-80-a add-info only-text">
					<div class="col">대인</div>
					<div class="col text-right"><?php echo $TPL_V1["car_insu_per"]?></div>
				</div>
				<div class="form-field row-80-a add-info only-text">
					<div class="col">대물</div>
					<div class="col text-right"><?php echo number_format($TPL_V1["car_insu_goods"])?>원</div>
				</div>
				<div class="form-field row-80-a add-info only-text">
					<div class="col">자손</div>
					<div class="col text-right"><?php echo number_format($TPL_V1["car_insu_self"])?>원</div>
				</div>

				<h3 class="add-info">결제정보</h3>
				<div class="form-field row-80-a add-info only-text">
					<div class="col">결제일</div>
					<div class="col text-right"><?php echo $TPL_V1["dt_create"]?></div>
				</div>
				<div class="form-field row-80-a add-info only-text">
					<div class="col">결제금액</div>
					<div class="col text-right"><?php echo number_format($TPL_V1["payment_account"])?>원</div>
				</div>

			</a>
<?php if($TPL_V1["dan"]=='1'){?>
			<button type="button" class="btn-error small mb-0 inline cancel" onclick="set_cancel(<?php echo $TPL_V1["idx"]?>)">예약취소</button>
<?php }?>
<?php if($TPL_V1["dan"]=='2'){?>
			<a href="/rent/extend.php?idx=<?php echo $TPL_V1["idx"]?>" class="btn-point2 small mb-0 inline extend openPopup" data-title="연장신청" data-width="340" data-cache="false">연장신청</a>
<?php }?>
		</div>
<?php }}?>
    </div>

	<form name="cancelForm" id="cancelForm" action="/member/cancel.php" method="post">
		<input type="hidden" name="allat_enc_data" value="" />
		<input type="hidden" name="mode" value="c" />
		<input type="hidden" name="order_idx" value="" />
		<input type="hidden" name="accountReturn" value="" />
	</form>
	<div id="paymentForm"></div>
<?php $this->print_("footer",$TPL_SCP,1);?>


	<script type="text/javascript" charset="EUC-KR" src="https://tx.allatpay.com/common/NonAllatPayREPlus.js"></script>
	<script type="text/javascript">
		var headerHeight = $('header').outerHeight();
		$(document)
			.on('click', 'a.handle', function(e) {
				e.preventDefault();
				var $handle = $(this);
				$handle.parent().toggleClass('on').siblings('.orderitem').removeClass('on');
				setTimeout(function() {
					$(window).scrollTop($handle.offset().top - headerHeight - 30);
				}, 100);
			});

		function set_cancel(idx) {
			if (confirm('취소 하시겠습니까?')) {
				var $cancelForm = $('#cancelForm');
				var $paymentForm = $('#paymentForm');
				$cancelForm.find('input[name="order_idx"]').val(idx);
				$.post($cancelForm.attr('action'), $cancelForm.serializeArray())
					.done(function(html) {
						$paymentForm.html(html);
					});
			}
		}
	</script>
</body>
</html>