<?
include $_SERVER['DOCUMENT_ROOT']."/old/inc/base.php";
include $config['incPath']."/connect.php";
include $config['incPath']."/session.php";
include $config['incPath']."/config_site.php";

header('Location: '."https://kauth.kakao.com/oauth/authorize?client_id=a08e5758805122cf8ad8c4afc1d395b3&redirect_uri=http://" . $_SERVER[HTTP_HOST] . "/social/kakao_callback.php&response_type=code&state=" . md5('111'));
exit;
?>