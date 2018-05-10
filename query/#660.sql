CREATE TABLE `kakao_template` (
	`code` CHAR(7) NOT NULL COMMENT '템플릿코드',
	`name` VARCHAR(50) NULL DEFAULT NULL COMMENT '템플릿명',
	`msg` VARCHAR(1000) NULL DEFAULT NULL COMMENT '템플릿내용',
	`button_type` CHAR(2) NULL DEFAULT NULL COMMENT '버튼타입(N: 없음, DS: 배송조회, C: 버튼)',
	`button_name` VARCHAR(50) NULL DEFAULT NULL COMMENT '버튼이름',
	`button_url` VARCHAR(100) NULL DEFAULT NULL COMMENT '버튼링크주소',
	`status` INT NULL DEFAULT NULL COMMENT '상태(1:등록, 2: 검수요청, 3: 승인, 4: 반려, 5: 승인중단)',
	PRIMARY KEY (`code`)
)
	COMMENT='카카오 알림톡 템플릿'
	COLLATE='utf8_general_ci'
;

CREATE TABLE `kakao_log` (
	`idx` BIGINT NOT NULL AUTO_INCREMENT,
	`phone` VARCHAR(20) NULL DEFAULT NULL COMMENT '수신할 핸드폰번호',
	`callback` VARCHAR(20) NULL DEFAULT NULL COMMENT '발신자 전화번호',
	`reqdate` DATETIME NULL DEFAULT NULL COMMENT '발송시간',
	`msg` VARCHAR(1000) NULL DEFAULT NULL COMMENT '전송할 메시지',
	`code` CHAR(7) NULL DEFAULT NULL COMMENT '템플릿코드',
	`failed_type` CHAR(3) NULL DEFAULT NULL COMMENT '실패시 전송할 메시지 타입',
	`failed_subject` VARCHAR(50) NULL DEFAULT NULL COMMENT '실패시 전송할 제목',
	`failed_msg` VARCHAR(1000) NULL DEFAULT NULL COMMENT '실패시 전송할 내용',
	`result_code` CHAR(3) NULL DEFAULT NULL COMMENT '처리 결과 코드 (100: User Error, 200: OK, 300: Parameter Error, 400: Etc Error, 500: 발신번호 차단, 600: 충전요금 부족, 700: 템플릿 미승인)',
	`result_msg` VARCHAR(100) NULL DEFAULT NULL COMMENT '처리 결과 메시지',
	`cmid` VARCHAR(50) NULL DEFAULT NULL COMMENT 'CMID',
	PRIMARY KEY (`idx`)
)
	COMMENT='카카오 알림톡 전송 로그'
	COLLATE='utf8_general_ci'
;

ALTER TABLE `kakao_log`
	ADD COLUMN `status` INT NULL DEFAULT NULL COMMENT '발송상태 (1: 발송대기, 2:전송완료, 3:결과수신완료)' AFTER `cmid`,
	ADD COLUMN `rslt` CHAR(1) NULL DEFAULT NULL COMMENT '카카오알림톡 결과' AFTER `status`,
	ADD COLUMN `msg_rslt` CHAR(1) NULL DEFAULT NULL COMMENT '카카오알림톡 실패시 메시지' AFTER `rslt`;

ALTER TABLE `kakao_log`
	CHANGE COLUMN `rslt` `rslt` CHAR(2) NULL DEFAULT NULL COMMENT '카카오알림톡 결과' AFTER `status`,
	CHANGE COLUMN `msg_rslt` `msg_rslt` CHAR(2) NULL DEFAULT NULL COMMENT '카카오알림톡 실패시 메시지' AFTER `rslt`;


DROP TABLE `situation_std`;
DROP TABLE `situation_msg`;