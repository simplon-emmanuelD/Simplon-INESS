<?php
/**
 * Handle the SiteOrigin theme settings panel.
 *
 * @package SiteOrigin Extras
 */


function siteorigin_settings_admin_init_action(){
	do_action('siteorigin_settings_init');
}
add_action('admin_init', 'siteorigin_settings_admin_init_action');

/**
 * Intialize the theme settings. Load settings from the database etc.
 *
 * @param $theme_name
 * @action after_setup_theme
 */
function siteorigin_settings_init( $theme_name = null ) {
	// Ensure this is only run once
	static $run;
	if(!empty($run)) return;
	$run = true;

	if ( empty( $theme_name ) ) {
		$theme_name = basename( get_template_directory() );
	}

	$GLOBALS['siteorigin_settings_theme_name'] = $theme_name;
	$GLOBALS['siteorigin_settings_name'] = $theme_name . '_theme_settings';
	$GLOBALS['siteorigin_settings_defaults'] = apply_filters( 'siteorigin_theme_default_settings', array() );

	$settings = get_option( $theme_name . '_theme_settings', array() );
	// Remove any settings with a -1 value
	if( !empty($settings) && is_array($settings) ) {
		foreach($settings as $name => $value) {
			if (intval($value) === -1) {
				unset($settings[$name]);
			}
		}
	}

	$GLOBALS['siteorigin_settings'] = wp_parse_args( $settings, $GLOBALS['siteorigin_settings_defaults'] );
	$GLOBALS['siteorigin_settings'] = apply_filters('siteorigin_settings_values', $GLOBALS['siteorigin_settings']);

	// Register all the actions for the settings page
	add_action( 'admin_menu', 'siteorigin_settings_admin_menu' );
	add_action( 'admin_init', 'siteorigin_settings_admin_init', 8 );
	add_action( 'siteorigin_adminbar', 'siteorigin_settings_adminbar' );

	add_action( 'admin_enqueue_scripts', 'siteorigin_settings_enqueue_scripts' );
}
add_action('after_setup_theme', 'siteorigin_settings_init', 5);

/**
 * Initialize admin settings in the admin
 *
 * @action admin_init
 */
function siteorigin_settings_admin_init() {
	register_setting( 'theme_settings', $GLOBALS['siteorigin_settings_name'], 'siteorigin_settings_validate' );
	if(get_theme_mod('version_activated', false) === false) {
		set_theme_mod('version_activated', SITEORIGIN_THEME_VERSION);
	}
}

/**
 * Set up the theme settings page.
 *
 * @action admin_menu
 */
function siteorigin_settings_admin_menu() {
	$theme = wp_get_theme();
	$page = add_theme_page(
		sprintf(__( '%s Theme Settings', 'vantage' ), $theme->get('Name') ),
		sprintf(__( 'Theme Settings', 'vantage' ), $theme->get('Name') ),
		'edit_theme_options',
		'theme_settings_page',
		'siteorigin_settings_render'
	);

	add_action( 'load-' . $page, 'siteorigin_settings_theme_help' );
}

/**
 * Add the Edit Home Page item to the admin bar.
 *
 * @param WP_Admin_Bar $admin_bar
 * @return WP_Admin_Bar
 */
function siteorigin_settings_admin_bar_menu($admin_bar){

	// Only display this until the theme settings have been saved for the first time
	if( get_option( get_template() . '_theme_settings', false ) !== false ) return $admin_bar;

	if( is_admin() ) {
		// Skip this on the settings page
		$screen = get_current_screen();
		if( $screen->base == 'appearance_page_theme_settings_page' ) return $admin_bar;
	}

	if( current_user_can('edit_theme_options') && has_filter('siteorigin_settings_tour_content') ){

		$admin_bar->add_node(array(
			'id' => 'theme-settings-tour',
			'title' => __('Theme Tour', 'vantage'),
			'href' => admin_url('themes.php?page=theme_settings_page#tour')
		) );
	}

	return $admin_bar;
}
add_action('admin_bar_menu', 'siteorigin_settings_admin_bar_menu', 100);

/**
 * Render the theme settings page
 */
function siteorigin_settings_render() {
	if( version_compare( get_bloginfo('version'), '3.4', '<' ) ) {
		?><div class="wrap"><div id="setting-error-settings_updated" class="updated settings-error"><p><strong><?php _e('Please update to the latest version of WordPress to use theme settings.', 'vantage') ?></strong></p></div></div><?php
		return;
	}

	locate_template( 'extras/settings/page.php', true, false );
}

/**
 * Enqueue all the settings scripts.
 *
 * @param $prefix
 */
function siteorigin_settings_enqueue_scripts( $prefix ) {
	if ( $prefix != 'appearance_page_theme_settings_page' ) return;

	$js_suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

	wp_enqueue_script( 'siteorigin-settings', get_template_directory_uri() . '/extras/settings/js/settings' . $js_suffix . '.js', array( 'jquery' ), SITEORIGIN_THEME_VERSION );
	wp_enqueue_style( 'siteorigin-settings', get_template_directory_uri() . '/extras/settings/css/settings.css', array(), SITEORIGIN_THEME_VERSION );

	if( has_filter('siteorigin_settings_tour_content') ) {
		wp_enqueue_script( 'siteorigin-settings-tour', get_template_directory_uri() . '/extras/settings/js/tour' . $js_suffix . '.js', array( 'jquery' ), SITEORIGIN_THEME_VERSION );
		wp_enqueue_style( 'siteorigin-settings-tour', get_template_directory_uri() . '/extras/settings/css/tour.css', array(  ), SITEORIGIN_THEME_VERSION );
	}

	wp_localize_script( 'siteorigin-settings', 'siteoriginSettings', array(
		'premium' => array(
			'hasPremium' => has_filter('siteorigin_premium_content'),
			'premiumUrl' => admin_url('themes.php?page=premium_upgrade'),
			'isPremium' => defined('SITEORIGIN_IS_PREMIUM'),
			'name' => apply_filters('siteorigin_premium_theme_name', ucfirst( get_option( 'template' ) ) . ' ' . __( 'Premium', 'vantage' ) ),
		),
		'tour' => array(
			'buttonText' => __('Theme Tour', 'vantage'),
			'content' => apply_filters( 'siteorigin_settings_tour_content', array() ),
		),
	) );

	if( wp_script_is( 'wp-color-picker', 'registered' ) ){
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker' );
	}
	else{
		wp_enqueue_style( 'farbtastic' );
		wp_enqueue_script( 'farbtastic' );
	}

	// We need the media editors
	wp_enqueue_media();
	
	// This is for the media uploader
	if ( function_exists( 'wp_enqueue_media' ) ) wp_enqueue_media();
}

/**
 * Add the admin bar to the settings page
 *
 * @param $bar
 * @return object|null
 */
function siteorigin_settings_adminbar( $bar ) {
	$screen = get_current_screen();
	if ( $screen->id == 'appearance_page_theme_settings_page' ) {
		$bar = (object) array( 'id' => $GLOBALS['siteorigin_settings_name'], 'message' => array( 'extras/settings/message' ) );
	}

	return $bar;
}

/**
 * Add a settings section.
 *
 * @param $id
 * @param $name
 */
function siteorigin_settings_add_section( $id, $name ) {
	// This is to prevent issues when adding settings that will only be used in the preview.
	if( is_admin() ) {
		add_settings_section( $id, $name, '__return_false', 'theme_settings' );
	}
}

/**
 * Add a setting
 *
 * @param string $section
 * @param string $id
 * @param string $type
 * @param string $title
 * @param array $args
 */
function siteorigin_settings_add_field( $section, $id, $type, $title = null, $args = array() ) {
	global $wp_settings_fields;
	if ( isset( $wp_settings_fields[ 'theme_settings' ][ $section ][ $id ] ) ) {
		if ( isset( $wp_settings_fields[ 'theme_settings' ][ $section ][ $id ][ 'args' ][ 'type' ] ) && $wp_settings_fields[ 'theme_settings' ][ $section ][ $id ][ 'args' ][ 'type' ] == 'teaser' ) {
			// Copy the args from the teaser field, then make sure we have the proper type set.
			$args = wp_parse_args( $args,  $wp_settings_fields[ 'theme_settings' ][ $section ][ $id ][ 'args' ]);
			$args['type'] = $type;

			if ( empty( $title ) && !empty( $wp_settings_fields[ 'theme_settings' ][ $section ][ $id ][ 'title' ] ) ) {
				// Copy across the title field
				$title = $wp_settings_fields[ 'theme_settings' ][ $section ][ $id ][ 'title' ];
			}
			
			// Replace the teaser field with the actual setting
			$wp_settings_fields[ 'theme_settings' ][ $section ][ $id ] = array(
				'id' => $id,
				'title' => $title,
				'callback' => 'siteorigin_settings_field',
				'args' => $args
			);
		}
		else return;
	}

	// Skip fields that don't have a title
	if( empty($title) ) return;

	$args = wp_parse_args( $args, array(
		'section' => $section,
		'field' => $id,
		'type' => $type,
	) );

	if( is_admin() ) {
		// Add the settings field if it's available (we're in the admin)
		add_settings_field( $id, $title, 'siteorigin_settings_field', 'theme_settings', $section, $args );
	}
	else {
		global $siteorigin_theme_settings_preview;
		if( empty($siteorigin_theme_settings_preview) ) $siteorigin_theme_settings_preview = array();
	}

	if ( is_admin() && $type == 'editor' && !empty($args['editor_style_formats']) ) {
		global $siteorigin_settings_editor_style_formats;
		$siteorigin_settings_editor_style_formats[$section . '_' . $id] = $args['editor_style_formats'];
	}
}

/**
 * Adds a field that might only be available in another version of the theme.
 *
 * @param $section
 * @param $id
 * @param $name
 * @param array $args
 */
function siteorigin_settings_add_teaser( $section, $id, $name, $args = array() ) {
	global $wp_settings_fields;
	if ( isset( $wp_settings_fields['theme_settings'][ $section ][ $id ] ) ) return;

	$args = wp_parse_args( $args, array(
		'section' => $section,
		'field' => $id,
		'type' => 'teaser',
	) );

	if( is_admin() ) {
		add_settings_field( $id, $name, 'siteorigin_settings_field', 'theme_settings', $section, $args );
	}
}

/**
 * Remove a setting.
 *
 * @param $section
 * @param $id
 */
function siteorigin_settings_remove_field( $section, $id ){
	global $wp_settings_fields;
	unset( $wp_settings_fields[ 'theme_settings' ][$section][$id] );
}

/**
 * Get the value of a setting, or the default value.
 *
 * @param string $name The setting name.
 * @param mixed $default The default setting.
 *
 * @return mixed
 */
function siteorigin_setting( $name , $default = null) {
	$value = null;
	
	if ( !is_null( $default ) && ( !is_bool( $GLOBALS[ 'siteorigin_settings' ][ $name ] ) && empty( $GLOBALS[ 'siteorigin_settings' ][ $name ] ) ) ) {
		return apply_filters( 'siteorigin_setting_'.$name, $default );
	}
	
	if ( !isset( $GLOBALS[ 'siteorigin_settings' ][ $name ] ) ) $value = null;
	else $value = $GLOBALS[ 'siteorigin_settings' ][ $name ];

	return apply_filters('siteorigin_setting_'.$name, $value);
}

/**
 * @param $name
 * @param null $default
 *
 * @return mixed
 */
function siteorigin_settings_get($name, $default = null){
	return siteorigin_setting($name, $default);
}

/**
 * Sets and a theme setting. Will attempt to validate if in the admin.
 *
 * @param $name
 * @param $value
 */
function siteorigin_settings_set($name, $value) {
	global $siteorigin_settings;
	$theme_name = basename( get_template_directory() );

	// Update settings in the database
	$settings = get_option( $theme_name . '_theme_settings', array() );
	$settings[$name] = $value;
	update_option( $theme_name . '_theme_settings', $settings );

	// Update the temporary value
	if( empty( $siteorigin_settings ) ) {
		$siteorigin_settings = array();
	}
	$siteorigin_settings[$name] = $value;
}

/**
 * Render a settings field.
 *
 * @param $args
 */
function siteorigin_settings_field( $args ) {
	$field_name = $GLOBALS['siteorigin_settings_name'] . '[' . $args['section'] . '_' . $args['field'] . ']';
	$field_id = $args['section'] . '_' . $args['field'];
	$current = isset( $GLOBALS['siteorigin_settings'][ $field_id ] ) ? $GLOBALS['siteorigin_settings'][ $field_id ] : null;


	$container_attr = array(
		'id' => 'siteorigin-settings-field-' . $args['section'] . '_' . $args['field'],
		'class' => 'siteorigin-settings-field',
		'data-type' => $args['type'],
		'data-field' => $field_id,
		'data-setting' => $args['section'] . '_' . $args['field'],
	);
	if( !empty($args['conditional']) ) {
		$container_attr['data-conditional'] = json_encode($args['conditional']);
	}

	?><div <?php foreach( $container_attr as $key => $val ) echo $key . '="' . esc_attr($val) . '"'; ?>><?php

	switch ( $args['type'] ) {
		case 'checkbox' :
			?>
			<div class="checkbox-wrapper">
				<input id="<?php echo esc_attr( $field_id ) ?>" name="<?php echo esc_attr( $field_name ) ?>" type="checkbox" <?php checked( $current ) ?> />
				<label for="<?php echo esc_attr( $field_id ) ?>"><?php echo esc_attr( !empty( $args['label'] ) ? $args['label'] : __( 'Enabled', 'vantage' ) ) ?></label>
			</div>
			<?php
			break;
		case 'text' :
		case 'number' :
			?>
			<input
				id="<?php echo esc_attr( $field_id ) ?>"
				name="<?php echo esc_attr( $field_name ) ?>"
				class="<?php echo esc_attr( $args['type'] == 'number' ? 'small-text' : 'regular-text' ); if( !empty($args['options']) ) echo ' siteorigin-settings-has-options'; ?>"
				size="25"
				type="<?php echo esc_attr( $args['type'] ) ?>"
				value="<?php echo esc_attr( $current ) ?>" />
			<?php if( !empty( $args['options'] ) ) : $args['options'] = apply_filters('siteorigin_setting_options_'.$field_id, $args['options']); ?>
				<select class="input-field-select">
					<option></option>
					<?php foreach($args['options'] as $value => $label) : ?>
						<option value="<?php echo esc_attr($value) ?>"><?php echo esc_html($label) ?></option>
					<?php endforeach; ?>
				</select>
			<?php endif;
			break;

		case 'select' :
			$args['options'] = apply_filters('siteorigin_setting_options_'.$field_id, $args['options']);
			?>
			<select id="<?php echo esc_attr( $field_id ) ?>" name="<?php echo esc_attr( $field_name ) ?>">
				<?php foreach ( $args['options'] as $option_id => $label ) : ?>
					<option value="<?php echo esc_attr( $option_id ) ?>" <?php selected( $option_id, $current ) ?>><?php echo esc_attr( $label ) ?></option>
				<?php endforeach ?>
			</select>
			<?php
			break;

		case 'textarea' :
			?><textarea id="<?php echo esc_attr( $field_id ) ?>" name="<?php echo esc_attr( $field_name ) ?>" class="large-text" rows="3"><?php echo esc_textarea( $current ) ?></textarea><?php
			break;

		case 'color' :
			if( wp_script_is('wp-color-picker', 'registered') ){
				?><input type="text" value="<?php echo esc_attr( $current ) ?>" class="color-field" name="<?php echo esc_attr( $field_name ) ?>" /><?php
			}
			else{
				?>
				<div class="colorpicker-wrapper">
					<div class="color-indicator" style="background-color: <?php echo esc_attr( $current ) ?>"></div>
					<input type="text" id="<?php echo esc_attr( $field_id ) ?>" value="<?php echo esc_attr( $current ) ?>" name="<?php echo esc_attr( $field_name ) ?>" />

					<div class="farbtastic-container"></div>
				</div>
				<?php
			}
			break;
		
		case 'media':
			if(version_compare(get_bloginfo('version'), '3.5', '<')){
				printf(__('You need to <a href="%s">upgrade</a> to WordPress 3.5 to use media fields', 'vantage'), admin_url('update-core.php'));
				break;
			}

			if(!empty($current)) {
				if(is_array($current)) {
					$src = $current;
				}
				else {
					$post = get_post($current);
					$src = wp_get_attachment_image_src($current, 'thumbnail');
					if(empty($src)) $src = wp_get_attachment_image_src($current, 'thumbnail', true);
				}
			}
			else{
				$src = array('', 0, 0);
			}

			$choose_title = empty($args['choose']) ? __('Choose Media', 'vantage') : $args['choose'];
			$update_button = empty($args['update']) ? __('Set Media', 'vantage') : $args['update'];
			
			?>
				<div class="media-field-wrapper">
					<div class="current">
						<div class="thumbnail-wrapper">
							<img src="<?php echo esc_url($src[0]) ?>" class="thumbnail" <?php if(empty($src[0])) echo "style='display:none'" ?> />
						</div>
						<div class="title"><?php if(!empty($post)) echo esc_attr($post->post_title) ?></div>
					</div>
					<a href="#" class="media-upload-button" data-choose="<?php echo esc_attr($choose_title) ?>" data-update="<?php echo esc_attr($update_button) ?>">
						<?php echo esc_html($choose_title) ?>
					</a>

					<a href="#" class="media-remove-button"><?php _e('Remove', 'vantage') ?></a>
				</div>

				<input type="hidden" id="<?php echo esc_attr( $field_id ) ?>" value="<?php echo esc_attr( is_array( $current ) ? '-1' : $current ) ?>" name="<?php echo esc_attr( $field_name ) ?>" />
				<div class="clear"></div>
			<?php
			break;
		
		case 'teaser' :
			$theme = get_option( 'template' );
			?>
			<a class="premium-teaser siteorigin-premium-teaser" href="<?php echo admin_url( 'themes.php?page=premium_upgrade' ) ?>" target="_blank">
				<em></em>
				<?php printf( __( 'Only available in <strong>%s</strong> - <strong class="upgrade">Upgrade Now</strong>', 'vantage' ), apply_filters('siteorigin_premium_theme_name', ucfirst($theme) . ' ' . __( 'Premium', 'vantage' ) ) ) ?>
				<?php if(!empty($args['teaser-image'])) : ?>
					<div class="teaser-image"><img src="<?php echo esc_url($args['teaser-image']) ?>" width="220" height="120" /><div class="pointer"></div></div>
				<?php endif; ?>
			</a>
			<?php
			break;

		case 'gallery' :
			?>
			<input
				id="<?php echo esc_attr( $field_id ) ?>"
				name="<?php echo esc_attr( $field_name ) ?>"
				class="regular-text gallery-ids"
				size="25"
				type="text"
				value="<?php echo esc_attr( $current ) ?>" />
			<a href="#" class="so-settings-gallery-edit"><?php _e('Select Images', 'vantage') ?></a>
			<?php
			break;

		case 'pages' :
			$pages = get_posts( array(
				'post_type' => 'page',
				'numberposts' => 200,
				'post_status' => empty($args['unpublished']) ? 'publish' : 'any',
			) );
			?>
			<select id="<?php echo esc_attr( $field_id ) ?>" name="<?php echo esc_attr( $field_name ) ?>">
				<option value="0"><?php _e('None', 'vantage') ?></option>
				<?php foreach ( $pages as $page ) : ?>
					<option value="<?php echo $page->ID ?>" <?php selected($page->ID, $current) ?>><?php echo esc_attr($page->post_title) ?></option>
				<?php endforeach ?>
			</select>
			<?php
			break;

		case 'editor' :
			$editor_settings = wp_parse_args( !empty($args['settings']) ? $args['settings'] : array(), array(
				'textarea_name' => $field_name,
				'textarea_rows' => 8,
			) );
			wp_editor( $current, $field_id, $editor_settings );
			break;

		case 'widget' :
			if(empty($args['widget_class'])) break;

			if( !class_exists($args['widget_class']) && !empty($args['bundle_widget']) && class_exists('SiteOrigin_Widgets_Bundle') ) {
				// If this is a widget bundle widget, and the class isn't available, then try activate it.
				SiteOrigin_Widgets_Bundle::single()->activate_widget($args['bundle_widget']);
			}

			if( !class_exists($args['widget_class']) ) {
				// Display the message prompting the user to install the widget plugin from WordPress.org
				?><div class="so-settings-widget-form"><?php
				printf( __('This field requires the %s plugin. ', 'vantage'), $args['plugin_name']);
				if( function_exists('siteorigin_plugin_activation_install_url') ) {
					$install_url = siteorigin_plugin_activation_install_url($args['plugin'], $args['plugin_name']);
					printf( __('<a href="%s" target="_blank">Install %s</a> now. ', 'vantage'), $install_url, $args['plugin_name']);
				}
				?></div>
				<input type="hidden" id="<?php echo esc_attr( $field_id ) ?>" name="<?php echo esc_attr( $field_name ) ?>" value="<?php echo esc_attr( serialize( $current ) ) ?>" /><?php
			}
			else {
				// Render the widget form
				$the_widget = new $args['widget_class']();
				$the_widget->id = $field_id;
				$the_widget->number = $field_id;

				ob_start();
				$the_widget->form( $current );
				$form = ob_get_clean();

				// Convert the widget field naming into ones that Settings will use
				$exp = preg_quote( $the_widget->get_field_name('____') );
				$exp = str_replace('____', '(.*?)', $exp);
				$form = preg_replace( '/'.$exp.'/', 'siteorigin_settings_widget['.preg_quote($field_id).'][$1]', $form );


				?>
				<div class="so-settings-widget-form">
					<script type="text/template" class="so-settings-widget-form-template">
						<?php echo $form ?>
					</script>
					<a href="#" class="so-settings-widget-edit" data-is-setup="0"><?php _e('Edit', 'siteorgin') ?></a>
				</div>
				<input type="hidden" id="<?php echo esc_attr( $field_id ) ?>" name="<?php echo esc_attr( $field_name ) ?>" value="<?php echo esc_attr( serialize( $current ) ) ?>" />
				<?php
			}
			break;

		default :
			_e( 'Unknown Field Type', 'vantage' );
			break;
	}

	?></div><?php

	if ( !empty( $args['description'] ) ) echo '<p class="description">' . $args['description'] . '</p>';
}

/**
 * Validate the settings values
 *
 * @param $values
 * @param $set_tab
 *
 * @return array
 */
function siteorigin_settings_validate( $values, $set_tab = true ) {
	if( is_admin() ) {
		global $wp_settings_fields;
		$theme_settings = !empty($wp_settings_fields['theme_settings']) ? $wp_settings_fields['theme_settings'] : array();
	}
	else {
		global $siteorigin_theme_settings_preview;
		$theme_settings = !empty($siteorigin_theme_settings_preview) ? $siteorigin_theme_settings_preview : array();
	}


	$theme_name = basename( get_template_directory() );
	$current = get_option( $theme_name . '_theme_settings', array() );

	if($set_tab) set_theme_mod( '_theme_settings_current_tab', isset( $_REQUEST['theme_settings_current_tab'] ) ? $_REQUEST['theme_settings_current_tab'] : 0 );

	$changed = false;
	foreach ( $theme_settings as $section_id => $fields ) {
		foreach ( $fields as $field_id => $field ) {
			$name = $section_id . '_' . $field_id;

			if( !isset($values[$name]) ) {
				$values[$name] = false;
				continue;
			}

			if( !empty($field['args']['options']) ){
				$field['args']['options'] = apply_filters('siteorigin_setting_options_'.$name, $field['args']['options']);
			}

			switch($field['args']['type']){
				case 'text' :
					$values[ $name ] = wp_kses_post( $values[ $name ] );
					$values[ $name ] = balanceTags( $values[ $name ] , true );
					break;

				case 'checkbox' :
					// Only allow true or false values
					$values[ $name ] = !empty( $values[ $name ] );
					break;
				
				case 'number' :
					// Only allow integers
					$values[ $name ] = isset( $values[ $name ] ) ? intval( $values[ $name ] ) : $GLOBALS['siteorigin_settings_defaults'][ $name ];
					// Check that the number falls within a max/min range if required
					if( isset($field['args']['max']) ) {
						$values[ $name ] = min( $values[ $name ], $field['args']['max'] );
					}
					if( isset($field['args']['min']) ) {
						$values[ $name ] = max( $values[ $name ], $field['args']['min'] );
					}
					break;
				
				case 'media' :
					// Only allow valid attachment post ids
					if( $values[ $name ] != -1 ) {
						$attachment = get_post( $values[ $name ] );
						if(empty($attachment) || $attachment->post_type != 'attachment') $values[ $name ] = '';
					}
					break;

				case 'select':
					if( !empty($field['args']['options']) && is_array($field['args']['options']) ) {
						// Make sure the value is in the options.
						if( !isset($field['args']['options'][ $values[$name] ]) ) $values[$name] = '';
					}
					break;

				case 'widget' :
					if( !class_exists($field['args']['widget_class']) ) {
						$values[ $name ] = !empty($values[ $name ]) ? unserialize( $values[ $name ] ) : false;
					}
					else if( !empty( $_POST['siteorigin_settings_widget'] ) && !empty($_POST['siteorigin_settings_widget'][$name]) ) {
						$widget_values = stripslashes_deep($_POST['siteorigin_settings_widget'][$name]);
						$the_widget = new $field['args']['widget_class']();
						$values[ $name ] = $the_widget->update( $widget_values, !empty($current[$name]) ? $current[$name] : array() );
					}
					else {
						$values[ $name ] = unserialize( $values[ $name ] );
					}
					break;

				case 'editor':
				case 'text':
					$values[ $name ] = sanitize_text_field( $values[ $name ] );
					break;
			}

			if ( !isset( $current[ $name ] ) || ( isset( $values[ $name ] ) && isset( $current[ $name ] ) && $values[ $name ] != $current[ $name ] ) ) {
				// Trigger an action that a field has changed
				do_action('siteorigin_settings_changed_field_changed', $name, isset($values[$name]) ? $values[$name] : null, isset($current[$name]) ? $current[$name] : null);
				do_action('siteorigin_settings_changed_field_changed_'.$name, isset($values[$name]) ? $values[$name] : null, isset($current[$name]) ? $current[$name] : null);
				$changed = true;
			}

			// See if this needs any special validation
			if ( !empty( $field['args']['validator'] ) && method_exists( 'SiteOrigin_Settings_Validator', $field['args']['validator'] ) ) {
				$values[ $name ] = call_user_func( array( 'SiteOrigin_Settings_Validator', $field['args']['validator'] ), $values[ $name ] );
			}

		}
	}

	if ( $changed ) {
		do_action( 'siteorigin_settings_changed' );
		set_theme_mod( 'siteorigin_settings_changed', true );
	}

	return $values;
}

/**
 * Display a message when the settings have been changed
 */
function siteorigin_settings_change_message() {
	if ( get_theme_mod( 'siteorigin_settings_changed' ) ) {
		remove_theme_mod( 'siteorigin_settings_changed' );

		?>
		<div id="setting-updated" class="updated">
			<p>
				<strong><?php _e( 'Settings saved.', 'vantage' ) ?></strong>
			</p>
		</div>
		<?php

		/**
		 * This is an action that the theme can use to display a settings changed message.
		 */
		do_action( 'siteorigin_settings_changed_message' );
	}
}

function siteorigin_settings_theme_help(){
	if( !is_admin() || !function_exists('get_current_screen') ) return;

	$screen = get_current_screen();
	$theme_name = basename( get_template_directory() );
	
	$text = sprintf(
		__( "Read %s's <a href='%s' target='_blank'>theme documentation</a> for help with these settings.", 'vantage' ),
		ucfirst($theme_name),
		'http://siteorigin.com/theme/'.$theme_name.'/?action=docs'
	); 
	
	$screen->add_help_tab( array(
		'id' => 'siteorigin_settings_help_tab',
		'title' => __( 'Settings Help', 'vantage' ),
		'content' => '<p>' . $text . '</p>',
	) );
}

/**
 * Gets all template layouts
 */
function siteorigin_settings_template_part_names($parts, $part_name){
	$return = array();

	$parent_parts = glob( get_template_directory().'/'.$parts.'*.php' );
	$child_parts = glob( get_stylesheet_directory().'/'.$parts.'*.php' );

	$files = array_unique( array_merge(
		!empty($parent_parts) ? $parent_parts : array(),
		!empty($child_parts) ? $child_parts : array()
	) );

	if( !empty($files) ) {
		foreach( $files as $file ) {
			$p = pathinfo($file);
			$filename = explode('-', $p['filename'], 2);
			$name = isset($filename[1]) ? $filename[1] : '';

			$info = get_file_data($file, array(
				'name' => $part_name,
			) );

			$return[$name] = $info['name'];
		}
	}

	ksort($return);
	return $return;
}

function siteorigin_settings_media_view_strings($strings, $post){
	if( !empty($post) ) return $strings;
	if( !is_admin() || !function_exists('get_current_screen') ) return $strings; // Skip this on front end usage

	$screen = get_current_screen();
	if(empty($screen->id) || $screen->id != 'appearance_page_theme_settings_page') return $strings;
	
	// Remove these strings, to remove the tabs
	// Luckily the JS gracefully handles these being unset
	unset($strings['createNewGallery']);
	unset($strings['createGalleryTitle']);
	unset($strings['insertFromUrlTitle']);
	
	$strings['insertIntoPost'] = __('Set Media File', 'vantage');
	
	return $strings;
}
add_filter('media_view_strings', 'siteorigin_settings_media_view_strings', 10, 2);

/**
 * Add editor formats for theme settings page
 */
function siteorigin_settings_add_editor_formats( $init_array ){
	// This ensures that we're in the admin. Not adding this line can cause problems with some plugins
	if( !function_exists('get_current_screen') ) return $init_array;

	// Make sure we're on the theme settings page
	$screen = get_current_screen();
	if( !empty($screen) && $screen->base == 'appearance_page_theme_settings_page' && !empty($init_array) ) {
		global $siteorigin_settings_editor_style_formats;
		if( isset( $siteorigin_settings_editor_style_formats[ $init_array['body_class'] ] ) ) {
			$init_array['style_formats'] = json_encode( $siteorigin_settings_editor_style_formats[ $init_array['body_class'] ] );
		}
	}

	return $init_array;
}
add_filter('tiny_mce_before_init', 'siteorigin_settings_add_editor_formats');

function siteorigin_settings_add_editor_styles_button($buttons){
	// This ensures that we're in the admin. Not adding this line can cause problems with some plugins
	if( !function_exists('get_current_screen') ) return $buttons;

	// Make sure we're on the theme settings page
	$screen = get_current_screen();
	if( !empty($screen) && $screen->base == 'appearance_page_theme_settings_page' && is_array($buttons) ) {
		array_unshift($buttons, 'styleselect');
	}


	return $buttons;
}
add_filter('mce_buttons_2', 'siteorigin_settings_add_editor_styles_button');

function siteorigin_settings_add_slider_options($options){
	// Add all Meta Sliders
	if( class_exists('MetaSliderPlugin') ){
		$sliders = get_posts(array(
			'post_type' => 'ml-slider',
			'numberposts' => 100,

		));

		foreach($sliders as $slider) {
			$options['[metaslider id="'.$slider->ID.'"]'] = __('Meta Slider: ', 'vantage').$slider->post_title;
		}
	}

	// Add all the Revolution sliders
	if( function_exists('rev_slider_shortcode') ) {
		global $wpdb;
		$sliders = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}revslider_sliders ORDER BY title");

		foreach($sliders as $slider) {
			$options['[rev_slider '.$slider->alias.']'] = __('Revolution Slider: ', 'vantage').$slider->title;
		}
	}

	// Add any LayerSlider Sliders
	if( function_exists('layerslider') ) {
		global $wpdb;
		$sliders = $wpdb->get_results("SELECT id,name FROM {$wpdb->prefix}layerslider ORDER BY name");

		foreach($sliders as $slider) {
			$options['[layerslider id="'.$slider->id.'"]'] = __('LayerSlider: ', 'vantage').$slider->name;
		}
	}

	return $options;
}

/**
 * Settings validators.
 */
class SiteOrigin_Settings_Validator {
	/**
	 * Extracts the twitter username from the string.
	 *
	 * @static
	 * @param $twitter
	 * @return bool|mixed|string
	 */
	static function twitter( $twitter ) {
		$twitter = trim( $twitter );
		if ( empty( $twitter ) ) return false;
		if ( $twitter[ 0 ] == '@' ) return preg_replace( '/^@+/', '', $twitter );

		$url = parse_url( $twitter );

		// Check if this is a twitter URL
		if ( isset( $url['host'] ) && !in_array( $url['host'], array( 'twitter.com', 'www.twitter.com' ) ) ) return false;

		// Check if this is a fragment URL
		if ( isset( $url['fragment'] ) && $url['fragment'][ 0 ] == '!' )
			return substr( $url['fragment'], 2 );

		// And our very last attempt... take it that the username is on the end of the path
		if ( isset( $url['path'] ) ) {
			$parts = explode( '/', $url['path'] );
			$username = array_pop( $parts );
			return $username;
		}

		return false;
	}
}

/**
 * Initialize the theme settings preview.
 */
function siteorigin_settings_preview_init(){

	if( !is_admin() &&
	    current_user_can('edit_theme_options') &&
	    !empty($_POST['siteorigin_settings_is_preview']) &&
	    !empty($_POST[basename( get_template_directory() ) . '_theme_settings']) &&
	    wp_verify_nonce($_POST['_wpnonce'], 'theme_settings-options')
	) {
		// We're in a preview mode, so filter the settings and hide the admin bar
		add_filter('siteorigin_settings_values', 'siteorigin_settings_preview_values');
		// Hide the admin bar - this is only involved when an administrator is previewing the theme settings (see previous IF statement).
		add_filter('show_admin_bar', '__return_false');
	}
}
add_action('after_setup_theme', 'siteorigin_settings_preview_init', 4); // This must run before we initialize the settings

/**
 * Filter SiteOrigin settings for the preview.
 * @param $values
 * @return array
 */
function siteorigin_settings_preview_values($values){
	do_action('siteorigin_settings_init');
	$post_values = siteorigin_settings_validate( stripslashes_deep( $_POST[basename( get_template_directory() ) . '_theme_settings'] ) , false );
	return $post_values;
}