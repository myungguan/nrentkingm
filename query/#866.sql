CREATE TABLE `log_search` (
	`idx` BIGINT NOT NULL AUTO_INCREMENT,
	`sdate` DATETIME NULL DEFAULT NULL COMMENT '픽업날짜',
	`edate` DATETIME NULL DEFAULT NULL COMMENT '반납날짜',
	`addr` VARCHAR(200) NOT NULL DEFAULT '0' COMMENT '검색주소',
	`retype` CHAR(1) NOT NULL DEFAULT '1' COMMENT '대여기간(1: 단기대여 / 2: 장기대여)',
	`rentshop` INT NOT NULL DEFAULT '0' COMMENT '검색업체',
	`total` INT NOT NULL DEFAULT '0' COMMENT '검색차량',
	`rawdata` VARCHAR(1000) NOT NULL DEFAULT '0' COMMENT '검색데이터',
	`dt` DATETIME NULL DEFAULT NULL COMMENT '검색일시',
	PRIMARY KEY (`idx`)
)
	COMMENT='사용자 검색 로그'
	COLLATE='utf8_general_ci'
;


ALTER TABLE `log_search`
	ADD INDEX `dt` (`dt`);


ALTER TABLE `log_search`
	ADD COLUMN `count` INT NOT NULL DEFAULT '0' COMMENT '검색내 검색 횟수' AFTER `edate`;


ALTER TABLE `log_search`
	ADD COLUMN `distance` INT NOT NULL DEFAULT '0' COMMENT '검색반경' AFTER `addr`;


ALTER TABLE `log_search`
	ADD COLUMN `ip` VARCHAR(30) NOT NULL DEFAULT '' COMMENT 'IP 주소' AFTER `idx`;