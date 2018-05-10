<?php
/**
 * Created by IntelliJ IDEA.
 * User: rentking
 * Date: 2017. 9. 26.
 * Time: PM 1:58
 */

include $_SERVER['DOCUMENT_ROOT'] . "/old/inc/base.php";
include $config['incPath'] . "/connect.php";
include $config['incPath']."/session.php";
include $config['incPath']."/config.php";

$ar_data = $ar_init_member;
$bankInfo = explode('||', $ar_data['rmemo']);
$bank = isset($bankInfo[0]) ? $bankInfo[0] : '';
$bankaccount = isset($bankInfo[1]) ? $bankInfo[1] : '';
$bankname = isset($bankInfo[2]) ? $bankInfo[2] : '';
$bankholder = isset($bankInfo[3]) ? $bankInfo[3] : '';

$transferInfo = array(
	'infoWithdraw' => '10000_170930_100000', 	//출금계좌 인자내역 TODO: 들어갈 데이터 - (rentshop.idx)_(정산날짜)_(payment_accounts.idx - 존재시)
	'bank' => $bank, 						//입금계좌 은행코드
	'bankAccount' => $bankaccount,		//입금계좌 번호
	'bankHolder' => $bankholder,				//입금계좌 예금주 사업자등록번호 or 생년월일(6) + 식별자(1)
	'bankName' => $bankname,					//입금계좌 예금주명
	'infoDeposit' => 'RK_1000000',				//입금계좌 인자내역 TODO: name = infoDeposit 들어갈 데이터 - rk_(정산날짜)_(payment.idx - 존재시) 10글자 이하만 가능.
	'cms' => '',							//필수값 아님. 이용기관 입장에서 추후 거래를 확인하기 위한 고유번호 개념으로 이용가능. 20바이트까지 가능
	'datetime' => date('YmdHis'),	//요청일시
	'account' => 1							//금액
);

$result = transferAccount($transferInfo);

?>
<html>
<title>정산금액 이체</title>
<body>
	<?

	//	echo '<br><br><b>transferInfo</b>';
	//	foreach($transferInfo as $key => $item)
	//	{
	//		echo '<br>'.$key.' => '.$item;
	//	}

	//		echo '<br>wd_print_content => '.$transferInfo['infoWithdraw'];
	//		echo '<br>req_cnt => 1';
	//		echo '<br>--tran_no => 1';
	//		echo '<br>--bank_code_std => '.$transferInfo['bank'];
	//		echo '<br>--account_num => '.$transferInfo['bankAccount'];
	//		echo '<br>--account_holder_name => '.$transferInfo['bankName'];
	//		echo '<br>--print_content => '.$transferInfo['content'];
	//		echo '<br>--tran_amt => '.$transferInfo['account'];
	//		echo '<br>tran_dtime => '.$transferInfo['datetime'];
	//		echo '<br>account_holder_info => '.$transferInfo['bankHolder'];

	//		echo '<br><br><b>openplatform transfer 응답메세지</b>';
	//		ini_set("xdebug.var_display_max_children", -1);
	//		ini_set("xdebug.var_display_max_data", -1);
	//		ini_set("xdebug.var_display_max_depth", -1);
	//
	//		var_dump($result);

	?>
	<script type="text/javascript">
		<?
		if($result['result'] == 'SUCCESS')
		{
		?>
		alert('이체가 정상적으로 완료되었습니다.\n\n계좌정보 : (<?=$transferInfo['bank']?>)<?=substr($transferInfo['bankAccount'],0,3).'******'.substr($transferInfo['bankAccount'],9)?>\n금액 : <?=$transferInfo['account']?>');
		<?
		}else{
		?>
		alert('<?=$result['msg'] ?>');
		<?}
		?>
		window.close();
	</script>

</body>
</html>


