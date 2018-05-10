# site_member 내 사용 안하는 필드 삭제
ALTER TABLE `site_member`
	DROP COLUMN `cpauth`,
	DROP COLUMN `lastlogin`,
	DROP COLUMN `lastorder`,
	DROP COLUMN `lastip`,
	DROP COLUMN `memcoins`,
	DROP COLUMN `memaccounts`,
	DROP COLUMN `mempriv`,
	DROP COLUMN `enterc`,
	DROP COLUMN `enterk`,
	DROP COLUMN `fid`;
