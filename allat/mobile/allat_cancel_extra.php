<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=euc-kr">
<title>CancelPhp</title>
</head>
<body>
<?php
    include "./allatutil.php";

    // Set CrossKey 
    // -------------------------------------------------------------------
    $at_cross_key="";  //가맹점 CrossKey값 (치환필요)

    // Set Value
    // -------------------------------------------------------------------
    $at_shop_id="ShopId";         //ShopId값     (최대  20자리)
    $at_order_no="1234567890";    //주문번호     (최대  80자리)
    $at_amt="0";                  //취소금액     (최대  10자리)
    $at_pay_type="CARD";          //원거래건의 결제방식[카드:CARD,계좌이체:ABANK]
    $at_seq_no="12345";           //거래일련번호 (최대  10자리) : 옵션필드임
    $at_test_yn="N";              //테스트 여부
    $at_opt_pin="NOUSE";
    $at_opt_mod="APP";

    // set Enc Data
    // -------------------------------------------------------------------
    $at_enc=setValue($at_enc,"allat_shop_id",$at_shop_id);
    $at_enc=setValue($at_enc,"allat_order_no",$at_order_no);
    $at_enc=setValue($at_enc,"allat_amt",$at_amt);
    $at_enc=setValue($at_enc,"allat_pay_type",$at_pay_type);
    $at_enc=setValue($at_enc,"allat_seq_no",$at_seq_no);
    $at_enc=setValue($at_enc,"allat_test_yn",$at_test_yn);
    $at_enc=setValue($at_enc,"allat_opt_pin",$at_opt_pin);
    $at_enc=setValue($at_enc,"allat_opt_mod",$at_opt_mod);

    // Set Request Data
    //--------------------------------------------------------------------
    $at_data   = "allat_shop_id=".$at_shop_id.
                 "&allat_enc_data=".$at_enc.
                 "&allat_cross_key=".$at_cross_key;

    // 올앳과 통신 후 결과값 받기 : CancelReq->통신함수
    //-----------------------------------------------------------------
    $at_txt=CancelReq($at_data,"SSL");

    // 결과값
    //----------------------------------------------------------------
    $REPLYCD     = getValue("reply_cd",$at_txt);       //결과코드
    $REPLYMSG    = getValue("reply_msg",$at_txt);      //결과 메세지

    // 결과값 처리
    //------------------------------------------------------------------
    if( !strcmp($REPLYCD,"0000") ){
      // reply_cd "0000" 일때만 성공
      $CANCEL_YMDHMS=getValue("cancel_ymdhms",$at_txt);
      $PART_CANCEL_FLAG=getValue("part_cancel_flag",$at_txt);
      $REMAIN_AMT=getValue("remain_amt",$at_txt);
      $PAY_TYPE=getValue("pay_type",$at_txt);
      echo "결과코드: ".$REPLYCD."<br>";
      echo "결과메세지: ".$REPLYMSG."<br>";
      echo "취소날짜: ".$CANCEL_YMDHMS."<br>";
      echo "취소구분: ".$PART_CANCEL_FLAG."<br>";
      echo "잔액: ".$REMAIN_AMT."<br>";
      echo "거래방식구분: ".$PAY_TYPE."<br>";
    } else {
      // reply_cd 가 "0000" 아닐때는 에러 (자세한 내용은 매뉴얼참조)
      // reply_msg 가 실패에 대한 메세지
      echo "결과코드: ".$REPLYCD."<br>";
      echo "결과메세지: ".$REPLYMSG."<br>";
    }
?>
</body>
</html>
