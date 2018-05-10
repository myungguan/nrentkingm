CREATE TABLE `log_visit` (
	`idx` BIGINT NOT NULL AUTO_INCREMENT,
	`session_id` VARCHAR(50) NOT NULL DEFAULT '',
	`dt` DATETIME NULL DEFAULT NULL,
	`referrer` VARCHAR(200) NULL DEFAULT NULL,
	`type` CHAR(2) NULL DEFAULT NULL,
	PRIMARY KEY (`idx`)
)
	COMMENT='사용자 방문 로그'
	COLLATE='utf8_general_ci'
;

ALTER TABLE `log_visit`
	CHANGE COLUMN `session_id` `session_id` VARCHAR(50) NOT NULL DEFAULT '' COMMENT 'session id' AFTER `idx`,
	CHANGE COLUMN `dt` `dt` DATETIME NULL DEFAULT NULL COMMENT '방문일시' AFTER `session_id`,
	CHANGE COLUMN `referrer` `referrer` VARCHAR(200) NULL DEFAULT NULL COMMENT 'referrer' AFTER `dt`,
	CHANGE COLUMN `type` `type` CHAR(2) NULL DEFAULT NULL COMMENT '타입(추후 이용)' AFTER `referrer`;

ALTER TABLE `log_visit`
	CHANGE COLUMN `referrer` `referer` VARCHAR(200) NULL DEFAULT NULL COMMENT 'referrer' AFTER `dt`;


ALTER TABLE `log_visit`
	ADD COLUMN `user_agent` VARCHAR(200) NULL DEFAULT NULL COMMENT 'User Agent' AFTER `referer`;


ALTER TABLE `log_visit`
	ADD COLUMN `ip` VARCHAR(30) NOT NULL DEFAULT '' COMMENT 'ip address' AFTER `idx`;


ALTER TABLE `log_visit`
	CHANGE COLUMN `referer` `referer` VARCHAR(500) NULL DEFAULT NULL COMMENT 'referrer' AFTER `dt`;


ALTER TABLE `log_visit`
	ADD COLUMN `url` VARCHAR(500) NULL DEFAULT NULL COMMENT '방문URL' AFTER `referer`;


ALTER TABLE `log_visit`
	CHANGE COLUMN `user_agent` `user_agent` VARCHAR(500) NULL DEFAULT NULL COMMENT 'User Agent' AFTER `url`;