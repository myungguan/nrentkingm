<?php /* Template_ 2.2.8 2017/12/28 16:47:16 /home/rentking/dev/rentking/old/sites/tpls/rentkingm/rent/search.htm 000011996 */ ?>
<!DOCTYPE html>
<html lang="ko" class="search">
<head>
<?php $this->print_("head",$TPL_SCP,1);?>

</head>
<body>
<?php $this->print_("header",$TPL_SCP,1);?>

	<div id="searchWrap">
		<form action="/rent/search_list.php" id="searchForm">
			<input type="hidden" name="searchCount" value="0" />
			<input type="hidden" name="sdate" value="<?php echo $TPL_VAR["data"]["sdate"]?>" />
			<input type="hidden" name="edate" value="<?php echo $TPL_VAR["data"]["edate"]?>" />
			<input type="hidden" name="grade_idx" value="<?php echo $TPL_VAR["data"]["grade_idx"]?>" />
			<input type="hidden" name="pickupAddr" value="" />
			<input type="hidden" name="returnAddr" value="" />
			<input type="hidden" name="searchLatLng" value="" />
			<input type="hidden" name="retype" value="<?php echo $TPL_VAR["data"]["retype"]?>" />
			<input type="hidden" name="rentshopList" value="" />
			<input type="hidden" name="distance" value="-1" />
			<input type="hidden" name="distanceM" value="0" />

			<!--결과 내 검색-->
			<input type="hidden" name="ptype" value="" />			<!-- 대여방법 -->
			<input type="hidden" name="insu" value="" />			<!-- 자차보험 -->
			<input type="hidden" name="modelQuery" value="" />		<!-- 모델 검색어 -->
			<input type="hidden" name="model" value="" />			<!-- 모델 -->
			<input type="hidden" name="fuel" value="" />			<!-- 연료 -->
			<input type="hidden" name="option" value="" />			<!-- 차량옵션 -->
			<input type="hidden" name="outdate" value="" />			<!-- 출고일 -->
			<input type="hidden" name="colorQuery" value="" />		<!-- 차량색상 검색어 -->
			<input type="hidden" name="color" value="" />			<!-- 차량색상 -->
			<input type="hidden" name="companyQuery" value="" />	<!--제조사 검색어 -->
			<input type="hidden" name="company" value="" />			<!-- 제조사 -->
			<input type="hidden" name="nosmoke" value="N" />		<!-- 금연차 -->

			<!--리스트-->
			<input type="hidden" name="totalItem" value="20" />
			<input type="hidden" name="currentItem" value="0" />
			<input type="hidden" name="itemTo" value="19" />
		</form>
		<form action="/rent/reservation.php" id="reservationForm">
			<input type="hidden" name="sdate" value="<?php echo $TPL_VAR["data"]["sdate"]?>" />
			<input type="hidden" name="edate" value="<?php echo $TPL_VAR["data"]["edate"]?>" />
			<input type="hidden" name="vehicle_idx" value="" />
			<input type="hidden" name="insu" value="" />
			<input type="hidden" name="addr" value="" />
			<input type="hidden" name="raddr" value="" />
		</form>
		<div class="map-wrap">
			<div id="searchMap">
				<div class="searching">위치를 검색 중입니다.<br />잠시만 기다려주세요.</div>
			</div>
			<div class="map-info" style="background:url(<?php echo $TPL_VAR["global"]["imgserver"]?><?php echo $TPL_VAR["global"]["config"]['image.main.m']?>) center -77px;background-size:100% auto;">
				<div class="map-search-wrap">
					<form action="/rent/search.php" class="search" method="get" title="주소로 위치를 검색해보세요"  data-toggle="tooltip" data-placement="right">
						<div class="input-wrap">
							<input type="text" name="address" value="" placeholder="주소, 지역명, 지하철명으로 검색" autocomplete="off"  />
						</div>
						<button type="submit">검색</button>
					</form>
				</div>
				<div class="map-info-wrap">
				<div class="info">
					<span class="pickup">픽업위치</span>
					<span class="pickup-addr" id="pickupAddr">지도를 움직여 픽업 위치를 선택해 주세요.</span>
					<span class="return">반납위치</span>
					<span class="return-addr" id="returnAddr">지도를 움직여 반납 위치를 선택해 주세요.</span>
					<span class="search">검색위치</span>
					<span class="search-addr" id="searchAddr">지도를 움직여 검색 위치를 선택해 주세요.</span>
				</div>
			</div>
			</div>
			<div class="map-point-wrap">
				<span class="point"></span>
				<button class="pickup" title="픽업위치">픽업<br />위치</button>
				<button class="pickup-return" title="검색위치">검색<br />위치</button>
				<button class="return" title="반납위치">반납<br />위치</button>
				<button class="search" title="검색위치">위치<br />선택</button>
				<!--<a href="/partials/help/select_location.m.html" class="help openPopup" title="도움말" data-title="검색위치 설정" data-width="730">?</a>-->
			</div>
			<a href="#goList" class="go-list"></a>
		</div>

		<div class="list-search-wrap">
			<div class="title">
				<h2>상세검색</h2>
				<button class="reset-search">초기화</button>
				<button class="close-search">닫기</button>
			</div>
			<div class="list-search-scroll-wrap scrollWrap">
				<div class="list-search-scroll-wrap-inner scrollWrapInner">
					<div class="list-search-inner scrollWrapContent">
						<div class="box delavail">
							<h3>대여방법</h3>
							<button class="option" data-name="ptype" data-value="1">배달대여</button>
							<!--<button class="option" data-name="ptype" data-value="2">지점방문</button>-->
						</div>
						<!--
						<div class="box grade">
							<h3>차종</h3>
<?php if(is_array($TPL_R1=get_carattr( 2))&&!empty($TPL_R1)){foreach($TPL_R1 as $TPL_V1){?>
							<button class="option" data-name="grade" data-value="<?php echo $TPL_V1["idx"]?>"><?php echo $TPL_V1["sname"]?></button>
<?php }}?>
						</div>
						-->
						<div class="box insu">
							<h3>자차보험 고객부담금 <a href="/partials/help/insurance.html" class="help-tooltip openPopup" data-title="자차보험 고객부담금" title="도움말" data-width="490"></a></h3>
							<button class="option" data-name="insu" data-value="section0">미포함</button>
							<button class="option" data-name="insu" data-value="section1">0원</button>
							<button class="option" data-name="insu" data-value="section2">30만원 이하</button>
							<button class="option" data-name="insu" data-value="section3">30~50만원</button>
							<button class="option" data-name="insu" data-value="section4">50~100만원</button>
							<button class="option" data-name="insu" data-value="section5">100만원 이상</button>
						</div>
						<div class="box model">
							<h3>모델</h3>
							<div class="autocomplete">
								<input type="text" name="model" id="searchModel" data-name="modelQuery" />
								<button type="button">검색</button>
							</div>
<?php if(is_array($TPL_R1=$TPL_VAR["data"]["model"])&&!empty($TPL_R1)){foreach($TPL_R1 as $TPL_V1){?>
							<button class="option" data-name="model" data-value="<?php echo $TPL_V1["model"]?>"><?php echo $TPL_V1["model"]?></button>
<?php }}?>
						</div>
						<div class="box fuel">
							<h3>연료</h3>
<?php if(is_array($TPL_R1=$TPL_VAR["data"]["fuel"])&&!empty($TPL_R1)){foreach($TPL_R1 as $TPL_V1){?>
							<button class="option" data-name="fuel" data-value="<?php echo $TPL_V1["idx"]?>"><?php echo $TPL_V1["sname"]?></button>
<?php }}?>
						</div>
						<div class="box option">
							<h3>차량옵션</h3>
							<button class="option" data-name="option" data-value="vop1">네비게이션</button>
							<button class="option" data-name="option" data-value="vop2">블랙박스</button>
							<button class="option" data-name="option" data-value="vop3">후방센서</button>
							<button class="option" data-name="option" data-value="vop4">후방카메라</button>
							<button class="option" data-name="option" data-value="vop5">스마트키</button>
							<button class="option" data-name="option" data-value="vop6">블루투스</button>
							<button class="option" data-name="option" data-value="vop7">썬루프</button>
							<button class="option" data-name="option" data-value="vop8">하이패스</button>
						</div>
						<div class="box outdate">
							<h3>출고일</h3>
<?php if(is_array($TPL_R1=$TPL_VAR["data"]["year"])&&!empty($TPL_R1)){$TPL_I1=-1;foreach($TPL_R1 as $TPL_V1){$TPL_I1++;?>
							<button class="option" data-name="outdate" data-value="<?php if($TPL_I1=='0'){?>~<?php }?><?php echo $TPL_V1?>"><?php if($TPL_I1=='0'){?>~ <?php }?><?php echo $TPL_V1?></button>
<?php }}?>
						</div>
						<div class="box color">
							<h3>차량색상</h3>
							<div class="autocomplete">
								<input type="text" name="color" id="searchColor" data-name="colorQuery" />
								<button type="button">검색</button>
							</div>
<?php if(is_array($TPL_R1=$TPL_VAR["data"]["color"])&&!empty($TPL_R1)){foreach($TPL_R1 as $TPL_V1){?>
							<button class="option" data-name="color" data-value="<?php echo $TPL_V1["color"]?>"><?php echo $TPL_V1["color"]?></button>
<?php }}?>
						</div>
						<div class="box company">
							<h3>제조사</h3>
							<div class="autocomplete">
								<input type="text" name="company" id="searchCompany" data-name="companyQuery" />
								<button type="button">검색</button>
							</div>
<?php if(is_array($TPL_R1=$TPL_VAR["data"]["company"])&&!empty($TPL_R1)){foreach($TPL_R1 as $TPL_V1){?>
							<button class="option" data-name="company" data-value="<?php echo $TPL_V1["company_name"]?>"><?php echo $TPL_V1["company_name"]?></button>
<?php }}?>
						</div>
						<div class="box nosmoke">
							<h3>금연차</h3>
							<input type="radio" name="nosmoke" value="N" id="smoke_n" checked /> <label for="smoke_n">미선택</label>
							<input type="radio" name="nosmoke" value="Y" id="smoke_y" /> <label for="smoke_y">선택</label>
						</div>
						<div class="btn-wrap">
							<button class="btn btn-primary big list-search-submit">확인</button>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="list-content-wrap">
			<div class="util">
				<a href="#filter" class="filter">상세검색</a>
				<a href="#re-search" class="re-search"><span>위치 재선택</span></a>
			</div>
			<div class="list">
				<a href="#goMap" class="delivery-info">
					<span class="pickup-title">픽업위치</span>
					<span class="pickup-addr"></span>
					<span class="return-title">반납위치</span>
					<span class="return-addr"></span>
					<span class="search-title">검색위치</span>
					<span class="search-addr"></span>
				</a>
				<div class="distance-info">
					<span class="info"></span>
					<span class="title">지점방문:</span>
				</div>
				<div class="grade-info">
<?php if(is_array($TPL_R1=get_carattr( 2))&&!empty($TPL_R1)){foreach($TPL_R1 as $TPL_V1){?>
					<span class="btn-wrap">
						<button class="option" data-name="grade_idx" data-value="<?php echo $TPL_V1["idx"]?>"><?php echo $TPL_V1["sname"]?></button>
					</span>
<?php }}?>
				</div>
				<div class="list-scroll scrollWrap">
					<div class="list-scroll-inner scrollWrapInner">
						<div class="list-scroll-content scrollWrapContent">
							<div class="loading"></div>
							<div class="no-item">
								선택하신 시간, 위치에<br />대여가능한 차량이 없습니다.<br />
								이른 아침, 늦은 저녁, 일부 도서산간 지역에서는 렌트 가능한 차량이 없을 수 있습니다.<br />
								날짜, 시간, 위치를 재설정 후 검색하시면<br />좀더 많은 차량을 확인 하실 수 있습니다.
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php $this->print_("footer",$TPL_SCP,1);?>


	<script type="text/javascript" src="/js/rentking.search.min.js?20171127062239<?php if(!$TPL_VAR["global"]["production"]){?>_<?php echo time()?><?php }?>"></script>
	<script type="text/javascript" src="/js/rentking.m.search.min.js?20171127062239<?php if(!$TPL_VAR["global"]["production"]){?>_<?php echo time()?><?php }?>"></script>
</body>
</html>