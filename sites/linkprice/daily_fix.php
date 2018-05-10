<?php
include $_SERVER['DOCUMENT_ROOT']."/old/inc/base.php";
include $config['incPath']."/connect.php";
	// DataBase ����

	// LINKPRICE �� ���ؼ� ��û�� �������� QUERY
	// QUERY ����  : ��������=yyyymmdd and ���޻�=��ũ�����̽�
	// SELECT �÷� : ���Žð�, LPINFO, ������ID, �������̸�, �ֹ���ȣ, ��ǰ�ڵ�, �ֹ�����, �����ݾ�
	// ex) $sql = "select * from ��ũ�����̽�_�������̺� where ��¥ like '$yyyymmdd%' and LPINFO is not null";

	$yyyymmdd = $_REQUEST['yyyymmdd'];

	$sql = "
		select DATE_FORMAT(dt, '%H%i%s') hhmiss, lpinfo, id, name, order_code, product_code, item_count, price, category_code, product_name, remote_addr
		from linkprice
		where type='cps' AND DATE_FORMAT(dt, '%Y%m%d') = '".$yyyymmdd."'
	";

	$result = mysql_query($sql);
//	$total = mysql_num_rows($result);

	while ($row = mysql_fetch_array($result))
	{
		$line  = $row["hhmiss"]."\t";
		$line .= $row["lpinfo"]."\t";
		$line .= $row["id"]."(".$row["name"].")"."\t";
		$line .= $row["order_code"]."\t";
		$line .= $row["product_code"]."\t";
		$line .= $row["item_count"]."\t";
		$line .= $row["price"]."\t";
		$line .= $row["category_code"]."\t\t";
		$line .= $row["product_name"]."\t";
		$line .= $row["remote_addr"];

		// ���� �������� ������ ���� �ƴϸ� �� �ٲ�(\n)�� �Ѵ�.
//		if ($total != 1)
			$line .= "\n";
//		else
//			$line .= $row["REMOTE_ADDR"];

		echo $line;
	}

	// DataBase ���� ����
?>