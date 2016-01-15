<?php

/**
 * Add submenu for plugin option, the page definition is on submenu-page.php
 */
add_action( 'admin_menu', 'pjc_slideshow_add_submenu' );
function pjc_slideshow_add_submenu() {	
	add_menu_page('FR Slideshow', 
		'FR Slideshow', 
		'moderate_comments', 
		'frs-setting-page', 
		'pjc_slideshow_submenu_page', 
		'dashicons-format-image');	
}

/**
 * admin_enqueue_scripts - equeue on plugin page only
 */
if (strpos($_SERVER['REQUEST_URI'], "frs-setting-page") !== false)
{
	add_action('admin_enqueue_scripts', 'frs_admin_enqueue_scripts');
}

function frs_admin_enqueue_scripts()
{
    wp_enqueue_style('frs-admin-css',plugin_dir_url( __FILE__ )."css/frs-admin.css",array(),FRS_VERSION);
    wp_enqueue_style('colorpicker-css',plugin_dir_url( __FILE__ )."css/jquery.miniColors.css",array(),FRS_VERSION);   
    wp_enqueue_style('frs-css',plugin_dir_url( __FILE__ )."css/frs.css",array(),FRS_VERSION);      
    wp_enqueue_style('frs-position',plugin_dir_url( __FILE__ )."css/frs-position.css",array(),FRS_VERSION);      
    
    /**
     * filter by admin page
     */
    $is_post_page = get_query_var( 'post' ) ? get_query_var( 'post' ) : false;

    if(isset($_GET['tab']) && $_GET['tab'] != "")
    {
        if(isset($_GET['page']) && $_GET['page'] == "frs-setting-page" && isset($_GET['tabtype']))
        {
            if($_GET['tabtype'] == "slide")
            {
                wp_enqueue_style('select2-css',plugin_dir_url( __FILE__ )."css/select2.css",array(),FRS_VERSION);
            }
            else
            {
                /* configuration page */
                wp_enqueue_style('select2-css',plugin_dir_url( __FILE__ )."css/select2-pure.css",array(),FRS_VERSION);
                wp_enqueue_script('ace-js',plugin_dir_url( __FILE__ )."js/ace-min-noconflict-css-monokai/ace.js",array(),FRS_VERSION);
            }
        }
        else if(isset($_GET['page']) && $_GET['page'] == "frs-setting-page" && ! isset($_GET['tabtype']))
        {
            /* configuration page */
            wp_enqueue_style('select2-css',plugin_dir_url( __FILE__ )."css/select2-pure.css",array(),FRS_VERSION);
            wp_enqueue_script('ace-js',plugin_dir_url( __FILE__ )."js/ace-min-noconflict-css-monokai/ace.js",array(),FRS_VERSION);
        }
    }
    else
    {
        wp_enqueue_style('select2-css',plugin_dir_url( __FILE__ )."css/select2.css",array(),FRS_VERSION);
    }

    wp_enqueue_script('jquery');  
    wp_enqueue_script('jquery-ui-accordion');

    $other = isset($_GET['other']) ? $_GET['other'] : '';

    // inline js
    echo "<script type='text/javascript'>";
    echo "var admin_url = '".get_admin_url()."admin.php?page=frs-setting-page';";
    echo "var assets_url = '".plugins_url(FRS_DIR_NAME."/assets/")."';";    
    echo "var get_other = '".$other."';";    
    echo "</script>";
    
    wp_enqueue_script('jquery-ui-sortable');
    wp_enqueue_script('frs-admin-js',plugin_dir_url( __FILE__ )."js/tonjoo_frs_admin.js",array(),FRS_VERSION);      
    wp_enqueue_script('jquery_validate_js', plugin_dir_url( __FILE__ )."js/jquery.validate.js", array('jquery'),FRS_VERSION);
    wp_enqueue_script('validate_action_js', plugin_dir_url( __FILE__ )."js/validate_action.js",array(),FRS_VERSION);
    wp_enqueue_script('colorpicker-js',plugin_dir_url( __FILE__ )."js/jquery.miniColors.js",array(),FRS_VERSION);   
    wp_enqueue_script('select2-js',plugin_dir_url( __FILE__ )."js/select2.js",array(),FRS_VERSION);  
    
    // accordion
    wp_enqueue_style('wp-accordion-frs-css',plugin_dir_url( __FILE__ )."css/wp-accordion.css",array(),FRS_VERSION);
    wp_enqueue_script('wp-accordion-frs-js',plugin_dir_url( __FILE__ )."js/wp-accordion.js",array(),FRS_VERSION);  

    // intro.js
    wp_enqueue_style('wp-introjs-frs-css',plugin_dir_url( __FILE__ )."js/introjs/introjs.min.css",array(),FRS_VERSION);
    wp_enqueue_script('wp-introjs-frs-js',plugin_dir_url( __FILE__ )."js/introjs/intro.min.js",array(),FRS_VERSION);  
    
}

require_once( plugin_dir_path( __FILE__ ) . 'submenu-page.php');