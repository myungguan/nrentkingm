<?php
  // 올앳관련 함수 Include
  //----------------------
  include "./allatutil.php";

  //Request Value Define
  //----------------------
  $at_cross_key = "가맹점 CrossKey";     //설정필요 [사이트 참조 - http://www.allatpay.com/servlet/AllatBiz/helpinfo/hi_install_guide.jsp#shop]
  $at_shop_id   = "가맹점 ShopId";       //설정필요

  // 요청 데이터 설정
  //----------------------
  $at_data   = "allat_shop_id=".$at_shop_id.
               "&allat_enc_data=".$_POST["allat_enc_data"].
               "&allat_cross_key=".$at_cross_key;

  // 올앳 서버와 통신 
  //--------------------------
  $at_txt = CertCancelReq($at_data,"SSL");

  // 결제 결과 값 확인
  //------------------
  $REPLYCD   = getValue("reply_cd",$at_txt);        //결과코드
  $REPLYMSG  = getValue("reply_msg",$at_txt);       //결과 메세지

  // 결과값 처리
  //--------------------------------------------------------------------------
  if( !strcmp($REPLYCD,"0000") ){
  // reply_cd "0000" 일때만 성공
	  $FIX_KEY		= getValue("fix_key",$at_txt);
	  $APPLY_YMD	= getValue("apply_ymd",$at_txt);

	  echo "결과코드	: ".$REPLYCD."<br>";
	  echo "결과메세지	: ".$REPLYMSG."<br>";
	  echo "인증키		: ".$FIX_KEY."<br>";
	  echo "해지일		: ".$APPLY_YMD."<br>";

  }else{
  // reply_cd 가 "0000" 아닐때는 에러 (자세한 내용은 매뉴얼참조)
  // reply_msg 는 실패에 대한 메세지
	  echo "결과코드	: ".$REPLYCD."<br>";
	  echo "결과메세지	: ".$REPLYMSG."<br>";
  }

?>
