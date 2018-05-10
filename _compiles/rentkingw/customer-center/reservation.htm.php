<?php /* Template_ 2.2.8 2017/12/28 16:47:16 /home/rentking/dev/rentking/old/sites/tpls/rentkingw/customer-center/reservation.htm 000008308 */ ?>
<!DOCTYPE html>
<html lang="ko">
<head>
<?php $this->print_("head",$TPL_SCP,1);?>

</head>
<body>
<?php $this->print_("header",$TPL_SCP,1);?>

	<div id="contentWrap">
<?php $this->print_("side_customer_center",$TPL_SCP,1);?>

		<div id="content">
			<div class="conBanner" style="margin-bottom:45px;">
				<div class="txt" style="width:580px">
					<h2>Make a Reservation &amp; Rates Quotation</h2>
					<strong>
						Welcome to Rentking!
					</strong>
					<p>
						We are a national-wide rent-a-car network in Korea.<br>
						You can enjoy the various car rental service from us.
					</p>
					<p></p>
				</div><!--//txt-->
				<span>
					<img src="/imgs/rentking.w/faqTopicon.jpg" alt="Icon">
				</span>
			</div><!--//conBanner-->
			<form name="regiform" id="regiform" action="<?=$PHP_SELF;?>" method="post" onsubmit="return foch('regiform');" enctype="multipart/form-data">
				<input type='hidden' name='mode' value='w'/>
				<h3>START A RESERVATION</h3>
				<table class="list" style="margin-bottom:32px;">
					<colgroup>
						<col width="180px">
						<col>
					</colgroup>
					<tbody>
					<tr>
						<th>LOCATION</th>
						<td>
							<input type="text" size="45" placeholder="ex. Incheon Int’l Airport, Seoul, Hyatt, city hall" name="location" valch="yes" msg="LOCATION" style="margin-bottom:4px;"><br />
							<input type="text" name="sdate" id="sdatess" class="mDate md1 dateTimePicker" placeholder="PICK-UP Date/Time" value="<?php if(empty($TPL_VAR["fordata"]["sdate"])){?><?php echo $TPL_VAR["global"]["sdate"]?><?php }else{?><?php echo $TPL_VAR["fordata"]["sdate"]?><?php }?>" readonly valch="yes" msg="PICK-UP Date/Time"
								data-locale="en"
								data-end="#edatess"
								data-min-date="<?php echo $TPL_VAR["global"]["sdate"]?>"
								data-max-date-origin="today"
								data-max-date="40d">
							<input type="text" name="edate" id="edatess" class="mDate dateTimePicker" placeholder="RETURN Date/Time" value="<?php if(empty($TPL_VAR["fordata"]["edate"])){?><?php echo $TPL_VAR["global"]["edate"]?><?php }else{?><?php echo $TPL_VAR["fordata"]["edate"]?><?php }?>" readonly valch="yes" msg="RETURN Date/Time"
								data-locale="en"
								data-template="1w,1M,2M,3M,6M,1y"
								data-start="#sdatess"
								data-min-date-origin="#sdatess"
								data-min-date="1d"
								data-max-date-origin="#sdate"
								data-max-date="1y">
						</td>
					</tr>
					<tr>
						<th>VEHICLE CLASS</th>
						<td>
							<select name="vclass" valch="yes" msg="VEHICLE CLASS" style="width:200px;">
								<option value="">SELECT VEHICLE CLASS</option>
								<option value="Ecomony">Ecomony</option>
								<option value="Compact">Compact</option>
								<option value="Intermediate">Intermediate</option>
								<option value="Full size">Full size</option>
								<option value="SUV">SUV</option>
								<option value="Minivan 9 Pax">Minivan 9 Pax</option>
								<option value="Minivan 12 Pax">Minivan 12 Pax</option>
								<option value="Passenger Van">Passenger Van</option>
								<option value="MINI BUS 15 PAX">MINI BUS 15 PAX</option>
								<option value="BUS 25 PAX">BUS 25 PAX</option>
								<option value="BUS 31 PAX">BUS 31 PAX</option>
								<option value="BUS 35 PAX">BUS 35 PAX</option>
								<option value="BUS 45 PAX">BUS 45 PAX</option>
								<option value="LIMOUSINE 16 PAX">LIMOUSINE 16 PAX</option>
								<option value="LIMOUSINE 20 PAX">LIMOUSINE 20 PAX</option>
								<option value="LIMOUSINE 28 PAX">LIMOUSINE 28 PAX</option>
								<option value="LIMOUSINE 45 PAX">LIMOUSINE 45 PAX</option>
							</select>
							<input type="text" placeholder="ex: Hyundai Avante AD 1.6 gasoline" name="car" style="width:250px;"/>
						</td>
					</tr>
					<tr>
						<th>DRIVE TYPE</th>
						<td>
							<select name="dtype" valch="yes" msg="DRIVE TYPE">
								<option value='Self drive'>Self drive</option>
								<option value='Chauffeur drive'>Chauffeur drive</option>
							</select>
						</td>
					</tr>
					<tr>
						<th>INQUIRY DETAILS</th>
						<td>
							<textarea name="detail" rows="10" style="width:100%;"></textarea>
						</td>
					</tr>
					</tbody>
				</table>

				<h3>CONTACT DETAILS</h3>
				<table class="list" style="margin-bottom:32px;">
					<colgroup>
						<col width="180px">
						<col>
					</colgroup>
					<tbody>
					<tr>
						<th rowspan="3">RENTER DETAILS</th>
						<td>
							<input type="text" placeholder="first Name" name="fname" valch="yes" msg="first Name">
							<input type="text" placeholder="last Name" name="lname" valch="yes" msg="last Name">
						</td>
					</tr>
					<tr>
						<td>
							<input type="text" placeholder="Phone Number" name="phone" valch="yes" msg="Phone Number">
						</td>
					</tr>
					<tr>
						<td>
							<input type="text" placeholder="E-mail Address" size="30" name="email" valch="yes" msg="E-mail Address">
						</td>
					</tr>
					<tr>
						<th>RENTER AGE</th>
						<td>
							<select name="age" valch="yes" msg="RENTER AGE">
								<option value=''>SELECT RENTAR AGE</option>
								<option value='21+'>21+</option>
								<option value='26+'>26+</option>
								<option value='30+'>30+</option>
							</select>
						</td>
					</tr>
					</tbody>
				</table>

				<div class="sub-title">
					<h3>SAVE TIME AT THE COUNTER</h3>
					<p class="description">*Optional - provide addtional information now and save time when you arrive.</p>
				</div>
				<table class="list" style="margin-bottom:32px;">
					<colgroup>
						<col width="180px">
						<col>
					</colgroup>
					<tbody>
					<tr>
						<th>RESIDENT OF</th>
						<td>
							<input type="text" name="resident" valch="yes" msg="RESIDENT">
						</td>
					</tr>
					<tr>
						<th>International Driving Permit</th>
						<td class="file_input">
							<input type="text" readonly="readonly" title="File Route" id="file_route">
							<label>Search file
								<input type="file" name="file" onchange="javascript:document.getElementById('file_route').value=this.value">
							</label>
						</td>
					</tr>
					</tbody>
				</table>

				<div class="sub-title">
					<h3>FLIGHT DETAILS</h3>
					<p class="description">*Optional - providing your flight information will help us keep your car ready upon arrival.</p>
				</div>
				<table class="list">
					<colgroup>
						<col width="180px">
						<col>
					</colgroup>
					<tbody>
					<tr>
						<th>FLIGHT DETAILS</th>
						<td>
							<input type="text" placeholder="Airline Name" name='airname'>
							<input type="text" placeholder="Flight Number" name='fnumber'>
						</td>
					</tr>
					</tbody>
				</table>

				<div class="button-box">
					<button class="btn-point big" type="button" onclick="$('#regiform').submit();">SUBMIT</button>
				</div>
			</form>
		</div>

	</div>
<?php $this->print_("footer",$TPL_SCP,1);?>

	<script type="text/javascript">
		function foch(f) {
			return check_form_eng(f) && confirm('Would you like to make a reservation?');
		}
		function check_form_eng(f) {
			var form = $("#" + f);
			var isok = true;
			form.find('input[type=text],input[type=checkbox],select,input[type=password],input[type=hidden]').each(function (key) {
				var obj = $(this);
				if (obj.attr('valch') == 'yes' && obj.css('display') != 'none') {

					if (obj.attr('tagName') == 'SELECT') {
						if (obj.find(':selected').val() == '') {
							alert("SELECT " + obj.attr('msg'));
							obj.focus();
							isok = false;
							return false;
						}
					}
					else if (obj.attr('type') == 'checkbox') {
						if (!obj.is(':checked')) {
							alert("CHECK " + obj.attr('msg'));
							obj.focus();
							isok = false;
							return false;
						}

					}

					else {
						if (obj.val() == '') {
							alert("ENTER " + obj.attr('msg'));
							obj.focus();
							isok = false;
							return false;
						}
					}
				}
			});
			return isok;
		}
	</script>
</body>
</html>