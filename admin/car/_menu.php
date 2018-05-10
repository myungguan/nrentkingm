<?
/**
 * 어드민 > 차량관리 > 사이드메뉴
 */
if(in_array($_SESSION['admin_grade'], [9]))	{ $_sidemenu['차량관리'][] = array('name'=>'모델관리', 'code'=>'plist'); }
if(in_array($_SESSION['admin_grade'], [9, 1]))	{ $_sidemenu['차량관리'][] = array('name'=>'등록차량 이미지관리', 'code'=>'plist_image'); }
if(in_array($_SESSION['admin_grade'], [9]))	{ $_sidemenu['차량관리'][] = array('name'=>'등록차량관리', 'code'=>'rentshoplist_s1'); }
if(in_array($_SESSION['admin_grade'], [9]))	{ $_sidemenu['차량관리'][] = array('name'=>'업체별 등록차량관리', 'code'=>'rentshoplist');  }
if(in_array($_SESSION['admin_grade'], [9]))	{ $_sidemenu['차량관리'][] = array('name'=>'차종별 등록현황', 'code'=>'carlist'); }
if(in_array($_SESSION['admin_grade'], [9]))	{ $_sidemenu['차량관리'][] = array('name'=>'차량속성관리', 'code'=>'carcon1'); }
if(in_array($_SESSION['admin_grade'], [9]))	{ $_sidemenu['차량관리'][] = array('name'=>'차량옵션관리', 'code'=>'caropt'); }
