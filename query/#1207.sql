ALTER TABLE `vehicle_price`
	ADD COLUMN `price_insu1_limit` INT(11) NOT NULL DEFAULT '0' COMMENT '자차1 보상한도' AFTER `price_insu1_exem`,
	ADD COLUMN `price_insu2_limit` INT(11) NOT NULL DEFAULT '0' COMMENT '자차2 보상한도' AFTER `price_insu2_exem`,
	ADD COLUMN `price_insu3_limit` INT(11) NOT NULL DEFAULT '0' COMMENT '자차3 보상한도' AFTER `price_insu3_exem`;


ALTER TABLE `reservation`
	ADD COLUMN `insu_limit` INT(10) UNSIGNED NULL DEFAULT NULL COMMENT '자차보상한도' AFTER `insu_exem`;

ALTER TABLE `reservation`
	CHANGE COLUMN `idx` `idx` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT FIRST,
	CHANGE COLUMN `car_idx` `car_idx` INT(11) UNSIGNED NOT NULL COMMENT 'site_vehicle.index_no' AFTER `session_id_real`,
	CHANGE COLUMN `market_idx` `market_idx` INT(11) UNSIGNED NULL DEFAULT NULL COMMENT '(사용안함)' AFTER `car_idx`,
	CHANGE COLUMN `pricetype` `pricetype` INT(11) NOT NULL DEFAULT '1' COMMENT '가격정책(1: 단기요금, 2:장기요금)' AFTER `retype`,
	CHANGE COLUMN `ddata1` `ddata1` INT(11) UNSIGNED NOT NULL COMMENT '대여기간(단기: 일 / 장기: 개월)' AFTER `pricetype`,
	CHANGE COLUMN `ddata2` `ddata2` INT(11) UNSIGNED NOT NULL COMMENT '대여기간(단기: 시간 / 장기: 일)' AFTER `ddata1`,
	CHANGE COLUMN `account` `account` INT(11) UNSIGNED NOT NULL COMMENT '차량 대여요금' AFTER `makedate`,
	CHANGE COLUMN `account1` `account1` INT(11) UNSIGNED NOT NULL COMMENT '장기대여시 월요금' AFTER `account`,
	CHANGE COLUMN `account2` `account2` INT(11) UNSIGNED NOT NULL COMMENT '장기대여시 잔여요금' AFTER `account1`,
	CHANGE COLUMN `preaccount` `preaccount` INT(11) UNSIGNED NOT NULL COMMENT '보증금' AFTER `account2`,
	CHANGE COLUMN `insuac` `insuac` INT(11) UNSIGNED NOT NULL COMMENT '보험료' AFTER `insu`,
	CHANGE COLUMN `insu_exem` `insu_exem` INT(11) UNSIGNED NULL DEFAULT NULL COMMENT '면책금' AFTER `insuac`,
	CHANGE COLUMN `insu_limit` `insu_limit` INT(11) UNSIGNED NULL DEFAULT NULL COMMENT '자차보상한도' AFTER `insu_exem`,
	CHANGE COLUMN `totalaccount` `totalaccount` INT(11) UNSIGNED NOT NULL COMMENT '총금액 (account + dealac + insuac)' AFTER `insu_limit`;

ALTER TABLE `vehicle_price_longterm`
	ADD COLUMN `price_longterm_insu_limit` INT(11) NOT NULL DEFAULT '0' COMMENT '자차 보상한도' AFTER `price_longterm_insu_exem`,
	ADD COLUMN `distance_limit` INT(11) NOT NULL DEFAULT '0' COMMENT '주행거리 제한' AFTER `price_longterm_insu_limit`,
	ADD COLUMN `distance_additional_price` INT(11) NOT NULL DEFAULT '0' COMMENT '추가거리 요금' AFTER `distance_limit`;

ALTER TABLE `reservation`
	ADD COLUMN `distance_limit` INT(11) UNSIGNED NULL DEFAULT NULL COMMENT '주행거리 제한' AFTER `insu_limit`,
	ADD COLUMN `distance_addtional_price` INT(11) UNSIGNED NULL DEFAULT NULL COMMENT '추가거리 요금' AFTER `distance_limit`;


ALTER TABLE `reservation`
	CHANGE COLUMN `distance_addtional_price` `distance_additional_price` INT(11) UNSIGNED NULL DEFAULT NULL COMMENT '추가거리 요금' AFTER `distance_limit`;

