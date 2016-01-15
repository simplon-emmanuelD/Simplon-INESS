<?php
	if(!defined('xmt'))
		exit;

	global $wpdb;
	global $xmt_cfg_def;

	$upd = false;	

	$ver = get_option('xmt_vsn');
	if(!$ver){
		$sql = '
			create table if not exists '.$wpdb->prefix.'xmt(
				id int(11) not null auto_increment,
				nme varchar(100) not null,
				cfg longblob not null,
				twt_cch longblob not null,
				twt_cch_dtp bigint(20) not null default \'0\',
				prf_cch longblob not null,
				prf_cch_dtp bigint(20) not null default \'0\',
				primary key (id),
				unique key nme_unique (nme)
			) default charset=utf8 collate=utf8_general_ci
		';
		if($wpdb->query($sql) === false)
			return false;

		$upd = true;

		$ver = '1.0.0';
		update_option('xmt_vsn', $ver);
	}

	if($ver == '1.0.0'){
		$sql = '
			create table if not exists '.$wpdb->prefix.'xmt_twt(
				id int(11) not null auto_increment,
				acc_nme varchar(100) not null,
				twt_id bigint(20) not null,
				twt_ath varchar(100) not null,
				twt varchar(255) not null,
				twt_dtp varchar(19) not null,
				twt_typ varchar(3) not null,
				twt_src varchar(255) not null,
				primary key (id)
			) default charset=utf8 collate=utf8_general_ci
		';
		if($wpdb->query($sql) === false)
			return false;

		$sql = '
			create table if not exists '.$wpdb->prefix.'xmt_ath(
				id int(11) not null auto_increment,
				uid varchar(100) not null,
				nme varchar(100) not null,
				img_url varchar(250) not null,
				dte_upd bigint(20) not null,
				primary key (id)
			) default charset=utf8 collate=utf8_general_ci
		';
		if($wpdb->query($sql) === false)
			return false;

		$sql = '
			alter table '.$wpdb->prefix.'xmt
			add las_twt_imp_dtp bigint(20) not null default \'0\' after prf_cch_dtp 
		';
		if($wpdb->query($sql) === false)
			return false;

		$sql = '
			rename table '.$wpdb->prefix.'xmt 
			to '.$wpdb->prefix.'xmt_acc
		';
		if($wpdb->query($sql) === false)
			return false;

		$upd = true;

		$ver = '1.0.1';
		update_option('xmt_vsn', $ver);
	}

	if($ver == '1.0.1'){
		$sql = '
			alter table '.$wpdb->prefix.'xmt_acc
			default character set utf8 collate utf8_general_ci
		';
		if($wpdb->query($sql) === false)
			return false;

		$sql = '
			alter table '.$wpdb->prefix.'xmt_twt
			default character set utf8 collate utf8_general_ci
		';
		if($wpdb->query($sql) === false)
			return false;

		$sql = '
			alter table '.$wpdb->prefix.'xmt_ath
			default character set utf8 collate utf8_general_ci
		';
		if($wpdb->query($sql) === false)
			return false;

		$sql = '
			alter table '.$wpdb->prefix.'xmt_acc
			change nme nme varchar(100) character set utf8 collate utf8_general_ci not null
		';
		if($wpdb->query($sql) === false)
			return false;

		$sql = '
			alter table '.$wpdb->prefix.'xmt_twt
			change acc_nme acc_nme varchar(100) character set utf8 collate utf8_general_ci not null
		';
		if($wpdb->query($sql) === false)
			return false;

		$sql = '
			alter table '.$wpdb->prefix.'xmt_twt
			change twt_ath twt_ath varchar(100) character set utf8 collate utf8_general_ci not null
		';
		if($wpdb->query($sql) === false)
			return false;

		$sql = '
			alter table '.$wpdb->prefix.'xmt_twt
			change twt twt varchar(255) character set utf8 collate utf8_general_ci not null
		';
		if($wpdb->query($sql) === false)
			return false;

		$sql = '
			alter table '.$wpdb->prefix.'xmt_twt
			change twt_dtp twt_dtp varchar(19) character set utf8 collate utf8_general_ci not null
		';
		if($wpdb->query($sql) === false)
			return false;

		$sql = '
			alter table '.$wpdb->prefix.'xmt_twt
			change twt_typ twt_typ varchar(3) character set utf8 collate utf8_general_ci not null
		';
		if($wpdb->query($sql) === false)
			return false;

		$sql = '
			alter table '.$wpdb->prefix.'xmt_twt
			change twt_src twt_src varchar(255) character set utf8 collate utf8_general_ci not null
		';
		if($wpdb->query($sql) === false)
			return false;

		$sql = '
			alter table '.$wpdb->prefix.'xmt_ath
			change uid uid varchar(100) character set utf8 collate utf8_general_ci not null
		';
		if($wpdb->query($sql) === false)
			return false;

		$sql = '
			alter table '.$wpdb->prefix.'xmt_ath
			change nme nme varchar(100) character set utf8 collate utf8_general_ci not null
		';
		if($wpdb->query($sql) === false)
			return false;

		$sql = '
			alter table '.$wpdb->prefix.'xmt_ath
			change img_url img_url varchar(250) character set utf8 collate utf8_general_ci not null
		';
		if($wpdb->query($sql) === false)
			return false;

		$sql = '
			alter table '.$wpdb->prefix.'xmt_ath
			change dte_upd dte_upd varchar(19) character set utf8 collate utf8_general_ci not null
		';
		if($wpdb->query($sql) === false)
			return false;

		$sql = '
			alter table '.$wpdb->prefix.'xmt_twt 
			add index acc_nme_index (acc_nme)
		';
		if($wpdb->query($sql) === false)
			return false;

		$sql = '
			alter table '.$wpdb->prefix.'xmt_twt 
			add index twt_id_index (twt_id)
		';
		if($wpdb->query($sql) === false)
			return false;

		$sql = '
			alter table '.$wpdb->prefix.'xmt_twt 
			add index twt_index (twt)
		';
		if($wpdb->query($sql) === false)
			return false;

		$sql = '
			alter table '.$wpdb->prefix.'xmt_twt 
			add index twt_dtp_index (twt_dtp)
		';
		if($wpdb->query($sql) === false)
			return false;

		$sql = '
			alter table '.$wpdb->prefix.'xmt_twt 
			add index twt_typ_index (twt_typ)
		';
		if($wpdb->query($sql) === false)
			return false;

		$sql = '
			alter table '.$wpdb->prefix.'xmt_ath 
			add unique uid_unique (uid)
		';
		if($wpdb->query($sql) === false)
			return false;

		$upd = true;

		$ver = '1.0.2';
		update_option('xmt_vsn', $ver);
	}

	if($ver == '1.0.2'){
		$sql = 'alter table '.$wpdb->prefix.'xmt_acc drop `twt_cch`';
		$wpdb->query($sql);

		$sql = 'alter table '.$wpdb->prefix.'xmt_acc drop `twt_cch_dtp`';
		$wpdb->query($sql);

		$sql = 'alter table '.$wpdb->prefix.'xmt_acc drop `prf_cch`';
		$wpdb->query($sql);

		$sql = 'alter table '.$wpdb->prefix.'xmt_acc drop `prf_cch_dtp`';
		$wpdb->query($sql);

		$sql = 'alter table '.$wpdb->prefix.'xmt_twt add unique `acc_nme_twt_id` (`acc_nme`, `twt_id`)';
		$wpdb->query($sql);

		$upd = true;

		$ver = '1.0.3';
		update_option('xmt_vsn', $ver);
	}

	if($upd)
		$upd_res = 1;
	else
		$upd_res = true;
?>