UPDATE vehicle SET model_idx = 87 WHERE model_idx IN (155, 289);
DELETE FROM vehicle_std WHERE idx IN (155, 289);

UPDATE vehicle SET model_idx = 288 WHERE model_idx IN (349, 348, 88);
DELETE FROM vehicle_std WHERE idx IN (349, 348, 88);

DELETE FROM vehicle_std WHERE idx IN (89, 346, 287, 79, 226, 91, 290, 132, 108, 310);

UPDATE vehicle SET model_idx = 14 WHERE model_idx = 132;
UPDATE vehicle SET model_idx = 307 WHERE model_idx = 108;

SELECT
	vs.idx idx,
	vs.modelname,
	va.sname 연료,
	vs.status 삭제여부,
	SUM(CASE WHEN v.idx IS NOT NULL THEN 1 ELSE 0 END) 등록차량
FROM
	vehicle_std vs
	LEFT JOIN vehicle v ON vs.idx = v.model_idx
	LEFT JOIN vehicle_attribute va ON vs.fuel = va.idx
GROUP BY vs.idx
ORDER BY vs.modelname;


UPDATE vehicle_std SET modelname = 'BMW 328i 컨버터블' WHERE idx = 75;
UPDATE vehicle_std SET modelname = 'BMW 740Li' WHERE idx = 161;
UPDATE vehicle_std SET modelname = 'BMW 750LD' WHERE idx = 156;
UPDATE vehicle_std SET modelname = 'K5 2.0' WHERE idx = 52;
UPDATE vehicle_std SET modelname = 'K5 올 뉴 MX' WHERE idx = 38;
UPDATE vehicle_std SET modelname = 'SM3 네오' WHERE idx = 185;
UPDATE vehicle_std SET modelname = 'SM5 뉴 노바' WHERE idx = 324;
UPDATE vehicle_std SET modelname = 'SM5 뉴 플래티넘' WHERE idx = 325;
UPDATE vehicle_std SET modelname = 'SM7 뉴 노바' WHERE idx = 126;
UPDATE vehicle_std SET modelname = '도요타 시에나' WHERE idx = 307;
UPDATE vehicle_std SET modelname = '렉서스 ES 300H' WHERE idx = 298;
UPDATE vehicle_std SET modelname = '렉서스 NX 300H' WHERE idx = 294;
UPDATE vehicle_std SET modelname = '말리부 2.0' WHERE idx = 113;
UPDATE vehicle_std SET modelname = '벤츠 CLA 250 4MATIC' WHERE idx = 217;
UPDATE vehicle_std SET modelname = '벤츠 CLA 250 4MATIC 45AMG' WHERE idx = 158;
UPDATE vehicle_std SET modelname = '벤츠 CLS 63AMG' WHERE idx = 102;
UPDATE vehicle_std SET modelname = '벤츠 E220 D' WHERE idx = 270;
UPDATE vehicle_std SET modelname = '벤츠 E250 D' WHERE idx = 97;
UPDATE vehicle_std SET modelname = '벤츠 E400' WHERE idx = 98;
UPDATE vehicle_std SET modelname = '벤츠 S350D' WHERE idx = 200;
UPDATE vehicle_std SET modelname = '벤츠 S500 L' WHERE idx = 277;
UPDATE vehicle_std SET modelname = '벤츠 S500 4MATIC L' WHERE idx = 107;
UPDATE vehicle_std SET modelname = '쏘울 올 뉴' WHERE idx = 197;
UPDATE vehicle_std SET modelname = '쏘울 더 뉴' WHERE idx = 55;
UPDATE vehicle_std SET modelname = '아반떼 MD' WHERE idx = 133;
UPDATE vehicle_std SET modelname = '아우디 A6 3.5 TDI' WHERE idx = 131;
UPDATE vehicle_std SET modelname = '아우디 A6 4.0 TDI' WHERE idx = 222;
UPDATE vehicle_std SET modelname = '아우디 A7 4.0 TDI' WHERE idx = 223;
UPDATE vehicle_std SET modelname = '크루즈 2.0 D' WHERE idx = 120;
UPDATE vehicle_std SET modelname = '폭스바겐 CC 2.0 TSI' WHERE idx = 139;
UPDATE vehicle_std SET modelname = '폭스바겐 티구안 2.0 4MOTION' WHERE idx = 206;

UPDATE vehicle SET model_idx=161 WHERE model_idx=286;
UPDATE vehicle SET model_idx=2 WHERE model_idx=3;
UPDATE vehicle SET model_idx=328 WHERE model_idx=39;
UPDATE vehicle SET model_idx=328 WHERE model_idx=323;
UPDATE vehicle SET model_idx=37 WHERE model_idx=194;
UPDATE vehicle SET model_idx=154 WHERE model_idx=119;
UPDATE vehicle SET model_idx=125 WHERE model_idx=1;
UPDATE vehicle SET model_idx=330 WHERE model_idx=143;
UPDATE vehicle SET model_idx=65 WHERE model_idx=151;
UPDATE vehicle SET model_idx=65 WHERE model_idx=129;
UPDATE vehicle SET model_idx=193 WHERE model_idx=186;
UPDATE vehicle SET model_idx=344 WHERE model_idx=202;
UPDATE vehicle SET model_idx=241 WHERE model_idx=242;
UPDATE vehicle SET model_idx=201 WHERE model_idx=100;
UPDATE vehicle SET model_idx=270 WHERE model_idx=285;
UPDATE vehicle SET model_idx=270 WHERE model_idx=83;
UPDATE vehicle SET model_idx=205 WHERE model_idx=96;
UPDATE vehicle SET model_idx=19 WHERE model_idx=18;
UPDATE vehicle SET model_idx=121 WHERE model_idx=301;
UPDATE vehicle SET model_idx=135 WHERE model_idx=166;
UPDATE vehicle SET model_idx=159 WHERE model_idx=267;
UPDATE vehicle SET model_idx=152 WHERE model_idx=130;

DELETE FROM vehicle_std WHERE idx IN (286, 3, 39, 323, 194, 119, 1, 143, 151, 129, 186, 202, 242, 274, 276, 94, 100, 101, 282, 285, 83, 269, 284, 283, 218, 272, 96, 273, 278, 104, 105, 263, 260, 18, 305, 322, 303, 302, 354, 301, 300, 304, 71, 166, 245, 215, 147, 267, 268, 317, 130, 314, 315, 316, 138);
