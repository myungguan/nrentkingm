<?php
	// �����
	$result_cd  = $_POST["allat_result_cd"];
	$result_msg = $_POST["allat_result_msg"];
	$enc_data   = $_POST["allat_enc_data"];

	// ����� Return
	echo "<script>parent.result_submit('".$result_cd."','".$result_msg."','".$enc_data."');</script>";
?>
