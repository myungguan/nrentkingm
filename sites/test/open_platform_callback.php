<?php
/**
 * Created by IntelliJ IDEA.
 * User: rentking
 * Date: 2017. 9. 26.
 * Time: PM 1:58
 */

include $_SERVER['DOCUMENT_ROOT']."/old/inc/base.php";
include $config['incPath']."/connect.php";

echo '<h3>open-platform callback test page</h3>';
var_dump($_REQUEST);