<?
include $_SERVER['DOCUMENT_ROOT']."/old/inc/base.php";
include $config['incPath']."/connect.php";
include $config['incPath']."/session.php";
include $config['incPath']."/config_site.php";

require_once './fb/autoload.php';


$fb = new Facebook\Facebook([
	'app_id' => '397761010564739', // Replace {app-id} with your app id
	'app_secret' => '26df12b0a5f7c17b44e858a7f0bb2e40',
	'default_graph_version' => 'v2.2',
]);

$helper = $fb->getRedirectLoginHelper();

$permissions = ['email']; // Optional permissions
$loginUrl = $helper->getLoginUrl("http://" . $_SERVER[HTTP_HOST] . "/social/fb_callback.php", $permissions);

header('Location: '."$loginUrl");