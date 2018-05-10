
ALTER TABLE `file`
	CHANGE COLUMN `article_idx` `article_idx` INT(11) NULL DEFAULT NULL COMMENT '연관된 게시물' AFTER `article_type`,
	ADD COLUMN `article_info` VARCHAR(100) NULL DEFAULT NULL COMMENT '게시물 정보' AFTER `article_idx`;


ALTER TABLE `vehicle_std`
	DROP COLUMN `files`;


ALTER TABLE `file`
	ADD INDEX `article_type_article_idx_article_info` (`article_type`, `article_idx`, `article_info`);