ALTER TABLE `rentshop`
	ADD COLUMN `contact1` VARCHAR(20) NULL DEFAULT NULL COMMENT '문자수신연락처1' AFTER `dbirth3`,
	ADD COLUMN `contact2` VARCHAR(20) NULL DEFAULT NULL COMMENT '문자수신연락처2' AFTER `contact1`,
	ADD COLUMN `contact3` VARCHAR(20) NULL DEFAULT NULL COMMENT '문자수신연락처3' AFTER `contact2`;

UPDATE rentshop SET contact1=dcp2 WHERE dcp2 IS NOT NULL AND LENGTH(TRIM(dcp2)) > 0;