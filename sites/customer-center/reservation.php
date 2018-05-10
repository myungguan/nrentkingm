<?
/**
 * www.rentking.co.kr/customer-center/reservation.php
 * 메인 > English
 * 영문예약시, DB저장 파트
 */
include $_SERVER['DOCUMENT_ROOT']."/old/inc/base.php";
include $config['incPath']."/connect.php";
include $config['incPath']."/session.php";
include $config['incPath']."/config_site.php";

$page['type'] = "2";
$page['title'] = "RESERVATION";

$mode = $_REQUEST['mode'];
if ($mode == 'w') {
    $value[location] = mysql_escape_string($_REQUEST['location']);
    $value[sdate] = $_REQUEST['sdate'];
    $value[edate] = $_REQUEST['edate'];
    $value[vclass] = $_REQUEST['vclass'];
    $value[car] = mysql_escape_string($_REQUEST['car']);
    $value[dtype] = $_REQUEST['dtype'];
    $value[detail] = mysql_escape_string($_REQUEST['detail']);
    $value[fname] = mysql_escape_string($_REQUEST['fname']);
    $value[lname] = mysql_escape_string($_REQUEST['lname']);
    $value[phone] = mysql_escape_string($_REQUEST['phone']);
    $value[email] = mysql_escape_string($_REQUEST['email']);
    $value[age] = $_REQUEST['age'];
    $value[resident] = mysql_escape_string($_REQUEST['resident']);
    $value[airname] = mysql_escape_string($_REQUEST['airname']);
    $value[fnumber] = mysql_escape_string($_REQUEST['fnumber']);
    $value[wdate] = date("Y-m-d H:i:s");
    $value[wip] = $_nip;
    insert("eng", $value);
    $idx = mysql_insert_id();

    fileUpload('file', 'eng', $idx);

    echo "<script>alert('your reservation is saved. thank you!');location.replace('$PHP_SELF');  </script>";
    exit;
}

$tpls->assign('page', $page);
$tpls->print_('mains');
?>
