
ALTER TABLE `site_coupen_serial`
	CHANGE COLUMN `coupen_idx` `coupon_idx` INT(10) UNSIGNED NOT NULL COMMENT 'site_coupon.index_no' AFTER `idx`;
RENAME TABLE `site_coupen_serial` TO `site_coupon_serial`;

ALTER TABLE `site_coupen`
	CHANGE COLUMN `coupenname` `couponname` VARCHAR(50) NOT NULL COMMENT '쿠폰명' AFTER `img`;
RENAME TABLE `site_coupen` TO `site_coupon`;

ALTER TABLE `site_coupen_mem`
	CHANGE COLUMN `coupen_idx` `coupon_idx` INT(10) UNSIGNED NOT NULL COMMENT 'site_coupon.index_no' AFTER `mem_idx`,
	CHANGE COLUMN `coupen_name` `coupon_name` VARCHAR(50) NOT NULL COMMENT 'site_coupon.couponname' AFTER `coupon_idx`,
	CHANGE COLUMN `sdate` `sdate` DATETIME NOT NULL COMMENT 'site_coupon.startdates' AFTER `mdate`,
	CHANGE COLUMN `edate` `edate` DATETIME NOT NULL COMMENT 'site_coupon.enddates' AFTER `sdate`,
	CHANGE COLUMN `actype` `actype` CHAR(1) NOT NULL COMMENT 'site_coupon.actype' AFTER `usehour`,
	CHANGE COLUMN `account` `account` INT(10) UNSIGNED NOT NULL COMMENT 'site_coupon.account' AFTER `usetype`,
	CHANGE COLUMN `canuseac` `canuseac` INT(10) UNSIGNED NOT NULL COMMENT 'site_coupon.canuseac' AFTER `memo`;
RENAME TABLE `site_coupen_mem` TO `site_coupon_mem`;

ALTER TABLE `site_marketdb`
	CHANGE COLUMN `usecoupen` `usecoupon` INT(10) UNSIGNED NOT NULL COMMENT '쿠폰사용(금액)' AFTER `dan`;