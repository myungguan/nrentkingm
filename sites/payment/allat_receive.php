<?php
	// 결과값
	$result_cd  = $_POST["allat_result_cd"];
	$result_msg = iconv("euc-kr","utf-8",$_POST["allat_result_msg"]);
//	$result_msg = $_POST["allat_result_msg"];
	$enc_data   = $_POST["allat_enc_data"];

	// 결과값 Return
	echo("<script>\n");
	echo("if(opener && typeof opener['result_submit'] !== 'undefined') {\n");
	echo("	opener.result_submit('".$result_cd."','".$result_msg."','".$enc_data."');\n");
	echo("	window.close();\n");
	echo("} else {\n");
	echo("	parent.result_submit('".$result_cd."','".$result_msg."','".$enc_data."');\n");
	echo("}\n");
	echo("</script>\n");
?>
