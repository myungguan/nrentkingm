<?php
$limit = 100;
$reloadTime = 1000;
$maxReloadTime = 5000;

$mode = $_REQUEST['mode'];
$from = $_REQUEST['from'];

if($mode == 'ajax') {
	include $_SERVER['DOCUMENT_ROOT']."/old/inc/base.php";
	include $config['incPath']."/connect.php";
	include $config['incPath']."/session.php";
	include $config['incPath']."/config.php";
	include "../admin_access.php";
}

$where = '';
if($from) {
	$where .= " AND idx > $from";
}

$query = "SELECT *
	FROM log_search
	WHERE 1=1
		$where
	ORDER BY dt DESC
	LIMIT 0, $limit";
$listr = mysql_query($query);

if($mode == 'ajax') {
	$count = mysql_num_rows($listr);
	$result = array('count' => $count);
	if($count > 0) {
		$data = [];
		while($row = mysql_fetch_assoc($listr)) {
			$data[] = $row;
		}
		$result['data'] = $data;
	}

	echo json_encode($result);
	exit;
}
?>
<div style="position:relative;">
	<span style="position:absolute;top:-20px;right:0;">reload time: <span id="reload"><?=number_format($reloadTime/1000, 2)?></span>s</span>
	<table class="listTableColor">
		<thead>
		<tr>
			<th>세션ID</th>
			<th>시간</th>
			<th>순서</th>
			<th>멤버사/차량</th>
			<th>픽업일시<br />반납일시</th>
			<th>검색주소</th>
			<th>지점방문</th>
			<th>등급</th>
			<th>고객부담금</th>
			<th>모델</th>
			<th>연료</th>
			<th>옵션</th>
		</tr>
		</thead>
		<tbody id="monitoringList">
		<?
		$count = 0;
		while($row = mysql_fetch_assoc($listr)) {?>
			<tr>
				<td class="al" style="padding:0 4px;"><a href="/monitoring/session_p.php?session_id=<?=$row['session_id']?>" onclick="MM_openBrWindow(this.href,'session_<?=$row['session_id']?>','scrollbars=yes,width=1200,height=700,top=0,left=0');return false;"><?=$row['session_id']?></a></td>
				<td class="al" style="padding:0 4px;"><span style="white-space:nowrap;"><?=substr($row['dt'], 11, 8)?></span></td>
				<td class="al" style="padding:0 4px;"><?=$row['count'] == 1 ? '시작' : '상세('.($row['count']-1).')'?></td>
				<td class="al" style="padding:0 4px;"><?=$row['rentshop']?> / <?=$row['total']?></td>
				<td class="al" style="padding:0 4px;"><span style="white-space:nowrap;"><?=substr($row['sdate'], 0 ,16)?></span><br /><span style="white-space:nowrap;"><?=substr($row['edate'], 0 ,16)?></span></td>
				<td class="al" style="padding:0 4px;"><?=$row['addr']?></td>
				<td class="ar" style="padding:0 4px;"><?=number_format($row['distance']/1000)?>&#13214;</td>
				<td class="al grade" style="padding:0 4px;"><?=$row['grade']?></td>
				<td class="al" style="padding:0 4px;"><?=str_replace('|', ',', str_replace('section', '', $row['insu']))?></td>
				<td class="al model" style="padding:0 4px;"><?=$row['model']?></td>
				<td class="al fuel" style="padding:0 4px;"><?=$row['fuel']?></td>
				<td class="al" style="padding:0 4px;"><?=str_replace('|', ',', str_replace('vop', '', $row['option']))?></td>
			</tr>
			<?
			if($count == 0)
				$from = $row['idx'];
			$count++;
		}?>
		</tbody>
	</table>
</div>

<script type="text/javascript">
	$(function() {
		var grade = [];
		<?
		$query = "SELECT idx, sname FROM codes WHERE ttype = 2 AND dt_delete IS NULL";
		$r = mysql_query($query);
		while($row = mysql_fetch_assoc($r)) {?>
		grade[<?=$row['idx']?>] = '<?=$row['sname']?>';
		<?}?>
		var replaceGrade = function(data) {
			var str = '';

			if(data.length > 0) {
				var arr = data.split('|');
				var lineFeed = 10;
				for(var i = 0; i < arr.length; i++) {
					if(i > 0) str += ',';
					if(str.length >= lineFeed) {
						str += '<br />';
						lineFeed += 16;
					}
					str += grade[arr[i]];
				}
			}
			return str;
		};

		var fuel = [];
		<?
		$query = "SELECT idx, sname FROM codes WHERE ttype = 3 AND dt_delete IS NULL";
		$r = mysql_query($query);
		while($row = mysql_fetch_assoc($r)) {?>
		fuel[<?=$row['idx']?>] = '<?=$row['sname']?>';
		<?}?>
		var replaceFuel = function(data) {
			var str = '';

			if(data.length > 0) {
				var arr = data.split('|');
				var lineFeed = 10;
				for(var i = 0; i < arr.length; i++) {
					if(i > 0) str += ',';
					if(str.length >= lineFeed) {
						str += '<br />';
						lineFeed += 16;
					}
					str += fuel[arr[i]];
				}
			}
			return str;
		};

		var replaceModel = function(data) {
			var str = '';

			if(data.length > 0) {
				var arr = data.split('|');
				var lineFeed = 10;
				for(var i = 0; i < arr.length; i++) {
					if(i > 0) str += ',';
					if(str.length >= lineFeed) {
						str += '<br />';
						lineFeed += 16;
					}
					str += arr[i];
				}
			}

			return str;
		};

		var from = '<?=$from ?>';
		var $monitoringList = $('#monitoringList');
		var reloadTime = <?=$reloadTime ?>;

		$monitoringList.find('td.grade').each(function() {
			$(this).html(replaceGrade($(this).text()));
		});
		$monitoringList.find('td.model').each(function() {
			$(this).html(replaceModel($(this).text()));
		});
		$monitoringList.find('td.fuel').each(function() {
			$(this).html(replaceFuel($(this).text()));
		});

		var getData = function() {
			$.getJSON('/monitoring/search.php?mode=ajax&from='+from, function(data) {
				if(data['count'] > 0) {
					from = data['data'][0]['idx'];
					var html = '';
					for(var i = 0; i < data['count']; i++) {
						html += '<tr  class="new">'
								+ '<td class="al" style="padding:0 4px;"><a href="/monitoring/session_p.php?session_id=' + data['data'][i]['session_id'] + '" onclick="MM_openBrWindow(this.href,\'session_' + data['data'][i]['session_id'] + '\',\'scrollbars=yes,width=1200,height=700,top=0,left=0\');return false;">' + data['data'][i]['session_id'] + '</a></td>'
								+ '<td class="al" style="padding:0 4px;"><span style="white-space:nowrap;">'+ data['data'][i]['dt'].substr(11) + '</span></td>'
								+ '<td class="al" style="padding:0 4px;">'+ (data['data'][i]['count'] == 1 ? '시작' : '상세(' + (data['data'][i]['count']-1) + ')') + '</td>'
								+ '<td class="al" style="padding:0 4px;">'+ data['data'][i]['rentshop'] + ' / ' + data['data'][i]['total'] + '</td>'
								+ '<td class="al" style="padding:0 4px;"><span style="white-space:nowrap;">'+ data['data'][i]['sdate'].substr(0, 16) + '</span><br /><span style="white-space:nowrap;">' + data['data'][i]['edate'].substr(0, 16) + '</span></td>'
								+ '<td class="al" style="padding:0 4px;">'+ data['data'][i]['addr'] + '</td>'
								+ '<td class="ar" style="padding:0 4px;">'+ (parseInt(data['data'][i]['distance'])/1000).numberFormat(0) + '&#13214;</td>'
								+ '<td class="al grade" style="padding:0 4px;">'+ replaceGrade(data['data'][i]['grade']) + '</td>'
								+ '<td class="al" style="padding:0 4px;">'+ data['data'][i]['insu'].replace(/section/g, '').replace(/\|/g, ',') + '</td>'
								+ '<td class="al" style="padding:0 4px;">'+ replaceModel(data['data'][i]['model']) + '</td>'
								+ '<td class="al fuel" style="padding:0 4px;">'+ replaceFuel(data['data'][i]['fuel']) + '</td>'
								+ '<td class="al" style="padding:0 4px;">'+ data['data'][i]['option'].replace(/vop/g, '').replace(/\|/g, ',') + '</td>'
							+ '</tr>';
					}
					$monitoringList.prepend(html);

					$monitoringList.find('tr').each(function(idx) {
						if(idx >= <?=$limit ?>)
							$(this).remove();
					});

					setTimeout(function() {
						$monitoringList.find('tr.new td:not(.ripple)').addClass('ripple');
					}, 200);
					reloadTime -= 250 * data['count'];
				} else {
					reloadTime += 250;
				}

				if(reloadTime > <?=$maxReloadTime ?>) {
					reloadTime = <?=$maxReloadTime ?>;
				} else if(reloadTime < <?=$reloadTime ?>) {
					reloadTime = <?=$reloadTime ?>;
				}

				$('#reload').text((reloadTime/1000).numberFormat(2));

				setTimeout(function() {
					getData();
				}, reloadTime);
			})
		};

		setTimeout(function() {
			getData();
		}, reloadTime);
	})
</script>