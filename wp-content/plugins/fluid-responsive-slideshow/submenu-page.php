<?php

/**
 * Register custom options for the plugin
 */
add_action("admin_init","init_pjc_slideshow_options");
function init_pjc_slideshow_options(){
		register_setting( 'pjc_options', 'pjc_slideshow_options');
}

/**
 * Option page definiton
 */
function pjc_slideshow_submenu_page(){
	if ( isset ( $_GET['tab'] ) ) 
		pjc_slideshow_tab($_GET['tab']); 
	else 
		pjc_slideshow_tab('plugin');
	
}

/**
 * Tab definiton
 */
function pjc_slideshow_tab($current = 'plugin')
{	
	if(! isset( $_REQUEST['settings-updated'])) $_REQUEST['settings-updated'] = false;
		
	// BEGIN SPAGETHY HTML-PHP-CSS-JS CODE HERE :D
	?>

	<script type="text/javascript">
		jQuery(document).ready(function($) {
			$("#button-slide-type").click(function(){
				var selected_slide = $("#select-slide-type").val();

				self.location.href = selected_slide;
			});
		});
	</script>

	<style type="text/css">
		.form-table tr th {
		    text-align: left;
		    font-weight: normal;
		}
	</style>

	<div class="wrap">
		<?php 
			/**
			 * get term for current slideshow 
			 */
			$term_id = 0;
			$term_slug = "";
			$current_name = "";

			if(isset($_GET['tab']) && $_GET['tab'] != "")
			{
				$term = term_exists($_GET['tab'], 'slide_type');
				$term_id = $term['term_id'];
				$term_slug = $_GET['tab'];
			}
			else
			{
				/**
				 * get term if not defined current slideshow
				 */
				$args = array('number' => '1','hide_empty' => false);
				$terms = get_terms('slide_type', $args );

				foreach ($terms as $term) {
					$term_id = $term->term_id;
					$term_slug = $term->slug;
					$current_name = $term->name;
				}
			}

			// if name is not defined
			if($current_name == "")
			{
				$current_term = get_term_by('slug', $term_slug, 'slide_type', 'ARRAY_A');
				$current_name = $current_term['name'];
			}


			/**
			 * Check if no slyde type
			 */
			if(wp_count_terms('slide_type') <= 0)
			{
				?>

				<div class='no-slide'>
					<h2>There is no slideshow created, create a new one ? </h2><br />
					<input type='text' name='title' id='frs-first-slideshow-input' placeholder='Your New Slideshow Name' value=''><br />
					<a id="frs-first-add-slideshow" href='javascript:;' class='button button-primary'>Create A Slideshow</a>
				</div>

				<?php
				die();
			}

			echo "<h2>Fluid Responsive Slideshow</h2>";
 		?>
		<!-- <p>You can add, edit, delete and re order your slide on this page. Drag the images to change slide ordering</p> -->
		<p>
			The configuration page. You can add, edit, delete or re-order your slideshow. &nbsp;&nbsp;
			<a href="javascript:;" class="button button-orange button-frs" id="frs-how-to-use">How to use ?</a>
			<a href="http://wordpress.org/support/view/plugin-reviews/fluid-responsive-slideshow?rate=5#postform" target="_blank" style="margin-left:10px;">Enjoy the plugin?, rate us!</a>
		</p>

		<!-- Progress Notification -->
		<div id="frs_ajax_on_progress">On Progress..</div>

		<!-- BEGIN TAB SLIDE AND OPTIONS -->
		<h2 class="nav-tab-wrapper">
		<?php if(isset($_GET['tab']) && $_GET['tab'] != ""): ?>
			<a class="nav-tab <?php if(isset($_GET['tabtype']) && $_GET['tabtype'] == "slide") echo "nav-tab-active" ?>" href='<?php echo get_admin_url()."admin.php?page=frs-setting-page&tab=".$_GET['tab']."&tabtype=slide" ?>' data-step="1" data-intro="<b>Slideshow</b> tab contains many slides.<br><br>You can add new, sort, edit, and delete each slide here">Slideshow</a>
			<a class="nav-tab <?php if(! isset($_GET['tabtype']) || $_GET['tabtype'] == "option") echo "nav-tab-active" ?>" href='<?php echo get_admin_url()."admin.php?page=frs-setting-page&tab=".$_GET['tab']."&tabtype=option" ?>' data-step="2" data-intro="<b>Slideshow Options</b> tab contains options of a slideshow">Slideshow Options</a>
		<?php 
			else: 
		?>
			<a class="nav-tab <?php if(! isset($_GET['tabtype'])) echo "nav-tab-active" ?>" href='<?php echo get_admin_url()."admin.php?page=frs-setting-page&tab=".$term_slug."&tabtype=slide" ?>' data-step="1" data-intro="<b>Slides</b> tab contains many slides<br><br>You can add new, sort, edit, and delete each slide here">Slideshow</a>
			<a class="nav-tab" href='<?php echo get_admin_url()."admin.php?page=frs-setting-page&tab=".$term_slug."&tabtype=option" ?>' data-step="2" data-intro="<b>Slideshow Options</b> tab contains options of a slideshow">Slideshow Options</a>
		<?php endif; ?>
			
		<?php if(function_exists('is_frs_premium_exist')): ?>		
			<a class="nav-tab <?php if(isset($_GET['tabtype']) && $_GET['tabtype'] == "license") echo "nav-tab-active" ?>" href='<?php echo get_admin_url()."admin.php?page=frs-setting-page&tab=".$term_slug."&tabtype=license" ?>' >License</a>		
		<?php endif ?>

		</h2>
		
		
		
		<!-- SELECT SLIDESHOW -->
		<div class="manage-menus" id="frs-select-slideshow-menu">
			<label for="menu" class="selected-menu">Select Slideshow:</label>
			<select id="select-slide-type" data-step="3" data-intro="You can select and switch between your slideshow here.">
			<?php
				$tabs = array();
				
				require( plugin_dir_path( __FILE__ ) . 'default-options.php');
				
				$terms = get_terms("slide_type",array("hide_empty"=>0));
			 
				foreach ( $terms as $term )
				{
					//$tabs[$term->name]=$term->name;
					$tabs[$term->slug]=$term->name;
				}
				
				foreach( $tabs as $tab => $name )
				{
		        	$class = ( $tab == $current ) ? 'selected' : '';

		        	$tabtype = $_GET['tabtype'] == '' ? 'slide' : $_GET['tabtype'];
		        	
		        	echo "<option value='".get_admin_url()."admin.php?page=frs-setting-page&tab=$tab&tabtype=$tabtype' $class>$name</option>";
		    	}		
			?>
			</select>
			<span class="submit-btn"><a href="javascript:;" id="button-slide-type" class="button">Show</a></span>

			<span class="add-new-action">
				or <a frs-add-slide-type href="javascript:;" data-step="4" data-intro="Or create a new fresh slideshow.">create a new slideshow</a>				
			</span><!-- /add-new-menu-action -->

			<span class="show-shortcode">
				Shortcode: <input frs-input-shortcode value='[pjc_slideshow slide_type="<?php echo $term_slug ?>"]' readonly data-step="5" data-intro="Put this shortcode to your post to add the current slideshow into your post. <br><br>You can also add shorcode by select the <b>Add FR Slideshow</b> button in your post editor." />
			</span>

			<span class="delete-action">
				<a frs-delete-slide-type id='<?php echo $term_id ?>' href="javascript:;">Delete Slideshow</a>
			</span><!-- /add-new-menu-action -->
		</div>

		<div class='frs-notice-wrapper'>
			<div class="updated below-h2 frs-updated"><p><strong>Updated!</strong> Your changes has been saved</p></div>		
			<div class="updated below-h2 frs-updated-error"><p><strong>Error Updated!</strong> Please try again in a moment</p></div>		
		</div>
		
		<?php if ( false !== $_REQUEST['settings-updated'] ) : ?>
			<div class="updated fade"><p><strong><?php _e('Options saved', 'pjc_slideshow_options'); ?></strong></p></div>
		<?php endif; ?> 


		<?php


		if(! isset($_GET['tab']) && isset($_GET['tabtype']) && $_GET['tabtype'] == 'license')
		{
			/**
			 * load license
			 */
			require_once( plugin_dir_path( __FILE__ ) . 'submenu-page-license.php');
		}
		else if(! isset($_GET['tab']) && ! isset($_GET['tabtype']))
		{
			/**
			 * load slide page if not defined current slideshow
			 */
			require_once( plugin_dir_path( __FILE__ ) . 'submenu-page-slide.php');
		}
		elseif(isset($_GET['tabtype']) && $_GET['tabtype'] == "slide")
		{
			/**
			 * load slide page
			 */
			require_once( plugin_dir_path( __FILE__ ) . 'submenu-page-slide.php');
		}
		elseif(isset($_GET['tabtype']) && $_GET['tabtype'] == "license")
		{
			/**
			 * load slide page
			 */
			require_once( plugin_dir_path( __FILE__ ) . 'submenu-page-license.php');
		}
		else
		{
			/**
			 * load options page
			 */
			require_once( plugin_dir_path( __FILE__ ) . 'submenu-page-options.php');
		}

		?>
		
		
	</div>

	<?php
}