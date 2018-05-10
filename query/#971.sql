SELECT
	DATE(m.makedate) dt,
	(CASE
		 WHEN lv.referer = '' AND lv.user_agent LIKE '%rentking%' THEN 'App'
		 WHEN lv.referer = '' OR lv.referer LIKE 'http://www.rentking.co.kr/%' OR lv.referer LIKE 'http://m.rentking.co.kr/%' OR lv.referer LIKE 'https://www.rentking.co.kr/%' OR lv.referer LIKE 'https://m.rentking.co.kr/%'  THEN '직접방문'
		 WHEN lv.url LIKE '/?gclid%' THEN 'Google(유료)'
		 WHEN lv.referer LIKE '%google.co%' THEN 'Google'
		 WHEN lv.referer LIKE '%search.naver%' AND (lv.url LIKE '/?NaPm%' OR lv.url LIKE '/?n_media=%') THEN 'Naver 검색(유료)'
		 WHEN lv.referer LIKE '%search.naver%' THEN 'Naver 검색'
		 WHEN lv.referer LIKE '%kin.naver%' THEN 'Naver 지식인'
		 WHEN lv.referer LIKE '%naver%' THEN 'Naver'
		 WHEN lv.referer LIKE '%daum.net%' THEN 'Daum'
		 WHEN lv.referer LIKE '%facebook%' THEN 'Facebook'
		 WHEN lv.referer LIKE '%linkprice.com%' THEN 'LinkPrice'
		 WHEN lv.referer LIKE '%dreamad.co.kr%' THEN 'dreamad'
		 WHEN lv.referer LIKE '%ncclick.co.kr%' THEN 'ncclick'
		 WHEN lv.referer LIKE '%dwclick.co.kr%' THEN 'dwclick'
		 ELSE '기타'
	 END) r,
	ma.account - ma.isouts account
FROM
	marketdb_accounts ma
	LEFT JOIN marketdb m ON ma.market_idx = m.idx
	LEFT JOIN marketdb_tmp mt ON m.tmp_idx = mt.idx
	LEFT JOIN log_visit lv ON mt.session_id = lv.session_id
WHERE
	ma.tbtype = 'I'
	AND ma.account > ma.isouts
	AND m.makedate >= '2017-07-25' AND m.makedate <= '2017.07-31'
	AND lv.referer LIKE '%facebook%'