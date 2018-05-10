<?
$_result = side_menu($_SERVER['QUERY_STRING'], $_sidemenu);
$_side_html = $_result[0];
$locationcur[0] = "";
if ( is_array($_result[1]) ) {
	$locationcur = @array_merge($locationcur, $_result[1]);
}
?>
<div class="leftMenuContaner">
	<div>
		<? if($PHP_SELF=='/mem.php') {?>
		<img src="/img/leftMenuPageOn_1.jpg">
		<?}?>
		<? if($PHP_SELF=='/car.php') {?>
		<img src="/img/leftMenuPageOn_3.jpg">
		<?}?>
		<? if($PHP_SELF=='/rent.php') {?>
		<img src="/img/sub/leftMenuPageOn_1.jpg">
		<?}?>
		<? if($PHP_SELF=='/reserve.php') {?>
		<img src="/img/leftMenuPageOn_2.jpg">
		<?}?>
		<? if($PHP_SELF=='/scar.php') {?>
		<img src="/img/sub/leftMenuPageOn_3.jpg">
		<?}?>
		<? if($PHP_SELF=='/sho.php') {?>
		<img src="/img/leftMenuPageOn_4.jpg">
		<?}?>
		<?=$_side_html?>
	</div>
</div>