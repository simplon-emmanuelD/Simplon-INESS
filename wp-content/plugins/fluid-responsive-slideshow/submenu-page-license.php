<script type="text/javascript">
	jQuery(document).ready(function($){
		$('#frs-select-slideshow-menu').hide();
	})
</script>

<?php 
/**
 * save options
 */
if($_POST && isset($_POST['pjc_slideshow_license']))
{
	/**
	 * Tonjoo License
	 */
	if(class_exists('TonjooPluginLicenseFRS'))
	{
		$PluginLicense = new TonjooPluginLicenseFRS($_POST['pjc_slideshow_license']['license_key']);
		$_POST = $PluginLicense->license_on_save($_POST);
	}

	// update option
	update_option('pjc_slideshow_license', $_POST['pjc_slideshow_license']);
	
	$location = admin_url("edit.php?post_type=pjc_slideshow&page=frs-setting-page&tab=$current&tabtype=license&settings-updated=true");
	echo "<meta http-equiv='refresh' content='0;url=$location' />";
	echo "<h2>Loading...</h2>";
	exit();
}

$options = get_option('pjc_slideshow_license');
?>

<form method="post" action="" id="frs-option-form">
<?php settings_fields('pjc_options'); ?>


<div class="metabox-holder columns-2" style="margin-right: 300px;">
<div class="postbox-container" style="width: 100%;min-width: 463px;float: left; ">
<div class="meta-box-sortables ui-sortable">
<div id="adminform" class="postbox">
<h3 class="hndle" data-step="6" data-intro="Configure and setting up your slideshow options"><span>License</span></h3>
<div class="inside" style="z-index:1;">
<!-- Extra style for options -->
<style type="text/css">
	#license_status input {
		width: 200px;
	}
</style>

<table class="form-table">	

	<?php
		/** 
		 * license status 
		 */	
		$license = isset($options['license_status']) ? unserialize($options['license_status']) : false;	

		$license_status = "<span style='color:red'>Unregistered</span>";
		$license_email = "<span style='color:red'>None</span>";
		$license_date = "<span style='color:red'>Not checked</span>";
		$license_site = "<span style='color:red'>None</span>";

		if($license)
		{
			// status
			if($license['status'])
			{
				$license_status = "<span style='color:blue'>";
				$license_status.= __('Registered',FRS_VERSION);
				$license_status.= "</span>";
			} else {
				$license_status = "<span style='color:red'>";
				$license_status.= __($license['message'],FRS_VERSION);
				$license_status.= "</span>";
			}

			// email
			if(isset($license['email']) && $license['email'] != 'false')
			{
				$license_email = "<span style='font-weight:bold'>{$license['email']}</span>";
			}
			else
			{
				$license_email = "<span style='color:red'>none</span>";
			}

			// date
			if(isset($license['date']) && $license['date'])
			{
				$license_date = $license['date'];
			}
			else
			{
				$license_date = "<span style='color:red'>not checked</span>";
			}

			// site
			if(isset($license['site']) && is_array($license['site']))
			{
				foreach ($license['site'] as $key => $value) 
				{
					$pos = strpos(home_url(), $value);

					if($pos !== false)
					{
						$license_site = $value;

						break;
					}
				}
			}

			// end license if true
		}
	?>

	<tr valign="top" id="license_status">
		<th scope="row">Your License Code</th>
		<td style="width: 300px;" colspan="2">
			<input type="text" name="pjc_slideshow_license[license_key]" value="<?php echo $options['license_key'] ?>" style="width:300px;">
			<input type="submit" name="save_status_license" class="button-primary" value="Register / Check Status" />
		</td>
	</tr>

	<tr valign="top" id="license_status">
		<th scope="row">Last Checked</th>
		<td style="width: 300px;" colspan="2">
			<?php echo $license_date ?>
		</td>
	</tr>

	<tr valign="top" id="license_status">
		<th scope="row">Last Status</th>
		<td style="width: 300px;" colspan="2">
			<?php echo $license_status ?>
		</td>
	</tr>

	<?php if($license['status']): ?>
		<tr valign="top" id="license_status">
			<th scope="row">Licensed To</th>
			<td style="width: 300px;" colspan="2">
				<?php echo $license_email ?>
			</td>
		</tr>

		<tr valign="top" id="license_status">
			<th scope="row">Registered Sites</th>
			<td style="width: 300px;" colspan="2">
				<?php echo $license_site ?>
			</td>
		</tr>

		<tr valign="top" id="license_status">
			<th scope="row" colspan="3">
				<input type="submit" name="unset_license" class="button" value="Unregister this site" />
			</th>
		</tr>
	<?php endif ?>

	<tr valign="top">
		<th colspan=3>
			<?php 
				_e('Register your license code here to get all benefit of Fluid Responsive Slideshow. ',FRS_VERSION);
				echo '<div style="height:10px;"></div>';
				_e('<b>Remove Ads</b> by register your license code. ',FRS_VERSION);
				echo '<div style="height:10px;"></div>';
				_e('Find your license code at ',FRS_VERSION);
			?> 
			<a href="https://tonjoostudio.com/manage/plugin" target="_blank">https://tonjoostudio.com/manage/plugin</a>
		</th>
	</tr>
</table>

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