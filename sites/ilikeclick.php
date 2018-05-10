<?php
// +----------------------------------------------------------------------------------------------+
// |  ILIKECLICK GATEPAGE Version 2.8                                                             |
// +----------------------------------------------------------------------------------------------+

// 고객님께서 직접 입력하실 부분입니다.
// 고객님의 SITE URL을 입력하십시오. (http:// 를 꼭 포함해서 입력 바랍니다.)
// 예를 들어 다움의 경우 http://www.daum.net 만 입력하시면 됩니다.

require_once("../inc/Mobile_Detect.php");
$detect = new Mobile_Detect();
$ismobile = $detect->isMobile();

if($ismobile) {
    $host = str_replace('www.', 'm.', $_SERVER['HTTP_HOST']);
} else {
    $host = str_replace('m.', 'www.', $_SERVER['HTTP_HOST']);
}

$SITE_URL = "http://".$host;

//echo $SITE_URL;
//exit();

// +----------------------------------------------------------------------------------------------+
// |  [경고] 아래의 프로그램은 변경하거나 수정하지 마십시오.                                      |
// +----------------------------------------------------------------------------------------------+

if ($HTTP_SERVER_VARS['REQUEST_METHOD'] == 'GET')
{
    $version        = $HTTP_GET_VARS['version'];
    $ValueFromClick = $HTTP_GET_VARS['ValueFromClick'];
    $Cookie_Time    = $HTTP_GET_VARS['Cookie_Time'];
    $tURL           = $HTTP_GET_VARS['tURL'];
}

if ($HTTP_SERVER_VARS['REQUEST_METHOD'] == 'POST')
{
    $version        = $HTTP_POST_VARS['version'];
    $ValueFromClick = $HTTP_POST_VARS['ValueFromClick'];
    $Cookie_Time    = $HTTP_POST_VARS['Cookie_Time'];
    $tURL           = $HTTP_POST_VARS['tURL'];
}

if (!isset($HTTP_SERVER_VARS))
{
    $version        = $_REQUEST['version'];
    $ValueFromClick = $_REQUEST['ValueFromClick'];
    $Cookie_Time    = $_REQUEST['Cookie_Time'];
    $tURL           = $_REQUEST['tURL'];
}

if ($version == "check")
{
    echo "ILikeClick Gate Version 2.8";
    exit;
}

$temp_dns = strtolower($SITE_URL);

if (substr($temp_dns, 0, 7) != "http://")
{
    echo "[오류] ilikeclick.php 파일에서 \$SITE_URL을 확인하십시오.";
    exit;
}

if (substr($temp_dns, 7, 3) == "www")
{
    $temp_dns = substr($temp_dns, 10);
}
else
{
    $temp_dns = ".".substr($temp_dns, 7);
}

list($temp_dns) = explode("/", $temp_dns);
list($DNS_NAME) = explode(":", $temp_dns);

//P3P 개인보호정책에 대한 쿠키문제 해결
Header("P3P: CP='NOI ADM DEV PSAi COM NAV OUR OTR STP IND DEM'");

if ($Cookie_Time == "0")
{
    $Cookie_Time = "0";
}
else
{
    $Cookie_Time = time() + ($Cookie_Time * 24 * 60 * 60);
}

//iLikeClick 에서 전달받은 값을 쿠키 세팅 (차후에 iLikeClick으로 재전송.)
setcookie( "c_ValueFromClick", $ValueFromClick, $Cookie_Time, "/", $DNS_NAME );

//이동할 타겟 URL 확인. tURL 값이 NULL아닐 경우 해당 페이지
if($tURL != NULL)
{
    $SITE_URL = $tURL;
}

Header("Location: $SITE_URL");
?>