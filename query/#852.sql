SELECT
	CONCAT(m.name, '(', r.affiliate, ')') 멤버사,
	r.isok 사용인증,
	r.demail2 제휴담당자이메일,
	r.demail3 경리담당자이메일
FROM
	member m
	LEFT JOIN rentshop r ON m.idx = r.mem_idx
WHERE
	m.memgrade=10