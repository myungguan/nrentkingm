-- 정기결제고객 조기반납했으나 dt_next_payment가 null 세팅이 안된 항목들 update
UPDATE payment SET dt_next_payment = null WHERE idx IN(1705, 1726, 755, 1414);