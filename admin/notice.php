<?php
/**
 * Created by Sanggoo.
 * Date: 2017-09-20
 */

include $_SERVER['DOCUMENT_ROOT']."/old/inc/base.php";
include $config['incPath']."/connect.php";
include $config['incPath']."/session.php";
include $config['incPath']."/config.php";

$idx = $_REQUEST['idx'];

$query = "UPDATE notices SET read_count = read_count + 1 WHERE idx = $idx";
mysql_query($query);

$query = "SELECT * FROM notices WHERE idx = $idx";
$notice = mysql_fetch_assoc(mysql_query($query));

?>

<div style="width:500px;">
	<?=$notice['content'] ?>
</div>
