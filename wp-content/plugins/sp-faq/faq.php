<?php
/*
Plugin Name: WP FAQ
Plugin URL: http://wponlinesupport.com
Description: A simple FAQ plugin
Text Domain: sp-faq
Domain Path: /languages/
Version: 3.2.1
Author: WP Online Support
Author URI: http://wponlinesupport.com
Contributors: WP Online Support
*/

register_activation_hook( __FILE__, 'install_premium_version_faq' );
function install_premium_version_faq(){
if( is_plugin_active('wp-faq-pro/faq.php') ){
     add_action('update_option_active_plugins', 'deactivate_premium_version_faq');
    }
}
function deactivate_premium_version_faq(){
   deactivate_plugins('wp-faq-pro/faq.php',true);
}
add_action( 'admin_notices', 'rpfs_admin_notice_faq' );
function rpfs_admin_notice_faq() {
   $dir = ABSPATH . 'wp-content/plugins/wp-faq-pro/faq.php';
    if( is_plugin_active( 'sp-faq/faq.php' ) && file_exists($dir)) {
        global $pagenow;
        if( $pagenow == 'plugins.php' ){
            deactivate_plugins ( 'wp-faq-pro/faq.php',true);
            if ( current_user_can( 'install_plugins' ) ) {
                echo '<div id="message" class="updated notice is-dismissible"><p><strong>Thank you for activating WP FAQ</strong>.<br /> It looks like you had PRO version <strong>(<em>WP FAQ Pro</em>)</strong> of this plugin activated. To avoid conflicts the extra version has been deactivated and we recommend you delete it. </p></div>';
            }
        }
    }
} 

add_action('plugins_loaded', 'sp_faq_load_textdomain');
function sp_faq_load_textdomain() {
	load_plugin_textdomain( 'sp-faq', false, dirname( plugin_basename(__FILE__) ) . '/languages/' );
} 

 
function sp_faq_setup_post_types() {
	$festivals_labels =  apply_filters( 'sp_faq_labels', array(
		'name'                 => _x('FAQs', 'sp-faq'),
		'singular_name'        => _x('FAQ', 'sp-faq'),
		'add_new'              => _x('Add New', 'sp-faq'),
		'add_new_item'        => __('Add New FAQ', 'sp-faq'),
		'edit_item'           => __('Edit FAQ', 'sp-faq'),
		'new_item'            => __('New FAQ', 'sp-faq'),
		'all_items'           => __('All FAQ', 'sp-faq'),
		'view_item'           => __('View FAQ', 'sp-faq'),
		'search_items'        => __('Search FAQ', 'sp-faq'),
		'not_found'           => __('No FAQ found', 'sp-faq'),
		'not_found_in_trash'  => __('No FAQ found in Trash', 'sp-faq'),
		'parent_item_colon'   => '',
		'menu_name'           => __('FAQ', 'sp-faq'),
		'exclude_from_search' => true
	) );
	$faq_args = array(
		'labels' 			=> $festivals_labels,
		'public' 			=> true,
		'publicly_queryable'=> true,
		'show_ui' 			=> true,
		'show_in_menu' 		=> true,
		'query_var' 		=> true,
		'capability_type' 	=> 'post',
		'has_archive' 		=> true,
		'hierarchical' 		=> false,
		'menu_icon'   => 'dashicons-info',
		'supports' => array('title','editor','thumbnail','excerpt')
	);
	register_post_type( 'sp_faq', apply_filters( 'sp_faq_post_type_args', $faq_args ) );
}
add_action('init', 'sp_faq_setup_post_types');
/*
 * Add [sp_faq limit="-1"] shortcode
 *
 */
function sp_faq_shortcode( $atts, $content = null ) {
	extract(shortcode_atts(array(
		"limit" => '',
		"category" => '',
		"single_open"   => '',
		"transition_speed" => '',
	), $atts));
	// Define limit
	if( $limit ) { 
		$posts_per_page = $limit; 
	} else {
		$posts_per_page = '-1';
	}
	// Define limit
	if( $category ) { 
		$cat = $category; 
	} else {
		$cat = '';
	}
	
	if( $single_open != ''  ) { 
		$faqsingleOpen = $single_open; 
	} else {
		$faqsingleOpen = 'true';
	}
	
	if( $transition_speed != '' ) { 
		$faqtransitionSpeed = $transition_speed; 
	} else {
		$faqtransitionSpeed = '300';
	}
	
	ob_start();
	// Create the Query
	
	$post_type 		= 'sp_faq';
	$orderby 		= 'post_date';
	$order 			= 'DESC';
				 
        $args = array ( 
            'post_type'      => $post_type, 
            'orderby'        => $orderby, 
            'order'          => $order,
            'posts_per_page' => $posts_per_page,           
            );
	if($cat != ""){
            	$args['tax_query'] = array( array( 'taxonomy' => 'faq_cat', 'field' => 'id', 'terms' => $cat) );
            }        
      $query = new WP_Query($args);
	//Get post type count
	$post_count = $query->post_count;
	$i = 1;
	// Displays Custom post info
	if( $post_count > 0) :
	?>
	<div class="faq-accordion" data-accordion-group>	
	
	<?php while ($query->have_posts()) : $query->the_post();
		?>			  
      <div data-accordion class="faq-main">
        <div data-control class="faq-title"><h4> <?php the_title(); ?></h4></div>
        <div data-content>
         <?php
                  if ( function_exists('has_post_thumbnail') && has_post_thumbnail() ) { 
				  
                    the_post_thumbnail('thumbnail'); 
                  }
                  ?>
				  
          
        <div class="faq-content"><?php the_content(); ?></div>
      
        </div>
      </div>
	  
		<?php
		$i++;
		endwhile; ?>
		</div>
<?php	endif;
	// Reset query to prevent conflicts
	wp_reset_query();
	?>
	    <script type="text/javascript">
      jQuery(document).ready(function() {
        jQuery('.faq-accordion [data-accordion]').accordionfaq({
		 singleOpen: <?php echo $faqsingleOpen; ?>,
		 transitionEasing: 'ease',
          transitionSpeed: <?php echo $faqtransitionSpeed; ?>
		});        
      });
    </script>
	<?php
	return ob_get_clean();
}
add_shortcode("sp_faq", "sp_faq_shortcode");

/* Register Taxonomy */
add_action( 'init', 'free_sp_faq_taxonomies');
function free_sp_faq_taxonomies() {
    $labels = array(
        'name'              => _x( 'Category', 'sp-faq' ),
        'singular_name'     => _x( 'Category', 'sp-faq' ),
        'search_items'      => __( 'Search Category', 'sp-faq' ),
        'all_items'         => __( 'All Category', 'sp-faq' ),
        'parent_item'       => __( 'Parent Category', 'sp-faq' ),
        'parent_item_colon' => __( 'Parent Category' , 'sp-faq' ),
        'edit_item'         => __( 'Edit Category', 'sp-faq' ),
        'update_item'       => __( 'Update Category', 'sp-faq' ),
        'add_new_item'      => __( 'Add New Category', 'sp-faq' ),
        'new_item_name'     => __( 'New Category Name', 'sp-faq' ),
        'menu_name'         => __( 'FAQ Category', 'sp-faq' ),
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'faq_cat' ),
    );

    register_taxonomy( 'faq_cat', array( 'sp_faq' ), $args );
}


add_action( 'wp_enqueue_scripts','style_css_script_free' );
    function style_css_script_free() {
        wp_enqueue_style( 'accordioncssfree',  plugin_dir_url( __FILE__ ). 'css/jquery.accordion.css' );
        wp_enqueue_script( 'accordionjsfree', plugin_dir_url( __FILE__ ) . 'js/jquery.accordion.js', array( 'jquery' ));
		
    }
	
	

// Manage Category Shortcode Columns
add_filter("manage_faq_cat_custom_column", 'free_sp_faq_cat_columns', 10, 3);
add_filter("manage_edit-faq_cat_columns", 'free_sp_faq_cat_manage_columns'); 
 
function free_sp_faq_cat_manage_columns($theme_columns) {
    $new_columns = array(
            'cb' => '<input type="checkbox" />',
            'name' => __('Name'),
            'faq_category_shortcode' => __( 'FAQ Category Shortcode', 'sp-faq' ),
            'slug' => __('Slug'),
            'posts' => __('Posts')
        );
    return $new_columns;

}


function free_sp_faq_cat_columns($out, $column_name, $theme_id) {
    $theme = get_term($theme_id, 'faq_cat');
    switch ($column_name) {
        
        case 'title':
            echo get_the_title();
        break;

        case 'faq_category_shortcode':             
             echo '[sp_faq category="' . $theme_id. '"]';
        break;
 
        default:
            break;
    }
    return $out;    
}


require_once( 'profaq_menu_function.php' );