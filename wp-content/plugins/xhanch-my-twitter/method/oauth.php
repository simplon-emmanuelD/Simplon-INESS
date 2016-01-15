<?php
	// Get Reply
	$req = xmt_twt_oah_rpl_get($acc);
	xmt_twt_raw_imp($acc, $req, 'rty');
	
	// Get Direct Message
	$req =xmt_twt_oah_drc_msg_get($acc);
	xmt_twt_raw_imp($acc, $req, 'dmg');
	
	// Get Tweet
	$req = xmt_twt_oah_twt_get($acc);
	xmt_twt_raw_imp($acc, $req, 'twt');
?>