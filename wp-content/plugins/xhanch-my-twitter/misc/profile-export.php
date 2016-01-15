<?php
	$expires = 2592000;
	
	header("Content-Type: text/css");
	header("Pragma: public");
	header("Cache-Control: maxage=".$expires);
	header('Expires: '.gmdate('D, d M Y H:i:s', time()+$expires).' GMT');
	
	include_once('../../../../wp-config.php');
	include_once('../../../../wp-load.php');
	include_once('../../../../wp-includes/wp-db.php');	

	if(!current_user_can('install_plugins'))
		exit;

	global $xmt_acc;
	
	$acc_sel = xmt_form_get('prf');

	$ept_dat = array(
		'nme' => $acc_sel,
		'cfg' => $xmt_acc[$acc_sel]['cfg']
	);
	$ept_dat_str = base64_encode(serialize($ept_dat));
	$fl_len = strlen($ept_dat_str);

	header('Pragma: public');
	header("Content-Length: $fl_len");
	header('Content-Description: File Transfer');
	header('Content-Transfer-Encoding: binary');	
	header('Content-Type: application/force-download');
	header('Content-Disposition: attachment; filename="xmt-'.$acc_sel.'.xmt"');
	
	echo $ept_dat_str;
?>