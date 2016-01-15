<?php 
/**
 * save options
 */
if($_POST && isset($_POST['pjc_slideshow_options']))
{
	update_option('pjc_slideshow_options', $_POST['pjc_slideshow_options']);
	
	$location = admin_url("edit.php?post_type=pjc_slideshow&page=frs-setting-page&tab=$current&settings-updated=true");
	echo "<meta http-equiv='refresh' content='0;url=$location' />";
	echo "<h2>Loading...</h2>";
	exit();
}
?>

<form method="post" action="" id="frs-option-form">
<?php settings_fields('pjc_options'); ?>

<?php
  	foreach ( $terms as $term ) {
		if(isset($options[$term->slug]) && $term->slug!=$current) {
			foreach ($options[$term->slug] as $key => $value) {
				echo "<input type='hidden' value='$value' name='pjc_slideshow_options[$term->slug][$key]'>";
			}
		}
  	}
?>

<div class="metabox-holder columns-2" style="margin-right: 300px;">
<div class="postbox-container" style="width: 100%;min-width: 463px;float: left; ">
<div class="meta-box-sortables ui-sortable">
<div id="adminform" class="postbox">
<h3 class="hndle" data-step="6" data-intro="Configure and setting up your slideshow options"><span><?php echo "$current_name - Slideshow Options"?></span></h3>
<div class="inside" style="z-index:1;">
<!-- Extra style for options -->
<style>
	.form-table td {
		vertical-align: middle;
	}

	.form-table th {
		width: 150px
		font-weight: 400;
	}

	.form-table input,.form-table select {

		width: 150px;
		margin-right: 10px;
	}

	.frs-slideshow-container {
		margin-left: auto;
		margin-right: auto;
	}

	<?php
		if($options[$current]['navigation']=='false')
			echo "
				.tonjoo_nav_option{
					display:none;
				}
			";
	?>

	label.error{
	    margin-left: 5px;
	    color: red;
	}

	.form-table tr th {
	    text-align: left;
	    font-weight: normal;
	}

	.meta-subtitle {
	    margin: 0px -22px !important;
		border-top: 1px solid #EEE;
		border-bottom: 1px solid #EEE;
		background-color: #F6F6F6;
		padding: 10px;
		font-size: 14px;		
	}

	@media (max-width: 767px) {
		    .meta-subtitle {
		      margin-left: -12px !important;
		    }
	}
</style>

<script type="text/javascript">
jQuery(document).ready(function($){
	$("#tonjoo-frs-textbox-bg select").change(function(){
		value = $(this).attr('value')

		$("#picture_prev").css('background-image',"url('<?php echo plugins_url( FRS_DIR_NAME.'/backgrounds/' , dirname(__FILE__) ) ?>" + value + ".png')");
	})

	$("select[name='pjc_slideshow_options[<?php echo $current ?>][skin]']").css({"width":'250px'});

	$("select[name='pjc_slideshow_options[<?php echo $current ?>][skin]']").select2();
})
</script>

<div id="preview_slide" style="margin:25px 20px;">
<?php 
	$attr['slide_type'] = $current;
	echo pjc_gallery_print($attr) 
?>
</div>


<table class="form-table">
	<tr><td colspan=2><h3 class="meta-subtitle">Skin & Animation</h3></td></tr>

	<?php

	$dir =  dirname(__FILE__)."/skins";

	
	if(! isset($options[$current]['skin']))
			$options[$current]['skin']="frs-skin-default";


	$skins = scandir($dir);

	$slideshow_skin =  array();

	foreach ($skins as $key => $value) {

		$extension = pathinfo($value, PATHINFO_EXTENSION); 
		$filename = pathinfo($value, PATHINFO_FILENAME); 
		$extension = strtolower($extension);
		$the_value = strtolower($filename);
		$filename_ucwords = str_replace('-', ' ', ucwords($filename));
		$filename_ucwords = ucwords($filename_ucwords);
		$filename_ucwords = str_replace('Frs Skin ', '', ucwords($filename_ucwords));

		if($extension=='css'){
			$data = array(
					"label"=>"$filename_ucwords",
					"value"=>"$the_value"								

				);

			array_push($slideshow_skin,$data);

		}
	}

	if(function_exists('is_frs_premium_exist')) 
	{
		
		$dir =  ABSPATH . 'wp-content/plugins/'.FRS_PREMIUM_DIR_NAME.'/skins';

		$skins = scandir($dir);

		foreach ($skins as $key => $value) {

			$extension = pathinfo($value, PATHINFO_EXTENSION); 
			$filename = pathinfo($value, PATHINFO_FILENAME); 
			$extension = strtolower($extension);
			$the_value = strtolower($filename);
			$filename_ucwords = str_replace('-', ' ', $filename);
			$filename_ucwords = ucwords($filename_ucwords);
			$filename_ucwords = str_replace('Frs Skin ', '', ucwords($filename_ucwords));


			if($extension=='css'){
				$data = array(
						"label"=>"$filename_ucwords (Premium)",
						"value"=>"$the_value-PREMIUMtrue"

					);

				array_push($slideshow_skin,$data);

			}
		}
	}

	$option_select = array(
					"name"=>"pjc_slideshow_options[{$current}][skin]",
					"description" => "&nbsp; Select skin",
					"label" => "Skin",
					"value" => $options[$current]['skin'],
					"select_array" => $slideshow_skin,
					"id"=>"tonjoo-frs-skin"
				);

	
	frs_print_select_option($option_select);
?>

	<tr valign="top">
		<th scope="row">Animation</th>
		<td>
			<select name="pjc_slideshow_options<?php echo "[$current][animation]"?>">
				<?php
				
					$navigation = array(									
						'0' => array(
							'value' =>	'horizontal-slide',
							'label' =>  'Horizontal Slide' 
						),
						'1' => array(
							'value' =>	'vertical-slide',
							'label' =>  'Vertical Slide'
						),
						'2' => array(
							'value' =>	'fade',
							'label' =>  'Fade'
						)
					);
					
				
					$selected = $options[$current]["animation"];
					$r = '';

					foreach ( $navigation as $option ) {
						$label = $option['label'];
						if ( $selected == $option['value'] )
							$r .= "<option selected='selected' value='" . esc_attr( $option['value'] ) . "'>$label</option>";
						else
							$r .= "<option value='" . esc_attr( $option['value'] ) . "'>$label</option>";
					}
					echo $r;
				?>
			</select>
			<label class="description" >The animation type in slide transition</label>
		</td>
	</tr>

	<tr><td colspan=2><h3 class="meta-subtitle">Base Dimension and Proportion</h3></td></tr>

	<tr valign="top">
		<th scope="row">Base Width</th>
		<td>
			<input required class="regular-text" type="number" name="pjc_slideshow_options<?php echo "[$current][width]"?>" value="<?php esc_attr_e($options[$current]["width"]); ?>" />
			<label class="description" >Slider width</label>
		</td>
	</tr>
			
	<tr valign="top">
		<th scope="row">Base Height</th>
		<td>
			<input class="regular-text" type="number" name="pjc_slideshow_options<?php echo "[$current][height]"?>" value="<?php esc_attr_e($options[$current]["height"]); ?>" />
			<label class="description" >Slider height</label>
		</td>
	</tr>

	<tr><td colspan=2><h3 class="meta-subtitle">Size And Scaling</h3></td></tr>

	<tr valign="top">
		<th scope="row">Full Width</th>
		<td>
			<select name="pjc_slideshow_options<?php echo "[$current][full_width]"?>">
				<?php
				
					$full_width = array(									
						'0' => array(
							'value' =>	'false',
							'label' =>  'No' 
						),
						'1' => array(
							'value' =>	'true',
							'label' =>  'Yes'
						)
					);
					
				
					$selected = $options[$current]["full_width"];
					$r = '';

					foreach ( $full_width as $option ) {
						$label = $option['label'];
						if ( $selected == $option['value'] )
							$r .= "<option selected='selected' value='" . esc_attr( $option['value'] ) . "'>$label</option>";
						else
							$r .= "<option value='" . esc_attr( $option['value'] ) . "'>$label</option>";
					}
					echo $r;
				?>
			</select>
			<label class="description" >Fluid the slideshow width to container width</label>
		</td>
	</tr>

	<tr valign="top">
		<th scope="row">Min Height</th>
		<td>
			<input class="regular-text" type="number" name="pjc_slideshow_options<?php echo "[$current][min_height]"?>" value="<?php esc_attr_e($options[$current]["min_height"]); ?>" />
			<label class="description" >Minimum slider shrink height</label>
		</td>
	</tr>

	<tr valign="top">
		<th scope="row">Max Height</th>
		<td>
			<input class="regular-text" type="number" name="pjc_slideshow_options<?php echo "[$current][max_height]"?>" value="<?php esc_attr_e($options[$current]["max_height"]); ?>" />
			<label class="description" >Maximum slider height, set to 0 (zero) to make it unlimited.</label>
		</td>
	</tr>

	<tr><td colspan=2><h3 class="meta-subtitle">Text Box</h3></td></tr>

	 <?php 

	$slideshow_select = array(
						'0' => array(
							'value' =>	'true',
							'label' =>  'Yes'
						),
						'1' => array(
							'value' =>	'false',
							'label' =>  'No' 
						)
					);


	$option_select = array(
					"name"=>"pjc_slideshow_options[{$current}][show_textbox]",
					"description" => "Select yes if you to show the textbox",
					"label" => "Show Textbox",
					"value" => $options[$current]['show_textbox'],
					"select_array" => $slideshow_select,
					"id"=>"tonjoo-frs-is-show-textbox"
				);

	
	 frs_print_select_option($option_select);
	?>
	
	<tr valign="top">
		<th scope="row">Title Size</th>
		<td>
			<input required class="regular-text" type="number" name="pjc_slideshow_options<?php echo "[$current][textbox_h4_size]"?>" value="<?php esc_attr_e($options[$current]["textbox_h4_size"]); ?>" />
			<label class="description" >Textbox Heading</label>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row">Description Size</th>
		<td>
			<input required class="regular-text" type="number" name="pjc_slideshow_options<?php echo "[$current][textbox_p_size]"?>" value="<?php esc_attr_e($options[$current]["textbox_p_size"]); ?>" />
			<label class="description" >Textbox Text Size</label>
		</td>
	</tr>

	<tr><td colspan=2><h3 class="meta-subtitle">Slide Time</h3></td></tr>

	<tr valign="top">
		<th scope="row">Slide Time</th>
		<td>
			<input required class="regular-text" type="number" name="pjc_slideshow_options<?php echo "[$current][fade_time]"?>" value="<?php esc_attr_e($options[$current]["fade_time"]); ?>" />
			<label class="description" >The speed image cycle (in millisecond).0 for manual slideshow</label>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row">Slide Transition Time</th>
		<td>
			<input required class="regular-text" type="number" name="pjc_slideshow_options<?php echo "[$current][animation_time]"?>" value="<?php esc_attr_e($options[$current]["animation_time"]); ?>" />
			<label class="description" >The speed of the transisiton animation (in millisecond).</label>
		</td>
	</tr>

	<tr><td colspan=2><h3 class="meta-subtitle">Mouse Hover Behaviour</h3></td></tr>

	<tr valign="top">
		<th scope="row">Pause On Hover</th>
		<td>
			<select name="pjc_slideshow_options<?php echo "[$current][pause]"?>">
				<?php
				
					$navigation = array(
						'0' => array(
							'value' =>	'true',
							'label' =>  'Yes'
						),
						'1' => array(
							'value' =>	'false',
							'label' =>  'No' 
						)
					);
					
				
					$selected = $options[$current]["pause"];
					$r = '';

					foreach ( $navigation as $option ) {
						$label = $option['label'];
						if ( $selected == $option['value'] )
							$r .= "<option selected='selected' value='" . esc_attr( $option['value'] ) . "'>$label</option>";
						else
							$r .= "<option value='" . esc_attr( $option['value'] ) . "'>$label</option>";
					}
					echo $r;
				?>
			</select>
			<label class="description" >Select yes to pause animation on mouse hover</label>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row">Continue On Mouseout</th>
		<td>
			<select name="pjc_slideshow_options<?php echo "[$current][start_mouseout]"?>">
				<?php
				
					$navigation = array(
						'0' => array(
							'value' =>	'true',
							'label' =>  'Yes'
						),
						'1' => array(
							'value' =>	'false',
							'label' =>  'No' 
						)
					);
					
				
					$selected = $options[$current]["start_mouseout"];
					$r = '';

					foreach ( $navigation as $option ) {
						$label = $option['label'];
						if ( $selected == $option['value'] )
							$r .= "<option selected='selected' value='" . esc_attr( $option['value'] ) . "'>$label</option>";
						else
							$r .= "<option value='" . esc_attr( $option['value'] ) . "'>$label</option>";
					}
					echo $r;
				?>
			</select>
			<label class="description" >Select yes to continue animation ater the mouseout event. In effect when 'Pause on hover' is set yes</label>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row">Delayed Start After Mouseout</th>
		<td>
			<input required class="regular-text" type="number" name="pjc_slideshow_options<?php echo "[$current][start_mouseout_after]"?>" value="<?php esc_attr_e($options[$current]["start_mouseout_after"]); ?>" />
			<label class="description" >Animation will resume after mouseout event in the given time (in ms). In effect when 'Continue on mouseout' is set yes</label>
		</td>
	</tr>

	<tr><td colspan=2><h3 class="meta-subtitle">Timer</h3></td></tr>

	<tr valign="top">
		<th scope="row">Show Timer</th>
		<td>
			<select name="pjc_slideshow_options<?php echo "[$current][show_timer]"?>">
				<?php
				
					$navigation = array(
						'0' => array(
							'value' =>	'true',
							'label' =>  'Yes'
						),
						'1' => array(
							'value' =>	'false',
							'label' =>  'No' 
						)
					);
					
				
					$selected = $options[$current]["show_timer"];
					$r = '';

					foreach ( $navigation as $option ) {
						$label = $option['label'];
						if ( $selected == $option['value'] )
							$r .= "<option selected='selected' value='" . esc_attr( $option['value'] ) . "'>$label</option>";
						else
							$r .= "<option value='" . esc_attr( $option['value'] ) . "'>$label</option>";
					}
					echo $r;
				?>
			</select>
			<label class="description" >Display a small timer on the slideshow</label>
		</td>
	</tr>

	<tr><td colspan=2><h3 class="meta-subtitle">Arrow Navigation</h3></td></tr>

	<tr valign="top" id='tonjoo_show_navigation_arrow'>
		<th scope="row">Arrow Navigation</th>
		<td>
			<select name="pjc_slideshow_options<?php echo "[$current][navigation]"?>">
				<?php
				
					$navigation = array(
						'0' => array(
							'value' =>	'true',
							'label' =>  'Yes'
						),
						'1' => array(
							'value' =>	'false',
							'label' =>  'No' 
						)
					);
					
				
					$selected = $options[$current]["navigation"];
					$r = '';

					foreach ( $navigation as $option ) {
						$label = $option['label'];
						if ( $selected == $option['value'] )
							$r .= "<option selected='selected' value='" . esc_attr( $option['value'] ) . "'>$label</option>";
						else
							$r .= "<option value='" . esc_attr( $option['value'] ) . "'>$label</option>";
					}
					echo $r;
				?>
			</select>
			<label class="description" >If "no" is selected the navigation arrow will not visible</label>
		</td>
	</tr>

	<tr><td colspan=2><h3 class="meta-subtitle">Slide Pagination</h3></td></tr>

	<tr valign="top">
		<th scope="row">Slide Pagination</th>
		<td>
			<select name="pjc_slideshow_options<?php echo "[$current][bullet]"?>">
				<?php
				
					$navigation = array(
						'0' => array(
							'value' =>	'true',
							'label' =>  'Yes'
						),
						'1' => array(
							'value' =>	'false',
							'label' =>  'No' 
						)
					);
					
				
					$selected = $options[$current]["bullet"];
					$r = '';

					foreach ( $navigation as $option ) {
						$label = $option['label'];
						if ( $selected == $option['value'] ) // Make default first in list
							$r .= "<option selected='selected' value='" . esc_attr( $option['value'] ) . "'>$label</option>";
						else
							$r .= "<option value='" . esc_attr( $option['value'] ) . "'>$label</option>";
					}
					echo $r;
				?>
			</select>
			<label class="description" >*Some skins pagination can't be disabled</label>
		</td>
	</tr>

	<tr><td colspan=2><h3 class="meta-subtitle">Custom CSS</h3></td></tr>

	<tr valign="top">
		<th colspan=2>
			<script type="text/javascript">
				jQuery(document).ready(function($){
					/**
					 * Ace editor
					 */
					var editor = ace.edit("ace-editor");
				    editor.setTheme("ace/theme/monokai");
				    editor.getSession().setMode("ace/mode/css");
				    editor.getSession().on('change', function(e) {
				    	var code = editor.getSession().getValue();

				    	$("#ace_editor_value").val(code);
					});
				});
			</script>
			
			<style type="text/css">
				#ace-editor { 
			        width: 100%;
			        height:350px;
			        margin-left: auto;
					padding-right: 10px;
					font-size: 16px;
			    }

			    #ace_editor_value {
			    	display: none;
			    }
			</style>

			<?php 
				$default_custom_css = ".frs-slideshow-container#".$attr['slide_type']."pjc {
	margin-top: 25px;
	margin-bottom: 75px;
}";

				$custom_css = $options[$current]["custom_css"] == '' ? $default_custom_css : $options[$current]["custom_css"];
			?>

			<p style="margin-top:-25px;">
				<ol>
					<li>To localize your css affect, always use wraper <code>.frs-slideshow-container#<?php echo $attr['slide_type'] ?>pjc</code></li>
					<li>Some css attribute need to use <code>!important</code> value to affect</li>
				</ol>
			</p>
			<div id="ace-editor"><?php echo $custom_css ?></div>
			<textarea id="ace_editor_value" name="pjc_slideshow_options<?php echo "[$current][custom_css]"?>" ><?php echo $custom_css ?></textarea>
		</th>
	</tr>

</table>

<br>
<br>
<input type="submit" class="button button-primary button-frs" value="<?php _e('Save Options', 'pjc_slideshow_options'); ?>" />			
</div>			
</div>			
</div>			
</div>			


<div class="postbox-container" style="float: right;margin-right: -300px;width: 280px;">
<div class="metabox-holder" style="padding-top:0px;">	
<div class="meta-box-sortables ui-sortable">
	<div id="email-signup" class="postbox">
		<h3 class="hndle"><span>Save Options</span></h3>
		<div class="inside" style="padding-top:10px;">
			Save your changes to apply the options
			<br>
			<br>
			<input type="submit" class="button button-primary button-frs" value="<?php _e('Save Options', 'pjc_slideshow_options'); ?>" data-step="7" data-intro="Save your changes to apply the options" />
			
		</div>
	</div>

	<!-- ADS -->
	<?php
		$options = get_option('pjc_slideshow_license');
		$license = isset($options['license_status']) ? unserialize($options['license_status']) : false;	
		if(!$license || !$license['status'] || !function_exists('is_frs_premium_exist')):
	?>

		<div class="postbox">			
		<script type="text/javascript">
			/**
			 * Setiap dicopy-paste, yang find dan dirubah adalah
			 * - var pluginName
			 * - premium_exist
			 */

			jQuery(function($){					
				var pluginName = "frs";
				var url = 'https://tonjoostudio.com/jsonp/?promo=get&plugin=' + pluginName;
				var promoFirst = new Array();
				var promoSecond = new Array();

				<?php if(function_exists('is_frs_premium_exist')): ?>
				var url = 'https://tonjoostudio.com/jsonp/?promo=get&plugin=' + pluginName + '&premium=true';
				<?php endif ?>

				// strpos function
				function strpos(haystack, needle, offset) {
					var i = (haystack + '')
						.indexOf(needle, (offset || 0));
					return i === -1 ? false : i;
				}

				$.ajax({url: url, dataType:'jsonp'}).done(function(data){
					
					if(typeof data =='object')
					{
						var fristImg, fristUrl;

					    // looping jsonp object
						$.each(data, function(index, value){

							<?php if(! function_exists('is_frs_premium_exist')): ?>

							fristImg = pluginName + '-premium-img';
							fristUrl = pluginName + '-premium-url';

							// promoFirst
							if(index == fristImg)
						    {
						    	promoFirst['img'] = value;
						    }

						    if(index == fristUrl)
						    {
						    	promoFirst['url'] = value;
						    }

						    <?php else: ?>

						    if(! fristImg)
						    {
						    	// promoFirst
								if(strpos(index, "-img"))
							    {
							    	promoFirst['img'] = value;

							    	fristImg = index;
							    }

							    if(strpos(index, "-url"))
							    {
							    	promoFirst['url'] = value;

							    	fristUrl = index;
							    }
						    }

						    <?php endif; ?>

							// promoSecond
							if(strpos(index, "-img") && index != fristImg)
						    {
						    	promoSecond['img'] = value;
						    }

						    if(strpos(index, "-url") && index != fristUrl)
						    {
						    	promoSecond['url'] = value;
						    }
						});

						//promo_1
						$("#promo_1 img").attr("src",promoFirst['img']);
						$("#promo_1 a").attr("href",promoFirst['url']);

						//promo_2
						$("#promo_2 img").attr("src",promoSecond['img']);
						$("#promo_2 a").attr("href",promoSecond['url']);
					}
				});
			});
		</script>

		<!-- <h3 class="hndle"><span>This may interest you</span></h3> -->
		<div class="inside" style="margin: 23px 10px 6px 10px;">
			<div id="promo_1" style="text-align: center;padding-bottom:17px;">
				<a href="https://tonjoostudio.com" target="_blank">
					<img src="<?php echo plugins_url(FRS_DIR_NAME."/assets/loading-big.gif") ?>" width="100%" <?php if(! function_exists('is_frs_premium_exist')): ?> data-step="8" data-intro="If you like this slider, please consider the premium version to support us and get all the skins.<b>Fluid Responsive Slideshow</b> !" <?php endif ?>>
				</a>
			</div>
			<div id="promo_2" style="text-align: center;">
				<a href="https://tonjoostudio.com" target="_blank">
					<img src="<?php echo plugins_url(FRS_DIR_NAME."/assets/loading-big.gif") ?>" width="100%">
				</a>
			</div>
		</div>

	</div>

	<?php endif; ?>

</div>
</div>
</div>	

</div>

</form>	