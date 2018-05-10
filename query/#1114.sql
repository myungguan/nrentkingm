SELECT
	r.idx 번호,
	CONCAT(m.name, '(', r.affiliate, ')') 회사명_지점,
	r.dname1 대표자명,
	r.dphone1 대표자Mobile,
	r.demail1 대표자Email,
	r.dbirth1 대표자생년월일,
	r.dname2 제휴담당자명,
	r.dphone2 제휴담당자명Mobile,
	r.demail2 제휴담당자명Email,
	r.dbirth2 제휴담당자명생년월일,
	r.dname3 경리담당자명,
	r.dphone3 경리담당자명Mobile,
	r.demail3 경리담당자명Email,
	r.dbirth3 경리담당자명생년월일
FROM
	rentshop r
	LEFT JOIN member m ON r.mem_idx = m.idx