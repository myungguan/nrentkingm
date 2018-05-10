
ALTER TABLE `coupon`
	CHANGE COLUMN `account` `account` FLOAT UNSIGNED NOT NULL COMMENT '할인액' AFTER `actype`;



ALTER TABLE `coupon_mem`
	DROP COLUMN `coupon_name`,
	DROP COLUMN `actype`,
	DROP COLUMN `account`,
	DROP COLUMN `canuseac`;