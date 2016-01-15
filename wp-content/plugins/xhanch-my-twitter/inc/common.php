<?php
	function xmt_is_ie6() {
		  $userAgent = strtolower($_SERVER['HTTP_USER_AGENT']);
		  if (ereg("msie 6.0", $userAgent))
				return true;
		  else
			return false;		  
	}

	function xmt_check(){
		global $xmt_acc;

		$issues = array();
		if(!function_exists('curl_init'))
			$issues[] = __('Oops, your web server does not provide/support/enable the CURL Extension. But, this plugin may work if you just leave the password field empty/blank or you can ask your hosting provider to enable it for you', 'xmt');
		if(!function_exists('simplexml_load_string'))
			$issues[] = __('SimpleXML cannot be found. You can ask your hosting provider to enable it or you can\'t use this plugin.', 'xmt');

		foreach($xmt_acc as $acc=>$acc_det){
			if($xmt_acc[$acc]['cfg']['cch_enb']){				
				if(!is_dir(xmt_cch_dir))
					@mkdir(xmt_cch_dir);
				if(!is_dir(xmt_cch_dir))
					$issues[] = __('Cannot create cache directory! Please create a new directory, <b>xhc-xmt</b>, in your <b>wp-content</b>', 'xmt');
				break;
			}
		}

		if(count($issues))
			echo '<div id="message" class="updated fade"><p><b>'.__('Plugin requirements issue(s)', 'xmt').'</b>:<br/><br/>'.implode('<br/><br/>', $issues).'</p></div>';
	}

	function xmt_replace_vars($str, $acc){		
		if(trim($str) == '')
			return $str;

		$str = convert_smilies(html_entity_decode($str));
		if(strpos($str, '@') === false)
			return $str;
		
		$det = xmt_prf_get($acc);
		$str = str_replace('@followers_count', intval($det['followers_count']), $str);
		$str = str_replace('@friends_count', intval($det['friends_count']), $str);
		$str = str_replace('@favourites_count', intval($det['favourites_count']), $str);
		$str = str_replace('@statuses_count', intval($det['statuses_count']), $str);
		$str = str_replace('@avatar', $det['avatar'], $str);
		$str = str_replace('@name', $det['name'], $str);
		$str = str_replace('@screen_name', $det['screen_name'], $str);
		return $str; 
	}

	function xmt_tmd($str = ''){
		global $xmt_tmd;	
		$span = time() - $xmt_tmd;
		xmt_log(($str?__($str, 'xmt').' - ':'').__('Exec time', 'xmt').' - '.$span.' s');
	}

	function xmt_log($str){
		if(isset($_GET['xmt_debug']))
			echo '<!-- XMT: '.str_replace('--', '-', __($str, 'xmt')).' -->';
		elseif(isset($_GET['xmt_debug_show']))
			echo '<i>- XMT: '.str_replace('--', '-', __($str, 'xmt')).' -</i><br/>';
	}

	function xmt_make_clickable($ret, $acc){
		global $xmt_acc;

		$ret = ' ' . $ret;
		
		$has_url = preg_match_all('#(?<=[\s>])(\()?([\w]+?://(?:[\w\\x80-\\xff\#$%&~/\-=?@\[\](+]|[.,;:](?![\s<])|(?(1)\)(?![\s<])|\)))+)#is', $ret, $tmp);
		if($has_url){
			foreach($tmp[2] AS $aV){
				$url = esc_url($aV);
				$rpc = '<a href="'.$url.'" rel="nofollow" '.($xmt_acc[$acc]['cfg']['lnk_new_tab']?'target="_blank"':'').'>'.($xmt_acc[$acc]['cfg']['url_lyt']?$xmt_acc[$acc]['cfg']['url_lyt']:$url).'</a>';
				$ret = str_replace($aV, $rpc, $ret);
			}
		}
		$ret = preg_replace("#(<a( [^>]+?>|>))<a [^>]+?>([^>]+?)</a></a>#i", "$1$3</a>", $ret);
		$ret = trim($ret);
		return $ret;
	}

	function xmt_time_in_zone() {
		if ($tz = get_option ('timezone_string') ) {
			$tz_obj = timezone_open ($tz);
			$offset = timezone_offset_get($tz_obj, new datetime('now',$tz_obj));
		}else if (($gmt_offset = get_option ('gmt_offset')) && (!(is_null($gmt_offset))) && (is_numeric($gmt_offset)))
			$offset = $gmt_offset;
		else 
			return(time());
		return (time() + $offset);
	}

	function xmt_time_span($unix_date){	 
		$periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
		$lengths = array("60","60","24","7","4.35","12","10");
	 
		//$now = xmt_time_in_zone();
	 	$now = time();
		
		if(empty($unix_date))  
			return "Bad date";
			 
		if($now > $unix_date){
			$difference = $now - $unix_date;
			$tense = "ago";	 
		}else{
			$difference = $unix_date - $now;
			$tense = "from now";
		}
	 
		for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++)
			$difference /= $lengths[$j];
			 
		$difference = round($difference);
	 
		if($difference != 1)
			$periods[$j].= "s";
		$per = __($periods[$j], 'xmt');
			 
		return $difference.' '.$per.' '.__($tense, 'xmt');
	}

	function xmt_form_get($str){
		if(!isset($_GET[$str]))
			return false;
		return xmt_read_var(urldecode($_GET[$str]));
	}

	function xmt_read_var($str){
		$res = $str;
		$res = str_replace('\\\'','\'',$res);
		$res = str_replace('\\\\','\\',$res);
		$res = str_replace('\\"','"',$res);
		return $res;
	}

	function xmt_form_post($str, $parse = true){
		if(!isset($_POST[$str]))
			return false;
		if($parse)
			return xmt_read_var($_POST[$str]);
		return $_POST[$str];
	}

	function xmt_get_dir($type) {
		if ( !defined('WP_CONTENT_URL')){
			$tmp_url = get_option('siteurl').'/wp-content';
			if($_SERVER["HTTPS"] == "on")
				$tmp_url = str_replace('http://', 'https://', $tmp_url);
			define( 'WP_CONTENT_URL', $tmp_url);
		}
		if ( !defined('WP_CONTENT_DIR') )
			define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );
		if ($type=='path') { return WP_CONTENT_DIR.'/plugins/'.plugin_basename(xmt_base_dir); }
		else { return WP_CONTENT_URL.'/plugins/'.plugin_basename(xmt_base_dir); }
	}

	function xmt_get_file($name){
		$res = '';
		$res = @file_get_contents($name);
		if($res === false || $res == ''){
			$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL, $name);
			curl_setopt($ch, CURLOPT_AUTOREFERER, 0);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_REFERER, $name);	
			@curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			@curl_setopt($ch, CURLOPT_TIMEOUT, 10);
			@curl_setopt($ch, CURLOPT_TIMEVALUE, null); 
			@curl_setopt($ch, CURLOPT_TIMECONDITION, 0); 
			curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.1.17) Gecko/20110121 Firefox/3.5.17');

			$res = curl_exec($ch);
			if($res === false)
				xmt_log('Failed to read feeds from twitter because of ' . curl_error($ch));				
			curl_close($ch);
		}		
		return $res;
	}	

	function xmt_get_time($dt, $gmt_cst_add = 0){		
		$gmt_cst_add = $gmt_cst_add * 60;
		
		$tmp = explode(' ', $dt);
		$time = explode(':', $tmp[3]);
		switch($tmp[1]){
			case 'Jan':$tmp[1]=1;break;
			case 'Feb':$tmp[1]=2;break;
			case 'Mar':$tmp[1]=3;break;
			case 'Apr':$tmp[1]=4;break;
			case 'May':$tmp[1]=5;break;
			case 'Jun':$tmp[1]=6;break;
			case 'Jul':$tmp[1]=7;break;
			case 'Aug':$tmp[1]=8;break;
			case 'Sep':$tmp[1]=9;break;
			case 'Oct':$tmp[1]=10;break;
			case 'Nov':$tmp[1]=11;break;
			case 'Dec':$tmp[1]=12;break;
		}
		return @mktime($time[0], $time[1], $time[2], $tmp[1], $tmp[2], $tmp[5]) + $gmt_cst_add;
	}

	function xmt_parse_time($dt, $date_format, $gmt_cst_add = 0){		
		$timestamp = '';
		if($date_format != ''){
			if($date_format == 'span')
				$timestamp .= xmt_time_span(xmt_get_time($dt, $gmt_cst_add));
			else
				$timestamp .= date($date_format, xmt_get_time($dt, $gmt_cst_add));
		}
		return $timestamp;
	}	

	function xmt_sql_str($str){
		global $wpdb;
		return '\''.esc_sql($str).'\'';
	}

	function xmt_sql_int($val){
		if($val == '')
			$val = 0;
		return intval($val);
	}

	function xmt_css_minify($v){
		$v = trim($v);
		$v = str_replace("\r\n", "\n", $v);
        $search = array("/\/\*[\d\D]*?\*\/|\t+/", "/\s+/", "/\}\s+/");
        $replace = array(null, " ", "}\n");
		$v = preg_replace($search, $replace, $v);
		$search = array("/\\;\s/", "/\s+\{\\s+/", "/\\:\s+\\#/", "/,\s+/i", "/\\:\s+\\\'/i", "/\\:\s+([0-9]+|[A-F]+)/i");
        $replace = array(";", "{", ":#", ",", ":\'", ":$1");
        $v = preg_replace($search, $replace, $v);
        $v = str_replace("\n", null, $v);
    	return $v;	
  	}
?>