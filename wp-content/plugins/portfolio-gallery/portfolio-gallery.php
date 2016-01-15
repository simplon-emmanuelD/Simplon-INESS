<?php

/*
Plugin Name: Huge IT Portfolio Gallery
Plugin URI: http://huge-it.com/portfolio-gallery
Description: Portfolio Gallery is a great plugin for adding specialized portfolios or gallery to your site. There are various view options for the images or videos to choose from.
Version: 1.8.3
Author: Huge IT
Author URI: http://huge-it.com/
License: GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
Text Domain: portfolio-gallery
Domain Path: /languages
*/


function portfolio_gallery_load_plugin_textdomain() {
    load_plugin_textdomain( 'portfolio-gallery', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
}

add_action( 'plugins_loaded', 'portfolio_gallery_load_plugin_textdomain' );


add_action('media_buttons_context', 'add_portfolio_my_custom_button');


add_action('admin_footer', 'add_portfolio_inline_popup_content');


function add_portfolio_my_custom_button($context) {
  

  $img = plugins_url( '/images/post.button.png' , __FILE__ );
  

  $container_id = 'huge_it_portfolio';
  
  $title = 'Select Huge IT Portfolio Gallery to insert into post.';

  $context .= '<a class="button thickbox" title="Select portfolio gallery to insert into post"    href="#TB_inline?width=400&inlineId='.$container_id.'">
		<span class="wp-media-buttons-icon" style="background: url('.$img.'); background-repeat: no-repeat; background-position: left bottom;"></span>
	Add Portfolio Gallery
	</a>';
  
  return $context;
}
add_action('wp_ajax_my_action', 'huge_it_portfolio_my_action_callback_frontend');
add_action('wp_ajax_nopriv_my_action', 'huge_it_portfolio_my_action_callback_frontend' );
    function huge_it_portfolio_my_action_callback_frontend(){
        //var_dump($_POST);
        global $wpdb;
        if($_POST['post'] == 'portfolioChangeOptions'){
            
            if(isset($_POST['id'])){
                $id = $_POST['id'];
                $query=$wpdb->prepare("SELECT * FROM ".$wpdb->prefix."huge_itportfolio_portfolios WHERE id = %d", $id);
                $row=$wpdb->get_row($query);
                $response = array(  'portfolio_effects_list'    => $row->portfolio_list_effects_s,
                                    'ht_show_sorting'           => $row->ht_show_sorting,
                                    'sl_pausetime'              => $row->description,
                                    'sl_changespeed'            => $row->param,
                                    'pause_on_hover'            => $row->pause_on_hover);
                echo json_encode($response);
                die();
            }
        }
        if($_POST['post'] == 'portfolioSaveOptions'){
            if(isset($_POST["htportfolio_id"])){
                $id = $_POST["htportfolio_id"];  
                $wpdb->query($wpdb->prepare("UPDATE ".$wpdb->prefix."huge_itportfolio_portfolios SET  ht_show_sorting = '%s'  WHERE id = %d ", sanitize_text_field($_POST["ht_show_sorting"]), $id));
                $wpdb->query($wpdb->prepare("UPDATE ".$wpdb->prefix."huge_itportfolio_portfolios SET  description = '%s'  WHERE id = %d ", sanitize_text_field($_POST["sl_pausetime"]), $id));
                $wpdb->query($wpdb->prepare("UPDATE ".$wpdb->prefix."huge_itportfolio_portfolios SET  param = '%s'  WHERE id = %d ", sanitize_text_field($_POST["sl_changespeed"]), $id));
                $wpdb->query($wpdb->prepare("UPDATE ".$wpdb->prefix."huge_itportfolio_portfolios SET  description = '%s'  WHERE id = %d ", sanitize_text_field($_POST["sl_pausetime"]), $id));
                $wpdb->query($wpdb->prepare("UPDATE ".$wpdb->prefix."huge_itportfolio_portfolios SET  pause_on_hover = '%s'  WHERE id = %d ", sanitize_text_field($_POST["pause_on_hover"]), $id));
                $wpdb->query($wpdb->prepare("UPDATE ".$wpdb->prefix."huge_itportfolio_portfolios SET  portfolio_list_effects_s = '%s'  WHERE id = %d ", sanitize_text_field($_POST["portfolio_effects_list"]), $id));
                /*$wpdb->query($wpdb->prepare("UPDATE ".$wpdb->prefix."huge_itportfolio_portfolios SET  sl_loading_icon = '%s' WHERE id = %d ", $_POST["sl_loading_icon"], $id));
                $wpdb->query($wpdb->prepare("UPDATE ".$wpdb->prefix."huge_itportfolio_portfolios SET  show_thumb = '%s' WHERE id = %d ", $_POST["show_thumb"], $id));/*add*/
            }
        }
    }

function add_portfolio_inline_popup_content() {
?>
<style>
    #portfolio-unique-options-list label{
        width:152px;
        display:inline-block;
    }
    #hugeitportfolioinsert{
        margin-left:10px;
    }
    #portfolio-unique-options-list input[type='checkbox']{
        margin-left: 1px;
    }
    #TB_window{
        background: #f1f1f1;
    }
    #sl_pausetime{
        width:80px;
    }
    #sl_changespeed{
        width:80px;
    }
</style>
<script type="text/javascript">
				jQuery(document).ready(function() {
                                       var ht_show_sorting;
                                       var ht_show_filtering;
                                       var pause_on_hover;
                                       
                                    jQuery('#ht_show_sorting').change(function(){
                                            if(jQuery('#ht_show_sorting').prop('checked')  == false){
                                               jQuery('#ht_show_sorting').val('off');
                                            }
                                            else if(jQuery('#ht_show_sorting').prop('checked')  == true){
                                               jQuery('#ht_show_sorting').val('on');
                                        }
                                    }); 
                                        jQuery('#pause_on_hover').change(function(){
                                            if(jQuery('#pause_on_hover').prop('checked')  == false){
                                               jQuery('#pause_on_hover').val('off');
                                            }
                                            else if(jQuery('#pause_on_hover').prop('checked')  == true){
                                               jQuery('#pause_on_hover').val('on');
                                            }
                                        }); 
                                        
                                    jQuery('#hugeitportfolioinsert').on('click', function() {console.log(1);
                                        ht_show_sorting = jQuery('#ht_show_sorting').val();
                                        pause_on_hover = jQuery('#pause_on_hover').val();
				  	var id = jQuery('#huge_it_portfolio-select option:selected').val();
                                        var portfolio_effects_list = jQuery('#portfolio_effects_list').val();
                                        var sl_pausetime = jQuery('#sl_pausetime').val();
                                        var sl_changespeed = jQuery('#sl_changespeed').val();
                                        var err=0;
                                        if(!jQuery.isNumeric(sl_pausetime) || sl_pausetime < 0){
                                            err = err + 1;
                                        }else{
                                            sl_pausetime = Math.round(sl_pausetime);
                                        }
                                        if(!jQuery.isNumeric(sl_changespeed) || sl_changespeed < 0){
                                            err = err + 1;
                                        }else{
                                            sl_changespeed = Math.round(sl_changespeed);
                                        }
                                        if(err>0) {
                                            alert('Fill the fields correctly.');
                                            return false;
                                        }
                                        var data = {
                                                action:                 'my_action',
                                                post:                   'portfolioSaveOptions',
                                                htportfolio_id:         id,
                                                portfolio_effects_list: portfolio_effects_list,
                                                ht_show_sorting:        ht_show_sorting,
                                                sl_pausetime:           sl_pausetime,
                                                sl_changespeed:         sl_changespeed,
                                                pause_on_hover:         pause_on_hover
                                            };
                                            jQuery.post("<?php echo admin_url('admin-ajax.php'); ?>", data, function(response) {
                                            
                                            });
				  	window.send_to_editor('[huge_it_portfolio id="' + id + '"]');
					tb_remove();
				 
				});
                                    jQuery('#portfolio_effects_list').on('change',function(){
                                        var sel = jQuery(this).val();
                                        if(sel == 5) {
                                                jQuery('.for-content-slider').css('display','block')
                                        }
                                        else {
                                                jQuery('.for-content-slider').css('display','none')
                                        }
                                        });
                                        jQuery('#portfolio_effects_list').change();

                                        //////////////////portfolio change options/////////////////////
                                        jQuery('#huge_it_portfolio-select').change(function(){
                                            
                                            var sel = jQuery(this).val();
                                            var data = {
                                                action: 'my_action',
                                                post:   'portfolioChangeOptions',
                                                id:     sel
                                            };
                                            jQuery.post("<?php echo admin_url('admin-ajax.php'); ?>", data, function(response) {
                                                response = JSON.parse(response);
                                                console.log(response);
                                                var list_effect = response.portfolio_list_effects_s;
                                                jQuery('#portfolio_effects_list').val(response.portfolio_effects_list);
                                                jQuery('#portfolio_effects_list option[value=list_effect]').attr('selected');
                                                jQuery('#ht_show_sorting').val(response.ht_show_sorting);
                                                if(jQuery('#ht_show_sorting').val()  == 'on'){
                                                    jQuery('#ht_show_sorting').attr('checked','checked');
                                                }
                                                else jQuery('#ht_show_sorting').removeAttr('checked');
                                                jQuery('#sl_pausetime').val(response.sl_pausetime);
                                                jQuery('#sl_changespeed').val(response.sl_changespeed);
                                                jQuery('#pause_on_hover').val(response.pause_on_hover);
                                                if(jQuery('#pause_on_hover').val()  == 'on'){
                                                    jQuery('#pause_on_hover').attr('checked','checked');
                                                }
                                                else jQuery('#pause_on_hover').removeAttr('checked');
                                                if(response){
                                                    sel1 = jQuery('#portfolio_effects_list').val();
                                                    if(sel1 == 5) {
                                                        jQuery('.for-content-slider').css('display','block')
                                                    }
                                                    else {
                                                        jQuery('.for-content-slider').css('display','none')
                                                    };
                                                }

                                            });
                                            
                                                
                                    });
                                });
</script>

<div id="huge_it_portfolio" style="display:none;">
  <h3>Select Huge IT Portfolio Gallery to insert into post</h3>
  <?php 
  	  global $wpdb;
          $query="SELECT * FROM ".$wpdb->prefix."huge_itportfolio_portfolios";
	  $firstrow=$wpdb->get_row($query);
          $container_id = 'huge_it_portfolio';
	  if(isset($_POST["hugeit_portfolio_id"])){
	  $id=$_POST["hugeit_portfolio_id"];
	  }
	  else{
	  $id=$firstrow->id;
	  }
	  $query="SELECT * FROM ".$wpdb->prefix."huge_itportfolio_portfolios order by id ASC";
			   $shortcodeportfolios=$wpdb->get_results($query);
        $query=$wpdb->prepare("SELECT * FROM ".$wpdb->prefix."huge_itportfolio_portfolios WHERE id= %d", $id);
	$row=$wpdb->get_row($query);
			   ?>

 <?php 	if (count($shortcodeportfolios)) {
            ?>
  
  <?php
							echo "<select id='huge_it_portfolio-select'  name='hugeit_portfolio_id'>";
							foreach ($shortcodeportfolios as $shortcodeportfolio) {
								echo "<option value='".$shortcodeportfolio->id."'>".$shortcodeportfolio->name."</option>";
							}
							echo "</select>";?>
                                                        <?php	echo "<button class='button primary' id='hugeitportfolioinsert'>Insert portfolio gallery</button>";
						} else {
							echo "No slideshows found", "huge_it_portfolio";
						}
						?>
	
                                            <h3>Current Portfolio Options</h3>   		          
                                            <ul id="portfolio-unique-options-list">
						<li style="display:none;">
							<label for="sl_width"><?php echo __( 'The requested action is not valid.', 'portfolio-gallery' );?></label>
							<input type="text" name="sl_width" id="sl_width" value="1111" class="text_area" />
						</li>
						<li style="display:none;">
							<label for="sl_height"><?php echo __( 'Height', 'portfolio-gallery' );?></label>
							<input type="text" name="sl_height" id="sl_height" value="<?php echo $row->sl_height; ?>" class="text_area" />
						</li>
						<li>
							<label for="portfolio_effects_list"><?php echo __( 'Select The View', 'portfolio-gallery' );?></label>
							<select name="portfolio_effects_list" id="portfolio_effects_list">
									<option <?php if($row->portfolio_list_effects_s == '0'){ echo 'selected'; } ?>  value="0"><?php echo __( 'Blocks Toggle Up/Down', 'portfolio-gallery' );?></option>
									<option <?php if($row->portfolio_list_effects_s == '1'){ echo 'selected'; } ?>  value="1"><?php echo __( 'Full-Height Blocks', 'portfolio-gallery' );?></option>
									<option <?php if($row->portfolio_list_effects_s == '2'){ echo 'selected'; } ?>  value="2"><?php echo __( 'Gallery/Content-Popup', 'portfolio-gallery' );?></option>
									<option <?php if($row->portfolio_list_effects_s == '3'){ echo 'selected'; } ?>  value="3"><?php echo __( 'Full-Width Blocks', 'portfolio-gallery' );?></option>
									<option <?php if($row->portfolio_list_effects_s == '4'){ echo 'selected'; } ?>  value="4"><?php echo __( 'FAQ Toggle Up/Down', 'portfolio-gallery' );?></option>
									<option <?php if($row->portfolio_list_effects_s == '5'){ echo 'selected'; } ?>  value="5"><?php echo __( 'Content Slider', 'portfolio-gallery' );?></option>
									<option <?php if($row->portfolio_list_effects_s == '6'){ echo 'selected'; } ?>  value="6"><?php echo __( 'Lightbox-Gallery', 'portfolio-gallery' );?></option>
							</select>
						</li>
                                                <li class="allowIsotope">
                                                    <label for="ht_show_sorting"><?php echo __( 'Show Sorting Buttons', 'portfolio-gallery' );?></label>
						    <input type="checkbox" id="ht_show_sorting"  <?php if($row->ht_show_sorting  == 'on'){ echo 'checked="checked"'; } ?>  name="ht_show_sorting" value="<?php echo $row->ht_show_sorting;?>" />
                                                </li>
                                                <li style="display:none;" class="for-content-slider">
							<label for="sl_pausetime"><?php echo __( 'Pause time', 'portfolio-gallery' );?></label>
                                                        <input type="number" name="sl_pausetime" id="sl_pausetime" value="<?php echo $row->description; ?>" class="text_area" />
						</li>
						<li style="display:none;"  class="for-content-slider">
							<label for="sl_changespeed"><?php echo __( 'Change speed', 'portfolio-gallery' );?></label>
							<input type="number" name="sl_changespeed" id="sl_changespeed" value="<?php echo $row->param; ?>" class="text_area" />
						<li style="display:none;margin-top:10px"  class="for-content-slider">
							<label for="pause_on_hover"><?php echo __( 'Autoslide ', 'portfolio-gallery' );?></label>
							<input type="checkbox" name="pause_on_hover"  value="<?php echo $row->pause_on_hover;?>" id="pause_on_hover"  <?php if($row->pause_on_hover  == 'on'){ echo 'checked="checked"'; } ?> />
						</li>
					</ul>
                                            
						
	
</div>
<?php
}
///////////////////////////////////shortcode update/////////////////////////////////////////////

add_action('init', 'hugesl_portfolio_do_output_buffer');
function hugesl_portfolio_do_output_buffer() {
        ob_start();
}
add_action('init', 'portfolio_lang_load');

function portfolio_lang_load()
{
    load_plugin_textdomain('sp_portfolio', false, basename(dirname(__FILE__)) . '/Languages');
}

function huge_it_portfolio_images_list_shotrcode($atts)
{
    extract(shortcode_atts(array(
        'id' => 'no huge_it portfolio',
    
    ), $atts));

    return huge_it_portfolio_images_list($atts['id']);

}

/////////////// Filter portfolio gallery

function portfolio_after_search_results($query)
{
    global $wpdb;
    if (isset($_REQUEST['s']) && $_REQUEST['s']) {
        $serch_word = htmlspecialchars(($_REQUEST['s']));
        $query = str_replace($wpdb->prefix . "posts.post_content", gen_string_portfolio_search($serch_word, $wpdb->prefix . 'posts.post_content') . " " . $wpdb->prefix . "posts.post_content", $query);
    }
    return $query;
}

add_filter('posts_request', 'portfolio_after_search_results');


function gen_string_portfolio_search($serch_word, $wordpress_query_post)
{
    $string_search = '';

    global $wpdb;
    if ($serch_word) {
        $rows_portfolio = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "huge_itportfolio_portfolios WHERE (description LIKE %s) OR (name LIKE %s)", '%' . $serch_word . '%', "%" . $serch_word . "%"));

        $count_cat_rows = count($rows_portfolio);

        for ($i = 0; $i < $count_cat_rows; $i++) {
            $string_search .= $wordpress_query_post . ' LIKE \'%[huge_it_portfolio id="' . $rows_portfolio[$i]->id . '" details="1" %\' OR ' . $wordpress_query_post . ' LIKE \'%[huge_it_portfolio id="' . $rows_portfolio[$i]->id . '" details="1"%\' OR ';
        }
		
        $rows_portfolio = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "huge_itportfolio_portfolios WHERE (name LIKE %s)","'%" . $serch_word . "%'"));
        $count_cat_rows = count($rows_portfolio);
        for ($i = 0; $i < $count_cat_rows; $i++) {
            $string_search .= $wordpress_query_post . ' LIKE \'%[huge_it_portfolio id="' . $rows_portfolio[$i]->id . '" details="0"%\' OR ' . $wordpress_query_post . ' LIKE \'%[huge_it_portfolio id="' . $rows_portfolio[$i]->id . '" details="0"%\' OR ';
        }

        $rows_single = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "huge_itportfolio_images WHERE name LIKE %s","'%" . $serch_word . "%'"));

        $count_sing_rows = count($rows_single);
        if ($count_sing_rows) {
            for ($i = 0; $i < $count_sing_rows; $i++) {
                $string_search .= $wordpress_query_post . ' LIKE \'%[huge_it_portfolio_Product id="' . $rows_single[$i]->id . '"]%\' OR ';
            }

        }
    }
    return $string_search;
}

///////////////////// end filter

add_shortcode('huge_it_portfolio', 'huge_it_portfolio_images_list_shotrcode');

function   huge_it_portfolio_images_list($id)
{
    require_once("Front_end/portfolio_front_end_view.php");
    require_once("Front_end/portfolio_front_end_func.php");
    if (isset($_GET['product_id'])) {
        if (isset($_GET['view'])) {
            if ($_GET['view'] == 'huge_itportfolio') {
                return showPublishedportfolios_1($id);
            } else {
                return front_end_single_product($_GET['product_id']);
            }
        } else {
            return front_end_single_product($_GET['product_id']);
        }
    } else {
        return showPublishedportfolios_1($id);
    }
}

add_filter('admin_head', 'huge_it_portfolio_ShowTinyMCE');
function huge_it_portfolio_ShowTinyMCE()
{
    // conditions here
    wp_enqueue_script('common');
    wp_enqueue_script('jquery-color');
    wp_print_scripts('editor');
    if (function_exists('add_thickbox')) add_thickbox();
    wp_print_scripts('media-upload');
    if (version_compare(get_bloginfo('version'), 3.3) < 0) {
        if (function_exists('wp_tiny_mce')) wp_tiny_mce();
    }
    wp_admin_css();
    wp_enqueue_script('utils');
    do_action("admin_print_styles-post-php");
    do_action('admin_print_styles');
}

function portfolio_frontend_scripts_and_styles() {
    wp_register_script( 'jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js', array('jquery'),'1.0.0',true  ); 
    wp_enqueue_script( 'jquery' );
    
    wp_register_script( 'portfolio-all-js', plugins_url('/js/portfolio-all.js', __FILE__), array('jquery'),'1.0.0',true  ); 
    wp_enqueue_script( 'portfolio-all-js' );
    
    wp_register_style( 'portfolio-all-css', plugins_url('/style/portfolio-all.css', __FILE__) );   
    wp_enqueue_style( 'portfolio-all-css' );
    
    wp_register_script( 'jquery.colorbox-js', plugins_url('/js/jquery.colorbox.js', __FILE__), array('jquery'),'1.0.0',true  ); 
    wp_enqueue_script( 'jquery.colorbox-js' );
    
     wp_register_script( 'hugeitmicro-min-js', plugins_url('/js/jquery.hugeitmicro.min.js', __FILE__), array('jquery'),'1.0.0',true  ); 
     wp_enqueue_script( 'hugeitmicro-min-js' );
    
    
    wp_register_style( 'style2-os-css', plugins_url('/style/style2-os.css', __FILE__) );   
    wp_enqueue_style( 'style2-os-css' );
    
    wp_register_style( 'lightbox-css', plugins_url('/style/lightbox.css', __FILE__) );   
    wp_enqueue_style( 'lightbox-css' );
}
add_action('wp_enqueue_scripts', 'portfolio_frontend_scripts_and_styles');

add_action('admin_menu', 'huge_it_portfolio_options_panel');
function huge_it_portfolio_options_panel()
{
    $page_cat = add_menu_page('Theme page title', 'Huge IT Portfolio', 'delete_pages', 'portfolios_huge_it_portfolio', 'portfolios_huge_it_portfolio', plugins_url('images/huge_it_portfolioLogoHover -for_menu.png', __FILE__));
    add_submenu_page('portfolios_huge_it_portfolio', 'Portfolios', 'Portfolios', 'delete_pages', 'portfolios_huge_it_portfolio', 'portfolios_huge_it_portfolio');
    $page_option = add_submenu_page('portfolios_huge_it_portfolio', 'General Options', 'General Options', 'manage_options', 'Options_portfolio_styles', 'Options_portfolio_styles');
	$lightbox_options = add_submenu_page('portfolios_huge_it_portfolio', 'Lightbox Options', 'Lightbox Options', 'manage_options', 'Options_portfolio_lightbox_styles', 'Options_portfolio_lightbox_styles');
	
	add_submenu_page( 'portfolios_huge_it_portfolio', 'Licensing', 'Licensing', 'manage_options', 'huge_it_portfolio_Licensing', 'huge_it_portfolio_Licensing');

	add_submenu_page('portfolios_huge_it_portfolio', 'Featured Plugins', 'Featured Plugins', 'manage_options', 'huge_it__portfolio_featured_plugins', 'huge_it__portfolio_featured_plugins');

	add_action('admin_print_styles-' . $page_cat, 'huge_it_portfolio_admin_script');
    add_action('admin_print_styles-' . $page_option, 'huge_it_portfolio_option_admin_script');
	add_action('admin_print_styles-' . $lightbox_options, 'huge_it_portfolio_option_admin_script');
}

function huge_it__portfolio_featured_plugins()
{
	include_once("admin/huge_it_featured_plugins.php");
}

function huge_it_portfolio_Licensing(){

	?>
    <div style="width:95%">
    <p>
	This plugin is the non-commercial version of the Huge IT Portfolio / Gallery. If you want to customize to the styles and colors of your website,than you need to buy a license.
Purchasing a license will add possibility to customize the general options and lightbox of the Huge IT Portfolio / Gallery. 

 </p>
<br /><br />
<a href="http://huge-it.com/portfolio-gallery/" class="button-primary" target="_blank">Purchase a License</a>
<br /><br /><br />
<p>After the purchasing the commercial version follow this steps:</p>
<ol>
	<li>Deactivate Huge IT Portfolio / Gallery Plugin</li>
	<li>Delete Huge IT Portfolio / Gallery Plugin</li>
	<li>Install the downloaded commercial version of the plugin</li>
</ol>
</div>
<?php
	}
	
function huge_it_portfolio_admin_script()
{
	wp_enqueue_media();
	wp_enqueue_style("jquery_ui", "http://code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css", FALSE);
	wp_enqueue_style("admin_css", plugins_url("style/admin.style.css", __FILE__), FALSE);
	wp_enqueue_script("admin_js", plugins_url("js/admin.js", __FILE__), FALSE);
}

function huge_it_portfolio_option_admin_script()
{
	wp_enqueue_script("jquery_old", "http://ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js", FALSE);
	wp_enqueue_script("simple_slider_js",  plugins_url("js/simple-slider.js", __FILE__), FALSE);
	wp_enqueue_style("simple_slider_css", plugins_url("style/simple-slider.css", __FILE__), FALSE);
	wp_enqueue_style("admin_css", plugins_url("style/admin.style.css", __FILE__), FALSE);
	wp_enqueue_script("admin_js", plugins_url("js/admin.js", __FILE__), FALSE);
	wp_enqueue_script('param_block2', plugins_url("elements/jscolor/jscolor.js", __FILE__));
}

function portfolios_huge_it_portfolio()
{
    require_once("admin/portfolios_func.php");
    require_once("admin/portfolios_view.php");
    if (!function_exists('print_html_nav'))
        require_once("portfolio_function/html_portfolio_func.php");

    if (isset($_GET["task"]))
        $task = $_GET["task"]; 
    else
        $task = '';
    if (isset($_GET["id"]))
        $id = $_GET["id"];
    else
        $id = 0;
    global $wpdb;
    switch ($task) {

        case 'add_cat':
            add_portfolio();
            break;
		/***<add>***/
			case 'portfolio_video':
            if ($id)
                portfolio_video($id);
            else {
                $id = $wpdb->get_var("SELECT MAX( id ) FROM " . $wpdb->prefix . "huge_itportfolio_portfolios");
                portfolio_video($id);
            }
            break;
			 case 'portfolio_video_edit':
			             if ($id)
                portfolio_video_edit($id);
            else {
                $id = $wpdb->get_var("SELECT MAX( id ) FROM " . $wpdb->prefix . "huge_itportfolio_portfolios");
                portfolio_video_edit($id);
            }
            break;
		/***<add>***/
        case 'edit_cat':
            if ($id)
                editportfolio($id);
            else {
                $id = $wpdb->get_var("SELECT MAX( id ) FROM " . $wpdb->prefix . "huge_itportfolio_portfolios");
                editportfolio($id);
            }
            break;

        case 'save':
            if ($id)
                apply_cat($id);
        case 'apply':
            if ($id) {
                apply_cat($id);
                editportfolio($id);
            } 
            break;
        case 'remove_cat':
            removeportfolio($id);
            showportfolio();
            break;
        default:
            showportfolio();
            break;
    }

}

function Options_portfolio_styles()
{
    require_once("admin/portfolio_Options_func.php");
    require_once("admin/portfolio_Options_view.php");
    if (isset($_GET['task']))
        if ($_GET['task'] == 'save')
            save_styles_options();
    showStyles();
}
function Options_portfolio_lightbox_styles()
{
    require_once("admin/portfolio_lightbox_func.php");
    require_once("admin/portfolio_lightbox_view.php");
    if (isset($_GET['task']))
        if ($_GET['task'] == 'save')
            save_styles_options();
    showStyles();
}


class Huge_it_portfolio_Widget extends WP_Widget {
	public function __construct() {
		parent::__construct(
	 		'Huge_it_portfolio_Widget', 
			'Huge IT Portfolio', 
			array( 'description' => __( 'Huge IT Portfolio', 'huge_it_portfolio' ), ) 
		);
	}

	public function widget( $args, $instance ) {
		extract($args);

		if (isset($instance['portfolio_id'])) {
			$portfolio_id = $instance['portfolio_id'];

			$title = apply_filters( 'widget_title', $instance['title'] );

			echo $before_widget;
			if ( ! empty( $title ) )
				echo $before_title . $title . $after_title;

			echo do_shortcode("[huge_it_portfolio id={$portfolio_id}]");
			echo $after_widget;
		}
	}


	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['portfolio_id'] = strip_tags( $new_instance['portfolio_id'] );
		$instance['title'] = strip_tags( $new_instance['title'] );

		return $instance;
	}


	public function form( $instance ) {
		$selected_portfolio = 0;
		$title = "";
		$portfolios = false;

		if (isset($instance['portfolio_id'])) {
			$selected_portfolio = $instance['portfolio_id'];
		}

		if (isset($instance['title'])) {
			$title = $instance['title'];
		}

		?>
		<p>
			
				<p>
					<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
					<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
				</p>
				<label for="<?php echo $this->get_field_id('portfolio_id'); ?>"><?php _e('Select portfolio:', 'huge_it_portfolio'); ?></label> 
				<select id="<?php echo $this->get_field_id('portfolio_id'); ?>" name="<?php echo $this->get_field_name('portfolio_id'); ?>">
				
				<?php
				 global $wpdb;
				$query="SELECT * FROM ".$wpdb->prefix."huge_itportfolio_portfolios ";
				$rowwidget=$wpdb->get_results($query);
				foreach($rowwidget as $rowwidgetecho){
				?>
					<option <?php if($rowwidgetecho->id == $instance['portfolio_id']){ echo 'selected'; } ?> value="<?php echo $rowwidgetecho->id; ?>"><?php echo $rowwidgetecho->name; ?></option>

					<?php } ?>
				</select>

		</p>
		<?php 
	}
}

add_action('widgets_init', 'register_Huge_it_portfolio_Widget');  

function register_Huge_it_portfolio_Widget() {  
    register_widget('Huge_it_portfolio_Widget'); 
}



//////////////////////////////////////////////////////                                             ///////////////////////////////////////////////////////
//////////////////////////////////////////////////////               Activate portfolio gallery                    ///////////////////////////////////////////////////////
//////////////////////////////////////////////////////                                             ///////////////////////////////////////////////////////

function huge_it_portfolio_activate()
{
    global $wpdb;

/// creat database tables

    $sql_huge_itportfolio_images = "
CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "huge_itportfolio_images` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `portfolio_id` varchar(200) DEFAULT NULL,
  `description` text,
  `image_url` text,
  `sl_url` varchar(128) DEFAULT NULL,
  `sl_type` text NOT NULL,
  `link_target` text NOT NULL,
  `ordering` int(11) NOT NULL,
  `published` tinyint(4) unsigned DEFAULT NULL,
  `published_in_sl_width` tinyint(4) unsigned DEFAULT NULL,
  `category`  varchar(200) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5";

    $sql_huge_itportfolio_portfolios = "
CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "huge_itportfolio_portfolios` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `sl_height` int(11) unsigned DEFAULT NULL,
  `sl_width` int(11) unsigned DEFAULT NULL,
  `pause_on_hover` text,
  `portfolio_list_effects_s` text,
  `description` text,
  `param` text,
  `sl_position` text NOT NULL,
  `ordering` int(11) NOT NULL,
  `published` text,
  `categories` text NOT NULL,
  `ht_show_sorting` text NOT NULL,
  `ht_show_filtering` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ";





    $table_name = $wpdb->prefix . "huge_itportfolio_images";
    $sql_2 = "
INSERT INTO 

`" . $table_name . "` (`id`, `name`, `portfolio_id`, `description`, `image_url`, `sl_url`, `sl_type`, `link_target`, `ordering`, `published`, `published_in_sl_width`) VALUES
(1, 'Cutthroat & Cavalier', '1', '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. </p><p>Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>', '".plugins_url("Front_images/projects/1.jpg", __FILE__).";".plugins_url("Front_images/projects/1.1.jpg", __FILE__).";".plugins_url("Front_images/projects/1.2.jpg", __FILE__).";', 'http://huge-it.com/fields/order-website-maintenance/', 'image', 'on', 0, 1, NULL),
(2, 'Nespresso', '1', '<h6>Lorem Ipsum </h6><p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. </p><p>Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p><ul><li>lorem ipsum</li><li>dolor sit amet</li><li>lorem ipsum</li><li>dolor sit amet</li></ul>', '"."https://vimeo.com/76602135".";".plugins_url("Front_images/projects/9.1.jpg", __FILE__).";".plugins_url("Front_images/projects/9.2.jpg", __FILE__).";', 'http://huge-it.com/fields/order-website-maintenance/', 'video', 'on', 1, 1, NULL),
(3, 'Nexus', '1', '<h6>Lorem Ipsum </h6><p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrudexercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. </p><p>Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p><ul><li>lorem ipsum</li><li>dolor sit amet</li><li>lorem ipsum</li><li>dolor sit amet</li></ul>', '".plugins_url("Front_images/projects/3.jpg", __FILE__).";".plugins_url("Front_images/projects/3.1.jpg", __FILE__).";".plugins_url("Front_images/projects/3.2.jpg", __FILE__).":"."https://www.youtube.com/watch?v=YMQdfGFK5XQ".";', 'http://huge-it.com/fields/order-website-maintenance/', 'image', 'on', 2, 1, NULL),
(4, 'De7igner', '1', '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. </p><h7>Dolor sit amet</h7><p>Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>', '".plugins_url("Front_images/projects/4.jpg", __FILE__).";".plugins_url("Front_images/projects/4.1.jpg", __FILE__).";".plugins_url("Front_images/projects/4.2.jpg", __FILE__).";', 'http://huge-it.com/fields/order-website-maintenance/', 'image', 'on', 3, 1, NULL),
(5, 'Autumn / Winter Collection', '1', '<h6>Lorem Ipsum</h6><p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>', '".plugins_url("Front_images/projects/2.jpg", __FILE__).";', 'http://huge-it.com/fields/order-website-maintenance/', 'image', 'on', 4, 1, NULL),
(6, 'Retro Headphones', '1', '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. </p><p>Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>', '".plugins_url("Front_images/projects/6.jpg", __FILE__).";"."https://vimeo.com/80514062".";".plugins_url("Front_images/projects/6.1.jpg", __FILE__).";".plugins_url("Front_images/projects/6.2.jpg", __FILE__).";', 'http://huge-it.com/fields/order-website-maintenance/', 'image', 'on', 5, 1, NULL),
(7, 'Take Fight', '1', '<h6>Lorem Ipsum</h6><p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. </p><p>Excepteur sint occaecat cupidatat non proident , sunt in culpa qui officia deserunt mollit anim id est laborum.</p>', '".plugins_url("Front_images/projects/7.jpg", __FILE__).";".plugins_url("Front_images/projects/7.2.jpg", __FILE__).";"."https://www.youtube.com/watch?v=SP3Dgr9S4pM".";".plugins_url("Front_images/projects/7.3.jpg", __FILE__).";', 'http://huge-it.com/fields/order-website-maintenance/', 'image', 'on', 6, 1, NULL),
(8, 'The Optic', '1', '<h6>Lorem Ipsum </h6><p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. </p><p>Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p><ul><li>lorem ipsum</li><li>dolor sit amet</li><li>lorem ipsum</li><li>dolor sit amet</li></ul>', '".plugins_url("Front_images/projects/8.jpg", __FILE__).";".plugins_url("Front_images/projects/8.1.jpg", __FILE__).";".plugins_url("Front_images/projects/8.3.jpg", __FILE__).";', 'http://huge-it.com/fields/order-website-maintenance/', 'image', 'on', 7, 1, NULL),
(9, 'Cone Music', '1', '<ul><li>lorem ipsumdolor sit amet</li><li>lorem ipsum dolor sit amet</li></ul><p>Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>', '".plugins_url("Front_images/projects/5.jpg", __FILE__).";".plugins_url("Front_images/projects/5.1.jpg", __FILE__).";".plugins_url("Front_images/projects/5.2.jpg", __FILE__).";', 'http://huge-it.com/fields/order-website-maintenance/', 'image', 'on', 8, 1, NULL)";



    $table_name = $wpdb->prefix . "huge_itportfolio_portfolios";


    $sql_3 = "

INSERT INTO `$table_name` (`id`, `name`, `sl_height`, `sl_width`, `pause_on_hover`, `portfolio_list_effects_s`, `description`, `param`, `sl_position`, `ordering`, `published`) VALUES
(1, 'My First Portfolio', 375, 600, 'on', '2', '4000', '1000', 'center', 1, '300')";


   // $wpdb->query($sql_huge_itportfolio_params);
    $wpdb->query($sql_huge_itportfolio_images);
    $wpdb->query($sql_huge_itportfolio_portfolios);



    if (!$wpdb->get_var("select count(*) from " . $wpdb->prefix . "huge_itportfolio_images")) {
      $wpdb->query($sql_2);
    }
    if (!$wpdb->get_var("select count(*) from " . $wpdb->prefix . "huge_itportfolio_portfolios")) {
      $wpdb->query($sql_3);
    }

			///////////////////////////update////////////////////////////////////
  


        $imagesAllFieldsInArray = $wpdb->get_results("DESCRIBE " . $wpdb->prefix . "huge_itportfolio_images", ARRAY_A);
        $forUpdate = 0;
        foreach ($imagesAllFieldsInArray as $portfoliosField) {
        if ($portfoliosField['Field'] == 'category') {
                                    // "ka category field.<br>";
            $forUpdate = 1;
            $catValues = $wpdb->get_results( "SELECT category FROM ".$wpdb->prefix."huge_itportfolio_images" );
            $needToUpdate=0;
            foreach($catValues as $catValue){
                if($catValue->category !== '') {
                    $needToUpdate=1;
                    //echo "category field - y datark chi.<br>";
                }
            }
            if($needToUpdate == 0){
                $wpdb->query("UPDATE ".$wpdb->prefix."huge_itportfolio_images SET category = 'My First Category,My Third Category,' WHERE id='1'");
                $wpdb->query("UPDATE ".$wpdb->prefix."huge_itportfolio_images SET category = 'My Second Category,' WHERE id='2'");
                $wpdb->query("UPDATE ".$wpdb->prefix."huge_itportfolio_images SET category = 'My Third Category,' WHERE id='3'");
                $wpdb->query("UPDATE ".$wpdb->prefix."huge_itportfolio_images SET category = 'My First Category,My Second Category,' WHERE id='4'");
                $wpdb->query("UPDATE ".$wpdb->prefix."huge_itportfolio_images SET category = 'My Second Category,My Third Category,' WHERE id='5'");
                $wpdb->query("UPDATE ".$wpdb->prefix."huge_itportfolio_images SET category = 'My Third Category,' WHERE id='6'");
                $wpdb->query("UPDATE ".$wpdb->prefix."huge_itportfolio_images SET category = 'My Second Category,' WHERE id='7'");
                $wpdb->query("UPDATE ".$wpdb->prefix."huge_itportfolio_images SET category = 'My First Category,' WHERE id='8'");
            }

            break;
        }
    }
    if ($forUpdate == '0') {
            $wpdb->query("ALTER TABLE ".$wpdb->prefix."huge_itportfolio_images ADD category text");
            $wpdb->query("UPDATE ".$wpdb->prefix."huge_itportfolio_images SET category = 'My First Category,My Third Category,' WHERE id='1'");
            $wpdb->query("UPDATE ".$wpdb->prefix."huge_itportfolio_images SET category = 'My Second Category,' WHERE id='2'");
            $wpdb->query("UPDATE ".$wpdb->prefix."huge_itportfolio_images SET category = 'My Third Category,' WHERE id='3'");
            $wpdb->query("UPDATE ".$wpdb->prefix."huge_itportfolio_images SET category = 'My First Category,My Second Category,' WHERE id='4'");
            $wpdb->query("UPDATE ".$wpdb->prefix."huge_itportfolio_images SET category = 'My Second Category,My Third Category,' WHERE id='5'");
            $wpdb->query("UPDATE ".$wpdb->prefix."huge_itportfolio_images SET category = 'My Third Category,' WHERE id='6'");
            $wpdb->query("UPDATE ".$wpdb->prefix."huge_itportfolio_images SET category = 'My Second Category,' WHERE id='7'");
            $wpdb->query("UPDATE ".$wpdb->prefix."huge_itportfolio_images SET category = 'My First Category,' WHERE id='8'");
        }
        
        $productPortfolio = $wpdb->get_results("DESCRIBE " . $wpdb->prefix . "huge_itportfolio_portfolios", ARRAY_A);
        $isUpdate = 0;
	foreach ($productPortfolio as $prodPortfolio) {
        if ($prodPortfolio['Field'] == 'categories' && $prodPortfolio['Type'] == 'text') {
            $isUpdate = 1;
            
                $allCats = $wpdb->get_results( "SELECT categories FROM ".$wpdb->prefix."huge_itportfolio_portfolios" );
                $needToUpdateAllCats=0;
                foreach($allCats as $AllCatsVal){
                    if($AllCatsVal->categories !== '') {
                        $needToUpdateAllCats=1;
                    }
                }
                if($needToUpdateAllCats == 0){
                    $wpdb->query("UPDATE ".$wpdb->prefix."huge_itportfolio_portfolios SET categories = 'My First Category,My Second Category,My Third Category,' ");
                    $wpdb->query("UPDATE ".$wpdb->prefix."huge_itportfolio_portfolios SET ht_show_sorting = 'off' ");
                    $wpdb->query("UPDATE ".$wpdb->prefix."huge_itportfolio_portfolios SET ht_show_filtering = 'off' ");
                }
            
            break;
        }
    }
	if ($isUpdate == '0') {
            $wpdb->query("ALTER TABLE ".$wpdb->prefix."huge_itportfolio_portfolios ADD categories text");
            $wpdb->query("UPDATE ".$wpdb->prefix."huge_itportfolio_portfolios SET categories = 'My First Category,My Second Category,My Third Category,'");
            
            $wpdb->query("ALTER TABLE ".$wpdb->prefix."huge_itportfolio_portfolios ADD ht_show_sorting text");
            $wpdb->query("UPDATE ".$wpdb->prefix."huge_itportfolio_portfolios SET ht_show_sorting = 'off'");
            
            $wpdb->query("ALTER TABLE ".$wpdb->prefix."huge_itportfolio_portfolios ADD ht_show_filtering text");
            $wpdb->query("UPDATE ".$wpdb->prefix."huge_itportfolio_portfolios SET ht_show_filtering = 'off'");
	}
        
}


register_activation_hook(__FILE__, 'huge_it_portfolio_activate');