<?php
  // �þܰ��� �Լ� Include
  //----------------------
  include "./allatutil.php";

  //Request Value Define
  //----------------------
  $at_cross_key = "e4725bb6a7d25d7bef1eb6636bfce862";     //�����ʿ� [����Ʈ ���� - http://www.allatpay.com/servlet/AllatBiz/helpinfo/hi_install_guide.jsp#shop]
  $at_shop_id   = "rentking";       //�����ʿ�

  // ��û ������ ����
  //----------------------
  $at_data   = "allat_shop_id=".$at_shop_id.
               "&allat_enc_data=".$_POST["allat_enc_data"].
               "&allat_cross_key=".$at_cross_key;


  // �þ� ������ ��� 
  //--------------------------
  $at_txt = CertRegReq($at_data,"SSL");

  // ���� ��� �� Ȯ��
  //------------------
  $REPLYCD   =getValue("reply_cd",$at_txt);        //����ڵ�
  $REPLYMSG  =getValue("reply_msg",$at_txt);       //��� �޼���

  // ����� ó��
  //--------------------------------------------------------------------------
  if( !strcmp($REPLYCD,"0000") ){
    // reply_cd "0000" �϶��� ����
    $FIX_KEY	= getValue("fix_key",$at_txt);
    $APPLY_YMD	= getValue("apply_ymd",$at_txt);
	
    echo "����ڵ�	: ".$REPLYCD."<br>";
    echo "����޼���	: ".$REPLYMSG."<br>";
    echo "����Ű	: ".$FIX_KEY."<br>";
    echo "������	: ".$APPLY_YMD."<br>";
  }else{
    // reply_cd �� "0000" �ƴҶ��� ���� (�ڼ��� ������ �Ŵ�������)
    // reply_msg �� ���п� ���� �޼���
    echo "����ڵ�  : ".$REPLYCD."<br>";
    echo "����޼���: ".$REPLYMSG."<br>";
  }
?>