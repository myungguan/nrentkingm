<?php

include $_SERVER['DOCUMENT_ROOT']."/old/inc/base.php";
include $config['incPath']."/connect.php";


/*
* 가맹점의 계좌 오픈플렛폼을 통해 체크
*/

$query = "
	SELECT
		*
	FROM rentshop r
	WHERE (bank_transfer_status = 'N' OR bank_transfer_status IS NULL) 
		AND bank != ''
		AND bankaccount != ''
		AND bankname != ''
		AND bankholder != ''
";
$r = mysql_query($query);

while($row = mysql_fetch_row($r)) {

	$result = transferCheckAccount($row['bank'], $row['bankaccount'], $row['bankname'], $row['bankholder']);

	if($result['result'] == 'SUCCESS') {
		$query = "
			UPDATE rentshop
			SET bank_transfer_status = 'Y'
			WHERE idx = {$row['idx']}
		";

		mysql_query($query);
	}
}
