
CREATE TABLE `config` (
	`type` VARCHAR(50) NOT NULL,
	`value` VARCHAR(200) NULL DEFAULT NULL,
	PRIMARY KEY (`type`)
)
	COMMENT='사이트 설정'
	COLLATE='utf8_general_ci'
;