/**
 * rentking admin ui script
 */

 function MM_openBrWindow(theURL, winName, features) { //v2.0
	window.open(theURL, winName, features);
}

function check_id() {
	if ($("#id").val() == '') {
		alert('아이디를 입력하세요');
		return;
	}
	var param = "id=" + $("#id").val();
	$.getJSON('/mo/proajax.php?modtype=mem_common&han=idcheck&' + param, function (result) {
		if (result.res == 'ok') {
			alert('사용가능한 아이디입니다');
			$("#idcheck").val('Y');
		}
		else {
			alert('중복된 아이디 입니다. 다른아이디를 입력해주세요');
		}
	});
}

function check_form(f) {
	var form = $("#" + f);
	var isok = true;
	form.find('input[type=text],input[type=checkbox],select,input[type=password],input[type=hidden]').each(function (key) {
		var obj = $(this);
		if (obj.attr('valch') == 'yes' && obj.css('display') != 'none') {

			if (obj.prop('tagName') == 'SELECT') {
				if (obj.find(':selected').val() == '') {
					alert(obj.attr('msg') + ' 선택하세요');
					obj.focus();
					isok = false;
					return false;
				}
			}
			else if (obj.attr('type') == 'checkbox') {
				if (!obj.is(':checked')) {
					alert(obj.attr('msg') + ' 에 체크하세요');
					obj.focus();
					isok = false;
					return false;
				}

			}
			else if (obj.attr('type') == 'hidden') {
				if (obj.val() == '') {
					alert(obj.attr('msg') + '을 입력하세요');
					isok = false;
					return false;
				}

			}
			else {
				if (obj.val() == '') {
					alert(obj.attr('msg') + ' 입력하세요');
					obj.focus();
					isok = false;
					return false;
				}
			}
		}
	});
	return isok;
}

function openDaumPostcode(mo) {
	new daum.Postcode({
		oncomplete: function (data) {


			// 신 우편번호 검색 추가 2016-04-27 dongs.
			document.getElementById('zipcode' + mo).value = data.zonecode;


			var fullAddr = ''; // 최종 주소 변수
			var extraAddr = ''; // 조합형 주소 변수

			// 사용자가 선택한 주소 타입에 따라 해당 주소 값을 가져온다.
			if (data.userSelectedType === 'R') { // 사용자가 도로명 주소를 선택했을 경우
				fullAddr = data.roadAddress;

			} else { // 사용자가 지번 주소를 선택했을 경우(J)
				fullAddr = data.jibunAddress;
			}

			// 사용자가 선택한 주소가 도로명 타입일때 조합한다.
			if (data.userSelectedType === 'R') {
				//법정동명이 있을 경우 추가한다.
				if (data.bname !== '') {
					extraAddr += data.bname;
				}
				// 건물명이 있을 경우 추가한다.
				if (data.buildingName !== '') {
					extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
				}
				// 조합형주소의 유무에 따라 양쪽에 괄호를 추가하여 최종 주소를 만든다.
				fullAddr += (extraAddr !== '' ? ' (' + extraAddr + ')' : '');
			}

			// 우편번호와 주소 정보를 해당 필드에 넣는다.


			document.getElementById('addr1' + mo).value = fullAddr;
			document.getElementById('addr2' + mo).focus();
		}
	}).open();
}

function delok(msg, url) {
	if (confirm(msg)) {
		location.href = url;
	}

	return false;
}

function sms_reg() {
	MM_openBrWindow('./popup/sms.php', 'sms', 'scrollbars=yes,width=800,height=700,top=0,left=0');
}


$(function() {
	/**
	 * Format Number
	 * @param precision
	 * @param comma
	 * @param currency
	 * @returns {string}
	 */
	Number.prototype.numberFormat = function(precision, comma, currency){
		var n = this,
			c = isNaN(precision = Math.abs(precision)) ? 2 : precision,
			d = comma == undefined ? "." : comma,
			t = currency == undefined ? "," : currency,
			s = n < 0 ? "-" : "",
			i = String(parseInt(n = Math.abs(Number(n) || 0).toFixed(c))),
			j = (j = i.length) > 3 ? j % 3 : 0;
		return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
	};

	/**
	 * number animation
	 */
	(function() {
		var numberAnimationOption = {
			maxTime: 800,
			interval: 30
		};
		$(document)
			.on('animate', '.numberAnimation', function(e) {
				e.stopPropagation();

				var $this = $(this);
				var target = $this.data('target');
				if(typeof target !== 'number')
					target = parseInt(target);
				var timeout = $this.data('timeout');
				if(typeof target !== 'undefined') {
					if(typeof timeout !== 'undefined') {
						clearTimeout(timeout);
					}

					target = parseInt(target);
					var delay = $this.data('delay');
					var current = $(this).data('current');
					if(typeof current === 'undefined')
						current = 0;
					else
						current = parseInt(current);
					var gap = Math.ceil((target - current) / (numberAnimationOption.maxTime / numberAnimationOption.interval));
					var next = function() {
						if(current != target) {
							current += gap;

							if(
								(gap > 0 && current > target)
								|| (gap < 0 && current < target)
							)
								current = target;

							$this.text(current.numberFormat(0)).data('current', current);

							$this.data('timeout', setTimeout(next, numberAnimationOption.interval));
						}
					};

					if(typeof delay === 'undefined')
						next();
					else
						$this.data('timeout', setTimeout(next, delay));
				}
			});
		$('.numberAnimation.numberAnimationAutoStart').trigger('animate');
	})();

	/**
	 * input number
	 */
	(function() {
		$(document)
			.on('format', '.inputNumber', function(e) {
				e.stopPropagation();

				var $input = $(this);
				if($input.val().match(/^[-0-9,\.]*$/)) {
					var n = Number($input.val().replace(/,/g, ''));
					if(n % 1 == 0) {
						$input.val(n.numberFormat(0));
					} else {
						var precision = (n + "").split(".")[1].length;
						if(precision > 4) precision = 4;

						$input.val(n.numberFormat(precision));
					}
				}
			})
			.on('input', '.inputNumber', function(e) {
				this.value = this.value.replace(/[^-,\.0-9]/g,'');
			})
			.on('keydown', '.inputNumber', function(e) {
				var keyCode = e.keyCode;
				if(
					!(keyCode >= 48 && keyCode <= 57)		//0-9
					&& !(keyCode >= 96 && keyCode <= 105)	//0-9
					&& keyCode != 8							//backspace
					&& keyCode != 9							//tab
					&& keyCode != 35						//end
					&& keyCode != 36						//home
					&& !(keyCode >= 37 && keyCode <= 40)	//arrow
					&& keyCode != 46						//delete
					&& keyCode != 110 && keyCode != 190		//.
				) {
					e.preventDefault();
					return false;
				}
			})
			.on('keyup', '.inputNumber', function(e) {
				var $input = $(this);
				var keyCode = e.keyCode;
				if(!this.readOnly
					&& !(keyCode >= 37 && keyCode <= 40)	//arrow
					&& keyCode != 110 && keyCode != 190		//.
					&& keyCode != 9							//tab
				) {
					$input.trigger('format');
				}
			})
		;

		$('.inputNumber').trigger('format');
	})();


	window.defaultChartOption = {
		theme: 'none',
		language: 'ko',
		startDuration: 0.5,
		startEffect: 'easeOutSine',
		balloonColor: '#DD1A1A',
		fontFamily: 'Nanum Gothic, sans-serif',
		fontSize: 11,
		colors: [
			"#103E8A",
			"#517DAE",

			"#FF6600",
			"#FFA524",

			"#B0DE09",
			"#CD0D74",
			"#CC0000",
			"#00CC00",
			"#DDDDDD",
			"#999999",
			"#333333",
			"#990000"
		],
		titles: {size: 15},
		legend: {
			enabled: true,
			useGraphSettings: true,
			position: 'absolute',
			top: -6,
			valueWidth: 0,
			valueTextRegular: ''
		},
		categoryAxis: {
			minorGridEnabled: true
		},
		valueAxes: {
			axisAlpha: 0.3,
			gridAlpha: 0.2,
			labelFunction: function(value) {
				var fixed = 0;
				if(value >= 100000000) {
					fixed = value % 100000000 == 0 ? 0 : 2;
					return (value / 100000000).numberFormat(fixed) + '억';
				} else if (value >= 10000) {
					fixed = value % 10000 == 0 ? 0 : 2;
					return (value / 10000).numberFormat(fixed) + '만';
				} else {
					return value.numberFormat(0);
				}
			}
		},
		chartCursor: {
			enabled:true,
			cursorColor: '#DD1A1A',
			fullWidth: true,
			cursorAlpha: 0.1
		},
		chartScrollbar: {
			enabled: false,
			oppositeAxis:false,
			offset:30,
			scrollbarHeight: 16,
			dragIconHeight: 30,
			dragIconWidth: 30,
			backgroundAlpha: 0.2,
			backgroundColor: '#517DAE',
			selectedBackgroundAlpha: 0.2,
			selectedBackgroundColor: '#103E8A',
			color:'#333'
		},
		pulledField: 'pulled',
		colorField: 'color',
		defaultGuide: {
			boldLabel: true,
			fontSize: 11,
			color: '#DD1A1A',
			lineColor: '#DD1A1A',
			lineAlpha: 1
		},
		export: {
			enabled: true,
			menu: [
				{
					'class': 'export-main',
					menu: [
						{label: '엑셀', format:'XLSX'},
						{label: '이미지', format:'JPG'},
						{label: '인쇄', format: 'PRINT'}
					]
				}
			],
			beforeCapture: function() {
				var chart = this.setup.chart;

				var enabled = chart.chartScrollbar.enabled;
				if(enabled) {
					chart.scrollbarEnabled = true;
					chart.chartScrollbar.enabled = false;
					chart.validateNow(false, false);
				}
			},
			afterCapture: function() {
				var chart = this.setup.chart;

				setTimeout(function() {
					if(chart.scrollbarEnabled) {
						chart.chartScrollbar.enabled = true;
						chart.validateNow(false, false);
					}
				}, 500);
			}
		},
		listeners: [
			{
				event: 'drawn',
				method: function(e) {
					if(e.chart.amLink) $(e.chart.amLink).remove();
				}
			}
		]
	};

	$(document)
		.on('click', '.btn_submit', function(e) {
			e.preventDefault();
			$($(this).data('form')).submit();
		});


	/**
	 * ui change car
	 */
	var uiChangeCarTimeout = null;
	var uiChangeCarSearchTimeout = null;
	$(document)
		.on('click', '.uiChangeCar a.changeCar', function(e) {
			e.preventDefault();

			var $link = $(this);
			$link.parents('.uiChangeCar').trigger('clearSearchList');
			$link.hide().next('.searchCar').show().find('input').val('').focus();
		})
		.on('click', '.uiChangeCar .autocomplete a', function(e) {
			e.preventDefault();

			var $link = $(this);
			var $uiChangeCar = $link.parents('.uiChangeCar');
			var idx = $link.attr('href').split('#')[1];
			var reservationIdx = $uiChangeCar.data('order');

			$uiChangeCar.trigger('clearSearchList');

			$.getJSON('/mo/proajax.php?modtype=market_common&han=change_car', {reservation_idx: reservationIdx, vehicle_idx: idx}, function(result) {
				if(result['res']) {
					$('.changeCar.rentshop').text(result['rentshop']);
					$('.changeCar.modelname').text(result['modelname'] + ' (' + result['fuel_name'] + ')');
					$('.changeCar.carnum').text(result['carnum']);
					$('.changeCar.outdate').text(result['outdate']);
					$('.changeCar.opt').text(result['opt']);
					$('.changeCar.insu').text(result['insu']);
				} else {
					alert('차량 변경 실패')
				}
			})
			.error(function() {
				alert('차량 변경 실패');
			});
		})
		.on('blur', '.uiChangeCar .searchCar input', function(e) {
			clearTimeout(uiChangeCarTimeout);
			var $searchCar = $(this).parent();
			uiChangeCarTimeout = setTimeout(function() {
				$searchCar.hide().siblings('a.changeCar').show();
			}, 1000);
		})
		.on('keyup', '.uiChangeCar .searchCar input', function(e) {
			var $input = $(this);
			var carnum = $.trim($input.val());
			var $uiChangeCar = $input.parents('.uiChangeCar');

			$uiChangeCar.trigger('clearSearchList');
			clearTimeout(uiChangeCarSearchTimeout);

			if(carnum.length > 2) {
				uiChangeCarSearchTimeout = setTimeout(function() {
					$uiChangeCar.trigger('searchList');
				}, 500);
			}
		})
		.on('clearSearchList', '.uiChangeCar', function(e) {
			e.stopPropagation();

			$(this).find('ul li').remove();
		})
		.on('searchList', '.uiChangeCar', function(e) {
			e.stopPropagation();
			var $uiChangeCar = $(this);
			var carnum = $.trim($uiChangeCar.find('input').val());
			var sdate = $uiChangeCar.data('sdate');
			var edate = $uiChangeCar.data('edate');
			var rentshop = $uiChangeCar.data('rentshop');
			var $autocomplete = $uiChangeCar.find('.autocomplete');

			var html = '';
			$.getJSON('/mo/proajax.php?modtype=car_common&han=getcarbycarnum', {carnum: carnum, sdate: sdate, edate: edate, rentshop: rentshop}, function(data) {
				if(typeof data === 'object') {
					if(data.length < 1) {
						html = '<li>변경 가능한 차량이 없습니다.</li>';
					} else {
						for(var i = 0; i < data.length; i++) {
							html += '<li><a href="#' + data[i].idx + '">' + data[i].carnum + ' - ' + data[i].rentshop + '</a></li>'
						}
					}
					
					$autocomplete.html(html);
				} else {
					alert('차량을 불러올 수 없습니다.');
				}
			})
			.error(function() {
				alert('차량을 불러올 수 없습니다.');
			});
		});
});
