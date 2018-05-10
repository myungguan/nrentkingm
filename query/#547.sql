ALTER TABLE `site_vehicle_std`
	CHANGE COLUMN `status` `status` CHAR(1) NOT NULL COMMENT '삭제여부(Y/N)' AFTER `moddate`;