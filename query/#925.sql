ALTER TABLE `member`
	ADD COLUMN `birth` DATE NOT NULL COMMENT '생년월일' AFTER `passwd`;

ALTER TABLE `member`
	CHANGE COLUMN `birth` `birth` DATE NULL COMMENT '생년월일' AFTER `passwd`;

UPDATE member SET birth = NULL WHERE birth = '0000-00-00';
SELECT birth_year, birth_month, birth_day FROM member WHERE LENGTH(birth_year) = 4 AND LENGTH(birth_month) = 2 AND LENGTH(birth_day) = 2 AND (birth_year < 1900 OR birth_year > 2000 OR birth_month > 12 OR birth_day > 31 OR (birth_month = 6 AND birth_day > 30));
UPDATE member SET birth = CONCAT(birth_year, '-', birth_month, '-', birth_day) WHERE LENGTH(birth_year) = 4 AND LENGTH(birth_month) = 2 AND LENGTH(birth_day) = 2;

ALTER TABLE `member`
	DROP COLUMN `birth_year`,
	DROP COLUMN `birth_month`,
	DROP COLUMN `birth_day`;


SELECT
	CASE
		WHEN age < 21 THEN '0_21세 미만'
		WHEN age < 26 THEN '1_21~25세'
		WHEN age < 30 THEN '2_26~29세'
		WHEN age < 40 THEN '3_30대'
		WHEN age < 50 THEN '4_40대'
		WHEN age < 60 THEN '5_50대'
		ELSE '6_60세 이상'
	END agetype,
	COUNT(1) member,
	SUM(reservation_short) reservation_short,
	SUM(reservation_long) reservation_long
FROM (
		SELECT
			FLOOR(DATEDIFF(NOW(), birth) / 365) age,
			SUM(
				CASE
					WHEN m.dan < 5 AND mt.retype = 1 THEN 1
					WHEN m.dan < 5 AND mt.retype = 2 THEN 0
					ELSE 0
				END
			) reservation_short,
			SUM(
				CASE
					WHEN m.dan < 5 AND mt.retype = 1 THEN 0
					WHEN m.dan < 5 AND mt.retype = 2 THEN 1
					ELSE 0
				END
			) reservation_long
		FROM
			member mem
			LEFT JOIN marketdb m ON mem.idx = m.mem_idx
			LEFT JOIN marketdb_tmp mt ON m.tmp_idx = mt.idx
		WHERE
			mem.memgrade = 100
		GROUP BY mem.idx
	) t
GROUP BY agetype






