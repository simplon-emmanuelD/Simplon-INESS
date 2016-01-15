<?php
	function xmt_twt_raw_imp($acc, $req, $typ = 'twt') {
		global $xmt_acc;

		if($typ == 'dmg'){
			$req = str_replace('direct-messages', 'statuses', $req);
			$req = str_replace('direct_message', 'status', $req);
			$req = str_replace('sender', 'user', $req);
		}

		if($req == '')
			return;

		$arr_twt = json_decode($req, true);	
			
		if(!$arr_twt){
			xmt_log($req);	
			return;
		}elseif($arr_twt['errors']){
			xmt_log($arr_twt['errors'][0]['message'].' (Code: '.$arr_twt['errors'][0]['code'].')');
			return;
		}
		
		foreach($arr_twt as $res){
			$twt_typ = $typ;
			if($res['retweeted'])
				$twt_typ = 'rtw';
			$rpl = (string)$res['in_reply_to_screen_name'];
			if($rpl != ''){
				if($rpl == $xmt_acc[$acc]['cfg']['twt_usr_nme'])
					$twt_typ = 'rty';
				else
					$twt_typ = 'rfy';
			}
			
			xmt_twt_ins($acc, array(
				'id' => (string)$res['id_str'],
				'twt' => (string)$res['text'],
				'ath' => (string)$res['user']['screen_name'],
				'src' => (string)$res['source'],
				'dtp' => date('Y-m-d H:i:s', xmt_get_time((string)$res['created_at'])),
				'typ' => $twt_typ,
			));

			xmt_ath_ins(array(
				'uid' => (string)$res['user']['screen_name'],
				'nme' => (string)$res['user']['name'],
				'img_url' => (string)$res['user']['profile_image_url'],
			));
		}
		unset($arr_twt);
	}

	function xmt_twt_imp($acc, $frc=false){
		global $wpdb;
		global $xmt_tmd;
		global $xmt_acc;
		
		if(!$frc){
			$las_imp = intval($xmt_acc[$acc]['las_twt_imp_dtp']);
			$imp_itv = intval($xmt_acc[$acc]['cfg']['imp_itv']) * 60;

			if(time() - $las_imp < $imp_itv)
				return;
		}

		xmt_tmd('Import Tweets - Start');

		$sql = '
			update '.$wpdb->prefix.'xmt_acc
			set las_twt_imp_dtp = '.xmt_sql_int(time()).'
			where nme = '.xmt_sql_str($acc).'
		';
		$wpdb->query($sql);
		
		if($xmt_acc[$acc]['cfg']['twt_usr_nme'] == '')
			return;

		$lmt = $xmt_acc[$acc]['cfg']['cnt'];			
		if($lmt <= 0)
			$lmt = 5;
				
		$method = 'oauth';
		if(!$xmt_acc[$acc]['cfg']['oah_use'])
			return;		
			
		@include xmt_base_dir.'/method/'.$method.'.php';

		xmt_tmd('Import Tweets - Finished');
	}

	function xmt_twt_ins($acc, $prm){
		global $wpdb;
		$sql = '
			insert ignore '.$wpdb->prefix.'xmt_twt(
				acc_nme, 
				twt_id, 
				twt_ath, 
				twt, 
				twt_dtp, 
				twt_typ, 
				twt_src
			)values(
				'.xmt_sql_str($acc).',
				'.xmt_sql_str($prm['id']).',
				'.xmt_sql_str($prm['ath']).',
				'.xmt_sql_str($prm['twt']).',
				'.xmt_sql_str($prm['dtp']).',
				'.xmt_sql_str($prm['typ']).',
				'.xmt_sql_str($prm['src']).'
			)
		';
		$wpdb->query($sql);
	}

	function xmt_twt_del($acc, $twt_id){
		global $wpdb;
		$sql = '
			delete from '.$wpdb->prefix.'xmt_twt
			where 
				acc_nme = '.xmt_sql_str($acc).' and
				twt_id = '.xmt_sql_str($twt_id).'
		';
		$wpdb->query($sql);
	}

	function xmt_ath_ins($prm){
		global $wpdb;
		$sql = '
			insert into '.$wpdb->prefix.'xmt_ath(
				uid, 
				nme, 
				img_url, 
				dte_upd
			)values(
				'.xmt_sql_str($prm['uid']).',
				'.xmt_sql_str($prm['nme']).',
				'.xmt_sql_str($prm['img_url']).',
				now()
			)on duplicate key update
				nme = '.xmt_sql_str($prm['uid']).', 
				img_url = '.xmt_sql_str($prm['img_url']).', 
				dte_upd = now()			
		';
		$wpdb->query($sql);
	}

	function xmt_twt_get($acc){
		global $wpdb;
		global $xmt_acc;

		xmt_tmd('Get Tweets - Start');

		$arr = xmt_cch_get($acc, 'twt');
		if(isset($_GET['xmt_debug_show']) || isset($_GET['xmt_debug']) || $arr === false || count($arr) == 0){
			$lmt = $xmt_acc[$acc]['cfg']['cnt'];			
			if($lmt <= 0)
				$lmt = 5;
			
			$arr = array();
			
			$crt = array();
			$crt[] = 'acc_nme = '.xmt_sql_str($acc);
			
			$typ_exc = array();
			if(!$xmt_acc[$acc]['cfg']['inc_rpl_fru'])
				$typ_exc[] = '\'rfy\'';
			if(!$xmt_acc[$acc]['cfg']['inc_rpl_tou'])
				$typ_exc[] = '\'rty\'';
			if(!$xmt_acc[$acc]['cfg']['inc_rtw'])
				$typ_exc[] = '\'rtw\'';
			if(!$xmt_acc[$acc]['cfg']['inc_drc_msg'])
				$typ_exc[] = '\'dmg\'';
			if(count($typ_exc) > 0)
				$crt[] = 'twt_typ not in ('.implode(',', $typ_exc).')';

			if($xmt_acc[$acc]['cfg']['ctn_kwd']){
				$ctn_kwd = explode(',', trim($xmt_acc[$acc]['cfg']['ctn_kwd']));
				if(count($ctn_kwd)){
					foreach($ctn_kwd as $kwd){
						$crt[] = 'twt like '.xmt_sql_str('%'.$kwd.'%');
					}
				}
			}
			if($xmt_acc[$acc]['cfg']['ctn_kwd_any']){
				$ctn_kwd = explode(',', trim($xmt_acc[$acc]['cfg']['ctn_kwd_any']));
				if(count($ctn_kwd)){
					$tmp_crt = '';
					foreach($ctn_kwd as $kwd){
						if($tmp_crt != '')
							$tmp_crt .= ' or ';
						$tmp_crt .= 'twt like '.xmt_sql_str('%'.$kwd.'%');
					}
					$crt[] = '('.$tmp_crt.')';
				}
			}
			if($xmt_acc[$acc]['cfg']['ecl_kwd']){
				$ecl_kwd = explode(',', trim($xmt_acc[$acc]['cfg']['ecl_kwd']));
				if(count($ecl_kwd)){
					foreach($ecl_kwd as $kwd){
						$crt[] = 'twt not like '.xmt_sql_str('%'.$kwd.'%');
					}
				}
			}
			if($xmt_acc[$acc]['cfg']['ecl_kwd_any']){
				$ctn_kwd = explode(',', trim($xmt_acc[$acc]['cfg']['ecl_kwd_any']));
				if(count($ctn_kwd)){
					$tmp_crt = '';
					foreach($ctn_kwd as $kwd){
						if($tmp_crt != '')
							$tmp_crt .= ' or ';
						$tmp_crt .= 'twt not like '.xmt_sql_str('%'.$kwd.'%');
					}
					$crt[] = '('.$tmp_crt.')';
				}
			}
			if($xmt_acc[$acc]['cfg']['sql_crt'])
				$crt[] = $xmt_acc[$acc]['cfg']['sql_crt'];
	
			$sql = '
				select 
					twt.twt_id,
					twt.twt_typ,
					twt.twt_dtp,
					twt.twt,
					twt.twt_src,
					twt.twt_ath,
					ath.nme as ath_nme,
					ath.img_url
				from '.$wpdb->prefix.'xmt_twt twt
				left join '.$wpdb->prefix.'xmt_ath ath
					on twt.twt_ath = ath.uid
				where '.implode(' and ', $crt).'
				order by twt_id '.($xmt_acc[$acc]['cfg']['ord']=='lto'?'desc':'asc').'
				limit '.$lmt.'
			';
			$rst = $wpdb->get_results($sql, ARRAY_A);
			foreach($rst as $row){
				$gmt_add = intval(get_option('gmt_offset')) * 60 * 60;
				$twt_dtp = strtotime($row['twt_dtp']) + $gmt_add + ($xmt_acc[$acc]['cfg']['gmt_add'] * 60);				
				if($xmt_acc[$acc]['cfg']['dtm_fmt'] != ''){
					if($xmt_acc[$acc]['cfg']['dtm_fmt'] == 'span')
						$twt_dtp = xmt_time_span($twt_dtp);
					else
						$twt_dtp = date($xmt_acc[$acc]['cfg']['dtm_fmt'], $twt_dtp);
				}

				$twt = $row['twt'];
				$twt = html_entity_decode($twt, ENT_COMPAT, 'UTF-8');
				$twt = htmlentities($twt, ENT_COMPAT, 'UTF-8');

				if($xmt_acc[$acc]['cfg']['trc_len'] > 0){
					if(strlen($twt) > $xmt_acc[$acc]['cfg']['trc_len'])
						$twt = substr($twt, 0, $xmt_acc[$acc]['cfg']['trc_len']).' '.$xmt_acc[$acc]['cfg']['trc_chr'];
				}
					
				if($xmt_acc[$acc]['cfg']['clc_url'])
					$twt = xmt_make_clickable($twt, $acc);

				if(!$xmt_acc[$acc]['cfg']['shw_hsh_tag']){
					$pattern = '/(\s\#([_a-z0-9\-]+))/i';
					$replace = '';
					$twt = preg_replace($pattern,$replace,$twt);
				}

				if($xmt_acc[$acc]['cfg']['shw_hsh_tag'] && $xmt_acc[$acc]['cfg']['clc_hsh_tag']){
					$pattern = '/(\s\#([_a-z0-9\-]+))/i';
					$replace = '<a href="https://twitter.com/search?q=%23$2&src=hash" '.($xmt_acc[$acc]['cfg']['lnk_new_tab']?'target="_blank"':'').'>$1</a>';
					$twt = preg_replace($pattern,$replace,$twt);
				}

				if($xmt_acc[$acc]['cfg']['clc_usr_tag']){
					$pattern = '/(@([_a-z0-9\-]+))/i';
					$replace = '<a href="http://twitter.com/$2" title="Follow $2" '.($xmt_acc[$acc]['cfg']['lnk_new_tab']?'target="_blank"':'').'>$1</a>';
					$twt = preg_replace($pattern,$replace,$twt);
				}

				if($xmt_acc[$acc]['cfg']['cvr_sml'])
					$twt = convert_smilies($twt);

				$arr[$row['twt_id']] = array(
					'type' => $row['twt_typ'],
					'timestamp' => $twt_dtp,
					'tweet' => $twt,
					'author' => $row['twt_ath'],
					'author_name' => $row['ath_nme'],
					'author_url' => 'http://twitter.com/'.$row['twt_ath'],
					'author_img' => $row['img_url'],
					'source' => $row['twt_src'],
				);
			}

			if(count($arr))
				xmt_cch_set($acc, 'twt', $arr);
			else{
				$arr = xmt_cch_get($acc, 'twt', -1);
				if($arr === false || !is_array($arr))
					$arr = array();
			}
		}
		
		xmt_tmd('Get Tweets - Finished');
		return $arr;
	}

	function xmt_prf_get($acc){
		global $xmt_acc;

		$arr = xmt_cch_get($acc, 'prf');
		if($arr === false || count($arr) == 0){
			$usr_det = xmt_twt_oah_prf_get($acc);	

			$arr = array(
				'avatar' => (string)$usr_det['img_url'],
				'followers_count' => intval($usr_det['tot_flw']),
				'friends_count' => intval($usr_det['tot_frd']),
				'favourites_count' => intval($usr_det['tot_fav']),
				'statuses_count' => intval($usr_det['tot_sts']),
				'name' => (string)$usr_det['nme'],
				'screen_name' => (string)$usr_det['scr_nme'],
			);

			if($xml !== false)
				xmt_cch_set($acc, 'prf', $arr);
			else{
				$arr = xmt_cch_get($acc, 'prf', -1);
				if($arr === false || !is_array($arr))
					$arr = array();
			}
		}

		return $arr;
	}
?>