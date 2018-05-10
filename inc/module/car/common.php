<?
if ($han == 'company') {

	$q = "SELECT * FROM codes WHERE ttype='1' AND dt_delete IS NULL ORDER BY idx ASC";
	$r = mysql_query($q);
	while ($row = mysql_fetch_array($r)) {
		$data[] = $row;
	}

	$redata['data'] = $data;
	$redata['res'] = 'ok';

	$result = json_encode($redata);
	header('Content-Type:application/json; charset=utf-8');
	echo $result;
	exit;
}
if ($han == 'getmodel') {

	$company_idx = $_REQUEST['company_idx'];
	$grade_idx = $_REQUEST['grade_idx'];
	$fuel_idx = $_REQUEST['fuel_idx'];

	$q = "SELECT vs.*,
			(
				SELECT GROUP_CONCAT(c.idx SEPARATOR '|')
				FROM vehicle_model_fuel_codes vsfc2
				LEFT JOIN codes c ON vsfc2.code_idx = c.idx AND c.dt_delete IS NULL
				WHERE vsfc2.vehicle_model_idx = vs.idx
			) fuel
 FROM vehicle_models vs";

	if ($fuel_idx) {
		$q .= " LEFT JOIN vehicle_model_fuel_codes vsfc ON vs.idx = vsfc.vehicle_model_idx ";
		$q .= " WHERE vs.dt_delete IS NULL ";
		$q .= " AND vsfc.code_idx = '$fuel_idx' ";
	}else {
		$q .= " WHERE vs.dt_delete IS NULL ";
	}

	if ($company_idx) {
		$q = $q . " AND company_idx='$company_idx'";
	}
	if ($grade_idx) {
		$q = $q . " AND grade_idx='$grade_idx'";
	}

	$q = $q . "  ORDER By vs.name ASC";
	$r = mysql_query($q);
	while ($row = mysql_fetch_array($r)) {
		$data[] = $row;
	}

	$redata['data'] = $data;
	$redata['res'] = 'ok';

	$result = json_encode($redata);
	header('Content-Type:application/json; charset=utf-8');
	echo $result;
	exit;
}
if ($han == 'getmodel_detal') {

	$idx = $_REQUEST['idx'];
	$ar_data = sel_query_all("vehicle_models", " WHERE idx='$idx'");

	$redata['data'] = $ar_data;
	$redata['res'] = 'ok';

	$result = json_encode($redata);
	header('Content-Type:application/json; charset=utf-8');
	echo $result;
	exit;
}
if ($han == 'getprice') {
	$idx = $_REQUEST['idx'];
	$data = sel_query_all('vehicle_price', 'WHERE idx=' . $idx);
	$result = json_encode($data);
	header('Content-Type:application/json; charset=utf-8');
	echo $result;
}
if ($han == 'getpricelongterm') {
	$idx = $_REQUEST['idx'];
	$data = sel_query_all('vehicle_price_longterm', 'WHERE idx=' . $idx);
	$result = json_encode($data);
	header('Content-Type:application/json; charset=utf-8');
	echo $result;
}
if ($han == 'loadcar') {
	$carnum = $_REQUEST['carnum'];
	$data = array();
	$data['vehicle'] = sel_query_all("vehicles", "WHERE carnum='$carnum'");
	$data['model'] = sel_query_all("vehicle_models", "WHERE idx={$data['vehicle']['model_idx']}");
	$data['price'] = sel_query_all("vehicle_price", "WHERE idx={$data['vehicle']['price_idx']}");
	$data['price_longterm'] = sel_query_all("vehicle_price_longterm", "WHERE idx={$data['vehicle']['price_longterm_idx']}");


	$data['res'] = 'ok';
	$result = json_encode($data);
	header('Content-Type:application/json; charset=utf-8');
	echo $result;
}
if ($han == 'getcarbycarnum') {
	$carnum = $_REQUEST['carnum'];
	$sdate = $_REQUEST['sdate'];
	$edate = $_REQUEST['edate'];
	$rentshop = $_REQUEST['rentshop'];
	$where = '';

	if ($member) {
		$where .= " AND r.idx=$rentshop";
	}

	if(isset($sdate) && isset($edate)) {
		$where .= "
		AND v.idx NOT IN (
			SELECT
				distinct(mt.vehicle_idx)
			FROM payments m
				LEFT JOIN reservation mt ON m.reservation_idx = mt.idx
				LEFT JOIN vehicles v1 ON mt.vehicle_idx = v1.idx
				LEFT JOIN rentshop r1 ON v1.rentshop_idx = r1.idx
			WHERE
				m.dan IN ('1','2','4')
				AND mt.edate >= DATE_ADD('$sdate:00', INTERVAL -r1.wtime HOUR)
				AND mt.sdate <= DATE_ADD('$edate:00', INTERVAL r1.wtime HOUR)
			)
		";
	}


	$query = "
		SELECT
			v.idx,
			v.carnum,
			CONCAT(r.name, '(', r.affiliate, ')') rentshop
		FROM vehicles v
			LEFT JOIN rentshop r ON v.rentshop_idx = r.idx
		WHERE
			v.dt_delete IS NULL
			AND v.carnum LIKE '%$carnum%'
			$where
		ORDER BY
			v.carnum
		LIMIT 0, 10
	";

	$data = [];
	$r = mysql_query($query);
	while ($row = mysql_fetch_assoc($r)) {
		$data[] = $row;
	}

	header('Content-Type:application/json; charset=utf-8');
	echo json_encode($data);
}

if ($han == 'getpricetitle') {
	$rentshopIdx = $_REQUEST['rentshop_idx'];
	$priceType = $_REQUEST['price_type'];

	$priceTitleQuery = "
		SELECT 
			idx, 
			title 
		FROM 
			vehicle_{$priceType} 
		WHERE 
			rentshop_idx = {$rentshopIdx}
			AND dt_delete IS NULL";

	if ($priceType != 'price_extra') {
		$priceTitleQuery .= " AND managed='Y'";
	}

	$data = [];
	$redata = [];
	$priceTitleResult = mysql_query($priceTitleQuery);
	while ($row = mysql_fetch_assoc($priceTitleResult)) {
		$data[] = $row;
	}
	$redata['data'] = $data;
	$redata['res'] = 'ok';

	$result = json_encode($redata);
	header('Content-Type:application/json; charset=utf-8');
	echo $result;
	exit;
}

if($han == 'changeonsale') {
	$idx = $_REQUEST['idx'];
	$onsale = $_REQUEST['onsale'];

	$query = "UPDATE vehicles set onsale = '$onsale' WHERE idx = $idx";
	mysql_query($query);

	$data = [];
	$data['res'] = mysql_affected_rows();

	$result = json_encode($data);
	header('Content-Type:application/json; charset=utf-8');
	echo $result;
	exit;
}
