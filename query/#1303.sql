-- 케이렌트카 -> 대보렌트카 대차했지만 수수료 변경 안된건 update
UPDATE payment_accounts SET settlement_per = 15 WHERE payment_idx = 2104;