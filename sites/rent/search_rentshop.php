<?
/**
 * 차량 검색 페이지
 * www.rentking.co.kr/rent/search.php
 */

include $_SERVER['DOCUMENT_ROOT']."/old/inc/base.php";
include $config['incPath']."/connect.php";
include $config['incPath']."/session.php";
include $config['incPath']."/config_site.php";

$sdate = $_REQUEST['sdate'];
$edate = $_REQUEST['edate'];
$pickupAddr = $_REQUEST['pickupAddr'];
$returnAddr = $_REQUEST['returnAddr'];
$searchLatLng = $_REQUEST['searchLatLng'];
$distance = $_REQUEST['distance'];
$retype = $_REQUEST['retype'];

$stime = getTime($sdate);
$etime = getTime($edate);

$sdateShort = substr($sdate,0,10);
$edateShort = substr($edate,0,10);

setcookie('searchLatLng', $searchLatLng, time() + (60 * 30), '/', 'rentking.co.kr');

$day1 = date("N",$stime);
$day2 = date("N",$etime);
$ststr = date("H:i",$stime);
$etstr = date("H:i",$etime);

$point = explode(',', $searchLatLng);
$distanceList = [3000, 5000, 10000, 20000, 40000];

$hoursInfo = calcHours($sdate, $edate);

$charge_where = '';
if($retype == 1) {
	$charge_where = " AND (vp.price_insu0_check = 'Y' OR vp.price_insu1_check = 'Y' OR vp.price_insu2_check = 'Y' OR vp.price_insu3_check = 'Y') AND vp.price_hour > 0 AND vp.price > 0 AND vp.price_holiday > 0 AND vp.price_discount2 > 0 AND vp.price_discount3 > 0 AND vp.price_discount4 > 0";
} else {
	$charge_where = 'AND vpl.price_longterm1 > 0 AND vpl.price_longterm2 > 0 AND vpl.price_longterm3 > 0 AND vpl.price_longterm_insu_exem > 0';
}

$result = array();
$field = "r.idx, 
	r.name,
	r.affiliate,
	r.addr1, 
	r.addr2, 
	r.s1time1, 
	r.s1time2, 
	r.s2time1, 
	r.s2time2, 
	totalcar, 
	(SELECT
			COUNT(1) 
		FROM vehicles v
#			LEFT JOIN vehicle_models vs ON v.model_idx=vs.idx
#			LEFT JOIN vehicle_price vp ON v.price_idx = vp.idx
#			LEFT JOIN vehicle_price_longterm vpl ON v.price_longterm_idx = vpl.idx
			LEFT JOIN vehicle_off vo ON v.idx = vo.vehicle_idx AND ('$sdateShort' BETWEEN vo.offsdate AND vo.offedate OR '$edateShort' BETWEEN vo.offsdate AND vo.offedate OR ('$sdateShort' < vo.offsdate AND '$edateShort' > vo.offedate) )
		WHERE 
			v.rentshop_idx=r.idx 
			AND v.dt_delete IS NULL
#			$charge_where
			AND v.onsale = 'Y'
#			AND v.idx NOT IN (
#				SELECT
#					distinct(mt.vehicle_idx)
#				FROM
#					payment m
#					LEFT JOIN reservation mt ON m.reservation_idx = mt.idx 
#					LEFT JOIN vehicles v1 ON mt.vehicle_idx = v1.idx
#					LEFT JOIN rentshop r1 ON v1.rentshop_idx = r1.idx
#				WHERE
#					m.dan IN ('1','2','4')
#					AND mt.edate >= '$sdate:00' + INTERVAL -r1.wtime HOUR
#					AND mt.sdate <= '$edate:00' + INTERVAL r1.wtime HOUR)
			AND vo.idx IS NULL
		) availcar, 
	X(latlng) lat, 
	Y(latlng) lng, 
	( 6371000 * acos( cos( radians({$point[0]}) )
		* cos( radians( X(r.latlng) ) )
		* cos( radians( Y(r.latlng) ) - radians({$point[1]}) )
		+ sin( radians({$point[0]}) )
		* sin( radians( X(r.latlng) ) ) ) ) distance";

$query = "SELECT [[FIELD]]
	FROM
		rentshop r
		LEFT JOIN rentshop_off ro ON r.idx = ro.rentshop_idx 
			AND ('$sdateShort' BETWEEN ro.sdate AND ro.edate OR '$edateShort' BETWEEN ro.sdate AND ro.edate)
		LEFT JOIN rentshop_reservation_limit rrl ON r.idx = rrl.rentshop_idx
			AND rrl.hour > {$hoursInfo['rent_hour']}
			AND ('$sdateShort' BETWEEN rrl.sdate AND rrl.edate OR '$edateShort' BETWEEN rrl.sdate AND rrl.edate OR ('$sdateShort' < rrl.sdate AND '$edateShort' > rrl.edate) )
	WHERE 
		r.isok = 'Y'
		AND ro.idx IS NULL
		AND rrl.idx IS NULL
		AND r.wtime1 <= TIMESTAMPDIFF(HOUR, NOW(), '$sdate:00')	-- 예약 대기시간
		";

//영업시간(출차)
if($day1<=5)	{
	$query .= " AND r.s1time1 <= '$ststr' AND r.s1time2 >= '$ststr' ";
} else {
	$query .= " AND r.s2time1 <= '$ststr' AND r.s2time2 >= '$ststr' ";
}

//영업시간(반차)
if($day2<=5)	{
	$query .= " AND r.s1time1 <= '$etstr' AND r.s1time2 >= '$etstr' ";
} else {
	$query .= " AND r.s2time1 <= '$etstr' AND r.s2time2 >= '$etstr' ";
}

$pickupAddr = getDbAddress($pickupAddr);
$pickupQuery = " AND INSTR ('$pickupAddr', CASE WHEN rl.loca2 = 'A' THEN a1.ac_alias ELSE REPLACE(CONCAT(CONCAT(a1.ac_alias, ' '), a2.ac_name), '  ', ' ') END) = 1";

$returnQuery = "";
if($returnAddr)	{
	$returnAddr = getDbAddress($returnAddr);
	$returnQuery = " AND INSTR ('$returnAddr', CASE WHEN rl.loca2 = 'A' THEN a1.ac_alias ELSE REPLACE(CONCAT(CONCAT(a1.ac_alias, ' '), a2.ac_name), '  ', ' ') END) = 1";
}

$deliveryTimeQuery = "";
//배반차가능시간(출차)
if($day1<=5)	{
	$deliveryTimeQuery .= " AND r.d1time1 <= '$ststr' AND r.d1time2 >= '$ststr' ";
} else {
	$deliveryTimeQuery .= " AND r.d2time1 <= '$ststr' AND r.d2time2 >= '$ststr' ";
}
//배반차가능시간(반차)
if($day2<=5)	{
	$deliveryTimeQuery .= " AND r.d1time1 <= '$etstr' AND r.d1time2 >= '$etstr' ";
} else {
	$deliveryTimeQuery .= " AND r.d2time1 <= '$etstr' AND r.d2time2 >= '$etstr' ";
}
$deliveryQuery = " t.idx IN (
		SELECT distinct(rl.rentshop_idx) 
		FROM
			rentshop_loca rl
			LEFT JOIN rentshop r ON rl.rentshop_idx = r.idx
			LEFT JOIN area a1 ON a1.ac_code = rl.loca1
			LEFT JOIN area a2 ON a2.ac_code = rl.loca2
		WHERE
			r.wtime2 <= TIMESTAMPDIFF(HOUR, NOW(), '$sdate:00')
			AND rl.stype='$retype'
			$deliveryTimeQuery
			$pickupQuery
			$returnQuery
		)";


$result['distanceList'] = $distanceList;
$searchQuery = str_replace('[[FIELD]]', $field, $query);
$query = "
	SELECT
		t.*,
		CASE
			WHEN t.distance < {$distanceList[$distance]} THEN 1
			ELSE 0
		END indistance 
	FROM ($searchQuery) t
	WHERE
		t.availcar > 0
		AND (t.distance < {$distanceList[$distance]} OR $deliveryQuery)
";

if($distance < 0) {
//    $distance = 0;
    $distance = 4;  //18.03.15 #1638 검색 디폴트반경 40km로 변경
    if(iconv_substr($pickupAddr, 0, 2, "utf-8") == "서울"){    //18.03.23 #1650 서울에서 검색할 경우 검색 디폴트반경 5km로 변경
        $distance = 1;

    }
	$count = 0;
	do {
		$rentshopDetail = array();
		$query = "
			SELECT
				t.*,
				CASE
					WHEN t.distance < {$distanceList[$distance]} THEN 1
					ELSE 0
				END indistance
			FROM ($searchQuery) t
			WHERE
				t.availcar > 0
				AND (t.distance < {$distanceList[$distance]} OR $deliveryQuery)
		";
		$rentshop = mysql_query($query);

		if($rentshop) {
			while($row = mysql_fetch_assoc($rentshop)) {
				$rentshopDetail[] = $row;
				if($row['indistance'] == 1)
					$count++;
			}
		}

		$distance++;

	} while($distance < count($distanceList) && $count < 2);
	$result['distance'] = $distanceList[$distance-1];
} else {
	$rentshopDetail = array();
	$rentshop = mysql_query($query);
	$result['distance'] = $distanceList[$distance];

	if($rentshop) {
		while($row = mysql_fetch_assoc($rentshop)) {
			$rentshopDetail[] = $row;
			if($row['deltype'] == 1)
				$count++;
		}
	}
}

$rentshopList = '';
$total = count($rentshopDetail);
for($i = 0; $i < $total; $i++) {
	if($i > 0)
		$rentshopList .= ',';
	$rentshopList .= $rentshopDetail[$i]['idx'];
}

$result['rentshopDetail'] = $rentshopDetail;
$result['rentshopList'] = $rentshopList;
echo json_encode($result);