<?php
	if(!function_exists('esc_url')){
		function esc_url( $url, $protocols = null, $_context = 'display' ) {
			$original_url = $url;			
			if ('' == $url)
				return $url;
			$url = preg_replace('|[^a-z0-9-~+_.?#=!&;,/:%@$\|*\'()\\x80-\\xff]|i', '', $url);
			$strip = array('%0d', '%0a', '%0D', '%0A');
			$url = _deep_replace($strip, $url);
			$url = str_replace(';//', '://', $url);
			
			if ( strpos($url, ':') === false &&
				substr( $url, 0, 1 ) != '/' && substr( $url, 0, 1 ) != '#' && !preg_match('/^[a-z0-9-]+?\.php/i', $url) )
				$url = 'http://' . $url;
			
			if ( 'display' == $_context ) {
				$url = preg_replace('/&([^#])(?![a-z]{2,8};)/', '&#038;$1', $url);
				$url = str_replace( "'", '&#039;', $url );
			}
			
			if ( !is_array($protocols) )
				$protocols = array ('http', 'https', 'ftp', 'ftps', 'mailto', 'news', 'irc', 'gopher', 'nntp', 'feed', 'telnet', 'mms', 'rtsp', 'svn');
			if ( wp_kses_bad_protocol( $url, $protocols ) != $url )
				return '';
			
			return apply_filters('clean_url', $url, $original_url, $_context);
		}
	}
?>