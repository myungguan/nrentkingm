<?
/**
 * admin.rentking.co.kr/sho.php?code=bbsm
 * 관리자페이지 > 사이트관리 > 게시물관리 > 조회 > 수정
 * 게시물관리 > 상세 게시글 수정페이지
 */
$idx = $_REQUEST['idx'];
$boardid = $_REQUEST['boardid'];
$tableName = 'notices';
if($boardid == '이벤트'){
	$tableName = 'events';
}else if($boardid == '렌트킹뉴스'){
    $tableName = 'rentking_news';
}

$q = "	
		SELECT
			t.*,
			m.name
		FROM $tableName t
		INNER JOIN member m ON m.idx = t.member_idx
		WHERE t.idx = $idx 
			 ";
$ar_data = mysql_fetch_array(mysql_query($q));

if ($_REQUEST['mode'] == 'w') {

    $tx_content = $_REQUEST['tx_content'];
    $tx_attach_image = $_REQUEST['tx_attach_image'];
    $tx_attach_image2 = $_REQUEST['tx_attach_image2'];
    $tx_attach_file = $_REQUEST['tx_attach_file'];

    $idx = $_REQUEST['idx'];

    $tx_content = stripslashes($tx_content);

    $value['title'] = mysql_escape_string($_POST['title']);
    $value['content'] = addslashes($tx_content);
	$value['dt_update'] = date("Y-m-d H:i:s", time());

    $r = update("$tableName", $value, " where idx='$idx'");
    if (!$r) {
        echo mysql_error();
        exit;
        echo "<Script>alert('게시물수정에 실패하였습니다.'); history.back(); </script>";
        exit;
    }

    echo "<Script>alert('게시물이수정되었습니다'); location.replace('sho.php?code=bbsr&idx=$idx&boardid=$boardid'); </script>";
    exit;
}
?>
<div class="content">
	<form name="writeform" id='writeform' action="sho.php?code=<?= $code; ?>" method="post"
	      ENCTYPE="multipart/form-data">
		<input type='hidden' name='mode' value='w'>
		<input type='hidden' name='idx' value='<?= $idx; ?>'>
		<input type='hidden' name='boardid' value='<?= $boardid; ?>'>
		<div class="form_wrap">
			<table class="detailTable2">
				<tr>
					<th>게시판명</th>
					<td><?= $boardid ?></td>
				</tr>
				<tr>
					<th>제목</th>
					<td><input type='text' name='title' id="id_subject" size='60' value='<?= $ar_data['title']; ?>'>
					</td>
				</tr>
				<tr>
					<th>작성자</th>
					<td><?= $ar_data['name']; ?></td>
				</tr>

				<td colspan='2'>
					<!-- editor 영역 -->
                    <?php
                    $memo = $ar_data['content'];
                    ?>
                    <? include "./newet/edit_mod.php"; ?>
					<!-- //editor 영역 -->
				</td>
				</tr>
                <? /*
<tr id='id_date' <? if($ar_boardconf[usedate]!='Y'){?> style='display:none;'<?}?>>
	<th>기간</th>
	<td><input type='text' class='input' name='sdate' id="sdates" value="<?=$ar_data[sdate];?>"> ~ <input type='text' class='input' name='edate' id="edates" value="<?=$ar_data[edate];?>"></td>
</tr>
*/ ?>
				<!--<tr>-->
				<!--	<th>첨부파일1</th>-->
				<!--	<td><input type='file' name='file1'> [이미지는 에디터 기능으로 이용하세요]</td>-->
				<!--</tr>-->
				<!--<tr>-->
				<!--	<th>첨부파일2</th>-->
				<!--	<td><input type='file' name='file2'> [이미지는 에디터 기능으로 이용하세요]</td>-->
				<!--</tr>-->

			</table>
		</div><!-- // .form_wrap -->
		<div class="btn_wrap btn_center">
			<span class="btn_green"><a href="javascript:saveContent();">수정하기</a></span>
		</div>

	</form><!-- // form[name="writeform"] -->

</div><!-- // .content -->
<script type="text/javascript">
	var iswrite = '';
	function saveContent() {
		Editor.save();
	}
	function validForm(editor) {
		var validator = new Trex.Validator();
		var content = editor.getContent();
		if (!validator.exists(content)) {
			alert('내용을 입력하세요');
			return false;
		}
		if ($("#id_mem_name").val() == '') {
			alert('작성자명을 입력하세요');
			$("#id_mem_name").focus();
			return false;
		}

		if ($("#id_subject").val() == '') {
			alert('제목을입력하세요.');
			$("#id_subject").focus();
			return false;
		}

		if ($("#id_btype option:selected").val() == '') {
			alert('글종류를 선택하세요.');
			$("#id_btype").focus();
			return false;
		}

		var validator = new Trex.Validator();
		var content = editor.getContent();
		validator.exists(content);

		//	2017.03.06 DonYoung - START
//	answer = confirm('게시물을수정하시겠습니까?');
		answer = confirm('게시물을 수정하시겠습니까?');
		//	2017.03.06 DonYoung - END

		if (answer == true) {
			return true;
		}
		else {
			return false;
		}
	}
	function setForm(editor) {
		var formGenerator = editor.getForm();
		var content = editor.getContent();
		var textarea = document.createElement('textarea');
		textarea.name = 'tx.content';
		textarea.value = content;
		formGenerator.createField(textarea);

		var images = editor.getAttachments('image');
		for (var i = 0; i< images.length; i++) {
			if (images[i].existStage) {
				input = document.createElement('input');
				input.type = 'hidden';
				input.name = 'attach_image';
				input.value = images[i].data.imageurl;
				formGenerator.createField(input);
			}
		}

		formGenerator.createField(tx.textarea({'name': "tx_content", 'style': {'display': "none"}}, content));

		var images = editor.getAttachments('image');
		for (var i = 0, len = images.length; i < len; i++) {
			if (images[i].existStage) {
				formGenerator.createField(tx.input({
					'type': "hidden",
					'name': 'tx_attach_image[]',
					'value': images[i].data.imageurl
				}));
				formGenerator.createField(tx.input({
					'type': "hidden",
					'name': 'tx_attach_image2[]',
					'value': images[i].data.originalurl
				}));
			}
		}

		var files = editor.getAttachments('file');
		for (var i = 0, len = files.length; i < len; i++) {
			formGenerator.createField(
				tx.input({
					'type': "hidden",
					'name': 'tx_attach_file',
					'value': files[i].data.attachurl
				})
			);
		}

		return true;
	}
</script>