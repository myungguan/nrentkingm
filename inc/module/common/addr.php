<?
if ($han == 'get_addr') {
	$code = $_REQUEST['code'];
	$data = get_sido($code);
	$redata['res'] = 'ok';
	$redata['data'] = $data;

	$result = json_encode($redata);
	header('Content-Type:application/json; charset=utf-8');
	echo $result;
	exit;
}
if ($han == 'get_partner') {

	$addr = $_REQUEST['addr'];

	$ar_addr = explode(" ", $addr);

	$q = "SELECT * FROM area WHERE ac_name='{$ar_addr[0]}' OR  ac_alias='{$ar_addr[0]}'";
	$r = mysql_query($q);
	$row = mysql_fetch_array($r);

	$ar_addr[0] = $row['ac_alias'];

	$q = "SELECT *, ASTEXT(latlng) latlng FROM rentshop WHERE isok='Y' AND  addr1 LIKE '" . $ar_addr[0] . "%'";
	if ($ar_addr[1]) {
		$q = $q . " AND addr1 like '%" . $ar_addr[1] . "%'";
	}

	$r = mysql_query($q);
	while ($row = mysql_fetch_array($r)) {
		$row['address'] = $row['addr1'];
		if ($row['addr2'] != '') {
			$row['address'] = $row['address'] . " " . $row['addr2'];
		}

		$row[10] = '';
		$data[] = $row;
	}

	$redata['res'] = 'ok';
	$redata['data'] = $data;

	$result = json_encode($redata);
	header('Content-Type:application/json; charset=utf-8');
	echo $result;
	exit;
}
?>