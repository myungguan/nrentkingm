<?php
	// 올앳관련 함수 Include
	//----------------------
	include "./allatutil.php";

	//Request Value Define
	//----------------------
	/*
	$at_cross_key = "가맹점 CrossKey";     //설정필요 [사이트 참조 - http://www.allatpay.com/servlet/AllatBiz/support/sp_install_guide_scriptapi.jsp#shop]
	$at_shop_id   = "가맹점 ShopId";       //설정필요
	*/

	//------------------------ Test Code ---------------------
	$at_cross_key = $_POST["test_cross_key"];
	$at_shop_id = $_POST["allat_shop_id"];
	//--------------------------------------------------------

	// 요청 데이터 설정
	//----------------------
	$at_data   = "allat_shop_id=".$at_shop_id.
	"&allat_enc_data=".$_POST["allat_enc_data"].
	"&allat_cross_key=".$at_cross_key;


	// 올앳 결제 서버와 통신 : CashCanReq->통신함수, $at_txt->결과값
	//----------------------------------------------------------------
	// PHP5 이상만 SSL 사용가능
	$at_txt = CashCanReq($at_data,"SSL");
	// $at_txt = CashCanReq($at_data, "NOSSL"); // PHP5 이하버전일 경우
	// 이 부분에서 로그를 남기는 것이 좋습니다.
	// (올앳 결제 서버와 통신 후에 로그를 남기면, 통신에러시 빠른 원인파악이 가능합니다.)

	// 결제 결과 값 확인
	//------------------
	$REPLYCD   =getValue("reply_cd",$at_txt);        //결과코드
	$REPLYMSG  =getValue("reply_msg",$at_txt);       //결과 메세지

	// 결과값
	//----------------------------------------------------------------
	$REPLYCD     = getValue("reply_cd",$at_txt);	//결과코드
	$REPLYMSG    = getValue("reply_msg",$at_txt);	//결과 메세지

	// 결과값 처리
	//--------------------------------------------------------------------------
	// 결과 값이 '0000'이면 정상임. 단, allat_test_yn=Y 일경우 '0001'이 정상임.
	// 실제 결제   : allat_test_yn=N 일 경우 reply_cd=0000 이면 정상
	// 테스트 결제 : allat_test_yn=Y 일 경우 reply_cd=0001 이면 정상
	//--------------------------------------------------------------------------
	if( !strcmp($REPLYCD,"0000") ){
		// reply_cd "0000" 일때만 성공
		$CANCEL_YMDHMS    =getValue("cancel_ymdhms",$at_txt);
		$PART_CANCEL_FLAG =getValue("part_cancel_flag",$at_txt);
		$REMAIN_AMT       =getValue("remain_amt",$at_txt);

		echo "결과코드	: ".$REPLYCD."<br>";
		echo "결과메세지	: ".$REPLYMSG."<br>";
		echo "취소일시	: ".$CANCEL_YMDHMS."<br>";
		echo "취소여부	: ".$PART_CANCEL_FLAG."<br>"; //취소: 0, 부분취소: 1
		echo "잔액		: ".$REMAIN_AMT."<br>";
	}else{
		// reply_cd 가 "0000" 아닐때는 에러 (자세한 내용은 매뉴얼참조)
		// reply_msg 가 실패에 대한 메세지
		echo "결과코드	: ".$REPLYCD."<br>";
		echo "결과메세지	: ".$REPLYMSG."<br>";
	}
?>
