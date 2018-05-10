<?php
/**
 * admin.rentking.co.kr/sho.php?code=kakao
 * 관리자페이지 > 사이트관리 > 카카오알림톡
 * 카카오 알림톡
 */

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://api.apistore.co.kr/kko/2/template/list/rentking');
curl_setopt($ch, CURLOPT_HTTPHEADER, array("x-waple-authorization: {$config['kakaoAuth']}"));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_POST, FALSE);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,0);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
$response = curl_exec($ch);
curl_close($ch);

$template_list = json_decode($response, TRUE);

if($template_list['result_code'] == 200) {
	mysql_query("TRUNCATE TABLE kakao_template");
	$query = "INSERT INTO kakao_template (code, name, msg, button_type, button_name, button_url, status) VALUES ";
	$count = 0;
	foreach($template_list['templateList'] as $template) {
		$code = mysql_escape_string($template['template_code']);
		$name = mysql_escape_string($template['template_name']);
		$msg = mysql_real_escape_string($template['template_msg']);
		$button_type = $template['btnList']['template_btn_type'];
		$button_name = mysql_escape_string($template['btnList']['template_btn_name']);
		$button_url = mysql_escape_string($template['btnList']['template_btn_url']);
		$status = mysql_escape_string($template['status']);

		if($count > 0)
			$query .= ", ";
		$query .= "('$code', '$name', '$msg', '$button_type', '$button_name', '$button_url', '$status')";

		$count++;
	}
	if($count > 0)
		mysql_query($query);
}

?>
<div class="list_wrap order_list">
	<table class="listTableColor">
		<thead>
		<tr>
			<th>코드</th>
			<th>이름</th>
			<th>내용</th>
			<th>버튼타입</th>
			<th>버튼이름</th>
			<th>버튼링크주소</th>
			<th>상태</th>
		</tr>
		</thead>
		<tbody>
		<?
		$query = "SELECT
				*,
				CASE
					WHEN button_type = 'DS' THEN '배송조회'
					WHEN button_type = 'C' THEN 'URL 버튼'
					ELSE '없음'
				END button_type
 			FROM kakao_template ORDER BY code";
		$r = mysql_query($query);

		while($row = mysql_fetch_assoc($r)) {?>
			<tr>
				<td><?=$row['code']?></td>
				<td><?=$row['name']?></td>
				<td style="text-align:left;"><?=nl2br($row['msg'])?></td>
				<td><?=$row['button_type']?></td>
				<td><?=$row['button_name']?></td>
				<td><?=$row['button_url']?></td>
				<td><?=$row['status']?></td>
			</tr>
		<?}?>
		</tbody>
	</table>
</div><!-- // .list_wrap -->
