<?
include $_SERVER['DOCUMENT_ROOT']."/old/inc/base.php";
include $config['incPath']."/connect.php";
include $config['incPath']."/session.php";
include $config['incPath']."/config.php";
include "../adminhead.php";

$idx = $_REQUEST['idx'];
$ar_member = sel_query_all("member"," where idx='$idx'");
$mode = $_REQUEST['mode'];
if($mode=='w')
{
	$coupon = $_REQUEST['coupon'];
	$ar_coupon = sel_query_all("coupons"," where idx='$coupon'");
	
	make_coupon($ar_coupon,$idx);


	echo "<script>alert('처리하였습니다.'); location.replace('$PHP_SELF?idx=$idx');</script>";
	exit;
}
if($mode=='d')
{
	$c_idx = $_REQUEST['c_idx'];

	mysql_query("UPDATE member_coupons SET dt_use = NOW() where idx='$c_idx'");

	echo "<script>alert('처리하였습니다.'); location.replace('$PHP_SELF?idx=$idx');</script>";
	exit;
}
?>
<script type="text/javascript">
function init(){
  var doc = document.getElementById('content');
  if(doc.offsetHeight == 0){
  } else {
  pageheight = doc.offsetHeight;

  parent.document.getElementById("iframebody").height = pageheight+"px";
  }
}
window.onload = function(){
  init();
}
function foch()
{
	if(document.form1.coupon.options.value=='')
	{
		alert('발급 쿠폰을 선택하시요.');
		document.form1.coupon.focus();
		return false;
	}
	if(!document.form1.memo.value)
	{
		alert('사유를 입력');
		document.form1.memo.focus();
		return false;
	}
	return true;
}
</script>
</head>
<body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0">
<div id="content" style="position:absolute;left:0;top:0;width:100%;"> 
	
	<form id="form1" name="form1" action="<?=$PHP_SELF;?>" method="post" onsubmit="return foch();">
	<input type='hidden' name='mode' value='w'>
	<input type='hidden' name='idx' value='<?=$idx;?>'>
	<div class="form_wrap">
	<table class="detailTable2">
	<tr>
	<th>지급쿠폰</th>
	<td><select class="uch" name='coupon'>
	<option value=''>선택</option>
	<?php
	$q = "select * from coupons";
	$r = mysql_query($q);
	while($row = mysql_fetch_array($r))
	{	echo "<option value='$row[idx]'>$row[name]</option>";	}
	?>
	</select>
	사유 : <input type='text' name='memo' size='30'>
	</td>
	</tr>
	</table>
	<div class="btn_wrap btn_center btn_bottom">
		<span class="btn_green btn_submit" data-form="#form1"><a href="javascript:">지급하기</a></span>
	</div>
	</div><!-- // .form_wrap -->
	</form><!-- // form[name="form1"] -->
	
	
	<div class="list_wrap order_list">
	<table class="listTableColor">
	<thead>
	<tr> 
	<th>NO</th>
	<th>쿠폰명</th>
	<th>발행일</th>
	<th>유효기간</th>
	<th>할인</th>
	<th>사용여부</th>
	<th>사유</th>
	<th></th>
	</tr>   
	</thead>
	<tbody>
<?php
$q = "select * from member_coupons where member_idx='$idx'";
$r = mysql_query($q);
$cou = 1;
while($row = mysql_fetch_array($r))
{
	$co = "";
	if(!($cou%2)) $co = "gray";

	$ar_coupon = sel_query_all("coupons"," where idx='$row[coupon_idx]'");
?>
	<tr class='<?=$co;?>'> 
	<td><?=$cou;?></td>
	<td><?=$ar_coupon['name'];?></td>
	<Td><?=$row['dt_create'];?></td>
	<Td><?=$row['edate'];?>까지</td>
	<td>
	<?php
		echo number_format($ar_coupon['account']);
		if($ar_coupon['actype']=='1') { echo "원";	} else { echo "%";	}
	?>
	</td>
	<td><? if($row['dt_use']!='') { echo $row['dt_use']."사용";	}?></td>
	<td><?=$row['memo'];?></tD>
	<td>
	<?php
	if(!$row['dt_use'])
	{
	?>
	<span class="btn_white_xs"><a href="javascript:delok('사용처리 하시겠습니까?', '/frame_data/mem_cplist.php?code=<?=$code;?>&mode=d&c_idx=<?=$row['idx'];?>&idx=<?=$idx;?>');">사용처리</a></span>
	<?php
	}
	?>
	</tr>
<?php
	$cou++;
}
?>
</tbody>
</table>
</div><!-- // .list_wrap -->
</div><!-- // #content -->
</body>
</html>