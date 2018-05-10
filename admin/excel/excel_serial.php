<?
/**
 * 엑셀 시리얼
 */

ini_set('memory_limit', -1);
header("Content-type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=member_" . date("Y-m-d", time()) . ".xls");
header("Content-Description: PHP4 Generated Data");
include $_SERVER['DOCUMENT_ROOT']."/old/inc/base.php";
include $config['incPath']."/connect.php";
include $config['incPath']."/session.php";
include $config['incPath']."/config.php";
$idx = $_REQUEST['idx'];
?>
<html> 
	<head>
		<meta http-equiv="Content-type" content="application/vnd.ms-excel;charset=utf-8">
	</head>
	<body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0">
		<table border="1" cellpadding="0" cellspacing="1">
			<thead>
				<tr>
					<th class=kor8>No</th>
					<th class=kor8>시리얼</th>
					<th class=kor8>생성일</th>
				</tr>
				<?php
				$idx = $_REQUEST['idx'];
				$q = "SELECT * FROM coupon_serials WHERE coupon_idx='{$idx}'";
				$q = $q . " ORDER BY idx ASC";
				$r = mysql_query($q);
				$cou = 1;
				while($row = mysql_fetch_array($r))
				{
				?>
				<tr>
					<td><?=$cou;?></td>
					<td><?=$row['serialnum'];?></td>
					<td><?=$row['dt_create'];?></td>
				</tr>
				<?
					$cou++;
				}
				?>
		</table>
	</body>
</html> 