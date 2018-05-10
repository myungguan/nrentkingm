<?
/**
 * 어드민 > 고객관리 > 회원 > 상세조회(팝업) || 어드민 > 고객관리 > 법인(단체) > 상세조회(팝업)
 * admin.rentking.co.kr/popup/memview.php?idx=1131
 */
include $_SERVER['DOCUMENT_ROOT']."/old/inc/base.php";
include $config['incPath']."/connect.php";
include $config['incPath']."/session.php";
include $config['incPath']."/config.php";
include "../adminhead.php";

if (!$idx) {
	$idx = $_REQUEST['idx'];
}
$mode = $_REQUEST['mode'];

if ($mode == 'd') {
	mysql_query("UPDATE member SET id='outmember' WHERE idx='{$idx}'");
	echo "<script type='text/javascript'>alert('탈퇴처리 하였습니다'); opener.location.reload(); window.close(); </script>";
	exit;
}

if (!$idx) {
	echo "<script type='text/javascript'>alert('잘못된 접근입니다.'); window.close(); </script>";
	exit;
}

$ar_mem = sel_query_all("member", " WHERE idx='{$idx}'");
?>
<div id="pop_contents">
	<span class="subTitle">* 회원기본정보</span>
	<table class="detailTable2">
		<colgroup>
			<col width="12%">
			<col width="21.3%">
			<col width="12%">
			<col width="21.3%">
			<col width="12%">
			<col width="21.3%">
		</colgroup>
		<tr>
			<th>성명[담당자]</th>
			<td><?= $ar_mem['name']; ?> (<? if ($ar_mem['sex'] == 'M') {
					echo "남";
				} else {
					echo "여";
				} ?>)
			</td>
			<th>아이디</th>
			<td><?= $ar_mem['id']; ?></td>
			<th>회원등급</th>
			<td>일반회원</td>
		</tr>
		<tr>
			<th>생년월일</th>
			<td><?= $ar_mem['birth']; ?>
				(<? if ($ar_mem['birthtype'] == 'S') {
					echo "양";
				} else {
					echo "음";
				} ?>)
			</td>
			<th>핸드폰</th>
			<td>
				<?= phone_number_format($ar_mem['cp']) ?>
			</td>
			<th>E-Mail</th>
			<td><?= $ar_mem['id']; ?></td>
		</tr>
		<tr>
			<th>가입처</th>
			<td><? if ($ar_mem['pid'] == 'A') {
					echo "관리자 등록";
				} else if ($ar_mem['pid'] == 'W') {
					echo "홈페이지 가입";
				} else {
					echo "모바일 가입";
				} ?></td>
		</tr>
	</table>
	<span class="subTitle">* 회원가입정보</span>

	<table class="detailTable2">
		<colgroup>
			<col width="12%">
			<col width="21.3%">
			<col width="12%">
			<col width="21.3%">
			<col width="12%">
			<col width="21.3%">
		</colgroup>
		<tr>
			<th>메일링</th>
			<td><?= $ar_mem['mailser']; ?></td>
			<th>SMS</th>
			<td><?= $ar_mem['smsser']; ?></td>
			<th>PUSH</th>
			<td><?= $ar_mem['pushser']; ?></td>
		</tr>

		<tr>
			<th>가입일</th>
			<td colspan="5"><?= $ar_mem['signdate']; ?></td>
		</tr>
		<tr>
			<th>접속허용</th>
			<td colspan='7'><? if ($ar_mem['canconnect'] == 'Y') {
					echo "접속허용";
				} else {
					echo "접속차단";
				} ?></td>
		</tr>
		<tr>
			<th>회원메모<span class="btn_white_xs"><a
							href="javascript:MM_openBrWindow('../popup/member_memo.php?idx=<?= $idx; ?>','gcopy','width=800,height=600,scrollbars=yes');"
							style='font-size:11px;'>수정</a></span></th>
			<td colspan='7'>
				<?= nl2br($ar_mem['rmemo']); ?>
			</td>
		</tr>
	</table>
	<?
	$ar_lince = array();
	if($ar_mem['driver_license_idx']) {
		$ar_lince = sel_query_all("driver_license", " WHERE idx='{$ar_mem['driver_license_idx']}'");
	}
	?>
	<span class="subTitle">* 회원면허정보</span>
	<table class="detailTable2">
		<colgroup>
			<col width="12%">
			<col width="21.3%">
			<col width="12%">
			<col width="21.3%">
			<col width="12%">
			<col width="21.3%">
		</colgroup>
		<tr>
			<th>면허종류</th>
			<td><?= $ar_lin[$ar_lince['kinds']]; ?></td>
			<th>면허번호</th>
			<td colspan="3"><?= $ar_lince['nums']; ?></td>
		</tr>
		<tr>
			<th>적성검사 만료일</th>
			<td><?= $ar_lince['date1']; ?></td>
			<th>발급일</th>
			<td><?= $ar_lince['date2']; ?></td>
			<th>먼허정보등록일</th>
			<td><?= $ar_lince['dt_create']; ?></td>
		</tr>
	</table>
	<div style='margin:20px 0 20px 0'></div>
	<div class="btn_wrap btn_center">
		<span class="btn_white_xs btn_navy btn_top">
			<a href="/frame_data/mem_mod.php?idx=<?= $idx; ?>" target="iframebody">회원정보변경</a>
		</span>
		<span class="btn_white_xs btn_navy btn_top">
			<a href="#none" onclick="delok('회원을 삭제하시겠습니까?','../popup/memview.php?mode=d&idx=<?= $idx; ?>');">회원탈퇴</a>
		</span>
		<span class="btn_white_xs btn_navy btn_top">
			<a href="../frame_data/mem_orderlist.php?idx=<?= $idx; ?>" target="iframebody">예약내역조회</a>
		</span>
		<span class="btn_white_xs btn_navy btn_top">
			<a href="../frame_data/mem_cplist.php?idx=<?= $idx; ?>" target="iframebody">쿠폰조회</a>
		</span>
		<!--span class="btn_white_xs btn_navy btn_top"><a href="/frame_data/mem_gmemo.php?idx=<?= $idx; ?>" target="iframebody">후기</a></span>
		<span class="btn_white_xs btn_navy btn_top"><a href="/frame_data/mem_qlist.php?idx=<?= $idx; ?>" target="iframebody">질문</a></span-->
	</div>
	<iframe src="" width="100%" height="100" frameborder="0" scrolling="no" name="iframebody" id="iframebody"></iframe>
</div><!-- // .content -->