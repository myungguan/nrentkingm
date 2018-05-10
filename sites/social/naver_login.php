<?php
// 네이버 로그인 접근토큰 요청 예제
$client_id = "qcRzr5vJ_po17OTzWL1f";
$redirectURI = urlencode("http://" . $_SERVER['HTTP_HOST'] . "/social/naver_callback.php");
$state = "RAMDOM_STATE";
$apiURL = "https://nid.naver.com/oauth2.0/authorize?response_type=code&client_id=" . $client_id . "&redirect_uri=" . $redirectURI . "&state=" . $state;

header('Location: '."$apiURL");
