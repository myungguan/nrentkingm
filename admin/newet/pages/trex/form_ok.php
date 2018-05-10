<?
/**
 * admin.rentking.co.kr/sho.php?code=bbsw
 * 관리자페이지 > 사이트관리 > 게시물관리 > 글쓰기 > 사진첨부 > 파일찾기 > 등록
 * admin.rentking.co.kr/sho.php?code=bbsm
 * 관리자페이지 > 사이트관리 > 게시물관리 > 조회 > 수정 > 사진첨부 > 파일찾기 > 등록
 * admin.rentking.co.kr/sho.php?code=faqw
 * 관리자페이지 > 사이트관리 > 자주하는질문 > FAQ등록 > 사진첨부 > 파일찾기 > 등록
 * admin.rentking.co.kr/sho.php?code=faqm
 * 관리자페이지 > 사이트관리 > 자주하는질문 > 수정 > 사진첨부 > 파일찾기 > 등록
 * 다음 에디터 글 작성 > 사진 첨부 > 파일찾기 후 업도르시 사용
 */
set_time_limit(0);
include $_SERVER['DOCUMENT_ROOT']."/old/inc/base.php";
include $config['incPath']."/connect.php";
include $config['incPath']."/session.php";
include $config['incPath']."/config.php";

/**
* NFUpload - 플래시 기반의 업로드 프로그래스바가 지원되는 멀티업로드 프로그램
*
* 라이센스 : 프리웨어 (개인/회사 구분없이 무료로 사용가능)
* 제작사 : 패스코리아넷 (http://passkorea.net/)
*
* 배포시 주의사항 : 제작사와 라이센스 정보를 삭제하시면 안됩니다.
*/

require_once('nfupload_conf.inc.php');		// NFUpload Config
?>
<script src="/newet/js/popup.js" type="text/javascript" charset="utf-8"></script>
<Script>
var _opener = PopupUtil.getOpener();

 var _attacher = getAttacher('image', _opener);
 registerAction(_attacher);
 </script>
<?
	$widths = $_POST['widths'];
// [NFUpload] 업로드된 파일명 해석
	$__nfu_files = array();
	$__nfu = explode('|:|', stripslashes($_POST['hidFileName']));
	for ($i=0; $i<sizeof($__nfu); $i++)
	{
		$__ex = explode('/', $__nfu[$i]);
		$__tmp_name = ($__ex[0] != '') ? $__ex[0] : $__ex[1];		// 유니크파일명이 없을때는 실제 파일명을 사용.
		$__nfu_files[] = array($__tmp_name, $__ex[1]);
	}	// for()

	for ($i=0; $i<sizeof($__nfu_files); $i++)
	{
		if (file_exists($__NFUpload['dir'].DIRECTORY_SEPARATOR.$__nfu_files[$i][0]))
		{
			$tpname = urlencode($__nfu_files[$i][0]);
			$name = urlencode($__nfu_files[$i][1]);
			
			$ex_filename = explode(".",$name);
			$extension = $ex_filename[sizeof($ex_filename)-1];

			$month = date('Ym');
			$savedir = $config['uploadPath']."/board/".$month.'/';
			if(!is_dir($savedir))
				mkdir($savedir);

			$newname = time()."_".$i.".".$extension;
			copy("./data/".$tpname,$savedir.$newname);
			unlink("./data/".$tpname);
			
			if($widths>0)
			{
				$size = @getimagesize($savedir.$newname);
				if($size[0]>$widths)
				{	
					$h = $widths * $size[1]/$size[0];
					$img = $newname;
				}
				else
				{	$w = $size[0];	$img = $newname;	}
			}
			else
			{	$w = $size[0];	$img = $newname;	}
?>
<script type="text/javascript">
var _mockdata = {
			'imageurl': '<?=$config['imgServer']?>/board/<?=$month.'/'.$img;?>',
			'filename': '<?=$name;?>',
			'filesize': '<?=filesize($savedir.$newname);?>',
			'imagealign': 'C',
			'originalurl': '<?=$config['imgServer']?>/board/<?=$month.'/'.$img;?>',
			'thumburl': '<?=$config['imgServer']?>/board/<?=$month.'/'.$img;?>'
};
execAttach(_mockdata);
</script>
<?
		} 
	}

?>

<script type="text/javascript">
closeWindow();
</script>
