<?php

include $_SERVER['DOCUMENT_ROOT']."/old/inc/base.php";
include $config['incPath']."/connect.php";

/*
 * vehicle_std.fuel, code.idx  n:n관계를 연결하는 테이블 vehicle_attribute_std에 기존 데이터 넣음
 */

$query = "
	SELECT
		idx, fuel
	FROM
		vehicle_std
";

$r = mysql_query($query);
$insertValues = '';

while($row = mysql_fetch_row($r)) {
	$fuel = explode('|', $row[1]);
	foreach($fuel as $item) {
		if($insertValues != '') {
			$insertValues .= ',';
		}
		$insertValues .= "(".$row[0].", ".$item.", NOW())";
	}
}

$query = "
	INSERT
	INTO vehicle_std_fuel_code(vehicle_std_idx, code_idx, dt_create)
	VALUES $insertValues
";
$r = mysql_query($query);