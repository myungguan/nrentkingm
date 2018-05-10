SELECT
	t.idx
FROM (
	SELECT
		m.idx,
		COUNT(1) c
	FROM
		marketdb m
		LEFT JOIN marketdb_accounts ma ON m.idx = ma.market_idx AND ma.tbtype = 'I' AND ma.account > ma.isouts
	WHERE
		ma.buymethod = 'F'
	GROUP BY m.idx
) t
WHERE t.c > 1
;

SELECT
	*
FROM
	marketdb_accounts
WHERE market_idx IN (SELECT
						 t.idx
					 FROM (
							  SELECT
								  m.idx,
								  COUNT(1) c
							  FROM
								  marketdb m
								  LEFT JOIN marketdb_accounts ma ON m.idx = ma.market_idx AND ma.tbtype = 'I' AND ma.account > ma.isouts
							  WHERE
								  ma.buymethod = 'F'
							  GROUP BY m.idx
						  ) t
					 WHERE t.c > 1)
ORDER BY market_idx, idx;