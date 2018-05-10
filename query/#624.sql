ALTER TABLE `site_vehicle_price_longterm`
	ADD COLUMN `price_longterm_insu_exem` INT(11) NOT NULL DEFAULT '0' COMMENT '자차 면책금' AFTER `price_longterm_deposit3`;

