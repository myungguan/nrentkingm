/*
	migration/1423.php 실행하기 전에 사용
	vehicle_std.fuel, vehicle_attribute.idx 를 연결할 중간테이블 생성
 */
CREATE TABLE `vehicle_std_fuel_code` (
	`idx`                   INT(11)  NOT NULL AUTO_INCREMENT,
	`vehicle_std_idx`       INT(11)  NULL     DEFAULT NULL,
	`code_idx` INT(11)  NULL     DEFAULT NULL,
	`dt_create`             DATETIME NULL     DEFAULT NULL,
	PRIMARY KEY (`idx`)
)
	COMMENT ='차량 속성(연료)'
	COLLATE = 'utf8_general_ci'
	ENGINE = InnoDB;


ALTER TABLE `vehicle_attribute`
	COMMENT='코드관리';
RENAME TABLE `vehicle_attribute` TO `code`;

ALTER TABLE `code`
	CHANGE COLUMN `ttype` `ttype` CHAR(1) NOT NULL COMMENT '타입: 제조사(1), 차량등급(2), 연료타입(3), 색상(4)' AFTER `idx`;


ALTER TABLE `code`
	DROP COLUMN `dtype`;

/*
	migration/1423.php 실행 후에 사용
	불필요 컬럼 삭제
 */
ALTER TABLE `vehicle_std`
	DROP COLUMN `fuel`;
