<table class="detailTable2" id="priceLongtermTable">
	<thead>
	<tr>
		<th>구분</th>
		<th>1개월</th>
		<th>6개월</th>
		<th>12개월</th>
	</tr>
	</thead>
	<tbody>
	<tr>
		<th>설명</th>
		<td colspan="3" style="text-align:left;padding:5px;line-height:18px;">
			월장기의 구간은 1개월~5개월, 6개월~11개월, 12개월 이상의 구간으로 나뉩니다.<br />
			하나의 구간에서 대여를 하게되면 대여기간의 개월요금 * 개월수로 계산됩니다.<br />
			기간의 개월 + 몇일의 대여가 된다면 1/30 요금을 1일 요금으로 계산되어 고객에게 청구됩니다.<br />
			요금이 입력되어 있지 않거나 0이면 월 장기 대여에 노출되지 않습니다.<br /><br />

			3개월: <span id="priceExampleLongterm1" style="font-weight:bold;"></span> 원<br />
			3개월 15일: <span id="priceExampleLongterm2" style="font-weight:bold;"></span> 원
		</td>
	</tr>
	<tr>
		<th>요금 (자차포함가격)</th>
		<td><input type="text" name="price_longterm1" id="priceLongterm1" class="mWt150 inputNumber" value="<?=number_format($item['price_longterm1'])?>" /> 원</td>
		<td><input type="text" name="price_longterm2" id="priceLongterm2" class="mWt150 inputNumber" value="<?=number_format($item['price_longterm2'])?>" /> 원</td>
		<td><input type="text" name="price_longterm3" id="priceLongterm3" class="mWt150 inputNumber" value="<?=number_format($item['price_longterm3'])?>" /> 원</td>
	</tr>
	<tr>
		<th>보증금</th>
		<td><input type="text" name="price_longterm_deposit1" class="mWt150 inputNumber" value="<?=number_format($item['price_longterm_deposit1'])?>" /> 원</td>
		<td><input type="text" name="price_longterm_deposit2" class="mWt150 inputNumber" value="<?=number_format($item['price_longterm_deposit2'])?>" /> 원</td>
		<td><input type="text" name="price_longterm_deposit3" class="mWt150 inputNumber" value="<?=number_format($item['price_longterm_deposit3'])?>" /> 원</td>
	</tr>
	<tr>
		<th>자차</th>
		<td>
			고객부담금: <input type="text" name="price_longterm_insu_exem" class="mWt150 inputNumber" value="<?=number_format($item['price_longterm_insu_exem'])?>" /> 원
		</td>
		<td colspan="2">
			보상한도: <input type="text" name="price_longterm_insu_limit" class="mWt150 inputNumber" value="<?=$item['price_longterm_insu_limit']?>"> 원 (0일 경우 한도 없음)
		</td>
	</tr>
	<tr>
		<th>주행거리</th>
		<td>
			제한: <input type="text" name="distance_limit" class="mWt150 inputNumber" value="<?=number_format($item['distance_limit'])?>" /> &#13214;/월 (0일 경우 제한 없음)
		</td>
		<td colspan="2">
			추가요금: <input type="text" name="distance_additional_price" class="mWt150 inputNumber" value="<?=number_format($item['distance_additional_price'])?>" /> 원/&#13214;
		</td>
	</tr>
	<tr>
		<th>NET 할인률</th>
		<td colspan="3">
			<input type="text" name="price_longterm_net" id="priceLongtermNet" class="mWt150 inputNumber" value="<?=number_format($item['price_longterm_net'] * 100)?>" /> %
			(업체간 거래시 할인되는 비율. 90%입력시 -> 10% 할인, 0일경우 거래하지 않음)
		</td>
	</tr>
	</tbody>
</table>