<?php
/**
 * Created by Sanggoo.
 * Date: 2017-08-09
 */

include $_SERVER['DOCUMENT_ROOT']."/old/inc/base.php";
include $config['incPath']."/connect.php";

$query = "SELECT * FROM payments WHERE namesub <> '' OR cpsub <> '' OR kindsub <> '' OR numsub <>'' OR date1sub <> '' OR date2sub <> ''";
$r = mysql_query($query);

while($row = mysql_fetch_assoc($r)) {
	$query = "INSERT INTO driver_license(name, cp, kinds, nums, date1, date2) VALUES (
			'{$row['namesub']}', '{$row['cpsub']}', '{$row['kindsub']}', '{$row['numsub']}', '{$row['date1sub']}', '{$row['date2sub']}'
		)";
	mysql_query($query);
	$idx = mysql_insert_id();
	$query = "UPDATE payments SET driver_license_idx = $idx WHERE idx = {$row['idx']}";
	mysql_query($query);
}