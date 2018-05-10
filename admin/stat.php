<?
include $_SERVER['DOCUMENT_ROOT']."/old/inc/base.php";
include $config['incPath']."/connect.php";
include $config['incPath']."/session.php";
include $config['incPath']."/config.php";
include "admin_access.php";
include "adminhead.php";
include "admintop.php";

$code = $_REQUEST['code'];
?>
	<div class="leftAndMain">
		<?	include "stat/_menu.php";	?>
		<? include "admin_sidemenu_print.php"; ?>
		<div class="mainContent">
			<?	include "admin_locationbar.php";	?>
			<?	include "stat/{$code}.php";	?>
		</div><!-- mainContent -->
	</div>
<?
include "adminfoot.php";
?>
