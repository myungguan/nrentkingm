ALTER TABLE `marketdb`
	ADD COLUMN `ptype` CHAR(1) NOT NULL COMMENT '대여방법(1: 배달대여 / 2: 지점방문)' AFTER `dan`,
	ADD COLUMN `delac` INT NOT NULL DEFAULT '0' COMMENT '배달료' AFTER `ptype`,
	CHANGE COLUMN `usecoupon` `usecoupon` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '쿠폰사용(금액)' AFTER `delac`,
	CHANGE COLUMN `usepoint` `usepoint` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '포인트 결제' AFTER `usecoupon`,
	CHANGE COLUMN `totalaccount` `totalaccount` INT(10) UNSIGNED NOT NULL COMMENT '최종 결제 금액 (site_marketdb_tmp.totalaccount + delac - usecoupon - usepoint)' AFTER `usepoint`;

UPDATE marketdb_tmp SET totalaccount = totalaccount - delac;
UPDATE marketdb m LEFT JOIN marketdb_tmp mt ON m.tmp_idx = mt.idx SET m.delac = CASE WHEN mt.delac IS NOT NULL THEN mt.delac ELSE 0 END;
UPDATE marketdb m LEFT JOIN marketdb_tmp mt ON m.tmp_idx = mt.idx SET m.ptype = CASE WHEN mt.ptype IS NOT NULL THEN mt.ptype ELSE '2' END;

ALTER TABLE `marketdb_tmp`
	DROP COLUMN `ptype`,
	DROP COLUMN `delac`;