
UPDATE `site_marketdb` SET `dan`='5' WHERE  `idx`=201;
UPDATE `site_marketdb` SET `dan`='5' WHERE  `idx`=228;

UPDATE `site_marketdb_accounts` SET `isouts`='271900' WHERE  `idx`=279;
UPDATE `site_marketdb_accounts` SET `isouts`='103500' WHERE  `idx`=317;

INSERT INTO site_marketdb_accounts(market_idx, tbtype, buymethod, account, rawdata, wdate, isouts, up_idx)
VALUES (201, 'O', 'F', 271900, '', '2017-05-26 16:24:28', 0, 279);

INSERT INTO site_marketdb_accounts(market_idx, tbtype, buymethod, account, rawdata, wdate, isouts, up_idx)
VALUES (228, 'O', 'F', 103500, '', '2017-05-30 10:18:08', 0, 317);