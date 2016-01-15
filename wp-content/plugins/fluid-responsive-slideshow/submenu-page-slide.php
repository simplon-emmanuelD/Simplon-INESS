<?php
/**
 * declared variable from submenu-page.php:
 *
 * $term_id
 * $term_slug
 */
?>

<style type="text/css">
	.hndle-desc {
		font-weight: normal;
		float: right;
		font-style: italic;
	}
</style>

<script type="text/javascript">	
	current_frs_slide_type = '<?php echo $term_id ?>'
</script>

<div class="metabox-holder columns-2" style="margin-right: 300px;">
<div class="postbox-container" style="width: 100%;min-width: 463px;float: left; ">
<div class="meta-box-sortables ui-sortable">
<div id="adminform" class="postbox">
<h3 class="hndle" data-step="7" data-intro="Your created slides will appears in this table. You can individualy edit or delete each slide. You can also sort the slides by drag and drop it.">
	<span><?php echo "$current_name - Slides"?><span class="hndle-desc">drag and drop the slides below to sort</span></span>
</h3>
<div class="inside" style="z-index:1;">

<?php 

global $post;

$args = array(
	'post_type' => 'pjc_slideshow',
	'posts_per_page' => 999,
	'orderby' => 'CONVERT(tonjoo_frs_order_number,signed) meta_value_num',
	'order' => 'ASC',
	'meta_key' => 'tonjoo_frs_order_number',
	'tax_query' => array(
		array(
			'taxonomy' => 'slide_type',
			'field' => 'slug',
			'terms' => $term_slug
		)
	)
);

$query = new WP_Query( $args );
$zeroHide = '';
?>

<table class="wp-list-table widefat fixed dataTable no-footer" id="table-slide" style="margin-top:20px;">
	<tbody>
	<?php if ($query->have_posts()): $zeroHide = 'hide' ?>


		<?php while ($query->have_posts()): $query->the_post(); ?>
		<?php

			//get permalink to edit
			$id = get_the_ID();
			$edit = admin_url()."post.php?post={$id}&action=edit";

			include 'ajax-row-template.php';
		?>
		<?php endwhile; ?>
	<?php endif; ?>

	</tbody>
</table>
<div class='no-slide <?php echo $zeroHide ?>'>
	<h2>The slide type seems empty ! </h2><br><a frs-add-slide  class='button button-primary'>Create First Slide</a><span class="spinner frs-button-spinner" ></span>
</div>

<?php 
	$id = null ;

	if($id==null){
		$type='Add';
	}
?>

</div>			
</div>			
</div>			
</div>		

<div class="postbox-container" style="float: right;margin-right: -300px;width: 280px;">
<div class="metabox-holder" style="padding-top:0px;">	
<div class="meta-box-sortables ui-sortable">
	<div id="email-signup" class="postbox">
		<h3 class="hndle"><span>Create New Slide</span></h3>
		<div class="inside" style="padding-top:10px;">
			A slideshow can be contains two or more slide, create your slide here.
			<br>
			<br>
			<a frs-add-slide class='button button-frs button-primary' data-step="6" data-intro="A slideshow can be contains two or more slide, create your slide here.">Add New Slide</a>
			<span class="spinner frs-button-spinner" ></span>
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


<?php if(isset($_GET['other']) && $_GET['other']=='add-new-intro'): ?>

<!-- Modal add and edit -->
<div class="frs-modal-backdrop"></div>
<div class='frs-modal-container postbox-container' style="display:none;">
	<div class="media-modal wp-core-ui" id="frs-tonjoo-modal">
		<div class="frs-modal-content">
			<div class="frs-modal-post">
				<form id="frs-modal-form">
					<div class="frs-table-left" data-step="11" data-intro="Configure the slide options <br><br>There are many options you can set here. Just do what you need :)">
						<!-- left content generated by ajax -->
					</div>

					<div class="frs-table-right">
						<input type="text" name="title" id="frs-title" placeholder="Slider Title" value="" data-step="8" data-intro="First to do to make a new slide, fill the slide title">
						<div frs-mediauploader="" style="margin:10px 0;" data-step="9" data-intro="Put your slide image <br><br>You can also fill the slide with color by do empty this image and set the <b>Slide Background Color</b> options">
							<img media-upload-image="" src="" class="frs-modal-image">
							<input media-upload-id="" type="hidden" name="featured_image" value="">
							<div>
				      			<input type="button" class="button-primary button-frs button" mediauploadbutton="" value="Set image">
				      			<a class="button-frs button button-danger" frs-remove-image="" data-image-default="">Remove Image</a>
							</div>		
						</div>	
						<div data-step="10" data-intro="Put your content <br>The content can be text or HTML">	
							<?php 
								$settings = array(
											'textarea_rows' => '30',
											'media_buttons' => false);

								wp_editor('','frs-modal-content',$settings);
							?>
						</div>
					</div>
				</form>
			</div>
		</div>
		<div class="floating-modal-button">
			<a class="button-frs button button-danger" data-post-id="" frs-delete-slide="">Delete Slide</a>
			<a class="button button-frs button-primary" frs-save-slider="" data-post-id="" data-step="12" data-intro="Don't forget to save your work">Save Slide</a>
			<span class="spinner frs-button-spinner frs-button-spinner-modal "></span>								
			
			<a class="button frs-modal-close" frs-modal-close-modal="">Cancel</a>
		</div>
	</div>	
</div>

<?php else: ?>

<!-- Modal add and edit -->
<div class="frs-modal-backdrop"></div>
<div class='frs-modal-container postbox-container' style="display:none;">
	<div class="media-modal wp-core-ui" id="frs-tonjoo-modal">
		<div class="frs-modal-content">
			<div class="frs-modal-post">
				<form id="frs-modal-form">
					<div class="frs-table-left">
						<!-- left content generated by ajax -->
					</div>

					<div class="frs-table-right">
						<input type="text" name="title" id="frs-title" placeholder="Slider Title" value="">							
						<div frs-mediauploader="" style="margin:10px 0;">
							<img media-upload-image="" src="" class="frs-modal-image">
							<input media-upload-id="" type="hidden" name="featured_image" value="">
							<div>
				      			<input type="button" class="button-primary button-frs button" mediauploadbutton="" value="Set image">
				      			<a class="button-frs button button-danger" frs-remove-image="" data-image-default="">Remove Image</a>
							</div>		
						</div>		
						<?php 
							$settings = array(
										'textarea_rows' => '30',
										'media_buttons' => false);

							wp_editor('','frs-modal-content',$settings);
						?>
					</div>
				</form>
			</div>
		</div>
		<div class="floating-modal-button">
			<a class="button-frs button button-danger" data-post-id="" frs-delete-slide="">Delete Slide</a>
			<a class="button button-frs button-primary" frs-save-slider="" data-post-id="">Save Slide</a>
			<span class="spinner frs-button-spinner frs-button-spinner-modal "></span>								
			
			<a class="button frs-modal-close" frs-modal-close-modal="">Cancel</a>
		</div>
	</div>	
</div>


<?php endif;

/* Restore original Post Data */
wp_enqueue_media();
wp_reset_postdata();