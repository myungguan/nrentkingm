CREATE TABLE `rentshop_reservation_limit` (
	`idx` INT NOT NULL AUTO_INCREMENT,
	`rentshop_idx` INT NULL DEFAULT NULL COMMENT 'rentshop.idx',
	`sdate` DATE NULL DEFAULT NULL COMMENT '시작일',
	`edate` DATE NULL DEFAULT NULL COMMENT '종료일',
	`hour` INT NULL DEFAULT NULL COMMENT '최소예약시간',
	`dt_create` DATETIME NULL DEFAULT NULL COMMENT '생성일',
	PRIMARY KEY (`idx`)
)
	COMMENT='최소예약시간'
	COLLATE='utf8_general_ci'
;



-- 운영시 배포
ALTER TABLE `rentshop_off`
	CHANGE COLUMN `com_idx` `rentshop_idx` INT(10) UNSIGNED NOT NULL COMMENT 'site_rentshop.index_no' AFTER `idx`,
	CHANGE COLUMN `wdate` `dt_create` DATETIME NOT NULL COMMENT '등록일' AFTER `edate`;


ALTER TABLE `rentshop_loca`
	CHANGE COLUMN `com_idx` `rentshop_idx` INT(10) UNSIGNED NOT NULL COMMENT 'rentshop.idx' AFTER `idx`;