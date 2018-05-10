
DROP TABLE `nows`;

ALTER TABLE `marketdb_memo`
	ALTER `writer_idx` DROP DEFAULT;
ALTER TABLE `marketdb_memo`
	CHANGE COLUMN `writer_idx` `writer_idx` INT(10) UNSIGNED NOT NULL COMMENT 'site_member.index_no' AFTER `idx`,
	CHANGE COLUMN `market_idx` `market_idx` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'site_marketdb.index_no' AFTER `writer_idx`,
	DROP COLUMN `cate1`,
	DROP COLUMN `cate2`,
	DROP COLUMN `cate3`,
	DROP COLUMN `mtype`,
	DROP COLUMN `iscl`,
	DROP COLUMN `isouts`,
	DROP COLUMN `isins`,
	DROP COLUMN `wtype`,
	DROP COLUMN `writer_s`,
	DROP COLUMN `isauto`,
	DROP COLUMN `ischeck`,
	DROP COLUMN `ischeck2`,
	DROP COLUMN `isstop`,
	DROP COLUMN `up_idx`,
	DROP COLUMN `resultdate`,
	DROP COLUMN `resultname`,
	DROP COLUMN `to_idx2`,
	DROP INDEX `isauto`,
	DROP INDEX `ischeck`,
	DROP INDEX `writer_idx`,
	DROP INDEX `isscore`,
	DROP INDEX `isdelay`,
	DROP INDEX `delaydate`;


ALTER TABLE `marketdb_memo`
	DROP COLUMN `resultmemo`,
	DROP COLUMN `isdelay`,
	DROP COLUMN `delaydate`,
	DROP COLUMN `to_idx1`;


ALTER TABLE `marketdb_memo`
	ADD COLUMN `dt` DATETIME NULL DEFAULT NULL COMMENT '작성일시' AFTER `wdate`,
	ADD INDEX `dt` (`dt`);

UPDATE marketdb_memo SET dt = FROM_UNIXTIME(wdate);


ALTER TABLE `marketdb_memo`
	DROP COLUMN `wdate`;


ALTER TABLE `marketdb_memo`
	CHANGE COLUMN `isscore` `isscore` CHAR(1) NOT NULL DEFAULT '2' COMMENT '중요도(1: 낮음 / 2: 보통 / 3: 높음)' AFTER `market_idx`;
