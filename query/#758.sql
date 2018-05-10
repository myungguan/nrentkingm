ALTER TABLE `site_vehicle_price`
	ADD COLUMN `price_insu0_check` CHAR(1) NOT NULL DEFAULT 'N' COMMENT '자차미포함'  AFTER `price_del4`,

	CHANGE COLUMN `price_insu_check` `price_insu1_check` CHAR(1) NOT NULL DEFAULT 'N' COMMENT '자차1 사용여부',
	CHANGE COLUMN `price_insu1` `price_insu1_1` INT(11) NOT NULL DEFAULT '0' COMMENT '자차1 요금(기간1)',
	CHANGE COLUMN `price_insu2` `price_insu1_2` INT(11) NOT NULL DEFAULT '0' COMMENT '자차1 요금(기간2)',
	CHANGE COLUMN `price_insu3` `price_insu1_3` INT(11) NOT NULL DEFAULT '0' COMMENT '자차1 요금(기간3)',
	CHANGE COLUMN `price_insu4` `price_insu1_4` INT(11) NOT NULL DEFAULT '0' COMMENT '자차1 요금(기간4)',
	CHANGE COLUMN `price_insu_exem` `price_insu1_exem` INT(11) NOT NULL DEFAULT '0' COMMENT '자차1 면책금',

	CHANGE COLUMN `price_insu_super_check` `price_insu2_check` CHAR(1) NOT NULL DEFAULT 'N' COMMENT '자차2 사용여부',
	CHANGE COLUMN `price_insu_super1` `price_insu2_1` INT(11) NOT NULL DEFAULT '0' COMMENT '자차2 요금(기간1)',
	CHANGE COLUMN `price_insu_super2` `price_insu2_2` INT(11) NOT NULL DEFAULT '0' COMMENT '자차2 요금(기간2)',
	CHANGE COLUMN `price_insu_super3` `price_insu2_3` INT(11) NOT NULL DEFAULT '0' COMMENT '자차2 요금(기간3)',
	CHANGE COLUMN `price_insu_super4` `price_insu2_4` INT(11) NOT NULL DEFAULT '0' COMMENT '자차2 요금(기간4)',
	CHANGE COLUMN `price_insu_super_exem` `price_insu2_exem` INT(11) NOT NULL DEFAULT '0' COMMENT '자차2 면책금',

	CHANGE COLUMN `price_insu_perfect_check` `price_insu3_check` CHAR(1) NOT NULL DEFAULT 'N' COMMENT '자차3 사용여부',
	CHANGE COLUMN `price_insu_perfect1` `price_insu3_1` INT(11) NOT NULL DEFAULT '0' COMMENT '자차3 요금(기간1)',
	CHANGE COLUMN `price_insu_perfect2` `price_insu3_2` INT(11) NOT NULL DEFAULT '0' COMMENT '자차3 요금(기간2)',
	CHANGE COLUMN `price_insu_perfect3` `price_insu3_3` INT(11) NOT NULL DEFAULT '0' COMMENT '자차3 요금(기간3)',
	CHANGE COLUMN `price_insu_perfect4` `price_insu3_4` INT(11) NOT NULL DEFAULT '0' COMMENT '자차3 요금(기간4)'
;


ALTER TABLE `site_vehicle_price`
	ADD COLUMN `price_insu3_exem` INT(11) NOT NULL DEFAULT '0' COMMENT '자차3 면책금' AFTER `price_insu3_4`
	;

SELECT * FROM site_vehicle_price WHERE price_insu0_check = 'N' AND price_insu1_check = 'N' AND price_insu2_check = 'N' AND price_insu3_check = 'N';
UPDATE site_vehicle_price SET price_insu0_check = 'Y' WHERE price_insu0_check = 'N' AND price_insu1_check = 'N' AND price_insu2_check = 'N' AND price_insu3_check = 'N';