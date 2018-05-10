<?php
/**
 * Created by Sanggoo.
 * Date: 2017-07-03
 */

include $_SERVER['DOCUMENT_ROOT']."/old/inc/base.php";
include $config['incPath']."/connect.php";

$query = "SELECT idx, rawdata FROM log_search WHERE rawdata IS NOT NULL AND rawdata <> ''";
$r = mysql_query($query);
while($row = mysql_fetch_assoc($r)) {
	$idx = $row['idx'];
	$rawdata = json_decode($row['rawdata'], true);

	$grade = str_replace('||', '|', preg_replace('/^\\|(.*)\\|$/', '$1', $rawdata['grade']));
	$ptype = str_replace('||', '|', preg_replace('/^\\|(.*)\\|$/', '$1', $rawdata['ptype']));
	$insu = str_replace('||', '|', preg_replace('/^\\|(.*)\\|$/', '$1', $rawdata['insu']));
	$modelQuery = $rawdata['modelQuery'];
	$model = $rawdata['model'];
	if($modelQuery)
		$model .= '|'.$modelQuery.'|';
	$model = str_replace('||', '|', preg_replace('/^\\|(.*)\\|$/', '$1', $model));
	$fuel = str_replace('||', '|', preg_replace('/^\\|(.*)\\|$/', '$1', $rawdata['fuel']));
	$option = str_replace('||', '|', preg_replace('/^\\|(.*)\\|$/', '$1', $rawdata['option']));
	$outdate = str_replace('||', '|', preg_replace('/^\\|(.*)\\|$/', '$1', $rawdata['outdate']));
	$colorQuery = $rawdata['colorQuery'];
	$color = $rawdata['color'];
	if($colorQuery)
		$color .= '|'.$colorQuery.'|';
	$color = str_replace('||', '|', preg_replace('/^\\|(.*)\\|$/', '$1', $color));
	$companyQuery = $rawdata['companyQuery'];
	$company = $rawdata['company'];
	if($companyQuery)
		$company .= '|'.$companyQuery.'|';
	$company = str_replace('||', '|', preg_replace('/^\\|(.*)\\|$/', '$1', $company));
	$nosmoke = $rawdata['nosmoke'];

	$query = "UPDATE log_search SET
			grade = '$grade',
			ptype = '$ptype',
			insu = '$insu',
			model = '$model',
			fuel = '$fuel',
			`option` = '$option',
			outdate = '$outdate',
			color = '$color',
			company = '$company',
			nosmoke = '$nosmoke'
		WHERE idx=$idx
	";
	mysql_query($query);
}