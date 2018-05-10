<?php /* Template_ 2.2.8 2017/12/28 16:47:16 /home/rentking/dev/rentking/old/sites/tpls/rentkingm/rent/payment.htm 000017044 */ ?>
<!DOCTYPE html>
<html lang="ko">
<head>
<?php $this->print_("head",$TPL_SCP,1);?>

</head>
<body>
<?php $this->print_("header",$TPL_SCP,1);?>

	<div id="contentWrap">

		<form action="/payment/allat_payment_form.php" method="post" id="reservationForm">
			<input type="hidden" name="order_idx" value="<?php echo $TPL_VAR["data"]["idx"]?>" />
			<input type="hidden" name="liscense" value="<?php echo $TPL_VAR["data"]["liscense"]["idx"]?>" />
			<input type="hidden" name="delcharge" value="<?php echo $TPL_VAR["data"]["car"]["delcharge"]?>" />
			<input type="hidden" name="extend" value="<?php echo $TPL_VAR["data"]["order"]["extend"]?>" />
			<input type="hidden" name="buymethod" id="buymethod" value="<?php if($TPL_VAR["data"]["order"]["retype"]== 2&&($TPL_VAR["data"]["order"]["ddata1"]> 1||$TPL_VAR["data"]["order"]["ddata2"]> 0)){?>F<?php }else{?>C<?php }?>" />
			<input type="hidden" name="allat_enc_data" value="" />
			<input type="hidden" name="mobile" value="Y" />

			<div class="form-box">
				<h3><?php echo $TPL_VAR["data"]["car"]["modelname"]?> (<?php echo $TPL_VAR["data"]["car"]["fuel_sname"]?>)</h3>
				<div class="form-field">
					<img src="<?php echo $TPL_VAR["global"]["imgserver"]?><?php if($TPL_VAR["data"]["car"]["car_image_path"]){?><?php echo $TPL_VAR["data"]["car"]["car_image_path"]?>?<?php echo $TPL_VAR["data"]["car"]["car_image_timestamp"]?><?php }else{?><?php echo $TPL_VAR["data"]["car"]["default_image_path"]?>?<?php echo $TPL_VAR["data"]["car"]["default_image_timestamp"]?><?php }?>" style="width:100%;display:block;" />
				</div>
			</div>

			<div class="form-box">
				<h3>대여정보</h3>
				<div class="form-field row-80-a only-text">
					<div class="col">대여기간</div>
					<div class="col text-right">
						<?php echo $TPL_VAR["data"]["order"]["sdate"]?><br />
						~ <?php echo $TPL_VAR["data"]["order"]["edate"]?>

					</div>
				</div>
				<div class="form-field row-80-a only-text">
					<div class="col">대여방법</div>
					<div class="col text-right">
<?php if($TPL_VAR["data"]["order"]["extend"]=='N'){?>
<?php if($TPL_VAR["data"]["car"]["delavail"]){?>
							<div class="text-left" style="display:inline-block;">
								<input type="radio" name="ptype" id="ptype2" value="2" /><label for="ptype2"> 지점방문</label><br />
								<input type="radio" name="ptype" id="ptype1" value="1" /><label for="ptype1" style="margin-right:0;"> 배달대여 (<?php if($TPL_VAR["data"]["car"]["delcharge"]> 0){?>+<?php echo number_format($TPL_VAR["data"]["car"]["delcharge"])?>원<?php }else{?>무료<?php }?>)</label>
							</div>
<?php }else{?>
							지점방문
							<input type="hidden" name="ptype" value="2" />
<?php }?>
<?php }else{?>
							연장
							<input type="hidden" name="ptype" value="<?php echo $TPL_VAR["data"]["extend_order"]["ptype"]?>" />
<?php }?>


					</div>
				</div>
				<div class="form-field row-80-a only-text last-child" id="positionRentshop">
					<div class="col">지점위치</div>
					<div class="col text-right">
					<? // [ $TPL_VAR["data"]["car"]["rentshop_name"]( $TPL_VAR["data"]["car"]["affiliate"])]<br /> $TPL_VAR["data"]["car"]["rentshop_addr1"]  $TPL_VAR["data"]["car"]["rentshop_addr2"]?>
						<?php echo $TPL_VAR["data"]["car"]["rentshop_addr"]?>

					</div>
				</div>
				<div class="form-field row-80-a only-text" id="positionPickup" style="display:none;">
					<div class="col">픽업위치</div>
					<div class="col text-right"><?php echo $TPL_VAR["data"]["order"]["addr"]?></div>
				</div>
				<div class="form-field row-80-a only-text" id="positionReturn" style="display:none;">
					<div class="col">반납위치</div>
					<div class="col text-right"><?php echo $TPL_VAR["data"]["order"]["raddr"]?></div>
				</div>
			</div>

			<!--
			<div class="form-box">
				<h3>차량정보</h3>
				<div class="form-field row-80-a only-text">
					<div class="col">색상</div>
					<div class="col text-right"><?php echo $TPL_VAR["data"]["car"]["color"]?></div>
				</div>
				<div class="form-field row-80-a only-text">
					<div class="col">주행거리</div>
					<div class="col text-right"><?php echo number_format($TPL_VAR["data"]["car"]["rundistan"])?>km</div>
				</div>
			</div>
			-->

			<div class="form-box">
				<h3>상세정보</h3>
				<div class="form-field text-point">
					* 지점의 사정으로 동급 또는 상급의 차량으로 변경될 수 있습니다.
				</div>
				<div class="form-field row-80-a only-text">
					<div class="col">차량옵션</div>
					<div class="col text-right"><?php echo $TPL_VAR["data"]["car"]["opt"]?></div>
				</div>
<?php if($TPL_VAR["data"]["order"]["distance_limit"]){?>
				<div class="form-field row-80-a only-text">
					<div class="col">주행거리제한</div>
					<div class="col text-right"><?php echo number_format($TPL_VAR["data"]["order"]["distance_limit"])?>&#13214;/월 (추가: <?php echo number_format($TPL_VAR["data"]["order"]["distance_additional_price"])?>원/&#13214;)</div>
				</div>
<?php }?>
				<div class="form-field row-80-a only-text">
					<div class="col">운전자나이</div>
					<div class="col text-right">만 <?php echo $TPL_VAR["data"]["car"]["rentage"]?>세 이상</div>
				</div>
				<div class="form-field row-80-a only-text">
					<div class="col">면허취득</div>
					<div class="col text-right">만 <?php echo $TPL_VAR["data"]["car"]["license_limit"]?>년 이상</div>
				</div>
				<div class="form-field row-100-a only-text" style="height:48px;">
					<div class="col">자차보험<br />고객부담금 <a href="/partials/help/insurance.html" class="help-tooltip openPopup" data-title="자차보험" title="도움말" data-width="490"></a></div>
					<div class="col text-right">
<?php if($TPL_VAR["data"]["order"]["pricetype"]== 2||$TPL_VAR["data"]["order"]["insu"]>= 1){?>
							<?php echo number_format($TPL_VAR["data"]["order"]["insu_exem"])?>원/건
<?php }else{?>
							자차미포함
<?php }?>
					</div>
				</div>
<?php if($TPL_VAR["data"]["order"]["insu_limit"]){?>
				<div class="form-field row-80-a only-text">
					<div class="col">자차보상한도</div>
					<div class="col text-right"><?php echo number_format($TPL_VAR["data"]["order"]["insu_limit"])?>원/건</div>
				</div>
<?php }?>
				<div class="form-field row-80-a only-text">
					<div class="col">대인</div>
					<div class="col text-right"><?php echo $TPL_VAR["data"]["car"]["insu_per"]?></div>
				</div>
				<div class="form-field row-80-a only-text">
					<div class="col">대물</div>
					<div class="col text-right"><?php echo number_format($TPL_VAR["data"]["car"]["insu_goods"])?>원</div>
				</div>
				<div class="form-field row-80-a only-text">
					<div class="col">자손</div>
					<div class="col text-right"><?php echo number_format($TPL_VAR["data"]["car"]["insu_self"])?>원</div>
				</div>
			</div>

<?php if($TPL_VAR["data"]["order"]["extend"]=='N'){?>
			<div class="form-box">
				<h3>제1운전자(예약자)</h3>
				<div class="form-field row-80-a only-text">
					<div class="col">이름</div>
					<div class="col text-right"><?php echo $TPL_VAR["data"]["member"]["name"]?></div>
				</div>
				<div class="form-field row-80-a only-text">
					<div class="col">휴대폰번호</div>
					<div class="col text-right"><?php echo $TPL_VAR["data"]["member"]["cp"]?></div>
				</div>
				<div class="form-field row-80-a">
					<div class="col">면허종류</div>
					<div class="col">
						<select name="kinds">
							<option value="FN" <?php if($TPL_VAR["data"]["liscense"]["kinds"]=='FN'){?>selected<?php }?>>1종 보통</option>
							<option value="FL" <?php if($TPL_VAR["data"]["liscense"]["kinds"]=='FL'){?>selected<?php }?>>1종 대형</option>
							<option value="SN" <?php if($TPL_VAR["data"]["liscense"]["kinds"]=='SN'){?>selected<?php }?>>2종 보통</option>
						</select>
					</div>
				</div>
				<div class="form-field row-80-a">
					<div class="col">면허번호</div>
					<div class="col"><input type="number" name="nums" maxlength="30" value="<?php echo $TPL_VAR["data"]["liscense"]["nums"]?>" /></div>
				</div>
				<div class="form-field row-110-a">
					<div class="col">적성검사 만료일</div>
					<div class="col"><input type="text" name="date1" value="<?php echo $TPL_VAR["data"]["liscense"]["date1"]?>" class="datePicker" data-parent="body" readonly /></div>
				</div>
				<div class="form-field row-110-a">
					<div class="col">발급일</div>
					<div class="col"><input type="text" name="date2" value="<?php echo $TPL_VAR["data"]["liscense"]["date2"]?>" class="datePicker" data-parent="body" readonly /></div>
				</div>
			</div>

			<div class="form-box">
				<h3>제2운전자</h3>
				<div class="form-field row-80-a">
					<div class="col">이름</div>
					<div class="col"><input type="text" name="namesub" maxlength="50" /></div>
				</div>
				<div class="form-field row-80-a">
					<div class="col">휴대폰번호</div>
					<div class="col"><input type="number" name="cpsub" maxlength="50" /></div>
				</div>
				<div class="form-field row-80-a">
					<div class="col">면허종류</div>
					<div class="col">
						<select name="kindsub">
							<option value="FN">1종 보통</option>
							<option value="FL">1종 대형</option>
							<option value="SN">2종 보통</option>
						</select>
					</div>
				</div>
				<div class="form-field row-80-a">
					<div class="col">면허번호</div>
					<div class="col"><input type="text" name="numsub" maxlength="30" value="" /></div>
				</div>
				<div class="form-field row-110-a">
					<div class="col">적성검사 만료일</div>
					<div class="col"><input type="text" name="date1sub" value="" class="datePicker" data-parent="body" readonly /></div>
				</div>
				<div class="form-field row-110-a">
					<div class="col">발급일</div>
					<div class="col"><input type="text" name="date2sub" value="" class="datePicker" data-parent="body" readonly /></div>
				</div>
			</div>
<?php }?>

			<div class="form-box">
				<h3>결제정보</h3>
				<div class="form-field row-80-a">
					<div class="col">결제내역</div>
					<div class="col">
						<ul class="payment-description">
<?php if($TPL_VAR["data"]["order"]["retype"]== 2&&$TPL_VAR["data"]["order"]["extend"]!='Y'){?>
							<li>
								보증금
								<span class="price"><?php echo number_format($TPL_VAR["data"]["order"]["preaccount"])?>원</span>
							</li>
<?php }?>
							<li>차량 대여료
								<span class="price"><?php echo number_format($TPL_VAR["data"]["order"]["account"])?>원</span>
							</li>
<?php if($TPL_VAR["data"]["car"]["delavail"]){?>
							<li style="display:none;" id="delCharge">배달료
								<span class="price"><?php if($TPL_VAR["data"]["car"]["delcharge"]> 0){?><?php echo number_format($TPL_VAR["data"]["car"]["delcharge"])?>원<?php }else{?>무료<?php }?></span>
							</li>
<?php }?>
<?php if($TPL_VAR["data"]["car"]["pricetype"]== 2){?>
							<li>보험료 <span class="price">포함</span></li>
<?php }elseif($TPL_VAR["data"]["order"]["insuac"]> 0){?>
							<li>보험료
								<span class="price"><?php echo number_format($TPL_VAR["data"]["order"]["insuac"])?>원</span>
							</li>
<?php }?>
							<li class="total">총 금액
								<span class="price text-point"><span class="numberAnimation numberAnimationAutoStart" id="totalPrice" data-target="<?php echo $TPL_VAR["data"]["order"]["totalaccount"]?>" data-total-account="<?php echo $TPL_VAR["data"]["order"]["totalaccount"]?>">0</span>원</span>
							</li>
						</ul>
					</div>
				</div>
<?php if($TPL_VAR["data"]["order"]["retype"]== 2&&($TPL_VAR["data"]["order"]["ddata1"]> 1||$TPL_VAR["data"]["order"]["ddata2"]> 0)){?>
				<div class="form-field row-80-a">
					<div class="col">결제방법</div>
					<div class="col">
						<input type="radio" name="payType" id="payType2" value="2" checked /><label for="payType2"> 정기결제</label>
						<input type="radio" name="payType" id="payType1" value="1" /><label for="payType1"> 전액결제</label><br />
						<ul class="payment-description" id="paymentDescription">
							<li>월비용
								<span class="price"><span class="month text-point"><?php echo number_format($TPL_VAR["data"]["order"]["account1"])?>원</span> X <?php echo $TPL_VAR["data"]["order"]["ddata1"]?></span>
							</li>
							<li>잔금
								<span class="price"><?php echo number_format($TPL_VAR["data"]["order"]["account2"])?>원</span>
							</li>
							<li class="total">합계
								<span class="price all"><?php echo number_format($TPL_VAR["data"]["order"]["totalaccount"])?>원</span>
							</li>
						</ul>
					</div>
				</div>
<?php }?>
<?php if(count($TPL_VAR["data"]["coupon"])> 0&&!$TPL_VAR["data"]["promotion"]){?>
				<div class="form-field row-80-a">
					<div class="col">쿠폰선택</div>
					<div class="col">
						<select name="coupon" id="coupon">
							<option value="0" data-account="0">사용하실 쿠폰을 선택해 주세요.</option>
<?php if(is_array($TPL_R1=$TPL_VAR["data"]["coupon"])&&!empty($TPL_R1)){foreach($TPL_R1 as $TPL_V1){?>
							<option value="<?php echo $TPL_V1["idx"]?>" data-account="<?php echo $TPL_V1["account"]?><?php if($TPL_V1["actype"]== 2){?>%<?php }?>"><?php echo $TPL_V1["name"]?> (<?php if($TPL_V1["actype"]== 1){?><?php echo number_format($TPL_V1["account"])?>원<?php }else{?><?php echo $TPL_V1["account"]?>%<?php }?>)</option>
<?php }}?>
						</select>
<?php if($TPL_VAR["data"]["order"]["retype"]== 2&&($TPL_VAR["data"]["order"]["ddata1"]> 1||$TPL_VAR["data"]["order"]["ddata2"]> 0)){?>
						<span class="text-muted">* 당월 결제금액에 적용됩니다.</span>
<?php }?>
					</div>
				</div>
<?php }?>
<?php if($TPL_VAR["data"]["promotion"]){?>
				<div class="form-field row-80-a only-text">
					<div class="col"><?php echo promotion($TPL_VAR["data"]["promotion"][ 0])?></div>
					<div class="col text-right"><span id="discount" class="numberAnimation" data-target="0">0</span>원</div>
				</div>
<?php }?>
				<div class="form-field row-80-a only-text">
					<div class="col">결제금액</div>
					<div class="col"><span class="text-point"><span class="numberAnimation" id="paymentAmount"
						data-amount="<?php if($TPL_VAR["data"]["order"]["retype"]!= 2){?><?php echo $TPL_VAR["data"]["order"]["totalaccount"]?><?php }else{?><?php echo $TPL_VAR["data"]["order"]["account1"]?><?php }?>"
						data-month="<?php echo $TPL_VAR["data"]["order"]["account1"]?>"
						data-all="<?php echo $TPL_VAR["data"]["order"]["totalaccount"]?>"
						data-delcharge="0"
<?php if($TPL_VAR["data"]["promotion"]){?>data-promotion="<?php echo $TPL_VAR["data"]["promotion"][ 1]?>"<?php }?>
						data-target="<?php if($TPL_VAR["data"]["order"]["retype"]!= 2){?><?php echo $TPL_VAR["data"]["order"]["totalaccount"]?><?php }else{?><?php echo $TPL_VAR["data"]["order"]["account1"]?><?php }?>">0</span>원</span></div>
				</div>
			</div>

			<div class="row-a-60">
				<div class="col"><input type="checkbox" data-title="취소수수료 규정" name="agreeCancel" id="agreeCancel" value="Y"><label for="agreeCancel"> 취소수수료 규정 동의</label></div>
				<div class="col text-right"><a href="/partials/terms/cancel.html?20170802033049<?php if(!$TPL_VAR["global"]["production"]){?>_<?php echo time()?><?php }?>" class="more openPopup" data-title="취소수수료 규정" data-width="500">전문보기&gt;</a></div>
			</div>
			<div class="row-a-60">
				<div class="col"><input type="checkbox" data-title="전자금융거래 기본약관" name="agreeTransaction" id="agreeTransaction" value="Y"><label for="agreeTransaction"> 전자금융거래 기본약관 동의</label></div>
				<div class="col text-right"><a href="/partials/terms/transaction.html" class="more openPopup" data-title="전자금융거래 기본약관" data-width="500">전문보기&gt;</a></div>
			</div>

			<button type="submit" class="btn btn-primary big" style="margin-top:20px;">결제하기</button>

			<div class="text-muted" style="font-size:11px;letter-spacing:-0.05em;margin-top:10px;">렌트킹은 통신판매중개자이며 통신판매의 당사자가 아니며,<br />상품·거래정보 및 거래에 대하여 책임을 지지 않습니다.</div>
		</form>
		<div id="paymentForm"></div>
	</div>
<?php $this->print_("footer",$TPL_SCP,1);?>


	<script type="text/javascript" charset="EUC-KR" src="https://tx.allatpay.com/common/AllatPayM.js"></script>
	<script type="text/javascript" src="/js/rentking.reservation.min.js?20170830061614<?php if(!$TPL_VAR["global"]["production"]){?>_<?php echo time()?><?php }?>"></script>
</body>
</html>