<?php /* Template_ 2.2.8 2018/04/06 14:23:23 /home/rentking/dev/rentking/old/sites/tpls/rentkingw/rent/payment.htm 000016906 */ ?>
<!DOCTYPE html>
<html lang="ko">
<head>
<?php $this->print_("head",$TPL_SCP,1);?>

</head>
<body>
<?php $this->print_("header",$TPL_SCP,1);?>

	<div id="contentWrap">
		<div class="conBanner">
			<div class="txt">
				<h2>결제 <a href="/partials/help/payment.w.html?20170913051830" class="help-tooltip openPopup" data-title="결제" title="도움말" data-width="744"></a></h2>
				<strong>가격과 보험사항을 확인해주세요!</strong>
				<p>이제 결제만 해주시면 정말 쉬워진 렌트카 예약을 경험하실 수 있습니다!</p>
			</div><!--//txt-->
			<span><img src="/imgs/rentking.w/paymentTopicon.jpg" alt="결제내역Icon"></span>
		</div><!--//conBanner-->
		<form action="/payment/allat_payment_form.php" method="post" id="reservationForm">
			<input type="hidden" name="order_idx" value="<?php echo $TPL_VAR["data"]["idx"]?>" />
			<input type="hidden" name="liscense" value="<?php echo $TPL_VAR["data"]["liscense"]["idx"]?>" />
			<input type="hidden" name="delcharge" value="<?php echo $TPL_VAR["data"]["car"]["delcharge"]?>" />
			<input type="hidden" name="extend" value="<?php echo $TPL_VAR["data"]["order"]["extend"]?>" />
			<input type="hidden" name="buymethod" id="buymethod" value="<?php if($TPL_VAR["data"]["order"]["retype"]== 2&&($TPL_VAR["data"]["order"]["ddata1"]> 1||$TPL_VAR["data"]["order"]["ddata2"]> 0)){?>F<?php }else{?>C<?php }?>" />
			<input type="hidden" name="allat_enc_data" value="" />

			<div class="sub-title">
				<h3>대여정보</h3>
			</div>
			<table class="list" style="margin-bottom:33px">
				<colgroup>
					<col style="width:162px;">
					<col style="width:120px;">
					<col>
				</colgroup>
				<tbody>
				<tr>
					<td rowspan="4" style="position:relative;vertical-align:middle" id="carImage">
						<img src="<?php echo $TPL_VAR["global"]["imgserver"]?><?php if($TPL_VAR["data"]["car"]["car_image_path"]){?><?php echo $TPL_VAR["data"]["car"]["car_image_path"]?>?<?php echo $TPL_VAR["data"]["car"]["car_image_timestamp"]?><?php }else{?><?php echo $TPL_VAR["data"]["car"]["default_image_path"]?>?<?php echo $TPL_VAR["data"]["car"]["default_image_timestamp"]?><?php }?>" style="width:100%;" />
					</td>
					<th>모델명</th>
					<td><?php echo $TPL_VAR["data"]["car"]["modelname"]?> (<?php echo $TPL_VAR["data"]["car"]["fuel_sname"]?>)</td>
				</tr>
				<tr>
					<th>대여기간</th>
					<td><?php echo $TPL_VAR["data"]["order"]["sdate"]?> ~ <?php echo $TPL_VAR["data"]["order"]["edate"]?></td>
				</tr>
				<tr>
					<th>대여방법</th>
					<td>
<?php if($TPL_VAR["data"]["order"]["extend"]=='N'){?>
<?php if($TPL_VAR["data"]["car"]["delavail"]){?>
							<label style="margin-right:10px;"><input type="radio" name="ptype" value="2" /> 지점방문</label>
							<label style="margin-right:10px;"><input type="radio" name="ptype" value="1" /> 배달대여 (<?php if($TPL_VAR["data"]["car"]["delcharge"]> 0){?>+<?php echo number_format($TPL_VAR["data"]["car"]["delcharge"])?>원<?php }else{?>무료<?php }?>)</label>
<?php }else{?>
							지점방문
							<input type="hidden" name="ptype" value="2" />
<?php }?>
<?php }else{?>
							연장
							<input type="hidden" name="ptype" value="<?php echo $TPL_VAR["data"]["extend_order"]["ptype"]?>" />
<?php }?>
					</td>
				</tr>
				<tr id="positionRentshop">
					<th>지점위치</th>
					<? // <td>[ $TPL_VAR["data"]["car"]["rentshop_name"]( $TPL_VAR["data"]["car"]["affiliate"])]  $TPL_VAR["data"]["car"]["rentshop_addr1"]  $TPL_VAR["data"]["car"]["rentshop_addr2"]</td> ?>
					<td><?php echo $TPL_VAR["data"]["car"]["rentshop_addr"]?></td>
				</tr>
				<tr id="positionPickup" style="display:none;">
					<th>픽업위치</th>
					<td><?php echo $TPL_VAR["data"]["order"]["addr"]?></td>
				</tr>
				<tr id="positionReturn" style="display:none;">
					<th>반납위치</th>
					<td><?php echo $TPL_VAR["data"]["order"]["raddr"]?></td>
				</tr>
				</tbody>
			</table>

			<div class="sub-title">
				<h3>상세정보</h3>
				<div class="description">* 지점의 사정으로 인하여 동급, 또는 상급의 차량으로 변경 될 수 있습니다.</div>
			</div>
			<table class="list" style="margin-bottom:33px">
				<colgroup>
					<col style="width:120px;">
					<col>
					<col>
					<col>
					<col>
					<col>
					<col>
				</colgroup>
				<tbody>
				<tr>
					<th>차량옵션</th>
					<td colspan="<?php if($TPL_VAR["data"]["order"]["insu_limit"]){?>7<?php }else{?>6<?php }?>"><?php echo $TPL_VAR["data"]["car"]["opt"]?></td>
				</tr>
<?php if($TPL_VAR["data"]["order"]["distance_limit"]){?>
				<tr>
					<th>주행거리제한</th>
					<td colspan="<?php if($TPL_VAR["data"]["order"]["insu_limit"]){?>7<?php }else{?>6<?php }?>"><?php echo number_format($TPL_VAR["data"]["order"]["distance_limit"])?>&#13214;/월 (추가: <?php echo number_format($TPL_VAR["data"]["order"]["distance_additional_price"])?>원/&#13214;)</td>
				</tr>
<?php }?>
				<tr>
					<th rowspan="2">보험안내</th>
					<th>운전자나이</th>
					<th>면허취득</th>
					<th>자차보험 고객부담금 <a href="/partials/help/insurance.html" class="help-tooltip openPopup" data-title="자차보험 고객부담금" title="도움말" data-width="490"></a></th>
<?php if($TPL_VAR["data"]["order"]["insu_limit"]){?><th>자차보상한도</th><?php }?>
					<th>대인</th>
					<th>대물</th>
					<th>자손</th>
				</tr>
				<tr>
					<td>만 <?php echo $TPL_VAR["data"]["car"]["rentage"]?>세 이상</td>
					<td>만 <?php echo $TPL_VAR["data"]["car"]["license_limit"]?>년 이상</td>
					<td>
<?php if($TPL_VAR["data"]["order"]["pricetype"]== 2||$TPL_VAR["data"]["order"]["insu"]>= 1){?>
							<?php echo number_format($TPL_VAR["data"]["order"]["insu_exem"])?>원/건
<?php }else{?>
							자차미포함
<?php }?>
					</td>
<?php if($TPL_VAR["data"]["order"]["insu_limit"]){?><td><?php echo number_format($TPL_VAR["data"]["order"]["insu_limit"])?>원/건</td><?php }?>
					<td><?php echo $TPL_VAR["data"]["car"]["insu_per"]?></td>
					<td><?php echo number_format($TPL_VAR["data"]["car"]["insu_goods"])?>원</td>
					<td><?php echo number_format($TPL_VAR["data"]["car"]["insu_self"])?>원</td>
				</tr>
				</tbody>
			</table>

<?php if($TPL_VAR["data"]["order"]["extend"]=='N'){?>
			<div class="sub-title">
				<h3>운전자정보 <a href="/partials/help/driver_license.html?20170731022208" class="help-tooltip openPopup" data-title="면허증" title="도움말" data-width="920"></a></h3>
				<div class="description">* 대여방법이 지점방문 대여일 경우, 면허증 등록을 하지 않으셔도 대여가 가능합니다.</div>
			</div>
			<table class="list" style="margin-bottom:33px">
				<colgroup>
					<col style="width:120px;">
					<col>
					<col style="width:120px;">
					<col>
				</colgroup>
				<thead>
				<tr class="border-b">
					<th colspan="2">제1운전자(예약자)</th>
					<th colspan="2">제2운전자</th>
				</tr>
				</thead>
				<tbody>
				<tr>
					<th>이름</th>
					<td><?php echo $TPL_VAR["data"]["member"]["name"]?></td>
					<th>이름</th>
					<td><input type="text" name="namesub" maxlength="50" /></td>
				</tr>
				<tr>
					<th>휴대폰번호</th>
					<td><?php echo $TPL_VAR["data"]["member"]["cp"]?></td>
					<th>휴대폰번호</th>
					<td><input type="text" name="cpsub" maxlength="50" /></td>
				</tr>
				<tr>
					<th>면허종류</th>
					<td>
						<select name="kinds">
							<option value="FN" <?php if($TPL_VAR["data"]["liscense"]["kinds"]=='FN'){?>selected<?php }?>>1종 보통</option>
							<option value="FL" <?php if($TPL_VAR["data"]["liscense"]["kinds"]=='FL'){?>selected<?php }?>>1종 대형</option>
							<option value="SN" <?php if($TPL_VAR["data"]["liscense"]["kinds"]=='SN'){?>selected<?php }?>>2종 보통</option>
						</select>
					</td>
					<th>면허종류</th>
					<td>
						<select name="kindsub">
							<option value="FN">1종 보통</option>
							<option value="FL">1종 대형</option>
							<option value="SN">2종 보통</option>
						</select>
					</td>
				</tr>
				<tr>
					<th>면허번호</th>
					<td>
						<input type="text" name="nums" maxlength="30" value="<?php echo $TPL_VAR["data"]["liscense"]["nums"]?>" />
					</td>
					<th>면허번호</th>
					<td>
						<input type="text" name="numsub" maxlength="30" value="" />
					</td>
				</tr>
				<tr>
					<th>적성검사 만료일</th>
					<td>
						<input type="text" name="date1" value="<?php echo $TPL_VAR["data"]["liscense"]["date1"]?>" class="datePicker" data-parent="body" readonly />
					</td>
					<th>적성검사 만료일</th>
					<td>
						<input type="text" name="date1sub" value="" class="datePicker" data-parent="body" readonly />
					</td>
				</tr>
				<tr>
					<th>발급일</th>
					<td>
						<input type="text" name="date2" value="<?php echo $TPL_VAR["data"]["liscense"]["date2"]?>" class="datePicker" data-parent="body" readonly />
					</td>
					<th>발급일</th>
					<td>
						<input type="text" name="date2sub" value="" class="datePicker" data-parent="body" readonly />
					</td>
				</tr>
				</tbody>
			</table>
<?php }?>

			<div class="sub-title">
				<h3>결제정보</h3>
			</div>
			<table class="list" style="margin-bottom:33px">
				<colgroup>
					<col style="width:120px;">
					<col>
				</colgroup>
				<tbody>
				<tr>
					<th>결제내역</th>
					<td>
						<ul class="payment-description">
<?php if($TPL_VAR["data"]["order"]["retype"]== 2&&$TPL_VAR["data"]["order"]["extend"]!='Y'){?>
							<li>
								보증금
								<a href="/partials/help/deposit.html" class="help-tooltip openPopup" data-title="보증금" title="도움말" data-width="380"></a>
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
					</td>
				</tr>
<?php if($TPL_VAR["data"]["order"]["retype"]== 2&&($TPL_VAR["data"]["order"]["ddata1"]> 1||$TPL_VAR["data"]["order"]["ddata2"]> 0)){?>
				<tr>
					<th>결제방법</th>
					<td>
<?php if($TPL_VAR["data"]["order"]["ddata1"]> 0){?>
						<label><input type="radio" name="payType" value="2" checked /> 정기결제</label>
						<a href="/partials/help/fixed_payment.html?20170717103158" class="help-tooltip openPopup mr-10" data-title="정기결제" title="도움말" data-width="350"></a>
<?php }?>
						<label class="mr-10"><input type="radio" name="payType" value="1" <?php if($TPL_VAR["data"]["order"]["ddata1"]== 0){?>checked <?php }?>> 전액결제</label>

						<span class="text-muted" id="paymentDescription">
							<span class="month text-point"><?php echo number_format($TPL_VAR["data"]["order"]["account1"])?>원</span> X <?php echo $TPL_VAR["data"]["order"]["ddata1"]?> + <?php echo number_format($TPL_VAR["data"]["order"]["account2"])?>원 =
							<span class="all"><?php echo number_format($TPL_VAR["data"]["order"]["totalaccount"])?>원</span>
						</span>
					</td>
				</tr>
<?php }?>
<?php if(count($TPL_VAR["data"]["coupon"])> 0&&!$TPL_VAR["data"]["promotion"]){?>
				<tr>
					<th>쿠폰선택</th>
					<td>
						<select name="coupon" id="coupon" style="width:auto;">
							<option value="0" data-account="0">사용하실 쿠폰을 선택해 주세요.</option>
<?php if(is_array($TPL_R1=$TPL_VAR["data"]["coupon"])&&!empty($TPL_R1)){foreach($TPL_R1 as $TPL_V1){?>
							<option value="<?php echo $TPL_V1["idx"]?>" data-account="<?php echo $TPL_V1["account"]?><?php if($TPL_V1["actype"]== 2){?>%<?php }?>"><?php echo $TPL_V1["name"]?> (<?php if($TPL_V1["actype"]== 1){?><?php echo number_format($TPL_V1["account"])?>원<?php }else{?><?php echo $TPL_V1["account"]?>%<?php }?>)</option>
<?php }}?>
						</select>
<?php if($TPL_VAR["data"]["order"]["retype"]== 2&&($TPL_VAR["data"]["order"]["ddata1"]> 1||$TPL_VAR["data"]["order"]["ddata2"]> 0)){?>
						<span class="text-muted">* 당월 결제금액에 적용됩니다.</span>
<?php }?>
					</td>
				</tr>
<?php }?>
<?php if($TPL_VAR["data"]["promotion"]){?>
				<tr>
					<th><?php echo promotion($TPL_VAR["data"]["promotion"][ 0])?></th>
					<td><span id="discount" class="numberAnimation" data-target="0">0</span>원</td>
				</tr>
<?php }?>
				<tr>
					<th>결제금액</th>
					<td><span class="text-point"><span class="numberAnimation" id="paymentAmount"
						data-amount="<?php if($TPL_VAR["data"]["order"]["retype"]!= 2||($TPL_VAR["data"]["order"]["retype"]== 2&&$TPL_VAR["data"]["order"]["ddata1"]== 0)){?><?php echo $TPL_VAR["data"]["order"]["totalaccount"]?><?php }else{?><?php echo $TPL_VAR["data"]["order"]["account1"]?><?php }?>"
						data-month="<?php echo $TPL_VAR["data"]["order"]["account1"]?>"
						data-all="<?php echo $TPL_VAR["data"]["order"]["totalaccount"]?>"
						data-delcharge="0"
<?php if($TPL_VAR["data"]["promotion"]){?>data-promotion="<?php echo $TPL_VAR["data"]["promotion"][ 1]?>"<?php }?>
						data-target="<?php if($TPL_VAR["data"]["order"]["retype"]!= 2){?><?php echo $TPL_VAR["data"]["order"]["totalaccount"]?><?php }else{?><?php echo $TPL_VAR["data"]["order"]["account1"]?><?php }?>">0</span>원</span></td>
				</tr>
				</tbody>
			</table>

			<div class="sub-title">
				<h3>취소 수수료 규정</h3>
			</div>
			<table class="list" style="margin-bottom:20px">
				<colgroup>
					<col style="width:240px;">
					<col>
				</colgroup>
				<thead>
				<tr class="border-b">
					<th>취소구분</th>
					<th>대여구분</th>
					<th>수수료</th>
				</tr>
				</thead>
				<tbody>
				<tr>
					<th>결제 후 1시간 이내 취소</th>
					<td></td>
					<td>
						<span class="text-point">없음</span><br />
					</td>
				</tr>
				<tr>
					<th>픽업시간 24시간 이전 취소</th>
					<td></td>
					<td>
						<span class="text-point">없음</span><br />
					</td>
				</tr>
				<tr>
					<th>24시간전 부터 픽업시간까지</th>
					<td>단기대여(1개월미만)<br />장기대여(1개월이상)</td>
					<td class="text-point">결제금액의 10%<br />1개월 금액의 5%</td>
				</tr>
				<tr>
					<th>픽업시간 이후 취소</th>
					<td>단기대여(1개월미만)<br />장기대여(1개월이상)</td>
					<td class="text-point">결제금액의 20%<br />1개월 금액의 10%</td>
				</tr>
				<tr>
					<th>차량 배달 시작 후 취소</th>
					<td colspan="2" class="text-point">취소 수수료 및 환불처리는 대여지점의 내규에 따릅니다.</td>
				</tr>
				<tr>
					<th>조기반납</th>
					<td colspan="2" class="text-point">취소 수수료 및 환불처리는 대여지점의 내규에 따릅니다.</td>
				</tr>
				</tbody>
			</table>

			<div class="clause2" style="margin-bottom:5px;">
				<? include "{$_SERVER['DOCUMENT_ROOT']}/old/sites/partials/terms/transaction.html";?>
			</div>

			<div>
				<label><input type="checkbox" id="agreeCancel" data-title="취소수수료 규정"> 취소수수료 규정 동의</label><br />
				<label><input type="checkbox" id="agreeTransaction" data-title="전자금융거래 기본약관"> 전자금융거래 기본약관 동의</label><br />
			</div>

			<div class="button-box">
				<button type="submit" class="btn-point big">결제하기</button>
			</div>
		</form>
		<div id="paymentForm"></div>
	</div>
<?php $this->print_("footer",$TPL_SCP,1);?>


	<script type="text/javascript" charset="EUC-KR" src="https://tx.allatpay.com/common/NonAllatPayRE.js"></script>
	<script type="text/javascript" charset="EUC-KR" src="https://tx.allatpay.com/common/NonAllatPayREPlus.js"></script>
	<script type="text/javascript" src="/js/rentking.reservation.min.js?2018011710<?php if(!$TPL_VAR["global"]["production"]){?>_<?php echo time()?><?php }?>"></script>
</body>
</html>