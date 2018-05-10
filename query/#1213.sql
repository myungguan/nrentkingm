
ALTER TABLE `vehicle`
	CHANGE COLUMN `com_idx` `rentshop_idx` INT(10) UNSIGNED NOT NULL COMMENT 'site_rentshop.index_no' AFTER `idx`;