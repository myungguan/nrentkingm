<?php
include $_SERVER['DOCUMENT_ROOT']."/old/inc/base.php";
include $config['incPath']."/connect.php";
include $config['incPath']."/session.php";
include $config['incPath']."/config_site.php";

$order_idx = $_POST['order_idx'];
$accountReturn = str_replace(',', '', $_REQUEST['accountReturn']);
$accountReturnFee = isset($_REQUEST['accountReturnFee']) ? str_replace(',', '', $_REQUEST['accountReturnFee']) : 0;

$query = "
	SELECT
		m.*,
		mt.idx reservation_idx,
		mt.vehicle_idx,
		mt.retype,
		mt.pricetype,
		mt.ddata1,
		mt.ddata2,
		mt.rtype,
		mt.sdate,
		mt.edate,
		mt.account,
		mt.account1,
		mt.account2,
		mt.preaccount,
		mt.insu,
		mt.insuac,
		mt.addr,
		mt.raddr,
		mt.extend extendyn
	FROM payments m
		LEFT JOIN reservation mt ON m.reservation_idx = mt.idx
	WHERE
		m.idx = $order_idx
";
$order = mysql_fetch_assoc(mysql_query($query));

$query = "SELECT * FROM payment_accounts WHERE payment_idx = $order_idx AND tbtype = 'I' ORDER BY dt_create DESC LIMIT 0, 1";
$account = mysql_fetch_assoc(mysql_query($query));
$buymethod = $account['buymethod'];
$shopId = $account['shop_id'];

$query = "SELECT * from member WHERE idx = {$order['member_idx']}";
$result = mysql_query($query);
$member = mysql_fetch_assoc($result);

$query = "SELECT r.* FROM vehicles v LEFT JOIN rentshop r ON v.rentshop_idx = r.idx WHERE v.idx = {$order['vehicle_idx']}";
$rentshop = mysql_fetch_assoc(mysql_query($query));

$query = "SELECT v.*, vs.name modelname FROM vehicles v LEFT JOIN vehicle_models vs ON v.model_idx = vs.idx WHERE v.idx = {$order['vehicle_idx']}";
$car = mysql_fetch_assoc(mysql_query($query));


// 올앳관련 함수 Include
//----------------------
include "./allatutil.php";

$paymentResult = false;
if(!$config['production'] && strpos($account['order_no'], 'dev') === false)
	$config['allatTest'] = 'Y';

$msg = '';
$errorCode = '';
if(isset($shopId) && isset($account['order_no'])) {

    if ($buymethod == 'C') {
        //Request Value Define
        //----------------------
        /*
        $at_cross_key = "가맹점 CrossKey";     //설정필요 [사이트 참조 - http://www.allatpay.com/servlet/AllatBiz/support/sp_install_guide_scriptapi.jsp#shop]
        $at_shop_id   = "가맹점 ShopId";       //설정필요
        */

        //------------------------ Test Code ---------------------
        $at_cross_key = $config['allatCrossKey'][2];    //설정필요 [사이트 참조 - http://www.allatpay.com/servlet/AllatBiz/support/sp_install_guide_scriptapi.jsp#shop]
        $at_shop_id = $config['allatShopId'][2];        //설정필요
        //--------------------------------------------------------

        // 요청 데이터 설정
        //----------------------
        $at_data = "allat_shop_id=" . $at_shop_id .
            "&allat_enc_data=" . $_POST["allat_enc_data"] .
            "&allat_cross_key=" . $at_cross_key;


        // 올앳 결제 서버와 통신 : CancelReq->통신함수, $at_txt->결과값
        //----------------------------------------------------------------
        // PHP5 이상만 SSL 사용가능
        $at_txt = CancelReq($at_data, "SSL");
        // $at_txt = CancelReq($at_data, "NOSSL"); // PHP5 이하버전일 경우
        // 이 부분에서 로그를 남기는 것이 좋습니다.
        // (올앳 결제 서버와 통신 후에 로그를 남기면, 통신에러시 빠른 원인파악이 가능합니다.)
        $at_txt = iconv("euc-kr", "utf-8", $at_txt);

        // 결제 결과 값 확인
        //------------------
        $REPLYCD = getValue("reply_cd", $at_txt);        //결과코드
        $REPLYMSG = getValue("reply_msg", $at_txt);       //결과 메세지

        // 결과값 처리
        //--------------------------------------------------------------------------
        // 결과 값이 '0000'이면 정상임. 단, allat_test_yn=Y 일경우 '0001'이 정상임.
        // 실제 결제   : allat_test_yn=N 일 경우 reply_cd=0000 이면 정상
        // 테스트 결제 : allat_test_yn=Y 일 경우 reply_cd=0001 이면 정상
        //--------------------------------------------------------------------------
        if (!strcmp($REPLYCD, "0000")
            || (!$config['production'] && !strcmp($REPLYCD, "0001"))
        ) {
            // reply_cd "0000" 일때만 성공
            $CANCEL_YMDHMS = getValue("cancel_ymdhms", $at_txt);
            $PART_CANCEL_FLAG = getValue("part_cancel_flag", $at_txt);
            $REMAIN_AMT = getValue("remain_amt", $at_txt);
            $PAY_TYPE = getValue("pay_type", $at_txt);

            $paymentResult = array();
            $paymentResult['CANCEL_YMDHMS'] = $CANCEL_YMDHMS;
            $paymentResult['PART_CANCEL_FLAG'] = $PART_CANCEL_FLAG;
            $paymentResult['REMAIN_AMT'] = $REMAIN_AMT;
            $paymentResult['PAY_TYPE'] = $PAY_TYPE;
        } else {
            // reply_cd 가 "0000" 아닐때는 에러 (자세한 내용은 매뉴얼참조)
            // reply_msg 가 실패에 대한 메세지
            $paymentResult = false;
            $errorCode = $REPLYCD;
            $msg = $REPLYMSG;

            if($errorCode == '0505'){   //이미 취소된 상태

                //TODO: 올엣 측 문의결과 몇초 차이로 2번 응답이 왔고, 올엣에서는 첫번째 응답으로 정상 회신했으나 렌트킹에서는 두번째 요청에 대한 응답만 받음.
                //TODO: 결제취소가 서버에서 응답오기 전에 다시 요청할 수 없도록 조치해야함
                $CANCEL_YMDHMS = getValue("cancel_ymdhms", $at_txt);
                $PART_CANCEL_FLAG = getValue("part_cancel_flag", $at_txt);
                $REMAIN_AMT = getValue("remain_amt", $at_txt);
                $PAY_TYPE = getValue("pay_type", $at_txt);

                $paymentResult = array();
                $paymentResult['CANCEL_YMDHMS'] = $CANCEL_YMDHMS;
                $paymentResult['PART_CANCEL_FLAG'] = $PART_CANCEL_FLAG;
                $paymentResult['REMAIN_AMT'] = $REMAIN_AMT;
                $paymentResult['PAY_TYPE'] = $PAY_TYPE;

                $comment = '카드결제 취소 처리(올엣측 0505에러, 취소 처리는 정상처리) ';
            }else{
                $comment = '결제 취소(예약취소) 실패 (카드결제, 에러코드:'.$errorCode.', '.$msg.')';
            }
            $query = "INSERT INTO comments(type, type_idx, public, comment, member_idx, dt_create, dt_update) VALUES 
            ('payment', $order_idx, 'N', '$comment', {$member['id']}, NOW(), NOW());";
            mysql_query($query);

        }

    } else if ($buymethod == 'F') {

        $rawdata = explode('|R|', $account['rawdata']);

        $card = null;
        $query = "SELECT cf.* FROM payments m LEFT JOIN card_fix cf ON m.card_fix_idx = cf.idx WHERE m.idx = $order_idx";
        $card = mysql_fetch_assoc(mysql_query($query));

        //일부 반환 일때 결제
        if ($accountReturn < $account['account']) {
            $paymentAmount = $account['account'] - $accountReturn;

            // 필수 항목
            $at_cross_key = $config['allatCrossKey'][0];   //CrossKey값(최대200자)
            $at_fix_key = $card['fix_key'];   //카드키(최대 24자)
            $at_sell_mm = "00";   //할부개월값(최대  2자)
            $at_amt = $paymentAmount;   //금액(최대 10자)
            $at_business_type = $card['cardt'];   //결제자 카드종류(최대 1자)       : 개인(0),법인(1)
            $at_registry_no = $card['datas'];   //주민번호(최대 13자리)           : szBusinessType=0 일경우
            $at_biz_no = $card['datas'];   //사업자번호(최대 20자리)         : szBusinessType=1 일경우
            $at_shop_id = $config['allatShopId'][0];   //상점ID(최대 20자)
            $at_shop_member_id = substr($member['id'], 0, 20);   //회원ID(최대 20자)               : 쇼핑몰회원ID
            $at_order_no = $order['reservation_idx'] . '.' . $account['turn'] . '.repay' . ($config['production'] ? '' : '.dev');   //주문번호(최대 80자)             : 쇼핑몰 고유 주문번호
            $at_product_cd = $order['vehicle_idx'];   //상품코드(최대 1000자)           : 여러 상품의 경우 구분자 이용, 구분자('||':파이프 2개)
            $at_product_nm = "rentking";   //상품명(최대 1000자)             : 여러 상품의 경우 구분자 이용, 구분자('||':파이프 2개)
            $at_cardcert_yn = "N";   //카드인증여부(최대 1자)          : 인증(Y),인증사용않음(N),인증만사용(X)
            $at_zerofee_yn = "N";   //일반/무이자 할부 사용 여부(최대 1자) : 일반(N), 무이자 할부(Y)
            $at_buyer_nm = "-";   //결제자성명(최대 20자)
            $at_recp_nm = "-";   //수취인성명(최대 20자)
            $at_recp_addr = strlen($order['addr']) > 0 ? $order['addr'] : $rentshop['addr1'];   //수취인주소(최대 120자)
            $at_buyer_ip = ($_SERVER['HTTP_CF_CONNECTING_IP'] ? $_SERVER['HTTP_CF_CONNECTING_IP'] : $_SERVER['REMOTE_ADDR']);   //결제자 IP(최대15자) - BuyerIp를 넣을수 없다면 "Unknown"으로 세팅
            $at_email_addr = substr($member['id'], 0, 50);   //결제자 이메일 주소(50자)
            $at_bonus_yn = "N";   //보너스포인트 사용여부(최대1자)  : 사용(Y), 사용않음(N)
            $at_gender = "";   //구매자 성별(최대 1자)           : 남자(M)/여자(F)
            $at_birth_ymd = substr($card['datas'], 0, 6);   //구매자의 생년월일(최대 8자)     : YYYYMMDD형식

            $at_enc = setValue($at_enc, "allat_card_key", $at_fix_key);
            $at_enc = setValue($at_enc, "allat_sell_mm", $at_sell_mm);
            $at_enc = setValue($at_enc, "allat_amt", $at_amt);
            $at_enc = setValue($at_enc, "allat_business_type", $at_business_type);
            if (strcmp($at_business_type, "0") == 0) {
                $at_enc = setValue($at_enc, "allat_registry_no", $at_registry_no);
            } else {
                $at_enc = setValue($at_enc, "allat_biz_no", $at_biz_no);
            }
            $at_enc = setValue($at_enc, "allat_shop_id", $at_shop_id);
            $at_enc = setValue($at_enc, "allat_shop_member_id", $at_shop_member_id);
            $at_enc = setValue($at_enc, "allat_order_no", $at_order_no);
            $at_enc = setValue($at_enc, "allat_product_cd", $at_product_cd);
            $at_enc = setValue($at_enc, "allat_product_nm", $at_product_nm);
            $at_enc = setValue($at_enc, "allat_cardcert_yn", $at_cardcert_yn);
            $at_enc = setValue($at_enc, "allat_zerofee_yn", $at_zerofee_yn);
            $at_enc = setValue($at_enc, "allat_buyer_nm", $at_buyer_nm);
            $at_enc = setValue($at_enc, "allat_recp_name", $at_recp_nm);
            $at_enc = setValue($at_enc, "allat_recp_addr", $at_recp_addr);
            $at_enc = setValue($at_enc, "allat_user_ip", $at_buyer_ip);
            $at_enc = setValue($at_enc, "allat_email_addr", $at_email_addr);
            $at_enc = setValue($at_enc, "allat_bonus_yn", $at_bonus_yn);
            $at_enc = setValue($at_enc, "allat_gender", $at_gender);
            $at_enc = setValue($at_enc, "allat_birth_ymd", $at_birth_ymd);
            $at_enc = setValue($at_enc, "allat_pay_type", "FIX");  //수정금지(결제방식 정의)
            $at_enc = setValue($at_enc, "allat_test_yn", $config['production'] ? 'N' : $config['allatTest']);  //테스트 :Y, 서비스 :N
            $at_enc = setValue($at_enc, "allat_opt_pin", "NOUSE");  //수정금지(올앳 참조 필드)
            $at_enc = setValue($at_enc, "allat_opt_mod", "APP");  //수정금지(올앳 참조 필드)

            $at_data = "allat_shop_id=" . $at_shop_id .
                "&allat_amt=" . $at_amt .
                "&allat_enc_data=" . $at_enc .
                "&allat_cross_key=" . $at_cross_key;

            // 올앳 결제 서버와 통신 : ApprovalReq->통신함수, $at_txt->결과값
            //----------------------------------------------------------------
            $at_txt = ApprovalReq($at_data, "SSL");
            $at_txt = iconv("euc-kr", "utf-8", $at_txt);

            // 결제 결과 값 확인
            //------------------
            $REPLYCD = getValue("reply_cd", $at_txt);        //결과코드
            $REPLYMSG = getValue("reply_msg", $at_txt);       //결과 메세지

            if (!strcmp($REPLYCD, "0000")
                || (!$config['production'] && !strcmp($REPLYCD, "0001"))
            ) {
                // reply_cd "0000" 일때만 성공
                $ORDER_NO = getValue("order_no", $at_txt);
                $AMT = getValue("amt", $at_txt);
                $PAY_TYPE = getValue("pay_type", $at_txt);
                $APPROVAL_YMDHMS = getValue("approval_ymdhms", $at_txt);
                $SEQ_NO = getValue("seq_no", $at_txt);
                $APPROVAL_NO = getValue("approval_no", $at_txt);
                $CARD_ID = getValue("card_id", $at_txt);
                $CARD_NM = getValue("card_nm", $at_txt);
                $SELL_MM = getValue("sell_mm", $at_txt);
                $ZEROFEE_YN = getValue("zerofee_yn", $at_txt);
                $CERT_YN = getValue("cert_yn", $at_txt);
                $CONTRACT_YN = getValue("contract_yn", $at_txt);

                $paymentResult = array();
                $paymentResult['ORDER_NO'] = $ORDER_NO;
                $paymentResult['AMT'] = $AMT;
                $paymentResult['PAY_TYPE'] = $PAY_TYPE;
                $paymentResult['APPROVAL_YMDHMS'] = $APPROVAL_YMDHMS;
                $paymentResult['SEQ_NO'] = $SEQ_NO;
                $paymentResult['APPROVAL_NO'] = $APPROVAL_NO;
                $paymentResult['CARD_ID'] = $CARD_ID;
                $paymentResult['CARD_NM'] = $CARD_NM;
                $paymentResult['SELL_MM'] = $SELL_MM;
                $paymentResult['ZEROFEE_YN'] = $ZEROFEE_YN;
                $paymentResult['CERT_YN'] = $CERT_YN;
                $paymentResult['CONTRACT_YN'] = $CONTRACT_YN;

                //payment_accounts 업데이트
                $rowdata = $paymentResult['PAY_TYPE'] . "|R|" . $paymentResult['APPROVAL_YMDHMS'] . "|R|" . $paymentResult['SEQ_NO'] . "|R|" . $paymentResult['APPROVAL_NO'] . "|R|" . $paymentResult['CARD_ID'] . "|R|" . $paymentResult['CARD_NM'] . "|R|" . $paymentResult['SELL_MM'] . "|R|" . $paymentResult['ZEROFEE_YN'] . "|R|" . $paymentResult['CERT_YN'] . "|R|" . $paymentResult['CONTRACT_YN'];
                $query = "INSERT INTO payment_accounts(payment_idx, shop_id, order_no, tbtype, buymethod, card_fix_idx, account, rawdata, dt_create, dt_settlement, settlement_per) VALUES (
                        $order_idx, '$shopId', '{$paymentResult['ORDER_NO']}', 'I', '$buymethod', " . ($card ? $card['idx'] : 'NULL') . ", {$paymentResult['AMT']}, '$rowdata', NOW(), '{$account['dt_settlement']}', {$account['settlement_per']}
                    )";
                mysql_query($query);
                $account['addaccount'] = $paymentResult['AMT'];
                $accountReturn = $account['account'];

                $msg = '잔금 결제 완료';
            } else {
                // reply_cd 가 "0000" 아닐때는 에러 (자세한 내용은 매뉴얼참조)
                // reply_msg 는 실패에 대한 메세지
                $paymentResult = false;
                $errorCode = $REPLYCD;
                $msg = '잔금 결재 실패';

                $comment = '결제 취소(예약취소) 실패 (정기결제, 에러코드:'.$errorCode.', '.$msg.')';

                $query = "INSERT INTO comments(type, type_idx, public, comment, member_idx, dt_create, dt_update) VALUES 
            ('payment', $order_idx, 'N', '$comment', {$member['id']}, NOW(), NOW());";
                mysql_query($query);
            }
        } else {
            $paymentResult = true;
        }

        if ($paymentResult) {
            $at_enc = "";
            $at_data = "";
            $at_txt = "";

            // 필수 항목
            $at_cross_key = $config['allatCrossKey'][0];    //상점의 Cross Key
            $at_shop_id = $config['allatShopId'][0];        //상점ID(최대 20자)
            $at_amt = $accountReturn;        //금액(최대 10자)
            $at_order_no = $account['order_no'];    //주문번호(최대 80자)         : 쇼핑몰 고유 주문번호
            $at_pay_type = "FIX";        //원거래건의 결제방식[CARD]
            $at_seq_no = $rawdata[2];            //거래일련번호 (최대  10자리) : 옵션필드임

            $at_enc = setValue($at_enc, "allat_shop_id", $at_shop_id);
            $at_enc = setValue($at_enc, "allat_amt", $at_amt);
            $at_enc = setValue($at_enc, "allat_order_no", $at_order_no);
            $at_enc = setValue($at_enc, "allat_pay_type", $at_pay_type);
            $at_enc = setValue($at_enc, "allat_seq_no", $at_seq_no);
            $at_enc = setValue($at_enc, "allat_test_yn", $config['production'] ? 'N' : $config['allatTest']);  //테스트 :Y, 서비스 :N
            $at_enc = setValue($at_enc, "allat_opt_pin", "NOUSE");  //수정금지(올앳 참조 필드)
            $at_enc = setValue($at_enc, "allat_opt_mod", "APP");  //수정금지(올앳 참조 필드)

            $at_data = "allat_shop_id=" . $at_shop_id .
                "&allat_amt=" . $at_amt .
                "&allat_enc_data=" . $at_enc .
                "&allat_cross_key=" . $at_cross_key;

            // 올앳 결제 서버와 통신 : ApprovalReq->통신함수, $at_txt->결과값
            //----------------------------------------------------------------
            $at_txt = CancelReq($at_data, "SSL");
            $at_txt = iconv("euc-kr", "utf-8", $at_txt);

            // 결제 결과 값 확인
            //------------------
            $REPLYCD = getValue("reply_cd", $at_txt);        //결과코드
            $REPLYMSG = getValue("reply_msg", $at_txt);       //결과 메세지

            if (!strcmp($REPLYCD, "0000")
                || (!$config['production'] && !strcmp($REPLYCD, "0001"))
            ) {
                // reply_cd "0000" 일때만 성공
                $CANCEL_YMDHMS = getValue("cancel_ymdhms", $at_txt);
                $PART_CANCEL_FLAG = getValue("part_cancel_flag", $at_txt);
                $REMAIN_AMT = getValue("remain_amt", $at_txt);
                $PAY_TYPE = getValue("pay_type", $at_txt);

                $paymentResult = array();
                $paymentResult['CANCEL_YMDHMS'] = $CANCEL_YMDHMS;
                $paymentResult['PART_CANCEL_FLAG'] = $PART_CANCEL_FLAG;
                $paymentResult['REMAIN_AMT'] = $REMAIN_AMT;
                $paymentResult['PAY_TYPE'] = $PAY_TYPE;
            } else {
                // reply_cd 가 "0000" 아닐때는 에러 (자세한 내용은 매뉴얼참조)
                // reply_msg 는 실패에 대한 메세지
                $paymentResult = false;
                $errorCode = $REPLYCD;
                $msg = $REPLYMSG;
            }
        }
    }
}

if($paymentResult) {
    if ($accountReturn > $account['account'] - $account['isouts'])
        $accountReturn = $account['account'] - $account['isouts'];

    //payment_account 추가 및 환불 금액 입력
    $rawdata = $paymentResult['CANCEL_YMDHMS'] . "|R|" . $paymentResult['PART_CANCEL_FLAG'] . "|R|" . $paymentResult['REMAIN_AMT'] . "|R|" . $paymentResult['PAY_TYPE'];
    $query = "
			INSERT INTO payment_accounts(payment_idx, shop_id, order_no, tbtype, buymethod, card_fix_idx, account, rawdata, dt_create, isouts, up_idx) VALUES (
				$order_idx, '$shopId', '{$account['order_no']}','O', '$buymethod', " . ($account['card_fix_idx'] ? $account['card_fix_idx'] : 'NULL') . ", $accountReturn, '$rawdata', NOW(), 0, {$account['idx']}
			)
		";
    mysql_query($query);

    $isout = $account['isouts'] + $accountReturn;
    $query = "UPDATE payment_accounts SET isouts=$isout WHERE idx={$account['idx']}";
    mysql_query($query);

    $addAccount = isset($account['addaccount']) ? $account['addaccount'] : 0;
    $msg = number_format($accountReturn-$addAccount)."원이 환불되었습니다.";
    $memo = '환불('.number_format($accountReturn-$addAccount).')';

    //잔액이 0일때 취소 처리
    if($isout + $accountReturnFee >= $account['account'] + $addAccount) {
        $msg = "예약이 취소되었습니다.";

        $query = "UPDATE payments SET dan=5, dt_cancel=NOW(), dt_next_payment=NULL, discount=0, promotion=NULL WHERE idx=$order_idx";
        mysql_query($query);

        $query = "
        SELECT
            v.*
        FROM payments m
            LEFT JOIN reservation mt ON m.reservation_idx = mt.idx
            LEFT JOIN vehicles v ON mt.vehicle_idx = v.idx
        WHERE
            m.dan < 3
            AND mt.sdate <= NOW()
            AND mt.edate >= NOW()
            AND v.idx = {$order['vehicle_idx']}
    ";
        $r = mysql_query($query);

        if(mysql_num_rows($r) < 1) {
            $query = "UPDATE vehicles SET status='S' WHERE idx = {$order['vehicle_idx']}";
            mysql_query($query);
        }

        if($order['extendyn'] == 'Y') {
            $query = "UPDATE payments SET extend_payment_idx = NULL WHERE extend_payment_idx={$order['idx']}";
            mysql_query($query);
        }

        //쿠폰 취소
        $query = "UPDATE member_coupons SET dt_use = NULL, payment_idx = NULL WHERE payment_idx=$order_idx";
        mysql_query($query);

        //문자 발송
        $kakao = array(
            'order' => $order_idx,
            'wdate' => $order['dt_create'],
            'cancel_date' => date("Y-m-d H:i:s"),
            'sdate' => $order['sdate'],
            'edate' => $order['edate'],
            'model' => $car['modelname'],
            'carnum' => $car['carnum'],
            'ac' => number_format($account['account']).'원',
            'ac_cancel' => number_format($accountReturn).'원',
            'name' => $member['name']
        );

        if(!$config['production']){
            $member['cp'] = '01034460336';
        }
        sendKakao($config['kakaoAuth'], $member['cp'], 'CAN0105', $kakao);

	    if($config['production']){
		    $query = "SELECT contact FROM rentshop_contacts WHERE rentshop_idx = ".$rentshop['idx'];
		    $r = mysql_query($query);
		    while($row = mysql_fetch_array($r)){
			    sendKakao($config['kakaoAuth'], $row['contact'], 'CAN0202', $kakao);
		    }

		    sendKakao($config['kakaoAuth'], '01020457173', 'CAN0202', $kakao);	//유태석 팀장
	    }

        if($config['production']) {
            //linkprice
            unset($value);
            $value['status'] = 2;
            update("linkprice", $value, " WHERE type='cps' AND order_code=".$order["reservation_idx"]." AND product_code=".$order["vehicle_idx"]);
            //linkprice
        }

        $memo = '예약취소';
    }

    $query = "INSERT INTO comments(type, type_idx, public, comment, member_idx, dt_create, dt_update) VALUES 
    ('payment', $order_idx, 'Y', '$memo', {$_SESSION['member_idx']}, NOW(), NOW());";
    mysql_query($query);
}


$msg = $errorCode ? "[$errorCode] $msg" : $msg;
echo "<script type='text/javascript'>alert('$msg');location.replace('{$_SERVER['HTTP_REFERER']}')</script>";

