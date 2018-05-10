-- 카카오톡 API 형식 변경에 따라 데이터타입 변경
ALTER TABLE `kakao_template`
	CHANGE COLUMN `status` `status` VARCHAR(50) NULL DEFAULT NULL COMMENT '상태' AFTER `button_url`;
