<?php
/*
Plugin Name: SiteOrigin Widgets Bundle
Description: A collection of all widgets, neatly bundled into a single plugin. It's also a framework to code your own widgets on top of.
Version: 1.5.4
Text Domain: so-widgets-bundle
Domain Path: /languages
Author: SiteOrigin
Author URI: https://siteorigin.com
Plugin URI: https://siteorigin.com/widgets-bundle/
License: GPL3
License URI: https://www.gnu.org/licenses/gpl-3.0.txt
*/

define('SOW_BUNDLE_VERSION', '1.5.4');
define('SOW_BUNDLE_JS_SUFFIX', '.min');
define('SOW_BUNDLE_BASE_FILE', __FILE__);

if( !function_exists('siteorigin_widget_get_plugin_path') ) {
	include plugin_dir_path(__FILE__).'base/inc.php';
	include plugin_dir_path(__FILE__).'icons/icons.php';
}

class SiteOrigin_Widgets_Bundle {

	private $widget_folders;

	/**
	 * @var array The array of default widgets.
	 */
	static $default_active_widgets = array(
		'so-button-widget' => true,
		'so-google-map-widget' => true,
		'so-image-widget' => true,
		'so-slider-widget' => true,
		'so-post-carousel-widget' => true,
		'so-editor-widget' => true,
	);

	function __construct(){
		add_action('admin_init', array($this, 'admin_activate_widget') );
		add_action('admin_menu', array($this, 'admin_menu_init') );
		add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts') );
		add_action('wp_ajax_so_widgets_bundle_manage', array($this, 'admin_ajax_manage_handler') );
		add_action('wp_ajax_sow_get_javascript_variables', array($this, 'admin_ajax_get_javascript_variables') );

		// Initialize the widgets, but do it fairly late
		add_action( 'plugins_loaded', array($this, 'set_plugin_textdomain'), 1 );
		add_action( 'after_setup_theme', array($this, 'get_widget_folders'), 11 );
		add_action( 'after_setup_theme', array($this, 'load_widget_plugins'), 11 );

		// Add the action links.
		add_action( 'plugin_action_links_' . plugin_basename(__FILE__), array($this, 'plugin_action_links') );

		// Version check for cache clearing
		add_action( 'admin_init', array($this, 'plugin_version_check') );
		add_action( 'siteorigin_widgets_version_update', array( $this, 'check_for_new_widgets' ) );
		add_action( 'admin_notices', array( $this, 'display_admin_notices') );

		// These filters are used to activate any widgets that are missing.
		add_filter( 'siteorigin_panels_data', array($this, 'load_missing_widgets') );
		add_filter( 'siteorigin_panels_prebuilt_layout', array($this, 'load_missing_widgets') );
		add_filter( 'siteorigin_panels_widget_object', array($this, 'load_missing_widget'), 10, 2 );

		add_filter( 'wp_enqueue_scripts', array($this, 'enqueue_active_widgets_scripts') );
	}

	/**
	 * Get the single of this plugin
	 *
	 * @return SiteOrigin_Widgets_Bundle
	 */
	static function single() {
		static $single;

		if( empty($single) ) {
			$single = new SiteOrigin_Widgets_Bundle();
		}

		return $single;
	}

	/**
	 * Set the text domain for the plugin
	 *
	 * @action plugins_loaded
	 */
	function set_plugin_textdomain(){
		load_plugin_textdomain('so-widgets-bundle', false, dirname( plugin_basename( __FILE__ ) ). '/languages/');
	}

	/**
	 * This clears the file cache.
	 *
	 * @action admin_init
	 */
	function plugin_version_check(){

		$active_version = get_option( 'siteorigin_widget_bundle_version' );

		if( empty($active_version) || version_compare( $active_version, SOW_BUNDLE_VERSION, '<' ) ) {
			// If this is a new version, then clear the cache.
			update_option( 'siteorigin_widget_bundle_version', SOW_BUNDLE_VERSION );
			siteorigin_widgets_deactivate_legacy_plugins();

			// Remove all cached CSS for SiteOrigin Widgets
			if( function_exists('WP_Filesystem') && WP_Filesystem() ) {
				global $wp_filesystem;
				$upload_dir = wp_upload_dir();

				// Remove any old widget cache files, if they exist.
				$list = $wp_filesystem->dirlist( $upload_dir['basedir'] . '/siteorigin-widgets/' );
				if( !empty($list) ) {
					foreach($list as $file) {
						// Delete the file
						$wp_filesystem->delete( $upload_dir['basedir'] . '/siteorigin-widgets/' . $file['name'] );
					}
				}
			}

			// An action to let widgets handle the updates.
			do_action( 'siteorigin_widgets_version_update', SOW_BUNDLE_VERSION, $active_version );
		}

	}

	/**
	 * Setup and return the widget folders
	 */
	function check_for_new_widgets() {
		// get list of available widgets
		$widgets = array_keys( $this->get_widgets_list() );
		// get option for previously installed widgets
		$old_widgets = get_option( 'siteorigin_widgets_old_widgets' );
		// if this has never been set before, it's probably a new installation so we don't want to notify for all the widgets
		if ( empty( $old_widgets ) ) {
			update_option( 'siteorigin_widgets_old_widgets', implode( ',', $widgets ) );
			return;
		}
		$old_widgets = explode( ',', $old_widgets );
		$new_widgets = array_diff( $widgets, $old_widgets );
		if ( ! empty( $new_widgets ) ) {
			update_option( 'siteorigin_widgets_new_widgets', $new_widgets );
			update_option( 'siteorigin_widgets_old_widgets', implode( ',', $widgets ) );
		}
	}

	function display_admin_notices() {
		$new_widgets = get_option( 'siteorigin_widgets_new_widgets' );
		if ( empty( $new_widgets ) ) {
			return;
		}
		?>
		<div class="updated">
			<p><?php echo __( 'New widgets available in the ') . '<a href="' . admin_url('plugins.php?page=so-widgets-plugins') . '">' . __('SiteOrigin Widgets Bundle', 'so-widgets-bundle' ) . '</a>!'; ?></p>
			<?php

			$default_headers = array(
				'Name' => 'Widget Name',
				'Description' => 'Description',
				'Author' => 'Author',
				'AuthorURI' => 'Author URI',
				'WidgetURI' => 'Widget URI',
				'VideoURI' => 'Video URI',
			);

			foreach ( $new_widgets as $widget_file_path ) {
				preg_match( '/.*[\/\\\\](.*).php/', $widget_file_path, $match );
				$widget = get_file_data( $widget_file_path, $default_headers, 'siteorigin-widget' );
				$name = empty( $widget['Name'] ) ? $match[1] : $widget['Name'];
				$description = empty( $widget['Description'] ) ? __( 'A new widget!', 'so-widgets-bundle' ) : $widget['Description'];
				?>
				<p><b><?php echo esc_html( $name . ' - ' . $description) ?></b></p>
				<?php
			}
			?>
		</div>
		<?php
		update_option( 'siteorigin_widgets_new_widgets', array() );
	}

	/**
	 * Setup and return the widget folders
	 */
	function get_widget_folders(){
		if( empty($this->widget_folders) ) {
			// We can use this filter to add more folders to use for widgets
			$this->widget_folders = apply_filters('siteorigin_widgets_widget_folders', array(
				plugin_dir_path(__FILE__).'widgets/'
			) );
		}

		return $this->widget_folders;
	}

	/**
	 * Load all the widgets if their plugins are not already active.
	 *
	 * @action plugins_loaded
	 */
	function load_widget_plugins(){

		// Load all the widget we currently have active and filter them
		$active_widgets = $this->get_active_widgets();
		$widget_folders = $this->get_widget_folders();

		foreach( $active_widgets as $widget_id => $active ) {
			if( empty($active) ) continue;

			foreach( $this->widget_folders as $folder ) {
				if ( !file_exists($folder . $widget_id.'/'.$widget_id.'.php') ) continue;

				// Include this widget file
				include_once $folder . $widget_id.'/'.$widget_id.'.php';
			}

		}
	}

	/**
	 * Get a list of currently active widgets.
	 *
	 * @param bool $filter
	 *
	 * @return mixed|void
	 */
	function get_active_widgets( $filter = true ){
		$active_widgets = get_option( 'siteorigin_widgets_active', array() );
		$active_widgets = wp_parse_args( $active_widgets, apply_filters('siteorigin_widgets_default_active', self::$default_active_widgets) );

		if( $filter ) $active_widgets = apply_filters( 'siteorigin_widgets_active_widgets',  $active_widgets);

		return $active_widgets;
	}

	/**
	 * Enqueue the admin page stuff.
	 */
	function admin_enqueue_scripts($prefix) {
		if( $prefix != 'plugins_page_so-widgets-plugins' ) return;
		wp_enqueue_style( 'siteorigin-widgets-manage-admin', plugin_dir_url( __FILE__ ) . 'admin/admin.css', array(), SOW_BUNDLE_VERSION );
		wp_enqueue_script( 'siteorigin-widgets-trianglify', plugin_dir_url( __FILE__ ) . 'admin/trianglify' . SOW_BUNDLE_JS_SUFFIX . '.js', array(), SOW_BUNDLE_VERSION );
		wp_enqueue_script( 'siteorigin-widgets-manage-admin', plugin_dir_url( __FILE__ ) . 'admin/admin' . SOW_BUNDLE_JS_SUFFIX . '.js', array(), SOW_BUNDLE_VERSION );

		wp_localize_script( 'siteorigin-widgets-manage-admin', 'soWidgetsAdmin', array(
			'toggleUrl' => wp_nonce_url( admin_url('admin-ajax.php?action=so_widgets_bundle_manage'), 'manage_so_widget' )
		) );
	}

	/**
	 * The fallback (from ajax) URL handler for activating or deactivating a widget
	 */
	function admin_activate_widget() {
		if(
			!empty($_GET['page'])
			&& $_GET['page'] == 'so-widgets-plugins'
			&& !empty( $_GET['widget_action'] ) && !empty( $_GET['widget'] )
			&& isset($_GET['_wpnonce'])
			&& wp_verify_nonce($_GET['_wpnonce'], 'siteorigin_widget_action')
		) {

			switch($_GET['widget_action']) {
				case 'activate':
					$this->activate_widget( $_GET['widget'] );
					break;

				case 'deactivate':
					$this->deactivate_widget( $_GET['widget'] );
					break;
			}

			// Redirect and clear all the args
			wp_redirect( add_query_arg( array(
				'_wpnonce' => false,
				'widget_action_done' => 'true',
			) ) );

		}
	}

	/**
	 * Handler for activating and deactivating widgets.
	 *
	 * @action wp_ajax_so_widgets_bundle_manage
	 */
	function admin_ajax_manage_handler(){
		if( !wp_verify_nonce($_GET['_wpnonce'], 'manage_so_widget') ) exit();
		if( !current_user_can( apply_filters('siteorigin_widgets_admin_menu_capability', 'install_plugins') ) ) exit();
		if( empty($_POST['widget']) ) exit();

		if( !empty($_POST['active']) ) $this->activate_widget($_POST['widget']);
		else $this->deactivate_widget( $_POST['widget'] );

		// Send a kind of dummy response.
		header('content-type: application/json');
		echo json_encode( array('done' => true) );
		exit();
	}

	/**
	 * Add the admin menu page.
	 *
	 * @action admin_menu
	 */
	function admin_menu_init(){
		add_plugins_page(
			__('SiteOrigin Widgets', 'so-widgets-bundle'),
			__('SiteOrigin Widgets', 'so-widgets-bundle'),
			apply_filters('siteorigin_widgets_admin_menu_capability', 'manage_options'),
			'so-widgets-plugins',
			array($this, 'admin_page')
		);
	}

	/**
	 * Display the admin page.
	 */
	function admin_page(){

		$bundle = SiteOrigin_Widgets_Bundle::single();
		$widgets = $bundle->get_widgets_list();

		if(
			isset($_GET['widget_action_done'])
			&& !empty($_GET['widget_action'])
			&& !empty($_GET['widget'])
			&& !empty( $widgets[ $_GET['widget'].'/'.$_GET['widget'].'.php' ] )
		) {

			?>
			<div class="updated">
				<p>
				<?php
				printf(
					__('%s was %s', 'so-widgets-bundle'),
					$widgets[ $_GET['widget'].'/'.$_GET['widget'].'.php' ]['Name'],
					$_GET['widget_action'] == 'activate' ? __('Activated', 'so-widgets-bundle') : __('Deactivated', 'so-widgets-bundle')
				)
				?>
				</p>
			</div>
			<?php
		}

		include plugin_dir_path(__FILE__).'admin/tpl/admin.php';
	}

	/**
	 * Get javascript variables for admin.
	 */
	function admin_ajax_get_javascript_variables() {
		if ( empty( $_REQUEST['_widgets_nonce'] ) || !wp_verify_nonce( $_REQUEST['_widgets_nonce'], 'widgets_action' ) ) return;
		$result = array();
		$widget_class = $_POST['widget'];
		global $wp_widget_factory;
		if ( ! empty( $wp_widget_factory->widgets[ $widget_class ] ) ) {
			$widget = $wp_widget_factory->widgets[ $widget_class ];
			if( method_exists($widget, 'get_javascript_variables') ) $result = $widget->get_javascript_variables();
		}

		header('content-type: application/json');
		echo json_encode($result);

		exit();
	}

	/**
	 * Activate a widget
	 *
	 * @param string $widget_id The ID of the widget that we're activating.
	 * @param bool $include Should we include the widget, to make it available in the current request.
	 *
	 * @return bool
	 */
	function activate_widget( $widget_id, $include = true ){
		$exists = false;
		foreach( $this->widget_folders as $folder ) {
			if( !file_exists($folder . $widget_id . '/' . $widget_id . '.php') ) continue;
			$exists = true;
		}

		if( !$exists ) return false;

		// There are times when we activate several widgets at once, so clear the cache.
		wp_cache_delete( 'siteorigin_widgets_active', 'options' );
		$active_widgets = $this->get_active_widgets();
		$active_widgets[$widget_id] = true;
		update_option( 'siteorigin_widgets_active', $active_widgets );

		// If we don't want to include the widget files, then our job here is done.
		if( !$include ) return;

		// Now, lets actually include the files
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

		foreach( $this->widget_folders as $folder ) {
			if( !file_exists($folder . $widget_id . '/' . $widget_id . '.php') ) continue;
			include_once $folder . $widget_id . '/' . $widget_id . '.php';

			if( has_action('widgets_init') ) {
				SiteOrigin_Widgets_Widget_Manager::single()->widgets_init();
			}
		}


		return true;
	}

	/**
	 * Include a widget that might not have been registered.
	 *
	 * @param $widget_id
	 *
	 * @return bool
	 */
	function include_widget( $widget_id ) {
		$folders = $this->get_widget_folders();

		foreach( $folders as $folder ) {
			if( !file_exists($folder . $widget_id . '/' . $widget_id . '.php') ) continue;
			include_once $folder . $widget_id . '/' . $widget_id . '.php';
			return true;
		}

		return false;
	}

	/**
	 * Deactivate a widget
	 *
	 * @param $id
	 */
	function deactivate_widget($id){
		$active_widgets = $this->get_active_widgets();
		$active_widgets[$id] = false;
		update_option( 'siteorigin_widgets_active', $active_widgets );
	}

	/**
	 * Gets a list of all available widgets
	 */
	function get_widgets_list(){
		$active = $this->get_active_widgets();
		$folders = $this->get_widget_folders();

		$default_headers = array(
			'Name' => 'Widget Name',
			'Description' => 'Description',
			'Author' => 'Author',
			'AuthorURI' => 'Author URI',
			'WidgetURI' => 'Widget URI',
			'VideoURI' => 'Video URI',
		);

		$widgets = array();
		foreach( $folders as $folder ) {

			$files = glob( $folder.'*/*.php' );
			foreach($files as $file) {
				$widget = get_file_data( $file, $default_headers, 'siteorigin-widget' );
				//skip the file if it's missing a name
				if ( empty( $widget['Name'] ) ) {
					continue;
				}
				$f = pathinfo($file);
				$id = $f['filename'];

				$widget['ID'] = $id;
				$widget['Active'] = !empty($active[$id]);
				$widget['File'] = $file;

				$widgets[$file] = $widget;
			}

		}

		// Sort the widgets alphabetically
		uasort( $widgets, array($this, 'widget_uasort') );
		return $widgets;
	}

	/**
	 * Sorting function to sort widgets by name
	 *
	 * @param $widget_a
	 * @param $widget_b
	 *
	 * @return int
	 */
	function widget_uasort($widget_a, $widget_b) {
		return $widget_a['Name'] > $widget_b['Name'] ? 1 : -1;
	}

	/**
	 * Look in Page Builder data for any missing widgets.
	 *
	 * @param $data
	 *
	 * @return mixed
	 *
	 * @action siteorigin_panels_data
	 */
	function load_missing_widgets($data){
		if(empty($data['widgets'])) return $data;

		global $wp_widget_factory;

		foreach($data['widgets'] as $widget) {
			if( empty($widget['panels_info']['class']) ) continue;
			if( !empty($wp_widget_factory->widgets[$widget['panels_info']['class']] ) ) continue;

			$class = $widget['panels_info']['class'];
			if( preg_match('/SiteOrigin_Widget_([A-Za-z]+)_Widget/', $class, $matches) ) {
				$name = $matches[1];
				// TODO change this when we transition to new widget names
				$id = 'so'.strtolower( implode( '-', preg_split('/(?=[A-Z])/',$name) ) ).'-widget';
				$this->activate_widget($id, true);
			}
		}

		return $data;
	}

	/**
	 * Attempt to load a single missing widget.
	 *
	 * @param $the_widget
	 * @param $class
	 *
	 * @return
	 */
	function load_missing_widget($the_widget, $class){
		// We only want to worry about missing widgets
		if( !empty($the_widget) ) return $the_widget;

		if( preg_match('/SiteOrigin_Widget_([A-Za-z]+)_Widget/', $class, $matches) ) {
			$name = $matches[1];
			// TODO change this when we transition to new widget names
			$id = 'so'.strtolower( implode( '-', preg_split('/(?=[A-Z])/',$name) ) ).'-widget';

			$this->activate_widget($id, true);
			global $wp_widget_factory;
			if( !empty($wp_widget_factory->widgets[$class]) ) return $wp_widget_factory->widgets[$class];
		}

		return $the_widget;
	}

	/**
	 * Add action links.
	 */
	function plugin_action_links($links){
		$links[] = '<a href="' . admin_url('plugins.php?page=so-widgets-plugins') . '">'.__('Manage Widgets', 'so-widgets-bundle').'</a>';
		$links[] = '<a href="https://siteorigin.com/thread/" target="_blank">'.__('Support', 'so-widgets-bundle').'</a>';
		return $links;
	}

	/**
	 * Ensure active widgets' scripts are enqueued at the right time.
	 */
	function enqueue_active_widgets_scripts() {
		global $wp_registered_widgets;
		$sidebars_widgets = wp_get_sidebars_widgets();
		foreach( $sidebars_widgets as $sidebar => $widgets ) {
			if ( ! empty( $widgets ) && $sidebar !== "wp_inactive_widgets") {
				foreach ( $widgets as $i => $id ) {
					if ( ! empty( $wp_registered_widgets[$id] ) ) {
						$widget = $wp_registered_widgets[$id]['callback'][0];
						if ( !empty($widget) && is_object($widget) && is_subclass_of($widget, 'SiteOrigin_Widget') && is_active_widget( false, false, $widget->id_base ) ) {
							$opt_wid = get_option( 'widget_' . $widget->id_base );
							preg_match( '/-([0-9]+$)/', $id, $num_match );
							$widget_instance = $opt_wid[ $num_match[1] ];
							$widget->enqueue_frontend_scripts( $widget_instance);
							$widget->generate_and_enqueue_instance_styles( $widget_instance );
						}
					}
				}
			}
		}
	}
}

// create the initial single
SiteOrigin_Widgets_Bundle::single();

// Initialize the Meta Box Manager
global $sow_meta_box_manager;
$sow_meta_box_manager = SiteOrigin_Widget_Meta_Box_Manager::single();

/**
 * Deactivate any old widget plugins that we used to have on the directory. We'll remove this after version 1.2.
 */
function siteorigin_widgets_deactivate_legacy_plugins(){
	// All we want to do here is disable all legacy widgets
	$the_plugins = get_option('active_plugins');
	foreach($the_plugins as $plugin_id) {
		if( preg_match('/^so-([a-z\-]+)-widget\/so-([a-z\-]+)-widget\.php$/', $plugin_id) ) {
			// Deactivate the legacy plugin
			deactivate_plugins($plugin_id, true);
		}
	}
}
register_activation_hook( __FILE__, 'siteorigin_widgets_deactivate_legacy_plugins' );