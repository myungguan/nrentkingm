ALTER TABLE `marketdb_card`
	COMMENT='정기결제 카드정보';
RENAME TABLE `marketdb_card` TO `card_fix`;


ALTER TABLE `marketdb`
	CHANGE COLUMN `buymethod` `buymethod` CHAR(1) NOT NULL COMMENT '결제 방법(C: 카드결제, F: 정기결제)' AFTER `totalaccount`;

ALTER TABLE `marketdb`
	ADD COLUMN `card_fix_idx` INT NULL DEFAULT NULL COMMENT '정기결제카드' AFTER `buymethod`;

UPDATE marketdb m
	LEFT JOIN card_fix cf ON m.mem_idx = cf.mem_idx AND cf.isdel = 'N'
SET m.card_fix_idx = cf.idx
WHERE m.buymethod = 'F';

ALTER TABLE `marketdb_accounts`
	ADD COLUMN `card_fix_idx` INT NULL DEFAULT NULL COMMENT '정기결제카드' AFTER `buymethod`;

UPDATE marketdb_accounts ma
	LEFT JOIN marketdb m ON ma.market_idx = m.idx
	LEFT JOIN card_fix cf ON m.card_fix_idx = cf.idx
SET ma.card_fix_idx = cf.idx
WHERE ma.buymethod = 'F'