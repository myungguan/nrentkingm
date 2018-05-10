ALTER TABLE `site_marketdb`
	ADD COLUMN `tmp_idx` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'site_marketdb_tmp.index_no' AFTER `index_no`;

UPDATE site_marketdb m SET tmp_idx = (CASE WHEN (SELECT index_no FROM site_marketdb_tmp WHERE market_idx IS NOT NULL AND market_idx = m.index_no) IS NULL THEN 0 ELSE (SELECT index_no FROM site_marketdb_tmp WHERE market_idx IS NOT NULL AND market_idx = m.index_no) END);

-- 소스 복사

ALTER TABLE `site_marketdb_tmp`
	DROP COLUMN `nomem`,
	DROP COLUMN `mem_idx`,
	DROP COLUMN `dan`,
	DROP COLUMN `zipcode`,
	DROP COLUMN `addr2`,
	DROP COLUMN `rzipcode`,
	DROP COLUMN `raddr2`,
	DROP COLUMN `buymethod`,
	DROP COLUMN `usepoint`,
	DROP COLUMN `model_idx`,
	CHANGE COLUMN `addr1` `addr` VARCHAR(200) NOT NULL COMMENT '대여 주소',
	CHANGE COLUMN `raddr1` `raddr` VARCHAR(200) NOT NULL COMMENT '반납 주소';

ALTER TABLE `site_marketdb`
	CHANGE COLUMN `usepoint` `usepoint` INT(10) UNSIGNED NOT NULL COMMENT '포인트 결제' AFTER `usecoupen`,
	CHANGE COLUMN `totalaccount` `totalaccount` INT(10) UNSIGNED NOT NULL COMMENT '최종 결제 금액 (account + dealac + insuac - usecoupon - usepoint)' AFTER `usepoint`,
	DROP COLUMN `mem_idx_sub`,
	DROP COLUMN `mem_name`,
	DROP COLUMN `mem_id`,
	DROP COLUMN `cp`,
	DROP COLUMN `birthsub`,
	DROP COLUMN `sexsub`,
	DROP COLUMN `phone`,
	DROP COLUMN `company`,
	DROP COLUMN `car_idx`,
	DROP COLUMN `model_idx`,
	DROP COLUMN `retype`,
	DROP COLUMN `ddata1`,
	DROP COLUMN `ddata2`,
	DROP COLUMN `ptype`,
	DROP COLUMN `rtype`,
	DROP COLUMN `sdate`,
	DROP COLUMN `edate`,
	DROP COLUMN `account`,
	DROP COLUMN `account1`,
	DROP COLUMN `account2`,
	DROP COLUMN `preaccount`,
	DROP COLUMN `delac`,
	DROP COLUMN `insu`,
	DROP COLUMN `insuac`,
	DROP COLUMN `addaccount`,
	DROP COLUMN `zipcode`,
	DROP COLUMN `addr1`,
	DROP COLUMN `addr2`,
	DROP COLUMN `rzipcode`,
	DROP COLUMN `raddr1`,
	DROP COLUMN `raddr2`;


ALTER TABLE `site_marketdb_accounts`
	DROP COLUMN `tmp_idx`;