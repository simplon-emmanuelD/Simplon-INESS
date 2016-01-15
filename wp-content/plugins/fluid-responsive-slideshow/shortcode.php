<?php
/**
 * Enqueue script
 */
add_action('wp_enqueue_scripts', 'pjc_jq_scripts', 10000);
function pjc_jq_scripts() 
{
	wp_enqueue_script('jquery');

	$file = dirname(__FILE__) . '/Fluid-Responsive-Slideshow.php';	
	$plugin_url = plugin_dir_url($file);	
	$frs_js = $plugin_url . "js/frs.js";	
	$images_loaded = $plugin_url . "js/imagesloaded.min.js";	
	$touchSwipe_js = $plugin_url . "js/jquery.touchSwipe.min.js";	
	
	wp_enqueue_style('OpenSans',FRS_HTTP_PROTO . "fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,400,700",array(),FRS_VERSION);
	wp_enqueue_script('fluid-responsive-slideshow',$frs_js,array(),FRS_VERSION);
	wp_enqueue_script('images-loaded',$images_loaded,array(),FRS_VERSION);
	wp_enqueue_script('touchSwipe_js',$touchSwipe_js,array(),FRS_VERSION);
}

/**
 * For admin slide preview
 */
add_action('init', 'pjc_jq_scripts_admin', 10000);
function pjc_jq_scripts_admin() 
{
	wp_enqueue_script('jquery');

	$file = dirname(__FILE__) . '/Fluid-Responsive-Slideshow.php';
	$plugin_url = plugin_dir_url($file);	
	$frs_js = $plugin_url . "js/frs.js";	
	$images_loaded = $plugin_url . "js/imagesloaded.min.js";	
	$touchSwipe_js = $plugin_url . "js/jquery.touchSwipe.min.js";	

	wp_enqueue_script('fluid-responsive-slideshow', $frs_js,array(),FRS_VERSION);	
	wp_enqueue_script('touchSwipe_js', $touchSwipe_js);
	wp_enqueue_script('images-loaded',$images_loaded,array(),FRS_VERSION);
}

function get_array_skins()
{
	$skins = scandir(dirname(__FILE__)."/skins");
	$slideshow_skin =  array();

	foreach ($skins as $key => $value) 
	{
		$extension = pathinfo($value, PATHINFO_EXTENSION); 
		$filename = pathinfo($value, PATHINFO_FILENAME); 
		$extension = strtolower($extension);
		$the_value = strtolower($filename);

		if($extension=='css')
		{			
			array_push($slideshow_skin,$the_value);
		}
	}

	if(function_exists('is_frs_premium_exist')) 
	{
		$skins = scandir(ABSPATH . 'wp-content/plugins/'.FRS_PREMIUM_DIR_NAME.'/skins');

		foreach ($skins as $key => $value) 
		{
			$extension = pathinfo($value, PATHINFO_EXTENSION); 
			$filename = pathinfo($value, PATHINFO_FILENAME); 
			$extension = strtolower($extension);
			$the_value = strtolower($filename);

			if($extension=='css')
			{
				array_push($slideshow_skin,$the_value.'-PREMIUMtrue');
			}
		}
	}

	return $slideshow_skin;
}

function get_array_buttonskins()
{
    $skins = scandir(dirname(__FILE__)."/buttons");

    $button_skin =  array();

    foreach ($skins as $key => $value) 
    {
        $extension = pathinfo($value, PATHINFO_EXTENSION); 
        $filename = pathinfo($value, PATHINFO_FILENAME); 
        $extension = strtolower($extension);
        $the_value = strtolower($filename);

        if($extension=='css')
        {
            array_push($button_skin,$the_value);
        }
    }

    if(function_exists('is_frs_premium_exist')) 
    {
        $skins = scandir(ABSPATH . 'wp-content/plugins/'.FRS_PREMIUM_DIR_NAME.'/buttons');

        foreach ($skins as $key => $value) 
        {
            $extension = pathinfo($value, PATHINFO_EXTENSION); 
            $filename = pathinfo($value, PATHINFO_FILENAME); 
            $extension = strtolower($extension);
            $the_value = strtolower($filename);

            if($extension=='css')
            {
                array_push($button_skin,$the_value.'-PREMIUMtrue');
            }
        }
    }

    return $button_skin;
}


/**
 * Generate shortcode
 */
add_shortcode('pjc_slideshow', 'pjc_gallery_print');
function pjc_gallery_print($attr) 
{
	/**
 	 * Query get post type
 	 */
	$condition  = array('orderby' => 'CONVERT(tonjoo_frs_order_number,signed) meta_value_num',
					'order' => 'ASC',
					'meta_key' => 'tonjoo_frs_order_number',
					'slide_type' => $attr['slide_type'],
					'posts_per_page'=>-1,
					'suppress_filters'=>true);

	/**
	 * Initialize slug shortcode
	 */
	$current = get_term_by('name', $attr['slide_type'], 'slide_type', 'ARRAY_A');
	if($current == NULL)
	{
		$current = get_term_by('slug', $attr['slide_type'], 'slide_type', 'ARRAY_A');
	}	

	$current = $current['slug'];

	require (plugin_dir_path(__FILE__) . 'default-options.php');

	/**
	 * Check skin name is available in skin folder
	 */
	$array_skin = get_array_skins();

	if(! isset($options[$current]['skin']) || ! in_array($options[$current]['skin'], $array_skin))
	{
	 	$options[$current]['skin']="frs-skin-default";
	}


	/**
	 * Get skin
	 */
	if(isset($_GET['skin'])) 
	{
		//Safe yourself from hacker
		if(!preg_match('/[0-9a-zA-Z- _]/',$_GET['skin']))
    		die();

		$options[$current]['skin'] = $_GET['skin'];
	}

	$exp = explode('-PREMIUM', $options[$current]['skin']);
	if(count($exp) > 1 AND $exp[1] == 'true')
	{
		if(function_exists('is_frs_premium_exist'))
		{
			$skin = plugins_url(FRS_PREMIUM_DIR_NAME."/skins/{$exp[0]}.css");

			$options[$current]['skin'] = $exp[0];
		}
		else
		{
			$skin = plugins_url("skins/default.css" , __FILE__ );

			$options[$current]['skin'] = 'default';
		}		
	}
	else
	{
		$skin = plugins_url("skins/{$options[$current]['skin']}.css" , __FILE__ );
	}

	/**
	 * Skin css enqueue
	 */
	wp_enqueue_style("{$options[$current]['skin']}",$skin,array(),FRS_VERSION);

	if (!$attr) {
		extract(shortcode_atts(array('slide_type' => 'empty', ), $attr));
	}

	/**
	 * Check if taxonomy exist
	 */
	if ($attr['slide_type'] == "empty" || !term_exists($attr['slide_type']))
	{
		return "<span style='padding:5px;margin:10px;background-color:#FF5959;color:white'>You must provide the correct slide type on the [pjc_slideshow slide_type='your_slide_type'] shortcode</span>";
	}
	else
	{
		if ($options[$current]['fade_time'] == '0') 
		{
			$options[$current]['timer'] = "false";
		} 
		else 
		{
			$options[$current]['timer'] = "true";
		}

		$attr['slide_type'] = $attr['slide_type'];
		$attr['slide_type_id'] = $attr['slide_type'] . "pjc";

		$textbox_style = "";

		/**
		 * Slider bullets
		 */
		$slider_bullets_skin = array('leaves','bric','prosix');
		$sbullets = "false";

		$skin_cutted = $options[$current]['skin'];
		$skin_cutted = explode('-', $skin_cutted);		
		$skin_cutted = $skin_cutted[2];

		if(in_array($skin_cutted,$slider_bullets_skin))
		{
			$sbullets = "true";
		}
		
		$javascript = "<!-- Slideshow generated using Fluid-Responsive-Slideshow, https://www.tonjoostudio.com/addons/fluid-responsive-slideshow/ -->
		<script type='text/javascript'>
			jQuery(document).ready(function($) {
				$('#{$attr['slide_type_id']}').frs({
					animation : '{$options[$current]['animation']}', // horizontal-slide, vertical-slide, fade
					animationSpeed :  {$options[$current]['animation_time']}, // how fast animtions are
					timer :  {$options[$current]['timer']}, // true or false to have the timer
					advanceSpeed :   {$options[$current]['fade_time']}, // if timer is enabled, time between transitions
					pauseOnHover : {$options[$current]['pause']}, // if you hover pauses the slider
					startClockOnMouseOut :  {$options[$current]['start_mouseout']}, // if clock should start on MouseOut
					startClockOnMouseOutAfter :  {$options[$current]['start_mouseout_after']}, // how long after MouseOut should the timer start again
					directionalNav : true, // manual advancing directional navs
					captions : true, // do you want captions?
					captionAnimation : 'fade', // fade, slideOpen, none
					captionAnimationSpeed : 800, // if so how quickly should they animate in
					bullets :  true, // true or false to activate the bullet navigation
					bulletThumbs : true, // thumbnails for the bullets
					bulletThumbLocation : '', // location from this file where thumbs will be
					navigationSmallTreshold:  false,
					navigationSmall: 600,
					skinClass: '{$options[$current]['skin']}',
					width: {$options[$current]['width']},
					height: {$options[$current]['height']},
					fullWidth: {$options[$current]['full_width']},
					minHeight: {$options[$current]['min_height']},
					maxHeight: {$options[$current]['max_height']},
					sbullets: $sbullets,
					sbulletsItemWidth: 200,
					continousSliding: false,
					jsOnly: false
				});		
						
				/**
				 * touchSwipe
				 */
				$('#$attr[slide_type_id]-slideshow .frs-slide-img').swipe( 
				{
			        swipeLeft:function(event, direction, distance, duration, fingerCount) {
						$('#$attr[slide_type_id]-slideshow .frs-slider-nav .frs-arrow-right').click();						
					},

					swipeRight:function(event, direction, distance, duration, fingerCount) {
						$('#$attr[slide_type_id]-slideshow .frs-slider-nav .frs-arrow-left').click();
					},

			        triggerOnTouchLeave:true
			    });
			})		
		</script>
		<!--[if lt IE 9]>
			<style type='text/css'>
			#$attr[slide_type_id]-slideshow .frs-timer { display: none !important; }
			#$attr[slide_type_id]-slideshow div.caption { background:transparent; filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000,endColorstr=#99000000);zoom: 1; }
			</style>
		<![endif]-->";

		if($options[$current]['show_timer']=="false")
		{
			$timer ="#$attr[slide_type_id]-slideshow .frs-timer { display: none !important; }";
		}
		else
		{
			$timer = "";
		}

 		if($options[$current]['navigation'] == 'false')
 		{
 			$directionalNav = "#$attr[slide_type_id]-slideshow .frs-slider-nav { display:none !important; }";
 		}
 		else
 		{
 			$directionalNav = "";
 		}


        /**
         * Get addon style
         */
        $addon_style = "";
        $addon_script = "<script type='text/javascript'>jQuery(document).ready(function($){";

        require( plugin_dir_path( __FILE__ ) . 'skins.php');
		
		require_once( ABSPATH . 'wp-admin/includes/plugin.php' );

        if(function_exists('is_frs_premium_exist')) 
		{
			$dir_skinphp =  ABSPATH . 'wp-content/plugins/'.FRS_PREMIUM_DIR_NAME.'/skins.php';
			require($dir_skinphp);
 		}

 		$li = isset($li) ? $li : "";


 		/**
 		 * Kill Switch Bullet Navigation, only for old template skins
 		 */
		$killswitchBullet =""; 		
	
 		if($options[$current]['bullet']=='false')
 		{
 			$skin_than_can_hide_bullet = array('candy','default','elegant','minimalist','modern','autum','cherryl','galactic','kafh','postcard','sephia','shade');

 			//check each skins 
 			foreach ($skin_than_can_hide_bullet as $value) 
 			{ 		
 				//!==false means the current skins match $skin_than	_can_hide_bullet 		
 				if(strpos($options[$current]['skin'],$value)!==false)
 				{ 			
 					$killswitchBullet = "#$attr[slide_type_id] .frs-wrapper .frs-bullets-wrapper{ display:none !important; height:0px !important; }";

		 			break;
 				}

 			}

 		}

 		$addon_script .= "})</script>";

 		/**
 		 * Print style
 		 */
        $style = "<style type='text/css'>
			$timer
			$li		
			$textbox_style
			$addon_style	
			$directionalNav	
			#$attr[slide_type_id] .frs-wrapper .frs-caption {
				display:none;
			}
			#$attr[slide_type_id]-slideshow .frs-caption h4, #$attr[slide_type_id]-slideshow .frs-caption-inner h4{
				font-size:{$options[$current]['textbox_h4_size']}px;
			    margin:0px;		
			}
			#$attr[slide_type_id]-slideshow .frs-caption p, #$attr[slide_type_id]-slideshow .frs-caption-inner p{
			    margin:0px;		
			    font-size:{$options[$current]['textbox_p_size']}px;
			}
			.frs-slideshow-container span.frs-caption {
				height: 0px !important;
				position: absolute;
			}
			.frs-caption-content div h1, .frs-caption-content div h2, .frs-caption-content div h3, .frs-caption-content div h4, .frs-caption-content div p {
				color: inherit !important;
				text-align: inherit !important;
				line-height: 1.5;
			}
			$killswitchBullet
		</style>";
      

		/**
		 * Button skin
		 */
		if($options[$current]['show_textbox']=="false"){
			$caption="";
		}
		else
		{
			$query = new WP_Query($condition);
			$arr_loaded_button_skin = array();

			while($query->have_posts())
		    {
		    	$query->the_post();

		    	$post = get_post(get_the_ID());

		    	$postmeta = get_post_meta($post->ID,'tonjoo_frs_meta',true);

		    	/**
				 * Check button skin name is available in button skin folder
				 */
		    	$array_buttonskins = get_array_buttonskins();

				if(! isset($postmeta['button_skin']) || ! in_array($postmeta['button_skin'], $array_buttonskins))
				{
			 		$postmeta['button_skin'] = 'frs-buttonskin-white';
			 	}	

		    	if(! in_array($postmeta['button_skin'], $arr_loaded_button_skin))
		    	{
		    		array_push($arr_loaded_button_skin, $postmeta['button_skin']);

			    	/* filter if premium */
					$exp = explode('-PREMIUM', $postmeta['button_skin']);
					if(count($exp) > 1 AND $exp[1] == 'true')
					{
						$button_skin = plugins_url(FRS_PREMIUM_DIR_NAME."/buttons/{$exp[0]}.css");
			    		$style .= "<link rel='stylesheet' href='$button_skin' type='text/css'>";			    		
			    	}
			    	else
			    	{
			    		$button_skin = plugins_url(FRS_DIR_NAME."/buttons/{$exp[0]}.css");
			    		$style .= "<link rel='stylesheet' href='$button_skin' type='text/css'>";
			    	}
		    	}
		    }

			/**
			 * Always reset query after perfor wp query
			 */
		    wp_reset_query();
		}

		 
		/**
		 * Loop  the slide
		 */
        $query = new WP_Query($condition);
	    $slide = "";
	    
    	while ( $query->have_posts() )
    	{    	
    		$query->the_post();

    		$post = get_post(get_the_ID());

    		$postmeta = get_post_meta($post->ID, 'tonjoo_frs_meta',true);
			$thumb = wp_get_attachment_image_src( get_post_thumbnail_id(),'original');
			$url = $thumb['0'];


			/**
			 * Caption
			 */
        	$caption = "";
        	$arr_position = array('left','top-left','top','top-right','right','bottom-right','bottom','bottom-left','center','sticky-top','sticky-bottom');

        	foreach ($arr_position as $key => $value) 
        	{
        		$arr_position[$key] = "frs-caption-position-".$value;
        	}

		 	if(! isset($postmeta['text_position']) || ! in_array($postmeta['text_position'], $arr_position))
		 	{
		 		$postmeta['text_position'] = 'frs-caption-position-left';
		 	}
		 	/* end caption position options */

		 	if(! isset($postmeta['textbox_width']))
		 	{
		 		$postmeta['textbox_width'] = 5;
		 	}

		 	/**
			 * Check button skin name is available in button skin folder
			 */
	    	$array_buttonskins = get_array_buttonskins();

			if(! isset($postmeta['button_skin']) || ! in_array($postmeta['button_skin'], $array_buttonskins))
			{
		 		$postmeta['button_skin'] = 'frs-buttonskin-white';
		 	}	 	  	
		
			if($postmeta['show_text']=='true')
			{
				$class_name = "caption".$attr['slide_type_id'];

				/* style h4 & p */
				$title_color = "{$postmeta['title_color']};";
				$textcolor = "{$postmeta['text_color']};";
				$text_align = isset($postmeta['text_align']) ? "{$postmeta['text_align']};" : 'left;';

				$style_h4 = "text-align:$text_align color:$title_color";
				$style_p = "text-align:$text_align color:$textcolor";

				/* style textbox bg */
				$bg_textbox_type = isset($postmeta['bg_textbox_type']) ? $postmeta['bg_textbox_type'] : "picture";
				$textbox_color = isset($postmeta['textbox_color']) ? $postmeta['textbox_color'] : "#ffffff";
				$textbox_bg = isset($postmeta['textbox_bg']) ? $postmeta['textbox_bg'] : "black";
				$textbox_style = "";

				if($bg_textbox_type == "color")
				{
					$textbox_style .= 'background: none !important;background-color: '.$textbox_color.' !important;';
				}
				elseif ($bg_textbox_type == "picture") 
				{
					$textbox_style .= 'background: url("'.plugins_url("backgrounds/$textbox_bg.png" , __FILE__ ).'") repeat scroll 0% 0% transparent !important;';
				}
				elseif ($bg_textbox_type == "none")
				{
					$textbox_style .= 'background: none !important;';
				}

				/* frs-caption-content style */

				$textbox_width = ($postmeta['textbox_width'] / 12) * 100;
				$textbox_padding = "padding:25px;";

				if(isset($postmeta['padding_type']) && $postmeta['padding_type'] == 'manual')
				{					
					$textbox_padding = "padding:{$postmeta['textbox_padding']};";
				}
				else
				{
					/**
					 * there is different padding style in some skin with sticky position
					 */
					$skin_padding_1 = array('elegant','autum-blue','autum-green','autum-orange',
											'autum-purple','cherryl-blue','cherryl-green','cherryl-orange',
											'cherryl-purple');
					$skin_padding_2 = array('leaves-blue','leaves-green','leaves-orange','leaves-red');
					$skin_padding_3 = array('modern');

					foreach ($skin_padding_1 as $key => $value) 
			    	{
			    		$skin_padding_1[$key] = "frs-skin-".$value;
			    	}

			    	foreach ($skin_padding_2 as $key => $value) 
			    	{
			    		$skin_padding_2[$key] = "frs-skin-".$value;
			    	}

			    	foreach ($skin_padding_3 as $key => $value) 
			    	{
			    		$skin_padding_3[$key] = "frs-skin-".$value;
			    	}

					if($postmeta['text_position'] == 'frs-caption-position-sticky-bottom')
					{
						if(in_array($options[$current]['skin'],$skin_padding_1))
						{
							$textbox_padding = "padding:25px 25px 60px 25px;";							
						}
						elseif(in_array($options[$current]['skin'],$skin_padding_2))
						{
							$textbox_padding = "padding:25px 25px 40px 25px;";
						}
					}					

					if($postmeta['text_position'] == 'frs-caption-position-sticky-top')
					{
						if(in_array($options[$current]['skin'], $skin_padding_3))
						{
							$textbox_padding = "padding:25px 25px 25px 100px;";
						}
					}
				}

				$frs_caption_content_style = "width: $textbox_width%; $textbox_style $textbox_padding";

				/* outer and inner style */
				$outer_style = "height:100%;width:100%;display:table;";
				$inner_style = "height:100%;display:table-cell;";

				/* print caption */
				$caption .= "<div class='frs-caption {$postmeta['text_position']}' style='display: block;' id='".$class_name."'>";					
				$caption .= "<div class='frs-caption-outer' style='$outer_style'>";
				$caption .= "<div class='frs-caption-inner' style='text-align:$text_align $inner_style'>";					
				$caption .= "<div class='frs-caption-content' style='$frs_caption_content_style'>";
				$caption .= "<h4 style='$style_h4'>{$post->post_title}</h4>";

				/* output buffering */
	
				$content = $post->post_content;

				$content = apply_filters('the_content',$content);

				$caption .= "<div style='$style_p'>".$content."</div>";
								
				/* print button */
				if(isset($postmeta['show_button']) && $postmeta['show_button'] == 'true')
				{
					$button_skin = explode('-PREMIUM', $postmeta['button_skin']);

					$caption .= "<p style='padding-bottom:0px;text-align:$text_align' class='frs-caption-button {$button_skin[0]}'><a href='{$postmeta['button_href']}'>";
					$caption .= "<span>{$postmeta['button_caption']}</span></a></p>";
				}

				$caption .= "</div>";
				$caption .= "</div>";
				$caption .= "</div>";					
				$caption .= "</div>";
			}


			/**
			 * Print slides
			 */
			$slider_style = "";			

			if(! isset($postmeta['slider_bg']))
			{
				$postmeta['slider_bg'] = "#000000";
			}

			$slider_style = "style = 'display:none;background:{$postmeta['slider_bg']}'";
			
			$slide .= "<div class='frs-slide-img' $slider_style >";

			if(! empty($url))
			{
				$slide .= "<img alt='$post->post_title' src='$url' />";
			}

			if($options[$current]['show_textbox'] == "true")
			{
				$slide .= $caption;
			}

			$slide .= "</div>";

        } //end get slide

        /**
         * custom css
         */
        $style .= "<style>{$options[$current]["custom_css"]}</style>";
		
		/**
		 * print html
		 */
		$html  = "<div class='frs-slideshow-container' id='{$attr['slide_type_id']}'>";
		$html .= "<div class='frs-slide-img-wrapper' style='display:none;'>";
		$html .= $slide;
		$html .= "</div>";
		$html .= "</div>";
		
		wp_reset_query(); // restore main query 
		
		/**
		 * Return shortcode
		 */
		if($query->have_posts()) 
		{
			$shortcode = $style.$javascript.$html.$addon_script;
		}
		else
		{
			$shortcode  = "<center style='margin:100px 0px;font-weight:bold;'>No slide available, click <a href='".get_admin_url();
			$shortcode .= "admin.php?page=frs-setting-page&tabtype=slide&tab={$_GET['tab']}'>here to add a new one</a></center>";
		}	
		
		return $shortcode;
	}
}