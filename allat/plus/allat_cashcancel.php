<?php
	// �þܰ��� �Լ� Include
	//----------------------
	include "./allatutil.php";

	//Request Value Define
	//----------------------
	/*
	$at_cross_key = "������ CrossKey";     //�����ʿ� [����Ʈ ���� - http://www.allatpay.com/servlet/AllatBiz/support/sp_install_guide_scriptapi.jsp#shop]
	$at_shop_id   = "������ ShopId";       //�����ʿ�
	*/

	//------------------------ Test Code ---------------------
	$at_cross_key = $_POST["test_cross_key"];
	$at_shop_id = $_POST["allat_shop_id"];
	//--------------------------------------------------------

	// ��û ������ ����
	//----------------------
	$at_data   = "allat_shop_id=".$at_shop_id.
	"&allat_enc_data=".$_POST["allat_enc_data"].
	"&allat_cross_key=".$at_cross_key;


	// �þ� ���� ������ ��� : CashCanReq->����Լ�, $at_txt->�����
	//----------------------------------------------------------------
	// PHP5 �̻� SSL ��밡��
	$at_txt = CashCanReq($at_data,"SSL");
	// $at_txt = CashCanReq($at_data, "NOSSL"); // PHP5 ���Ϲ����� ���
	// �� �κп��� �α׸� ����� ���� �����ϴ�.
	// (�þ� ���� ������ ��� �Ŀ� �α׸� �����, ��ſ����� ���� �����ľ��� �����մϴ�.)

	// ���� ��� �� Ȯ��
	//------------------
	$REPLYCD   =getValue("reply_cd",$at_txt);        //����ڵ�
	$REPLYMSG  =getValue("reply_msg",$at_txt);       //��� �޼���

	// �����
	//----------------------------------------------------------------
	$REPLYCD     = getValue("reply_cd",$at_txt);	//����ڵ�
	$REPLYMSG    = getValue("reply_msg",$at_txt);	//��� �޼���

	// ����� ó��
	//--------------------------------------------------------------------------
	// ��� ���� '0000'�̸� ������. ��, allat_test_yn=Y �ϰ�� '0001'�� ������.
	// ���� ����   : allat_test_yn=N �� ��� reply_cd=0000 �̸� ����
	// �׽�Ʈ ���� : allat_test_yn=Y �� ��� reply_cd=0001 �̸� ����
	//--------------------------------------------------------------------------
	if( !strcmp($REPLYCD,"0000") ){
		// reply_cd "0000" �϶��� ����
		$CANCEL_YMDHMS    =getValue("cancel_ymdhms",$at_txt);
		$PART_CANCEL_FLAG =getValue("part_cancel_flag",$at_txt);
		$REMAIN_AMT       =getValue("remain_amt",$at_txt);

		echo "����ڵ�	: ".$REPLYCD."<br>";
		echo "����޼���	: ".$REPLYMSG."<br>";
		echo "����Ͻ�	: ".$CANCEL_YMDHMS."<br>";
		echo "��ҿ���	: ".$PART_CANCEL_FLAG."<br>"; //���: 0, �κ����: 1
		echo "�ܾ�		: ".$REMAIN_AMT."<br>";
	}else{
		// reply_cd �� "0000" �ƴҶ��� ���� (�ڼ��� ������ �Ŵ�������)
		// reply_msg �� ���п� ���� �޼���
		echo "����ڵ�	: ".$REPLYCD."<br>";
		echo "����޼���	: ".$REPLYMSG."<br>";
	}
?>
