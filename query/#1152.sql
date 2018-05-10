
ALTER TABLE `marketdb`
	ADD COLUMN `dt_next_payment` DATE NULL DEFAULT NULL COMMENT '다음 결제일' AFTER `extend_marketdb_idx`;

UPDATE
		marketdb m
		LEFT JOIN marketdb_tmp mt ON m.tmp_idx = mt.idx
SET
	m.dt_next_payment = DATE(mt.sdate + INTERVAL (SELECT MAX(turn) FROM marketdb_accounts WHERE market_idx = m.idx AND tbtype = 'I') MONTH)
WHERE
	m.idx IN (366,582,615,673,755,859,877,1165,1173,1332,1413,1414,1444,1479,1523,1547,1551,1574,1630,1637,1689,1705);