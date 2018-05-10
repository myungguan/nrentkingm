<?php
/**
 * admin.rentking.co.kr/sho.php?code=faq
 * 관리자페이지 > 사이트관리 > 자주하는질문
 * 자주하는질문 리스트 페이지
 */
$mode = $_REQUEST['mode'];
$key = $_REQUEST['key'];
$keyword = trim($_REQUEST['keyword']);
if($mode=='d')
{
	$idx = $_GET['idx'];
	$q = "UPDATE faq SET dt_delete = NOW() WHERE idx='$idx'";
	$r = mysql_query($q);

	echo "<script>alert('삭제하였습니다.'); location.replace('sho.php?code=$code'); </script>";
	exit;
}
?>
<script language="javascript">
function OptionSelect(n)
{
	if($("#opt_"+n).css('display')=='none')
	{	$("#opt_"+n).show();	}
	else
	{	$("#opt_"+n).hide();	}
}


</script>

<div class='site_select' style="height:33px">
		<div style="float:left;margin-top:4px"">검색조건 : </div>
		<div style="float:left">
		<form id="search" name="search" action="sho.php?code=<?=$code;?>" method="post">
<!--			<input type="hidden" name="fid" value=""/>-->
<!--			<input type="hidden" name="faqcate" value=""/>-->

			<select class="uch" name='key'>
				<option value='title' <? if($key=='title') { echo "selected"; } ?>>제목</option>
				<option value='content' <? if($key=='content') { echo "selected"; } ?>>내용</option>

			</select>
			<input type='text' name='keyword' size='40' value="<?=$keyword;?>">
			<span class="btn_white_s btn_submit" data-form="#search"><a href="javascript:" >검색하기</a></span>
		</form><!-- // form[name"search"] -->
		</div>
</div>	<!-- // .site_select -->
<div style="clear:both;"></div>

<div class="btn_wrap btn_center" style="text-align:right;margin-bottom:5px">
	<span class="btn_green"><a href="sho.php?code=<?=$code;?>w">FAQ등록</a></span>
</div>
<div class="list_wrap order_list">
<table class="listTableColor">
<colgroup>
	<col width="45px" />

	<col width="" />
	<col width="180px" />
	<col width="100px" />
</colgroup>
<thead>
<tr>
<th class=kor8>No</th>

<th class=kor8>제목</th>
<th class=kor8>최종수정</th>
<th class=kor8>조회수</th>
<th class=kor8></th>
</tr>
</thead>
<tbody>
<?php
$page = $_GET['page'];
if(!$page)
{	$page = 1;	}


$num_per_page = 20;
$page_per_block = 10;

$q= "SELECT COUNT(idx) FROM faq WHERE dt_delete IS NULL";
if($keyword!='')
{	$q = $q . " and REPLACE($key,' ','') like '%".str_replace(" ","",$keyword)."%'";	}
$r = mysql_query($q);
$total_record = mysql_result($r,0,0);
mysql_free_result($r);

$total_page = ceil($total_record/$num_per_page);

if($total_record == 0)
	{ $first = 1;
	  $last = 0; }
else
	{ $first = $num_per_page*($page-1);
	  $last = $num_per_page*$page; }

$q = "SELECT * FROM faq WHERE dt_delete IS NULL ";
if($keyword!='')
{	$q = $q . " and REPLACE($key,' ','') like '%".str_replace(" ","",$keyword)."%'";	}
$q = $q . " order by idx desc limit $first, $num_per_page";

$r = mysql_query($q);
$cou = 1;
$article_num = $total_record - (($page-1)*$num_per_page);
while($row = mysql_fetch_array($r))
{
	$co = "";
	if(!($cou%2)) $co = "gray";
	
?>
		<tr class='<?=$co;?>' onmouseover="this.style.backgroundColor='#F6F6F6'" onmouseout="this.style.backgroundColor=''">
		<td class="first"><?=$row['idx'];?></td>
		<td style='text-align:left;padding-left:5px;'><a href="javascript:OptionSelect(<?=$cou;?>);"><?=$row['title'];?></a></td>
		<td><?=$row['dt_create'];?></td>
		<td><?=$row['read_count']?></td>
		<td><span class="btn_white_xs"><a href="sho.php?code=<?=$code;?>m&idx=<?=$row['idx'];?>">수정</a></span> | <span class="btn_white_xs"><a href="javascript:delok('삭제하시겠습니까?','sho.php?code=<?=$code;?>&idx=<?=$row['idx'];?>&mode=d');">삭제</a></span></td>
		</tr>
		<tr id='opt_<?=$cou;?>' style="display:none;">
		<td style="padding:10pt;text-align:left;" colspan='7'><?= nl2br($row['content']) ?></td></tr>
<?php
	$article_num--;
	$cou++;
}
?>
</tbody>
</table>	
</div><!-- // .list_wrap -->


<div class="paging">
<?php
############### 게시물 페이지 이동
$total_block = ceil($total_page/$page_per_block);
$block = ceil($page/$page_per_block);

$first_page = ($block-1)*$page_per_block;
$last_page = $block*$page_per_block;

if($total_block <= $block)
{	$last_page = $total_page;	}

###########################

if($block>1)
{
	$before_page = $first_page;
	echo ("<span class='btn_white_xs btn_prev'><a href=sho.php?page=$before_page&code=$code&key=$key&keyword=$keyword><i class='fa fa-angle-left'></i>이전</a></span>");
}

for($direct_page = $first_page+1; $direct_page <= $last_page;$direct_page++)	
{
	if($page==$direct_page)
	{	echo ("<strong class='current'>$direct_page</strong>");	}
	else
	{	echo ("<a href=sho.php?page=$direct_page&code=$code&key=$key&keyword=$keyword>$direct_page</a>");	}
}
if($block<$total_block)
{
	$daum_page = $last_page+1;
	echo ("<span class='btn_white_xs btn_next'><a href=sho.php?page=$daum_page&code=$code&key=$key&keyword=$keyword>다음<i class='fa fa-angle-right'></i></a></span>");
}
?>
</div><!-- // .paging -->
