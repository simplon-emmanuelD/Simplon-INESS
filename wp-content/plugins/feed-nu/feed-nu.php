<?php
/*
 Plugin Name: Feed.nu
Plugin URI: http://wordpress.org/extend/plugins/feed-nu/
Description: Create a native Android app of your blog. With this plugin you can automatically generate your mobile app ready to deploy on android market or host it by yourself. No programming skills needed and it's instantly available. IPhone version comming soon. <a href="http://feed.nu">http://feed.nu</a>
Author: Feed.nu
Author URI: http://feed.nu/
Version: 2.0.3
License: GPLv2 or later
*/

/* AVOID DIRECT CALLS
 *
* avoid direct calls to this file where
* wp core files not present
************************************************************/
if(!defined('ABSPATH'))
	die('Please do not load this file directly.');
require_once dirname(__FILE__).'/lib/Unirest/Unirest.php';
require_once dirname(__FILE__).'/lib/Unirest/HttpMethod.php';
require_once dirname(__FILE__).'/lib/Unirest/HttpResponse.php';




class Feed_app_plugin {

	public $version = '2.0.3';
	public $remote_url = 'http://build.feed.nu';


	function __construct() {
		if ( is_admin() ){
			if($_GET['feed_attachment']) {
				add_action( 'admin_init', array(&$this, 'download_file') );	
			}
			else {

				add_action('admin_menu', array(&$this, 'on_admin_menu'));
				add_action( 'admin_init', array(&$this, 'on_admin_init'));
				add_action('wp_ajax_feed_migrated', array(&$this, 'feed_migrated_callback'));


			}
		}
		else {
  			// non-admin enqueues, actions, and filters
		}
	}


	/**
	 * BUILD MENU
	 * Creates menu on the left bottom, with an smartphone icon
	 *
	 * @since 1.1
	 ************************************************************/
	public function on_admin_menu() {
		$icon = WP_PLUGIN_URL . '/feed-nu/img/Smartphone-icon.png';
		$menu = add_menu_page('Feed', "Feed.nu", 'manage_options', 'feed-nu', array(&$this, 'on_render_page'), $icon);

		add_submenu_page('feed-nu', __('Feed settings'), __('Settings'), 'manage_options', 'feed-nu-settings', array(&$this, 'create_admin_page'));
		add_action('load-'.$menu, array(&$this, 'on_load_menu'));


		

	}


	public function feed_loged_in_callback() {
		
        if(isset($_POST['token'])) {

        	$token = $_POST['token'];

        	if($this->is_valid_token($token)) {
        		$user_id = wp_get_current_user()->ID;
			    update_user_meta( $user_id, 'api_key', $token);
			    die('feed token saved on ' . $user_id);
        	}
		}

		die('Could not store feed token since it is not valid');
	}


	public function feed_migrated_callback() {
		
		$this->set_migrated(true);

		die('marked as migrated');
	}



	public function create_admin_page(){
        ?>
		<div class="wrap">
		    <?php screen_icon(); ?>
		    <h2>Feed settings</h2>			
		    <form method="post" action="options.php">
		        <?php
	                    // This prints out all hidden setting fields
			    settings_fields('feed_option_group');	
			    do_settings_sections('feed-setting-admin');
			?>
		        <?php submit_button(); ?>
		    </form>
		</div>
		<?php
    }

	public function on_admin_init(){		
		register_setting('feed_option_group', 'array_key', array($this, 'check_ID'));
			
	    add_settings_section(
		    'setting_section_id',
		    'Account',
		    array($this, 'print_account_section_info'),
		    'feed-setting-admin'
		);	
			
		add_settings_field(
		    'api_key', 
		    'Token', 
		    array($this, 'create_an_id_field'), 
		    'feed-setting-admin',
		    'setting_section_id'			
		);		
    }

     public function check_ID($input) {
        if(isset($input['api_key'])) {
        	$token = $input['api_key'];
        	if($this->is_valid_token($token)) {
        		$mid = $token;
			    update_user_meta( wp_get_current_user()->ID, 'api_key', $token);
        	}
		} else {
		    $mid = '';
		}
		return $mid;
    }


    public function is_valid_token($token) {
    	$response = Unirest::get(
				"https://feed.p.mashape.com/user/",
			  array(
			    "token" => $token,
			    "X-Mashape-Authorization" => "k0ivjgqqhpct7gpnhupp9m3dl1i5d9"
			  ));
    	if($response->body->status == 200) {
    		return true;
    	}
    	return false;
    }
	
    public function print_account_section_info(){
		print 'Your account information:';
    }
	
    public function create_an_id_field(){
        print '<input type="text" id="api_key" name="array_key[api_key]" value="'.get_user_meta(wp_get_current_user()->ID, "api_key", true) .'" />';
    }

	/**
	 * Includes some css and javascript when visiting the plugin
	 *
	 * @since 1.1
	 ************************************************************/
	public function on_load_menu() {
		//TODO: compress js and css
		wp_enqueue_style('feed_nu_style', WP_PLUGIN_URL . '/feed-nu/css/feed.css', false, $this->version);
		wp_enqueue_script('feed_js_parent', WP_PLUGIN_URL . '/feed-nu/js/parent.js', false, $this->version);
		wp_enqueue_script('feed_js_main', WP_PLUGIN_URL . '/feed-nu/js/main.js', false, $this->version);

	}

	public function register_mysettings() {// whitelist options
		register_setting( 'baw-settings-group', 'new_option_name' );
		register_setting( 'baw-settings-group', 'some_other_option' );
		register_setting( 'baw-settings-group', 'option_etc' );
	}

	public function download_file() {
		$attachment = $_GET['feed_attachment'];
		$fullPath = get_attached_file( $attachment, true );



		header("Content-type: image/png");
	    header("Content-length: $fsize");
	    header("access-control-allow-origin: " . $this->remote_url);

		if ($fd = fopen ($fullPath, "r")) {
			$fsize = filesize($fullPath);
			header("Content-type: image/png");
			header("Content-length: $fsize");
			while(!feof($fd)) {
				$buffer = fread($fd, 2048);
				echo $buffer;
			}
		}
		fclose ($fd);
		exit;
	}

	public function on_render_page(){
		//delete_user_meta( wp_get_current_user()->ID, 'api_key');
		//$this->set_migrated(false);

		$current_user = wp_get_current_user();
		if ( 0 == $current_user->ID ) {
			// Not logged in.
		} else {
			// Logged in.
			// include "register.php";
			echo '<script>' . "\n";
			echo 	'window.feed_settings = '. $this->get_pref() . ';' . "\n";
			if($this->is_migrated() == false) {
				echo 	'window.feed_migrate_settings = '. $this->get_migrate_settings() .';' . "\n";
			}
			echo 	'window.feed_remote_url = "'. $this->remote_url .'";' . "\n";
			echo '</script>' . "\n";
			
			echo '<iframe src="'.$this->remote_url.'" width="100%" height="100%"></iframe>' . "\n";
		}
	}


	public function get_migrate_settings(){
		$feed_settings = get_option('feed_settings');
		if( isset($feed_settings['Android'] )) {
			return json_encode($feed_settings);
		}
		return "null";
	}


	public function set_migrated($bool){
		update_option('feed_migrated', $bool);
	}
	public function is_migrated(){
		return get_option('feed_migrated');
	}

	public function get_pref(){
		$current_user = wp_get_current_user();
		$domain  = $this->feed_get_blog_domain();
		$packageName = "com.warting.blogg." . preg_replace('/[^A-Za-z0-9]/', '_', $domain);

		$json = array(
			"baseUrl" => $this->baseUrl,
			"adminEmail" => $current_user->user_email,
			"defaultPackageName" => $packageName,
			"token" => get_user_meta($current_user->ID, 'api_key', true),
			"system" => "wordpress"
		);
		return json_encode($json);
	}

	private function feed_get_blog_domain(){
		$domain = get_option('siteurl');
		$domain = strtolower($domain);
		$domain = str_replace('http://', '', $domain);
		$domain = str_replace('https://', '', $domain);
		while(substr($domain, -1, 1) == '/') {
			$domain = substr($domain, 0, strlen($domain)-1);
		}
		return $domain;
	}

}



//we need to detect WordPress version via our hepler function
function feed_get_wp_version() {
   global $wp_version;
   return $wp_version;
}

//also we will add an option function that will check for plugin admin page or not
function feed_is_plugin_page() {
   $server_uri = "http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
   //for example I added just one of page to check - feed_index as in feed_wp_pointer_content function
   foreach (array('feed-nu') as $allowURI) {
      if(stristr($server_uri, $allowURI)) return true;
   }
   return false;
}

//add media WP scripts
function feed_admin_scripts_init() {
   if(feed_is_plugin_page()) {
      //double check for WordPress version and function exists
      if(function_exists('wp_enqueue_media') && version_compare(feed_get_wp_version(), '3.5', '>=')) {
         //call for new media manager
         wp_enqueue_media();
      }

   }
}
add_action('admin_enqueue_scripts', 'feed_admin_scripts_init');

new Feed_app_plugin();
