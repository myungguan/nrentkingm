<?php /* Template_ 2.2.8 2018/03/29 11:46:35 /home/rentking/dev/rentking/old/sites/tpls/rentkingw/_common/head.htm 000004626 */ ?>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="description" content="<?php echo $TPL_VAR["global"]["description"]?>" />
<meta property="og:url" content="https://www.rentking.co.kr" />
<meta property="og:title" content="렌트카는 렌트킹" />
<meta property="og:image" content="https://www.rentking.co.kr/imgs/icon/og.jpg" />
<meta property="og:description" content="<?php echo $TPL_VAR["global"]["description"]?>" />
<meta property="fb:app_id" content="397761010564739" />
<title><?php if($TPL_VAR["page"]){?><?php echo $TPL_VAR["page"]["title"]?><?php }else{?>렌트킹<?php }?></title>

<link rel="apple-touch-icon" sizes="57x57" href="/imgs/icon/apple-icon-57x57.png">
<link rel="apple-touch-icon" sizes="60x60" href="/imgs/icon/apple-icon-60x60.png">
<link rel="apple-touch-icon" sizes="72x72" href="/imgs/icon/apple-icon-72x72.png">
<link rel="apple-touch-icon" sizes="76x76" href="/imgs/icon/apple-icon-76x76.png">
<link rel="apple-touch-icon" sizes="114x114" href="/imgs/icon/apple-icon-114x114.png">
<link rel="apple-touch-icon" sizes="120x120" href="/imgs/icon/apple-icon-120x120.png">
<link rel="apple-touch-icon" sizes="144x144" href="/imgs/icon/apple-icon-144x144.png">
<link rel="apple-touch-icon" sizes="152x152" href="/imgs/icon/apple-icon-152x152.png">
<link rel="apple-touch-icon" sizes="180x180" href="/imgs/icon/apple-icon-180x180.png">
<link rel="icon" type="image/png" sizes="192x192"  href="/imgs/icon/android-icon-192x192.png">
<link rel="icon" type="image/png" sizes="32x32" href="/imgs/icon/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="96x96" href="/imgs/icon/favicon-96x96.png">
<link rel="icon" type="image/png" sizes="16x16" href="/imgs/icon/favicon-16x16.png">
<link rel="manifest" href="/imgs/icon/manifest.json">
<meta name="msapplication-TileColor" content="#000000">
<meta name="msapplication-TileImage" content="/imgs/icon/ms-icon-144x144.png">
<meta name="theme-color" content="#000000">

<link rel="stylesheet" href="//fonts.googleapis.com/earlyaccess/nanumgothic.css">
<link rel="stylesheet" href="/css/font-awesome.min.css">
<link rel="stylesheet" href="/css/bootstrap.rentking.min.css?20180329<?php if(!$TPL_VAR["global"]["production"]){?>_<?php echo time()?><?php }?>">
<link rel="stylesheet" href="/css/bootstrap-datetimepicker.min.css">
<link rel="stylesheet" href="/css/swiper.min.css">
<link rel="stylesheet" href="/css/shepherd/shepherd-theme-arrows.css">
<link rel="stylesheet" href="/css/rentking.w.min.css?28100302<?php if(!$TPL_VAR["global"]["production"]){?>_<?php echo time()?><?php }?>">

<!--[if lt IE 9]>
<script src="/js//html5shiv.min.js"></script>
<![endif]-->
<script type="text/javascript" src="/js/jquery-1.11.1.min.js?20171113015549"></script>
<script type="text/javascript" src="/js/moment-with-locales.min.js?20171113015549"></script>
<script type="text/javascript" src="/js/bootstrap.min.js?20171113015549"></script>
<script type="text/javascript" src="/js/bootstrap-datetimepicker.rentking.min.js?20171113015549<?php if(!$TPL_VAR["global"]["production"]){?>_<?php echo time()?><?php }?>"></script>
<script type="text/javascript" src="/js/swiper.jquery.min.js?20180323"></script>
<script type="text/javascript" src="/js/async.min.js?20171113015549"></script>
<script type="text/javascript" src="/js/tether.min.js?20171113015549"></script>
<script type="text/javascript" src="/js/shepherd.min.js?20171113015549"></script>
<script type="text/javascript" src="//apis.daum.net/maps/maps3.js?apikey=fefdc7502e819521ae797c44ed5f3e53&libraries=services"></script>
<!--<script type="text/javascript" src="http://map.daum.net/iptargeting/point"></script>-->

<script type="text/javascript" src="/js/rentking.min.js?20171127062239<?php if(!$TPL_VAR["global"]["production"]){?>_<?php echo time()?><?php }?>"></script>
<script type="text/javascript" src="/js/rentking.w.min.js?20180205<?php if(!$TPL_VAR["global"]["production"]){?>_<?php echo time()?><?php }?>"></script>
<? include_once($_SERVER['DOCUMENT_ROOT']."/old/inc/log/rentking.php"); ?>

<?
	if($TPL_VAR['global']['production']) {
		include_once($_SERVER['DOCUMENT_ROOT']."/old/inc/log/pixel.php");
		include_once($_SERVER['DOCUMENT_ROOT']."/old/inc/plugins/acecounter_www.php");
		include_once($_SERVER['DOCUMENT_ROOT']."/old/inc/plugins/google_gdn.php");
		include_once($_SERVER['DOCUMENT_ROOT']."/old/inc/plugins/hotjar.php");
		include_once($_SERVER['DOCUMENT_ROOT']."/old/inc/plugins/progressmedia_cts.php");
	}
?>