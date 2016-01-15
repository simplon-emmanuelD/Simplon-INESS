<?php
	if(!defined('xmt'))
		exit;

	function xmt_acc_add($acc, $cfg){
		global $wpdb;
		global $xmt_acc;

		$sql = '
			insert into '.$wpdb->prefix.'xmt_acc(
				nme,
				cfg,
				las_twt_imp_dtp						
			)values(
				'.xmt_sql_str($acc).',
				'.xmt_sql_str(serialize($cfg)).',
				0
			)
		';
		$wpdb->query($sql);
		$rid = $wpdb->insert_id;

		$xmt_acc[$acc] = array(
			'id' => $rid,
			'cfg' => $cfg,
			'las_twt_imp_dtp' => 0
		);
	}

	function xmt_acc_del($acc){
		global $wpdb;
		global $xmt_acc;

		$sql = '
			delete from '.$wpdb->prefix.'xmt_twt
			where acc_nme = '.xmt_sql_str($acc).'
		';
		 $wpdb->query($sql);

		$sql = '
			delete from '.$wpdb->prefix.'xmt_acc
			where nme = '.xmt_sql_str($acc).'
		';
		 $wpdb->query($sql);

		 unset($xmt_acc[$acc]);
	}

	function xmt_acc_cfg_upd($acc, $cfg){
		global $wpdb;
		global $xmt_acc;

		$sql = '
			update '.$wpdb->prefix.'xmt_acc
			set cfg = '.xmt_sql_str(serialize($cfg)).'
			where nme = '.xmt_sql_str($acc).'
		';
		$wpdb->query($sql);

		$xmt_acc[$acc]['cfg'] = $cfg;
	}

	function xmt_cch_get($acc, $pth, $epr=0){
		global $xmt_acc;
		
		if(!$xmt_acc[$acc]['cfg']['cch_enb'])
			return false;
		
		$cch_fle = xmt_cch_dir.$acc.'-'.$pth.'.cch.php';

		if(!file_exists($cch_fle))
			return false;

		$cch_dat = mb_substr(@file_get_contents($cch_fle), 52);
		if(!$cch_dat)
			return false;
		$cch_dat = @json_decode($cch_dat, true);
		if(!$cch_dat)
			return false;
		
		$tme_spn = time() - intval($cch_dat['dte']);
		if($epr == 0 && intval($xmt_acc[$acc]['cfg']['cch_exp']) > 0){
			if($tme_spn > intval($xmt_acc[$acc]['cfg']['cch_exp']) * 60)
				return false;
		}elseif($epr > 0 && $tme_spn > $epr * 60){
			return false;
		}

		return $cch_dat['dat'];
	}

	function xmt_cch_set($acc, $pth, $dat){
		global $xmt_acc;
		
		if(!$xmt_acc[$acc]['cfg']['cch_enb'])
			return;

		if(!is_dir(xmt_cch_dir))
			@mkdir(xmt_cch_dir);		
		
		$cch_fle = xmt_cch_dir.$acc.'-'.$pth.'.cch.php';
		if(!file_exists($cch_fle))
			@unlink($cch_fle);
		
		@file_put_contents($cch_fle, '<?php if(!defined(\'xmt\')) header(\'location:/\'); ?>'."\r\n".json_encode(array('dte' => time(), 'dat' => $dat)));
		unset($dat);
	}

	function xmt_cch_del($acc, $pth){
		$cch_fle = xmt_cch_dir.$acc.'-'.$pth.'.cch.php';
		if(file_exists($cch_fle))
			@unlink($cch_fle);
	}

	function xmt_twt_cch_rst($acc){
		xmt_cch_del($acc, 'twt');
	}

	function xmt_prf_cch_rst($acc){
		global $xmt_acc;
		xmt_cch_del($acc, 'prf');
	}
?>