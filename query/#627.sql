ALTER TABLE `site_marketdb`
	CHANGE COLUMN `pid` `pid` CHAR(1) NOT NULL AFTER `pdan`;

UPDATE site_marketdb sm SET sm.pid='A' WHERE sm.pid='0';
UPDATE site_marketdb sm SET sm.pid='W' WHERE sm.pid='1';
UPDATE site_marketdb sm SET sm.pid='M' WHERE sm.pid='2';