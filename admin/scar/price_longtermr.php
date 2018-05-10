<?
/**
 * admin.rentking.co.kr/scar.php?code=price_longtermr
 * 멤버사 > 차량관리 > 장기요금관리 > 등록
 */
$mode = $_REQUEST['mode'];
if($mode=='w')	{
	$ar_rent = sel_query_all("rentshop"," WHERE idx={$_SESSION['rentshop_idx']}");

	$title = mysql_escape_string($_REQUEST['title']);
	$price_longterm1 = intval(str_replace(',', '', $_REQUEST['price_longterm1']));
	$price_longterm2 = intval(str_replace(',', '', $_REQUEST['price_longterm2']));
	$price_longterm3 = intval(str_replace(',', '', $_REQUEST['price_longterm3']));
	$price_longterm_deposit1 = intval(str_replace(',', '', $_REQUEST['price_longterm_deposit1']));
	$price_longterm_deposit2 = intval(str_replace(',', '', $_REQUEST['price_longterm_deposit2']));
	$price_longterm_deposit3 = intval(str_replace(',', '', $_REQUEST['price_longterm_deposit3']));
	$price_longterm_insu_exem = intval(str_replace(',', '', $_REQUEST['price_longterm_insu_exem']));
	$price_longterm_insu_limit = intval(str_replace(',', '', $_REQUEST['price_longterm_insu_limit']));
	$distance_limit = intval(str_replace(',', '', $_REQUEST['distance_limit']));
	$distance_additional_price = intval(str_replace(',', '', $_REQUEST['distance_additional_price']));
	$price_longterm_net = floatval(str_replace(',', '', $_REQUEST['price_longterm_net'])) / 100;

	$query = "
		INSERT INTO
			vehicle_price_longterm (
				member_idx, 
				rentshop_idx,
				managed,
				title,
				price_longterm1,
				price_longterm2,
				price_longterm3,
				price_longterm_deposit1,
				price_longterm_deposit2,
				price_longterm_deposit3,
				price_longterm_insu_exem,
				price_longterm_insu_limit,
				distance_limit,
				distance_additional_price,
				price_longterm_net,
				dt_create
			) VALUES (
				'$g_memidx',
				'{$ar_rent['idx']}',
				'Y',
				'$title',
				'$price_longterm1',
				'$price_longterm2',
				'$price_longterm3',
				'$price_longterm_deposit1',
				'$price_longterm_deposit2',
				'$price_longterm_deposit3',
				'$price_longterm_insu_exem',
				'$price_longterm_insu_limit',
				'$distance_limit',
				'$distance_additional_price',
				'$price_longterm_net',
				NOW()
			)
	";
	mysql_query($query);

	echo "<script>alert('등록완료'); location.replace('/scar.php?code=price_longterm'); </script>";
	exit;
}
?>
<script type="text/javascript">
	function foch(f) {
		if(check_form(f) && checkPriceLongtermTable())	{
			return !!confirm('등록 하시겠습니까?');
		}
		else	{
			return false;
		}
	}
</script>
<script type="text/javascript" src="/js/price.js?20170417030345"></script>
<form name="regiform" id="regiform" action="/scar.php?code=<?=$code;?>" method="post" onsubmit="return foch('regiform');" enctype="multipart/form-data">
	<input type="hidden" name="mode" value="w" />
	
	<table class="listTable3">
		<tbody>
		<tr>
			<th>제목</th>
			<td><input type="text" name="title" placeholder="제목을 입력하세요" maxlength="90" style="width:400px;" valch="yes" msg="제목을" /></td>
		</tr>
		</tbody>
	</table>

	<? include "price_table_longterm.php"; ?>

	<div class="topBtn" style='text-align:center;'>
		<a href="javascript:" class="btn_submit" data-form="#regiform"><span class="greenBtn">저장</span></a>
		<a href="/scar.php?code=price_longterm"><span class="greenBtn">목록</span></a>
	</div>

</form>
		