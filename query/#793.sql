SELECT
	vs.modelname 모델,
	(SELECT COUNT(1) FROM site_vehicle WHERE model_idx = vs.idx) 등록차량
FROM
	site_vehicle_std vs
ORDER BY
	vs.modelname