/**
 * rentking ui script
 */

var isall = true;

function openDaumPostcode() {
	new daum.Postcode({
		oncomplete: function (data) {
			document.getElementById('zip').value = data['zonecode'];

			var fullAddr = ''; // 최종 주소 변수
			var extraAddr = ''; // 조합형 주소 변수

			// 사용자가 선택한 주소 타입에 따라 해당 주소 값을 가져온다.
			if (data['userSelectedType'] === 'R') { // 사용자가 도로명 주소를 선택했을 경우
				fullAddr = data['roadAddress'];

			} else { // 사용자가 지번 주소를 선택했을 경우(J)
				fullAddr = data['jibunAddress'];
			}

			// 사용자가 선택한 주소가 도로명 타입일때 조합한다.
			if (data['userSelectedType'] === 'R') {
				//법정동명이 있을 경우 추가한다.
				if (data['bname'] !== '') {
					extraAddr += data['bname'];
				}
				// 건물명이 있을 경우 추가한다.
				if (data['buildingName'] !== '') {
					extraAddr += (extraAddr !== '' ? ', ' + data['buildingName'] : data['buildingName']);
				}
				// 조합형주소의 유무에 따라 양쪽에 괄호를 추가하여 최종 주소를 만든다.
				fullAddr += (extraAddr !== '' ? ' (' + extraAddr + ')' : '');
			}

			// 우편번호와 주소 정보를 해당 필드에 넣는다.


			document.getElementById('addr1').value = fullAddr;
			document.getElementById('addr2').focus();
		}
	}).open();
}

function check_form(f) {
	var form = $("#" + f);
	var isok = true;
	form.find('textarea,input[type=text],input[type=checkbox],select,input[type=password],input[type=hidden]').each(function () {
		var obj = $(this);
		if (obj.attr('valch') == 'yes' && obj.css('display') != 'none') {

			if (obj.attr('tagName') == 'SELECT') {
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
function findid(fos) {
	var form = $("#" + fos);
	var isall = true;

	form.find('input[type=text],select,input[type=password]').each(function () {
		var obj = $(this);

		if (isall && obj.attr('valch') == 'yes' && (obj.parent().css('display') != 'none' && obj.parent().parent().css('display') != 'none')) {
			if (obj.attr('tagName') == 'SELECT') {
				if (obj.find(':selected').val() == '') {
					alert(obj.attr('msg') + ' 선택하세요');
					obj.focus();
					isall = false;
				}
			}
			else {
				if (obj.val() == '') {
					alert(obj.attr('msg') + ' 입력하세요');
					obj.focus();
					isall = false;
				}

			}

		}
	});

	return isall;
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
			j = i.length > 3 ? i.length % 3 : 0;
		return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
	};

	/**
	 * get cookie
	 * @param cname	cookie name
	 * @returns {*}
	 */
	window.getCookie = function getCookie(cname) {
		var name = cname + "=";
		var decodedCookie = decodeURIComponent(document.cookie);
		var ca = decodedCookie.split(';');
		for(var i = 0; i <ca.length; i++) {
			var c = ca[i];
			while (c.charAt(0) == ' ') {
				c = c.substring(1);
			}
			if (c.indexOf(name) == 0) {
				return c.substring(name.length, c.length);
			}
		}
		return "";
	};

	/**
	 * set cookie
	 * @param cname		cookiename
	 * @param cvalue	value
	 * @param exdays	expire day
	 */
	window.setCookie = function setCookie(cname, cvalue, exdays) {
		var expires = 'expires=0';

		if(typeof exdays !== 'undefined') {
			var d = new Date();
			d.setTime(d.getTime() + (exdays*24*60*60*1000));
			expires = "expires="+ d.toUTCString();
		}

		document.cookie = cname + "=" + cvalue + "; " + expires + "; domain=rentking.co.kr; path=/";
	};

	/**
	 * check device
	 * @returns {isMobile: boolean, isIOs: boolean, isAndroid: boolean, isApp: boolean}
	 */
	window.isMobile = false;
	window.checkDevice = function checkDevice() {
		var result = {
			isMobile: false,
			isIOs: false,
			isAndroid: false,
			isApp: false
		};

		var userAgent = navigator['userAgent']||navigator['vendor']||window['opera'];
		if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(userAgent)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(userAgent.substr(0,4))) {
			result.isMobile=true;
			if(/iphone|ipad/i.test(userAgent))	result.isIOs = true;
			else								result.isAndroid = true;

			if(/rentking/i.test(userAgent))		result.isApp = true;
		}

		return result;
	};

	/**
	 *
	 */
	window.getBrowser = function() {
		var userAgent = navigator.userAgent.toLowerCase();

		if (/edge/i.test(userAgent)) {
			return 'edge';
		} else if (/rv:11/i.test(userAgent)) {
			return 'ie11';
		} else if (/msie 10/i.test(userAgent)) {
			return 'ie10';
		} else if (/msie/i.test(userAgent)) {
			return 'ie';
		} else if (/opr/i.test(userAgent)) {
			return 'opera';
		} else if (/chrome/i.test(userAgent)) {
			return 'chrome';
		} else if (/firefox/i.test(userAgent)) {
			return 'firefox';
		} else if (!!navigator.userAgent.match(/Version\/[\d\.]+.*Safari/)) {
			return 'safari';
		}

		return undefined;
	};

	/**
	 * debug
	 */
	var $debugger = $('#debugger');
	window.debug = function(log) {
		if($debugger.length > 0) {
			$debugger.append('<span>' + log + '</span>').scrollTop(100000);
		} else {
			console.debug(log);
		}
	};

	/**
	 * loading
	 */
	$.fn.extend({
		showLoading: function(callback) {
			return this.each(function() {
				var $this = $(this);
				var $loading = $this.find('.rentkingLoading');
				if($loading.length < 1) {
					$loading = $('<div class="rentkingLoading"></div>');
					$this.append($loading);
				}

				var timeout = $loading.data('timeout');
				if(typeof timeout !== 'undefined') {
					clearTimeout(timeout);
				}
				$loading.addClass('rentkingLoadingShow').data('timeout', setTimeout(function() {
					$loading.addClass('rentkingLoadingReveal');
					if(typeof callback === 'function') {
						setTimeout(function() {
							callback();
						}, 400)
					}
				}, 50));
			});
		},
		hideLoading: function(callback) {
			return this.each(function() {
				var $this = $(this);
				var $loading = $this.find('.rentkingLoading');
				if($loading.length > 0) {
					var timeout = $loading.data('timeout');
					if(typeof timeout !== 'undefined') {
						clearTimeout(timeout);
					}
					$loading.addClass('rentkingLoadingHide').data('timeout', setTimeout(function() {
						if(typeof callback === 'function') {
							callback();
						}
						$loading.removeClass('rentkingLoadingShow rentkingLoadingReveal rentkingLoadingHide');

					}, 400))
				}
			});
		}
	});

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
					if(typeof timeout !== 'undefined')
						clearTimeout(timeout);

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
	 * form
	 */
	(function() {
		function validationForm(form) {
			var $form = $(form);
			var $required = $form.find('input.validationFormRequired, textarea.validationFormRequired');
			var validation = {
				result: true,
				msg: null,
				$item: null
			};

			$required.each(function() {
				var $item = $(this);
				var val = $.trim($item.val());

				if(val.length < 1) {
					validation.result = false;
					validation.msg = $item.data('error');
					validation.$item = $item;
					return false;
				}
			});

			if(!validation.result) {
				if(validation.msg) {
					alert(validation.msg);
				}

				if(validation.$item) {
					validation.$item.focus();
				}

				return false;
			}

			return true;
		}
		$(document)
			.on('click', '.checkId', function(e) {
				e.preventDefault();

				var $id = $('#id');
				$id.val($.trim($id.val()));

				if ($id.val() == '') {
					alert('아이디를 입력하세요');
					$id.focus();
					return;
				}
				if (!(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test($id.val()))) {
					alert('아이디는 이메일형식으로 입력하셔야 합니다.');
					$id.focus();
					return;
				}


				var param = "id=" + $id.val();
				$.getJSON('/mo/proajax.php?modtype=mem_common&han=idcheck&' + param, function (result) {
					if (result['res'] == 'ok') {
						alert('사용가능한 아이디입니다');
						$("#idcheck").val('Y');
					}
					else {
						alert('중복된 아이디 입니다. 다른아이디를 입력해주세요');
						$id.focus();
					}
				});
			})
			.on('submit', 'form.validationForm', function() {
				return validationForm(this);
			});
	})();

	/**
	 * searchBar
	 */
	(function() {
		var $searchBar = $('#searchBar');
		function getNextReservationStartDate() {
			var now = moment().add(4, 'h');
			var min = now.minute();
			if(min > 0 && min < 30) {
				now.add(30 - min, 'm');
			} else {
				now.add(60 - min, 'm');
			}
			var hour = now.hour();
			min = now.minute();

			if(hour < 10) {
				now.add(10 - hour, 'h');
				if(min == 30) {
					now.subtract(30, 'm');
				}
			} else if(hour > 19) {
				now.add(24-hour + 10, 'h');
				if(min == 30) {
					now.subtract(30, 'm');
				}
			}

			return now;
		}
		$(document)
			.on('click', '.goReservation', function(e) {
				e.preventDefault();
				var $sdate = $searchBar.find('input#sdate');
				var $edate = $searchBar.find('input#edate');

				var now = getNextReservationStartDate();
				var to = now.clone().add(1, 'd');

				$sdate.val(now.format('YYYY-MM-DD HH:mm'));
				$edate.val(to.format('YYYY-MM-DD HH:mm'));
				$searchBar.submit();
			})
			.on('click', '.goLongtimeReservation', function(e) {
				e.preventDefault();
				var $sdate = $searchBar.find('input#sdate');
				var $edate = $searchBar.find('input#edate');

				var now = getNextReservationStartDate();
				var to = now.clone().add(1, 'M');

				$sdate.val(now.format('YYYY-MM-DD HH:mm'));
				$edate.val(to.format('YYYY-MM-DD HH:mm'));
				$searchBar.submit();
			})
			.on('change', 'form#searchBar select', function() {
				var $select = $(this);
				var $label = $select.siblings('label');
				$label.find('.value').text($select.find('option:selected').text());
			})
			.on('change', 'form#searchBar #ptype', function() {
				var ptype = $(this).val();

				$searchBar.removeClass('ptype1 ptype2').addClass('ptype'+ptype);
			})
		;

		$('form#searchBar select').trigger('change');
	})();

	/**
	 * slide menu
	 */
	(function() {
		$(document)
			.on('click', '.slideMenuBtn', function(e) {
				e.stopPropagation();
				e.preventDefault();

				var $btn = $(this);
				var $body = $('body');
				if($btn.hasClass('open')) {
					$body.addClass('slideOpen')
				} else {
					$body.removeClass('slideOpen')
				}
			})
			.on('touchstart', '#sidemenuDimm', function() {
				var $body = $('body');
				$body.addClass('slideClose');
				setTimeout(function() {
					$body.removeClass('slideClose slideOpen');
				}, 400);
			})
		;
	})();

	/**
	 * tooltip
	 */
	(function() {
		$('[data-toggle="tooltip"]')
			.tooltip()
			.on('shown.bs.tooltip', function() {
				var $tooltip = $('#'+$(this).attr('aria-describedby'));
				$tooltip.removeClass('out').addClass('scale');
			})
			.on('hide.bs.tooltip', function() {
				var $tooltip = $('#'+$(this).attr('aria-describedby'));
				$tooltip.addClass('out');
			})
		;
	})();

	/**
	 * 회원가입 폼
	 */
	(function() {
		$(document)
			.on('submit', '#joinform', function() {
				isall = true;
				var $form = $("#joinform");

				$form.find('input[type=text],input[type=checkbox],select,input[type=password]').each(function () {
					var obj = $(this);
					if (obj.attr('valch') == 'yes' && obj.css('display') != 'none') {
						if (obj.attr('type') == 'checkbox') {
							if (!this.checked) {
								alert(obj.attr('msg') + ' 에 체크하세요');
								obj.focus();
								isall = false;
								return false;
							}
						}
						else {
							if (obj.val() == '') {
								alert(obj.attr('msg') + ' 입력하세요');
								obj.focus();
								isall = false;
								return false;
							}
						}
						if (obj.attr('tagName') == 'SELECT') {
							if (obj.find(':selected').val() == '') {
								alert(obj.attr('msg') + ' 선택하세요');
								obj.focus();
								isall = false;
								return false;
							}
						}

						if (obj.attr('islen')) {
							var leng = obj.attr('islen');
							var bleng = getByteLength(obj.val());

							if (parseInt(bleng) < parseInt(leng)) {
								alert(obj.attr('msg') + ' 는 ' + leng + '자 이상 입력하세요');
								obj.focus();
								isall = false;
								return false;
							}
						}

					}
				});

				var $birth = $form.find('#birth');
				if($birth.val().length != 10) {
					alert('생년월일을 입력해주세요');
					$birth.focus();
					return false;
				}

				if (isall) {
					if ($("#idcheck").val() == 'N') {
						alert('아이디 중복확인을 해주세요');
						return false;
					}
					if ($("#channel").val() == '') {
						if (!(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test($("#id").val()))) {
							alert('아이디는 이메일형식으로 입력하셔야 합니다.');
							return false;
						}
					}
					if ($("#passwd").val() != $("#repasswd").val()) {
						alert('비밀번호가 맞지 않습니다');
						return false;
					}


					if ($("#cpcheck").val() != $("#returnnum").val() || $('#cpcheckcp').val() != $('#cp').val()) {
						alert('휴대폰번호와 인증번호가 맞지 않습니다');
						isall = false;
						return false;
					}

					if ($("#mem_type").val() == 'p') {
						if (!$("#woman").is(":checked") && !$("#man").is(":checked")) {
							alert('성별을 선택해 주세요');
							return false;
						}
					}

					if (confirm('회원가입하시겠습니까?')) {
						return true;
					}
				}

				return false;
			})
	})();

	/**
	 * location
	 * 현재 위치 찾기
	 */
	(function() {
		var device = checkDevice();
		var location = null;
		var locationTask = [];
		var rentkingLocation = {
			lat: 37.56076282158076,
			lng: 126.93548660635388
		};

		var addLocationTask = function(func) {
			if(location) {
				func(location);
			} else {
				locationTask.push(func);
			}
		};

		var runLocationTask = function() {
			var task = function() {
				for(var i = 0; i < locationTask.length; i++) {
					locationTask[i](location);
				}
			};

			if(!location) {
				getLocation(function(coords) {
					location = {
						lat: coords.lat,
						lng: coords.lng
					};

					task();
				});
			} else {
				task();
			}

		};

		var getLocation = function(next) {
			async.waterfall([
				function(next) {			//searchLatLng cookie 사용
					var searchLatLng = getCookie('searchLatLng');
					if(searchLatLng) {
						var latLng = searchLatLng.split(',');
						if(latLng.length == 2 && !isNaN(parseFloat(latLng[0])) && !isNaN(parseFloat(latLng[1])) ) {
							next( {
								lat: latLng[0],
								lng: latLng[1]
							})
						} else {
							next(null);
						}
					} else {
						next(null);
					}
				},
				function(next) {			//navigator.geolocation 사용
					if(navigator.geolocation) {
						debug('navigator.geolocation');

						var fail = false;
						var geolocationTimeout = setTimeout(function() {
							debug('navigator.geolocation - timeout');
							fail = true;
							next(null);
						}, 20000);

						navigator.geolocation.getCurrentPosition(function(position) {
							clearTimeout(geolocationTimeout);
							if(!fail) {
								debug('navigator.geolocation - success (lat: ' + position.coords.latitude + ', lng: ' + position.coords.longitude + ')');
								next({
									lat: position.coords.latitude,
									lng: position.coords.longitude
								});
							}
						}, function(error) {
							clearTimeout(geolocationTimeout);
							if(!fail) {
								debug('navigator.geolocation - fail(' + error + ')');
								next(null);
							}
						});
					} else {
						debug('navigator.geolocation - not defined');
						next(null);
					}
				},
				function(next) {
					if(device.isApp && device.isIOs) {
						debug('ios.getLocation');

						var fail = false;
						var getLocationTimeout = setTimeout(function() {
							debug('ios.getLocation - timeout');
							fail = true;
							next(null);
						}, 5000);

						window.setLocation = function(position) {
							clearTimeout(getLocationTimeout);
							if(!fail) {
								debug('ios.getLocation - success (lat: ' + position.coords.latitude + ', lng: ' + position.coords.longitude + ')');
								next({
									lat: position.coords.latitude,
									lng: position.coords.longitude
								});
							}
						};

						window.location = 'rentking://getLocation';
					} else {
						debug('ios.getLocation - not ios');
						next(null);
					}
				},
				function(next) {		//다음 API사용
					if(!device.isMobile && typeof t_mapx !== 'undefined') {
						var geocoder = new daum.maps.services.Geocoder();
						geocoder.transCoord(t_mapx, t_mapy, daum.maps.services.Coords.CONGNAMUL, daum.maps.services.Coords.WGS84, function(status, result) {
							if (status === daum.maps.services.Status.OK) {
								debug('transcoord - success');
								next({
									lat: result.y,
									lng: result.x
								});
							} else {
								debug('transcoord - fail');
								next(null);
							}
						})
					} else {
						next(null);
					}
				}
			], function (result) {
				var location = rentkingLocation;
				if(result) {
					location = result;
				}

				debug('location (lat: ' + location.lat + ', lng: ' + location.lng + ')');

				next(location);
			});
		};

		window.rentkingLocation = rentkingLocation;
		window.addLocationTask = addLocationTask;
		window.runLocationTask = runLocationTask;
	})();

	/**
	 * layer popup
	 */
	(function() {
		$(document)
			.on('reposition', '.pop-wrap', function(e, param) {
				e.stopPropagation();
				var $popWrap = $(this);
				var $popContent = $popWrap.find('.pop-content');
				var $popBody = $popContent.find('.pop-body');
				var winHeight = $(window).height();
				var winWidth = $(window).width();

				$popWrap.addClass('ready');

				var width = $popContent.outerWidth();
				if(width + 34 > winWidth) {
					width = winWidth - 34;
					$popContent.css({width: width});
				}

				var height = $popContent.outerHeight();
				if(height > winHeight) {
					$popBody.css({height: winHeight - 90});
					height = winHeight - 34;
				}
				setTimeout(function() {
					$popContent.css({marginLeft: -width / 2, marginTop: -height / 2, left:'50%', top:'50%'});

					if(param && typeof param['callback'] != 'undefined') {
						param['callback']();
					}
				}, 100);
			})
			.on('open', '.pop-wrap', function(e) {
				e.stopPropagation();
				var $popWrap = $(this);
				$popWrap.trigger('reposition', {callback: function() {
					$popWrap.addClass('open');
				}});
			})
			.on('close', '.pop-wrap', function(e) {
				e.stopPropagation();

				var $popWrap = $(this);
				$popWrap.addClass('close');
				setTimeout(function() {
					$popWrap.removeClass('ready open close');
				}, 300);
			})
			.on('click', '.pop-wrap', function() {
				$(this).trigger('close');
			})
			.on('click', '.pop-wrap .pop-content', function(e) {
				e.stopPropagation();
			})
			.on('click', '.pop-wrap .pop-close', function(e) {
				e.preventDefault();
				e.stopPropagation();

				$(this).parents('.pop-wrap').trigger('close');
			})
			.on('click', 'a.openPopup', function(e) {
				e.preventDefault();

				var $link = $(this);
				var url = $link.attr('href');
				var cache = $link.data('cache');
				if(typeof cache === 'undefined') {
					cache = true;
				}

				var popupIdx = $link.data('popupIdx');
				var $popWrap = !popupIdx ? null : $('#'+popupIdx);
				if(popupIdx && $popWrap.length > 0 && cache) {
					$popWrap.trigger('open');
				} else {
					$.get(url, function(html) {
						var title = $link.data('title') || $link.attr('title');
						if(!title)
							title = '렌트킹';
						var $popWrap = $('<div class="pop-wrap" id="popupPw">'
							+ '	<div class="pop-content">'
							+ '		<div class="pop-header">'
							+ '			<h3>' + title + '</h3>'
							+ '			<button class="pop-close">닫기</button>'
							+ '		</div>'
							+ '		<div class="pop-body">'
							+ html
							+ '		</div>'
							+ '	</div>'
							+ '</div>');

						var ts = (new Date()).getTime();
						$popWrap.attr('id', 'popup' + ts);
						$link.data('popupIdx', 'popup' + ts);

						var width = $link.data('width');
						if(width) {
							$popWrap.find('.pop-content').css({width:width});
						}

						var padding = $link.data('padding');
						if(typeof padding !== 'undefined') {
							$popWrap.find('.pop-body').css({padding:padding});
						}

						var $imgs = $popWrap.find('img');
						var loadedImgs = 0;

						$imgs.on('load', function() {
							loadedImgs++;
							if($imgs.length <= loadedImgs)
								$popWrap.trigger('open');
						});

						$popWrap.appendTo('body');

						if($imgs.length < 1) {
							$popWrap.trigger('open');
						}
					});
				}

			})
	})();

	/**
	 * datetimepicker
	 */
	(function() {
		var datePickerOption = {
			locale: 'ko',
			stepping: 30,
			sideBySide: true,
			ignoreReadonly: true,
			useCurrent: false,
			toolbarPlacement: 'bottom',
			showClose: true,
			icons: {
				previous: 'arrow-left',
				next: 'arrow-right',
				up: 'arrow-top',
				down: 'arrow-bottom'
			}
		};
		$(document)
			.on('init', 'input.datePicker, input.dateTimePicker', function(e) {
				e.stopPropagation();

				var $input = $(this);
				var minDate = $input.data('minDate');
				var maxDate = $input.data('maxDate');
				var minDateOrigin = $input.data('minDateOrigin');
				var maxDateOrigin = $input.data('maxDateOrigin');
				var template = $input.data('template');
				var locale = $input.data('locale');
				var fieldDate = $input.data('fieldDate');
				var fieldTime = $input.data('fieldTime');
				var minViewMode = $input.data('minViewMode');

				if(minDate) {
					if(minDateOrigin) {
						if(minDateOrigin == 'today') {
							minDate = moment().add(parseInt(minDate.replace(/([0-9]*)(.*)/, '$1')), minDate.replace(/([0-9]*)(.*)/, '$2'))
						} else {
							$(minDateOrigin).on('dp.change', function(e) {
								var minDate = $input.data('minDate');
								var md = e.date.clone().add(parseInt(minDate.replace(/([0-9]*)(.*)/, '$1')), minDate.replace(/([0-9]*)(.*)/, '$2'));
								$input.data('DateTimePicker').minDate(md);
								var date = $input.data('DateTimePicker').date();
								if(date < md) {
									$input.data('DateTimePicker').date(md);
								}

							});
							minDate = $(minDateOrigin).data('DateTimePicker').date().clone().add(parseInt(minDate.replace(/([0-9]*)(.*)/, '$1')), minDate.replace(/([0-9]*)(.*)/, '$2'));
						}
					} else {
						minDate = moment(minDate);
					}
				} else {
					minDate = false;
				}

				if(maxDate) {
					if(maxDateOrigin) {
						if(maxDateOrigin == 'today') {
							maxDate = moment().add(parseInt(maxDate.replace(/([0-9]*)(.*)/, '$1')), maxDate.replace(/([0-9]*)(.*)/, '$2'))
						} else {
							$(maxDateOrigin).on('dp.change', function(e) {
								var maxDate = $input.data('maxDate');
								var md = e.date.clone().add(parseInt(maxDate.replace(/([0-9]*)(.*)/, '$1')), maxDate.replace(/([0-9]*)(.*)/, '$2'));
								$input.data('DateTimePicker').maxDate(md);
								var date = $input.data('DateTimePicker').date();
								if(date > md) {
									$input.data('DateTimePicker').date(md);
								}
							});
							maxDate = $(maxDateOrigin).data('DateTimePicker').date().clone().add(parseInt(maxDate.replace(/([0-9]*)(.*)/, '$1')), maxDate.replace(/([0-9]*)(.*)/, '$2'));
						}
					} else {
						maxDate = moment(maxDate);
					}
				} else {
					maxDate = false;
				}

				var option = {
					format: 'YYYY-MM-DD',
					minDate: minDate,
					maxDate: maxDate
				};

				if($input.hasClass('dateTimePicker')) {
					option['format'] = 'YYYY-MM-DD HH:mm';
				}

				if(minViewMode)
					option['viewMode'] = minViewMode;

				var dayViewHeaderFormat = 'YYYY년 MMMM';
				if(locale) {
					if(locale != 'ko')
						dayViewHeaderFormat = 'YYYY MMMM';
					option['locale'] = locale;
				}

				option['dayViewHeaderFormat'] = dayViewHeaderFormat;

				if($input.data('parent')) {
					option['widgetParent'] = $input.data('parent');
				}

				$input.datetimepicker($.extend({}, datePickerOption, option));
				var addOn = '';
				if(template) {
					template = template.split(',');
					addOn = '<div class="add-on"><ul>';

					for(var t = 0; t < template.length; t++) {
						var duration = template[t].replace(/([0-9]*)(.*)/, '$1');
						var text = template[t].replace(/([0-9]*)(.*)/, '$2');
						switch(text) {
							case 'y':
								text = locale && locale != 'ko' ? ' year' : '년';
								break;
							case 'M':
								text = locale && locale != 'ko' ? ' month' : '개월';
								break;
							case 'w':
								text = locale && locale != 'ko' ? ' week' : '주일';
								break;
							case 'd':
								text = locale && locale != 'ko' ? ' day' : '일';
								break;
						}
						addOn += '<li><button type="button" data-duration="'+template[t]+'">'+duration+text+'</button></li>';
					}

					addOn += '</ul></div>';
				}

				var setDateTimeField = function() {
					if(fieldDate) {
						$(fieldDate).text($input.data('DateTimePicker').date().format('YYYY-MM-DD'));
					}

					if(fieldTime) {
						$(fieldTime).text($input.data('DateTimePicker').date().format('HH:mm'));
					}
				};

				setDateTimeField();
				$input.on('dp.show', function(e) {
					var $picker = $('.bootstrap-datetimepicker-widget');
					if($input.hasClass('dateTimePicker')) {
						$picker.find('.datepicker').removeClass('col-sm-8').addClass('col-sm-12');

						var $datePickerDays = $picker.find('.datepicker-days');
						if(!$input.data('DateTimePicker').date())
							$input.data('DateTimePicker').date(moment());
						var $timeSelector = $('<div class="time-selector"><button type="button" class="prev-time btn-normal">-</button><span class="time">' + $input.data('DateTimePicker').date().format('HH:mm') + '</span><button type="button" class="next-time btn-normal">+</button></div>');
						$timeSelector.data('input', $input);

						$datePickerDays.append($timeSelector);
					}
					$picker.data('input', $input);
					$input.data('picker', $picker);

					if(addOn) {
						var $prev = $picker.find('>.row');
						if($prev.length < 1)
							$prev = $picker.find('>ul.list-unstyled');
						$prev.after(addOn);
						$picker.find('.add-on button').data('dtp', $picker.data('dtp')).data('input', $picker.data('input'));
					}
				});
				$input.on('dp.change', function(e) {
					$(this).trigger('dateChanged');
					var $picker = $('.bootstrap-datetimepicker-widget');
					var $time = $picker.find('.time-selector .time');
					if($time.length > 0) {
						$time.text($input.data('DateTimePicker').date().format('HH:mm'));
					}

					setDateTimeField();
				});
				$input.addClass('inti');
			})
			.on('click', '.bootstrap-datetimepicker-widget .add-on button', function(e) {
				e.stopPropagation();

				var $button = $(this);
				var $input = $button.data('input');
				var duration = $button.data('duration');
				var start = $input.data('start');
				var end = $input.data('end');
				var $start = null;
				var $end = null;
				var $dateTimePicker = $input.data('DateTimePicker');

				if(start) {
					$end = $input;
					$start = $(start);
				}

				if(end) {
					$start = $input;
					$end = $(end);
				}

				if(duration && $start && $end && $start.length > 0 && $end.length > 0) {
					var date = $start.data('DateTimePicker').date().clone();
					date.add(parseInt(duration.replace(/([0-9]*)(.*)/, '$1')), duration.replace(/([0-9]*)(.*)/, '$2'));
					$end.data('DateTimePicker').date(date);
				}
				// $dateTimePicker.hide();
			})
			.on('click', '.bootstrap-datetimepicker-widget .time-selector button.prev-time', function(e) {
				var $input = $(this).parents('.time-selector').data('input');
				var dateTimePicker = $input.data('DateTimePicker');
				var date = dateTimePicker.date();
				dateTimePicker.date(date.subtract(30, 'm'));
			})
			.on('click', '.bootstrap-datetimepicker-widget .time-selector button.next-time', function(e) {
				var $input = $(this).parents('.time-selector').data('input');
				var dateTimePicker = $input.data('DateTimePicker');
				var date = dateTimePicker.date();
				dateTimePicker.date(date.add(30, 'm'));
			})
			.on('mousewheel', '.bootstrap-datetimepicker-widget .timepicker-picker table tr > td:nth-child(1)', function(e) {
				e.stopPropagation();
				e.preventDefault();

				var $picker = $(this).parents('.bootstrap-datetimepicker-widget');
				var $input = $picker.data('input');
				var dateTimePicker = $input.data('DateTimePicker');

				if(e.originalEvent.wheelDelta > 0) {
					dateTimePicker.date(dateTimePicker.date().subtract(1, 'h'));
				} else {
					dateTimePicker.date(dateTimePicker.date().add(1, 'h'));
				}
			})
			.on('mousewheel', '.bootstrap-datetimepicker-widget .timepicker-picker table tr > td:nth-child(3)', function(e) {
				e.stopPropagation();
				e.preventDefault();

				var $picker = $(this).parents('.bootstrap-datetimepicker-widget');
				var $input = $picker.data('input');
				var dateTimePicker = $input.data('DateTimePicker');
				var step = dateTimePicker.stepping();

				if(e.originalEvent.wheelDelta > 0) {
					dateTimePicker.date(dateTimePicker.date().subtract(step, 'm'));
				} else {
					dateTimePicker.date(dateTimePicker.date().add(step, 'm'));
				}
			})
			.on('click', '.datePickerRange', function(e) {
				e.preventDefault();

				var $btn = $(this);
				var $start = $($btn.data('start'));
				var $end = $($btn.data('end'));
				var duration = $btn.data('duration');

				if($start.length > 0 && $end.length > 0 && duration) {
					var startPicker = $start.data('DateTimePicker');
					var endPicker = $end.data('DateTimePicker');
					duration = duration.match(/([0-9]+)([a-zA-Z]+)/);

					var now = moment();
					endPicker.date(now);
					startPicker.date(now.subtract(parseInt(duration[1]), duration[2]).add(1, 'd'));
				}
			})
		;

		$('input.datePicker, input.dateTimePicker').trigger('init');
	})();

	/**
	 * logo
	 */
	(function() {
		var logoTime = 2000;
		var logoTimeout = null;
		var setLogo = function(init) {
			$('.logo').each(function() {
				var $this = $(this);
				if($this.find('.logo-ci').length > 0) {
					if(init)	$this.addClass('init');
					else 		$this.removeClass('init');
				}
			});
		};

		clearTimeout(logoTimeout);
		logoTimeout = setTimeout(function() {
			setLogo(true);
		}, logoTime);

		var b = getBrowser();
		if (b == 'chrome' || b == 'safari' || b == 'firefox' || b == 'opera') {
			$(window)
				.on('blur', function() {
					clearTimeout(logoTimeout);
					setLogo(false);
				})
				.on('focus', function() {
					clearTimeout(logoTimeout);
					logoTimeout = setTimeout(function() {
						setLogo(true);
					}, logoTime);
				});
		}
	})();
});
