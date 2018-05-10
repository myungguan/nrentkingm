
ALTER TABLE `vehicle`
	CHANGE COLUMN `rentage` `rentage` CHAR(2) NOT NULL COMMENT '연령제한' AFTER `color`;

SELECT rentage FROM vehicle;
UPDATE vehicle SET rentage=21 WHERE rentage='';
UPDATE vehicle SET rentage=21 WHERE rentage='A';
UPDATE vehicle SET rentage=26 WHERE rentage='B';


ALTER TABLE `vehicle`
	CHANGE COLUMN `rentage` `rentage` INT NOT NULL COMMENT '연령제한' AFTER `color`;