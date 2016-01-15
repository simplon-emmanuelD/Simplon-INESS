<?php

class GB_JSON_API {
  
  function __construct() {
    $this->query = new GB_JSON_API_Query();
    $this->introspector = new GB_JSON_API_Introspector();
    $this->response = new GB_JSON_API_Response();
    add_action('template_redirect', array(&$this, 'template_redirect'));
    add_action('admin_menu', array(&$this, 'admin_menu'));
    add_action('update_option_gb_json_api_base', array(&$this, 'gb_flush_rewrite_rules'));
    add_action('pre_update_option_gb_json_api_controllers', array(&$this, 'gb_update_controllers'));
  }
  
  function template_redirect() {
    // Check to see if there's an appropriate API controller + method    
    $controller = strtolower($this->query->get_controller());
    $available_controllers = $this->get_controllers();
    $enabled_controllers = explode(',', get_option('gb_json_api_controllers', 'core,respond'));
    $active_controllers = array_intersect($available_controllers, $enabled_controllers);
    if ($controller) {
      
      if (!in_array($controller, $active_controllers)) {
        $this->error("Unknown controller $temp '$controller'.");
      }
      
      $controller_path = $this->controller_path($controller);
      if (file_exists($controller_path)) {
        require_once $controller_path;
      }
      $controller_class = $this->controller_class($controller);
      
      if (!class_exists($controller_class)) {
        $this->error("Unknown controller '$controller_class'.");
      }
      
      $this->controller = new $controller_class();
      $method = $this->query->get_method($controller);
      
      if ($method) {
        
        $this->response->setup();
        
        // Run action hooks for method
        do_action("gb_json_api-{$controller}-$method");
        
        // Error out if nothing is found
        if ($method == '404') {
          $this->error('Not found');
        }
        
        // Run the method
        $result = $this->controller->$method();
        
        // Handle the result
        $this->response->respond($result);
        
        // Done!
        exit;
      }
    }
  }
 

 
  function admin_menu() {
    add_options_page('GoodBarber Api Settings', 'GoodBarber Api', 'manage_options', 'gb-json-api', array(&$this, 'admin_options'));
  }
  
  function admin_options() { 
	if (!current_user_can('manage_options'))  {
      wp_die( __('You do not have sufficient permissions to access this page.') );
    }
	$available_controllers = $this->get_controllers();
    $active_controllers = explode(',', get_option('gb_json_api_controllers', 'core,respond'));

    if (count($active_controllers) == 1 && empty($active_controllers[0])) {
      $active_controllers = array();
    }
    if($_REQUEST['gb_submit'] != null ){	 
	if ($_REQUEST['is_secure_mode_enabled'] == 'on'){
		$option_exists = (get_option('is_secure_mode_enabled', null) !== null);
		    if ($option_exists) {
		      update_option('is_secure_mode_enabled', 'True');
		    } else {
		      add_option('is_secure_mode_enabled', 'True');
    		    }
	
	}else{
		$option_exists = (get_option('is_secure_mode_enabled', null) !== null);
                    if ($option_exists) {
                      update_option('is_secure_mode_enabled', 'False');
                    } else {
                      add_option('is_secure_mode_enabled', 'False');
                    }
	}
	
	if ($_REQUEST['is_comments_enabled'] == 'on'){
                $option_exists = (get_option('is_comments_enabled', null) !== null);
                    if ($option_exists) {
                      update_option('is_comments_enabled', 'True');
                    } else {
                      add_option('is_comments_enabled', 'True');
                    }
		update_option('gb_json_api_controllers', 'core,respond');

        }else{
                $option_exists = (get_option('is_comments_enabled', null) !== null);
                    if ($option_exists) {
                      update_option('is_comments_enabled', 'False');
                    } else {
                      add_option('is_comments_enabled', 'False');
                    }
		update_option('gb_json_api_controllers', 'core');
        }
    }
	 ?>
		
	<div>
	<form action="options-general.php?page=gb-json-api" method="post" >
		<style type="text/css">  #goodbarber {background-color : "#EDF2F2" !important; color : "#222222" !important} </style>
		<div id='goodbarber' >
		<h1> GoodBarber Api</h1>
		<?php _e('A JSON-API fork used to synchronize your GoodBarber app with your wordpress.') ?><br/><br/>
		<h2>GoodBarber Key </h2>
		<?php _e('Provide this key in your GoodBarber back office when you first add this website as a source.') ?>
		<h1><?php echo get_option('gb_api_key', ''); ?></h1><br/>
	        <h2>Options</h2>
		<h3><?php _e('Comments submission')?> </h3>
		<?php $ice=get_option('is_comments_enabled', 'True');  ?>
		<?php _e('If you want to let your users post comments to your website right from your GoodBarber mobile app, check the box below.') ?><br/><br/>
		<input name='is_comments_enabled' type="checkbox" <?php if($ice == 'True'){echo 'checked="checked"'; } ?>><?php _e(' Enable comment submission') ?><br/><br/>
		<h3><?php _e('Security management') ?> </h3>
                <?php _e('By default, GoodBarber Api secure mode is off. If you want to limit the access to this plugin to GoodBarber servers only, check the box below.') ?><br/><br/>
                <?php $isme= get_option('is_secure_mode_enabled', 'False'); ?>
                <input name='is_secure_mode_enabled' type="checkbox" <?php if($isme == 'True'){echo 'checked="checked"';} ?>><?php _e(' Enable secure mode (IP restriction to GoodBarber servers only)') ?>
   		<p class="submit"> <input type="submit" class="button-primary" name="gb_submit" value="<?php _e('Save Changes') ?>" /> </p></div></form></div>
<?php		
  } 
  function get_method_url($controller, $method, $options = '') {
    $url = get_bloginfo('url');
    $base = get_option('gb_json_api_base', 'gbapi');
    $permalink_structure = get_option('permalink_structure', '');
    if (!empty($options) && is_array($options)) {
      $args = array();
      foreach ($options as $key => $value) {
        $args[] = urlencode($key) . '=' . urlencode($value);
      }
      $args = implode('&', $args);
    } else {
      $args = $options;
    }
    if ($controller != 'core') {
      $method = "$controller/$method";
    }
    if (!empty($base) && !empty($permalink_structure)) {
      if (!empty($args)) {
        $args = "?$args";
      }
      return "$url/$base/$method/$args";
    } else {
      return "$url?gbjson=$method&$args";
    }
  }
  
  function save_option($id, $value) {
    $option_exists = (get_option($id, null) !== null);
    if ($option_exists) {
      update_option($id, $value);
    } else {
      add_option($id, $value);
    }
  }
  
  function get_controllers() {
    $controllers = array();
    $dir = gb_json_api_dir();
    $dh = opendir("$dir/controllers");
    while ($file = readdir($dh)) {
      if (preg_match('/(.+)\.php$/', $file, $matches)) {
        $controllers[] = $matches[1];
      }
    }
    $controllers = apply_filters('gb_json_api_controllers', $controllers);
    return array_map('strtolower', $controllers);
  }
  
  function controller_is_active($controller) {
    if (defined('GB_JSON_API_CONTROLLERS')) {
      $default = GB_JSON_API_CONTROLLERS;
    } else {
      $default = 'core,respond';
    }
    $active_controllers = explode(',', get_option('gb_json_api_controllers', $default));
    return (in_array($controller, $active_controllers));
  }
  
  function gb_update_controllers($controllers) {
    if (is_array($controllers)) {
      return implode(',', $controllers);
    } else {
      return $controllers;
    }
  }
  
  function controller_info($controller) {
    $path = $this->controller_path($controller);
    $class = $this->controller_class($controller);
    $response = array(
      'name' => $controller,
      'description' => '(No description available)',
      'methods' => array()
    );
    if (file_exists($path)) {
      $source = file_get_contents($path);
      if (preg_match('/^\s*Controller name:(.+)$/im', $source, $matches)) {
        $response['name'] = trim($matches[1]);
      }
      if (preg_match('/^\s*Controller description:(.+)$/im', $source, $matches)) {
        $response['description'] = trim($matches[1]);
      }
      if (preg_match('/^\s*Controller URI:(.+)$/im', $source, $matches)) {
        $response['docs'] = trim($matches[1]);
      }
      if (!class_exists($class)) {
        require_once($path);
      }
      $response['methods'] = get_class_methods($class);
      return $response;
    } else if (is_admin()) {
      return "Cannot find controller class '$class' (filtered path: $path).";
    } else {
      $this->error("Unknown controller .'$controller'.");
    }
    return $response;
  }
  
  function controller_class($controller) {
    return "gb_json_api_{$controller}_controller";
  }
  
  function controller_path($controller) {
    $dir = gb_json_api_dir();
    $controller_class = $this->controller_class($controller);
    return apply_filters("{$controller_class}_path", "$dir/controllers/$controller.php");
  }
  
  function get_nonce_id($controller, $method) {
    $controller = strtolower($controller);
    $method = strtolower($method);
    return "gb_json_api-$controller-$method";
  }
  
  function flush_rewrite_rules() {
    global $wp_rewrite;
    $wp_rewrite->flush_rules();
  }
  
  function error($message = 'Unknown error', $status = 'error') {
    $this->response->respond(array(
      'error' => $message
    ), $status);
  }
  
  function include_value($key) {
    return $this->response->is_value_included($key);
  }
  
}

?>
