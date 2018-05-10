<table class="detailTable2" id="priceTable">
	<thead>
	<tr>
		<th style="width:200px;">구분</th>
		<th>24시간 이상 ~ 48시간 이하</th>
		<th>48시간 초과 ~ 72시간 이하</th>
		<th>72시간 초과 ~ 96시간 이하</th>
		<th>96시간 초과~</th>
	</tr>
	</thead>
	<tbody>
	<tr>
		<th>설명</th>
		<td colspan="4" style="text-align:left;padding:5px;line-height:18px;">
            단기요금은 요금제를 설정하시어 한 번에 입력이 가능합니다.<br/>
            단기요금은 기본요금(1일 요금)을 선택하시고 기간에 따른 할인율을 입력해주시면 자동으로 입력이 됩니다.<br/>
            배반차료, 자차보험 요금은 직접 모든 칸에 입력해주셔야 합니다.<br/>
            지점정보-배반차 지역을 빈칸으로 두시면 배달예약이 안되게 바뀝니다.<br/>
            자차보험은 오른쪽의 체크박스에 선택된 것 만 나오게 되며 24시간을 기준으로 자차보험이 되고 있습니다.<br/>
            요금은 주말(금, 토, 일, 공휴일), 주중을 따로 계산합니다.<br/>
            고객이 목 12:00 ~ 금 12:00 까지 대여한다면 주중요금의 12/24 + 주말요금의 12/24 로 계산 되어 집니다.<br/>
            인하율로 나타난 금액은 각 대여기간 구간의 24시간 요금입니다.<br/>
            고객이 50시간을 예약 했다면 48시간은 정상 요금으로 되며 나머지 2시간은 아래 시간요금*2 로 계산됩니다.<br/>
            시간요금이 입력되어 있지 않다면 대여기간 구간 요금의 1/12 을 1시간요금으로 계산하여 결제됩니다.<br /><br />

			24시간(1d): <span id="priceExample1" style="font-weight:bold;"></span> 원 (100원 이하 절삭)<br />
			35시간(1d 11h): <span id="priceExample2" style="font-weight:bold;"></span> 원 (100원 이하 절삭)<br />
			36시간(1d 12h): <span id="priceExample3" style="font-weight:bold;"></span> 원 (100원 이하 절삭)<br />
			48시간(2d): <span id="priceExample4" style="font-weight:bold;"></span>원 (100원 이하 절삭)<br />
			72시간(3d): <span id="priceExample5" style="font-weight:bold;"></span>원 (100원 이하 절삭)
		</td>
	</tr>
	<tr>
		<th>요금인하율</th>
		<td><span class="mWt150 inputNumber" style="display:inline-block;">100</span> %</td>
		<td><input type="text" name="price_discount2" id="discount2" class="mWt150 inputNumber" value="<?=$item ? $item['price_discount2']*100 : 95?>" /> %</td>
		<td><input type="text" name="price_discount3" id="discount3" class="mWt150 inputNumber" value="<?=$item ? $item['price_discount3']*100 : 90?>" /> %</td>
		<td><input type="text" name="price_discount4" id="discount4" class="mWt150 inputNumber" value="<?=$item ? $item['price_discount4']*100 : 85?>" /> %</td>
	</tr>
	<tr>
		<th>기본요금</th>
		<td><input type="text" name="price" id="price" class="mWt150 inputNumber" value="<?=$item['price']?>" /> 원</td>
		<td><span id="price2" class="mWt150 inputNumber" style="display:inline-block;">0</span> 원</td>
		<td><span id="price3" class="mWt150 inputNumber" style="display:inline-block;">0</span> 원</td>
		<td><span id="price4" class="mWt150 inputNumber" style="display:inline-block;">0</span> 원</td>
	</tr>
	<tr>
		<th>시간요금</th>
		<td colspan="4">
			기본요금 / <input type="text" name="price_hour" id="priceHourRate" class="mWt90 inputNumber" value="<?=$item ? $item['price_hour'] : 12?>" />
			(<span id="priceHour" class="inputNumber" style="display:inline-block;">0</span> 원 / 시간)
		</td>
	</tr>
	<tr>
		<th>주말요금</th>
		<td><input type="text" name="price_holiday" id="priceHoliday" class="mWt150 inputNumber" value="<?=$item['price_holiday']?>" /> 원</td>
		<td><span id="priceHoliday2" class="mWt150 inputNumber" style="display:inline-block;">0</span> 원</td>
		<td><span id="priceHoliday3" class="mWt150 inputNumber" style="display:inline-block;">0</span> 원</td>
		<td><span id="priceHoliday4" class="mWt150 inputNumber" style="display:inline-block;">0</span> 원</td>
	</tr>
	<tr>
		<th>배반차료</th>
		<td><input type="text" name="price_del1" id="priceDel1" class="mWt150 inputNumber" value="<?=$item['price_del1']?>" /> 원</td>
		<td><input type="text" name="price_del2" id="priceDel2" class="mWt150 inputNumber" value="<?=$item['price_del2']?>" /> 원</td>
		<td><input type="text" name="price_del3" id="priceDel3" class="mWt150 inputNumber" value="<?=$item['price_del3']?>" /> 원</td>
		<td><input type="text" name="price_del4" id="priceDel4" class="mWt150 inputNumber" value="<?=$item['price_del4']?>" /> 원</td>
	</tr>
	<tr>
		<th>자차미포함 <input type="checkbox" name="price_insu0_check" value="Y" <? if(!isset($item['price_insu0_check']) || $item['price_insu0_check']=='Y') { echo "checked";}?> /></th>
		<td colspan="4">자차미포함에 체크하면 자차미포함 차량을 대여할 수 있습니다.</td>
	</tr>
	<tr>
		<th rowspan="2">자차1 <input type="checkbox" name="price_insu1_check" value="Y" <? if($item['price_insu1_check']=='Y') { echo "checked";}?> /></th>
		<td><input type="text" name="price_insu1_1" class="mWt150 inputNumber" value="<?=$item['price_insu1_1']?>" /> 원</td>
		<td><input type="text" name="price_insu1_2" class="mWt150 inputNumber" value="<?=$item['price_insu1_2']?>" /> 원</td>
		<td><input type="text" name="price_insu1_3" class="mWt150 inputNumber" value="<?=$item['price_insu1_3']?>" /> 원</td>
		<td><input type="text" name="price_insu1_4" class="mWt150 inputNumber" value="<?=$item['price_insu1_4']?>" /> 원</td>
	</tr>
	<tr>
		<td>
			고객부담금: <input type="text" name="price_insu1_exem" class="mWt150 inputNumber" value="<?=$item['price_insu1_exem']?>"> 원
		</td>
		<td colspan="3">
			보상한도: <input type="text" name="price_insu1_limit" class="mWt150 inputNumber" value="<?=$item['price_insu1_limit']?>"> 원 (0일경우 한도 없음)
		</td>
	</tr>
	<tr>
		<th rowspan="2">자차2 <input type="checkbox" name="price_insu2_check" value="Y" <? if($item['price_insu2_check']=='Y') { echo "checked";}?> /></th>
		<td><input type="text" name="price_insu2_1" class="mWt150 inputNumber" value="<?=$item['price_insu2_1']?>" /> 원</td>
		<td><input type="text" name="price_insu2_2" class="mWt150 inputNumber" value="<?=$item['price_insu2_2']?>" /> 원</td>
		<td><input type="text" name="price_insu2_3" class="mWt150 inputNumber" value="<?=$item['price_insu2_3']?>" /> 원</td>
		<td><input type="text" name="price_insu2_4" class="mWt150 inputNumber" value="<?=$item['price_insu2_4']?>" /> 원</td>
	</tr>
	<tr>
		<td>
			고객부담금: <input type="text" name="price_insu2_exem" class="mWt150 inputNumber" value="<?=$item['price_insu2_exem'];?>" /> 원
		</td>
		<td colspan="3">
			보상한도: <input type="text" name="price_insu2_limit" class="mWt150 inputNumber" value="<?=$item['price_insu2_limit']?>"> 원 (0일경우 한도 없음)
		</td>
	</tr>
	<tr>
		<th rowspan="2">차차3 <input type="checkbox" name="price_insu3_check" value="Y" <? if($item['price_insu3_check']=='Y') { echo "checked";}?> /></th>
		<td><input type="text" name="price_insu3_1" class="mWt150 inputNumber" value="<?=$item['price_insu3_1']?>" /> 원</td>
		<td><input type="text" name="price_insu3_2" class="mWt150 inputNumber" value="<?=$item['price_insu3_2']?>" /> 원</td>
		<td><input type="text" name="price_insu3_3" class="mWt150 inputNumber" value="<?=$item['price_insu3_3']?>" /> 원</td>
		<td><input type="text" name="price_insu3_4" class="mWt150 inputNumber" value="<?=$item['price_insu3_4']?>" /> 원</td>
	</tr>
	<tr>
		<td>
			고객부담금: <input type="text" name="price_insu3_exem" class="mWt150 inputNumber" value="<?=$item['price_insu3_exem'];?>" /> 원
		</td>
		<td colspan="3">
			보상한도: <input type="text" name="price_insu3_limit" class="mWt150 inputNumber" value="<?=$item['price_insu3_limit']?>"> 원 (0일경우 한도 없음)
		</td>
	</tr>
	<tr>
		<th>NET 할인률</th>
		<td colspan="4">
			<input type="text" name="price_net" id="priceNet" class="mWt150 inputNumber" value="<?=$item['price_net'] * 100 ?>" />%
			(업체간 거래시 할인되는 비율. 90%입력시 -> 10% 할인, 0일경우 거래하지 않음)
		</td>
	</tr>
	<tr>
		<td colspan="6" style="text-align:left;padding:10px;">
			* 자차보험료는 일(24시간)단위로 적용됩니다.
		</td>
	</tr>
	</tbody>
</table>