ALTER TABLE `qna`
	CHANGE COLUMN `resultwriter` `resultwriter` VARCHAR(20) NULL COMMENT '답변작성자' AFTER `result`,
	CHANGE COLUMN `resultmemo` `resultmemo` TEXT NULL COMMENT '답변내용' AFTER `resultwriter`,
	CHANGE COLUMN `resultdate` `resultdate` DATETIME NULL COMMENT '답변작성일' AFTER `resultmemo`,
	CHANGE COLUMN `pid` `pid` INT(10) UNSIGNED NOT NULL COMMENT '작성위치(W: 웹 / M: 모바일)' AFTER `isdel`,
	DROP COLUMN `btype`,
	DROP COLUMN `goods_idx`,
	DROP COLUMN `market_idx`,
	DROP COLUMN `cate`,
	DROP COLUMN `issec`,
	DROP COLUMN `mem_name`,
	DROP COLUMN `mem_id`,
	DROP COLUMN `cp`,
	DROP COLUMN `email`,
	DROP COLUMN `file1`,
	DROP COLUMN `file2`,
	DROP COLUMN `nip`,
	DROP COLUMN `passwds`,
	DROP COLUMN `isjak`,
	DROP COLUMN `delname`,
	DROP COLUMN `last_idx`,
	DROP COLUMN `last_idx1`,
	DROP COLUMN `last_idx2`,
	DROP COLUMN `fid`,
	DROP COLUMN `language`;


UPDATE qna SET resultwriter = 1;


ALTER TABLE `qna`
	CHANGE COLUMN `resultwriter` `resultwriter` INT NULL DEFAULT NULL COMMENT '답변작성자' AFTER `result`;


ALTER TABLE `qna`
	CHANGE COLUMN `pid` `pid` CHAR(1) NOT NULL DEFAULT 'W' COMMENT '작성위치(W: 웹 / M: 모바일)' AFTER `isdel`;

INSERT INTO qna(mem_idx, subject, memo, wdate) VALUES (4687, '결제', '21일 결제햇는데 그다음 확인하러 들어와보니  로그인이 안되서 확인이 불가하네요..
아이디도기억안나구
폰번호로 가입하려하니  이미 가입된 번호라구 나옵니다.
결제카드는 제카드로결제햇구요..
폰 번호도 제명의입니다.
확인좀 부탁드리겠습니다.
성명..김재영
폰 번호...010-5711-6343
결제카드 국민', NOW());

UPDATE qna SET resultwriter = NULL, resultmemo=NULL WHERE result = 'N';