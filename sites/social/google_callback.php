<?
require_once 'Google/autoload.php';
require_once "Google/Client.php";

$client = new Google_Client();
$client->setScopes(array(
	"https://www.googleapis.com/auth/plus.login",
	"https://www.googleapis.com/auth/userinfo.email"
));
$client->setRedirectUri("http://komuseum.cafe24.com/social/google_callback.php");
$client->setClientId("1006497533154-sqraiahfago6p6237utmnke4sq2o0rrv.apps.googleusercontent.com");
$client->setClientSecret("_BBWX1SBtbPRq241UxsCDcqS");

$objOAuthService = new Google_Service_Oauth2($client);


$client->authenticate($_GET['code']);
$access_token = $client->getAccessToken();

$userData = $objOAuthService->userinfo->get();

header('Location: '."/member/login_social.php?id=" . $userData[id] . "&name=" . $userData[name] . "&email=" . $userData[email] . "&channel=google");