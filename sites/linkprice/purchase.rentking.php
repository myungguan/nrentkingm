<?php
	/*
	* 링크프라이스를 통하여 생성된 실적일 경우 상품구매 완료시 링크프라이스로 실시간 실적 전송을 해야 합니다.
	*
	* 즉, 사용자 컴퓨터에 LPINFO 쿠키값이 있다면 링크프라이스로 실적 전송을 해야 하며 해당 실적을 DB에 저장해야 합니다.
	*
	* 샘플코드에는
	*
	*  1. 링크프라이스로 실시간 실적 전송.
	*  2. 링크프라이스 실적 보관
	*
	* 내용이 포함되어 있습니다.
	*
	* 샘플코드를 자사의 시스템에 맞게 수정하여 상품구매 완료 페이지에 삽입하여 주시면 되겠습니다.
	*/

	/*
	* 사용자가 다른 종류의 여러 물품을 장바구니에 담아 구매를 할 수 있습니다.
	* 이와같은 복수 상품을 링크프라이스로 전송할 때에는 각 상품별 정보를 구분하여 보내게 됩니다.
	* 이를 처리하기 위하며 밑의 for loop가 실행됩니다.
	* 하지만 장바구니를 처리하는 부분이 있다면 for loop 구문을 제외한 후
	*
	*   $p_cd_ar[]   = $product_code;
	*   $it_cnt_ar[] = $item_count;
	*   $c_cd_ar[]   = $category_code;
	*   $sales_ar[]  = $price;
	*   $p_nm_ar[]   = $product_name;
	*
	* 부분을 장바구니 처리 부분에 넣어주셔도 되겠습니다.
	* $product_code는 상품코드 입니다.
	* $item_cout는 물품의 개수 입니다. 즉, $product_code가 A인 물품을 2개 구매했다면 2가 되겠습니다.
	* $category_code는 물품의 카테고리 코드값 입니다. 만약 카테고리 코드가 없다면 null로 처리해 주시기 바랍니다.
	* $price에는 물품의 가격을 넣어주시면 되겠습니다. 하지만 상품의 unitprice가 아닌 item_count*unitprice의 값입니다.
	* 즉, 상품 A를 2개 구매했고, 1개의 가격이 1000원이라면 $price는 2000원이 되겠습니다. 따라서 2000을 넣어주시면 되겠습니다.
	* 판매가격이 마이너스 인 경우 0 원으로 변경하도록 예외처리 해주셔야 합니다.
	* $product_name 은 상품이름입니다.
	* 캐릭터셋이 UTF-8일경우 EUC-KR로 변환하시면 되겠습니다.
	* 이렇게 생성된 array의 값은 밑의 실적 전송 부분에서 사용됩니다.
	* 자세한 사항은 http://setup.linkprice.com 을 참고하여 주시기 바랍니다.
	*/

if (isset($_COOKIE["LPINFO"]))		// 링크프라이스 통해서 온 사용자일 경우 (in case the user visits via Linkprice’s affiliate)
{

	// 주문 상품 수
	$item_cnt = 1;

	$pos = strpos($_SERVER['HTTP_HOST'], 'www.');
	for ($i = 0; $i < $item_cnt; $i++)
	{

		$p_cd_ar[]   = $order['vehicle_idx'];		// 링크프라이스 전송태그 생성용 배열 (data array to send data to Linkprice)
		$it_cnt_ar[] = 1;
		$c_cd_ar[]   = ($pos !== false && $pos == 0 ? 'web' : 'mobile').'_'.$order['retype'];
		$sales_ar[]  = $paymentResult['AMT'];					// 판매액. 상품단가는 판매액/판매수량으로 유추. (Sales amount. Unit price of product can be inferred by $sales_amount/ $Item_count)

		$p_nm_ar[]   = $order['retype'] == 1 ? '단기예약' : '장기예약';
	}

	// 링크프라이스 어필리에이를 통해 들어왔을때 사용자 터미널에 저장되는 쿠키 값
	// (cookie saved in user’s terminal. This cookie is issued by merchant system when a user visit via Linkprice’s affiliate)

	$order_code = $order['idx'];
	/*
	* a_id   : LPINFO 쿠키값 입니다. 샘플코드 그대로 사용하시면 되겠습니다.
	* m_id   : 자사의 ID 값입니다. (머천트 아이디) 샘플코드 그대로 사용하시면 되겠습니다.
	* mbr_id : 상품구매를 한 사용자 정보 입니다. '사용자ID(이름)' 형태로 넣어주시면 되겠습니다.
	*          $id, $name 을 자사의 시스템에 맞는 값으로 수정해 주시면 되겠습니다.
	* o_cd   : 상품구매 주문번호 입니다. $order_code에 자사의 주문번호 값을 넣어주시면 되겠습니다.
	* 결제 완료페이지에 SSL을 사용하신다면 밑의 URL을
	* https://service.linkprice.com/lppurchase.php 로 수정해 주시기 바랍니다.
	*/

	$linkprice_url = "http://service.linkprice.com/lppurchase.php";     // 수정 금지. 실적 전송 URL (postback URL)
	$linkprice_url.= "?a_id=".$_COOKIE["LPINFO"];                       // 수정 금지. LPINFO 쿠키정보 (LPINFO Cookie)
	$linkprice_url.= "&m_id=rentking";                           // 수정 금지. 머천트 ID (ID of merchant in Linkprice system)
	$linkprice_url.= "&mbr_id=".$ar_init_member["id"]."(".$ar_init_member["name"].")";                      // $id = 사용자 ID값, $name = 사용자 이름값, 만약 둘 중 없는 값이 있다면 존재하는 값만을 넣어주시기 바랍니다. 회원 ID (ID of purchaser in merchant system)
	$linkprice_url.= "&o_cd=".$order_code;                              // 수정 금지. 주문코드 (order number)
	$linkprice_url.= "&p_cd=".implode("||", $p_cd_ar);                  // 수정 금지. 상품코드 (product code)
	$linkprice_url.= "&it_cnt=".implode("||", $it_cnt_ar);              // 수정 금지. 상품개수 (number of products sales)
	$linkprice_url.= "&sales=".implode("||", $sales_ar);                // 수정 금지. 판매액 (sales amount)
	$linkprice_url.= "&c_cd=".implode("||", $c_cd_ar);                  // 수정 금지. 카테고리코드 (category code)
	$linkprice_url.= "&p_nm=".implode("||", $p_nm_ar);                  // 수정 금지. 상품이름 (name of product)
	$linkprice_url.= "&remote_addr=".($_SERVER['HTTP_CF_CONNECTING_IP'] ? $_SERVER['HTTP_CF_CONNECTING_IP'] : $_SERVER['REMOTE_ADDR']);           // 원격 주소

	/*
	* 링크프라이스 data 암호화 처리
	*
	* lpbase64.php 파일을 같은 폴더에 저장하셨을 경우
	*
	* require_once "lpbase64.php"; 부분을 그대로 사용하시면 되겠습니다.
	*
	* 하지만 변경되었을 경우 정확한 경로를 넣어주셔야 합니다.
	*
	* code, pad 값은 절대 수정하시면 안됩니다. 소스코드의 내용 그대로 사용해 주시기 바랍니다.
	*/

	require_once "lpbase64.php";

	$code = "04222";				// 암호화 코드 값 (encryption code)
	$pad = "DnRCvX24uspFIY1iVh7Ut6wH9gqoO.MGebPzQLBy8x30mEJT*kd5NjfalZrWASKc";				// Encryption pad


	$linkprice_url = lp_url_trt($linkprice_url, $code, $pad);		// 실적 암호화 (Encryption)
	$linkprice_tag = "<script type=\"text/javascript\" src=\"".$linkprice_url."\"> </script>";

	/*
	* 링크프라이스 실적 DB에 저장
	*
	* 링크프라이스를 통하여 발생된 실적은 DB에 따로 저장을 해주셔야 합니다.
	* 실적 발생시 실시간으로 전송이 되지만 누락이 될 수 있기 때문에 크로스 체크를 해야 합니다.
	* 따라서 실적을 저장해 주셔야 하며, 링크프라이스에서 이 데이터를 하루에 한번씩 받아서 누락된 실적을 복구처리 합니다.
	* 이곳에선 실적을 DB에 저장합니다.
	* DB에 저장하기 위해서 링크프라이스 테이블을 생성하셔야 합니다. 혹은 자사의 테이블에 field를 추가해 주셔도 됩니다.
	* 그리고 실적 저장시 LPINFO 쿠키값도 꼭 함께 저장해 주셔야 합니다. 이는 varchar2(300)으로 해주셔야 합니다.
	*
	* 테이블 생성 예제,
	*
	*	create table TLINKPRICE (
	*		LPINFO         varchar2(300),
	*		YYYYMMDD       char(8),
	*		HHMISS         char(6),
	*		ORDER_CODE     varchar2(100),  // 자사의 주문번호(o_cd) field 와 같게 처리해 주시기 바랍니다.
	*		PRODUCT_CODE   varchar2(100),  // 자사의 상품코드(p_cd) field와 같게 처리해 주시기 바랍니다.
	*		ITEM_COUNT     number(5),      // 자사의 구매수(it_cnt) field와 같게 처리해 주시기 바랍니다.
	*		PRICE          number(8),      // 자사의 물품가격(sales) field와 같게 처리해 주시기 바랍니다.
	*		PRODUCT_NAME   varchar2(100),  // 자사의 상품이름(p_nm) field와 같게 처리해 주시기 바랍니다.
	*		CATEGORY_CODE  varchar2(100),  // 자사의 카테고리코드(c_cd) field와 같게 처리해 주시기 바랍니다.
	*		ID             varchar2(10),   // 자사의 사용자ID field와 같게 처리해 주시기 바랍니다.
	*		NAME           varchar2(10),   // 자사의 사용자 이름 field와 같게 처리해 주시기 바랍니다.
	*		REMOTE_ADDR    varchar2(100)   // 상품을 구매한 사용자의 IP를 field에 저장해 주시기 바랍니다.
	*	)
	*
	* data insert 예제,
	*
	*	$ymd = date("Ymd");
	*	$his = date("His");
	*
	*	for ($i = 0; $i < $item_cnt; $i++)
	*	(
	*		$sql = "
	*			insert into tlinkprice
	*				(
	*					lpinfo, yyyymmdd, hhmiss,
	*					order_code, product_code, item_count, price, product_name, category_code,
	*					id, name, remote_addr
	*				)
	*			values
	*				(
	*					'".$_COOKIE["LPINFO"]."', '".$ymd."', '".$his."',
	*					'".$order_code."', '".$p_cd_ar[$i]."', ".$it_cnt_ar[$i].", ".$price[$i].", '".$p_nm_ar[$i]."', '".$c_cd_ar[$i]."',
	*					'".$id."', '".$name."', '".($_SERVER['HTTP_CF_CONNECTING_IP'] ? $_SERVER['HTTP_CF_CONNECTING_IP'] : $_SERVER['REMOTE_ADDR'])."'
	*				)
	*		";
	*		DB execute 실행
	*	)
	*
	* 위의 예제와 같이 테이블을 생성하신 후 실적 전송시에 해당 데이터를 테이블에 저장하시면 되겠습니다.
	* DB에 실적을 꼭 저장해 주셔야 합니다.
	*/

	$sql = "
		insert into linkprice (
				lpinfo, dt, order_code, product_code, item_count, price, product_name, category_code, id, name, remote_addr
		) values (
				'".$_COOKIE["LPINFO"]."', NOW(),
				'".$order_code."', '".$p_cd_ar[0]."', ".$it_cnt_ar[0].", ".$sales_ar[0].", '".$p_nm_ar[0]."', '".$c_cd_ar[0]."',
				'".$ar_init_member["id"]."', '".$ar_init_member["name"]."', '".($_SERVER['HTTP_CF_CONNECTING_IP'] ? $_SERVER['HTTP_CF_CONNECTING_IP'] : $_SERVER['REMOTE_ADDR'])."'
			)
	";
	mysql_query($sql);

	echo $linkprice_tag;
}
?>
<!-- 링크프라이스로 실적 전송 (Transmission to Linkprice) -->