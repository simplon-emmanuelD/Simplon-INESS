<?php

define('SITEORIGIN_PANELS_LITE_VERSION', '1.0');

include get_template_directory() . '/inc/panels-lite/inc/plugin-activation.php';
include get_template_directory() . '/inc/panels-lite/inc/css.php';
include get_template_directory() . '/inc/panels-lite/inc/default-styles.php';
include get_template_directory() . '/inc/panels-lite/inc/widgets.php';

/**
 * Add the admin menu entries
 */
function siteorigin_panels_lite_admin_menu(){
	add_theme_page(
		__('Custom Home Page Builder', 'vantage'),
		__('Home Page', 'vantage'),
		'edit_theme_options',
		'so_panels_home_page',
		'siteorigin_panels_lite_render_admin_home_page'
	);
}
add_action('admin_menu', 'siteorigin_panels_lite_admin_menu');

/**
 * Render the page used to build the custom home page.
 */
function siteorigin_panels_lite_render_admin_home_page(){
	add_meta_box( 'so-panels-panels', __( 'Page Builder', 'vantage' ), 'siteorigin_panels_metabox_render', 'appearance_page_so_panels_home_page', 'advanced', 'high' );

	if(isset($_GET['_wpnonce']) && isset($_GET['toggle']) && wp_verify_nonce($_GET['_wpnonce'], 'toggle_panels_home')){
		// Update home page enabled setting
		set_theme_mod('siteorigin_panels_home_page_enabled', (bool) $_GET['panels_new']);
	}

	get_template_part('inc/panels-lite/tpl/admin', 'home-page');
}

/**
 * Handle the action for toggling the value of the home page theme mod
 */
function siteorigin_panels_lite_handle_toggle(){
	if( !current_user_can('edit_theme_options') ) exit();
	if( empty($_GET['_wpnonce']) || !wp_verify_nonce($_GET['_wpnonce'], 'toggle_panels_home') ) exit();
	set_theme_mod('siteorigin_panels_home_page_enabled', (bool) $_GET['panels_new']);

	if( !empty($_GET['redirect']) ) {
		wp_redirect( $_GET['redirect'] );
	}
	else {
		wp_redirect( admin_url('themes.php?page=so_panels_home_page') );
	}

	exit();
}
add_action('wp_ajax_panels_lite_toggle', 'siteorigin_panels_lite_handle_toggle');

/**
 * Enqueue any required admin scripts.
 *
 * @param $prefix
 */
function siteorigin_panels_lite_enqueue_admin($prefix){
	if($prefix == 'appearance_page_so_panels_home_page'){
		wp_enqueue_style('siteorigin-panels-lite-teaser', get_template_directory_uri().'/inc/panels-lite/css/panels-admin.css');
	}

	if( ( $prefix == 'post.php' || $prefix == 'post-new.php' ) ) {
		$install_url = siteorigin_panels_lite_plugin_activation_install_url();

		if( current_user_can( 'install_plugins' ) ) {
			$js_suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
			wp_enqueue_script( 'siteorigin-panels-lite-teaser', get_template_directory_uri() . '/inc/panels-lite/js/tab' . $js_suffix . '.js', array( 'jquery' ), SITEORIGIN_PANELS_LITE_VERSION );
			wp_localize_script( 'siteorigin-panels-lite-teaser', 'panelsLiteTeaser', array(
				'tab'        => __( 'Page Builder', 'vantage' ),
				'buttons'    => array(
					'install'   => __('Install', 'vantage'),
					'cancel'    => __('Cancel', 'vantage'),
				),
				'message'    => __( "Refresh this page after you've installed Page Builder.", 'vantage' ),
				'contentUrl' => admin_url('admin-ajax.php?action=panels_lite_install_content'),
				'installUrl' => $install_url
			) );

			wp_enqueue_style( 'siteorigin-panels-lite-teaser', get_template_directory_uri() . '/inc/panels-lite/css/post-teaser.css', array(), SITEORIGIN_PANELS_LITE_VERSION );
		}
	}
}
add_action('admin_enqueue_scripts', 'siteorigin_panels_lite_enqueue_admin');

/**
 * The admin action for displaying content
 */
function siteorigin_panels_lite_install_content(){
	if( !current_user_can( 'install_plugins' ) ) exit();

	get_template_part('inc/panels-lite/tpl/install');
	exit();
}
add_action('wp_ajax_panels_lite_install_content', 'siteorigin_panels_lite_install_content');

/**
 * Get a setting value
 *
 * @param bool $key
 * @return mixed|null|void
 */
function siteorigin_panels_lite_setting($key = false){

	$settings = get_theme_support('siteorigin-panels');
	if(!empty($settings)) $settings = $settings[0];
	else $settings = array();

	$settings = wp_parse_args( $settings, array(
		'home-page' => false,																								// Is the home page supported
		'home-page-default' => false,																						// What's the default prebuilt layout for the home page?
		'home-template' => 'home-panels.php',																				// The file used to render a home page.
		'home-demo-template' => false,																				        // The file used to render the home page demo.
		'post-types' => get_option('siteorigin_panels_post_types', array('page', 'post') ),									// Post types that can be edited.

		'bundled-widgets' => !isset( $display_settings['bundled-widgets'] ) ? true : $display_settings['bundled-widgets'],	// Include bundled widgets.
		'responsive' => !isset( $display_settings['responsive'] ) ? true : $display_settings['responsive'],				    // Should we use a responsive layout
		'mobile-width' => !isset( $display_settings['mobile-width'] ) ? 780 : $display_settings['mobile-width'],			// What is considered a mobile width?

		'margin-bottom' => !isset( $display_settings['margin-bottom'] ) ? 30 : $display_settings['margin-bottom'],			// Bottom margin of a cell
		'margin-sides' => !isset( $display_settings['margin-sides'] ) ? 30 : $display_settings['margin-sides'],				// Spacing between 2 cells
		'affiliate-id' => false,																							// Set your affiliate ID
		'copy-content' => !isset( $display_settings['copy-content'] ) ? true : $display_settings['copy-content'],			// Should we copy across content
		'animations' => !isset( $display_settings['animations'] ) ? true : $display_settings['animations'],					// Do we need animations
		'inline-css' => !isset( $display_settings['inline-css'] ) ? true : $display_settings['inline-css'],				    // How to display CSS
	) );

	// Filter these settings
	$settings = apply_filters('siteorigin_panels_settings', $settings);
	if( empty( $settings['post-types'] ) ) $settings['post-types'] = array();

	if( !empty( $key ) ) return isset( $settings[$key] ) ? $settings[$key] : null;
	return $settings;
}

/**
 * Modify the front page template
 *
 * @param $template
 * @return string
 */
function siteorigin_panels_lite_filter_home_template($template){
	// The user has already selected their own page as the home template
	if ( get_option( 'show_on_front' ) !== 'posts' ) return $template;

	// Do we even support the home template
	if ( !get_theme_mod('siteorigin_panels_home_page_enabled', siteorigin_panels_lite_setting('home-page-default') ) ) return $template;
	if ( !siteorigin_panels_lite_setting('home-page-default') || !siteorigin_panels_lite_setting('home-demo-template') ) return $template;

	return locate_template( array(
		siteorigin_panels_lite_setting( 'home-demo-template' ),
		$template
	) );
}
add_filter('home_template', 'siteorigin_panels_lite_filter_home_template');

/**
 * @return mixed|void Are we currently viewing the home page
 */
function siteorigin_panels_lite_is_home(){
	$home = ( is_home() && get_theme_mod('siteorigin_panels_home_page_enabled', siteorigin_panels_lite_setting('home-page-default') ) && !is_page() && siteorigin_panels_lite_setting( 'home-page-default' ) );
	return apply_filters('siteorigin_panels_is_home', $home);
}

/**
 * Enqueue the required styles
 */
function siteorigin_panels_lite_enqueue_styles(){
	if( siteorigin_panels_lite_is_home() ){
		wp_enqueue_style( 'siteorigin-panels-lite-front', get_template_directory_uri() . '/inc/panels-lite/css/front.css', array(), SITEORIGIN_PANELS_LITE_VERSION );

		// Render this here so we can enqueue all the scripts we need early.
		global $siteorigin_panels_cache;
		if( empty($siteorigin_panels_cache[ get_the_ID() ] ) ) {
			$siteorigin_panels_cache[ 'home' ] = siteorigin_panels_lite_home_render( );
		}
	}
}
add_action('wp_enqueue_scripts', 'siteorigin_panels_lite_enqueue_styles');

/**
 * Set the home body class when we're displaying a panels page.
 *
 * @param $classes
 * @return array
 */
function siteorigin_panels_lite_body_class($classes){
	if( siteorigin_panels_lite_is_home() ) {
		$classes[] = 'siteorigin-panels';
		$classes[] = 'siteorigin-panels-home';
		$classes[] = 'siteorigin-panels-lite-home';
	}
	return $classes;
}
add_filter('body_class', 'siteorigin_panels_lite_body_class');

/**
 * Render the widget.
 *
 * @param string $widget The widget class name.
 * @param array $instance The widget instance
 * @param $grid
 * @param $cell
 * @param $panel
 * @param $is_first
 * @param $is_last
 */
function siteorigin_panels_lite_the_widget( $widget, $instance, $grid, $cell, $panel, $is_first, $is_last ) {
	global $wp_widget_factory;

	// Load the widget from the widget factory and give themes and plugins a chance to provide their own
	$the_widget = !empty($wp_widget_factory->widgets[$widget]) ? $wp_widget_factory->widgets[$widget] : false;
	$the_widget = apply_filters( 'siteorigin_panels_widget_object', $the_widget, $widget, $instance );

	if( empty($post_id) ) $post_id = get_the_ID();

	$classes = apply_filters( 'siteorigin_panels_widget_classes', array( 'panel', 'widget' ), $widget, $instance);
	if ( !empty( $the_widget ) && !empty( $the_widget->id_base ) ) $classes[] = 'widget_' . $the_widget->id_base;
	if ( $is_first ) $classes[] = 'panel-first-child';
	if ( $is_last ) $classes[] = 'panel-last-child';
	$id = 'panel-' . $post_id . '-' . $grid . '-' . $cell . '-' . $panel;

	// Filter and sanitize the classes
	$classes = apply_filters('siteorigin_panels_widget_classes', $classes, $widget, $instance);
	$classes = array_map('sanitize_html_class', $classes);

	$args = array(
		'before_widget' => '<div class="' . esc_attr( implode( ' ', $classes ) ) . '" id="' . $id . '">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
		'widget_id' => 'widget-' . $grid . '-' . $cell . '-' . $panel
	);

	// If there is a style wrapper, add it.
	if( !empty($style_wrapper) ) {
		$args['before_widget'] = $args['before_widget'] . $style_wrapper;
		$args['after_widget'] = '</div>' . $args['after_widget'];
	}

	$widget_code = '';
	if ( !empty($the_widget) && is_a($the_widget, 'WP_Widget')  ) {
		ob_start();
		$the_widget->widget($args , $instance );
		$widget_code = ob_get_clean();
	}
	else {
		// This gives themes a chance to display some sort of placeholder for missing widgets
		$widget_code = apply_filters('siteorigin_panels_missing_widget', $args['before_widget'] . $args['after_widget'], $widget, $args , $instance);
	}

	// This is a special filter to create demo content
	echo apply_filters('siteorigin_panels_lite_widget', $widget_code, $widget, $args , $instance);
}

/**
 * Echo the CSS for the current panel
 *
 * @param array $panels_data
 */
function siteorigin_panels_lite_generate_css($post_id, $panels_data) {
	// Exit if we don't have panels data
	if ( empty( $panels_data ) || empty( $panels_data['grids'] ) ) return;

	// Get some of the default settings
	$settings = siteorigin_panels_lite_setting();
	$panels_mobile_width = $settings['mobile-width'];
	$panels_margin_bottom = $settings['margin-bottom'];

	$css = new SiteOrigin_Panels_Lite_Css_Builder();

	$ci = 0;
	foreach ( $panels_data['grids'] as $gi => $grid ) {

		$cell_count = intval( $grid['cells'] );

		// Add the cell sizing
		for ( $i = 0; $i < $cell_count; $i++ ) {
			$cell = $panels_data['grid_cells'][$ci++];

			if ( $cell_count > 1 ) {
				$width = round( $cell['weight'] * 100, 3 ) . '%';
				$width = apply_filters('siteorigin_panels_css_cell_width', $width, $grid, $gi, $cell, $ci - 1, $panels_data, $post_id);

				// Add the width and ensure we have correct formatting for CSS.
				$css->add_cell_css($post_id, $gi, $i, '', array(
					'width' => str_replace(',', '.', $width)
				));
			}
		}

		// Add the bottom margin to any grids that aren't the last
		if($gi != count($panels_data['grids'])-1){
			// Filter the bottom margin for this row with the arguments
			$css->add_row_css($post_id, $gi, '', array(
				'margin-bottom' => apply_filters('siteorigin_panels_css_row_margin_bottom', $panels_margin_bottom.'px', $grid, $gi, $panels_data, $post_id)
			));
		}

		if ( $cell_count > 1 ) {
			$css->add_cell_css($post_id, $gi, false, '', array(
				// Float right for RTL
				'float' => !is_rtl() ? 'left' : 'right'
			));
		}

		if ( $settings['responsive'] ) {
			// Mobile Responsive
			$css->add_cell_css($post_id, $gi, false, '', array(
				'float' => 'none',
				'width' => 'auto'
			), $panels_mobile_width);

			for ( $i = 0; $i < $cell_count; $i++ ) {
				if ( $i != $cell_count - 1 ) {
					$css->add_cell_css($post_id, $gi, $i, '', array(
						'margin-bottom' => $panels_margin_bottom . 'px',
					), $panels_mobile_width);
				}
			}
		}
	}

	if( $settings['responsive'] ) {
		// Add CSS to prevent overflow on mobile resolution.
		$css->add_row_css($post_id, false, '', array(
			'margin-left' => 0,
			'margin-right' => 0,
		), $panels_mobile_width);

		$css->add_cell_css($post_id, false, false, '', array(
			'padding' => 0,
		), $panels_mobile_width);
	}

	// Add the bottom margins
	$css->add_cell_css($post_id, false, false, '.panel', array(
		'margin-bottom' => $panels_margin_bottom.'px'
	));
	$css->add_cell_css($post_id, false, false, '.panel:last-child', array(
		'margin-bottom' => 0
	));

	// Let other plugins customize various aspects of the rows (grids)
	foreach ( $panels_data['grids'] as $gi => $grid ) {
		// Rows with only one cell don't need gutters
		if($grid['cells'] <= 1) continue;

		// Let other themes and plugins change the gutter.
		$gutter = apply_filters('siteorigin_panels_css_row_gutter', $settings['margin-sides'].'px', $grid, $gi, $panels_data);

		if( !empty($gutter) ) {
			// We actually need to find half the gutter.
			preg_match('/([0-9\.,]+)(.*)/', $gutter, $match);
			if( !empty( $match[1] ) ) {
				$margin_half = (floatval($match[1])/2) . $match[2];
				$css->add_row_css($post_id, $gi, '', array(
					'margin-left' => '-' . $margin_half,
					'margin-right' => '-' . $margin_half,
				) );
				$css->add_cell_css($post_id, $gi, false, '', array(
					'padding-left' => $margin_half,
					'padding-right' => $margin_half,
				) );

			}
		}
	}

	// Let other plugins and components filter the CSS object.
	$css = apply_filters('siteorigin_panels_css_object', $css, $panels_data, $post_id);
	return $css->get_css();
}

/**
 *
 */
function siteorigin_panels_lite_css(){
	if( !siteorigin_panels_lite_is_home() ) return;

	$layouts = apply_filters( 'siteorigin_panels_prebuilt_layouts', array() );
	if(empty($layouts[ siteorigin_panels_lite_setting('home-page-default') ])) return;
	$panels_data = $layouts[siteorigin_panels_lite_setting('home-page-default')];
	$panels_data = apply_filters( 'siteorigin_panels_data', $panels_data, 'home' );

	?><style type="text/css"><?php echo siteorigin_panels_lite_generate_css('home', $panels_data) ?></style><?php
}
add_action( 'wp_head', 'siteorigin_panels_lite_css', 15 );


/**
 * @param string $post_id
 *
 * @return mixed|void
 */
function siteorigin_panels_lite_home_render( $post_id = 'home' ){
	if( empty($post_id) ) $post_id = get_the_ID();

	global $siteorigin_panels_current_post;
	$old_current_post = $siteorigin_panels_current_post;
	$siteorigin_panels_current_post = $post_id;

	// Try get the cached panel from in memory cache.
	global $siteorigin_panels_cache;
	if(!empty($siteorigin_panels_cache) && !empty($siteorigin_panels_cache[$post_id]))
		return $siteorigin_panels_cache[$post_id];

	// Load the default layout
	$layouts = apply_filters('siteorigin_panels_prebuilt_layouts', array());
	$prebuilt_id = siteorigin_panels_lite_setting('home-page-default') ? siteorigin_panels_lite_setting('home-page-default') : 'home';
	$panels_data = !empty($layouts[$prebuilt_id]) ? $layouts[$prebuilt_id] : current($layouts);

	$panels_data = apply_filters( 'siteorigin_panels_data', $panels_data, $post_id );
	if( empty( $panels_data ) || empty( $panels_data['grids'] ) ) return '';

	// Create the skeleton of the grids
	$grids = array();
	if( !empty( $panels_data['grids'] ) && !empty( $panels_data['grids'] ) ) {
		foreach ( $panels_data['grids'] as $gi => $grid ) {
			$gi = intval( $gi );
			$grids[$gi] = array();
			for ( $i = 0; $i < $grid['cells']; $i++ ) {
				$grids[$gi][$i] = array();
			}
		}
	}

	// We need this to migrate from the old $panels_data that put widget meta into the "info" key instead of "panels_info"
	if( !empty( $panels_data['widgets'] ) && is_array($panels_data['widgets']) ) {
		foreach ( $panels_data['widgets'] as $i => $widget ) {
			if( empty( $panels_data['widgets'][$i]['panels_info'] ) ) {
				$panels_data['widgets'][$i]['panels_info'] = $panels_data['widgets'][$i]['info'];
				unset($panels_data['widgets'][$i]['info']);
			}
		}
	}

	if( !empty( $panels_data['widgets'] ) && is_array($panels_data['widgets']) ){
		foreach ( $panels_data['widgets'] as $widget ) {
			// Put the widgets in the grids
			$grids[ intval( $widget['panels_info']['grid']) ][ intval( $widget['panels_info']['cell'] ) ][] = $widget;
		}
	}

	ob_start();

	if( current_user_can('edit_theme_options') ) {
		$install_url = siteorigin_panels_lite_plugin_activation_install_url();

		$home = get_theme_mod( 'siteorigin_panels_home_page_enabled', siteorigin_panels_lite_setting('home-page-default') );
		$toggle_url = add_query_arg('redirect', add_query_arg(false, false), wp_nonce_url(admin_url('admin-ajax.php?action=panels_lite_toggle&panels_new='.($home ? 0 : 1)), 'toggle_panels_home') );

		?>
		<p class="siteorigin-panels-lite-message">
			<?php if( current_user_can( 'install_plugins' ) ) : ?>
				<?php
				printf(
					__('<a href="%s">Install Page Builder</a> to <a href="%s">edit</a> this default home page.', 'vantage'),
					$install_url,
					admin_url('themes.php?page=so_panels_home_page')
				); ?>
			<?php endif; ?>

			<?php printf( __("<a href='%s'>Disable this page</a> if you'd prefer to have a standard blog home.", 'vantage'), $toggle_url ) ?>
		</p>
		<?php
	}

	// Add the panel layout wrapper
	echo '<div id="pl-' . $post_id . '">';

	global $siteorigin_panels_inline_css;
	if( empty($siteorigin_panels_inline_css) ) $siteorigin_panels_inline_css = '';

	echo apply_filters( 'siteorigin_panels_before_content', '', $panels_data, $post_id );

	foreach ( $grids as $gi => $cells ) {

		$grid_classes = apply_filters( 'siteorigin_panels_row_classes', array('panel-grid'), $panels_data['grids'][$gi] );
		$grid_attributes = apply_filters( 'siteorigin_panels_row_attributes', array(
			'class' => implode( ' ', $grid_classes ),
			'id' => 'pg-' . $post_id . '-' . $gi
		), $panels_data['grids'][$gi] );

		// This allows other themes and plugins to add html before the row
		echo apply_filters( 'siteorigin_panels_before_row', '', $panels_data['grids'][$gi], $grid_attributes );

		echo '<div ';
		foreach ( $grid_attributes as $name => $value ) {
			echo $name.'="'.esc_attr($value).'" ';
		}
		echo '>';

		$style_attributes = array();
		if( !empty( $panels_data['grids'][$gi]['style']['class'] ) ) {
			$style_attributes['class'] = array('panel-row-style-'.$panels_data['grids'][$gi]['style']['class']);
		}

		// Themes can add their own attributes to the style wrapper
		$row_style_wrapper = siteorigin_panels_lite_start_style_wrapper( 'row', $style_attributes, !empty($panels_data['grids'][$gi]['style']) ? $panels_data['grids'][$gi]['style'] : array() );
		if( !empty($row_style_wrapper) ) echo $row_style_wrapper;

		foreach ( $cells as $ci => $widgets ) {
			// Themes can add their own styles to cells
			$cell_classes = apply_filters( 'siteorigin_panels_row_cell_classes', array('panel-grid-cell'), $panels_data );
			$cell_attributes = apply_filters( 'siteorigin_panels_row_cell_attributes', array(
				'class' => implode( ' ', $cell_classes ),
				'id' => 'pgc-' . $post_id . '-' . $gi  . '-' . $ci
			), $panels_data );

			echo '<div ';
			foreach ( $cell_attributes as $name => $value ) {
				echo $name.'="'.esc_attr($value).'" ';
			}
			echo '>';

			$cell_style_wrapper = siteorigin_panels_lite_start_style_wrapper( 'cell', array(), !empty($panels_data['grids'][$gi]['style']) ? $panels_data['grids'][$gi]['style'] : array() );
			if( !empty($cell_style_wrapper) ) echo $cell_style_wrapper;

			foreach ( $widgets as $pi => $widget_info ) {
				$instance = $widget_info;
				unset( $instance['panels_info'] );

				// TODO this wrapper should go in the before/after widget arguments
				$widget_style_wrapper = siteorigin_panels_lite_start_style_wrapper( 'widget', array(), !empty( $widget_info['panels_info']['style'] ) ? $widget_info['panels_info']['style'] : array() );
				siteorigin_panels_lite_the_widget( $widget_info['panels_info']['class'], $instance, $gi, $ci, $pi, $pi == 0, $pi == count( $widgets ) - 1, $post_id, $widget_style_wrapper );
			}
			if ( empty( $widgets ) ) echo '&nbsp;';

			if( !empty($cell_style_wrapper) ) echo '</div>';
			echo '</div>';
		}

		echo '</div>';

		// Close the
		if( !empty($row_style_wrapper) ) echo '</div>';

		// This allows other themes and plugins to add html after the row
		echo apply_filters( 'siteorigin_panels_after_row', '', $panels_data['grids'][$gi], $grid_attributes );
	}

	echo apply_filters( 'siteorigin_panels_after_content', '', $panels_data, $post_id );

	echo '</div>';

	$html = ob_get_clean();

	// Reset the current post
	$siteorigin_panels_current_post = $old_current_post;

	return apply_filters( 'siteorigin_panels_render', $html, $post_id, !empty($post) ? $post : null );
}

/**
 * Echo the style wrapper and return if there was a wrapper
 *
 * @param $name
 * @param $style_attributes
 * @param array $style_args
 *
 * @return bool Is there a style wrapper
 */
function siteorigin_panels_lite_start_style_wrapper($name, $style_attributes, $style_args = array()){

	$style_wrapper = '';

	if( empty($style_attributes['class']) ) $style_attributes['class'] = array();
	if( empty($style_attributes['style']) ) $style_attributes['style'] = '';

	$style_attributes = apply_filters('siteorigin_panels_' . $name . '_style_attributes', $style_attributes, $style_args );

	if( empty($style_attributes['class']) ) unset($style_attributes['class']);
	if( empty($style_attributes['style']) ) unset($style_attributes['style']);

	if( !empty($style_attributes) ) {
		if(empty($style_attributes['class'])) $style_attributes['class'] = array();
		$style_attributes['class'][] = 'panel-' . $name . '-style';
		$style_attributes['class'] = array_unique( $style_attributes['class'] );

		// Filter and sanitize the classes
		$style_attributes['class'] = apply_filters('siteorigin_panels_' . $name . '_style_classes', $style_attributes['class'], $style_attributes, $style_args);
		$style_attributes['class'] = array_map('sanitize_html_class', $style_attributes['class']);

		$style_wrapper = '<div ';
		foreach ( $style_attributes as $name => $value ) {
			if( is_array($value) ) {
				$style_wrapper .= $name.'="'.esc_attr( implode( " ", array_unique( $value ) ) ).'" ';
			}
			else {
				$style_wrapper .= $name.'="'.esc_attr($value).'" ';
			}
		}
		$style_wrapper .= '>';

		return $style_wrapper;
	}

	return $style_wrapper;
}