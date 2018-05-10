<?php
$_sidemenu['정산관리'][] = array('name'=>'정산내역', 'code'=>'list');
if($_SESSION['member_grade']!='10') {
	$_sidemenu['정산관리'][] = array('name' => '링크프라이스', 'code' => 'linkprice');
	$_sidemenu['정산관리'][] = array('name' => '아이라이크클릭', 'code' => 'ilikeclick');
}