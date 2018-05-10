<?
/**
 * /about/alliance.php
 * 회사소개 > 입점/제휴문의
 */
include $_SERVER['DOCUMENT_ROOT']."/old/inc/base.php";
include $config['incPath']."/connect.php";
include $config['incPath']."/session.php";
include $config['incPath']."/config_site.php";

$page['type'] = "2";
$page['title'] = "입점/제휴문의";

$mode = $_REQUEST['mode'];

if($mode == 'w') {
	$company = mysql_escape_string($_REQUEST['company']);
	$location = mysql_escape_string($_REQUEST['location']);
	$manager = mysql_escape_string($_REQUEST['manager']);
	$contact = mysql_escape_string($_REQUEST['contact']);
	$email = mysql_escape_string($_REQUEST['email']);
	$content = mysql_escape_string($_REQUEST['content']);

	$query = "INSERT INTO alliance (company, location, manager, contact, email, content, dt_create) VALUES ('$company', '$location', '$manager', '$contact', '$email', '$content', NOW())";
	mysql_query($query);

	?>
	<script type="text/javascript">
		alert('전송되었습니다.');
		location.replace('/about/alliance.php');
	</script>
	<?
//	header("Location: /about/alliance.php");
} else {
	$query = "SELECT * FROM area WHERE LENGTH(ac_code) = 2 ORDER BY orders";
	$r = mysql_query($query);
	$locations = [];
	while($row = mysql_fetch_assoc($r)) {
		$locations[] = $row;
	}
	$tpls->assign('locations', $locations);
	$tpls->assign('page', $page);
	$tpls->print_('mains');
}

?>
