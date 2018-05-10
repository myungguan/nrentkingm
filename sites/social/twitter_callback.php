<?
session_start();
define('CONSUMER_KEY', '7NYGewYeQiA8RslGdxNjUfr5d');
define('CONSUMER_SECRET', 'SDOOmXYawluGDnYbM3SaaxjpJCIRjIWj1mB38wowJTakjIsEkC');

//ini_set('error_reporting',E_ALL | E_STRICT);

//ini_set("display_errors", 1);


include_once("twitteroauth.php");


if ($_REQUEST['oauth_verifier'] != "" && $_REQUEST['oauth_token'] && $_SESSION['token_secret'] != "") {

	$twitteroauth = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $_REQUEST['oauth_token'], $_SESSION['token_secret']);

	$access_token = $twitteroauth->getAccessToken($_REQUEST['oauth_verifier']);


	$_SESSION['access_token'] = $access_token;

	$params = array('include_email' => 'true', 'include_entities' => 'false', 'skip_status' => 'true');
	$user_info = $twitteroauth->get('account/verify_credentials', $params);

	$twt_id = $user_info->id;
	$twt_email = $user_info->email;
	$twt_name = $user_info->name;

	if (!$twt_email) {
		$twt_email = $twt_id;
	}


	header('Location: '."/member/login_social.php?id=" . $twt_id . "&name=" . $twt_name . "&email=" . $twt_email . "&channel=twitter");
} else {
	header('Location: /member/login.php');
}