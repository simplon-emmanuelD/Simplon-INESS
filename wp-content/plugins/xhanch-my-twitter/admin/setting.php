<?php
	if(!defined('xmt'))
		exit;
	
	function xmt_setting(){
		global $wpdb;
		global $xmt_acc;
		global $xmt_cfg_def;
				
		$acc_sel = urldecode(xmt_form_get('profile'));
		
		if($acc_sel == ''){
			foreach($xmt_acc as $acc=>$xmt_det){
				$acc_sel = $acc;
				break;
			}
		}
		$twt_str = '';
			
		$arr_header_style = array(
			'' => 'No Header',
			'avatar' => 'Your avatar + display name',
			'default' => 'Elegant Twitter bird + display name',
			'bird_with_text-2' => 'Twitter bird - side view + display name',
			'bird_with_text-3' => 'Twitter bird plays a notebook + display name',
			'bird_with_text-4' => 'Twitter bird with sharp nose + display name',
			'bird_with_text-5' => 'Twitter bird holds a \'Twitter\' board + display name',
			'bird_with_text-6' => 'Twitter bird holds a \'Follow Me\' banner + display name',
			'bird_with_text-7' => 'Twitter bird listens to music + display name',
			'bird_with_text-8' => 'Twitter bird - front view + display name',
			'bird_with_text-9' => 'Twitter bird - side view + display name',
			'bird_with_text-10' => 'Twitter bird with one wing down + display name',
			'bird_with_text-11' => 'Twitter bird stands next to \'Twitter\' board + display name',
			'bird_with_text-12' => 'Cute Twitter bird + display name',
			'bird_with_text-13' => 'Silenced Twitter bird + display name',
			'bird_with_text-14' => 'Twitter bird on a tree branch + display name',
			'bird_with_text-15' => 'Winking Twitter bird  + display name',
			'logo_with_text-1' => 'Twitter logo (Old) 1 + display name',
			'logo_with_text-2' => 'Twitter logo (Old) 2 + display name',
			'logo_with_text-3' => 'Twitter logo (New) 1 + display name',
			'logo_with_text_36-1' => 'Twitter logo - med (New) 1 + display name',
			'logo_with_text_36-2' => 'Twitter logo - med (New) 2 + display name',
			'logo_with_text_36-3' => 'Twitter logo - med (New) 3 + display name',
			'header_image-1' => 'Full Twitter logo (New) 1',
			'header_image_27-1' => 'Follow Me Button 1',
			'header_image_27-2' => 'Follow Me Button 2',
			'header_image_27-3' => 'Follow Me Button 3',
		);
		
		$arr_dt_format = array(
			'd/m/Y' => 'dd/mm/yyyy',	
			'd.m.y' => 'dd.mm.yy',	
			'm/d/Y' => 'mm/dd/yyyy',
			'Y-m-d' => 'yyyy-mm-dd',
			'M d, Y' => 'mmm dd, yyyy',
			'd-F-Y' => 'dd-'.__('month', 'xmt').'-yyyy',
			'l, F d, Y' => ''.__('dayname', 'xmt').', '.__('month', 'xmt').' dd, yyyy',
		);
		
		$arr_tm_format = array(		
			'H:i' => 'hh:mm',
			'H:i:s' => 'hh:mm:ss',
			'h:i a' => 'hh:mm am/pm',
		);

		$arr_date_format = array();
		foreach($arr_dt_format as $dt_f=>$dt_v){
			$arr_date_format[$dt_f] = $dt_v;
			foreach($arr_tm_format as $tm_f=>$tm_v)
				$arr_date_format[$dt_f.' '.$tm_f] = $dt_v.' '.$tm_v;							
		}
		$arr_date_format['span'] = '? period ago';		

		$arr_ord = array(
			'lto' => 'Latest to oldest',
			'otl' => 'Oldest to latest',
		);

		if(!empty($_POST) && !wp_verify_nonce($_POST['vrf_xmt_cfg_frm'],'xmt_cfg_frm')){
			echo '<div id="message" class="updated fade"><p>'.__('Invalid form verification token.', 'xmt').'</p></div>'; 
			exit;
		}
				
		if(isset($_POST['cmd_xmt_crt_prf']) || isset($_POST['cmd_xmt_dpl_prf'])){
			$acc_nme = strtolower(xmt_form_post('txt_xmt_acc_nme'));
			$valid_chars = array(
				'a','b','c','d','e','f','g','h','i','j',
				'k','l','m','n','o','p','q','r','s','t',
				'u','v','w','x','y','z',
				'0','1','2','3','4','5','6','7','8','9'
			);
		
			if(empty($acc_nme))
				echo '<div id="message" class="updated fade"><p>'.__('Profile name is empty', 'xmt').'</p></div>'; 
			elseif($acc_nme == 'new')
				echo '<div id="message" class="updated fade"><p>'.__('Profile name cannot be <b>new</b>', 'xmt').'</p></div>';
			elseif(array_key_exists($acc_nme, $xmt_acc))
				echo '<div id="message" class="updated fade"><p>'.__('Profile already exists', 'xmt').'</p></div>';
			else{
				$chars = str_split($acc_nme);
				$valid = true;
				foreach($chars as $key){
					if(!in_array($key, $valid_chars)){		
						$valid = false;
						echo '<div id="message" class="updated fade"><p>'.__('Profile name must contain A to Z and 0 to 9', 'xmt').'</p></div>';
						break;
					}
				}
				if($valid){
					if(isset($_POST['cmd_xmt_dpl_prf'])){
						xmt_acc_add($acc_nme, $xmt_acc[$acc_sel]['cfg']);		
						echo '<div id="message" class="updated fade"><p>'.__('The profile <b>'.$acc_sel.'</b> has been duplicated as <b>'.$acc_nme.'</b>', 'xmt').'</p></div>';	
					}else{
						xmt_acc_add($acc_nme, $xmt_cfg_def);
						echo '<div id="message" class="updated fade"><p>'.__('A new profile has been created', 'xmt').'</p></div>';			
					}
				}
			}
		}elseif(isset($_POST['cmd_xmt_delete_profile'])){
			xmt_acc_del($acc_sel);
			echo '<div id="message" class="updated fade"><p>Profile <b>'.htmlspecialchars($acc_sel).'</b> has been deleted</p></div>';		
		}elseif(isset($_POST['cmd_xmt_get_tweets'])){
			xmt_twt_imp($acc_sel, true);
			echo '<div id="message" class="updated fade"><p>Tweets updated</p></div>';			
		}elseif(isset($_POST['cmd_xmt_delete_tweets'])){
			$sql = '
				delete from '.$wpdb->prefix.'xmt_twt
				where acc_nme = '.xmt_sql_str($acc_sel).'
			';
			$wpdb->query($sql);
			echo '<div id="message" class="updated fade"><p>All stored tweets for <b>'.htmlspecialchars($acc_sel).'</b> has been deleted</p></div>';
			xmt_twt_cch_rst($acc_sel);
		}elseif(isset($_POST['cmd_xmt_disconnect'])){
			$cfg = $xmt_acc[$acc_sel]['cfg'];
			$cfg['oah_use'] = 0;					
			$cfg['csm_key'] = '';
			$cfg['csm_sct'] = '';							
			$cfg['oah_tkn'] = '';
			$cfg['oah_sct'] = '';						
			$cfg['tmp_oah_tkn'] = '';
			$cfg['tmp_oah_sct'] = '';			
			xmt_acc_cfg_upd($acc_sel, $cfg);
			echo '<div id="message" class="updated fade"><p>'.__('This profile has been disconnected with Twitter', 'xmt').'</p></div>';				
		}elseif(isset($_POST['cmd_xmt_clear_cache'])){
			xmt_twt_cch_rst($acc_sel);
			xmt_prf_cch_rst($acc_sel);
			echo '<div id="message" class="updated fade"><p>'.__('Cache has been cleared', 'xmt').'</p></div>';				
		}elseif(isset($_POST['cmd_xmt_update_profile'])){
			$cfg = $xmt_acc[$acc_sel]['cfg'];
			$tmp_cfg = array(
				'ttl' => xmt_form_post('txt_xmt_ttl'),
				'nme' => xmt_form_post('txt_xmt_nme'),
				'lnk_ttl' => intval(xmt_form_post('chk_xmt_lnk_ttl')),
				'hdr_sty' => xmt_form_post('cbo_xmt_hdr_sty'),
				'cst_hdr_txt' => xmt_form_post('txa_xmt_hdr_txt'),
				'cst_ftr_txt' => xmt_form_post('txa_xmt_ftr_txt'),
				'twt_usr_nme' => xmt_form_post('txt_xmt_twt_usr_nme'),
				'oah_use' => $cfg['oah_use'],
				'csm_key' => trim($cfg['csm_key']),
				'csm_sct' => trim($cfg['csm_sct']),
				'oah_tkn' => trim($cfg['oah_tkn']),
				'oah_sct' => trim($cfg['oah_sct']),
				'ord' => xmt_form_post('cbo_xmt_ord'),	
				'cnt' => intval(xmt_form_post('int_xmt_cnt')),
				'trc_len' => intval(xmt_form_post('int_xmt_trc_len')),
				'trc_chr' => xmt_form_post('txt_xmt_trc_chr'),
				'gmt_add' => floatval(xmt_form_post('int_xmt_gmt_add')),
				'dtm_fmt' => xmt_form_post('txt_xmt_dtm_fmt'),
				'twt_lyt' => xmt_form_post('txa_xmt_twt_lyt'),
				'shw_hrl' => intval(xmt_form_post('chk_xmt_shw_hrl')),
				'shw_pst_frm' => intval(xmt_form_post('chk_xmt_shw_pst_frm')),
				'shw_org_rtw' => intval(xmt_form_post('chk_xmt_shw_org_rtw')),
				'twt_new_pst' => intval(xmt_form_post('chk_xmt_twt_new_pst')),
				'twt_new_pst_lyt' => xmt_form_post('txa_xmt_twt_new_pst_lyt'),
				'twt_upd_pst' => intval(xmt_form_post('chk_xmt_twt_upd_pst')),
				'twt_upd_pst_lyt' => xmt_form_post('txa_xmt_twt_upd_pst_lyt'),
				'twt_new_pag' => intval(xmt_form_post('chk_xmt_twt_new_pag')),
				'twt_new_pag_lyt' => xmt_form_post('txa_xmt_twt_new_pag_lyt'),
				'twt_upd_pag' => intval(xmt_form_post('chk_xmt_twt_upd_pag')),
				'twt_upd_pag_lyt' => xmt_form_post('txa_xmt_twt_upd_pag_lyt'),
				'clc_usr_tag' => intval(xmt_form_post('chk_xmt_clc_usr_tag')),
				'clc_hsh_tag' => intval(xmt_form_post('chk_xmt_clc_hsh_tag')),
				'shw_hsh_tag' => intval(xmt_form_post('chk_xmt_shw_hsh_tag')),
				'clc_url' => intval(xmt_form_post('chk_xmt_clc_url')),
				'url_lyt' => xmt_form_post('txt_xmt_url_lyt'),
				'avt_shw' => intval(xmt_form_post('chk_xmt_avt_shw')),
				'avt_szw' => intval(xmt_form_post('int_xmt_avt_szw')),
				'avt_szh' => intval(xmt_form_post('int_xmt_avt_szh')),
				'inc_rpl_fru' => intval(xmt_form_post('chk_xmt_inc_rpl_fru')),
				'inc_rpl_tou' => intval(xmt_form_post('chk_xmt_inc_rpl_tou')),
				'inc_rtw' => intval(xmt_form_post('chk_xmt_inc_rtw')),
				'inc_drc_msg' => intval(xmt_form_post('chk_xmt_inc_drc_msg')),
				'ctn_kwd' => xmt_form_post('txt_xmt_ctn_kwd'),
				'ctn_kwd_any' => xmt_form_post('txt_xmt_ctn_kwd_any'),
				'ecl_kwd' => xmt_form_post('txt_xmt_ecl_kwd'),
				'ecl_kwd_any' => xmt_form_post('txt_xmt_ecl_kwd_any'),
				'sql_crt' => xmt_form_post('txt_xmt_sql_crt'),
				'cch_enb' => intval(xmt_form_post('chk_xmt_cch_enb')),
				'cch_exp' => intval(xmt_form_post('int_xmt_cch_exp')),	
				'imp_itv' => intval(xmt_form_post('int_xmt_imp_itv')),	
				'thm' => xmt_form_post('cbo_xmt_thm'),
				'cst_css' => xmt_form_post('txa_xmt_cst_css'),
				'shw_crd' => (intval(xmt_form_post('chk_xmt_shw_crd'))?1:0),
				'cvr_sml' => intval(xmt_form_post('chk_xmt_cvr_sml')),
				'lnk_new_tab' => intval(xmt_form_post('chk_xmt_lnk_new_tab')),
				'tmp_oah_tkn' => '',
				'tmp_oah_sct' => ''
			);

			if(isset($_POST['txt_xmt_csm_key']))
				$tmp_cfg['csm_key'] = xmt_form_post('txt_xmt_csm_key');
			if(isset($_POST['txt_xmt_csm_sct']))
				$tmp_cfg['csm_sct'] = xmt_form_post('txt_xmt_csm_sct');
			if(isset($_POST['txt_xmt_oah_tkn']))
				$tmp_cfg['oah_tkn'] = xmt_form_post('txt_xmt_oah_tkn');
			if(isset($_POST['txt_xmt_oah_sct']))
				$tmp_cfg['oah_sct'] = xmt_form_post('txt_xmt_oah_sct');

			$path = xmt_base_dir.'/theme';		
			$dir = dir($path);	
			while($thm = $dir->read()){
				if($thm == '.' || $thm == '..' || substr($thm,-5) == '.html')
					continue;
				$target = $path.'/'.$thm.'/conf-set.php';
				$tpl_cfg = array();
				if(file_exists($target))
					include $target;
				$tmp_cfg = array_merge($tmp_cfg, $tpl_cfg);
			}
			$dir->close();

			xmt_acc_cfg_upd($acc_sel, $tmp_cfg);
			xmt_twt_cch_rst($acc_sel);
			xmt_prf_cch_rst($acc_sel);
			echo '<div id="message" class="updated fade"><p>'.__('Configuration Updated', 'xmt').'</p></div>';
		}elseif(isset($_POST['cmd_xmt_dtb_ver_upd'])){
			update_option('xmt_vsn', xmt_form_post('txt_xmt_dtb_ver'));
			echo '<div id="message" class="updated fade"><p>Database version has been set to <b>'.htmlspecialchars(xmt_form_post('txt_xmt_dtb_ver')).'</b></p></div>';	
		}elseif(isset($_POST['cmd_xmt_rit'])){
			$sql = 'drop table if exists '.$wpdb->prefix.'xmt';
			$wpdb->query($sql);
			$sql = 'drop table if exists '.$wpdb->prefix.'xmt_acc';
			$wpdb->query($sql);
			$sql = 'drop table if exists '.$wpdb->prefix.'xmt_ath';
			$wpdb->query($sql);
			$sql = 'drop table if exists '.$wpdb->prefix.'xmt_twt';
			$wpdb->query($sql);
			$sql = '
				delete from '.$wpdb->prefix.'options 
				where option_name = \'xmt_vsn\';
			';
			$wpdb->query($sql);

			$path = xmt_cch_dir;		
			$dir = dir($path);
			while($file = $dir->read()){
				if($file == '.' || $file == '..')
					continue;
				$target = $path.'/'.$file;			
				if(!is_dir($target))
					 @unlink($target);
			}
			$dir->close();

			$xmt_acc = array();
			echo '<div id="message" class="updated fade"><p>Xhanch - My Twitter has been reinstalled</p></div>';
		}elseif(isset($_POST['cmd_xmt_twt_pst'])){
			$twt_str = trim(xmt_form_post('txa_xmt_twt_str'));
			$msg = '';

			if($twt_str == '')
				$msg = 'Your tweet is empty!';
			if(strlen($twt_str) > 140)
				$msg = 'Your tweet exceeds 140 characters!';
			if($msg == ''){			
				xmt_twt_oah_twt_pst($acc_sel, $twt_str);
				$msg = 'Your tweet has been posted';
				$xmt_acc[$acc_sel]['las_twt_imp_dtp'] = 0;
				xmt_twt_cch_rst($acc_sel);
				xmt_twt_imp($acc_sel);
			}

			if($msg)
				echo '<div id="message" class="updated fade"><p>'.__($msg, 'xmt').'</p></div>';	
		}elseif(isset($_POST['cmd_xmt_import_profile'])){
			$xmt_fle_nme = $_FILES['fil_xmt_prf_fle']['tmp_name'];	
			$xmt_dat = unserialize(base64_decode(file_get_contents($xmt_fle_nme)));
			if($xmt_dat === false)
				echo '<div id="message" class="updated fade"><p>'.__('Invalid XMT profile file', 'xmt').'</p></div>';	
			else{
				xmt_acc_del($xmt_dat['nme']);
				xmt_acc_add($xmt_dat['nme'], $xmt_dat['cfg']);	
				echo '<div id="message" class="updated fade"><p>'.__('XMT profile (<b>'.$xmt_dat['nme'].'</b>) has been successfully imported', 'xmt').'</p></div>';
			}
		}
				
		ksort($xmt_acc);
		
		if($acc_sel == ''){
			foreach($xmt_acc as $acc=>$xmt_det){
				$acc_sel = $acc;
				break;
			}
		}
?>
		<style type="text/css">
			table, td{font-family:Arial;font-size:12px}
			tr{height:22px}
			ul li{line-height:2px}	
			.clear{clear:both}
		</style>
		<script type="text/javascript">
			function show_spoiler(obj){
				var inner = obj.parentNode.getElementsByTagName("div")[0];
				if (inner.style.display == "none")
					inner.style.display = "";
				else
					inner.style.display = "none";
			}
			function show_more(obj_nm){
				var obj = document.getElementById(obj_nm);
				if (obj.style.display == "none")
					obj.style.display = "";
				else
					obj.style.display = "none";
			}
			function show_mode_opt(){
				var obj = document.getElementById("cbo_xmt_thm");				
				var md_sel = document.getElementById("sct_md_" + obj.value);
				
				<?php 
					$path = xmt_base_dir.'/theme';		
					$dir = dir($path);	
					while($thm = $dir->read()){
						if($thm == '.' || $thm == '..' || substr($thm,-5) == '.html')
							continue;
						echo 'document.getElementById("sct_md_'.$thm.'").style.display = "none";';										
					}
					$dir->close();
				?>				
				
				md_sel.style.display = "";	
			}
			function set_dt_fmt(fmt){
				var obj = document.getElementById("txt_xmt_dtm_fmt");				
				obj.value = fmt;
			}
    	</script>
		<div class="wrap">
			<h2><?php echo __('Xhanch - My Twitter - Configuration', 'xmt'); ?></h2>
			<br/>

			<iframe src="http://ads.xhanch.com" style="width:468px;height:60px;
			float:left;border:1px solid #cacaca" scrolling="no" allowTransparency="true"></iframe>	

            <div style="float:right;width:400px">
				<div style="float:right;line-height:21px">
					<b><?php echo __('Do you like this Xhanch - My Twitter? ', 'xmt'); ?></b> <iframe src="http://www.facebook.com/plugins/like.php?href=http%3A%2F%2Fxhanch.com%2Fwp-plugin-my-twitter%2F&amp;layout=button_count&amp;show_faces=false&amp;width=450&amp;action=like&amp;font=arial&amp;colorscheme=light&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:100px; height:21px; margin:0 0 0 10px; float:right;padding:1px" allowTransparency="true"></iframe>           
				</div>
				<div class="clear"></div>
				<div style="float:right;line-height:21px">
					<b><?php echo __('Do you like our service and support? ', 'xmt'); ?></b> <iframe src="http://www.facebook.com/plugins/like.php?href=http%3A%2F%2Fwww.facebook.com%2Fpages%2FXhanch-Studio%2F146245898739871&amp;layout=button_count&amp;show_faces=false&amp;width=450&amp;action=like&amp;font=arial&amp;colorscheme=light&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:100px; height:21px; margin:0 0 0 10px; float:right;padding:1px" allowTransparency="true"></iframe>   
				</div>
				<div class="clear"></div>
			</div>
            <div class="clear"></div>
			<br/>
            <?php xmt_check(); ?>
			
			<br/>
			<div id="icon-themes" class="icon32"><br /></div>
			<h2>
				<?php foreach($xmt_acc as $acc=>$acc_det){ ?>
					<a href="admin.php?page=xhanch-my-twitter/admin/setting.php&profile=<?php echo urlencode($acc); ?>" class="nav-tab<?php echo ($acc==$acc_sel?' nav-tab-active':''); ?>"><?php echo ucwords(htmlspecialchars($acc)); ?></a>																	
				<?php } ?>
				<a href="admin.php?page=xhanch-my-twitter/admin/setting.php&profile=new" class="nav-tab<?php echo ('new'==$acc_sel?' nav-tab-active':''); ?>">+</a>	
			</h2>
			<div class="clear" style="border-top:1px solid #CCC;margin-top:-3px;"/><br/>			
					
			<?php 
				if(array_key_exists($acc_sel, $xmt_acc)){ 
					$cfg = $xmt_acc[$acc_sel]['cfg'];
					
					if($cfg['csm_key'] != '' && $cfg['csm_sct'] != '' && $cfg['oah_tkn'] != '' && $cfg['oah_sct'] != ''){
						$twt_prf = xmt_twt_oah_prf_get($acc_sel);				
						if($twt_prf !== false){
							$cfg['twt_usr_nme'] = $twt_prf['scr_nme'];
							$cfg['oah_use'] = 1;
							xmt_acc_cfg_upd($acc_sel, $cfg);
						}else{
							$cfg['oah_use'] = 0;
							xmt_acc_cfg_upd($acc_sel, $cfg);
						}
					}else{
						$cfg['oah_use'] = 0;
						xmt_acc_cfg_upd($acc_sel, $cfg);
					}							
			?>		
				<form action="" method="post" id="frm_config" enctype="multipart/form-data">
					<i><small>Note: <a href="#guide"><?php echo __('Click here for a complete explaination about these configurations fields', 'xmt'); ?></a></small></i><br/>
					<br/>				
                        	
                    <b><?php echo __('Authentication', 'xmt'); ?></b><br/><br/>
                    <?php if(!$cfg['oah_use']){ ?>
						<?php echo __('To make this plugin work, you will need to create a new Twitter application.', 'xmt'); ?><br/><br/>
						<div id="sct_adv_ftr">
							<?php echo __('To create a new Twitter application, just follow these steps:', 'xmt'); ?><br/>
							- Create a new Twitter application <a href="http://dev.twitter.com/apps/new" title="Twitter App Registration" target="_blank">via this page</a><br/>
							- If you're not logged in, you can use your Twitter username and password<br/>
							- Some details you need to know when filling the form:<br/>
							&nbsp;&nbsp;+ Application Name: Just give a name.<br/>
							&nbsp;&nbsp;+ Callback URL: <strong><?php echo get_bloginfo('url'); ?></strong><br/>
							&nbsp;&nbsp;+ Fill in the remaining details as you wish<br/>
							&nbsp;&nbsp;+ Submit<br/>
							- When your application is successfully created, you will see your application's detail page<br/>
							&nbsp;&nbsp;+ Click <strong>Settings</strong> tab, change the <strong>Access</strong> to <strong>Read &amp; Write</strong>.<br/>
							&nbsp;&nbsp;+ Submit to update<br/>
							<br/>

							Go back to <strong>Details</strong> tab.<br/>
							On that page, find your <b>Consumer key</b> and <b>Consumer secret</b>.<br/>
							<table cellpadding="0" cellspacing="0">
								<tr>
									<td width="150px"><?php echo __('Consumer key', 'xmt'); ?></td>
									<td width="200px"><input type="text" value="<?php echo htmlspecialchars($cfg['csm_key']); ?>" id="txt_xmt_csm_key" name="txt_xmt_csm_key" style="width:100%"/></td>
									<td width="10px"></td>
									<td width="150px"><?php echo __('Consumer secret', 'xmt'); ?></td>
									<td width="200px"><input type="text" value="<?php echo htmlspecialchars($cfg['csm_sct']); ?>" id="txt_xmt_csm_sct" name="txt_xmt_csm_sct" style="width:100%"/></td>
								</tr>
							</table><br/>
							Then, at the bottom, click <strong>Create my access token</strong> button.<br/>
							You will get <b>Access Token</b> and <b>Access Token Secret</b>.<br/>
							<table cellpadding="0" cellspacing="0">
								<tr>
									<td width="150px"><?php echo __('Access Token', 'xmt'); ?></td>
									<td width="200px"><input type="text" value="<?php echo htmlspecialchars($cfg['oah_tkn']); ?>" id="txt_xmt_oah_tkn" name="txt_xmt_oah_tkn" style="width:100%"/></td>
									<td width="10px"></td>
									<td width="150px"><?php echo __('Access Token Secret', 'xmt'); ?></td>
									<td width="200px"><input type="text" value="<?php echo htmlspecialchars($cfg['oah_sct']); ?>" id="txt_xmt_oah_sct" name="txt_xmt_oah_sct" style="width:100%"/></td>
								</tr>
							</table>
							<br/><br/>
						</div>
                   	<?php }else{ ?>
                    	<?php echo __('You are currently connected as', 'xmt'); ?> <b><?php echo $twt_prf['nme']; ?></b> (<b><?php echo $twt_prf['scr_nme']; ?></b>)<br/><br/>
						<b><?php echo __('Post a tweet', 'xmt'); ?></b><br/>
						<br/>
						<textarea name="txa_xmt_twt_str" style="width:710px;height:40px"><?php echo htmlspecialchars($twt_str); ?></textarea><br/>
						<input type="submit" name="cmd_xmt_twt_pst" value="<?php echo __('Post', 'xmt'); ?>"/>
						<div class="clear"></div>
						<br/>
                   	<?php } ?>
                   	
					<b><?php echo __('Widget Setting', 'xmt'); ?></b><br/>
					<br/>
					<table cellpadding="0" cellspacing="0">
						<tr>
							<td width="150px"><?php echo __('Title', 'xmt'); ?></td>
							<td width="200px"><input type="text" id="txt_xmt_ttl" name="txt_xmt_ttl" value="<?php echo htmlspecialchars($cfg['ttl']); ?>" style="width:100%"/></td>
							<td width="10px"></td>
							<td width="150px"><?php echo __('Name', 'xmt'); ?></td>
							<td width="200px"><input type="text" id="txt_xmt_nme" name="txt_xmt_nme" value="<?php echo htmlspecialchars($cfg['nme']); ?>" style="width:100%"/></td>
						</tr>
						<tr>
							<td><?php echo __('Header style', 'xmt'); ?></td>
							<td>
								<select id="cbo_xmt_hdr_sty" name="cbo_xmt_hdr_sty" style="width:100%">
									<?php foreach($arr_header_style as $key=>$row){ ?>
										<option value="<?php echo $key; ?>" <?php echo ($key==htmlspecialchars($cfg['hdr_sty']))?'selected="selected"':''; ?>><?php echo __($row, 'xmt'); ?></option>
									<?php } ?>
								</select>
							</td>
							<td></td>
							<td><?php echo __('Turn title to link?', 'xmt'); ?></td>
							<td><input type="checkbox" id="chk_xmt_lnk_ttl" name="chk_xmt_lnk_ttl" value="1" <?php echo ($cfg['lnk_ttl']?'checked="checked"':''); ?>/></td>
						</tr>
						<tr>
							<td colspan="5">
								<?php echo __('Header text', 'xmt'); ?> (<a href="javascript:show_more('sct_text_var')"><?php echo __('show/hide available variables', 'xmt'); ?></a>)
								<textarea id="txa_xmt_hdr_txt" name="txa_xmt_hdr_txt" style="width:100%;height:40px"><?php echo htmlspecialchars($cfg['cst_hdr_txt']); ?></textarea>
								<br/>
				
								<?php echo __('', 'xmt'); ?>Footer text (<a href="javascript:show_more('sct_text_var')"><?php echo __('show/hide available variables', 'xmt'); ?></a>)
								<textarea id="txa_xmt_ftr_txt" name="txa_xmt_ftr_txt" style="width:100%;height:40px"><?php echo htmlspecialchars($cfg['cst_ftr_txt']); ?></textarea>
								<br/>
                                
                                <div id="sct_text_var" style="display:none;">		
                                    <small><i><?php echo __('Available variables for footer and header text', 'xmt'); ?></i></small>
                                    <ul>
                                        <li><small><b>@avatar</b>: <?php echo __('display URL of your Twitter avatar', 'xmt'); ?></small></li>
                                        <li><small><b>@name</b>: <?php echo __('display your full name on Twitter', 'xmt'); ?></small></li>
                                        <li><small><b>@screen_name</b>: <?php echo __('display your screen name on Twitter', 'xmt'); ?></small></li>
                                        <li><small><b>@followers_count</b>: <?php echo __('display a number of your followers', 'xmt'); ?></small></li>
                                        <li><small><b>@statuses_count</b>: <?php echo __('display a number of your total statuses/tweets', 'xmt'); ?></small></li>
                                        <li><small><b>@favourites_count</b>: <?php echo __('display a number of your favourites', 'xmt'); ?></small></li>
                                        <li><small><b>@friends_count</b>: <?php echo __('display a number of your friends', 'xmt'); ?></small></li>
                                    </ul>
                                </div>
												
							</td>
						</tr>
					</table><br/>
					
					<b><?php echo __('Tweet Settings', 'xmt'); ?></b><br/>
					<br/>
					<table cellpadding="0" cellspacing="0">
						<tr>
							<td width="150px"><?php echo __('Username', 'xmt'); ?></td>
							<td width="200px"><input type="text" <?php echo ($cfg['oah_use']?'value="'.$twt_prf['scr_nme'].'" disabled="disabled"':'value="'.htmlspecialchars($cfg['twt_usr_nme']).'"'); ?> id="txt_xmt_twt_usr_nme" name="txt_xmt_twt_usr_nme" style="width:100%"/></td>
							<td width="10px"></td>
							<td width="150px"></td>
							<td width="200px"></td>
						</tr>
						<tr>
							<td><?php echo __('Tweet order', 'xmt'); ?></td>
							<td>
								<select id="cbo_xmt_ord" name="cbo_xmt_ord" style="width:100%">
									<?php foreach($arr_ord as $key=>$row){ ?>
										<option value="<?php echo $key; ?>" <?php echo ($key==htmlspecialchars($cfg['ord']))?'selected="selected"':''; ?>><?php echo __($row, 'xmt'); ?></option>
									<?php } ?>
								</select>
							</td>
							<td></td>
							<td><?php echo __('# Latest tweets', 'xmt'); ?></td>
							<td><input type="text" id="int_xmt_cnt" name="int_xmt_cnt" value="<?php echo htmlspecialchars($cfg['cnt']); ?>" size="5"  maxlength="3"/></td>
						</tr>
						<tr>
							<td><?php echo __('Inc. replies to you?', 'xmt'); ?></td>
							<td><input type="checkbox" id="chk_xmt_inc_rpl_tou" name="chk_xmt_inc_rpl_tou" value="1" <?php echo ($cfg['inc_rpl_tou']?'checked="checked"':''); ?>/></td>
							<td></td>
							<td><?php echo __('Inc. replies from you?', 'xmt'); ?></td>
							<td><input type="checkbox" id="chk_xmt_inc_rpl_fru" name="chk_xmt_inc_rpl_fru" value="1" <?php echo ($cfg['inc_rpl_fru']?'checked="checked"':''); ?>/></td>
						</tr>
						<tr>
							<td><?php echo __('Inc. retweet?', 'xmt'); ?></td>
							<td><input type="checkbox" id="chk_xmt_inc_rtw" name="chk_xmt_inc_rtw" value="1" <?php echo ($cfg['inc_rtw']?'checked="checked"':''); ?>/></td>
							<td></td>
							<td><?php echo __('Show origin retweet?', 'xmt'); ?></td>
							<td><input type="checkbox" id="chk_xmt_shw_org_rtw" name="chk_xmt_shw_org_rtw" value="1" <?php echo ($cfg['shw_org_rtw']?'checked="checked"':''); ?>/></td>
						</tr>
						<tr>
							<td><?php echo __('Date format', 'xmt'); ?> (<a href="javascript:show_more('sct_twt_dt_fmt')"><?php echo __('more', 'xmt'); ?></a>)</td>
							<td><input type="text" value="<?php echo htmlspecialchars($cfg['dtm_fmt']); ?>" id="txt_xmt_dtm_fmt" name="txt_xmt_dtm_fmt" style="width:100%"/></td>
							<td></td>
							<td><?php echo __('GMT add (in minutes)', 'xmt'); ?></td>
							<td><input type="text" id="int_xmt_gmt_add" name="int_xmt_gmt_add" value="<?php echo $cfg['gmt_add']; ?>" size="5"  maxlength="4"/></td>
						</tr>
						<tr id="sct_twt_dt_fmt" style="display:none;">
							<td colspan="5">  
                                <small><i><?php echo __('Commonly used date formats', 'xmt'); ?></i></small>
                                <ul>
                                    <?php foreach($arr_date_format as $fmt_val=>$fmt_ex){ ?>
                                        <li><small><a href="javascript:set_dt_fmt('<?php echo $fmt_val; ?>')" style="text-decoration:none">[Use This]</a> <b><?php echo $fmt_val; ?></b>: <?php echo __($fmt_ex, 'xmt'); ?></small></li>
                                    <?php } ?>
                                  	<li><small><a href="http://xhanch.com/php-script-formatting-date-and-time/" target="_blank"><?php echo __('Click Here to see more explainations about date and time formatting', 'xmt'); ?></a></small></li>
                                </ul>
                         	</td>
                      	</tr>
						<tr>
							<td colspan="5">
                            	<?php echo __('Tweet layout', 'xmt'); ?> (<a href="javascript:show_more('sct_twt_layout_var')"><?php echo __('show/hide available variables', 'xmt'); ?></a>)<br/>
								<textarea id="txa_xmt_twt_lyt" name="txa_xmt_twt_lyt" style="width:100%;height:40px"><?php echo htmlspecialchars($cfg['twt_lyt']); ?></textarea><br/>
                                <div id="sct_twt_layout_var" style="display:none;">		
                                    <small><i><?php echo __('Available variables for tweet layout', 'xmt'); ?></i></small>
                                    <ul>
                                        <li><small><b>@screen_name</b>: <?php echo __('display the screen name who posts the tweet (Link Mode)', 'xmt'); ?></small></li>
                                        <li><small><b>@screen_name_plain</b>: <?php echo __('display the screen name who posts the tweet', 'xmt'); ?></small></li>
                                        <li><small><b>@name</b>: <?php echo __('display the full name who posts the tweet (Link Mode)', 'xmt'); ?></small></li>
                                        <li><small><b>@name_plain</b>: <?php echo __('display the full name who posts the tweet', 'xmt'); ?></small></li>
                                        <li><small><b>@tweet</b>: <?php echo __('content of the tweet', 'xmt'); ?></small></li>
                                        <li><small><b>@date</b>: <?php echo __('formatted publish date time of a tweet', 'xmt'); ?></small></li>
                                        <li><small><b>@source</b>: <?php echo __('display how/where the tweet is posted', 'xmt'); ?></small></li>
                                        <li><small><b>@reply_url</b>: <?php echo __('URL to reply a status', 'xmt'); ?></small></li>
                                        <li><small><b>@reply_link</b>: <?php echo __('Link to reply a status', 'xmt'); ?></small></li>
                                        <li><small><b>@retweet_url</b>: <?php echo __('URL to retweet a status', 'xmt'); ?></small></li>
                                        <li><small><b>@retweet_link</b>: <?php echo __('Link to retweet a status', 'xmt'); ?></small></li>
                                        <li><small><b>@status_url</b>: <?php echo __('URL to view the status on Twitter page', 'xmt'); ?></small></li>
                                    </ul>
                                </div>
                                
							</td>
						</tr>
						<tr>
							<td><?php echo __('Clickable URL?', 'xmt'); ?></td>
							<td><input type="checkbox" id="chk_xmt_clc_url" name="chk_xmt_clc_url" value="1" <?php echo ($cfg['clc_url']?'checked="checked"':''); ?>/></td>
							<td></td>
							<td><?php echo __('Display URL as', 'xmt'); ?></td>
							<td><input type="text" id="txt_xmt_url_lyt" name="txt_xmt_url_lyt" value="<?php echo $cfg['url_lyt']; ?>"/></td>
						</tr>
						<tr>
							<td><?php echo __('Clickable hash tag?', 'xmt'); ?></td>
							<td><input type="checkbox" id="chk_xmt_clc_hsh_tag" name="chk_xmt_clc_hsh_tag" value="1" <?php echo ($cfg['clc_hsh_tag']?'checked="checked"':''); ?>/></td>
							<td></td>
							<td><?php echo __('Show hash tag?', 'xmt'); ?></td>
							<td><input type="checkbox" id="chk_xmt_shw_hsh_tag" name="chk_xmt_shw_hsh_tag" value="1" <?php echo ($cfg['shw_hsh_tag']?'checked="checked"':''); ?>/></td>
						</tr>
						<tr>
							<td><?php echo __('Clickable user tag?', 'xmt'); ?></td>
							<td><input type="checkbox" id="chk_xmt_clc_usr_tag" name="chk_xmt_clc_usr_tag" value="1" <?php echo ($cfg['clc_usr_tag']?'checked="checked"':''); ?>/></td>
							<td></td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td><?php echo __('Show divider line?', 'xmt'); ?></td>
							<td><input type="checkbox" id="chk_xmt_shw_hrl" name="chk_xmt_shw_hrl" value="1" <?php echo ($cfg['shw_hrl']?'checked="checked"':''); ?>/></td>
                            <td></td>
                            <td></td>
                            <td></td>
						</tr>
						<tr>
							<td><?php echo __('Show avatar?', 'xmt'); ?></td>
							<td><input type="checkbox" id="chk_xmt_avt_shw" name="chk_xmt_avt_shw" value="1" <?php echo ($cfg['avt_shw']?'checked="checked"':''); ?>/></td>
							<td></td>
							<td><?php echo __('Avatar size', 'xmt'); ?></td>
							<td>
								W: <input type="text" id="int_xmt_avt_szw" name="int_xmt_avt_szw" value="<?php echo $cfg['avt_szw']; ?>" size="5" maxlength="3"/> px; 
								H:	<input type="text" id="int_xmt_avt_szh" name="int_xmt_avt_szh" value="<?php echo $cfg['avt_szh']; ?>" size="5" maxlength="3"/> px
							</td>
						</tr>			
						<tr>
							<td><?php echo __('Enable cache?', 'xmt'); ?></td>
							<td><input type="checkbox" id="chk_xmt_cch_enb" name="chk_xmt_cch_enb" value="1" <?php echo ($cfg['cch_enb']?'checked="checked"':''); ?>/></td>
							<td></td>
							<td><?php echo __('Cache expiry', 'xmt'); ?></td>
							<td><input type="text" id="int_xmt_cch_exp" name="int_xmt_cch_exp" value="<?php echo $cfg['cch_exp']; ?>" size="5"  maxlength="3"/> minute(s)</td>
						</tr>				
						<tr>
							<td><?php echo __('Import tweets every', 'xmt'); ?></td>
							<td><input type="text" id="int_xmt_imp_itv" name="int_xmt_imp_itv" value="<?php echo $cfg['imp_itv']; ?>" size="5"  maxlength="5"/> minute(s)</td>
						</tr>		
						<tr>
							<td><?php echo __('Truncate tweet after', 'xmt'); ?></td>
							<td><input type="text" id="int_xmt_trc_len" name="int_xmt_trc_len" value="<?php echo $cfg['trc_len']; ?>" size="5" maxlength="3"/> character(s)</td>
							<td></td>
							<td><?php echo __('Ellipsis', 'xmt'); ?></td>
							<td><input type="text" id="txt_xmt_trc_chr" name="txt_xmt_trc_chr" value="<?php echo $cfg['trc_chr']; ?>" style="width:100%"/></td>
						</tr>						
						<tr>
							<td colspan="5">
								<?php echo __('Show tweets that contain all of these words', 'xmt'); ?><br/>
								<input type="text" id="txt_xmt_ctn_kwd" name="txt_xmt_ctn_kwd" value="<?php echo $cfg['ctn_kwd']; ?>" style="width:100%"/><br/>
								<small><i>Note: Separate the words with comma (,)</i></small>
							</td>
						</tr>						
						<tr>
							<td colspan="5">
								<?php echo __('Show tweets that contain any of these words', 'xmt'); ?><br/>
								<input type="text" id="txt_xmt_ctn_kwd_any" name="txt_xmt_ctn_kwd_any" value="<?php echo $cfg['ctn_kwd_any']; ?>" style="width:100%"/><br/>
								<small><i>Note: Separate the words with comma (,)</i></small>
							</td>
						</tr>					
						<tr>
							<td colspan="5">
								<?php echo __('Don\'t show tweets that contain all of these words', 'xmt'); ?><br/>
								<input type="text" id="txt_xmt_ecl_kwd" name="txt_xmt_ecl_kwd" value="<?php echo $cfg['ecl_kwd']; ?>" style="width:100%"/><br/>
								<small><i>Note: Separate the words with a comma (,)</i></small>
							</td>
						</tr>						
						<tr>
							<td colspan="5">
								<?php echo __('Don\'t show tweets that contain any of these words', 'xmt'); ?><br/>
								<input type="text" id="txt_xmt_ecl_kwd_any" name="txt_xmt_ecl_kwd_any" value="<?php echo $cfg['ecl_kwd_any']; ?>" style="width:100%"/><br/>
								<small><i>Note: Separate the words with a comma (,)</i></small>
							</td>
						</tr>				
						<tr>
							<td colspan="5">
								<?php echo __('Additional criteria', 'xmt'); ?><br/>
								<input type="text" id="txt_xmt_sql_crt" name="txt_xmt_sql_crt" value="<?php echo $cfg['sql_crt']; ?>" style="width:100%"/>
							</td>
						</tr>								
					</table><br/>       
					<br/>

					<?php if($cfg['oah_use']){ ?>						
						<b><?php echo __('Advanced Setting', 'xmt'); ?></b><br/>
						<br/>						
                        <table cellpadding="0" cellspacing="0">
                            <tr>
                                <td colspan="5"><input type="checkbox" id="chk_xmt_inc_drc_msg" name="chk_xmt_shw_pst_frm" value="1" <?php echo ($cfg['shw_pst_frm']?'checked="checked"':''); ?>/> <?php echo __('Show a form to post a tweet/status when logged in as Admin', 'xmt'); ?></td>
                            </tr>
                            <tr>
                                <td colspan="5"><input type="checkbox" id="chk_xmt_inc_drc_msg" name="chk_xmt_inc_drc_msg" value="1" <?php echo ($cfg['inc_drc_msg']?'checked="checked"':''); ?>/> <?php echo __('Show/include direct messages', 'xmt'); ?></td>
                            </tr>
                            <tr>
                                <td colspan="5"><input type="checkbox" id="chk_xmt_twt_new_pst" name="chk_xmt_twt_new_pst" value="1" <?php echo ($cfg['twt_new_pst']?'checked="checked"':''); ?>/> <?php echo __('Post a tweet as you publish a new post', 'xmt'); ?></td>
                            </tr>
                            <tr>
                                <td colspan="5">
                                    <?php echo __('Auto tweet layout (post)', 'xmt'); ?> (<a href="javascript:show_more('sct_twt_auto_layout_var')"><?php echo __('show/hide available variables', 'xmt'); ?></a>)<br/>
                                    <textarea id="txa_xmt_twt_new_pst_lyt" name="txa_xmt_twt_new_pst_lyt" style="width:100%;height:40px"><?php echo htmlspecialchars($cfg['twt_new_pst_lyt']); ?></textarea>                                    
                                </td>
                            </tr>
                            <tr>
                                <td colspan="5"><input type="checkbox" id="chk_xmt_twt_upd_pst" name="chk_xmt_twt_upd_pst" value="1" <?php echo ($cfg['twt_upd_pst']?'checked="checked"':''); ?>/> <?php echo __('Post a tweet as you update an existing post', 'xmt'); ?></td>
                            </tr>
                            <tr>
                                <td colspan="5">
                                    <?php echo __('Auto tweet layout (post updated)', 'xmt'); ?> (<a href="javascript:show_more('sct_twt_auto_layout_var')"><?php echo __('show/hide available variables', 'xmt'); ?></a>)<br/>
                                    <textarea id="txa_xmt_twt_upd_pst_lyt" name="txa_xmt_twt_upd_pst_lyt" style="width:100%;height:40px"><?php echo htmlspecialchars($cfg['twt_upd_pst_lyt']); ?></textarea>                                    
                                </td>
                            </tr>
                            <tr>
                                <td colspan="5"><input type="checkbox" id="chk_xmt_twt_new_pag" name="chk_xmt_twt_new_pag" value="1" <?php echo ($cfg['twt_new_pag']?'checked="checked"':''); ?>/> <?php echo __('Post a tweet as you publish a new page', 'xmt'); ?></td>
                            </tr>
                            <tr>
                                <td colspan="5">
                                    <?php echo __('Auto tweet layout (page)', 'xmt'); ?> (<a href="javascript:show_more('sct_twt_auto_layout_var')"><?php echo __('show/hide available variables', 'xmt'); ?></a>)<br/>
                                    <textarea id="txa_xmt_twt_new_pag_lyt" name="txa_xmt_twt_new_pag_lyt" style="width:100%;height:40px"><?php echo htmlspecialchars($cfg['twt_new_pag_lyt']); ?></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="5"><input type="checkbox" id="chk_xmt_twt_upd_pag" name="chk_xmt_twt_upd_pag" value="1" <?php echo ($cfg['twt_upd_pag']?'checked="checked"':''); ?>/> <?php echo __('Post a tweet as you update an existing page', 'xmt'); ?></td>
                            </tr>
                            <tr>
                                <td colspan="5">
                                    <?php echo __('Auto tweet layout (page updated)', 'xmt'); ?> (<a href="javascript:show_more('sct_twt_auto_layout_var')"><?php echo __('show/hide available variables', 'xmt'); ?></a>)<br/>
                                    <textarea id="txa_xmt_twt_upd_pag_lyt" name="txa_xmt_twt_upd_pag_lyt" style="width:100%;height:40px"><?php echo htmlspecialchars($cfg['twt_upd_pag_lyt']); ?></textarea><br/>
                                    <div id="sct_twt_auto_layout_var" style="display:none;">		
                                        <small><i><?php echo __('Available variables for tweet layout', 'xmt'); ?></i></small>
                                        <ul>
                                            <li><small><b>@title</b>: <?php echo __('post/page title', 'xmt'); ?></small></li>
                                            <li><small><b>@url</b>: <?php echo __('URL to your post/page', 'xmt'); ?></small></li>
                                            <li><small><b>@summary</b>: <?php echo __('content summary', 'xmt'); ?></small></li>
                                        </ul>
                                    </div>                                    
                                </td>
                            </tr>
                            <tr>
                                <td width="150px"></td>
                                <td width="200px"></td>
                                <td width="10px"></td>
                                <td width="150px"></td>
                                <td width="200px"></td>
                            </tr>
                      	</table><br/><br/>
                    <?php } ?>          
	
					<b><?php echo __('Theme', 'xmt'); ?></b><br/>					
					<br/>
					<table cellpadding="0" cellspacing="0">
						<tr>
							<td width="150px"><?php echo __('Selected Theme', 'xmt'); ?></td>
							<td width="200px">
								<select id="cbo_xmt_thm" name="cbo_xmt_thm" style="width:100%" onchange="show_mode_opt()">															
								<?php 
									$path = xmt_base_dir.'/theme';		
									$dir = dir($path);	
									while($thm = $dir->read()){
										if($thm == '.' || $thm == '..' || substr($thm,-5) == '.html')
											continue;
								?>
										<option value="<?php echo $thm; ?>" <?php echo ($thm==$cfg['thm'])?'selected="selected"':''; ?>><?php echo __(ucwords($thm), 'xmt'); ?></option>	
								<?php
									}
									$dir->close();
								?>
								</select>
							</td>
                            <td width="10px"></td>
                            <td width="150px"></td>
                            <td width="200px"></td>
						</tr>
						<tr>
							<td colspan="5">
								<?php 
									$path = xmt_base_dir.'/theme';		
									$dir = dir($path);	
									while($thm = $dir->read()){
										if($thm == '.' || $thm == '..' || substr($thm,-5) == '.html')
											continue;
										echo '<div id="sct_md_'.$thm.'" style="display:none;">';

										$target = $path.'/'.$thm.'/conf-frm.php';										
										if(file_exists($target))
											require_once $target;
										echo '</div>';										
									}
									$dir->close();
								?>
                            	
                                <script type="text/javascript">show_mode_opt();</script>
                            </td>
						</tr>						
					</table>
					<br/>
										
					<b><?php echo __('Custom CSS', 'xmt'); ?></b><br/>
					<br/>
					<table cellpadding="0" cellspacing="0" width="710px">
						<tr>
							<td>
                            	<textarea style="width:710px" rows="5" id="txa_xmt_cst_css" name="txa_xmt_cst_css"><?php echo $cfg['cst_css']; ?></textarea><br/>
                                <i>
                                	<?php echo __('{xmt_id} will be replaced with the DIV id for Xhanch - My Twitter Widget for this profile', 'xmt'); ?><br/>
                                    <a href="http://xhanch.com/wp-content/plugins/xhanch-my-twitter/css/css.css" target="_blank"><?php echo __('Need reference to set your custom CSS? Click here to view the default CSS codes', 'xmt'); ?></a>
                                </i>
                            </td>
						</tr>
					</table><br/>
										
					<b><?php echo __('Other Settings', 'xmt'); ?></b><br/>
					<br/>
					<table cellpadding="0" cellspacing="0">
						<tr>
							<td width="150px"><?php echo __('Convert Smilies?', 'xmt'); ?></td>
							<td width="200px"><input type="checkbox" id="chk_xmt_cvr_sml" name="chk_xmt_cvr_sml" value="1" <?php echo ($cfg['cvr_sml']?'checked="checked"':''); ?>/></td>
							<td width="10px"></td>
							<td width="150px"><?php echo __('Open link in new tab?', 'xmt'); ?></td>
							<td width="200px"><input type="checkbox" id="chk_xmt_lnk_new_tab" name="chk_xmt_lnk_new_tab" value="1" <?php echo ($cfg['lnk_new_tab']?'checked="checked"':''); ?>/></td>
						</tr>
					</table><br/>
										
					<b><?php echo __('Codes for Template and Post/Page', 'xmt'); ?></b><br/>
					<br/>
					<table cellpadding="0" cellspacing="0" width="710px">
						<tr>
							<td>
                            	<?php echo __('This plugin provides widgets for your dynamic sidebars.', 'xmt'); ?><br/>
                                <?php echo __('But, if your theme does not support dynamic sidebars, you can use these codes.', 'xmt'); ?><br/>
                                <br/>
                                
                                <a href="javascript:show_more('sct_php_code')"><?php echo __('Show/hide paste-able code (PHP version)', 'xmt'); ?></a>                                
                                <div id="sct_php_code" style="display:none;">	                                
                            	<?php echo __('Here is your template code', 'xmt'); ?>
                            	<textarea style="width:710px" onfocus="this.select()" onclick="this.select()" rows="7" readonly="readonly">&lt;?php
    $args = array(
		'before_widget' => '',
		'after_widget' => '',
		'before_title' => '',
		'after_title' => '',
    );
    xmt($args, '<?php echo $acc_sel; ?>');
?&gt;</textarea><br/></div><br/>
                            	<a href="javascript:show_more('sct_scc_code')"><?php echo __('Show/hide paste-able code code (WordPress short code version)', 'xmt'); ?></a>                                
                                <div id="sct_scc_code" style="display:none;">	
                            	<textarea style="width:710px" onfocus="this.select()" onclick="this.select()" rows="2" readonly="readonly">[xmt profile=<?php echo $acc_sel; ?> before_widget="" after_widget="" before_title="" after_title=""]</textarea><br/><br/></div>
                                
                            </td>
						</tr>
					</table><br/><br/>

					<input type="checkbox" id="chk_xmt_shw_crd" name="chk_xmt_shw_crd" value="1" <?php echo ($cfg['shw_crd']?'checked="checked"':''); ?>/>
					<b><?php echo __('Show credit link ("Powered by"), I will <a href="http://xhanch.com/xhanch-donate/" target="_blank">donate</a> later.', 'xmt'); ?></b>
					<br/>

					<p class="submit">
						<input type="submit" name="cmd_xmt_update_profile" value="<?php echo __('Update Profile', 'xmt'); ?>"/>
						<input type="submit" name="cmd_xmt_clear_cache" value="<?php echo __('Clear Cache', 'xmt'); ?>" onclick="return confirm('Are you sure to clear the cached data for this profile?')"/>
						<?php if($cfg['oah_use']){ ?>
                    		<input type="submit" name="cmd_xmt_disconnect" value="<?php echo __('Disconnect From Twitter', 'xmt'); ?>"/>							
						<?php } ?>
						<input type="submit" name="cmd_xmt_get_tweets" value="<?php echo __('Get New Tweets', 'xmt'); ?>"/>
						<input type="submit" name="cmd_xmt_delete_tweets" value="<?php echo __('Delete All Stored Tweets', 'xmt'); ?>" onclick="return confirm('Are you sure to delete all stored tweets?')"/>
						<input type="button" name="cmd_xmt_export_profile" value="<?php echo __('Export Profile', 'xmt'); ?>" onclick="location.href='<?php echo xmt_get_dir('url').'/misc/profile-export.php?prf='.urlencode($acc_sel); ?>'"/>
						<input type="submit" name="cmd_xmt_delete_profile" value="<?php echo __('Delete Profile', 'xmt'); ?>" onclick="return confirm('Are you sure to delete this profile?')"/>
					</p>
                    
                    <br/>
                    <b>Duplicate this profile</b><br/><br/>
                    
                    <table cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="150px"><?php echo __('New Profile Name', 'xmt'); ?></td>
                            <td><input type="text" id="txt_xmt_acc_nme" name="txt_xmt_acc_nme" value="" style="width:200px"/></td>
                        </tr>
                  	</table>
					
					<p class="submit">
						<input type="submit" name="cmd_xmt_dpl_prf" value="<?php echo __('Duplicate Profile', 'xmt'); ?>"/>
					</p>

					<?php wp_nonce_field('xmt_cfg_frm','vrf_xmt_cfg_frm'); ?>
				</form>
			<?php }else{ ?>	
				<form action="" method="post">
					<?php if(count($xmt_acc) == 0){ ?>
						<?php echo __('You have not created any profile yet.', 'xmt'); ?><br/><br/>
					<?php } ?>
					
					<b><big><?php echo __('Add Profile', 'xmt'); ?></big></b><br/>
					<br/>
					<?php echo __('Fill the following form to create a new profile', 'xmt'); ?>
					<br/><br/>
					<table cellpadding="0" cellspacing="0">
						<tr>
							<td width="150px"><?php echo __('Name', 'xmt'); ?></td>
							<td><input type="text" id="txt_xmt_acc_nme" name="txt_xmt_acc_nme" value="" style="width:200px"/></td>
						</tr>
					</table>
					<i><small><?php echo __('Note: Profile name must only contain alphanumeric characters (A to Z and 0 to 9)', 'xmt'); ?></small></i><br/>
					<i><small><?php echo __('Each profile will create a new widget to be placed to your sidebar/post/template code', 'xmt'); ?></small></i><br/>
					<p class="submit"><input type="submit" name="cmd_xmt_crt_prf" value="<?php echo __('Create Profile', 'xmt'); ?>"/></p>
					<?php wp_nonce_field('xmt_cfg_frm','vrf_xmt_cfg_frm'); ?>
				</form>
				<br/><br/>

				<form action="" method="post" enctype="multipart/form-data">					
					<b><big><?php echo __('Import XMT profile', 'xmt'); ?></big></b><br/>
					<br/>
					<?php echo __('Browse for your XMT profile file (<b>.xmt</b>)', 'xmt'); ?><br/>							
					<input type="file" size="30" name="fil_xmt_prf_fle"/><br/>	
					<p class="submit"><input type="submit" name="cmd_xmt_import_profile" value="<?php echo __('Import Profile', 'xmt'); ?>"/></p>
					<?php wp_nonce_field('xmt_cfg_frm','vrf_xmt_cfg_frm'); ?>
				</form>
				<br/><br/>
				
				<form action="" method="post">				
					<b><big><?php echo __('Database Information', 'xmt'); ?></big></b><br/>
					<br/>				
					<table cellpadding="0" cellspacing="0">
						<tr>
							<td width="150px"><?php echo __('Current version', 'xmt'); ?></td>
							<td><input type="text" id="txt_xmt_dtb_ver" name="txt_xmt_dtb_ver" value="<?php echo get_option('xmt_vsn'); ?>" style="width:100px"/></td>
						</tr>
					</table>
					<p class="submit"><input type="submit" name="cmd_xmt_dtb_ver_upd" value="<?php echo __('Change', 'xmt'); ?>"/></p>
					<?php wp_nonce_field('xmt_cfg_frm','vrf_xmt_cfg_frm'); ?>
				</form>
				<br/><br/>
				
				<form action="" method="post">				
					<b><big><?php echo __('Reinstall Plugin', 'xmt'); ?></big></b><br/>
					<br/>
					This will completely remove your existing XMT tables and data and will create fresh XMT database tables.<br/>
					You can export your profiles so you can import them again after reinstall.
					<p class="submit"><input type="submit" name="cmd_xmt_rit" value="<?php echo __('Confirm', 'xmt'); ?>"/></p>
					<?php wp_nonce_field('xmt_cfg_frm','vrf_xmt_cfg_frm'); ?>
				</form>
			<?php } ?>

			<br/><br/>
				
			<a name="guide"></a>
			<b><big><?php echo __('Support This Plugin Development', 'xmt'); ?></big></b><br/>		
			<br/>
			<?php echo __('Do you like this plugin? Do you think this plugin very helpful?', 'xmt'); ?><br/>
			<?php echo __('Why don\'t you support this plugin developement by donating any amount you are willing to give?', 'xmt'); ?><br/>
			<br/>
			<?php echo __('If you wish to support the developer and make a donation, please click the following button. Thanks!', 'xmt'); ?><br/>
			<a href="http://xhanch.com/xhanch-donate/" target="_blank"><img src="http://xhanch.com/image/paypal/btn_donate.gif" alt="<?php echo __('Donate', 'xmt'); ?>"></a></p>

			<br/><br/>
			<a name="guide"></a>
			<b><big><?php echo __('Complete Info and Share Room', 'xmt'); ?></big></b><br/>		
			<br/>
			<div class="spoiler">
				<input type="button" onclick="show_spoiler(this);" value="<?php echo __('Complete information regarding Xhanch - My Twitter (Share Room)', 'xmt'); ?>"/>
				<div class="inner" style="display:none;">
					<br/>
					<iframe src="http://xhanch.com/wp-plugin-my-twitter/" style="width:700px;height:500px"></iframe>
				</div>
			</div>
			<br/>
			<b>Useful links:</b><br/>
			- <a href="http://forum.xhanch.com/index.php/board,13.0.html" target="_blank">Update/change logs of this plugin</a><br/>
			- <a href="http://forum.xhanch.com/index.php/board,9.0.html" target="_blank">Ask and share about how to customize this plugin. You may also ask questions about plugin configurations</a><br/>
			- <a href="http://forum.xhanch.com/index.php/board,12.0.html" target="_blank">Have a thought to improve this plugin? Suggest it here</a><br/>
			- <a href="http://forum.xhanch.com/index.php/board,10.0.html" target="_blank">Found a bug/error? Kindly report it here</a><br/>
			- <a href="http://forum.xhanch.com/index.php/board,11.0.html" target="_blank">Share your experience of using this plugin. You may show off your websites that use this plugin here by providing the URL of yor website</a><br/>
			<br/>
		</div>
<?php
	}
	
	xmt_setting();
?>