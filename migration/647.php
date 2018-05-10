<?php
/**
 *
 * Created by DonYoung.
 * Date: 2017-03-29
 * site_vehicle_opt_std 필드값 변경
 * ishave = Y
 *
 */

include $_SERVER['DOCUMENT_ROOT']."/old/inc/base.php";
include $config['incPath']."/connect.php";

$r = mysql_query("UPDATE site_vehicle_opt_std set ishave = 'Y' WHERE ishave = ''");

