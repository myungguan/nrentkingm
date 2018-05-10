<?php /* Template_ 2.2.8 2018/02/08 09:49:37 /home/rentking/dev/rentking/old/sites/tpls/rentkingw/rent/search_list.htm 000008469 */ ?>
<div class="page">
	<ul>
<?php if(is_array($TPL_R1=$TPL_VAR["data"]["list"])&&!empty($TPL_R1)){foreach($TPL_R1 as $TPL_V1){?>
		<li class="item retype<?php echo $TPL_VAR["data"]["retype"]?>" data-rentshop="<?php echo $TPL_V1["rentshop_idx"]?>">
			<a href="#<?php echo $TPL_V1["idx"]?>" class="item-link">
				<div class="car"><span class="name"><?php echo $TPL_V1["modelname"]?></span> <span class="fuel">(<?php echo $TPL_V1["fuel_sname"]?>)</span></div>
				<div class="image" style="background-image:url(<?php echo $TPL_VAR["global"]["imgserver"]?><?php if($TPL_V1["car_image_path"]){?><?php echo $TPL_V1["car_image_path"]?><?php }else{?><?php echo $TPL_V1["default_image_path"]?><?php }?>)">
					<div class="label-area">
<?php if($TPL_V1["delavail"]){?>
						<span class="delavail">배반차가능 (<?php if($TPL_V1["delcharge"]> 0){?>+<?php echo number_format($TPL_V1["delcharge"])?>원<?php }else{?>무료<?php }?>)</span>
<?php }else{?>
						<span class="nodelivery">지점방문</span>
<?php }?>
<?php if($TPL_V1["passengers"]> 10){?> <span class="type1">1종면허</span><?php }?>
<?php if($TPL_V1["promotion"]){?> <span class="discount"><?php echo promotion($TPL_V1["promotion"][ 0])?></span><?php }?>
						<!--
<?php if($TPL_V1["count_car"]> 1){?><span class="count-car"><?php echo $TPL_V1["count_car"]?>대</span><?php }?>
						-->
					</div>
<?php if($TPL_VAR["data"]["retype"]== 2){?>
					<div class="<?php if($TPL_V1["outyear"]==date('Y')){?>outdate-new<?php }else{?>outdate<?php }?>"><?php if($TPL_V1["outyear"]==date('Y')){?>NEW<br><?php }?><?php echo $TPL_V1["outyear"]?></div>
<?php }?>
				</div>
				<div class="info">
					<div class="shop"><?php echo $TPL_V1["affiliate"]?> (<?php echo number_format($TPL_V1["distance"]/ 1000, 1)?>&#13214;)</div>
					<div class="insu">
						<div class="age">운전자나이 만 <?php echo $TPL_V1["rentage"]?>세 이상</div>
						<div class="liscense">면허취득 만 <?php echo $TPL_V1["license_limit"]?>년 이상</div>
					</div>
<?php if($TPL_VAR["data"]["retype"]=='2'){?><div class="deposit">보증금: <span class="numberAnimation" data-target="<?php echo $TPL_V1["precharge"]?>">0</span>원</div><?php }?>
					<div class="price"><?php if($TPL_VAR["data"]["retype"]=='1'){?><span class="numberAnimation" data-target="<?php echo $TPL_V1["total_charge"]?>">0</span>원<?php }else{?><span class="numberAnimation" data-target="<?php echo $TPL_V1["moncharge"]?>">0</span>원<span class="month">/월</span><?php }?></div>
				</div>
				<div class="detail">
					<div class="insurance">
						<div class="title">보험안내</div>
						<ul>
							<li>대인 <span class="value"><?php echo $TPL_V1["insu_per"]?></span></li>
							<li>대물 <span class="value"><?php echo number_format($TPL_V1["insu_goods"])?>원</span></li>
							<li>자손 <span class="value"><?php echo number_format($TPL_V1["insu_self"])?>원</span></li>
						</ul>
					</div>

					<div class="option">
						<div class="title">차량옵션<span class="update">출고일: <?php echo $TPL_V1["outdate"]?></span></div>
						<div class="color">색상: <?php echo $TPL_V1["color"]?></div>
						<div class="rundistance">주행거리: <?php echo number_format($TPL_V1["rundistan"])?>km</div>
						<div class="etc"><?php echo $TPL_V1["opt"]?></div>
					</div>
				</div>
			</a>
			<ul class="button-area">
<?php if($TPL_VAR["data"]["retype"]== 1){?>
<?php if($TPL_V1["pricetype"]== 1){?>
<?php if($TPL_V1["price_insu0_check"]=='Y'){?>
						<li>
							<a href="#<?php echo $TPL_V1["idx"]?>" class="reservation">
								<div class="title">바로 예약하기</div>
								<div class="description">
									<span class="con">자차 미포함</span>
									<span class="price"><span class="numberAnimation" data-target="<?php echo $TPL_V1["charge"]?>">0</span>원</span>
								</div>
							</a>
						</li>
<?php }?>
<?php if($TPL_V1["price_insu1_check"]=='Y'){?>
						<li>
							<a href="#<?php echo $TPL_V1["idx"]?>" class="reservation" data-insu="1">
								<div class="title">바로 예약하기</div>
								<div class="description">
									<span class="con">자차포함(고객부담금: <?php echo number_format($TPL_V1["price_insu1_exem"])?>원/건)<?php if($TPL_V1["price_insu1_limit"]> 0){?><br />자차보상한도: <?php echo number_format($TPL_V1["price_insu1_limit"])?>원/건<?php }?></span>
									<span class="price"><span class="numberAnimation" data-target="<?php echo $TPL_V1["charge"]+$TPL_V1["price_insu1"]?>">0</span>원</span>
								</div>
							</a>
						</li>
<?php }?>
<?php if($TPL_V1["price_insu2_check"]=='Y'){?>
						<li>
							<a href="#<?php echo $TPL_V1["idx"]?>" class="reservation" data-insu="2">
								<div class="title">바로 예약하기</div>
								<div class="description">
									<span class="con">자차포함(고객부담금: <?php echo number_format($TPL_V1["price_insu2_exem"])?>원/건)<?php if($TPL_V1["price_insu2_limit"]> 0){?><br />자차보상한도: <?php echo number_format($TPL_V1["price_insu2_limit"])?>원/건<?php }?></span>
									<span class="price"><span class="numberAnimation" data-target="<?php echo $TPL_V1["charge"]+$TPL_V1["price_insu2"]?>">0</span>원</span>
								</div>
							</a>
						</li>
<?php }?>
<?php if($TPL_V1["price_insu3_check"]=='Y'){?>
						<li>
							<a href="#<?php echo $TPL_V1["idx"]?>" class="reservation" data-insu="3">
								<div class="title">바로 예약하기</div>
								<div class="description">
									<span class="con">자차포함(고객부담금: <?php echo number_format($TPL_V1["price_insu3_exem"])?>원/건)<?php if($TPL_V1["price_insu3_limit"]> 0){?><br />자차보상한도: <?php echo number_format($TPL_V1["price_insu3_limit"])?>원/건<?php }?></span>
									<span class="price"><span class="numberAnimation" data-target="<?php echo $TPL_V1["charge"]+$TPL_V1["price_insu3"]?>">0</span>원</span>
								</div>
							</a>
						</li>
<?php }?>
<?php }else{?>
					<li>
						<a href="#<?php echo $TPL_V1["idx"]?>" class="reservation">
							<div class="title">바로 예약하기</div>
							<div class="description">
								<span class="con">자차포함(고객부담금: <?php echo number_format($TPL_V1["price_longterm_insu_exem"])?>원/건)<?php if($TPL_V1["price_longterm_insu_limit"]> 0){?><br />자차보상한도: <?php echo number_format($TPL_V1["price_longterm_insu_limit"])?>원/건<?php }?></span>
								<span class="price"><span class="numberAnimation" data-target="<?php echo $TPL_V1["charge"]?>">0</span>원</span>
							</div>
						</a>
					</li>
<?php }?>
<?php }else{?>
					<li>
						<a href="#<?php echo $TPL_V1["idx"]?>" class="reservation">
							<div class="title">바로 예약하기</div>
							<div class="description">
								<span class="con">자차포함(고객부담금: <?php echo number_format($TPL_V1["price_longterm_insu_exem"])?>원/건)<?php if($TPL_V1["price_longterm_insu_limit"]> 0){?><br />자차보상한도: <?php echo number_format($TPL_V1["price_longterm_insu_limit"])?>원/건<?php }?></span>
							</div>
<?php if($TPL_V1["distance_limit"]> 0){?>
							<div class="description">
								<span class="con">주행거리제한: <?php echo number_format($TPL_V1["distance_limit"])?>&#13214;/월 (추가: <?php echo number_format($TPL_V1["distance_additional_price"])?>원/&#13214;)</span>
							</div>
<?php }?>
							<div class="description">
								<span class="con">월대여료</span>
								<span class="price"><span class="numberAnimation" data-target="<?php echo $TPL_V1["moncharge"]?>">0</span>원 X <?php echo $TPL_VAR["data"]["months"]?>회</span>
							</div>
							<div class="description">
								<span class="con">마지막 월 대여료</span>
								<span class="price"><span class="numberAnimation" data-target="<?php echo $TPL_V1["leftcharge"]?>">0</span>원</span>
							</div>
							<div class="description">
								<span class="con">전체 대여료</span>
								<span class="price"><span class="numberAnimation" data-target="<?php echo $TPL_V1["total_charge"]?>">0</span>원</span>
							</div>
						</a>
					</li>
<?php }?>
			</ul>
		</li>
<?php }}?>
	</ul>

	<script type="text/javascript">
		var totalItem = <?php echo $TPL_VAR["data"]["totalItem"]?>;
		$('#searchForm').find('input[name="totalItem"]').val(totalItem);
	</script>
</div>