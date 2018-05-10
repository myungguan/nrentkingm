<?php
/**
 * Created by Sanggoo.
 * Date: 2017-07-31
 */

include $_SERVER['DOCUMENT_ROOT']."/old/inc/base.php";
include $config['incPath']."/connect.php";

$query = "SELECT
	m.idx payment_idx,
	ma.idx,
	ma.turn,
	mt.retype,
	mt.sdate,
	mt.edate
FROM payment_accounts ma
	LEFT JOIN payments m ON ma.payment_idx = m.idx
	LEFT JOIN reservation mt ON m.tmp_idx = mt.idx
WHERE
	ma.tbtype = 'I'
	AND ma.dt_settlement IS NULL
	AND m.idx IS NOT NULL
	AND mt.idx IS NOT NULL";
$r = mysql_query($query);

while($row = mysql_fetch_assoc($r)) {
	$row['dt_settlement'] = getSettlementDate($row['retype'], $row['sdate'], $row['edate'], $row['turn']);
	$query = "UPDATE payment_accounts SET dt_settlement = '{$row['dt_settlement']}' WHERE idx={$row['idx']}";
	mysql_query($query);
}