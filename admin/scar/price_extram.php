<?
/**
 * 멤버사 > 차량관리 > 특별요금관리 > 조회 및 수정
 * admin.rentking.co.kr/scar.php?code=price_extram&idx=11
 * 특별 요금 조회 및 수정 페이지
 */

$mode = $_REQUEST['mode'];

$query = "SELECT * FROM vehicle_price_extra WHERE idx = {$_REQUEST['idx']}";
$values = mysql_fetch_array(mysql_query($query));

if ($mode == 'm') {
	$values['title'] = mysql_escape_string($_REQUEST['title']);
	$values['type'] = $_REQUEST['type'];
	$values['price'] = $_REQUEST['price'];
    $values['dt_start'] = date("Y-m-d H:i:s", strtotime($_REQUEST['dt_start']));
    $values['dt_end'] = date("Y-m-d H:i:s", strtotime($_REQUEST['dt_end'])).' 23:59:59';
    $values['dt_start2'] = $_REQUEST['dt_start2'] ? date("Y-m-d H:i:s", strtotime($_REQUEST['dt_start2'])) : NULL;
    $values['dt_end2'] = $_REQUEST['dt_end2'] ? date("Y-m-d H:i:s", strtotime($_REQUEST['dt_end2'])).' 23:59:59' : NULL;
    $values['dt_start3'] = $_REQUEST['dt_start3'] ? date("Y-m-d H:i:s", strtotime($_REQUEST['dt_start3'])) : NULL;
    $values['dt_end3'] = $_REQUEST['dt_end3'] ? date("Y-m-d H:i:s", strtotime($_REQUEST['dt_end3'])).' 23:59:59' : NULL;



	$q = "
			UPDATE
				vehicle_price_extra
			SET
				member_idx = {$values['member_idx']},
				rentshop_idx = {$values['rentshop_idx']},
				title = '{$values['title']}',
				type = {$values['type']},
				price = " . str_replace(',', '', $values['price']) . ",
				dt_start = ".($values['dt_start'] ? "'{$values['dt_start']}'" : 'NULL').",
				dt_end = ".($values['dt_end'] ? "'{$values['dt_end']}'" : 'NULL').",
				dt_start2 = ".($values['dt_start2'] ? "'{$values['dt_start2']}'" : 'NULL').",
				dt_end2 = ".($values['dt_end2'] ? "'{$values['dt_end2']}'" : 'NULL').",
				dt_start3 = ".($values['dt_start3'] ? "'{$values['dt_start3']}'" : 'NULL').",
				dt_end3 = ".($values['dt_end3'] ? "'{$values['dt_end3']}'" : 'NULL').",
				dt_update = NOW()
			WHERE
				idx = {$values['idx']}";

	$result = mysql_query($q);

	if (!$result) {
		echo "<script>alert('수정에 실패하였습니다. 다시 시도해주세요.'); history.back(); </script>";
		exit;
	}

	echo "<script>alert('수정 완료'); location.replace('../scar.php?code=price_extram&idx={$_REQUEST['idx']}'); </script>";
	exit;
}
?>

<script type="text/javascript">
	function foch() {
		if (!$.trim($("#id_title").val())) {
			alert('제목을 입력해주세요.');
			return false;
		}

		if ($.trim($("#id_price").val().replace(',','')) < 1000) {
			alert('할인/인상 금액은 최소 1,000원 이상이어야 합니다.');
			return false;
		}

        if (!$.trim($("#id_dt_start").val()) || !$.trim($("#id_dt_end").val())) {
            alert('적용 기간1은 필수 입력 입니다.');
            return false;
        } else if ($.trim($("#id_dt_start").val()) > $.trim($("#id_dt_end").val())) {
            alert('적용기간1: 적용 기간이 적절하지 않습니다.');
            return false;
        }

        if (($("#id_dt_start2").val() != '' && $("#id_dt_end2").val() == '') || ($("#id_dt_start2").val() == '' && $("#id_dt_end2").val() != '')) {
            alert('적용기간2: 적용기간 입력시 시작날짜와 종료날짜를 모두 입력해야 합니다.');
            return false;
        } else if ($.trim($("#id_dt_start2").val()) > $.trim($("#id_dt_end2").val())) {
            alert('적용기간2: 적용 기간이 적절하지 않습니다.');
            return false;
        }

        if (($("#id_dt_start3").val() != '' && $("#id_dt_end3").val() == '') || ($("#id_dt_start3").val() == '' && $("#id_dt_end3").val() != '')) {
            alert('적용기간3: 적용기간 입력시 시작날짜와 종료날짜를 모두 입력해야 합니다.');
            return false;
        } else if ($.trim($("#id_dt_start3").val()) > $.trim($("#id_dt_end3").val())) {
            alert('적용기간3: 적용 기간이 적절하지 않습니다.');
            return false;
        }

	}

    function reset2(){
        $("#id_dt_start2").val('');
        $("#id_dt_end2").val('');
    }
    function reset3(){
        $("#id_dt_start3").val('');
        $("#id_dt_end3").val('');
    }
</script>

<form name="regiform" id="regiform" action="../scar.php?code=<?= $code; ?>&idx=<?= $_REQUEST['idx'] ?>" method="post" onsubmit="return foch('regiform');" enctype="multipart/form-data">
	<input type="hidden" name="mode" value="m"/>
	<table class="detailTable2">
		<tbody>
		<tr>
			<th>제목</th>
			<td colspan="10" style="text-align:left;padding:5px;line-height:18px;">
				<input type="text" name="title" id="id_title" placeholder="제목을 입력하세요." maxlength="90" style="width:400px;" valch="yes" value="<?= $values['title'] ?>"/>
			</td>
		</tr>
		<tr>
			<th>설명</th>
			<td colspan="10" style="text-align:left;padding:5px;line-height:18px;">
				등록된 요금정책은 1일(24시간) 대여금액 단위로 할인/인상되어 결제됩니다.<br/>
				특별요금의 기간이 대여기간 중 일부에 속하게 되면 전 기간 특별요금으로 책정되어 결제됩니다.
			</td>
		</tr>
		<tr>
			<th>할인/인상</th>
			<td colspan="10" style="text-align:left;padding:5px;line-height:18px;">
				<label><input type="radio" name="type" id="id_type" value="1" <? if ($values['type'] == 1) { ?> checked <? } ?>> 인상</label>
				<label><input type="radio" name="type" id="id_type" value="2" <? if ($values['type'] == 2) { ?> checked <? } ?>> 할인</label>
			</td>
		</tr>
		<tr>
			<th>금액</th>
			<td colspan="10" style="text-align:left;padding:5px;line-height:18px;">
				<input type="text" name="price" id="id_price" class="mWt150 inputNumber"
					   value="<?= $values['price'] ?>" msg="금액을"/> 원/일
			</td>
		</tr>
        <tr>
            <th>적용기간1</th>
            <td colspan="10" style="text-align:left;padding:5px;line-height:18px;">
                <input type="text" name='dt_start' id="id_dt_start" value="<?= $values['dt_start'] ?>" class="datePicker mWt100" data-parent="body" readonly> ~
                <input type="text" name='dt_end' id="id_dt_end" value="<?= $values['dt_end'] ?>" class="datePicker mWt100" data-parent="body" readonly>
            </td>
        </tr>
        <tr>
            <th>적용기간2</th>
            <td colspan="10" style="text-align:left;padding:5px;line-height:18px;">
                <input type="text" name='dt_start2' id="id_dt_start2" value="<?= $values['dt_start2'] ?>" class="datePicker mWt100" data-parent="body" readonly> ~
                <input type="text" name='dt_end2' id="id_dt_end2" value="<?= $values['dt_end2'] ?>" class="datePicker mWt100" data-parent="body" readonly>
                <a href="javascript:reset2();"><span class="greenBtn" style="width:160px">적용기간2 초기화(삭제)</span></a>
            </td>
        </tr>
        <tr>
            <th>적용기간3</th>
            <td colspan="10" style="text-align:left;padding:5px;line-height:18px;">
                <input type="text" name='dt_start3' id="id_dt_start3" value="<?= $values['dt_start3'] ?>" class="datePicker mWt100" data-parent="body" readonly> ~
                <input type="text" name='dt_end3' id="id_dt_end3" value="<?= $values['dt_end3'] ?>" class="datePicker mWt100" data-parent="body" readonly>
                <a href="javascript:reset3();"><span class="greenBtn" style="width:160px">적용기간3 초기화(삭제)</span></a>
            </td>
        </tr>
		</tbody>
	</table>
	<div class="topBtn" style="text-align:center">
		<span class="greenBtn btn_submit" data-form="#regiform"><a href="javascript:">저장</a></span>
		<a href="/scar.php?code=price_extra"><span class="greenBtn">목록</span></a>
	</div>
</form>