<?
include $_SERVER['DOCUMENT_ROOT']."/old/inc/base.php";
include $config['incPath']."/connect.php";
include $config['incPath']."/session.php";
include $config['incPath']."/config.php";

$safeIp = [
	"127.0.0.1",
    "58.230.185.166", // smkang
	"218.55.101.75", // rentking office
    "182.162.90.80", // prd
    "117.52.87.35" // dev
];

if(in_array($config['remoteAddr'], $safeIp)) {
	$output = '';

	//예약 변경 (5분마다 실행)
	if(date('i') % 5 == 0) {
		//linkprice
		$output .= "Linkprice cancel: ";
		$query = "SELECT
			mem.name mem_name, m.member_idx, mt.vehicle_idx, m.reservation_idx
		FROM payments m
			LEFT JOIN reservation mt ON m.reservation_idx = mt.idx
			LEFT JOIN member mem ON m.member_idx = mem.idx
		WHERE
			m.dan='1' AND m.pdan='' AND mt.sdate<='" . date("Y-m-d H:i:s") . "'";
		$r = mysql_query($query);
		$count = 0;
		while ($row = mysql_fetch_array($r)) {
			$count++;
			update("linkprice", array("status" => 1), " WHERE type='cps' AND order_code=" . $row["reservation_idx"] . " AND product_code=" . $row["vehicle_idx"]);
		}
		$output .= "$count\n";

        //반납일시가 경과되고 대여중으로 바뀌어야되는 예약의 차를 사용중인 대여건은 반납완료로 변경
        $output .= "end rent & same car  status to 3: ";
        $query = "
        SELECT p.idx, r.vehicle_idx FROM payments p
			    LEFT JOIN reservation r ON p.reservation_idx = r.idx
			    WHERE
			    p.dan = '2'
			    AND r.edate <= NOW()
			    AND r.vehicle_idx in (
			      SELECT r.vehicle_idx
		          FROM payments p
			      LEFT JOIN reservation r ON p.reservation_idx = r.idx
			      LEFT JOIN vehicles v ON r.vehicle_idx = v.idx
		          WHERE p.dan = '1'
			      AND r.sdate <= NOW() 
			      AND r.edate >= NOW()
			      AND v.status = 'O'
			    )
        ";

        $r = mysql_query($query);
        $updateList = '';
        while($row = mysql_fetch_assoc($r)) {
            if(strlen($updateList) > 0)
                $updateList .= ', ';
            else
                $updateList = ' (';
            $updateList .= "[".$row['idx'].", ".$row['vehicle_idx']."]";
        }
        if($updateList != ''){
            $updateList .= ')';
        }
        
        $query = "
            UPDATE payments
			SET dan = '3'
			WHERE idx IN (
			  SELECT * FROM (
			    SELECT p.idx FROM payments p
			    LEFT JOIN reservation r ON p.reservation_idx = r.idx
			    WHERE
			    p.dan = '2'
			    AND r.edate <= NOW()
			    AND r.vehicle_idx in (
			      SELECT r.vehicle_idx
		          FROM payments p
			      LEFT JOIN reservation r ON p.reservation_idx = r.idx
			      LEFT JOIN vehicles v ON r.vehicle_idx = v.idx
		          WHERE p.dan = '1'
			      AND r.sdate <= NOW() 
			      AND r.edate >= NOW()
			      AND v.status = 'O'
			    )
			  )
			AS A)";
        mysql_query($query);
        $output .= mysql_affected_rows().$updateList."\n";

		//예약 대여중으로 변경
		$output .= "Change reservation status to 2: ";
		$query = "UPDATE payments m
			LEFT JOIN reservation mt ON m.reservation_idx = mt.idx
		SET m.dan='2'
		WHERE
			m.dan='1' AND m.pdan='' AND mt.sdate<='" . date("Y-m-d H:i:s") . "'";
		mysql_query($query);
		$output .= mysql_affected_rows()."\n";

		//차량 대여중으로 변경
		$output .= "Change vehicle status to O: ";
		$query = "SELECT
			mt.vehicle_idx
		FROM payments m
			LEFT JOIN reservation mt ON m.reservation_idx = mt.idx
			LEFT JOIN vehicles v ON mt.vehicle_idx = v.idx
		WHERE
			m.dan = '2'
			AND mt.sdate <= NOW()
			AND mt.edate >= NOW()
			AND v.status <> 'O'";
		$r = mysql_query($query);

		$updateList = '';
		while($row = mysql_fetch_assoc($r)) {
			if(strlen($updateList) > 0) $updateList .= ',';
            $updateList .= $row['vehicle_idx'];
		}

		if(strlen($list) > 0) {
			$query = "UPDATE vehicles
			SET
				status = 'O'
			WHERE
				idx IN ($updateList)
		";
			mysql_query($query);
			$output .= mysql_affected_rows()."\n";
		} else {
			$output .= "0\n";
		}

        //직접예약 - 예약 대여중으로 변경
        $output .= "Change direct reservation status to 2: ";
        $query = "UPDATE reservation_direct
		SET dan='2'
		WHERE
			dan='1' AND sdate<='" . date("Y-m-d H:i:s") . "'";
        mysql_query($query);
        $output .= mysql_affected_rows()."\n";

        //직접예약 - 차량 대여중으로 변경
        $output .= "Change direct vehicle status to O: ";
        $query = "SELECT
			rd.vehicle_idx
		FROM reservation_direct rd
			LEFT JOIN vehicles v ON rd.vehicle_idx = v.idx
		WHERE
			rd.dan = '2'
			AND vehicle_idx != 0
			AND v.status <> 'O'";
        $r = mysql_query($query);

        $updateList = '';
        while($row = mysql_fetch_assoc($r)) {
            if(strlen($updateList) > 0) $updateList .= ',';
            $updateList .= $row['vehicle_idx'];
        }

        if(strlen($list) > 0) {
            $query = "UPDATE vehicles
			SET
				status = 'O'
			WHERE
				idx IN ($updateList)
		";
            mysql_query($query);
            $output .= mysql_affected_rows()."\n";
        } else {
            $output .= "0\n";
        }


		//연장차량 반납으로 변경
		$output .= "Change extended reservation status to 3: ";
		$query = "UPDATE payments m
			LEFT JOIN reservation mt ON m.reservation_idx = mt.idx
		SET
			m.dan='3',
			m.dt_return = NOW()
		WHERE
			m.dan = '2'
			AND m.extend_payment_idx IS NOT NULL
			AND mt.edate <= NOW()";
		mysql_query($query);
		$output .= mysql_affected_rows()."\n";
	}

	//반납요청 문자 발송 (매일 10:00에 실행)
	if(date('H:i') == '10:00') {
		 $result = mysql_query("SELECT
			DISTINCT rc.contact
			FROM
				payments p
				LEFT JOIN reservation r ON p.reservation_idx = r.idx
				LEFT JOIN vehicles v ON r.vehicle_idx = v.idx
				LEFT JOIN rentshop rs ON v.rentshop_idx = rs.idx
				LEFT JOIN rentshop_contacts rc ON rs.idx = rc.rentshop_idx
			WHERE
				r.edate <= NOW()
				AND p.dan < 3
				AND rs.isok <> 'N' ");

		 $contacts = [];
		 $count = 0;
		 while($row = mysql_fetch_assoc($result)) {
			 $contacts[] = $row['contact'];
		 }

		 if(!$config['production'] && $count > 0) {
		 	$contacts = ['01034460336'];
		 }

		 foreach($contacts as $contact) {
			 sendKakao($config['kakaoAuth'], $contact, 'PAY0402');
		 }

		 $output .= "Send Return Sms: $count\n";
	}

	if(strlen($output) < 1)
		$output = "Nothing to do\n";

	echo date('Y-m-d H:i:s')."\n";
	echo $output."\n";
}
