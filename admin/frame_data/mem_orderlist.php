<?
include $_SERVER['DOCUMENT_ROOT']."/old/inc/base.php";
include $config['incPath']."/connect.php";
include $config['incPath']."/session.php";
include $config['incPath']."/config.php";
include "../adminhead.php";


$idx = $_REQUEST['idx'];
$ar_mem = sel_query_all("member"," where idx='$idx'");
?>
<script type="text/javascript">
function init(){
  var doc = document.getElementById('content');
  if(doc.offsetHeight == 0){
  } else {
  pageheight = doc.offsetHeight;

  parent.document.getElementById("iframebody").height = pageheight+"px";
  }
}
window.onload = function(){
  init();
}
</script>
</head>
<body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0">
<div id="content" style="position:absolute;left:0;top:0;width:100%;"> 

<div class="h3_wrap">
	<h3>
		<?	if($ar_mem['memgrade']=='100')	{	?>
		* <?=$ar_mem['name'];?> 회원 렌트내역
		<?}else{?>
		* <?=$ar_mem['company'];?> 구성원 렌트내역
		<?}?>
	</h3>
</div>

		<table class="listTable">
		<thead>
		<tr>
			<th>예약번호</th>
			<th>구분</th>
			<th>예약일시</th>
			<th>예약자Mobile</th>
			<th>차종</th>
			<th>대여일시반납일시</th>
			<th>대여지점</th>
			<th>배달장소반납장소</th>
			<th>결제금액</th>
			<th>진행상태</th>
		</tr>
		</thead>
		<tbody>
		<?
			$q = "SELECT m.idx, v.rentshop_idx, mt.retype, mt.addr, mt.raddr, mem.name mem_name, mem.cp cp, mt.sdate, mt.edate, vs.name, r.name, r.affiliate
				FROM payments m
					LEFT JOIN reservation mt ON m.reservation_idx = mt.idx
					LEFT JOIN member mem ON m.member_idx = mem.idx
					LEFT JOIN vehicles v ON v.idx=mt.vehicle_idx
					LEFT JOIN vehicle_models vs ON v.model_idx = vs.idx
					LEFT JOIN rentshop r ON r.idx = v.rentshop_idx
				WHERE m.member_idx='$idx' ORDER BY m.dt_create DESC";


		$r = mysql_query($q);
		while($row = mysql_fetch_array($r))	{
			$total_payment = 0;
			$query = "
					SELECT
						tbtype, account
					FROM
						payment_accounts
					WHERE
						payment_idx = {$row['idx']}
				";
			$ar = mysql_query($query);
			while ($a = mysql_fetch_assoc($ar)) {
				if ($a['tbtype'] == 'I') {
					$total_payment += $a['account'];
				} else {
					$total_payment -= $a['account'];
				}
			}
		?>
		<tr>
			<td><?=$row['idx'];?></td>
			<td><?=$ar_retype[$row['retype']];?></td>
			<td><?=$row['dt_create'];?></td>
			<td><?=$row['mem_name'];?>/<?=$row['cp'];?></td>
			<td><?=$row['name'];?></td>
			<td><?=date("y.m.d H:i",strtotime($row['sdate']));?> ~ <?=date("y.m.d H:i",strtotime($row['edate']));?></td>
			<td><?=$row['name'] ?>(<?=$row['affiliate'];?>)</td>
			<td>
			<?
			if($row['ptype']=='2')	{
				echo "지점대여";
			}
			else	{
				echo $row['addr']."<br />".$row['raddr'];
			}
			?>
			</td>
			<td style='text-align:right;padding-right:5px;'><?=number_format($total_payment);?>원</td>
			<td style='width:90px;'><? get_marketdan($row['dan'], $row['extend_payment_idx']);	?></td>
		</tr>
		<?
		}
		?>
		</tbody>
		</table>


</div><!-- // #content -->
</body>
</html>