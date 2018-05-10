
ALTER TABLE `member_license`
	COMMENT='운전면허정보',
	ADD COLUMN `name` VARCHAR(50) NULL AFTER `mem_idx`;

ALTER TABLE `member`
	ADD COLUMN `driver_license_idx` INT NULL DEFAULT NULL AFTER `pushser`;

ALTER TABLE `marketdb`
	ADD COLUMN `driver_license_idx` INT(10) UNSIGNED NULL DEFAULT NULL COMMENT '제2운전자 면허정보' AFTER `mem_idx`;

ALTER TABLE `member`
	CHANGE COLUMN `driver_license_idx` `driver_license_idx` INT(11) NULL DEFAULT NULL COMMENT '운전면허증' AFTER `pushser`;

-- 운영배포시 실행

RENAME TABLE `member_license` TO `driver_license`;

ALTER TABLE `driver_license`
	ADD COLUMN `birth` DATE NULL DEFAULT NULL COMMENT '생년월일' AFTER `name`,
	ADD COLUMN `gender` CHAR(1) NULL DEFAULT NULL COMMENT '성별' AFTER `birth`;

UPDATE driver_license dl LEFT JOIN member m ON dl.mem_idx = m.idx SET dl.name = m.name;
UPDATE member m LEFT JOIN driver_license dl ON dl.mem_idx = m.idx SET m.driver_license_idx = dl.idx;

ALTER TABLE `driver_license`
	DROP COLUMN `mem_idx`;

ALTER TABLE `driver_license`
	CHANGE COLUMN `name` `name` VARCHAR(50) NULL DEFAULT NULL COMMENT '이름' AFTER `idx`,
	ADD COLUMN `cp` VARCHAR(50) NULL DEFAULT NULL COMMENT '전화번호' AFTER `name`,
	CHANGE COLUMN `kinds` `kinds` VARCHAR(10) NULL DEFAULT NULL COMMENT '면허종류(FL: 1종 대형 / FN: 1종 보통 / SN: 2종 보통)' AFTER `cp`,
	CHANGE COLUMN `nums` `nums` VARCHAR(30) NULL DEFAULT NULL COMMENT '면허번호' AFTER `kinds`,
	CHANGE COLUMN `date1` `date1` VARCHAR(10) NULL DEFAULT NULL COMMENT '갱신기간' AFTER `nums`,
	CHANGE COLUMN `date2` `date2` VARCHAR(10) NULL DEFAULT NULL COMMENT '발급일' AFTER `date1`;


ALTER TABLE `marketdb`
	DROP COLUMN `namesub`,
	DROP COLUMN `cpsub`,
	DROP COLUMN `kindsub`,
	DROP COLUMN `numsub`,
	DROP COLUMN `date1sub`,
	DROP COLUMN `date2sub`;