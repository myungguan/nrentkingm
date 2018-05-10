<?php
$c_ValueFromClick = $_COOKIE ['c_ValueFromClick'];
if( $c_ValueFromClick != NULL )
{
	$ilc_mid ='rentking1';
	$ilc_buyno =$order['idx'];
	$ilc_ccode =$order['retype'];
	$ilc_pcode =$order['vehicle_idx'];
	$ilc_pname =$order['retype'] == 1 ? '단기예약' : '장기예약';
	$ilc_pnum ='1';
	$ilc_popt ='1';
	$ilc_price =$paymentResult['AMT'];
	$ilc_buytype ='C';
	$ilc_member_id =$ar_init_member["id"];
	$ilc_username =$ar_init_member["name"];

	$ilc_script ='http://www.ilikeclick.com/tracking/sale/v1_Sale.php';
	$ilc_script .='?MID='.$ilc_mid;
	$ilc_script .='&BUYNO='.$ilc_buyno;
	$ilc_script .='&CCODE='.$ilc_ccode;
	$ilc_script .='&PCODE='.$ilc_pcode;
	$ilc_script .='&POPT='.$ilc_popt;
	$ilc_script .='&PNAME='.urlencode($ilc_pname);
	$ilc_script .='&PNUM='.$ilc_pnum;
	$ilc_script .='&PRICE='.$ilc_price;
	$ilc_script .='&BUYTYPE='.$ilc_buytype;
	$ilc_script .='&MEMBER_ID='.$ilc_member_id;
	$ilc_script .='&USERNAME='.urlencode($ilc_username);
	$ilc_script .='&ValueFromClick='.$c_ValueFromClick;

	$sql = "
		insert into ilikeclick (
				buyno, ccode, pcode, pname, pnum, popt, price, buytype, member_id, username, valuefromclick, dt
		) values (
			'$ilc_buyno', '$ilc_ccode', '$ilc_ccode', '$ilc_pname', '$ilc_pnum', '$ilc_popt', '$ilc_price', '$ilc_buytype', '$ilc_member_id', '$ilc_username', '$c_ValueFromClick', NOW()
		)
	";
	mysql_query($sql);

	echo "<SCRIPT LANGUAGE='JavaScript' src='$ilc_script'></script>";
}
?>