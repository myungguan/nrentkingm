<?php
  // �þܰ��� �Լ� Include
  //----------------------
  include "./allatutil.php";

	$at_enc       = "";
	$at_data      = "";
	$at_txt       = "";


	// �ʼ� �׸�
	$at_cross_key      = "CrossKey";	//(�ʼ�)������ Cross Key
    $at_shop_id        = "ShopId";		//(�ʼ�)����ID(�ִ� 20��)
	$at_fix_key        = "";			//(�ʼ�)ī������Ű
	$at_fix_type       = "";			//(�ɼ�)�������Ÿ��( FIX : �����������, HOF : ȣ�����������, Default : FIX )
	
    $at_enc = setValue($at_enc,"allat_shop_id"          ,   $at_shop_id        );
	$at_enc = setValue($at_enc,"allat_fix_key"          ,   $at_fix_key        );
	$at_enc = setValue($at_enc,"allat_fix_type"         ,   $at_fix_type       );
    $at_enc = setValue($at_enc,"allat_test_yn"          ,   "N"                );  //�׽�Ʈ :Y, ���� :N
    $at_enc = setValue($at_enc,"allat_opt_pin"          ,   "NOUSE"            );  //��������(�þ� ���� �ʵ�)
    $at_enc = setValue($at_enc,"allat_opt_mod"          ,   "APP"              );  //��������(�þ� ���� �ʵ�)

	$at_data = "allat_shop_id=".$at_shop_id.
			   "&allat_enc_data=".$at_enc.
			   "&allat_cross_key=".$at_cross_key;
	
	// �þ� ���� ������ ��� : CertCancelReq->����Լ�, $at_txt->�����
	//--------------------------
	$at_txt = CertCancelReq($at_data,"SSL");

	// ���� ��� �� Ȯ��
	//------------------
	$REPLYCD   =getValue("reply_cd",$at_txt);        //����ڵ�
	$REPLYMSG  =getValue("reply_msg",$at_txt);       //��� �޼���

	// ����� ó��
	//--------------------------------------------------------------------------
	if( !strcmp($REPLYCD,"0000") ){
		// reply_cd "0000" �϶��� ����
		$FIX_KEY          =getValue("fix_key",$at_txt);
		$APPLY_YMD        =getValue("apply_ymd",$at_txt);

		echo "����ڵ�              : ".$REPLYCD."<br>";
		echo "����޼���            : ".$REPLYMSG."<br>";
		echo "����Ű                : ".$FIX_KEY."<br>";
		echo "������                : ".$APPLY_YMD."<br>";

	}else{
		// reply_cd �� "0000" �ƴҶ��� ���� (�ڼ��� ������ �Ŵ�������)
		// reply_msg �� ���п� ���� �޼���
		echo "����ڵ�  : ".$REPLYCD."<br>";
		echo "����޼���: ".$REPLYMSG."<br>";
	}
?>
