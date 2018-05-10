
ALTER TABLE `marketdb_accounts`
	ADD COLUMN `settlement_per` DATETIME NULL DEFAULT NULL COMMENT '정산수수료' AFTER `dt_settlement_finish`;


ALTER TABLE `marketdb_accounts`
	CHANGE COLUMN `settlement_per` `settlement_per` FLOAT NULL DEFAULT NULL COMMENT '정산수수료' AFTER `dt_settlement_finish`;

UPDATE marketdb_accounts SET settlement_per = NULL;
UPDATE marketdb_accounts ma SET ma.settlement_per = (SELECT r.per1 FROM marketdb m LEFT JOIN marketdb_tmp mt ON m.tmp_idx = mt.idx LEFT JOIN vehicle v ON mt.car_idx = v.idx LEFT JOIN rentshop r ON v.com_idx = r.idx WHERE m.idx = ma.market_idx) WHERE ma.tbtype='I';