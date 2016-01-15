<?php
	function xmt_hdr_sty($acc){		
		global $xmt_acc;
		
		xmt_tmd('Build header - Start');

		$twt_url = 'http://twitter.com/'.$xmt_acc[$acc]['cfg']['twt_usr_nme'];
		$img_url = xmt_get_dir('url').'/img/icon/';

		$part = explode('-', $xmt_acc[$acc]['cfg']['hdr_sty']); 		
		$sty_type = $part[0];
		if(count($part) >= 2)
			$sty_var = $part[1];

		switch($sty_type){
			case '':
				break;
			case 'bird_with_text':
				echo '<div class="header_48"><a href="'.$twt_url.'" '.($xmt_acc[$acc]['cfg']['lnk_new_tab']?'target="_blank"':'').'><img src="'.$img_url.'twitter-bird-'.$sty_var.'.png" class="img_left" alt="'.$xmt_acc[$acc]['cfg']['twt_usr_nme'].'"/></a><a '.($xmt_acc[$acc]['cfg']['lnk_new_tab']?'target="_blank"':'').' class="header_48 text_18" href="'.$twt_url.'">'.$xmt_acc[$acc]['cfg']['nme'].'</a></div>';
				break;
			case 'logo_with_text':
				echo '<div class="header_48"><a href="'.$twt_url.'" '.($xmt_acc[$acc]['cfg']['lnk_new_tab']?'target="_blank"':'').'><img src="'.$img_url.'twitter-logo-'.$sty_var.'.png" class="img_left" alt="'.$xmt_acc[$acc]['cfg']['twt_usr_nme'].'"/></a><a '.($xmt_acc[$acc]['cfg']['lnk_new_tab']?'target="_blank"':'').' class="header_48 text_18" href="'.$twt_url.'">'.$xmt_acc[$acc]['cfg']['nme'].'</a></div>';
				break;
			case 'logo_with_text_36':
				echo '<div class="header_36"><a href="'.$twt_url.'" '.($xmt_acc[$acc]['cfg']['lnk_new_tab']?'target="_blank"':'').'><img src="'.$img_url.'twitter-logo-36-'.$sty_var.'.png" class="img_left" alt="'.$xmt_acc[$acc]['cfg']['twt_usr_nme'].'"/></a><a '.($xmt_acc[$acc]['cfg']['lnk_new_tab']?'target="_blank"':'').' class="header_36 text_18" href="'.$twt_url.'">'.$xmt_acc[$acc]['cfg']['nme'].'</a></div>';
				break;
			case 'header_image':
				echo '<div class="header_48"><a href="'.$twt_url.'" '.($xmt_acc[$acc]['cfg']['lnk_new_tab']?'target="_blank"':'').'><img src="'.$img_url.'header-image-'.$sty_var.'.png" class="img_left" alt="'.$xmt_acc[$acc]['cfg']['twt_usr_nme'].'"/></a></div>';
				break;
			case 'header_image_27':
				echo '<div class="header_27"><a href="'.$twt_url.'" '.($xmt_acc[$acc]['cfg']['lnk_new_tab']?'target="_blank"':'').'><img src="'.$img_url.'header-image-27-'.$sty_var.'.png" class="img_left" alt="'.$xmt_acc[$acc]['cfg']['twt_usr_nme'].'"/></a></div>';
				break;
			case 'avatar':
				$det = xmt_prf_get($acc); 
				if(!$det['avatar']){
					echo '<div class="header_48"><a href="'.$twt_url.'" '.($xmt_acc[$acc]['cfg']['lnk_new_tab']?'target="_blank"':'').'><img src="'.$img_url.'twitter-bird-1.png" class="img_left" alt="'.$xmt_acc[$acc]['cfg']['twt_usr_nme'].'"/></a><a '.($xmt_acc[$acc]['cfg']['lnk_new_tab']?'target="_blank"':'').' class="header_48 text_18" href="'.$twt_url.'">'.$xmt_acc[$acc]['cfg']['nme'].'</a></div>';
				}else{
					echo '<div class="header_48"><a href="'.$twt_url.'" '.($xmt_acc[$acc]['cfg']['lnk_new_tab']?'target="_blank"':'').'><img src="'.$det['avatar'].'" class="img_left" alt="'.$xmt_acc[$acc]['cfg']['twt_usr_nme'].'"/></a><a '.($xmt_acc[$acc]['cfg']['lnk_new_tab']?'target="_blank"':'').' class="header_48 text_18" href="'.$twt_url.'">'.$xmt_acc[$acc]['cfg']['nme'].'</a></div>';
				}
				break;
			default:
				echo '<div class="header_48"><a href="'.$twt_url.'" '.($xmt_acc[$acc]['cfg']['lnk_new_tab']?'target="_blank"':'').'><img src="'.$img_url.'twitter-bird-1.png" class="img_left" alt="'.$xmt_acc[$acc]['cfg']['twt_usr_nme'].'"/></a><a '.($xmt_acc[$acc]['cfg']['lnk_new_tab']?'target="_blank"':'').' class="header_48 text_18" href="'.$twt_url.'">'.$xmt_acc[$acc]['cfg']['nme'].'</a></div>';
				break;
		}
		xmt_tmd('Build header - Finished');
	}
?>