<!doctype html>
<html>
<head>
	<meta charset="UTF-8">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no" />
	<title>렌트킹</title>
	<link rel="apple-touch-icon" sizes="57x57" href="/imgs/icon.admin/apple-icon-57x57.png?20170810033411">
	<link rel="apple-touch-icon" sizes="60x60" href="/imgs/icon.admin/apple-icon-60x60.png?20170810033411">
	<link rel="apple-touch-icon" sizes="72x72" href="/imgs/icon.admin/apple-icon-72x72.png?20170810033411">
	<link rel="apple-touch-icon" sizes="76x76" href="/imgs/icon.admin/apple-icon-76x76.png?20170810033411">
	<link rel="apple-touch-icon" sizes="114x114" href="/imgs/icon.admin/apple-icon-114x114.png?20170810033411">
	<link rel="apple-touch-icon" sizes="120x120" href="/imgs/icon.admin/apple-icon-120x120.png?20170810033411">
	<link rel="apple-touch-icon" sizes="144x144" href="/imgs/icon.admin/apple-icon-144x144.png?20170810033411">
	<link rel="apple-touch-icon" sizes="152x152" href="/imgs/icon.admin/apple-icon-152x152.png?20170810033411">
	<link rel="apple-touch-icon" sizes="180x180" href="/imgs/icon.admin/apple-icon-180x180.png?20170810033411">
	<link rel="icon" type="image/png" sizes="192x192"  href="/imgs/icon.admin/android-icon-192x192.png?20170810033411">
	<link rel="icon" type="image/png" sizes="32x32" href="/imgs/icon.admin/favicon-32x32.png?20170810033411">
	<link rel="icon" type="image/png" sizes="96x96" href="/imgs/icon.admin/favicon-96x96.png?20170810033411">
	<link rel="icon" type="image/png" sizes="16x16" href="/imgs/icon.admin/favicon-16x16.png?20170810033411">
	<link rel="manifest" href="/imgs/icon.admin/manifest.json?20170810033411">
	<meta name="msapplication-TileColor" content="#000000">
	<meta name="msapplication-TileImage" content="/imgs/icon.admin/ms-icon-144x144.png?20170810033411">
	<meta name="theme-color" content="#000000">

	<link rel='stylesheet' href='//fonts.googleapis.com/earlyaccess/nanumgothic.css'>
	<link rel="stylesheet" href="/css/font-awesome.min.css">
	<link rel="stylesheet" href="/css/bootstrap.rentking.min.css?20171018025113<?=$config['production'] ? '' : time() ?>">
	<link rel="stylesheet" href="/css/rentking.admin.min.css?20171018025113<?=$config['production'] ? '' : time() ?>">

	<script type="text/javascript" src="/js/jquery-1.11.1.min.js"></script>
	<script type="text/javascript" src="/js/moment-with-locales.min.js"></script>
	<script type="text/javascript" src="/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="/js/bootstrap-datetimepicker.rentking.min.js?20171018025113<?=$config['production'] ? '' : time() ?>"></script>
	<script type="text/javascript" src="/js/amcharts/amcharts.js"></script>
	<script type="text/javascript" src="/js/amcharts/serial.js"></script>
	<script type="text/javascript" src="/js/amcharts/pie.js"></script>
	<script type="text/javascript" src="/js/amcharts/plugins/export/export.min.js"></script>
	<script type="text/javascript" src="/js/amcharts/plugins/tools/autoGuides/autoGuides.min.js"></script>
	<script type="text/javascript" src="/js/amcharts/plugins/tools/datePadding/datePadding.min.js"></script>
	<script type="text/javascript" src="/js/amcharts/lang/ko.js"></script>
	<script type="text/javascript" src="/js/amcharts/plugins/export/lang/ko.js"></script>
	<script type="text/javascript" src="/js/rentking.min.js?20171018025113<?=$config['production'] ? '' : time() ?>"></script>
	<script type="text/javascript" src="/js/rentking.admin.min.js?20171018025113<?=$config['production'] ? '' : time() ?>"></script>
</head>
<body class="login">
	<form method="post" id="loginform" name='loginform' action="/login/loginpro.php">
		<input type="hidden" name="url" value="<?=$_REQUEST['url']?>" />
		<input type="text" name="id" class="id" placeholder="아이디">
		<input type="password" name="passwd" class="pw" placeholder="비밀번호">
		<button type="submit">로그인</button>

		<ul class="description">
			<li>승인된 렌트킹 관계자만 로그인이 가능합니다.<br />
				승인되지 않은 방법으로 접속시에는 손해배상 등 법률적 책임소재가 따를 수 있습니다.
			</li>
		</ul>
		<div class="logo"><span class="logo-ci"></span><span class="logo-text1"></span><span class="logo-text2"></span></div>
	</form>

	<script type="text/javascript">
		$(function() {
			$(document)
				.on('submit', '#loginform', function(e) {
					var $form = $(this);
					var $id = $form.find('.id');
					var $pw = $form.find('.pw');
					if ($.trim($id.val().length) < 1) {
						alert('아이디를 입력하세요');
						$id.focus();
						return false;
					}
					if ($.trim($pw.val().length) < 1) {
						alert('비밀번호를 입력하세요');
						$pw.focus();
						return false;
					}

					return true;
				});

			$('#loginform .id').focus();
		});
	</script>
</body>
</html>