<?
/**
 * admin.rentking.co.kr/main.php
 * 관리자페이지 > 메인
 * 메인화면
 */
include $_SERVER['DOCUMENT_ROOT']."/old/inc/base.php";
include $config['incPath']."/connect.php";
include $config['incPath']."/session.php";
include $config['incPath']."/config.php";

$mode = $_REQUEST['mode'];
$realtimeLimit = 10;
$minSec = 10;
$reloadTime = 1000;
$maxReloadTime = 5000;

if($mode == 'ajax') {
	$command = $_REQUEST['command'];

	$data = [];
	if($command == 'realtime') {
		$count = $_REQUEST['count'];
		$query = "SELECT NOW() now, FROM_UNIXTIME(FLOOR(UNIX_TIMESTAMP(NOW())/$minSec)*$minSec) 10s";
		$data['time'] = mysql_fetch_assoc(mysql_query($query));

		$set = microtime(true);
		$query = "SELECT * FROM record";
		$record = [];
		$r = mysql_query($query);
		while($row = mysql_fetch_assoc($r)) {
			$record[$row['type']] = $row['value'];
		}
		$data['record'] = $record;
		$data['record_extime'] = floor((microtime(true) - $set) * 1000);

		$set = microtime(true);
		$query = "SELECT
				COUNT(*) session
			FROM (
				SELECT DISTINCT session_id
				FROM log_pageview
				WHERE dt >= NOW() - INTERVAL $realtimeLimit MINUTE
				) t";
		$data['count1'] = mysql_fetch_row(mysql_query($query))[0];
		$data['count1_extime'] = floor((microtime(true) - $set) * 1000);

		$set = microtime(true);
		$query = "SELECT
				COUNT(*) session
			FROM (
				SELECT DISTINCT session_id
				FROM log_search
				WHERE dt >= NOW() - INTERVAL $realtimeLimit MINUTE
				 ) t";
		$data['count2'] = mysql_fetch_row(mysql_query($query))[0];
		$data['count2_extime'] = floor((microtime(true) - $set) * 1000);

		$set = microtime(true);
		$query = "SELECT
				COUNT(*) session
			FROM (
				SELECT DISTINCT session_id
				FROM log_pageview
				WHERE
					dt >= NOW() - INTERVAL $realtimeLimit MINUTE
					AND url LIKE '/rent/payment%'
		 ) t";
		$data['count3'] = mysql_fetch_row(mysql_query($query))[0];
		$data['count3_extime'] = floor((microtime(true) - $set) * 1000);

		$set = microtime(true);
		$query = "SELECT
				SUM(CASE WHEN dan < 4 THEN 1 ELSE 0 END) reservation,
				SUM(CASE WHEN dan = 5 THEN 1 ELSE 0 END) cancel
			FROM payments
			WHERE
				dt_create >= CURDATE()";
		$data['count4'] = mysql_fetch_assoc(mysql_query($query));
		if($data['count4']['reservation'] >= $record['reservation_day']) {
			mysql_query("UPDATE record SET value={$data['count4']['reservation']} WHERE type = 'reservation_day'");
		}
		$query = "SELECT
				COUNT(*) cancelr
			FROM payments
			WHERE
				dan = 4;";
		$data['count4'] = array_merge($data['count4'], mysql_fetch_assoc(mysql_query($query)));
		$data['count4_extime'] = floor((microtime(true) - $set) * 1000);

		$set = microtime(true);
		$query = "SELECT SUM(account - isouts) sales
			FROM payment_accounts
			WHERE
				dt_create >= CURDATE()
				AND tbtype = 'I'
				AND account > isouts";
		$data['count5'] = mysql_fetch_row(mysql_query($query))[0];
		if($data['count5'] >= $record['sales_day']) {
			mysql_query("UPDATE record SET value={$data['count5']} WHERE type = 'sales_day'");
		}
		$data['count5_extime'] = floor((microtime(true) - $set) * 1000);

		$set = microtime(true);
		$query = "SELECT
				COUNT(*) session
			FROM (
				SELECT DISTINCT session_id
				FROM log_pageview
				WHERE dt >= CURDATE()
				) t";
		$data['count6'] = mysql_fetch_row(mysql_query($query))[0];
		if($data['count6'] >= $record['visit_day']) {
			mysql_query("UPDATE record SET value={$data['count6']} WHERE type = 'visit_day'");
		}
		$data['count6_extime'] = floor((microtime(true) - $set) * 1000);

		$set = microtime(true);
		$query = "SELECT
				COUNT(*) session
			FROM (
				SELECT DISTINCT session_id
				FROM log_search
				WHERE dt >= CURDATE()
				 ) t";
		$data['count7'] = mysql_fetch_row(mysql_query($query))[0];
		if($data['count7'] >= $record['search_day']) {
			mysql_query("UPDATE record SET value={$data['count7']} WHERE type = 'search_day'");
		}
		$data['count7_extime'] = floor((microtime(true) - $set) * 1000);

		$set = microtime(true);
		$query = "SELECT
				COUNT(*) session
			FROM (
				SELECT DISTINCT session_id
				FROM log_pageview
				WHERE
					dt >= CURDATE()
					AND url LIKE '/rent/payment%'
		 ) t";
		$data['count8'] = mysql_fetch_row(mysql_query($query))[0];
		if($data['count8'] >= $record['visit_reservation_page_day']) {
			mysql_query("UPDATE record SET value={$data['count8']} WHERE type = 'visit_reservation_page_day'");
		}
		$data['count8_extime'] = floor((microtime(true) - $set) * 1000);

		$set = microtime(true);
		$query = "SELECT COUNT(*) member
			FROM member
			WHERE
				signdate >= CURDATE()
				AND memgrade = 100";
		$data['count9'] = mysql_fetch_row(mysql_query($query))[0];
		if($data['count9'] >= $record['join_day']) {
			mysql_query("UPDATE record SET value={$data['count9']} WHERE type = 'join_day'");
		}
		$data['count9_extime'] = floor((microtime(true) - $set) * 1000);

		$set = microtime(true);
		$query = "SELECT
				FROM_UNIXTIME(FLOOR(UNIX_TIMESTAMP(dt)/$minSec)*$minSec) time,
				COUNT(*) data
			FROM log_pageview
			WHERE dt >= NOW() - INTERVAL $realtimeLimit MINUTE
			GROUP BY time";
		$r = mysql_query($query);
		$chart1 = [];
		while($row = mysql_fetch_assoc($r)) {
			$chart1[] = $row;
		}
		$data['chart1'] = $chart1;
		$data['chart1_extime'] = floor((microtime(true) - $set) * 1000);

		$set = microtime(true);
		$query = "SELECT
				FROM_UNIXTIME(FLOOR(UNIX_TIMESTAMP(dt)/$minSec)*$minSec) time,
				COUNT(*) data
			FROM log_search
			WHERE dt >= NOW() - INTERVAL $realtimeLimit MINUTE
			GROUP BY time";
		$r = mysql_query($query);
		$chart2 = [];
		while($row = mysql_fetch_assoc($r)) {
			$chart2[] = $row;
		}
		$data['chart2'] = $chart2;
		$data['chart2_extime'] = floor((microtime(true) - $set) * 1000);

		$set = microtime(true);
		$query = "SELECT
				FROM_UNIXTIME(FLOOR(UNIX_TIMESTAMP(dt)/$minSec)*$minSec) time,
				COUNT(*) data
			FROM log_pageview
			WHERE dt >= NOW() - INTERVAL $realtimeLimit MINUTE
				AND url LIKE '/rent/payment%'
			GROUP BY time";
		$r = mysql_query($query);
		$chart3 = [];
		while($row = mysql_fetch_assoc($r)) {
			$chart3[] = $row;
		}
		$data['chart3'] = $chart3;
		$data['chart3_extime'] = floor((microtime(true) - $set) * 1000);

		$set = microtime(true);
		$query = "SELECT
				DATE_FORMAT(m.dt_create, '%Y-%m-%d') time,
				SUM(CASE
					WHEN mt.retype = 1 THEN 1
					ELSE 0
				END) reservation_short,
				SUM(CASE
					WHEN mt.retype = 2 THEN 1
					ELSE 0
				END) reservation_long
			FROM payments m
				LEFT JOIN reservation mt ON m.reservation_idx = mt.idx
			WHERE
				m.dt_create >= CURDATE() - INTERVAL 60 DAY
				AND m.dan < 5
			GROUP BY time";
		$r = mysql_query($query);
		$chart4 = [];
		while($row = mysql_fetch_assoc($r)) {
			$chart4[] = $row;
		}
		$data['chart4'] = $chart4;
		$data['chart4_extime'] = floor((microtime(true) - $set) * 1000);

		$set = microtime(true);
		$query = "SELECT
				DATE_FORMAT(ma.dt_create, '%Y-%m-%d') time,
				SUM(CASE
					WHEN mt.retype = 1 THEN ma.account - ma.isouts
					ELSE 0
				END) sales_short,
				SUM(CASE
					WHEN mt.retype = 2 THEN ma.account - ma.isouts
					ELSE 0
				END) sales_long
			FROM payment_accounts ma
				LEFT JOIN payments m ON ma.payment_idx = m.idx
				LEFT JOIN reservation mt ON m.reservation_idx = mt.idx
			WHERE
				ma.dt_create >= CURDATE() - INTERVAL 60 DAY
				AND ma.tbtype = 'I'
				AND ma.account > ma.isouts
			GROUP BY time";
		$r = mysql_query($query);
		$chart5 = [];
		while($row = mysql_fetch_assoc($r)) {
			$chart5[] = $row;
		}
		$data['chart5'] = $chart5;
		$data['chart5_extime'] = floor((microtime(true) - $set) * 1000);

		$set = microtime(true);
		$chart6 = [];
		if($count == 1) {
			$query = "SELECT
				t.time,
				COUNT(*) data
			FROM (
				SELECT DATE(dt) time
				FROM log_pageview
				WHERE dt >= CURDATE() - INTERVAL 30 DAY
				GROUP BY time, session_id
				 ) t
			GROUP BY time
			ORDER BY time";
			$r = mysql_query($query);

			while($row = mysql_fetch_assoc($r)) {
				$chart6[] = $row;
			}
		} else {
			array_push($chart6, array('time' => substr($data['time']['now'], 0, 10), 'data' => $data['count6']));
		}
		$data['chart6'] = $chart6;
		$data['chart6_extime'] = floor((microtime(true) - $set) * 1000);

		$set = microtime(true);
		$chart7 = [];
		if($count == 1) {
			$query = "SELECT
				t.time,
				COUNT(*) data
			FROM (
				SELECT DATE(dt) time
				FROM log_search
				WHERE dt >= CURDATE() - INTERVAL 30 DAY
				GROUP BY time, session_id
			) t
			GROUP BY time
			ORDER BY time";
			$r = mysql_query($query);
			while($row = mysql_fetch_assoc($r)) {
				$chart7[] = $row;
			}
		} else {
			array_push($chart7, array('time' => substr($data['time']['now'], 0, 10), 'data' => $data['count7']));
		}
		$data['chart7'] = $chart7;
		$data['chart7_extime'] = floor((microtime(true) - $set) * 1000);

		$set = microtime(true);
		$chart8 = [];
		if($count == 1){
			$query = "SELECT
				t.time,
				COUNT(*) data
			FROM (
				SELECT DATE(dt) time
				FROM log_pageview
				WHERE
					dt >= CURDATE() - INTERVAL 30 DAY
					AND url LIKE '/rent/payment%'
				GROUP BY time, session_id
				 ) t
			GROUP BY time
			ORDER BY time";
			$r = mysql_query($query);
			while($row = mysql_fetch_assoc($r)) {
				$chart8[] = $row;
			}
		} else {
			array_push($chart8, array('time' => substr($data['time']['now'], 0, 10), 'data' => $data['count8']));
		}
		$data['chart8'] = $chart8;
		$data['chart8_extime'] = floor((microtime(true) - $set) * 1000);

		$set = microtime(true);
		$chart9 = [];
		if($count == 1) {
			$query = "SELECT
				DATE(signdate) time,
				COUNT(*) data
			FROM member
			WHERE signdate >= CURDATE() - INTERVAL 30 DAY
			GROUP BY time
			ORDER BY time";
			$r = mysql_query($query);

			while($row = mysql_fetch_assoc($r)) {
				$chart9[] = $row;
			}
		} else {
			array_push($chart9, array('time' => substr($data['time']['now'], 0, 10), 'data' => $data['count9']));
		}
		$data['chart9'] = $chart9;
		$data['chart9_extime'] = floor((microtime(true) - $set) * 1000);

		$set = microtime(true);
		$query = "SELECT
				(CASE
					WHEN lv.user_agent LIKE '%rentking%' THEN 'App'
					WHEN lv.user_agent LIKE '%promotion/%' THEN SUBSTR(SUBSTR(user_agent, LOCATE('promotion/', user_agent)), 11, 100) 
					WHEN lv.referer = '' OR lv.referer LIKE 'http://www.rentking.co.kr/%' OR lv.referer LIKE 'http://m.rentking.co.kr/%' OR lv.referer LIKE 'https://www.rentking.co.kr/%' OR lv.referer LIKE 'https://m.rentking.co.kr/%'  THEN '직접방문'
					WHEN lv.url LIKE '/?gclid%' THEN 'Google'
					WHEN lv.referer LIKE '%google.co%' THEN 'Google'
					WHEN lv.referer LIKE '%search.naver%' AND (lv.url LIKE '/?NaPm%' OR lv.url LIKE '/?n_media=%') THEN 'Naver검색'
					WHEN lv.referer LIKE '%search.naver%' THEN 'Naver검색'
					WHEN lv.referer LIKE '%kin.naver%' THEN 'Naver지식인'
					WHEN lv.referer LIKE '%naver%' THEN 'Naver기타'
					WHEN lv.referer LIKE '%daum.net%' THEN 'Daum'
					WHEN lv.referer LIKE '%facebook%' THEN 'Facebook'
					WHEN lv.referer LIKE '%linkprice.com%' THEN 'LinkPrice'
	 				WHEN lv.referer LIKE '%ilikeclick.com%' THEN 'ILikeClick'
					WHEN lv.referer LIKE '%journalle.com%' THEN '10PING'
#					WHEN lv.referer LIKE '%dreamad.co.kr%' THEN 'dreamad'
#					WHEN lv.referer LIKE '%ncclick.co.kr%' THEN 'ncclick'
#					WHEN lv.referer LIKE '%dwclick.co.kr%' THEN 'dwclick'
					ELSE '기타'
				END) r,
				COUNT(*) total,
				SUM(CASE
					WHEN ls.session_id IS NOT NULL THEN 1
					ELSE 0
				END) search,
				SUM(CASE
					WHEN m.session_id IS NOT NULL THEN 1
					ELSE 0
				END) reservation
			FROM
				(SELECT DISTINCT session_id FROM log_pageview WHERE dt >= CURDATE()) lp
				LEFT JOIN log_visit lv ON lp.session_id = lv.session_id
				LEFT JOIN (SELECT DISTINCT session_id FROM log_search WHERE dt >= CURDATE()) ls ON lv.session_id = ls.session_id
				LEFT JOIN (SELECT DISTINCT mt.session_id FROM reservation mt LEFT JOIN payments m ON mt.idx = m.reservation_idx WHERE m.dan < 5 AND m.dt_create >= CURDATE()) m ON lp.session_id = m.session_id
			GROUP BY r
			ORDER BY total DESC, search DESC";
		$r = mysql_query($query);
		$chart10 = [];
		while($row = mysql_fetch_assoc($r)) {
			$chart10[] = $row;
		}
		$data['chart10'] = $chart10;
		$data['chart10_extime'] = floor((microtime(true) - $set) * 1000);

		$set = microtime(true);
		$query = "SELECT
				t.query,
				SUM(t.point) point,
				SUM(CASE
					WHEN t.reservation IS NOT NULL THEN 1
					ELSE 0
					END) reservation
			FROM (
					 SELECT
						 (CASE
							WHEN lv.referer LIKE '%search.naver%' THEN REPLACE(SUBSTRING(lv.referer, LOCATE('query=', lv.referer)+6, CASE WHEN LOCATE('&', lv.referer, LOCATE('query=', lv.referer)) = 0 THEN 100 ELSE LOCATE('&', lv.referer, LOCATE('query=', lv.referer)) - LOCATE('query=', lv.referer) END - 6), ' ', '')
							WHEN lv.referer LIKE '%search.daum%' THEN REPLACE(SUBSTRING(lv.referer, LOCATE('q=', lv.referer)+2, CASE WHEN LOCATE('&', lv.referer, LOCATE('q=', lv.referer)) = 0 THEN 100 ELSE LOCATE('&', lv.referer, LOCATE('q=', lv.referer)) - LOCATE('q=', lv.referer) END - 2), ' ', '')
							WHEN lv.referer LIKE '%www.bing.com/search%' THEN REPLACE(SUBSTRING(lv.referer, LOCATE('q=', lv.referer)+2, CASE WHEN LOCATE('&', lv.referer, LOCATE('q=', lv.referer)) = 0 THEN 100 ELSE LOCATE('&', lv.referer, LOCATE('q=', lv.referer)) - LOCATE('q=', lv.referer) END - 2), ' ', '')
						  END) query,
						 (POWER(24, 2) - POWER((UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(lv.dt)) / 3600, 2)) point,
						 m.session_id reservation
					 FROM
						 log_visit lv
						 LEFT JOIN (SELECT DISTINCT mt.session_id FROM reservation mt LEFT JOIN payments m ON mt.idx = m.reservation_idx WHERE m.dan < 5 AND m.dt_create >= NOW() - INTERVAL 24 HOUR) m ON lv.session_id = m.session_id
					 WHERE
						 lv.dt >= NOW() - INTERVAL 24 HOUR
						 AND (
							 lv.referer LIKE '%search.naver%' OR
							 lv.referer LIKE '%search.daum%' OR
							 lv.referer LIKE '%www.bing.com/search%'
						 )
			
				 ) t
			GROUP BY t.query";
		$r = mysql_query($query . ' ORDER BY point DESC LIMIT 0,10');
		$search = [];
		while($row = mysql_fetch_assoc($r)) {
			$search[] = $row;
		}
		$data['search'] = $search;
		$data['search_extime'] = floor((microtime(true) - $set) * 1000);

		$set = microtime(true);
		$r = mysql_query($query . ' ORDER BY reservation DESC, point DESC LIMIT 0,10');
		$search = [];
		while($row = mysql_fetch_assoc($r)) {
			$search[] = $row;
		}
		$data['search2'] = $search;
		$data['search2_extime'] = floor((microtime(true) - $set) * 1000);

	}

	echo json_encode($data);
	exit;
}


include "admin_access.php";
include "adminhead.php";
$bodyClass = 'main';
include "admintop.php";

?>

<div id="mainPageContent">
	<?if ($_SESSION['admin_grade'] == '8') {
	include "test/openplatform.php";
	}?>

	<?if ($_SESSION['admin_grade'] == '9') {?>
		<a href="#none" onclick="$('body').toggleClass('full-screen');return false;" id="now" style="position:absolute;top:10px;right:0;"></a>
		<div class="rentking-row" style="height:150px;margin-bottom:16px;">
			<div class="col-6" style="height:100%;">
				<div style="height:100%;position:relative;border:solid 1px #cdd0d5;background:#fff;">
					<div class="blinker" style="height:100%;width:100%;position:absolute;left:0;top:0;"></div>
					<div id="chartReal4" style="position:relative;height:100%;width:100%;"></div>
					<a href="/reserve.php?code=list" title="예약관리로 이동" target="_blank" id="countReal4" style="position:absolute;left:0;top:0;display:block;height:100%;width:100%;line-height:120px;padding-top:30px;text-align:center;letter-spacing:-0.05em;font-size:70px;font-weight:bold;">
						<span id="countReal4_1" class="numberAnimation">0</span>
						/ <span id="countReal4_2" class="numberAnimation">0</span>
						<!--
						/ <span id="countReal4_3" class="numberAnimation">0</span>
						-->
					</a>
					<span class="new-record" id="topRecord4" style="position:absolute;left:5px;bottom:5px;display:none;"></span>
				</div>
			</div>
			<div class="col-6" style="height:100%;">
				<div style="height:100%;position:relative;border:solid 1px #cdd0d5;background:#fff;">
					<div class="blinker" style="height:100%;width:100%;position:absolute;left:0;top:0;"></div>
					<div id="chartReal5" style="position:relative;height:100%;width:100%;"></div>
					<a href="/stat.php?code=sales" title="기간별 예약/매출 통계로 이동" target="_blank" style="position:absolute;left:0;top:0;display:block;height:100%;width:100%;line-height:120px;padding-top:30px;text-align:center;letter-spacing:-0.05em;font-size:70px;font-weight:bold;">
						<span style="font-size:.7em">&#x20A9;</span><span class="numberAnimation" id="countReal5">0</span>
					</a>
					<span class="new-record" id="topRecord5" style="position:absolute;left:5px;bottom:5px;display:none;"></span>
				</div>
			</div>
		</div>
		<div class="rentking-row" style="height:150px;margin-bottom:16px;">
			<div class="col-4" style="height:100%;position:relative;">
				<div style="height:100%;position:relative;border:solid 1px #cdd0d5;background:#fff;">
					<div class="blinker" style="height:100%;width:100%;position:absolute;left:0;top:0;"></div>
					<div id="chartReal1" style="position:relative;height:100%;width:100%;"></div>
					<a href="/stat.php?code=conversion" title="방문/전환 통계로 이동" target="_blank" class="numberAnimation" id="countReal1" style="position:absolute;left:0;top:0;display:block;width:100%;height:100%;line-height:120px;padding-top:30px;text-align:center;letter-spacing:-0.05em;font-size:70px;font-weight:bold;">0</a>
				</div>
			</div>
			<div class="col-4" style="height:100%;">
				<div style="height:100%;position:relative;border:solid 1px #cdd0d5;background:#fff;">
					<div class="blinker" style="height:100%;width:100%;position:absolute;left:0;top:0;"></div>
					<div id="chartReal2" style="position:relative;height:100%;width:100%;"></div>
					<a href="/stat.php?code=conversion" title="방문/전환 통계로 이동" target="_blank" class="numberAnimation" id="countReal2" style="position:absolute;left:0;top:0;display:block;width:100%;height:100%;line-height:120px;padding-top:30px;text-align:center;letter-spacing:-0.05em;font-size:70px;font-weight:bold;">0</a>
				</div>
			</div>
			<div class="col-4" style="height:100%;">
				<div style="height:100%;position:relative;border:solid 1px #cdd0d5;background:#fff;">
					<div class="blinker" style="height:100%;width:100%;position:absolute;left:0;top:0;"></div>
					<div id="chartReal3" style="position:relative;height:100%;width:100%;"></div>
					<a href="/stat.php?code=conversion" title="방문/전환 통계로 이동" target="_blank" class="numberAnimation" id="countReal3" style="position:absolute;left:0;top:0;display:block;width:100%;height:100%;line-height:120px;padding-top:30px;text-align:center;letter-spacing:-0.05em;font-size:70px;font-weight:bold;">0</a>
				</div>
			</div>
		</div>
		<div class="rentking-row" style="height:150px;margin-bottom:16px;">
			<div class="col-3" style="height:100%;position:relative;">
				<div style="height:100%;position:relative;border:solid 1px #cdd0d5;background:#fff;">
					<div class="blinker" style="height:100%;width:100%;position:absolute;left:0;top:0;"></div>
					<div id="chartReal6" style="position:relative;height:100%;width:100%;"></div>
					<a href="/stat.php?code=conversion" title="방문/전환 통계로 이동" target="_blank" class="numberAnimation" id="countReal6" style="position:absolute;left:0;top:0;display:block;width:100%;height:100%;line-height:120px;padding-top:30px;text-align:center;letter-spacing:-0.05em;font-size:70px;font-weight:bold;">0</a>
					<span class="new-record" id="topRecord6" style="position:absolute;left:5px;bottom:5px;display:none;"></span>
				</div>
			</div>
			<div class="col-3" style="height:100%;position:relative;">
				<div style="height:100%;position:relative;border:solid 1px #cdd0d5;background:#fff;">
					<div class="blinker" style="height:100%;width:100%;position:absolute;left:0;top:0;"></div>
					<div id="chartReal7" style="position:relative;height:100%;width:100%;"></div>
					<a href="/stat.php?code=conversion" title="방문/전환 통계로 이동" target="_blank" class="numberAnimation" id="countReal7" style="position:absolute;left:0;top:0;display:block;width:100%;height:100%;line-height:120px;padding-top:30px;text-align:center;letter-spacing:-0.05em;font-size:70px;font-weight:bold;">0</a>
					<span class="new-record" id="topRecord7" style="position:absolute;left:5px;bottom:5px;display:none;"></span>
				</div>
			</div>
			<div class="col-3" style="height:100%;position:relative;">
				<div style="height:100%;position:relative;border:solid 1px #cdd0d5;background:#fff;">
					<div class="blinker" style="height:100%;width:100%;position:absolute;left:0;top:0;"></div>
					<div id="chartReal8" style="position:relative;height:100%;width:100%;"></div>
					<a href="/stat.php?code=conversion" title="방문/전환 통계로 이동" target="_blank" class="numberAnimation" id="countReal8" style="position:absolute;left:0;top:0;display:block;width:100%;height:100%;line-height:120px;padding-top:30px;text-align:center;letter-spacing:-0.05em;font-size:70px;font-weight:bold;">0</a>
					<span class="new-record" id="topRecord8" style="position:absolute;left:5px;bottom:5px;display:none;"></span>
				</div>
			</div>
			<div class="col-3" style="height:100%;position:relative;">
				<div style="height:100%;position:relative;border:solid 1px #cdd0d5;background:#fff;">
					<div class="blinker" style="height:100%;width:100%;position:absolute;left:0;top:0;"></div>
					<div id="chartReal9" style="position:relative;height:100%;width:100%;"></div>
					<a href="/stat.php?code=member" title="회원 통계로 이동" target="_blank" class="numberAnimation" id="countReal9" style="position:absolute;left:0;top:0;display:block;width:100%;height:100%;line-height:120px;padding-top:30px;text-align:center;letter-spacing:-0.05em;font-size:70px;font-weight:bold;">0</a>
					<span class="new-record" id="topRecord9" style="position:absolute;left:5px;bottom:5px;display:none;"></span>
				</div>
			</div>
		</div>
		<div class="rentking-row" style="height:250px;margin-bottom:16px;">
			<div class="col-8" style="height:100%;">
				<div style="height:100%;position:relative;border:solid 1px #cdd0d5;background:#fff;">
					<div id="chartReal10" style="position:relative;height:100%;width:100%;"></div>
				</div>
			</div>
			<div class="col-2" style="height:100%;">
				<div style="height:100%;position:relative;border:solid 1px #cdd0d5;background:#fff;">
					<h3 style="text-align:center;font-size:18px;margin-top:10px;">실시간 검색어 (방문순)</h3>
					<ul id="searchReal">
					</ul>
				</div>
			</div>
			<div class="col-2" style="height:100%;">
				<div style="height:100%;position:relative;border:solid 1px #cdd0d5;background:#fff;">
					<h3 style="text-align:center;font-size:18px;margin-top:10px;">실시간 검색어 (예약순)</h3>
					<ul id="searchReal2">
					</ul>
				</div>
			</div>
		</div>

		<script type="text/javascript">
			$(function() {
				var chartOption = {
					type: 'serial',
					colors: [defaultChartOption.colors[1]],
					graphs: [
						{
							id: 'session',
							type: 'column',
							lineThickness: 0,
							title: '데이터',
							valueField: 'data',
							balloonText: '',
							fillAlphas: 0.3
						}],
					dataDateFormat: 'YYYY-MM-DD JJ:NN:SS',
					categoryAxis: {
						parseDates: true,
						labelsEnabled: false,
						minPeriod: '<?=$minSec ?>ss',
						dateFormats: [
							{period: 'fff', format: 'YYYY-MM-DD JJ:NN:SS'},
							{period: 'ss', format: 'YYYY-MM-DD JJ:NN:SS'},
							{period: '<?=$minSec ?>ss', format: 'YYYY-MM-DD JJ:NN:SS'},
							{period: 'mm', format: 'YYYY-MM-DD JJ:NN:SS'},
							{period: 'hh', format: 'YYYY-MM-DD JJ:NN:SS'},
							{period: 'DD', format: 'YYYY-MM-DD JJ:NN:SS'},
							{period: 'WW', format: 'YYYY-MM-DD JJ:NN:SS'},
							{period: 'MM', format: 'YYYY-MM-DD JJ:NN:SS'},
							{period: 'YYYY', format: 'YYYY-MM-DD JJ:NN:SS'}
						],
						axisColor:'#CCCCCC',
						startOnAxis: true,
						gridAlpha: 0,
						axisAlpha: 0,
						minorGridEnabled: false
					},
					valueAxes: [
						$.extend(true, {}, defaultChartOption.valueAxes, {gridAlpha:0, axisAlpha:0, minimum:0})
					],
					categoryField: 'time',
					legend: {enabled: false},
					chartCursor: {enabled: false},
					autoMargins: false,
					marginLeft: 5,
					marginRight: 5,
					marginBottom: 0,
					marginTop: 40,
					export: {enabled: false}
				};

				var chartReal1 = AmCharts.makeChart('chartReal1', $.extend(true, {}, defaultChartOption, chartOption, {
					colors: [defaultChartOption.colors[1]],
					titles: [$.extend(true, {}, defaultChartOption.titles, {id: 'title1', text: '현재 방문', size: 18})]
				}));

				var chartReal2 = AmCharts.makeChart('chartReal2', $.extend(true, {}, defaultChartOption, chartOption, {
					colors: [defaultChartOption.colors[1]],
					titles: [$.extend(true, {}, defaultChartOption.titles, {id: 'title2', text: '현재 검색', size: 18})]
				}));

				var chartReal3 = AmCharts.makeChart('chartReal3', $.extend(true, {}, defaultChartOption, chartOption, {
					colors: [defaultChartOption.colors[3]],
					titles: [$.extend(true, {}, defaultChartOption.titles, {id: 'title3', text: '현재 예약페이지 방문', size: 18})]
				}));

				var chartReal4 = AmCharts.makeChart('chartReal4', $.extend(true, {}, defaultChartOption, chartOption, {
					colors: [defaultChartOption.colors[2], defaultChartOption.colors[3]],
					titles: [$.extend(true, {}, defaultChartOption.titles, {id: 'title3', text: '오늘 예약 / 취소요청', size: 18})],
					dataDateFormat: 'YYYY-MM-DD',
					categoryAxis: {
						minPeriod: 'DD',
						dateFormats: [
							{period: 'DD', format: 'YYYY-MM-DD'}
						]
					},
					valueAxes: [
						$.extend(true, {}, defaultChartOption.valueAxes, {gridAlpha: 0, axisAlpha: 0, stackType: 'regular'})
					],
					graphs: [
						{
							id: 'long',
							type: 'column',
							lineThickness: 0,
							title: '장기',
							valueField: 'reservation_long',
							balloonText: '',
							fillAlphas: 0.3
						},
						{
							id: 'short',
							type: 'column',
							lineThickness: 0,
							title: '단기',
							valueField: 'reservation_short',
							balloonText: '',
							fillAlphas: 0.3
						}]
				}));

				var chartReal5 = AmCharts.makeChart('chartReal5', $.extend(true, {}, defaultChartOption, chartOption, {
					colors: [defaultChartOption.colors[0], defaultChartOption.colors[1]],
					titles: [$.extend(true, {}, defaultChartOption.titles, {id: 'title3', text: '오늘 매출', size: 18})],
					dataDateFormat: 'YYYY-MM-DD',
					categoryAxis: {
						minPeriod: 'DD',
						dateFormats: [
							{period: 'DD', format: 'YYYY-MM-DD'}
						]
					},
					valueAxes: [
						$.extend(true, {}, defaultChartOption.valueAxes, {gridAlpha: 0, axisAlpha: 0, stackType: 'regular'})
					],
					graphs: [
						{
							id: 'long',
							type: 'column',
							lineThickness: 0,
							title: '장기',
							valueField: 'sales_long',
							balloonText: '',
							fillAlphas: 0.3
						},
						{
							id: 'short',
							type: 'column',
							lineThickness: 0,
							title: '단기',
							valueField: 'sales_short',
							balloonText: '',
							fillAlphas: 0.3
						}]
				}));

				var chartReal6 = AmCharts.makeChart('chartReal6', $.extend(true, {}, defaultChartOption, chartOption, {
					colors: [defaultChartOption.colors[1]],
					titles: [$.extend(true, {}, defaultChartOption.titles, {id: 'title3', text: '오늘 방문', size: 18})],
					dataDateFormat: 'YYYY-MM-DD',
					categoryAxis: {
						minPeriod: 'DD',
						dateFormats: [
							{period: 'DD', format: 'YYYY-MM-DD'}
						]
					}
				}));

				var chartReal7 = AmCharts.makeChart('chartReal7', $.extend(true, {}, defaultChartOption, chartOption, {
					colors: [defaultChartOption.colors[1]],
					titles: [$.extend(true, {}, defaultChartOption.titles, {id: 'title3', text: '오늘 검색', size: 18})],
					dataDateFormat: 'YYYY-MM-DD',
					categoryAxis: {
						minPeriod: 'DD',
						dateFormats: [
							{period: 'DD', format: 'YYYY-MM-DD'}
						]
					}
				}));

				var chartReal8 = AmCharts.makeChart('chartReal8', $.extend(true, {}, defaultChartOption, chartOption, {
					colors: [defaultChartOption.colors[1]],
					titles: [$.extend(true, {}, defaultChartOption.titles, {id: 'title3', text: '오늘 예약페이지 방문', size: 18})],
					dataDateFormat: 'YYYY-MM-DD',
					categoryAxis: {
						minPeriod: 'DD',
						dateFormats: [
							{period: 'DD', format: 'YYYY-MM-DD'}
						]
					}
				}));

				var chartReal9 = AmCharts.makeChart('chartReal9', $.extend(true, {}, defaultChartOption, chartOption, {
					colors: [defaultChartOption.colors[1]],
					titles: [$.extend(true, {}, defaultChartOption.titles, {id: 'title3', text: '오늘 회원가입', size: 18})],
					dataDateFormat: 'YYYY-MM-DD',
					categoryAxis: {
						minPeriod: 'DD',
						dateFormats: [
							{period: 'DD', format: 'YYYY-MM-DD'}
						]
					}
				}));

				var chartReal10 = AmCharts.makeChart('chartReal10', $.extend(true, {}, defaultChartOption, chartOption, {
					colors: [defaultChartOption.colors[1], defaultChartOption.colors[0], defaultChartOption.colors[2]],
					titles: [$.extend(true, {}, defaultChartOption.titles, {id: 'title3', text: '오늘 방문 소스', size: 18})],
					dataDateFormat: 'YYYY-MM-DD',
					graphs: [
						{
							id: 'total',
							type: 'column',
							lineThickness: 0,
							title: '방문',
							valueField: 'total',
							balloonText: '',
							fillAlphas: 0.9,
							balloonColor: defaultChartOption.balloonColor,
							balloonFunction: function(item, graph) {
								var html = '<div style="text-align:left;font-weight:bold;">'
									+ '<p style="margin:0;color:' + defaultChartOption.colors[1] + ';">방문: <span style="float:right;margin-left:10px;">' + Number(item.dataContext['total']).numberFormat(0) + '</span></p>'
									+ '<p style="margin:0;color:' + defaultChartOption.colors[0] + ';">검색: <span style="float:right;margin-left:10px;">' + Number(item.dataContext['search']).numberFormat(0) + '(' + (Number(item.dataContext['search'])/Number(item.dataContext['total']) * 100).numberFormat(1) + '%)</span></p>'
									+ '<p style="margin:0;color:' + defaultChartOption.colors[2] + ';">예약: <span style="float:right;margin-left:10px;">' + Number(item.dataContext['reservation']).numberFormat(0) + '건(' + (Number(item.dataContext['reservation'])/Number(item.dataContext['total']) * 100).numberFormat(1) + '%)</span></p>'
								html += '</div>';
								return html;
							}
						},
						{
							id: 'search',
							type: 'column',
							lineThickness: 0,
							title: '검색',
							valueField: 'search',
							valueAxis: 'total',
							balloonText: '',
							fillAlphas: 0.9
						},
						{
							id: 'reservation',
							type: 'column',
							lineThickness: 0,
							title: '예약',
							valueField: 'reservation',
							valueAxis: 'reservationTotal',
							balloonText: '',
							fillAlphas: 0.9
						}],
					categoryField: 'r',
					categoryAxis: {
						parseDates: false,
						labelsEnabled: true,
						axisColor:'#000000',
						startOnAxis: false,
						gridAlpha: 0.15,
						axisAlpha: 1,
						gridPosition: 'start'
					},
					valueAxes: [
						$.extend(true, {}, defaultChartOption.valueAxes, {id: 'total', gridAlpha: 0.15, axisAlpha: 1}),
						$.extend(true, {}, defaultChartOption.valueAxes, {id: 'reservationTotal', gridAlpha: 0, axisAlpha: 1, position: 'right', labelFunction: function(value, valueText, valueAxis) {return value+'건';}})
					],
					autoMargins: true,
					marginLeft: 20,
					marginRight: 20,
					marginBottom: 20,
					marginTop: 20,
					chartCursor: {enabled: true},
					legend: {enabled: true}
				}));

				var $countReal1 = $('#countReal1');
				var $countReal2 = $('#countReal2');
				var $countReal3 = $('#countReal3');
				var $countReal4_1 = $('#countReal4_1');
				var $countReal4_2 = $('#countReal4_2');
//				var $countReal4_3 = $('#countReal4_3');
				var $countReal5 = $('#countReal5');
				var $countReal6 = $('#countReal6');
				var $countReal7 = $('#countReal7');
				var $countReal8 = $('#countReal8');
				var $countReal9 = $('#countReal9');
				var $searchReal = $('#searchReal');
				var $searchReal2 = $('#searchReal2');
				var reloadTime = <?=$reloadTime ?>;

				var $topRecord4 = $('#topRecord4');
				var $topRecord5 = $('#topRecord5');
				var $topRecord6 = $('#topRecord6');
				var $topRecord7 = $('#topRecord7');
				var $topRecord8 = $('#topRecord8');
				var $topRecord9 = $('#topRecord9');

				var count = 0;
				var reload = function() {
					count++;
					$countReal1.siblings('.blinker').removeClass('ripple');
					$countReal2.siblings('.blinker').removeClass('ripple');
					$countReal3.siblings('.blinker').removeClass('ripple');
					$countReal4_1.parent().siblings('.blinker').removeClass('ripple');
					$countReal5.parent().siblings('.blinker').removeClass('ripple');
					$countReal6.siblings('.blinker').removeClass('ripple');
					$countReal7.siblings('.blinker').removeClass('ripple');
					$countReal8.siblings('.blinker').removeClass('ripple');
					$countReal9.siblings('.blinker').removeClass('ripple');

					$.getJSON('/main.php?mode=ajax&command=realtime&count='+count, function(data) {
						var currentCount1 = $countReal1.data('target');
						var currentCount2 = $countReal2.data('target');
						var currentCount3 = $countReal3.data('target');
						var currentCount4_1 = $countReal4_1.data('target');
						var currentCount4_2 = $countReal4_2.data('target');
//						var currentCount4_3 = $countReal4_3.data('target');
						var currentCount5 = $countReal5.data('target');
						var currentCount6 = $countReal6.data('target');
						var currentCount7 = $countReal7.data('target');
						var currentCount8 = $countReal8.data('target');
						var currentCount9 = $countReal9.data('target');

						var count1 = parseInt(data['count1']);
						var count2 = parseInt(data['count2']);
						var count3 = parseInt(data['count3']);
						var count4_1 = parseInt(data['count4']['reservation']);
						var count4_2 = parseInt(data['count4']['cancelr']);
//						var count4_3 = parseInt(data['count4']['cancel']);
						var count5 = parseInt(data['count5']);
						var count6 = parseInt(data['count6']);
						var count7 = parseInt(data['count7']);
						var count8 = parseInt(data['count8']);
						var count9 = parseInt(data['count9']);

						if(isNaN(count1))	count1 = 0;
						if(isNaN(count2))	count2 = 0;
						if(isNaN(count3))	count3 = 0;
						if(isNaN(count4_1))	count4_1 = 0;
						if(isNaN(count4_2))	count4_2 = 0;
//						if(isNaN(count4_3))	count4_3 = 0;
						if(isNaN(count5))	count5 = 0;
						if(isNaN(count6))	count6 = 0;
						if(isNaN(count7))	count7 = 0;
						if(isNaN(count8))	count8 = 0;
						if(isNaN(count9))	count9 = 0;

						var changed = false;
						if(currentCount1 != count1) {
							changed = true;
							$countReal1.siblings('.blinker').addClass('ripple');

							if(typeof $countReal1.data('target') !== 'undefined') {
								$countReal1.data('target', count1).text(count1.numberFormat(0));
							} else {
								$countReal1.data('target', count1).trigger('animate');
							}
						}
						if(currentCount2 != count2) {
							changed = true;
							$countReal2.siblings('.blinker').addClass('ripple');

							if(typeof $countReal2.data('target') !== 'undefined') {
								$countReal2.data('target', count2).text(count2.numberFormat(0));
							} else {
								$countReal2.data('target', count2).trigger('animate');
							}
						}
						if(currentCount3 != count3) {
							changed = true;
							$countReal3.siblings('.blinker').addClass('ripple');

							if(typeof $countReal3.data('target') !== 'undefined') {
								$countReal3.data('target', count3).text(count3.numberFormat(0));
							} else {
								$countReal3.data('target', count3).trigger('animate');
							}
						}
						if(currentCount4_1 != count4_1 || currentCount4_2 != count4_2) { // || currentCount4_3 != count4_3) {
							changed = true;
							$countReal4_1.parent().siblings('.blinker').addClass('ripple');

							if(count4_2 > 0) {
								$countReal4_1.parent().siblings('.blinker').addClass('important-ripple');
							} else {
								$countReal4_1.parent().siblings('.blinker').removeClass('important-ripple');
							}

							if(typeof $countReal4_1.data('target') !== 'undefined') {
								$countReal4_1.data('target', count4_1).text(count4_1.numberFormat(0));
							} else {
								$countReal4_1.data('target', count4_1).trigger('animate');
							}

							if(typeof $countReal4_2.data('target') !== 'undefined') {
								$countReal4_2.data('target', count4_2).text(count4_2.numberFormat(0));
							} else {
								$countReal4_2.data('target', count4_2).trigger('animate');
							}

//							if(typeof $countReal4_3.data('target') !== 'undefined') {
//								$countReal4_3.data('target', count4_3).text(count4_3.numberFormat(0));
//							} else {
//								$countReal4_3.data('target', count4_3).trigger('animate');
//							}
						}
						if(currentCount5 != count5) {
							changed = true;
							$countReal5.parent().siblings('.blinker').addClass('ripple');

							if(typeof $countReal5.data('target') !== 'undefined') {
								$countReal5.data('target', count5).text(count5.numberFormat(0));
							} else {
								$countReal5.data('target', count5).trigger('animate');
							}
						}
						if(currentCount6 != count6) {
							changed = true;
							$countReal6.siblings('.blinker').addClass('ripple');

							if(typeof $countReal6.data('target') !== 'undefined') {
								$countReal6.data('target', count6).text(count6.numberFormat(0));
							} else {
								$countReal6.data('target', count6).trigger('animate');
							}
						}
						if(currentCount7 != count7) {
							changed = true;
							$countReal7.siblings('.blinker').addClass('ripple');

							if(typeof $countReal7.data('target') !== 'undefined') {
								$countReal7.data('target', count7).text(count7.numberFormat(0));
							} else {
								$countReal7.data('target', count7).trigger('animate');
							}
						}
						if(currentCount8 != count8) {
							changed = true;
							$countReal8.siblings('.blinker').addClass('ripple');

							if(typeof $countReal8.data('target') !== 'undefined') {
								$countReal8.data('target', count8).text(count8.numberFormat(0));
							} else {
								$countReal8.data('target', count8).trigger('animate');
							}
						}
						if(currentCount9 != count9) {
							changed = true;
							$countReal9.siblings('.blinker').addClass('ripple');

							if(typeof $countReal9.data('target') !== 'undefined') {
								$countReal9.data('target', count9).text(count9.numberFormat(0));
							} else {
								$countReal9.data('target', count9).trigger('animate');
							}
						}

						if(changed) {
							reloadTime -= 500;
						} else {
							reloadTime += 250;
						}

						if(reloadTime > <?=$maxReloadTime ?>) {
							reloadTime = <?=$maxReloadTime ?>;
						} else if(reloadTime < <?=$reloadTime ?>) {
							reloadTime = <?=$reloadTime ?>;
						}

						var now10s = {time:moment(data['time']['10s']).format('YYYY-MM-DD HH:mm:ss'), data:0};
						var now1d = {time:moment(data['time']['now']).format('YYYY-MM-DD'), data:0};

						var before10m10s = {time: moment(data['time']['10s']).add(-10, 'm').format('YYYY-MM-DD HH:mm:ss'), data:0};
						var before30d1d = {time: moment(data['time']['now']).add(-30, 'd').format('YYYY-MM-DD'), data: 0};
						var before60d1d = {time: moment(data['time']['now']).add(-60, 'd').format('YYYY-MM-DD'), data: 0};

						if(data['chart1'].length > 0 && data['chart1'][0]['time'] != before10m10s['time'])	data['chart1'].splice(0,0,before10m10s);
						if(data['chart2'].length > 0 && data['chart2'][0]['time'] != before10m10s['time'])	data['chart2'].splice(0,0,before10m10s);
						if(data['chart3'].length > 0 && data['chart3'][0]['time'] != before10m10s['time'])	data['chart3'].splice(0,0,before10m10s);
						if(data['chart4'].length > 0 && data['chart4'][0]['time'] != before60d1d['time'])	data['chart4'].splice(0,0,before60d1d);
						if(data['chart5'].length > 0 && data['chart5'][0]['time'] != before60d1d['time'])	data['chart5'].splice(0,0,before60d1d);
						if(count == 1) {
							if(data['chart6'].length > 0 && data['chart6'][0]['time'] != before30d1d['time'])	data['chart6'].splice(0,0,before30d1d);
							if(data['chart7'].length > 0 && data['chart7'][0]['time'] != before30d1d['time'])	data['chart7'].splice(0,0,before30d1d);
							if(data['chart8'].length > 0 && data['chart8'][0]['time'] != before30d1d['time'])	data['chart8'].splice(0,0,before30d1d);
							if(data['chart9'].length > 0 && data['chart9'][0]['time'] != before30d1d['time'])	data['chart9'].splice(0,0,before30d1d);
						}

						if(data['chart1'].length > 0 && data['chart1'][data['chart1'].length - 1]['time'] != now10s['time'])	data['chart1'].push(now10s);
						if(data['chart2'].length > 0 && data['chart2'][data['chart2'].length - 1]['time'] != now10s['time'])	data['chart2'].push(now10s);
						if(data['chart3'].length > 0 && data['chart3'][data['chart3'].length - 1]['time'] != now10s['time'])	data['chart3'].push(now10s);
						if(data['chart4'].length > 0 && data['chart4'][data['chart4'].length - 1]['time'] != now1d['time'])		data['chart4'].push(now1d);
						if(data['chart5'].length > 0 && data['chart5'][data['chart5'].length - 1]['time'] != now1d['time'])		data['chart5'].push(now1d);
						if(count == 1) {
							if(data['chart6'].length > 0 && data['chart6'][data['chart6'].length - 1]['time'] != now1d['time'])		data['chart6'].push(now1d);
							if(data['chart7'].length > 0 && data['chart7'][data['chart7'].length - 1]['time'] != now1d['time'])		data['chart7'].push(now1d);
							if(data['chart8'].length > 0 && data['chart8'][data['chart8'].length - 1]['time'] != now1d['time'])		data['chart8'].push(now1d);
							if(data['chart9'].length > 0 && data['chart9'][data['chart9'].length - 1]['time'] != now1d['time'])		data['chart9'].push(now1d);
						}

						chartReal1.dataProvider = data['chart1'];
						chartReal1.validateData();

						chartReal2.dataProvider = data['chart2'];
						chartReal2.validateData();

						chartReal3.dataProvider = data['chart3'];
						chartReal3.validateData();

						chartReal4.dataProvider = data['chart4'];
						chartReal4.validateData();

						chartReal5.dataProvider = data['chart5'];
						chartReal5.validateData();

						if(count == 1) {
							chartReal6.dataProvider = data['chart6'];
							chartReal7.dataProvider = data['chart7'];
							chartReal8.dataProvider = data['chart8'];
							chartReal9.dataProvider = data['chart9'];
						} else {
							var update = false;
							var dataProvider = chartReal6.dataProvider;
							var dataName = 'chart6';
							for(var i in dataProvider) {
								if(dataProvider[i]['time'] == data[dataName][0]['time']) {
									update = true;
									dataProvider[i] = data[dataName][0];
								}
							}
							if(!update)	dataProvider.push(data[dataName][0]);

							update = false;
							dataProvider = chartReal7.dataProvider;
							dataName = 'chart7';
							for(var i in dataProvider) {
								if(dataProvider[i]['time'] == data[dataName][0]['time']) {
									update = true;
									dataProvider[i] = data[dataName][0];
								}
							}
							if(!update)	dataProvider.push(data[dataName][0]);

							update = false;
							dataProvider = chartReal8.dataProvider;
							dataName = 'chart8';
							for(var i in dataProvider) {
								if(dataProvider[i]['time'] == data[dataName][0]['time']) {
									update = true;
									dataProvider[i] = data[dataName][0];
								}
							}
							if(!update)	dataProvider.push(data[dataName][0]);

							update = false;
							dataProvider = chartReal9.dataProvider;
							dataName = 'chart9';
							for(var i in dataProvider) {
								if(dataProvider[i]['time'] == data[dataName][0]['time']) {
									update = true;
									dataProvider[i] = data[dataName][0];
								}
							}
							if(!update)	dataProvider.push(data[dataName][0]);
						}
						chartReal6.validateData();
						chartReal7.validateData();
						chartReal8.validateData();
						chartReal9.validateData();

						var max = 0;
						for(var i in data['chart10']) {
							var reservation = Number(data['chart10'][i]['reservation']);
							if(reservation > max) {
								max = reservation;
							}
						}

						if(max >= 10)
							delete chartReal10.valueAxes[1].maximum;
						else
							chartReal10.valueAxes[1].maximum = 10;
						chartReal10.dataProvider = data['chart10'];
						chartReal10.validateData();

						var html = '';
						var query = '';
						for(i in data['search']) {
							query = data['search'][i]['query'];
							html += '<li style="position:relative;">' +
								'<span style="font-weight:bold;display:inline-block;width:30px;text-align:center;color:#103e8a;position:absolute;left:10px;top:0;height:100%;">' + (Number(i) + 1) + '</span>' +
								'<div style="margin:0 50px 0 40px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">' + ($.trim(query) ? query : '검색어없음') + '</div>' +
								'<span style="font-weight:bold;display:inline-block;width:40px;padding-right:10px;position:absolute;top:0;right:0;color:#FF6600;text-align:right;">' + data['search'][i]['reservation'] + '</span>'
								'</li>'
						}
						$searchReal.html(html);

						html = '';
						for(i in data['search2']) {
							query = data['search2'][i]['query'];
							html += '<li style="position:relative;">' +
								'<span style="font-weight:bold;display:inline-block;width:30px;text-align:center;color:#103e8a;position:absolute;left:10px;top:0;height:100%;">' + (Number(i) + 1) + '</span>' +
								'<div style="margin:0 50px 0 40px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">' + ($.trim(query) ? query : '검색어없음') + '</div>' +
								'<span style="font-weight:bold;display:inline-block;width:40px;padding-right:10px;position:absolute;top:0;right:0;color:#FF6600;text-align:right;">' + data['search2'][i]['reservation'] + '</span>'
							'</li>'
						}
						$searchReal2.html(html);


						//top-record
						var record = data['record'];
						if(count4_1 >= parseInt(record['reservation_day']))		$topRecord4.addClass('show');
						else													$topRecord4.removeClass('show');

						if(count5 >= parseInt(record['sales_day']))		$topRecord5.addClass('show');
						else											$topRecord5.removeClass('show');

						if(count6 >= parseInt(record['visit_day']))		$topRecord6.addClass('show');
						else											$topRecord6.removeClass('show');

						if(count7 >= parseInt(record['search_day']))		$topRecord7.addClass('show');
						else												$topRecord7.removeClass('show');

						if(count8 >= parseInt(record['visit_reservation_page_day']))	$topRecord8.addClass('show');
						else															$topRecord8.removeClass('show');

						if(count9 >= parseInt(record['join_day']))		$topRecord9.addClass('show');
						else											$topRecord9.removeClass('show');

						$('#now').text(data['time']['now'] + ' (' + (reloadTime/1000).numberFormat(2) + 's)');
						if(window.refresh)
							setTimeout(reload, reloadTime);
					}).fail(function() {setTimeout(reload, reloadTime * 2);})
				};

				reload();
				window.refresh = true;

				setTimeout(function() {
					location.reload();
				}, 3600 * 1000)
			})
		</script>
	<?}?>



	<?if ($_SESSION['member_grade'] == '10') {?>
	<div class="mainPageCon_2">
		<div class="mpc_2">
			<span class="subTitle">공지사항</span>
			<table class="listTable">
				<colgroup>
					<col style="width:100px;">
					<col>
					<col style="width:200px;">
				</colgroup>
				<thead>
				<tr>
					<th>번호</th>
					<th>제목</th>
					<th>등록일</th>
				</tr>
				</thead>
				<tbody>
				<?
				$query = "SELECT * FROM notices WHERE dt_delete IS NULL AND rentshop_yn = 'Y' ORDER BY dt_create DESC LIMIT 0,10";
				$r = mysql_query($query);
				if(mysql_num_rows($r) > 0) {
					while($row = mysql_fetch_assoc($r)) {?>
						<tr>
							<td><?=$row['idx']?></td>
							<td style="text-align:left;padding:0 5px;"><a href="/notice.php?idx=<?=$row['idx']?>&t=<?=time()?>" class="openPopup" title="<?=$row['title']?>"><?=$row['title']?></a></td>
							<td><?=$row['dt_create']?></td>
						</tr>
					<?}
				} else {?>
					<tr>
						<td colspan="3">공지사항이 없습니다.</td>
					</tr>
				<?} ?>
				</tbody>
			</table>
		</div>

		<div class="mpc_2" style="margin-top:20px;">
			<span class="subTitle" style="margin-bottom:-10px;">예약</span>
			<a href="reserve.php?code=list"><span class="blackBtn">더보기</span></a>
			<table class="listTable">
				<thead>
				<tr>
					<th>예약번호</th>
					<th>구분</th>
					<th>예약일시</th>
					<th>예약자<br>휴대폰</th>
					<th>차종</th>
					<th>대여일시<br>반납일시</th>
					<th>배달장소<br>반납장소</th>
					<th>총금액</th>
					<th>진행상태</th>
				</tr>
				</thead>
				<tbody>
				<?
				$q = "SELECT
						m.*, 
						mem.name mem_name, 
						mem.cp cp, 
						mt.vehicle_idx, 
						mt.addr, 
						mt.raddr, 
						mt.rtype, 
						mt.sdate, 
						mt.edate, 
						mt.retype, 
						vs.name modelname,
						lv.user_agent,
						(SELECT SUM(CASE WHEN turn = 1 THEN account-isouts+m.discount ELSE account-isouts END) FROM payment_accounts WHERE payment_idx = m.idx AND tbtype='I' AND account > isouts) total_account, 
						v.carnum, r.name, r.affiliate, mt.extend extendyn
					FROM payments m
						LEFT JOIN reservation mt ON m.reservation_idx = mt.idx
						LEFT JOIN member mem ON m.member_idx = mem.idx
						LEFT JOIN vehicles v ON v.idx = mt.vehicle_idx
						LEFT JOIN vehicle_models vs ON v.model_idx = vs.idx
						LEFT JOIN rentshop r ON v.rentshop_idx = r.idx
						LEFT JOIN log_visit lv ON m.session_id = lv.session_id
					WHERE 
						v.rentshop_idx={$ar_rshop['idx']}
						AND m.dan IN (1, 4)
					ORDER BY m.dt_create DESC
					";
				$r = mysql_query($q);

				if(mysql_num_rows($r) > 0) {
					while($row = mysql_fetch_assoc($r)) {
						$onclick = "MM_openBrWindow('/popup/orderview.php?idx={$row['idx']}','order{$row['idx']}','scrollbars=yes,width=1150,height=700,top=0,left=0');return false;";
						?>
						<tr>
							<td><a href="#<?=$row['idx'];?>" onclick="<?=$onclick?>"><?=$row['idx'];?></a></td>
							<td><?=$ar_retype[$row['retype']];?>(<?
								if(strpos($row['user_agent'], 'rentking/') !== FALSE) {
									echo 'A';
								} else {
									echo $row['pid'];
								}
								?>)</td>
							<td><?=$row['dt_create'] ?></td>
							<td style="text-align:left;padding:0 5px;"><?=$row['mem_name'];?><br/><?=phone_number_format($row['cp'])?></td>
							<td style="text-align:left;padding:0 5px;"><?=$row['modelname'];?><br/>[<?=$row['carnum'];?>]</td>
							<td><?=date("y.m.d H:i", strtotime($row['sdate']));?><br/><?=date("y.m.d H:i", strtotime($row['edate']));?></td>
							<td style="text-align:left;padding:0 5px;">
								<?
								if ($row['ptype'] == '2') {
									echo "지점대여";
								} else {
									if($row['rtype'] == 1) {
										echo $row['addr'];
									} else {
										echo $row['addr'] . "<br />" . $row['raddr'];
									}
								}
								?>
							</td>
							<td style='text-align:right;padding-right:5px;'><?=number_format($row['total_account']);?>원</td>
							<td style='width:90px;'><? get_marketdan($row['dan'], $row['extend_payment_idx'], $row['extendyn']); ?></td>
						</tr>

					<?}
				} else {?>
					<tr>
						<td colspan="9">예약이 없습니다.</td>
					</tr>
				<?}?>
				</tbody>
			</table>
		</div>

		<div class="mpc_2" style="margin-top:20px;">
			<span class="subTitle" style="margin-bottom:-10px;">대여중</span>
			<a href="reserve.php?code=list"><span class="blackBtn">더보기</span></a>
			<table class="listTable">
				<thead>
				<tr>
					<th>예약번호</th>
					<th>구분</th>
					<th>예약일시</th>
					<th>예약자<br>휴대폰</th>
					<th>차종</th>
					<th>대여일시<br>반납일시</th>
					<th>배달장소<br>반납장소</th>
					<th>총금액</th>
					<th>진행상태</th>
				</tr>
				</thead>
				<tbody>
				<?
				$q = "SELECT
						m.*, 
						mem.name mem_name, 
						mem.cp cp, 
						mt.vehicle_idx, 
						mt.addr, 
						mt.raddr, 
						mt.rtype, 
						mt.sdate, 
						mt.edate, 
						mt.retype, 
						vs.name modelname,
						lv.user_agent,
						(SELECT SUM(CASE WHEN turn = 1 THEN account-isouts+m.discount ELSE account-isouts END) FROM payment_accounts WHERE payment_idx = m.idx AND tbtype='I' AND account > isouts) total_account, 
						v.carnum, r.name, r.affiliate, mt.extend extendyn
					FROM payments m
						LEFT JOIN reservation mt ON m.reservation_idx = mt.idx
						LEFT JOIN member mem ON m.member_idx = mem.idx
						LEFT JOIN vehicles v ON v.idx = mt.vehicle_idx
						LEFT JOIN vehicle_models vs ON v.model_idx = vs.idx
						LEFT JOIN rentshop r ON v.rentshop_idx = r.idx
						LEFT JOIN log_visit lv ON m.session_id = lv.session_id
					WHERE 
						v.rentshop_idx={$ar_rshop['idx']}
						AND m.dan IN (2)
					ORDER BY m.dt_create DESC
					";
				$r = mysql_query($q);

				if(mysql_num_rows($r) > 0) {
					while($row = mysql_fetch_assoc($r)) {
						$onclick = "MM_openBrWindow('/popup/orderview.php?idx={$row['idx']}','order{$row['idx']}','scrollbars=yes,width=1150,height=700,top=0,left=0');return false;";
						?>
						<tr>
							<td><a href="#<?=$row['idx'];?>" onclick="<?=$onclick?>"><?=$row['idx'];?></a></td>
							<td><?=$ar_retype[$row['retype']];?>(<?
								if(strpos($row['user_agent'], 'rentking/') !== FALSE) {
									echo 'A';
								} else {
									echo $row['pid'];
								}
								?>)</td>
							<td><?=$row['dt_create'] ?></td>
							<td style="text-align:left;padding:0 5px;"><?=$row['mem_name'];?><br/><?=phone_number_format($row['cp'])?></td>
							<td style="text-align:left;padding:0 5px;"><?=$row['modelname'];?><br/>[<?=$row['carnum'];?>]</td>
							<td><?=date("y.m.d H:i", strtotime($row['sdate']));?><br/><?=date("y.m.d H:i", strtotime($row['edate']));?></td>
							<td style="text-align:left;padding:0 5px;">
								<?
								if ($row['ptype'] == '2') {
									echo "지점대여";
								} else {
									if($row['rtype'] == 1) {
										echo $row['addr'];
									} else {
										echo $row['addr'] . "<br />" . $row['raddr'];
									}
								}
								?>
							</td>
							<td style='text-align:right;padding-right:5px;'><?=number_format($row['total_account']);?>원</td>
							<td style='width:90px;'><? get_marketdan($row['dan'], $row['extend_payment_idx'], $row['extendyn']); ?></td>
						</tr>

					<?}
				} else {?>
					<tr>
						<td colspan="9">예약이 없습니다.</td>
					</tr>
				<?}?>
				</tbody>
			</table>
		</div>
	</div>
	<?}
	include "adminfoot.php";
