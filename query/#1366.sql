-- 웹 프론트, 멤버사 공지사항 전용 테이블 생성
CREATE TABLE `notices` (
	`idx` INT(11) NOT NULL AUTO_INCREMENT,
	`title` VARCHAR(200) NOT NULL COMMENT '제목',
	`content` LONGTEXT NOT NULL COMMENT '내용',
	`member_idx` INT(11) NOT NULL COMMENT '작성자 member.idx',
	`read_count` INT(11) NOT NULL DEFAULT '0' COMMENT '조회수',
	`front_yn` ENUM('Y','N') NOT NULL DEFAULT 'N' COMMENT '웹 프론트 공지사항 노출 여부',
	`rentshop_yn` ENUM('Y','N') NOT NULL DEFAULT 'N' COMMENT '맴버사 공지사항 노출 여부',
	`dt_create` DATETIME NULL DEFAULT NULL,
	`dt_modify` DATETIME NULL DEFAULT NULL,
	`dt_delete` DATETIME NULL DEFAULT NULL,
	PRIMARY KEY (`idx`)
)
	COMMENT='공지사항'
	COLLATE='utf8_general_ci'
	ENGINE=InnoDB
;



-- 이벤트 전용 테이블 생성
CREATE TABLE `events` (
	`idx` INT(11) NOT NULL AUTO_INCREMENT,
	`title` VARCHAR(200) NOT NULL COMMENT '제목',
	`content` LONGTEXT NOT NULL COMMENT '내용',
	`member_idx` INT(11) NOT NULL COMMENT '작성자 member.idx',
	`read_count` INT(11) NOT NULL DEFAULT '0' COMMENT '조회수',
	`dt_start` DATETIME NULL DEFAULT NULL COMMENT '이벤트 시작 날짜',
	`dt_end` DATETIME NULL DEFAULT NULL COMMENT '이벤트 종료 날짜',
	`dt_create` DATETIME NULL DEFAULT NULL,
	`dt_modify` DATETIME NULL DEFAULT NULL,
	`dt_delete` DATETIME NULL DEFAULT NULL,
	PRIMARY KEY (`idx`)
)
	COMMENT='이벤트'
	COLLATE='utf8_general_ci'
	ENGINE=InnoDB
;



-- 기존 웹 프론트 노출 공지사항 데이터 insert
INSERT INTO notices (title, content, member_idx, read_count, front_yn, rentshop_yn, dt_create, dt_delete)
	SELECT
		subject,
		memo,
		mem_idx,
		readcount,
		'Y',
		'N',
		wdate,
		IF(isdel = 'Y',now(),NULL)
	FROM board
	WHERE
		boardid = 'notice'
	ORDER BY
		wdate ASC;

-- 기존 멤버사 노출 공지사항 데이터 insert
INSERT INTO notices (title, content, member_idx, read_count, front_yn, rentshop_yn, dt_create, dt_delete)
	SELECT
		subject,
		memo,
		mem_idx,
		readcount,
		'N',
		'Y',
		wdate,
		IF(isdel = 'Y',now(),NULL)
	FROM board
	WHERE
		boardid = 'pnotice'
	ORDER BY
		wdate ASC;


-- 기존 이벤트 데이터 insert
INSERT INTO events (title, content, member_idx, read_count, dt_start, dt_end, dt_create, dt_delete)
	SELECT
		subject,
		memo,
		mem_idx,
		readcount,
		'2000-01-01 00:00:00',
		'2100-12-31 00:00:00',
		wdate,
		IF(isdel = 'Y',now(),NULL)
	FROM board
	WHERE
		boardid = 'event'
	ORDER BY
		wdate ASC;


DROP TABLE `board`;
DROP TABLE `board_attach`;
DROP TABLE `board_conf`;
