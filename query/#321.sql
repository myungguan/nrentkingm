SELECT
	-- 브랜드
	vs.company,
	vac.sname company_name,

	-- 연식
	SUBSTR(v.outdate, 1, 4) year,

	-- 연료
	vs.fuel,
	vaf.sname fuel_name,

	-- 색상
	v.color
FROM
	site_vehicle v
	LEFT JOIN site_vehicle_std vs ON v.model_idx=vs.index_no
	LEFT JOIN site_vehicle_attribute vac ON vac.index_no = vs.company
	LEFT JOIN site_vehicle_attribute vaf ON vaf.index_no = vs.fuel
	LEFT JOIN site_rentshop r ON v.com_idx = r.index_no
	LEFT JOIN site_rentshop_off ro ON v.com_idx = ro.com_idx AND ('2017-03-16' BETWEEN ro.sdate AND ro.edate OR '2017-05-16' BETWEEN ro.sdate AND ro.edate)
	LEFT JOIN site_vehicle_off vo ON v.index_no = vo.car_idx AND ('2017-03-16' BETWEEN vo.offsdate AND vo.offedate OR '2017-05-16' BETWEEN vo.offsdate AND vo.offedate)
WHERE
	v.isdel='N'
	AND r.isok = 'Y'
	AND v.index_no NOT IN (SELECT distinct(car_idx) FROM site_marketdb WHERE dan IN ('1','2','4') AND edate>='2017-03-16 17:00:00' AND sdate<='2017-05-16 17:00:00');