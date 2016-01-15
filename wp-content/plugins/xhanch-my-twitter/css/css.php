<?php
	$expires = 2592000;
	
	header("Content-Type: text/css");
	header("Pragma: public");
	header("Cache-Control: maxage=".$expires);
	header('Expires: '.gmdate('D, d M Y H:i:s', time()+$expires).' GMT');
	
	include_once('../../../../wp-config.php');
	include_once('../../../../wp-load.php');
	include_once('../../../../wp-includes/wp-db.php');	
	
	$css = xmt_css_minify(file_get_contents("css.css"));
	
	global $xmt_acc;
	foreach($xmt_acc as $acc=>$xmt_det)
		echo str_replace('{xmt_id}', '#xmt_'.$acc.'_wid', $css);
?>