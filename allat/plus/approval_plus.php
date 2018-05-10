<?php
	// 올앳관련 함수 Include
	//----------------------
	include "./allatutil.php";

	$at_shop_id		= "";
	$at_cross_key	= "";

	$at_data	= "allat_shop_id=".$at_shop_id."&allat_cross_key=".$at_cross_key;

	// PHP5 이상만 SSL 사용가능
	$at_txt		= CardlistReq($at_data, "SSL");
	// $at_txt		= CardlistReq($at_data, "NOSSL"); // PHP5 이하버전일 경우

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
		echo("alert('결과코드 : ".$REPLYCD." / 결과메세지 : ".$REPLYMSG."');");
		echo("</script>");		
	}
?>

<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=euc-kr">
<title>New All@PayPro 주문정보 입력(통합)</title>
<style><!--
body { font-family:굴림체; font-size:12px; }
td   { font-family:굴림체; font-size:12px; }
.title { font-family:굴림체; font-size:16px; }
.head { background-color:#EFF7FC; padding: 3 3 0 5 }
.body { background-color:#FFFFFF; padding: 3 3 0 5  }
.nbody { background-color:#FFFFCC; padding: 3 3 0 5  }
//--></style>

<script language=JavaScript charset='euc-kr' src="http://tx.allatpay.com/common/NonAllatPayREPlus.js"></script>

<script language=Javascript>
function use_select_card(sId) {	// 카드리스트
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

function use_select_vbank(sId) {	// 가상계좌리스트
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

function use_select_ticket(sId){	// 상품권리스트
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

// 결제수단별 입력필드
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

// 결제창 호출
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
			alert('결제하실 카드사를 선택하시기 바랍니다.');
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
			alert('결제하실 은행을 선택하시기 바랍니다.');
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
			alert('결제하실 상품권을 선택하시기 바랍니다.');
			dfm.allat_ticket_code_sel.focus();
			return;
		}
		dfm.allat_ticket_cd.value = dfm.allat_ticket_code_sel[tIndex].value;
		dfm.allat_pay_type.value = "TICKET";
	} else {
		alert("결제수단을 선택해주세요.");
		return;
	}

	Allat_Plus_Approval(dfm, "0", "0"); /* 포지션 지정 (결제창 크기, 320*360) */ 
}

// 결과값 반환( receive 페이지에서 호출 )
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
<p align="center" class="title"><u>New All@PayPro™ 승인요청 예제페이지(통합)</u></p>

<!-- HTML : Form 설정 -->
<form name="sendFm"  method="POST">
<table border="0" cellpadding="0" cellspacing="1" bgcolor="#606060" width="1152" align="center" style="TABLE-LAYOUT: fixed;">
	<font color="red">◆ 필수 정보 : <b>결제 수단 선택(공통)</b></font>
	<tr>
		<td class="head" colspan="5">
			<input type="radio" name="chkapp" value="CARD" onclick="javascript:chk_app('CARD')">카드인증결제
			<input type="radio" name="chkapp" value="NOR" onclick="javascript:chk_app('NOR')">카드일반결제
			<input type="radio" name="chkapp" value="ABANK" onclick="javascript:chk_app('ABANK')">계좌이체
			<input type="radio" name="chkapp" value="VBANK" onclick="javascript:chk_app('VBANK')">무통장(가상계좌)
			<input type="radio" name="chkapp" value="HP" onclick="javascript:chk_app('HP')">휴대폰결제
			<input type="radio" name="chkapp" value="TICKET" onclick="javascript:chk_app('TICKET')">상품권결제
			&nbsp;&nbsp;&nbsp;&nbsp<a target="_new" href="https://www.allatpay.com/servlet/AllatBizV2/support/SupportInstallGuideCL?menu_id=m040301"><b>[FAQ]</b></a>
		</td>
	</tr>
</table>

<!-- 승인요청 및 결과수신페이지 지정 -->
<table border="0" cellpadding="0" cellspacing="1" bgcolor="#606060" width="1152" align="center" style="TABLE-LAYOUT: fixed;">
</br>
	<font color="red">◆ 필수 정보 : <b>결제 필수 항목(공통)</b></font>
	<tr>
		<td width="140" class="head">항목</td>
		<td width="160" class="head">예시 값</td>
		<td width="70"  class="head">&nbsp최대길이<br>(영문기준)</td>
		<td width="150"  class="head">변수명</td>
		<td class="head">변수 설명</td>
	</tr>
	<tr>
		<td class="head">상점 ID</td>
		<td class="body"><input type="text" name="allat_shop_id" value="" size="19" maxlength="20"></td>
		<td class="body">20</td>
		<td class="body">allat_shop_id</td>
		<td class="body">Allat에서 발급한 고유 상점 ID</td>
	</tr>
	<tr>
		<td class="head">주문번호</td>
		<td class="body"><input type="text" name="allat_order_no" value="" size="19" maxlength="80"></td>
		<td class="body">80</td>
		<td class="body">allat_order_no</td>
		<td class="body">쇼핑몰에서 사용하는 고유 주문번호 : 공백,작은따옴표('),큰따옴표(") 사용 불가</td>
	</tr>
	<tr>
		<td class="head">승인금액</td>
		<td class="body"><input type="text" name="allat_amt" value="" size="19" maxlength="16"></td>
		<td class="body">16</td>
		<td class="body">allat_amt</td>
		<td class="body">총 결제금액 : 숫자(0~9)만 사용가능</td>
	</tr>
	<tr>
		<td class="head">회원ID</td>
		<td class="body"><input type="text" name="allat_pmember_id" value="" size="19" maxlength="20"></td>
		<td class="body">20</td>
		<td class="body">allat_pmember_id</td>
		<td class="body">쇼핑몰의 회원ID : 공백,작은따옴표('),큰따옴표(") 사용 불가</td>
	</tr>
	<tr>
		<td class="head">상품코드</td>
		<td class="body"><input type="text" name="allat_product_cd" value="" size="19" maxlength="1000"></td>
		<td class="body">1000</td>
		<td class="body">allat_product_cd</td>
		<td class="body">여러 상품의 경우 구분자 이용, 구분자('||':파이프 2개) : 공백,작은따옴표('),큰따옴표(") 사용 불가</td>
	</tr>
	<tr>
		<td class="head">상품명</td>
		<td class="body"><input type="text" name="allat_product_nm" value="" size="19" maxlength="1000"></td>
		<td class="body">1000</td>
		<td class="body">allat_product_nm</td>
		<td class="body">여러 상품의 경우 구분자 이용, 구분자('||':파이프 2개)</td>
	</tr>
	<tr>
		<td class="head">결제자성명</td>
		<td class="body"><input type="text" name="allat_buyer_nm" value="" size="19" maxlength="20"></td>
		<td class="body">20</td>
		<td class="body">allat_buyer_nm</td>
		<td class="body"></td>
	</tr>
	<tr>
		<td class="head">수취인성명</td>
		<td class="body"><input type="text" name="allat_recp_nm" value="" size="19" maxlength="20"></td>
		<td class="body">20</td>
		<td class="body">allat_recp_nm</td>
		<td class="body"></td>
	</tr>
	<tr>
		<td class="head">수취인주소</td>
		<td class="body"><input type="text" name="allat_recp_addr" value="" size="19" maxlength="120"></td>
		<td class="body">120</td>
		<td class="body">allat_recp_addr</td>
		<td class="body"></td>
	</tr>
	<tr>
		<td class="head">결제방법선택</td>
		<td class="body"><input type="text" name="allat_pay_type" value="" size="19" maxlength="10" readonly></td>
		<td class="body">10</td>
		<td class="body">allat_pay_type</td>
		<td class="body">
			카드인증결제 - CARD</br>
			카드일반결제 - NOR </br>
			기타 결제 - 무통장입금:VBANK, 휴대폰결제:HP, 상품권결제:TICKET</td>
	</tr>
	<tr>
		<td class="head">인증정보수신URL<br>(shop_receive_url)</td>
		<td class="body"><input type="text" name="shop_receive_url" value="" size="19" maxlength="120"></td>
		<td class="body">120</td>
		<td class="body">shop_receive_url</td>
		<td class="body"></td>
	</tr>
	<tr>
		<td class="head">주문정보암호화필드</td>
		<td class="body"><font color="red">값은 자동으로 설정됨</font></td>
		<td class="body">-</td>
		<td class="body">allat_enc_data</td>
		<td class="body"><font color="red">&ltinput type=hidden name=allat_enc_data value=''&gt<br>
		※결제정보가 암호화되어 설정되는값.</br>hidden field로 설정해야함</font>
		</td>
	<input type=hidden name="allat_enc_data" value="">
	</tr>
</table>
<br>
<!-- 결제 옵션 정보 -->
<table border="0" cellpadding="0" cellspacing="1" bgcolor="#606060" width="1152" align="center" style="TABLE-LAYOUT: fixed;">
	<font color=blue>◆ 결제 옵션 정보 : <b>결제 옵션 항목(공통)</b></font>
	<tr>
		<td width="140" class="head">상품 에스크로 여부</td>
		<td width="160" class="body"><input type=text name="allat_escrow_yn" value="N" size="19" maxlength="1"></td>
		<td width="70"  class="body">1</td>
		<td width="150" class="body">allat_escrow_yn</td>        
		<td class="body">
			적용(Y),미적용(N) -> 고객입력값</br>
			계좌이체/무통장입금(가상계좌)를 사용하는 상점 중 배송이 필요한 실물거래시 에스크로를 적용해야함
		</td>
	</tr>
	<tr>
		<td class="head">고객 에스크로 인증번호</td>
		<td class="body"><input type="text" name="allat_escrow_no" value="" size="19" maxlength="10"></td>
		<td class="body">10</td>
		<td class="body">allat_escrow_no</td>        
		<td class="body">4~10자리 에스크로 비밀번호(숫자,영문허용)-> 고객 입력값<br>
		<font color="blue">고객이 에스크로 확인시에 사용함</font></td>
	</tr>
	<tr>
		<td class="head">상품 실물 여부</td>
		<td class="body"><input type=text name="allat_real_yn" value="N" size="19" maxlength="1"></td>
		<td class="body">1</td>
		<td class="body">allat_real_yn</td>        
		<td class="body">상품이 실물일 경우(Y), 상품이 실물이 아닐경우(N)<br>
		<font color="blue">※상품이 실물이고, 10만원 이상 계좌이체/무통장 서비스에는 에스크로 여부를 Y/N로 꼭 체크해야함</font><br></td>
	</tr>    
	<tr>
		<td class="head">결제자 Email</td>
		<td class="body"><input type=text name="allat_email_addr" value="" size="19" maxlength="50"></td>
		<td class="body">50</td>
		<td class="body">allat_email_addr</td>
		<td class="body"><font color="red">에스크로 사용시( allat_escrow_yn = Y )에만 필수 필드임</font></td>
	</tr>
	<tr>
		<td class="head">테스트 여부</td>
		<td class="body"><input type=text name="allat_test_yn" value="N" size="19" maxlength="1"></td>
		<td class="body">1</td>
		<td class="body">allat_test_yn</td>        
		<td class="body">
			테스트(Y),서비스(N) - Default 값 : N</br>
			테스트 결제는 카드사나 은행까지 보내지 않으므로, 실제 결제가 되지 않음</br>
			테스트 결제는 실제 결제가 나지 않으므로, 거래조회에서 조회하실 수 없음</br>
			테스트 설공시 결과값 : '0001' / 실제 결제 성공시 '0000'이 리턴됨</br>
			<font color="red">오픈시에는 반드시 'N'으로 수정하여야함</font>
		</td>
	</tr>
	<tr>
		<td class="head">제공기간</td>
		<td class="body"><input type="text" name="allat_provide_date" value="" size="19" maxlength="25"></td>
		<td class="body">25</td>
		<td class="body">allat_provide_date</td>        
		<td class="body">컨텐츠 상품의 제공기간 : YYYY.MM.DD ~ YYYY.MM.DD</td>
	</tr>
	<tr>
		<td class="head">성별</td>
		<td class="body"><input type="text" name="allat_gender" value="" size="19" maxlength="1"></td>
		<td class="body">1</td>
		<td class="body">allat_gender</td>        
		<td class="body">구매자 성별, 남자(M)/여자(F)</td>
	</tr>
	<tr>
		<td class="head">생년월일</td>
		<td class="body"><input type="text" name="allat_birth_ymd" value="" size="19" maxlength="8"></td>
		<td class="body">8</td>
		<td class="body">allat_birth_ymd</td>        
		<td class="body">구매자의 생년월일 8자, YYYYMMDD형식</td>
	</tr>
</table>

<!-- 카드결제 필수항목 -->
<div id="CARD" style="{display:none}">
</br>
<table border="0" cellpadding="0" cellspacing="1" bgcolor="#606060" width="1152" align="center" style="TABLE-LAYOUT: fixed;">
	<font color="red">◆ 카드 결제 정보 : <b>결제 필수 항목</b></font>
	<tr>
        <td width="140" class="head" >카드종류</td>
        <td width="160" class="body">
            <select name="allat_card_code_sel" id="allat_card_code_sel">
                <option value="">카드를 선택하세요</option>
            </select>
        </td>
        <td width="70"  class="body">1</td>
        <td width="150" class="body">allat_card_code_sel</td>          
        <td class="body">
			카드코드 Select BOX 지원</br>
			ISP : BC, 국민, 수협, 광주, 제주, 전북, 우리</br>
			3D : 그 외</td>
        <script language="Javascript">use_select_card("allat_card_code_sel")</script>
    </tr>
	<tr>
		<td width="140" class="head">카드코드</td>
		<td width="160" class="body">allat_card_code</td>
		<td width="70"  class="body">-</td>
		<td width="150" class="body">allat_card_code</td>          
		<td class="body">
			카드선택시 자동설정</br>
			<font color="red">&ltinput type=hidden name=allat_card_code value=''&gt</font>
			<input type="hidden" name="allat_card_code" value="">
		</td>
	</tr>
</table>
</div>

<!-- 일반결제 -->
<div id="NOR" style="{display:none}">
</br>
<table border="0" cellpadding="0" cellspacing="1" bgcolor="#606060" width="1152" align="center" style="TABLE-LAYOUT: fixed;">
	<font color="red">◆ 일반 결제 : <b>결제 필수 항목</b></font>
	<tr>
		<td width="140" class="head">카드 번호</td>
		<td width="160" class="body"><input type=text name="allat_card_no" value="" size="19" maxlength="16"></td>
		<td width="70"  class="body">16</td>
		<td width="150" class="body">allat_card_no</td>        
		<td class="body"></td>
	</tr>
	<tr>
		<td class="head">카드 유효기간</td>
		<td class="body"><input type=text name="allat_cardvalid_ym" value="" size="19" maxlength="4"></td>
		<td class="body">4</td>
		<td class="body">allat_cardvalid_ym</td>
		<td class="body">YYMM ( 예: 2007년 10월 ==> 0710 )</td>
	</tr>
	<tr>
		<td class="head">카드 비밀번호</td>
		<td class="body"><input type="password" name="allat_passwd_no" value="" size="19" maxlength="2"></td>
		<td class="body">2</td>
		<td class="body">allat_passwd_no</td>
		<td class="body">2자리 ( 0815 ==> 08 ) <font color="red">※필수 조건 : allat_cardcert_yn=Y</font></td>
	</tr>
	<tr>
		<td class="head">결제자 카드종류</td>
		<td class="body"><input type="text" name="allat_business_type" value="" size="19" maxlength="1"></td>
		<td class="body">1</td>
		<td class="body">allat_business_type</td>
		<td class="body">개인(0)/법인(1)</td>
	</tr>
	<tr>
		<td class="head">사업자번호</td>
		<td class="body"><input type="text" name="allat_biz_no" value="" size="19" maxlength="20"></td>
		<td class="body">20</td>
		<td class="body">allat_biz_no</td>
		<td class="body"><font color="red">※필수 조건 : allat_business_type = 1, allat_cert_yn=Y or X</font></td>
	</tr>
</table>
</div>

<!-- 3D, 일반 결제시 필수 필드 -->
<div id="NOR_3D" style="{display:none}">
</br>
<table border="0" cellpadding="0" cellspacing="1" bgcolor="#606060" width="1152" align="center" style="TABLE-LAYOUT: fixed;">
	<font color="red">◆ 3D, 일반 결제 정보 : <b>결제 필수 항목</b></font>
	<tr>
		<td width="140" class="head">할부개월값</td>
		<td width="160" class="body"><input type="text" name="allat_sell_mm" value="" size="19" maxlength="2"></td>
		<td width="70"  class="body">2</td>
		<td width="150" class="body">allat_sell_mm</td>                  
		<td class="body">00,0,01,1 : 일시불 (할부 사용시 2개월 이상으로 설정 )</td>
	</tr>
</table>
</div>

<!-- ISP (옵션) -->
<div id="ISP_CARD" style="{display:none}">
</br>
<table border="0" cellpadding="0" cellspacing="1" bgcolor="#606060" width="1152" align="center" style="TABLE-LAYOUT: fixed;">
	<font color="blue">◆ ISP 결제 정보 : <b>결제 옵션 항목</b></font>
	<tr>
		<td width="140" class="head">공인 인증서 적용<br>최소 금액</td>
		<td width="160" class="body"></td>
		<td width="70"  class="body">2</td>
		<td width="150" class="body">KVP_OACERT_INF</td>          
		<td class="body">KVP 공인인증서 적용 최소 금액(30만원 이하 적용시 사용)<br>
		<font color="blue">&ltinput type=hidden name=KVP_OACERT_INF value='NONE'&gt</font>
		<input type="hidden" name="KVP_OACERT_INF" value="NONE"></td>
	</tr>
	<tr>
		<td class="head">선택카드사</td>
		<td class="nbody"><input type=text name="allat_select_card_id" value="" size="19" maxlength="2"></td>
		<td class="nbody">2</td>
		<td class="nbody">allat_select_card_id</td>        
		<td class="nbody">
			ISP 카드사</br>
			BC : 61, 국민 : 62, 우리 : 75, 수협 : 73, 전북 : 37, 제주 : 35, 광주 : 34
		</td>
	</tr>
</table>
</div>

<!-- ISP, 일반결제 (옵션) -->
<div id="ISP_NOR" style="{display:none}">
</br>
<table border="0" cellpadding="0" cellspacing="1" bgcolor="#606060" width="1152" align="center" style="TABLE-LAYOUT: fixed;">
	<font color="blue">◆ ISP, 일반 결제 정보 : <b>결제 옵션 항목</b></font>
	<tr>
		<td width="140" class="head">주민번호</td>
		<td width="160" class="body"><input type="text" name="allat_registry_no" value="" size="19" maxlength="13"></td>
		<td width="70"  class="body">13</td>
		<td width="150" class="body">allat_registry_no</td>
		<td class="body">
		<font color="red">※필수 조건 : allat_business_type = 0, allat_cert_yn=Y or X </font><br>
		<font color="red">일반결제 - allat_business_type = 0 일 때 주민번호 뒷자리 7자리</font><br>
		<font color="blue">ISP     - 주민번호 13자리(ISP일때는 특정 사업자만 사용함.대부분 사용하지 않음)</font></td>
	</tr>
	<tr>
		<td class="head">KB복합결제 적용여부</td>
		<td class="body"><input type=text name="allat_kbcon_point_yn" value="" size="19" maxlength="1"></td>
		<td class="body">1</td>
		<td class="body">allat_kbcon_point_yn</td>        
		<td class="body">KB복합결제 적용여부 : 적용(Y), 미적용(N)</td>
	</tr>
</table>
</div>

<!-- 카드, 일반결제 (옵션) -->
<div id="CARD_APP" style="{display:none}">
</br>
<table border="0" cellpadding="0" cellspacing="1" bgcolor="#606060" width="1152" align="center" style="TABLE-LAYOUT: fixed;">
	<!--//----- 카드 결제시 옵션 필드, 계좌 이체시에는 사용하지 않음 --------//-->
	<font color="blue">◆ 카드 결제 정보 : <b>결제 옵션 항목</b></font>
	<tr>
		<td width="140" class="head">일반/무이자 할부<br>사용여부</td>
		<td width="160" class="body"><input type=text name="allat_zerofee_yn" value="N" size="19" maxlength="1"></td>
		<td width="70"  class="body">1</td>
		<td width="150" class="body">allat_zerofee_yn</td>
		<td class="body">
			일반(N), 무이자 할부(Y) - Default : N</br>
			신용카드 결제금액이 50,000원 이상일 때 할부가 가능합니다.</br>
			<font color="red">무이자할부 여부를 'Y'로 하더라도, 올앳에 무이자할부 행사가 등록되어 있지 않은 카드사이거나, 할부개월이 일시불일 때는 적용되지 않습니다.</font></br>
			<a target="_new" href="https://www.allatpay.com/servlet/AllatBizV2/support/SupportFaqCL?menu_id=m040201&type=detail&page=7&seq_no=1143"><b>[무이자신청 방법]</b></a>
		</td>
	</tr>
	<tr>
		<td class="head">포인트 사용여부</td>
		<td class="body"><input type=text name="allat_bonus_yn" value="N" size="19" maxlength="1"></td>
		<td class="body">1</td>
		<td class="body">allat_bonus_yn</td>        
		<td class="body">
			사용(Y), 사용 않음(N) - Default : N</br>
			상점이 포인트가맹점(삼성, 국민, BC 등) 이용시 포인트를 사용하여 결제하는 서비스</br>			
			<font color="red">상점에 포인트 가맹점이 설정되어 있어야함.</br>
			특수포인트의 경우 사용불가</br>
			예)현대 M포인트 : 할부개월+40, allat_bonus_yn='N'</br></font>
		</td>
	</tr>
	<tr>
		<td class="head">카드 인증 여부</td>
		<td class="body"><input type=text name="allat_cardcert_yn" value="N" size="19" maxlength="1"></td>
		<td class="body">1</td>
		<td class="body">allat_cardcert_yn</td>        
		<td class="body">인증(Y), 인증 사용않음(N), 인증만 사용(X) - Default : N</td>
	</tr>
</table>
</div>

<!-- 은행결제(BANK) 필수 항목 -->
<div id="BANK_APP" style="{display:none}">
</br>
<table border="0" cellpadding="0" cellspacing="1" bgcolor="#606060" width="1152" align="center" style="TABLE-LAYOUT: fixed;">
	<font color="red">◆ 은행 결제 정보 : <b>결제 필수 항목</b></font>
	<tr>
		<td width="140" class="head">예금주명</td>
		<td width="160" class="body"><input type="text" name="allat_account_nm" value="" size="19" maxlength="30"></td>
		<td width="70"  class="body">30</td>
		<td width="150" class="body">allat_account_nm</td>   
		<td class="body"></td>
	</tr>
	<tr>
		<td class="head">현금영수증 등록 여부</td>
		<td class="body"><input type=text name="allat_cash_yn" value="N" size="19" maxlength="1"></td>
		<td class="body">1</td>
		<td class="body">allat_cash_yn</td>         
		<td class="body"><a target="_new" href="https://www.allatpay.com/servlet/AllatBizV2/svcinfo/SvcInfoMainCL?menu_id=m010601"><b>[설명]</b></a>
		</td>
	</tr>
	<tr>
		<td class="head">현금영수증 인증정보</td>
		<td class="body"><input type="text" name="allat_cert_no" value="" size="19" maxlength="18"></td>
		<td class="body">18</td>
		<td class="body">allat_cert_no</td>          
		<td class="body">핸드폰번호, 주민번호, 사업자번호, 현금영수증카드번호
		<font color="blue">[ allat_cash_yn='Y'일 경우에 사용 ]</font></td>
	</tr>
	<tr>
		<td class="head">등록할 사업자 번호</td>
		<td class="body"><input type="text" name="allat_reg_business_no" value="" size="19" maxlength="10"></td>
		<td class="body">10</td>
		<td class="body">allat_reg_business_no</td>           
		<td class="body"><font color="blue">상점ID와 다른경우에 사용</font></td>
	</tr>
</table>
</div>

<!-- 은행결제(BANK) 옵션 항목 -->
<div id="BANK_OPT" style="{display:none}">
</br>
<table border="0" cellpadding="0" cellspacing="1" bgcolor="#606060" width="1152" align="center" style="TABLE-LAYOUT: fixed;">
	<font color="blue">◆ 은행 결제 정보 : <b>결제 옵션 항목</b></font>
	<tr>
		<td width="140" class="head">과세여부</td>
		<td width="160" class="body"><input type="text" name="allat_tax_yn" value="Y" size="19" maxlength="1"></td>
		<td width="70"  class="body">1</td>
		<td width="150" class="body">allat_tax_yn</td>                  
		<td class="body">Y(과세), N(비과세) - Default : Y</td>
	</tr>
</table>
</div>

<!-- 무통장[가상계좌](VBANK) -->
<div id="VBANK" style="{display:none}">
</br>
<table border="0" cellpadding="0" cellspacing="1" bgcolor="#606060" width="1152" align="center" style="TABLE-LAYOUT: fixed;">
	<font color="red">◆ 무통장[가상계좌](VBANK)  : <b>결제 필수 항목</b></font>
	<tr>
        <td width="140" class="head" >은행 코드</td>
        <td width="160" class="body">
            <select name="allat_vbank_code_sel" id="oSelect">
                <option value="">은행선택</option>
            </select>
        </td>
        <td width="70" class="body">-</td>
        <td width="150" class="body">allat_vbank_code</td>                   
        <td class="body">은행코드 Select BOX 지원</td>
        <script language="Javascript">use_select_vbank("oSelect")</script>        
    </tr>
	<tr>
		<td width="140" class="head">은행코드</td>
		<td width="160" class="body">allat_vbank_cd</td>
		<td width="70"  class="body">-</td>
		<td width="150" class="body">allat_vbank_cd</td>          
		<td class="body">
			은행선택시 자동설정</br>
			<font color="red">&ltinput type=hidden name=allat_vbank_cd value=''&gt</font>
			<input type="hidden" name="allat_vbank_cd" value="">
		</td>
	</tr>
	<tr>
		<td class="head">입금예정일</td>
		<td class="body"><input type="text" name="allat_income_expect_ymd" value="" size="19" maxlength="8"></td>
		<td class="body">8</td>
		<td class="body">allat_income_expect_ymd</td>
		<td class="body">구매자의 입금 예정일</td>
	</tr>
	<tr>
		<td class="head">무통장(가상계좌)<br>인증 Key</td>
		<td class="body"><input type="text" name="allat_account_key" value="" size="19" maxlength="20"></td>
		<td class="body">20</td>
		<td class="body">allat_account_key</td>
		<td class="body">Key 별 채번 방식 사용시 인증 Key<br>
		<font color="red">Key별 채번 방식 상점은 필수 필드임</font></td>
	</tr>
</table>

<!-- 무통장[가상계좌](VBANK) 옵션 -->
<table border="0" cellpadding="0" cellspacing="1" bgcolor="#606060" width="1152" align="center" style="TABLE-LAYOUT: fixed;">
</br>
	<font color="blue">◆ 무통장[가상계좌](VBANK) : <b>결제 옵션 항목</b></font>
	<tr>
		<td width="140" class="head">입급기한일</td>
		<td width="160" class="nbody"><input type="text" name="allat_income_limit_ymd" value="" size="19" maxlength="8"></td>
		<td width="70"  class="nbody">8</td>
		<td width="150" class="nbody">allat_income_limit_ymd</td>
		<td class="nbody">가상계좌의 입금기한일</td>
	</tr>
</table>
</div>

<!-- 상품권결제(TICKET) 필수 항목 -->
<div id="TICKET" style="{display:none}">
</br>
<table border="0" cellpadding="0" cellspacing="1" bgcolor="#606060" width="1152" align="center" style="TABLE-LAYOUT: fixed;">
	<font color="red">◆ 상품권 선택 정보 : <b>결제 필수 항목</b></font>
	<tr>
        <td width="140" class="head" >상품권 종류 선택</td>
        <td width="160" class="body">
            <select name="allat_ticket_code_sel" id="oSelectTicket">
                <option value="">상품권을 선택하세요</option>
            </select>
        </td>
        <td width="70"  class="body">1</td>
        <td width="150" class="body">allat_ticket_code</td>          
        <td class="body">상품권코드 Select BOX 지원</td>
        <script language="Javascript">use_select_ticket("oSelectTicket")</script>
    </tr>
	<tr>
		<td width="140" class="head">상품권코드</td>
		<td width="160" class="body">allat_ticket_cd</td>
		<td width="70"  class="body">2</td>
		<td width="150" class="body">allat_ticket_cd</td>          
		<td class="body">
			상품권선택시 자동설정</br>
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
		<input type="button" value=" 결제 요청 " name="app_btn" onClick="javascript:ftn_app(document.sendFm);">
		</td>
	</tr>
</table>
</p>
</form>
</body>
</html>