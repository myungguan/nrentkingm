</head>
<body class="<?=$bodyClass?>">
<div id="container">
	<div class="topMain">
		<div class="topMenu">
			<h1><a href="/main.php" class="logo">Rentking <span class="logo-ci"></span><span class="logo-text1"></span><span class="logo-text2"></span></a></h1>
			<?=$_SESSION['admin_grade'] ?>
			<ul class="menu">
				<?if(in_array($_SESSION['admin_grade'], [9])) {?><li><a href="/mem.php?code=list">고객관리</a></li><?}?>
				<?if($_SESSION['member_grade']=='10')	{?><li><a href="/rent.php?code=mod">지점정보</a></li><?}?>
				<?if($_SESSION['admin_grade'] == 8)	{?><li><a href="/test/openplatform_modify.php">지점정보</a></li><?}?>

				<?if(in_array($_SESSION['admin_grade'], [9]) || $_SESSION['member_grade']=='10') {?><li><a href="/reserve.php?code=list">예약관리</a></li><?}?>

				<?if(in_array($_SESSION['admin_grade'], [9]) || $_SESSION['member_grade']=='10') {?><li><a href="/settlement.php?code=list">정산관리</a></li><?}?>

				<?if($_SESSION['member_grade']=='10')	{?><li><a href="/scar.php?code=list">차량관리</a></li><?}?>
				<?if(in_array($_SESSION['admin_grade'], [1,9])) {
					$link = '/car.php?code=plist';
					if(!in_array($_SESSION['admin_grade'], [9])) {
						$link = '/car.php?code=rentshoplist_s1';
					}
                    if(in_array($_SESSION['admin_grade'], [1])) {
                        $link = '/car.php?code=plist_image';
                    }
					?>
					<li><a href="<?=$link ?>">차량관리</a></li>
				<?}?>

				<?if(in_array($_SESSION['admin_grade'], [9])) {?><li><a href="/sho.php?code=main">사이트 관리</a></li><?}?>
				<?if(in_array($_SESSION['admin_grade'], [9])) {?><li><a href="/stat.php?code=sales">통계</a></li><?}?>
				<?if(in_array($_SESSION['admin_grade'], [9])) {?><li><a href="/monitoring.php?code=visit">로그</a></li><?}?>
			</ul>
			<ul class="util">
				<li>[<span style="font-weight:bold;"><?=$_SESSION['member_name'];?></span>님 로그인중]</li>
				<li><a href="/login/logout.php"><span class="topLogout">로그아웃</span></a></li>
			</ul>
		</div>