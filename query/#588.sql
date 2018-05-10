CREATE TABLE `site_ilikeclick` (
	`buyno` VARCHAR(50) NOT NULL,
	`ccode` VARCHAR(100) NULL DEFAULT NULL,
	`pcode` VARCHAR(100) NULL DEFAULT NULL,
	`pname` VARCHAR(100) NULL DEFAULT NULL,
	`pnum` INT(11) NULL DEFAULT NULL,
	`popt` VARCHAR(100) NULL DEFAULT NULL,
	`price` BIGINT(20) NULL DEFAULT NULL,
	`buytype` CHAR(1) NULL DEFAULT NULL,
	`member_id` VARCHAR(50) NULL DEFAULT NULL,
	`username` VARCHAR(50) NULL DEFAULT NULL,
	`valuefromclick` VARCHAR(255) NULL DEFAULT NULL,
	`dt` DATETIME NULL DEFAULT NULL,
	PRIMARY KEY (`buyno`)
)
ENGINE=MyISAM
;