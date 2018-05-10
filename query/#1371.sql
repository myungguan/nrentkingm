-- 날짜관련컬럼 네이밍 변경
ALTER TABLE `qna`
	CHANGE COLUMN `wdate` `dt_create` DATETIME NOT NULL COMMENT '작성일' AFTER `memo`,
	CHANGE COLUMN `resultdate` `dt_result` DATETIME NULL DEFAULT NULL COMMENT '답변작성일' AFTER `resultmemo`;
