<?php
/**
 * Created by IntelliJ IDEA.
 * User: rentking
 * Date: 2017. 9. 29.
 * Time: AM 10:44
 */


?>
<div class="mainContent" style="width:1385px;margin-top:-40px;margin-bottom:30px;">
	<div class="pageTitle">
		<strong>정산내역</strong></div>
	<div style="text-align:right;margin-top:10px;margin-bottom:10px;">
	</div>
	<table class="listTableColor">
		<thead>
		<tr>
			<th>정산예정일</th>
			<th>정산예정금액<br>(총금액 - 수수료)</th>
			<th>정산완료금액</th>
			<th>미정산금액</th>
			<!--
			<th>은행</th>
			<th>계좌번호</th>
			<th>예금주</th>
			<th>인증번호</th>
			-->
			<th>상태</th>
		</tr>
		</thead>
		<tbody>
		<?for($i=0; $i<10; $i++) {?>
			<tr class="primary">
				<td>
					<a href="#">2017-11-30</a>
				</td>
				<td style="text-align:right;padding:0 5px;">
					<a href="#">264,063</a>
				</td>
				<td style="text-align:right;padding:0 5px;">0</td>
				<td style="font-weight:bold;text-align:right;padding:0 5px;">264,063</td>
				<!--
				<td>
					<select name="bank">
						<option value="002">KDB산업은행(002)</option>
						<option value="003">IBK기업은행(003)</option>
						<option value="004">KB국민은행(004)</option>
						<option value="005">외환은행(005)</option>
						<option value="006">주택은행(006)</option>
						<option value="007">수협(007)</option>
						<option value="011">농협은행(011)</option>
						<option value="020">우리은행(020)</option>
						<option value="023">SC제일은행(023)</option>
						<option value="027">한국씨티은행(027)</option>
						<option value="031">대구은행(031)</option>
						<option value="032">부산은행(032)</option>
						<option value="034">광주은행(034)</option>
						<option value="035">제주은행(035)</option>
						<option value="037">전북은행(037)</option>
						<option value="039">경남은행(039)</option>
						<option value="045">새마을금고(045)</option>
						<option value="048">신협(048)</option>
						<option value="050">상호저축은행(050)</option>
						<option value="064">산림조합(064)</option>
						<option value="071">우체국(071)</option>
						<option value="081">KEB하나은행(081)</option>
						<option value="088">신한은행(088)</option>
					</select>
				</td>
				<td><input type="text" name="bankAccount" /></td>
				<td><input type="text" name="bankName" /></td>
				<td><input type="text" name="bankHolder" /></td>
				-->
				<td style="font-weight:bold;"><button type="button" class="blackBtn_small btnSettlement" data-rentshop="84" data-dt="2017-09-15">정산</button></td>
			</tr>
		<?}?>
		<?for($i=0; $i<2; $i++) {?>
			<tr class="error">
				<td>
					<a href="#">2017-10-30</a>
				</td>
				<td style="text-align:right;padding:0 5px;">
					<a href="#">3,661,487</a>
				</td>
				<td style="text-align:right;padding:0 5px;">2,718,977</td>
				<td style="font-weight:bold;text-align:right;padding:0 5px;">942,510</td>
				<!--
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				-->
				<td style="font-weight:bold;"></td>
			</tr>
		<?}?>
		<?for($i=0; $i<5; $i++) {?>
			<tr>
				<td>
					<a href="#">2017-09-30</a>
				</td>
				<td style="text-align:right;padding:0 5px;">6,676,246</td>
				<td style="text-align:right;padding:0 5px;">6,676,246</td>
				<td style="font-weight:bold;text-align:right;padding:0 5px;">0</td>
				<!--
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				-->
				<td style="font-weight:bold;">정산완료</td>
			</tr>
		<?}?>
		</tbody>
	</table>
</div>
<form id="transferForm" action="/test/openplatform_transfer.php" method="post">
	<input type="hidden" name="XDEBUG_SESSION_START" value="" />
<!--	<input type="hidden" name="bank" />-->
<!--	<input type="hidden" name="bankAccount" />-->
<!--	<input type="hidden" name="bankName" />-->
<!--	<input type="hidden" name="bankHolder" />-->
</form>
<script type="text/javascript">
	$(function() {
		$('.btnSettlement').click(function(e){
			var $transferForm = $('#transferForm');
			//
			// var $tr = $(this).parents('tr');
			// var bank = $.trim($tr.find('select[name="bank"]').val());
			// var bankAccount = $.trim($tr.find('input[name="bankAccount"]').val());
			// var bankName = $.trim($tr.find('input[name="bankName"]').val());
			// var bankHolder = $.trim($tr.find('input[name="bankHolder"]').val());
			//
			// if(bankAccount == '') {
			// 	alert('계좌번호를 입력하세요');
			// 	$tr.find('input[name="bankAccount"]').focus();
			// 	return;
			// }
			//
			// if(bankName == '') {
			// 	alert('예금주를 입력하세요');
			// 	$tr.find('input[name="bankName"]').focus();
			// 	return;
			// }
			//
			// if(bankHolder == '') {
			// 	alert('인증번호를 입력하세요');
			// 	$tr.find('input[name="bankHolder"]').focus();
			// 	return;
			// }
			//
			// $transferForm.find('input[name="bank"]').val(bank);
			// $transferForm.find('input[name="bankAccount"]').val(bankAccount);
			// $transferForm.find('input[name="bankName"]').val(bankName);
			// $transferForm.find('input[name="bankHolder"]').val(bankHolder);
			$transferForm.submit();


		});

		$('#transferForm').on('submit', function() {
			window.open('about:blank','openplatform_transfer','width=400, height=650, menubar=no, status=no, toolbar=no');
			this.target = 'openplatform_transfer';
		})
	})

</script>