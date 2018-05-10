<?
/**
 * 어드민 > 고객관리 사이드 메뉴
 */
$_sidemenu['고객관리'][] = array('name'=>'회원', 'code'=>'list');
$_sidemenu['고객관리'][] = array('name'=>'멤버사', 'code'=>'rentcar');
//$_sidemenu['고객관리'][] = array('name'=>'커넥터', 'code'=>'salesdealer');
if(!$config['production'] ||
   $_SESSION['member_id'] == 'shin@rentking.co.kr' ||
   $_SESSION['member_id'] == 'sanggoo@rentking.co.kr' ||
   $_SESSION['member_id'] == 'admin'
) {
	$_sidemenu['고객관리'][] = array('name'=>'관리자등록', 'code'=>'manage');
}
