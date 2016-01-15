<?php
	if(!defined('xmt'))
		exit;

	xmt_tmd('Build Body - Start');
	
	if(count($res) == 0) 
		return;		
	echo $before_widget;

	if($xmt_acc[$acc]['cfg']['ttl'] != ''){
		echo $before_title;
		
		if($xmt_acc[$acc]['cfg']['lnk_ttl'])
			echo '<a href="http://twitter.com/'.$xmt_acc[$acc]['cfg']['twt_usr_nme'].'" rel="external nofollow" '.($xmt_acc[$acc]['cfg']['lnk_new_tab']?'target="_blank"':'').'>';
		echo $xmt_acc[$acc]['cfg']['ttl'];
		if($xmt_acc[$acc]['cfg']['lnk_ttl'])
			echo '</a>';
		
		echo $after_title;		
	}

	echo '<div id="xmt_'.$acc.'_wid" class="xmt xmt_'.$acc.'">';
	xmt_hdr_sty($acc);

	echo xmt_replace_vars($xmt_acc[$acc]['cfg']['cst_hdr_txt'], $acc);
	
	if($cur_role == 'administrator'){
		echo '<a name="xmt_'.$acc.'"></a>';
		if($msg)
			echo '<div>'.__($msg, 'xmt').'</div>';
		if($alw_twt){
			echo '<form action="#xmt_'.$acc.'" method="post">'.__('What\'s happening?', 'xmt').'<br/>';
			wp_nonce_field('xmt_twt_frm','vrf_xmt_wgt_twt_frm_'.$acc);
			echo '<textarea name="txa_xmt_'.$acc.'_tweet"></textarea><input type="submit" class="submit" name="cmd_xmt_'.$acc.'_post" value="'.__('Tweet', 'xmt').'"/><div class="clear"></div></form>';
		}
	}

	if($xmt_acc[$acc]['cfg']['thm_scr_anm'])
		echo '<div id="xmt_'.$acc.'_tweet_area_cont" style="height:'.$xmt_acc[$acc]['cfg']['thm_scr_szh'].'px;overflow:hidden;position:relative"><div id="xmt_'.$acc.'_tweet_area">';
	else
		echo '<div style="max-height:'.$xmt_acc[$acc]['cfg']['thm_scr_szh'].'px;overflow:auto">';		

	echo '<ul class="tweet_area">';

	$twt_lyt = $xmt_acc[$acc]['cfg']['twt_lyt'];
	$twt_lyt = convert_smilies(html_entity_decode($twt_lyt));

	foreach($res as $sts_id=>$row){
		echo '<li class="tweet_list">';
			if($xmt_acc[$acc]['cfg']['shw_hrl'])
				echo '<hr />';
			
			if($xmt_acc[$acc]['cfg']['avt_shw']){					
				echo '<a href="'.$row['author_url'].'" '.($xmt_acc[$acc]['cfg']['lnk_new_tab']?'target="_blank"':'').'><img class="tweet_avatar" src="'.$row['author_img'].'" alt="'.$row['author_name'].'"/></a>';				
			}
						
			$lnk_sts = 'http://twitter.com/'.$row['author'].'/status/'.$sts_id;
			$lnk_rtw = 'http://twitter.com/home?status='.urlencode('RT @'.$row['author'].' '.strip_tags($row['tweet']));
			$lnk_rpl = 'http://twitter.com/home?status='.urlencode('@'.$row['author']).'&amp;in_reply_to_status_id='.$sts_id.'&amp;in_reply_to='.urlencode($row['author']);
			
			$tmp_str = str_replace('@screen_name_plain', $row['author'], $twt_lyt);
			$tmp_str = str_replace('@screen_name', '<a href="'.$row['author_url'].'"  '.($xmt_acc[$acc]['cfg']['lnk_new_tab']?'target="_blank"':'').' rel="external nofollow">'.$row['author'].'</a>', $tmp_str);
			$tmp_str = str_replace('@name_plain', $row['author_name'], $tmp_str);
			$tmp_str = str_replace('@name', '<a href="'.$row['author_url'].'"  '.($xmt_acc[$acc]['cfg']['lnk_new_tab']?'target="_blank"':'').' rel="external nofollow">'.$row['author_name'].'</a>', $tmp_str);
			$tmp_str = str_replace('@date', $row['timestamp'], $tmp_str);
			$tmp_str = str_replace('@source', $row['source'], $tmp_str);
			$tmp_str = str_replace('@tweet', $row['tweet'], $tmp_str);
			$tmp_str = str_replace('@reply_url', $lnk_rpl, $tmp_str);
			$tmp_str = str_replace('@reply_link', '<a href="'.$lnk_rpl.'"  '.($xmt_acc[$acc]['cfg']['lnk_new_tab']?'target="_blank"':'').' rel="external nofollow">'.__('reply', 'xmt').'</a>', $tmp_str);
			$tmp_str = str_replace('@retweet_url', $lnk_rtw, $tmp_str);
			$tmp_str = str_replace('@retweet_link', '<a href="'.$lnk_rtw.'"  '.($xmt_acc[$acc]['cfg']['lnk_new_tab']?'target="_blank"':'').' rel="external nofollow">'.__('retweet', 'xmt').'</a>', $tmp_str);
			$tmp_str = str_replace('@status_url', $lnk_sts, $tmp_str);
			
			echo $tmp_str;

			if($cur_role == 'administrator')
				echo ' <a href="'.wp_nonce_url('?xmt_'.$acc.'_twt_id='.$sts_id, 'xmt_wgt_act').'#xmt_'.$acc.'">[delete]</a>';	
		echo '</li>';
	}
	echo '</ul>';
	if($xmt_acc[$acc]['cfg']['shw_hrl']) 
		echo '<hr/>';

	if($xmt_acc[$acc]['cfg']['thm_scr_anm']){
		$pos_str = '';
		if($xmt_acc[$acc]['cfg']['thm_scr_anm_dir'] == 'down')
			$pos_str = 'xmt_'.$acc.'_ta.style.top = xmt_'.$acc.'_ta_limit + "px";';
		else
			$pos_str = 'xmt_'.$acc.'_ta.style.top = '.$xmt_acc[$acc]['cfg']['thm_scr_szh'].' + "px";';
			
		echo '</div></div>';							
		echo '
			<script language="javascript" type="text/javascript">
				//<![CDATA[
					jQuery(document).ready(function(){
						var xmt_'.$acc.'_ta = document.getElementById("xmt_'.$acc.'_tweet_area");
						var xmt_'.$acc.'_ta_limit = xmt_'.$acc.'_ta.offsetHeight * -1;							
						$xmt_marquee.config.refresh = '.$xmt_acc[$acc]['cfg']['thm_scr_anm_dly'].';
						$xmt_marquee.add("#xmt_'.$acc.'_tweet_area_cont","#xmt_'.$acc.'_tweet_area","'.$xmt_acc[$acc]['cfg']['thm_scr_anm_dir'].'",'.$xmt_acc[$acc]['cfg']['thm_scr_anm_amt'].',true);
						'.$pos_str.'
						$xmt_marquee.start();
					})
				//]]>
			</script>
		';
	}else
		echo '</div>';		
				
	echo xmt_replace_vars($xmt_acc[$acc]['cfg']['cst_ftr_txt'], $acc); 

	if ($xmt_acc[$acc]['cfg']['shw_crd']){
		echo '<div class="credit"><a href="http://xhanch.com/wp-plugin-my-twitter/" rel="section" title="'.__('Xhanch My Twitter - The best WordPress plugin to integrate your WordPress website with your Twitter accounts', 'xmt').'">'.__('My Twitter', 'xmt').'</a>, <a href="http://xhanch.com/" rel="section" title="'.__('Developed by Xhanch Studio', 'xmt').'">'.__('by Xhanch', 'xmt').'</a></div>';
	}
	echo '</div>';
			
	echo $after_widget;
	xmt_tmd('Build Body - Finished');
?>