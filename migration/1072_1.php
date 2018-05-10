<?php

include $_SERVER['DOCUMENT_ROOT']."/old/inc/base.php";
include $config['incPath']."/connect.php";

/*
 * account_transfer 테이블 생성에 따른 기존 정산데이터의
 * payment_accounts.dt_settlement_finish컬럼 삭제,
 * account_transfer 데이터 생성,
 * payment_accounts.account_transfer_idx 삽입
 */
$query = "SELECT 
			pa.idx,
			pa.dt_settlement_finish,
			pa.account,
			r2.bank,
			r2.bankaccount,
			r2.bankname,
			r2.bankholder
		FROM payment_accounts pa
		LEFT JOIN payments p ON pa.payment_idx = p.idx
		LEFT JOIN reservation r ON p.reservation_idx = r.idx
		LEFT JOIN vehicle v ON r.vehicle_idx = v.idx
		LEFT JOIN rentshop r2 ON v.rentshop_idx = r2.idx
		WHERE pa.dt_settlement_finish IS NOT NULL";
$r = mysql_query($query);

while($row = mysql_fetch_assoc($r)) {
	$set = microtime(true);
	$query = "INSERT INTO account_transfer(bank, bankaccount, bankname, bankholder, account, info_withdraw, info_deposit, rawdata, dt_create) VALUES (
			'{$row['bank']}', '{$row['bankaccount']}', '{$row['bankname']}', '{$row['bankholder']}', '{$row['account']}', null, null, null, '{$row['dt_settlement_finish']}'
		)";
	mysql_query($query);
	$idx = mysql_insert_id();

	$query = "UPDATE payment_accounts SET account_transfer_idx = $idx WHERE idx = {$row['idx']}";
	mysql_query($query);

	echo floor((microtime(true) - $set) * 1000).'<br />';
}