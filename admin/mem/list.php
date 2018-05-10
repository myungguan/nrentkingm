<?
/**
 * 어드민 > 고객관리 > 회원
 * admin.rentking.co.kr/mem.php?code=list
 * 회원 리스트 페이지
 */
$pid = $_REQUEST['pid'];
$sdate = $_REQUEST['sdate'];
$edate = $_REQUEST['edate'];
$numper = $_REQUEST['numper'] ? $_REQUEST['numper'] : 25;

$page_per_block = 10;
$page = $_GET['page'] ? $_GET['page'] : 1;

/* 정렬 기본 */
if (!$sortcol)
	$sortcol = "signdate";

if (!$sortby)
	$sortby = "desc";
/* 정렬 기본 */

//HTTP QUERY STRING
$keyword = trim($keyword);
$key = $_REQUEST['key'];
$qArr['numper'] = $numper;
$qArr['page'] = $page;
$qArr['code'] = $code;
$qArr['sortcol'] = $sortcol;
$qArr['sortby'] = $sortby;
$qArr['sdate'] = $sdate;
$qArr['edate'] = $edate;
$qArr['key'] = $key;
$qArr['keyword'] = $keyword;
$qArr['pid'] = $pid;

$q = "SELECT '[FIELD]' FROM member WHERE memgrade='100' AND id!='outmember'";
if ($keyword) {
	$q = $q . " AND {$key} like '%".mysql_escape_string($keyword)."%'";
}
if ($sdate) {
	$q = $q . " AND signdate>='{$sdate} 00:00:00'";
}
if ($edate) {
	$q = $q . " AND signdate<='{$edate} 23:59:59'";
}
if ($pid) {
	$q = $q . " AND pid='{$pid}'";
}
//카운터쿼리
$sql = str_replace("'[FIELD]'", "COUNT(idx)", $q);
$r = mysql_query($sql);
$total_record = mysql_result($r, 0, 0);
mysql_free_result($r);

if ($total_record == 0) {
	$first = 0;
	$last = 0;
} else {
	$first = $numper * ($page - 1);
	$last = $numper * $page;
}

//데이터쿼리
$_sql = str_replace("'[FIELD]'", "*", $q);

$_tArr = explode(",", $sortcol);
if (is_array($_tArr) && count($_tArr)) {
	foreach ($_tArr as $v) {
		$orderbyArr[] = "{$v} {$sortby}";
	}
	$orderby = implode(", ", $orderbyArr);
}

$sql_order = " ORDER BY {$orderby}";
$sql_limit = " LIMIT {$first}, {$numper}";
$sql = $_sql . $sql_order;// . $sql_limit;

$r = mysql_query($sql);
while ($row = mysql_fetch_array($r)) {
	$data[] = $row;
}

//엑셀쿼리
$sql_excel = $_sql . $sql_order;
$_SESSION['sql_excel'] = $sql_excel;
?>
<div style="margin-top:10px;">
	<form id="search" name="search" method="get">
		<input type="hidden" name="code" value="<?=$code?>" />
		<table class="detailTable2">
			<tbody>
				<tr>
					<th>가입처</th>
					<td><select name='pid'>
					<option value=''>가입처전체</option>
					<option value='0' <? if($pid=='0') { echo "selected";	}?>>관리자가입</option>
					</select></td>
				</tr>
				<tr>
					<th>검색조건</th>
					<td>
						<select class="uch" name="key">
						<option value="name" <? if($key=='name') { echo "selected";	}?>>성명</option>
						<option value="id" <? if($key=='id') { echo "selected";	}?>>아이디</option>
                            <option value="cp" <? if($key=='cp') { echo "selected";	}?>>휴대폰</option>
                            <option value="birth" <? if($key=='birth') { echo "selected";	}?>>생년월일(YYYY-MM-DD 형식)</option>
					</select> <input type="text" maxlength="50" name="keyword" size="50" value="<?=$keyword;?>" class="basic_input" onkeypress="javascript:if(event.keyCode == 13) { form.submit() }">
					</td>
				</tr>
				<tr>
					<th>가입일</th>
					<td>
						<input type='text' name='sdate' id='sdates' size='10' value='<?=$sdate;?>' class="datePicker" data-parent="#container">
						~
						<input type='text' name='edate' id='edates' size='10' value='<?=$edate;?>' class="datePicker" data-parent="#container">
					</td>
				</tr>
				<tr>
					<th>노출갯수</th>
					<td>
					<select class="uch" name='numper'>
					<?php
					$ar_list_config = array('25', '50', '70', '100', '200', '300', '400', '500');
					foreach ($ar_list_config as $opt) {
					?>
						<option value='<?=$opt?>' <? if ($numper == $opt) { echo "selected"; }?>><?=$opt?></option>
					<?php
					}
					?>
					</select>
					</td>
				</tr>
			</tbody>
		</table>
		<div class="btn_wrap btn_center btn_bottom">
			<span class="greenBtn btn_submit" data-form="#search"><a href="javascript:">검색하기</a></span>
		</div>
		<input type="submit" style="display:none;">
	</form>
</div>
<div style='margin-top:20px;'>
	<div class="h3_wrap">
		<h3>* 검색된 회원은 총 <?=number_format($total_record);?>명 입니다.</h3>
		<div class="btn_wrap btn_right">
<!--			<span class="greenBtn"><a href="#none" onclick="location.href='./excel/excel_down.php?act=member';">EXCEL</a></span> 18.01.02 smkang: 개인정보보호관련 처리 -->
<!--			<span class="greenBtn"><a href="#none" onclick="sms_reg();">문자발송</a></span>-->
<!--			<span class="greenBtn"><a href="#none" onclick="PrintElem('printdiv');">프린트</a></span>-->
		</div>
	</div>
	<div  id='printdiv'>
		<table class="listTableColor">
			<thead>
			<tr>
				<th>회원번호</th>
				<th>아이디</th>
				<th>이름</th>
				<th>계정타입</th>
				<th>연락처</th>
				<th>최종렌트차량</th>
				<th>가입일</th>
			</tr>
			</thead>
			<tbody>
				<?
				for($is=0;$is<count($data);$is++){
					$row = $data[$is];
					$onclick = "MM_openBrWindow('/popup/memview.php?idx={$row['idx']}','member{$row['idx']}','scrollbars=yes,width=1150,height=700,top=0,left=0');return false;";
				?>
				<tr onclick="">
					<td><?=$row['idx'];?></td>
					<td style="text-align:left;padding:0 5px;"><a href="#<?=$row['idx']?>" onclick="<?=$onclick?>"><?=$row['id'];?></a></td>
					<td><a href="#<?=$row['idx']?>" onclick="<?=$onclick?>"><?=$row['name'];?></a></td>
					<td>
						<?
						switch($row['social_type']) {
							case 'kakao':
								echo '카카오';
								break;
							case 'fb':
								echo '페이스북';
								break;
							case 'naver':
								echo '네이버';
								break;
						}
						?>
					</td>
					<td><?=phone_number_format($row['cp'])?></td>
					<td>
					<?
					$qs = "SELECT vs.name modelname
						FROM payments m
							LEFT JOIN reservation mt ON m.reservation_idx = mt.idx
							LEFT JOIN vehicles v ON mt.vehicle_idx = v.idx
							LEFT JOIN vehicle_models vs ON v.model_idx = vs.idx
						WHERE m.member_idx='{$row['idx']}' ORDER BY m.dt_create DESC limit 0,1";
					$rs = mysql_query($qs);
					$rows = mysql_fetch_array($rs);

					if($rows)	{
						echo $rows['modelname'];
					}
					?>
					</td>
										<!--161115 input박스 추가-->
					<td><?=$row['signdate'];?></td>
									<!--//161115 input박스 추가-->
				</tr>
				<?}?>
			</tbody>
		</table>
					<!-- paging     161123추가  -->
		<div class="paging">
			<?=paging_admin($page, $total_record, $numper, $page_per_block, $qArr);?>
		</div>
	</div>
</div>