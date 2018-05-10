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

$url = $_REQUEST['url'];
if($url) {
	$where .= " AND url LIKE '$url%'";
}

$query = "SELECT *
	FROM log_pageview
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

<form id="search" name="search" action="/monitoring.php" method="get">
	<input type="hidden" name="code" value="<?=$code ?>" />
	<table class="detailTable2">
		<tbody>
		<tr>
			<th>URL</th>
			<td>
				<input type="text" name="url" value="<?=$url?>" />
				<span class="greenBtn btn_submit" data-form="#search" style="height:23px;line-height:23px;"><a href="javascript:">검색하기</a></span>
			</td>
		</tr>
		</tbody>
	</table>
</form>

<div style="position:relative;margin-top:20px;">
	<span style="position:absolute;top:-20px;right:0;">reload time: <span id="reload"><?=number_format($reloadTime/1000, 2)?></span>s</span>
	<table class="listTableColor">
		<thead>
		<tr>
			<th>세션ID</th>
			<th>시간</th>
			<th>URL</th>
		</tr>
		</thead>
		<tbody id="monitoringList">
		<?
		$count = 0;
		while($row = mysql_fetch_assoc($listr)) {?>
			<tr>
				<td class="al" style="padding:0 4px;"><a href="/monitoring/session_p.php?session_id=<?=$row['session_id']?>" onclick="MM_openBrWindow(this.href,'session_<?=$row['session_id']?>','scrollbars=yes,width=1200,height=700,top=0,left=0');return false;"><?=$row['session_id']?></a></td>
				<td class="al" style="padding:0 4px;"><span style="white-space:nowrap;"><?=substr($row['dt'], 11, 8)?></span></td>
				<td class="al" style="padding:0 4px;"><?=substr($row['url'], 0, 2) == '/?' ? '/' : (substr($row['url'], 0, 80).(strlen($row['url']) > 80 ? '...' : ''))?></td>
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
		var from = '<?=$from ?>';
		var $monitoringList = $('#monitoringList');
		var reloadTime = <?=$reloadTime ?>;

		var getData = function() {
			$.getJSON('/monitoring/pageview.php?mode=ajax&from='+from,  $('#search').serializeArray(), function(data) {
				if(data['count'] > 0) {
					from = data['data'][0]['idx'];
					var html = '';
					for(var i = 0; i < data['count']; i++) {
						html += '<tr  class="new">'
								+ '<td class="al" style="padding:0 4px;"><a href="/monitoring/session_p.php?session_id=' + data['data'][i]['session_id'] + '" onclick="MM_openBrWindow(this.href,\'session_' + data['data'][i]['session_id'] + '\',\'scrollbars=yes,width=1200,height=700,top=0,left=0\');return false;">' + data['data'][i]['session_id'] + '</a></td>'
								+ '<td class="al" style="padding:0 4px;"><span style="white-space:nowrap;">'+ data['data'][i]['dt'].substr(11) + '</span></td>'
								+ '<td class="al" style="padding:0 4px;">'+ (data['data'][i]['url'].indexOf('/?') == 0 ? data['data'][i]['url'].replace(/([^?]*)\?.+/, '$1') : data['data'][i]['url'].substr(0, 80) + (data['data'][i]['url'].length > 80 ? '...' : '')) + '</td>'
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
