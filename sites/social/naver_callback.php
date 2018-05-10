<?
$client_id = "qcRzr5vJ_po17OTzWL1f";
$client_secret = "Gv2TwZ0DI9";
$code = $_GET["code"];;
$state = $_GET["state"];;
$redirectURI = urlencode("http://" . $_SERVER['HTTP_HOST'] . "/social/naver_callback.php");
$url = "https://nid.naver.com/oauth2.0/token?grant_type=authorization_code&client_id=" . $client_id . "&client_secret=" . $client_secret . "&redirect_uri=" . $redirectURI . "&code=" . $code . "&state=" . $state;
$is_post = false;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_POST, $is_post);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$headers = array();
$response = curl_exec($ch);
$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
//echo "status_code:".$status_code."<br>";
curl_close($ch);
if ($status_code == 200) {
	$response;
	$responseDecoded = json_decode($response, true);

} else {
	header('Location: '."/member/join.php?mtype=p");
	exit;
}


$token = $responseDecoded[access_token];
$header = "Bearer " . $token; // Bearer 다음에 공백 추가
$url = "https://openapi.naver.com/v1/nid/me";
$is_post = false;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_POST, $is_post);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$headers = array();
$headers[] = "Authorization: " . $header;
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
$response = curl_exec($ch);
$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
//echo "status_code:".$status_code."<br>";
curl_close($ch);
if ($status_code == 200) {
	//echo $response;

	$rd = json_decode($response, true);

	header('Location: '."/member/login_social.php?id=" . $rd[response][id] . "&name=" . $rd[response][name] . "&email=" . $rd[response][email] . "&channel=naver");
} else {
	header('Location: '."/member/login.php");
}
