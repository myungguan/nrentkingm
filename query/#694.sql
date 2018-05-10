CREATE TABLE `app` (
	`idx` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`platform` CHAR(10) NOT NULL COMMENT '플랫폼(android / ios)',
	`version` CHAR(50) NULL DEFAULT NULL COMMENT '버전',
	`dt_release` DATE NULL DEFAULT NULL COMMENT '배포 일자',
	`force_update` CHAR(1) NULL DEFAULT NULL COMMENT '강제 업데이트 Y/N',
	PRIMARY KEY (`idx`)
)
	COMMENT='App 강제업데이트 정보 테이블'
	ENGINE=MyISAM;
