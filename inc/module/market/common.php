<?
//주문서에서 주문정보 수정하기 위해 데이터를 html 로 전달
if(!$_SESSION['member_idx'])
{	
	$redata[res] = 'error';
	$redata[resmsg] = '로그인후 재시도';
	
	$result = json_encode ($redata);
	header ( 'Content-Type:application/json; charset=utf-8' );
	echo $result;
	exit;
}
if($han=='get_reservation_memo')	{
	
	$mods = $_REQUEST['mods'];
	$idx = $_REQUEST['idx'];
	if(!$idx)	{
		$redata['res'] = 'error';
		$redata['resmsg'] = '잘못된 데이터 전송';
	
		$result = json_encode ($redata);
		header ( 'Content-Type:application/json; charset=utf-8' );
		echo $result;
		exit;
	}

	$qp = "SELECT
			c.*,
			m.name member_name
		FROM 
			comments c 
			LEFT JOIN member m ON c.member_idx = m.idx
		WHERE
			c.type = 'payment'
			AND c.type_idx='$idx'";

	if(!$_SESSION['admin_grade']) {
		$qp .= " AND c.public = 'Y'";
	}
	$qp = $qp . " ORDER BY dt_update DESC";
	$rp = mysql_query($qp);
	$cou = 1;
	while($rowp = mysql_fetch_array($rp))	{
		$rowp['nums'] = $cou;
		$rowp['comment'] = nl2br($rowp['comment']);
		$redata['data'][] = $rowp;
		$cou++;
	}

	$redata['res'] = 'ok';
	$result = json_encode ($redata);
	header ( 'Content-Type:application/json; charset=utf-8' );
	echo $result;
}	
if($han=='set_writememo')	{
	$idx = $_REQUEST['idx'];
	if(!$idx)
	{	
		$redata['res'] = 'error';
		$redata['resmsg'] = '잘못된 데이터 전송';
	
		$result = json_encode ($redata);
		header ( 'Content-Type:application/json; charset=utf-8' );
		echo $result;
		exit;
	}

	$value['type'] = 'payment';
	$value['type_idx'] = $idx;
	if(isset($_REQUEST['public']))
		$value['public'] = $_REQUEST['public'];
	$value['comment'] = $_REQUEST['comment'];
	$value['member_idx'] = $g_memidx;
	$value['dt_update'] = $value['dt_create'] = date('Y-m-d H:i:s');
	insert("comments",$value);
	unset($value);

	
	$redata[res] = 'ok';
	$result = json_encode ($redata);
	header ( 'Content-Type:application/json; charset=utf-8' );
	echo $result;
	exit;
}
if($han=='change_car') {
	$tmpIdx = $_REQUEST['reservation_idx'];
	$carIdx = $_REQUEST['vehicle_idx'];
	$res = true;

	$result = [];

	$query = "
		SELECT
			m.idx payment_idx,
			mt.vehicle_idx,
			mt.sdate,
			mt.edate,
			m.ptype,
			mt.addr,
			vs.name modelname,
			v.carnum,
			CONCAT(r.name, '(', r.affiliate, ')') rentshop,
			m.dt_create market_makedate,
			mem.name member_name,
			mem.cp member_cp,
			r.dcp2,
			r.contact1,
			r.contact2,
			r.contact3,
			m.payment_account,
			m.discount,
			mt.insu_exem,
			m.dan,
			mt.preaccount
		FROM
			reservation mt
			LEFT JOIN payments m ON mt.idx = m.reservation_idx
			LEFT JOIN member mem ON m.member_idx = mem.idx
			LEFT JOIN vehicles v ON mt.vehicle_idx = v.idx
			LEFT JOIN rentshop r ON v.rentshop_idx = r.idx
			LEFT JOIN vehicle_models vs ON v.model_idx = vs.idx
		WHERE
			mt.idx = $tmpIdx
	";
	$r = mysql_query($query);

	if(mysql_num_rows($r) < 1)
		$res = false;

	$paymentTmp = null;
	$dan = 1;
	if($res) {
		$paymentTmp = mysql_fetch_assoc($r);
		$memo = "차량변경\n{$paymentTmp['modelname']}({$paymentTmp['carnum']}) -> ";
		if(date("Y-m-d H:i:s") >= $paymentTmp['sdate']) {
			$dan = 2;
		}

		$query = "SELECT
				v.*
			FROM payments m
				LEFT JOIN reservation mt ON m.reservation_idx = mt.idx
				LEFT JOIN vehicles v ON mt.vehicle_idx = v.idx
			WHERE
				m.dan < 3
				AND mt.sdate <= NOW()
				AND mt.edate >= NOW()
				AND v.idx = {$paymentTmp['vehicle_idx']}
				AND mt.idx <> $tmpIdx";
		$r = mysql_query($query);

		if(mysql_num_rows($r) < 1) {
			$query = "UPDATE vehicles SET status='S' WHERE idx = {$paymentTmp['vehicle_idx']}";
			mysql_query($query);
		}

		$status = $dan == 1 ? 'S' : 'O';
		$query = "UPDATE vehicles SET status='$status' WHERE idx = $carIdx";
		mysql_query($query);

		$query = "UPDATE reservation SET vehicle_idx=$carIdx WHERE idx=$tmpIdx";
		mysql_query($query);

		$query = "SELECT
				v.carnum,
				v.outdate,
				vs.name modelname,
				r.dcp2,
				r.contact1,
				r.contact2,
				r.contact3,
				r.per1,
				c.sname fuel_name,
				CONCAT(r.name, '(', r.affiliate, ')') rentshop,
				CONCAT('대인: ', v.insu_per, ' / 대물: ', v.insu_goods, ' / 자손: ', v.insu_self) insu,
				(SELECT
					 GROUP_CONCAT(CONCAT(op_name, ': ', op_data) SEPARATOR ', ')
				 FROM
					 vehicle_opt
				 WHERE
					 vehicle_idx = v.idx
					 AND op_data <> '') opt
			FROM vehicles v
				LEFT JOIN vehicle_models vs ON v.model_idx = vs.idx
				LEFT JOIN codes c ON v.fuel_idx = c.idx
				LEFT JOIN rentshop r ON v.rentshop_idx = r.idx
			WHERE
				v.idx = $carIdx";
		$r = mysql_query($query);
		$car = mysql_fetch_assoc($r);

		$memo .= "{$car['modelname']}({$car['carnum']})";
		$query = "INSERT INTO comments(type, type_idx, comment, member_idx, dt_create, dt_update) VALUES 
			('payment', {$paymentTmp['payment_idx']}, '$memo', $g_memidx, NOW(), NOW())";
		mysql_query($query);

		$query = "
			UPDATE payment_accounts
			SET settlement_per={$car['per1']}
			WHERE payment_idx={$paymentTmp['payment_idx']}";
		mysql_query($query);

		$result = array_merge($result, $car);

		//예약확정 상태일때만 문자발송
		if($paymentTmp['dan'] < 2) {
			$kakao = array(
				'order' => $paymentTmp['payment_idx'],
				'wdate' => $paymentTmp['market_makedate'],
				'cancel_date' => date('Y-m-d H:i:s'),
				'sdate' => $paymentTmp['sdate'],
				'edate' => $paymentTmp['edate'],
				'model' => $paymentTmp['modelname'],
				'carnum' => $paymentTmp['carnum'],
				'retype' => $paymentTmp['ptype'] == '1' ? '배달대여': '지점방문',
				'retype_title' => $paymentTmp['ptype'] == '1' ? '배달위치:': '',
				'addr' => $paymentTmp['ptype'] == '1' ? $paymentTmp['addr']: '',
				'total_ac' => number_format($paymentTmp['payment_account'] + $paymentTmp['discount']).'원',
				'name' => $paymentTmp['member_name'],
				'member_phone' => phone_number_format($paymentTmp['member_cp']),
				'insurance' => $paymentTmp['insu_exem'] != NULL ? '고객부담금 '.number_format($paymentTmp['insu_exem']).'원/건' : '자차미가입',
				'preaccount' => number_format($paymentTmp['preaccount']).'원'
			);

			//예약 취소 문자
			$contact1 = $config['production'] ? $paymentTmp['contact1'] : '01034460336';
			$contact2 = $config['production'] ? $paymentTmp['contact2'] : '';
			$contact3 = $config['production'] ? $paymentTmp['contact3'] : '';
			if($contact1)
				sendKakao($config['kakaoAuth'], $contact1, 'CAN0202', $kakao);
			if($contact2)
				sendKakao($config['kakaoAuth'], $contact2, 'CAN0202', $kakao);
			if($contact3)
				sendKakao($config['kakaoAuth'], $contact3, 'CAN0202', $kakao);

			//예약 문자
			$kakao['model'] = $car['modelname'];
			$kakao['carnum'] = $car['carnum'];

			$contact1 = $config['production'] ? $car['contact1'] : '01034460336';
			$contact2 = $config['production'] ? $car['contact2'] : '';
			$contact3 = $config['production'] ? $car['contact3'] : '';
			if($contact1)
				sendKakao($config['kakaoAuth'], $contact1, 'PAY0204', $kakao);
			if($contact2)
				sendKakao($config['kakaoAuth'], $contact2, 'PAY0204', $kakao);
			if($contact3)
				sendKakao($config['kakaoAuth'], $contact3, 'PAY0204', $kakao);
		}
	}

	$result['res'] = $res;
	$result['tmpIdx'] = $tmpIdx;
	$result['carIdx'] = $carIdx;


	header ( 'Content-Type:application/json; charset=utf-8' );
	echo json_encode ($result);
}
?>