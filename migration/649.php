<?php
/**
 *
 * Created by DonYoung.
 * Date: 2017-03-31
 * site_member 일부 값 update
 *
 */

include $_SERVER['DOCUMENT_ROOT']."/old/inc/base.php";
include $config['incPath']."/connect.php";

$r = mysql_query("UPDATE site_member set id = 'outmember' WHERE name = '개발아이디'");

