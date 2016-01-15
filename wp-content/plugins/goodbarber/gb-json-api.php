<?php
/*
Plugin Name: GoodBarber Api
Plugin URI: http://www.goodbarber.com/
Description: A JSON API fork for WordPress made by goodbarber
Version: 1.0.10
Original Author: Dan Phiffer
Original Author URI: http://phiffer.org/
Author: GoodBarber
Author URI: http://www.goodbarber.com/ 
*/

$dir = gb_json_api_dir();
/*@include_once "$dir/singletons/api.php";
@include_once "$dir/singletons/query.php";
@include_once "$dir/singletons/introspector.php";
@include_once "$dir/singletons/response.php";
@include_once "$dir/models/post.php";
@include_once "$dir/models/comment.php";
@include_once "$dir/models/category.php";
@include_once "$dir/models/tag.php";
@include_once "$dir/models/author.php";
@include_once "$dir/models/attachment.php";
*/
include_once "$dir/singletons/api.php";
include_once "$dir/singletons/query.php";
include_once "$dir/singletons/introspector.php";
include_once "$dir/singletons/response.php";
include_once "$dir/models/post.php";
include_once "$dir/models/comment.php";
include_once "$dir/models/category.php";
include_once "$dir/models/tag.php";
include_once "$dir/models/author.php";
include_once "$dir/models/attachment.php";


function gb_json_api_init() {
  global $gb_json_api;
  if (phpversion() < 5) {
    add_action('admin_notices', 'gb_json_api_php_version_warning');
    return;
  }
  if (!class_exists('GB_JSON_API')) {
    add_action('admin_notices', 'gb_json_api_class_warning');
    return;
  }
  add_filter('rewrite_rules_array', 'gb_json_api_rewrites');
  $gb_json_api = new GB_JSON_API();
}

function gb_json_api_php_version_warning() {
  echo "<div id=\"gb-json-api-warning\" class=\"updated fade\"><p>Sorry, GB JSON API requires PHP version 5.0 or greater.</p></div>";
}

function gb_json_api_class_warning() {
  echo "<div id=\"gb-json-api-warning\" class=\"updated fade\"><p>Oops, GB_JSON_API class not found. If you've defined a GB_JSON_API_DIR constant, double check that the path is correct.</p></div>";
}

function gb_json_api_activation() {
  // Add the rewrite rule on activation
  global $wp_rewrite;
  add_option('gb_json_api_controllers', 'core,respond');
  add_filter('rewrite_rules_array', 'gb_json_api_rewrites');
  add_option('gb_api_key', rand(20000000, 800000000 ) );
  $wp_rewrite->flush_rules();
}

function gb_json_api_deactivation() {
  // Remove the rewrite rule on deactivation
  global $wp_rewrite;
  $wp_rewrite->flush_rules();
}

function gb_json_api_rewrites($wp_rules) {
  $base = get_option('gb_json_api_base', 'gbapi');
  if (empty($base)) {
    return $wp_rules;
  }
  $gb_json_api_rules = array(
    "$base\$" => 'index.php?gbjson=info',
    "$base/(.+)\$" => 'index.php?gbjson=$matches[1]'
  );
  return array_merge($gb_json_api_rules, $wp_rules);
}

function gb_json_api_dir() {
  if (defined('GB_JSON_API_DIR') && file_exists(GB_JSON_API_DIR)) {
    return GB_JSON_API_DIR;
  } else {
    return dirname(__FILE__);
  }
}

// Add initialization and activation hooks
add_action('init', 'gb_json_api_init');
register_activation_hook("$dir/gb-json-api.php", 'gb_json_api_activation');
register_deactivation_hook("$dir/gb-json-api.php", 'gb_json_api_deactivation');

?>
