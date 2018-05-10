<?
$idx = $_REQUEST['idx'];
$q = "SELECT se.*, sf.idx file_idx, sf.name file_name FROM eng se LEFT JOIN files sf ON sf.article_idx = se.idx AND sf.article_type = 'eng'  WHERE se.idx = $idx";
$ar_data = [];
$r = mysql_query($q);
if($r) {
	$ar_data = mysql_fetch_array($r);
}

if ( $mode == 'w' ) {
	$value[memo] = mysql_escape_string($_POST['memo']);
	update( "eng", $value, " WHERE idx='$idx'" );

	echo "<script>alert('수정완료'); location.replace('$PHP_SELF?code=$code&idx=$idx'); </script>";
	exit;
}
?>
<script>
	function foch(f) {
		var re = check_form(f);
		if (re) {
			answer = confirm('수정 하시겠습니까?');
			if (answer == true) {
				return true;
			}
			else {
				return false;
			}
		}
		else {
			return false;
		}

	}
</script>
<span class="subTitle">* 영문예약신청내역조회</span>
<form name="regiform" id="regiform" action="<?= $PHP_SELF; ?>?code=<?= $code; ?>" method="post"
	  onsubmit="return foch('regiform');" enctype="multipart/form-data">
	<input type='hidden' name='mode' value='w'>
	<input type='hidden' name='idx' value='<?= $idx; ?>'>
	<table class="detailTable2">
		<tr>
			<th>작성일</th>
			<td><?= $ar_data[wdate]; ?></td>
		</tr>
		<tr>
			<th>장소</th>
			<td><?= $ar_data[location]; ?></td>
		</tr>
		<tr>
			<th>기간</th>
			<td><?= $ar_data[sdate]; ?> ~ <?= $ar_data[edate]; ?></td>
		</tr>
		<tr>
			<th>대여차량클래스</th>
			<td><?= $ar_data[vclass]; ?></td>
		</tr>
		<tr>
			<th>차량</th>
			<td><?= $ar_data[car]; ?></td>
		</tr>
		<tr>
			<th>대여타입</th>
			<td><?= $ar_data[dtype]; ?></td>
		</tr>
		<tr>
			<th>상세내용</th>
			<td><?= str_replace("\n", '<br />', $ar_data[detail]); ?></td>
		</tr>
		<tr>
			<th>성명</th>
			<td>FRIST : <?= $ar_data[fname]; ?> / LAST : <?= $ar_data[lname]; ?></td>
		</tr>
		<tr>
			<th>연락처</th>
			<td><?= $ar_data[phone]; ?></td>
		</tr>
		<tr>
			<th>E-MAIL</th>
			<td><?= $ar_data[email]; ?></td>
		</tr>
		<tr>
			<th>age</th>
			<td><?= $ar_data[age]; ?></td>
		</tr>
		<tr>
			<th>거주지</th>
			<td><?= $ar_data[resident]; ?></td>
		</tr>
		<tr>
			<th>비행편</th>
			<td><?= $ar_data[airname]; ?> / <?= $ar_data[fnumber]; ?></td>
		</tr>
		<tr>
			<th>첨부파일</th>
			<td>
				<?
				if ( $ar_data['file_idx'] != '' ) {
					?>
					<a href="<?=$config['imgServer']?>/download.php?idx=<?=$ar_data['file_idx']?>" target="_blank"><?= $ar_data['file_name']; ?></a>
				<? } ?>
			</td>
		</tr>
		<tr>
			<th>관리자메모</th>
			<td><textarea name="memo" style="width:600px;height:200px;"><?= $ar_data[memo]; ?></textarea></td>
		</tr>
	</table>
	<div class="topBtn" style='text-align:center;'>
		<span class="greenBtn btn_submit" data-form="#regiform"><a href="javascript:">관리자메모수정</a></span>
	</div>
</form>