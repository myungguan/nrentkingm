ALTER TABLE `member`
	ADD COLUMN `join_ip` VARCHAR(30) NULL DEFAULT NULL COMMENT '가입 ip' AFTER `idx`,
	ADD COLUMN `join_session_id` VARCHAR(50) NULL DEFAULT NULL COMMENT '가입 session id' AFTER `join_ip`;
