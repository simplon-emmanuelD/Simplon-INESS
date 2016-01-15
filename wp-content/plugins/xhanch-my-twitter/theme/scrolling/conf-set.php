<?php
	if(!defined('xmt'))
		exit;

	$tpl_cfg = array(
		'thm_scr_szh' => intval(xmt_form_post('int_xmt_thm_scr_szh')),
		'thm_scr_anm' => intval(xmt_form_post('chk_xmt_thm_scr_anm')),
		'thm_scr_anm_dir' => xmt_form_post('cbo_xmt_thm_scr_anm_dir'),
		'thm_scr_anm_amt' => intval(xmt_form_post('int_xmt_thm_scr_anm_amt')),
		'thm_scr_anm_dly' => intval(xmt_form_post('int_xmt_thm_scr_anm_dly')),
	);
?>