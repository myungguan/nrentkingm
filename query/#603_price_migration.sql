ALTER TABLE `site_vehicle`
	ADD COLUMN `price_idx` INT NOT NULL DEFAULT '0' COMMENT '단기요금제' AFTER `nosmoke`,
	ADD COLUMN `price_longterm_idx` INT NOT NULL DEFAULT '0' COMMENT '장기요금제' AFTER `price_idx`,
	ADD COLUMN `liscense_limit` INT NOT NULL DEFAULT '0' COMMENT '면허제한' AFTER `insu_self`;
;

DROP TABLE IF EXISTS `site_vehicle_price`;
CREATE TABLE `site_vehicle_price` (
	`idx` INT(11) NOT NULL AUTO_INCREMENT,
	`member_idx` INT(11) NOT NULL DEFAULT '0' COMMENT '멤버',
	`rentshop_idx` INT(11) NOT NULL DEFAULT '0' COMMENT '렌트샵',
	`managed` CHAR(1) NOT NULL DEFAULT 'N' COMMENT '요금리스트에 있는지 1/0',
	`title` VARCHAR(100) NULL DEFAULT NULL COMMENT '제목',
	`price` INT(11) NOT NULL DEFAULT '0' COMMENT '기본요금',
	`price_hour` INT(11) NOT NULL DEFAULT '12' COMMENT '시간요금 비율',
	`price_holiday` INT(11) NOT NULL DEFAULT '0' COMMENT '주말요금',
	`price_discount2` FLOAT NOT NULL DEFAULT '1' COMMENT '요금인하율(기간2)',
	`price_discount3` FLOAT NOT NULL DEFAULT '1' COMMENT '요금인하율(기간3)',
	`price_discount4` FLOAT NOT NULL DEFAULT '1' COMMENT '요금인하율(기간4)',
	`price_del1` INT(11) NOT NULL DEFAULT '0' COMMENT '배반차료1',
	`price_del2` INT(11) NOT NULL DEFAULT '0' COMMENT '배반차료2',
	`price_del3` INT(11) NOT NULL DEFAULT '0' COMMENT '배반차료3',
	`price_del4` INT(11) NOT NULL DEFAULT '0' COMMENT '배반차료4',
	`price_insu_check` CHAR(1) NOT NULL DEFAULT 'N' COMMENT '일반자차 사용여부',
	`price_insu1` INT(11) NOT NULL DEFAULT '0' COMMENT '일반자차 요금(기간1)',
	`price_insu2` INT(11) NOT NULL DEFAULT '0' COMMENT '일반자차 요금(기간2)',
	`price_insu3` INT(11) NOT NULL DEFAULT '0' COMMENT '일반자차 요금(기간3)',
	`price_insu4` INT(11) NOT NULL DEFAULT '0' COMMENT '일반자차 요금(기간4)',
	`price_insu_exem` INT(11) NOT NULL DEFAULT '0' COMMENT '일반자차 면책금',
	`price_insu_super_check` CHAR(1) NOT NULL DEFAULT 'N' COMMENT '슈퍼자차 사용여부',
	`price_insu_super1` INT(11) NOT NULL DEFAULT '0' COMMENT '슈퍼자차 요금(기간1)',
	`price_insu_super2` INT(11) NOT NULL DEFAULT '0' COMMENT '슈퍼자차 요금(기간2)',
	`price_insu_super3` INT(11) NOT NULL DEFAULT '0' COMMENT '슈퍼자차 요금(기간3)',
	`price_insu_super4` INT(11) NOT NULL DEFAULT '0' COMMENT '슈퍼자차 요금(기간4)',
	`price_insu_super_exem` INT(11) NOT NULL DEFAULT '0' COMMENT '슈퍼자차 면책금',
	`price_insu_perfect_check` CHAR(1) NOT NULL DEFAULT 'N' COMMENT '완전자차 사용여부',
	`price_insu_perfect1` INT(11) NOT NULL DEFAULT '0' COMMENT '완전자차 요금(기간1)',
	`price_insu_perfect2` INT(11) NOT NULL DEFAULT '0' COMMENT '완전자차 요금(기간2)',
	`price_insu_perfect3` INT(11) NOT NULL DEFAULT '0' COMMENT '완전자차 요금(기간3)',
	`price_insu_perfect4` INT(11) NOT NULL DEFAULT '0' COMMENT '완전자차 요금(기간4)',
	`price_net` FLOAT NOT NULL DEFAULT '0' COMMENT 'NET 할일율',
	`dt_create` DATETIME NOT NULL COMMENT '생성일',
	`dt_delete` DATETIME NULL DEFAULT NULL COMMENT '삭제일',
	PRIMARY KEY (`idx`)
)
	COMMENT='단기 요금제'
	ENGINE=MyISAM
;



DROP TABLE IF EXISTS  `site_vehicle_price_longterm`;
CREATE TABLE `site_vehicle_price_longterm` (
	`idx` INT(11) NOT NULL AUTO_INCREMENT,
	`member_idx` INT(11) NOT NULL DEFAULT '0' COMMENT '멤버',
	`rentshop_idx` INT(11) NOT NULL DEFAULT '0' COMMENT '렌트샵',
	`managed` CHAR(1) NOT NULL DEFAULT 'N' COMMENT '요금리스트에 있는지 1/0',
	`title` VARCHAR(100) NULL DEFAULT NULL COMMENT '타이틀',
	`price_longterm1` INT(11) NOT NULL DEFAULT '0' COMMENT '요금 (기간1)',
	`price_longterm2` INT(11) NOT NULL DEFAULT '0' COMMENT '요금 (기간2)',
	`price_longterm3` INT(11) NOT NULL DEFAULT '0' COMMENT '요금 (기간3)',
	`price_longterm_deposit1` INT(11) NOT NULL DEFAULT '0' COMMENT '보증금 (기간1)',
	`price_longterm_deposit2` INT(11) NOT NULL DEFAULT '0' COMMENT '보증금 (기간2)',
	`price_longterm_deposit3` INT(11) NOT NULL DEFAULT '0' COMMENT '보증금 (기간3)',
	`price_longterm_net` FLOAT NOT NULL DEFAULT '0' COMMENT 'NET 할인율',
	`dt_create` DATETIME NOT NULL COMMENT '생성일',
	`dt_delete` DATETIME NULL DEFAULT NULL COMMENT '삭제일',
	PRIMARY KEY (`idx`)
)
	COMMENT='장기 요금제'
	ENGINE=MyISAM
;



-- 운영이관시 http://admin.rentking.co.kr/migration/603.php 실행
-- 주의!!!!!!!!!!!!!! 아래 코드 실행후 603.php 실행 금지 (가격 리셋됨)

ALTER TABLE `site_vehicle_std`
	DROP COLUMN `ac1`,
	DROP COLUMN `ac2`,
	DROP COLUMN `ac3`,
	DROP COLUMN `ac4`,
	DROP COLUMN `act1`,
	DROP COLUMN `act2`,
	DROP COLUMN `act3`,
	DROP COLUMN `act4`;


ALTER TABLE `site_vehicle`
	DROP COLUMN `file1`,
	DROP COLUMN `file2`,
	DROP COLUMN `file3`,
	DROP COLUMN `file4`,
	DROP COLUMN `file5`,
	DROP COLUMN `file6`,
	DROP COLUMN `showapp_1`,
	DROP COLUMN `showapp_2`,
	DROP COLUMN `showapp_3`,
	DROP COLUMN `showintra_1`,
	DROP COLUMN `showintra_2`,
	DROP COLUMN `showintra_3`,
	DROP COLUMN `ac1_1`,
	DROP COLUMN `ac1_1per`,
	DROP COLUMN `ac1_2`,
	DROP COLUMN `ac1_2per`,
	DROP COLUMN `act1_1`,
	DROP COLUMN `act1_1per`,
	DROP COLUMN `act1_2`,
	DROP COLUMN `act1_2per`,
	DROP COLUMN `del1_ac`,
	DROP COLUMN `del1_ac1`,
	DROP COLUMN `del1_ac2`,
	DROP COLUMN `insu1_1`,
	DROP COLUMN `insu1_1ac`,
	DROP COLUMN `insu1_1stdac`,
	DROP COLUMN `insu1_2`,
	DROP COLUMN `insu1_2ac`,
	DROP COLUMN `insu1_2stdac`,
	DROP COLUMN `insu1_3`,
	DROP COLUMN `insu1_3ac`,
	DROP COLUMN `acl1`,
	DROP COLUMN `racl1`,
	DROP COLUMN `ac2_1`,
	DROP COLUMN `ac2_1per`,
	DROP COLUMN `ac2_2`,
	DROP COLUMN `ac2_2per`,
	DROP COLUMN `act2_1`,
	DROP COLUMN `act2_1per`,
	DROP COLUMN `act2_2`,
	DROP COLUMN `act2_2per`,
	DROP COLUMN `del2_ac`,
	DROP COLUMN `del2_ac1`,
	DROP COLUMN `del2_ac2`,
	DROP COLUMN `insu2_1`,
	DROP COLUMN `insu2_1ac`,
	DROP COLUMN `insu2_1stdac`,
	DROP COLUMN `insu2_2`,
	DROP COLUMN `insu2_2ac`,
	DROP COLUMN `insu2_2stdac`,
	DROP COLUMN `insu2_3`,
	DROP COLUMN `insu2_3ac`,
	DROP COLUMN `acl2`,
	DROP COLUMN `racl2`;


