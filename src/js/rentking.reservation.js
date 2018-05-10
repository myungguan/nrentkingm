$(function() {
	var $paymentDescription = $('#paymentDescription');
	var $paymentAmount = $('#paymentAmount');
	var $coupon = $('#coupon');
	var $reservationForm = $('#reservationForm');
	var $paymentForm = $('#paymentForm');
	var $totalPrice = $('#totalPrice');

	$(document)
		.on('calc', '#paymentAmount', function(e) {
			e.stopPropagation();

			$totalPrice.trigger('animate');

			var target = parseInt($paymentAmount.data('amount')) + parseInt($paymentAmount.data('delcharge'));

			var coupon = $paymentAmount.data('coupon');
			if(typeof coupon !== 'undefined' && coupon != 0) {
				var couponPercent = false;

				if(('' + coupon).indexOf('%') > -1) {
					couponPercent = true;
				}

				var couponDiscountAmount = parseFloat(coupon);

				if(couponPercent) {
					couponDiscountAmount = Math.floor(target * couponDiscountAmount / 10000) * 100;
				}

				target -= couponDiscountAmount;
			}


			var promotion = $paymentAmount.data('promotion');
			if(typeof promotion !== 'undefined' && promotion != 0) {
				var promotionPercent = false;

				if(('' + promotion).indexOf('%') > -1) {
					promotionPercent = true;
				}
				var promotionDiscountAmount = parseFloat(promotion);

				if(promotionPercent) {
					promotionDiscountAmount = Math.floor(target * promotionDiscountAmount / 10000) * 100;
				}
				target -= promotionDiscountAmount;

				$('#discount').data('target', promotionDiscountAmount).trigger('animate');
			}

			if(target < 0) {
				//TODO: alert 띄울지 운영팀과 협의
				//alert('쿠폰 차감금액이 결제금액을 초과합니다.\n초과되는 금액은 예약과 동시에 쿠폰과 함께 소멸됩니다.');
			}

			$paymentAmount.data('target', target < 0 ? 0 : target).trigger('animate');
		})
		.on('change', 'input[name="payType"]', function(e) {
			var val = $(this).val();

			$paymentDescription.find('span').removeClass('text-point');
			var $buymethod = $('#buymethod');
			if(val == 1) {	//전액 결제
				$paymentAmount.data('amount', parseInt($paymentAmount.data('all')));
				$paymentDescription.find('span.all').addClass('text-point');
				$buymethod.val('C');
			} else {		//월별 결제
				$paymentAmount.data('amount', parseInt($paymentAmount.data('month')));
				$paymentDescription.find('span.month').addClass('text-point');
				$buymethod.val('F');
			}
			$paymentAmount.trigger('calc');
		})
		.on('change', 'input[name="ptype"]', function(e) {
			var val = $(this).val();

			var $carImage = $('#carImage');
			var $positionRentshop = $('#positionRentshop');
			var $positionPickup = $('#positionPickup');
			var $positionReturn = $('#positionReturn');
			var $delCharge = $('#delCharge');
			var delcharge = parseInt($('input[name="delcharge"]').val());

			if(val == 1) {
				$carImage.attr('rowspan', 5);
				$positionRentshop.hide();
				$positionPickup.show();
				$positionReturn.show();

				$delCharge.show();

				$totalPrice.data('target', parseInt($totalPrice.data('totalAccount')) + delcharge);

				$paymentAmount.data('delcharge', delcharge).trigger('calc');

			} else {
				$carImage.attr('rowspan', 4);
				$positionRentshop.show();
				$positionPickup.hide();
				$positionReturn.hide();

				$delCharge.hide();

				$totalPrice.data('target', parseInt($totalPrice.data('totalAccount')));

				$paymentAmount.data('delcharge', 0).trigger('calc');
			}
		})
		.on('change', '#coupon', function(e) {
			var amount = $coupon.find('option:selected').data('account');
			$paymentAmount.data('coupon', amount).trigger('calc');
		})
		.on('submit', '#reservationForm', function(e) {
			var $form = $(this);

			if(!$form.data('payment')) {
				e.preventDefault();

				var $agreeCancel = $('#agreeCancel');
				var $agreeTransaction = $('#agreeTransaction');
				var $agree = null;
				var $ptype = $('input[name="ptype"]');
				var buyMethod = $('#buymethod').val();
				var payType = $("input[name=payType]").val();

				if($ptype.length > 1){

					if($ptype.filter(':checked').length < 1) {
                        alert('대여방법을 선택해주세요');
                        $($ptype[0]).focus();
                        return false;
                    }

                    if($("input[name=ptype]:checked").val() == 1) {
                        var nums = $('input[name=nums]').val();
                        var date1 = $('input[name=date1]').val();
                        var date2 = $('input[name=date2]').val();

                        if(nums == '' || date1 == '' || date2 == ''){
                            alert('대여방법이 "배달대여"일 경우 제1운전자 면허정보는 필수 입력사항 입니다.');
                            return false;
						}else if(nums.length < 10){
                            alert('제1운전자 면허번호를 올바르게 입력해주시기 바랍니다.');
                            $('input[name=nums]').focus();
                            return false;
						}
					}
				}

				if(!$agree && !$agreeCancel[0].checked) {
					$agree = $agreeCancel;
				}

				if(!$agree && !$agreeTransaction[0].checked) {
					$agree = $agreeTransaction;
				}

				if($agree) {
					alert($agree.data('title') + '에 동의하셔야합니다.');
					$agree.focus();
					return false;
				}

                var action = $reservationForm.attr('action');
                if($paymentAmount.data('current') <= 0) {	//결제금액이 0원
                    if(buyMethod == "F" && payType == "2"){ //장기(정기결제) 일경우
						alert('쿠폰등의 할인을 통한 결제금액이 0원일 경우,\n결제 방법은 \"전액결제\"로만 진행 가능합니다.');
						return false;
                    }else{
						if(!confirm('결제금액이 0원이므로, 결제정보 입력 절차 없이 바로 예약이 진행됩니다.\n\n예약을 진행하시겠습니까?')) {
                            return false;
                        }
                        action = '/payment/allat_payment.php';
					}
                }
				var running = $form.data('running');
				if(!running) {
					// $form.data('running', true);
					// var buymethod = $('#buymethod').val();

					$.post(action, $reservationForm.serializeArray())
						.done(function(html){
							$paymentForm.html(html);
						});
				}
			}
		})

	$paymentAmount.trigger('calc');
});