
ALTER TABLE `marketdb_accounts`
	ADD COLUMN `dt_settlement` DATE NULL DEFAULT NULL COMMENT '정산일' AFTER `up_idx`;

ALTER TABLE `marketdb_accounts`
	CHANGE COLUMN `dt_settlement` `dt_settlement` DATE NULL DEFAULT NULL COMMENT '정산예정일' AFTER `up_idx`,
	ADD COLUMN `dt_settlement_finish` DATETIME NULL DEFAULT NULL COMMENT '정산완료일시' AFTER `dt_settlement`;


SELECT mt.* FROM marketdb m LEFT JOIN marketdb_tmp mt ON m.tmp_idx = mt.idx  WHERE m.idx=36