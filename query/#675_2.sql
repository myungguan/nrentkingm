ALTER TABLE `site_marketdb`
	CHANGE COLUMN `insu` `insu` CHAR(1) NOT NULL COMMENT '보험종류(1: 일반자차 / 2: 슈퍼자차 / 3: 완전면책)' AFTER `delac`;

ALTER TABLE `site_marketdb_tmp`
	CHANGE COLUMN `insu` `insu` CHAR(1) NOT NULL COMMENT '보험종류(1: 일반자차 / 2: 슈퍼자차 / 3: 완전면책)' AFTER `delac`;