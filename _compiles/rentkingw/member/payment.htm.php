<?php /* Template_ 2.2.8 2017/12/28 16:47:16 /home/rentking/dev/rentking/old/sites/tpls/rentkingw/member/payment.htm 000006915 */ 
$TPL_olist_1=empty($TPL_VAR["olist"])||!is_array($TPL_VAR["olist"])?0:count($TPL_VAR["olist"]);?>
<!DOCTYPE html>
<html lang="ko">
<head>
<?php $this->print_("head",$TPL_SCP,1);?>

</head>
<body>
<?php $this->print_("header",$TPL_SCP,1);?>

	<div id="contentWrap">
<?php $this->print_("side_member",$TPL_SCP,1);?>

		<div id="content">
			<form name="receiptFm" id="receiptFm" method="post">
				<input type="hidden" name="tx_seq_no">
				<input type="hidden" name="order_no">
			</form>
			<div class="conBanner">
				<div class="txt">
					<h2>결제내역</h2>
					<strong>결제하신 내역들을 확인 할 수 있습니다.</strong>
					<p>렌트킹에서 결제하신 카드의 매출전표, 기간연장등을 하실 수 있습니다.</p>
				</div><!--//txt-->
				<span><img src="/imgs/rentking.w/paymentTopicon.jpg" alt="결제내역Icon"></span>
			</div><!--//conBanner-->
<?php if($TPL_olist_1){foreach($TPL_VAR["olist"] as $TPL_V1){?>
			<div id="order<?php echo $TPL_V1["order_idx"]?>" style="margin-bottom:40px;">
				<table class="list">
					<thead>
					<tr class="border-b">
						<th></th>
						<th>구매정보</th>
						<th>예약일시</th>
						<th>대여일시<br />반납일시</th>
						<th>결제금액</th>
					</tr>
					</thead>
					<tbody>
					<tr>
						<th rowspan="2" style="width:12px;" class="<?php switch($TPL_V1["dan"]){case  1:?>bg-error<?php break;case  2:?>bg-point<?php break;case  4:?>bg-error<?php }?>">
<?php switch($TPL_V1["dan"]){case  1:?>	예약
<?php break;case  2:?>			대여중
<?php break;case  3:?>			반납완료
<?php break;case  4:?>			취소요청
<?php break;case  5:?>			취소완료
<?php }?>
						</th>
						<td>
							<span class="text-point"><?php echo $TPL_V1["modelname"]?> (<?php echo $TPL_V1["fuel_sname"]?>)</span><br />
							자차보험:
<?php if($TPL_V1["pricetype"]== 2||$TPL_V1["insu"]>= 1){?>
								고객부담금(<?php echo number_format($TPL_V1["insu_exem"])?>원/건)
<?php }else{?>
								자차미포함
<?php }?>
<?php if($TPL_V1["insu_limit"]){?>
								<br />자차보상한도: <?php echo number_format($TPL_V1["insu_limit"])?>원/건
<?php }?>
						</td>
						<td>
							<?php echo $TPL_V1["dt_create"]?>

						</td>
						<td>
							<?php echo $TPL_V1["sdate"]?><br />
							<?php echo $TPL_V1["edate"]?>

						</td>
						<td rowspan="2" style="vertical-align:top;">
							<ul class="payment-description" style="width:150px;">
								<li>차량 대여료
									<span class="price"><?php echo number_format($TPL_V1["account"])?>원</span>
								</li>
<?php if($TPL_V1["delac"]> 0){?>
								<li>배달료
									<span class="price"><?php echo number_format($TPL_V1["delac"])?>원</span>
								</li>
<?php }?>
<?php if($TPL_V1["pricetype"]== 2){?>
								<li>보험료 <span class="price">포함</span></li>
<?php }elseif($TPL_V1["insuac"]> 0){?>
								<li>보험료
									<span class="price"><?php echo number_format($TPL_V1["insuac"])?>원</span>
								</li>
<?php }?>
<?php if($TPL_V1["discount"]> 0){?>
								<li>할인
									<span class="price">- <?php echo number_format($TPL_V1["discount"])?>원</span>
								</li>
<?php }?>
								<li class="total">결제금액
									<span class="price text-point"><?php echo number_format($TPL_V1["payment_account"])?>원</span>
								</li>
							</ul>
						</td>
					</tr>
					<tr>
						<td colspan="3">
							옵션: <span class="text-muted"><?php echo $TPL_V1["opt"]?></span><br />
<?php if($TPL_V1["distance_limit"]){?>
							주행거리제한: <?php echo number_format($TPL_V1["distance_limit"])?>&#13214;/월 (추가: <?php echo number_format($TPL_V1["distance_additional_price"])?>원/&#13214;)<br />
<?php }?>
							지점: <?php echo $TPL_V1["rentshop_name"]?>(<?php echo $TPL_V1["affiliate"]?>, <?php echo phone_number_format($TPL_V1["rentshop_dphone2"])?>) <span class="text-muted">(<?php echo $TPL_V1["rentshop_addr1"]?> <?php echo $TPL_V1["rentshop_addr2"]?>)</span>
<?php if($TPL_V1["ptype"]== 1){?>
								<br />픽업위치: <?php echo $TPL_V1["addr"]?><br />
								반납위치: <?php echo $TPL_V1["raddr"]?>

<?php }?>
						</td>
					</tr>
					</tbody>
				</table>
				<div style="text-align:right;margin-top:20px;">
<?php if($TPL_V1["buymethod"]=='C'){?>
					<button type="button" class="btn-point" onclick="f_cardReceiptPrint('<?php echo $TPL_V1["order_idx"]?>')">매출전표</button>
<?php }?>
<?php if($TPL_V1["buymethod"]=='F'){?>
					<button type="button" class="btn-point" onclick="f_fixReceiptPrint('<?php echo $TPL_V1["tno"]?>', '<?php echo $TPL_V1["order_idx"]?>')">매출전표</button>
<?php }?>
<?php if($TPL_V1["dan"]=='2'){?>
					<a href="/rent/extend.php?idx=<?php echo $TPL_V1["idx"]?>" class="btn-point2 openPopup" data-title="연장신청" data-width="340" data-cache="false">연장신청</a>
<?php }?>
<?php if($TPL_V1["dan"]=='1'){?>
					<button type="button" class="btn-error" onclick="set_cancel(<?php echo $TPL_V1["idx"]?>)">예약취소</button>
<?php }?>
				</div>
			</div>
<?php }}?>
		</div>
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
		function f_cardReceiptPrint(orderNo) {
			var urls ="http://www.allatpay.com/servlet/AllatBizPop/member/pop_card_receipt.jsp?shop_id=rentking2&order_no="+orderNo;
			window.open(urls,"app","width=410,height=650,scrollbars=0");
		}
		function f_fixReceiptPrint(txSeqNo, orderNo) {
			receiptFm.tx_seq_no.value = txSeqNo;
			receiptFm.order_no.value = orderNo;

			window.open("", "cardReceipt", "width=410,height=650,scrollbars=1");
			receiptFm.action = "https://www.allatpay.com/servlet/AllatBizPop/member/pop_card_receipt.jsp";
			receiptFm.method = "post";
			receiptFm.target = "cardReceipt";
			receiptFm.submit();
		}
	</script>
</body>
</html>