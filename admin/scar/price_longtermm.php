<?
/**
 * admin.rentking.co.kr/scar.php?code=price_longtermm&idx=
 * 멤버사 > 차량관리 > 장기요금관리 > 수정
 */
$idx = $_REQUEST['idx'];

$mode = $_REQUEST['mode'];
if($mode=='w')	{
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
		UPDATE vehicle_price_longterm
		SET
			title='$title',
			price_longterm1='$price_longterm1',
			price_longterm2='$price_longterm2',
			price_longterm3='$price_longterm3',
			price_longterm_deposit1='$price_longterm_deposit1',
			price_longterm_deposit2='$price_longterm_deposit2',
			price_longterm_deposit3='$price_longterm_deposit3',
			price_longterm_insu_exem='$price_longterm_insu_exem',
			price_longterm_insu_limit='$price_longterm_insu_limit',
			distance_limit='$distance_limit',
			distance_additional_price='$distance_additional_price',
			price_longterm_net='$price_longterm_net'
		WHERE
			idx = $idx
	";
	mysql_query($query);

	echo "<script>alert('수정완료'); location.replace('/scar.php?code=price_longtermm&idx=$idx'); </script>";
	exit;
}

$query = "
	SELECT
		*
	FROM
		vehicle_price_longterm
	WHERE
		idx = $idx
";
$item = mysql_fetch_array(mysql_query($query));
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
<script type="text/javascript" src="/js/price.js?20170417030337"></script>
<form name="regiform" id="regiform" action="/scar.php?code=<?=$code;?>" method="post" onsubmit="return foch('regiform');" enctype="multipart/form-data">
	<input type="hidden" name="mode" value="w" />
	<input type="hidden" name="idx" value="<?=$item['idx']?>" />

	<table class="detailTable2">
		<tbody>
		<tr>
			<th>제목</th>
			<td><input type="text" name="title" placeholder="제목을 입력하세요" maxlength="90" style="width:400px;" valch="yes" msg="제목을" value="<?=$item['title'] ?>" /></td>
		</tr>
		</tbody>
	</table>

	<? include "price_table_longterm.php"; ?>

	<div class="topBtn" style='text-align:center;'>
		<a href="javascript:" class="btn_submit" data-form="#regiform"><span class="greenBtn">저장</span></a>
		<a href="/scar.php?code=price_longterm"><span class="greenBtn">목록</span></a>
	</div>

</form>
		