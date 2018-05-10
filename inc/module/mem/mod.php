<?
if(!$_SESSION['member_idx'])	{
	$redata[res] = 'error';
	$redata[resmsg] = '로그인후 이용이 가능합니다.';
	
	$result = json_encode ($redata);
	header ( 'Content-Type:application/json; charset=utf-8' );
	echo $result;
	exit;
}

if($han=='passwd')	{
	
	$nowpasswd = $_REQUEST['nowpasswd'];
	$passwd = $_REQUEST['passwd'];
	
	$dbpasswd = $ar_init_member[passwd];

	$result = mysql_query("select password('$nowpasswd')");
	$user_passwds = array(mysql_result($result,0,0), hash("sha256", md5($nowpasswd)), md5($nowpasswd), hash("sha512", md5($nowpasswd)."paulshop01".$_SESSION['member_id']));

	if(!in_array($dbpasswd,$user_passwds))	{
		
		$redata[res] = 'error';
		$redata[resmsg] = '현재사용중인 비밀번호가 잘못되었습니다.';
	
		$result = json_encode ($redata);
		header ( 'Content-Type:application/json; charset=utf-8' );
		echo $result;
		exit;

	}
	$redata[res] = 'ok';

	$value[passwd] = $passwd;
	update("member",$value," WHERE idx='$g_memidx'");
	
	$result = json_encode ($redata);
	header ( 'Content-Type:application/json; charset=utf-8' );
	echo $result;
	exit;
}	

if($han=='cp')	{
	
	$value[cp] = str_replace("-","",$_REQUEST['cp']);
	update("member",$value," WHERE idx='$g_memidx'");
	
	$redata[res] = 'ok';
	$result = json_encode ($redata);
	header ( 'Content-Type:application/json; charset=utf-8' );
	echo $result;
	exit;
}
if($han=='license')	{
	$ar_member = sel_query_all("member", " WHERE idx='{$g_memidx}'");
	$ar_data = array();
	if($ar_member['driver_license_idx']) {
		$ar_data = sel_query_all("driver_license"," WHERE idx={$ar_member['driver_license_idx']}");
	}

	$value['name'] = $ar_member['name'];
	$value['cp'] = $ar_member['cp'];
	$value[kinds] = $_REQUEST['kinds'];
	$value[nums] = $_REQUEST['nums'];
	$value[date1] = $_REQUEST['date1'];
	$value[date2] = $_REQUEST['date2'];
	$value[dt_create] = date("Y-m-d H:i:s");
	if($ar_data['idx'])	{
		update("driver_license",$value," WHERE idx={$ar_data['idx']}");
	}
	else	{
		insert("driver_license",$value);
	}

	unset($value);

	$redata[res] = 'ok';
	$result = json_encode ($redata);
	header ( 'Content-Type:application/json; charset=utf-8' );
	echo $result;
	exit;
}
?>