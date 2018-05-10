<?php /* Template_ 2.2.8 2017/11/15 17:26:42 D:\Documents\projects\rentking\trunk\www\old\sites\tpls\rentkingw\ad\list.htm 000001097 */ 
$TPL_list_1=empty($TPL_VAR["list"])||!is_array($TPL_VAR["list"])?0:count($TPL_VAR["list"]);?>
<!DOCTYPE html>
<html lang="ko">
<head>
<?php $this->print_("head",$TPL_SCP,1);?>

</head>
<body>
<?php $this->print_("header",$TPL_SCP,1);?>

	<div id="contentWrap" style="padding-top:50px;">
		<table class="list" style="width:100%;">
				<thead>
				<tr>
					<th style="width:120px;">번호</th>
					<th class="etit">제목</th>
					<th style="width:150px;">게시일</th>
				</tr>
				</thead>
				<tbody>
<?php if($TPL_list_1){foreach($TPL_VAR["list"] as $TPL_V1){?>
				<tr>
					<td style="padding:14px 0;"><?php echo $TPL_V1["idx"]?></td>
					<td><a href="/ad/view.php?idx=<?php echo $TPL_V1["idx"]?>"><?php echo $TPL_V1["title"]?></a></td>
					<td><?php echo $TPL_V1["dt_create"]?></td>
				</tr>
<?php }}?>
				</tbody>
			</table><!--//boxTop-->
	</div>

<?php $this->print_("footer",$TPL_SCP,1);?>

</body>
</html>