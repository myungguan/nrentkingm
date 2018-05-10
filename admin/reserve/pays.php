<?php
/**
 * 예약관리 > 정기결제관리
 * admin.rentking.co.kr/reserve.php?code=pays
 */
$sdate = $_REQUEST['sdate'];
$edate = $_REQUEST['edate'];
$mode = $_REQUEST['mode'];

if (!$sdate) {
	$sdate = date("Y-m-01");
}


if (!$edate) {
	$edate = date("Y-m-d");
}

if($mode == 'changeDt') {
	$idx = $_REQUEST['idx'];
	$dt = $_REQUEST['dt'];

	$query = "UPDATE payments SET dt_next_payment='$dt' WHERE idx = $idx";
	mysql_query($query);

	echo "<script type='text/javascript'>alert('변경되었습니다.');location.replace('/reserve.php?code=pays');</script>";


}

if(!$config['production'] && strpos($account['order_no'], 'dev') === false)
	$config['allatTest'] = 'Y';

if ($mode == 'go') {
	$idx = $_REQUEST['idx'];
	$turn = $_REQUEST['turn'];

	$query = "SELECT * FROM payment_accounts pa WHERE payment_idx = $idx ORDER BY idx LIMIT 0, 1";
	$result = mysql_query($query);
	if(mysql_num_rows($result) > 0) {
		$account = mysql_fetch_assoc($result);
	}

	$query = "
		SELECT
			CASE WHEN t1.ddata2 = 0 OR t1.count_total - t1.count_payment > 1 THEN t1.account1 ELSE t1.account2 END charge,
			t1.*
		FROM (
			SELECT
				(CASE WHEN mt.ddata2 < 1 THEN mt.ddata1 ELSE mt.ddata1 + 1 END) count_total,
				(SELECT MAX(turn) FROM payment_accounts WHERE payment_idx = m.idx AND tbtype = 'I') count_payment,
				mt.account1,
				mt.account2,
				mt.ddata2,
				mt.vehicle_idx,
				r.addr1 rentshop_addr1,
				r.name,
				r.affiliate,
				r.per1 rentshop_per1,
				mt.retype,
				mt.sdate,
				mt.edate,
				vs.name modelname,
				mem.cp mem_cp,
				m.*
			FROM payments m
				LEFT JOIN member mem ON m.member_idx = mem.idx
				LEFT JOIN reservation mt ON m.reservation_idx = mt.idx
				LEFT JOIN vehicles v ON mt.vehicle_idx = v.idx
				LEFT JOIN vehicle_models vs ON v.model_idx = vs.idx
				LEFT JOIN rentshop r ON v.rentshop_idx = r.idx
			WHERE
				m.idx = $idx
			) t1
	";
	$order = mysql_fetch_assoc(mysql_query($query));
	$member = sel_query_all("member", " WHERE idx={$order['member_idx']}");

	include $config['basePath']."/sites/payment/allatutil.php";

	$peymantResult = false;

	$card = null;
	$query = "SELECT cf.* FROM payments m LEFT JOIN card_fix cf ON m.card_fix_idx = cf.idx WHERE m.idx = $idx AND cf.dt_delete IS NULL";
	$result = mysql_query($query);
	if(mysql_num_rows($result) > 0) {
		$card = mysql_fetch_assoc($result);
	}



	if (!$card) {
		$msg = '카드 정보가 없습니다.';
	} else {

		$at_cross_key      = $config['allatCrossKey'][0];   //CrossKey값(최대200자)
		$at_fix_key        = $card['fix_key'];   //카드키(최대 24자)
		$at_sell_mm        = "00";   //할부개월값(최대  2자)
		$at_amt            = $order['charge'];   //금액(최대 10자)
		$at_business_type  = $card['cardt'];   //결제자 카드종류(최대 1자)       : 개인(0),법인(1)
		$at_registry_no    = $card['datas'];   //주민번호(최대 13자리)           : szBusinessType=0 일경우
		$at_biz_no         =  $card['datas'];   //사업자번호(최대 20자리)         : szBusinessType=1 일경우
		$at_shop_id        = $config['allatShopId'][0];   //상점ID(최대 20자)
		$at_shop_member_id = substr($member['id'], 0, 20);   //회원ID(최대 20자)               : 쇼핑몰회원ID
		$at_order_no       = $order['reservation_idx'].'.'.($order['count_payment'] + 1).($config['production'] ? '' : '.dev');   //주문번호(최대 80자)             : 쇼핑몰 고유 주문번호
		$at_product_cd     = $order['vehicle_idx'];   //상품코드(최대 1000자)           : 여러 상품의 경우 구분자 이용, 구분자('||':파이프 2개)
		$at_product_nm     = "rentking";   //상품명(최대 1000자)             : 여러 상품의 경우 구분자 이용, 구분자('||':파이프 2개)
		$at_cardcert_yn    = "N";   //카드인증여부(최대 1자)          : 인증(Y),인증사용않음(N),인증만사용(X)
		$at_zerofee_yn     = "N";   //일반/무이자 할부 사용 여부(최대 1자) : 일반(N), 무이자 할부(Y)
		$at_buyer_nm       = "-";   //결제자성명(최대 20자)
		$at_recp_nm        = "-";   //수취인성명(최대 20자)
		$at_recp_addr      = strlen($order['addr']) > 0 ? $order['addr'] : $order['rentshop_addr1'];   //수취인주소(최대 120자)
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

			$msg = '결제가 완료 되었습니다.';
		}else{
			// reply_cd 가 "0000" 아닐때는 에러 (자세한 내용은 매뉴얼참조)
			// reply_msg 는 실패에 대한 메세지
			$paymentResult = false;
			$msg = $REPLYMSG;
		}
	}

	if($paymentResult) {
		//payment_accounts 업데이트
		$dtSettlement = getSettlementDate($order['sdate'], $turn);
		$rowdata = $paymentResult['PAY_TYPE']."|R|".$paymentResult['APPROVAL_YMDHMS']."|R|".$paymentResult['SEQ_NO']."|R|".$paymentResult['APPROVAL_NO']."|R|".$paymentResult['CARD_ID']."|R|".$paymentResult['CARD_NM']."|R|".$paymentResult['SELL_MM']."|R|".$paymentResult['ZEROFEE_YN']."|R|".$paymentResult['CERT_YN']."|R|".$paymentResult['CONTRACT_YN'];
		$query = "INSERT INTO payment_accounts(
		payment_idx, shop_id, order_no, tbtype, buymethod, card_fix_idx, turn, account, rawdata, dt_create, dt_settlement, settlement_per) VALUES (
			{$order['idx']}, '{$config['allatShopId'][0]}', '{$paymentResult['ORDER_NO']}', 'I', 'F', ". ($card ? $card['idx'] : 'NULL') . ", $turn, {$paymentResult['AMT']}, '$rowdata', NOW(), '$dtSettlement', {$order['rentshop_per1']}
		)";
		mysql_query($query);

		if($turn < $order['count_total']) {	//다음 결제일로 변경
			$query = "UPDATE payments SET dt_next_payment = dt_next_payment + INTERVAL 1 MONTH WHERE idx=$idx";
		} else {							//결제 종료
			$query = "UPDATE payments SET dt_next_payment = NULL WHERE idx=$idx";
		}
		mysql_query($query);

		//문자 발송
		$kakao = array(
			'wdate' => date("Y-m-d H:i:s"),
			'sdate' => $order['sdate'],
			'edate' => $order['edate'],
			'model' => $order['modelname'],
			'ac' => number_format($paymentResult['AMT']).'원',
			'count' => "$turn"
		);

        if(!$config['production']){
            $order['mem_cp'] = '01034460336';
        }
		sendKakao($config['kakaoAuth'], $order['mem_cp'], 'PAY0302', $kakao);

		echo "<script type='text/javascript'>alert('$msg'); location.replace('/reserve.php?code=pays');</script>";

	} else {
		echo '<script type="text/javascript">alert("'.$msg.'");history.back();</script>';
	}

	exit;
}

if ($mode == 'change') {
	include $config['basePath']."/sites/payment/allatutil.php";

	$idx = $_POST['idx'];
	$member_idx = $_POST['member_idx'];
	$card = null;

	$query = "SELECT * FROM payment_accounts pa WHERE payment_idx = $idx ORDER BY idx LIMIT 0, 1";
	$result = mysql_query($query);
	if(mysql_num_rows($result) > 0) {
		$account = mysql_fetch_assoc($result);
	}

	if($member_idx != '' && $idx != '') {
		$query = "SELECT cf.* FROM payments m LEFT JOIN card_fix cf ON m.card_fix_idx = cf.idx WHERE m.idx = $idx AND cf.dt_delete IS NULL";
		$r = mysql_query($query);

		if(mysql_num_rows($r) > 0) {
			$card = mysql_fetch_assoc($r);
		}

		$at_cross_key      = $config['allatCrossKey'][0];   //CrossKey값(최대200자)
		$at_shop_id        = $config['allatShopId'][0];   //상점ID(최대 20자)

		//기존 카드 취소
		if($card) {
			$at_fix_key        = $card['fix_key'];			//(필수)카드인증키
			$at_fix_type       = "FIX";			//(옵션)정기과금타입( FIX : 상점정기과금, HOF : 호스팅정기과금, Default : FIX )

			$at_enc = setValue($at_enc,"allat_shop_id"          ,   $at_shop_id        );
			$at_enc = setValue($at_enc,"allat_fix_key"          ,   $at_fix_key        );
			$at_enc = setValue($at_enc,"allat_fix_type"         ,   $at_fix_type       );
			$at_enc = setValue($at_enc,"allat_test_yn"          ,   $config['production'] ? 'N' : $config['allatTest']                );  //테스트 :Y, 서비스 :N
			$at_enc = setValue($at_enc,"allat_opt_pin"          ,   "NOUSE"            );  //수정금지(올앳 참조 필드)
			$at_enc = setValue($at_enc,"allat_opt_mod"          ,   "APP"              );  //수정금지(올앳 참조 필드)

			$at_data = "allat_shop_id=".$at_shop_id.
					   "&allat_enc_data=".$at_enc.
					   "&allat_cross_key=".$at_cross_key;

			$at_txt = CertCancelReq($at_data,"SSL");
			$at_txt = iconv("euc-kr","utf-8",$at_txt);

			// 결제 결과 값 확인
			//------------------
			$REPLYCD   =getValue("reply_cd",$at_txt);        //결과코드
			$REPLYMSG  =getValue("reply_msg",$at_txt);       //결과 메세지

			if (!strcmp($REPLYCD, "0000")
				|| (!$config['production'] && !strcmp($REPLYCD, "0001"))
			) {
				$query = "UPDATE card_fix SET dt_delete=NOW() WHERE idx = {$card['idx']}";
				mysql_query($query);
			} else {

			}
		}

		//카드 등록
		$at_data   = "allat_shop_id=".$at_shop_id.
					 "&allat_enc_data=".$_POST["allat_enc_data"].
					 "&allat_cross_key=".$at_cross_key;

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
			$cardFixIdx = mysql_insert_id();

			$query = "UPDATE payments SET card_fix_idx = $cardFixIdx WHERE idx=$idx";
			mysql_query($query);
			$REPLYMSG = "정상적으로 변경되었습니다.";
		}

		echo "<script type='text/javascript'>alert('$REPLYMSG');location.replace('/reserve.php?code=pays');</script>";
	}
}

$q = "SELECT
		CASE WHEN t1.ddata2 = 0 OR t1.count_total - t1.count_payment > 1 THEN t1.account1 ELSE t1.account2 END charge,
		CASE WHEN t1.dt_next_payment <= CURDATE() THEN 1 ELSE 0 END pay,
		t1.*
	FROM (
		SELECT
			mem.name mem_name,
			mem.id mem_id,
			mem.cp,
			(CASE WHEN mt.ddata2 < 1 THEN mt.ddata1 ELSE mt.ddata1 + 1 END) count_total,
			(SELECT MAX(turn) FROM payment_accounts WHERE payment_idx = m.idx AND tbtype = 'I') count_payment,
			mt.account - m.discount - m.usepoint price_total,
			(SELECT SUM(account - isouts) FROM payment_accounts WHERE payment_idx = m.idx AND tbtype='I') price_payment,
			mt.ddata1,
			mt.ddata2,
			mt.retype,
			vs.name modelname,
			v.carnum,
			mt.sdate,
			mt.edate,
			mt.account,
			mt.account1,
			mt.account2,
			r.name,
			r.affiliate,
			m.*
		FROM payments m
			LEFT JOIN reservation mt ON m.reservation_idx = mt.idx
			LEFT JOIN member mem ON m.member_idx = mem.idx
			LEFT JOIN vehicles v ON mt.vehicle_idx = v.idx
			LEFT JOIN vehicle_models vs ON v.model_idx = vs.idx
			LEFT JOIN rentshop r ON v.rentshop_idx = r.idx
		WHERE
			m.dt_next_payment IS NOT NULL
			AND m.idx != 3186
	) t1
	ORDER BY dt_next_payment, t1.idx";  //장기결제 부분취소건 하드코딩 미노출. admin2.0에서 추후 해결해야함.
$r = mysql_query($q);
?>
<div class="table_2">
	<table class="listTableColor">
		<thead>
		<tr>
			<th>예약번호</th>
			<th>예약일시</th>
			<th>예약자<br>Mobile</th>
			<th>차종</th>
			<th>대여일시<br>반납일시</th>
			<th>회사명<br>지점</th>
			<th>총금액</th>
			<th>결제금액</th>
			<th>회차</th>
			<th>결제예정일</th>
			<th></th>
		</tr>
		</thead>
		<tbody>
		<?
		$total = 0;
		while ($row = mysql_fetch_array($r)) {
			$total += $row['charge'];
			$dtSettlement = getPaymentDateRange(getSettlementDate($row['sdate'], $row['count_payment']+1));
			?>
			<tr>
				<td>
					<a href="#none" onclick="MM_openBrWindow('/popup/orderview.php?idx=<?=$row['idx'];?>','order<?=$row['idx'];?>','scrollbars=yes,width=1150,height=700,top=0,left=0');"><?=$row['idx'];?></a>
				</td>
				<td><?=str_replace(' ', '<br />', $row['dt_create'])?></td>
				<td style="text-align:left;padding:0 5px;"><?=$row['mem_name'];?><br/><?=phone_number_format($row['cp'])?></td>
				<td style="text-align:left;padding:0 5px;"><?=$row['modelname'];?><br/>[<?=$row['carnum'];?>]</td>
				<td><?=date("y.m.d H:i", strtotime($row['sdate']));?><br/><?=date("y.m.d H:i", strtotime($row['edate']));?></td>
				<td style="text-align:left;padding:0 5px;"><?=$row['name'] ?><br /><?=$row['affiliate'];?></td>
				<td style='text-align:right;padding-right:5px;'><?=number_format($row['account']);?>원</td>
				<td style='text-align:right;padding-right:5px;'><?=number_format($row['charge']);?>원</td>
				<td><?=$row['count_payment']+1?>/<?=$row['count_total']?></td>
				<td>
					<input type="text" name="dtNextPayment" value="<?=$row['dt_next_payment'] ?>" class="datePicker" readonly style="width:100px;border:none;height:20px;padding-top:0;padding-right:0;"
						data-parent="#container"
						data-min-date="<?=$dtSettlement['start']?>"
						data-max-date="<?=$dtSettlement['end'] ?>"/>
					<button type="button" class="blackBtn_small changeDtNextPayment" data-idx="<?=$row['idx']?>" data-dt="<?=$row['dt_next_payment'] ?>">변경</button>
				</td>
				<td>
					<?if($row['pay'] == 1) {?>
						<button type="button" class="blackBtn_small" onclick="delok('결제하시겠습니까?','/reserve.php?code=<?=$code;?>&mode=go&idx=<?=$row['idx'];?>&turn=<?=$row['count_payment']+1?>');">결제하기</button>
					<?}?>
					<button type="button" class="blackBtn_small changeCard" data-idx="<?=$row['idx']?>" data-mem-idx="<?=$row['member_idx']?>" data-mem-id="<?=$row['mem_id']?>">카드변경</button>
				</td>
			</tr>
			<?
		}
		if($total > 0) {?>
			<tr style="font-weight:bold;">
				<td colspan="7">합계</td>
				<td><?=number_format($total) ?>원</td>
				<td colspan="3"></td>
			</tr>
		<? } ?>

		</tbody>
	</table>
	<!-- paging     16.11.07 추가  -->

</div>
<form name="allatPayment" id="allatPayment" method="post">
	<!-- 필수 정보 -->
	<input type="hidden" name="allat_enc_data" value="">
	<input type="hidden" placeholder="상점 ID"		name="allat_shop_id"	value="<?=$config['allatShopId'][0] ?>" maxlength="20">
	<input type="hidden" placeholder="주문번호"		name="allat_order_no"	value="" maxlength="70" data-default="<?=date('YmdHis') ?><?=$config['production'] ? '' : '.dev' ?>">
	<input type="hidden" placeholder="Receive URL"	name="shop_receive_url"	value="<?=$config['scheme']?>://<?=$_SERVER['HTTP_HOST']?>/sites/payment/allat_receive.php">
	<!-- 필수 정보 -->

	<!-- 옵션 정보 -->
	<input type="hidden" placeholder="회원ID"					name="allat_pmember_id" value="<?=substr($member['id'], 0, 20)?>" maxlength="20">
	<input type="hidden" placeholder="승인금액"					name="allat_amt" value="" maxlength="20">
	<input type="hidden" placeholder="상품명"					name="allat_product_nm" value="rentking" maxlength="100">
	<input type="hidden" placeholder="결제 정보 수신 E-mail"	name="allat_email_addr" value="<?=substr($member['id'], 0, 50)?>" maxlength="50">
	<input type="hidden" placeholder="주민번호"					name="allat_registry_no" value="" maxlength="13">
	<input type="hidden" placeholder="정기과금 타입"			name="allat_fix_type" value="" maxlength="3">
	<input type="hidden" placeholder="테스트 여부"				name="allat_test_yn" value="<?=$config['production'] ? 'N' : $config['allatTest'] ?>" maxlength="1">
	<!-- 옵션 정보 -->
</form>

<form name="changeForm" id="changeForm" method="post">
	<input type="hidden" name="allat_enc_data" value="" />
	<input type="hidden" name="idx" value="" />
	<input type="hidden" name="dt" value="" />
	<input type="hidden" name="member_idx" value="" />
</form>
<script type="text/javascript" charset="EUC-KR" src="https://tx.allatpay.com/common/NonAllatPayREPlus.js"></script>
<script type="text/javascript">
	var orderIdx = null;
	var memIdx = null;

	function result_submit(resultCd, resultMsg, encData) {
		var $changeForm = $('#changeForm');
		if(resultCd != '0000') {
			alert(resultMsg);
		} else {
			$changeForm.find('input[name="allat_enc_data"]').val(encData);
			$changeForm.find('input[name="idx"]').val(orderIdx);
			$changeForm.find('input[name="member_idx"]').val(memIdx);
			$changeForm.attr('action', '/reserve.php?code=<?=$code;?>&mode=change').data('payment', true).submit();
		}
	}

	$(function() {
		$(document)
			.on('click', '.changeCard', function() {
				var $btn = $(this);
				var idx = $btn.data('idx');
				var mIdx = $btn.data('memIdx');
				var mId = $btn.data('memId');
				orderIdx = idx;
				memIdx = mIdx;

				var $allatPayment = $('form#allatPayment');

				var $allatOrderNo = $allatPayment.find('input[name="allat_order_no"]');
				$allatOrderNo.val(idx + '.' + $allatOrderNo.data('default'));
				$allatPayment.find('input[name="allat_pmember_id"]').val(mId.substring(0, 19));
				$allatPayment.find('input[name="allat_email_addr"]').val(mId.substring(0, 49));

				Allat_Plus_Fix(allatPayment, '0', '0');
			})
			.on('click', '.changeDtNextPayment', function() {
				var $btn = $(this);
				var idx = $btn.data('idx');
				var dt = $btn.prev('input[name="dtNextPayment"]').val();

				if(dt == $btn.data('dt')) {
					alert('변경할 날짜를 입력하세요');
				} else if(confirm('변경하시겠습니까?')) {
					var $changeForm = $('#changeForm');
					$changeForm.find('input[name="idx"]').val(idx);
					$changeForm.find('input[name="dt"]').val(dt);
					$changeForm.attr('action', '/reserve.php?code=<?=$code;?>&mode=changeDt').submit();
				}
			})
	})
</script>