<?php
	// �þܰ��� �Լ� Include
	//----------------------
	include "./allatutil.php";

	$at_shop_id		= "";
	$at_cross_key	= "";

	$at_data	= "allat_shop_id=".$at_shop_id."&allat_cross_key=".$at_cross_key;

	// PHP5 �̻� SSL ��밡��
	$at_txt		= CardlistReq($at_data, "SSL");
	// $at_txt		= CardlistReq($at_data, "NOSSL"); // PHP5 ���Ϲ����� ���

	$REPLYCD	= getValue("reply_cd", $at_txt);
	$REPLYMSG	= getValue("reply_msg", $at_txt);

	$card_num	= getValue("card_num", $at_txt);
	$vbank_num	= getValue("vbank_num", $at_txt);
	$ticket_num	= getValue("ticket_num", $at_txt);

	$card_list		= array();
	$card_id		= array();
	$card_nm		= array();
	$vbank_list		= array();
	$vbank_id		= array();
	$vbank_nm		= array();
	$ticket_list	= array();
	$ticket_id		= array();
	$ticket_nm		= array();

	if(!strcmp($REPLYCD, "0000")) {
		for($i=0; $i<$card_num; $i++) {
			$temp = getValue("card_list_".$i, $at_txt);
			$card_list = explode("|", $temp);
			$card_id[$i] = $card_list[0];
			$card_nm[$i] = $card_list[1];
		}
		for($i=0; $i<$vbank_num; $i++) {
			$temp = getValue("vbank_list_".$i, $at_txt);
			$vbank_list = explode("|", $temp);
			$vbank_id[$i] = $vbank_list[0];
			$vbank_nm[$i] = $vbank_list[1];
		}
		for($i=0; $i<$ticket_num; $i++) {
			$temp = getValue("ticket_list_".$i, $at_txt);
			$ticket_list = explode("|", $temp);
			$ticket_id[$i] = $ticket_list[0];
			$ticket_nm[$i] = $ticket_list[1];
		}
	} else {
		echo("<script>");
		echo("alert('����ڵ� : ".$REPLYCD." / ����޼��� : ".$REPLYMSG."');");
		echo("</script>");		
	}
?>

<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=euc-kr">
<title>New All@PayPro �ֹ����� �Է�(����)</title>
<style><!--
body { font-family:����ü; font-size:12px; }
td   { font-family:����ü; font-size:12px; }
.title { font-family:����ü; font-size:16px; }
.head { background-color:#EFF7FC; padding: 3 3 0 5 }
.body { background-color:#FFFFFF; padding: 3 3 0 5  }
.nbody { background-color:#FFFFCC; padding: 3 3 0 5  }
//--></style>

<script language=JavaScript charset='euc-kr' src="http://tx.allatpay.com/common/NonAllatPayREPlus.js"></script>

<script language=Javascript>
function use_select_card(sId) {	// ī�帮��Ʈ
    var sel_id = document.getElementById(sId);
<?
	if(!strcmp($REPLYCD, "0000")) {
		for($i=0; $i<$card_num; $i++) {
?>
	var oOption = document.createElement("OPTION");
	sel_id.options.add(oOption);
	oOption.innerHTML = "<?echo $card_nm[$i]?>";
	oOption.value = "<?echo $card_id[$i]?>";
<?
		}
    }
?>
}

function use_select_vbank(sId) {	// ������¸���Ʈ
    var sel_id = document.getElementById(sId);
<?
	if(!strcmp($REPLYCD, "0000")) {
		for($i=0; $i<$vbank_num; $i++) {
?>
	var oOption = document.createElement("OPTION");
	sel_id.options.add(oOption);
	oOption.innerHTML = "<?echo $vbank_nm[$i]?>";
	oOption.value = "<?echo $vbank_id[$i]?>";
<?
		}
    }
?>
}

function use_select_ticket(sId){	// ��ǰ�Ǹ���Ʈ
    var sel_id = document.getElementById(sId);
<?
	if(!strcmp($REPLYCD, "0000")) {
		for($i=0; $i<$ticket_num; $i++) {
?>
	var oOption = document.createElement("OPTION");
	sel_id.options.add(oOption);
	oOption.innerHTML = "<?echo $ticket_nm[$i]?>";
	oOption.value = "<?echo $ticket_id[$i]?>";
<?
		}
    }
?>
}

// �������ܺ� �Է��ʵ�
function chk_app(what) {
	if(what == "CARD") {
		document.sendFm.allat_pay_type.value = "CARD"
		CARD_APP.style.display = "";
		CARD.style.display = "";
		NOR_3D.style.display = "";
		ISP_CARD.style.display = "";
		NOR.style.display = "none";
		ISP_NOR.style.display = "";
		BANK_APP.style.display = "none";
		BANK_OPT.style.display = "none";
		VBANK.style.display = "none";
		TICKET.style.display = "none";
	} else if(what == "NOR") {
		document.sendFm.allat_pay_type.value = "NOR"
		CARD_APP.style.display = "";
		CARD.style.display = "none";
		NOR_3D.style.display = "";
		ISP_CARD.style.display = "none";
		NOR.style.display = "";
		ISP_NOR.style.display = "";
		BANK_APP.style.display = "none";
		BANK_OPT.style.display = "none";
		VBANK.style.display = "none";
		TICKET.style.display = "none";
	} else if(what == "ABANK") {
		document.sendFm.allat_pay_type.value = "ABANK"
		CARD_APP.style.display = "none";
		CARD.style.display = "none";
		NOR_3D.style.display = "none";
		ISP_CARD.style.display = "none";
		NOR.style.display = "none";
		ISP_NOR.style.display = "none";
		BANK_APP.style.display = "";
		BANK_OPT.style.display = "";
		VBANK.style.display = "none";
		TICKET.style.display = "none";
	} else if(what == "VBANK") {
		document.sendFm.allat_pay_type.value = "VBANK"
		CARD_APP.style.display = "none";
		CARD.style.display = "none";
		NOR_3D.style.display = "none";
		ISP_CARD.style.display = "none";
		NOR.style.display = "none";
		ISP_NOR.style.display = "none";
		BANK_APP.style.display = "";
		BANK_OPT.style.display = "";
		VBANK.style.display = "";
		TICKET.style.display = "none";
	} else if(what == "HP") {
		document.sendFm.allat_pay_type.value = "HP"
		CARD_APP.style.display = "none";
		CARD.style.display = "none";
		NOR_3D.style.display = "none";
		ISP_CARD.style.display = "none";
		NOR.style.display = "none";
		ISP_NOR.style.display = "none";
		BANK_APP.style.display = "none";
		BANK_OPT.style.display = "none";
		VBANK.style.display = "none";
		TICKET.style.display = "none";
	} else if(what == "TICKET") {
		document.sendFm.allat_pay_type.value = "TICKET"
		CARD_APP.style.display = "none";
		CARD.style.display = "none";
		NOR_3D.style.display = "none";
		ISP_CARD.style.display = "none";
		NOR.style.display = "none";
		ISP_NOR.style.display = "none";
		BANK_APP.style.display = "none";
		BANK_OPT.style.display = "none";
		VBANK.style.display = "none";
		TICKET.style.display = "";
	} else {
		return;
	}
}

// ����â ȣ��
function ftn_app(dfm) {
	var ret;

	var app_type = "";
	for(i=0; i< dfm.chkapp.length; i++) {
		if(dfm.chkapp[i].checked == true) {
			app_type = dfm.chkapp[i].value;
			break;
		}
	}

	if(app_type == "CARD") {
		var cIndex = dfm.allat_card_code_sel.selectedIndex;
		if(cIndex == 0) {
			alert('�����Ͻ� ī��縦 �����Ͻñ� �ٶ��ϴ�.');
			dfm.allat_card_code_sel.focus();
			return;
		}
		dfm.allat_card_code.value = dfm.allat_card_code_sel[cIndex].value;
		dfm.allat_pay_type.value = "CARD";
	} else if(app_type == "NOR") {
		dfm.allat_pay_type.value = "NOR";
	} else if(app_type == "ABANK") {
		dfm.allat_pay_type.value = "ABANK";
	} else if(app_type == "VBANK") {
		var cIndex = dfm.allat_vbank_code_sel.selectedIndex;
		if(cIndex == 0) {
			alert('�����Ͻ� ������ �����Ͻñ� �ٶ��ϴ�.');
			dfm.allat_vbank_code_sel.focus();
			return; 
		}
		dfm.allat_vbank_cd.value = dfm.allat_vbank_code_sel[cIndex].value; 
		dfm.allat_pay_type.value = "VBANK";
	} else if(app_type == "HP") {
		dfm.allat_pay_type.value = "HP";
	} else if(app_type == "TICKET") {
		var tIndex = dfm.allat_ticket_code_sel.selectedIndex;
		if( tIndex == 0 ) {
			alert('�����Ͻ� ��ǰ���� �����Ͻñ� �ٶ��ϴ�.');
			dfm.allat_ticket_code_sel.focus();
			return;
		}
		dfm.allat_ticket_cd.value = dfm.allat_ticket_code_sel[tIndex].value;
		dfm.allat_pay_type.value = "TICKET";
	} else {
		alert("���������� �������ּ���.");
		return;
	}

	Allat_Plus_Approval(dfm, "0", "0"); /* ������ ���� (����â ũ��, 320*360) */ 
}

// ����� ��ȯ( receive ���������� ȣ�� )
function result_submit(result_cd,result_msg,enc_data) {
	Allat_Plus_Close();

	if(result_cd != '0000') {
		alert(result_cd + " : " + result_msg);
	} else {
		sendFm.allat_enc_data.value = enc_data;

		sendFm.action = "allat_approval.php";
		sendFm.method = "post";
		sendFm.target = "_self";
		sendFm.submit();
	}
}
</script>

</head>
<body>
<p align="center" class="title"><u>New All@PayPro�� ���ο�û ����������(����)</u></p>

<!-- HTML : Form ���� -->
<form name="sendFm"  method="POST">
<table border="0" cellpadding="0" cellspacing="1" bgcolor="#606060" width="1152" align="center" style="TABLE-LAYOUT: fixed;">
	<font color="red">�� �ʼ� ���� : <b>���� ���� ����(����)</b></font>
	<tr>
		<td class="head" colspan="5">
			<input type="radio" name="chkapp" value="CARD" onclick="javascript:chk_app('CARD')">ī����������
			<input type="radio" name="chkapp" value="NOR" onclick="javascript:chk_app('NOR')">ī���Ϲݰ���
			<input type="radio" name="chkapp" value="ABANK" onclick="javascript:chk_app('ABANK')">������ü
			<input type="radio" name="chkapp" value="VBANK" onclick="javascript:chk_app('VBANK')">������(�������)
			<input type="radio" name="chkapp" value="HP" onclick="javascript:chk_app('HP')">�޴�������
			<input type="radio" name="chkapp" value="TICKET" onclick="javascript:chk_app('TICKET')">��ǰ�ǰ���
			&nbsp;&nbsp;&nbsp;&nbsp<a target="_new" href="https://www.allatpay.com/servlet/AllatBizV2/support/SupportInstallGuideCL?menu_id=m040301"><b>[FAQ]</b></a>
		</td>
	</tr>
</table>

<!-- ���ο�û �� ������������� ���� -->
<table border="0" cellpadding="0" cellspacing="1" bgcolor="#606060" width="1152" align="center" style="TABLE-LAYOUT: fixed;">
</br>
	<font color="red">�� �ʼ� ���� : <b>���� �ʼ� �׸�(����)</b></font>
	<tr>
		<td width="140" class="head">�׸�</td>
		<td width="160" class="head">���� ��</td>
		<td width="70"  class="head">&nbsp�ִ����<br>(��������)</td>
		<td width="150"  class="head">������</td>
		<td class="head">���� ����</td>
	</tr>
	<tr>
		<td class="head">���� ID</td>
		<td class="body"><input type="text" name="allat_shop_id" value="" size="19" maxlength="20"></td>
		<td class="body">20</td>
		<td class="body">allat_shop_id</td>
		<td class="body">Allat���� �߱��� ���� ���� ID</td>
	</tr>
	<tr>
		<td class="head">�ֹ���ȣ</td>
		<td class="body"><input type="text" name="allat_order_no" value="" size="19" maxlength="80"></td>
		<td class="body">80</td>
		<td class="body">allat_order_no</td>
		<td class="body">���θ����� ����ϴ� ���� �ֹ���ȣ : ����,��������ǥ('),ū����ǥ(") ��� �Ұ�</td>
	</tr>
	<tr>
		<td class="head">���αݾ�</td>
		<td class="body"><input type="text" name="allat_amt" value="" size="19" maxlength="16"></td>
		<td class="body">16</td>
		<td class="body">allat_amt</td>
		<td class="body">�� �����ݾ� : ����(0~9)�� ��밡��</td>
	</tr>
	<tr>
		<td class="head">ȸ��ID</td>
		<td class="body"><input type="text" name="allat_pmember_id" value="" size="19" maxlength="20"></td>
		<td class="body">20</td>
		<td class="body">allat_pmember_id</td>
		<td class="body">���θ��� ȸ��ID : ����,��������ǥ('),ū����ǥ(") ��� �Ұ�</td>
	</tr>
	<tr>
		<td class="head">��ǰ�ڵ�</td>
		<td class="body"><input type="text" name="allat_product_cd" value="" size="19" maxlength="1000"></td>
		<td class="body">1000</td>
		<td class="body">allat_product_cd</td>
		<td class="body">���� ��ǰ�� ��� ������ �̿�, ������('||':������ 2��) : ����,��������ǥ('),ū����ǥ(") ��� �Ұ�</td>
	</tr>
	<tr>
		<td class="head">��ǰ��</td>
		<td class="body"><input type="text" name="allat_product_nm" value="" size="19" maxlength="1000"></td>
		<td class="body">1000</td>
		<td class="body">allat_product_nm</td>
		<td class="body">���� ��ǰ�� ��� ������ �̿�, ������('||':������ 2��)</td>
	</tr>
	<tr>
		<td class="head">�����ڼ���</td>
		<td class="body"><input type="text" name="allat_buyer_nm" value="" size="19" maxlength="20"></td>
		<td class="body">20</td>
		<td class="body">allat_buyer_nm</td>
		<td class="body"></td>
	</tr>
	<tr>
		<td class="head">�����μ���</td>
		<td class="body"><input type="text" name="allat_recp_nm" value="" size="19" maxlength="20"></td>
		<td class="body">20</td>
		<td class="body">allat_recp_nm</td>
		<td class="body"></td>
	</tr>
	<tr>
		<td class="head">�������ּ�</td>
		<td class="body"><input type="text" name="allat_recp_addr" value="" size="19" maxlength="120"></td>
		<td class="body">120</td>
		<td class="body">allat_recp_addr</td>
		<td class="body"></td>
	</tr>
	<tr>
		<td class="head">�����������</td>
		<td class="body"><input type="text" name="allat_pay_type" value="" size="19" maxlength="10" readonly></td>
		<td class="body">10</td>
		<td class="body">allat_pay_type</td>
		<td class="body">
			ī���������� - CARD</br>
			ī���Ϲݰ��� - NOR </br>
			��Ÿ ���� - �������Ա�:VBANK, �޴�������:HP, ��ǰ�ǰ���:TICKET</td>
	</tr>
	<tr>
		<td class="head">������������URL<br>(shop_receive_url)</td>
		<td class="body"><input type="text" name="shop_receive_url" value="" size="19" maxlength="120"></td>
		<td class="body">120</td>
		<td class="body">shop_receive_url</td>
		<td class="body"></td>
	</tr>
	<tr>
		<td class="head">�ֹ�������ȣȭ�ʵ�</td>
		<td class="body"><font color="red">���� �ڵ����� ������</font></td>
		<td class="body">-</td>
		<td class="body">allat_enc_data</td>
		<td class="body"><font color="red">&ltinput type=hidden name=allat_enc_data value=''&gt<br>
		�ذ��������� ��ȣȭ�Ǿ� �����Ǵ°�.</br>hidden field�� �����ؾ���</font>
		</td>
	<input type=hidden name="allat_enc_data" value="">
	</tr>
</table>
<br>
<!-- ���� �ɼ� ���� -->
<table border="0" cellpadding="0" cellspacing="1" bgcolor="#606060" width="1152" align="center" style="TABLE-LAYOUT: fixed;">
	<font color=blue>�� ���� �ɼ� ���� : <b>���� �ɼ� �׸�(����)</b></font>
	<tr>
		<td width="140" class="head">��ǰ ����ũ�� ����</td>
		<td width="160" class="body"><input type=text name="allat_escrow_yn" value="N" size="19" maxlength="1"></td>
		<td width="70"  class="body">1</td>
		<td width="150" class="body">allat_escrow_yn</td>        
		<td class="body">
			����(Y),������(N) -> ���Է°�</br>
			������ü/�������Ա�(�������)�� ����ϴ� ���� �� ����� �ʿ��� �ǹ��ŷ��� ����ũ�θ� �����ؾ���
		</td>
	</tr>
	<tr>
		<td class="head">�� ����ũ�� ������ȣ</td>
		<td class="body"><input type="text" name="allat_escrow_no" value="" size="19" maxlength="10"></td>
		<td class="body">10</td>
		<td class="body">allat_escrow_no</td>        
		<td class="body">4~10�ڸ� ����ũ�� ��й�ȣ(����,�������)-> �� �Է°�<br>
		<font color="blue">���� ����ũ�� Ȯ�νÿ� �����</font></td>
	</tr>
	<tr>
		<td class="head">��ǰ �ǹ� ����</td>
		<td class="body"><input type=text name="allat_real_yn" value="N" size="19" maxlength="1"></td>
		<td class="body">1</td>
		<td class="body">allat_real_yn</td>        
		<td class="body">��ǰ�� �ǹ��� ���(Y), ��ǰ�� �ǹ��� �ƴҰ��(N)<br>
		<font color="blue">�ػ�ǰ�� �ǹ��̰�, 10���� �̻� ������ü/������ ���񽺿��� ����ũ�� ���θ� Y/N�� �� üũ�ؾ���</font><br></td>
	</tr>    
	<tr>
		<td class="head">������ Email</td>
		<td class="body"><input type=text name="allat_email_addr" value="" size="19" maxlength="50"></td>
		<td class="body">50</td>
		<td class="body">allat_email_addr</td>
		<td class="body"><font color="red">����ũ�� ����( allat_escrow_yn = Y )���� �ʼ� �ʵ���</font></td>
	</tr>
	<tr>
		<td class="head">�׽�Ʈ ����</td>
		<td class="body"><input type=text name="allat_test_yn" value="N" size="19" maxlength="1"></td>
		<td class="body">1</td>
		<td class="body">allat_test_yn</td>        
		<td class="body">
			�׽�Ʈ(Y),����(N) - Default �� : N</br>
			�׽�Ʈ ������ ī��糪 ������� ������ �����Ƿ�, ���� ������ ���� ����</br>
			�׽�Ʈ ������ ���� ������ ���� �����Ƿ�, �ŷ���ȸ���� ��ȸ�Ͻ� �� ����</br>
			�׽�Ʈ ������ ����� : '0001' / ���� ���� ������ '0000'�� ���ϵ�</br>
			<font color="red">���½ÿ��� �ݵ�� 'N'���� �����Ͽ�����</font>
		</td>
	</tr>
	<tr>
		<td class="head">�����Ⱓ</td>
		<td class="body"><input type="text" name="allat_provide_date" value="" size="19" maxlength="25"></td>
		<td class="body">25</td>
		<td class="body">allat_provide_date</td>        
		<td class="body">������ ��ǰ�� �����Ⱓ : YYYY.MM.DD ~ YYYY.MM.DD</td>
	</tr>
	<tr>
		<td class="head">����</td>
		<td class="body"><input type="text" name="allat_gender" value="" size="19" maxlength="1"></td>
		<td class="body">1</td>
		<td class="body">allat_gender</td>        
		<td class="body">������ ����, ����(M)/����(F)</td>
	</tr>
	<tr>
		<td class="head">�������</td>
		<td class="body"><input type="text" name="allat_birth_ymd" value="" size="19" maxlength="8"></td>
		<td class="body">8</td>
		<td class="body">allat_birth_ymd</td>        
		<td class="body">�������� ������� 8��, YYYYMMDD����</td>
	</tr>
</table>

<!-- ī����� �ʼ��׸� -->
<div id="CARD" style="{display:none}">
</br>
<table border="0" cellpadding="0" cellspacing="1" bgcolor="#606060" width="1152" align="center" style="TABLE-LAYOUT: fixed;">
	<font color="red">�� ī�� ���� ���� : <b>���� �ʼ� �׸�</b></font>
	<tr>
        <td width="140" class="head" >ī������</td>
        <td width="160" class="body">
            <select name="allat_card_code_sel" id="allat_card_code_sel">
                <option value="">ī�带 �����ϼ���</option>
            </select>
        </td>
        <td width="70"  class="body">1</td>
        <td width="150" class="body">allat_card_code_sel</td>          
        <td class="body">
			ī���ڵ� Select BOX ����</br>
			ISP : BC, ����, ����, ����, ����, ����, �츮</br>
			3D : �� ��</td>
        <script language="Javascript">use_select_card("allat_card_code_sel")</script>
    </tr>
	<tr>
		<td width="140" class="head">ī���ڵ�</td>
		<td width="160" class="body">allat_card_code</td>
		<td width="70"  class="body">-</td>
		<td width="150" class="body">allat_card_code</td>          
		<td class="body">
			ī�弱�ý� �ڵ�����</br>
			<font color="red">&ltinput type=hidden name=allat_card_code value=''&gt</font>
			<input type="hidden" name="allat_card_code" value="">
		</td>
	</tr>
</table>
</div>

<!-- �Ϲݰ��� -->
<div id="NOR" style="{display:none}">
</br>
<table border="0" cellpadding="0" cellspacing="1" bgcolor="#606060" width="1152" align="center" style="TABLE-LAYOUT: fixed;">
	<font color="red">�� �Ϲ� ���� : <b>���� �ʼ� �׸�</b></font>
	<tr>
		<td width="140" class="head">ī�� ��ȣ</td>
		<td width="160" class="body"><input type=text name="allat_card_no" value="" size="19" maxlength="16"></td>
		<td width="70"  class="body">16</td>
		<td width="150" class="body">allat_card_no</td>        
		<td class="body"></td>
	</tr>
	<tr>
		<td class="head">ī�� ��ȿ�Ⱓ</td>
		<td class="body"><input type=text name="allat_cardvalid_ym" value="" size="19" maxlength="4"></td>
		<td class="body">4</td>
		<td class="body">allat_cardvalid_ym</td>
		<td class="body">YYMM ( ��: 2007�� 10�� ==> 0710 )</td>
	</tr>
	<tr>
		<td class="head">ī�� ��й�ȣ</td>
		<td class="body"><input type="password" name="allat_passwd_no" value="" size="19" maxlength="2"></td>
		<td class="body">2</td>
		<td class="body">allat_passwd_no</td>
		<td class="body">2�ڸ� ( 0815 ==> 08 ) <font color="red">���ʼ� ���� : allat_cardcert_yn=Y</font></td>
	</tr>
	<tr>
		<td class="head">������ ī������</td>
		<td class="body"><input type="text" name="allat_business_type" value="" size="19" maxlength="1"></td>
		<td class="body">1</td>
		<td class="body">allat_business_type</td>
		<td class="body">����(0)/����(1)</td>
	</tr>
	<tr>
		<td class="head">����ڹ�ȣ</td>
		<td class="body"><input type="text" name="allat_biz_no" value="" size="19" maxlength="20"></td>
		<td class="body">20</td>
		<td class="body">allat_biz_no</td>
		<td class="body"><font color="red">���ʼ� ���� : allat_business_type = 1, allat_cert_yn=Y or X</font></td>
	</tr>
</table>
</div>

<!-- 3D, �Ϲ� ������ �ʼ� �ʵ� -->
<div id="NOR_3D" style="{display:none}">
</br>
<table border="0" cellpadding="0" cellspacing="1" bgcolor="#606060" width="1152" align="center" style="TABLE-LAYOUT: fixed;">
	<font color="red">�� 3D, �Ϲ� ���� ���� : <b>���� �ʼ� �׸�</b></font>
	<tr>
		<td width="140" class="head">�Һΰ�����</td>
		<td width="160" class="body"><input type="text" name="allat_sell_mm" value="" size="19" maxlength="2"></td>
		<td width="70"  class="body">2</td>
		<td width="150" class="body">allat_sell_mm</td>                  
		<td class="body">00,0,01,1 : �Ͻú� (�Һ� ���� 2���� �̻����� ���� )</td>
	</tr>
</table>
</div>

<!-- ISP (�ɼ�) -->
<div id="ISP_CARD" style="{display:none}">
</br>
<table border="0" cellpadding="0" cellspacing="1" bgcolor="#606060" width="1152" align="center" style="TABLE-LAYOUT: fixed;">
	<font color="blue">�� ISP ���� ���� : <b>���� �ɼ� �׸�</b></font>
	<tr>
		<td width="140" class="head">���� ������ ����<br>�ּ� �ݾ�</td>
		<td width="160" class="body"></td>
		<td width="70"  class="body">2</td>
		<td width="150" class="body">KVP_OACERT_INF</td>          
		<td class="body">KVP ���������� ���� �ּ� �ݾ�(30���� ���� ����� ���)<br>
		<font color="blue">&ltinput type=hidden name=KVP_OACERT_INF value='NONE'&gt</font>
		<input type="hidden" name="KVP_OACERT_INF" value="NONE"></td>
	</tr>
	<tr>
		<td class="head">����ī���</td>
		<td class="nbody"><input type=text name="allat_select_card_id" value="" size="19" maxlength="2"></td>
		<td class="nbody">2</td>
		<td class="nbody">allat_select_card_id</td>        
		<td class="nbody">
			ISP ī���</br>
			BC : 61, ���� : 62, �츮 : 75, ���� : 73, ���� : 37, ���� : 35, ���� : 34
		</td>
	</tr>
</table>
</div>

<!-- ISP, �Ϲݰ��� (�ɼ�) -->
<div id="ISP_NOR" style="{display:none}">
</br>
<table border="0" cellpadding="0" cellspacing="1" bgcolor="#606060" width="1152" align="center" style="TABLE-LAYOUT: fixed;">
	<font color="blue">�� ISP, �Ϲ� ���� ���� : <b>���� �ɼ� �׸�</b></font>
	<tr>
		<td width="140" class="head">�ֹι�ȣ</td>
		<td width="160" class="body"><input type="text" name="allat_registry_no" value="" size="19" maxlength="13"></td>
		<td width="70"  class="body">13</td>
		<td width="150" class="body">allat_registry_no</td>
		<td class="body">
		<font color="red">���ʼ� ���� : allat_business_type = 0, allat_cert_yn=Y or X </font><br>
		<font color="red">�Ϲݰ��� - allat_business_type = 0 �� �� �ֹι�ȣ ���ڸ� 7�ڸ�</font><br>
		<font color="blue">ISP     - �ֹι�ȣ 13�ڸ�(ISP�϶��� Ư�� ����ڸ� �����.��κ� ������� ����)</font></td>
	</tr>
	<tr>
		<td class="head">KB���հ��� ���뿩��</td>
		<td class="body"><input type=text name="allat_kbcon_point_yn" value="" size="19" maxlength="1"></td>
		<td class="body">1</td>
		<td class="body">allat_kbcon_point_yn</td>        
		<td class="body">KB���հ��� ���뿩�� : ����(Y), ������(N)</td>
	</tr>
</table>
</div>

<!-- ī��, �Ϲݰ��� (�ɼ�) -->
<div id="CARD_APP" style="{display:none}">
</br>
<table border="0" cellpadding="0" cellspacing="1" bgcolor="#606060" width="1152" align="center" style="TABLE-LAYOUT: fixed;">
	<!--//----- ī�� ������ �ɼ� �ʵ�, ���� ��ü�ÿ��� ������� ���� --------//-->
	<font color="blue">�� ī�� ���� ���� : <b>���� �ɼ� �׸�</b></font>
	<tr>
		<td width="140" class="head">�Ϲ�/������ �Һ�<br>��뿩��</td>
		<td width="160" class="body"><input type=text name="allat_zerofee_yn" value="N" size="19" maxlength="1"></td>
		<td width="70"  class="body">1</td>
		<td width="150" class="body">allat_zerofee_yn</td>
		<td class="body">
			�Ϲ�(N), ������ �Һ�(Y) - Default : N</br>
			�ſ�ī�� �����ݾ��� 50,000�� �̻��� �� �Һΰ� �����մϴ�.</br>
			<font color="red">�������Һ� ���θ� 'Y'�� �ϴ���, �þܿ� �������Һ� ��簡 ��ϵǾ� ���� ���� ī����̰ų�, �Һΰ����� �Ͻú��� ���� ������� �ʽ��ϴ�.</font></br>
			<a target="_new" href="https://www.allatpay.com/servlet/AllatBizV2/support/SupportFaqCL?menu_id=m040201&type=detail&page=7&seq_no=1143"><b>[�����ڽ�û ���]</b></a>
		</td>
	</tr>
	<tr>
		<td class="head">����Ʈ ��뿩��</td>
		<td class="body"><input type=text name="allat_bonus_yn" value="N" size="19" maxlength="1"></td>
		<td class="body">1</td>
		<td class="body">allat_bonus_yn</td>        
		<td class="body">
			���(Y), ��� ����(N) - Default : N</br>
			������ ����Ʈ������(�Ｚ, ����, BC ��) �̿�� ����Ʈ�� ����Ͽ� �����ϴ� ����</br>			
			<font color="red">������ ����Ʈ �������� �����Ǿ� �־����.</br>
			Ư������Ʈ�� ��� ���Ұ�</br>
			��)���� M����Ʈ : �Һΰ���+40, allat_bonus_yn='N'</br></font>
		</td>
	</tr>
	<tr>
		<td class="head">ī�� ���� ����</td>
		<td class="body"><input type=text name="allat_cardcert_yn" value="N" size="19" maxlength="1"></td>
		<td class="body">1</td>
		<td class="body">allat_cardcert_yn</td>        
		<td class="body">����(Y), ���� ������(N), ������ ���(X) - Default : N</td>
	</tr>
</table>
</div>

<!-- �������(BANK) �ʼ� �׸� -->
<div id="BANK_APP" style="{display:none}">
</br>
<table border="0" cellpadding="0" cellspacing="1" bgcolor="#606060" width="1152" align="center" style="TABLE-LAYOUT: fixed;">
	<font color="red">�� ���� ���� ���� : <b>���� �ʼ� �׸�</b></font>
	<tr>
		<td width="140" class="head">�����ָ�</td>
		<td width="160" class="body"><input type="text" name="allat_account_nm" value="" size="19" maxlength="30"></td>
		<td width="70"  class="body">30</td>
		<td width="150" class="body">allat_account_nm</td>   
		<td class="body"></td>
	</tr>
	<tr>
		<td class="head">���ݿ����� ��� ����</td>
		<td class="body"><input type=text name="allat_cash_yn" value="N" size="19" maxlength="1"></td>
		<td class="body">1</td>
		<td class="body">allat_cash_yn</td>         
		<td class="body"><a target="_new" href="https://www.allatpay.com/servlet/AllatBizV2/svcinfo/SvcInfoMainCL?menu_id=m010601"><b>[����]</b></a>
		</td>
	</tr>
	<tr>
		<td class="head">���ݿ����� ��������</td>
		<td class="body"><input type="text" name="allat_cert_no" value="" size="19" maxlength="18"></td>
		<td class="body">18</td>
		<td class="body">allat_cert_no</td>          
		<td class="body">�ڵ�����ȣ, �ֹι�ȣ, ����ڹ�ȣ, ���ݿ�����ī���ȣ
		<font color="blue">[ allat_cash_yn='Y'�� ��쿡 ��� ]</font></td>
	</tr>
	<tr>
		<td class="head">����� ����� ��ȣ</td>
		<td class="body"><input type="text" name="allat_reg_business_no" value="" size="19" maxlength="10"></td>
		<td class="body">10</td>
		<td class="body">allat_reg_business_no</td>           
		<td class="body"><font color="blue">����ID�� �ٸ���쿡 ���</font></td>
	</tr>
</table>
</div>

<!-- �������(BANK) �ɼ� �׸� -->
<div id="BANK_OPT" style="{display:none}">
</br>
<table border="0" cellpadding="0" cellspacing="1" bgcolor="#606060" width="1152" align="center" style="TABLE-LAYOUT: fixed;">
	<font color="blue">�� ���� ���� ���� : <b>���� �ɼ� �׸�</b></font>
	<tr>
		<td width="140" class="head">��������</td>
		<td width="160" class="body"><input type="text" name="allat_tax_yn" value="Y" size="19" maxlength="1"></td>
		<td width="70"  class="body">1</td>
		<td width="150" class="body">allat_tax_yn</td>                  
		<td class="body">Y(����), N(�����) - Default : Y</td>
	</tr>
</table>
</div>

<!-- ������[�������](VBANK) -->
<div id="VBANK" style="{display:none}">
</br>
<table border="0" cellpadding="0" cellspacing="1" bgcolor="#606060" width="1152" align="center" style="TABLE-LAYOUT: fixed;">
	<font color="red">�� ������[�������](VBANK)  : <b>���� �ʼ� �׸�</b></font>
	<tr>
        <td width="140" class="head" >���� �ڵ�</td>
        <td width="160" class="body">
            <select name="allat_vbank_code_sel" id="oSelect">
                <option value="">���༱��</option>
            </select>
        </td>
        <td width="70" class="body">-</td>
        <td width="150" class="body">allat_vbank_code</td>                   
        <td class="body">�����ڵ� Select BOX ����</td>
        <script language="Javascript">use_select_vbank("oSelect")</script>        
    </tr>
	<tr>
		<td width="140" class="head">�����ڵ�</td>
		<td width="160" class="body">allat_vbank_cd</td>
		<td width="70"  class="body">-</td>
		<td width="150" class="body">allat_vbank_cd</td>          
		<td class="body">
			���༱�ý� �ڵ�����</br>
			<font color="red">&ltinput type=hidden name=allat_vbank_cd value=''&gt</font>
			<input type="hidden" name="allat_vbank_cd" value="">
		</td>
	</tr>
	<tr>
		<td class="head">�Աݿ�����</td>
		<td class="body"><input type="text" name="allat_income_expect_ymd" value="" size="19" maxlength="8"></td>
		<td class="body">8</td>
		<td class="body">allat_income_expect_ymd</td>
		<td class="body">�������� �Ա� ������</td>
	</tr>
	<tr>
		<td class="head">������(�������)<br>���� Key</td>
		<td class="body"><input type="text" name="allat_account_key" value="" size="19" maxlength="20"></td>
		<td class="body">20</td>
		<td class="body">allat_account_key</td>
		<td class="body">Key �� ä�� ��� ���� ���� Key<br>
		<font color="red">Key�� ä�� ��� ������ �ʼ� �ʵ���</font></td>
	</tr>
</table>

<!-- ������[�������](VBANK) �ɼ� -->
<table border="0" cellpadding="0" cellspacing="1" bgcolor="#606060" width="1152" align="center" style="TABLE-LAYOUT: fixed;">
</br>
	<font color="blue">�� ������[�������](VBANK) : <b>���� �ɼ� �׸�</b></font>
	<tr>
		<td width="140" class="head">�Աޱ�����</td>
		<td width="160" class="nbody"><input type="text" name="allat_income_limit_ymd" value="" size="19" maxlength="8"></td>
		<td width="70"  class="nbody">8</td>
		<td width="150" class="nbody">allat_income_limit_ymd</td>
		<td class="nbody">��������� �Աݱ�����</td>
	</tr>
</table>
</div>

<!-- ��ǰ�ǰ���(TICKET) �ʼ� �׸� -->
<div id="TICKET" style="{display:none}">
</br>
<table border="0" cellpadding="0" cellspacing="1" bgcolor="#606060" width="1152" align="center" style="TABLE-LAYOUT: fixed;">
	<font color="red">�� ��ǰ�� ���� ���� : <b>���� �ʼ� �׸�</b></font>
	<tr>
        <td width="140" class="head" >��ǰ�� ���� ����</td>
        <td width="160" class="body">
            <select name="allat_ticket_code_sel" id="oSelectTicket">
                <option value="">��ǰ���� �����ϼ���</option>
            </select>
        </td>
        <td width="70"  class="body">1</td>
        <td width="150" class="body">allat_ticket_code</td>          
        <td class="body">��ǰ���ڵ� Select BOX ����</td>
        <script language="Javascript">use_select_ticket("oSelectTicket")</script>
    </tr>
	<tr>
		<td width="140" class="head">��ǰ���ڵ�</td>
		<td width="160" class="body">allat_ticket_cd</td>
		<td width="70"  class="body">2</td>
		<td width="150" class="body">allat_ticket_cd</td>          
		<td class="body">
			��ǰ�Ǽ��ý� �ڵ�����</br>
			<font color="red">&ltinput type=hidden name=allat_ticket_cd value=''&gt</font>
			<input type="hidden" name="allat_ticket_cd" value="">
		</td>
	</tr>
</table>
</div>

<p align="center">
<table border="0" cellpadding="0" cellspacing="1" width="1152" align="center">
	<tr>
		<td align="center">
		<input type="button" value=" ���� ��û " name="app_btn" onClick="javascript:ftn_app(document.sendFm);">
		</td>
	</tr>
</table>
</p>
</form>
</body>
</html>