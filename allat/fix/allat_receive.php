<?php
	// 결과값
	$result_cd  = $_POST["allat_result_cd"];
	$result_msg = $_POST["allat_result_msg"];
	$enc_data   = $_POST["allat_enc_data"];

	// 결과값 Return
	echo("<script>");
	echo("if(window.opener != undefined) {");
	echo("	opener.result_submit('".$result_cd."','".$result_msg."','".$enc_data."');");
	echo("	window.close();");
	echo("} else {");
	echo("	parent.result_submit('".$result_cd."','".$result_msg."','".$enc_data."');");
	echo("}");
	echo("</script>");
?>