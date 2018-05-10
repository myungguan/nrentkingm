-- payment_accounts.order_no 추가
ALTER TABLE `payment_accounts`
	ADD COLUMN `order_no` VARCHAR(20) NULL DEFAULT '' COMMENT 'pg사 주문번호' AFTER `shop_id`;

-- 추가된 order_no에 데이터 넣음
UPDATE payment_accounts pa
	LEFT JOIN payments p ON pa.payment_idx = p.idx
SET order_no =
CONCAT(p.reservation_idx,
	   CASE
	   WHEN pa.buymethod = 'F' THEN CONCAT('.', pa.turn)
	   ELSE ''
	   END)
WHERE order_no = '';