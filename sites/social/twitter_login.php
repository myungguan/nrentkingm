<?
session_start();
define('CONSUMER_KEY', '7NYGewYeQiA8RslGdxNjUfr5d');
define('CONSUMER_SECRET', 'SDOOmXYawluGDnYbM3SaaxjpJCIRjIWj1mB38wowJTakjIsEkC');
define('OAUTH_CALLBACK', 'http://' . $_SERVER['HTTP_HOST'] . '/social/twitter_callback.php');

include_once("twitteroauth.php");


$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
$request_token = $connection->getRequestToken(OAUTH_CALLBACK);

//Received token info from twitter
$_SESSION['token'] = $request_token['oauth_token'];
$_SESSION['token_secret'] = $request_token['oauth_token_secret'];

//Any value other than 200 is failure, so continue only if http code is 200

if ($connection->http_code == '200') {
	//redirect user to twitter
	$twitter_url = $connection->getAuthorizeURL($request_token['oauth_token']);
	header('Location: ' . $twitter_url);
} else {
	die("error connecting to twitter! try again later!");
}
?>