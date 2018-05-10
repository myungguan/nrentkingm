<?php
$_sidemenu['예약관리'][] = array('name'=>'예약관리', 'code'=>'list');
if(in_array($_SESSION['admin_grade'], [9]))	{
$_sidemenu['예약관리'][] = array('name'=>'정기결제관리', 'code'=>'pays');
}
