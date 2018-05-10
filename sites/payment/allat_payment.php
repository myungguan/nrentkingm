<?php
include $_SERVER['DOCUMENT_ROOT']."/old/inc/base.php";
include $config['incPath']."/connect.php";
include $config['incPath']."/session.php";
include $config['incPath']."/config_site.php";

$buymethod = $_REQUEST['buymethod'];
$ptype = $_REQUEST['ptype'];
$order_idx = $_REQUEST['order_idx'];

//결제 - allat관련
$paymentResult = false;
$shopId = '';
$card = null;
$msg = '';
$errorCode = '';

$query = "SELECT * FROM member WHERE idx = $g_memidx";
$result = mysql_query($query);
if(mysql_num_rows($result) < 1) {
	$msg = '사용자 정보를 찾을 수 없습니다.';
	logError($msg);
	echo '<script type="text/javascript">alert("' . $msg . '");location.replace("/rent/search.php");</script>';
	exit;
}
$member = mysql_fetch_assoc($result);

$query = "SELECT * FROM reservation WHERE idx = $order_idx";
$result = mysql_query($query);
if(mysql_num_rows($result) < 1) {
	$msg = '예약정보가 없습니다.';
	logError($msg);
	echo '<script type="text/javascript">alert("' . $msg . '");location.replace("/rent/search.php");</script>';
	exit;
}
$order = mysql_fetch_assoc($result);

//연장일 경우
$extend_order = NULL;
if($order['extend'] == 'Y') {
	$query = "SELECT
			m.*
		FROM payments m
			LEFT JOIN reservation mt ON m.reservation_idx = mt.idx
		WHERE
			mt.edate = '{$order['sdate']}'
			AND mt.vehicle_idx = {$order['vehicle_idx']}
			AND m.member_idx = $g_memidx
		ORDER BY m.idx DESC
		limit 1
			";
	$r = mysql_query($query);
	if(mysql_num_rows($r) != 1) {
		$msg = '잘못된 예약 정보 입니다.';
		logError($msg);
		echo '<script type="text/javascript">alert("' . $msg . '");location.replace("/rent/search.php");</script>';
		exit;
	}

	$extend_order = mysql_fetch_assoc($r);
	$data['extend_order'] = $extend_order;
}

$query = "SELECT * FROM payments WHERE reservation_idx = $order_idx";
if(mysql_num_rows(mysql_query($query)) > 0) {
	$msg = '결제 완료된 예약입니다.';
	logError($msg);
	echo '<script type="text/javascript">alert("' . $msg . '");location.replace("/rent/search.php");</script>';
	exit;
}

$memberType = 1;
if($ar_init_member['memgrade']=='10' || $ar_init_member['memgrade']=='12')	{
	$memberType = 2;
}
$search = getSearchInfo($memberType, $order['sdate'], $order['edate'], $order['retype'], $order['addr'], $order['raddr'], null, $order['vehicle_idx'], $extend_order);

$q = str_replace('[FIELD]', 't.*', $search['query']);
$result = mysql_query($q);
if(mysql_num_rows($result) < 1) {
	$msg = '예약 가능한 차량이 없습니다.';
	logError($msg);
	echo '<script type="text/javascript">alert("'. $msg . '");location.replace("/rent/search.php");</script>';
	exit;
}
$car = mysql_fetch_assoc($result);

//쿠폰 정보
$coupon = $_REQUEST['coupon'];
if(isset($coupon) && $coupon != '0') {
    $query = "
		SELECT
			cm.*,
			c.account,
			c.actype
		FROM member_coupons cm
			LEFT JOIN coupons c ON cm.coupon_idx = c.idx
		WHERE
			cm.member_idx = $g_memidx
			AND cm.dt_use IS NULL
			AND cm.idx = $coupon";
    $coupon = mysql_fetch_assoc(mysql_query($query));
} else {
    $coupon = null;
}


//쿠폰등의 할인으로 최종 결제금액이 0원이면 결제(allat_payment_form.php)를 거치지 않고 들어옴
//결제금액이 없으므로 PG사(allat)를 거치지 않고 내부적으로 결제 및 예약 프로세스 진행
if(!isset($_SESSION['paymentAmount']) && !isset($_SESSION['discountAccount']) && $payType != 2){

    $amount = $order['totalaccount'];
    if($payType == 2) {
        $amount = $order['account1'];
    }

    $delcharge = $car['delcharge'];
    if($ptype == 2 || $extend_order)
        $delcharge = 0;

    $amount += $delcharge;

    $discountAccount = 0;

    if($coupon) {
        $discount = $coupon['account'];
        $percent = false;

        if($coupon['actype'] == 2) {
            $percent = true;
        }

        $discountAccount = $discount = floatval($discount);

        if($percent) {
            $discountAccount = floor($amount * $discount / 10000) * 100;
        }

        $amount -= $discountAccount;
    }

    if($_SESSION['promotion']) {
        $discount = explode('||', $_SESSION['promotion']);

        $discount = $discount[1];
        $percent = false;

        if(strpos($discount, '%') !== FALSE) {
            $percent = true;
        }

        $discountAccount = $discount = floatval($discount);

        if($percent) {
            $discountAccount = floor($amount * $discount / 10000) * 100;
        }

        $amount -= $discountAccount;
    }

    if($amount > 0) {   //결제할 금액을 재 계산해봤으나 정상이 아닌경우
        $msg = '비정상적인 접근입니다. 같은 현상이 반복될 경우 고객센터로 문의해주시기 바랍니다.';
        logError($msg);
        echo '<script type="text/javascript">alert("' . $msg . '");location.replace("/rent/search.php");</script>';
        exit;
    }

    $_SESSION['discountAccount'] = $discountAccount;
    $_SESSION['paymentAmount'] = $amount < 0 ? 0 : $amount;

    $paymentResult = array();
//    $paymentResult['ORDER_NO'] = $ORDER_NO;
    $paymentResult['AMT'] = $_SESSION['paymentAmount'];
//    $paymentResult['PAY_TYPE'] = $PAY_TYPE;
//    $paymentResult['APPROVAL_YMDHMS'] = $APPROVAL_YMDHMS;
//    $paymentResult['SEQ_NO'] = $SEQ_NO;
//    $paymentResult['APPROVAL_NO'] = $APPROVAL_NO;
//    $paymentResult['CARD_ID'] = $CARD_ID;
//    $paymentResult['CARD_NM'] = $CARD_NM;
//    $paymentResult['SELL_MM'] = $SELL_MM;
//    $paymentResult['ZEROFEE_YN'] = $ZEROFEE_YN;
//    $paymentResult['CERT_YN'] = $CERT_YN;
//    $paymentResult['CONTRACT_YN'] = $CONTRACT_YN;

    $msg = '예약이 완료되었습니다';

}else{
    // 올앳관련 함수 Include
    //----------------------
    include "./allatutil.php";

    if($buymethod == 'C') {
        $shopId = $config['allatShopId'][2];

        //Request Value Define
        //----------------------
        $at_cross_key = $config['allatCrossKey'][2];    //설정필요 [사이트 참조 - http://www.allatpay.com/servlet/AllatBiz/support/sp_install_guide_scriptapi.jsp#shop]
        $at_shop_id = $config['allatShopId'][2];        //설정필요
        $at_amt = $_SESSION['paymentAmount'];                        //결제 금액을 다시 계산해서 만들어야 함(해킹방지), ( session, DB 사용 )

        // 요청 데이터 설정
        //----------------------
        $at_data = "allat_shop_id=" . $at_shop_id .
            "&allat_amt=" . $at_amt .
            "&allat_enc_data=" . $_POST["allat_enc_data"] .
            "&allat_cross_key=" . $at_cross_key;


        // 올앳 결제 서버와 통신 : ApprovalReq->통신함수, $at_txt->결과값
        //----------------------------------------------------------------
        // PHP5 이상만 SSL 사용가능
        $at_txt = ApprovalReq($at_data, "SSL");
        // $at_txt = ApprovalReq($at_data, "NOSSL"); // PHP5 이하버전일 경우
        // 이 부분에서 로그를 남기는 것이 좋습니다.
        // (올앳 결제 서버와 통신 후에 로그를 남기면, 통신에러시 빠른 원인파악이 가능합니다.)
        $at_txt = iconv("euc-kr","utf-8",$at_txt);


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
            $SAVE_AMT = getValue("save_amt", $at_txt);
            $CARD_POINTDC_AMT = getValue("card_pointdc_amt", $at_txt);
            $BANK_ID = getValue("bank_id", $at_txt);
            $BANK_NM = getValue("bank_nm", $at_txt);
            $CASH_BILL_NO = getValue("cash_bill_no", $at_txt);
            $ESCROW_YN = getValue("escrow_yn", $at_txt);
            $ACCOUNT_NO = getValue("account_no", $at_txt);
            $ACCOUNT_NM = getValue("account_nm", $at_txt);
            $INCOME_ACC_NM = getValue("income_account_nm", $at_txt);
            $INCOME_LIMIT_YMD = getValue("income_limit_ymd", $at_txt);
            $INCOME_EXPECT_YMD = getValue("income_expect_ymd", $at_txt);
            $CASH_YN = getValue("cash_yn", $at_txt);
            $HP_ID = getValue("hp_id", $at_txt);
            $TICKET_ID = getValue("ticket_id", $at_txt);
            $TICKET_PAY_TYPE = getValue("ticket_pay_type", $at_txt);
            $TICKET_NAME = getValue("ticket_nm", $at_txt);
            $PARTCANCEL_YN = getValue("partcancel_yn", $at_txt);

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

            $msg = '예약이 완료되었습니다';
        } else {
            // reply_cd 가 "0000" 아닐때는 에러 (자세한 내용은 매뉴얼참조)
            // reply_msg 는 실패에 대한 메세지
            $paymentResult = false;
            $errorCode = $REPLYCD;
            $msg = $REPLYMSG;
            logError($msg);
        }

        /*
            [신용카드 전표출력 예제]

            결제가 정상적으로 완료되면 아래의 소스를 이용하여, 고객에게 신용카드 전표를 보여줄 수 있습니다.
            전표 출력시 상점아이디와 주문번호를 설정하시기 바랍니다.

            var urls ="http://www.allatpay.com/servlet/AllatBizPop/member/pop_card_receipt.jsp?shop_id=상점아이디&order_no=주문번호";
            window.open(urls,"app","width=410,height=650,scrollbars=0");

            현금영수증 전표 또는 거래확인서 출력에 대한 문의는 올앳페이 사이트의 1:1상담을 이용하시거나
            02) 3788-9990 으로 전화 주시기 바랍니다.

            전표출력 페이지는 저희 올앳 홈페이지의 일부로써, 홈페이지 개편 등의 이유로 인하여 페이지 변경 또는 URL 변경이 있을 수
            있습니다. 홈페이지 개편에 관한 공지가 있을 경우, 전표출력 URL을 확인하시기 바랍니다.
        */
    } else if($buymethod == 'F') {
        $shopId = $config['allatShopId'][0];

        if(!$card) {
            $at_cross_key = $config['allatCrossKey'][0];     //설정필요 [사이트 참조 - http://www.allatpay.com/servlet/AllatBiz/helpinfo/hi_install_guide.jsp#shop]
            $at_shop_id   = $config['allatShopId'][0];       //설정필요

            $at_data   = "allat_shop_id=".$at_shop_id.
                "&allat_enc_data=".$_POST["allat_enc_data"].
                "&allat_cross_key=".$at_cross_key;

            //--------------------------
            $at_txt = CertRegReq($at_data,"SSL");
            $at_txt = iconv("euc-kr","utf-8",$at_txt);

            // 결제 결과 값 확인
            //------------------
            $REPLYCD   =getValue("reply_cd",$at_txt);        //결과코드
            $REPLYMSG  =getValue("reply_msg",$at_txt);       //결과 메세지


            // 결과값 처리
            //--------------------------------------------------------------------------
            if (!strcmp($REPLYCD, "0000")
                || (!$config['production'] && !strcmp($REPLYCD, "0001"))
            ) {
                // reply_cd "0000" 일때만 성공
                $FIX_KEY	= getValue("fix_key",$at_txt);
                $APPLY_YMD	= getValue("apply_ymd",$at_txt);

                $query = "
				INSERT INTO card_fix (cardname, cardt, datas, fix_key, apply_ymd, dt_create) VALUES ('정기결제카드', '', '', '$FIX_KEY', '$APPLY_YMD', NOW())
			";
                mysql_query($query);
                $idx = mysql_insert_id();

                $query = "SELECT * FROM card_fix WHERE idx = $idx AND dt_delete IS NULL";
                $card = mysql_fetch_assoc(mysql_query($query));
            }else{
                // reply_cd 가 "0000" 아닐때는 에러 (자세한 내용은 매뉴얼참조)
                // reply_msg 는 실패에 대한 메세지
                $paymentResult = false;
                $msg = $REPLYMSG;
                $errorCode = $REPLYCD;
                logError($msg);
            }
        }

        if($card) {
            // 필수 항목
            $at_cross_key      = $config['allatCrossKey'][0];   //CrossKey값(최대200자)
            $at_fix_key        = $card['fix_key'];   //카드키(최대 24자)
            $at_sell_mm        = "00";   //할부개월값(최대  2자)
            $at_amt            = $_SESSION['paymentAmount'];   //금액(최대 10자)
            $at_business_type  = $card['cardt'];   //결제자 카드종류(최대 1자)       : 개인(0),법인(1)
            $at_registry_no    = $card['datas'];   //주민번호(최대 13자리)           : szBusinessType=0 일경우
            $at_biz_no         =  $card['datas'];   //사업자번호(최대 20자리)         : szBusinessType=1 일경우
            $at_shop_id        = $config['allatShopId'][0];   //상점ID(최대 20자)
            $at_shop_member_id = substr($member['id'], 0, 20);   //회원ID(최대 20자)               : 쇼핑몰회원ID
            $at_order_no       = $order['idx'].'.1'.($config['production'] ? '' : '.dev');   //주문번호(최대 80자)             : 쇼핑몰 고유 주문번호
            $at_product_cd     = $order['vehicle_idx'];   //상품코드(최대 1000자)           : 여러 상품의 경우 구분자 이용, 구분자('||':파이프 2개)
            $at_product_nm     = "rentking";   //상품명(최대 1000자)             : 여러 상품의 경우 구분자 이용, 구분자('||':파이프 2개)
            $at_cardcert_yn    = "N";   //카드인증여부(최대 1자)          : 인증(Y),인증사용않음(N),인증만사용(X)
            $at_zerofee_yn     = "N";   //일반/무이자 할부 사용 여부(최대 1자) : 일반(N), 무이자 할부(Y)
            $at_buyer_nm       = "-";   //결제자성명(최대 20자)
            $at_recp_nm        = "-";   //수취인성명(최대 20자)
            $at_recp_addr      = strlen($order['addr']) > 0 ? $order['addr'] : $car['rentshop_addr1'];   //수취인주소(최대 120자)
            $at_buyer_ip       = ($_SERVER['HTTP_CF_CONNECTING_IP'] ? $_SERVER['HTTP_CF_CONNECTING_IP'] : $_SERVER['REMOTE_ADDR']);   //결제자 IP(최대15자) - BuyerIp를 넣을수 없다면 "Unknown"으로 세팅
            $at_email_addr     = substr($member['id'], 0, 50);   //결제자 이메일 주소(50자)
            $at_bonus_yn       = "N";   //보너스포인트 사용여부(최대1자)  : 사용(Y), 사용않음(N)
            $at_gender         = "";   //구매자 성별(최대 1자)           : 남자(M)/여자(F)
            $at_birth_ymd      = substr($card['datas'],0,6);   //구매자의 생년월일(최대 8자)     : YYYYMMDD형식

            $at_enc = setValue($at_enc,"allat_card_key"         ,   $at_fix_key        );
            $at_enc = setValue($at_enc,"allat_sell_mm"          ,   $at_sell_mm        );
            $at_enc = setValue($at_enc,"allat_amt"              ,   $at_amt            );
            $at_enc = setValue($at_enc,"allat_business_type"    ,   $at_business_type  );
            if( strcmp($at_business_type,"0") == 0 ){
                $at_enc = setValue($at_enc,"allat_registry_no"  ,   $at_registry_no    );
            }else{
                $at_enc = setValue($at_enc,"allat_biz_no"       ,   $at_biz_no         );
            }
            $at_enc = setValue($at_enc,"allat_shop_id"          ,   $at_shop_id        );
            $at_enc = setValue($at_enc,"allat_shop_member_id"   ,   $at_shop_member_id );
            $at_enc = setValue($at_enc,"allat_order_no"         ,   $at_order_no       );
            $at_enc = setValue($at_enc,"allat_product_cd"       ,   $at_product_cd     );
            $at_enc = setValue($at_enc,"allat_product_nm"       ,   $at_product_nm     );
            $at_enc = setValue($at_enc,"allat_cardcert_yn"      ,   $at_cardcert_yn    );
            $at_enc = setValue($at_enc,"allat_zerofee_yn"       ,   $at_zerofee_yn     );
            $at_enc = setValue($at_enc,"allat_buyer_nm"         ,   $at_buyer_nm       );
            $at_enc = setValue($at_enc,"allat_recp_name"        ,   $at_recp_nm        );
            $at_enc = setValue($at_enc,"allat_recp_addr"        ,   $at_recp_addr      );
            $at_enc = setValue($at_enc,"allat_user_ip"          ,   $at_buyer_ip       );
            $at_enc = setValue($at_enc,"allat_email_addr"       ,   $at_email_addr     );
            $at_enc = setValue($at_enc,"allat_bonus_yn"         ,   $at_bonus_yn       );
            $at_enc = setValue($at_enc,"allat_gender"           ,   $at_gender         );
            $at_enc = setValue($at_enc,"allat_birth_ymd"        ,   $at_birth_ymd      );
            $at_enc = setValue($at_enc,"allat_pay_type"         ,   "FIX"              );  //수정금지(결제방식 정의)
            $at_enc = setValue($at_enc,"allat_test_yn"          ,   $config['production'] ? 'N' : $config['allatTest']);  //테스트 :Y, 서비스 :N
            $at_enc = setValue($at_enc,"allat_opt_pin"          ,   "NOUSE"            );  //수정금지(올앳 참조 필드)
            $at_enc = setValue($at_enc,"allat_opt_mod"          ,   "APP"              );  //수정금지(올앳 참조 필드)

            $at_data = "allat_shop_id=".$at_shop_id.
                "&allat_amt=".$at_amt.
                "&allat_enc_data=".$at_enc.
                "&allat_cross_key=".$at_cross_key;

            // 올앳 결제 서버와 통신 : ApprovalReq->통신함수, $at_txt->결과값
            //----------------------------------------------------------------
            $at_txt = ApprovalReq($at_data,"SSL");
            $at_txt = iconv("euc-kr","utf-8",$at_txt);

            // 결제 결과 값 확인
            //------------------
            $REPLYCD   =getValue("reply_cd",$at_txt);        //결과코드
            $REPLYMSG  =getValue("reply_msg",$at_txt);       //결과 메세지

            if (!strcmp($REPLYCD, "0000")
                || (!$config['production'] && !strcmp($REPLYCD, "0001"))
            ) {
                // reply_cd "0000" 일때만 성공
                $ORDER_NO         =getValue("order_no",$at_txt);
                $AMT              =getValue("amt",$at_txt);
                $PAY_TYPE         =getValue("pay_type",$at_txt);
                $APPROVAL_YMDHMS  =getValue("approval_ymdhms",$at_txt);
                $SEQ_NO           =getValue("seq_no",$at_txt);
                $APPROVAL_NO      =getValue("approval_no",$at_txt);
                $CARD_ID          =getValue("card_id",$at_txt);
                $CARD_NM          =getValue("card_nm",$at_txt);
                $SELL_MM          =getValue("sell_mm",$at_txt);
                $ZEROFEE_YN       =getValue("zerofee_yn",$at_txt);
                $CERT_YN          =getValue("cert_yn",$at_txt);
                $CONTRACT_YN      =getValue("contract_yn",$at_txt);

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

                $msg = '예약이 완료되었습니다';
            }else{
                // reply_cd 가 "0000" 아닐때는 에러 (자세한 내용은 매뉴얼참조)
                // reply_msg 는 실패에 대한 메세지
                $paymentResult = false;
                $msg = $REPLYMSG;
                $errorCode = $REPLYCD;
                logError($msg);
            }
        }
    }
}


//면허증 정보 업데이트
$memberLiscense = null;
if($member['driver_license_idx']) {
    $query = "SELECT *
        FROM driver_license
        WHERE idx = {$member['driver_license_idx']}";
    $result = mysql_query($query);
    if(mysql_num_rows($result) > 0) {
        $memberLiscense = mysql_fetch_assoc($result);
    }
}

if(!$memberLiscense) {
    $query = "
    INSERT INTO driver_license(name, birth, gender, cp, kinds, nums, date1, date2, dt_create) VALUES
    ('{$member['name']}', '{$member['birth']}', '{$member['sex']}', '{$member['cp']}', '{$_REQUEST['kinds']}', '{$_REQUEST['nums']}', '{$_REQUEST['date1']}', '{$_REQUEST['date2']}', NOW());
";

    mysql_query($query);
    $driverLicense = mysql_insert_id();
    $query = "UPDATE member SET driver_license_idx = '$driverLicense' WHERE idx = '$g_memidx'";
    mysql_query($query);
} else if(isset($_REQUEST['nums']) && isset($_REQUEST['date1']) && isset($_REQUEST['date2'])){  //면허정보가 있었으나 예약시 기입안하면 덮어씌우는 문제 방지
    $query = "
    UPDATE driver_license SET
        kinds = '{$_REQUEST['kinds']}',
        nums = '{$_REQUEST['nums']}',
        date1 = '{$_REQUEST['date1']}',
        date2 = '{$_REQUEST['date2']}'
    WHERE
        idx = {$member['driver_license_idx']}
";
    mysql_query($query);
}

//차량정보 업데이트
//	$query = "UPDATE vehicles SET status = 'O' WHERE idx={$car['idx']}";
//	mysql_query($query);

$discountAccount = $_SESSION['discountAccount'];

$promotion = isset($_SESSION['promotion']) ? $_SESSION['promotion'] : null;

$delac = $car['delcharge'];
if($ptype == 2 || $extend_order)
    $delac = 0;

$namesub = $extend_order ? $extend_order['namesub'] : $_REQUEST['namesub'];
$cpsub = $extend_order ? $extend_order['cpsub'] : $_REQUEST['cpsub'];
$kindsub = $extend_order ? $extend_order['kindsub'] : $_REQUEST['kindsub'];
$numsub = $extend_order ? $extend_order['numsub'] : $_REQUEST['numsub'];
$date1sub = $extend_order ? $extend_order['date1sub'] : $_REQUEST['date1sub'];
$date2sub = $extend_order ? $extend_order['date2sub'] : $_REQUEST['date2sub'];
$sessionId = logSessionId();
$sessionIdReal = session_id();

$driverLicense = null;
if($_REQUEST['namesub'] || $_REQUEST['cpsub'] || $_REQUEST['numsub'] || $_REQUEST['date1sub'] || $_REQUEST['date2sub']) {
    $query = "INSERT INTO driver_license(name, cp, kinds, nums, date1, date2, dt_create) VALUES (
			'{$_REQUEST['namesub']}', '{$_REQUEST['cpsub']}', '{$_REQUEST['kindsub']}', '{$_REQUEST['numsub']}', '{$_REQUEST['date1sub']}', '{$_REQUEST['date2sub']}', NOW()
			)";
    mysql_query($query);
    $driverLicense = mysql_insert_id();
}

if($paymentResult) {
	//payment 업데이트

	$dtNextPayment = NULL;

	$query = "INSERT INTO payments(
			ip, session_id, session_id_real,
			reservation_idx, member_idx, 
			driver_license_idx,
			dt_create, dan, ptype, delac,
			promotion, discount, payment_account,
			buymethod, card_fix_idx, pid) VALUES (
			'" . ($_SERVER['HTTP_CF_CONNECTING_IP'] ? $_SERVER['HTTP_CF_CONNECTING_IP'] : $_SERVER['REMOTE_ADDR']) . "', '{$sessionId}', '$sessionIdReal', 
			{$order['idx']}, $g_memidx,
			" . ($driverLicense ? $driverLicense : 'NULL') . ",
			NOW(), 1, $ptype, $delac,
			" . ($promotion ? "'$promotion'" : 'NULL') . ", $discountAccount, {$paymentResult['AMT']},
			'{$buymethod}', ". ($card ? $card['idx'] : 'NULL') . ", '$pid'
			)";

	mysql_query($query);
	$payment_idx = mysql_insert_id();

	if($extend_order) {
		$query = "UPDATE payments SET extend_payment_idx=$payment_idx WHERE idx={$extend_order['idx']}";
		mysql_query($query);
	}

	if($order['retype'] == 2 && $buymethod == 'F') {
		$query = "UPDATE payments SET dt_next_payment = DATE('{$order['sdate']}') + INTERVAL 1 MONTH WHERE idx = $payment_idx";
		mysql_query($query);
	}

	//payment_accounts 업데이트
	$dtSettlementPos = $order['edate'];
	if($order['retype'] == 2) {
		$dtSettlementPos = $order['sdate'];
	}

	if(date('Y-m-d H:i') < '2018-05-01 00:00') {    //2018-05-01 기준 단기도 픽업일 기준으로 정산일이 산정되도록함 #1599
		$dtSettlementPos = $order['sdate'];
	}

	$dtSettlement = getSettlementDate($dtSettlementPos);
	$rowdata = $paymentResult['PAY_TYPE']."|R|".$paymentResult['APPROVAL_YMDHMS']."|R|".$paymentResult['SEQ_NO']."|R|".$paymentResult['APPROVAL_NO']."|R|".$paymentResult['CARD_ID']."|R|".$paymentResult['CARD_NM']."|R|".$paymentResult['SELL_MM']."|R|".$paymentResult['ZEROFEE_YN']."|R|".$paymentResult['CERT_YN']."|R|".$paymentResult['CONTRACT_YN'];
	$query = "INSERT INTO payment_accounts(payment_idx, shop_id, order_no, tbtype, buymethod, card_fix_idx, account, rawdata, dt_create, dt_settlement, settlement_per) VALUES (
			$payment_idx, '$shopId', '{$paymentResult['ORDER_NO']}', 'I', '$buymethod', ". ($card ? $card['idx'] : 'NULL') . ", {$paymentResult['AMT']}, '$rowdata', NOW(), '$dtSettlement', {$car['rentshop_per1']}
		)";
	mysql_query($query);

	//쿠폰정보 업데이트

	if(isset($coupon) && $coupon != '0') {
		$query = "UPDATE member_coupons SET dt_use=NOW(), payment_idx=$payment_idx WHERE idx={$coupon['idx']}";
		mysql_query($query);
	}

	//문자 발송
	$kakao = array(
		'order' => $payment_idx,
		'wdate' => date("Y-m-d H:i:s"),
		'sdate' => $order['sdate'],
		'edate' => $order['edate'],
		'model' => $car['modelname'],
		'carnum' => $car['carnum'],
		'ac' => number_format($paymentResult['AMT']).'원',
		'coupon_title' => $discountAccount > 0 ? "\n할인액:" : "",
		'coupon_ac' => $discountAccount > 0 ? number_format($discountAccount).'원' : '',
		'insurance' => $order['insu_exem'] != NULL ? '고객부담금 '.number_format($order['insu_exem']).'원/건' : '자차미가입',
		'total_ac' => number_format($paymentResult['AMT'] + $discountAccount).'원',
		'retype' => $extend_order ? '연장예약' : ($ptype == '1' ? '배달대여': '지점방문'),
		'rentshop' => $car['rentshop_name'].'('.$car['affiliate'].')',
		'rentshop_phone' => phone_number_format($car['rentshop_dphone2']),
		'retype_title' => $extend_order ? '' : ($ptype == '1' ? '배달위치:': '지점위치:'),
		'addr' => $extend_order ? '' : ($ptype == '1' ? $order['addr']: $car['rentshop_addr1'] . " " . $car['rentshop_addr2']),
		'url_position' => 'http://www.rentking.co.kr/rent/position.php?idx='.$payment_idx,
		'name' => $member['name'],
		'member_phone' => phone_number_format($member['cp']),
		'preaccount' => number_format($order['preaccount']).'원'
	);

    if(!$config['production']){
        $member['cp'] = '01034460336';
    }
	sendKakao($config['kakaoAuth'], $member['cp'], 'PAY0107', $kakao);

	$kakao['retype_title'] = $ptype == '1' ? '배달위치:': '';
	$kakao['addr'] = $ptype  == '1' ? $order['addr']: '';

	if($config['production']){
		$query = "SELECT contact FROM rentshop_contacts WHERE rentshop_idx = ".$car['rentshop_idx'];
		$r = mysql_query($query);
		while($row = mysql_fetch_array($r)){
			sendKakao($config['kakaoAuth'], $row['contact'], 'PAY0204', $kakao);
		}

		sendKakao($config['kakaoAuth'], '01020457173', 'PAY0204', $kakao);	//유태석 팀장
	}

	if($config['production']) {
		//linkprice
		include "../linkprice/purchase.rentking.php";

		//ilikeclick
		include "../ilikeclick/purchase.rentking.php";

		//google analytics
		?>
		<script>
			(function (i, s, o, g, r, a, m) {
				i['GoogleAnalyticsObject'] = r;
				i[r] = i[r] || function () {
						(i[r].q = i[r].q || []).push(arguments)
					}, i[r].l = 1 * new Date();
				a = s.createElement(o),
					m = s.getElementsByTagName(o)[0];
				a.async = 1;
				a.src = g;
				m.parentNode.insertBefore(a, m)
			})(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');

			ga('create', 'UA-77251395-1', 'auto');
			ga('require', 'ecommerce');

			ga('ecommerce:addTransaction', {
				'id': '<?=$order['idx']?>',                     	// Transaction ID. Required.
				'affiliation': '<?=$car['rentshop_name']?>(<?=$car['affiliate']?>)', 	// Affiliation or store name.
				'revenue': '<?=$paymentResult['AMT']?>',               			// Grand Total.
				'shipping': '0',                  				// Shipping.
				'tax': '0',                     // Tax.
				'currency': 'KRW'
			});
			ga('ecommerce:addItem', {
				'id': '<?=$order['idx']?>',                     // Transaction ID. Required.
				'name': '<?=$car['modelname'] ?>',    				// Product name. Required.
				'sku': '<?=$car['carnum'] ?>',                 // SKU/code.
				'category': '<?=$order['retype'] == 1 ? '단기' : '장기'?>',         // Category or variation.
				'price': '<?=$paymentResult['AMT']?>',                 // Unit price.
				'quantity': '1',                   // Quantity.
				'currency': 'KRW'
			});
			ga('ecommerce:send');
		</script>
		<?

		//Naver
		?>
		<script type="text/javascript" src="//wcs.naver.net/wcslog.js"></script>
		<script type="text/javascript">
			var _nasa = {};
			_nasa["cnv"] = wcs.cnv("4", "<?=$paymentResult['AMT']?>"); // 전환유형, 전환가치 설정해야함. 설치매뉴얼 참고
		</script>
		<?

		//10PING
		?>
		<script src="//tenping.kr/scripts/cpa/tenping.cpa.V2.min.js"></script>
		<script type="text/javascript">
			TenpingScript.SendConversion();
		</script>
		<?

		//Pixel
		?>
		<!-- Facebook Pixel Code -->
		<script>
			!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
				n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
				n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
				t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
				document,'script','https://connect.facebook.net/en_US/fbevents.js');
			fbq('init', '119460582025199', {em: 'insert_email_variable,'});
		</script>
		<noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=119460582025199&ev=PageView&noscript=1"/></noscript>
		<!-- DO NOT MODIFY -->
		<!-- End Facebook Pixel Code -->
		<script>
			fbq('track', 'Purchase', {
				value: <?=$paymentResult['AMT']?>,
				currency: 'KRW'
			});
		</script>
        <? include_once($_SERVER['DOCUMENT_ROOT']."/old/inc/plugins/progressmedia_cts.php"); ?>
        <script type="text/javascript">
            ex2cts.push('purchase', {value:<?=$paymentResult['AMT']?>, product_id:'<?=$payment_idx?>', currency: 'KRW', order_id:'<?=$order['idx']?>'});
        </script>
		<?
	}

	$msg = $errorCode ? "[$errorCode] $msg" : $msg;
	echo "<script type='text/javascript'>alert('$msg'); location.replace('/member/payment.php'); </script>";
} else {
	$msg = $errorCode ? "[$errorCode] $msg" : $msg;
	echo '<script type="text/javascript">alert("'.$msg.'");history.back();</script>';
}

