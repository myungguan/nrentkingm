<?php
$cnt = count($locationcur);
foreach ( $locationcur as $val ) {
	$loop++;
	if ( $loop == $cnt ) {
		$location_html .= "<strong>{$val}</strong>";
		$title_first = "[{$val}]";
	} else {
		//$location_html .= "{$val} &gt; ";
		//$title_lasts[] = "{$val}";
	}
}
$title_last = @implode(">", $title_lasts);
$Btitle = "{$title_first} {$title_last}";
?>
<div class="pageTitle">
<?=$location_html;?>
</div>