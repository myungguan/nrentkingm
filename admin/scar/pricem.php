<?
/**
 * admin.rentking.co.kr/scar.php?code=pricer
 * 멤버사 > 차량관리 > 단기요금관리 > 수정
 */
$idx = $_REQUEST['idx'];

$mode = $_REQUEST['mode'];
if($mode=='w')	{
	$title = mysql_escape_string($_REQUEST['title']);
	$price_discount2 = floatval($_REQUEST['price_discount2'])/100;
	$price_discount3 = floatval($_REQUEST['price_discount3'])/100;
	$price_discount4 = floatval($_REQUEST['price_discount4'])/100;
	$price = intval(str_replace(',', '', $_REQUEST['price']));
	$price_hour = intval(str_replace(',', '', $_REQUEST['price_hour']));
	$price_holiday = intval(str_replace(',', '', $_REQUEST['price_holiday']));
	$price_del1 = intval(str_replace(',', '', $_REQUEST['price_del1']));
	$price_del2 = intval(str_replace(',', '', $_REQUEST['price_del2']));
	$price_del3 = intval(str_replace(',', '', $_REQUEST['price_del3']));
	$price_del4 = intval(str_replace(',', '', $_REQUEST['price_del4']));
	$price_insu0_check = $_REQUEST['price_insu0_check'] == 'Y' ? 'Y' : 'N';
	$price_insu1_check = $_REQUEST['price_insu1_check'] == 'Y' ? 'Y' : 'N';
	$price_insu1_1 = intval(str_replace(',', '', $_REQUEST['price_insu1_1']));
	$price_insu1_2 = intval(str_replace(',', '', $_REQUEST['price_insu1_2']));
	$price_insu1_3 = intval(str_replace(',', '', $_REQUEST['price_insu1_3']));
	$price_insu1_4 = intval(str_replace(',', '', $_REQUEST['price_insu1_4']));
	$price_insu1_exem = intval(str_replace(',', '', $_REQUEST['price_insu1_exem']));
	$price_insu1_limit = intval(str_replace(',', '', $_REQUEST['price_insu1_limit']));
	$price_insu2_check = $_REQUEST['price_insu2_check'] == 'Y' ? 'Y' : 'N';
	$price_insu2_1 = intval(str_replace(',', '', $_REQUEST['price_insu2_1']));
	$price_insu2_2 = intval(str_replace(',', '', $_REQUEST['price_insu2_2']));
	$price_insu2_3 = intval(str_replace(',', '', $_REQUEST['price_insu2_3']));
	$price_insu2_4 = intval(str_replace(',', '', $_REQUEST['price_insu2_4']));
	$price_insu2_exem = intval(str_replace(',', '', $_REQUEST['price_insu2_exem']));
	$price_insu2_limit = intval(str_replace(',', '', $_REQUEST['price_insu2_limit']));
	$price_insu3_check = $_REQUEST['price_insu3_check'] == 'Y' ? 'Y' : 'N';
	$price_insu3_1 = intval(str_replace(',', '', $_REQUEST['price_insu3_1']));
	$price_insu3_2 = intval(str_replace(',', '', $_REQUEST['price_insu3_2']));
	$price_insu3_3 = intval(str_replace(',', '', $_REQUEST['price_insu3_3']));
	$price_insu3_4 = intval(str_replace(',', '', $_REQUEST['price_insu3_4']));
	$price_insu3_exem = intval(str_replace(',', '', $_REQUEST['price_insu3_exem']));
	$price_insu3_limit = intval(str_replace(',', '', $_REQUEST['price_insu3_limit']));
	$price_net = floatval(str_replace(',', '', $_REQUEST['price_net'])) / 100;

	$query = "
		UPDATE vehicle_price
		SET
			title='$title',
			price_discount2='$price_discount2',
			price_discount3='$price_discount3',
			price_discount4='$price_discount4',
			price='$price',
			price_hour='$price_hour',
			price_holiday='$price_holiday',
			price_del1='$price_del1',
			price_del2='$price_del2',
			price_del3='$price_del3',
			price_del4='$price_del4',
			price_insu0_check='$price_insu0_check',
			price_insu1_check='$price_insu1_check',
			price_insu1_1='$price_insu1_1',
			price_insu1_2='$price_insu1_2',
			price_insu1_3='$price_insu1_3',
			price_insu1_4='$price_insu1_4',
			price_insu1_exem='$price_insu1_exem',
			price_insu1_limit='$price_insu1_limit',
			price_insu2_check='$price_insu2_check',
			price_insu2_1='$price_insu2_1',
			price_insu2_2='$price_insu2_2',
			price_insu2_3='$price_insu2_3',
			price_insu2_4='$price_insu2_4',
			price_insu2_exem='$price_insu2_exem',
			price_insu2_limit='$price_insu2_limit',
			price_insu3_check='$price_insu3_check',
			price_insu3_1='$price_insu3_1',
			price_insu3_2='$price_insu3_2',
			price_insu3_3='$price_insu3_3',
			price_insu3_4='$price_insu3_4',
			price_insu3_exem='$price_insu3_exem',
			price_insu3_limit='$price_insu3_limit',
			price_net='$price_net'
		WHERE
			idx = $idx
	";
	mysql_query($query);

	echo "<script>alert('수정완료'); location.replace('/scar.php?code=pricem&idx=$idx'); </script>";
	exit;
}

$query = "
	SELECT
		*
	FROM
		vehicle_price
	WHERE
		idx = $idx
";
$item = mysql_fetch_array(mysql_query($query));
?>
<script type="text/javascript">
	function foch(f) {
		if(check_form(f) && checkPriceTable())	{
			return !!confirm('등록 하시겠습니까?');
		}
		else	{
			return false;
		}
	}
</script>
<script type="text/javascript" src="/js/price.js?20170417030349"></script>
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

	<? include "price_table.php"; ?>

	<div class="topBtn" style='text-align:center;'>
		<a href="javascript:" class="btn_submit" data-form="#regiform"><span class="greenBtn">저장</span></a>
		<a href="/scar.php?code=price"><span class="greenBtn">목록</span></a>
	</div>

</form>
		