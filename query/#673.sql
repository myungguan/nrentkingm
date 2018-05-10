CREATE TABLE `alliance` (
	`idx` INT NOT NULL AUTO_INCREMENT,
	`manager` VARCHAR(50) NULL DEFAULT NULL COMMENT '담당자 성함',
	`contact` VARCHAR(50) NULL DEFAULT NULL COMMENT '연락처',
	`email` VARCHAR(50) NULL DEFAULT NULL COMMENT '이메일',
	`company` VARCHAR(50) NULL DEFAULT NULL COMMENT '법인명(단체명)',
	`content` TEXT NULL DEFAULT NULL COMMENT '제휴내용',
	`memo` TEXT NULL DEFAULT NULL COMMENT '관리자 메모',
	`dt_create` DATETIME NULL DEFAULT NULL COMMENT '작성일',
	PRIMARY KEY (`idx`)
)
	COMMENT='입점/제휴 문의'
;

ALTER TABLE `site_member`
	CHANGE COLUMN `bussinessk1` `businessk1` VARCHAR(50) NOT NULL COMMENT '업태' AFTER `daename`,
	CHANGE COLUMN `bussinessk2` `businessk2` VARCHAR(50) NOT NULL COMMENT '업종' AFTER `businessk1`;