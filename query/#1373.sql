-- FAQ 불필요 컬럼제거, 네이밍 변경, 수정,삭제일 추가
ALTER TABLE `faq`
	CHANGE COLUMN `subject` `title` VARCHAR(255) NOT NULL COMMENT '제목' AFTER `idx`,
	CHANGE COLUMN `memo` `content` TEXT NOT NULL COMMENT '내용' AFTER `title`,
	ADD COLUMN `read_count` INT NOT NULL DEFAULT '0' AFTER `content`,
	CHANGE COLUMN `wdate` `dt_create` DATETIME NOT NULL COMMENT '작성일' AFTER `read_count`,
	ADD COLUMN `dt_modify` DATETIME NULL DEFAULT NULL COMMENT '수정일' AFTER `dt_create`,
	ADD COLUMN `dt_delete` DATETIME NULL DEFAULT NULL COMMENT '삭제일' AFTER `dt_modify`,
	DROP COLUMN `fid`,
	DROP COLUMN `cate_idx`,
	DROP COLUMN `cate`,
	DROP COLUMN `isbest`,
	DROP COLUMN `memo_mode`,
	DROP COLUMN `score`;

-- 수정일 값 채움
UPDATE faq SET dt_modify = dt_create;