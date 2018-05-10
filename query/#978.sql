
ALTER TABLE `vehicle`
	ADD COLUMN `fuel` INT(10) UNSIGNED NOT NULL COMMENT '연료' AFTER `model_idx`;


ALTER TABLE `vehicle`
	CHANGE COLUMN `fuel` `fuel` INT(10) UNSIGNED NULL COMMENT '연료' AFTER `model_idx`;

ALTER TABLE `vehicle_std`
	CHANGE COLUMN `fuel` `fuel` VARCHAR(20) NULL DEFAULT NULL COMMENT '연료타입(site_vehicle_attribute.ttype = 3, .index_no)' AFTER `grade`;

UPDATE vehicle v LEFT JOIN vehicle_std vs ON v.model_idx = vs.idx SET v.fuel = vs.fuel;

SELECT * FROM vehicle_std WHERE status <> 'Y' ORDER BY modelname;
UPDATE vehicle SET model_idx=254 WHERE model_idx IN (255);

