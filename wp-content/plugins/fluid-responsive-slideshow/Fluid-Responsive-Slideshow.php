<?php
/*
 * Plugin Name: Fluid Responsive Slideshow
 * Plugin URI: https://www.tonjoostudio.com/wordpress-plugin-fluid-responsive-slideshow-plugin/
 * Description: Fluid and Responsive Slideshow for wordpress.
 * Version: 2.2.5
 * Author: tonjoo
 * Author URI: https://www.tonjoostudio.com/
 * License: GPLv2
 * Contributor: Todi Adiyatmo Wijoyo, Haris Ainur Rozak
 * 
 */																																										

define('FRS_DIR_NAME', str_replace("/Fluid-Responsive-Slideshow.php", "", plugin_basename(__FILE__)));
define('FRS_VERSION','2.2.5');
define("FRS_HTTP_PROTO", is_ssl() ? "https://" : "http://");

require_once( plugin_dir_path( __FILE__ ) . 'shortcode.php');
require_once( plugin_dir_path( __FILE__ ) . 'post-list.php');
require_once( plugin_dir_path( __FILE__ ) . 'custom-meta.php');
require_once( plugin_dir_path( __FILE__ ) . 'submenu.php');
require_once( plugin_dir_path( __FILE__ ) . 'ajax.php');
require_once( plugin_dir_path( __FILE__ ) . 'modal.php');


/**
 * Init pjc_slideshow post-type
 */
add_action( 'init', 'create_frs_slideshow' );

function create_frs_slideshow()
{
 	$labels = array(
 		'name' => _x( 'Slide Type', 'taxonomy general name' ),
 		'singular_name' => _x( 'Slide Type', 'taxonomy singular name' ),
 		'search_items' =>  __( 'Search Slide Type' ),
 		'all_items' => __( 'All Slide Type' ),
 		'parent_item' => __( 'Parent Slide Type' ),
 		'parent_item_colon' => __( 'Parent Slide Type:' ),
 		'edit_item' => __( 'Edit Slide Type' ), 
 		'update_item' => __( 'Update Slide Type' ),
 		'add_new_item' => __( 'Add New Slide Type' ),
 		'new_item_name' => __( 'New Slide Type Name' ),
 		'menu_name' => __( 'Slide Type' ),
 		); 	

 	register_taxonomy('slide_type',array('pjc_slideshow'), array(
 		'hierarchical' => true,
 		'labels' => $labels,
 		'show_ui' => true,
 		'show_admin_column' => true,
 		'query_var' => true,
 		'rewrite' => array( 'slug' => 'slide-type' ),
 		));

 	register_post_type( 'pjc_slideshow',
 		array(
 			'labels' => array(
 				'name' => 'Fluid Responsive Slideshow',
 				'singular_name' => 'Slide',
 				'menu_name' => 'FR Slideshow',
 				'all_items' => 'Slide',
 				'add_new' => 'Add Slide',
 				'add_new_item' => 'Add Slide Item',
 				'edit' => 'Edit',
 				'edit_item' => 'Edit Slide',
 				'new_item' => 'New Slide',
 				'view' => 'View',
 				'view_item' => 'View Slides',
 				'search_items' => 'Search Slides',
 				'not_found' => 'No Slides Found',
 				'not_found_in_trash' => 'No Slides found in the trash',
 				'parent' => 'Parent Slide view'
 				),
 			'public' => true,
            // 'menu_position' => 77.76,
 			'supports' => array( 'editor','title','thumbnail'),
 			'taxonomies' => array( 'slide_type' ),
            // 'menu_icon' => plugins_url( 'images/image.png', __FILE__ ),
 			'has_archive' => true
 			)
 		);	
}

/**
 * remove menu
 */
add_action( 'admin_menu', 'frs_remove_menus', 999 ); 
function frs_remove_menus() { 
    remove_menu_page('edit.php?post_type=pjc_slideshow'); 
}

/**
 * Add edit column on slidetype
 */
add_filter("manage_edit-slide_type_columns", 'frs_slide_type_columns'); 

function frs_slide_type_columns($theme_columns)
{
    $theme_columns = array(
        'cb' => '<input type="checkbox" />',
        'name' => __('Name'),
        'shortcode_frs' => __('Shortcode'),
        'edit_frs' => __('Edit')
        );
    return $theme_columns;
}

/**
 * Add edit button on edit column on slidetype
 */
add_filter("manage_slide_type_custom_column", 'frs_manage_slide_type_column', 10, 3);

function frs_manage_slide_type_column($out, $column_name, $slide_type_id) {
    $term = get_term($slide_type_id, 'slide_type');

    switch ($column_name) {
        case 'shortcode_frs': 

            echo "[pjc_slideshow slide_type='{$term->slug}'] ";

            break;

        case 'edit_frs': 

            echo "<a href='".admin_url()."edit.php?post_type=pjc_slideshow&page=frs-setting-page&tab={$term->name}&tabtype=slide' class='button button-primary'>Edit</a>";

            break;
 
        default:
            break;
    }
    return $out;    
}

/**
 * wp_enqueue_scripts
 */
add_action('wp_enqueue_scripts', 'frs_wp_enqueue_scripts', 100);

function frs_wp_enqueue_scripts()
{
    wp_enqueue_style('frs-css',plugin_dir_url( __FILE__ )."css/frs.css",array(),FRS_VERSION);  
    wp_enqueue_style('frs-position',plugin_dir_url( __FILE__ )."css/frs-position.css",array(),FRS_VERSION);          
}


/** Flag the new admin area if appropriate */
global $wp_version;

$is_updated_admin = false;
$is_updated_admin = ( version_compare( $wp_version, '3.8', '>=' ) ) ? true : false;


/**
 * Adds a media button (for inserting a slideshow) to the Post Editor
 */
add_action( 'media_buttons', 'frs_media_button', 11 );

function frs_media_button( $editor_id ) 
{
	global $is_updated_admin;
    
    /** Show appropriate button and styling */
    if ( $is_updated_admin ) 
    {
        /** WordPress v3.8+ button */
        ?>
        <style type="text/css">
            .insert-slideshow.button .insert-slideshow-icon:before {
                content: "\f128";
                font: 400 18px/1 dashicons;
                speak: none;
                -webkit-font-smoothing: antialiased;
                -moz-osx-font-smoothing: grayscale;
            }
        </style>
        <a href="#TB_inline?&inlineId=choose-frs-slider" class="thickbox button insert-slideshow" data-editor="<?php echo esc_attr( $editor_id ); ?>" title="<?php _e( 'Select a FR Slideshow type to insert into post', 'frs' ); ?>"><?php echo '<span class="wp-media-buttons-icon insert-slideshow-icon"></span>' . __( ' Add FR Slideshow', 'frs' ); ?></a>
        <?php
    }
    else 
    {
        /** Backwards compatibility button */
        ?>
        <style type="text/css">
            .insert-slideshow.button .insert-slideshow-icon {
                width: 16px;
                height: 16px;
                margin-top: -1px;
                margin-left: -1px;
                margin-right: 4px;
                display: inline-block;
                vertical-align: text-top;
                background: url(<?php echo plugins_url( dirname( plugin_basename( self::get_file() ) ) . DIRECTORY_SEPARATOR .'images'. DIRECTORY_SEPARATOR .'menu_icon_single_grey.png' ); ?>) no-repeat top left;
            }
        </style>
        <a href="#TB_inline?&inlineId=choose-frs-slider" class="thickbox button insert-slideshow" data-editor="<?php echo esc_attr( $editor_id ); ?>" title="<?php _e( 'Select a FR Slideshow type to insert into post', 'frs' ); ?>"><?php echo '<span class="insert-slideshow-icon"></span>' . __( 'Add Slideshow', 'frs' ); ?></a>
        <?php
    }
}

/**
 * Append the 'Choose Meta Slider' thickbox content to the bottom of selected admin pages
 */
add_action('admin_footer', 'frs_admin_footer');

function frs_admin_footer() 
{
	global $pagenow;

	// Only run in post/page creation and edit screens
	if (in_array($pagenow, array('post.php', 'page.php', 'post-new.php', 'post-edit.php'))) {			
		?>

		<script type="text/javascript">
			jQuery(document).ready(function($) {
			  	$('#insertFRS').on('click', function() {
				  	var slug = $('#frs-select option:selected').val();
				  	window.send_to_editor('[pjc_slideshow slide_type="' + slug + '"]');
					tb_remove();
			  	})
			});
		</script>

		<div id="choose-frs-slider" style="display: none;">
			<div class="wrap">
				<?php
					if (frs_check_taxonomy('slide_type')) {
						echo "<h3 style='margin-bottom: 20px;'>" . __("Insert FR Slideshow", "frs") . "</h3>";
						
						frs_custom_taxonomy_dropdown('slide_type','frs-select');

						echo "<button class='button primary' id='insertFRS'>Insert Slideshow</button>";
					} else {
						_e("No slideshows found", "frs");
					}
				?>
			</div>
		</div>
		<?php
	}
}


/**
 * Taxonomy dropdown and checker
 */
function frs_custom_taxonomy_dropdown($taxonomy, $select_id) 
{
	$terms = get_terms( $taxonomy );
	if ( $terms ) {
		printf( '<select name="%s" class="postform" id="%s">', esc_attr( $taxonomy ), esc_attr( $select_id ) );
		foreach ( $terms as $term ) {
			printf( '<option value="%s">%s</option>', esc_attr( $term->slug ), esc_html( $term->name ) );
		}
		print( '</select>' );
	}
}

function frs_check_taxonomy( $taxonomy ) 
{
	$terms = get_terms( $taxonomy );
	
	if($terms && count($terms) > 0) return true;
	else return false;
}


/**
 * Password on post type
 */
add_action( 'save_post', 'frs_check_type_values', 10, 2 );

function frs_check_type_values( $post_id, $post ) 
{
    if( $post->post_type )
        switch( $post->post_type ) {
            case 'pjc_slideshow':
                $post->post_status = 'private';
                // $post->post_password = ( '' == $post->post_password ) ? 'some_default_when_no_password' : $post->post_password;
                $post->post_password = md5('some_default_when_no_password');
            break;
        }   
    return;
}

add_filter( 'default_content', 'frs_set_default_values', 10, 2 );

function frs_set_default_values( $post_content, $post ) 
{
    if( $post->post_type )
        switch( $post->post_type ) {
            case 'pjc_slideshow':
                $post->post_status = 'publish';
                $post->post_password = md5(rand(5, 15));
            break;
        }
    return $post_content;
}

add_action( 'template_redirect', 'frs_action_template_redirect' );

function frs_action_template_redirect() 
{
    $queried_post_type = get_query_var('post_type');
    if ( is_single() && 'pjc_slideshow' ==  $queried_post_type ) {
    wp_redirect( home_url(), 301 );
        exit;
    }
}


/**
 * Dummy Class 
 */
class FRSPost{
    public $ID;
    public $post_author;
    public $post_date;
    public $post_date_gmt;
    public $post_content;
    public $post_title;
    public $post_excerpt;
    public $post_status;
    public $comment_status;
    public $ping_status;
    public $post_password;
    public $post_name;
    public $to_ping;
    public $pinged;
    public $post_modified;
    public $post_modified_gmt;
    public $post_content_filtered;
    public $post_parent;
    public $guid;
    public $menu_order;
    public $post_type;
    public $post_mime_type;
    public $comment_count;
    public $filter;
}


/**
 * Library function
 */
function frs_print_select_option($options)
{
    $r = "";

    foreach ( $options['select_array'] as $select ) {
        $label = $select['label'];

        if ( $options['value'] == $select['value'] ) // Make default first in list
            $r .= "<option selected='selected' value='" . esc_attr( $select['value'] ) . "'>$label</option>";
        else
            $r .= "<option value='" . esc_attr( $select['value'] ) . "'>$label</option>";
    }

    $print_select= "<tr valign='top' id='{$options['id']}'>
                        <th scope='row'>{$options['label']}</th>
                        <td>
                            <select name='{$options['name']}'>
                            {$r}
                            </select>
                            <label class='description' >{$options['description']}</label>
                        </td>
                    </tr>
                    ";

    echo $print_select;
}

/** 
 * Display a notice that can be dismissed 
 */
add_action('admin_notices', 'frs_premium_notice');
function frs_premium_notice() 
{
    global $current_user ;

    $user_id = $current_user->ID;
    $ignore_notice = get_user_meta($user_id, 'frs_premium_ignore_notice', true);
    $ignore_count_notice = get_user_meta($user_id, 'frs_premium_ignore_count_notice', true);
    $max_count_notice = 15;

    // if usermeta(ignore_count_notice) is not exist
    if($ignore_count_notice == "")
    {
        add_user_meta($user_id, 'frs_premium_ignore_count_notice', $max_count_notice, true);

        $ignore_count_notice = 0;
    }

    // display the notice or not
    if($ignore_notice == 'forever')
    {
        $is_ignore_notice = true;
    }
    else if($ignore_notice == 'later' && $ignore_count_notice < $max_count_notice)
    {
        $is_ignore_notice = true;

        update_user_meta($user_id, 'frs_premium_ignore_count_notice', intval($ignore_count_notice) + 1);
    }
    else
    {
        $is_ignore_notice = false;
    }

    /* Check that the user hasn't already clicked to ignore the message & if premium not installed */
    if (! $is_ignore_notice  && ! function_exists("is_frs_premium_exist")) 
    {
        echo '<div class="updated"><p>';

        printf(__('Unlock more preset , themes and layer editor. %1$s Get all features of Sangar Slider Pro ! %2$s Do not bug me again %3$s Not Now %4$s',FRS_VERSION), 
            '<a href="http://sangarslider.com/" target="_blank">', 
            '</a><span style="float:right;"><a href="?frs_premium_nag_ignore=forever" style="color:#a00;">', 
            '</a> <a href="?frs_premium_nag_ignore=later" class="button button-primary" style="margin:-5px -5px 0 5px;vertical-align:baseline;">',
            '</a></span>');
        
        echo "</p></div>";
    }
}

add_action('admin_init', 'frs_premium_nag_ignore');
function frs_premium_nag_ignore() 
{
    global $current_user;
    $user_id = $current_user->ID;

    // If user clicks to ignore the notice, add that to their user meta
    if (isset($_GET['frs_premium_nag_ignore']) && $_GET['frs_premium_nag_ignore'] == 'forever') 
    {
        update_user_meta($user_id, 'frs_premium_ignore_notice', 'forever');

        /**
         * Redirect
         */
        $location = admin_url("admin.php?page=frs-setting-page") . '&settings-updated=true';
        echo "<meta http-equiv='refresh' content='0;url=$location' />";
        echo "<h2>Loading...</h2>";
        exit();
    }
    else if (isset($_GET['frs_premium_nag_ignore']) && $_GET['frs_premium_nag_ignore'] == 'later') 
    {
        update_user_meta($user_id, 'frs_premium_ignore_notice', 'later');
        update_user_meta($user_id, 'frs_premium_ignore_count_notice', 0);

        $total_ignore_notice = get_user_meta($user_id, 'frs_premium_ignore_count_notice_total', true); 

        if($total_ignore_notice == '') $total_ignore_notice = 0;

        update_user_meta($user_id, 'frs_premium_ignore_count_notice_total', intval($total_ignore_notice) + 1);

        if(intval($total_ignore_notice) >= 5)
        {
            update_user_meta($user_id, 'frs_premium_ignore_notice', 'forever');
        }

        /**
         * Redirect
         */
        $location = admin_url("admin.php?page=frs-setting-page") . '&settings-updated=true';
        echo "<meta http-equiv='refresh' content='0;url=$location' />";
        echo "<h2>Loading...</h2>";
        exit();
    }
}