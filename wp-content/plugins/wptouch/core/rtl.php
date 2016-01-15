<?php

function wptouch_should_load_rtl() {
	global $wptouch_pro;

	if ( is_admin() ) {
		$settings = wptouch_get_settings();
		if ( defined( 'WPTOUCH_ADMIN_IGNORE_LOCALIZATION' ) ) {
			return false;
		}
		if ( !$settings->translate_admin ) {
			return false;
		}
	}

	return is_rtl() || $wptouch_pro->locale == 'ar' || $wptouch_pro->locale == 'he_IL';
}
