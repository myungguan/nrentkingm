SELECT color FROM site_vehicle;
UPDATE site_vehicle SET color = '블랙' WHERE color='검은색';
UPDATE site_vehicle SET color = '화이트' WHERE color='흰색';
UPDATE site_vehicle SET color = '레드' WHERE color='빨강';
UPDATE site_vehicle SET color = '옐로우' WHERE color='노랑';
UPDATE site_vehicle SET color = '그레이' WHERE color='회색';
UPDATE site_vehicle SET color = '실버' WHERE color='은색';

ALTER TABLE `site_rentshop`
	ADD COLUMN `latlng` POINT NULL DEFAULT NULL AFTER `addr2`;


SELECT
	index_no,
	addr1,
	X(latlng) lat,
	Y(latlng) lng,
	( 6371000 * acos( cos( radians(37.477613041) )
				   * cos( radians( X(latlng) ) )
				   * cos( radians( Y(latlng) ) - radians(127.1249628964) )
				   + sin( radians(37.477613041) )
					 * sin( radians( X(latlng) ) ) ) ) distance
# 	GLENGTH(
# 			LINESTRING(
# 				GEOMCOLLFROMTEXT('POINT(37.477613041 127.1249628964)'),
# 				latlng
# 			)
# 	) distance2,
# 	(GLength(LineStringFromWKB(LineString(latlng, GeomFromText('POINT(37.477613041 127.1249628964)'))))) * 100 distance3
FROM site_rentshop
ORDER BY distance
# LIMIT 0, 20
;

ALTER TABLE `site_marketdb`
	CHANGE COLUMN `namesub` `namesub` VARCHAR(50) NULL COMMENT '제2운전자 이름' AFTER `mem_idx_sub`,
	CHANGE COLUMN `cpsub` `cpsub` VARCHAR(50) NULL COMMENT '제2운전자 휴대폰번호' AFTER `namesub`,
	ADD COLUMN `kindsub` VARCHAR(10) NULL COMMENT '제2운전자 면허종류' AFTER `cpsub`,
	ADD COLUMN `codesub` VARCHAR(10) NULL COMMENT '제2운전자 지역/코드' AFTER `kindsub`,
	ADD COLUMN `numsub` VARCHAR(30) NULL COMMENT '제2운전자 면허번호' AFTER `codesub`,
	ADD COLUMN `date1sub` VARCHAR(10) NULL COMMENT '제2운전자 적성검사 만료일' AFTER `numsub`,
	ADD COLUMN `date2sub` VARCHAR(10) NULL COMMENT '제2운전자 발급일' AFTER `date1sub`;
;


ALTER TABLE `site_marketdb_tmp`
	ADD COLUMN `market_idx` INT(10) UNSIGNED NULL COMMENT 'site_marketdb.index_no' AFTER `car_idx`;

ALTER TABLE `site_member`
	DROP COLUMN `name_etc`;

ALTER TABLE `site_member`
	DROP COLUMN `nickname`;


-- 여기까지 실행됨


-- 운영배포시 실행!!!!!!!!!!!!!!!
INSERT INTO app(platform, version, dt_release, force_update) VALUES ('android', '1.2.835', NOW(), 'N');
UPDATE site_marketdb_tmp SET pricetype = retype WHERE market_idx IS NULL;
UPDATE site_marketdb_tmp mt SET mt.market_idx = (SELECT market_idx FROM site_marketdb_accounts WHERE tmp_idx = mt.index_no ORDER BY wdate DESC LIMIT 0, 1);
UPDATE site_marketdb_accounts ma SET buymethod = 'F' WHERE ma.rawdata LIKE 'FIX%';
UPDATE site_marketdb m SET m.buymethod =
	CASE
		WHEN (SELECT buymethod FROM site_marketdb_accounts WHERE market_idx = m.index_no AND tbtype='I') IS NOT NULL THEN (SELECT buymethod FROM site_marketdb_accounts WHERE market_idx = m.index_no AND tbtype='I')
		ELSE ''
	END;
UPDATE site_marketdb_accounts ma SET buymethod = (SELECT buymethod FROM site_marketdb WHERE index_no = ma.market_idx) WHERE ma.up_idx <> 0;
ALTER TABLE `site_member_card`
	CHANGE COLUMN `cardt` `cardt` CHAR(1) NOT NULL COMMENT '카드타입(0: 개인카드 / 1: 법인카드)' AFTER `cardname`;
UPDATE site_member_card SET cardt = '0' WHERE cardt = '1';
UPDATE site_member_card SET cardt = '1' WHERE cardt = '2';

UPDATE site_member SET name = company WHERE memgrade=10;