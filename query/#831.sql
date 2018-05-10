
ALTER TABLE `marketdb_tmp`
	ADD COLUMN `insu_exem` INT(10) UNSIGNED NULL DEFAULT NULL COMMENT '면책금' AFTER `insuac`;

UPDATE
		marketdb_tmp mt
		LEFT JOIN vehicle v ON mt.car_idx = v.idx
		LEFT JOIN vehicle_price vp ON v.price_idx = vp.idx
		LEFT JOIN vehicle_price_longterm vpl ON v.price_longterm_idx = vpl.idx
SET insu_exem = CASE
				WHEN mt.pricetype = 2 THEN vpl.price_longterm_insu_exem
				WHEN mt.pricetype = 1 AND mt.insu = 1 THEN vp.price_insu1_exem
				WHEN mt.pricetype = 1 AND mt.insu = 2 THEN vp.price_insu2_exem
				WHEN mt.pricetype = 1 AND mt.insu = 3 THEN vp.price_insu3_exem
				END;