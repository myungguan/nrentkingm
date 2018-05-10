/*
과거 대차기록중 수술 변경이 안된채 넘어가있는 데이터 수정
 */

-- 결제내역 수수료와 맴버사의 수수료가 일치하지 않는 건 조회
SELECT
	CONCAT(m.name, '(', r2.affiliate, ')') 멤버사,
	p.idx 예약번호,
	pa.settlement_per 잘못된_수수료,
	r2.per1 정상_수수료,
	pa.dt_settlement 정산예정일,
	at.dt_create 정산일,
	FLOOR(CASE
		  WHEN p.dan < 4 AND  pa.turn = 1 THEN (pa.account - pa.isouts) + p.discount
		  ELSE pa.account - pa.isouts
		  END * (100 - pa.settlement_per) / 100) 잘못된_정산금액,
	FLOOR(CASE
		  WHEN p.dan < 4 AND  pa.turn = 1 THEN (pa.account - pa.isouts) + p.discount
		  ELSE pa.account - pa.isouts
		  END * (100 - r2.per1) / 100) 정상_정산금액,
	FLOOR(CASE
		  WHEN p.dan < 4 AND  pa.turn = 1 THEN (pa.account - pa.isouts) + p.discount
		  ELSE pa.account - pa.isouts
		  END * (100 - pa.settlement_per) / 100) -
	FLOOR(CASE
			  WHEN p.dan < 4 AND  pa.turn = 1 THEN (pa.account - pa.isouts) + p.discount
			  ELSE pa.account - pa.isouts
			  END * (100 - r2.per1) / 100) 차이
FROM payments p
	LEFT JOIN payment_accounts pa ON pa.payment_idx = p.idx
	LEFT JOIN reservation r ON p.reservation_idx = r.idx
	LEFT JOIN vehicle v ON r.car_idx = v.idx
	LEFT JOIN rentshop r2 ON v.rentshop_idx = r2.idx
	LEFT JOIN account_transfer at ON pa.account_transfer_idx = at.idx
	LEFT JOIN member m ON r2.mem_idx = m.idx
WHERE pa.settlement_per != r2.per1
ORDER BY pa.dt_settlement DESC, at.dt_create DESC;

-- 결제내역 수수료와 맴버사의 수수료가 일치하지 않는 건 맴버사 수수료로 update
UPDATE payments p
	LEFT JOIN payment_accounts pa ON pa.payment_idx = p.idx
	LEFT JOIN reservation r ON p.reservation_idx = r.idx
	LEFT JOIN vehicle v ON r.car_idx = v.idx
	LEFT JOIN rentshop r2 ON v.rentshop_idx = r2.idx
SET pa.settlement_per = r2.per1
WHERE pa.settlement_per != r2.per1;