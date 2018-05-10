<?php
  // 올앳관련 함수 Include
  //----------------------
  include "./allatutil.php";

	$at_enc       = "";
	$at_data      = "";
	$at_txt       = "";


	// 필수 항목
	$at_cross_key      = "CrossKey";	//(필수)상점의 Cross Key
    $at_shop_id        = "ShopId";		//(필수)상점ID(최대 20자)
	$at_fix_key        = "";			//(필수)카드인증키
	$at_fix_type       = "";			//(옵션)정기과금타입( FIX : 상점정기과금, HOF : 호스팅정기과금, Default : FIX )
	
    $at_enc = setValue($at_enc,"allat_shop_id"          ,   $at_shop_id        );
	$at_enc = setValue($at_enc,"allat_fix_key"          ,   $at_fix_key        );
	$at_enc = setValue($at_enc,"allat_fix_type"         ,   $at_fix_type       );
    $at_enc = setValue($at_enc,"allat_test_yn"          ,   "N"                );  //테스트 :Y, 서비스 :N
    $at_enc = setValue($at_enc,"allat_opt_pin"          ,   "NOUSE"            );  //수정금지(올앳 참조 필드)
    $at_enc = setValue($at_enc,"allat_opt_mod"          ,   "APP"              );  //수정금지(올앳 참조 필드)

	$at_data = "allat_shop_id=".$at_shop_id.
			   "&allat_enc_data=".$at_enc.
			   "&allat_cross_key=".$at_cross_key;
	
	// 올앳 결제 서버와 통신 : CertCancelReq->통신함수, $at_txt->결과값
	//--------------------------
	$at_txt = CertCancelReq($at_data,"SSL");

	// 결제 결과 값 확인
	//------------------
	$REPLYCD   =getValue("reply_cd",$at_txt);        //결과코드
	$REPLYMSG  =getValue("reply_msg",$at_txt);       //결과 메세지

	// 결과값 처리
	//--------------------------------------------------------------------------
	if( !strcmp($REPLYCD,"0000") ){
		// reply_cd "0000" 일때만 성공
		$FIX_KEY          =getValue("fix_key",$at_txt);
		$APPLY_YMD        =getValue("apply_ymd",$at_txt);

		echo "결과코드              : ".$REPLYCD."<br>";
		echo "결과메세지            : ".$REPLYMSG."<br>";
		echo "인증키                : ".$FIX_KEY."<br>";
		echo "해지일                : ".$APPLY_YMD."<br>";

	}else{
		// reply_cd 가 "0000" 아닐때는 에러 (자세한 내용은 매뉴얼참조)
		// reply_msg 는 실패에 대한 메세지
		echo "결과코드  : ".$REPLYCD."<br>";
		echo "결과메세지: ".$REPLYMSG."<br>";
	}
?>
