SELECT idx, name FROM site_member WHERE memgrade=10;
SELECT idx, affiliate FROM site_rentshop;


ALTER TABLE `site_member`
	CHANGE COLUMN `name` `name` VARCHAR(50) NOT NULL DEFAULT '' COMMENT '이름' AFTER `idx`,
	DROP COLUMN `company`;

ALTER TABLE `site_rentshop`
	CHANGE COLUMN `nickname` `affiliate` VARCHAR(50) NOT NULL COMMENT '닉네임' AFTER `mem_idx`,
	DROP COLUMN `name`;

-- site_rentshop.affiliate -> site_member.name
UPDATE site_member m LEFT JOIN site_rentshop r ON m.idx = r.mem_idx SET m.name = r.affiliate WHERE m.memgrade = 10;

-- site_rentshop.affiliate = site_member.name (지점명)
SELECT m.name, TRIM(LEFT(m.name, POSITION('/' IN m.name)-1)) affiliate FROM site_member m WHERE m.memgrade = 10 AND m.name LIKE '%/%';
UPDATE site_rentshop r LEFT JOIN site_member m ON r.mem_idx = m.idx SET r.affiliate = TRIM(LEFT(m.name, POSITION('/' IN m.name)-1));

-- site_member.name = 업체명
SELECT m.name, TRIM(SUBSTR(m.name, POSITION('/' IN m.name)+1)) nameto FROM site_member m WHERE m.memgrade = 10 AND m.name LIKE '%/%';
UPDATE site_member m SET m.name = TRIM(SUBSTR(m.name, POSITION('/' IN m.name)+1)) WHERE m.memgrade = 10 AND m.name LIKE '%/%';