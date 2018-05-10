<?php
  // 올앳관련 함수 Include
  //----------------------
  include "./allatutil.php";

	$at_enc       = "";
	$at_data      = "";
	$at_txt       = "";


	// 필수 항목
	$at_cross_key      = "CrossKey";	//상점의 Cross Key
    $at_shop_id        = "ShopId";		//상점ID(최대 20자)
    $at_amt            = "1000";		//금액(최대 10자)
    $at_order_no       = "test0001";	//주문번호(최대 80자)         : 쇼핑몰 고유 주문번호 
	$at_pay_type       = "CARD";		//원거래건의 결제방식[CARD]
	$at_seq_no         = "";			//거래일련번호 (최대  10자리) : 옵션필드임
	
    $at_enc = setValue($at_enc,"allat_shop_id"          ,   $at_shop_id        );
	$at_enc = setValue($at_enc,"allat_amt"              ,   $at_amt            );
	$at_enc = setValue($at_enc,"allat_order_no"         ,   $at_order_no       );
	$at_enc = setValue($at_enc,"allat_pay_type"         ,   $at_pay_type       );
	$at_enc = setValue($at_enc,"allat_seq_no"           ,   $at_seq_no         );
    $at_enc = setValue($at_enc,"allat_test_yn"          ,   "N"                );  //테스트 :Y, 서비스 :N
    $at_enc = setValue($at_enc,"allat_opt_pin"          ,   "NOUSE"            );  //수정금지(올앳 참조 필드)
    $at_enc = setValue($at_enc,"allat_opt_mod"          ,   "APP"              );  //수정금지(올앳 참조 필드)

	$at_data = "allat_shop_id=".$at_shop_id.
			   "&allat_amt=".$at_amt.
			   "&allat_enc_data=".$at_enc.
			   "&allat_cross_key=".$at_cross_key;
	
	// 올앳 결제 서버와 통신 : ApprovalReq->통신함수, $at_txt->결과값
	//----------------------------------------------------------------
	$at_txt = CancelReq($at_data,"SSL");

	// 결제 결과 값 확인
	//------------------
	$REPLYCD   =getValue("reply_cd",$at_txt);        //결과코드
	$REPLYMSG  =getValue("reply_msg",$at_txt);       //결과 메세지

	if( !strcmp($REPLYCD,"0000") ){
		// reply_cd "0000" 일때만 성공
		$CANCEL_YMDHMS    =getValue("cancel_ymdhms",$at_txt);
		$PART_CANCEL_FLAG =getValue("part_cancel_flag",$at_txt);
		$REMAIN_AMT       =getValue("remain_amt",$at_txt);
		$PAY_TYPE         =getValue("pay_type",$at_txt);

		echo "결과코드              : ".$REPLYCD."<br>";
		echo "결과메세지            : ".$REPLYMSG."<br>";
		echo "취소날짜              : ".$CANCEL_YMDHMS."<br>";
		echo "취소구분              : ".$PART_CANCEL_FLAG."<br>";
		echo "잔액                  : ".$REMAIN_AMT."<br>";
		echo "거래방식구분          : ".$PAY_TYPE."<br>";
	}else{
		// reply_cd 가 "0000" 아닐때는 에러 (자세한 내용은 매뉴얼참조)
		// reply_msg 는 실패에 대한 메세지
		echo "결과코드  : ".$REPLYCD."<br>";
		echo "결과메세지: ".$REPLYMSG."<br>";
	}

?>
