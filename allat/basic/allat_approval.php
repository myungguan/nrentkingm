<?php
  // �þܰ��� �Լ� Include
  //----------------------
  include "./allatutil.php";

  //Request Value Define
  //----------------------
  $at_cross_key = "e4725bb6a7d25d7bef1eb6636bfce862";	//�����ʿ� [����Ʈ ���� - http://www.allatpay.com/servlet/AllatBiz/support/sp_install_guide_scriptapi.jsp#shop]
  $at_shop_id   = "rentking";		//�����ʿ�
  $at_amt=500000;						//���� �ݾ��� �ٽ� ����ؼ� ������ ��(��ŷ����), ( session, DB ��� )

  // ��û ������ ����
  //----------------------
  $at_data   = "allat_shop_id=".$at_shop_id.
               "&allat_amt=".$at_amt.
               "&allat_enc_data=".$_POST["allat_enc_data"].
               "&allat_cross_key=".$at_cross_key;


  // �þ� ���� ������ ��� : ApprovalReq->����Լ�, $at_txt->�����
  //----------------------------------------------------------------
  // PHP5 �̻� SSL ��밡��
  $at_txt = ApprovalReq($at_data,"SSL");
  // $at_txt = ApprovalReq($at_data, "NOSSL"); // PHP5 ���Ϲ����� ���
  // �� �κп��� �α׸� ����� ���� �����ϴ�.
  // (�þ� ���� ������ ��� �Ŀ� �α׸� �����, ��ſ����� ���� �����ľ��� �����մϴ�.)

  // ���� ��� �� Ȯ��
  //------------------
  $REPLYCD   =getValue("reply_cd",$at_txt);        //����ڵ�
  $REPLYMSG  =getValue("reply_msg",$at_txt);       //��� �޼���

  // ����� ó��
  //--------------------------------------------------------------------------
  // ��� ���� '0000'�̸� ������. ��, allat_test_yn=Y �ϰ�� '0001'�� ������.
  // ���� ����   : allat_test_yn=N �� ��� reply_cd=0000 �̸� ����
  // �׽�Ʈ ���� : allat_test_yn=Y �� ��� reply_cd=0001 �̸� ����
  //--------------------------------------------------------------------------
  if( !strcmp($REPLYCD,"0000") ){
    // reply_cd "0000" �϶��� ����
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
    $SAVE_AMT         =getValue("save_amt",$at_txt);
	$CARD_POINTDC_AMT =getValue("card_pointdc_amt",$at_txt);
    $BANK_ID          =getValue("bank_id",$at_txt);
    $BANK_NM          =getValue("bank_nm",$at_txt);
    $CASH_BILL_NO     =getValue("cash_bill_no",$at_txt);
    $ESCROW_YN        =getValue("escrow_yn",$at_txt);
    $ACCOUNT_NO       =getValue("account_no",$at_txt);
    $ACCOUNT_NM       =getValue("account_nm",$at_txt);
    $INCOME_ACC_NM    =getValue("income_account_nm",$at_txt);
    $INCOME_LIMIT_YMD =getValue("income_limit_ymd",$at_txt);
    $INCOME_EXPECT_YMD=getValue("income_expect_ymd",$at_txt);
    $CASH_YN          =getValue("cash_yn",$at_txt);
    $HP_ID            =getValue("hp_id",$at_txt);
    $TICKET_ID        =getValue("ticket_id",$at_txt);
    $TICKET_PAY_TYPE  =getValue("ticket_pay_type",$at_txt);
    $TICKET_NAME      =getValue("ticket_nm",$at_txt);	
    $PARTCANCEL_YN    =getValue("partcancel_yn",$at_txt);	

    echo "����ڵ�              : ".$REPLYCD."<br>";
    echo "����޼���            : ".$REPLYMSG."<br>";
    echo "�ֹ���ȣ              : ".$ORDER_NO."<br>";
    echo "���αݾ�              : ".$AMT."<br>";
    echo "���Ҽ���              : ".$PAY_TYPE."<br>";
    echo "�����Ͻ�              : ".$APPROVAL_YMDHMS."<br>";
    echo "�ŷ��Ϸù�ȣ          : ".$SEQ_NO."<br>";
    echo "����ũ�� ���� ����    : ".$ESCROW_YN."<br>";
    echo "=============== �ſ� ī�� ===============================<br>";
    echo "���ι�ȣ              : ".$APPROVAL_NO."<br>";
    echo "ī��ID                : ".$CARD_ID."<br>";
    echo "ī���                : ".$CARD_NM."<br>";
    echo "�Һΰ���              : ".$SELL_MM."<br>";
    echo "�����ڿ���            : ".$ZEROFEE_YN."<br>";   //������(Y),�Ͻú�(N)
    echo "��������              : ".$CERT_YN."<br>";      //����(Y),������(N)
    echo "�����Ϳ���            : ".$CONTRACT_YN."<br>";  //3�ڰ�����(Y),��ǥ������(N)
    echo "���̺� ���� �ݾ�      : ".$SAVE_AMT."<br>";
    echo "����Ʈ���� ���� �ݾ�  : ".$CARD_POINTDC_AMT."<br>";
    echo "=============== ���� ��ü / ������� ====================<br>";
    echo "����ID                : ".$BANK_ID."<br>";
    echo "�����                : ".$BANK_NM."<br>";
    echo "���ݿ����� �Ϸ� ��ȣ  : ".$CASH_BILL_NO."<br>";
    echo "=============== ������� ================================<br>";
    echo "���¹�ȣ              : ".$ACCOUNT_NO."<br>";
    echo "�Աݰ��¸�            : ".$INCOME_ACC_NM."<br>";
    echo "�Ա��ڸ�              : ".$ACCOUNT_NM."<br>";
    echo "�Աݱ�����            : ".$INCOME_LIMIT_YMD."<br>";
    echo "�Աݿ�����            : ".$INCOME_EXPECT_YMD."<br>";
    echo "���ݿ�������û ����   : ".$CASH_YN."<br>";
    echo "=============== �޴��� ���� =============================<br>";
    echo "�̵���Ż籸��        : ".$HP_ID."<br>";
    echo "=============== ��ǰ�� ���� =============================<br>";
    echo "��ǰ�� ID             : ".$TICKET_ID."<br>";
    echo "��ǰ�� �̸�           : ".$TICKET_NAME."<br>";
    echo "��������              : ".$TICKET_PAY_TYPE."<br>";

	echo "�κ���Ұ��ɿ��� : ".$PARTCANCEL_YN."<br>";

  }else{
    // reply_cd �� "0000" �ƴҶ��� ���� (�ڼ��� ������ �Ŵ�������)
    // reply_msg �� ���п� ���� �޼���
    echo "����ڵ�  : ".$REPLYCD."<br>";
    echo "����޼���: ".$REPLYMSG."<br>";
  }

/*
    [�ſ�ī�� ��ǥ��� ����]

    ������ ���������� �Ϸ�Ǹ� �Ʒ��� �ҽ��� �̿��Ͽ�, ������ �ſ�ī�� ��ǥ�� ������ �� �ֽ��ϴ�.
    ��ǥ ��½� �������̵�� �ֹ���ȣ�� �����Ͻñ� �ٶ��ϴ�.

    var urls ="http://www.allatpay.com/servlet/AllatBizPop/member/pop_card_receipt.jsp?shop_id=�������̵�&order_no=�ֹ���ȣ";
    window.open(urls,"app","width=410,height=650,scrollbars=0");

    ���ݿ����� ��ǥ �Ǵ� �ŷ�Ȯ�μ� ��¿� ���� ���Ǵ� �þ����� ����Ʈ�� 1:1����� �̿��Ͻðų�
    02) 3788-9990 ���� ��ȭ �ֽñ� �ٶ��ϴ�.

    ��ǥ��� �������� ���� �þ� Ȩ�������� �Ϻην�, Ȩ������ ���� ���� ������ ���Ͽ� ������ ���� �Ǵ� URL ������ ���� ��
    �ֽ��ϴ�. Ȩ������ ���� ���� ������ ���� ���, ��ǥ��� URL�� Ȯ���Ͻñ� �ٶ��ϴ�.
*/
?>
