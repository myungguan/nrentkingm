<?
$boardid = $_REQUEST['boardid'];
if ($_REQUEST['mode'] == 'w') {
	$tableName = 'notices';
	if($boardid == '이벤트'){
		$tableName = 'events';
	}else if($boardid == '렌트킹뉴스') {
        $tableName = 'rentking_news';
    }

	$tx_content = $_REQUEST['tx_content'];
	$tx_attach_image = $_REQUEST['tx_attach_image'];
	$tx_attach_image_filename = $_REQUEST['tx_attach_image_filename'];
	$tx_attach_image2 = $_REQUEST['tx_attach_image2'];
	$tx_attach_file = $_REQUEST['tx_attach_file'];

	$value['title'] = mysql_escape_string($_POST['title']);
	$value['content'] = addslashes($tx_content);
	$value['member_idx'] = $_SESSION['member_idx'];
	$value['dt_create'] = date("Y-m-d H:i:s", time());
	if($boardid == '공지사항') {
		$value['front_yn'] = 'Y';
	}else if($boardid == '멤버사 공지사항') {
		$value['rentshop_yn'] = 'Y';
	}

	$r = insert("$tableName", $value);
	if (!$r) {
		echo mysql_error();
		exit;
		echo "<Script>alert('게시물작성에 실패하였습니다.'); history.back(); </script>";
		exit;
	}

	echo "<Script>alert('게시물이작성되었습니다'); location.replace('$PHP_SELF?code=bbs'); </script>";
	exit;
}
?>

<form name="writeform" id='writeform' action="<?=$PHP_SELF;?>?code=<?=$code;?>" method="post" ENCTYPE="multipart/form-data">
	<input type='hidden' name='mode' value='w'>
	<input type='hidden' name='boardid' value='<?=$boardid;?>'>
	<div style='margin-top:10px;'>
		<table class="detailTable2">
			<tr>
				<th>게시판명</th>
				<td><?=$boardid;?></td>
			</tr>
			<tr>
				<th>제목</th>
				<td><input type='text' name='title' id="id_subject" size='60'></td>
			</tr>
			<tr>

				<td colspan='2'>
					<? include "./newet/edit.php"; ?>
				</td>
			</tr>
			<? /*
<tr id='id_date' <? if($ar_boardconf[usedate]!='Y'){?> style='display:none;'<?}?>>
	<th>기간</th>
	<td><input type='text' class='input' name='sdate' id="sdates"> ~ <input type='text' class='input' name='edate' id="edates"></td>
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

		<div class="btn_wrap btn_center">
			<span class="btn_green"><a href="javascript:saveContent();">등록하기</a></span>
		</div>


	</div><!-- // .content -->
</form><!-- // form[name="writeform"] -->
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
		validator.exists(content)
		answer = confirm('게시물을등록하시겠습니까?');
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
					'name': 'tx_attach_image_filename[]',
					'value': images[i].data.filename
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