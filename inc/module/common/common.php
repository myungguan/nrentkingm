<?
if($han=='maketime')	{
	


	$sd = $_REQUEST['sd'];
	$ed = $_REQUEST['ed'];
	
	$arsd = explode(" ",$sd);
	$ar_sd1 = explode("-",$arsd[0]);
	$ar_sd2 = explode(":",$arsd[1]);

	$mk_sd = mktime($ar_sd2[0],$ar_sd2[1],"0",$ar_sd1[1],$ar_sd1[2],$ar_sd1[0]);
	
	$ared = explode(" ",$ed);
	$ar_ed1 = explode("-",$ared[0]);
	$ar_ed2 = explode(":",$ared[1]);

	$mk_ed = mktime($ar_ed2[0],$ar_ed2[1],"0",$ar_ed1[1],$ar_ed1[2],$ar_ed1[0]);

	$mk_mins = $mk_ed - $mk_sd;

	sscanf($arsd[0],'%4d-%2d-%2d',$y1,$m1,$d1);
	sscanf($ared[0],'%4d-%2d-%2d',$y2,$m2,$d2);

	if($mk_mins<0)	{
		$redata[res] = 'error';
		$redata[resmsg] = '반납일은 픽업일 이전이 될수 없습니다';

		$result = json_encode ($redata);
		header ( 'Content-Type:application/json; charset=utf-8' );
		echo $result;
		exit;	
	}
	else	{
		$indata[timestamp] =  $mk_mins;
		
		$days = intval($mk_mins/86400);

		if($days<15)	{
			$indata[t] = "1";
			$indata[days] = $days;

			$indata[times] = $mk_mins - $indata[days]*86400;
			$indata[times] = intval($indata[times]/3600);
		}
		else	{
			$indata[t] = "2";

			$y3 = $y2 - $y1;
			$m3 = $m2 - $m1;
			$d3 = $d2 - $d1;

			if($m3<=0)	{
				$m3 = $m3 + $y3*12;
			}
			$indata[month] = $m3;
			
			if($d3<0)	{
				$d3 = $d2 - $d1 + 30;
				$indata[month] = $indata[month] - 1;
				$indata[days] = $d3;
			}
			else	{
				$indata[days] = $d3;
			}

		}
		
		$redata[res] = 'ok';
		$redata[datas] = $indata;

		$result = json_encode ($redata);
		header ( 'Content-Type:application/json; charset=utf-8' );
		echo $result;
		exit;	
	}
}	
if($han=='times')	{
	$startTime = $_REQUEST['startTime'];
	$endTime = $_REQUEST['endTime'];

	$std = mktime(substr($startTime,8,2),substr($startTime,10,2),"00",substr($startTime,4,2),substr($startTime,6,2),substr($startTime,0,4));
	$etd = mktime(substr($endTime,8,2),substr($endTime,10,2),"00",substr($endTime,4,2),substr($endTime,6,2),substr($endTime,0,4));

	$min = $etd - $std;

	$redata[res] = 'ok';
	if($min/3600<12)	{
		$redata[res] = 'error';
	}

	$result = json_encode ($redata);
	header ( 'Content-Type:application/json; charset=utf-8' );
	echo $result;
	exit;	
}
?>