<?php
	if(!defined('xmt'))
		exit;
	
	function xmt_thm_sld_enq_scr(){
		wp_enqueue_script('jquery');
		wp_enqueue_script('xmt_innerfade', xmt_get_dir('url').'/js/innerfade.js', array('jquery'));
	}

	add_action('wp_enqueue_scripts', 'xmt_thm_sld_enq_scr');

	$tpl_cfg = array(
		'thm_sld_int' => 2000
	);
?>