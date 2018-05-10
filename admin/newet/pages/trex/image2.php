<?
require_once('nfupload_conf.inc.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>이미지 첨부</title> 

<link rel="stylesheet" href="/newet/css/popup.css" type="text/css"  charset="utf-8"/>
<script type="text/javascript">
	function initUploader(){
	    var _opener = PopupUtil.getOpener();
	    if (!_opener) {
	        //alert('잘못된 경로로 접근하셨습니다.');
	        return;
	    }
	    
	    var _attacher = getAttacher('image', _opener);
	    registerAction(_attacher);
	}
</script>
<script language="JavaScript" type="text/javascript" src="/newet/js/nfupload.js?d=20081028"></script>
<script language="JavaScript" type="text/javascript">
<!--
	// -----------------------------------------------------------------------------
	// NFUpload User's Config
	// -----------------------------------------------------------------------------
		// 업로드 설정
			var _NF_UploadUrl = "//<?= $_SERVER['HTTP_HOST'] ?><?=dirname($_SERVER['PHP_SELF']) ?>/nfupload_proc.php";					  
			var _NF_FileFilter = "이미지 파일|:|*.jpg;*.jpeg;*.gif;*.png;*.bmp";								
			var _NF_DataFieldName = "upfile";				
			var _NF_Flash_Url = "./nfupload.swf?d=20081028";		

		// 화면 구성
			var _NF_Width = 600;									
			var _NF_Height = 200;								  
			var _NF_ColumnHeader1 = "파일명";					  
			var _NF_ColumnHeader2 = "용량";						
			var _NF_FontFamily = "돋움";							
			var _NF_FontSize = "11";								

		// 업로드 제한
			var _NF_MaxFileSize = <?= $__NFUpload['max_size_total'] ?>;							
			var _NF_MaxFileCount = <?= $__NFUpload['max_count'] ?>;							  // 업로드 파일 제한 갯수 (기본값: 10)
			var _NF_File_Overwrite = <? if ($__NFUpload['file_overwrite']) echo 'true'; else echo 'false'; ?>;						 // 업로드시 파일명 처리방법(true : 원본파일명 유지, 덮어씌우기 모드 / false : 유니크파일명으로 변환, 중복방지)
			var _NF_Limit_Ext = "<?= $__NFUpload['limit_ext'] ?>";	 // 파일 제한 확장자

		// [2008-10-28] Flash 10 support
		var _NF_Img_FileBrowse = "/imgs/file_find_btn.jpg";  // 파일찾기 이미지
		var _NF_Img_FileBrowse_Width = "90";                    // 파일찾기 이미지 넓이 (기본값 59)
		var _NF_Img_FileBrowse_Height = "25";                   // 파일찾기 이미지 폭 (기본값 22)
		var _NF_Img_FileDelete = "/imgs/file_delete_btn.jpg";  // 파일삭제 이미지
		var _NF_Img_FileDelete_Width = "80";                    // 파일삭제 이미지 넓이 (기본값 59)
		var _NF_Img_FileDelete_Height = "25";                   // 파일삭제 이미지 폭 (기본값 22)
		var _NF_TotalSize_Text = "첨부용량 ";                   // 파일용량 텍스트
		var _NF_TotalSize_FontFamily = "돋움";                  // 파일용량 텍스트 폰트
		var _NF_TotalSize_FontSize = "12";                      // 파일용량 텍스트 폰트 크기

	// -----------------------------------------------------------------------------
	// NFUpload Function
	// -----------------------------------------------------------------------------
		// 폼입력 완료
		function NFU_Complete(value) {
			var files = document.FrmUpload.hidFileName.value;
			var fileCount = 0;
			var i = 0;

			// 이 부분을 수정해서 파일이 선택되지 않았을 때에도 submit을 하게 수정할 수 있습니다.
			if (value == null)
			{
				alert("업로드할 파일을 선택해 주세요.");
				return;
			}

			fileCount = value.length;

			for (i = 0; i < fileCount; i++)
			{
				var fileName = value[i].name;
				var realName = value[i].realName;
				var fileSize = value[i].size;

				// 분리자(|:|)는 다른 문자로 변경할 수 있다.
				files += fileName + "/" + realName + "|:|";
			}

			if (files.substring(files.length - 3, files.length) == "|:|")
				files = files.substring(0, files.length - 3);

			document.FrmUpload.hidFileName.value = files;
			document.FrmUpload.submit();
		}

		// 폼입력 취소
		function NF_Cancel()
		{
			// 초기화 할때는 첨부파일 리스트도 같이 초기화 시켜 준다.
			NfUpload.AllFileDelete();
			FrmUpload.reset();
		}

		// 선택된 파일들의 총용량을 화면에 갱신시킴.
		function NF_ShowUploadSize(value) {
			// value값에 실제 업로드된 용량이 넘어온다.
			sUploadSize.innerHTML = value;
		}

		// 업로드 실패시 경고문구
		function NFUpload_Debug(value)
		{
			Debug("업로드가 실패하였습니다.\r\n\r\n관리자일 경우 디버깅모드를 활성화시켜 디버깅로그를 확인해보시면 됩니다.\r\n\r\n" + value);
		}

		window.onload=function(){
			document.FrmUpload.hidFileName.value = "";
			// [2008-10-28] Flash 10 support
			//sMaxSize.innerHTML = SizeCalc(_NF_MaxFileSize);
		}
// -->
</script>
</head>
<body>
<div class="wrapper">
	<div class="header">
		<h1>사진 첨부</h1>
	</div>	
	<div class="body">
<!--  -->
<table border="0" cellpadding="0" cellspacing="0" width="600" align='center'>
<form name="FrmUpload" method="post" action="form_ok.php" style="margin:0px">
<input type="hidden" name="hidFileName"/>
<input type='hidden' name='widths' value=''>
	<tr>
		<td align="center" colspan="2" style="padding-top:3px;">
			<script language="javascript">
                NfUpload = new NFUpload({
                        nf_upload_id : _NF_Uploader_Id,
                        nf_width : _NF_Width,
                        nf_height : _NF_Height,
                        nf_field_name1 : _NF_ColumnHeader1,
                        nf_field_name2 : _NF_ColumnHeader2,
                        nf_max_file_size : _NF_MaxFileSize,
                        nf_max_file_count : _NF_MaxFileCount,
                        nf_upload_url : _NF_UploadUrl,
                        nf_file_filter : _NF_FileFilter,
                        nf_data_field_name : _NF_DataFieldName,
                        nf_font_family : _NF_FontFamily,
                        nf_font_size : _NF_FontSize,
                        nf_flash_url : _NF_Flash_Url,
                        nf_file_overwrite : _NF_File_Overwrite,
                        nf_limit_ext : _NF_Limit_Ext,
                        nf_img_file_browse : _NF_Img_FileBrowse,
                        nf_img_file_browse_width : _NF_Img_FileBrowse_Width,
                        nf_img_file_browse_height : _NF_Img_FileBrowse_Height,
                        nf_img_file_delete : _NF_Img_FileDelete,
                        nf_img_file_delete_width : _NF_Img_FileDelete_Width,
                        nf_img_file_delete_height : _NF_Img_FileDelete_Height,
                        nf_total_size_text : _NF_TotalSize_Text,
                        nf_total_size_font_family : _NF_TotalSize_FontFamily,
                        nf_total_size_font_size : _NF_TotalSize_FontSize
                });
			</script>
		</td>
	</tr>
	<tr>
		<td align="center" colspan="2" height='30'>
			
		</td>
	</tr>
</form>
</table>
<!--  -->
	</div>

	<div class="footer">
		<p><a href="#" onclick="window.close();" title="닫기" class="close">닫기</a></p>
		<ul>
			<li class="submit"><a href="#" onclick="NfUpload.FileUpload();" title="등록" class="btnlink">등록</a> </li>
			<li class="cancel"><a href="#" onclick="window.close();" title="취소" class="btnlink">취소</a></li>
		</ul>
	</div>
</div>
<script>
document.FrmUpload.widths.value = opener.document.writeform.readimgsize.value;
</script>
</body>
</html>
