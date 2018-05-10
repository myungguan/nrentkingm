<?php
/**
 * Created by Sanggoo.
 * Date: 2017-03-10
 */


$lpinfo = $_REQUEST["lpinfo"];
$rd = $_REQUEST["rd"];

include $_SERVER['DOCUMENT_ROOT']."/old/inc/base.php";
include $config['incPath']."/connect.php";
include $config['incPath']."/session.php";

Header("P3P:CP=\"NOI DEVa TAIa OUR BUS UNI\"");
if(strpos($_SERVER['HTTP_USER_AGENT'], 'rentking') !== false) {
	//ilikeclick 쿠키 제거
	if (isset($_COOKIE ['c_ValueFromClick'])) {
		$SITE_URL = "http://" . $_SERVER['HTTP_HOST'];
		$temp_dns = strtolower( $SITE_URL );
		if ( substr( $temp_dns, 7, 3 ) == "www" ) {
			$temp_dns = substr( $temp_dns, 10 );
		} else {
			$temp_dns = "." . substr( $temp_dns, 7 );
		}

		list( $temp_dns ) = explode( "/", $temp_dns );
		list( $DNS_NAME ) = explode( ":", $temp_dns );
		unset( $_COOKIE['c_ValueFromClick'] );
		setcookie( 'c_ValueFromClick', null, - 1, "/", $DNS_NAME );
	}

	SetCookie("LPINFO", $lpinfo, time() + ($rd * 24 * 60 * 60), "/", ".rentking.co.kr");

	$order_code = 'install_'.time();
	$sql = "
		insert into linkprice (
				lpinfo, dt, order_code, product_code, item_count, price, product_name, category_code, id, name, remote_addr, type
		) values (
				'".$_COOKIE["LPINFO"]."', NOW(),
				'$order_code', 'install', 1, 0, 'RentKing', 'install',
				'rentking', 'install', '".($_SERVER['HTTP_CF_CONNECTING_IP'] ? $_SERVER['HTTP_CF_CONNECTING_IP'] : $_SERVER['REMOTE_ADDR'])."', 'cpi'
			)
	";
	mysql_query($sql);
	$m_id = 'rentapp';
	if(isset($_REQUEST['m_id'])) {
		$m_id = $_REQUEST['m_id'];
	}

	$linkprice_url = "http://service.linkprice.com/lppurchase.php";     // 수정 금지. 실적 전송 URL (postback URL)
	$linkprice_url.= "?a_id=".$_COOKIE["LPINFO"];                       // 수정 금지. LPINFO 쿠키정보 (LPINFO Cookie)
	$linkprice_url.= "&m_id=$m_id";                                 	 // 수정 금지. 머천트 ID (ID of merchant in Linkprice system)
	$linkprice_url.= "&mbr_id=rentking(install)";						// $id = 사용자 ID값, $name = 사용자 이름값, 만약 둘 중 없는 값이 있다면 존재하는 값만을 넣어주시기 바랍니다. 회원 ID (ID of purchaser in merchant system)
	$linkprice_url.= "&o_cd=".$order_code;                              // 수정 금지. 주문코드 (order number)
	$linkprice_url.= "&p_cd=install";                  // 수정 금지. 상품코드 (product code)
	$linkprice_url.= "&it_cnt=1";              // 수정 금지. 상품개수 (number of products sales)
	$linkprice_url.= "&sales=0";                // 수정 금지. 판매액 (sales amount)
	$linkprice_url.= "&c_cd=install";                  // 수정 금지. 카테고리코드 (category code)
	$linkprice_url.= "&p_nm=RentKing";                  // 수정 금지. 상품이름 (name of product)

	require_once "../lpbase64.php";

	$code = "04222";				// 암호화 코드 값 (encryption code)
	$pad = "DnRCvX24uspFIY1iVh7Ut6wH9gqoO.MGebPzQLBy8x30mEJT*kd5NjfalZrWASKc";				// Encryption pad


	$linkprice_url = lp_url_trt($linkprice_url, $code, $pad);		// 실적 암호화 (Encryption)
	$linkprice_tag = "<script type=\"text/javascript\" src=\"".$linkprice_url."\"> </script>";

	echo $linkprice_tag;
	echo '<script type="text/javascript">location.href="'."http://".$_SERVER["HTTP_HOST"].'"</script>';
}