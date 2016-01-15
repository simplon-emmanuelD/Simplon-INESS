<?php

define( 'WPTOUCH_PRO_ADMIN_SETTINGS_PAGE', 0 );
define( 'WPTOUCH_PRO_ADMIN_CUSTOM_PAGE', 1 );

require_once( WPTOUCH_DIR . '/core/admin-menu.php' );

function wptouch_admin_menu_get_nonce() {
	return wp_create_nonce( 'admin_nonce' );
}

function wptouch_admin_menu_the_nonce() {
	echo wptouch_admin_get_nonce();
}

function wptouch_admin_menu_nonce_is_valid( $nonce ) {
	return wp_verify_nonce( $nonce, 'admin_nonce' );
}

function wptouch_admin_check_api() {
	require_once( WPTOUCH_DIR . '/core/bncid.php' );
	wptouch_check_api();
}


function wptouch_admin_build_menu( $network_admin = false ) {
	wptouch_admin_check_api();

	$settings = wptouch_get_settings();

	$available_menus = wptouch_admin_get_predefined_menus( $network_admin );

	// Add the main plugin menu for WPtouch Pro
	add_menu_page(
		WPTOUCH_PRODUCT_NAME,
		WPTOUCH_PRODUCT_NAME,
		'manage_options',
		wptouch_admin_get_root_slug( $network_admin ),
		'',
		WPTOUCH_ADMIN_URL . '/images/wptouch-admin-icon.png'
	);

	// Iterate through available menus
	foreach( $available_menus as $id => $menu ) {
		add_submenu_page(
			$available_menus[ wptouch_admin_get_root_slug( $network_admin ) ]->slug,
			$menu->friendly_name,
			$menu->friendly_name,
			'manage_options',
			$menu->slug,
			'wptouch_admin_render_menu'
		);
	}
}

function wptouch_admin_build_network_menu() {
	wptouch_admin_build_menu( true );
}

function wptouch_add_sub_page( $sub_page_name, $sub_page_slug, &$options ) {
	$sub_page_info = new stdClass;
	$sub_page_info->name = $sub_page_name;
	$sub_page_info->slug = $sub_page_slug;
	$sub_page_info->sections = array();

	$options[ $sub_page_name ] = $sub_page_info;
}

function wptouch_add_page_section( $sub_page_name, $section_name, $section_slug, $section_settings, &$options, $domain = 'wptouch_pro', $use_customizer = false, $section_description = false, $section_weight = 50 ) {
	$section = new stdClass;

	$skip_domains = array( ADDON_SETTING_DOMAIN, 'wptouch_pro', 'bncid' );
	$skip_pages = array( 'Compatibility', 'Web-App Mode', 'Basic Ads', 'General' );

	if ( defined( 'WPTOUCH_IS_FREE' ) ) {
		$use_customizer = false;
	}

	if ( !$use_customizer && !in_array( $domain, $skip_domains ) && !in_array( $sub_page_name, $skip_pages ) ) {
		$sub_page_name = 'Theme Settings';
	} elseif ( $use_customizer ) {
	 	$sub_page_name = 'Customizer';
	}

	if ( isset( $options[ $sub_page_name ] ) || $sub_page_name == 'Customizer' ) {
		$section->sub_page_name = $sub_page_name;
		$section->name = $section_name;
		$section->slug = $section_slug;
		$section->settings = $section_settings;
		$section->domain = $domain;
		$section->use_customizer = $use_customizer;
		$section->description = $section_description;
		$section->weight = $section_weight;

		// Populate domain on default settings
		foreach( $section->settings as $setting ) {
			if ( !$setting->domain ) {
				$setting->domain = $section->domain;
			}
		}

		$options[ $sub_page_name ]->sections[ $section_name ] = $section;
	}
}

function _wptouch_add_setting( $type, $name, $desc = '', $tooltip = '', $level = WPTOUCH_SETTING_BASIC, $version = false, $extra = false, $domain = '', $is_pro = false, $prefix = false ) {
	$setting = new stdClass;

	if ( $type == 'radiolist' || $type == 'radio' ) {
		$type = 'list';
	}

	if ( defined( 'WPTOUCH_IS_FREE' ) && $type == 'select' ) {
		$type = 'list';
	}

	if ( defined( 'WPTOUCH_IS_FREE' ) && $type == 'range' ) {
		$type = 'text';
	}

	if ( defined( 'WPTOUCH_IS_FREE' ) && $type == 'url' ) {
		$type = 'text';
	}

	$setting->type = $type;
	$setting->name = $name;
	$setting->desc = $desc;
	$setting->level = $level;
	$setting->tooltip = $tooltip;
	$setting->version = $version;
	$setting->extra = $extra;
	$setting->domain = $domain;
	$setting->is_pro = $is_pro;

	return $setting;
}

function wptouch_add_pro_setting( $type, $name, $desc = '', $tooltip = '', $level = WPTOUCH_SETTING_BASIC, $version = false, $extra = false, $domain = '' ) {
	return _wptouch_add_setting( $type, $name, $desc, $tooltip, $level, $version, $extra, $domain, true );
}

function wptouch_add_setting( $type, $name, $desc = '', $tooltip = '', $level = WPTOUCH_SETTING_BASIC, $version = false, $extra = false, $domain = '' ) {
	return _wptouch_add_setting( $type, $name, $desc, $tooltip, $level, $version, $extra, $domain, false );
}

function wptouch_admin_render_menu() {
	global $panel_options;
	global $wptouch_panel_slug;

	// Determine which menu generated this page
	$page_name = $_GET[ 'page' ];
	$admin_panel_name = $page_name . '.php';

	$wptouch_panel_slug = $page_name;

	if ( file_exists( WPTOUCH_ADMIN_DIR . '/pages/' . $admin_panel_name ) ) {
		require_once( WPTOUCH_ADMIN_DIR . '/pages/' . $admin_panel_name );
	}

	$panel_options = apply_filters( 'wptouch_admin_page_render_' . $page_name, $panel_options );

	if ( $page_name == WPTOUCH_PRO_ADMIN_GENERAL_SETTINGS || wptouch_is_customizing_mobile() ) {
		$panel_options = apply_filters( 'wptouch_admin_page_render_wptouch-admin-theme-settings', $panel_options );
	}

	include( WPTOUCH_DIR . '/core/admin-render.php' );
}

function wptouch_admin_render_setting( $setting ) {
	require_once( WPTOUCH_DIR . '/core/settings.php' );
	// Check if this is a custom setting
	if ( $setting->type == 'custom' ) {
		return wptouch_admin_render_special_setting( $setting );
	}

	$setting_filename = $setting->type . '.php';

	$directories = array( WPTOUCH_ADMIN_DIR . '/settings', WPTOUCH_DIR . '/pro/settings' );
	$rendered = false;
	foreach( $directories as $dir ) {
		if ( file_exists( $dir . '/html/' . $setting_filename ) ) {
			wptouch_admin_prime_setting_for_display( $setting );

			// Load associated setting code if it exists
			if ( file_exists( $dir . '/include/' . $setting_filename ) ) {
				require_once( $dir . '/include/' . $setting_filename );
			}

			include( $dir . '/html/' . $setting_filename );

			$rendered = true;
			break;
		}
	}

	if ( !$rendered ) {
		do_action( 'wptouch_admin_render_setting', $setting );
	}
}

function wptouch_admin_render_special_setting( $setting ) {
	require_once( WPTOUCH_DIR . '/core/settings.php' );

	if ( $setting->type == 'custom' ) {
		$setting_filename = $setting->name . '.php';

		$directories = array( WPTOUCH_ADMIN_DIR . '/settings', WPTOUCH_DIR . '/pro/settings' );
		foreach( $directories as $dir ) {
			if ( file_exists( $dir . '/html/' . $setting_filename ) ) {
				wptouch_admin_prime_setting_for_display( $setting );

				// Load associated setting code if it exists
				if ( file_exists($dir . '/include/' . $setting_filename ) ) {
					require_once( $dir . '/include/' . $setting_filename );
				}

				include( $dir . '/html/' . $setting_filename );

				break;
			}
		}
	}
}

function wptouch_admin_get_menu_friendly_name( $slug = false ) {
	if ( !$slug ) {
		global $wptouch_panel_slug;
		$slug = $wptouch_panel_slug;
	}

	$menu_pages = wptouch_admin_get_predefined_menus();
	$friendly_name = '';

	foreach( $menu_pages as $page ) {
		if ( $page->slug == $slug ) {
			if ( $page->display_name ) {
				$friendly_name = $page->display_name;
			} else {
				$friendly_name = $page->friendly_name;
			}
			break;
		}
	}

	return $friendly_name;
}

function wptouch_admin_the_menu_friendly_name( $slug = false ) {
	echo wptouch_admin_get_menu_friendly_name( $slug );
}

function wptouch_admin_is_custom_page( $slug = false ) {
	if ( !$slug ) {
		global $wptouch_panel_slug;
		$slug = $wptouch_panel_slug;
	}

	$menu_pages = wptouch_admin_get_predefined_menus();

	if ( isset( $menu_pages[ $slug ] ) ) {
		return ( $menu_pages[ $slug ]->menu_type == WPTOUCH_PRO_ADMIN_CUSTOM_PAGE );
	}
}

function wptouch_admin_render_custom_page( $slug = false ) {
	require_once( WPTOUCH_DIR . '/core/settings.php' );

	if ( !$slug ) {
		global $wptouch_panel_slug;
		$slug = $wptouch_panel_slug;
	}

	$admin_panel_name = $wptouch_panel_slug . '.php';

	if ( file_exists( WPTOUCH_ADMIN_DIR . '/pages/custom/' . $admin_panel_name ) ) {
		require_once( WPTOUCH_ADMIN_DIR . '/pages/custom/' . $admin_panel_name );
	}

	$panel_options = do_action( 'wptouch_admin_page_render_custom', $admin_panel_name );
}

function wptouch_section_has_visible_settings( $section ) {
	$viewable_settings = 0;

	$settings = wptouch_get_settings();

	if ( isset( $section->settings) && is_array( $section->settings ) && count( $section->settings ) ) {
		foreach( $section->settings as $setting ) {
			if ( !$setting->is_pro || !defined( 'WPTOUCH_IS_FREE' ) ) {
				// This setting is viewable
				$viewable_settings++;
			}
		}
	}

	return ( $viewable_settings > 0 );
}

function wptouch_admin_panel_get_classes( $classes = false ) {
	if ( $classes ) {
		if ( is_array( $classes ) ) {
			$final_classes = $classes;
		} else {
			$final_classes = array( $classes );
		}
	} else {
		$final_classes = array();
	}

	global $wptouch_pro;
	$final_classes[] = 'wplocale-' . $wptouch_pro->locale;

	if ( wptouch_should_show_license_nag() ) {
		$final_classes[] = 'unlicensed';
	} else {
		$final_classes[] = 'licensed';
	}

	if ( defined( 'WPTOUCH_IS_FREE' ) ) {
		$final_classes[] = 'wptouch-free';
	} else {
		$final_classes[] = 'wptouch-not-free';
	}

	if ( strpos( $_SERVER['REQUEST_URI'], 'wptouch-admin-license' ) == true ) {
		$final_classes[] = 'remodal-bg';
	}

	if ( $wptouch_pro->cache_smash->is_cache_plugin_detected() && !$wptouch_pro->cache_smash->is_cache_configured() ) {
		$final_classes[] = 'cache-not-configured';
	}

	return $final_classes;
}

function wptouch_admin_panel_classes( $classes = false ) {
	echo implode( ' ', wptouch_admin_panel_get_classes( $classes ) );
}
