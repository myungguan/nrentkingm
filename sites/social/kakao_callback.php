<?
include $_SERVER['DOCUMENT_ROOT']."/old/inc/base.php";
include $config['incPath']."/connect.php";
include $config['incPath']."/session.php";
include $config['incPath']."/config_site.php";

$code = $_REQUEST['code'];
$state = $_REQUEST['state'];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://kauth.kakao.com/oauth/token');
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_POST, TRUE);
$post_param = 'grant_type=authorization_code';
$post_param .= '&client_id=a08e5758805122cf8ad8c4afc1d395b3';
$post_param .= '&redirect_uri=http://' . $_SERVER['HTTP_HOST'] . '/social/kakao_callback.php';
$post_param .= '&code=' . $code;
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_param);
$response = curl_exec($ch);
curl_close($ch);
$access_token = json_decode($response, TRUE);


$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://kapi.kakao.com/v1/user/me');
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $access_token['access_token']));
$response = curl_exec($ch);
curl_close($ch);
$re = json_decode($response, TRUE);


header('Location: '."/member/login_social.php?id=" . $re['id'] . "&name=" . $re['properties']['nickname'] . "&email=" . $re['kaccount_email'] . "&channel=kakao");
