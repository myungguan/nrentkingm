<?php
/**
 *
 * Created by Sanggoo.
 * Date: 2017-03-16
 * site_vehicle_price_longterm 에 price_longterm_insu_exem 추가
*/

include $_SERVER['DOCUMENT_ROOT']."/old/inc/base.php";
include $config['incPath']."/connect.php";

$r = mysql_query("SELECT v.price_idx, v.price_longterm_idx, vp.* FROM site_vehicle v LEFT JOIN site_vehicle_price vp ON v.price_idx = vp.idx");
while($row = mysql_fetch_array($r)) {
	$query = "UPDATE site_vehicle_price_longterm SET price_longterm_insu_exem = {$row['price_insu_exem']} WHERE idx = {$row['price_longterm_idx']}";
	mysql_query($query);
}