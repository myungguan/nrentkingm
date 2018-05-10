<?
/**
 * common method list
 */

if ($han == 'idcheck') {
	$id = trim($_REQUEST['id']);

	$q = "SELECT * FROM member WHERE id='{$id}'";
	$r = mysql_query($q);
	$isit = mysql_num_rows($r);

	if ($isit == 0) {
		$redata['res'] = 'ok';
	} else {
		$redata['res'] = 'error';
	}

	$result = json_encode($redata);
	header('Content-Type:application/json; charset=utf-8');
	echo $result;
	exit;
}
if ($han == 'cpcheck') {
	$cp = $_REQUEST['cp'];
	$memg = $_REQUEST['memg'];

	$q = "SELECT * FROM member WHERE cp='{$cp}' AND id!='outmember'";
	if ($memg == '100' || $memg == '12') {
		$q = $q . "AND memgrade='{$memg}'";
	}
	$r = mysql_query($q);
	$row = mysql_fetch_array($r);

	if ($row['idx']) {
		$redata['res'] = 'error';
		$result = json_encode($redata);
		header('Content-Type:application/json; charset=utf-8');
		echo $result;
		exit;
	} else {
		$num = mt_rand(0, 9).mt_rand(0, 9).mt_rand(0, 9).mt_rand(0, 9).mt_rand(0, 9).mt_rand(0, 9);
		$kakao = [];
		$kakao['number'] = $num;
        if(!$config['production']){
            $cp = '01034460336';
        }
		sendKakao($config['kakaoAuth'], $cp, 'CER0102', $kakao);
		$redata['res'] = 'ok';
		$redata['data'] = $num;
		$redata['cp'] = $cp;
		$result = json_encode($redata);
		header('Content-Type:application/json; charset=utf-8');
		echo $result;
		exit;
	}
}
if ($han == 'cpcheck_mod') {
	$cp = $_REQUEST['cp'];
	$q = "SELECT * FROM member WHERE cp='{$cp}' AND id!='outmember' AND idx!='{$g_memidx}'";
	if ($ar_init_member['memgrade'] == '100') {
		$q = $q . "AND memgrade='100'";
	}
	$r = mysql_query($q);
	$row = mysql_fetch_array($r);

	if ($row['idx']) {
		$redata['res'] = 'error';
		$result = json_encode($redata);
		header('Content-Type:application/json; charset=utf-8');
		echo $result;
		exit;
	} else {
		$num = mt_rand(0, 9).mt_rand(0, 9).mt_rand(0, 9).mt_rand(0, 9).mt_rand(0, 9).mt_rand(0, 9);
		$kakao = [];
		$kakao['number'] = $num;
        if(!$config['production']){
            $cp = '01034460336';
        }
		sendKakao($config['kakaoAuth'], $cp, 'CER0102', $kakao);
		$redata['res'] = 'ok';
		$redata['data'] = $num;
		$redata['cp'] = $cp;
		$result = json_encode($redata);
		header('Content-Type:application/json; charset=utf-8');
		echo $result;
		exit;
	}
}

if ($han == 'searchcompany') {

	$search_word = $_REQUEST['search_word'];

	$q = "SELECT * FROM member WHERE memgrade='99' AND company like '%{$search_word}%'";
	$r = mysql_query($q);
	$cou = 0;
	while ($row = mysql_fetch_array($r)) {
		$indata[] = $row;
		$cou++;
	}

	if ($cou != 0) {
		$redata['res'] = 'ok';
		$redata['datas'] = $indata;
	} else {
		$redata['res'] = 'error';
	}

	$result = json_encode($redata);
	header('Content-Type:application/json; charset=utf-8');
	echo $result;
	exit;
}
if ($han == 'search2member') {
	$search_word = $_REQUEST['search_word'];

	$q = "SELECT * FROM member WHERE memgrade='100' AND (id like '%{$search_word}%' OR name like '%{$search_word}%') and id!='outmember' and idx!='{$g_memidx}'";

	$r = mysql_query($q);
	$cou = 0;
	while ($row = mysql_fetch_array($r)) {
		$indata[] = $row;
		$cou++;
	}

	if ($cou != 0) {
		$redata['res'] = 'ok';
		$redata['datas'] = $indata;
	} else {
		$redata['res'] = 'error';
	}

	$result = json_encode($redata);
	header('Content-Type:application/json; charset=utf-8');
	echo $result;
	exit;
}
if ($han == 'search2memberdi') {
	$search_word = $_REQUEST['search_word'];

	$q = "SELECT * FROM member WHERE memgrade='100' AND id='{$search_word}' and id!='outmember' and idx!='{$g_memidx}'";
	$r = mysql_query($q);
	$row = mysql_fetch_array($r);

	$cou = 0;
	if ($row['idx']) {
		$cou++;
	}

	if ($cou != 0) {
		$redata['res'] = 'ok';
		$redata['datas'] = $row;
	} else {
		$redata['res'] = 'error';
	}

	$result = json_encode($redata);
	header('Content-Type:application/json; charset=utf-8');
	echo $result;
	exit;
}
if ($han == 'search') {
	$key = $_REQUEST['key'];
	$keyword = $_REQUEST['keyword'];
	$target = $_REQUEST['target'];

	$q = "SELECT * FROM member WHERE {$key} like '%{$keyword}%' AND memgrade='100' and id!='outmember'";
	$r = mysql_query($q);
	$cou = 0;

	while ($row = mysql_fetch_array($r)) {
		$row['lastcar'] = "";
		$qs = "SELECT vehicle_models.name modelname FROM payments INNER JOIN vehicle_models ON vehicle_models.idx=payments.model_idx WHERE member_idx='{$row['idx']}' ORDER BY dt_create DESC limit 0,1";
		$rs = mysql_query($qs);
		$rows = mysql_fetch_array($rs);

		if ($rows) {
			$row['lastcar'] = $rows['modelname'];
		}
		$indata[] = $row;
		$cou++;
	}

	if ($cou == 0) {
		$redata['res'] = 'error';
		$redata['resmsg'] = '검색결과가 없습니다.';

		$result = json_encode($redata);
		header('Content-Type:application/json; charset=utf-8');
		echo $result;
		exit;
	} else {
		$redata['res'] = 'ok';
		$redata['datas'] = $indata;
		$redata['target'] = $target;
		$result = json_encode($redata);
		header('Content-Type:application/json; charset=utf-8');
		echo $result;
		exit;
	}
}
if ($han == 'applink') {
    $cp = $_REQUEST['cp'];

    $cp = preg_replace("/[^0-9]/", "", $cp);
    if(!preg_match("/^01[0-9]{8,9}$/", $cp)) {
        $redata['res'] = 'error';
        $redata['msg'] = '휴대폰 번호를 올바르게 입력해주세요.';
        $result = json_encode($redata);
        header('Content-Type:application/json; charset=utf-8');
        echo $result;
        exit;
    }else{
        $kakao = [];
        $kakao['number'] = "앱다운로드링크 테스트";
        if(!$config['production']){
            $cp = '01034460336';
        }
        sendKakao($config['kakaoAuth'], $cp, 'CER0102', $kakao);
        $redata['res'] = 'ok';
        $redata['data'] = $num;
        $redata['cp'] = $cp;
        $result = json_encode($redata);
        header('Content-Type:application/json; charset=utf-8');
        echo $result;
        exit;
    }



    $q = "SELECT * FROM member WHERE cp='{$cp}' AND id!='outmember'";
    if ($memg == '100' || $memg == '12') {
        $q = $q . "AND memgrade='{$memg}'";
    }
    $r = mysql_query($q);
    $row = mysql_fetch_array($r);

    if ($row['idx']) {
        $redata['res'] = 'error';
        $result = json_encode($redata);
        header('Content-Type:application/json; charset=utf-8');
        echo $result;
        exit;
    } else {
        $num = mt_rand(0, 9).mt_rand(0, 9).mt_rand(0, 9).mt_rand(0, 9).mt_rand(0, 9).mt_rand(0, 9);
        $kakao = [];
        $kakao['number'] = $num;
        if(!$config['production']){
            $cp = '01034460336';
        }
        sendKakao($config['kakaoAuth'], $cp, 'CER0102', $kakao);
        $redata['res'] = 'ok';
        $redata['data'] = $num;
        $redata['cp'] = $cp;
        $result = json_encode($redata);
        header('Content-Type:application/json; charset=utf-8');
        echo $result;
        exit;
    }
}
?>