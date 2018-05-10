
ALTER TABLE `marketdb`
	COMMENT='예약결제';
RENAME TABLE `marketdb` TO `payment`;

ALTER TABLE `marketdb_accounts`
	COMMENT='예약결제_결제정보';
RENAME TABLE `marketdb_accounts` TO `payment_accounts`;

ALTER TABLE `marketdb_memo`
	COMMENT='예약_메모';
RENAME TABLE `marketdb_memo` TO `reservation_memo`;

ALTER TABLE `marketdb_tmp`
	COMMENT='예약';
RENAME TABLE `marketdb_tmp` TO `reservation`;

ALTER TABLE `payment`
	CHANGE COLUMN `extend_marketdb_idx` `extend_payment_idx` INT(11) NULL DEFAULT NULL COMMENT '다음연장예약 idx' AFTER `pid`;



ALTER TABLE `reservation_memo`
	CHANGE COLUMN `market_idx` `payment_idx` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'site_marketdb.index_no' AFTER `writer_idx`;


ALTER TABLE `payment_accounts`
	CHANGE COLUMN `market_idx` `payment_idx` INT(10) UNSIGNED NOT NULL COMMENT 'site_marketdb.index_no' AFTER `idx`;


ALTER TABLE `board`
	CHANGE COLUMN `market_idx` `payment_idx` INT(10) UNSIGNED NOT NULL COMMENT '(사용안함)' AFTER `event_idx`;


ALTER TABLE `coupon_mem`
	CHANGE COLUMN `market_idx` `payment_idx` INT(10) UNSIGNED NOT NULL COMMENT 'site_marketdb.index_no' AFTER `dt_use`;


ALTER TABLE `payment`
	CHANGE COLUMN `tmp_idx` `reservation_idx` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'site_marketdb_tmp.index_no' AFTER `session_id_real`;