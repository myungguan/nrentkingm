<?php
include $_SERVER['DOCUMENT_ROOT']."/old/inc/base.php";
include $config['incPath']."/connect.php";
	// DataBase ����

	// LINKPRICE �� ���ؼ� ��û�� �������� QUERY
	// QUERY ����  : ��������=yyyymmdd and ���޻�=��ũ�����̽�
	// SELECT �÷� : ���Žð�, LPINFO, ������ID, �������̸�, �ֹ���ȣ, ��ǰ�ڵ�, �ֹ�����, �����ݾ�
	// ex) $sql = "select * from ��ũ�����̽�_�������̺� where ��¥ like '$yyyymmdd%' and LPINFO is not null";

$o_cd = $_REQUEST['o_cd'];
$p_cd = $_REQUEST['p_cd'];


	$sql = "
		select status
		from linkprice
		where type='cps' AND order_code = '$o_cd' and product_code = '$p_cd';
	";

	$result = mysql_query($sql);
	$total = mysql_num_rows($result);

	if($total > 0) {
		while ($row = mysql_fetch_array($result))
		{
			$line  = $row["status"]."\t";
			$text = 'Ȯ�ο��';
			if($row['status'] == 0) {
				$text = '����(����)';
			} else if($row ['status'] == 1) {
				$text = '�����Ϸ�';
			} else if($row ['status'] == 2) {
				$text = '�������';
			}
			$line .= $text;

			echo $line;
		}
	} else {
		echo "3\t�ֹ� ����";
	}


	// DataBase ���� ����
?>