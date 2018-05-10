/**
  * Date: 2017-03-17
 */

$(function() {
	function calculatePrice() {
		var $priceTable = $('table#priceTable');
		var $priceLongtermTable = $('table#priceLongtermTable');
		var $priceManaged = $('input[name="price_managed"]');
		var $priceLongtermManaged = $('input[name="price_longterm_managed"]');
		var $priceHourRate = $('input#priceHourRate');

		if($priceTable.length > 0) {
			var discount2 = parseFloat($('input#discount2').val().replace(/,/g, ''));
			var discount3 = parseFloat($('input#discount3').val().replace(/,/g, ''));
			var discount4 = parseFloat($('input#discount4').val().replace(/,/g, ''));
			var price = parseFloat($('input#price').val().replace(/,/g, ''));
			var priceHoliday = parseFloat($('input#priceHoliday').val().replace(/,/g, ''));
			var priceHourRate = parseFloat($priceHourRate.val().replace(/,/g, ''));

			if(isNaN(discount2))		discount2 = 0;
			if(isNaN(discount3))		discount3 = 0;
			if(isNaN(discount4))		discount4 = 0;
			if(isNaN(price))			price = 0;
			if(isNaN(priceHoliday))		priceHoliday = 0;
			if(isNaN(priceHourRate)) {
				$priceHourRate.val(12);
				priceHourRate = 12
			}

			$('#price2').text((price * discount2 / 100).numberFormat(0));
			$('#price3').text((price * discount3 / 100).numberFormat(0));
			$('#price4').text((price * discount4 / 100).numberFormat(0));

			if(priceHourRate > 0) {
				$('#priceHour').text((price / priceHourRate).numberFormat(0));
			} else {
				$('#priceHour').text(0);
			}

			$('#priceHoliday2').text((priceHoliday * discount2 / 100).numberFormat(0));
			$('#priceHoliday3').text((priceHoliday * discount3 / 100).numberFormat(0));
			$('#priceHoliday4').text((priceHoliday * discount4 / 100).numberFormat(0));

			$('#priceExample1').text((Math.floor(price / 100) * 100).numberFormat(0));
			$('#priceExample2').text((Math.floor((price > 0 ? price + price / priceHourRate * 11 : 0) / 100) * 100).numberFormat(0));
			$('#priceExample3').text((Math.floor((price * 2) / 100) * 100).numberFormat(0));
			$('#priceExample4').text((Math.floor((price * 2) / 100) * 100).numberFormat(0));
			$('#priceExample5').text((Math.floor((price * 3 * discount2 / 100) / 100) * 100).numberFormat(0));

			if($priceManaged.length > 0 && $priceManaged.val() == 'Y') {
				$priceTable.find('input[type="text"]').attr('readonly', true);
			} else {
				$priceTable.find('input[type="text"]').attr('readonly', false);
			}
		}

		if($priceLongtermTable.length > 0) {
			var priceLongterm1 = parseFloat($('input#priceLongterm1').val().replace(/,/g, ''));
			var priceLongterm2 = parseFloat($('input#priceLongterm2').val().replace(/,/g, ''));
			var priceLongterm3 = parseFloat($('input#priceLongterm3').val().replace(/,/g, ''));

			if(isNaN(priceLongterm1))		priceLongterm1 = 0;
			if(isNaN(priceLongterm2))		priceLongterm2 = 0;
			if(isNaN(priceLongterm3))		priceLongterm3 = 0;

			$('#priceExampleLongterm1').text((priceLongterm1 * 3).numberFormat(0));
			$('#priceExampleLongterm2').text((priceLongterm1 * 3 + priceLongterm1 / 30 * 15).numberFormat(0));

			if($priceLongtermManaged.length > 0 && $priceLongtermManaged.val() == 'Y') {
				$priceLongtermTable.find('input[type="text"]').attr('readonly', true);
			} else {
				$priceLongtermTable.find('input[type="text"]').attr('readonly', false);
			}
		}

	}
	window.calculatePrice = calculatePrice;

	calculatePrice();

	$(document)
		.on('keyup', '#priceTable input, #priceLongtermTable input', function(e) {
			calculatePrice();
		})
		.on('change', '#priceIdx', function() {
			var $select = $(this);
			var priceIdx = $select.val();
			var $priceTable = $('table#priceTable');

			if(priceIdx != -1) {
				$.getJSON('/mo/proajax.php?modtype=car_common&han=getprice&idx='+priceIdx, function(data) {
					$('input[name="price_managed"]').val(data['managed']);

					for(var field in data) {
						var $input = $priceTable.find('input[name="'+field+'"]');
						if($input.length > 0) {
							if($input.attr('type') == 'text') {
								if(field != 'price_discount2' && field != 'price_discount3' && field != 'price_discount4' && field != 'price_net') {
									$input.val(data[field]);
								} else {
									$input.val(data[field] * 100);
								}
							} else if($input.attr('type') == 'checkbox') {
								$input[0].checked = data[field] == 'Y'
							}

						}
					}

					$priceTable.find('.inputNumber').trigger('format');

					calculatePrice();
				});
			} else {
				$('input[name="price_managed"]').val('N');
				calculatePrice();
			}
		})
		.on('change', '#priceLongtermIdx', function() {
			var $select = $(this);
			var priceIdx = $select.val();
			var $priceTable = $('table#priceLongtermTable');

			if(priceIdx != -1) {
				$.getJSON('/mo/proajax.php?modtype=car_common&han=getpricelongterm&idx='+priceIdx, function(data) {
					$('input[name="price_longterm_managed"]').val(data['managed']);

					for(var field in data) {
						var $input = $priceTable.find('input[name="'+field+'"]');
						if($input.length > 0) {
							if($input.attr('type') == 'text') {
								if(field != 'price_longterm_net') {
									$input.val(data[field]);
								} else {
									$input.val(data[field] * 100);
								}
							} else if($input.attr('type') == 'checkbox') {
								$input[0].checked = data[field] == 'Y'
							}

						}
					}

					$priceTable.find('.inputNumber').trigger('format');

					calculatePrice();
				});
			} else {
				$('input[name="price_longterm_managed"]').val('N');
				calculatePrice();
			}
		})
});

function checkPriceTable() {
	var $form = $('table#priceTable');

	var $discount2 = $form.find('input#discount2');
	var $discount3 = $form.find('input#discount3');
	var $discount4 = $form.find('input#discount4');
	var $priceHourRate = $form.find('input#priceHourRate');
	var $priceNet = $form.find('input#priceNet');

	if(parseFloat($discount2.val().replace(/,/g, '')) > 100) {
		alert('비율은 100보다 클 수 없습니다.');
		$discount2.focus();
		return false;
	}

	if(parseFloat($discount3.val().replace(/,/g, '')) > 100) {
		alert('비율은 100보다 클 수 없습니다.');
		$discount3.focus();
		return false;
	}

	if(parseFloat($discount4.val().replace(/,/g, '')) > 100) {
		alert('비율은 100보다 클 수 없습니다.');
		$discount4.focus();
		return false;
	}

	if(parseInt($priceHourRate.val().replace(/,/g, '')) < 1) {
		alert('시간요금비율은 1보다 작을 수 없습니다.');
		$priceHourRate.focus();
		return false;
	}

	if(parseFloat($priceNet.val().replace(/,/g, '')) > 100) {
		alert('비율은 100보다 클 수 없습니다.');
		$priceNet.focus();
		return false;
	}

	return true;
}

function checkPriceLongtermTable() {
	var $form = $('table#priceLongtermTable');

	var $priceLongtermNet = $form.find('input#priceLongtermNet');

	if(parseFloat($priceLongtermNet.val().replace(/,/g, '')) > 100) {
		alert('비율은 100보다 클 수 없습니다.');
		$priceLongtermNet.focus();
		return false;
	}

	return true;
}