<?php
	function xmt_twt_oah_prf_get($acc){
		global $xmt_acc;
		$cls = new TwitterOAuth($xmt_acc[$acc]['cfg']['csm_key'], $xmt_acc[$acc]['cfg']['csm_sct'], $xmt_acc[$acc]['cfg']['oah_tkn'], $xmt_acc[$acc]['cfg']['oah_sct']);
		$usr = json_decode($cls->get('account/verify_credentials'));
		if($usr->screen_name != ''){
			return array(
				'img_url' => $usr->profile_image_url,
				'nme' => $usr->name,
				'scr_nme' => $usr->screen_name,
				'dtp_crt' => $usr->created_at,
				'tot_frd' => $usr->friends_count,
				'tot_flw' => $usr->followers_count,
				'tot_sts' => $usr->statuses_count
			);
		}else
			return false;
	}

	function xmt_twt_oah_twt_get($acc){
		global $xmt_acc;
		$cls = new TwitterOAuth($xmt_acc[$acc]['cfg']['csm_key'], $xmt_acc[$acc]['cfg']['csm_sct'], $xmt_acc[$acc]['cfg']['oah_tkn'], $xmt_acc[$acc]['cfg']['oah_sct']);
		$cls->format = 'json';
		return $cls->get('statuses/user_timeline', array('count' => intval($xmt_acc[$acc]['cfg']['cnt']), 'include_rts' => intval($xmt_acc[$acc]['cfg']['inc_rtw'])));
	}

	function xmt_twt_oah_twt_pst($acc, $twt){
		global $xmt_acc;
		$cls = new TwitterOAuth($xmt_acc[$acc]['cfg']['csm_key'], $xmt_acc[$acc]['cfg']['csm_sct'], $xmt_acc[$acc]['cfg']['oah_tkn'], $xmt_acc[$acc]['cfg']['oah_sct']);
		return $cls->post('statuses/update', array('status' => $twt));
	}

	function xmt_twt_oah_rpl_get($acc){
		global $xmt_acc;
		$cls = new TwitterOAuth($xmt_acc[$acc]['cfg']['csm_key'], $xmt_acc[$acc]['cfg']['csm_sct'], $xmt_acc[$acc]['cfg']['oah_tkn'], $xmt_acc[$acc]['cfg']['oah_sct']);
		$cls->format = 'json';
		return $cls->get('statuses/mentions_timeline', array('count' => intval($xmt_acc[$acc]['cfg']['cnt'])));
	}

	function xmt_twt_oah_drc_msg_get($acc){
		global $xmt_acc;
		$cls = new TwitterOAuth($xmt_acc[$acc]['cfg']['csm_key'], $xmt_acc[$acc]['cfg']['csm_sct'], $xmt_acc[$acc]['cfg']['oah_tkn'], $xmt_acc[$acc]['cfg']['oah_sct']);
		$cls->format = 'json';
		return $cls->get('direct_messages', array('count' => intval($xmt_acc[$acc]['cfg']['cnt'])));
	}
?>