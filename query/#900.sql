CREATE TABLE `log_pageview` (
	`idx` BIGINT(20) NOT NULL AUTO_INCREMENT,
	`ip` VARCHAR(30) NOT NULL DEFAULT '' COMMENT 'ip address',
	`session_id` VARCHAR(50) NOT NULL DEFAULT '' COMMENT 'session id',
	`dt` DATETIME NULL DEFAULT NULL COMMENT '방문일시',
	`user_agent` VARCHAR(500) NULL DEFAULT NULL COMMENT 'User Agent',
	PRIMARY KEY (`idx`)
)
	COMMENT='사용자 페이지뷰 로그'
	COLLATE='utf8_general_ci'
;
