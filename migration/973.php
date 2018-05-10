<?php
/**
 * Created by Sanggoo.
 * Date: 2017-08-01
 */

include $_SERVER['DOCUMENT_ROOT']."/old/inc/base.php";
include $config['incPath']."/connect.php";

$query = "SELECT vs.idx, vs.files, vs.regidate
	FROM
		vehicle_std vs
		LEFT JOIN files f ON f.article_type = 'car' AND f.article_idx = vs.idx
	WHERE
		f.idx IS NULL
";
$r = mysql_query($query);
while($row = mysql_fetch_assoc($r)) {
	$idx = $row['idx'];
	$name = $row['files'];
	$path = '/car/'.$name;
	$file = $_SERVER['DOCUMENT_ROOT'].'/files'.$path;
	$type = mime_content_type($file);
	$size = filesize($file);
	$imagesize = getimagesize($file);

	$dt = $row['regidate'];
	$query = "INSERT INTO files(article_type, article_idx, article_info, name, path, type, size, width, height, dt) VALUES ('car', $idx, 'default', '$name', '$path', '$type', $size, ${imagesize[0]}, ${imagesize[1]}, '$dt')";
	mysql_query($query);
	echo $query;
	echo "<br />\n";
}