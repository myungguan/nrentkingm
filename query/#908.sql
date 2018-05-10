CREATE TABLE `log_error` (
	`idx` BIGINT NOT NULL AUTO_INCREMENT,
	`ip` VARCHAR(30) NOT NULL DEFAULT '' COMMENT 'ip address',
	`session_id` VARCHAR(50) NOT NULL DEFAULT '' COMMENT 'session_id',
	`dt` DATETIME NULL DEFAULT NULL COMMENT '일시',
	`url` VARCHAR(500) NULL DEFAULT NULL COMMENT 'URL',
	`msg` VARCHAR(100) NULL DEFAULT NULL COMMENT '에러메시지',
	PRIMARY KEY (`idx`),
	INDEX `dt` (`dt`),
	INDEX `session_id_dt` (`session_id`, `dt`)
)
	COMMENT='에러로그'
	COLLATE='utf8_general_ci'
;


ALTER TABLE `log_error`
	ADD COLUMN `request` TEXT NULL DEFAULT NULL COMMENT 'request' AFTER `url`;