<?
$key = $_REQUEST['key'];
$keyword = $_REQUEST['keyword'];
?>
<div class="content">

<div class="form_wrap">
<form id="search" name="search" action="<?=$PHP_SELF;?>?code=<?=$code;?>" method="post">
<table class="detailTable2">
<tR>
<Th>검색조건</th>
<td>
	<select class="uch" name='key'>
		<option value='name' <? if($key=='name') { echo "selected"; } ?>>쿠폰이름</option>
	</select>
	<input type='text' name='keyword' size='60' value="<?=$keyword;?>" class="basic_input" onKeyPress="javascript:if(event.keyCode == 13) { form.submit() }" >
	
</td>
</tR>
</table>

<div class="btn_wrap btn_center btn_bottom">
	<span class="btn_green btn_submit" data-form="#search"><a href="javascript:">검색하기</a></span>
</div>
<input type="submit" style="display:none;"/>
</form><!-- // form[name="search"] -->
</div><!-- // .form_wrap -->


<div class="btn_wrap btn_center" style="text-align:right;margin-bottom:5px">
	<span class="btn_green">
		<a href="<?=$PHP_SELF;?>?code=<?=$code;?>w">
		등록하기
		</a>
	</span>
</div>


<table class="listTableColor">
<colgroup>
	<col width="45px" />
	<col width="80px" />
	<col width="" />
	<col width="70px" /><!-- 할인율 -->
	<col width="70px" /><!-- 쿠핀종류 -->
	<col width="150px" /><!-- 배포기간 -->
	<col width="90px" />
	<col width="" />
	<col width="" />
	<col width="80px" />
	<col width="80px" />
	<col width="110px" />
	<col width="90px" />
</colgroup>
<thead>
<tr>
	<th>번호</th>
	<th>IMG</th>
	<th>쿠폰명</th>
	<th>할인율/금액</th>
	<th>베포기간</th>
	<th>유효기간</th>
	<th>발급쿠폰</th>
	<th>사용쿠폰</th>
	<th>종류</th>
	<th></th>
	<th></th>
</tr>
</thead>
<tbody>
	
<?php
$page = $_GET['page'];
if(!$page)
{	$page = 1;	}
$num_per_page = 20;
$page_per_block = 10;

$len_cate = strlen($cate);

$q= "select count(idx) from coupons where dt_delete IS NULL";
if($keyword!='')
{	$q = $q . " and $key like '%$keyword%'";	}

$r = mysql_query($q);
$total_record = mysql_result($r,0,0);
mysql_free_result($r);

$total_page = ceil($total_record/$num_per_page);

if($total_record == 0)
	{ $first = 0;
	  $last = 0; }
else
	{ $first = $num_per_page*($page-1);
	  $last = $num_per_page*$page; }

$q = "select c.*, f.path from coupons c LEFT JOIN files f ON c.idx = f.article_idx AND article_type = 'coupon' where c.dt_delete IS NULL";
if($keyword!='')
{	$q = $q . " and $key like '%$keyword%'";	}
$q = $q . " order by idx desc limit $first, $num_per_page";
$r = mysql_query($q);
$article_num = $total_record - (($page-1)*$num_per_page);
$cou = 0;
while($row = mysql_fetch_array($r))
{
	$co = "";
	if(!($cou%2)) $co = "gray";
?>

<tr class='<?=$co;?>' onmouseover="this.style.backgroundColor='#F6F6F6'" onmouseout="this.style.backgroundColor=''" >
<td class="first"><?=$row[idx];?></td>
<td><? if($row['path']!=''){?><img src="<?=$config['imgServer']?><?=$row['path'];?>" style='max-width:140px;'><?}?></td>
<td><?=$row[name];?><br/>/mypage/rcoupon.php?idx=<?=$row[idx];?></td>
<td>
	
<?php
if($row[actype]=='3')
{	echo "배송비면제";	}
else
{
	echo number_format($row[account]);
	if($row[actype]=='1') { echo "원";	} else { echo "%";	}

}
?>

</td>
<td><?=$row['dt_publish_start'];?> ~ <?=$row['dt_publish_end'];?></td>
<td>
	<? 
		echo $row['dt_use_start'] ." ~ ". $row['dt_use_end'];
	?>
</td>
<tD>
<?
	$q2 = "select count(idx) from member_coupons where coupon_idx='$row[idx]'";
	$r2 = mysql_query($q2);
	$row2 = mysql_fetch_array($r2);

	$u1 = $row2[0];

	echo number_format($u1);
?>
</td>
<tD>
<?
	$q2 = "select count(idx) from member_coupons where coupon_idx='$row[idx]'";
	$q2 = $q2 ." and dt_use IS NOT NULL";
	$r2 = mysql_query($q2);
	$row2 = mysql_fetch_array($r2);

	$u2 = $row2[0];

	echo number_format($u2);
?>
</td>
<td>
	<?
	switch($row['type']) {
		case 'LO':
			echo '로그인';
			break;
		case 'SI':
			echo '회원가입';
			break;
		case 'SE':
			echo '시리얼<br />[' . $row['serialnum'] .']';
			break;
		case 'RS':
			echo '랜덤시리얼';
			break;
	}
	?>
</td>
<td>
	<span class="btn_white_xs"><a href="<?=$PHP_SELF;?>?code=<?=$code;?>m&idx=<?=$row[idx];?>">조회</a></span>
	<span class="btn_white_xs"><a href="<?=$PHP_SELF;?>?code=<?=$code;?>l&idx=<?=$row[idx];?>">발급내역</a></span>
</td>
<td>
	<? if($row[type]=='RS') {?><span class="btn_white_xs"><a href="<?=$PHP_SELF;?>?code=<?=$code;?>s&idx=<?=$row[idx];?>">시리얼관리</a></span><?}?>
</td>
</tr>
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
	echo ("<span class='btn_white_xs btn_prev'><a href=$PHP_SELF?page=$before_page&code=$code&sgcate=$sgcate&key=$key&keyword=$keyword><i class='fa fa-angle-left'></i>이전</a></span>");
}

for($direct_page = $first_page+1; $direct_page <= $last_page;$direct_page++)	
{
	if($page==$direct_page)
	{	echo ("<strong class='current'>$direct_page</strong>");	}
	else
	{	echo ("<a href=$PHP_SELF?page=$direct_page&code=$code&sgcate=$sgcate&key=$key&keyword=$keyword>$direct_page</a>");	}
}
if($block<$total_block)
{
	$daum_page = $last_page+1;
	echo ("<span class='btn_white_xs btn_next'><a href=$PHP_SELF?page=$daum_page&code=$code&sgcate=$sgcate&key=$key&keyword=$keyword>다음<i class='fa fa-angle-right'></i></a></span>");
}
?>

</div><!-- // .paging -->
</div><!-- // .content -->