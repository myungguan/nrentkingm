ALTER TABLE `vehicle`
	CHANGE COLUMN `moddate` `moddate` DATETIME NOT NULL COMMENT '최종수정일' AFTER `wdate`,
	CHANGE COLUMN `cardan` `cardan` CHAR(1) NOT NULL COMMENT '차량상태(\' \' : 대기중 / 1: 예약중 / 2: 대여중 / 3: 휴차)' AFTER `moddate`,
	ADD COLUMN `onsale` CHAR(1) NOT NULL DEFAULT 'Y' COMMENT '검색노출(Y/N)' AFTER `cardan`,
	DROP COLUMN `ceiling_type`,
	DROP COLUMN `seat_type`,
	DROP COLUMN `hipass_installed`,
	DROP COLUMN `blackbox_installed`,
	DROP COLUMN `last_idx`;


ALTER TABLE `vehicle`
	CHANGE COLUMN `cardan` `status` CHAR(1) NOT NULL DEFAULT 'S' COMMENT '차량상태(S:대기, O:대여중)' AFTER `moddate`;

UPDATE vehicle SET status='S' WHERE status='' OR status='1';
UPDATE vehicle SET status='O' WHERE status='2';