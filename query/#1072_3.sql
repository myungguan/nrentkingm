-- 정산 정보
CREATE TABLE `account_transfer` (
	`idx` INT(11) NOT NULL AUTO_INCREMENT,
	`bank` VARCHAR(50) NULL DEFAULT NULL COMMENT '은행코드',
	`bankaccount` VARCHAR(50) NULL DEFAULT NULL COMMENT '받는은행 계좌번호',
	`bankname` VARCHAR(50) NULL DEFAULT NULL COMMENT '받는사람 계좌주',
	`bankholder` VARCHAR(50) NULL DEFAULT NULL COMMENT '받는사람 계좌주 정보(사업자등록번호 or 주민등록번호 앞6+뒤1)',
	`account` INT(11) NULL DEFAULT NULL COMMENT '이체금액',
	`info_withdraw` VARCHAR(50) NULL DEFAULT NULL COMMENT '(rentshop.idx)_(정산날짜)_(payment_accounts.idx - 존재시)',
	`info_deposit` VARCHAR(50) NULL DEFAULT NULL COMMENT '(RK_(정산날짜)_(payment.idx-존재시)',
	`rawdata` TEXT NULL COMMENT '이체완료시 정보 json',
	`dt_create` DATETIME NULL DEFAULT NULL COMMENT '이체 날짜',
	PRIMARY KEY (`idx`)
)
	COMMENT='정산 이체 기록'
	COLLATE='utf8_general_ci'
	ENGINE=InnoDB
;

-- 예약결제 정보 테이블에 정산 idx 값 넣는 컬럼 추가
ALTER TABLE `payment_accounts`
	ADD COLUMN `account_transfer_idx` INT NULL DEFAULT NULL COMMENT 'settlement.idx' AFTER `settlement_per`;
