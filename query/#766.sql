CREATE TABLE `vehicle_price_extra` (
	`idx` INT(11) NOT NULL AUTO_INCREMENT,
	`member_idx` INT(11) NOT NULL DEFAULT '0' COMMENT '멤버',
	`rentshop_idx` INT(11) NOT NULL DEFAULT '0' COMMENT '렌트샵',
	`title` VARCHAR(100) NOT NULL DEFAULT '0' COMMENT '제목',
	`type` INT(11) NOT NULL DEFAULT '0' COMMENT '인상:1, 할인:2',
	`price` INT(11) NOT NULL DEFAULT '0' COMMENT '금액(원)',
	`dt_start` DATETIME NOT NULL COMMENT '기간(시작)',
	`dt_end` DATETIME NOT NULL COMMENT '기간(종료)',
	`dt_create` DATETIME NOT NULL COMMENT '생성일',
	`dt_delete` DATETIME NULL DEFAULT NULL COMMENT '삭제일',
	PRIMARY KEY (`idx`)
)
	COMMENT='특별요금'
	ENGINE=InnoDB
;


ALTER TABLE `vehicle`
	ADD COLUMN `price_extra_idx` INT(11) NOT NULL DEFAULT '0' COMMENT '특별요금(vehicle_price_extra.idx)' AFTER `price_longterm_idx`;_

ALTER TABLE `vehicle_price_extra`
	ADD COLUMN `dt_modify` DATETIME NULL DEFAULT NULL COMMENT '마지막 수정일' AFTER `dt_create`;