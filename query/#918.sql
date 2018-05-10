SELECT NOW();

-- 실시간 방문 세션
SELECT
	COUNT(1) session
FROM (
		 SELECT session_id
		 FROM log_pageview
		 WHERE dt >= NOW() - INTERVAL 10 MINUTE
		 GROUP BY session_id
	 ) t;

SELECT
	FROM_UNIXTIME(FLOOR(UNIX_TIMESTAMP(dt)/20)*20) time,
	COUNT(1) session
FROM log_pageview
WHERE dt >= NOW() - INTERVAL 10 MINUTE
GROUP BY time;

SELECT
	dt
FROM log_pageview
WHERE dt >= NOW() - INTERVAL 10 MINUTE;

-- 실시간 검색 세션
SELECT
	COUNT(1) session
FROM (
		 SELECT session_id
		 FROM log_search
		 WHERE dt >= NOW() - INTERVAL 10 MINUTE
		 GROUP BY session_id
	 ) t;

SELECT
	FROM_UNIXTIME(FLOOR(UNIX_TIMESTAMP(dt)/20)*20) time,
	COUNT(1) session
FROM log_search
WHERE dt >= NOW() - INTERVAL 10 MINUTE
GROUP BY time;

-- 실시간 예약페이지 방문 세션
SELECT
	COUNT(1) session
FROM (
		 SELECT session_id
		 FROM log_pageview
		 WHERE
			 dt >= NOW() - INTERVAL 10 MINUTE
			 AND url LIKE '/rent/reservation%'
		 GROUP BY session_id
	 ) t;

SELECT
	FROM_UNIXTIME(FLOOR(UNIX_TIMESTAMP(dt)/20)*20) time,
	COUNT(1) session
FROM log_pageview
WHERE
	dt >= NOW() - INTERVAL 10 MINUTE
	AND url LIKE '/rent/reservation%'
GROUP BY time;

-- 금일 예약/취소
SELECT
	SUM(CASE WHEN dan < 5 THEN 1 ELSE 0 END) reservation,
	SUM(CASE WHEN dan < 5 THEN 0 ELSE 1 END) cancel
FROM
	marketdb
WHERE
	makedate >= DATE_FORMAT(NOW(), '%Y-%m-%d');

SELECT
	CONCAT(LEFT(makedate, 13), ':00:00') time,
	COUNT(1) reservation
FROM
	marketdb
WHERE
	makedate >= NOW() - INTERVAL 24 HOUR
	AND dan < 5
GROUP BY time;

SELECT
	DATE_FORMAT(makedate, '%Y-%m-%d') time,
	COUNT(1) data
FROM
	marketdb
WHERE
	makedate >= NOW() - INTERVAL 30 DAY
	AND dan < 5
GROUP BY time;

-- 금일 매출
SELECT
	SUM(account - isouts) sales
FROM
	marketdb_accounts
WHERE
	wdate >= DATE_FORMAT(NOW(), '%Y-%m-%d')
	AND tbtype = 'I'
	AND account > isouts;

SELECT
	DATE_FORMAT(wdate, '%Y-%m-%d') time,
	SUM(account - isouts) data
FROM
	marketdb_accounts
WHERE
	wdate >= DATE_FORMAT(NOW(), '%Y-%m-%d') - INTERVAL 30 DAY
	AND tbtype = 'I'
	AND account > isouts
GROUP BY time;

-- 금일 회원가입
SELECT COUNT(1) member
FROM member
WHERE
	signdate >= DATE_FORMAT(NOW(), '%Y-%m-%d')
	AND memgrade = 100;


SELECT
	FROM_UNIXTIME(FLOOR(UNIX_TIMESTAMP(signdate)/3600)*3600) time,
	COUNT(1) data
FROM member
WHERE signdate >= DATE_FORMAT(NOW(), '%Y-%m-%d %H') - INTERVAL 1 DAY
GROUP BY time;
