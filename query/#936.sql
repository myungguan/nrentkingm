
CREATE TABLE `record` (
	`type` VARCHAR(20) NOT NULL COMMENT '타입',
	`value` BIGINT NOT NULL COMMENT '값',
	PRIMARY KEY (`type`)
)
	COMMENT='최고기록'
	COLLATE='utf8_general_ci'
;


ALTER TABLE `record`
	CHANGE COLUMN `type` `type` VARCHAR(40) NOT NULL COMMENT '타입' FIRST;


INSERT INTO `record` (`type`, `value`) VALUES ('reservation_day', '25');
INSERT INTO `record` (`type`, `value`) VALUES ('sales_day', '7164300');
INSERT INTO `record` (`type`, `value`) VALUES ('visit_day', '3149');
INSERT INTO `record` (`type`, `value`) VALUES ('search_day', '1524');
INSERT INTO `record` (`type`, `value`) VALUES ('visit_reservation_page_day', '93');
INSERT INTO `record` (`type`, `value`) VALUES ('join_day', '82');