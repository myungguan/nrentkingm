<?php
function sel_query_all($table,$where) {
	$q = "select * from $table $where";
	$r = mysql_query($q);
	$row = mysql_fetch_array($r);

	return $row;
}
function sel_query($table,$val,$where) {
	$q = "select $val from $table $where";
	$r = mysql_query($q);
	$row = mysql_fetch_array($r);

	return $row;
}

function side_menu($code, $_sidemenu, $nolink = false) {
	parse_str($code, $out);

	$_li_html = '';
	$_side_html = '';
	$location = [];
	foreach ($_sidemenu as $key => $subdata) {
		$subtitle = $key;

		foreach ($subdata as $menudata) {

			parse_str("code={$menudata['code']}", $rout);

			$match = true;
			foreach ($rout as $k => $v) {
				if ($out[$k] != $v) {
					$match = false;
				}
			}
			if ($match) {
				$location[] = $subtitle;
				$location[] = $menudata['name'];
			} else if (is_array($menudata['group']) && in_array($out['code'], $menudata['group']) && !$nolink) {
				$location[] = $subtitle;
				$location[] = $menudata['name'];
			}
			$path = substr($_SERVER['REQUEST_URI'], 0, strpos($_SERVER['REQUEST_URI'], '?'));
			$url = "{$path}?code={$menudata['code']}";
			if (isset($menudata['url'])) {
				$url = $menudata['url'];
			}

			$target = '';
			if (isset($menudata['target'])) {
				$target = "target=\"{$menudata["target"]}\"";
			}

			if ($menudata['img1']) {
				$_li_html .= "<a href=\"$url\" $target><img src=\"./img/{$menudata['img1']}\" onmouseover=\"src='./img/{$menudata['img2']}'\" onmouseout=\"src='./img/{$menudata['img1']}'\"></a>";
			} else {
				$_li_html .= "<a href=\"$url\" $target>{$menudata['name']}</a>";
			}
		}

		$_side_html .= $_li_html;
	}

	return array($_side_html, $location);
}

function insert($table,$values) {
	global $connect;

	$count=count($values);
	if(!$count) return false;

	$i=1;
	$field = '';
	$value = '';
	foreach($values as $index => $key) {
		if($i > 1) {
			$field .= ', ';
			$value .= ', ';
		}

		$field .= $index;
		if($index=='passwd') {
			$value .= "password('".$key."')";
		} else {
			$value .= "'".$key."'";
		}

		$i++;
	}

	$query="INSERT INTO $table ($field) VALUES ($value)";	// 실제 쿼리 생성
	$r = mysql_query($query, $connect);
	return $r;
}

function update($table,$values,$where="") {
	global $connect;

	$count=count($values);
	if(!$count)return false;

	$i=1;
	$value = '';
	foreach($values as $index => $key) {
		if($i > 1) {
			$value .= ', ';
		}

		if($index=='passwd') {
			$value .= $index."=password('".$key."') ";
		} else {
			$value .= $index."='".$key."' ";
		}
		$i++;
	}

	$query="UPDATE $table SET $value ".$where;	// 실제 쿼리 생성
	$result=mysql_query($query, $connect);
	return $result;

}
function get_sido($code="",$n="") {

	if($n=='1')	{
		$ar_a = sel_query_all("area"," WHERE ac_name='$code'");
		$code = $ar_a['ac_code'];
	}

	$lencode = strlen($code);

	$q = "SELECT * FROM area";
	if($code!='')	{
		$q = $q . " WHERE LEFT(ac_code,$lencode)='$code' AND LENGTH(ac_code)='5' AND isuse='Y' ORDER BY idx ASC";
	}
	else	{
		$q = $q . " WHERE LENGTH(ac_code)='2' AND isuse='Y' order by orders asc";
	}

	$r = mysql_query($q);
	$indata = [];
	while($row = mysql_fetch_array($r))	{
		$indata[] = $row;
	}
	return $indata;
}
function get_time()	{
	
	$hour   = date("H");
	$minute = date("i");
    $select_time_options    = array();

    if ($minute < 30) {
		$hour++;
		$minute = "30";
    } else {
		$hour   = $hour + 2;
		$minute = "00";
	}

    if ($hour == 24) {
      $hour   = 0;
    } else if ($hour == 25) {
       $hour   = 1;
    }

    $hour   = (string)(strlen($hour) == 1 ? "0".$hour : $hour);
    $abletime   = $hour.$minute;

    for ($i = 0; $i < 24; $i++) {
       $hour   = cMakeFrontZero($i, 2);
       $noon   = $i < 12 ? "오전" : "오후";

       $select_time_options[]['d']  = '<option value="'.$hour.'00"' . ($abletime == $hour."00" ? ' selected="selected"' : '') . '>' . $noon . ' ' . $hour . ':00</option>';

       if ($i < 24) $select_time_options[]['d'] = '<option value="' . $hour . '30"' . ($abletime == $hour."30" ? ' selected="selected"' : '') . '>' . $noon . ' ' . $hour . ':30</option>';
   }
   return $select_time_options;
}
function cMakeFrontZero($value, $length) {
        $value  = trim((String)$value);
        $length = (int)$length;

        while (strlen($value) < $length) {
            $value = "0".$value;
        }

        return $value;
}
function paging_admin($page, $total_record, $num_per_page, $page_per_block, $qArr, $skin = null) {
	if ( !$skin ) {
		$url = str_replace('/old/admin', '', $_SERVER['PHP_SELF']);
		$skin['prev'] = "<a class='img' href=\"{$url}?[[QUERY_STRING]]\"><img src='/img/btn_prev01.gif'></a>";
		$skin['curpage'] = "<a href=\"#\" class='on'>[[PAGE]]</a>";
		$skin['paging'] = "<a href=\"{$url}?[[QUERY_STRING]]\">[[PAGE]]</a>";
		$skin['next'] = "<a class='img' href=\"{$url}?[[QUERY_STRING]]\"><img src='/img/btn_next01.gif'></a>";
	}

	$sRet = [];
	$total_page = ceil($total_record/$num_per_page);

	$total_block = ceil($total_page/$page_per_block);
	$block = ceil($page/$page_per_block);

	$first_page = ($block-1)*$page_per_block;
	$last_page = $block*$page_per_block;

	if($total_block <= $block) {
		$last_page = $total_page;	
	}
	
	if ($block > 1) {
		//이전
		$before_page = $first_page;
		$qArr['page'] = $before_page;
		$qstr = http_build_query($qArr);
		$sRet[] = str_replace("[[QUERY_STRING]]", $qstr, $skin['prev']);
	}
	for($direct_page = $first_page + 1; $direct_page <= $last_page; $direct_page++){
		//페이징
		if($page==$direct_page) {	
			$sRet[] = str_replace("[[PAGE]]", $direct_page, $skin['curpage']);
		} else {
			$qArr['page'] = $direct_page;
			$qstr = http_build_query($qArr);
			$sRet[] = str_replace(array("[[PAGE]]", "[[QUERY_STRING]]"), array($direct_page, $qstr), $skin['paging']);
		}
	}
	if ($block < $total_block) {
		//다음
		$daum_page = $last_page+1;
		$qArr['page'] = $daum_page;
		$qstr = http_build_query($qArr);
		$sRet[] = str_replace("[[QUERY_STRING]]", $qstr, $skin['next']);
	}

	if(is_array($sRet))
	{	return implode("\n", $sRet);	}
	else
	{	return "";	}
}
function uploadfile($userfile, $tmpname, $i, $savedir) {

	if ($userfile == '') return "";

	$ar_rx = array("JPEG","jpeg","JPG","jpg","gif","GIF","BMP","bmp","png","PNG","xls","XLS","csv","CSV","xlsx","XLSX","zip","ZIP");
	$filename = $userfile;
	$ex_filename = explode(".",$filename);
	$extension = $ex_filename[sizeof($ex_filename)-1];
	$filename = time() . "_" . $i. ".".$extension;
	$dest = $savedir.$filename;
	$cou = 1;
	
	if (!in_array($extension, $ar_rx)) {
		return false;
	}

	while (1) {
		if(file_exists($dest)) {
			$filename = time() . "_" . $i. "[$cou].".$extension;
			$dest = $savedir.$filename;
			$cou++;
		} else {
			break;	
		}
	}

	if (!copy($tmpname, $dest)) {
		echo "<script>alert('$tmpname $dest 업로드에 실패하였습니다.'); history.back(); </script>";
		exit;
	}

	if (!unlink($tmpname)) {
		echo "<script>alert('업로드에 실패하였습니다.'); history.back(); </script>";
		exit;
	}
	

	
	return $filename;
}
function uploadfile_mod($userfile,$tmpname,$i,$savedir,$last,$delfile) {
    // 2017.03.06 DonYoung - START
    $filename = $userfile;
	$ar_rx = array("JPEG","jpeg","JPG","jpg","gif","GIF","BMP","bmp","png","PNG","xls","XLS","csv","CSV","xlsx","XLSX","zip","ZIP","ico","ICO");
	if($userfile=='')
	{	
		if($delfile=='Y')
		{	@unlink($savedir.$filename);	return "";		}
		else
		{	return $last;	}
		
	}
	else
	{
		@unlink($savedir.$filename);
//		$filename = $userfile;
		$ex_filename = explode(".",$filename);
		$extension = $ex_filename[sizeof($ex_filename)-1];
		$filename = time() . "_" . $i. ".".$extension;
		$dest = $savedir.$filename;

		if(!in_array($extension,$ar_rx))
		{
			return false;
		}

		$cou = 1;
		while(1)
		{
			if(file_exists($dest))
			{
				$filename = time() . "_" . $i. "[$cou].".$extension;
				$dest = $savedir.$filename;
				$cou++;
			}
			else
			{	break;	}
		}

		if(!copy($tmpname,$dest))
		{
			echo "<script>alert('업로드에 실패하였습니다.'); history.back(); </script>";
			exit;
		}
		if(!unlink($tmpname))
		{
			echo "<script>alert('업로드에 실패하였습니다.'); history.back(); </script>";
			exit;
		}


		return $filename;
	}
}

//전체 메뉴 반환
function gmenu_info($valid = false) {
	global $admin_menu, $bizhost_custom_menu;
	
	if ( is_array($admin_menu) ) {
		if ( $valid ) {
			foreach ( $admin_menu as $k => $v ) {
				$_temp = explode(".", $k);
				$dir = $_temp[0];
				
				$side_menu_file = "./{$dir}/side_menu.php";
				if( is_file($side_menu_file) ) {
					unset($_sidemenu);
					include $side_menu_file;
					
					foreach ( $_sidemenu as $kk => $vv ) {
						foreach ( $vv as $kkk => $vvv ) {
							$q = "code=".$vvv['code'];
							parse_str($q, $out);
							unset($varstr);
							foreach ( $out as $kkkk => $vvvv ) {
								$varstr .= "$kkkk$vvvv";
							}
							
							$gmenu[$dir][$varstr]['name'] = $vvv['name'];
							$gmenu[$dir][$varstr]['code'] = $vvv['code'];
							
							if ( is_array($vvv['group']) ) {
								foreach ( $vvv['group'] as $vvvv ) {
									$gmenu[$dir]["code".$vvvv]['name'] = $vvv['name'];
									$gmenu[$dir]["code".$vvvv]['code'] = $vvv['code'];
								}
							}
						}
					}
				}
			}
			return $gmenu;
		} else {
			foreach ( $admin_menu as $k => $v ) {
				$_temp = explode(".", $k);
				$dir = $_temp[0];
				
				$side_menu_file = "./{$dir}/side_menu.php";
				if( is_file($side_menu_file) ) {
					unset($_sidemenu);
					include $side_menu_file;
					
					//커스텀 메뉴 추가
					if ( $bizhost_custom_menu[$k] ) {
						$_sidemenu = array_merge($_sidemenu, $bizhost_custom_menu[$k]);
					}

					$gmenu[$v] = $_sidemenu;
					$gmenudir[$v] = $dir;
				}
			}

			return array($gmenu, $gmenudir);
		}
	} else {
		return false;
	}
}
function kstrcut($str, $len, $suffix) {
	if ($len >= strlen($str)) return $str;

	$klen = $len - 1;

	while(ord($str[$klen]) & 0x80) $klen--;

	return substr($str, 0, $len - (($len + $klen + 1) % 2)) . $suffix;
}
function make_coupon($ar_coupon,$member_idx,$log_idx="") {
	$value['member_idx'] = $member_idx;
	$value['coupon_idx'] = $ar_coupon['idx'];
	$value['dt_create'] = date("Y-m-d H:i:s",time());
	
	$value['sdate'] = $ar_coupon['dt_use_start'];
	$value['edate'] = $ar_coupon['dt_use_end'];
//	$value['actype'] = $ar_coupon['actype'];
//	$value['account'] = $ar_coupon['account'];
//	$value['mtype'] = "M";
	$value['memo'] = $_POST['memo'];
//	$value['canuseac'] = $ar_coupon['canuseac'];
	insert("member_coupons",$value);
	echo mysql_error();
	unset($value);
}
function get_marketdan($dan, $extend_payment_idx=null, $extendyn='N')	{
	switch ($dan){
		case 1 :
			if($extendyn == 'Y')
				echo "  <span class='btn_white_xs btn_white'><a>연장확정</a></span>  ";
			else
				echo "  <span class='btn_white_xs btn_white'><a>예약확정</a></span>  ";
			break;
		case 2 : echo "  <span class='btn_white_xs btn_orange'><a>대여중</a></span>  "; break;
		case 3 :
			if($extend_payment_idx)
				echo "  <span class='btn_white_xs btn_yellow'><a>연장완료</a></span>  ";
			else
				echo "  <span class='btn_white_xs btn_yellow'><a>반납완료</a></span>  ";
			break;
		case 4 : echo "  <span class='btn_white_xs btn_red'><a>취소요청</a></span>  "; break;
		case 5 : echo "  <span class='btn_white_xs btn_blue'><a>취소완료</a></span>  "; break;
		case 'S': echo "  <span class='btn_white_xs btn_yellow'><a>대기중</a></span>  "; break;
		case 'O': echo "  <span class='btn_white_xs btn_orange'><a>대여중</a></span>  "; break;
	}
}

function get_carattr($n) {

	$q = "SELECT * FROM codes WHERE ttype='$n' AND dt_delete IS NULL ORDER BY idx ASC";
	$r = mysql_query($q);
	while($row = mysql_fetch_array($r))	{
		$indata[] = $row;
	}

	return $indata;
}

function phone_number_format($phone) {
	$phone = str_replace('-', '', $phone);
	if(preg_match('/^(02)(\d{3,4})(\d{4})$/', $phone, $p)) {
		$phone = $p[1].'-'.$p[2].'-'.$p[3];
	}else if(preg_match('/^(\d{3})(\d{3,4})(\d{4})$/', $phone, $p)) {
		$phone = $p[1].'-'.$p[2].'-'.$p[3];
	} else if(preg_match('/^(\d{4})(\d{4})$/', $phone, $p)) {
		$phone = $p[1].'-'.$p[2];
	}
	return $phone;
}

/**
 * 임의 휴일(주말요금적용) 설정된 날짜 가져옴
 */
function getCustomHoilyday($sdate, $edate) {
    $sdate = date('Y-m-d',strtotime($sdate));
    $edate = date('Y-m-d',strtotime($edate));
    $q = "SELECT date FROM hoilyday WHERE date between '$sdate' and '$edate' ORDER BY date ASC";
    $r = mysql_query($q);
    $hoilydayArr = array();
    while($row = mysql_fetch_array($r)){
        if(date('N', strtotime($row['date'])) < 6){
            $hoilydayArr[] = $row['date'];
        }
    }
    return $hoilydayArr;
}


/**
 * 기간 동안 날짜 계산
 *
 * @param $sdate string 시작 날짜 시간 '2016-10-29 10:00'
 * @param $edate string 종료 날짜 시간 '2016-10-29 10:00'
 *
 * @return array (months, days)
 * months	전체 시간
 * days		주중 시간
 */
function calcDays($sdate, $edate) {
    $s = strtotime($sdate);
    $e = strtotime($edate);

    $m = strtotime('+1 months', $s);
    if(date('d', $s) != date('d', $m)) {
        $m = strtotime('-'.date('d', $m).' days', $m);
    }

    $months = 0;
    $days = 0;
    if(date('Y-m-d', $e) >= date('Y-m-d', $m)) {	//한달 이상

        $months = 0;
        do {
            $months++;
            $m = strtotime('+'.$months.' months', $s);
            if(date('d', $s) != date('d', $m)) {
                $m = strtotime('-'.date('d', $m).' days', $m);
            }
        } while(date('Y-m-d', $m) <= date('Y-m-d', $e));

        $months--;

        $m = strtotime('+'.$months.' months', $s);
        if(date('d', $s) != date('d', $m)) {
            $m = strtotime('-'.date('d', $m).' days', $m);
        }
        $days = ceil(($e - $m) / 3600 / 24);

    } else {
        $days = ceil(($e-$s) / 3600 / 24);
    }

    return array('months' => $months, 'days' => $days);
}

/**
 * 기간 동안 시간 계산
 * 금,토,일, admin에서 지정된 휴일 = 주말
 * 월,화,수,목 = 평일
 *
 * @param $sdate string 시작 날짜 시간 '2016-10-29 10:00'
 * @param $edate string 종료 날짜 시간 '2016-10-29 10:00'
 *
 * @return array (rent_hour, total_hour, week_hour, holi_hour, remain_hour)
 * rent_hour	렌트시간 = total_hour + remain_hour
 * total_hour	가격 계산 시간 = week_hour + holi_hour
 * week_hour	주중 시간
 * holi_hour	주말 시간
 * remain_hour	24단위 남은 시간
 */
function calcHours($sdate, $edate) {
    $ar_s = explode(" ",$sdate);
    $ar_sd = explode("-",$ar_s[0]);
    $ar_st = explode(":",$ar_s[1]);

    $ar_e = explode(" ",$edate);
    $ar_ed = explode("-",$ar_e[0]);
    $ar_et = explode(":",$ar_e[1]);

    $ssec = mktime($ar_st[0], $ar_st[1], 0, $ar_sd[1], $ar_sd[2], $ar_sd[0]);
    $esec = mktime($ar_et[0], $ar_et[1], 0, $ar_ed[1], $ar_ed[2], $ar_ed[0]);
    $rent_hour = $total_hour = ($esec - $ssec) / 3600;
    $holi_hour = 0;
    $remain_hour_holi = 0;
    $current = $ssec;
    $remain_hour = fmod($total_hour, 24);
    if($remain_hour >= 12){  //남는시간이 12시간 이상이면 하루로 계산
        $total_hour += 24 - $remain_hour;
        $esec += (24 - $remain_hour) * 3600;
        $remain_hour = 0;
    }

    $holiydayArr = getCustomHoilyday($sdate, $edate);

    while($current <= $esec) {   //휴일(주말)시간 계산 - 예약 시작~종료까지 1시간 단위 loop 시간계산
        if(date('N',$current-1) > 4 || in_array(date('Y-m-d',$current-1), $holiydayArr)) {  //주말이거나 설정되있는 휴일일 경우
            $holi_hour++;
            if($current-1 > $esec - (3600 * $remain_hour)){
                $remain_hour_holi++;
            }
        }
        $current += 3600;
    }

    $week_hour = $total_hour - $holi_hour;
    $remain_hour_week = $remain_hour - $remain_hour_holi;

    $week_hour -= $remain_hour_week;
    $holi_hour -= $remain_hour_holi;

    return array('rent_hour' => $rent_hour, 'total_hour' => $total_hour, 'week_hour' => $week_hour, 'holi_hour' => $holi_hour, 'remain_hour' => $remain_hour);

//    $ar_s = explode(" ",$sdate);
//    $ar_sd = explode("-",$ar_s[0]);
//    $ar_st = explode(":",$ar_s[1]);
//
//    $ar_e = explode(" ",$edate);
//    $ar_ed = explode("-",$ar_e[0]);
//    $ar_et = explode(":",$ar_e[1]);
//
//    $ssec = mktime($ar_st[0], $ar_st[1], 0, $ar_sd[1], $ar_sd[2], $ar_sd[0]);
//    $esec = mktime($ar_et[0], $ar_et[1], 0, $ar_ed[1], $ar_ed[2], $ar_ed[0]);
//    $rent_hour = $total_hour = ($esec - $ssec) / 3600;
//    $holi_hour = 0;
//    $current = $ssec;
//    $remain_hour = 0;
//
//    $h = date('H',$current);
//    if(fmod($total_hour, 24) >= 12) {
//        $hour = 24 - fmod($total_hour, 24);
//        $esec += $hour * 3600;
//    } else if(fmod($total_hour,  24) > 0 && fmod($total_hour,  24) < 12) {
//        $remain_hour = fmod($total_hour,  24);
//        $esec -= $remain_hour * 3600;
//    }
//    $total_hour = ($esec - $ssec) / 3600;
//
//    if($h < 12) {
//        $h = 12 - $h;
//        $current = $current + $h * 3600;
//        if(date('N',$current) > 5) {
//            $holi_hour += $h;
//        }
//    } else if($h < 24 && $h > 12) {
//        $h = 24 - $h;
//        $current = $current + $h * 3600;
//        if(date('N',$current) > 5) {
//            $holi_hour += $h;
//        }
//    }
//    while($current < $esec) {
//        $diff = ($esec - $current) / 3600;
//        if($diff >= 12) {
//            $diff = 12;
//        }
//        $t = $current + 12 * 3600;
//        if(date('N', $t) > 5) {
//            $holi_hour += $diff;
//        }
//
//        $current += 12 * 3600;
//    }
//    $week_hour = $total_hour - $holi_hour;
//
//    return array('rent_hour' => $rent_hour, 'total_hour' => $total_hour, 'week_hour' => $week_hour, 'holi_hour' => $holi_hour, 'remain_hour' => $remain_hour);
}

/**
 * @param $date string 날짜 시간 '2016-10-29 10:00'
 *
 * @return integer	time
 */
function getTime($date) {
	$date = preg_split('/[\- :]/', $date);

	return mktime($date[3], $date[4], 0, $date[1], $date[2], $date[0]);
}

/**
 * 아이피로 주소 찾기
 *
 * @param $ip string 아이피
 *
 * @return string 주소
 */
function getAddressByIp($ip) {
	$address = null;
	$url = 'https://whois.kisa.or.kr/kor/whois/whois.jsc?query='.$ip;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLINFO_HEADER_OUT, true);
	$response = curl_exec($ch);
	$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close($ch);
	if($status_code == 200) {
		$response = explode("\n", $response);
		if(count($response) > 0) {
			for($i = 0; $i < count($response); $i++) {
				$pos = strpos($response[$i],'주소');
				if($pos !== false && $pos == 0) {

				} else {
					array_splice($response, $i, 1);
					$i--;
				}
			}

			if(count($response) > 0) {
				$address = preg_replace('/주소\s*:\s*/i', '', $response[count($response) - 1]);

				return $address;
			}
		}
	}

	return $address;
}

/**
 * 기본 차량 검색 파라미터
 *
 * @return array (ptype, mindate, sdate, edate, grade_idx, loca1, loca1str)
 * ptype	대여방법 1:배달대여, 2:지점방문
 * mindate	최소 시작날짜
 * sdate	시작날짜
 * edate	종료날짜
 * grade	차종
 * loca1	검색지역 코드
 * loca1str	검색지역 문자열
 */
function getDefaultSearch() {
	$defaultSearch = $_COOKIE['defaultSearch'];
	$ptype = '1';
	$sdate = '';
	$edate = '';
	$grade = '';
	$addr = '';

	require_once("Mobile_Detect.php");
	$detect = new Mobile_Detect();
	$ismobile = $detect->isMobile();

	if(isset($defaultSearch)) {
		$defaultSearch = explode('|', $defaultSearch);
		$ptype = $defaultSearch[0];
		$sdate = $defaultSearch[1];
		$edate = $defaultSearch[2];
		$grade = $defaultSearch[3];
		$addr = $defaultSearch[4];
	} else if(!$ismobile) {
		$address = getAddressByIp(($_SERVER['HTTP_CF_CONNECTING_IP'] ? $_SERVER['HTTP_CF_CONNECTING_IP'] : $_SERVER['REMOTE_ADDR']));

		//지역 설정
		if($address) {
			$addr = $address;
			$address = explode(' ', $address);
			$address = $address[0];
		}
	}

	$stime = strtotime($sdate);
	$etime = strtotime($edate);

	$now = $mindate = time() + 3600 * 3;
	$min = date('i', $now);
	if($min > 0 && $min <30) {
		$now = $now + (30 - $min) * 60;
	} else if($min > 30) {
		$now = $now + (60 - $min) * 60;
	}
	$hour = date('H', $now);

	$min = date('i', $now);
	if($hour < 10) {
		$now += (10 - $hour) * 3600;
		if($min == 30) {
			$now -= 30 * 60;
		}
	} else if($hour > 19) {
		$now += (24 - $hour) * 3600 + 10 * 3600;
		if($min == 30) {
			$now -= 30 * 60;
		}
	}

	if($now > $stime) {
		$etime = $now + $etime - $stime;
		$stime = $now;
	}

	setcookie('defaultSearch', $ptype.'|'.$sdate.'|'.$edate.'|'.$grade.'|'.$addr, 0, '/', 'rentking.co.kr');

	return array('ptype' => $ptype, 'mindate' => date('Y-m-d H:i', $mindate), 'sdate' => date('Y-m-d H:i', $stime), 'edate' => date('Y-m-d H:i', $etime), 'grade_idx' => $grade, 'addr' => $addr);
}

/**
 * 파일을 업로드 하고 file table에 정보를 저장한다.
 *
 * @param string	$field			input[file] 필드네임
 * @param string	$article_type	게시물 타입
 * @param int		$article_idx	게시물 번호
 * @param string	$article_info	게시물 정보
 * @param int		$original_idx	기존 파일 (0이 아닐경우 article_idx를 0으로 변경)
 *
 * @return array|null	file 정보
 */
function fileUpload($field, $article_type, $article_idx, $article_info = '', $original_idx = -1) {
	global $config;

	$files = $_FILES[$field];
	if($files['error'] == 0 && $files['name'] != '') {
		$name = $files['name'];
		$extension = pathinfo($name, PATHINFO_EXTENSION);
		$uploadDir = "/$article_type/".date('Ym')."/";
		$filepath = $uploadDir.time();
		$file = [];

		if(!file_exists($config['uploadPath'].$uploadDir)) {
			mkdir($config['uploadPath'].$uploadDir, 0777, true);
		}
		$count = 0;
		$path = $filepath.".$extension";
		if(file_exists($config['uploadPath'].$path)) {
			$count++;
			$path = $filepath."_$count.$extension";
		}

		$file['name'] = $name;
		$file['path'] = $path;
		$file['type'] = $files['type'];
		$file['size'] = $files['size'];
		if(strpos($files['type'], 'image') == 0) {
			$size = getimagesize($files['tmp_name']);
			$file['width'] = $size[0];
			$file['height'] = $size[1];
		}

		move_uploaded_file($files['tmp_name'], $config['uploadPath'].$path);

		if($original_idx != -1)
			mysql_query("UPDATE files SET article_idx=-1 WHERE idx = $original_idx");

		$query = "INSERT INTO files (article_type, article_idx, article_info, name, path, type, size";
		if(isset($file['width'])) {
			$query .= ", width, height";
		}
		$query .= ", dt_create) VALUES ('$article_type', $article_idx, '$article_info', '{$file['name']}', '{$file['path']}', '{$file['type']}', {$file['size']}";
		if(isset($file['width'])) {
			$query .= ", {$file['width']}, {$file['height']}";
		}
		$query .= ", NOW())";
		if(!mysql_query($query))
			return null;

		$file['idx'] = mysql_insert_id();

		return $file;
	}

	return null;
}

/**
 * 로그인 세션 세팅
 * @param      $memberIdx		int		member.idx
 * @param      $memberId		string	member.id
 * @param      $memberName		string	member.name
 * @param      $memberGrade		int		member.memgrade
 * @param null $adminGrade		int		auth_admin.grade
 * @param null $rentshopIdx		int		rentshop.idx
 */
function setLoginSession($memberIdx, $memberId, $memberName, $memberGrade, $adminGrade=NULL, $rentshopIdx=NULL) {
	$_SESSION['sid'] = session_id();
	$_SESSION['member_idx'] = $memberIdx;
	$_SESSION['member_id'] = $memberId;
	$_SESSION['member_name'] = $memberName;
	$_SESSION['member_grade'] = $memberGrade;
	$_SESSION['admin_grade'] = $adminGrade;
	$_SESSION['rentshop_idx'] = $rentshopIdx;
}

/**
 * 차량검색 정보 얻기
 *
 * @param      $memberType		int		멤버타입 (1: 일반, 2: 딜러)
 * @param      $sdate			string	대여일시 (2016-10-29 10:30)
 * @param      $edate			string	반납일시 (2016-10-20 10:30)
 * @param      $retype			int		1: 단기, 2: 장기
 * @param      $pickupAddr		string	픽업위치
 * @param      $returnAddr		string	반납위치
 * @param null $latLng			array	검색위치
 * @param null $carIdx			string	검색할 차량
 * @param null $extendOrder		array	연장할 예약
 * @param null $rentshopList	string	검색할 멤버사
 * @param null $grade			int		차종
 * @param null $ptype			string	대여방법
 * @param null $insu			string	보험
 * @param null $model			string	모델
 * @param null $fuel			string	연료
 * @param null $option			string	옵션
 * @param null $outdate			string	출고일
 * @param null $color			string	색상
 * @param null $company			string	제조사
 * @param null $nosmoke			string	금연여부
 *
 * @return array (query)
 * query: 쿼리
 * daysInfo: 날짜 정보
 * hoursInfo: 시간 정보
 */
function getSearchInfo(
	$memberType,
	$sdate,
	$edate,
	$retype,
	$pickupAddr,
	$returnAddr,
	$latLng = null,
	$carIdx = null,
	$extendOrder = null,
	$rentshopList = null,
	$grade = null,
	$ptype = null,
	$insu = null,
	$model = null,
	$fuel = null,
	$option = null,
	$outdate = null,
	$color = null,
	$company = null,
	$nosmoke = null) {

	$stime = strtotime($sdate);
	$etime = strtotime($edate);

	$sdateShort = substr($sdate,0,10);
	$edateShort = substr($edate,0,10);

	$day1 = date("N",$stime);
	$day2 = date("N",$etime);
	$ststr = date("H:i",$stime);
	$etstr = date("H:i",$etime);

	$daysInfo = calcDays($sdate, $edate);

	$hoursInfo = calcHours($sdate, $edate);
//	print_r($hoursInfo);
	$rent_hour = $hoursInfo['rent_hour'];
	$total_hour = $hoursInfo['total_hour'];
	$week_hour = $hoursInfo['week_hour'];
	$holi_hour = $hoursInfo['holi_hour'];
	$remain_hour = $hoursInfo['remain_hour'];

	$charge = 'vp.price';
	$precharge = '0';
	$delcharge = '0';
	$moncharge = '0';
	$leftcharge = '0';
	$charge_where = '';
	$chargetype = '1';
	$pricetype = 1;
	$price_insu1 = 0;
	$price_insu2 = 0;
	$price_insu3 = 0;


	if($retype == '1') {
		$extracharge = "
			(CASE
				WHEN vpe.idx IS NOT NULL AND vpe.type = 1 THEN vpe.price
				WHEN vpe.idx IS NOT NULL AND vpe.type = 2 THEN -vpe.price
				ELSE 0
			END)
		";

        	$charge = "((vp.price + $extracharge) * $week_hour / 24 + (vp.price_holiday + $extracharge) * $holi_hour / 24";

		if($remain_hour > 0) {
            $charge .= "+ (vp.price + $extracharge) * $remain_hour / vp.price_hour";
//            $charge .= "+ (vp.price / vp.price_hour * $remain_hour) + $extracharge";
		}
		$charge .= ')';
		if($rent_hour > 144) {
			$chargetype = '4';
		} else if($rent_hour> 96) {
			$chargetype = '3';
		} else if($rent_hour > 48) {
			$chargetype = '2';
		}

		if($chargetype > 1)
			$charge .= '* vp.price_discount'.$chargetype;

		$delcharge = 'vp.price_del'.$chargetype;

		$pricetype = "CASE WHEN vpl.price_longterm1 > 0 AND vpl.price_longterm_insu_exem > 0 AND vpl.price_longterm1 < ($charge) THEN 2 ELSE 1 END";
		$charge = "CASE WHEN vpl.price_longterm1 > 0 AND vpl.price_longterm_insu_exem > 0 AND vpl.price_longterm1 < ($charge) THEN vpl.price_longterm1 ELSE $charge END";
		$charge_where = " AND (vp.price_insu0_check = 'Y' OR vp.price_insu1_check = 'Y' OR vp.price_insu2_check = 'Y' OR vp.price_insu3_check = 'Y') AND vp.price_hour > 0 AND vp.price > 0 AND vp.price_holiday > 0 AND vp.price_discount2 > 0 AND vp.price_discount3 > 0 AND vp.price_discount4 > 0";

		$days = ceil($rent_hour / 24);
		$price_insu1 = "CASE WHEN vp.price_insu1_check = 'Y' THEN vp.price_insu1_".$chargetype."*$days ELSE 0 END";
		$price_insu2 = "CASE WHEN vp.price_insu2_check = 'Y' THEN vp.price_insu2_".$chargetype."*$days ELSE 0 END";
		$price_insu3 = "CASE WHEN vp.price_insu3_check = 'Y' THEN vp.price_insu3_".$chargetype."*$days ELSE 0 END";


//		if($memberType == '2') {
//			$charge = "($charge) * vp.price_net";
//			$charge_where .= " AND vp.price_net > 0";
//		}
	} else {
		$pricetype = 2;

		$months = $daysInfo['months'];
		$days = $daysInfo['days'];

		$moncharge = "vpl.price_longterm1";
		$charge = "vpl.price_longterm1 * $months + vpl.price_longterm1 * $days / 30";
		$precharge = "vpl.price_longterm_deposit1";
		if($months >= 12) {
			$moncharge = "vpl.price_longterm3";
			$charge = "vpl.price_longterm3 * $months + vpl.price_longterm3 * $days / 30";
			$precharge = "vpl.price_longterm_deposit3";
		} else if ($months >= 6) {
			$moncharge = "vpl.price_longterm2";
			$charge = "vpl.price_longterm2 * $months + vpl.price_longterm2 * $days / 30";
			$precharge = "vpl.price_longterm_deposit2";
		}

		$charge_where = ' AND vpl.price_longterm_insu_exem > 0';
		if($months >= 1 && $months < 6) {
			$charge_where .= ' AND vpl.price_longterm1 > 0';
		}
		if($months >= 6 && $months < 12) {
			$charge_where .= ' AND vpl.price_longterm2 > 0';
		}
		if($months >= 12) {
			$charge_where .= ' AND vpl.price_longterm3 > 0';
		}

//		if($memberType == '2') {
//			$charge = "($charge) * vpl.price_longterm_net";
//			$charge_where .= " AND vpl.price_longterm_net > 0";
//		}
	}

	$charge = "FLOOR(ROUND($charge) / 100) * 100";
	$delcharge = "FLOOR(ROUND($delcharge) / 100) * 100";

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

	$deliveryQuery = " r.idx IN (
		SELECT distinct(rl.rentshop_idx) 
		FROM
			rentshop_loca rl
			LEFT JOIN area a1 ON a1.ac_code = rl.loca1
			LEFT JOIN area a2 ON a2.ac_code = rl.loca2
		WHERE
			r.wtime2 <= TIMESTAMPDIFF(HOUR, NOW(), '$sdate:00')
			AND rl.stype='$retype'
			$deliveryTimeQuery
			$pickupQuery
			$returnQuery
		)";

	$delavail = "
		CASE
			WHEN ($deliveryQuery) THEN 1
			ELSE 0
		END
	";

	$total_charge = "(
		CASE 
			WHEN ($pricetype) = 1 THEN
				$charge + 
				LEAST(
					CASE WHEN vp.price_insu0_check = 'Y' THEN 0 ELSE ~0 >> 32 END,
					CASE WHEN vp.price_insu1_check = 'Y' THEN $price_insu1 ELSE ~0 >> 32 END,
					CASE WHEN vp.price_insu2_check = 'Y' THEN $price_insu2 ELSE ~0 >> 32 END,
					CASE WHEN vp.price_insu3_check = 'Y' THEN $price_insu3 ELSE ~0 >> 32 END
				)
			ELSE
				$charge
		END
		)";
	if($retype == 2) {
		$leftcharge = "$charge % $moncharge";
		$total_charge = $charge;
	}

	$chargehour = $total_hour + $remain_hour;

	$distance = 0;
	if($latLng) {
		$distance = "( 6371000 * acos( cos( radians({$latLng[0]}) )
		* cos( radians( X(r.latlng) ) )
		* cos( radians( Y(r.latlng) ) - radians({$latLng[1]}) )
		+ sin( radians({$latLng[0]}) )
		* sin( radians( X(r.latlng) ) ) ) )";
	}
//	$searchTime = " AND '$sdate:00' >= NOW() + INTERVAL r.wtime1 HOUR ";
	if($carIdx) {
		$searchTime = '';
	}

	$query = "
	SELECT
		[FIELD]
	FROM (
	SELECT
		vs.company_idx,
		c_company.sname company_name,
		vs.name modelname,
		c_fuel.sname fuel_sname,
		vs.grade_idx,
		default_image.path default_image_path,
		UNIX_TIMESTAMP(default_image.dt_create) default_image_timestamp,
		car_image.path car_image_path,
		UNIX_TIMESTAMP(car_image.dt_create) car_image_timestamp,
		vs.memcou passengers,
		r.name rentshop_name,
		r.affiliate,
		r.addr1 rentshop_addr1,
		r.addr2 rentshop_addr2,
		r.dphone2 rentshop_dphone2,
		r.dcp2 rentshop_dcp2,
		r.per1 rentshop_per1,
		$delavail delavail,
		$total_charge total_charge,
		$charge charge,
		$precharge precharge,
		$delcharge delcharge,
		$moncharge moncharge,
		$leftcharge leftcharge,
		$chargetype chargetype,
		$chargehour chargehour,
		$rent_hour renthour,
		$pricetype pricetype,
		$price_insu1 price_insu1,
		$price_insu2 price_insu2,
		$price_insu3 price_insu3,
		$distance distance,
		r.s1time1,
		r.s1time2,
		r.s2time1,
		r.s2time2,
		r.wtime,
		r.wtime1,
		r.wtime2,
		v.*,
		vp.price_insu0_check,
		vp.price_insu1_check,
		vp.price_insu1_1,
		vp.price_insu1_2,
		vp.price_insu1_3,
		vp.price_insu1_4,
		vp.price_insu1_exem,
		vp.price_insu1_limit,
		vp.price_insu2_check,
		vp.price_insu2_1,
		vp.price_insu2_2,
		vp.price_insu2_3,
		vp.price_insu2_4,
		vp.price_insu2_exem,
		vp.price_insu2_limit,
		vp.price_insu3_check,
		vp.price_insu3_1,
		vp.price_insu3_2,
		vp.price_insu3_3,
		vp.price_insu3_4,
		vp.price_insu3_exem,
		vp.price_insu3_limit,
		vpl.price_longterm_insu_exem,
		vpl.price_longterm_insu_limit,
		vpl.distance_limit,
		vpl.distance_additional_price,
		(SELECT GROUP_CONCAT(CASE WHEN op_data = '있음' THEN op_name ELSE op_data END SEPARATOR ' / ') FROM vehicle_opt WHERE vehicle_idx = v.idx AND op_data <> '') opt,
		COUNT(1) count_car
	FROM vehicles v
        LEFT JOIN vehicle_models vs ON v.model_idx=vs.idx
        LEFT JOIN files default_image ON default_image.article_type = 'car' AND default_image.article_idx = vs.idx AND default_image.article_info = 'default'
        LEFT JOIN files car_image ON car_image.article_type = 'car' AND car_image.article_idx = vs.idx AND car_image.article_info = v.color
        LEFT JOIN codes c_company ON vs.company_idx = c_company.idx
        LEFT JOIN codes c_fuel ON v.fuel_idx = c_fuel.idx
        LEFT JOIN vehicle_price vp ON v.price_idx = vp.idx
        LEFT JOIN vehicle_price_longterm vpl ON v.price_longterm_idx = vpl.idx
        LEFT JOIN vehicle_price_extra vpe ON v.price_extra_idx = vpe.idx 
        -- AND ('$sdateShort' BETWEEN vpe.dt_start AND vpe.dt_end OR '$edateShort' BETWEEN vpe.dt_start AND vpe.dt_end OR ('$sdateShort' < vpe.dt_start AND '$edateShort' > vpe.dt_end) ) 
        AND (
            ('$sdateShort' BETWEEN vpe.dt_start AND vpe.dt_end OR '$edateShort' BETWEEN vpe.dt_start AND vpe.dt_end OR ('$sdateShort' < vpe.dt_start AND '$edateShort' > vpe.dt_end) )
            OR ('$sdateShort' BETWEEN vpe.dt_start2 AND vpe.dt_end2 OR '$edateShort' BETWEEN vpe.dt_start2 AND vpe.dt_end2 OR ('$sdateShort' < vpe.dt_start2 AND '$edateShort' > vpe.dt_end2) )
            OR ('$sdateShort' BETWEEN vpe.dt_start3 AND vpe.dt_end3 OR '$edateShort' BETWEEN vpe.dt_start3 AND vpe.dt_end3 OR ('$sdateShort' < vpe.dt_start3 AND '$edateShort' > vpe.dt_end3) )
        )
		LEFT JOIN rentshop r ON v.rentshop_idx = r.idx
		LEFT JOIN vehicle_off vo ON v.idx = vo.vehicle_idx AND ('$sdateShort' BETWEEN vo.offsdate AND vo.offedate OR '$edateShort' BETWEEN vo.offsdate AND vo.offedate OR ('$sdateShort' < vo.offsdate AND '$edateShort' > vo.offedate) )
		LEFT JOIN vehicle_opt vop1 ON v.idx = vop1.vehicle_idx AND vop1.op_name = '네비게이션'
		LEFT JOIN vehicle_opt vop2 ON v.idx = vop2.vehicle_idx AND vop2.op_name = '블랙박스'
		LEFT JOIN vehicle_opt vop3 ON v.idx = vop3.vehicle_idx AND vop3.op_name = '후방센서'
		LEFT JOIN vehicle_opt vop4 ON v.idx = vop4.vehicle_idx AND vop4.op_name = '후방카메라'
		LEFT JOIN vehicle_opt vop5 ON v.idx = vop5.vehicle_idx AND vop5.op_name = '스마트키'
		LEFT JOIN vehicle_opt vop6 ON v.idx = vop6.vehicle_idx AND vop6.op_name = '블루투스'
		LEFT JOIN vehicle_opt vop7 ON v.idx = vop7.vehicle_idx AND vop7.op_name = '천장'
		LEFT JOIN vehicle_opt vop8 ON v.idx = vop8.vehicle_idx AND vop8.op_name = '하이패스'
	WHERE
		v.dt_delete IS NULL
		AND v.onsale='Y'
		" . ($rentshopList ? "AND r.idx IN ($rentshopList)" : "") . "
		" . ($carIdx ? "AND v.idx IN ($carIdx)" : "") . "
		$charge_where
		AND vo.idx IS NULL
		$searchTime
		AND v.idx NOT IN (
			SELECT
				distinct(mt.vehicle_idx)
			FROM payments m
				LEFT JOIN reservation mt ON m.reservation_idx = mt.idx 
				LEFT JOIN vehicles v1 ON mt.vehicle_idx = v1.idx
				LEFT JOIN rentshop r1 ON v1.rentshop_idx = r1.idx
			WHERE
				m.dan IN ('1','2','4')
				" . ($extendOrder ? "AND m.idx <> {$extendOrder['idx']}" : "") . "
				" . ($extendOrder ? "AND v1.idx = $carIdx" : "") . "
				AND (
					'$sdate:00' BETWEEN mt.sdate + INTERVAL " . ($extendOrder ? "0" : "-r1.wtime") . " HOUR AND mt.edate + INTERVAL " . ($extendOrder ? "0" : "r1.wtime") . " HOUR
					OR '$edate:00' BETWEEN mt.sdate + INTERVAL " . ($extendOrder ? "0" : "-r1.wtime") . " HOUR AND mt.edate + INTERVAL " . ($extendOrder ? "0" : "r1.wtime") . " HOUR
					OR ('$sdate:00' < mt.sdate + INTERVAL " . ($extendOrder ? "0" : "-r1.wtime") . " HOUR AND '$edate:00' > mt.edate +INTERVAL " . ($extendOrder ? "0" : "r1.wtime") . " HOUR)
				)
			)
		AND v.idx NOT IN (
			SELECT
				distinct(rd.vehicle_idx)
			FROM reservation_direct rd
				LEFT JOIN vehicles v1 ON rd.vehicle_idx = v1.idx
				LEFT JOIN rentshop r1 ON v1.rentshop_idx = r1.idx
			WHERE
				rd.dan IN ('1','2')
				AND (
					'$sdate:00' BETWEEN rd.sdate + INTERVAL " . ($extendOrder ? "0" : "-r1.wtime") . " HOUR AND rd.edate + INTERVAL " . ($extendOrder ? "0" : "r1.wtime") . " HOUR
					OR '$edate:00' BETWEEN rd.sdate + INTERVAL " . ($extendOrder ? "0" : "-r1.wtime") . " HOUR AND rd.edate + INTERVAL " . ($extendOrder ? "0" : "r1.wtime") . " HOUR
					OR ('$sdate:00' < rd.sdate + INTERVAL " . ($extendOrder ? "0" : "-r1.wtime") . " HOUR AND '$edate:00' > rd.edate +INTERVAL " . ($extendOrder ? "0" : "r1.wtime") . " HOUR)
				)
			)
		";

	//차종
	if($grade) {
		$gradeIdx = explode('|', $grade);
		$gradeList = '';
		for($i = 0; $i < count($gradeIdx); $i++) {
			$f = trim($gradeIdx[$i]);
			if(strlen($f) > 0) {
				if(strlen($gradeList) > 0) $gradeList .= ',';
				$gradeList .= $f;
			}
		}
		if(strlen($gradeList) > 0) {
			$query .= " AND vs.grade_idx IN ($gradeList)";
		}
	}
	//금연차
	if($nosmoke == 'Y') {
		$query .= " AND v.nosmoke='Y'";
	}
	//연료
	if($fuel) {
		$fuelIdx = explode('|', $fuel);
		$fuelList = '';
		for($i = 0; $i < count($fuelIdx); $i++) {
			$f = trim($fuelIdx[$i]);
			if(strlen($f) > 0) {
				if(strlen($fuelList) > 0) $fuelList .= ',';
				$fuelList .= $f;
			}
		}
		if(strlen($fuelList) > 0) {
			$query .= " AND v.fuel_idx IN ($fuelList)";
		}
	}
	//차량옵션
	if($option) {
		$optionField = explode('|', $option);
		$optionCount = 0;
		$optionQuery = '';
		for($i = 0; $i < count($optionField); $i++) {
			$op = trim($optionField[$i]);
			if(strlen($op) > 0) {
				if($optionCount > 0) {
					$optionQuery .= ' AND ';
				}

				$optionQuery .= "({$op}.op_data IS NOT NULL AND {$op}.op_data <> '')";
				$optionCount++;
			}
		}
		if(strlen($optionQuery) > 0) {
			$query .= " AND ($optionQuery)";
		}
	}
	//출고일
	if($outdate) {
		$outdate = explode('|', $outdate);
		$optionCount = 0;
		$outdateQuery = '';
		for($i = 0; $i < count($outdate); $i++) {
			$o = str_replace('~', '', trim($outdate[$i]));
			if(strlen($o) > 0) {
				if($optionCount > 0) {
					$outdateQuery .= ' OR ';
				}
				if(strpos($outdate[$i], '~') === 0) {
					$outdateQuery .= "LEFT(v.outdate, 4) <= '$o%'";
				} else {
					$outdateQuery .= "v.outdate LIKE '$o%'";
				}
				$optionCount++;
			}
		}

		if(strlen($outdateQuery) > 0) {
			$query .= " AND ($outdateQuery)";
		}
	}
	//색상
	if($color) {
		$q = '';

		$color = explode('|', $color);
		$colorCount = 0;
		for($i = 0; $i < count($color); $i++) {
			$c = trim($color[$i]);
			if(strlen($c) > 0) {
				if($colorCount > 0) {
					$q .= ' OR ';
				}
				$q .= "v.color LIKE '%$c%'";
				$colorCount++;
			}
		}

		if(strlen($q) > 0) {
			$query .= " AND ($q)";
		}
	}
	//제조사
	if($company) {
		$q = '';

		$company = explode('|', $company);
		$companyCount = 0;
		for($i = 0; $i < count($company); $i++) {
			$c = trim($company[$i]);
			if(strlen($c) > 0) {
				if($companyCount > 0) {
					$q .= ' OR ';
				}
				$q .= "c_company.sname LIKE '%$c%'";
				$companyCount++;
			}
		}

		if(strlen($q) > 0) {
			$query .= " AND ($q)";
		}
	}
	//모델
	if($model) {
		$q = '';

		$model = explode('|', $model);
		$modelCount = 0;
		for($i = 0; $i < count($model); $i++) {
			$m = trim($model[$i]);
			if(strlen($m) > 0) {
				if($modelCount > 0) {
					$q .= ' OR ';
				}
				$q .= "vs.name LIKE '%$m%'";
				$modelCount++;
			}
		}

		if(strlen($q) > 0) {
			$query .= " AND ($q)";
		}
	}

	$query .= " GROUP BY v.idx) t WHERE 1=1";

	//자차보험
	if($insu) {
		$insuQuery1 = '';
		$insuQuery2 = '';

		if(stripos($insu, 'section0') !== false) {
			$insuQuery1 .= "t.price_insu0_check = 'Y'";
		}

		if(stripos($insu, 'section1') !== false) {
			if(strlen($insuQuery1) > 0) {
				$insuQuery1 .= " OR ";
			}
			$insuQuery1 .= "(
				(t.price_insu1_check = 'Y' AND t.price_insu1_exem = 0)
				OR (t.price_insu2_check = 'Y' AND t.price_insu2_exem = 0)
				OR (t.price_insu3_check = 'Y' AND t.price_insu3_exem = 0)
				)";

			$insuQuery2 .= "(
				t.price_longterm_insu_exem = 0
			)";
		}
		if(stripos($insu, 'section2') !== false) {
			if(strlen($insuQuery1) > 0) {
				$insuQuery1 .= " OR ";
			}
			$insuQuery1 .= "(
				(t.price_insu1_check = 'Y' AND t.price_insu1_exem <= 300000)
				OR (t.price_insu2_check = 'Y' AND t.price_insu2_exem <= 300000)
				OR (t.price_insu3_check = 'Y' AND t.price_insu3_exem <= 300000)
				)";

			if(strlen($insuQuery2) > 0) {
				$insuQuery2 .= " OR ";
			}

			$insuQuery2 .= "(
				t.price_longterm_insu_exem <= 300000
			)";
		}
		if(stripos($insu, 'section3') !== false) {
			if(strlen($insuQuery1) > 0) {
				$insuQuery1 .= " OR ";
			}
			$insuQuery1 .= "(
				(t.price_insu1_check = 'Y' AND t.price_insu1_exem >= 300000 AND t.price_insu1_exem <= 500000)
				OR (t.price_insu2_check = 'Y' AND t.price_insu2_exem >= 300000 AND t.price_insu2_exem <= 500000)
				OR (t.price_insu3_check = 'Y' AND t.price_insu3_exem >= 300000 AND t.price_insu3_exem <= 500000)
				)";

			if(strlen($insuQuery2) > 0) {
				$insuQuery2 .= " OR ";
			}

			$insuQuery2 .= "(
				t.price_longterm_insu_exem >= 300000 AND t.price_longterm_insu_exem <= 500000
			)";
		}
		if(stripos($insu, 'section4') !== false) {
			if(strlen($insuQuery1) > 0) {
				$insuQuery1 .= " OR ";
			}
			$insuQuery1 .= "(
				(t.price_insu1_check = 'Y' AND t.price_insu1_exem >= 500000 AND t.price_insu1_exem <= 1000000)
				OR (t.price_insu2_check = 'Y' AND t.price_insu2_exem >= 500000 AND t.price_insu2_exem <= 1000000)
				OR (t.price_insu3_check = 'Y' AND t.price_insu3_exem >= 500000 AND t.price_insu3_exem <= 1000000)
				)";

			if(strlen($insuQuery2) > 0) {
				$insuQuery2 .= " OR ";
			}

			$insuQuery2 .= "(
				t.price_longterm_insu_exem >= 500000 AND t.price_longterm_insu_exem <= 1000000
			)";
		}
		if(stripos($insu, 'section5') !== false) {
			if(strlen($insuQuery1) > 0) {
				$insuQuery1 .= " OR ";
			}
			$insuQuery1 .= "(
				(t.price_insu1_check = 'Y' AND t.price_insu1_exem >= 1000000)
				OR (t.price_insu2_check = 'Y' AND t.price_insu2_exem >= 1000000)
				OR (t.price_insu3_check = 'Y' AND t.price_insu3_exem >= 1000000)
				)";

			if(strlen($insuQuery2) > 0) {
				$insuQuery2 .= " OR ";
			}

			$insuQuery2 .= "(
				t.price_longterm_insu_exem >= 1000000
			)";
		}


		if(strlen($insuQuery1) > 0 || strlen($insuQuery2) > 0) {
			$query .= " AND ( (t.pricetype=1 AND ($insuQuery1)) ";
			if(strlen($insuQuery2) > 0) {
				$query .= " OR (t.pricetype=2 AND ($insuQuery2))";
			}
			$query .= ")";
		}
	}

	//대여방법
	if($ptype) {
		$ptypeIdx = explode('|', $ptype);
		$ptypeList = '';
		for($i = 0; $i < count($ptypeIdx); $i++) {
			$p = trim($ptypeIdx[$i]);
			if(strlen($p) > 0) {
				if(strlen($ptypeList) > 0) $ptypeList .= ',';
				$ptypeList .= $p == '1' ? '1' : '0';
			}
		}
		if(strlen($ptypeList) > 0) {
			$query .= " AND t.delavail IN ($ptypeList)";
		}
	}

	$query .= " GROUP BY rentshop_idx, model_idx, fuel_idx, color, total_charge ORDER BY total_charge, modelname, distance";

	return array('query' => $query, 'daysInfo' => $daysInfo, 'hoursInfo' => $hoursInfo);
}

/**
 * DB에서 사용할 수 있는 주소로 변환
 * 강원도 -> 강원
 * 경기도 -> 경기 ...
 *
 * @param $addr string 주소
 *
 * @return string 주소
 */
function getDbAddress($addr) {
	$addr = str_replace('강원도', '강원', $addr);
	$addr = str_replace('경기도', '경기', $addr);
	$addr = str_replace('경상남도', '경남', $addr);
	$addr = str_replace('경상북도', '경북', $addr);
	$addr = str_replace('광주광역시', '광주', $addr);
	$addr = str_replace('대구광역시', '대구', $addr);
	$addr = str_replace('대전광역시', '대전', $addr);
	$addr = str_replace('부산광역시', '부산', $addr);
	$addr = str_replace('서울특별시', '서울', $addr);
	$addr = str_replace('세종특별자치시', '세종', $addr);
	$addr = str_replace('울산광역시', '울산', $addr);
	$addr = str_replace('인천광역시', '인천', $addr);
	$addr = str_replace('전라남도', '전남', $addr);
	$addr = str_replace('전라북도', '전북', $addr);
	$addr = str_replace('제주특별자치도', '제주', $addr);
	$addr = str_replace('충청남도', '충남', $addr);
	$addr = str_replace('충청북도', '충북', $addr);

	return $addr;
}

/**
 * 차량 모델네임 generalize query
 *
 * @return string query
 */
function getModelnameGeneral() {
	return "
		CASE
			WHEN vs.name LIKE '%스타렉스%' THEN '스타렉스'
			WHEN vs.name LIKE '%아반떼%' OR vs.name LIKE '%아반테%' THEN '아반떼'
			WHEN vs.name LIKE '%쏘나타%' THEN '쏘나타'
			WHEN vs.name LIKE '%카니발%' THEN '카니발'
			WHEN vs.name LIKE '%그랜저%' OR vs.name LIKE '%그랜져%' THEN '그랜저'
			WHEN vs.name LIKE '%소울%' OR vs.name LIKE '%쏘울%' THEN '쏘울'
			WHEN vs.name LIKE '%제네시스%' THEN '제네시스'
			WHEN vs.name LIKE '%모닝%' THEN '모닝'
			WHEN vs.name LIKE '%스포티지%' THEN '스포티지'
			WHEN vs.name LIKE '%쏘렌토%' THEN '쏘렌토'
			WHEN vs.name LIKE '%말리부%' THEN '말리부'
			WHEN vs.name LIKE '%투싼%' THEN '투싼'
			WHEN vs.name LIKE '%에쿠스%' THEN '에쿠스'
			WHEN vs.name LIKE '%닛산 알티마%' THEN '닛산 알티마'
			WHEN vs.name LIKE '%산타페%' OR vs.name LIKE '%싼타페%' THEN '싼타페'
			WHEN c.idx = 1 AND vs.name LIKE '%i30%' THEN 'i30'
			WHEN c.idx = 1 AND vs.name LIKE '%i40%' THEN 'i40'
			WHEN c.idx = 2 AND vs.name LIKE '%K9%' THEN 'K9'
			WHEN c.idx = 2 AND vs.name LIKE '%K7%' THEN 'K7'
			WHEN c.idx = 2 AND vs.name LIKE '%K5%' THEN 'K5'
			WHEN c.idx = 2 AND vs.name LIKE '%K3%' THEN 'K3'
			WHEN c.idx = 4 AND vs.name LIKE '%SM7%' THEN 'SM7'
			WHEN c.idx = 4 AND vs.name LIKE '%SM5%' THEN 'SM5'
			WHEN c.idx = 4 AND vs.name LIKE '%SM3%' THEN 'SM3'
			WHEN c.idx = 4 AND vs.name LIKE '%QM5%' THEN 'QM5'
			WHEN c.idx = 4 AND vs.name LIKE '%QM3%' THEN 'QM3'
			WHEN c.idx = 3 AND vs.name LIKE '%크루즈%' THEN '크루즈'
			WHEN c.idx = 11 AND vs.name LIKE '%미니%' THEN '미니'
			WHEN c.idx = 7 AND (vs.name LIKE '벤츠 CL%' OR vs.name LIKE '벤츠 SL%' OR vs.name LIKE '벤츠 GL%') THEN SUBSTR(vs.name, 1, 6)
			WHEN vs.name LIKE '벤츠 A%' OR vs.name LIKE '벤츠 C%' OR vs.name LIKE '벤츠 E%' OR vs.name LIKE '벤츠 S%' THEN CONCAT(SUBSTR(vs.name, 1, 4), ' Class')
			WHEN vs.name LIKE '아우디 TTS%' THEN '아우디 TTS'
			WHEN
				vs.name LIKE '아우디 A%' OR
				vs.name LIKE '아우디 Q%' OR
				vs.name LIKE '아우디 S%' OR
				vs.name LIKE '아우디 TT%'
				THEN SUBSTR(vs.name, 1, 6)
			WHEN
				vs.name LIKE 'BMW 1%' OR
				vs.name LIKE 'BMW 3%' OR
				vs.name LIKE 'BMW 4%' OR
				vs.name LIKE 'BMW 5%' OR
				vs.name LIKE 'BMW 6%' OR
				vs.name LIKE 'BMW 7%'
				THEN CONCAT(SUBSTR(vs.name, 1, 5), ' Series')
			ELSE vs.name
		END
		";
}

/**
 * 정산일 계산
 * @param $dt		string	기준일
 * @param $turn		int		회차
 *
 * @return			string	정산일
 */
function getSettlementDate($dt, $turn = NULL) {
	$date = strtotime($dt);
	if($turn && $turn > 1) {
		$query = "SELECT '$dt' + INTERVAL ".($turn-1)." MONTH";
		$date = strtotime(mysql_fetch_row(mysql_query($query))[0]);
	}

	$date = strtotime('+15 days', $date);

	//다음 15일 혹은 말일
	$dayOfMonth = date('d', $date);
	if($dayOfMonth <= 15)
		$date = strtotime('+'.(15-$dayOfMonth).' days', $date);
	else
		$date = strtotime(date('Y-m-t', $date));
	
	return date('Y-m-d', $date);
}

/**
 * 정산일로 결제일 계산
 * @param $settlementDate	string	정산일
 *
 * @return array	(start => 시작, end => 끝)
 */
function getPaymentDateRange($settlementDate) {
	$date = [];

	$t = strtotime($settlementDate);

	$date['end'] = date('Y-m-d', strtotime('-15 days', $t));
	$date['start'] = date('Y-m-d', strtotime('-29 days', $t));

	return $date;
}

/**
 * 색상코드 받기
 * @param $color	string	색상명
 * @param $default	string	기본색상
 * @return			string	컬러코드 (#123456)
 */
function getColorCode($color, $default = '#000000') {
	switch($color) {
		case '블랙':				return '#';
		case '블루':				return '#';
		case '화이트':				return '#';
		case '레드':				return '#';
		case '옐로우':				return '#';
		case '그레이':				return '#';
		case '베이지':				return '#';
		case '민트':				return '#';
		case '브라운':				return '#';
		case '실버':				return '#';
		case '메탈':				return '#';
		case '네이비':				return '#';
		case '진주색':				return '#';
		case '새틴메탈':			return '#';
		case '쥐색':				return '#';
		case '밤색':				return '#';
		case '금색':				return '#';
		case '하늘색':				return '#';
		case '그라파이트':			return '#';
		case '라임':				return '#';
		case '아이보리':			return '#';
		case '그린':				return '#';
		case '와인':				return '#';
		case '주홍':				return '#';
		case '초록':				return '#';
		case '브론즈':				return '#';
		case '라이트그라파이트':	return '#';
		case '스노우화이트펄':		return '#';
		case '판테라그레이':		return '#';

		default:			return $default;
	}
}

/**
 * 프로모션 이름
 * @param $promotion	string	프로모션 코드
 *
 * @return string				프로모션 이름
 */
function promotion($promotion) {
	switch($promotion) {
		case 'wooricard':
			return '우리카드 연계할인';
		case 'promotion001':
			return '첫 구매고객 할인';
	}
	
	return '';
}

/**
 * 문자발송
 * @param      $telnum
 * @param      $message
 * @param null $smstype
 * @param null $subject
 */
function sendSMS($telnum, $message, $smstype = null, $subject = null) {
	$user_id = "rentking77";
	$secure_key = "86686a4f32583924ebe5b8511dbfa422";
	$sms_url = "http://sslsms.cafe24.com/sms_sender.php"; // 전송요청 URL
	$send_num = array("1661", "3313", "");

	$sms["user_id"] = base64_encode($user_id);
	$sms["secure"] = base64_encode($secure_key);
	$sms["msg"] = base64_encode(stripslashes($message));

	if( $smstype == "L"){
		$sms["subject"] = base64_encode($subject);
	}

	$sms["rphone"] = base64_encode($telnum);
	$sms["sphone1"] = base64_encode($send_num[0]);
	$sms["sphone2"] = base64_encode($send_num[1]);
	$sms["sphone3"] = base64_encode($send_num[2]);
	$sms["rdate"]           = base64_encode("");
	$sms["rtime"]           = base64_encode("");
	$sms["mode"]            = base64_encode("1");
	$sms["returnurl"]       = base64_encode("");
	$sms["testflag"]        = base64_encode("");
	$sms["destination"] = strtr(base64_encode(""), "+/=", "-,");
	$sms["repeatFlag"]      = base64_encode("");
	$sms["repeatNum"]       = base64_encode("");
	$sms["repeatTime"]      = base64_encode("");
	$sms["smsType"]         = base64_encode($smstype);
	$host_info      = explode("/", $sms_url);
	$host           = $host_info[2];
	$path           = $host_info[3];

	$boundary                       = "---------------------".substr(md5(rand(0,32000)),0,10);
	$header                         = "POST /".$path ." HTTP/1.0\r\n";
	$header                         .= "Host: ".$host."\r\n";
	$header                         .= "Content-type: multipart/form-data, boundary=".$boundary."\r\n";

	$data = '';
	foreach($sms AS $index => $value)	{
		$data   .= "--$boundary\r\n";
		$data   .= "Content-Disposition: form-data; name=\"".$index."\"\r\n";
		$data   .= "\r\n".$value."\r\n";
		$data   .= "--$boundary\r\n";
	}
	$header .= "Content-length: " . strlen($data) . "\r\n\r\n";
	$fp = fsockopen($host, 80);

	if ($fp) {
		fputs($fp, $header.$data);
		$rsp    = "";
		while (!feof($fp)) {
			$rsp    .= fgets($fp, 8192);
		}
	}
	fclose($fp);
}

/**
 * 카카오 알림톡 발송
 * @param      $kakaoAuth	string	API 인증키
 * @param      $phone		string	수신할 핸드폰번호
 * @param      $code		string	템플릿 코드
 * @param      $data		array	템플릿 변환 데이터
 *
 * @return null|string				cmid 코드
 */
function sendKakao($kakaoAuth, $phone='', $code='', $data=[]) {
	$result = NULL;

	if($phone && $code) {
		$query = "SELECT * FROM kakao_template WHERE code = '$code'";
		$r = mysql_query($query);

		if(!$r)
			return $result;

		$template = mysql_fetch_assoc($r);

		if($template) {
			$msg = trim($template['msg']);
            $isFirst = $config['production'] ? false : true;
            foreach($data as $key => $value) {
                if($isFirst){   //개발환경일 경우 첫번째 인자값에 prefix
                    $value .= '##### dev #####';
                    $isFirst = false;
                }
				$msg = str_replace('#{'.$key.'}', $value, $msg);
			}

			$failedMsg = $msg;

			$param = "phone=$phone";
			$param .= "&callback=16613313";
			$param .= "&template_code=$code";
			if($template['button_type'] == '웹링크') {
				$url = (isset($data['button_url']) ? $data['button_url'] : $template['button_url']);
				$param .= "&btn_types=웹링크";
				$param .= "&btn_txts={$template['button_name']}";
				$param .= "&btn_urls1=".urlencode($url);
				$failedMsg .= "\n\n{$template['button_name']}\n$url";
			}

			$param .= "&msg=".urlencode($msg);

			$failedType = strlen($failedMsg) < 78 ? 'SMS' : 'LMS';
			$failedSubject = '렌트킹';
			$param .= "&failed_type=$failedType";
			$param .= "&failed_subject=$failedSubject";
			$param .= "&failed_msg=".urlencode($failedMsg);

			//TODO: apistore 정기점검 #1497 2017-12-14 삭제
			if(date('Y-m-d H:i:s') <= '2017-12-13 00:00:00' || date('Y-m-d H:i:s') >= '2017-12-13 06:00:00') {
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, 'http://api.apistore.co.kr/kko/2/msg/rentking');
				curl_setopt($ch, CURLOPT_HTTPHEADER, array("x-waple-authorization: $kakaoAuth"));
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
				curl_setopt($ch, CURLOPT_POST, TRUE);
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,0);
				curl_setopt($ch, CURLOPT_TIMEOUT, 10);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
				$response = curl_exec($ch);
				curl_close($ch);

				if($response) {
					$res = json_decode($response, true);

					$msg = mysql_escape_string($msg);
					$failedMsg = mysql_escape_string($failedMsg);
					$query = "INSERT INTO kakao_log(phone, callback, reqdate, msg, code, failed_type, failed_subject, failed_msg, result_code, result_msg, cmid) VALUES (
					'$phone', '16613313', NOW(), '$msg', '$code', '$failedType', '$failedSubject', '$failedMsg', '{$res['result_code']}', '{$res['result_message']}', '{$res['cmid']}'
				)";
					mysql_query($query);
					if($res['result_code'] == 200) {
						$result = $res['cmid'];
					}
				}
			} else {
				sendSms($phone, $failedMsg, 'L', '');
			}

		}
	}

	return $result;
}

/**
 * 로그용 session id 저장 or 가져오기
 * @param null $sessionId	string	저장할 session id
 *
 * @return null|string				저장된 session id
 */
function logSessionId($sessionId = null) {
	if($sessionId) {
		setcookie('logVisit', $sessionId, time() + (60 * 60 * 12), '/', 'rentking.co.kr');
	} else {
		$sessionId = isset($_COOKIE['logVisit']) && strlen($_COOKIE['logVisit']) >= 10 ? $_COOKIE['logVisit'] : session_id();
	}

	return $sessionId;
}

/**
 * user agent에 따라 로그 기록 여부 판단
 * @param $userAgent	string user agent
 *
 * @return bool					로그 기록 여부
 */
function logCheckUserAgent($userAgent) {
	if(strlen($userAgent) > 0
		&& stripos($userAgent, 'crawler') === FALSE
		&& stripos($userAgent, 'facebookexternalhit') === FALSE
		&& stripos($userAgent, 'kakaotalk-scrap') === FALSE
		&& stripos($userAgent, 'Googlebot') === FALSE
		&& stripos($userAgent, 'evc-batch') === FALSE
		&& stripos($userAgent, 'CloudFlare-AlwaysOnline') === FALSE
		&& stripos($userAgent, 'Apache-HttpClient') === FALSE
	   && stripos($userAgent, 'naver.me/bot') === FALSE
	   && stripos($userAgent, 'wget/') === FALSE
	   && stripos($userAgent, 'curl/') === FALSE
	   && stripos($userAgent, 'CFNetwork/') === FALSE
	   && stripos($userAgent, 'www.google.com/adsbot') === FALSE
	   && stripos($userAgent, 'zgrab') === FALSE
	   && stripos($userAgent, 'WinHttp.WinHttpRequest') === FALSE
	   && stripos($userAgent, 'python-requests/') === FALSE
	   && stripos($userAgent, '//ltx') === FALSE
	   && stripos($userAgent, 'yandex.com/bots') === FALSE
	   && stripos($userAgent, 'adsbot') === FALSE
	   && stripos($userAgent, 'bingbot') === FALSE
	   && stripos($userAgent, 'bot/') === FALSE
	   && stripos($userAgent, 'Go-http-client/') === FALSE
	)
		return true;

	return false;
}

/**
 * ip에 따라 로그 기록 여부 판단
 * @param $ip	string ip
 *
 * @return bool			로그 기록 여부
 */
function logUse($ip) {
	global $config;

	$rentkingIp = array('127.0.0.1');

	return !in_array($ip, $rentkingIp) || !$config['production'];
}

/**
 * 방문 로그 기록
 *
 * @param $referer	string	referer
 * @param $url		string	url
 */
function logVisit($referer = null, $url = null) {
	$ip = ($_SERVER['HTTP_CF_CONNECTING_IP'] ? $_SERVER['HTTP_CF_CONNECTING_IP'] : $_SERVER['REMOTE_ADDR']);
	$userAgent = substr(mysql_escape_string($_SERVER['HTTP_USER_AGENT']), 0, 480);

	if(isset($_SESSION['promotion']) && strpos($_SESSION['promotion'], 'wooricard') !== FALSE) {
		$userAgent .= ' promotion/' . explode('||', $_SESSION['promotion'])[0];
	}

	if(logCheckUserAgent($userAgent)) {
		$sessionId = isset($_COOKIE['logVisit']) && strlen($_COOKIE['logVisit']) >= 10 ? $_COOKIE['logVisit'] : NULL;
		$referer = substr(mysql_escape_string(urldecode($referer ? $referer : $_SERVER['HTTP_REFERER'])), 0, 980);
		$url = substr(mysql_escape_string(urldecode($url ? preg_replace("/^https?:\\/\\/(www|m)(\\.(local|dev|remote[0-9]))*\\.rentking\\.co\\.kr/i", '', $url) : $_SERVER['REQUEST_URI'])), 0, 480);
		$visit = false;

		//쿠키없음 (처음 방문 or 12시간 이후 재방문)
		if($sessionId == NULL) {
			$sessionId = session_id();
			$visit = true;
		} else {
			//session id가 logVisit과 다르고 referer가 있고 rentking이 아니면 새로운 방문으로 간주
			if($sessionId != session_id() && strlen($referer) > 0 && !preg_match('/^https?:\/\/(www|m|img)\.(local\.|dev\.)*rentking.co.kr.*/', $referer)) {
				$sessionId = session_id();
				$visit = true;
			}
		}

		if($visit) {
			logSessionId($sessionId);
			//TODO: lastVisit 쿠키 체크
			//TOOD: initialVisit 쿠키 체크

			$query = "SELECT * FROM log_visit WHERE session_id='$sessionId'";
			$r = mysql_query($query);

			if(mysql_num_rows($r) < 1) {
				//TODO: 재방문, 신규방문 체크
				if(!isset($_COOKIE['initialVisit'])) {
					setcookie('initialVisit', $sessionId.'|'.date('Y-m-d H:i:s'), time() + (60 * 60 * 24 * 365), '/', 'rentking.co.kr');
				}
				$lastSessionId = null;
				if(isset($_COOKIE['lastVisit'])) {
					$lastSessionId = explode('|', $_COOKIE['lastVisit']);
					if(count($lastSessionId) != 2) {
						$lastSessionId = null;
					}
				}
				$query = "INSERT INTO log_visit(ip, session_id, dt, last_session_id, last_dt, referer, url, user_agent) VALUE ('$ip', '$sessionId', NOW(), " .
						 ($lastSessionId ? "'$lastSessionId[0]'" : 'NULL') . ',' .
						 ($lastSessionId ? "'$lastSessionId[1]'" : 'NULL') . ',' .
						 " '$referer', '$url', '$userAgent')";
				mysql_query($query);
			}
		}
	}
}

/**
 * 페이지뷰 로그 기록
 *
 * @param	$url	string	url
 */
function logPageview($url = null) {
	$ip = ($_SERVER['HTTP_CF_CONNECTING_IP'] ? $_SERVER['HTTP_CF_CONNECTING_IP'] : $_SERVER['REMOTE_ADDR']);
	$userAgent = substr(mysql_escape_string($_SERVER['HTTP_USER_AGENT']), 0, 480);

	if(isset($_SESSION['promotion']) && strpos($_SESSION['promotion'], 'wooricard') !== FALSE) {
		$userAgent .= ' promotion/' . explode('||', $_SESSION['promotion'])[0];
	}

	if(logCheckUserAgent($userAgent)) {
		$sessionId = logSessionId();
		$sessionIdReal = session_id();
		$url = substr(mysql_escape_string(urldecode($url ? preg_replace("/^https?:\\/\\/(www|m)(\\.(local|dev|remote[0-9]))*\\.rentking\\.co\\.kr/i", '', $url) : $_SERVER['REQUEST_URI'])), 0, 480);

		$query = "SELECT idx FROM log_pageview WHERE session_id = '$sessionId' ORDER BY dt DESC LIMIT 0, 1";
		$r = mysql_query($query);
		$beforeIdx = '';
		if (mysql_num_rows($r) > 0) {
			$row = mysql_fetch_assoc($r);
			$beforeIdx = $row['idx'];
		}

		$query = "INSERT INTO log_pageview(ip, session_id, session_id_real, dt, url, user_agent, before_idx) VALUE ('$ip', '$sessionId', '$sessionIdReal', NOW(), '$url', '$userAgent', " . ($beforeIdx ? $beforeIdx : 'NULL') . ")";
		mysql_query($query);

		$idx = mysql_insert_id();

		if ($beforeIdx) {
			$query = "UPDATE log_pageview SET next_idx=$idx WHERE idx = $beforeIdx";
			mysql_query($query);
		}

		logSessionId($sessionId);
		setcookie('lastVisit', $sessionId.'|'.date('Y-m-d H:i:s'), time() + (60 * 60 * 24 * 365), '/', 'rentking.co.kr');
	}
}

/**
 * 에러 로그 기록
 *
 * @param $msg string 에러메시지
 */
function logError($msg) {
	$ip = ($_SERVER['HTTP_CF_CONNECTING_IP'] ? $_SERVER['HTTP_CF_CONNECTING_IP'] : $_SERVER['REMOTE_ADDR']);
	$userAgent = substr(mysql_escape_string($_SERVER['HTTP_USER_AGENT']), 0, 480);

	if(isset($_SESSION['promotion']) && strpos($_SESSION['promotion'], 'wooricard') !== FALSE) {
		$userAgent .= ' promotion/' . explode('||', $_SESSION['promotion'])[0];
	}

	if(logCheckUserAgent($userAgent)) {
		$sessionId = logSessionId();
		$sessionIdReal = session_id();
		$url = substr(mysql_escape_string(urldecode($_SERVER['REQUEST_URI'])), 0, 480);
		$msg = substr(mysql_escape_string($msg), 0, 90);
		$request = $_REQUEST ? mysql_escape_string(json_encode($_REQUEST, JSON_UNESCAPED_UNICODE)) : NULL;

		$query = "INSERT INTO log_error(ip, session_id, session_id_real, dt, url, request, msg) VALUE ('$ip', '$sessionId', '$sessionIdReal', NOW(), '$url', " . ($request ? "'$request'" : "NULL") . ", '$msg')";
		mysql_query($query);
	}
}

/**
 * 암호화
 * @param $string			string	암호화할 문자열
 * @param $encryptionKey	string	암호화 키
 *
 * @return string					암호화된 문자열
 */
function encrypt($string, $encryptionKey) {
	return openssl_encrypt($string, 'AES-128-ECB', $encryptionKey);
}

/**
 * 복호화
 * @param $string			string	복호화할 문자열
 * @param $encryptionKey	string	복호화 키
 *
 * @return string					복호화된 키
 */
function decrypt($string, $encryptionKey) {
	return openssl_decrypt($string, 'AES-128-ECB', $encryptionKey);
}

/**
 * open-plfatform apikey, apiSecret로 access token 발급받음
 * @param $client_id
 * @param $client_sceret
 *
 * @return array|mixed
 */
function openPlatformAuth() {
	global $config;
	$param = "client_id=".$config['bankTransferKey'];
	$param .= "&client_secret=".$config['bankTransferSecret'];
	$param .= "&grant_type=client_credentials";
	$param .= "&scope=oob";

	return openPlatformCurl('/oauth/2.0/token', $param);
}

/**
 * open-platform 계좌 실존여부 확인, 예금주명 일치 확인
 * @param $accessToken openPlatformAuth()를 통해 받은 access token
 * @param $bank	은행코드
 * @param $bankAccount 계좌번호
 * @param $bankName 예금주명
 * @param $bankHolder 인증정보 (사업자등록번호 10자리 or 주민등록번호 앞 7자리)
 * @param $datetime 요청일시
 *
 * @return array|mixed
 */
function openPlatformCheckAccount($accessToken, $bank, $bankAccount, $bankName, $bankHolder, $datetime){
	$param = json_encode(array(
		'bank_code_std'			=>	$bank,
		'account_num'			=>	$bankAccount,
		'account_holder_info'	=>	$bankHolder,
		'tran_dtime'			=>	$datetime
	));

	$result = openPlatformCurl('/v1.0/inquiry/real_name', $param, $accessToken);

	if($result['rsp_code'] == 'A0001' && $result['account_holder_name'] != $bankName) {
		$result['rsp_code'] = 'BANK_ACCOUNT_HOLDER_NAME_NOT_VALID';
		$result['rsp_message'] = '입력된 예금주 명과 은행 계좌의 예금주 명이 일치하지 않습니다.';
	}

	return $result;
}

/**
 * open-platform 출금이체 진행
 * @param $accessToken
 * @param $transferInfo
 *
 * @return array|mixed
 */
function openPlatformTransfer($accessToken, $transferInfo){
	global $config;
	$transferParam = json_encode(array(
		'wd_pass_phrase'	=>	$config['bankTransferPassword'],
		'wd_print_content'	=>	$transferInfo['info'],
		'req_cnt'			=>	'1',
		'tran_dtime'		=>	$transferInfo['datetime'],
		'req_list'			=> array(
			array(
				'tran_no'				=>	'1',
				'bank_code_std'			=>	$transferInfo['bank'],
				'account_num'			=>	$transferInfo['bankAccount'],
				'account_holder_name'	=>	$transferInfo['bankName'],
				'print_content'			=>	$transferInfo['infoDeposit'],
				'tran_amt'				=>	$transferInfo['account'],
				'cms_no'				=>	$transferInfo['cms']
			)
		)
	));
	return openPlatformCurl('/v1.0/transfer/deposit2', $transferParam, $accessToken);
}

/**
 * 오픈플렛폼과 연동된 은행들의 상태 확인
 * @param $accessToken
 *
 * @return array|mixed
 */
function openPlatformCheckBankStatus($accessToken, $bank){
	global $config;
	$result = array();
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $config['bankTransferUrl'].'/v1.0/bank/status');
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$accessToken));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,0);
	curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	$response = curl_exec($ch);
	if($response) {
		$result = json_decode($response,true);
	}else{
		var_dump(curl_error($ch));
	}
	curl_close($ch);


	$checkBankAvailable = false;
	foreach($result['res_list'] as $item)
	{
		if($item['bank_code_std'] == $bank && $item['bank_status'] == 'Y')
		{
			$checkBankAvailable = true;
		}
	}

	if(!$checkBankAvailable){
		$result['rsp_code'] = 'BANK_STATUS_NOT_AVAILABLE';
		$result['rsp_message'] = '은행코드 \''.$bank.'\'(은)는 현재 오픈플렛폼에서 지원하지 않거나 점검시간입니다.';
	}

	return $result;
}

/**
 * openplatform curl
 *
 * @param        $url
 * @param        $params
 * @param string $accessToken
 *
 * @return array|mixed
 */
function openPlatformCurl($url, $params, $accessToken = ''){
	global $config;
	$result = array();
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $config['bankTransferUrl'].$url);
	if($accessToken != ''){
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$accessToken));
	}
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_POST, TRUE);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,0);
	curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	$response = curl_exec($ch);
	if($response) {
		$result = json_decode($response,true);
	}else{
		var_dump(curl_error($ch));
	}
	curl_close($ch);
	return $result;
}

/**
 * 계좌 출금이체
 * @param array $transferInfo 이체정보
 *
 * @return array
 */
function transferAccount($transferInfo){

	global $config;

	if(!$config['production'])
	{
		$transferInfo['bank'] = '003';
		$transferInfo['bankAccount'] = '58600880001017';
		$transferInfo['bankHolder'] = '8709291';
		$transferInfo['bankName'] = '강성민';
//		$transferInfo['bank'] = '020';
//		$transferInfo['bankAccount'] = '1005403204036';
//		$transferInfo['bankHolder'] = '8138100123';
//		$transferInfo['bankName'] = '주식회사렌트킹';
	}

	$transferInfo['bankAccount'] = preg_replace("/[^0-9]*/s", "", $transferInfo['bankAccount']);

	$result = array();
	$authResult = openPlatformAuth();
	if(empty($authResult))
	{
		$result['result'] = "AUTHENTICATION_ERROR";
		$result['msg'] = "인증 에러 발생";

	}else{
		$checkBankStatus = openPlatformCheckBankStatus($authResult['access_token'], $transferInfo['bank']);

		if ($checkBankStatus['rsp_code'] != 'A0000') {
			$result['result'] = $checkBankStatus['rsp_code'];
			$result['msg'] = $checkBankStatus['rsp_message'];
			$result['data'] = $checkBankStatus;
		}else {
			$checkAccountResult = openPlatformCheckAccount($authResult['access_token'], $transferInfo['bank'], $transferInfo['bankAccount'], $transferInfo['bankName'], $transferInfo['bankHolder'], $transferInfo['datetime']);

			if ($checkAccountResult['rsp_code'] != 'A0000') {
				$result['result'] = $checkAccountResult['rsp_code'];
				$result['msg'] = $checkAccountResult['rsp_message'];
				$result['data'] = $checkAccountResult;
			} else {

				$transferResult = openPlatformTransfer($authResult['access_token'], $transferInfo);

				if ($transferResult['rsp_code'] != 'A0000') {
					$result['result'] = $transferResult['rsp_code'];
					$result['msg'] = $transferResult['rsp_message'];
					$result['data'] = $transferResult;
				} else {
					$result['result'] = 'SUCCESS';
					$result['msg'] = '이체 성공. 금액 : ' . $transferInfo['account'];
					$result['data'] = $transferResult;
				}
			}
		}
	}

	return $result;
}

/**
 * 은행계좌 확인
 * @param $bank 은행코드
 * @param $bankAccount 은행계좌번호
 * @param $bankName 은행예금주명
 * @param $bankHolder 은행계좌인증정보(사업자등록번호10자리 or 주민등록번호 앞7자리)
 *
 * @return array
 */
function transferCheckAccount($bank, $bankAccount, $bankName, $bankHolder){

	$bankAccount = preg_replace("/[^0-9]*/s", "", $bankAccount);

	$result = array();
	$authResult = openPlatformAuth();
	if(empty($authResult))
	{
		$result['result'] = "AUTHENTICATION_ERROR";
		$result['msg'] = "인증 에러 발생";

	}else {

		$checkBankStatus = openPlatformCheckBankStatus($authResult['access_token'], $bank);

		if($checkBankStatus['rsp_code'] != 'A0000'){
			$result['result'] = $checkBankStatus['rsp_code'];
			$result['msg'] = $checkBankStatus['rsp_message'];
			$result['data'] = $checkBankStatus;
		} else {
			$checkAccountResult = openPlatformCheckAccount($authResult['access_token'], $bank, $bankAccount, $bankName, $bankHolder, date('YmdHis'));

			if ($checkAccountResult['rsp_code'] != 'A0000') {
				$result['result'] = $checkAccountResult['rsp_code'];
				$result['msg'] = $checkAccountResult['rsp_message'];
				$result['data'] = $checkAccountResult;
			} else {
				$result['result'] = 'SUCCESS';
				$result['msg'] = '이체 계좌 확인 성공!';
				$result['data'] = $checkAccountResult;
			}
		}
	}
	return $result;
}

/**
 * 은행 코드에 맞는 은행명
 * 참조 : https://namu.wiki/w/%EA%B8%88%EC%9C%B5%EA%B3%B5%EB%8F%99%EB%A7%9D#rfn-1
 * @param string $bankCode
 * @param bool $isShowCode	true = 은행코드 포함, false = 은행명만
 *
 * @return string
 */
function getBankName($bankCode, $isShowCode = false){
	switch($bankCode){
		case '001' : $bankName = '한국은행';				break;
		case '002' : $bankName = 'KDB산업은행';			break;
		case '003' : $bankName = 'IBK기업은행';			break;
		case '004' : $bankName = 'KB국민은행';				break;
		case '005' : $bankName = 'KEB하나은행(구.외환)';	break;
		case '006' : $bankName = '국민(구.주택)';			break;
		case '007' : $bankName = '수협';					break;
		case '011' : $bankName = '농협은행';				break;
		case '020' : $bankName = '우리은행';				break;
		case '023' : $bankName = 'SC제일은행';				break;
		case '027' : $bankName = '한국씨티은행';			break;
		case '031' : $bankName = '대구은행';				break;
		case '032' : $bankName = '부산은행';				break;
		case '034' : $bankName = '광주은행';				break;
		case '035' : $bankName = '제주은행';				break;
		case '037' : $bankName = '전북은행';				break;
		case '039' : $bankName = '경남은행';				break;
		case '045' : $bankName = '새마을금고';				break;
		case '048' : $bankName = '신협';					break;
		case '050' : $bankName = '상호저축은행';			break;
		case '052' : $bankName = '모간스탠리은행';			break;
		case '054' : $bankName = 'HSBC은행';				break;
		case '055' : $bankName = '도이치은행';				break;
		case '056' : $bankName = 'RBS은행';				break;
		case '057' : $bankName = 'JP모간체이스은행';		break;
		case '058' : $bankName = '미즈호은행';				break;
		case '059' : $bankName = '미쓰비시은행';			break;
		case '060' : $bankName = 'BOA은행';				break;
		case '064' : $bankName = '산림조합';				break;
		case '071' : $bankName = '우체국';				break;
		case '081' : $bankName = 'KEB하나은행';			break;
		case '088' : $bankName = '신한은행';				break;
		default : return $bankCode;
	}

	if($isShowCode)
	{
		return $bankName . ' (' . $bankCode . ') ';
	}

	return $bankName;
}

function createCouponSerial($parent, $length = 10) {
	$serial = "RENTKING-$parent-";
	for($i = 0; $i < $length; $i++) {
		$char = rand(45, 90);
		if($char < 55)
			$char += 3;
		else if($char < 65)
			$char -= 7;

		$serial .= chr($char);
	}

	return $serial;
}