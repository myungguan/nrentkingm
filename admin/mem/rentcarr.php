<?
/**
 * 어드민 > 고객관리 > 멤버사 > 등록
 * admin.rentking.co.kr/mem.php?code=rentcarr
 * 어드민 멤버사 리스트 상세조회 페이지
 */

if ($mode == 'w') {
//	$value['name'] = mysql_escape_string($_REQUEST['name']);
//	$value['id'] = mysql_escape_string($_REQUEST['id']);
//	$value['passwd'] = mysql_escape_string($_REQUEST['passwd']);
//	$value['signdate'] = date("Y-m-d H:i:s");
//	$value['memgrade'] = "10";
//	$value['canconnect'] = "Y";
//
//	insert("member", $value);
//	unset($value);
//	$idx = mysql_insert_id();

	$owner_id = $_REQUEST['owner_id'];
	$query = "SELECT * FROM member WHERE id = '$owner_id'";
	$r = mysql_query($query);
	if(mysql_num_rows($r) < 1) {
		echo "<script type='text/javascript'>alert('소유자 정보를 찾을 수 없습니다.'); history.back(); </script>";
		exit;
	}

	$owner = mysql_fetch_assoc($r);
	if($owner['canconnect'] != 'Y') {
		echo "<script type='text/javascript'>alert('소유자가 현재 접속할 수 없는 아이디 입니다.'); history.back(); </script>";
		exit;
	}

	$savedir = $config['uploadPath'] . "/paperimg/";

	if (!is_dir($savedir)) {
		@mkdir($savedir, 0777);
		chmod($savedir, 0707);
	}

	$userfile = array($_FILES['files']['name']);
	$tmpfile = array($_FILES['files']['tmp_name']);
	$ar_del = array("N");
	$ar_last = array("");

	$fs = array();
	for ($i = 0; $i < sizeof($userfile); $i++) {
		$fs[$i] = uploadfile_mod($userfile[$i], $tmpfile[$i], $i, $savedir, $ar_last[$i], $ar_del[$i]);
	}
	$value['paperimg'] = $fs[0];

//	$value['mem_idx'] = $idx;
	$value['owner_idx'] = $owner['idx'];
	$value['name'] = mysql_escape_string($_REQUEST['name']);
	$value['affiliate'] = mysql_escape_string($_REQUEST['affiliate']);
	$value['businessnum'] = mysql_escape_string($_REQUEST['businessnum']);
	$value['totalcar'] = mysql_escape_string($_REQUEST['totalcar']);
	$value['zipcode'] = $_REQUEST['zipcode'];
	$value['addr1'] = $_REQUEST['addr1'];
	$value['addr2'] = mysql_escape_string($_REQUEST['addr2']);
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
	$value['dbirth1'] =  mysql_escape_string($_REQUEST['dbirth1']);
	$value['dbirth2'] =  mysql_escape_string($_REQUEST['dbirth2']);
	$value['dbirth3'] =  mysql_escape_string($_REQUEST['dbirth3']);
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
	$value['makedate'] = $_REQUEST['makedate'];
	$value['memo'] = mysql_escape_string($_REQUEST['memo']);
	$value['isok'] = $_REQUEST['isok'];
	$value['per1'] = mysql_escape_string($_REQUEST['per1']);
	$value['per2'] = mysql_escape_string($_REQUEST['per2']);

	$locationResult = true; // location check
	if (!isset($value['addr1'])) { // 주소가 빈값일때
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
				$value['latlng'] = "POINT({$decode['channel']['item'][0]['lat']},{$decode['channel']['item'][0]['lng']}),";
			} else {
				$locationResult = false;
			}
		} else {
			$locationResult = false;
		}
	}

	if (!$locationResult) { // locationResult == false
		echo "<script type='text/javascript'>alert('위치 정보를 찾을 수 없습니다.'); history.back(); </script>";
		exit;
	}

	$query = "
			INSERT INTO
				rentshop (
					owner_idx,
					name,
					affiliate,
					totalcar,
					zipcode,
					addr1,
					addr2,
					latlng,
					dname1,
					dname2,
					dname3,
					dphone1,
					dphone2,
					dphone3,
					dcp1,
					dcp2,
					dcp3,
					demail1,
					demail2,
					demail3,
					dbirth1,
					dbirth2,
					dbirth3,
					contact1,
					contact2,
					contact3,
					wtime,
					s1time1,
					s1time2,
					s2time1,
					s2time2,
					d1time1,
					d1time2,
					d2time1,
					d2time2,
					wtime1,
					wtime2,
					makedate,
					memo,
					isok,
					per1,
					per2,
					dt_create)
			VALUES (
					'{$value['owner_idx']}',
					'{$value['name']}',
					'{$value['affiliate']}',
					'{$value['totalcar']}',
					'{$value['zipcode']}',
					'{$value['addr1']}',
					'{$value['addr2']}',
				  	{$value['latlng']}
					'{$value['dname1']}',
					'{$value['dname2']}',
					'{$value['dname3']}',
					'{$value['dphone1']}',
					'{$value['dphone2']}',
					'{$value['dphone3']}',
					'{$value['dcp1']}',
					'{$value['dcp2']}',
					'{$value['dcp3']}',
					'{$value['demail1']}',
					'{$value['demail2']}',
					'{$value['demail3']}',
					'{$value['dbirth1']}',
					'{$value['dbirth2']}',
					'{$value['dbirth3']}',
					" . (strlen(trim($value['contact1'])) > 0 ? "'{$value['contact1']}'" : 'NULL') . ",
					" . (strlen(trim($value['contact2'])) > 0 ? "'{$value['contact2']}'" : 'NULL') . ",
					" . (strlen(trim($value['contact3'])) > 0 ? "'{$value['contact3']}'" : 'NULL') . ",
					'{$value['wtime']}',
					'{$value['s1time1']}',
					'{$value['s1time2']}',
					'{$value['s2time1']}',
					'{$value['s2time2']}',
					'{$value['d1time1']}',
					'{$value['d1time2']}',
					'{$value['d2time1']}',
					'{$value['d2time2']}',
					'{$value['wtime1']}',
					'{$value['wtime2']}',
					'{$value['makedate']}',
					'{$value['memo']}',
					'{$value['isok']}',
					'{$value['per1']}',
					'{$value['per2']}',
					NOW())";

	mysql_query($query);

	$idx = mysql_insert_id();
	unset($value);

	$ar_loca1_1 = $_REQUEST['ar_loca1_1'];
	$ar_loca1_2 = $_REQUEST['ar_loca1_2'];

	for ($i = 0; $i < sizeof($ar_loca1_1); $i++) {

		$value['rentshop_idx'] = $idx;
		$value['stype'] = "1";
		$value['loca1'] = $ar_loca1_1[$i];
		$value['loca2'] = $ar_loca1_2[$i];
		insert("rentshop_loca", $value);
		unset($value);
	}

	$ar_loca2_1 = $_REQUEST['ar_loca2_1'];
	$ar_loca2_2 = $_REQUEST['ar_loca2_2'];

	for ($i = 0; $i < sizeof($ar_loca2_1); $i++) {

		$value['rentshop_idx'] = $idx;
		$value['stype'] = "2";
		$value['loca1'] = $ar_loca2_1[$i];
		$value['loca2'] = $ar_loca2_2[$i];
		insert("rentshop_loca", $value);
		unset($value);
	}

	echo "<script type='text/javascript'>alert('등록되었습니다'); location.replace('../mem.php?code=$code'); </script>";
	exit;
}
?>
<div class="topBtn">
<!--	<span class="greenBtn">프린트</span>-->
</div>
<!--161130 div class="firstTable" 추가-->
<form name="regiform" id="regiform" action="../mem.php?code=<?= $code; ?>" enctype="multipart/form-data"
	  method="post" onsubmit="return foch('regiform');">
	<input type='hidden' name='mode' value='w'>
	<input type='hidden' name='ischeck' id='idcheck' value='N'>
	<div class="row-fluid">
		<div class="span5">
			<span class="subTitle">멤버사 정보</span>
			<table class="detailTable2">
				<tbody>
				<tr>
					<th>소유자아이디</th>
					<td colspan="3">
						<input type="text" name="owner_id" valch="yes" msg="소유자아이디" value="" />
					</td>
				</tr>
				<tr>
					<th>회사명</th>
					<td colspan="3"><input type="text" maxlength="50" name="name" valch="yes" msg="회사명" class=""></td>
				</tr>
				<tr>
					<th>지점</th>
					<td colspan="3"><input type="text" maxlength="50" name="affiliate" valch="yes" msg="지점" class=""></td>
				</tr>
				<tr>
					<th>전화번호</th>
					<td colspan="3"><input type="text" maxlength="50" name="phone" valch="yes" msg="전화번호" class=""></td>
				</tr>
				<tr>
					<th>총 차량대수</th>
					<td colspan="3"><input type="text" maxlength="10" name="totalcar" valch="yes" msg="총차량대수" class=""></td>
				</tr>
				<tr>
					<th>우편번호</th>
					<td colspan="3">
						<input type="text" name="zipcode" id="zipcode" valch="yes" msg="우편번호" readonly="readonly"
							   onclick="openDaumPostcode('')" class="">
						<span class="greenBtn_small" onclick="openDaumPostcode('')">우편번호 검색</span>
					</td>
				</tr>
				<tr>
					<th>주소</th>
					<td colspan="3"><input type="text" name="addr1" id="addr1" valch="yes" msg="주소" readonly
										   onclick="openDaumPostcode('')" class=""></td>
				</tr>
				<tr>
					<th>상세주소</th>
					<td colspan="3"><input type="text" maxlength="200" name="addr2" id="addr2" valch="yes" msg="상세주소" class=""></td>
				</tr>
				<tr>
					<th>사업자등록번호</th>
					<td colspan="3"><input type="text" maxlength="50" name="businessnum" valch="yes" msg="사업자등록번호" isnum="yes" class=""
										   placeholder="숫자만 입력해주세요"></td>
				</tr>
				<tr>
					<th>설립일</th>
					<td colspan="3"><input type="text" maxlength="" name="makedate" id='makedate' valch="yes" msg="설립일" class="datePicker" data-parent="#container" readonly></td>
				</tr>
				<tr>
					<th>회사특징</th>
					<td colspan="3"><textarea name="memo" class="memo" rows="9" style="width:100%"></textarea></td>
				</tr>
				<tr>
					<th>사용인증</th>
					<td colspan="3">
						<input type="radio" name="isok" value="Y"><label>허가</label>
						<input type="radio" name="isok" value="N" checked><label>비허가</label>
					</td>
				</tr>
				<tr>
					<th>사업자등록증</th>
					<td colspan="3"><input type='file' name='files'>
					</td>
				</tr>
				</tbody>
			</table>

			<span class="subTitle" style="margin-top:20px;">수수료</span>
			<table class="detailTable2">
				<colgroup>
					<col width="150px;">
					<col width="*">
				</colgroup>
				<tbody>
				<tr>
					<th>App/Web 수수료</th>
					<td><input type="text" name="per1"> %</td>
				</tr>
				<tr>
					<th>NET 수수료</th>
					<td><input type="text" name="per2"> %</td>
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
					<td><input type="text" maxlength="50" name='dname1' class="mWt100"></td>
					<th>제휴담당자</th>
					<td><input type="text" maxlength="50" name='dname2' class="mWt100"></td>
					<th>경리담당자</th>
					<td><input type="text" maxlength="50" name='dname3' class="mWt100"></td>
				</tr>
				<tr>
					<th>Tel</th>
					<td><input type="text" maxlength="20" name='dphone1' class="mWt100"></td>
					<th>Tel</th>
					<td><input type="text" maxlength="20" name='dphone2' class="mWt100"></td>
					<th>Tel</th>
					<td><input type="text" maxlength="20" name='dphone3' class="mWt100"></td>
				</tr>
				<tr>
					<th>Mobile</th>
					<td><input type="text" maxlength="20" name='dcp1' class="mWt100"></td>
					<th>Mobile</th>
					<td><input type="text" maxlength="20" name='dcp2' class="mWt100"></td>
					<th>Mobile</th>
					<td><input type="text" maxlength="20" name='dcp3' class="mWt100"></td>
				</tr>
				<tr>
					<th>E-mail</th>
					<td><input type="text" maxlength="50" name='demail1' class="mWt100"></td>
					<th>E-mail</th>
					<td><input type="text" maxlength="50" name='demail2' class="mWt100"></td>
					<th>E-mail</th>
					<td><input type="text" maxlength="50" name='demail3' class="mWt100"></td>
				</tr>
				<tr>
					<th>생년월일</th>
					<td><input type="text" name='dbirth1' class="datePicker mWt100" data-parent="#container" readonly></td>
					<th>생년월일</th>
					<td><input type="text" name='dbirth2' class="datePicker mWt100" data-parent="#container" readonly></td>
					<th>생년월일</th>
					<td><input type="text" name='dbirth3' class="datePicker mWt100" data-parent="#container" readonly></td>
				</tr>
				<!--//161130 input추가-->
				</tbody>
			</table>

			<span class="subTitle" style="margin-top:20px;">문자수신연락처</span>
			<table class="detailTable2">
				<tbody>
				<tr>
					<th>Mobile</th>
					<td><input type="text" maxlength="20" name='contact1' value='' class="mWt100"></td>
					<th>Mobile</th>
					<td><input type="text" maxlength="20" name='contact2' value='' class="mWt100"></td>
					<th>Mobile</th>
					<td><input type="text" maxlength="20" name='contact3' value='' class="mWt100"></td>
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
							<?
							for ($i = 3; $i <= 48; $i++) {
								if ($i >= 3 && $i <= 12) {
									echo "<option value='{$i}'>{$i}</option>";
								} else if ($i > 12 && $i <= 24) {
									if ($i % 2 == 0) {
										echo "<option value='{$i}'>{$i}</option>";
									}
								} else if ($i > 24 && $i <= 48) {
									if ($i % 6 == 0) {
										echo "<option value='{$i}'>{$i}</option>";
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
						평일 : <select name="s1time1">
							<?
							for ($i = 0; $i <= 24; $i++) {
								$j = $i;
								if (strlen($j) == 1) {
									$j = "0" . $i;
								}
								$time = $j . ":00";
								if ($j == 24) {
									$time = "23:59";
								}
								echo "<option value='{$time}'>{$time}</option>";
							}
							?>
						</select> ~
						<select name="s1time2">
							<?
							for ($i = 0; $i <= 24; $i++) {
								$j = $i;
								if (strlen($j) == 1) {
									$j = "0" . $i;
								}
								$time = $j . ":00";
								if ($j == 24) {
									$time = "23:59";
								}
								echo "<option value='{$time}'>{$time}</option>";
							}
							?>
						</select>
						<!--161118삭제
                        <br>-->
						&nbsp;주말/공휴일
						<select name="s2time1">
							<?
							for ($i = 0; $i <= 24; $i++) {
								$j = $i;
								if (strlen($j) == 1) {
									$j = "0" . $i;
								}
								$time = $j . ":00";
								if ($j == 24) {
									$time = "23:59";
								}
								echo "<option value='{$time}'>{$time}</option>";
							}
							?>
						</select> ~
						<select name="s2time2">
							<?
							for ($i = 0; $i <= 24; $i++) {
								$j = $i;
								if (strlen($j) == 1) {
									$j = "0" . $i;
								}
								$time = $j . ":00";
								if ($j == 24) {
									$time = "23:59";
								}
								echo "<option value='{$time}'>{$time}</option>";
							}
							?>
						</select>
					</td>
				</tr>

				<tr>
					<th>배반차가능시간</th>
					<td>
						평일 : <select name="d1time1">
							<?
							for ($i = 0; $i <= 24; $i++) {
								$j = $i;
								if (strlen($j) == 1) {
									$j = "0" . $i;
								}
								$time = $j . ":00";
								if ($j == 24) {
									$time = "23:59";
								}
								echo "<option value='{$time}'>{$time}</option>";
							}
							?>
						</select> ~
						<select name="d1time2">
							<?
							for ($i = 0; $i <= 24; $i++) {
								$j = $i;
								if (strlen($j) == 1) {
									$j = "0" . $i;
								}
								$time = $j . ":00";
								if ($j == 24) {
									$time = "23:59";
								}
								echo "<option value='{$time}'>{$time}</option>";
							}
							?>
						</select>
						<!--161118삭제
                        <br>-->
						&nbsp;주말/공휴일
						<select name="d2time1">
							<?
							for ($i = 0; $i <= 24; $i++) {
								$j = $i;
								if (strlen($j) == 1) {
									$j = "0" . $i;
								}
								$time = $j . ":00";
								if ($j == 24) {
									$time = "23:59";
								}
								echo "<option value='{$time}'>{$time}</option>";
							}
							?>
						</select> ~
						<select name="d2time2">
							<?
							for ($i = 0; $i <= 24; $i++) {
								$j = $i;
								if (strlen($j) == 1) {
									$j = "0" . $i;
								}
								$time = $j . ":00";
								if ($j == 24) {
									$time = "23:59";
								}
								echo "<option value='{$time}'>{$time}</option>";
							}
							?>
						</select>
					</td>
				</tr>
				<tr>
					<th>예약대기시간[지점방문]</th>
					<td>
						<select name="wtime1">
							<?
							for ($i = 1; $i <= 48; $i++) {
								if ($i >= 1 && $i <= 12) {
									echo "<option value='{$i}'>{$i}</option>";
								} else if ($i > 12 && $i <= 24) {
									if ($i % 2 == 0) {
										echo "<option value='{$i}'>{$i}</option>";
									}
								} else if ($i > 24 && $i <= 48) {
									if ($i % 6 == 0) {
										echo "<option value='{$i}'>{$i}</option>";
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
							<?
							for ($i = 3; $i <= 48; $i++) {
								if ($i >= 3 && $i <= 12) {
									echo "<option value='{$i}'>{$i}</option>";
								} else if ($i > 12 && $i <= 24) {
									if ($i % 2 == 0) {
										echo "<option value='{$i}'>{$i}</option>";
									}
								} else if ($i > 24 && $i <= 48) {
									if ($i % 6 == 0) {
										echo "<option value='{$i}'>{$i}</option>";
									}
								}
							}
							?>
						</select>시간 이후
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
						<ul class="listLoca" style="margin-top:5px;" data-code="1"></ul>
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
						<ul class="listLoca" style="margin-top:5px;" data-code="2"></ul>
					</td>
				</tr>
				</tbody>
			</table>
		</div>
	</div>
	<div class="btn_wrap btn_center btn_bottom">
		<span class="greenBtn btn_submit" data-form="#regiform"><a href="javascript:">등록</a></span>
	</div>
</form>

<script src='<?=$config['daumPostcode'] ?>'></script>
<script type="text/javascript">
	function foch(f) {
		var re = check_form(f);
		if(re)	{
			var $affiliate = $('input[name="affiliate"]');
			var affiliate = $affiliate.val();
			if(affiliate.split(' ').length > 1) {
				alert('지점은 한 단어로 입력해야합니다. (~~역, ~~동 등)');
				$affiliate.focus();
				return false;
			}

			var exit = affiliate.match(/[0-9]+번/gi);
			if(exit) {
				alert('"' + exit[0] + '"은 입력할 수 없습니다.');
				$affiliate.focus();
				return false;
			}

			return confirm('등록 하시겠습니까?');
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
		;
	});
</script>