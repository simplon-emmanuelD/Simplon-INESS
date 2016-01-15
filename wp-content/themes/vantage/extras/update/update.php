<?php

/**
 * Add some custom SiteOrigin update information to the update_themes transient.
 *
 * This ONLY applies when the user enters a valid premium order number. A user should be aware that the updates will be
 * coming from a different source after they upgrade to the premium version.
 *
 * @param $current
 * @return mixed
 */
function siteorigin_theme_update_filter( $current ) {
	$theme = basename( get_template_directory() );
	$order_number = siteorigin_setting('premium_order_number');
	if ( empty( $order_number ) ) return $current; // Skip if the user has not entered an order number.

	static $request = false;
	if(empty($request)){
		// Only keep a single instance of this request. Stops double requests.
		$request = wp_remote_get(
			add_query_arg( array(
				'timestamp' => time(),
				'action' => 'update_info',
				'version' => SITEORIGIN_THEME_VERSION,
				'order_number' => $order_number
			), SITEORIGIN_THEME_ENDPOINT . '/premium/' . $theme . '/' ),
			array(
				'timeout'     => 10,
			)
		);
	}

	if ( !is_wp_error( $request ) && $request['response']['code'] == 200 && !empty( $request['body'] ) ) {
		$data = unserialize( $request['body'] );
		if ( empty( $current ) )  $current = new stdClass();
		if ( empty( $current->response ) ) $current->response = array();
		if ( !empty( $data ) ) $current->response[ $theme ] = $data;
	}

	return $current;
}

add_filter( 'pre_set_site_transient_update_themes', 'siteorigin_theme_update_filter' );

/**
 * Add the order number setting.
 *
 * @action admin_init
 */
function siteorigin_theme_update_settings() {
	siteorigin_settings_add_section('premium', __('Premium', 'vantage'));
	siteorigin_settings_add_field('premium', 'order_number', 'text', __('Order Number', 'vantage'), array(
		'description' => __('Enter the order number we sent you by email', 'vantage')
	));
}
add_action( 'siteorigin_settings_init', 'siteorigin_theme_update_settings', 40 );

/**
 * Add the order number default, this is to take into account the legacy order number.
 */
function siteorigin_theme_update_settings_defaults( $defaults ){
	$theme = basename( get_template_directory() );
	$name = 'siteorigin_order_number_' . $theme;
	$defaults['premium_order_number'] = get_option($name, false);

	return $defaults;
}
add_filter('siteorigin_theme_default_settings', 'siteorigin_theme_update_settings_defaults');

/**
 * Trigger an update check
 */
function siteorigin_theme_update_refresh( ) {
	// This tells the theme update to recheck
	set_site_transient( 'update_themes', null );
}
add_action('siteorigin_settings_changed_field_changed_premium_order_number', 'siteorigin_theme_update_refresh');