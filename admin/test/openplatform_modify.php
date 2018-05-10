<?
include $_SERVER['DOCUMENT_ROOT']."/old/inc/base.php";
include $config['incPath']."/connect.php";
include $config['incPath']."/session.php";
include $config['incPath']."/config.php";
include "../admin_access.php";
include "../adminhead.php";
include "../admintop.php";

$code = $_REQUEST['code'];
?>
	<div class="leftAndMain">
		<div class="leftMenuContaner">
			<div>
			</div>
		</div>
		<div class="mainContent">
			<?
			/**
			 * 멤버사 > 지점정보 > 대표지점정보
			 * admin.rentking.co.kr/admin/rent.php?code=mod
			 * 멤버사 대표지점정보 페이지
			 */

			$ar_data = $ar_init_member;
			$bankInfo = explode('||', $ar_data['rmemo']);
			$bank = isset($bankInfo[0]) ? $bankInfo[0] : '';
			$bankaccount = isset($bankInfo[1]) ? $bankInfo[1] : '';
			$bankname = isset($bankInfo[2]) ? $bankInfo[2] : '';
			$bankholder = isset($bankInfo[3]) ? $bankInfo[3] : '';
			if ($mode == 'w') {
				$value['zipcode'] = $_REQUEST['zipcode'];
				$value['addr1'] = $_REQUEST['addr1'];
				$value['addr2'] = mysql_escape_string($_REQUEST['addr2']);
				$value['name'] = mysql_escape_string($_REQUEST['name']);
				$value['rmemo'] = $_REQUEST['bank'].'||'.$_REQUEST['bankaccount'].'||'.$_REQUEST['bankname'].'||'.$_REQUEST['bankholder'];
				update("member", $value, " WHERE idx='{$g_memidx}'");
				unset($value);

				echo "<script type='text/javascript'>alert('수정되었습니다'); location.replace('/test/openplatform_modify.php'); </script>";
				exit;
			}
			?>
			<!--161130 div class="firstTable" 추가-->
			<form name="regiform" id="regiform" action="/test/openplatform_modify.php" method="post" onsubmit="return foch('regiform');">
				<input type='hidden' name='mode' value='w'>
				<div class="row-fluid">
					<div class="span5">
						<span class="subTitle">제휴사렌트카정보</span>
						<table class="detailTable2">
							<tbody>
							<tr>
								<th>아이디</th>
								<td colspan="3">
									<?=$_SESSION['member_id'];?>
								</td>
							</tr>
							<tr>
								<th>총 차량대수</th>
								<td colspan="3"><?=number_format($ar_rshop['totalcar']);?></td>
							</tr>
							<tr>
								<th>우편번호</th>
								<td colspan="3">
									<input type="text" maxlength="5" name="zipcode" id="zipcode" valch="yes" msg="우편번호" readonly="readonly" onclick="openDaumPostcode('')" class="" value='<?=$ar_data['zipcode'];?>'>
									<button type="button" class="greenBtn_small" onclick="openDaumPostcode('')" style="vertical-align:top">우편번호 검색</button>
								</td>
							</tr>
							<tr>
								<th>주소</th>
								<td colspan="3">
									<input type="text" maxlength="200" name="addr1" id="addr1" valch="yes" msg="주소" readonly onclick="openDaumPostcode('')" class="" value='<?=$ar_data['addr1'];?>'>
								</td>
							</tr>
							<tr>
								<th>상세주소</th>
								<td colspan="3">
									<input type="text" maxlength="200" name="addr2" id="addr2" class="" value='<?=$ar_data['addr2'];?>'>
								</td>
							</tr>
							<tr>
								<th>회사명</th>
								<td colspan="3">
									<input type="text" maxlength="30" name="name" valch="yes" msg="회사명" class="" value='<?=$ar_data['name'];?>'>
								</td>
							</tr>
							<tr>
								<th>지점</th>
								<td colspan="3">
									<input type="text" maxlength="50" name="affiliate" class="" value="<?=$ar_rshop['affiliate'];?>">
								</td>
							</tr>
							<tr>
								<th>사업자등록번호</th>
								<td colspan="3"><?=$ar_rshop['businessnum'];?></td>
							</tr>
							<tr>
								<th>설립일</th>
								<td colspan="3"><?=$ar_rshop['makedate'];?></td>
							</tr>
							<tr>
								<th>은행명(은행코드)</th>
								<td>
									<select name="bank">
										<!--							<option value="001" --><?//= $ar_rshop['bank']=='001' ? 'selected' : '' ?><!-->한국은행(001)</option>-->
										<option value="002" <?= $bank=='002' ? 'selected' : '' ?>>KDB산업은행(002)</option>
										<option value="003" <?= $bank=='003' ? 'selected' : '' ?>>IBK기업은행(003)</option>
										<option value="004" <?= $bank=='004' ? 'selected' : '' ?>>KB국민은행(004)</option>
										<option value="005" <?= $bank=='005' ? 'selected' : '' ?>>외환은행(005)</option>
										<option value="006" <?= $bank=='006' ? 'selected' : '' ?>>주택은행(006)</option>
										<option value="007" <?= $bank=='007' ? 'selected' : '' ?>>수협(007)</option>
										<option value="011" <?= $bank=='011' ? 'selected' : '' ?>>농협은행(011)</option>
										<option value="020" <?= $bank=='020' ? 'selected' : '' ?>>우리은행(020)</option>
										<option value="023" <?= $bank=='023' ? 'selected' : '' ?>>SC제일은행(023)</option>
										<option value="027" <?= $bank=='027' ? 'selected' : '' ?>>한국씨티은행(027)</option>
										<option value="031" <?= $bank=='031' ? 'selected' : '' ?>>대구은행(031)</option>
										<option value="032" <?= $bank=='032' ? 'selected' : '' ?>>부산은행(032)</option>
										<option value="034" <?= $bank=='034' ? 'selected' : '' ?>>광주은행(034)</option>
										<option value="035" <?= $bank=='035' ? 'selected' : '' ?>>제주은행(035)</option>
										<option value="037" <?= $bank=='037' ? 'selected' : '' ?>>전북은행(037)</option>
										<option value="039" <?= $bank=='039' ? 'selected' : '' ?>>경남은행(039)</option>
										<option value="045" <?= $bank=='045' ? 'selected' : '' ?>>새마을금고(045)</option>
										<option value="048" <?= $bank=='048' ? 'selected' : '' ?>>신협(048)</option>
										<option value="050" <?= $bank=='050' ? 'selected' : '' ?>>상호저축은행(050)</option>
										<!--							<option value="052" --><?//= $bank=='052' ? 'selected' : '' ?><!-->모간스탠리은행(052)</option>-->
										<!--							<option value="054" --><?//= $bank=='054' ? 'selected' : '' ?><!-->HSBC은행(054)</option>-->
										<!--							<option value="055" --><?//= $bank=='055' ? 'selected' : '' ?><!-->도이치은행(055)</option>-->
										<!--							<option value="056" --><?//= $bank=='056' ? 'selected' : '' ?><!-->RBS은행(056)</option>-->
										<!--							<option value="057" --><?//= $bank=='057' ? 'selected' : '' ?><!-->JP모간체이스은행(057)</option>-->
										<!--							<option value="058" --><?//= $bank=='058' ? 'selected' : '' ?><!-->미즈호은행(058)</option>-->
										<!--							<option value="059" --><?//= $bank=='059' ? 'selected' : '' ?><!-->미쓰비시은행(059)</option>-->
										<!--							<option value="060" --><?//= $bank=='060' ? 'selected' : '' ?><!-->BOA은행(060)</option>-->
										<option value="064" <?= $bank=='064' ? 'selected' : '' ?>>산림조합(064)</option>
										<option value="071" <?= $bank=='071' ? 'selected' : '' ?>>우체국(071)</option>
										<option value="081" <?= $bank=='081' ? 'selected' : '' ?>>KEB하나은행(081)</option>
										<option value="088" <?= $bank=='088' ? 'selected' : '' ?>>신한은행(088)</option>
									</select>
								</td>
							</tr>
							<tr>
								<th>계좌번호</th>
								<td>
									<input type="text" maxlength="50" name="bankaccount" class="" value='<?=$bankaccount;?>'>
								</td>
							</tr>
							<tr>
								<th>예금주</th>
								<td><input type="text" maxlength="50" name="bankname" class="" value='<?=$bankname;?>'>
								</td>
							</tr>
							<tr>
								<th>은행계좌 인증정보</th>
								<td>
									<input type="text" maxlength="50" name="bankholder" class="" value='<?=$bankholder;?>'><br />
									예금주의 사업자 등록번호 10자리 혹은<br />
									생년월일 6자리 + 주민등록번호 7번째 1자리
								</td>
							</tr>
							<tr>
								<th>App/Web 수수료</th>
								<td>11 %</td>
							</tr>
							<tr>
								<th>NET 수수료</th>
								<td>0 %</td>
							</tr>
							</tbody>
						</table>
					</div>
				</div>
				<div class="btn_wrap btn_center btn_bottom">
					<span class="greenBtn btn_submit" data-form="#regiform"><a href="javascript:">수정</a></span>
				</div>
			</form>

			<script src='<?=$config['daumPostcode']?>'></script>
			<script type="text/javascript">
				function foch(f) {
					var re = check_form(f);
					if (re) {
						var $affiliate = $('input[name="affiliate"]');
						var affiliate = $affiliate.val();
						if (affiliate.split(' ').length > 1) {
							alert('지점은 한 단어로 입력해야합니다. (~~역, ~~동 등)');
							$affiliate.focus();
							return false;
						}

						var exit = affiliate.match(/[0-9]+번/gi);
						if (exit) {
							alert('"' + exit[0] + '"은 입력할 수 없습니다.');
							$affiliate.focus();
							return false;
						}

						var bankAccount = $('input[name=bankaccount]').val();
						var bankName = $('input[name=bankname]').val();
						var bankHolder = $('input[name=bankholder]').val();
						if ( ((bankAccount != '' || bankName != '' || bankHolder != '') && (bankAccount == '' || bankName == '' || bankHolder == ''))
							|| ( (bankAccount == '' || bankName == '' || bankHolder == '') && (bankAccount != '' || bankName != '' || bankHolder != ''))) {
							alert('계좌번호, 예금주, 은행계좌 인증정보는 모두 입력하거나 모두 입력하지 않아야 합니다.');
							return false;
						}

						return confirm('수정 하시겠습니까?');
					} else {
						return false;
					}
				}

				function check_email(tstr1) {

					if (!(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(tstr1))) {
						return false;
					}
					else {
						return true;
					}
				}

				$(function() {
					$(document)
						.on('change', '.selectLoca1', function() {
							var $select = $(this);
							var key = $select.find(':selected').val();

							var html = '<option value="">지역선택</option>';
							if (key == '') {
								$select.next('select.selectLoca2').html(html);
								return;
							}
							$.getJSON("/mo/proajax.php?modtype=common_addr&han=get_addr&code=" + key, function (result) {
								if (result['res'] == 'ok') {
									html += '<option value="A">전체</option>';
									$(result.data).each(function (index, item) {
										html += '<option value="' + item.ac_code + '">' + item.ac_name + '</option>';
									});

									$select.next('select.selectLoca2').html(html);
								}
								else {
									alert(result.resmsg);
								}
							});
						})
						.on('change', '.selectLoca2', function() {
							var $selectLoca2 = $(this);
							var $selectLoca1 = $selectLoca2.siblings('.selectLoca1');
							var $listLoca = $selectLoca2.siblings('.listLoca');

							var loca1 = $selectLoca1.val();
							var loca2 = $selectLoca2.val();
							if(loca1 != '' && loca2 != '') {
								var loca1Text = $selectLoca1.find(':selected').text();
								var loca2Text = $selectLoca2.find(':selected').text();

								if($listLoca.find('li[data-c="' + loca1 + loca2 + '"]').length < 1) {
									var code = $listLoca.data('code');
									$('<li style="display:inline-block;white-space:nowrap;" data-c="' + loca1 + loca2 + '">' +
										'<a href="deleteLoca" class="deleteLoca">[' + loca1Text + '/' + loca2Text + ' X]</a>' +
										'<input type="hidden" name="ar_loca' + code + '_1[]" value="' + loca1 +'" />' +
										'<input type="hidden" name="ar_loca' + code + '_2[]" value="' + loca2 +'" />' +
										'</li>').appendTo($listLoca);
								}
							}
						})
						.on('click', '.deleteLoca', function(e) {
							e.preventDefault();
							$(this).parents('li').remove();
						})
						.on('click', '.deleteOff', function(e) {
							e.preventDefault();

							$(this).parents('li').remove();
						})
						.on('click', '.addOff', function(e) {
							e.preventDefault();

							var sdate = $('#sdateOff').val();
							var edate = $('#edateOff').val();

							if(sdate == '' || edate == '') {
								alert('시작일과 종료일을 입력하세요');
								return false;
							}

							if(sdate > edate) {
								alert('시작일이 종료일 이전입니다.');
								return false;
							}

							$('<li>' +
								'<a href="#deleteOff" class="deleteOff">[' + sdate + '~' + edate +' X]</a>' +
								'<input type="hidden" name="rentshopOff[]" value="' + sdate + '~' + edate +'" />' +
								'</li>').appendTo('ul.listOff');
						})
						.on('click', '.deleteLimit', function(e) {
							e.preventDefault();

							$(this).parents('li').remove();
						})
						.on('click', '.addLimit', function(e) {
							e.preventDefault();

							var sdate = $('#sdateLimit').val();
							var edate = $('#edateLimit').val();
							var hour =  $('#hourLimit').val();

							if(sdate == '' || edate == '') {
								alert('시작일과 종료일을 입력하세요');
								return false;
							}

							if(sdate > edate) {
								alert('시작일이 종료일 이전입니다.');
								return false;
							}

							$('<li>' +
								'<a href="#deleteOff" class="deleteLimit">[' + sdate + '~' + edate + '(' + hour + ') X]</a>' +
								'<input type="hidden" name="reservationLimit[]" value="' + sdate + '~' + edate + '(' + hour + ')" />' +
								'</li>').appendTo('ul.listLimit');
						})
					;
				});
			</script>
		</div><!-- mainContent -->
	</div>
<?
include "../adminfoot.php";
?>
