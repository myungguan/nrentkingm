-- 불필요 컬럼 제거
ALTER TABLE `payment_accounts`
	DROP COLUMN `dt_settlement_finish`,
	DROP COLUMN `settlement_idx`;

-- rentshop 테이블 컬럼명 변경
ALTER TABLE `rentshop`
	CHANGE COLUMN `bank` `bank` VARCHAR(50) NOT NULL COMMENT '은행코드' AFTER `per2`,
	CHANGE COLUMN `bankholder` `bankholder` VARCHAR(20) NOT NULL COMMENT '은행계좌의 인증정보(사업자등록번호 or 주민등록번호 왼쪽7자리)' AFTER `bankname`;

-- 가맹점의 계좌 체크 상태 컬럼 추가
ALTER TABLE `rentshop`
	ADD COLUMN `bank_transfer_status` CHAR(1) NULL DEFAULT NULL COMMENT '오픈플렛폼 계좌 체크 상태(null : admin에서 가맹점 등록 후 가맹점에서 입력 전, Y : 이체가능, N : 이체불가)' AFTER `bankholder`;

-- 기존 가맹점의 계좌 체크 상태 이체불가로 설정
UPDATE rentshop SET bank_transfer_status = 'N';