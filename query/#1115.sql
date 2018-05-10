
CREATE TABLE `auth_admin` (
	`idx` INT NOT NULL AUTO_INCREMENT,
	`member_idx` INT NOT NULL COMMENT 'member.idx',
	`grade` INT NOT NULL COMMENT '등급',
	`auth` VARCHAR(1000) NULL DEFAULT NULL COMMENT '권한',
	`dt_create` DATETIME NULL DEFAULT NULL COMMENT '생성일',
	`dt_modify` DATETIME NULL DEFAULT NULL COMMENT '수정일',
	PRIMARY KEY (`idx`)
)
	COMMENT='관리자 권한'
	COLLATE='utf8_general_ci'
;

INSERT INTO auth_admin (member_idx, grade, dt_create, dt_modify) SELECT idx, amemgrade, NOW(), NOW() FROM member WHERE amemgrade <> 0;


DROP TABLE `admin_grade`;
DROP TABLE `admin_priv`;


ALTER TABLE `member`
	DROP COLUMN `amemgrade`;