<?
/**
 * 멤버사 > 지점정보 > 대표지점정보
 * admin.rentking.co.kr/admin/rent.php?code=mod
 * 멤버사 대표지점정보 페이지
 */

//최소예약시간, 휴무일 지난날짜 삭제
$query = "DELETE FROM rentshop_off WHERE edate < CURDATE();";
mysql_query($query);
$query = "DELETE FROM rentshop_reservation_limit WHERE edate < CURDATE()";
mysql_query($query);

if ($mode == 'w') {
//	if (trim($_REQUEST['passwd']) != '') {
//		$value['passwd'] = $_REQUEST['passwd'];
//	}

	$value['affiliate'] = mysql_escape_string($_REQUEST['affiliate']);
	$value['name'] = mysql_escape_string($_REQUEST['name']);
	$value['zipcode'] = $_REQUEST['zipcode'];
	$value['addr1'] = $_REQUEST['addr1'];
	$value['addr2'] = mysql_escape_string($_REQUEST['addr2']);
    $value['businessnum'] = mysql_escape_string($_REQUEST['businessnum']);
    $value['makedate'] = mysql_escape_string($_REQUEST['makedate']);
    $value['phone'] = mysql_escape_string($_REQUEST['phone']);
	$value['dname1'] = mysql_escape_string($_REQUEST['dname1']);
	$value['dname2'] = mysql_escape_string($_REQUEST['dname2']);
	$value['dname3'] = mysql_escape_string($_REQUEST['dname3']);
	$value['dphone1'] = mysql_escape_string($_REQUEST['dphone1']);
	$value['dphone2'] = mysql_escape_string($_REQUEST['dphone2']);
	$value['dphone3'] = mysql_escape_string($_REQUEST['dphone3']);
	$value['dcp1'] = mysql_escape_string($_REQUEST['dcp1']);
	$value['dcp2'] = mysql_escape_string($_REQUEST['dcp2']);
	$value['dcp3'] = mysql_escape_string($_REQUEST['dcp3']);
	$value['demail1'] = mysql_escape_string($_REQUEST['demail1']);
	$value['demail2'] = mysql_escape_string($_REQUEST['demail2']);
	$value['demail3'] = mysql_escape_string($_REQUEST['demail3']);
	$value['dbirth1'] = $_REQUEST['dbirth1'];
	$value['dbirth2'] = $_REQUEST['dbirth2'];
	$value['dbirth3'] = $_REQUEST['dbirth3'];
	$value['contact1'] = mysql_escape_string($_REQUEST['contact1']);
	$value['contact2'] = mysql_escape_string($_REQUEST['contact2']);
	$value['contact3'] = mysql_escape_string($_REQUEST['contact3']);
	$value['wtime'] = $_REQUEST['wtime'];
	$value['s1time1'] = $_REQUEST['s1time1'];
	$value['s1time2'] = $_REQUEST['s1time2'];
	$value['s2time1'] = $_REQUEST['s2time1'];
	$value['s2time2'] = $_REQUEST['s2time2'];
	$value['d1time1'] = $_REQUEST['d1time1'];
	$value['d1time2'] = $_REQUEST['d1time2'];
	$value['d2time1'] = $_REQUEST['d2time1'];
	$value['d2time2'] = $_REQUEST['d2time2'];
	$value['wtime1'] = $_REQUEST['wtime1'];
	$value['wtime2'] = $_REQUEST['wtime2'];
	$value['bank'] = mysql_escape_string($_REQUEST['bank']);
	$value['bankaccount'] = mysql_escape_string($_REQUEST['bankaccount']);
	$value['bankname'] = mysql_escape_string($_REQUEST['bankname']);
	$value['bankholder'] = mysql_escape_string($_REQUEST['bankholder']);

	/* TODO: 오픈 플렛폼 사용시 적용
	if(!$value['bankname'] && !$value['bankaccount'] && !$value['bankholder']) {
		$value['bank_transfer_status'] = '';
	}else if(!($value['bankname'] && $value['bankaccount'] && $value['bankholder'])) {
		echo "<script type='text/javascript'>alert('예금주, 계좌번호, 은행계좌 인증정보를 모두 입력해 주세요.'); history.back(); </script>";
		exit;
	}else {
		//TODO: #1072 오픈플렛폼 정식 사용시 production 관련 조건문 해제
		if($config['production']) {
			$value['bank_transfer_status'] = 'N';
		}else{
			$bankTransferCheckAccount = transferCheckAccount($value['bank'], $value['bankaccount'], $value['bankname'], $value['bankholder']);
			if($bankTransferCheckAccount['result'] == 'SUCCESS') {
				$value['bank_transfer_status'] = 'Y';
			}else if($bankTransferCheckAccount['result'] == 'BANK_STATUS_NOT_AVAILABLE') {	//오픈플렛폼에서 지원하지 않는 은행 기입
				$value['bank_transfer_status'] = 'N';
			}else{	//오픈플렛폼에서 지원하는 은행을 기입했으나 계좌정보 확인에 성공하지 못한경우
				//			echo "<script type='text/javascript'>alert('입력하신 은행계좌 정보를 찾을 수 없습니다.\\n예금주, 계좌번호, 은행계좌 인증정보를 다시한번 확인 해 주세요.\\n\\n오류내용:\\n".$bankTransferCheckAccount['msg']."'); history.back(); </script>";
				echo "<script type='text/javascript'>alert('입력하신 은행계좌 정보를 찾을 수 없습니다.\\n예금주, 계좌번호, 은행계좌 인증정보를 다시한번 확인 해 주세요.'); history.back(); </script>";
				exit;
			}
		}
	}
	*/


	$latlng = "";
	$locationResult = true; // location check
	if (!isset($value['addr1'])) {
		$locationResult = false;
	}

	if ($locationResult) {
		$params = array(
			'output' => 'json',
			'apikey' => 'fefdc7502e819521ae797c44ed5f3e53',
			'q' => $value['addr1']
		);

		$url = "http://apis.daum.net/local/geo/addr2coord"; // 다음 위도경도 변환 api

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url . '?' . http_build_query($params, '', '&'));
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		$response = curl_exec($ch);
		$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		curl_close($ch);

		if ($status_code == 200) {
			$decode = json_decode($response, true);
			if (!empty($decode['channel']['item'])) {
				$latlng = "POINT({$decode['channel']['item'][0]['lat']},{$decode['channel']['item'][0]['lng']})";
			} else {
				$locationResult = false;
			}
		} else {
			$locationResult = false;
		}
	}

	if (!$locationResult) {
		echo "<script type='text/javascript'>alert('위치 정보를 찾을 수 없습니다.'); history.back(); </script>";
		exit;
	}

	$query = "
			UPDATE 
				rentshop 
			SET 
				affiliate=	'{$value['affiliate']}', 
				name=	'{$value['name']}', 
				zipcode=	'{$value['zipcode']}',
				addr1=		'{$value['addr1']}',
				addr2=		'{$value['addr2']}',
				latlng =	 {$latlng},
				phone=      '{$value['phone']}',
				businessnum='{$value['businessnum']}',
				makedate=   '{$value['makedate']}',
				dname1=		'{$value['dname1']}',
				dname2=		'{$value['dname2']}', 
				dname3=		'{$value['dname3']}', 
				dphone1=	'{$value['dphone1']}',
				dphone2=	'{$value['dphone2']}',
				dphone3=	'{$value['dphone3']}',
				dcp1=		'{$value['dcp1']}',
				dcp2=		'{$value['dcp2']}',
				dcp3=		'{$value['dcp3']}',
				demail1=	'{$value['demail1']}',
				demail2=	'{$value['demail2']}',
				demail3=	'{$value['demail3']}',
				dbirth1=	'{$value['dbirth1']}',
				dbirth2=	'{$value['dbirth2']}',
				dbirth3=	'{$value['dbirth3']}',
				contact1=	" . (strlen(trim($value['contact1'])) > 0 ? "'{$value['contact1']}'" : 'NULL') . ",
				contact2=	" . (strlen(trim($value['contact2'])) > 0 ? "'{$value['contact2']}'" : 'NULL') . ",
				contact3=	" . (strlen(trim($value['contact3'])) > 0 ? "'{$value['contact3']}'" : 'NULL') . ",
				wtime=		'{$value['wtime']}', 
				s1time1=	'{$value['s1time1']}', 
				s1time2=	'{$value['s1time2']}', 
				s2time1=	'{$value['s2time1']}', 
				s2time2=	'{$value['s2time2']}', 
				d1time1=	'{$value['d1time1']}', 
				d1time2=	'{$value['d1time2']}', 
				d2time1=	'{$value['d2time1']}', 
				d2time2=	'{$value['d2time2']}', 
				wtime1=		'{$value['wtime1']}', 
				wtime2=		'{$value['wtime2']}', 
				bank=		'{$value['bank']}', 
				bankaccount='{$value['bankaccount']}', 
				bankname=	'{$value['bankname']}', 
				bankholder=	'{$value['bankholder']}',
				bank_transfer_status= IF('{$value['bank_transfer_status']}' != '','{$value['bank_transfer_status']}',null)
			WHERE 
				idx={$_SESSION['rentshop_idx']}";

	mysql_query($query);
	unset($value);

//TODO: 유저, 멤버사 연결 관련 처리시 같이 처리
//	$value['name'] = mysql_escape_string($_REQUEST['name']);
//	update("member", $value, " WHERE idx='{$g_memidx}'");
//	unset($value);

	//최소 예약시간
	$query = "DELETE FROM rentshop_reservation_limit WHERE rentshop_idx = {$ar_rshop['idx']}";
	mysql_query($query);
	$reservationLimit = $_REQUEST['reservationLimit'];
	if(count($reservationLimit) > 0) {
		$query = "INSERT INTO rentshop_reservation_limit(rentshop_idx, sdate, edate, hour, dt_create) VALUES ";

		for($i = 0; $i < count($reservationLimit); $i++) {
			if($i > 0)
				$query .= ', ';
			preg_match('/^(.+)~(.+)\((.+)\)$/', $reservationLimit[$i], $rl, PREG_OFFSET_CAPTURE);

			$query .= "({$ar_rshop['idx']}, '{$rl[1][0]}', '{$rl[2][0]}', {$rl[3][0]}, NOW())";
		}

		mysql_query($query);
	}

	//휴무일
	$query = "DELETE FROM rentshop_off WHERE rentshop_idx = {$ar_rshop['idx']}";
	mysql_query($query);
	$rentshopOff = $_REQUEST['rentshopOff'];
	if(count($rentshopOff) > 0) {
		$query = "INSERT INTO rentshop_off(rentshop_idx, sdate, edate, dt_create) VALUES ";

		for($i = 0; $i < count($rentshopOff); $i++) {
			if($i > 0)
				$query .= ', ';
			$ro = explode('~', $rentshopOff[$i]);
			$query .= "({$ar_rshop['idx']}, '{$ro[0]}', '{$ro[1]}', NOW())";
		}

		mysql_query($query);
	}

	mysql_query("UPDATE rentshop_loca set tmp='Y' WHERE rentshop_idx='{$ar_rshop['idx']}'");

	$ar_loca1_1 = $_REQUEST['ar_loca1_1'];
	$ar_loca1_2 = $_REQUEST['ar_loca1_2'];

	for ($i = 0; $i < sizeof($ar_loca1_1); $i ++) {

		if ($ar_loca1_1[$i] == '' && $ar_loca1_2[$i] == '') {
			continue;
		}

		$q = "SELECT * FROM rentshop_loca WHERE rentshop_idx='{$ar_rshop['idx']}' AND stype='1' AND loca1='{$ar_loca1_1[$i]}' AND loca2='{$ar_loca1_2[$i]}'";
		$r = mysql_query($q);
		$isit = mysql_num_rows($r);

		if ($isit == 0) {
			$value['rentshop_idx'] = $ar_rshop['idx'];
			$value['stype'] = "1";
			$value['loca1'] = $ar_loca1_1[$i];
			$value['loca2'] = $ar_loca1_2[$i];
			insert("rentshop_loca", $value);
			unset($value);
		} else {
			$row = mysql_fetch_array($r);

			$value['tmp'] = "";
			update("rentshop_loca", $value, " WHERE idx='{$row['idx']}'");
			unset($value);
		}
	}

	$ar_loca2_1 = $_REQUEST['ar_loca2_1'];
	$ar_loca2_2 = $_REQUEST['ar_loca2_2'];

	for ($i = 0; $i < sizeof($ar_loca2_2); $i ++) {

		if ($ar_loca2_1[$i] == '' && $ar_loca2_2[$i] == '') {
			continue;
		}

		$q = "SELECT * FROM rentshop_loca WHERE rentshop_idx='{$ar_rshop['idx']}' AND stype='2' AND loca1='{$ar_loca2_1[$i]}' AND loca2='{$ar_loca2_2[$i]}'";
		$r = mysql_query($q);
		$isit = mysql_num_rows($r);

		if ($isit == 0) {
			$value['rentshop_idx'] = $ar_rshop['idx'];
			$value['stype'] = "2";
			$value['loca1'] = $ar_loca2_1[$i];
			$value['loca2'] = $ar_loca2_2[$i];
			insert("rentshop_loca", $value);
			unset($value);
		} else {
			$row = mysql_fetch_array($r);

			$value['tmp'] = "";
			update("rentshop_loca", $value, " WHERE idx='{$row['idx']}'");
			unset($value);
		}
	}

	mysql_query("DELETE FROM rentshop_loca WHERE tmp='Y' AND rentshop_idx='{$ar_rshop['idx']}'");

	echo "<script type='text/javascript'>alert('수정되었습니다'); location.replace('rent.php?code={$code}'); </script>";
	exit;
}
?>
<div class="topBtn">
	<span class="greenBtn">프린트</span>
</div>
<!--161130 div class="firstTable" 추가-->
<form name="regiform" id="regiform" action="rent.php?code=<?=$code;?>" method="post" onsubmit="return foch('regiform');">
	<input type='hidden' name='mode' value='w'>
	<div class="row-fluid">
		<div class="span5">
			<span class="subTitle">제휴사렌트카정보</span>
			<table class="detailTable2">
				<tbody>
				<tr>
					<th>소유자</th>
					<td colspan="3">
						<?=$ar_rshop['owner_name']?>(<?=$ar_rshop['owner_id']?>)
					</td>
				</tr>
				<tr>
					<th>회사명</th>
					<td colspan="3">
						<input type="text" maxlength="30" name="name" valch="yes" msg="회사명" class="" value='<?=$ar_rshop['name'];?>'>
					</td>
				</tr>
				<tr>
					<th>지점</th>
					<td colspan="3">
						<input type="text" maxlength="50" name="affiliate" class="" value="<?=$ar_rshop['affiliate'];?>">
					</td>
				</tr>
				<tr>
					<th>전화번호</th>
					<td colspan="3"><input type="text" maxlength="50" name="phone" valch="yes" msg="전화번호" class="" value="<?=$ar_rshop['phone'];?>"></td>
				</tr>
				<tr>
					<th>총 차량대수</th>
					<td colspan="3"><?=number_format($ar_rshop['totalcar']);?></td>
				</tr>
				<tr>
					<th>우편번호</th>
					<td colspan="3">
						<input type="text" maxlength="5" name="zipcode" id="zipcode" valch="yes" msg="우편번호" readonly="readonly" onclick="openDaumPostcode('')" class="" value='<?=$ar_rshop['zipcode'];?>'>
						<button type="button" class="greenBtn_small" onclick="openDaumPostcode('')" style="vertical-align:top">우편번호 검색</button>
					</td>
				</tr>
				<tr>
					<th>주소</th>
					<td colspan="3">
						<input type="text" maxlength="200" name="addr1" id="addr1" valch="yes" msg="주소" readonly onclick="openDaumPostcode('')" class="" value='<?=$ar_rshop['addr1'];?>'>
					</td>
				</tr>
				<tr>
					<th>상세주소</th>
					<td colspan="3">
						<input type="text" maxlength="200" name="addr2" id="addr2" valch="yes" msg="상세주소" class="" value='<?=$ar_rshop['addr2'];?>'>
					</td>
				</tr>
				<tr>
					<th>사업자등록번호</th>
					<td colspan="3"><input type="text" maxlength="50" name="businessnum" value="<?=$ar_rshop['businessnum'];?>" valch="yes" msg="사업자등록번호" isnum="yes" class="" placeholder="숫자만 입력해주세요"></td>
				</tr>
				<tr>
					<th>설립일</th>
					<td colspan="3"><input type="text" name="makedate" id='makedate' readonly valch="yes" msg="설립일" value="<?=$ar_rshop['makedate'];?>" class="datePicker" data-parent="#container"></td>
				</tr>
				<tr>
					<th>은행명(은행코드)</th>
					<td>
						<select name="bank">
<!--							<option value="001" --><?//= $ar_rshop['bank']=='001' ? 'selected' : '' ?><!-->한국은행(001)</option>-->
							<option value="002" <?= $ar_rshop['bank']=='002' ? 'selected' : '' ?>>KDB산업은행(002)</option>
							<option value="003" <?= $ar_rshop['bank']=='003' ? 'selected' : '' ?>>IBK기업은행(003)</option>
							<option value="004" <?= $ar_rshop['bank']=='004' ? 'selected' : '' ?>>KB국민은행(004)</option>
							<option value="005" <?= $ar_rshop['bank']=='005' ? 'selected' : '' ?>>외환은행(005)</option>
							<option value="006" <?= $ar_rshop['bank']=='006' ? 'selected' : '' ?>>주택은행(006)</option>
							<option value="007" <?= $ar_rshop['bank']=='007' ? 'selected' : '' ?>>수협(007)</option>
							<option value="011" <?= $ar_rshop['bank']=='011' ? 'selected' : '' ?>>농협은행(011)</option>
							<option value="020" <?= $ar_rshop['bank']=='020' ? 'selected' : '' ?>>우리은행(020)</option>
							<option value="023" <?= $ar_rshop['bank']=='023' ? 'selected' : '' ?>>SC제일은행(023)</option>
							<option value="027" <?= $ar_rshop['bank']=='027' ? 'selected' : '' ?>>한국씨티은행(027)</option>
							<option value="031" <?= $ar_rshop['bank']=='031' ? 'selected' : '' ?>>대구은행(031)</option>
							<option value="032" <?= $ar_rshop['bank']=='032' ? 'selected' : '' ?>>부산은행(032)</option>
							<option value="034" <?= $ar_rshop['bank']=='034' ? 'selected' : '' ?>>광주은행(034)</option>
							<option value="035" <?= $ar_rshop['bank']=='035' ? 'selected' : '' ?>>제주은행(035)</option>
							<option value="037" <?= $ar_rshop['bank']=='037' ? 'selected' : '' ?>>전북은행(037)</option>
							<option value="039" <?= $ar_rshop['bank']=='039' ? 'selected' : '' ?>>경남은행(039)</option>
							<option value="045" <?= $ar_rshop['bank']=='045' ? 'selected' : '' ?>>새마을금고(045)</option>
							<option value="048" <?= $ar_rshop['bank']=='048' ? 'selected' : '' ?>>신협(048)</option>
							<option value="050" <?= $ar_rshop['bank']=='050' ? 'selected' : '' ?>>상호저축은행(050)</option>
<!--							<option value="052" --><?//= $ar_rshop['bank']=='052' ? 'selected' : '' ?><!-->모간스탠리은행(052)</option>-->
<!--							<option value="054" --><?//= $ar_rshop['bank']=='054' ? 'selected' : '' ?><!-->HSBC은행(054)</option>-->
<!--							<option value="055" --><?//= $ar_rshop['bank']=='055' ? 'selected' : '' ?><!-->도이치은행(055)</option>-->
<!--							<option value="056" --><?//= $ar_rshop['bank']=='056' ? 'selected' : '' ?><!-->RBS은행(056)</option>-->
<!--							<option value="057" --><?//= $ar_rshop['bank']=='057' ? 'selected' : '' ?><!-->JP모간체이스은행(057)</option>-->
<!--							<option value="058" --><?//= $ar_rshop['bank']=='058' ? 'selected' : '' ?><!-->미즈호은행(058)</option>-->
<!--							<option value="059" --><?//= $ar_rshop['bank']=='059' ? 'selected' : '' ?><!-->미쓰비시은행(059)</option>-->
<!--							<option value="060" --><?//= $ar_rshop['bank']=='060' ? 'selected' : '' ?><!-->BOA은행(060)</option>-->
							<option value="064" <?= $ar_rshop['bank']=='064' ? 'selected' : '' ?>>산림조합(064)</option>
							<option value="071" <?= $ar_rshop['bank']=='071' ? 'selected' : '' ?>>우체국(071)</option>
							<option value="081" <?= $ar_rshop['bank']=='081' ? 'selected' : '' ?>>KEB하나은행(081)</option>
							<option value="088" <?= $ar_rshop['bank']=='088' ? 'selected' : '' ?>>신한은행(088)</option>
						</select>
					</td>
				</tr>
				<tr>
					<th>계좌번호</th>
					<td>
						<input type="text" maxlength="50" name="bankaccount" class="" value='<?=$ar_rshop['bankaccount'];?>'>
					</td>
				</tr>
				<tr>
					<th>예금주</th>
					<td><input type="text" maxlength="50" name="bankname" class="" value='<?=$ar_rshop['bankname'];?>'>
					</td>
				</tr>
				<tr>
					<th>은행계좌 인증정보</th>
					<td>
						<input type="text" maxlength="50" name="bankholder" class="" value='<?=$ar_rshop['bankholder'];?>'><br />
						예금주의 사업자 등록번호 10자리 혹은<br />
						생년월일 6자리 + 주민등록번호 7번째 1자리
					</td>
				</tr>
				<tr>
					<th>App/Web 수수료</th>
					<td><?=$ar_rshop['per1'];?> %</td>
				</tr>
				<tr>
					<th>NET 수수료</th>
					<td><?=$ar_rshop['per2'];?> %</td>
				</tr>
				</tbody>
			</table>
		</div>
		<div class="span7">
			<span class="subTitle">인적정보</span>
			<table class="detailTable2">
				<tbody>
				<tr>
					<th>대표자명</th>
					<td>
						<input type="text" maxlength="50" name='dname1' value='<?=$ar_rshop['dname1'];?>' class="mWt100">
					</td>
					<th>제휴담당자</th>
					<td>
						<input type="text" maxlength="50" name='dname2' value='<?=$ar_rshop['dname2'];?>' class="mWt100">
					</td>
					<th>경리담당자</th>
					<td>
						<input type="text" maxlength="50" name='dname3' value='<?=$ar_rshop['dname3'];?>' class="mWt100">
					</td>
				</tr>
				<tr>
					<th>Tel</th>
					<td>
						<input type="text" maxlength="20" name='dphone1' value='<?=$ar_rshop['dphone1'];?>' class="mWt100">
					</td>
					<th>Tel</th>
					<td>
						<input type="text" maxlength="20" name='dphone2' value='<?=$ar_rshop['dphone2'];?>' class="mWt100">
					</td>
					<th>Tel</th>
					<td>
						<input type="text" maxlength="20" name='dphone3' value='<?=$ar_rshop['dphone3'];?>' class="mWt100">
					</td>
				</tr>
				<tr>
					<th>Mobile</th>
					<td>
						<input type="text" maxlength="20" maxlength="20" name='dcp1' value='<?=$ar_rshop['dcp1'];?>' class="mWt100">
					</td>
					<th>Mobile</th>
					<td><input type="text" maxlength="20" name='dcp2' value='<?=$ar_rshop['dcp2'];?>' class="mWt100">
					</td>
					<th>Mobile</th>
					<td><input type="text" maxlength="20" name='dcp3' value='<?=$ar_rshop['dcp3'];?>' class="mWt100">
					</td>
				</tr>
				<tr>
					<th>E-mail</th>
					<td>
						<input type="text" maxlength="50" name='demail1' value='<?=$ar_rshop['demail1'];?>' class="mWt100">
					</td>
					<th>E-mail</th>
					<td>
						<input type="text" maxlength="50" name='demail2' value='<?=$ar_rshop['demail2'];?>' class="mWt100">
					</td>
					<th>E-mail</th>
					<td>
						<input type="text" maxlength="50" name='demail3' value='<?=$ar_rshop['demail3'];?>' class="mWt100">
					</td>
				</tr>
				<tr>
					<th>생년월일</th>
					<td>
						<input type="text" name='dbirth1' value='<?=$ar_rshop['dbirth1'];?>' class="datePicker mWt100" data-parent="#container" readonly>
					</td>
					<th>생년월일</th>
					<td>
						<input type="text" name='dbirth2' value='<?=$ar_rshop['dbirth2'];?>' class="datePicker mWt100" data-parent="#container" readonly>
					</td>
					<th>생년월일</th>
					<td>
						<input type="text" name='dbirth3' value='<?=$ar_rshop['dbirth3'];?>' class="datePicker mWt100" data-parent="#container" readonly>
					</td>
				</tr>
				</tbody>
			</table>

			<span class="subTitle" style="margin-top:20px;">문자수신연락처</span>
			<table class="detailTable2">
				<tbody>
				<tr>
					<th>Mobile</th>
					<td>
						<input type="text" maxlength="20" name='contact1' value='<?=$ar_rshop['contact1'];?>' class="mWt100">
					</td>
					<th>Mobile</th>
					<td>
						<input type="text" maxlength="20" name='contact2' value='<?=$ar_rshop['contact2'];?>' class="mWt100">
					</td>
					<th>Mobile</th>
					<td>
						<input type="text" maxlength="20" name='contact3' value='<?=$ar_rshop['contact3'];?>' class="mWt100">
					</td>
				</tr>
				</tbody>
			</table>


			<span class="subTitle" style="margin-top:20px;">예약정보</span>
			<table class="detailTable2">
				<colgroup>
					<col width="150px;">
					<col width="*">
				</colgroup>
				<tbody>
				<tr>
					<th>반납정비 시간</th>
					<td>
						<select name="wtime">
							<?php
							for ($i = 3; $i <= 48; $i ++) {
								$se = "";
								if ($ar_rshop['wtime'] == $i) {
									$se = "selected";
								}

								if ($i >= 3 && $i <= 12) {
									echo "<option value='{$i}' $se>{$i}</option>";
								} else if ($i > 12 && $i <= 24) {
									if ($i % 2 == 0) {
										echo "<option value='{$i}' $se>{$i}</option>";
									}
								} else if ($i > 24 && $i <= 48) {
									if ($i % 6 == 0) {
										echo "<option value='{$i}' $se>{$i}</option>";
									}
								}

							}
							?>
						</select>시간 이후
					</td>
				</tr>
				<tr>
					<th>영업시간</th>
					<td>
						평일 <select name="s1time1">
							<?php
							for ($i = 0; $i <= 24; $i ++) {
								$j = $i;
								if (strlen($j) == 1) {
									$j = "0" . $i;
								}
								$time = $j . ":00";
								if ($j == 24) {
									$time = "23:59";
								}

								$se = "";
								if ($ar_rshop['s1time1'] == $time) {
									$se = "selected";
								}

								echo "<option value='{$time}' $se>$time</option>";
							}
							?>
						</select>~
						<select name="s1time2">
							<?php
							for ($i = 0; $i <= 24; $i ++) {
								$j = $i;
								if (strlen($j) == 1) {
									$j = "0" . $i;
								}
								$time = $j . ":00";
								if ($j == 24) {
									$time = "23:59";
								}

								$se = "";
								if ($ar_rshop['s1time2'] == $time) {
									$se = "selected";
								}

								echo "<option value='{$time}' $se>$time</option>";
							}
							?>
						</select>
						<!--161118삭제
						<br>-->
						&nbsp;주말/공휴일
						<select name="s2time1">
							<?php
							for ($i = 0; $i <= 24; $i ++) {
								$j = $i;
								if (strlen($j) == 1) {
									$j = "0" . $i;
								}
								$time = $j . ":00";
								if ($j == 24) {
									$time = "23:59";
								}

								$se = "";
								if ($ar_rshop['s2time1'] == $time) {
									$se = "selected";
								}

								echo "<option value='{$time}' $se>$time</option>";
							}
							?>
						</select>~
						<select name="s2time2">
							<?php
							for ($i = 0; $i <= 24; $i ++) {
								$j = $i;
								if (strlen($j) == 1) {
									$j = "0" . $i;
								}
								$time = $j . ":00";
								if ($j == 24) {
									$time = "23:59";
								}

								$se = "";
								if ($ar_rshop['s2time2'] == $time) {
									$se = "selected";
								}

								echo "<option value='{$time}' $se>$time</option>";
							}
							?>
						</select>
					</td>
				</tr>

				<tr>
					<th>배반차가능시간</th>
					<td>
						평일 : <select name="d1time1">
							<?php
							for ($i = 0; $i <= 24; $i ++) {
								$j = $i;
								if (strlen($j) == 1) {
									$j = "0" . $i;
								}
								$time = $j . ":00";
								if ($j == 24) {
									$time = "23:59";
								}

								$se = "";
								if ($ar_rshop['d1time1'] == $time) {
									$se = "selected";
								}

								echo "<option value='$time' $se>$time</option>";
							}
							?>
						</select>~
						<select name="d1time2">
							<?php
							for ($i = 0; $i <= 24; $i ++) {
								$j = $i;
								if (strlen($j) == 1) {
									$j = "0" . $i;
								}
								$time = $j . ":00";
								if ($j == 24) {
									$time = "23:59";
								}
								$se = "";
								if ($ar_rshop['d1time2'] == $time) {
									$se = "selected";
								}

								echo "<option value='{$time}' $se>$time</option>";
							}
							?>
						</select>
						<!--161118삭제
						<br>-->
						&nbsp;주말/공휴일
						<select name="d2time1">
							<?php
							for ($i = 0; $i <= 24; $i ++) {
								$j = $i;
								if (strlen($j) == 1) {
									$j = "0" . $i;
								}
								$time = $j . ":00";
								if ($j == 24) {
									$time = "23:59";
								}
								$se = "";
								if ($ar_rshop['d2time1'] == $time) {
									$se = "selected";
								}

								echo "<option value='{$time}' $se>$time</option>";
							}
							?>
						</select>~
						<select name="d2time2">
							<?
							for ($i = 0; $i <= 24; $i ++) {
								$j = $i;
								if (strlen($j) == 1) {
									$j = "0" . $i;
								}
								$time = $j . ":00";
								if ($j == 24) {
									$time = "23:59";
								}
								$se = "";
								if ($ar_rshop['d2time2'] == $time) {
									$se = "selected";
								}
								echo "<option value='{$time}' $se>$time</option>";
							}
							?>
						</select>
					</td>
				</tr>

				<tr>
					<th>예약대기시간[지점방문]</th>
					<td>
						<select name="wtime1">
							<?php
							for ($i = 1; $i <= 48; $i ++) {

								$se = "";
								if ($ar_rshop['wtime1'] == $i) {
									$se = "selected";
								}
								if ($i >= 1 && $i <= 12) {
									echo "<option value='{$i}' $se>{$i}</option>";
								} else if ($i > 12 && $i <= 24) {
									if ($i % 2 == 0) {
										echo "<option value='{$i}' $se>{$i}</option>";
									}
								} else if ($i > 24 && $i <= 48) {
									if ($i % 6 == 0) {
										echo "<option value='{$i}' $se>{$i}</option>";
									}
								}
							}
							?>
						</select>시간 이후
					</td>
				</tr>
				<tr>
					<th>예약대기시간[배달대여]</th>
					<td>
						<select name="wtime2">
							<?php
							for ($i = 3; $i <= 48; $i ++) {
								$se = "";
								if ($ar_rshop['wtime2'] == $i) {
									$se = "selected";
								}

								if ($i >= 3 && $i <= 12) {
									echo "<option value='{$i}' $se>{$i}</option>";
								} else if ($i > 12 && $i <= 24) {
									if ($i % 2 == 0) {
										echo "<option value='{$i}' $se>{$i}</option>";
									}
								} else if ($i > 24 && $i <= 48) {
									if ($i % 6 == 0) {
										echo "<option value='{$i}' $se>{$i}</option>";
									}
								}
							}
							?>
						</select>시간 이후
					</td>
				</tr>
				<tr>
					<th>최소예약시간</th>
					<td>
						<input type="text" name="sdateLimit" id="sdateLimit" class="datePicker" data-parent="#container" readonly> ~
						<input type="text" name="edateLimit" id="edateLimit" class="datePicker" data-parent="#container" readonly>
                        <!--<select name="hourLimit" id="hourLimit">
                                <option value="36">36시간 이상</option>
                                <option value="48">48시간 이상</option>
                                <option value="60">60시간 이상</option>
                                <option value="72">72시간 이상</option>
                            </select>-->
                        <input type="text" name="hourLimit" id="hourLimit" placeholder="30~96" class="mWt50">시간 이상
						<button type="button" class="greenBtn_small addLimit">추가</button>
						<ul class="listLimit" style="margin-top:5px;">
						<?
						$query = "SELECT * FROM rentshop_reservation_limit WHERE rentshop_idx = {$ar_rshop['idx']} ORDER BY sdate";
						$r = mysql_query($query);
						while($row = mysql_fetch_assoc($r)) {?>
							<li>
								<a href="#deleteLimit" class="deleteLimit">[<?=$row['sdate']?>~<?=$row['edate']?>(<?=$row['hour']?>) X]</a>
								<input type="hidden" name="reservationLimit[]" value="<?=$row['sdate']?>~<?=$row['edate']?>(<?=$row['hour']?>)" />
							</li>
						<?}?>
						</ul>
					</td>
				</tr>
				<tr>
					<th>휴무일</th>
					<td>
						<input type="text" name="sdateOff" id="sdateOff" class="datePicker" data-parent="#container" readonly> ~
						<input type="text" name="edateOff" id="edateOff" class="datePicker" data-parent="#container" readonly>
						<button type="button" class="greenBtn_small addOff">추가</button>
						<ul class="listOff" style="margin-top:5px;">
						<?
						$query = "SELECT * FROM rentshop_off WHERE rentshop_idx = {$ar_rshop['idx']} ORDER BY sdate";
						$r = mysql_query($query);
						while($row = mysql_fetch_assoc($r)) {?>
							<li>
								<a href="#deleteOff" class="deleteOff">[<?=$row['sdate']?>~<?=$row['edate']?> X]</a>
								<input type="hidden" name="rentshopOff[]" value="<?=$row['sdate']?>~<?=$row['edate']?>" />
							</li>
						<?}?>
						</ul>
					</td>
				</tr>
				<tr>
					<th>배반차 지역(단기)</th>
					<td>
						<select class="selectLoca1">
							<option value=''>지역선택</option>
							<?php
							$q = "SELECT * FROM area WHERE LENGTH(ac_code)='2' ORDER BY orders ASC";
							$r = mysql_query($q);
							while ($row = mysql_fetch_array($r)) {
								echo "<option value='{$row['ac_code']}'>{$row['ac_alias']}</option>";
								$ar_a1[$row['ac_code']] = $row['ac_alias'];
							}
							?>
						</select>
						<select class="selectLoca2">
							<option value=''>지역선택</option>
						</select>
						<ul class="listLoca" style="margin-top:5px;" data-code="1">
							<?php
							$q = "SELECT * FROM rentshop_loca WHERE rentshop_idx='{$ar_rshop['idx']}' AND stype='1'";
							$r = mysql_query($q);
							while ($row = mysql_fetch_array($r)) {

								if ($row['loca2'] == 'A') {
									$ar_a2['ac_name'] = "전체";
								} else {
									$ar_a2 = sel_query_all("area", " WHERE ac_code='{$row['loca2']}'");
								}
								?>
								<li style="display:inline-block;white-space:nowrap;" data-c="<?=$row['loca1']?><?=$row['loca2']?>">
									<a href="#deleteLoca" class="deleteLoca">[<?=$ar_a1[$row['loca1']]?>/<?=$ar_a2['ac_name']?> X]</a>
									<input type="hidden" name="ar_loca1_1[]" value='<?=$row['loca1']?>'>
									<input type="hidden" name="ar_loca1_2[]" value='<?=$row['loca2']?>'>
								</li>
							<?}?>
						</ul>
					</td>
				</tr>
				<tr>
					<th>배반차 지역(월장기)</th>
					<td>
						<select class="selectLoca1">
							<option value=''>지역선택</option>
							<?php
							$q = "SELECT * FROM area WHERE LENGTH(ac_code)='2' ORDER BY orders ASC";
							$r = mysql_query($q);
							while ($row = mysql_fetch_array($r)) {
								echo "<option value='{$row['ac_code']}'>{$row['ac_alias']}</option>";
							}
							?>
						</select>
						<select class="selectLoca2">
							<option value=''>지역선택</option>
						</select>
						<ul class="listLoca" style="margin-top:5px;" data-code="2">
							<?php
							$q = "SELECT * FROM rentshop_loca WHERE rentshop_idx='{$ar_rshop['idx']}' AND stype='2'";
							$r = mysql_query($q);
							while ($row = mysql_fetch_array($r)) {

								if ($row['loca2'] == 'A') {
									$ar_a2['ac_name'] = "전체";
								} else {
									$ar_a2 = sel_query_all("area", " WHERE ac_code='{$row['loca2']}'");

								}?>
								<li style="display:inline-block;white-space:nowrap;" data-c="<?=$row['loca1']?><?=$row['loca2']?>">
									<a href="#deleteLoca" class="deleteLoca">[<?=$ar_a1[$row['loca1']]?>/<?=$ar_a2['ac_name']?> X]</a>
									<input type="hidden" name="ar_loca2_1[]" value='<?=$row['loca1']?>'>
									<input type="hidden" name="ar_loca2_2[]" value='<?=$row['loca2']?>'>
								</li>
								<?
							}
							?>
						</ul>
					</td>
				</tr>
				</tbody>
			</table>
		</div>
	</div>
	<div class="btn_wrap btn_center btn_bottom">
		<span class="greenBtn btn_submit" data-form="#regiform"><a href="javascript:">수정</a></span>
	</div>
</form>
<div style="margin-top:30px;">
	<table class="listTableColor">
		<thead>
		<tr>
			<th rowspan="2">총 차량 대수</th>
			<th rowspan="2">등록대수</th>
			<th colspan="<?=(sizeof($ar_ttype2_idx) + 1);?>">등록현황</th>
		</tr>
		<tr>
			<?php
			for ($i = 0; $i < sizeof($ar_ttype2_idx); $i ++) {
				?>
				<th><?=$ar_ttype2_sname[$ar_ttype2_idx[$i]];?></th>
			<? } ?>
			<th>계</th>
		</tr>
		</thead>
		<tbody>
		<?php
		$qs = "SELECT vehicles.*, grade_idx FROM vehicles LEFT JOIN vehicle_models ON vehicle_models.idx=vehicles.model_idx WHERE rentshop_idx='{$ar_rshop['idx']}' AND vehicles.dt_delete IS NULL";
		$rs = mysql_query($qs);
		$total = 0;
		while ($rows = mysql_fetch_array($rs)) {
			if (!$ar_now[$rows['grade_idx']]) {
				$ar_now[$rows['grade_idx']] = 0;
			}
			$ar_now[$rows['grade_idx']] ++;
			$total ++;
		}
		?>
		<tr onClick="location.href='scar.php?code=list'">
			<td><?=number_format($ar_rshop['totalcar']);?></td>
			<td><?=number_format($total);?></td>
			<?php
			for ($i = 0; $i < sizeof($ar_ttype2_idx); $i ++) {
				?>
				<td><?=number_format($ar_now[$ar_ttype2_idx[$i]]);?></td>
			<?php } ?>
			<td><?=number_format($total);?></td>
		</tr>
		</tbody>
	</table>
</div>

<script src='<?=$config['daumPostcode']?>'></script>
<script type="text/javascript">
	function foch(f) {
		var re = check_form(f);
		if (re) {
			var $affiliate = $('input[name="affiliate"]');
			var affiliate = $affiliate.val();
			if (affiliate.split(' ').length > 1) {
				alert('지점은 한 단어로 입력해야합니다. (~~역, ~~동 등)');
				$affiliate.focus();
				return false;
			}

			var exit = affiliate.match(/[0-9]+번/gi);
			if (exit) {
				alert('"' + exit[0] + '"은 입력할 수 없습니다.');
				$affiliate.focus();
				return false;
			}

			//TODO: 오픈 플렛폼 사용시 적용
			// var bankAccount = $('input[name=bankaccount]').val();
			// var bankName = $('input[name=bankname]').val();
			// var bankHolder = $('input[name=bankholder]').val();
			// if ( ((bankAccount != '' || bankName != '' || bankHolder != '') && (bankAccount == '' || bankName == '' || bankHolder == ''))
			// || ( (bankAccount == '' || bankName == '' || bankHolder == '') && (bankAccount != '' || bankName != '' || bankHolder != ''))) {
			// 	alert('계좌번호, 예금주, 은행계좌 인증정보는 모두 입력하거나 모두 입력하지 않아야 합니다.');
			// 	return false;
			// }

			return confirm('수정 하시겠습니까?');
		} else {
			return false;
		}
	}

	function check_email(tstr1) {

		if (!(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(tstr1))) {
			return false;
		}
		else {
			return true;
		}
	}

	$(function() {
		$(document)
			.on('change', '.selectLoca1', function() {
				var $select = $(this);
				var key = $select.find(':selected').val();

				var html = '<option value="">지역선택</option>';
				if (key == '') {
					$select.next('select.selectLoca2').html(html);
					return;
				}
				$.getJSON("/mo/proajax.php?modtype=common_addr&han=get_addr&code=" + key, function (result) {
					if (result['res'] == 'ok') {
						html += '<option value="A">전체</option>';
						$(result.data).each(function (index, item) {
							html += '<option value="' + item.ac_code + '">' + item.ac_name + '</option>';
						});

						$select.next('select.selectLoca2').html(html);
					}
					else {
						alert(result.resmsg);
					}
				});
			})
			.on('change', '.selectLoca2', function() {
				var $selectLoca2 = $(this);
				var $selectLoca1 = $selectLoca2.siblings('.selectLoca1');
				var $listLoca = $selectLoca2.siblings('.listLoca');

				var loca1 = $selectLoca1.val();
				var loca2 = $selectLoca2.val();
				if(loca1 != '' && loca2 != '') {
					var loca1Text = $selectLoca1.find(':selected').text();
					var loca2Text = $selectLoca2.find(':selected').text();

					if($listLoca.find('li[data-c="' + loca1 + loca2 + '"]').length < 1) {
						var code = $listLoca.data('code');
						$('<li style="display:inline-block;white-space:nowrap;" data-c="' + loca1 + loca2 + '">' +
							'<a href="deleteLoca" class="deleteLoca">[' + loca1Text + '/' + loca2Text + ' X]</a>' +
							'<input type="hidden" name="ar_loca' + code + '_1[]" value="' + loca1 +'" />' +
							'<input type="hidden" name="ar_loca' + code + '_2[]" value="' + loca2 +'" />' +
							'</li>').appendTo($listLoca);
					}
				}
			})
			.on('click', '.deleteLoca', function(e) {
				e.preventDefault();
				$(this).parents('li').remove();
			})
			.on('click', '.deleteOff', function(e) {
				e.preventDefault();

				$(this).parents('li').remove();
			})
			.on('click', '.addOff', function(e) {
				e.preventDefault();

				var sdate = $('#sdateOff').val();
				var edate = $('#edateOff').val();

				if(sdate == '' || edate == '') {
					alert('시작일과 종료일을 입력하세요');
					return false;
				}

				if(sdate > edate) {
					alert('시작일이 종료일 이전입니다.');
					return false;
				}

				$('<li>' +
					'<a href="#deleteOff" class="deleteOff">[' + sdate + '~' + edate +' X]</a>' +
					'<input type="hidden" name="rentshopOff[]" value="' + sdate + '~' + edate +'" />' +
					'</li>').appendTo('ul.listOff');
			})
			.on('click', '.deleteLimit', function(e) {
				e.preventDefault();

				$(this).parents('li').remove();
			})
			.on('click', '.addLimit', function(e) {
				e.preventDefault();

				var sdate = $('#sdateLimit').val();
				var edate = $('#edateLimit').val();
				var hour =  $('#hourLimit').val();

				if(sdate == '' || edate == '') {
					alert('시작일과 종료일을 입력하세요');
					return false;
				}

				if(sdate > edate) {
					alert('시작일이 종료일 이전입니다.');
					return false;
				}

                if(! /^[0-9]*$/.test(hour) || hour == '' || hour < 30 || hour > 96) {
                    alert('최소 예약시간은 30~96사이 숫자만 입력해주세요.');
                    return false;
                }

				$('<li>' +
					'<a href="#deleteOff" class="deleteLimit">[' + sdate + '~' + edate + '(' + hour + ') X]</a>' +
					'<input type="hidden" name="reservationLimit[]" value="' + sdate + '~' + edate + '(' + hour + ')" />' +
					'</li>').appendTo('ul.listLimit');
			})
		;
	});
</script>