<?php
	if(!defined('xmt'))
		exit;
			
	function xmt_thm_scl_enq_scr(){
		wp_enqueue_script('jquery');
		wp_enqueue_script('xmt_marquee', xmt_get_dir('url').'/js/marquee.js', array('jquery'));
	}

	add_action('wp_enqueue_scripts', 'xmt_thm_scl_enq_scr');

	$tpl_cfg = array(
		'thm_scr_szh' => 200,
		'thm_scr_anm' => 0,
		'thm_scr_anm_dir' => 'up',
		'thm_scr_anm_amt' => 1,
		'thm_scr_anm_dly' => 50
	);
?>