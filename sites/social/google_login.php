<?
error_reporting(E_ALL);
ini_set("display_errors", 1);

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

$authUrl = $client->createAuthUrl();

header('Location: '."$authUrl");


