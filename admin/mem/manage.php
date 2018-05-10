<?
/**
 * 어드민 > 고객관리 > 관리자등록
 * admin.rentking.co.kr/mem.php?code=manage
 * 관리자 등록 페이지
 */
$mode = $_REQUEST['mode'];

if($mode == 'ajax') {
	include $_SERVER['DOCUMENT_ROOT']."/old/inc/base.php";
	include $config['incPath']."/connect.php";
	include $config['incPath']."/session.php";
	include $config['incPath']."/config.php";

	$command = $_REQUEST['command'];

	$data = array('result' => false);

	if($command == 'delete') {
		$idx = $_REQUEST['idx'];
		$query = "DELETE FROM auth_admin WHERE idx = $idx";
		mysql_query($query);
		$data['result'] = true;
	}

	if($command == 'change') {
		$idx = $_REQUEST['idx'];
		$grade = $_REQUEST['grade'];
		$query = "UPDATE auth_admin SET grade = $grade WHERE idx = $idx";
		mysql_query($query);
		$data['result'] = true;
	}

	echo json_encode($data);
	exit;
}

if ($mode == 'w') {
	$ids = $_REQUEST['ids'];
	$query = "SELECT * FROM member WHERE id='$ids' LIMIT 0,1";
	$r = mysql_query($query);

	if (mysql_num_rows($r) == 0) {
		echo "<script type='text/javascript'>alert('존재하지 않는 아이디 입니다'); history.back(); </script>";
		exit;
	}
	$member = mysql_fetch_assoc($r);

	$query = "SELECT * FROM auth_admin WHERE member_idx = {$member['idx']}";
	$r = mysql_query($query);
	if(mysql_num_rows($r) > 0) {
		echo "<script type='text/javascript'>alert('이미 등록된 아이디 입니다'); history.back(); </script>";
		exit;
	}

	$query = "INSERT INTO auth_admin(member_idx, grade) VALUE ({$member['idx']}, {$_REQUEST['memg']})";
	mysql_query($query);

	echo "<script type='text/javascript'>alert('등록되었습니다.'); location.replace('../mem.php?code={$code}'); </script>";
	exit;
}

$numper = $_REQUEST['numper'] ? $_REQUEST['numper'] : 25;

$page_per_block = 10;
$page = $_GET['page'] ? $_GET['page'] : 1;

/* 정렬 기본 */
if (!$sortcol)
	$sortcol = "m.signdate";

if (!$sortby)
	$sortby = "desc";
/* 정렬 기본 */

//HTTP QUERY STRING
$keyword = trim($keyword);
$key = $_REQUEST['key'];
$qArr['numper'] = $numper;
$qArr['page'] = $page;
$qArr['code'] = $code;

$q = "SELECT '[FIELD]' FROM auth_admin aa LEFT JOIN member m ON aa.member_idx = m.idx WHERE m.id!='outmember' AND m.id <> 'admin'";

//카운터쿼리
$sql = str_replace("'[FIELD]'", "COUNT(m.idx)", $q);
$r = mysql_query($sql);
$total_record = mysql_result($r, 0, 0);
mysql_free_result($r);

if ($total_record == 0) {
	$first = 0;
	$last = 0;
} else {
	$first = $numper * ($page - 1);
	$last = $numper * $page;
}

//데이터쿼리
$_sql = str_replace("'[FIELD]'", "m.*, aa.grade, aa.idx auth_idx", $q);

$_tArr = explode(",", $sortcol);
if (is_array($_tArr) && count($_tArr)) {
	foreach ($_tArr as $v) {
		$orderbyArr[] = "{$v} {$sortby}";
	}
	$orderby = implode(", ", $orderbyArr);
}

$sql_order = " ORDER BY {$orderby}";
$sql_limit = " LIMIT {$first}, {$numper}";
$sql = $_sql . $sql_order . $sql_limit;

$r = mysql_query($sql);
while ($row = mysql_fetch_array($r)) {
	$data[] = $row;
}

//엑셀쿼리
$sql_excel = $_sql . $sql_order;
$_SESSION['sql_excel'] = $sql_excel;
?>
<script>
	function foch(f) {
		return check_form(f) && confirm('등록 하시겠습니까?');
	}
</script>
<div style="margin-top:10px;">
	<form id="search" name="search" action="../mem.php?code=<?=$code;?>" method="post" onsubmit="return foch('search');">
		<input type='hidden' name='mode' value='w'>
		<table class="detailTable2">
			<tbody>
				<tr>
					<th>등록할아이디</th>
					<td><input type='text' name='ids' valch="yes" msg="아이디"></td>
				</tr>
				<tr>
					<th>지정등급</th>
					<td>
						<select name="memg" valch="yes" msg="등급">
							<option value="9">관리자</option>
							<option value="1">CS</option>
						</select>
					</td>
				</tr>
			</tbody>
		</table>
		<div class="btn_wrap btn_center btn_bottom">
			<span class="greenBtn btn_submit" data-form="#search"><a href="javascript:">관리자등록</a></span>
		</div>
		<input type="submit" style="display:none;">
	</form>
</div>
<div style='margin-top:20px;'>
	<table class="listTableColor">
		<thead>
			<tr>
				<th>회원번호</th>
				<th>아이디</th>
				<th>이름</th>
				<th>연락처</th>
				<th>가입일</th>
				<th>설정</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
		<?
		for($is=0;$is<count($data);$is++){
			$row = $data[$is];
		?>
		<tr>
			<td ><?=$row['idx'];?></td>
			<td ><?=$row['id'];?></td>
			<td ><?=$row['name'];?></td>
			<td ><?=$row['cp'];?></td>
			<td ><?=$row['signdate'];?></td>
			<td>
				<select name="memg" data-idx="<?=$row['auth_idx']?>">
					<option value="9" <?=$row['grade'] == 9 ? 'selected' : '' ?>>관리자</option>
					<option value="1" <?=$row['grade'] == 1 ? 'selected' : '' ?>>CS</option>
				</select>
			</td>
			<td>
				<button type="button" class="blackBtn_small delete" style="white-space:nowrap;" data-idx="<?=$row['auth_idx']?>">삭제</button>
			</td>
			<!--//161115 탈퇴 -> 삭제로 변경-->
		</tr>
		<?}?>
		</tbody>
	</table>
	<!-- paging     161123추가  -->
	<div class="paging">
		<?=paging_admin($page, $total_record, $numper, $page_per_block, $qArr);?>
	</div>
	<!-- //paging -->
</div>

<script type="text/javascript">
	$(document)
		.on('click', '.delete', function(e) {
			var $btn = $(this);
			if(confirm('삭제하시겠습니까?')) {
				var idx = $btn.data('idx');

				$.getJSON('/mem/manage.php', {mode:'ajax', command:'delete', idx: idx}, function(data) {
					if(data['result']) {
						$btn.parents('tr').remove();
					} else {
						alert('삭제 실패')
					}
				})
			}
		})
		.on('change', 'select[name="memg"]', function(e) {
			var $memg = $(this);

			var idx = $memg.data('idx');
			$.getJSON('/mem/manage.php', {mode:'ajax', command:'change', idx: idx, grade: $memg.val()}, function(data) {
				if(!data['result']) {
					alert('변경 실패')
				}
			});
		})
</script>