<?
/**
 * 어드민 > 사이트관리 > 쿠폰관리 > 조회
 * admin.rentking.co.kr/admin/sho.php?code=couponm&idx=10
 * 해당 쿠폰 수정 페이지
 */
$idx = $_REQUEST['idx'];
$query = "select c.*, f.idx file_idx, f.path FROM coupons c LEFT JOIN files f ON f.article_idx = c.idx AND f.article_type='coupon' WHERE c.idx=$idx";
$ar_data = mysql_fetch_assoc(mysql_query($query));
$mode = $_REQUEST['mode'];
if ($mode == 'w') {

	if ('SE' == $_POST['type']) {
		if (1 > strlen($_POST['serialnum'])) {
			echo "<script>alert('지정시리얼번호 입력해주세요.');window.history.back()</script>";
			exit;
		}

		$ar_cnt = sel_query("coupons", "COUNT(*) AS cnt", " WHERE serialnum = '{$_POST['serialnum']}' AND type='SE' and idx!='$idx'");
		if ($ar_cnt['cnt']) {
			echo "<script>alert('지정시리얼번호가 중복시리얼번호 입니다. 변경해주세요.');window.history.back()</script>";
			exit;
		}
	}


	$value['name'] = $_POST['name'];
	$value['actype'] = $_POST['actype'];
	$value['account'] = $_POST['account'];
	$value['dt_publish_start'] = $_POST['dt_publish_start'];
	$value['dt_publish_end'] = $_POST['dt_publish_end'];
	$value['canuseac'] = $_POST['canuseac'];
	$value['dt_use_start'] = $_POST['dt_use_start'];
	$value['dt_use_end'] = $_POST['dt_use_end'];
	$value['memo'] = $_POST['memo'];
	$value['type'] = $_POST['type'];
	$value['serialnum'] = trim($_POST['serialnum']);
	$value['dt_update'] = date('Y-m-d H:i:s');

	$usesites = $_POST['usesite'];
	$usesites = serialize($usesites);

	update("coupons", $value, " where idx='$idx'");

	fileUpload('img', 'coupon', $idx, '', $ar_data['file_idx']);

	echo "<script>alert('수정완료'); location.replace('$PHP_SELF?code=$code&idx=$idx'); </script>";
	exit;
}

?>
<script language="javascript" type="text/javascript">
	function regichss() {
		if ($("#id_name").val() == '') {
			alert('쿠폰이름을 입력하세요');
			return false;
		}
		if (!$('input[id=id_actype]:checked').val()) {
			alert('쿠폰할인종류를 선택하세요');
			return false;
		}
		if ($("#id_account").val() == '' && $('input[id=id_actype]:checked').val() != '3') {
			alert('쿠폰할인금액을 입력하세요');
			return false;
		}

		if($('input[name="type"]:checked').val() == 'SE' && $('input[name="serialnum"]').val() == '') {
			alert('지정시리얼번호를 입력하세요.');
			return false;
		}

		if ($('input[name="dt_publish_start"]').val() == '' || $('input[name="dt_publish_end"]').val() == '') {
			alert('쿠폰배포기간을 입력하세요');
			return false;
		}

		if ($('input[name="dt_use_start"]').val() == '' || $('input[name="dt_use_end"]').val() == '') {
			alert('쿠폰사용기간을 입력하세요');
			return false;
		}

		if (confirm('쿠폰을 수정 하시겠습니까?')) {
			return true;
		}
		else {
			return false;
		}
	}

	$(function() {
		$(document)
			.on('click', 'input[name="type"]', function(e) {
				var type = $(this).val();

				if(type == 'SE')
					$('#serialnum').show();
				else
					$('#serialnum').hide();
			})
	})
</script>
<div class="content">

	<div class="form_wrap">

		<form id="regiform" name="regiform" action="<?= $PHP_SELF; ?>?code=<?= $code; ?>" method="post"
			  ENCTYPE="multipart/form-data" onsubmit="return regichss();">
			<input type='hidden' name='idx' value='<?= $idx; ?>'>
			<input type='hidden' name='mode' value='w'>
			<table class="detailTable2">
				<tr>
					<th>쿠폰이름</th>
					<td>
						<input type='text' name='name' id="id_name" size='30'
							   value="<?= $ar_data['name']; ?>">
					</td>
				</tr>
				<tr>
					<th>쿠폰이미지</th>
					<td><input type='file' name='img'></td>
				</tr>
				<tr>
					<th>쿠폰할인종류</th>
					<td><input type='radio' name='actype' id="id_actype" value='1' <? if ($ar_data['actype'] == '1') {
							echo "checked";
						} ?>>금액할인
						<input type='radio' name='actype' id="id_actype" value='2' <? if ($ar_data['actype'] == '2') {
							echo "checked";
						} ?>>%할인
					</td>
				</tr>
				<tr>
					<th>쿠폰할인금액</th>
					<td><input type='text' name='account' id="id_account" size='15' value="<?= $ar_data['account']; ?>">
					</td>
				</tr>
				<tr>
					<th>쿠폰타입</th>
					<td>
						<input type="radio" name="type" value="LO" <?=$ar_data['type'] == 'LO' ? 'checked' : '' ?> />로그인
						<input type="radio" name="type" value="SI" <?=$ar_data['type'] == 'SI' ? 'checked' : '' ?> />회원가입
						<input type="radio" name="type" value="SE" <?=$ar_data['type'] == 'SE' ? 'checked' : '' ?> />시리얼
						<input type="radio" name="type" value="RS" <?=$ar_data['type'] == 'RS' ? 'checked' : '' ?> />랜덤시리얼
					</td>
				</tr>

				<tr style='<?if($ar_data['type'] != 'SE') {?>display:none;<?}?>' id='serialnum'>
					<th>지정시리얼번호</th>
					<td>
						<input type='text' name='serialnum' id="id_serialnum" size='20'
							   value="<?= $ar_data['serialnum']; ?>">
					</td>
				</tr>
				<tr>
					<th>배포기간</th>
					<td>
						<input type='text' name='dt_publish_start' size='20' class="dateTimePicker"
							   data-parent="#container" readonly value="<?= $ar_data['dt_publish_start']; ?>"> ~
						<input type='text' name='dt_publish_end' size='20' class="dateTimePicker"
							   data-parent="#container" readonly value="<?= $ar_data['dt_publish_end']; ?>">
						<Br/>배포기간이란? 다운로드 혹은 시리얼 쿠폰의 경우 고객이 받을수 있는 기간을 의미합니다
					</td>
				</tr>

				<tr>
					<th>
						사용조건
					</th>
					<td>
						주문시
						<input type='text' name='canuseac' size='10' value='<?= $ar_data['canuseac']; ?>'>원 이상시에만 사용가능
						[주문쿠폰에만 해당, 0으로 설정시 조건없이 사용가능]
					</td>
				</tr>
				<tr>
					<th>사용기간</th>
					<td>
						<input type='text' name='dt_use_start' id='dt_use_start' value='<?= $ar_data['dt_use_start'] ?>' class="dateTimePicker"
							   data-parent="#container" readonly>
						<input type='text' name='dt_use_end' id='dt_use_end' value='<?= $ar_data['dt_use_end'] ?>' class="dateTimePicker"
							   data-parent="#container" readonly>
					</td>
				</tr>
				<tr>
					<th>설명</th>
					<td><textarea name='memo' cols='70' rows='5'><?=$ar_data['memo']?></textarea></td>
				</tr>
			</table>

			<div class="btn_wrap btn_center btn_bottom">
				<span class="btn_green btn_submit" data-form="#regiform"><a href="javascript:">수정하기</a></span>
			</div>

		</form>
	</div><!-- // .form_wrap -->
</div><!-- // .content -->