SELECT
	v.index_no 번호,
	r.nickname 멤버사명,
	vs.modelname 차종,
	v.carnum 차량번호,
	vo.op_data 시트
FROM
	site_vehicle v
	LEFT JOIN site_rentshop r ON v.com_idx = r.index_no
	LEFT JOIN site_vehicle_std vs ON v.model_idx = vs.index_no
	LEFT JOIN site_vehicle_opt vo ON vo.car_idx = v.index_no AND vo.op_name = '시트'
ORDER BY
	r.index_no
;