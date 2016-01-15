    <?php

function front_end_portfolio($images, $paramssld, $portfolio)
{
/**<add>**/	
if (!function_exists('get_video_id_from_url_portfolio'))	{
	function get_video_id_from_url_portfolio($url){
		if(strpos($url,'youtube') !== false || strpos($url,'youtu') !== false){	
			if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match)) {
				return array ($match[1],'youtube');
			}
		}else {
			$vimeoid =  explode( "/", $url );
			$vimeoid =  end($vimeoid);
			return array($vimeoid,'vimeo');
		}
	}
}

if (!function_exists('youtube_or_vimeo_portfolio'))	{

	function youtube_or_vimeo_portfolio($url){
		if(strpos($url,'youtube') !== false || strpos($url,'youtu') !== false){	
			if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match)) {
				return 'youtube';
			}
		}
		elseif(strpos($url,'vimeo') !== false) {
			$explode = explode("/",$url);
			$end = end($explode);
			if(strlen($end) == 8 || strlen($end) == 9)
				return 'vimeo';
		}
		return 'image';
	}

}

/**</add>**/

/**</add>**/

 ob_start();
	$portfolioID=$portfolio[0]->id;
	$portfoliotitle=$portfolio[0]->name;
	$portfolioheight=$portfolio[0]->sl_height;
	$portfoliowidth=$portfolio[0]->sl_width;
	$portfolioeffect=$portfolio[0]->portfolio_list_effects_s;
	$slidepausetime=($portfolio[0]->description+$portfolio[0]->param);
	$portfoliopauseonhover=$portfolio[0]->pause_on_hover;
	$portfolioposition=$portfolio[0]->sl_position;
	$slidechangespeed=$portfolio[0]->param;
        $portfolioCats=$portfolio[0]->categories;
        $portfolioShowSorting=$portfolio[0]->ht_show_sorting;
        $portfolioShowFiltering=$portfolio[0]->ht_show_filtering;
		
				/***<optimize_images>***/
		$image_prefix = "_huge_it_small_portfolio";
	if(!function_exists('get_huge_image')) {
		function get_huge_image($image_url,$img_prefix) {
				$pathinfo = pathinfo($image_url);
				$upload_dir = wp_upload_dir();
				$url_img_copy = $upload_dir["url"].'/'.$pathinfo["filename"].$img_prefix.'.'.$pathinfo["extension"];
				$img_abs_path = $url_img_copy;
				$img_abs_path= parse_url($url_img_copy, PHP_URL_PATH);
				$img_abs_path =  $_SERVER['DOCUMENT_ROOT'].$img_abs_path;
				if(file_exists($img_abs_path))
				return $url_img_copy; else
			 return $image_url;
		}
	}
			/***</optimize_images>***/
			
			/***<title display>***/
	if(!function_exists('huge_it_title_img_display')) {
		function huge_it_title_img_display($image_name,$title) {
			for($i = 0;$i < count($title);$i++) {
				$title_explode = explode("_-_-_",$title[$i]);
				if($title_explode[1] == $image_name) {
					echo $title_explode[0];  
				}
				else { 
					echo "" ;
				}
			}
		}
	}
		 	/***</title display>***/


$paramssld['ht_view0_border_width'] = "1";
$paramssld["ht_view0_togglebutton_style"] = "dark";
$paramssld["ht_view0_show_separator_lines"] = "on";
$paramssld["ht_view0_linkbutton_text"] = "View More";
$paramssld["ht_view0_show_linkbutton"] = "on";
$paramssld["ht_view0_linkbutton_background_hover_color"] = "df2e1b";
$paramssld["ht_view0_linkbutton_background_color"] = "e74c3c";
$paramssld["ht_view0_linkbutton_font_hover_color"] = "ffffff";
$paramssld["ht_view0_linkbutton_color"] = "ffffff";
$paramssld["ht_view0_linkbutton_font_size"] = "14";
$paramssld["ht_view0_description_color"] = "5b5b5b";
$paramssld["ht_view0_description_font_size"] = "14";
$paramssld["ht_view0_show_description"] = "on";
$paramssld["ht_view0_thumbs_width"] = "75";
$paramssld["ht_view0_thumbs_position"] = "before";
$paramssld["ht_view0_show_thumbs"] = "on";
$paramssld["ht_view0_title_font_size"] = "15";
$paramssld["ht_view0_title_font_color"] = "555555";
$paramssld["ht_view0_element_border_width"] = "1";
$paramssld["ht_view0_element_border_color"] = "D0D0D0";
$paramssld["ht_view0_element_background_color"] = "f7f7f7";
$paramssld["ht_view0_block_width"] = "275";
$paramssld["ht_view0_block_height"] = "160";
$paramssld["ht_view1_show_separator_lines"] = "on";
$paramssld["ht_view1_linkbutton_text"] = "View More";
$paramssld["ht_view1_show_linkbutton"] = "on";
$paramssld["ht_view1_linkbutton_background_hover_color"] = "df2e1b";
$paramssld["ht_view1_linkbutton_background_color"] = "e74c3c";
$paramssld["ht_view1_linkbutton_font_hover_color"] = "ffffff";
$paramssld["ht_view1_linkbutton_color"] = "ffffff";
$paramssld["ht_view1_linkbutton_font_size"] = "14";
$paramssld["ht_view1_description_color"] = "5b5b5b";
$paramssld["ht_view1_description_font_size"] = "14";
$paramssld["ht_view1_show_description"] = "on";
$paramssld["ht_view1_thumbs_width"] = "75";
$paramssld["ht_view1_thumbs_position"] = "before";
$paramssld["ht_view1_show_thumbs"] = "on";
$paramssld["ht_view1_title_font_size"] = "15";
$paramssld["ht_view1_title_font_color"] = "555555";
$paramssld["ht_view1_element_border_width"] = "1";
$paramssld["ht_view1_element_border_color"] = "D0D0D0";
$paramssld["ht_view1_element_background_color"] = "f7f7f7";
$paramssld["ht_view1_block_width"] = "275";
$paramssld["ht_view2_element_linkbutton_text"] = "View More";
$paramssld["ht_view2_element_show_linkbutton"] = "on";
$paramssld["ht_view2_element_linkbutton_color"] = "ffffff";
$paramssld["ht_view2_element_linkbutton_font_size"] = "14";
$paramssld["ht_view2_element_linkbutton_background_color"] = "2ea2cd";
$paramssld["ht_view2_show_popup_linkbutton"] = "on";
$paramssld["ht_view2_popup_linkbutton_text"] = "View More";
$paramssld["ht_view2_popup_linkbutton_background_hover_color"] = "0074a2";
$paramssld["ht_view2_popup_linkbutton_background_color"] = "2ea2cd";
$paramssld["ht_view2_popup_linkbutton_font_hover_color"] = "ffffff";
$paramssld["ht_view2_popup_linkbutton_color"] = "ffffff";
$paramssld["ht_view2_popup_linkbutton_font_size"] = "14";
$paramssld["ht_view2_description_color"] = "222222";
$paramssld["ht_view2_description_font_size"] = "14";
$paramssld["ht_view2_show_description"] = "on";
$paramssld["ht_view2_thumbs_width"] = "75";
$paramssld["ht_view2_thumbs_height"] = "75";
$paramssld["ht_view2_thumbs_position"] = "before";
$paramssld["ht_view2_show_thumbs"] = "on";
$paramssld["ht_view2_popup_background_color"] = "FFFFFF";
$paramssld["ht_view2_popup_overlay_color"] = "000000";
$paramssld["ht_view2_popup_overlay_transparency_color"] = "70";
$paramssld["ht_view2_popup_closebutton_style"] = "dark";
$paramssld["ht_view2_show_separator_lines"] = "on";
$paramssld["ht_view2_show_popup_title"] = "on";
$paramssld["ht_view2_element_title_font_size"] = "18";
$paramssld["ht_view2_element_title_font_color"] = "222222";
$paramssld["ht_view2_popup_title_font_size"] = "18";
$paramssld["ht_view2_popup_title_font_color"] = "222222";
$paramssld["ht_view2_element_overlay_color"] = "FFFFFF";
$paramssld["ht_view2_element_overlay_transparency"] = "70";
$paramssld["ht_view2_zoombutton_style"] = "light";
$paramssld["ht_view2_element_border_width"] = "1";
$paramssld["ht_view2_element_border_color"] = "dedede";
$paramssld["ht_view2_element_background_color"] = "f9f9f9";
$paramssld["ht_view2_element_width"] = "275";
$paramssld["ht_view2_element_height"] = "160";
$paramssld["ht_view3_show_separator_lines"] = "on";
$paramssld["ht_view3_linkbutton_text"] = "View More";
$paramssld["ht_view3_show_linkbutton"] = "on";
$paramssld["ht_view3_linkbutton_background_hover_color"] = "0074a2";
$paramssld["ht_view3_linkbutton_background_color"] = "2ea2cd";
$paramssld["ht_view3_linkbutton_font_hover_color"] = "ffffff";
$paramssld["ht_view3_linkbutton_color"] = "ffffff";
$paramssld["ht_view3_linkbutton_font_size"] = "14";
$paramssld["ht_view3_description_color"] = "555555";
$paramssld["ht_view3_description_font_size"] = "14";
$paramssld["ht_view3_show_description"] = "on";
$paramssld["ht_view3_thumbs_width"] = "75";
$paramssld["ht_view3_thumbs_height"] = "75";
$paramssld["ht_view3_show_thumbs"] = "on";
$paramssld["ht_view3_title_font_size"] = "18";
$paramssld["ht_view3_title_font_color"] = "0074a2";
$paramssld["ht_view3_mainimage_width"] = "240";
$paramssld["ht_view3_element_border_width"] = "1";
$paramssld["ht_view3_element_border_color"] = "dedede";
$paramssld["ht_view3_element_background_color"] = "f9f9f9";
$paramssld["ht_view4_togglebutton_style"] = "dark";
$paramssld["ht_view4_show_separator_lines"] = "on";
$paramssld["ht_view4_linkbutton_text"] = "View More";
$paramssld["ht_view4_show_linkbutton"] = "on";
$paramssld["ht_view4_linkbutton_background_hover_color"] = "df2e1b";
$paramssld["ht_view4_linkbutton_background_color"] = "e74c3c";
$paramssld["ht_view4_linkbutton_font_hover_color"] = "ffffff";
$paramssld["ht_view4_linkbutton_color"] = "ffffff";
$paramssld["ht_view4_linkbutton_font_size"] = "14";
$paramssld["ht_view4_description_color"] = "555555";
$paramssld["ht_view4_description_font_size"] = "14";
$paramssld["ht_view4_show_description"] = "on";
$paramssld["ht_view4_title_font_size"] = "18";
$paramssld["ht_view4_title_font_color"] = "E74C3C";
$paramssld["ht_view4_element_border_width"] = "1";
$paramssld["ht_view4_element_border_color"] = "dedede";
$paramssld["ht_view4_element_background_color"] = "f9f9f9";
$paramssld["ht_view4_block_width"] = "275";
$paramssld["ht_view5_icons_style"] = "dark";
$paramssld["ht_view5_show_separator_lines"] = "on";
$paramssld["ht_view5_linkbutton_text"] = "View More";
$paramssld["ht_view5_show_linkbutton"] = "on";
$paramssld["ht_view5_linkbutton_background_hover_color"] = "0074a2";
$paramssld["ht_view5_linkbutton_background_color"] = "2ea2cd";
$paramssld["ht_view5_linkbutton_font_hover_color"] = "ffffff";
$paramssld["ht_view5_linkbutton_color"] = "ffffff";
$paramssld["ht_view5_linkbutton_font_size"] = "14";
$paramssld["ht_view5_description_color"] = "555555";
$paramssld["ht_view5_description_font_size"] = "14";
$paramssld["ht_view5_show_description"] = "on";
$paramssld["ht_view5_thumbs_width"] = "75";
$paramssld["ht_view5_thumbs_height"] = "75";
$paramssld["ht_view5_show_thumbs"] = "on";
$paramssld["ht_view5_title_font_size"] = "16";
$paramssld["ht_view5_title_font_color"] = "0074a2";
$paramssld["ht_view5_main_image_width"] = "275";
$paramssld["ht_view5_slider_tabs_font_color"] = "d9d99";
$paramssld["ht_view5_slider_tabs_background_color"] = "555555";
$paramssld["ht_view5_slider_background_color"] = "f9f9f9";
$paramssld["ht_view6_title_font_size"] = "16";
$paramssld["ht_view6_title_font_color"] = "0074A2";
$paramssld["ht_view6_title_font_hover_color"] = "2EA2CD";
$paramssld["ht_view6_title_background_color"] = "000000";
$paramssld["ht_view6_title_background_transparency"] = "80";
$paramssld["ht_view6_border_radius"] = "3";
$paramssld["ht_view6_border_width"] = "0";
$paramssld["ht_view6_border_color"] = "eeeeee";
$paramssld["ht_view6_width"] = "275";
$paramssld["light_box_size"] = "17";
$paramssld["light_box_width"] = "500";
$paramssld["light_box_transition"] = "elastic";
$paramssld["light_box_speed"] = "800";
$paramssld["light_box_href"] = "False";
$paramssld["light_box_title"] = "false";
$paramssld["light_box_scalephotos"] = "true";
$paramssld["light_box_rel"] = "false";
$paramssld["light_box_scrolling"] = "false";
$paramssld["light_box_opacity"] = "20";
$paramssld["light_box_open"] = "false";
$paramssld["light_box_overlayclose"] = "true";
$paramssld["light_box_esckey"] = "false";
$paramssld["light_box_arrowkey"] = "false";
$paramssld["light_box_loop"] = "true";
$paramssld["light_box_data"] = "false";
$paramssld["light_box_classname"] = "false";
$paramssld["light_box_fadeout"] = "300";
$paramssld["light_box_closebutton"] = "true";
$paramssld["light_box_current"] = "image";
$paramssld["light_box_previous"] = "previous";
$paramssld["light_box_next"] = "next";
$paramssld["light_box_close"] = "close";
$paramssld["light_box_iframe"] = "false";
$paramssld["light_box_inline"] = "false";
$paramssld["light_box_html"] = "false";
$paramssld["light_box_photo"] = "false";
$paramssld["light_box_height"] = "500";
$paramssld["light_box_innerwidth"] = "false";
$paramssld["light_box_innerheight"] = "false";
$paramssld["light_box_initialwidth"] = "300";
$paramssld["light_box_initialheight"] = "100";
$paramssld["light_box_maxwidth"] = "768";
$paramssld["light_box_maxheight"] = "500";
$paramssld["light_box_slideshow"] = "false";
$paramssld["light_box_slideshowspeed"] = "2500";
$paramssld["light_box_slideshowauto"] = "true";
$paramssld["light_box_slideshowstart"] = "start slideshow";
$paramssld["light_box_slideshowstop"] = "stop slideshow";
$paramssld["light_box_fixed"] = "true";
$paramssld["light_box_top"] = "false";
$paramssld["light_box_bottom"] = "false";
$paramssld["light_box_left"] = "false";
$paramssld["light_box_right"] = "false";
$paramssld["light_box_reposition"] = "false";
$paramssld["light_box_retinaimage"] = "true";
$paramssld["light_box_retinaurl"] = "false";
$paramssld["light_box_retinasuffix"] = "@2x.$1";
$paramssld["light_box_returnfocus"] = "true";
$paramssld["light_box_trapfocus"] = "true";
$paramssld["light_box_fastiframe"] = "true";
$paramssld["light_box_preloading"] = "true";
$paramssld["slider_title_position"] = "5";
$paramssld["light_box_style"] = "1";
$paramssld["light_box_size_fix"] = "false";
$paramssld["ht_view0_show_sorting"] = "on";
$paramssld["ht_view0_sortbutton_font_size"] = "14";
$paramssld["ht_view0_sortbutton_font_color"] = "555555";
$paramssld["ht_view0_sortbutton_hover_font_color"] = "ffffff";
$paramssld["ht_view0_sortbutton_background_color"] = "F7F7F7";
$paramssld["ht_view0_sortbutton_hover_background_color"] = "FF3845";
$paramssld["ht_view0_sortbutton_border_radius"] = "0";
$paramssld["ht_view0_sortbutton_border_padding"] = "3";
$paramssld["ht_view0_sorting_float"] = "top";
$paramssld["ht_view0_show_filtering"] = "on";
$paramssld["ht_view0_filterbutton_font_size"] = "14";
$paramssld["ht_view0_filterbutton_font_color"] = "555555";
$paramssld["ht_view0_filterbutton_background_color"] = "F7F7F7";
$paramssld["ht_view0_filterbutton_hover_font_color"] = "ffffff";
$paramssld["ht_view0_filterbutton_hover_background_color"] = "FF3845";
$paramssld["ht_view0_filterbutton_border_radius"] = "0";
$paramssld["ht_view0_filterbutton_border_padding"] = "3";
$paramssld["ht_view0_filtering_float"] = "left";
$paramssld["ht_view1_show_sorting"] = "on";
$paramssld["ht_view1_sortbutton_font_size"] = "14";
$paramssld["ht_view1_sortbutton_font_color"] = "555555";
$paramssld["ht_view1_sortbutton_hover_font_color"] = "ffffff";
$paramssld["ht_view1_sortbutton_background_color"] = "F7F7F7";
$paramssld["ht_view1_sortbutton_hover_background_color"] = "FF3845";
$paramssld["ht_view1_sortbutton_border_radius"] = "0";
$paramssld["ht_view1_sortbutton_border_padding"] = "3";
$paramssld["ht_view1_sorting_float"] = "top";
$paramssld["ht_view1_show_filtering"] = "on";
$paramssld["ht_view1_filterbutton_font_size"] = "14";
$paramssld["ht_view1_filterbutton_font_color"] = "555555";
$paramssld["ht_view1_filterbutton_background_color"] = "F7F7F7";
$paramssld["ht_view1_filterbutton_hover_font_color"] = "ffffff";
$paramssld["ht_view1_filterbutton_hover_background_color"] = "FF3845";
$paramssld["ht_view1_filterbutton_border_radius"] = "0";
$paramssld["ht_view1_filterbutton_border_padding"] = "3";
$paramssld["ht_view1_filtering_float"] = "left";
$paramssld["ht_view2_show_sorting"] = "on";
$paramssld["ht_view2_sortbutton_font_size"] = "14";
$paramssld["ht_view2_sortbutton_font_color"] = "555555";
$paramssld["ht_view2_sortbutton_hover_font_color"] = "ffffff";
$paramssld["ht_view2_sortbutton_background_color"] = "F7F7F7";
$paramssld["ht_view2_sortbutton_hover_background_color"] = "FF3845";
$paramssld["ht_view2_sortbutton_border_radius"] = "0";
$paramssld["ht_view2_sortbutton_border_padding"] = "3";
$paramssld["ht_view2_sorting_float"] = "top";
$paramssld["ht_view2_show_filtering"] = "on";
$paramssld["ht_view2_filterbutton_font_size"] = "14";
$paramssld["ht_view2_filterbutton_font_color"] = "555555";
$paramssld["ht_view2_filterbutton_background_color"] = "F7F7F7";
$paramssld["ht_view2_filterbutton_hover_font_color"] = "ffffff";
$paramssld["ht_view2_filterbutton_hover_background_color"] = "FF3845";
$paramssld["ht_view2_filterbutton_border_radius"] = "0";
$paramssld["ht_view2_filterbutton_border_padding"] = "3";
$paramssld["ht_view2_filtering_float"] = "left";
$paramssld["ht_view3_show_sorting"] = "on";
$paramssld["ht_view3_sortbutton_font_size"] = "14";
$paramssld["ht_view3_sortbutton_font_color"] = "555555";
$paramssld["ht_view3_sortbutton_hover_font_color"] = "ffffff";
$paramssld["ht_view3_sortbutton_background_color"] = "F7F7F7";
$paramssld["ht_view3_sortbutton_hover_background_color"] = "FF3845";
$paramssld["ht_view3_sortbutton_border_radius"] = "0";
$paramssld["ht_view3_sortbutton_border_padding"] = "3";
$paramssld["ht_view3_sorting_float"] = "top";
$paramssld["ht_view3_show_filtering"] = "on";
$paramssld["ht_view3_filterbutton_font_size"] = "14";
$paramssld["ht_view3_filterbutton_font_color"] = "555555";
$paramssld["ht_view3_filterbutton_background_color"] = "F7F7F7";
$paramssld["ht_view3_filterbutton_hover_font_color"] = "ffffff";
$paramssld["ht_view3_filterbutton_hover_background_color"] = "FF3845";
$paramssld["ht_view3_filterbutton_border_radius"] = "0";
$paramssld["ht_view3_filterbutton_border_padding"] = "3";
$paramssld["ht_view3_filtering_float"] = "left";
$paramssld["ht_view4_show_sorting"] = "on";
$paramssld["ht_view4_sortbutton_font_size"] = "14";
$paramssld["ht_view4_sortbutton_font_color"] = "555555";
$paramssld["ht_view4_sortbutton_hover_font_color"] = "ffffff";
$paramssld["ht_view4_sortbutton_background_color"] = "F7F7F7";
$paramssld["ht_view4_sortbutton_hover_background_color"] = "FF3845";
$paramssld["ht_view4_sortbutton_border_radius"] = "0";
$paramssld["ht_view4_sortbutton_border_padding"] = "3";
$paramssld["ht_view4_sorting_float"] = "top";
$paramssld["ht_view4_show_filtering"] = "on";
$paramssld["ht_view4_filterbutton_font_size"] = "14";
$paramssld["ht_view4_filterbutton_font_color"] = "555555";
$paramssld["ht_view4_filterbutton_background_color"] = "F7F7F7";
$paramssld["ht_view4_filterbutton_hover_font_color"] = "ffffff";
$paramssld["ht_view4_filterbutton_hover_background_color"] = "FF3845";
$paramssld["ht_view4_filterbutton_border_radius"] = "0";
$paramssld["ht_view4_filterbutton_border_padding"] = "3";
$paramssld["ht_view4_filtering_float"] = "left";
$paramssld["ht_view6_show_sorting"] = "on";
$paramssld["ht_view6_sortbutton_font_size"] = "14";
$paramssld["ht_view6_sortbutton_font_color"] = "555555";
$paramssld["ht_view6_sortbutton_hover_font_color"] = "ffffff";
$paramssld["ht_view6_sortbutton_background_color"] = "F7F7F7";
$paramssld["ht_view6_sortbutton_hover_background_color"] = "FF3845";
$paramssld["ht_view6_sortbutton_border_radius"] = "0";
$paramssld["ht_view6_sortbutton_border_padding"] = "3";
$paramssld["ht_view6_sorting_float"] = "top";
$paramssld["ht_view6_show_filtering"] = "on";
$paramssld["ht_view6_filterbutton_font_size"] = "14";
$paramssld["ht_view6_filterbutton_font_color"] = "555555";
$paramssld["ht_view6_filterbutton_background_color"] = "F7F7F7";
$paramssld["ht_view6_filterbutton_hover_font_color"] = "ffffff";
$paramssld["ht_view6_filterbutton_hover_background_color"] = "FF3845";
$paramssld["ht_view6_filterbutton_border_radius"] = "0";
$paramssld["ht_view6_filterbutton_border_padding"] = "3";
$paramssld["ht_view6_filtering_float"] = "left";
$paramssld["ht_view0_sorting_name_by_default"] = "Default";
$paramssld["ht_view0_sorting_name_by_id"] = "Date";
$paramssld["ht_view0_sorting_name_by_name"] = "Title";
$paramssld["ht_view0_sorting_name_by_random"] = "Random";
$paramssld["ht_view0_sorting_name_by_asc"] = "Asceding";
$paramssld["ht_view0_sorting_name_by_desc"] = "Desceding";
$paramssld["ht_view1_sorting_name_by_default"] = "Default";
$paramssld["ht_view1_sorting_name_by_id"] = "Date";
$paramssld["ht_view1_sorting_name_by_name"] = "Title";
$paramssld["ht_view1_sorting_name_by_random"] = "Random";
$paramssld["ht_view1_sorting_name_by_asc"] = "Asceding";
$paramssld["ht_view1_sorting_name_by_desc"] = "Desceding";
$paramssld["ht_view2_popup_full_width"] = "on";
$paramssld["ht_view2_sorting_name_by_default"] = "Default";
$paramssld["ht_view2_sorting_name_by_id"] = "Date";
$paramssld["ht_view2_sorting_name_by_name"] = "Title";
$paramssld["ht_view2_sorting_name_by_random"] = "Random";
$paramssld["ht_view2_sorting_name_by_asc"] = "Asceding";
$paramssld["ht_view2_sorting_name_by_desc"] = "Desceding";
$paramssld["ht_view3_sorting_name_by_default"] = "Default";
$paramssld["ht_view3_sorting_name_by_id"] = "Date";
$paramssld["ht_view3_sorting_name_by_name"] = "Title";
$paramssld["ht_view3_sorting_name_by_random"] = "Random";
$paramssld["ht_view3_sorting_name_by_asc"] = "Asceding";
$paramssld["ht_view3_sorting_name_by_desc"] = "Desceding";
$paramssld["ht_view4_sorting_name_by_default"] = "Default";
$paramssld["ht_view4_sorting_name_by_id"] = "Date";
$paramssld["ht_view4_sorting_name_by_name"] = "Title";
$paramssld["ht_view4_sorting_name_by_random"] = "Random";
$paramssld["ht_view4_sorting_name_by_asc"] = "Asceding";
$paramssld["ht_view4_sorting_name_by_desc"] = "Desceding";
$paramssld["ht_view5_sorting_name_by_default"] = "Default";
$paramssld["ht_view5_sorting_name_by_id"] = "Date";
$paramssld["ht_view5_sorting_name_by_name"] = "Title";
$paramssld["ht_view5_sorting_name_by_random"] = "Random";
$paramssld["ht_view5_sorting_name_by_asc"] = "Asceding";
$paramssld["ht_view5_sorting_name_by_desc"] = "Desceding";
$paramssld["ht_view6_sorting_name_by_default"] = "Default";
$paramssld["ht_view6_sorting_name_by_id"] = "Date";
$paramssld["ht_view6_sorting_name_by_name"] = "Title";
$paramssld["ht_view6_sorting_name_by_random"] = "Random";
$paramssld["ht_view6_sorting_name_by_asc"] = "Asceding";
$paramssld["ht_view6_sorting_name_by_desc"] = "Desceding";
$paramssld["ht_view0_togglebutton_style"] = "dark";
$paramssld["ht_view0_show_separator_lines"] = "on";
$paramssld["ht_view0_linkbutton_text"] = "View More";
$paramssld["ht_view0_show_linkbutton"] = "on";
$paramssld["ht_view0_linkbutton_background_hover_color"] = "df2e1b";
$paramssld["ht_view0_linkbutton_background_color"] = "e74c3c";
$paramssld["ht_view0_linkbutton_font_hover_color"] = "ffffff";
$paramssld["ht_view0_linkbutton_color"] = "ffffff";
$paramssld["ht_view0_linkbutton_font_size"] = "14";
$paramssld["ht_view0_description_color"] = "5b5b5b";
$paramssld["ht_view0_description_font_size"] = "14";
$paramssld["ht_view0_show_description"] = "on";
$paramssld["ht_view0_thumbs_width"] = "75";
$paramssld["ht_view0_thumbs_position"] = "before";
$paramssld["ht_view0_show_thumbs"] = "on";
$paramssld["ht_view0_title_font_size"] = "15";
$paramssld["ht_view0_title_font_color"] = "555555";
$paramssld["ht_view0_element_border_width"] = "1";
$paramssld["ht_view0_element_border_color"] = "D0D0D0";
$paramssld["ht_view0_element_background_color"] = "f7f7f7";
$paramssld["ht_view0_block_width"] = "275";
$paramssld["ht_view0_block_height"] = "160";
$paramssld["ht_view1_show_separator_lines"] = "on";
$paramssld["ht_view1_linkbutton_text"] = "View More";
$paramssld["ht_view1_show_linkbutton"] = "on";
$paramssld["ht_view1_linkbutton_background_hover_color"] = "df2e1b";
$paramssld["ht_view1_linkbutton_background_color"] = "e74c3c";
$paramssld["ht_view1_linkbutton_font_hover_color"] = "ffffff";
$paramssld["ht_view1_linkbutton_color"] = "ffffff";
$paramssld["ht_view1_linkbutton_font_size"] = "14";
$paramssld["ht_view1_description_color"] = "5b5b5b";
$paramssld["ht_view1_description_font_size"] = "14";
$paramssld["ht_view1_show_description"] = "on";
$paramssld["ht_view1_thumbs_width"] = "75";
$paramssld["ht_view1_thumbs_position"] = "before";
$paramssld["ht_view1_show_thumbs"] = "on";
$paramssld["ht_view1_title_font_size"] = "15";
$paramssld["ht_view1_title_font_color"] = "555555";
$paramssld["ht_view1_element_border_width"] = "1";
$paramssld["ht_view1_element_border_color"] = "D0D0D0";
$paramssld["ht_view1_element_background_color"] = "f7f7f7";
$paramssld["ht_view1_block_width"] = "275";
$paramssld["ht_view2_element_linkbutton_text"] = "View More";
$paramssld["ht_view2_element_show_linkbutton"] = "on";
$paramssld["ht_view2_element_linkbutton_color"] = "ffffff";
$paramssld["ht_view2_element_linkbutton_font_size"] = "14";
$paramssld["ht_view2_element_linkbutton_background_color"] = "2ea2cd";
$paramssld["ht_view2_show_popup_linkbutton"] = "on";
$paramssld["ht_view2_popup_linkbutton_text"] = "View More";
$paramssld["ht_view2_popup_linkbutton_background_hover_color"] = "0074a2";
$paramssld["ht_view2_popup_linkbutton_background_color"] = "2ea2cd";
$paramssld["ht_view2_popup_linkbutton_font_hover_color"] = "ffffff";
$paramssld["ht_view2_popup_linkbutton_color"] = "ffffff";
$paramssld["ht_view2_popup_linkbutton_font_size"] = "14";
$paramssld["ht_view2_description_color"] = "222222";
$paramssld["ht_view2_description_font_size"] = "14";
$paramssld["ht_view2_show_description"] = "on";
$paramssld["ht_view2_thumbs_width"] = "75";
$paramssld["ht_view2_thumbs_height"] = "75";
$paramssld["ht_view2_thumbs_position"] = "before";
$paramssld["ht_view2_show_thumbs"] = "on";
$paramssld["ht_view2_popup_background_color"] = "FFFFFF";
$paramssld["ht_view2_popup_overlay_color"] = "000000";
$paramssld["ht_view2_popup_overlay_transparency_color"] = "70";
$paramssld["ht_view2_popup_closebutton_style"] = "dark";
$paramssld["ht_view2_show_separator_lines"] = "on";
$paramssld["ht_view2_show_popup_title"] = "on";
$paramssld["ht_view2_element_title_font_size"] = "18";
$paramssld["ht_view2_element_title_font_color"] = "222222";
$paramssld["ht_view2_popup_title_font_size"] = "18";
$paramssld["ht_view2_popup_title_font_color"] = "222222";
$paramssld["ht_view2_element_overlay_color"] = "FFFFFF";
$paramssld["ht_view2_element_overlay_transparency"] = "70";
$paramssld["ht_view2_zoombutton_style"] = "light";
$paramssld["ht_view2_element_border_width"] = "1";
$paramssld["ht_view2_element_border_color"] = "dedede";
$paramssld["ht_view2_element_background_color"] = "f9f9f9";
$paramssld["ht_view2_element_width"] = "275";
$paramssld["ht_view2_element_height"] = "160";
$paramssld["ht_view3_show_separator_lines"] = "on";
$paramssld["ht_view3_linkbutton_text"] = "View More";
$paramssld["ht_view3_show_linkbutton"] = "on";
$paramssld["ht_view3_linkbutton_background_hover_color"] = "0074a2";
$paramssld["ht_view3_linkbutton_background_color"] = "2ea2cd";
$paramssld["ht_view3_linkbutton_font_hover_color"] = "ffffff";
$paramssld["ht_view3_linkbutton_color"] = "ffffff";
$paramssld["ht_view3_linkbutton_font_size"] = "14";
$paramssld["ht_view3_description_color"] = "555555";
$paramssld["ht_view3_description_font_size"] = "14";
$paramssld["ht_view3_show_description"] = "on";
$paramssld["ht_view3_thumbs_width"] = "75";
$paramssld["ht_view3_thumbs_height"] = "75";
$paramssld["ht_view3_show_thumbs"] = "on";
$paramssld["ht_view3_title_font_size"] = "18";
$paramssld["ht_view3_title_font_color"] = "0074a2";
$paramssld["ht_view3_mainimage_width"] = "240";
$paramssld["ht_view3_element_border_width"] = "1";
$paramssld["ht_view3_element_border_color"] = "dedede";
$paramssld["ht_view3_element_background_color"] = "f9f9f9";
$paramssld["ht_view4_togglebutton_style"] = "dark";
$paramssld["ht_view4_show_separator_lines"] = "on";
$paramssld["ht_view4_linkbutton_text"] = "View More";
$paramssld["ht_view4_show_linkbutton"] = "on";
$paramssld["ht_view4_linkbutton_background_hover_color"] = "df2e1b";
$paramssld["ht_view4_linkbutton_background_color"] = "e74c3c";
$paramssld["ht_view4_linkbutton_font_hover_color"] = "ffffff";
$paramssld["ht_view4_linkbutton_color"] = "ffffff";
$paramssld["ht_view4_linkbutton_font_size"] = "14";
$paramssld["ht_view4_description_color"] = "555555";
$paramssld["ht_view4_description_font_size"] = "14";
$paramssld["ht_view4_show_description"] = "on";
$paramssld["ht_view4_title_font_size"] = "18";
$paramssld["ht_view4_title_font_color"] = "E74C3C";
$paramssld["ht_view4_element_border_width"] = "1";
$paramssld["ht_view4_element_border_color"] = "dedede";
$paramssld["ht_view4_element_background_color"] = "f9f9f9";
$paramssld["ht_view4_block_width"] = "275";
$paramssld["ht_view5_icons_style"] = "dark";
$paramssld["ht_view5_show_separator_lines"] = "on";
$paramssld["ht_view5_linkbutton_text"] = "View More";
$paramssld["ht_view5_show_linkbutton"] = "on";
$paramssld["ht_view5_linkbutton_background_hover_color"] = "0074a2";
$paramssld["ht_view5_linkbutton_background_color"] = "2ea2cd";
$paramssld["ht_view5_linkbutton_font_hover_color"] = "ffffff";
$paramssld["ht_view5_linkbutton_color"] = "ffffff";
$paramssld["ht_view5_linkbutton_font_size"] = "14";
$paramssld["ht_view5_description_color"] = "555555";
$paramssld["ht_view5_description_font_size"] = "14";
$paramssld["ht_view5_show_description"] = "on";
$paramssld["ht_view5_thumbs_width"] = "75";
$paramssld["ht_view5_thumbs_height"] = "75";
$paramssld["ht_view5_show_thumbs"] = "on";
$paramssld["ht_view5_title_font_size"] = "16";
$paramssld["ht_view5_title_font_color"] = "0074a2";
$paramssld["ht_view5_main_image_width"] = "275";
$paramssld["ht_view5_slider_tabs_font_color"] = "d9d99";
$paramssld["ht_view5_slider_tabs_background_color"] = "555555";
$paramssld["ht_view5_slider_background_color"] = "f9f9f9";
$paramssld["ht_view6_title_font_size"] = "16";
$paramssld["ht_view6_title_font_color"] = "0074A2";
$paramssld["ht_view6_title_font_hover_color"] = "2EA2CD";
$paramssld["ht_view6_title_background_color"] = "000000";
$paramssld["ht_view6_title_background_transparency"] = "80";
$paramssld["ht_view6_border_radius"] = "3";
$paramssld["ht_view6_border_width"] = "0";
$paramssld["ht_view6_border_color"] = "eeeeee";
$paramssld["ht_view6_width"] = "275";
$paramssld["light_box_size"] = "17";
$paramssld["light_box_width"] = "500";
$paramssld["light_box_transition"] = "elastic";
$paramssld["light_box_speed"] = "800";
$paramssld["light_box_href"] = "False";
$paramssld["light_box_title"] = "false";
$paramssld["light_box_scalephotos"] = "true";
$paramssld["light_box_rel"] = "false";
$paramssld["light_box_scrolling"] = "false";
$paramssld["light_box_opacity"] = "20";
$paramssld["light_box_open"] = "false";
$paramssld["light_box_overlayclose"] = "true";
$paramssld["light_box_esckey"] = "false";
$paramssld["light_box_arrowkey"] = "false";
$paramssld["light_box_loop"] = "true";
$paramssld["light_box_data"] = "false";
$paramssld["light_box_classname"] = "false";
$paramssld["light_box_fadeout"] = "300";
$paramssld["light_box_closebutton"] = "true";
$paramssld["light_box_current"] = "image";
$paramssld["light_box_previous"] = "previous";
$paramssld["light_box_next"] = "next";
$paramssld["light_box_close"] = "close";
$paramssld["light_box_iframe"] = "false";
$paramssld["light_box_inline"] = "false";
$paramssld["light_box_html"] = "false";
$paramssld["light_box_photo"] = "false";
$paramssld["light_box_height"] = "500";
$paramssld["light_box_innerwidth"] = "false";
$paramssld["light_box_innerheight"] = "false";
$paramssld["light_box_initialwidth"] = "300";
$paramssld["light_box_initialheight"] = "100";
$paramssld["light_box_maxwidth"] = "768";
$paramssld["light_box_maxheight"] = "500";
$paramssld["light_box_slideshow"] = "false";
$paramssld["light_box_slideshowspeed"] = "2500";
$paramssld["light_box_slideshowauto"] = "true";
$paramssld["light_box_slideshowstart"] = "start slideshow";
$paramssld["light_box_slideshowstop"] = "stop slideshow";
$paramssld["light_box_fixed"] = "true";
$paramssld["light_box_top"] = "false";
$paramssld["light_box_bottom"] = "false";
$paramssld["light_box_left"] = "false";
$paramssld["light_box_right"] = "false";
$paramssld["light_box_reposition"] = "false";
$paramssld["light_box_retinaimage"] = "true";
$paramssld["light_box_retinaurl"] = "false";
$paramssld["light_box_retinasuffix"] = "@2x.$1";
$paramssld["light_box_returnfocus"] = "true";
$paramssld["light_box_trapfocus"] = "true";
$paramssld["light_box_fastiframe"] = "true";
$paramssld["light_box_preloading"] = "true";
$paramssld["slider_title_position"] = "5";
$paramssld["light_box_style"] = "1";
$paramssld["light_box_size_fix"] = "false";
$paramssld["ht_view0_show_sorting"] = "on";
$paramssld["ht_view0_sortbutton_font_size"] = "14";
$paramssld["ht_view0_sortbutton_font_color"] = "555555";
$paramssld["ht_view0_sortbutton_hover_font_color"] = "ffffff";
$paramssld["ht_view0_sortbutton_background_color"] = "F7F7F7";
$paramssld["ht_view0_sortbutton_hover_background_color"] = "FF3845";
$paramssld["ht_view0_sortbutton_border_radius"] = "0";
$paramssld["ht_view0_sortbutton_border_padding"] = "3";
$paramssld["ht_view0_sorting_float"] = "top";
$paramssld["ht_view0_show_filtering"] = "on";
$paramssld["ht_view0_filterbutton_font_size"] = "14";
$paramssld["ht_view0_filterbutton_font_color"] = "555555";
$paramssld["ht_view0_filterbutton_background_color"] = "F7F7F7";
$paramssld["ht_view0_filterbutton_hover_font_color"] = "ffffff";
$paramssld["ht_view0_filterbutton_hover_background_color"] = "FF3845";
$paramssld["ht_view0_filterbutton_border_radius"] = "0";
$paramssld["ht_view0_filterbutton_border_padding"] = "3";
$paramssld["ht_view0_filtering_float"] = "left";
$paramssld["ht_view1_show_sorting"] = "on";
$paramssld["ht_view1_sortbutton_font_size"] = "14";
$paramssld["ht_view1_sortbutton_font_color"] = "555555";
$paramssld["ht_view1_sortbutton_hover_font_color"] = "ffffff";
$paramssld["ht_view1_sortbutton_background_color"] = "F7F7F7";
$paramssld["ht_view1_sortbutton_hover_background_color"] = "FF3845";
$paramssld["ht_view1_sortbutton_border_radius"] = "0";
$paramssld["ht_view1_sortbutton_border_padding"] = "3";
$paramssld["ht_view1_sorting_float"] = "top";
$paramssld["ht_view1_show_filtering"] = "on";
$paramssld["ht_view1_filterbutton_font_size"] = "14";
$paramssld["ht_view1_filterbutton_font_color"] = "555555";
$paramssld["ht_view1_filterbutton_background_color"] = "F7F7F7";
$paramssld["ht_view1_filterbutton_hover_font_color"] = "ffffff";
$paramssld["ht_view1_filterbutton_hover_background_color"] = "FF3845";
$paramssld["ht_view1_filterbutton_border_radius"] = "0";
$paramssld["ht_view1_filterbutton_border_padding"] = "3";
$paramssld["ht_view1_filtering_float"] = "left";
$paramssld["ht_view2_show_sorting"] = "on";
$paramssld["ht_view2_sortbutton_font_size"] = "14";
$paramssld["ht_view2_sortbutton_font_color"] = "555555";
$paramssld["ht_view2_sortbutton_hover_font_color"] = "ffffff";
$paramssld["ht_view2_sortbutton_background_color"] = "F7F7F7";
$paramssld["ht_view2_sortbutton_hover_background_color"] = "FF3845";
$paramssld["ht_view2_sortbutton_border_radius"] = "0";
$paramssld["ht_view2_sortbutton_border_padding"] = "3";
$paramssld["ht_view2_sorting_float"] = "top";
$paramssld["ht_view2_show_filtering"] = "on";
$paramssld["ht_view2_filterbutton_font_size"] = "14";
$paramssld["ht_view2_filterbutton_font_color"] = "555555";
$paramssld["ht_view2_filterbutton_background_color"] = "F7F7F7";
$paramssld["ht_view2_filterbutton_hover_font_color"] = "ffffff";
$paramssld["ht_view2_filterbutton_hover_background_color"] = "FF3845";
$paramssld["ht_view2_filterbutton_border_radius"] = "0";
$paramssld["ht_view2_filterbutton_border_padding"] = "3";
$paramssld["ht_view2_filtering_float"] = "left";
$paramssld["ht_view3_show_sorting"] = "on";
$paramssld["ht_view3_sortbutton_font_size"] = "14";
$paramssld["ht_view3_sortbutton_font_color"] = "555555";
$paramssld["ht_view3_sortbutton_hover_font_color"] = "ffffff";
$paramssld["ht_view3_sortbutton_background_color"] = "F7F7F7";
$paramssld["ht_view3_sortbutton_hover_background_color"] = "FF3845";
$paramssld["ht_view3_sortbutton_border_radius"] = "0";
$paramssld["ht_view3_sortbutton_border_padding"] = "3";
$paramssld["ht_view3_sorting_float"] = "top";
$paramssld["ht_view3_show_filtering"] = "on";
$paramssld["ht_view3_filterbutton_font_size"] = "14";
$paramssld["ht_view3_filterbutton_font_color"] = "555555";
$paramssld["ht_view3_filterbutton_background_color"] = "F7F7F7";
$paramssld["ht_view3_filterbutton_hover_font_color"] = "ffffff";
$paramssld["ht_view3_filterbutton_hover_background_color"] = "FF3845";
$paramssld["ht_view3_filterbutton_border_radius"] = "0";
$paramssld["ht_view3_filterbutton_border_padding"] = "3";
$paramssld["ht_view3_filtering_float"] = "left";
$paramssld["ht_view4_show_sorting"] = "on";
$paramssld["ht_view4_sortbutton_font_size"] = "14";
$paramssld["ht_view4_sortbutton_font_color"] = "555555";
$paramssld["ht_view4_sortbutton_hover_font_color"] = "ffffff";
$paramssld["ht_view4_sortbutton_background_color"] = "F7F7F7";
$paramssld["ht_view4_sortbutton_hover_background_color"] = "FF3845";
$paramssld["ht_view4_sortbutton_border_radius"] = "0";
$paramssld["ht_view4_sortbutton_border_padding"] = "3";
$paramssld["ht_view4_sorting_float"] = "top";
$paramssld["ht_view4_show_filtering"] = "on";
$paramssld["ht_view4_filterbutton_font_size"] = "14";
$paramssld["ht_view4_filterbutton_font_color"] = "555555";
$paramssld["ht_view4_filterbutton_background_color"] = "F7F7F7";
$paramssld["ht_view4_filterbutton_hover_font_color"] = "ffffff";
$paramssld["ht_view4_filterbutton_hover_background_color"] = "FF3845";
$paramssld["ht_view4_filterbutton_border_radius"] = "0";
$paramssld["ht_view4_filterbutton_border_padding"] = "3";
$paramssld["ht_view4_filtering_float"] = "left";
$paramssld["ht_view6_show_sorting"] = "on";
$paramssld["ht_view6_sortbutton_font_size"] = "14";
$paramssld["ht_view6_sortbutton_font_color"] = "555555";
$paramssld["ht_view6_sortbutton_hover_font_color"] = "ffffff";
$paramssld["ht_view6_sortbutton_background_color"] = "F7F7F7";
$paramssld["ht_view6_sortbutton_hover_background_color"] = "FF3845";
$paramssld["ht_view6_sortbutton_border_radius"] = "0";
$paramssld["ht_view6_sortbutton_border_padding"] = "3";
$paramssld["ht_view6_sorting_float"] = "top";
$paramssld["ht_view6_show_filtering"] = "on";
$paramssld["ht_view6_filterbutton_font_size"] = "14";
$paramssld["ht_view6_filterbutton_font_color"] = "555555";
$paramssld["ht_view6_filterbutton_background_color"] = "F7F7F7";
$paramssld["ht_view6_filterbutton_hover_font_color"] = "ffffff";
$paramssld["ht_view6_filterbutton_hover_background_color"] = "FF3845";
$paramssld["ht_view6_filterbutton_border_radius"] = "0";
$paramssld["ht_view6_filterbutton_border_padding"] = "3";
$paramssld["ht_view6_filtering_float"] = "left";
$paramssld["ht_view0_sorting_name_by_default"] = "Default";
$paramssld["ht_view0_sorting_name_by_id"] = "Date";
$paramssld["ht_view0_sorting_name_by_name"] = "Title";
$paramssld["ht_view0_sorting_name_by_random"] = "Random";
$paramssld["ht_view0_sorting_name_by_asc"] = "Asceding";
$paramssld["ht_view0_sorting_name_by_desc"] = "Desceding";
$paramssld["ht_view1_sorting_name_by_default"] = "Default";
$paramssld["ht_view1_sorting_name_by_id"] = "Date";
$paramssld["ht_view1_sorting_name_by_name"] = "Title";
$paramssld["ht_view1_sorting_name_by_random"] = "Random";
$paramssld["ht_view1_sorting_name_by_asc"] = "Asceding";
$paramssld["ht_view1_sorting_name_by_desc"] = "Desceding";
$paramssld["ht_view2_popup_full_width"] = "on";
$paramssld["ht_view2_sorting_name_by_default"] = "Default";
$paramssld["ht_view2_sorting_name_by_id"] = "Date";
$paramssld["ht_view2_sorting_name_by_name"] = "Title";
$paramssld["ht_view2_sorting_name_by_random"] = "Random";
$paramssld["ht_view2_sorting_name_by_asc"] = "Asceding";
$paramssld["ht_view2_sorting_name_by_desc"] = "Desceding";
$paramssld["ht_view3_sorting_name_by_default"] = "Default";
$paramssld["ht_view3_sorting_name_by_id"] = "Date";
$paramssld["ht_view3_sorting_name_by_name"] = "Title";
$paramssld["ht_view3_sorting_name_by_random"] = "Random";
$paramssld["ht_view3_sorting_name_by_asc"] = "Asceding";
$paramssld["ht_view3_sorting_name_by_desc"] = "Desceding";
$paramssld["ht_view4_sorting_name_by_default"] = "Default";
$paramssld["ht_view4_sorting_name_by_id"] = "Date";
$paramssld["ht_view4_sorting_name_by_name"] = "Title";
$paramssld["ht_view4_sorting_name_by_random"] = "Random";
$paramssld["ht_view4_sorting_name_by_asc"] = "Asceding";
$paramssld["ht_view4_sorting_name_by_desc"] = "Desceding";
$paramssld["ht_view5_sorting_name_by_default"] = "Default";
$paramssld["ht_view5_sorting_name_by_id"] = "Date";
$paramssld["ht_view5_sorting_name_by_name"] = "Title";
$paramssld["ht_view5_sorting_name_by_random"] = "Random";
$paramssld["ht_view5_sorting_name_by_asc"] = "Asceding";
$paramssld["ht_view5_sorting_name_by_desc"] = "Desceding";
$paramssld["ht_view6_sorting_name_by_default"] = "Default";
$paramssld["ht_view6_sorting_name_by_id"] = "Date";
$paramssld["ht_view6_sorting_name_by_name"] = "Title";
$paramssld["ht_view6_sorting_name_by_random"] = "Random";
$paramssld["ht_view6_sorting_name_by_asc"] = "Asceding";
$paramssld["ht_view6_sorting_name_by_desc"] = "Desceding";
$paramssld["ht_view0_cat_all"] = "All";
$paramssld["ht_view1_cat_all"] = "All";
$paramssld["ht_view2_cat_all"] = "All";
$paramssld["ht_view3_cat_all"] = "All";
$paramssld["ht_view4_cat_all"] = "All";
$paramssld["ht_view6_cat_all"] = "All";
				
				/***<optimize_images>***/
		$image_prefix = "_huge_it_small_portfolio";
	if(!function_exists('get_huge_image')) {
		function get_huge_image($image_url,$img_prefix) {
			//if(huge_it_copy_image_to_small($image_url,$image_prefix,$cropwidth)) {
				$pathinfo = pathinfo($image_url);
				$upload_dir = wp_upload_dir();
				$url_img_copy = $upload_dir["url"].'/'.$pathinfo["filename"].$img_prefix.'.'.$pathinfo["extension"];
				$img_abs_path = $url_img_copy;
				$img_abs_path= parse_url($url_img_copy, PHP_URL_PATH);
				$img_abs_path =  $_SERVER['DOCUMENT_ROOT'].$img_abs_path;
				if(file_exists($img_abs_path))
				return $url_img_copy; else
			//}
			 return $image_url;
		}
	}
			/***</optimize_images>***/
			
			/***<title display>***   free has not this option   /
		function huge_it_title_img_display($image_name,$title) {
			for($i = 0;$i < count($title);$i++) {
				$title_explode = explode("_-_-_",$title[$i]);
				if($title_explode[1] == $image_name) {
					echo $title_explode[0];  
				}
				else { 
					echo "" ;
				}
			}
		}
		 	/***</title display>***/
?>
<script>
	var lightbox_transition = '<?php echo $paramssld['light_box_transition'];?>';
	var lightbox_speed = <?php echo $paramssld['light_box_speed'];?>;
	var lightbox_fadeOut = <?php echo $paramssld['light_box_fadeout'];?>;
	var lightbox_title = <?php echo $paramssld['light_box_title'];?>;
	var lightbox_scalePhotos = <?php echo $paramssld['light_box_scalephotos'];?>;
	var lightbox_scrolling = <?php echo $paramssld['light_box_scrolling'];?>;
	var lightbox_opacity = <?php echo ($paramssld['light_box_opacity']/100)+0.001;?>;
	var lightbox_open = <?php echo $paramssld['light_box_open'];?>;
	var lightbox_returnFocus = <?php echo $paramssld['light_box_returnfocus'];?>;
	var lightbox_trapFocus = <?php echo $paramssld['light_box_trapfocus'];?>;
	var lightbox_fastIframe = <?php echo $paramssld['light_box_fastiframe'];?>;
	var lightbox_preloading = <?php echo $paramssld['light_box_preloading'];?>;
	var lightbox_overlayClose = <?php echo $paramssld['light_box_overlayclose'];?>;
	var lightbox_escKey = <?php echo $paramssld['light_box_esckey'];?>;
	var lightbox_arrowKey = <?php echo $paramssld['light_box_arrowkey'];?>;
	var lightbox_loop = <?php echo $paramssld['light_box_loop'];?>;
	var lightbox_closeButton = <?php echo $paramssld['light_box_closebutton'];?>;
	var lightbox_previous = "<?php echo $paramssld['light_box_previous'];?>";
	var lightbox_next = "<?php echo $paramssld['light_box_next'];?>";
	var lightbox_close = "<?php echo $paramssld['light_box_close'];?>";
	var lightbox_html = <?php echo $paramssld['light_box_html'];?>;
	var lightbox_photo = <?php echo $paramssld['light_box_photo'];?>;
	var lightbox_width = '<?php if($paramssld['light_box_size_fix'] == 'false'){ echo '';} else { echo $paramssld['light_box_width']; } ?>';
	var lightbox_height = '<?php if($paramssld['light_box_size_fix'] == 'false'){ echo '';} else { echo $paramssld['light_box_height']; } ?>';
	var lightbox_innerWidth = '<?php echo $paramssld['light_box_innerwidth'];?>';
	var lightbox_innerHeight = '<?php echo $paramssld['light_box_innerheight'];?>';
	var lightbox_initialWidth = '<?php echo $paramssld['light_box_initialwidth'];?>';
	var lightbox_initialHeight = '<?php echo $paramssld['light_box_initialheight'];?>';
        
	var maxwidth=jQuery(window).width();
        if(maxwidth><?php echo $paramssld['light_box_maxwidth'];?>){maxwidth=<?php echo $paramssld['light_box_maxwidth'];?>;}
        var lightbox_maxWidth = <?php if($paramssld['light_box_size_fix'] == 'true'){ echo '"100%"';} else { echo 'maxwidth'; } ?>;
        var lightbox_maxHeight = <?php if($paramssld['light_box_size_fix'] == 'true'){ echo '"100%"';} else { echo $paramssld['light_box_maxheight']; } ?>;
        
	var lightbox_slideshow = <?php echo $paramssld['light_box_slideshow'];?>;
	var lightbox_slideshowSpeed = <?php echo $paramssld['light_box_slideshowspeed'];?>;
	var lightbox_slideshowAuto = <?php echo $paramssld['light_box_slideshowauto'];?>;
	var lightbox_slideshowStart = "<?php echo $paramssld['light_box_slideshowstart'];?>";
	var lightbox_slideshowStop = "<?php echo $paramssld['light_box_slideshowstop'];?>";
	var lightbox_fixed = <?php echo $paramssld['light_box_fixed'];?>;
	<?php
	$pos = $paramssld['slider_title_position'];
	switch($pos){ 
	case 1:
	?>
		var lightbox_top = '10%';
		var lightbox_bottom = false;
		var lightbox_left = '10%';
		var lightbox_right = false;
	<?php
	break;	
	case 1:
	?>
		var lightbox_top = '10%';
		var lightbox_bottom = false;
		var lightbox_left = '10%';
		var lightbox_right = false;
	<?php
	break;	
	case 2:
	?>
		var lightbox_top = '10%';
		var lightbox_bottom = false;
		var lightbox_left = false;
		var lightbox_right = false;
	<?php
	break;	
	case 3:
	?>
		var lightbox_top = '10%';
		var lightbox_bottom = false;
		var lightbox_left = false;
		var lightbox_right = '10%';
	<?php
	break;
	case 4:
	?>
		var lightbox_top = false;
		var lightbox_bottom = false;
		var lightbox_left = '10%';
		var lightbox_right = false;
	<?php
	break;	
	case 5:
	?>
		var lightbox_top = false;
		var lightbox_bottom = false;
		var lightbox_left = false;
		var lightbox_right = false;
	<?php
	break;	
	case 6:
	?>
		var lightbox_top = false;
		var lightbox_bottom = false;
		var lightbox_left = false;
		var lightbox_right = '10%';
	<?php
	break;	
	case 7:
	?>
		var lightbox_top = false;
		var lightbox_bottom = '10%';
		var lightbox_left = '10%';
		var lightbox_right = false;
	<?php
	break;	
	case 8:
	?>
		var lightbox_top = false;
		var lightbox_bottom = '10%';
		var lightbox_left = false;
		var lightbox_right = false;
	<?php
	break;	
	case 9:
	?>
		var lightbox_top = false;
		var lightbox_bottom = '10%';
		var lightbox_left = false;
		var lightbox_right = '10%';
	<?php
	break;	
	} ?>
	
	var lightbox_reposition = <?php echo $paramssld['light_box_reposition'];?>;
	var lightbox_retinaImage = <?php echo $paramssld['light_box_retinaimage'];?>;
	var lightbox_retinaUrl = <?php echo $paramssld['light_box_retinaurl'];?>;
	var lightbox_retinaSuffix = "<?php echo $paramssld['light_box_retinasuffix'];?>";
	
				jQuery(document).ready(function(){
				jQuery("#huge_it_portfolio_content_<?php echo $portfolioID; ?> a[href$='.jpg'], #huge_it_portfolio_content_<?php echo $portfolioID; ?> a[href$='.png'], #huge_it_portfolio_content_<?php echo $portfolioID; ?> a[href$='.gif']").addClass('group1');
			//	jQuery("#huge_it_portfolio_content_<?php echo $portfolioID; ?> a").addClass('group1');
				                         var group_count = 0;
										 var groups = <?php echo $portfolioID; ?>;
                                jQuery(".portelement_<?php echo $portfolioID; ?>").each(function(){
                                    group_count++;
                                });
                                for(var i = 1; i <= group_count; i++){
                                    jQuery(".portfolio-group" + i+"-"+groups).colorbox({rel:'portfolio-group' + i+"-"+groups});
                                }
									jQuery(".portfolio-lightbox-group"+groups).colorbox({rel:"portfolio-lightbox-group"+groups});
									jQuery(".portfolio-lightbox a[href$='.png'],.portfolio-lightbox a[href$='.jpg'],.portfolio-lightbox a[href$='.gif'],.portfolio-lightbox a[href$='.jpeg']").addClass("portfolio-lightbox-group");
											var groups = <?php echo $portfolioID; ?>;
											var group_count_slider = 0;					 
                                jQuery(".slider-content").each(function(){
                                    group_count_slider++;
                                });
								var group_count_slider_clone = 0;
								jQuery(".portfolio-group-slider"+i).colorbox({rel:'portfolio-group-slider'+i});
								//jQuery(".group1").colorbox({rel:'group1'});
								for(var i = 1; i <= group_count_slider; i++){                                    
													jQuery(".portfolio-group-slider_"+groups+"_"+i).colorbox({rel:'portfolio-group-slider_'+groups+"_"+i});
													jQuery("#main-slider_<?php echo $portfolioID; ?> .clone  a").removeClass();

								}
				jQuery(".youtube").colorbox({iframe:true, innerWidth:640, innerHeight:390});
				jQuery(".vimeo").colorbox({iframe:true, innerWidth:640, innerHeight:390});
				jQuery(".callbacks").colorbox({
					onOpen:function(){ alert('onOpen: colorbox is about to open'); },
					onLoad:function(){ alert('onLoad: colorbox has started to load the targeted content'); },
					onComplete:function(){ alert('onComplete: colorbox has displayed the loaded content'); },
					onCleanup:function(){ alert('onCleanup: colorbox has begun the close process'); },
					onClosed:function(){ alert('onClosed: colorbox has completely closed'); }
				});

				jQuery('.non-retina').colorbox({rel:'group5', transition:'none'})
				jQuery('.retina').colorbox({rel:'group5', transition:'none', retinaImage:true, retinaUrl:true});
				

				jQuery("#click").click(function(){ 
					jQuery('#click').css({"background-color":"#f00", "color":"#fff", "cursor":"inherit"}).text("Open this window again and this message will still be here.");
					return false;
				});
				jQuery("huge_it_portfolio_filters_<?php $portfolioID;?>")
				jQuery("#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul li a").click(function(){
					jQuery("#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul li").removeClass("active");
						jQuery(this).parent().addClass("active");
	
				});
			});
</script>
	<!--Huge IT portfolio START-->
	<?php include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		if ( !(is_plugin_active( 'lightbox/lightbox.php' ) )) { 
		?>
	<link href="<?php echo plugins_url('../style/colorbox-'.$paramssld['light_box_style'].'.css', __FILE__);?>" rel="stylesheet" type="text/css" />
	<?php } ?>
		
	<?php
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		if ( !(is_plugin_active( 'wp-lightbox-2/wp-lightbox-2.php' ) )) { ?>

	<?php } ?>
	
	
	
	

	<?php 
	$i = $portfolioeffect;
	switch ($i) {
	
	
	/////////////////////////////// VIEW 0 Toggle Up/Down Blocks /////////////////////////////////////////
    case 0:
	?>
<?php
    if($paramssld["ht_view0_sorting_float"] == "left" && $paramssld["ht_view0_filtering_float"] == "right" ||
       $paramssld["ht_view0_sorting_float"] == "right" && $paramssld["ht_view0_filtering_float"] == "left" ||
       $paramssld["ht_view0_sorting_float"] == $paramssld["ht_view0_filtering_float"])
       { $sorting_block_width ="20%"; $filtering_block_width ="20%"; $middle_with = "56%"; }
    else if($paramssld["ht_view0_sorting_float"] == "left" || $paramssld["ht_view0_sorting_float"] == "right" && $paramssld["ht_view0_filtering_float"] == "top")
       { $sorting_block_width ="30%"; $filtering_block_width ="100%"; $paramssld["ht_view0_filtering_float"] = "none"; $width_middle = "65%"; }
    else if($paramssld["ht_view0_filtering_float"] == "left" || $paramssld["ht_view0_filtering_float"] == "right" && $paramssld["ht_view0_sorting_float"] == "top")
       { $sorting_block_width ="100%"; $filtering_block_width ="30%"; $paramssld["ht_view0_sorting_float"] = "none"; $width_middle = "65%"; }
    if($paramssld["ht_view0_sorting_float"] == "top" && $paramssld["ht_view0_filtering_float"] == "top")
       { $sorting_block_width ="100%"; $filtering_block_width ="100%"; $left_to_top = "ok"; }
?>
<style type="text/css">
/***<add>***/
#huge_it_portfolio_content_<?php echo $portfolioID; ?> {
	display:none;
}
#huge_it_portfolio_content_<?php echo $portfolioID; ?> a{
    border:none;
}
.portelement_<?php echo $portfolioID; ?> .play-icon.youtube-icon  {
	background: url(<?php echo  plugins_url( '../images/play.youtube.png' , __FILE__ );?>) center center no-repeat;
	background-size: 30% 30%;
}
.portelement_<?php echo $portfolioID; ?> .play-icon.vimeo-icon  {
	background: url(<?php echo  plugins_url( '../images/play.vimeo.png' , __FILE__ );?>) center center no-repeat;
	background-size: 30% 30%;
}
.portelement_<?php echo $portfolioID; ?> .play-icon {
    position: absolute;
    top: 0px;
    left: 0px;
    width: 100%;
    height: 100%;
}
.portelement_<?php echo $portfolioID; ?> .dropdownable .play-icon {
	display: none;
}
.portelement_<?php echo $portfolioID; ?>  .add-H-relative {
	position: relative;
}		
/***</add>***/
.portelement_<?php echo $portfolioID; ?> {
	background:#<?php echo $paramssld['ht_view0_element_background_color']?>;
	max-width:<?php echo $paramssld['ht_view0_block_width']; ?>px !important;
	width:100%;
	margin: 5px;
	float: left;
	overflow: hidden;
	outline:none;
	border:<?php echo $paramssld['ht_view0_element_border_width']; ?>px solid #<?php echo $paramssld['ht_view0_element_border_color']; ?>;
}

.portelement_<?php echo $portfolioID; ?>.large,
.variable-sizes .portelement_<?php echo $portfolioID; ?>.large,
.variable-sizes .portelement_<?php echo $portfolioID; ?>.large.width2.height2 {
	max-width: <?php echo $paramssld['ht_view0_block_width']; ?>px;
	width: 100%;
	z-index: 10;
}

.default-block_<?php echo $portfolioID; ?> {
	position:relative;
	max-width:<?php echo $paramssld['ht_view0_block_width']-2*$paramssld['ht_view0_element_border_width'];?>px !important;
	width: 100%;
	height:<?php echo $paramssld['ht_view0_block_height']+45;?>px !important;
} 

.default-block_<?php echo $portfolioID; ?> .image-block_<?php echo $portfolioID; ?> {
	margin:0px;
	padding:0px;
	line-height:0px;
	border-bottom:1px solid #<?php echo $paramssld['ht_view0_element_border_color']; ?>;
}

.default-block_<?php echo $portfolioID; ?> img {
	margin:0px !important;
	padding:0px !important;
	max-width:none !important;
	max-width:<?php echo $paramssld['ht_view0_block_width']-2*$paramssld['ht_view0_element_border_width'];?>px !important;
	width: 100%;
	height:<?php echo $paramssld['ht_view0_block_height']; ?>px !important;
	border-radius:0px;
}

.default-block_<?php echo $portfolioID; ?> .title-block_<?php echo $portfolioID; ?> {
	position:relative;
	display:block;
	height:35px;
	padding:10px 0px 0px 0px;
	max-width:<?php echo $paramssld['ht_view0_block_width']; ?>px !important;
	width: 100%;
}

.default-block_<?php echo $portfolioID; ?> .title-block_<?php echo $portfolioID; ?> h3 {
	position:relative;
	margin:0px !important;
	padding:0px 0px 0px 5px !important;
	max-width:<?php echo $paramssld['ht_view0_block_width']-30; ?>px !important;
	width: 70%;
	text-overflow: ellipsis;
	overflow: hidden; 
	white-space:nowrap;
	font-weight:normal;
	color:#<?php echo $paramssld['ht_view0_title_font_color']; ?>;
	font-size:<?php echo $paramssld['ht_view0_title_font_size']; ?>px !important;
	line-height:<?php echo $paramssld['ht_view0_title_font_size']+4; ?>px !important;
}

.portelement_<?php echo $portfolioID; ?> .title-block_<?php echo $portfolioID; ?> .open-close-button {
	width:20px;
	height:20px;
	display:block;
	position:absolute;
	top:13px;
	right:2%;
	background:url('<?php echo  plugins_url( '../images/open-close.'.$paramssld['ht_view0_togglebutton_style'].'.png' , __FILE__ ); ?>') left top no-repeat;
	z-index:5;
	cursor:pointer;
	opacity:0.33;
}

 .portelement_<?php echo $portfolioID; ?>:hover .title-block_<?php echo $portfolioID; ?> .open-close-button {opacity:1;}

.portelement_<?php echo $portfolioID; ?>.large .open-close-button {
	background:url('<?php echo  plugins_url( '../images/open-close.'.$paramssld['ht_view0_togglebutton_style'].'.png' , __FILE__ ); ?>') left bottom no-repeat;
}

.wd-portfolio-panel_<?php echo $portfolioID; ?> {
	position: absolute;
	display:block;
	width:<?php echo $paramssld['ht_view0_block_width']-10; ?>px !important;
	margin:0px 5px 0px 5px;
	padding:0px;
	text-align:left;
	top:<?php echo $paramssld['ht_view0_block_height']+45; ?>px;  
	z-index:6; 
	height:200px;
}


.wd-portfolio-panel_<?php echo $portfolioID; ?> .description-block_<?php echo $portfolioID; ?>, .portelement_<?php echo $portfolioID; ?> div.right-block .description-block_<?php echo $portfolioID; ?> * {
	position:relative;
	clear:both;
}

.wd-portfolio-panel_<?php echo $portfolioID; ?> .description-block_<?php echo $portfolioID; ?> p,.wd-portfolio-panel_<?php echo $portfolioID; ?> .description-block_<?php echo $portfolioID; ?> * {	
	text-align:justify;
	/*font-weight:normal;*/
	font-size:<?php echo $paramssld['ht_view0_description_font_size']; ?>px;
	color:#<?php echo $paramssld['ht_view0_description_color']; ?>;
	margin:0px;
	padding:0px;
}



.wd-portfolio-panel_<?php echo $portfolioID; ?> .description-block_<?php echo $portfolioID; ?> h1,
.wd-portfolio-panel_<?php echo $portfolioID; ?> .description-block_<?php echo $portfolioID; ?> h2,
.wd-portfolio-panel_<?php echo $portfolioID; ?> .description-block_<?php echo $portfolioID; ?> h3,
.wd-portfolio-panel_<?php echo $portfolioID; ?> .description-block_<?php echo $portfolioID; ?> h4,
.wd-portfolio-panel_<?php echo $portfolioID; ?> .description-block_<?php echo $portfolioID; ?> h5,
.wd-portfolio-panel_<?php echo $portfolioID; ?> .description-block_<?php echo $portfolioID; ?> h6,
.wd-portfolio-panel_<?php echo $portfolioID; ?> .description-block_<?php echo $portfolioID; ?> p, 
.wd-portfolio-panel_<?php echo $portfolioID; ?> .description-block_<?php echo $portfolioID; ?> strong,
.wd-portfolio-panel_<?php echo $portfolioID; ?> .description-block_<?php echo $portfolioID; ?> span {
	padding:2px !important;
	margin:0px !important;
}

.wd-portfolio-panel_<?php echo $portfolioID; ?> .description-block_<?php echo $portfolioID; ?> ul,
.wd-portfolio-panel_<?php echo $portfolioID; ?> .description-block_<?php echo $portfolioID; ?> li {
	padding:2px 0px 2px 5px;
	margin:0px 0px 0px 8px;
}


.wd-portfolio-panel_<?php echo $portfolioID; ?> .thumbs-list_<?php echo $portfolioID; ?> {
	position:relative;
	clear:both;
	list-style:none;
	display:table;
	width:100%;
	padding:0px;
	margin:3px 0px 0px 0px;
	text-align:center;
}

.wd-portfolio-panel_<?php echo $portfolioID; ?> .thumbs-list_<?php echo $portfolioID; ?> li {
	display:inline-block;
	margin:0px 3px 0px 2px;
}

.wd-portfolio-panel_<?php echo $portfolioID; ?> .thumbs-list_<?php echo $portfolioID; ?> li a {
	display:block;
	width:<?php echo $paramssld['ht_view0_thumbs_width']; ?>px;
	height:<?php echo $paramssld['ht_view0_thumbs_width']; ?>px;
	opacity:0.7;
	display:table;
}

.wd-portfolio-panel_<?php echo $portfolioID; ?> .thumbs-list_<?php echo $portfolioID; ?> li a:hover {
	opacity:1;
}

.wd-portfolio-panel_<?php echo $portfolioID; ?> img {
	margin:0px !important;
	padding:0px !important;
	display:table-cell;
	vertical-align:middle;
	width:<?php echo $paramssld['ht_view0_thumbs_width']; ?>px !important;
	max-height:<?php echo $paramssld['ht_view0_thumbs_width']; ?>px !important;
	width:100%;
	height:100%;
}

.wd-portfolio-panel_<?php echo $portfolioID; ?> > div {
	position:relative;
	clear:both;
	padding-top:10px;
	margin-bottom:10px;
	<?php if($paramssld['ht_view0_show_separator_lines']=="on") {?>
		background:url('<?php echo  plugins_url( '../images/divider.line.png' , __FILE__ ); ?>') center top repeat-x;
	<?php } ?>
}

.wd-portfolio-panel_<?php echo $portfolioID; ?> .button-block {
	padding-top:10px;
	margin-bottom:10px;
	
}

.wd-portfolio-panel_<?php echo $portfolioID; ?> .button-block a, .wd-portfolio-panel_<?php echo $portfolioID; ?> .button-block a:link, .wd-portfolio-panel_<?php echo $portfolioID; ?> .button-block a:visited {
	padding:6px 12px;
	text-decoration:none;
	display:inline-block;
	font-size:<?php echo $paramssld['ht_view0_linkbutton_font_size']; ?>px;
	background:#<?php echo $paramssld['ht_view0_linkbutton_background_color']; ?>;
	color:#<?php echo $paramssld['ht_view0_linkbutton_color']; ?>;
}

.wd-portfolio-panel_<?php echo $portfolioID; ?> .button-block a:hover, .wd-portfolio-panel_<?php echo $portfolioID; ?> .button-block a:focus, .wd-portfolio-panel_<?php echo $portfolioID; ?> .button-block a:active {
	background:#<?php echo $paramssld['ht_view0_linkbutton_background_hover_color']; ?>;
	color:#<?php echo $paramssld['ht_view0_linkbutton_font_hover_color']; ?>;
	text-decoration:none;
}

#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_options_<?php echo $portfolioID; ?> {
	position: relative;
    <?php if ($paramssld["ht_view0_show_sorting"] == 'off')
    echo "display:none;";
    if($paramssld["ht_view0_filtering_float"] == 'left' && $paramssld["ht_view0_sorting_float"] == 'none') {  if($portfolioShowFiltering == "on") { echo "margin-left: 31%;"; } else { echo "margin-left: 1%;"; }   }
    else if($paramssld["ht_view0_filtering_float"] == 'right' && $paramssld["ht_view0_sorting_float"] == 'none' || ($sorting_block_width == '100%' && $filtering_block_width == "100%")) { echo "margin-left: 1%;"; } ?>
    overflow: hidden;
    margin-top: 5px;
    float: <?php echo $paramssld["ht_view0_sorting_float"]; ?>;
    width: <?php echo $sorting_block_width; ?>;
}

#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_options_<?php echo $portfolioID; ?> ul {
  margin: 0px !important;
  padding: 0px !important;
  list-style: none;
<?php if($paramssld["ht_view0_sorting_float"] == 'top') {
      echo "float:left;margin-left:1%;";
      } ?>
}

#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul {
  margin: 0px !important;
  padding: 0px !important;
  overflow: hidden;
  <?php if($paramssld["ht_view0_filtering_float"] == 'top') {
      echo "float:left;margin-left:1%;";
      } ?>
}

<?php if($paramssld["ht_view0_sorting_float"] == 'none') { ?>
            #huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_options_<?php echo $portfolioID; ?> ul {
                float: left;
            }
    <?php } ?>
            
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_options_<?php echo $portfolioID; ?> ul li {
    border-radius: <?php echo $paramssld["ht_view0_sortbutton_border_radius"];?>px;
    list-style-type: none;
    margin: 0px !important;
	padding: 0;
    <?php
        if($sorting_block_width == "100%" ) {
            echo "float:left !important;margin: 4px 8px 4px 0px !important;";
        }
        if($left_to_top == "ok")
        { echo "float:left !important;"; }
        if($paramssld["ht_view0_sorting_float"] == "left" || $paramssld["ht_view0_sorting_float"] == "right")
        { echo 'border-bottom: 1px solid #ccc;'; }
        else
        { echo 'border: 1px solid #ccc;'; }
    ?>
}

#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_options_<?php echo $portfolioID; ?> ul li a {
    background-color: #<?php echo $paramssld["ht_view0_sortbutton_background_color"];?> !important;
    font-size:<?php echo $paramssld["ht_view0_sortbutton_font_size"];?>px !important;
    color:#<?php echo $paramssld["ht_view0_sortbutton_font_color"];?> !important;
    text-decoration: none;
    cursor: pointer;
    margin: 0px !important;
    display: block;
    padding:3px;
}

/*#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_options_<?php echo $portfolioID; ?> ul li:hover {
    
}*/

#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_options_<?php echo $portfolioID; ?> ul li a:hover {
    background-color: #<?php echo $paramssld["ht_view0_sortbutton_hover_background_color"];?> !important;
    color:#<?php echo $paramssld["ht_view0_sortbutton_hover_font_color"];?> !important;
    cursor: pointer;
}

#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?> {
    margin-top: 5px;
    float: <?php echo $paramssld["ht_view0_filtering_float"]; ?>;
    width: <?php echo $filtering_block_width; ?>;
    <?php
        if ($paramssld["ht_view0_show_filtering"] == 'off') echo "display:none;";
        if($paramssld["ht_view0_filtering_float"] == 'none' && ($paramssld["ht_view0_sorting_float"] == 'left') ) {  if($portfolioShowSorting == 'on') { echo "margin-left: 31%;"; } else echo "margin-left: 1%"; } 
        if(($paramssld["ht_view0_filtering_float"] == 'none' && ($paramssld["ht_view0_sorting_float"] == 'right')) || ($sorting_block_width == '100%' && $filtering_block_width == "100%")) { echo "margin-left: 1%";}
    ?>
}

#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul li {
    list-style-type: none;
    <?php
        if($filtering_block_width == "100%") { echo "float:left !important;margin: 4px 8px 4px 0px !important;"; }
        if($left_to_top == "ok") { echo "float:left !important;"; }
        if($paramssld["ht_view0_filtering_float"] == "left" || $paramssld["ht_view0_filtering_float"] == "right")
        { echo 'border-bottom: 1px solid #ccc;'; }
        else echo "border: 1px solid #ccc;";
    ?>
}

#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul li a,
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul li a:link,
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul li a:visited {
    font-size:<?php echo $paramssld["ht_view0_filterbutton_font_size"];?>px !important;
    color:#<?php echo $paramssld["ht_view0_filterbutton_font_color"];?> !important;
    background-color: #<?php echo $paramssld["ht_view0_filterbutton_background_color"];?> !important;
    border-radius: <?php echo $paramssld["ht_view0_filterbutton_border_radius"];?>px;
    padding: 3px;
    display: block;
    text-decoration: none;
}

#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?>  ul li a:hover,
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?>  ul li a:focus,
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?>  ul li a:active {
    color:#<?php echo $paramssld["ht_view0_filterbutton_hover_font_color"];?> !important;
    background-color: #<?php echo $paramssld["ht_view0_filterbutton_hover_background_color"];?> !important;
    cursor: pointer;
}

#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul li.active a,
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul li.active a:link,
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul li.active a:visited,
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?>  ul li.active a:hover,
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?>  ul li.active a:focus,
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?>  ul li.active a:active {
    color:#<?php echo $paramssld["ht_view0_filterbutton_hover_font_color"];?> !important;
    background-color: #<?php echo $paramssld["ht_view0_filterbutton_hover_background_color"];?> !important;
    cursor: pointer;
}

#huge_it_portfolio_content_<?php echo $portfolioID; ?> section {
    position:relative;
    display:block;
}

#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_container_<?php echo $portfolioID; ?> {
<?php if($paramssld["ht_view0_sorting_float"] == "left" && $paramssld["ht_view0_filtering_float"] == "right" ||
         $paramssld["ht_view0_sorting_float"] == "right" && $paramssld["ht_view0_filtering_float"] == "left")
       { echo "margin: 0px auto;"; }
       if((($paramssld["ht_view0_filtering_float"] == "left" || $paramssld["ht_view0_filtering_float"] == "right" && $paramssld["ht_view0_sorting_float"] == "top") || ($paramssld["ht_view0_sorting_float"] == "left" || $paramssld["ht_view0_sorting_float"] == "right" && $paramssld["ht_view0_filting_float"] == "top")) && $portfolioShowFiltering == "on" && $portfolioShowSorting == "on")
       {
?>
        width: <?php echo $width_middle; ?> !important;
 <?php } ?>
}
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_options_<?php echo $portfolioID; ?> #sort-direction{
    position: static;
}
@media screen and (max-width: 768px) {
	
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul li a,
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul li a:link,
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul li a:visited {
		font-size: 2vw !important;
	}
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_options_<?php echo $portfolioID; ?> ul li a {
		font-size:2vw !important;
	}

}
@media screen and (max-width: 480px) {
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_options_<?php echo $portfolioID; ?> {
	float: left;
}
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_options_<?php echo $portfolioID; ?> #sort-by{
	float: left;
	width: 100% !important;
}
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_options_<?php echo $portfolioID; ?> #sort-direction{
    float: left;
    width: 100% !important;
    position: relative;
    padding-left: 31% !important;
	right: 31%;
}
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul li a,
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul li a:link,
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul li a:visited {
		font-size: 3vw !important;
	}
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_options_<?php echo $portfolioID; ?> ul li a {
		line-height: 3vw;
		font-size:3vw !important;
	}
}
@media screen and (max-width: 420px) {
	
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul li a,
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul li a:link,
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul li a:visited {
		font-size: 4vw !important;
	}
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_options_<?php echo $portfolioID; ?> ul li a {
		font-size:4vw !important;
	}
}
@media screen and (max-width: <?php echo $paramssld['ht_view0_block_width']+2*$paramssld['ht_view0_element_border_width']+40; ?>px) {
   .portelement_<?php echo $portfolioID; ?>  {
		width:98%;
		margin: 1% !important;
		float: left;
		overflow: hidden;
		outline:none;
		border:<?php echo $paramssld['ht_view0_element_border_width']; ?>px solid #<?php echo $paramssld['ht_view0_element_border_color']; ?>;
    }
	.wd-portfolio-panel_<?php echo $portfolioID; ?> {
		width: 100% !important;
	}
}


</style>


<section id="huge_it_portfolio_content_<?php echo $portfolioID; ?>">
      <?php if($portfolioShowSorting == "on")
        { ?>
          <div id="huge_it_portfolio_options_<?php echo $portfolioID; ?>" class="" >
            <ul id="sort-by" class="option-set clearfix" data-option-key="sortBy">
                <li><a href="#sortBy=original-order" data-option-value="original-order" class="selected" data><?php echo $paramssld["ht_view0_sorting_name_by_default"]; ?></a></li>
                <li><a href="#sortBy=id" data-option-value="id"><?php echo $paramssld["ht_view0_sorting_name_by_id"]; ?></a></li>
                <li><a href="#sortBy=symbol" data-option-value="symbol"><?php echo $paramssld["ht_view0_sorting_name_by_name"]; ?></a></li>
                <li id="shuffle"><a href='#shuffle'><?php echo $paramssld["ht_view0_sorting_name_by_random"]; ?></a></li>
            </ul>
              
            <ul id="sort-direction" class="option-set clearfix" data-option-key="sortAscending">
                <li><a href="#sortAscending=true" data-option-value="true" class="selected"><?php echo $paramssld["ht_view0_sorting_name_by_asc"]; ?></a></li>
                <li><a href="#sortAscending=false" data-option-value="false"><?php echo $paramssld["ht_view0_sorting_name_by_desc"]; ?></a></li>
            </ul>
          </div>
  <?php }
   if($portfolioShowFiltering == "on")
      { ?>
         <div id="huge_it_portfolio_filters_<?php echo $portfolioID; ?>" style>
            <ul>
                <li rel="*"><a><?php echo $paramssld["ht_view0_cat_all"];?></a></li>
                <?php
                $portfolioCats = explode(",", $portfolioCats);
                foreach ($portfolioCats as $portfolioCatsValue) {
                    if(!empty($portfolioCatsValue))
                    {
                ?>
                <li rel=".<?php echo str_replace(" ","_",$portfolioCatsValue); ?>"><a><?php echo str_replace("_"," ",$portfolioCatsValue); ?></a></li>
                    <?php
                    }
                }
                ?>
            </ul>
        </div>
<?php } ?>
         <div id="huge_it_portfolio_container_<?php echo $portfolioID; ?>" class="super-list variable-sizes clearfix" <?php if($paramssld["ht_view0_sorting_float"] == "top" && $paramssld["ht_view0_filtering_float"] == "top") echo "style='clear: both;'";?>>
               <?php
				$group_key1= 0;
              foreach($images as $key=>$row)
              {
                      $group_key1++;
                      $group_key = (string)$group_key1;
					  $portfolioID1 = (string)$portfolioID;
					  $group_key =$group_key."-".$portfolioID;
					  $link = $row->sl_url;
                      $descnohtml=strip_tags($row->description);
                      $result = substr($descnohtml, 0, 50);
                      $catForFilter = explode(",", $row->category);
					  $imgurl=explode(";",$row->image_url);
					  $lighboxable = (count($imgurl) == 2)?"lighboxable":"dropdownable"; 
                      ?>
                      <div class="portelement_<?php echo $portfolioID; ?> colorbox_grouping <?php foreach ($catForFilter as $catForFilterValue) { echo str_replace(" ","-",$catForFilterValue)." ";} ?>" data-symbol="<?php echo $row->name; ?>" data-category="alkaline-earth">
                              <div class="default-block_<?php echo $portfolioID; ?> <?php echo $lighboxable; ?>">
                                      <div class="image-block_<?php echo $portfolioID; ?>  add-H-relative">
                                              <?php $imgurl=explode(";",$row->image_url); ?>
                                              <?php 	
											    if($row->image_url != ';'){
													switch(youtube_or_vimeo_portfolio($imgurl[0])) { 
														case 'image':		?>	
														<a href="<?php echo $imgurl[0]; ?>" class=" portfolio-group<?php if( $lighboxable == "lighboxable")  echo $group_key;?>"  title = "<?php echo $row->name;?>">
															<img alt="<?php echo $row->name; ?>" id="wd-cl-img<?php echo $key; ?>" src="<?php echo get_huge_image($imgurl[0],$image_prefix); ?>" />
														</a>
														<?php 
														break;
														case 'youtube':
														$videourl=get_video_id_from_url_portfolio($imgurl[0]);?>
														<a href="https://www.youtube.com/embed/<?php echo $videourl[0];?>" class="huge_it_portfolio_item youtube portfolio-group<?php if( $lighboxable == "lighboxable")  echo $group_key;?> "  title = "<?php echo $row->name;?>">
														<img alt="<?php echo $row->name; ?>" id="wd-cl-img<?php echo $key; ?>"  src="//img.youtube.com/vi/<?php echo $videourl[0]; ?>/mqdefault.jpg"  />
														<div class="play-icon <?php echo $videourl[1];?>-icon"></div>
														</a>
														<?php
														break;
														case 'vimeo':
														$videourl=get_video_id_from_url_portfolio($imgurl[0]);
														$hash = unserialize(file_get_contents("http://vimeo.com/api/v2/video/".$videourl[0].".php"));
														$imgsrc=$hash[0]['thumbnail_large'];
													?>
														<a href="http://player.vimeo.com/video/<?php echo $videourl[0]; ?>" class="huge_it_portfolio_item vimeo portfolio-group<?php if( $lighboxable == "lighboxable")  echo $group_key;?> "  title = "<?php echo $row->name;?>">
														<img alt="<?php echo $row->name; ?>" src="<?php echo $imgsrc; ?>"  />
														<div class="play-icon <?php echo $videourl[1];?>-icon"></div>
														</a>
														<?php break;
											  
													}
											    }
											  
											  else { ?>
                                              <img alt="<?php echo $row->name; ?>" id="wd-cl-img<?php echo $key; ?>" src="images/noimage.jpg" />
                                              <?php
                                              } ?>	
                                      </div>
                                      <div class="title-block_<?php echo $portfolioID; ?>">
                                              <h3 class="title"><?php echo $row->name; ?></h3>
                                              <div class="open-close-button"></div>
                                      </div>
                              </div>

                              <div class="wd-portfolio-panel_<?php echo $portfolioID; ?>" id="panel<?php echo $key; ?>">
                              <?php  $imgurl=explode(";",$row->image_url);
									 // array_shift($imgurl);
									if($paramssld['ht_view0_show_thumbs']=='on' and $paramssld['ht_view0_thumbs_position']=="before" && count($imgurl) != 2)
                                      {?>
                                              <div>
                                                      <ul class="thumbs-list_<?php echo $portfolioID; ?>">
                                                              <?php
                                                             
															array_pop($imgurl);
                                                              foreach($imgurl as $key1=>$img)
                                                              {
                                                              ?>
                                                              <li>
															  <?php 
															  switch(youtube_or_vimeo_portfolio($img)) { 
																case 'image':?>
                                                                      <a href="<?php echo $img; ?>" class=" portfolio-group<?php echo $group_key;?> "  title = "<?php echo $row->name; ?>"><img src="<?php echo get_huge_image($img,$image_prefix); ?>"></a>
															<?php 
																break;
																case 'youtube':
																	$videourl=get_video_id_from_url_portfolio($img);?>
                                                                    <a href="https://www.youtube.com/embed/<?php echo $videourl[0];?>" class="huge_it_portfolio_item youtube portfolio-group<?php echo $group_key;?> "  title = "<?php echo $row->name; ?>" style="position:relative">
																	<img src="//img.youtube.com/vi/<?php echo $videourl[0]; ?>/mqdefault.jpg"><div class="play-icon youtube-icon"></div></a>
																	
															  <?php 
															    break;
																case 'vimeo':
																	$videourl=get_video_id_from_url_portfolio($img);
																	$hash = unserialize(file_get_contents("http://vimeo.com/api/v2/video/".$videourl[0].".php"));
																	$imgsrc=$hash[0]['thumbnail_large'];?>
																	<a class="huge_it_portfolio_item vimeo portfolio-group<?php echo $group_key;?> " href="http://player.vimeo.com/video/<?php echo $videourl[0]; ?>" title="<?php echo $row->name; ?>"  style="position:relative">
																	<img src="<?php echo $imgsrc; ?>" alt="<?php echo $row->name; ?>" /><div class="play-icon vimeo-icon"></div>
																	</a>
																<?php	
																break;
																}?>
															  </li>
                                                              <?php
                                                              }
                                                              ?>
                                                      </ul>
                                              </div>
                                      <?php } 
                                      if($paramssld['ht_view0_show_description']=='on'){?>
                                              <div class="description-block_<?php echo $portfolioID; ?>">
                                                      <p><?php echo $row->description; ?></p>
                                              </div>
                                      <?php }
									   $imgurl=explode(";",$row->image_url);
									 //  array_shift($imgurl);
                                      if($paramssld['ht_view0_show_thumbs']=='on' and $paramssld['ht_view0_thumbs_position']=="after" && count($imgurl) != 2){?>
                                              <div>
                                                      <ul class="thumbs-list_<?php echo $portfolioID; ?>">
                                                              <?php
                                                             
                                                              array_pop($imgurl);
                                                              foreach($imgurl as $key1=>$img)
                                                              {
                                                              ?>
                                                              <li>
															  <?php 
															  switch(youtube_or_vimeo_portfolio($img)) { 
																case 'image':?>
                                                                      <a href="<?php echo $img; ?>" class=" portfolio-group<?php echo $group_key;?> "  title = "<?php echo $row->name; ?>"><img src="<?php echo get_huge_image($img,$image_prefix); ?>"></a>
															<?php 
																break;
																case 'youtube':
																	$videourl=get_video_id_from_url_portfolio($img);?>
                                                                    <a href="https://www.youtube.com/embed/<?php echo $videourl[0];?>" class="huge_it_portfolio_item youtube portfolio-group<?php echo $group_key;?> "  title = "<?php echo $row->name; ?>" style="position:relative">
																	<img src="//img.youtube.com/vi/<?php echo $videourl[0]; ?>/mqdefault.jpg"><div class="play-icon youtube-icon"></div></a>
																	
															  <?php 
															    break;
																case 'vimeo':
																	$videourl=get_video_id_from_url_portfolio($img);
																	$hash = unserialize(file_get_contents("http://vimeo.com/api/v2/video/".$videourl[0].".php"));
																	$imgsrc=$hash[0]['thumbnail_large'];?>
																	<a class="huge_it_portfolio_item vimeo portfolio-group<?php echo $group_key;?> " href="http://player.vimeo.com/video/<?php echo $videourl[0]; ?>" title="<?php echo $row->name; ?>"  style="position:relative">
																	<img src="<?php echo $imgsrc; ?>" alt="<?php echo $row->name; ?>" /><div class="play-icon vimeo-icon"></div>
																	</a>
																<?php	
																break;
																}?>
															  </li>
                                                              <?php
                                                              }
                                                              ?>
                                                      </ul>
                                              </div>
                                      <?php } 
                                      if($paramssld['ht_view0_show_linkbutton']=='on' && $link != ''){?>
                                              <div class="button-block">
                                                      <a href="<?php echo $link; ?>" <?php if ($row->link_target=="on"){echo 'target="_blank"';}?>><?php echo $paramssld['ht_view0_linkbutton_text']; ?></a>
                                              </div>
                                      <?php } ?>
                              </div>
                      </div>

                      <?php
              }
              ?>
        </div>
</section>

<script>
jQuery(function(){

jQuery('#huge_it_portfolio_content_<?php echo $portfolioID; ?>').css('display','block');
	
var defaultBlockHeight=<?php echo $paramssld['ht_view0_block_height']; ?>;
var defaultBlockWidth=<?php echo $paramssld['ht_view0_block_width']; ?>;
    
    var $container = jQuery('#huge_it_portfolio_container_<?php echo $portfolioID; ?>');
    jQuery(window).load(function(){
            $container.hugeitmicro({ filter: '*' });
        });
    
      // add randomish size classes
      $container.find('.portelement_<?php echo $portfolioID; ?>').each(function(){
        var $this = jQuery(this),
            number = parseInt( $this.find('.number').text(), 10 );
			//alert(number);
        if ( number % 7 % 2 === 1 ) {
          $this.addClass('width2');
        }
        if ( number % 3 === 0 ) {
          $this.addClass('height2');
        }
      });
    
    $container.hugeitmicro({
      itemSelector : '.portelement_<?php echo $portfolioID; ?>',
      masonry : {
        columnWidth :  <?php echo $paramssld['ht_view0_block_width']; ?>+20+<?php echo $paramssld['ht_view0_border_width']*2; ?>
      },
      masonryHorizontal : {
        rowHeight: 300+20
      },
      cellsByRow : {
        columnWidth : 300+20,
        rowHeight : 240
      },
      cellsByColumn : {
        columnWidth : 300+20,
        rowHeight : 240
      },
      getSortData : {
        symbol : function( $elem ) {
          return $elem.attr('data-symbol');
        },
        category : function( $elem ) {
          return $elem.attr('data-category');
        },
        number : function( $elem ) {
          return parseInt( $elem.find('.number').text(), 10 );
        },
        weight : function( $elem ) {
          return parseFloat( $elem.find('.weight').text().replace( /[\(\)]/g, '') );
        },
        id : function ( $elem ) {
          return $elem.find('.id').text();
        }
      }
    });
        

    var $optionSets = jQuery('#huge_it_portfolio_options_<?php echo $portfolioID; ?> .option-set'),
          $optionLinks = $optionSets.find('a');

      $optionLinks.click(function(){
        var $this = jQuery(this);

        if ( $this.hasClass('selected') ) {
          return false;
        }
        var $optionSet = $this.parents('.option-set');
        $optionSet.find('.selected').removeClass('selected');
        $this.addClass('selected');
  

        var options = {},
            key = $optionSet.attr('data-option-key'),
            value = $this.attr('data-option-value');

        value = value === 'false' ? false : value;
        options[ key ] = value;
        if ( key === 'layoutMode' && typeof changeLayoutMode === 'function' ) {

          changeLayoutMode( $this, options )
        } else {

          $container.hugeitmicro( options );
        }
        
        return false;
      });

      var isHorizontal = false;
      function changeLayoutMode( $link, options ) {
        var wasHorizontal = isHorizontal;
        isHorizontal = $link.hasClass('horizontal');

        if ( wasHorizontal !== isHorizontal ) {

          var style = isHorizontal ? 
            { height: '75%', width: $container.width() } : 
            { width: 'auto' };

          $container.filter(':animated').stop();

          $container.addClass('no-transition').css( style );
          setTimeout(function(){
            $container.removeClass('no-transition').hugeitmicro( options );
          }, 100 )
        } else {
          $container.hugeitmicro( options );
        }
      }
    

     $container.delegate( '.default-block_<?php echo $portfolioID; ?>.dropdownable', 'click', function(){//console.log("delegate");
          var strheight=0;
          jQuery(this).parents('.portelement_<?php echo $portfolioID; ?>').find('.wd-portfolio-panel_<?php echo $portfolioID; ?> > div').each(function(){//console.log("each");
                strheight+=jQuery(this).outerHeight()+10;
                //alert(strheight);
          })
          strheight+=<?php echo $paramssld['ht_view0_block_height']+45; ?>;
	  			if(jQuery(this).parents('.portelement_<?php echo $portfolioID; ?>').hasClass("large")){//console.log("hasclass");
			jQuery(this).parents('.portelement_<?php echo $portfolioID; ?>').animate({
				height: "<?php echo $paramssld['ht_view0_block_height']+45; ?>px"
			}, 300, function() {
				jQuery(this).removeClass('large');
				$container.hugeitmicro('reLayout');
			});
			
			jQuery(this).parents('.portelement_<?php echo $portfolioID; ?>').removeClass("active");
			return false;
		}
		
	
		jQuery(this).parents('.portelement_<?php echo $portfolioID; ?>').css({height:strheight});
		jQuery(this).parents('.portelement_<?php echo $portfolioID; ?>').addClass('large');

		$container.hugeitmicro('reLayout');
		jQuery(this).parents('.portelement_<?php echo $portfolioID; ?>').css({height:"<?php echo $paramssld['ht_view0_block_height']+45; ?>px"});		 
		 
		//alert(strheight);
		 
		 jQuery(this).parents('.portelement_<?php echo $portfolioID; ?>').animate({
			height:strheight+"px",
		  }, 300,function(){	$container.hugeitmicro('reLayout');});
		  return false;
	});
     $container.delegate( '.title-block_<?php echo $portfolioID; ?>', 'click', function(){//console.log("delegate");
          var strheight=0;
          jQuery(this).parents('.portelement_<?php echo $portfolioID; ?>').find('.wd-portfolio-panel_<?php echo $portfolioID; ?> > div').each(function(){//console.log("each");
                strheight+=jQuery(this).outerHeight()+10;
                //alert(strheight);
          })
          strheight+=<?php echo $paramssld['ht_view0_block_height']+45; ?>;
	  			if(jQuery(this).parents('.portelement_<?php echo $portfolioID; ?>').hasClass("large")){//console.log("hasclass");
			jQuery(this).parents('.portelement_<?php echo $portfolioID; ?>').animate({
				height: "<?php echo $paramssld['ht_view0_block_height']+45; ?>px"
			}, 300, function() {
				jQuery(this).removeClass('large');
				$container.hugeitmicro('reLayout');
			});
			
			jQuery(this).parents('.portelement_<?php echo $portfolioID; ?>').removeClass("active");
			return false;
		}
		
	
		jQuery(this).parents('.portelement_<?php echo $portfolioID; ?>').css({height:strheight});
		jQuery(this).parents('.portelement_<?php echo $portfolioID; ?>').addClass('large');

		$container.hugeitmicro('reLayout');
		jQuery(this).parents('.portelement_<?php echo $portfolioID; ?>').css({height:"<?php echo $paramssld['ht_view0_block_height']+45; ?>px"});		 
		 
		//alert(strheight);
		 
		 jQuery(this).parents('.portelement_<?php echo $portfolioID; ?>').animate({
			height:strheight+"px",
		  }, 300,function(){	$container.hugeitmicro('reLayout');});
		  		  return false;

	});
    var $sortBy =  jQuery('#huge_it_portfolio_content_<?php echo $portfolioID; ?> #sort-by');
    jQuery('#huge_it_portfolio_content_<?php echo $portfolioID; ?> #shuffle a').click(function(){
      $container.hugeitmicro('shuffle');
      $sortBy.find('.selected').removeClass('selected');
      $sortBy.find('[data-option-value="random"]').addClass('selected');
      return false;
    });
    
        // bind filter on select change
        jQuery(document).ready(function(){
            jQuery('#huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul li').click(function() {
              // get filter value from option value
              var filterValue = jQuery(this).attr('rel');
              // use filterFn if matches value
              filterValue = filterValue;//filterFns[ filterValue ] || 
              $container.hugeitmicro({ filter: filterValue });
            });
            <?php if(($paramssld["ht_view0_sorting_float"] == "left" || $paramssld["ht_view0_sorting_float"] == "right") && $paramssld["ht_view0_filtering_float"] == "none")
                  { ?>
                        var topmargin = jQuery("#huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul").height();
                        jQuery("#huge_it_portfolio_options_<?php echo $portfolioID; ?>").css({'margin-top':parseInt(topmargin) + 5});
            <?php }
            else  {
                    if(($paramssld["ht_view0_filtering_float"] == "left" || $paramssld["ht_view0_filtering_float"] == "right") && $paramssld["ht_view0_sorting_float"] == "none")
                      { ?>
                         var topmargin = jQuery("#huge_it_portfolio_options_<?php echo $portfolioID; ?>").height();
                         jQuery("#huge_it_portfolio_filters_<?php echo $portfolioID; ?>").css({'margin-top':'5px'});
                <?php }
                  } ?>
        });
        
        jQuery(window).load(function(){

            $container.hugeitmicro({ filter: '*' });
        });
  });
</script>
	
<?php        
        break;
	
	///////////////////////////////// VIEW 1 FullHeight Blocks //////////////////////////////////////////////
	
	case 1;
 ?>
<?php
    if($paramssld["ht_view1_sorting_float"] == "left" && $paramssld["ht_view1_filtering_float"] == "right" ||
       $paramssld["ht_view1_sorting_float"] == "right" && $paramssld["ht_view1_filtering_float"] == "left" ||
       $paramssld["ht_view1_sorting_float"] == $paramssld["ht_view1_filtering_float"])
       { $sorting_block_width ="20%"; $filtering_block_width ="20%"; $middle_with = "56%"; }
    else if($paramssld["ht_view1_sorting_float"] == "left" || $paramssld["ht_view1_sorting_float"] == "right" && $paramssld["ht_view1_filtering_float"] == "top")
       { $sorting_block_width ="30%"; $filtering_block_width ="100%"; $paramssld["ht_view1_filtering_float"] = "none"; $width_middle = "65%"; }
    else if($paramssld["ht_view1_filtering_float"] == "left" || $paramssld["ht_view1_filtering_float"] == "right" && $paramssld["ht_view1_sorting_float"] == "top")
       { $sorting_block_width ="100%"; $filtering_block_width ="30%"; $paramssld["ht_view1_sorting_float"] = "none"; $width_middle = "65%"; }
    if($paramssld["ht_view1_sorting_float"] == "top" && $paramssld["ht_view1_filtering_float"] == "top")
       { $sorting_block_width ="100%"; $filtering_block_width ="100%"; $left_to_top = "ok"; }
?>

<style type="text/css"> 
/***<add>***/
.portelement_<?php echo $portfolioID; ?> .play-icon.youtube-icon  {
	background: url(<?php echo  plugins_url( '../images/play.youtube.png' , __FILE__ );?>) center center no-repeat;
	background-size: 30% 30%;
}
.portelement_<?php echo $portfolioID; ?> .play-icon.vimeo-icon  {
	background: url(<?php echo  plugins_url( '../images/play.vimeo.png' , __FILE__ );?>) center center no-repeat;
	background-size: 30% 30%;
}
.portelement_<?php echo $portfolioID; ?> .play-icon {
    position: absolute;
    top: 0px;
    left: 0px;
    width: 100%;
    height: 100%;
}	
.portelement_<?php echo $portfolioID; ?> .add-H-relative {
    position: relative;
}	
/***</add>***/
.portelement_<?php echo $portfolioID; ?> {
  max-width:<?php echo $paramssld['ht_view1_block_width']; ?>px;
  width: 100%;
  height:auto;
  margin: 5px;
  float: left;
  overflow: hidden;
  position: relative;
  outline:none; 
  background:#<?php echo $paramssld['ht_view1_element_background_color']?>;
  border:<?php echo $paramssld['ht_view1_element_border_width']; ?>px solid #<?php echo $paramssld['ht_view1_element_border_color']; ?>;
}

.default-block_<?php echo $portfolioID; ?> {
	position:relative;;
	max-width:<?php echo $paramssld['ht_view1_block_width']; ?>px;
	width:100%;
} 

.default-block_<?php echo $portfolioID; ?> .image-block_<?php echo $portfolioID; ?> {
	margin:0px;
	padding:0px;
	line-height:0px;
	border:1px solid #<?php echo $paramssld['ht_view1_element_border_color']; ?>;
}

.default-block_<?php echo $portfolioID; ?> img {
  margin:0px !important;
  padding:0px !important;
  max-width:<?php echo $paramssld['ht_view1_block_width']; ?>px !important;
  width: 100%;
  border-radius:0px;
}

.default-block_<?php echo $portfolioID; ?> .title-block_<?php echo $portfolioID; ?> {
	display:block;
	height:auto;
	padding:10px 0px 0px 0px;
	width:100%;
	text-overflow: ellipsis;
}

.default-block_<?php echo $portfolioID; ?> .title-block_<?php echo $portfolioID; ?> h3 {
	position:relative;
	margin:0px !important;
	padding:0px 5px 0px 5px !important;
	max-width:<?php echo $paramssld['ht_view1_block_width']; ?>px !important;
	width: 100%;
	text-overflow: ellipsis;
	overflow: hidden; 
	/*white-space:nowrap;*/
	font-weight:normal;
	color:#<?php echo $paramssld['ht_view1_title_font_color']; ?>;
	font-size:<?php echo $paramssld['ht_view1_title_font_size']; ?>px !important;
	line-height:<?php echo $paramssld['ht_view1_title_font_size']+4; ?>px !important;
}


.wd-portfolio-panel_<?php echo $portfolioID; ?> {
	position: relative;
	display:block;
	width:<?php echo $paramssld['ht_view1_block_width']-10; ?>px !important;
	margin:10px 5px 0px 5px;
	padding:0px;
	text-align:left;
}

.wd-portfolio-panel_<?php echo $portfolioID; ?> .description-block_<?php echo $portfolioID; ?> p,.wd-portfolio-panel_<?php echo $portfolioID; ?> .description-block_<?php echo $portfolioID; ?> * {	
	text-align:justify;
	font-size:<?php echo $paramssld['ht_view1_description_font_size']; ?>px !important;
	color:#<?php echo $paramssld['ht_view1_description_color']; ?>;
	margin:0px !important;
	padding:0px !important;
}


.wd-portfolio-panel_<?php echo $portfolioID; ?> .description-block_<?php echo $portfolioID; ?> h1,
.wd-portfolio-panel_<?php echo $portfolioID; ?> .description-block_<?php echo $portfolioID; ?> h2,
.wd-portfolio-panel_<?php echo $portfolioID; ?> .description-block_<?php echo $portfolioID; ?> h3,
.wd-portfolio-panel_<?php echo $portfolioID; ?> .description-block_<?php echo $portfolioID; ?> h4,
.wd-portfolio-panel_<?php echo $portfolioID; ?> .description-block_<?php echo $portfolioID; ?> h5,
.wd-portfolio-panel_<?php echo $portfolioID; ?> .description-block_<?php echo $portfolioID; ?> h6,
.wd-portfolio-panel_<?php echo $portfolioID; ?> .description-block_<?php echo $portfolioID; ?> p, 
.wd-portfolio-panel_<?php echo $portfolioID; ?> .description-block_<?php echo $portfolioID; ?> strong,
.wd-portfolio-panel_<?php echo $portfolioID; ?> .description-block_<?php echo $portfolioID; ?> span {
	padding:2px !important;
	margin:0px !important;
}

.wd-portfolio-panel_<?php echo $portfolioID; ?> .description-block_<?php echo $portfolioID; ?> ul,
.wd-portfolio-panel_<?php echo $portfolioID; ?> .description-block_<?php echo $portfolioID; ?> li {
	padding:2px 0px 2px 5px;
	margin:0px 0px 0px 8px;
}


.wd-portfolio-panel_<?php echo $portfolioID; ?> .thumbs-list_<?php echo $portfolioID; ?> {
	list-style:none;
	clear:both;
	display:table;
	width:100%;
	padding:0px;
	margin:3px 0px 0px 0px;
	text-align:center;
}

.wd-portfolio-panel_<?php echo $portfolioID; ?> .thumbs-list_<?php echo $portfolioID; ?> li {
	display:inline-block;
	margin:0px 3px 0px 2px;
}

.wd-portfolio-panel_<?php echo $portfolioID; ?> .thumbs-list_<?php echo $portfolioID; ?> li a {
	display:block;
	width:<?php echo $paramssld['ht_view1_thumbs_width']; ?>px;
	height:<?php echo $paramssld['ht_view1_thumbs_width']; ?>px;
	opacity:0.7;
	display:table;
}

.wd-portfolio-panel_<?php echo $portfolioID; ?> .thumbs-list_<?php echo $portfolioID; ?> li a:hover {
	opacity:1;
}

.wd-portfolio-panel_<?php echo $portfolioID; ?> img {
	margin:0px !important;
	padding:0px !important;
	display:table-cell;
	vertical-align:middle;
	width:<?php echo $paramssld['ht_view1_thumbs_width']; ?>px !important;
	max-height:<?php echo $paramssld['ht_view1_thumbs_width']; ?>px !important;
	width:100%;
	height:100%;
}

.wd-portfolio-panel_<?php echo $portfolioID; ?> > div {
	padding-top:10px;
	margin-bottom:10px;
	<?php if($paramssld['ht_view1_show_separator_lines']=="on") {?>
		background:url('<?php echo  plugins_url( '../images/divider.line.png' , __FILE__ ); ?>') center top repeat-x;
	<?php } ?>
}

.wd-portfolio-panel_<?php echo $portfolioID; ?> .button-block {
	padding-top:10px;
	margin-bottom:10px;
	
}

.wd-portfolio-panel_<?php echo $portfolioID; ?> .button-block a, .wd-portfolio-panel_<?php echo $portfolioID; ?> .button-block a:link, .wd-portfolio-panel_<?php echo $portfolioID; ?> .button-block a:visited {
	padding:10px;
	display:inline-block;
	font-size:<?php echo $paramssld['ht_view1_linkbutton_font_size']; ?>px;
	background:#<?php echo $paramssld['ht_view1_linkbutton_background_color']; ?>;
	color:#<?php echo $paramssld['ht_view1_linkbutton_color']; ?>;
	padding:6px 12px;
	text-decoration:none;
}

.wd-portfolio-panel_<?php echo $portfolioID; ?> .button-block a:hover, .wd-portfolio-panel_<?php echo $portfolioID; ?> .button-block a:focus, .wd-portfolio-panel_<?php echo $portfolioID; ?> .button-block a:active {
	background:#<?php echo $paramssld['ht_view1_linkbutton_background_hover_color']; ?>;
	color:#<?php echo $paramssld['ht_view1_linkbutton_font_hover_color']; ?>;
	text-decoration:none;
}
#huge_it_portfolio_content_<?php echo $portfolioID; ?> a{
    border:none;
}
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_options_<?php echo $portfolioID; ?> {
	position: relative;
    <?php if ($paramssld["ht_view1_show_sorting"] == 'off')
    echo "display:none;";
    if($paramssld["ht_view1_filtering_float"] == 'left' && $paramssld["ht_view1_sorting_float"] == 'none') { if($portfolioShowFiltering == "on") { echo "margin-left: 31%;"; } else echo "margin-left: 1%;"; }
    else if($paramssld["ht_view1_filtering_float"] == 'right' && $paramssld["ht_view1_sorting_float"] == 'none' || ($sorting_block_width == '100%' && $filtering_block_width == "100%")) { echo "margin-left: 1%;"; } ?>
    overflow: hidden;
    margin-top: 5px;
    float: <?php echo $paramssld["ht_view1_sorting_float"]; ?>;
    width:<?php echo $sorting_block_width; ?>;
}

#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_options_<?php echo $portfolioID; ?> ul {
  margin: 0px !important;
  padding: 0px !important;
  list-style: none;
<?php if($paramssld["ht_view1_sorting_float"] == 'top') {
      echo "float:left;margin-left:1%;";
      } ?>
}

#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul {
  margin: 0px !important;
  padding: 0px !important;
  overflow: hidden;
  <?php if($paramssld["ht_view1_filtering_float"] == 'top') {
      echo "float:left;margin-left:1%;";
      } ?>
}

<?php if($paramssld["ht_view1_sorting_float"] == 'none') { ?>
            #huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_options_<?php echo $portfolioID; ?> ul {
                float: left;
            }
    <?php } ?>


#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_options_<?php echo $portfolioID; ?> ul li {
    border-radius: <?php echo $paramssld["ht_view1_sortbutton_border_radius"];?>px;
    list-style-type: none;
    margin: 0px !important;
	padding: 0;
    <?php
        if($sorting_block_width == "100%" ) {
            echo "float:left !important;margin: 4px 8px 4px 0px !important;";
        }
        if($left_to_top == "ok")
        { echo "float:left !important;"; }
        if($paramssld["ht_view1_sorting_float"] == "left" || $paramssld["ht_view1_sorting_float"] == "right")
        { echo 'border-bottom: 1px solid #ccc;'; }
        else
        { echo 'border: 1px solid #ccc;'; }
    ?>
}

#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_options_<?php echo $portfolioID; ?> ul li a {
    background-color: #<?php echo $paramssld["ht_view1_sortbutton_background_color"];?> !important;
    font-size:<?php echo $paramssld["ht_view1_sortbutton_font_size"];?>px !important;
    color:#<?php echo $paramssld["ht_view1_sortbutton_font_color"];?> !important;
    text-decoration: none;
    cursor: pointer;
    margin: 0px !important;
    display: block;
    padding:3px;
}

/*#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_options_<?php echo $portfolioID; ?> ul li:hover {
    
}*/

#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_options_<?php echo $portfolioID; ?> ul li a:hover {
    background-color: #<?php echo $paramssld["ht_view1_sortbutton_hover_background_color"];?> !important;
    color:#<?php echo $paramssld["ht_view1_sortbutton_hover_font_color"];?> !important;
    cursor: pointer;
}

#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?> {
    margin-top: 5px;
    float: <?php echo $paramssld["ht_view1_filtering_float"]; ?>;
    width: <?php echo $filtering_block_width; ?>;
    <?php
        if ($paramssld["ht_view1_show_filtering"] == 'off') echo "display:none;";
        if($paramssld["ht_view1_filtering_float"] == 'none' && $paramssld["ht_view1_sorting_float"] == 'left' ) { if($portfolioShowSorting == 'on') { echo "margin-left: 31%;"; } else echo "margin-left: 1%";} 
        if(($paramssld["ht_view1_filtering_float"] == 'none' && ($paramssld["ht_view1_sorting_float"] == 'right')) || ($sorting_block_width == '100%' && $filtering_block_width == "100%")) { echo "margin-left: 1%";}
    ?>
}

#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul li {
    list-style-type: none;
    <?php
        if($filtering_block_width == "100%") { echo "float:left !important;margin: 4px 8px 4px 0px !important;"; }
        if($left_to_top == "ok") { echo "float:left !important;"; }
        if($paramssld["ht_view1_filtering_float"] == "left" || $paramssld["ht_view1_filtering_float"] == "right")
        { echo 'border-bottom: 1px solid #ccc;'; }
        else echo "border: 1px solid #ccc;";
    ?>
}

#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul li a {
    font-size:<?php echo $paramssld["ht_view1_filterbutton_font_size"];?>px !important;
    color:#<?php echo $paramssld["ht_view1_filterbutton_font_color"];?> !important;
    background-color: #<?php echo $paramssld["ht_view1_filterbutton_background_color"];?> !important;
    border-radius: <?php echo $paramssld["ht_view1_filterbutton_border_radius"];?>px;
    padding: 3px;
    display: block;
    text-decoration: none;
}

#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?>  ul li a:hover {
    color:#<?php echo $paramssld["ht_view1_filterbutton_hover_font_color"];?> !important;
    background-color: #<?php echo $paramssld["ht_view1_filterbutton_hover_background_color"];?> !important;
    cursor: pointer
}
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul li.active a,
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul li.active a:link,
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul li.active a:visited,
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?>  ul li.active a:hover,
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?>  ul li.active a:focus,
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?>  ul li.active a:active {
    color:#<?php echo $paramssld["ht_view1_filterbutton_hover_font_color"];?> !important;
    background-color: #<?php echo $paramssld["ht_view1_filterbutton_hover_background_color"];?> !important;
    cursor: pointer;
}
#huge_it_portfolio_content_<?php echo $portfolioID; ?> section {
    position:relative;
    display:block;
}

#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_container_<?php echo $portfolioID; ?> {
<?php if($paramssld["ht_view1_sorting_float"] == "left" && $paramssld["ht_view1_filtering_float"] == "right" ||
         $paramssld["ht_view1_sorting_float"] == "right" && $paramssld["ht_view1_filtering_float"] == "left")
       { echo "margin: 0px auto;"; }
       if((($paramssld["ht_view1_filtering_float"] == "left" || $paramssld["ht_view1_filtering_float"] == "right" && $paramssld["ht_view1_sorting_float"] == "top") || ($paramssld["ht_view1_sorting_float"] == "left" || $paramssld["ht_view1_sorting_float"] == "right" && $paramssld["ht_view1_filting_float"] == "top")) && $portfolioShowFiltering == "on" && $portfolioShowSorting == "on")
       {
?>
    width: <?php echo $width_middle; ?> !important;
 <?php } ?>
}
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_options_<?php echo $portfolioID; ?> #sort-direction{
    position: static;
}
@media screen and (max-width: 768px) {
	
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul li a,
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul li a:link,
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul li a:visited {
		font-size: 2vw !important;
	}
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_options_<?php echo $portfolioID; ?> ul li a {
		font-size:2vw !important;
	}

}
@media screen and (max-width: 480px) {
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_options_<?php echo $portfolioID; ?> {
	float: left;
}
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_options_<?php echo $portfolioID; ?> #sort-by{
	float: left;
	width: 100% !important;
}
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_options_<?php echo $portfolioID; ?> #sort-direction{
    float: left;
    width: 100% !important;
    position: relative;
    padding-left: 31% !important;
	right: 31%;
}
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul li a,
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul li a:link,
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul li a:visited {
		font-size: 3vw !important;
	}
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_options_<?php echo $portfolioID; ?> ul li a {
		line-height: 3vw;
		font-size:3vw !important;
	}
}
@media screen and (max-width: 420px) {
	
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul li a,
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul li a:link,
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul li a:visited {
		font-size: 4vw !important;
	}
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_options_<?php echo $portfolioID; ?> ul li a {
		font-size:4vw !important;
	}
}
@media screen and (max-width: <?php echo $paramssld['ht_view0_block_width']+2*$paramssld['ht_view0_element_border_width']+40; ?>px) {
   .portelement_<?php echo $portfolioID; ?>  {
		width:98%;
		margin: 1% !important;
		float: left;
		overflow: hidden;
		outline:none;
		border:<?php echo $paramssld['ht_view0_element_border_width']; ?>px solid #<?php echo $paramssld['ht_view0_element_border_color']; ?>;
    }
	.wd-portfolio-panel_<?php echo $portfolioID; ?> {
		width: 100% !important;
	}
}
</style>
<section id="huge_it_portfolio_content_<?php echo $portfolioID; ?>">
    <?php if($portfolioShowSorting == "on")
        { ?>
          <div id="huge_it_portfolio_options_<?php echo $portfolioID; ?>" class="">
            <ul id="sort-by" class="option-set clearfix" data-option-key="sortBy">
                <li><a href="#sortBy=original-order" data-option-value="original-order" class="selected" data><?php echo $paramssld["ht_view1_sorting_name_by_default"]; ?></a></li>
                <li><a href="#sortBy=id" data-option-value="id"><?php echo $paramssld["ht_view1_sorting_name_by_id"]; ?></a></li>
                <li><a href="#sortBy=symbol" data-option-value="symbol"><?php echo $paramssld["ht_view1_sorting_name_by_name"]; ?></a></li>
                <li id="shuffle"><a href='#shuffle'><?php echo $paramssld["ht_view1_sorting_name_by_random"]; ?></a></li>
            </ul>
              
            <ul id="sort-direction" class="option-set clearfix" data-option-key="sortAscending">
                <li><a href="#sortAscending=true" data-option-value="true" class="selected"><?php echo $paramssld["ht_view1_sorting_name_by_asc"]; ?></a></li>
                <li><a href="#sortAscending=false" data-option-value="false"><?php echo $paramssld["ht_view1_sorting_name_by_desc"]; ?></a></li>
            </ul>
          </div>
  <?php }
   if($portfolioShowFiltering == "on")
      { ?>
         <div id="huge_it_portfolio_filters_<?php echo $portfolioID; ?>" >
            <ul>
                <li rel="*"><a><?php echo $paramssld["ht_view1_cat_all"];?></a></li>
                <?php
                $portfolioCats = explode(",", $portfolioCats);
                foreach ($portfolioCats as $portfolioCatsValue) {
                    if(!empty($portfolioCatsValue))
                    {
                ?>
                <li rel=".<?php echo str_replace(" ","_",$portfolioCatsValue); ?>"><a><?php echo str_replace("_"," ",$portfolioCatsValue); ?></a></li>
                    <?php
                    }
                }
                ?>
            </ul>
        </div>
<?php } ?>
        <div id="huge_it_portfolio_container_<?php echo $portfolioID; ?>" class="super-list variable-sizes clearfix" <?php if($paramssld["ht_view1_sorting_float"] == "top" && $paramssld["ht_view1_filtering_float"] == "top") echo "style='clear: both;'";?>>
              <?php
			    $group_key1 = 0;	
              foreach($images as $key=>$row)
              {
                      $group_key1++;
                      $group_key = (string)$group_key1;
					  $portfolioID1 = (string)$portfolioID;
					  $group_key =$group_key."-".$portfolioID;
                      $link = $row->sl_url;
                      $descnohtml=strip_tags($row->description);
                      $result = substr($descnohtml, 0, 50);
                      $catForFilter = explode(",", $row->category);
                      ?>
                      <div class="portelement_<?php echo $portfolioID; ?> colorbox_grouping <?php foreach ($catForFilter as $catForFilterValue) { echo str_replace(" ","-",$catForFilterValue)." ";} ?>" data-symbol="<?php echo $row->name; ?>" data-category="alkaline-earth">
                              <div class="default-block_<?php echo $portfolioID; ?>">
                                      <div class="image-block_<?php echo $portfolioID; ?> add-H-relative" >
                                              <?php $imgurl=explode(";",$row->image_url); ?>
                                              <?php
											    if($row->image_url != ';'){
													switch(youtube_or_vimeo_portfolio($imgurl[0])) { 
														case 'image':		?>	
                                                        <a href="<?php echo$imgurl[0]; ?>" class=" portfolio-group<?php echo $group_key;?> " title="<?php echo $row->name;?>">														
															<img alt="<?php echo $row->name; ?>" id="wd-cl-img<?php echo $key; ?>" src="<?php echo get_huge_image($imgurl[0],$image_prefix); ?>" />
														</a>	
														<?php 
														break;
														case 'youtube':
														$videourl=get_video_id_from_url_portfolio($imgurl[0]);?>
                                                        <a href="https://www.youtube.com/embed/<?php echo $videourl[0];?>" class="huge_it_portfolio_item youtube portfolio-group<?php echo $group_key;?>" title="<?php echo $row->name;?>">
															<img alt="<?php echo $row->name; ?>" id="wd-cl-img<?php echo $key; ?>"  src="//img.youtube.com/vi/<?php echo $videourl[0]; ?>/mqdefault.jpg"  />
															<div class="play-icon <?php echo $videourl[1];?>-icon"></div>
														</a>	
														<?php
														break;
														case 'vimeo':
														$videourl=get_video_id_from_url_portfolio($imgurl[0]);
														$hash = unserialize(file_get_contents("http://vimeo.com/api/v2/video/".$videourl[0].".php"));
														$imgsrc=$hash[0]['thumbnail_large'];
													?>
														<a class="huge_it_portfolio_item vimeo portfolio-group<?php echo $group_key;?> " href="http://player.vimeo.com/video/<?php echo $videourl[0]; ?>" title="<?php echo $row->name; ?>">
															<img alt="<?php echo $row->name; ?>" src="<?php echo $imgsrc; ?>"  />
															<div class="play-icon <?php echo $videourl[1];?>-icon"></div>
														</a>	
														<?php break;
											  
													}
											    }
											  else { ?>
                                              <img alt="<?php echo $row->name; ?>" id="wd-cl-img<?php echo $key; ?>" src="images/noimage.jpg" />
                                              <?php
                                              } ?>	
                                      </div>
                                      <div class="title-block_<?php echo $portfolioID; ?>">
                                              <h3 class="title"><?php echo $row->name; ?></h3>
                                      </div>
                              </div>

                              <div class="wd-portfolio-panel_<?php echo $portfolioID; ?>" id="panel<?php echo $key; ?>">
                              <?php	$imgurl=explode(";",$row->image_url);
									array_shift($imgurl);
									if($paramssld['ht_view1_show_thumbs']=='on' and $paramssld['ht_view1_thumbs_position']=="before" && count($imgurl) !=2 )
                                      {?>
                                              <div>
                                                      <ul class="thumbs-list_<?php echo $portfolioID; ?>">
                                                              <?php
                                                              array_pop($imgurl);
                                                              foreach($imgurl as $key1=>$img)
                                                              {
                                                              ?>
                                                              <li>
															  <?php 
															  switch(youtube_or_vimeo_portfolio($img)) { 
																case 'image':?>
                                                                      <a href="<?php echo $img; ?>" class=" portfolio-group<?php echo $group_key;?> "  title = "<?php echo $row->name; ?>"><img src="<?php echo get_huge_image($img,$image_prefix); ?>"></a>
															<?php 
																break;
																case 'youtube':
																	$videourl=get_video_id_from_url_portfolio($img);?>
                                                                    <a href="https://www.youtube.com/embed/<?php echo $videourl[0];?>" class="huge_it_portfolio_item youtube portfolio-group<?php echo $group_key;?> "  title="<?php echo $row->name; ?>" style="position:relative">
																	<img src="//img.youtube.com/vi/<?php echo $videourl[0]; ?>/mqdefault.jpg"><div class="play-icon youtube-icon"></div></a>
																	
															  <?php 
															    break;
																case 'vimeo':
																	$videourl=get_video_id_from_url_portfolio($img);
																	$hash = unserialize(file_get_contents("http://vimeo.com/api/v2/video/".$videourl[0].".php"));
																	$imgsrc=$hash[0]['thumbnail_large'];?>
																	<a class="huge_it_portfolio_item vimeo portfolio-group<?php echo $group_key;?> " href="http://player.vimeo.com/video/<?php echo $videourl[0]; ?>" title="<?php echo $row->name; ?>"  style="position:relative">
																	<img src="<?php echo $imgsrc; ?>" alt="<?php echo $row->name; ?>" /><div class="play-icon vimeo-icon"></div>
																	</a>
																<?php	
																break;
																}?>
															  </li>
                                                              <?php
                                                              }
                                                              ?>
                                                      </ul>
                                              </div>
                                      <?php } 
                                      if($paramssld['ht_view1_show_description']=='on'){?>
                                              <div class="description-block_<?php echo $portfolioID; ?>">
                                                      <p><?php echo $row->description; ?></p>
                                              </div>
                                      <?php }
                                      $imgurl=explode(";",$row->image_url);
									  array_shift($imgurl);
                                      if($paramssld['ht_view1_show_thumbs']=='on' and $paramssld['ht_view1_thumbs_position']=="after" && count($imgurl) != 2){?>
                                              <div>
                                                      <ul class="thumbs-list_<?php echo $portfolioID; ?>">
                                                              <?php
                                                              array_pop($imgurl);
                                                              foreach($imgurl as $key1=>$img)
                                                              {
                                                              ?>
                                                              <li>
															  <?php 
															  switch(youtube_or_vimeo_portfolio($img)) { 
																case 'image':?>
                                                                      <a href="<?php echo $img; ?>" class=" portfolio-group<?php echo $group_key;?> "  title = "<?php  echo $row->name; ?>;"><img src="<?php echo get_huge_image($img,$image_prefix); ?>"></a>
															<?php 
																break;
																case 'youtube':
																	$videourl=get_video_id_from_url_portfolio($img);?>
                                                                    <a href="https://www.youtube.com/embed/<?php echo $videourl[0];?>" class="huge_it_portfolio_item youtube portfolio-group<?php echo $group_key;?> "  title="<?php echo $row->name; ?>" style="position:relative">
																	<img src="//img.youtube.com/vi/<?php echo $videourl[0]; ?>/mqdefault.jpg"><div class="play-icon youtube-icon"></div></a>
																	
															  <?php 
															    break;
																case 'vimeo':
																	$videourl=get_video_id_from_url_portfolio($img);
																	$hash = unserialize(file_get_contents("http://vimeo.com/api/v2/video/".$videourl[0].".php"));
																	$imgsrc=$hash[0]['thumbnail_large'];?>
																	<a class="huge_it_portfolio_item vimeo portfolio-group<?php echo $group_key;?> " href="http://player.vimeo.com/video/<?php echo $videourl[0]; ?>" title="<?php echo $row->name; ?>"  style="position:relative">
																	<img src="<?php echo $imgsrc; ?>" alt="<?php echo $row->name; ?>" /><div class="play-icon vimeo-icon"></div>
																	</a>
																<?php	
																break;
																}?>
															  </li>
                                                              <?php
                                                              }
                                                              ?>
                                                      </ul>
                                              </div>
                                      <?php } 
                                      if($paramssld['ht_view1_show_linkbutton']=='on' && $link != ''){?>
                                              <div class="button-block">
                                                      <a href="<?php echo $link; ?>" <?php if ($row->link_target=="on"){echo 'target="_blank"';}?>><?php echo $paramssld['ht_view1_linkbutton_text']; ?></a>
                                              </div>
                                      <?php } ?>
                              </div>
                      </div>

                      <?php
              }
              ?>
        </div>
 </section>
    <script>
jQuery(function(){
var defaultBlockWidth=<?php echo $paramssld['ht_view1_block_width']; ?>;
    
    var $container = jQuery('#huge_it_portfolio_container_<?php echo $portfolioID; ?>');
    
    
      // add randomish size classes
      $container.find('.portelement_<?php echo $portfolioID; ?>').each(function(){
        var $this = jQuery(this),
            number = parseInt( $this.find('.number').text(), 10 );
			//alert(number);
        if ( number % 7 % 2 === 1 ) {
          $this.addClass('width2');
        }
        if ( number % 3 === 0 ) {
          $this.addClass('height2');
        }
      });
    
    $container.hugeitmicro({
      itemSelector : '.portelement_<?php echo $portfolioID; ?>',
      masonry : {
        columnWidth : <?php echo $paramssld['ht_view1_block_width']; ?>+20+<?php echo $paramssld['ht_view1_element_border_width']*2; ?>
      },
      masonryHorizontal : {
        rowHeight: 300+20
      },
      cellsByRow : {
        columnWidth : 300+20,
        rowHeight : 240
      },
      cellsByColumn : {
        columnWidth : 300+20,
        rowHeight : 240
      },
      getSortData : {
        symbol : function( $elem ) {
          return $elem.attr('data-symbol');
        },
        category : function( $elem ) {
          return $elem.attr('data-category');
        },
        number : function( $elem ) {
          return parseInt( $elem.find('.number').text(), 10 );
        },
        weight : function( $elem ) {
          return parseFloat( $elem.find('.weight').text().replace( /[\(\)]/g, '') );
        },
        id : function ( $elem ) {
          return $elem.find('.id').text();
        }
      }
    });
    
    
      var $optionSets = jQuery('#huge_it_portfolio_options_<?php echo $portfolioID; ?> .option-set'),
          $optionLinks = $optionSets.find('a');

      $optionLinks.click(function(){
        var $this = jQuery(this);

        if ( $this.hasClass('selected') ) {
          return false;
        }
        var $optionSet = $this.parents('.option-set');
        $optionSet.find('.selected').removeClass('selected');
        $this.addClass('selected');
  

        var options = {},
            key = $optionSet.attr('data-option-key'),
            value = $this.attr('data-option-value');

        value = value === 'false' ? false : value;
        options[ key ] = value;
        if ( key === 'layoutMode' && typeof changeLayoutMode === 'function' ) {

          changeLayoutMode( $this, options )
        } else {

          $container.hugeitmicro( options );
        }
        
        return false;
      });


    

      var isHorizontal = false;
      function changeLayoutMode( $link, options ) {
        var wasHorizontal = isHorizontal;
        isHorizontal = $link.hasClass('horizontal');

        if ( wasHorizontal !== isHorizontal ) {

          var style = isHorizontal ? 
            { height: '75%', width: $container.width() } : 
            { width: 'auto' };

          $container.filter(':animated').stop();

          $container.addClass('no-transition').css( style );
          setTimeout(function(){
            $container.removeClass('no-transition').hugeitmicro( options );
          }, 100 )
        } else {
          $container.hugeitmicro( options );
        }
      }

    var $sortBy =  jQuery('#huge_it_portfolio_content_<?php echo $portfolioID; ?> #sort-by');
    jQuery('#huge_it_portfolio_content_<?php echo $portfolioID; ?> #shuffle a').click(function(){
      $container.hugeitmicro('shuffle');
      $sortBy.find('.selected').removeClass('selected');
      $sortBy.find('[data-option-value="random"]').addClass('selected');
      return false;
    });
    
    ////filteringgggggg

        // bind filter on select change
        jQuery(document).ready(function(){
            jQuery('#huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul li').click(function() {
              // get filter value from option value
              var filterValue = jQuery(this).attr('rel');
              // use filterFn if matches value
              filterValue = filterValue;//filterFns[ filterValue ] || 
              $container.hugeitmicro({ filter: filterValue });
            });
            <?php if(($paramssld["ht_view1_sorting_float"] == "left" || $paramssld["ht_view1_sorting_float"] == "right") && $paramssld["ht_view1_filtering_float"] == "none")
                  { ?>
                        var topmargin = jQuery("#huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul").height();
                        jQuery("#huge_it_portfolio_options_<?php echo $portfolioID; ?>").css({'margin-top':parseInt(topmargin) + 5});
            <?php }
            else  {
                    if(($paramssld["ht_view1_filtering_float"] == "left" || $paramssld["ht_view1_filtering_float"] == "right") && $paramssld["ht_view1_sorting_float"] == "none")
                      { ?>
                         var topmargin = jQuery("#huge_it_portfolio_options_<?php echo $portfolioID; ?>").height();
                         jQuery("#huge_it_portfolio_filters_<?php echo $portfolioID; ?>").css({'margin-top':'5px'});
                <?php }
                  } ?>
        });
        jQuery(window).resize(function(){
			  $container.hugeitmicro('reLayout');
		 });
        jQuery(window).load(function(){

            $container.hugeitmicro({ filter: '*' });
        });
  });
</script>	  
	  <?php
	  
        break;
/////////////////////////////// VIEW 2 Popup /////////////////////////////
		  case 2:
      
	
	  ?>
<?php
    if($paramssld["ht_view2_sorting_float"] == "left" && $paramssld["ht_view2_filtering_float"] == "right" ||
       $paramssld["ht_view2_sorting_float"] == "right" && $paramssld["ht_view2_filtering_float"] == "left" ||
       $paramssld["ht_view2_sorting_float"] == $paramssld["ht_view2_filtering_float"])
       { $sorting_block_width ="20%"; $filtering_block_width ="20%"; $middle_with = "56%"; }
    else if($paramssld["ht_view2_sorting_float"] == "left" || $paramssld["ht_view2_sorting_float"] == "right" && $paramssld["ht_view2_filtering_float"] == "top")
       { $sorting_block_width ="30%"; $filtering_block_width ="100%"; $paramssld["ht_view2_filtering_float"] = "none"; $width_middle = "65%"; }
    else if($paramssld["ht_view2_filtering_float"] == "left" || $paramssld["ht_view2_filtering_float"] == "right" && $paramssld["ht_view2_sorting_float"] == "top")
       { $sorting_block_width ="100%"; $filtering_block_width ="30%"; $paramssld["ht_view2_sorting_float"] = "none"; $width_middle = "65%"; }
    if($paramssld["ht_view2_sorting_float"] == "top" && $paramssld["ht_view2_filtering_float"] == "top")
       { $sorting_block_width ="100%"; $filtering_block_width ="100%"; $left_to_top = "ok"; }
?>

<script>
jQuery(function(){
var defaultBlockWidth=<?php echo $paramssld['ht_view2_element_width']; ?>;
var defaultBlockHeight=<?php echo $paramssld['ht_view2_element_height']; ?>;
    
    var $container = jQuery('#huge_it_portfolio_container_<?php echo $portfolioID; ?>');
    
    
      // add randomish size classes
      $container.find('.portelement_<?php echo $portfolioID; ?>').each(function(){//hech
        var $this = jQuery(this),
            number = parseInt( $this.find('.number').text(), 10 );
			//alert(number);
        if ( number % 7 % 2 === 1 ) {
          $this.addClass('width2');
        }
        if ( number % 3 === 0 ) {
          $this.addClass('height2');
        }
      });
    
    $container.hugeitmicro({//
      itemSelector : '.portelement_<?php echo $portfolioID; ?>',
      masonry : {
        columnWidth : <?php echo $paramssld['ht_view2_element_width']; ?>+20+<?php echo $paramssld['ht_view2_element_border_width']*2; ?>
      },
      masonryHorizontal : {
        rowHeight: 300+20
      },
      cellsByRow : {
        columnWidth : 300+20,
        rowHeight : 240
      },
      cellsByColumn : {
        columnWidth : 300+20,
        rowHeight : 240
      },
      getSortData : {
        symbol : function( $elem ) {
          return $elem.attr('data-symbol');
        },
        category : function( $elem ) {
          return $elem.attr('data-category');
        },
        number : function( $elem ) {
          return parseInt( $elem.find('.number').text(), 10 );
        },
        weight : function( $elem ) {
          return parseFloat( $elem.find('.weight').text().replace( /[\(\)]/g, '') );
        },
        id : function ( $elem ) {
          return $elem.find('.id').text();
        }
      }
    });
    
    
      var $optionSets = jQuery('#huge_it_portfolio_options_<?php echo $portfolioID; ?> .option-set'),
          $optionLinks = $optionSets.find('a');

      $optionLinks.click(function(){//
        var $this = jQuery(this);

        if ( $this.hasClass('selected') ) {
          return false;
        }
        var $optionSet = $this.parents('.option-set');
        $optionSet.find('.selected').removeClass('selected');
        $this.addClass('selected');
  

        var options = {},
            key = $optionSet.attr('data-option-key'),
            value = $this.attr('data-option-value');

        value = value === 'false' ? false : value;
        options[ key ] = value;
        if ( key === 'layoutMode' && typeof changeLayoutMode === 'function' ) {

          changeLayoutMode( $this, options )
        } else {

          $container.hugeitmicro( options );
        }
        
        return false;
      });


    

      var isHorizontal = false;
      function changeLayoutMode( $link, options ) {//console.log("changeLayoutMode")
        var wasHorizontal = isHorizontal;
        isHorizontal = $link.hasClass('horizontal');

        if ( wasHorizontal !== isHorizontal ) {

          var style = isHorizontal ? 
            { height: '75%', width: $container.width() } : 
            { width: 'auto' };

          $container.filter(':animated').stop();

          $container.addClass('no-transition').css( style );
          setTimeout(function(){
            $container.removeClass('no-transition').hugeitmicro( options );
          }, 100 )
        } else {
          $container.hugeitmicro( options );
        }
      }


    

      $container.delegate( '.default-block_<?php echo $portfolioID; ?>', 'click', function(){//console.log("changeLayoutMode")
          var strheight=0;
          jQuery(this).parents('.portelement_<?php echo $portfolioID; ?>').find('.wd-portfolio-panel_<?php echo $portfolioID; ?> > div').each(function(){
                strheight+=jQuery(this).outerHeight()+10;
                //alert(strheight);
          })
          strheight+=<?php echo $paramssld['ht_view0_block_height']+45; ?>;
	  			if(jQuery(this).parents('.portelement_<?php echo $portfolioID; ?>').hasClass("large")){
			jQuery(this).parents('.portelement_<?php echo $portfolioID; ?>').animate({
				height: "<?php echo $paramssld['ht_view0_block_height']+45; ?>px"
			}, 300, function() {
				jQuery(this).removeClass('large');
				$container.hugeitmicro('reLayout');
			});
			
			jQuery(this).parents('.portelement_<?php echo $portfolioID; ?>').removeClass("active");
			return false;
		}
		
	
		jQuery(this).parents('.portelement_<?php echo $portfolioID; ?>').css({height:strheight});
		jQuery(this).parents('.portelement_<?php echo $portfolioID; ?>').addClass('large');

		$container.hugeitmicro('reLayout');
		jQuery(this).parents('.portelement_<?php echo $portfolioID; ?>').css({height:"<?php echo $paramssld['ht_view0_block_height']+45; ?>px"});		 
		 
		//alert(strheight);
		 
		 jQuery(this).parents('.portelement_<?php echo $portfolioID; ?>').animate({
			height:strheight+"px",
		  }, 300,function(){	$container.hugeitmicro('reLayout');});
	});

    var $sortBy =  jQuery('#huge_it_portfolio_content_<?php echo $portfolioID; ?> #sort-by');
    jQuery('#huge_it_portfolio_content_<?php echo $portfolioID; ?> #shuffle a').click(function(){//random dasavorum
      $container.hugeitmicro('shuffle');
      $sortBy.find('.selected').removeClass('selected');
      $sortBy.find('[data-option-value="random"]').addClass('selected');
      return false;
    });
		jQuery(window).resize(function(){$container.hugeitmicro('reLayout');});

    
    ////filteringgggggg

        // bind filter on select change
        jQuery(document).ready(function(){
            jQuery('#huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul li').click(function() {//console.log("filter");
              // get filter value from option value
              var filterValue = jQuery(this).attr('rel');
              // use filterFn if matches value
              filterValue = filterValue;//filterFns[ filterValue ] || 
              $container.hugeitmicro({ filter: filterValue });
            });
            <?php if(($paramssld["ht_view2_sorting_float"] == "left" || $paramssld["ht_view2_sorting_float"] == "right") && $paramssld["ht_view2_filtering_float"] == "none")
                  { ?>
                        var topmargin = jQuery("#huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul").height();
                        jQuery("#huge_it_portfolio_options_<?php echo $portfolioID; ?>").css({'margin-top':parseInt(topmargin) + 5});
            <?php }
            else  {
                    if(($paramssld["ht_view2_filtering_float"] == "left" || $paramssld["ht_view2_filtering_float"] == "right") && $paramssld["ht_view2_sorting_float"] == "none")
                      { ?>
                         var topmargin = jQuery("#huge_it_portfolio_options_<?php echo $portfolioID; ?>").height();
                         jQuery("#huge_it_portfolio_filters_<?php echo $portfolioID; ?>").css({'margin-top':'5px'});
                <?php }
                  } ?>
        });

  });
  jQuery(document).ready(function(){

	jQuery('.portelement_<?php echo $portfolioID; ?> .image-block_<?php echo $portfolioID; ?> .image-overlay a').on('click',function(){//console.log("filter");//
		var strid = jQuery(this).attr('href').replace('#','');
		jQuery(this).parents('body').append('<div id="huge-popup-overlay-portfolio_<?php echo $portfolioID; ?>"></div>');
		jQuery('#huge_it_portfolio_popup_list_<?php echo $portfolioID; ?>').insertBefore('#huge-popup-overlay-portfolio_<?php echo $portfolioID; ?>');
		var height = jQuery(window).height();
		var width=jQuery(window).width();
		if(width<=767){
			jQuery(window).scrollTop(0);
		}
		jQuery('#huge_it_portfolio_pupup_element_'+strid).addClass('active').css({height:height*0.7});
		jQuery('#huge_it_portfolio_popup_list_<?php echo $portfolioID; ?>').addClass('active');
		
		jQuery('#huge_it_portfolio_pupup_element_'+strid+' ul.thumbs-list_<?php echo $portfolioID; ?> li:first-child').addClass('active');
		var strsrc=jQuery('#huge_it_portfolio_pupup_element_'+strid+' ul.thumbs-list_<?php echo $portfolioID; ?> li:first-child a img').attr('src');
		jQuery('#huge_it_portfolio_pupup_element_'+strid+' .image-block_<?php echo $portfolioID; ?> img').attr('src',strsrc);
		return false;

	});
	
	jQuery('#huge_it_portfolio_popup_list_<?php echo $portfolioID; ?> .popup-wrapper_<?php echo $portfolioID; ?> .right-block ul.thumbs-list_<?php echo $portfolioID; ?> li ').on('click','a.img-thumb',function(){//console.log("filter");//thubnail neri meccnelna
		var width=jQuery(window).width();
		var strsrc = jQuery(this).find('img').attr('src')
		if(width<=767){
			jQuery(window).scrollTop(0);
		}
		jQuery(this).parent().parent().find('li.active').removeClass('active');
		jQuery(this).parent().addClass('active');
		//jQuery(this).parents('.right-block').prev().find('img').attr('src',jQuery(this).find('img').attr('src'));
		var left_block = jQuery(this).parents('.right-block').prev();
		if(left_block.find('img').length !=0) 
			left_block.find('img').attr('src',strsrc);
		else 
		{	
			left_block.html('<img src="'+strsrc+'" />');
		}
				return false;

	});
		 /*      <-- POPUP LEFT CLICK -->        */
        jQuery("#huge_it_portfolio_popup_list_<?php echo $portfolioID; ?> .heading-navigation_<?php echo $portfolioID; ?> .left-change").click(function(){
        	//var strid = jQuery(this).closest(".pupup-element").prev(".pupup-element").find('a').data('popupid').replace('#','');
        	var height = jQuery(window).height();
        	//jQuery('#huge_it_gallery_pupup_element_'+strid).css({height:height*0.7});
            var num = jQuery(this).find("a").attr("href").replace('#', '');
            if(num >= 1){
            	var strid = jQuery(this).closest(".pupup-element").prev(".pupup-element").find('a').data('popupid').replace('#','');
            	jQuery('#huge_it_portfolio_pupup_element_'+strid).css({height:height*0.7});
                jQuery(this).closest(".pupup-element").removeClass("active");
                jQuery(this).closest(".pupup-element").prev(".pupup-element").addClass("active");
            }else{
            	var strid = jQuery("#huge_it_portfolio_popup_list_<?php echo $portfolioID; ?>").find(".pupup-element").last().find('a').data('popupid').replace('#','');
            	jQuery('#huge_it_portfolio_pupup_element_'+strid).css({height:height*0.7});
                jQuery(this).closest(".pupup-element").removeClass("active");
                jQuery("#huge_it_portfolio_popup_list_<?php echo $portfolioID; ?>").find(".pupup-element").last().addClass("active");
            }
            
        });
        
        /*      <-- POPUP RIGHT CLICK -->        */
        jQuery("#huge_it_portfolio_popup_list_<?php echo $portfolioID; ?> .heading-navigation_<?php echo $portfolioID; ?> .right-change").click(function(){
        	var height = jQuery(window).height();
            var num = jQuery(this).find("a").attr("href").replace('#', '');
            var cnt = 0;
            jQuery("#huge_it_portfolio_popup_list_<?php echo $portfolioID; ?>").find(".pupup-element").each(function(){
                cnt++;
            });
//            alert(num+" "+cnt);
            if(num <= cnt){
            	var strid = jQuery(this).closest(".pupup-element").next(".pupup-element").find('a').data('popupid').replace('#','');
	        	jQuery('#huge_it_portfolio_pupup_element_'+strid).css({height:height*0.7});
                jQuery(this).closest(".pupup-element").removeClass("active");
                jQuery(this).closest(".pupup-element").next(".pupup-element").addClass("active");
            }else{
            	var strid = jQuery("#huge_it_portfolio_popup_list_<?php echo $portfolioID; ?>").find(".pupup-element:first-child a").data('popupid').replace('#','');	        	
	        	jQuery('#huge_it_portfolio_pupup_element_'+strid).css({height:height*0.7});
                jQuery(this).closest(".pupup-element").removeClass("active");
                jQuery("#huge_it_portfolio_popup_list_<?php echo $portfolioID; ?>").find(".pupup-element:first-child").addClass("active");
            }
        });
	//////
	jQuery('#huge_it_portfolio_popup_list_<?php echo $portfolioID; ?> .heading-navigation_<?php echo $portfolioID; ?> .close').on('click',function(){//console.log("filter");
		closePopup();
		return false;
	});
	
	jQuery('body').on('click','#huge-popup-overlay-portfolio_<?php echo $portfolioID; ?>',function(){
		closePopup();
		return false;
	});
	
	function closePopup() {
		var scrollingTo = jQuery('#huge_it_portfolio_popup_list_<?php echo $portfolioID; ?> .pupup-element.active').attr('id');
		//alert(scrollingTo);
		jQuery(window).scrollTop(jQuery("#"+scrollingTo+"_child").offset().top-100);
		var end_video_src = jQuery('#huge_it_portfolio_popup_list_<?php echo $portfolioID; ?> li.active iframe').attr('src');
		var end_video = '&enablejsapi=1';
		jQuery('#huge_it_portfolio_popup_list_<?php echo $portfolioID; ?> li.active iframe').attr('src',end_video_src+end_video);
		jQuery('#huge-popup-overlay-portfolio_<?php echo $portfolioID; ?>').remove();
		jQuery('#huge_it_portfolio_popup_list_<?php echo $portfolioID; ?> li').removeClass('active');
		jQuery('#huge_it_portfolio_popup_list_<?php echo $portfolioID; ?>').removeClass('active');
	}
	
        
});
/***<add>***/
jQuery(function(){
	jQuery("#huge_it_portfolio_popup_list_<?php echo $portfolioID; ?> .video-thumb .play-icon").on('click',function() {
		new_video_id = jQuery(this).attr("title");
		var showcontrols,prefix,add_src;
			var showcontrols,new_video_id,prefix;
			if(!new_video_id) 
				return;
			if(new_video_id.length == 11) {
				 showcontrols = "?modestbranding=1&showinfo=0&controls=1";
				 prefix = "//www.youtube.com/embed/";
			}
			else {
			 showcontrols = "?title=0&amp;byline=0&amp;portrait=0";
			 prefix = "//player.vimeo.com/video/";

			}
			add_src = prefix+new_video_id+showcontrols;
			var left_block = jQuery(this).parents('.right-block').prev();
			if(left_block.find('iframe').length !=0) 
				left_block.find('iframe').attr('src',add_src);
			else 
				left_block.html('<iframe src="'+add_src+'" frameborder allowfullscreen></iframe> ');
			
			return false;
	});
}); 
/***</add>***/ 
</script>

<style type="text/css"> 
/***<add>***/
.portelement_<?php echo $portfolioID; ?> .play-icon.youtube-icon, 
 .play-icon.youtube-icon {
	background: url(<?php echo  plugins_url( '../images/play.youtube.png' , __FILE__ );?>) center center no-repeat;
	background-size: 30% 30%;
}
.portelement_<?php echo $portfolioID; ?> .play-icon.vimeo-icon,
 .play-icon.vimeo-icon {
	background: url(<?php echo  plugins_url( '../images/play.vimeo.png' , __FILE__ );?>) center center no-repeat;
	background-size: 30% 30%;
}

.portelement_<?php echo $portfolioID; ?> .play-icon,.play-icon {
    position: absolute;
    top: 0px;
    left: 0px;
    width: 100%;
    height: 100%;
}	
/***</add>***/
.portelement_<?php echo $portfolioID; ?> {
	max-width:<?php echo $paramssld['ht_view2_element_width']; ?>px;
	width:100%;
	height:<?php echo $paramssld['ht_view2_element_height']+45; ?>px;
	margin:0px 0px 10px 0px;
	background:#<?php echo $paramssld['ht_view2_element_background_color']; ?>;
	border:<?php echo $paramssld['ht_view2_element_border_width']; ?>px solid #<?php echo $paramssld['ht_view2_element_border_color']; ?>;
	outline:none;
}

.portelement_<?php echo $portfolioID; ?> .image-block_<?php echo $portfolioID; ?> {
	position:relative;
	width:100%;
}

.portelement_<?php echo $portfolioID; ?> .image-block_<?php echo $portfolioID; ?> img {
	margin:0px !important;
	padding:0px !important;
	max-width:<?php echo $paramssld['ht_view2_element_width']; ?>px !important;
	width: 100%;
	height:<?php echo $paramssld['ht_view2_element_height']; ?>px !important;
	display:block;
	border-radius: 0px !important;
	box-shadow: 0 0px 0px rgba(0, 0, 0, 0) !important; 
}

.portelement_<?php echo $portfolioID; ?> .image-block_<?php echo $portfolioID; ?> .image-overlay {
	position:absolute;
	top:0px;
	left:0px;
	width:100%;
	height:100%;
	background: <?php
			list($r,$g,$b) = array_map('hexdec',str_split($paramssld['ht_view2_element_overlay_color'],2));
				$titleopacity=$paramssld["ht_view2_element_overlay_transparency"]/100;						
				echo 'rgba('.$r.','.$g.','.$b.','.$titleopacity.')  !important'; 		
	?>;
	display:none;
}

.portelement_<?php echo $portfolioID; ?>:hover .image-block_<?php echo $portfolioID; ?>  .image-overlay {
	display:block;
}

.portelement_<?php echo $portfolioID; ?> .image-block_<?php echo $portfolioID; ?> .image-overlay a {
        border: none;
	position:absolute;
	top:0px;
	left:0px;
	display:block;
	width:100%;
	height:100%;
	background:url('<?php echo  plugins_url( '../images/zoom.'.$paramssld["ht_view2_zoombutton_style"].'.png' , __FILE__ ); ?>') center center no-repeat;
}

.portelement_<?php echo $portfolioID; ?> .title-block_<?php echo $portfolioID; ?> {
	position:relative;
	height: 30px;
	margin: 0;
	padding: 15px 0px 15px 0px;
	-webkit-box-shadow: inset 0 1px 0 rgba(0,0,0,.1);
	box-shadow: inset 0 1px 0 rgba(0,0,0,.1);
}

.portelement_<?php echo $portfolioID; ?> .title-block_<?php echo $portfolioID; ?> h3 {
	position:relative;
	margin:0px !important;
	padding:0px 1% 0px 1% !important;
	width:98%;
	text-overflow: ellipsis;
	overflow: hidden; 
	white-space:nowrap;
	font-weight:normal;
	font-size: <?php echo $paramssld["ht_view2_popup_title_font_size"];?>px !important;
	line-height: <?php echo $paramssld["ht_view2_popup_title_font_size"]+4;?>px !important;
	color:#<?php echo $paramssld["ht_view2_element_title_font_color"];?>;
}

.portelement_<?php echo $portfolioID; ?> .title-block_<?php echo $portfolioID; ?> .button-block {
	position:absolute;
	right:0px;
	top:0px;
	display:none;
	vertical-align:middle;
	height:30px;
	padding:10px 10px 4px 10px;
	background: <?php
			list($r,$g,$b) = array_map('hexdec',str_split($paramssld['ht_view2_element_overlay_color'],2));
				$titleopacity=$paramssld["ht_view2_element_overlay_transparency"]/100;						
				echo 'rgba('.$r.','.$g.','.$b.','.$titleopacity.')  !important'; 		
	?>;
	border-left: 1px solid rgba(0,0,0,.05);
}
.portelement_<?php echo $portfolioID; ?>:hover .title-block_<?php echo $portfolioID; ?> .button-block {display:block;}

.portelement_<?php echo $portfolioID; ?> .title-block_<?php echo $portfolioID; ?> a,.portelement_<?php echo $portfolioID; ?> .title-block_<?php echo $portfolioID; ?> a:link,.portelement_<?php echo $portfolioID; ?> .title-block_<?php echo $portfolioID; ?> a:visited,
.portelement_<?php echo $portfolioID; ?> .title-block_<?php echo $portfolioID; ?> a:hover,.portelement_<?php echo $portfolioID; ?> .title-block_<?php echo $portfolioID; ?> a:focus,.portelement_<?php echo $portfolioID; ?> .title-block_<?php echo $portfolioID; ?> a:active {
	position:relative;
	display:block;
	vertical-align:middle;
	padding: 3px 10px 3px 10px; 
	border-radius:3px;
	font-size:<?php echo $paramssld["ht_view2_element_linkbutton_font_size"];?>px;
	background:#<?php echo $paramssld["ht_view2_element_linkbutton_background_color"];?>;
	color:#<?php echo $paramssld["ht_view2_element_linkbutton_color"];?>;
	text-decoration:none;
}

/*#####POPUP#####*/

#huge_it_portfolio_popup_list_<?php echo $portfolioID; ?> {
	position:fixed;
	display:table;
	width:80%;
	top:7%;
	left:7%;
	margin:0px !important;
	list-style:none;
	z-index:2000;
	display:none;
	height:90%;
}

#huge_it_portfolio_popup_list_<?php echo $portfolioID; ?>.active {display:table;}

#huge_it_portfolio_popup_list_<?php echo $portfolioID; ?> li.pupup-element {
	position:relative;
	display:none;
	width:100%;
	padding:40px 0px 20px 0px;
	min-height:100%;
	position:relative;
	background:#<?php echo $paramssld["ht_view2_popup_background_color"];?>;
}

#huge_it_portfolio_popup_list_<?php echo $portfolioID; ?> li.pupup-element.active {
	display:block;
}

#huge_it_portfolio_popup_list_<?php echo $portfolioID; ?> .heading-navigation_<?php echo $portfolioID; ?> {
	position:absolute;
	width:100%;
	height:40px;
	top:0px;
	left:0px;
	z-index:2001;
	background:url('<?php echo  plugins_url( '../images/divider.line.png' , __FILE__ ); ?>') center bottom repeat-x;
}

#huge_it_portfolio_popup_list_<?php echo $portfolioID; ?> .heading-navigation_<?php echo $portfolioID; ?> .close,#huge_it_portfolio_popup_list_<?php echo $portfolioID; ?> .heading-navigation_<?php echo $portfolioID; ?> .close:link, #huge_it_portfolio_popup_list_<?php echo $portfolioID; ?> .heading-navigation_<?php echo $portfolioID; ?> .close:visited {
	position:relative;
	float:right;
	width:40px;
	height:40px;
	display:block;
	background:url('<?php echo  plugins_url( '../images/close.popup.'.$paramssld["ht_view2_popup_closebutton_style"].'.png' , __FILE__ ); ?>') center center no-repeat;
	border-left:1px solid #ccc;
	opacity:.65;
}

#huge_it_portfolio_popup_list_<?php echo $portfolioID; ?> .heading-navigation_<?php echo $portfolioID; ?> .close:hover, #huge_it_portfolio_popup_list_<?php echo $portfolioID; ?> .heading-navigation_<?php echo $portfolioID; ?> .close:focus, #huge_it_portfolio_popup_list_<?php echo $portfolioID; ?> .heading-navigation_<?php echo $portfolioID; ?> .close:active {opacity:1;}


#huge_it_portfolio_popup_list_<?php echo $portfolioID; ?> li.pupup-element .popup-wrapper_<?php echo $portfolioID; ?> {
	overflow-y:scroll;
	position:relative;
	width:96%;
	height:98%;
	padding:2% 2% 0% 2%;
}

#huge_it_portfolio_popup_list_<?php echo $portfolioID; ?> .popup-wrapper_<?php echo $portfolioID; ?> .image-block_<?php echo $portfolioID; ?> {
	width:60%;
        <?php if($paramssld['ht_view2_popup_full_width'] == 'off') { echo "height:100%;"; } ?>
	position:relative;
	height: 60%;
	float:left;
	margin-right:2%;
	border-right:1px solid #ccc;
	min-width:200px;
}

#huge_it_portfolio_popup_list_<?php echo $portfolioID; ?> .popup-wrapper_<?php echo $portfolioID; ?> .image-block_<?php echo $portfolioID; ?> img {
        <?php
            if($paramssld['ht_view2_popup_full_width'] == 'off') { echo "max-width:100% !important; max-height:100% !important; margin: 0px auto !important; position:relative;"; }
            else { echo "width:100% !important;"; }
        ?>
	display:block;
	padding:0px !important;
}

#huge_it_portfolio_popup_list_<?php echo $portfolioID; ?> .popup-wrapper_<?php echo $portfolioID; ?> .right-block {
	width:37%;
	position:relative;
	float:left;
}

#huge_it_portfolio_popup_list_<?php echo $portfolioID; ?> li.pupup-element .popup-wrapper_<?php echo $portfolioID; ?> .right-block > div {
	padding-top:10px;
	margin-bottom:10px;
	<?php if($paramssld['ht_view2_show_separator_lines']=="on") {?>
		background:url('<?php echo  plugins_url( '../images/divider.line.png' , __FILE__ ); ?>') center top repeat-x;
	<?php } ?>
}
#huge_it_portfolio_popup_list_<?php echo $portfolioID; ?> li.pupup-element .popup-wrapper_<?php echo $portfolioID; ?> .right-block > div:last-child {background:none;}


#huge_it_portfolio_popup_list_<?php echo $portfolioID; ?> .popup-wrapper_<?php echo $portfolioID; ?> .right-block .title {
	position:relative;
	display:block;
	margin:0px 0px 10px 0px !important;
	font-size:<?php echo $paramssld["ht_view2_element_title_font_size"];?>px !important;
	line-height:<?php echo $paramssld["ht_view2_element_title_font_size"]+4;?>px !important;
	color:#<?php echo $paramssld["ht_view2_popup_title_font_color"];?>;
}

#huge_it_portfolio_popup_list_<?php echo $portfolioID; ?> .popup-wrapper_<?php echo $portfolioID; ?> .right-block .description {
	clear:both;
	position:relative;
	text-align:justify;
	font-size:<?php echo $paramssld["ht_view2_description_font_size"];?>px !important;
	color:#<?php echo $paramssld["ht_view2_description_color"];?>;
}

#huge_it_portfolio_popup_list_<?php echo $portfolioID; ?> .popup-wrapper_<?php echo $portfolioID; ?> .right-block .description h1,
#huge_it_portfolio_popup_list_<?php echo $portfolioID; ?> .popup-wrapper_<?php echo $portfolioID; ?> .right-block .description h2,
#huge_it_portfolio_popup_list_<?php echo $portfolioID; ?> .popup-wrapper_<?php echo $portfolioID; ?> .right-block .description h3,
#huge_it_portfolio_popup_list_<?php echo $portfolioID; ?> .popup-wrapper_<?php echo $portfolioID; ?> .right-block .description h4,
#huge_it_portfolio_popup_list_<?php echo $portfolioID; ?> .popup-wrapper_<?php echo $portfolioID; ?> .right-block .description h5,
#huge_it_portfolio_popup_list_<?php echo $portfolioID; ?> .popup-wrapper_<?php echo $portfolioID; ?> .right-block .description h6,
#huge_it_portfolio_popup_list_<?php echo $portfolioID; ?> .popup-wrapper_<?php echo $portfolioID; ?> .right-block .description p, 
#huge_it_portfolio_popup_list_<?php echo $portfolioID; ?> .popup-wrapper_<?php echo $portfolioID; ?> .right-block .description strong,
#huge_it_portfolio_popup_list_<?php echo $portfolioID; ?> .popup-wrapper_<?php echo $portfolioID; ?> .right-block .description span {
	padding:2px !important;
	margin:0px !important;
}

#huge_it_portfolio_popup_list_<?php echo $portfolioID; ?> .popup-wrapper_<?php echo $portfolioID; ?> .right-block .description ul,
#huge_it_portfolio_popup_list_<?php echo $portfolioID; ?> .popup-wrapper_<?php echo $portfolioID; ?> .right-block .description li {
	padding:2px 0px 2px 5px;
	margin:0px 0px 0px 8px;
}

#huge_it_portfolio_popup_list_<?php echo $portfolioID; ?> .popup-wrapper_<?php echo $portfolioID; ?> .right-block ul.thumbs-list_<?php echo $portfolioID; ?> {
	list-style:none;
	display:table;
	position:relative;
	clear:both;
	width:100%;
	margin:0px auto;
	padding:0px;
}

#huge_it_portfolio_popup_list_<?php echo $portfolioID; ?> .popup-wrapper_<?php echo $portfolioID; ?> .right-block ul.thumbs-list_<?php echo $portfolioID; ?> li {
	display:block;
	float:left;
	width:<?php echo $paramssld["ht_view2_thumbs_width"];?>px;
	height:<?php echo $paramssld["ht_view2_thumbs_height"];?>px;
	margin:0px 2% 5px 1% !important;
	opacity:0.45;
}

#huge_it_portfolio_popup_list_<?php echo $portfolioID; ?> .popup-wrapper_<?php echo $portfolioID; ?> .right-block ul.thumbs-list_<?php echo $portfolioID; ?> li.active,#huge_it_portfolio_popup_list_<?php echo $portfolioID; ?> .popup-wrapper_<?php echo $portfolioID; ?> .right-block ul.thumbs-list_<?php echo $portfolioID; ?> li:hover {
	opacity:1;
}

#huge_it_portfolio_popup_list_<?php echo $portfolioID; ?> .popup-wrapper_<?php echo $portfolioID; ?> .right-block ul.thumbs-list_<?php echo $portfolioID; ?> li a {
	display:block;
}

#huge_it_portfolio_popup_list_<?php echo $portfolioID; ?> .popup-wrapper_<?php echo $portfolioID; ?> .right-block ul.thumbs-list_<?php echo $portfolioID; ?> li img {
	margin:0px !important;
	padding:0px !important;
	width:<?php echo $paramssld["ht_view2_thumbs_width"];?>px !important;
	height:<?php echo $paramssld["ht_view2_thumbs_height"];?>px !important;
}


#huge_it_portfolio_popup_list_<?php echo $portfolioID; ?> .popup-wrapper_<?php echo $portfolioID; ?> .image-block_<?php echo $portfolioID; ?> iframe  {
	width:100% !important;
	height:100%;
	display:block;

}
/**/
#huge_it_portfolio_popup_list_<?php echo $portfolioID; ?> .heading-navigation_<?php echo $portfolioID; ?> .left-change, #huge_it_portfolio_popup_list_<?php echo $portfolioID; ?> .heading-navigation_<?php echo $portfolioID; ?> .right-change{
    position: relative;
    width: 40px;
    height: 39px;
    font-size: 25px;
    display: inline-block;
    text-align: center;
    border: 1px solid #eee;
    border-bottom: none;
    border-top: none;
}
#huge_it_portfolio_popup_list_<?php echo $portfolioID; ?> .heading-navigation_<?php echo $portfolioID; ?> .right-change{
	margin-left: -6px;
}
#huge_it_portfolio_popup_list_<?php echo $portfolioID; ?> .heading-navigation_<?php echo $portfolioID; ?> .right-change:hover, #huge_it_portfolio_popup_list_<?php echo $portfolioID; ?> .heading-navigation_<?php echo $portfolioID; ?> .left-change:hover{
    background: #ddd;
    border-color: #ccc;
    color: #000 !important;
    cursor: pointer;
}

#huge_it_portfolio_popup_list_<?php echo $portfolioID; ?> .heading-navigation_<?php echo $portfolioID; ?> .right-change a, #huge_it_portfolio_popup_list_<?php echo $portfolioID; ?> .heading-navigation_<?php echo $portfolioID; ?> .left-change a{
    position: absolute;
    transform: translate(-50%, 50%) !important;
    color: #777;
    text-decoration: none;
    width: 12px;
    height: 17px;
    display: inline-block;
}


/**/
.pupup-element .button-block {
	position:relative;
}

.pupup-element .button-block a,.pupup-element .button-block a:link,.pupup-element .button-block a:visited{
	position:relative;
	display:inline-block;
	padding:6px 12px;
	background:#<?php echo $paramssld["ht_view2_popup_linkbutton_background_color"];?>;
	color:#<?php echo $paramssld["ht_view2_popup_linkbutton_color"];?>;
	font-size:<?php echo $paramssld["ht_view2_popup_linkbutton_font_size"];?>px;
	text-decoration:none;
}

.pupup-element .button-block a:hover,.pupup-element .button-block a:focus,.pupup-element .button-block a:active {
	background:#<?php echo $paramssld["ht_view2_popup_linkbutton_background_hover_color"];?>;
	color:#<?php echo $paramssld["ht_view2_popup_linkbutton_font_hover_color"];?>;
}


#huge-popup-overlay-portfolio_<?php echo $portfolioID; ?> {
	position:fixed;
	top:0px;
	left:0px;
	width:100%;
	height:100%;
	z-index:199;
	background: <?php
			list($r,$g,$b) = array_map('hexdec',str_split($paramssld['ht_view2_popup_overlay_color'],2));
				$titleopacity=$paramssld["ht_view2_popup_overlay_transparency_color"]/100;						
				echo 'rgba('.$r.','.$g.','.$b.','.$titleopacity.')  !important'; 		
	?>
}


@media only screen and (max-width: 767px) {
	
	#huge_it_portfolio_popup_list_<?php echo $portfolioID; ?> {
		position:absolute;
		left:0px;
		top:0px;
		width:100%;
		height:auto !important;
		left:0px;
	}
	
	#huge_it_portfolio_popup_list_<?php echo $portfolioID; ?> li.pupup-element {
		margin:0px;
		height:auto !important;
		position:absolute;
		left:0px;
		top:0px;
	}

	#huge_it_portfolio_popup_list_<?php echo $portfolioID; ?> li.pupup-element .popup-wrapper_<?php echo $portfolioID; ?> {
		height:auto !important;
		overflow-y:auto;
	}


	#huge_it_portfolio_popup_list_<?php echo $portfolioID; ?> .popup-wrapper_<?php echo $portfolioID; ?> .image-block_<?php echo $portfolioID; ?> {
		width:100%;
		float:none;
		clear:both;
		margin-right:0px;
		border-right:0px;
	}

	#huge_it_portfolio_popup_list_<?php echo $portfolioID; ?> .popup-wrapper_<?php echo $portfolioID; ?> .right-block {
		width:100%;
		float:none;
		clear:both;
		margin-right:0px;
		border-right:0px;
	}

	#huge-popup-overlay-portfolio_<?php echo $portfolioID; ?> {
		position:fixed;
		top:0px;
		left:0px;
		width:100%;
		height:100%;
		z-index:199;
	}

}
#huge_it_portfolio_content_<?php echo $portfolioID; ?> a{
    border:none;
}

#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_options_<?php echo $portfolioID; ?> {
	position: relative;	
    <?php if ($paramssld["ht_view2_show_sorting"] == 'off')
    echo "display:none;";
    if($paramssld["ht_view2_filtering_float"] == 'left' && $paramssld["ht_view2_sorting_float"] == 'none') { if($portfolioShowFiltering == "on") { echo "margin-left: 30%;"; } else { echo "margin-left: 0%;"; } }
    //else if($paramssld["ht_view2_filtering_float"] == 'right' && $paramssld["ht_view2_sorting_float"] == 'none' || ($sorting_block_width == '100%' && $filtering_block_width == "100%")) { echo "margin-left: 1%;"; } ?>
    overflow: hidden;
    /*margin-top: 5px;*/
    float: <?php echo $paramssld["ht_view2_sorting_float"]; ?>;
    width: <?php echo $sorting_block_width; ?>;
}

#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_options_<?php echo $portfolioID; ?> ul {
  margin: 0px !important;
  padding: 0px !important;
  list-style: none;
<?php if($paramssld["ht_view2_sorting_float"] == 'top') {
      echo "float:left;margin-left:1%;";
      } ?>
}

#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul {
  margin: 0px !important;
  padding: 0px !important;
  overflow: hidden;
  <?php if($paramssld["ht_view2_filtering_float"] == 'top') {
      echo "float:left;margin-left:1%;";
      } ?>
}

<?php if($paramssld["ht_view2_sorting_float"] == 'none') { ?>
            #huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_options_<?php echo $portfolioID; ?> ul {
                float: left;
            }
    <?php } ?>
            
            
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_options_<?php echo $portfolioID; ?> ul li {
    border-radius: <?php echo $paramssld["ht_view2_sortbutton_border_radius"];?>px;
    list-style-type: none;
    margin: 0px !important;
	padding: 0;
    <?php
        if($sorting_block_width == "100%" ) {
            echo "float:left !important;margin: 4px 8px 4px 0px !important;";
        }
        if($left_to_top == "ok")
        { echo "float:left !important;"; }
        if($paramssld["ht_view2_sorting_float"] == "left" || $paramssld["ht_view2_sorting_float"] == "right")
        { echo 'border-bottom: 1px solid #ccc;'; }
        else
        { echo 'border: 1px solid #ccc;'; }
    ?>
}

#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_options_<?php echo $portfolioID; ?> ul li a {
    background-color: #<?php echo $paramssld["ht_view2_sortbutton_background_color"];?> !important;
    font-size:<?php echo $paramssld["ht_view2_sortbutton_font_size"];?>px !important;
    color:#<?php echo $paramssld["ht_view2_sortbutton_font_color"];?> !important;
    text-decoration: none;
    cursor: pointer;
    margin: 0px !important;
    display: block;
    padding:3px;
}

/*#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_options_<?php echo $portfolioID; ?> ul li:hover {
    
}*/

#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_options_<?php echo $portfolioID; ?> ul li a:hover {
    background-color: #<?php echo $paramssld["ht_view2_sortbutton_hover_background_color"];?> !important;
    color:#<?php echo $paramssld["ht_view2_sortbutton_hover_font_color"];?> !important;
    cursor: pointer;
}

#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?> {
    /*margin-top: 5px;*/
    float: <?php echo $paramssld["ht_view2_filtering_float"]; ?>;
    width: <?php echo $filtering_block_width; ?>;
    <?php
        if ($paramssld["ht_view2_show_filtering"] == 'off') echo "display:none;";
        if($paramssld["ht_view2_filtering_float"] == 'top' && ($paramssld["ht_view2_sorting_float"] == 'left') ) {  if($portfolioShowSorting == 'on') { echo "margin-left: 31%;"; } else echo "margin-left: 1%"; } 
        //if(($paramssld["ht_view2_filtering_float"] == 'none' && ($paramssld["ht_view2_sorting_float"] == 'right')) || ($sorting_block_width == '100%' && $filtering_block_width == "100%")) { echo "margin-left: 1%";}
    ?>
}

#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul li {
    list-style-type: none;
    <?php
        if($filtering_block_width == "100%") { echo "float:left !important;margin: 4px 8px 4px 0px !important;"; }
        if($left_to_top == "ok") { echo "float:left !important;"; }
        if($paramssld["ht_view2_filtering_float"] == "left" || $paramssld["ht_view2_filtering_float"] == "right")
        { echo 'border-bottom: 1px solid #ccc;'; }
        else echo "border: 1px solid #ccc;";
    ?>
}

#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul li a {
    font-size:<?php echo $paramssld["ht_view2_filterbutton_font_size"];?>px !important;
    color:#<?php echo $paramssld["ht_view2_filterbutton_font_color"];?> !important;
    background-color: #<?php echo $paramssld["ht_view2_filterbutton_background_color"];?> !important;
    border-radius: <?php echo $paramssld["ht_view2_filterbutton_border_radius"];?>px;
    padding: 3px;
    display: block;
    text-decoration: none;
}

#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?>  ul li a:hover {
    color:#<?php echo $paramssld["ht_view2_filterbutton_hover_font_color"];?> !important;
    background-color: #<?php echo $paramssld["ht_view2_filterbutton_hover_background_color"];?> !important;
    cursor: pointer
}
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul li.active a,
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul li.active a:link,
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul li.active a:visited,
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?>  ul li.active a:hover,
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?>  ul li.active a:focus,
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?>  ul li.active a:active {
    color:#<?php echo $paramssld["ht_view2_filterbutton_hover_font_color"];?> !important;
    background-color: #<?php echo $paramssld["ht_view2_filterbutton_hover_background_color"];?> !important;
    cursor: pointer;
}
#huge_it_portfolio_content_<?php echo $portfolioID; ?> section {
    position:relative;
    display:block;
}

#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_container_<?php echo $portfolioID; ?> {
<?php if($paramssld["ht_view2_sorting_float"] == "left" && $paramssld["ht_view2_filtering_float"] == "right" ||
         $paramssld["ht_view2_sorting_float"] == "right" && $paramssld["ht_view2_filtering_float"] == "left")
       { echo "margin: 0px auto;"; }
       if((($paramssld["ht_view2_filtering_float"] == "left" || $paramssld["ht_view2_filtering_float"] == "right" && $paramssld["ht_view2_sorting_float"] == "top") || ($paramssld["ht_view2_sorting_float"] == "left" || $paramssld["ht_view2_sorting_float"] == "right" && $paramssld["ht_view2_filting_float"] == "top")) && $portfolioShowFiltering == "on" && $portfolioShowSorting == "on")
       {
?>
        width: <?php echo $width_middle; ?> !important;
 <?php } ?>
}
@media screen and (max-width: 768px) {
	
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul li a,
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul li a:link,
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul li a:visited {
		font-size: 2vw !important;
	}
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_options_<?php echo $portfolioID; ?> ul li a {
		font-size:2vw !important;
	}

}
@media screen and (max-width: 480px) {
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_options_<?php echo $portfolioID; ?> {
	float: left;
}
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_options_<?php echo $portfolioID; ?> #sort-by{
	float: left;
	width: 100% !important;
}
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_options_<?php echo $portfolioID; ?> #sort-direction{
    float: left;
    width: 100% !important;
    position: relative;
    padding-left: 31% !important;
	right: 31%;
}
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul li a,
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul li a:link,
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul li a:visited {
		font-size: 3vw !important;
	}
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_options_<?php echo $portfolioID; ?> ul li a {
		line-height: 3vw;
		font-size:3vw !important;
	}
}
@media screen and (max-width: 420px) {
	
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul li a,
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul li a:link,
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul li a:visited {
		font-size: 4vw !important;
	}
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_options_<?php echo $portfolioID; ?> ul li a {
		font-size:4vw !important;
	}
}
@media screen and (max-width: <?php echo $paramssld['ht_view0_block_width']+2*$paramssld['ht_view0_element_border_width']+40; ?>px) {
   .portelement_<?php echo $portfolioID; ?>  {
		width:98%;
		margin: 1% !important;
		float: left;
		overflow: hidden;
		outline:none;
		border:<?php echo $paramssld['ht_view0_element_border_width']; ?>px solid #<?php echo $paramssld['ht_view0_element_border_color']; ?>;
    }
	.wd-portfolio-panel_<?php echo $portfolioID; ?> {
		width: 100% !important;
	}
}
</style>
<section id="huge_it_portfolio_content_<?php echo $portfolioID; ?>">
    <?php if($portfolioShowSorting == "on")
        { ?>
          <div id="huge_it_portfolio_options_<?php echo $portfolioID; ?>" class="">
            <ul id="sort-by" class="option-set clearfix" data-option-key="sortBy">
                <li><a href="#sortBy=original-order" data-option-value="original-order" class="selected" data><?php echo $paramssld["ht_view2_sorting_name_by_default"]; ?></a></li>
                <li><a href="#sortBy=id" data-option-value="id"><?php echo $paramssld["ht_view2_sorting_name_by_id"]; ?></a></li>
                <li><a href="#sortBy=symbol" data-option-value="symbol"><?php echo $paramssld["ht_view2_sorting_name_by_name"]; ?></a></li>
                <li id="shuffle"><a href='#shuffle'><?php echo $paramssld["ht_view2_sorting_name_by_random"]; ?></a></li>
            </ul>
              
            <ul id="sort-direction" class="option-set clearfix" data-option-key="sortAscending">
                <li><a href="#sortAscending=true" data-option-value="true" class="selected"><?php echo $paramssld["ht_view2_sorting_name_by_asc"]; ?></a></li>
                <li><a href="#sortAscending=false" data-option-value="false"><?php echo $paramssld["ht_view2_sorting_name_by_desc"]; ?></a></li>
            </ul>
          </div>
  <?php }
   if($portfolioShowFiltering == "on")
      { ?>
         <div id="huge_it_portfolio_filters_<?php echo $portfolioID; ?>">
            <ul>
                <li rel="*"><a><?php echo $paramssld["ht_view2_cat_all"];?></a></li>
                <?php
                $portfolioCats = explode(",", $portfolioCats);
                foreach ($portfolioCats as $portfolioCatsValue) {
                    if(!empty($portfolioCatsValue))
                    {
                ?>
                <li rel=".<?php echo str_replace(" ","_",$portfolioCatsValue); ?>"><a><?php echo str_replace("_"," ",$portfolioCatsValue); ?></a></li>
                    <?php
                    }
                }
                ?>
            </ul>
        </div>
<?php } ?>
    <div id="huge_it_portfolio_container_<?php echo $portfolioID; ?>" class="super-list variable-sizes clearfix" <?php if($paramssld["ht_view2_sorting_float"] == "top" && $paramssld["ht_view2_filtering_float"] == "top") echo "style='clear: both;'";?> style="margin-top: 5px;">
              <?php

              foreach($images as $key=>$row)
              {
                      $link = $row->sl_url;
                      $descnohtml=strip_tags($row->description);
                      $result = substr($descnohtml, 0, 50);
                      $catForFilter = explode(",", $row->category);
                      ?>
                      <div id="huge_it_portfolio_pupup_element_<?php echo $row->id; ?>_child" class="portelement_<?php echo $portfolioID; ?>  <?php foreach ($catForFilter as $catForFilterValue) { echo str_replace(" ","-",$catForFilterValue)." ";} ?>" tabindex="0" data-symbol="<?php echo $row->name; ?>" data-category="alkaline-earth">
                              <div class="image-block_<?php echo $portfolioID; ?>">
                                      <?php $imgurl=explode(";",$row->image_url); ?>
                                              <?php 	
											    if($row->image_url != ';'){
													switch(youtube_or_vimeo_portfolio($imgurl[0])) { 
														case 'image':		?>										  
														<img alt="<?php echo $row->name; ?>" id="wd-cl-img<?php echo $key; ?>" src="<?php echo get_huge_image($imgurl[0],$image_prefix); ?>" />
														<?php 
														break;
														case 'youtube':
														$videourl=get_video_id_from_url_portfolio($imgurl[0]);?>
														<img alt="<?php echo $row->name; ?>" id="wd-cl-img<?php echo $key; ?>"  src="//img.youtube.com/vi/<?php echo $videourl[0]; ?>/mqdefault.jpg"  />
														<?php
														break;
														case 'vimeo':
														$videourl=get_video_id_from_url_portfolio($imgurl[0]);
														$hash = unserialize(file_get_contents("http://vimeo.com/api/v2/video/".$videourl[0].".php"));
														$imgsrc=$hash[0]['thumbnail_large'];
													?>
														<img alt="<?php echo $row->name; ?>" src="<?php echo $imgsrc; ?>"  />
														<?php break;
											  
													}
											    }
											  else { ?>
                                              <img alt="<?php echo $row->name; ?>" id="wd-cl-img<?php echo $key; ?>" src="images/noimage.jpg" />
                                              <?php
                                              } ?>	
                                      <div class="image-overlay"><a href="#<?php echo $row->id; ?>"></a></div>
                              </div>
                              <div class="title-block_<?php echo $portfolioID; ?>">
                                      <h3><?php echo $row->name; ?></h3>
                                      <?php if($paramssld["ht_view2_element_show_linkbutton"]=='on'  && $link != '' ){?>
                                              <div class="button-block"><a href="<?php echo $row->sl_url; ?>" <?php if ($row->link_target=="on"){echo 'target="_blank"';}?> ><?php echo $paramssld["ht_view2_element_linkbutton_text"]; ?></a></div>
                                      <?php } ?>
                              </div>
                      </div>	
                      <?php
              }?>
              <div style="clear:both;"></div>
        </div>
</section>
<ul id="huge_it_portfolio_popup_list_<?php echo $portfolioID; ?>">
	<?php
	$changePopup=1;
	foreach($images as $key=>$row)
	{
		$imgurl=explode(";",$row->image_url);array_pop($imgurl);
		$link = $row->sl_url;
		$descnohtml=strip_tags($row->description);
		$result = substr($descnohtml, 0, 50);
		?>
		<li class="pupup-element" id="huge_it_portfolio_pupup_element_<?php echo $row->id; ?>">
			<div class="heading-navigation_<?php echo $portfolioID; ?>">
				<div style="display: inline-block; float: left;">
                        <div class="left-change" ><a href="#<?php echo $changePopup - 1; ?>" data-popupid="#<?php echo $row->id; ?>"><</a></div>
                        <div class="right-change" ><a href="#<?php echo $changePopup + 1; ?>" data-popupid="#<?php echo $row->id; ?>">></a></div>
                </div>
                <?php $changePopup=$changePopup+1; ?>
				<a href="#close" class="close"></a>
				<div style="clear:both;"></div>
			</div>
			<div class="popup-wrapper_<?php echo $portfolioID; ?>">
				<div class="image-block_<?php echo $portfolioID; ?>">
					<?php 	
					
					if($row->image_url != ';'){ 
						switch(youtube_or_vimeo_portfolio($imgurl[0])) {
							case 'image':
							?>
							<img alt="<?php echo $row->name; ?>" id="wd-cl-img<?php echo $key; ?>" src="<?php echo $imgurl[0]; ?>" />
							<?php 
							break;
							case 'youtube':
							$videourl=get_video_id_from_url_portfolio($imgurl[0]);//var_dump($videourl[0]);?>
							<iframe src="//www.youtube.com/embed/<?php echo $videourl[0]; ?>?modestbranding=1&showinfo=0" frameborder="0" allowfullscreen></iframe>
						<?php 
							break;
							case 'vimeo':
							$videourl=get_video_id_from_url_portfolio($imgurl[0]);//var_dump($videourl[0]);?>
							<iframe src="//player.vimeo.com/video/<?php echo $videourl[0]; ?>?title=0&amp;byline=0&amp;portrait=0" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
						<?php break;
						}
					}
					else { ?>
					<img alt="<?php echo $row->name; ?>" id="wd-cl-img<?php echo $key; ?>" src="images/noimage.jpg" />
					<?php
					} ?>					
				</div>
				<div class="right-block">
					<?php if($paramssld["ht_view2_show_popup_title"]=='on'){?><h3 class="title"><?php echo $row->name; ?></h3><?php } ?>
					
					<?php if($paramssld["ht_view2_thumbs_position"]=='before' and $paramssld["ht_view2_show_thumbs"] == 'on' && count($imgurl)!=1){?>
					<div><ul class="thumbs-list_<?php echo $portfolioID; ?>">
						<?php   
								foreach($imgurl as $key=>$img){
									?>
									<li>
									<?php
									switch(youtube_or_vimeo_portfolio($img)) {
										case 'image':?>
									
										<a href="<?php echo $row->sl_url; ?>" class="img-thumb" title="<?php echo $row->name; ?>"><img src="<?php echo $img; ?>"></a>
									
									<?php
										break;
										case 'youtube':
										$videourl=get_video_id_from_url_portfolio($img);?>
										<a href="https://www.youtube.com/embed/<?php echo $videourl[0];?>" class="video-thumb"  title="<?php echo $row->name; ?>" style="position:relative">
										<img src="//img.youtube.com/vi/<?php echo $videourl[0]; ?>/mqdefault.jpg"><div class="play-icon youtube-icon" title="<?php echo $videourl[0]; ?>"></div>
										</a>
								<?php	break;
										case 'vimeo':
										$videourl=get_video_id_from_url_portfolio($img);	
										$hash = unserialize(file_get_contents("http://vimeo.com/api/v2/video/".$videourl[0].".php"));
										$imgsrc=$hash[0]['thumbnail_large'];
										?>
										<a class=" video-thumb" href="http://player.vimeo.com/video/<?php echo $videourl[0]; ?>" title="<?php echo $row->name; ?>"  style="position:relative">
											<img src="<?php echo $imgsrc; ?>" alt="<?php echo $row->name; ?>" /><div class="play-icon vimeo-icon"  title="<?php echo $videourl[0]; ?>"></div>
										</a>
									<?php
										break;
										
									}
									?>
									</li>
								<?php 
								} ?>
					</ul></div>
					<?php } ?>
					
					<?php if($paramssld["ht_view2_show_description"]=='on'){?><div class="description"><?php echo $row->description; ?></div><?php } ?>
					
					<?php if($paramssld["ht_view2_thumbs_position"]=='after' and $paramssld["ht_view2_show_thumbs"] == 'on'  && count($imgurl)!=1){?>
					<div><ul class="thumbs-list_<?php echo $portfolioID; ?>">
						<?php   
								foreach($imgurl as $key=>$img){
									?>
									<li>
									<?php
									switch(youtube_or_vimeo_portfolio($img)) {
										case 'image':?>
									
										<a href="<?php echo $row->sl_url; ?>" class="img-thumb" title="<?php echo $row->name; ?>"><img src="<?php echo $img; ?>"></a>
									
									<?php
										break;
										case 'youtube':
										$videourl=get_video_id_from_url_portfolio($img);?>
										<a href="https://www.youtube.com/embed/<?php echo $videourl[0];?>" class=" video-thumb"  title="<?php echo $row->name; ?>" style="position:relative">
										<img src="//img.youtube.com/vi/<?php echo $videourl[0]; ?>/mqdefault.jpg"><div class="play-icon youtube-icon" title="<?php echo $videourl[0]; ?>"></div>
										</a>
								<?php	break;
										case 'vimeo':
										$videourl=get_video_id_from_url_portfolio($img);	
										$hash = unserialize(file_get_contents("http://vimeo.com/api/v2/video/".$videourl[0].".php"));
										$imgsrc=$hash[0]['thumbnail_large'];
										?>
										<a class="video-thumb" href="http://player.vimeo.com/video/<?php echo $videourl[0]; ?>" title="<?php echo $row->name; ?>"  style="position:relative">
											<img src="<?php echo $imgsrc; ?>" alt="<?php echo $row->name; ?>" /><div class="play-icon vimeo-icon"  title="<?php echo $videourl[0]; ?>"></div>
										</a>
									<?php
										break;
										
									}
									?>
									</li>
								<?php 
								} ?>
					</ul></div>
					<?php } ?>
					
					<?php if($paramssld["ht_view2_show_popup_linkbutton"]=='on' && $link != ''){?>
						<div class="button-block">
						<a href="<?php echo $link; ?>"  <?php if ($row->link_target=="on"){echo 'target="_blank"';}?>><?php echo $paramssld["ht_view2_popup_linkbutton_text"]; ?></a>
						</div>
					<?php } ?>
					<div style="clear:both;"></div>
				</div>
				<div style="clear:both;"></div>
			</div>
		</li>
		<?php
	}?>
</ul>
	  
  <?php	  
	break;
	////////////////////////////// VIEW 3 FullWidth ////////////////////////////////////////////// 
	case 3:
  ?>
<?php
    if($paramssld["ht_view3_sorting_float"] == "left" && $paramssld["ht_view3_filtering_float"] == "right" ||
       $paramssld["ht_view3_sorting_float"] == "right" && $paramssld["ht_view3_filtering_float"] == "left" ||
       $paramssld["ht_view3_sorting_float"] == $paramssld["ht_view3_filtering_float"])
       { $sorting_block_width ="20%"; $filtering_block_width ="20%"; $middle_with = "56%"; }
    else if($paramssld["ht_view3_sorting_float"] == "left" || $paramssld["ht_view3_sorting_float"] == "right" && $paramssld["ht_view3_filtering_float"] == "top")
       { $sorting_block_width ="30%"; $filtering_block_width ="100%"; $paramssld["ht_view3_filtering_float"] = "none"; $width_middle = "65%"; }
    else if($paramssld["ht_view3_filtering_float"] == "left" || $paramssld["ht_view3_filtering_float"] == "right" && $paramssld["ht_view3_sorting_float"] == "top")
       { $sorting_block_width ="100%"; $filtering_block_width ="30%"; $paramssld["ht_view3_sorting_float"] = "none"; $width_middle = "65%"; }
    if($paramssld["ht_view3_sorting_float"] == "top" && $paramssld["ht_view3_filtering_float"] == "top")
       { $sorting_block_width ="100%"; $filtering_block_width ="100%"; $left_to_top = "ok"; }
?>

<style type="text/css"> 
/***<add>***/
.portelement_<?php echo $portfolioID; ?> .play-icon.youtube-icon  {
	background: url(<?php echo  plugins_url( '../images/play.youtube.png' , __FILE__ );?>) center center no-repeat;
	background-size: 30% 30%;
}
.portelement_<?php echo $portfolioID; ?> .play-icon.vimeo-icon  {
	background: url(<?php echo  plugins_url( '../images/play.vimeo.png' , __FILE__ );?>) center center no-repeat;
	background-size: 30% 30%;
}
.portelement_<?php echo $portfolioID; ?> .play-icon {
    position: absolute;
    top: 0px;
    left: 0px;
    width: 100%;
    height: 100%;
}
.add-H-relative {
	position: relative;
}	
.add-H-block {
	display: block;
}		
/***</add>***/
.portelement_<?php echo $portfolioID; ?> {
	position: relative;
	width:95%; 
	margin:5px 0px 5px 0px;
	padding:2%;
	clear:both;
	overflow: hidden;
	border:<?php echo $paramssld['ht_view3_element_border_width']; ?>px solid #<?php echo $paramssld['ht_view3_element_border_color']; ?>;
	background:#<?php echo $paramssld['ht_view3_element_background_color']; ?>;
}

.portelement_<?php echo $portfolioID; ?> > div {
	display:table-cell;
}

.portelement_<?php echo $portfolioID; ?> div.left-block_<?php echo $portfolioID; ?> {
	padding-right:10px;
}

.portelement_<?php echo $portfolioID; ?> div.left-block_<?php echo $portfolioID; ?> .main-image-block_<?php echo $portfolioID; ?> {
	clear:both;
	width:<?php echo $paramssld['ht_view3_mainimage_width']; ?>px; 
}

.portelement_<?php echo $portfolioID; ?> div.left-block_<?php echo $portfolioID; ?> .main-image-block_<?php echo $portfolioID; ?> img {
	margin:0px !important;
	padding:0px !important;
	width:<?php echo $paramssld['ht_view3_mainimage_width']; ?>px !important; 
	height:auto;
}

.portelement_<?php echo $portfolioID; ?> div.left-block_<?php echo $portfolioID; ?> .thumbs-block {
	position:relative;
	margin-top:10px;
}

.portelement_<?php echo $portfolioID; ?> div.left-block_<?php echo $portfolioID; ?> .thumbs-block ul {
	width:<?php echo $paramssld['ht_view3_mainimage_width']; ?>px; 
	height:auto;
	display:table;
	margin:0px;
	padding:0px;
	list-style:none;
}

.portelement_<?php echo $portfolioID; ?> div.left-block_<?php echo $portfolioID; ?> .thumbs-block ul li {
	margin:0px 3px 0px 2px;
	padding:0px;
	width:<?php echo $paramssld['ht_view3_thumbs_width']; ?>px; 
	height:<?php echo $paramssld['ht_view3_thumbs_height']; ?>px; 
	float:left;
}

.portelement_<?php echo $portfolioID; ?> div.left-block_<?php echo $portfolioID; ?> .thumbs-block ul li a {
	display:block;
	width:<?php echo $paramssld['ht_view3_thumbs_width']; ?>px; 
	height:<?php echo $paramssld['ht_view3_thumbs_height']; ?>px; 
}

.portelement_<?php echo $portfolioID; ?> div.left-block_<?php echo $portfolioID; ?> .thumbs-block ul li a img {
	margin:0px !important;
	padding:0px !important;
	width:<?php echo $paramssld['ht_view3_thumbs_width']; ?>px; 
	height:<?php echo $paramssld['ht_view3_thumbs_height']; ?>px; 
}

.portelement_<?php echo $portfolioID; ?> div.right-block {
	vertical-align:top;
}

.portelement_<?php echo $portfolioID; ?> div.right-block > div {
	width:100%;
	padding-bottom:10px;
	margin-top:10px;
	<?php if($paramssld['ht_view3_show_separator_lines']=="on") {?>
		background:url('<?php echo  plugins_url( '../images/divider.line.png' , __FILE__ ); ?>') center bottom repeat-x;
	<?php } ?>	
}

.portelement_<?php echo $portfolioID; ?> div.right-block > div:last-child {
	background:none;
}

.portelement_<?php echo $portfolioID; ?> div.right-block .title-block_<?php echo $portfolioID; ?>  {
	margin-top:3px;
}

.portelement_<?php echo $portfolioID; ?> div.right-block .title-block_<?php echo $portfolioID; ?> h3 {
	margin:0px;
	padding:0px;
	font-weight:normal;
	font-size:<?php echo $paramssld['ht_view3_title_font_size']; ?>px !important;
	line-height:<?php echo $paramssld['ht_view3_title_font_size']+4; ?>px !important;
	color:#<?php echo $paramssld['ht_view3_title_font_color']; ?>;
}

.portelement_<?php echo $portfolioID; ?> div.right-block .description-block_<?php echo $portfolioID; ?> p,.portelement_<?php echo $portfolioID; ?> div.right-block .description-block_<?php echo $portfolioID; ?> * {
	margin:0px;
	padding:0px;
	font-size:<?php echo $paramssld['ht_view3_description_font_size']; ?>px;
	color:#<?php echo $paramssld['ht_view3_description_color']; ?>;
}


.portelement_<?php echo $portfolioID; ?> div.right-block .description-block_<?php echo $portfolioID; ?> h1,
.portelement_<?php echo $portfolioID; ?> div.right-block .description-block_<?php echo $portfolioID; ?> h2,
.portelement_<?php echo $portfolioID; ?> div.right-block .description-block_<?php echo $portfolioID; ?> h3,
.portelement_<?php echo $portfolioID; ?> div.right-block .description-block_<?php echo $portfolioID; ?> h4,
.portelement_<?php echo $portfolioID; ?> div.right-block .description-block_<?php echo $portfolioID; ?> h5,
.portelement_<?php echo $portfolioID; ?> div.right-block .description-block_<?php echo $portfolioID; ?> h6,
.portelement_<?php echo $portfolioID; ?> div.right-block .description-block_<?php echo $portfolioID; ?> p, 
.portelement_<?php echo $portfolioID; ?> div.right-block .description-block_<?php echo $portfolioID; ?> strong,
.portelement_<?php echo $portfolioID; ?> div.right-block .description-block_<?php echo $portfolioID; ?> span {
	padding:2px !important;
	margin:0px !important;
}

.portelement_<?php echo $portfolioID; ?> div.right-block .description-block_<?php echo $portfolioID; ?> ul,
.portelement_<?php echo $portfolioID; ?> div.right-block .description-block_<?php echo $portfolioID; ?> li {
	padding:2px 0px 2px 5px;
	margin:0px 0px 0px 8px;
}

.portelement_<?php echo $portfolioID; ?> .button-block {
	position:relative;
}

.portelement_<?php echo $portfolioID; ?> div.right-block .button-block a,.portelement_<?php echo $portfolioID; ?> div.right-block .button-block a:link,.portelement_<?php echo $portfolioID; ?> div.right-block .button-block a:visited {
	position:relative;
	display:inline-block;
	padding:6px 12px;
	background:#<?php echo $paramssld["ht_view3_linkbutton_background_color"];?>;
	color:#<?php echo $paramssld["ht_view3_linkbutton_color"];?>;
	font-size:<?php echo $paramssld["ht_view3_linkbutton_font_size"];?>px;
	text-decoration:none;
}

.portelement_<?php echo $portfolioID; ?> div.right-block .button-block a:hover,.pupup-elemen.element div.right-block .button-block a:focus,.portelement_<?php echo $portfolioID; ?> div.right-block .button-block a:active {
	background:#<?php echo $paramssld["ht_view3_linkbutton_background_hover_color"];?>;
	color:#<?php echo $paramssld["ht_view3_linkbutton_font_hover_color"];?>;
}



@media only screen and (max-width: 767px) {
	
	.portelement_<?php echo $portfolioID; ?> > div {
		display:block;
		width:100%;
		clear:both;
	}

	.portelement_<?php echo $portfolioID; ?> div.left-block_<?php echo $portfolioID; ?> {
		padding-right:0px;
	}

	.portelement_<?php echo $portfolioID; ?> div.left-block_<?php echo $portfolioID; ?> .main-image-block_<?php echo $portfolioID; ?> {
		clear:both;
		width:100%; 
	}

	.portelement_<?php echo $portfolioID; ?> div.left-block_<?php echo $portfolioID; ?> .main-image-block_<?php echo $portfolioID; ?> img {
		margin:0px !important;
		padding:0px !important;
		width:100% !important;  
		height:auto;
	}

	.portelement_<?php echo $portfolioID; ?> div.left-block_<?php echo $portfolioID; ?> .thumbs-block ul {
		width:100%; 
	}
}
#huge_it_portfolio_content_<?php echo $portfolioID; ?> a{
    border:none;
}

#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_options_<?php echo $portfolioID; ?> {
	position: relative;
    <?php if ($paramssld["ht_view3_show_sorting"] == 'off')
    echo "display:none;";
    if($paramssld["ht_view3_filtering_float"] == 'left' && $paramssld["ht_view3_sorting_float"] == 'none' && $portfolioShowFiltering == 'on') { echo "margin-left: 30%;"; }
//    else if($paramssld["ht_view3_filtering_float"] == 'right' && $paramssld["ht_view3_sorting_float"] == 'none' || ($sorting_block_width == '100%' && $filtering_block_width == "100%")) { echo "margin-left: 1%;"; } ?>
    overflow: hidden;
    margin-top: 5px;
    float: <?php echo $paramssld["ht_view3_sorting_float"]; ?>;
    width: <?php echo $sorting_block_width; ?>;
}

#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_options_<?php echo $portfolioID; ?> ul {
  margin: 0px !important;
  padding: 0px !important;
  list-style: none;
<?php if($paramssld["ht_view3_sorting_float"] == 'top') {
      echo "float:left;margin-left:1%;";
      } ?>
}

#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul {
  margin: 0px !important;
  padding: 0px !important;
  overflow: hidden;
  <?php if($paramssld["ht_view3_filtering_float"] == 'top') {
      echo "float:left;margin-left:1%;";
      } ?>
}

<?php if($paramssld["ht_view3_sorting_float"] == 'none') { ?>
            #huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_options_<?php echo $portfolioID; ?> ul {
                float: left;
            }
    <?php } ?>

#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_options_<?php echo $portfolioID; ?> ul li {
    border-radius: <?php echo $paramssld["ht_view3_sortbutton_border_radius"];?>px;
    list-style-type: none;
    margin: 0px !important;
	padding: 0;
    <?php
        if($sorting_block_width == "100%" ) {
            echo "float:left !important;margin: 4px 8px 4px 0px !important;";
        }
		if(isset($left_to_top)){
        if($left_to_top == "ok")
        { echo "float:left !important;"; }
		}
        if($paramssld["ht_view3_sorting_float"] == "left" || $paramssld["ht_view3_sorting_float"] == "right")
        { echo 'border-bottom: 1px solid #ccc;'; }
        else
        { echo 'border: 1px solid #ccc;'; }
    ?>
}

#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_options_<?php echo $portfolioID; ?> ul li a {
    background-color: #<?php echo $paramssld["ht_view3_sortbutton_background_color"];?> !important;
    font-size:<?php echo $paramssld["ht_view3_sortbutton_font_size"];?>px !important;
    color:#<?php echo $paramssld["ht_view3_sortbutton_font_color"];?> !important;
    text-decoration: none;
    cursor: pointer;
    margin: 0px !important;
    display: block;
    padding:3px;
}

/*#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_options_<?php echo $portfolioID; ?> ul li:hover {
    
}*/

#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_options_<?php echo $portfolioID; ?> ul li a:hover {
    background-color: #<?php echo $paramssld["ht_view3_sortbutton_hover_background_color"];?> !important;
    color:#<?php echo $paramssld["ht_view3_sortbutton_hover_font_color"];?> !important;
    cursor: pointer;
}

#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?> {
    margin-top: 5px;
    float: <?php echo $paramssld["ht_view3_filtering_float"]; ?>;
   width: <?php echo $filtering_block_width; ?>;
    <?php
        if ($paramssld["ht_view3_show_filtering"] == 'off') echo "display:none;";
        if($paramssld["ht_view3_filtering_float"] == 'none' && ($paramssld["ht_view3_sorting_float"] == 'left') ) { echo "margin-left: 31%";} 
//        if(($paramssld["ht_view3_filtering_float"] == 'none' && ($paramssld["ht_view3_sorting_float"] == 'right')) || ($sorting_block_width == '100%' && $filtering_block_width == "100%")) { echo "margin-left: 1%";}
    ?>
}

#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul li {
    list-style-type: none;
    <?php
        if($filtering_block_width == "100%") { echo "float:left !important;margin: 4px 8px 4px 0px !important;"; }
		if(isset($left_to_top)){
        if($left_to_top == "ok") { echo "float:left !important;"; }
		}
        if($paramssld["ht_view3_filtering_float"] == "left" || $paramssld["ht_view3_filtering_float"] == "right")
        { echo 'border-bottom: 1px solid #ccc;'; }
        else echo "border: 1px solid #ccc;";
    ?>
}

#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul li a {
    font-size:<?php echo $paramssld["ht_view3_filterbutton_font_size"];?>px !important;
    color:#<?php echo $paramssld["ht_view3_filterbutton_font_color"];?> !important;
    background-color: #<?php echo $paramssld["ht_view3_filterbutton_background_color"];?> !important;
    border-radius: <?php echo $paramssld["ht_view3_filterbutton_border_radius"];?>px;
    padding: 3px;
    display: block;
    text-decoration: none;
}

#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?>  ul li a:hover {
    color:#<?php echo $paramssld["ht_view3_filterbutton_hover_font_color"];?> !important;
    background-color: #<?php echo $paramssld["ht_view3_filterbutton_hover_background_color"];?> !important;
    cursor: pointer;
}
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul li.active a,
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul li.active a:link,
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul li.active a:visited,
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?>  ul li.active a:hover,
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?>  ul li.active a:focus,
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?>  ul li.active a:active {
    color:#<?php echo $paramssld["ht_view3_filterbutton_hover_font_color"];?> !important;
    background-color: #<?php echo $paramssld["ht_view3_filterbutton_hover_background_color"];?> !important;
    cursor: pointer;
}
#huge_it_portfolio_content_<?php echo $portfolioID; ?> section {
    position:relative;
    display:block;
}

#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_container_<?php echo $portfolioID; ?> {
    <?php
        if($paramssld["ht_view3_sorting_float"] == "left" && $paramssld["ht_view3_filtering_float"] == "right" ||
           $paramssld["ht_view3_sorting_float"] == "right" && $paramssld["ht_view3_filtering_float"] == "left")
        { ?>
            margin-left: 21%;
  <?php } 
  if((($paramssld["ht_view3_filtering_float"] == "left" || $paramssld["ht_view3_filtering_float"] == "right" && $paramssld["ht_view3_sorting_float"] == "top") || ($paramssld["ht_view3_sorting_float"] == "left" || $paramssld["ht_view3_sorting_float"] == "right" && $paramssld["ht_view3_filting_float"] == "top")) && $portfolioShowFiltering == "on" && $portfolioShowSorting == "on")
       { ?>
        width: <?php echo $width_middle; ?> !important;
 <?php } ?>
}
</style>
      
<section id="huge_it_portfolio_content_<?php echo $portfolioID; ?>">
    <?php if($portfolioShowSorting == "on")
        { ?>
          <div id="huge_it_portfolio_options_<?php echo $portfolioID; ?>" class="">
            <ul id="sort-by" class="option-set clearfix" data-option-key="sortBy">
                <li><a href="#sortBy=original-order" data-option-value="original-order" class="selected" data><?php echo $paramssld["ht_view3_sorting_name_by_default"]; ?></a></li>
                <li><a href="#sortBy=id" data-option-value="id"><?php echo $paramssld["ht_view3_sorting_name_by_id"]; ?></a></li>
                <li><a href="#sortBy=symbol" data-option-value="symbol"><?php echo $paramssld["ht_view3_sorting_name_by_name"]; ?></a></li>
                <li id="shuffle"><a href='#shuffle'><?php echo $paramssld["ht_view3_sorting_name_by_random"]; ?></a></li>
            </ul>
              
            <ul id="sort-direction" class="option-set clearfix" data-option-key="sortAscending">
                <li><a href="#sortAscending=true" data-option-value="true" class="selected"><?php echo $paramssld["ht_view3_sorting_name_by_asc"]; ?></a></li>
                <li><a href="#sortAscending=false" data-option-value="false"><?php echo $paramssld["ht_view3_sorting_name_by_desc"]; ?></a></li>
            </ul>
          </div>
  <?php }
   if($portfolioShowFiltering == "on")
      { ?>
         <div id="huge_it_portfolio_filters_<?php echo $portfolioID; ?>">
            <ul>
                <li rel="*"><a><?php echo $paramssld["ht_view3_cat_all"];?></a></li>
                <?php
                $portfolioCats = explode(",", $portfolioCats);
                foreach ($portfolioCats as $portfolioCatsValue) {
                    if(!empty($portfolioCatsValue))
                    {
                ?>
                <li rel=".<?php echo str_replace(" ","_",$portfolioCatsValue); ?>"><a><?php echo str_replace("_"," ",$portfolioCatsValue); ?></a></li>
                    <?php
                    }
                }
                ?>
            </ul>
        </div>
<?php } ?> 
       <div id="huge_it_portfolio_container_<?php echo $portfolioID; ?>" class="super-list variable-sizes clearfix" <?php if($paramssld["ht_view3_sorting_float"] == "top" && $paramssld["ht_view3_filtering_float"] == "top") echo "style='clear: both;'";?>>
              <?php
					$group_key1=0;
              foreach($images as $key=>$row)
              {			
                      $group_key1++;
                      $group_key = (string)$group_key1;
					  $portfolioID1 = (string)$portfolioID;
					  $group_key =$group_key."-".$portfolioID;
                      $link = $row->sl_url;
                      $catForFilter = explode(",", $row->category);
                      ?>
                      <div class="portelement_<?php echo $portfolioID; ?> colorbox_grouping  <?php foreach ($catForFilter as $catForFilterValue) { echo str_replace(" ","-",$catForFilterValue)." ";} ?>" data-symbol="<?php echo $row->name; ?>" data-category="alkaline-earth">
                              <div class="left-block_<?php echo $portfolioID; ?>">
                                      <div class="main-image-block_<?php echo $portfolioID; ?> add-H-relative" >
                                              <?php $imgurl=explode(";",$row->image_url); ?>
                                              <?php 	
											  if($row->image_url != ';') {
												  switch(youtube_or_vimeo_portfolio($imgurl[0])) {
													case 'image':
												  
												  ?>
														<a href="<?php echo $imgurl[0]; ?>" class=" portfolio-group<?php echo $group_key; ?>" title="<?php echo $row->name; ?>" ><img alt="<?php echo $row->name; ?>" id="wd-cl-img<?php echo $key; ?>" src="<?php echo get_huge_image($imgurl[0],$image_prefix); ?>"></a>
												<?php 
													break;
													case 'youtube':
													$videourl=get_video_id_from_url_portfolio($imgurl[0]);?>
														<a href="https://www.youtube.com/embed/<?php echo $videourl[0];?>" class="huge_it_portfolio_item youtube portfolio-group<?php echo $group_key;?> add-H-block"  title="<?php echo $row->name; ?>"  >
														<img src="//img.youtube.com/vi/<?php echo $videourl[0]; ?>/mqdefault.jpg"><div class="play-icon <?php echo $videourl[1];?>-icon"></div></a>
																			
											  <?php break;
													case 'vimeo':
													$videourl=get_video_id_from_url_portfolio($imgurl[0]);
													$hash = unserialize(file_get_contents("http://vimeo.com/api/v2/video/".$videourl[0].".php"));
													$imgsrc=$hash[0]['thumbnail_large'];?>
													<a class="huge_it_portfolio_item vimeo portfolio-group<?php echo $group_key;?> add-H-block" href="http://player.vimeo.com/video/<?php echo $videourl[0]; ?> " title="<?php echo $row->name; ?>" >
														<img src="<?php echo $imgsrc; ?>" alt="<?php echo $row->name; ?>" /><div class="play-icon <?php echo $videourl[1];?>-icon"></div>
													</a>
											  <?php
												}
											 }
											  else { ?>
                                                      <a href="<?php echo $imgurl[0]; ?>" class=" portfolio-group<?php echo $group_key; ?>"><img alt="<?php echo $row->name; ?>" id="wd-cl-img<?php echo $key; ?>" src="images/noimage.jpg"></a>
                                              <?php
                                              }
                                              ?>
                                      </div>
                                      <div class="thumbs-block">
                                          <?php
                                          if($paramssld["ht_view3_show_thumbs"] == 'on')
                                              {
                                          ?>
                                              <ul class="thumbs-list_<?php echo $portfolioID; ?>">					
                                                      <?php
                                                      $imgurl=explode(";",$row->image_url);
                                                      array_pop($imgurl);
                                                      array_shift($imgurl);

                                                      foreach($imgurl as $key=>$img)
                                                      {
														  switch(youtube_or_vimeo_portfolio($img)) {
															  case 'image':
                                                              ?>
                                                                      <li><a href="<?php echo $img;?>" class=" portfolio-group<?php echo $group_key; ?> "  title = "<?php echo $row->name; ?>"><img src="<?php echo get_huge_image($img,$image_prefix); ?>"></a></li>
                                                      <?php   break;
															  case 'youtube':
																	 $videourl=get_video_id_from_url_portfolio($img);?>
																	  <li><a href="https://www.youtube.com/embed/<?php echo $videourl[0];?>" class="huge_it_portfolio_item youtube portfolio-group<?php echo $group_key;?>  add-H-relative"  title="<?php echo $row->name; ?>" >
																	  <img src="//img.youtube.com/vi/<?php echo $videourl[0]; ?>/mqdefault.jpg"><div class="play-icon <?php echo $videourl[1];?>-icon"></div></a>
																	  </li>
														  <?php
															  break;
															  case 'vimeo':
																   $videourl=get_video_id_from_url_portfolio($img);
																   $hash = unserialize(file_get_contents("http://vimeo.com/api/v2/video/".$videourl[0].".php"));
																	$imgsrc=$hash[0]['thumbnail_large'];?>
																	<li>
																	<a class="huge_it_portfolio_item vimeo portfolio-group<?php echo $group_key;?>  add-H-relative" href="http://player.vimeo.com/video/<?php echo $videourl[0]; ?>" title="<?php echo $row->name; ?>">
																		<img src="<?php echo $imgsrc; ?>" alt="<?php echo $row->name; ?>" /><div class="play-icon <?php echo $videourl[1];?>-icon"></div>
																	</a>
																	</li>																	
															  <?php
																break;	
															  }
															  
                                                      }
                                                      ?>
                                              </ul>
                                          <?php
                                          }
                                          ?>
                                      </div>
                              </div>
                              <div class="right-block">
                                <?php if($row->name!=''){?><div class="title-block_<?php echo $portfolioID; ?>"><h3><?php echo $row->name; ?></h3></div><?php } ?>
                                <?php
                                if($paramssld["ht_view3_show_description"] == 'on')
                                    {
                                      if($row->description!='')
                                          { ?>
                                          <div class="description-block_<?php echo $portfolioID; ?>"><p><?php echo $row->description; ?></p></div>
                                    <?php } ?>
                              <?php }

                                      if($link!='')
                                      { 
                                      if($paramssld["ht_view3_show_linkbutton"] == 'on' && $paramssld["ht_view3_linkbutton_text"] != '' && $link != '') {
                                      ?>
                                          <div class="button-block">
                                                  <a href="<?php echo $link; ?>" <?php if ($row->link_target=="on"){echo 'target="_blank"';}?>><?php echo $paramssld["ht_view3_linkbutton_text"]; ?></a>
                                          </div>
                                      <?php }
                                      } ?>
                              </div>
                      </div>

          <?php
              }
              ?>

        </div>
 </section>
  
<script>
jQuery(function(){
var defaultBlockWidth=<?php echo $paramssld['ht_view3_mainimage_width']; ?>;
    
    var $container = jQuery('#huge_it_portfolio_container_<?php echo $portfolioID; ?>');
    
    
      // add randomish size classes
      $container.find('.portelement_<?php echo $portfolioID; ?>').each(function(){
        var $this = jQuery(this),
            number = parseInt( $this.find('.number').text(), 10 );
			//alert(number);
        if ( number % 7 % 2 === 1 ) {
          $this.addClass('width2');
        }
        if ( number % 3 === 0 ) {
          $this.addClass('height2');
        }
      });
    
    $container.hugeitmicro({
      itemSelector : '.portelement_<?php echo $portfolioID; ?>',
      masonry : { 
        columnWidth : <?php echo $paramssld["ht_view3_mainimage_width"]; ?>+20+<?php echo $paramssld["ht_view3_element_border_width"]*2; ?>
      },
      masonryHorizontal : {
        rowHeight: 300+20
      },
      cellsByRow : {
        columnWidth : 300+20,
        rowHeight : 240
      },
      cellsByColumn : {
        columnWidth : 300+20,
        rowHeight : 240
      },
      getSortData : {
        symbol : function( $elem ) {
          return $elem.attr('data-symbol');
        },
        category : function( $elem ) {
          return $elem.attr('data-category');
        },
        number : function( $elem ) {
          return parseInt( $elem.find('.number').text(), 10 );
        },
        weight : function( $elem ) {
          return parseFloat( $elem.find('.weight').text().replace( /[\(\)]/g, '') );
        },
        id : function ( $elem ) {
          return $elem.find('.id').text();
        }
      }
    });
    
    
      var $optionSets = jQuery('#huge_it_portfolio_options_<?php echo $portfolioID; ?> .option-set'),
          $optionLinks = $optionSets.find('a');

      $optionLinks.click(function(){
        var $this = jQuery(this);

        if ( $this.hasClass('selected') ) {
          return false;
        }
        var $optionSet = $this.parents('.option-set');
        $optionSet.find('.selected').removeClass('selected');
        $this.addClass('selected');
  

        var options = {},
            key = $optionSet.attr('data-option-key'),
            value = $this.attr('data-option-value');

        value = value === 'false' ? false : value;
        options[ key ] = value;
        if ( key === 'layoutMode' && typeof changeLayoutMode === 'function' ) {

          changeLayoutMode( $this, options )
        } else {

          $container.hugeitmicro( options );
        }
        
        return false;
      });


    

      var isHorizontal = false;
      function changeLayoutMode( $link, options ) {
        var wasHorizontal = isHorizontal;
        isHorizontal = $link.hasClass('horizontal');

        if ( wasHorizontal !== isHorizontal ) {

          var style = isHorizontal ? 
            { height: '75%', width: $container.width() } : 
            { width: 'auto' };

          $container.filter(':animated').stop();

          $container.addClass('no-transition').css( style );
          setTimeout(function(){
            $container.removeClass('no-transition').hugeitmicro( options );
          }, 100 )
        } else {
          $container.hugeitmicro( options );
        }
      }


    

      $container.delegate( '.default-block_<?php echo $portfolioID; ?>', 'click', function(){
          var strheight=0;
          jQuery(this).parents('.portelement_<?php echo $portfolioID; ?>').find('.wd-portfolio-panel_<?php echo $portfolioID; ?> > div').each(function(){
                strheight+=jQuery(this).outerHeight()+10;
                //alert(strheight);
          })
          strheight+=<?php echo $paramssld['ht_view0_block_height']+45; ?>;
	  			if(jQuery(this).parents('.portelement_<?php echo $portfolioID; ?>').hasClass("large")){
			jQuery(this).parents('.portelement_<?php echo $portfolioID; ?>').animate({
				height: "<?php echo $paramssld['ht_view0_block_height']+45; ?>px"
			}, 300, function() {
				jQuery(this).removeClass('large');
				$container.hugeitmicro('reLayout');
			});
			
			jQuery(this).parents('.portelement_<?php echo $portfolioID; ?>').removeClass("active");
			return false;
		}
		
	
		jQuery(this).parents('.portelement_<?php echo $portfolioID; ?>').css({height:strheight});
		jQuery(this).parents('.portelement_<?php echo $portfolioID; ?>').addClass('large');

		$container.hugeitmicro('reLayout');
		jQuery(this).parents('.portelement_<?php echo $portfolioID; ?>').css({height:"<?php echo $paramssld['ht_view0_block_height']+45; ?>px"});		 
		 
		//alert(strheight);
		 
		 jQuery(this).parents('.portelement_<?php echo $portfolioID; ?>').animate({
			height:strheight+"px",
		  }, 300,function(){	$container.hugeitmicro('reLayout');});
	});

    var $sortBy =  jQuery('#huge_it_portfolio_content_<?php echo $portfolioID; ?> #sort-by');
    jQuery('#huge_it_portfolio_content_<?php echo $portfolioID; ?> #shuffle a').click(function(){
      $container.hugeitmicro('shuffle');
      $sortBy.find('.selected').removeClass('selected');
      $sortBy.find('[data-option-value="random"]').addClass('selected');
      return false;
    });
    
    ////filteringgggggg

        // bind filter on select change
        jQuery(document).ready(function(){
            jQuery('#huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul li').click(function() {
              // get filter value from option value
              var filterValue = jQuery(this).attr('rel');
              // use filterFn if matches value
              filterValue = filterValue;//filterFns[ filterValue ] || 
              $container.hugeitmicro({ filter: filterValue });
            });
            <?php if(($paramssld["ht_view3_sorting_float"] == "left" || $paramssld["ht_view3_sorting_float"] == "right") && $paramssld["ht_view3_filtering_float"] == "none")
                  { ?>
                        var topmargin = jQuery("#huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul").height();
                        jQuery("#huge_it_portfolio_options_<?php echo $portfolioID; ?>").css({'margin-top':parseInt(topmargin) + 5});
            <?php }
            else  {
                    if(($paramssld["ht_view3_filtering_float"] == "left" || $paramssld["ht_view3_filtering_float"] == "right") && $paramssld["ht_view3_sorting_float"] == "none")
                      { ?>
                         var topmargin = jQuery("#huge_it_portfolio_options_<?php echo $portfolioID; ?>").height();
                         jQuery("#huge_it_portfolio_filters_<?php echo $portfolioID; ?>").css({'margin-top':'5px'});
                <?php }
                  } ?>
        });
        
        //end of filtering
        
        jQuery(window).load(function(){
		$container.hugeitmicro('reLayout');
		jQuery(window).resize(function(){$container.hugeitmicro('reLayout');});
	});

  });
</script>
	  
	  <?php
	    
        break;
		
/////////////////////////////////// VIEW 4 FAQ ////////////////////////////////////
	case 4;

?>
<?php
    if($paramssld["ht_view4_sorting_float"] == "left" && $paramssld["ht_view4_filtering_float"] == "right" ||
       $paramssld["ht_view4_sorting_float"] == "right" && $paramssld["ht_view4_filtering_float"] == "left" ||
       $paramssld["ht_view4_sorting_float"] == $paramssld["ht_view4_filtering_float"])
       { $sorting_block_width ="20%"; $filtering_block_width ="20%"; $middle_with = "56%"; }
    else if($paramssld["ht_view4_sorting_float"] == "left" || $paramssld["ht_view4_sorting_float"] == "right" && $paramssld["ht_view4_filtering_float"] == "top")
       { $sorting_block_width ="30%"; $filtering_block_width ="100%"; $paramssld["ht_view4_filtering_float"] = "none"; $width_middle = "65%"; }
    else if($paramssld["ht_view4_filtering_float"] == "left" || $paramssld["ht_view4_filtering_float"] == "right" && $paramssld["ht_view4_sorting_float"] == "top")
       { $sorting_block_width ="100%"; $filtering_block_width ="30%"; $paramssld["ht_view4_sorting_float"] = "none"; $width_middle = "65%"; }
    if($paramssld["ht_view4_sorting_float"] == "top" && $paramssld["ht_view4_filtering_float"] == "top")
       { $sorting_block_width ="100%"; $filtering_block_width ="100%"; $left_to_top = "ok"; }
?>

<style type="text/css"> 
.portelement_<?php echo $portfolioID; ?> {
	background:#<?php echo $paramssld['ht_view4_element_background_color']?>;
	max-width:<?php echo $paramssld['ht_view4_block_width']; ?>px;
	width: 100%;
	height:45px;
	margin: 5px;
	float: left;
	overflow: hidden;
	position: relative;
	outline:none;
	border:<?php echo $paramssld['ht_view4_element_border_width']; ?>px solid #<?php echo $paramssld['ht_view4_element_border_color']; ?>;
}

.portelement_<?php echo $portfolioID; ?>.large,
.variable-sizes .portelement_<?php echo $portfolioID; ?>.large,
.variable-sizes .portelement_<?php echo $portfolioID; ?>.large.width2.height2 {
	width: <?php echo $paramssld['ht_view4_block_width']; ?>px;
	z-index: 10;
}


.title-block_<?php echo $portfolioID; ?> {
	position:relative;
	display:block;
	height:45px;
	padding:10px 0px 0px 0px;
	width:<?php echo $paramssld['ht_view4_block_width']; ?>px;
       /* max-width: 467px;*/
}

.title-block_<?php echo $portfolioID; ?> h3 {
	position:relative;
	margin:0px !important;
	padding:0px 5px 0px 5px;
	max-width:<?php echo $paramssld['ht_view4_block_width']-40; ?>px;
	width: 100%;
	text-overflow: ellipsis;
	overflow: hidden; 
	white-space:nowrap;
	font-weight:normal;
	color:#<?php echo $paramssld['ht_view4_title_font_color']; ?>;
	font-size:<?php echo $paramssld['ht_view4_title_font_size']; ?>px;
	line-height: <?php echo $paramssld['ht_view4_title_font_size'] + 4; ?>px !important;
}

.portelement_<?php echo $portfolioID; ?> .title-block_<?php echo $portfolioID; ?> .open-close-button {
	width:20px;
	height:20px;
	position:absolute;
	top:13px;
	right:5px;
	background:url('<?php echo  plugins_url( '../images/open-close.'.$paramssld['ht_view4_togglebutton_style'].'.png' , __FILE__ ); ?>') left top no-repeat;
	z-index:5;
	cursor:pointer;
	opacity:0.33;
}

.portelement_<?php echo $portfolioID; ?>:hover .title-block_<?php echo $portfolioID; ?> .open-close-button {opacity:1;}

.portelement_<?php echo $portfolioID; ?>.large .open-close-button {
	background:url('<?php echo  plugins_url( '../images/open-close.'.$paramssld['ht_view4_togglebutton_style'].'.png' , __FILE__ ); ?>') left bottom no-repeat;
}

.wd-portfolio-panel_<?php echo $portfolioID; ?> {
	position:relative;
	clear:both;
	display:block;
	width:<?php echo $paramssld['ht_view4_block_width']-10; ?>px;
	margin:0px 5px 0px 5px !important;
	padding:0px;
	text-align:left;
	z-index:6; 
}

.wd-portfolio-panel_<?php echo $portfolioID; ?> .description-block_<?php echo $portfolioID; ?> p,.portelement_<?php echo $portfolioID; ?> div.right-block .description-block_<?php echo $portfolioID; ?> * {	
	text-align:justify;
	font-size:<?php echo $paramssld['ht_view4_description_font_size']; ?>px;
	color:#<?php echo $paramssld['ht_view4_description_color']; ?>;
	margin:0px;
	padding:0px;
}

.wd-portfolio-panel_<?php echo $portfolioID; ?> .description-block_<?php echo $portfolioID; ?> h1,
.wd-portfolio-panel_<?php echo $portfolioID; ?> .description-block_<?php echo $portfolioID; ?> h2,
.wd-portfolio-panel_<?php echo $portfolioID; ?> .description-block_<?php echo $portfolioID; ?> h3,
.wd-portfolio-panel_<?php echo $portfolioID; ?> .description-block_<?php echo $portfolioID; ?> h4,
.wd-portfolio-panel_<?php echo $portfolioID; ?> .description-block_<?php echo $portfolioID; ?> h5,
.wd-portfolio-panel_<?php echo $portfolioID; ?> .description-block_<?php echo $portfolioID; ?> h6,
.wd-portfolio-panel_<?php echo $portfolioID; ?> .description-block_<?php echo $portfolioID; ?> p, 
.wd-portfolio-panel_<?php echo $portfolioID; ?> .description-block_<?php echo $portfolioID; ?> strong,
.wd-portfolio-panel_<?php echo $portfolioID; ?> .description-block_<?php echo $portfolioID; ?> span {
	line-height:auto !important;
	padding:2px !important;
	margin:0px !important;
}

.wd-portfolio-panel_<?php echo $portfolioID; ?> .description-block_<?php echo $portfolioID; ?> ul,
.wd-portfolio-panel_<?php echo $portfolioID; ?> .description-block_<?php echo $portfolioID; ?> li {
	padding:2px 0px 2px 5px;
	margin:0px 0px 0px 8px;
}

.wd-portfolio-panel_<?php echo $portfolioID; ?> > div {
	padding-top:10px;
	margin-bottom:10px;
	<?php if($paramssld['ht_view4_show_separator_lines']=="on") {?>
		background:url('<?php echo  plugins_url( '../images/divider.line.png' , __FILE__ ); ?>') center top repeat-x;
	<?php } ?>
}

.wd-portfolio-panel_<?php echo $portfolioID; ?> .button-block {
	padding:10px 0px 10px 0px;
}

.wd-portfolio-panel_<?php echo $portfolioID; ?> .button-block a, .wd-portfolio-panel_<?php echo $portfolioID; ?> .button-block a:link, .wd-portfolio-panel_<?php echo $portfolioID; ?> .button-block a:visited {
	padding:6px 12px;
	display:inline-block;
	font-size:<?php echo $paramssld['ht_view4_linkbutton_font_size']; ?>px;
	background:#<?php echo $paramssld['ht_view4_linkbutton_background_color']; ?>;
	color:#<?php echo $paramssld['ht_view4_linkbutton_color']; ?>;
	text-decoration:none;
}

.wd-portfolio-panel_<?php echo $portfolioID; ?> .button-block a:hover, .wd-portfolio-panel_<?php echo $portfolioID; ?> .button-block a:focus, .wd-portfolio-panel_<?php echo $portfolioID; ?> .button-block a:active {
	background:#<?php echo $paramssld['ht_view4_linkbutton_background_hover_color']; ?>;
	color:#<?php echo $paramssld['ht_view4_linkbutton_font_hover_color']; ?>;
	text-decoration:none;
}


@media only screen and (max-width: <?php echo $paramssld['ht_view4_block_width']; ?>px) {
	.portelement_<?php echo $portfolioID; ?> {
	  width:95%;
	}

	.portelement_<?php echo $portfolioID; ?>.large,
	.variable-sizes .portelement_<?php echo $portfolioID; ?>.large,
	.variable-sizes .portelement_<?php echo $portfolioID; ?>.large.width2.height2 {
	  width: 95%;
	}


	.title-block_<?php echo $portfolioID; ?> {
		width:88%;
	}
}
#huge_it_portfolio_content_<?php echo $portfolioID; ?> a{
    border:none;
}

#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_options_<?php echo $portfolioID; ?> {
	position: relative;
    <?php if ($paramssld["ht_view4_show_sorting"] == 'off')
    echo "display:none;";
    if($paramssld["ht_view4_filtering_float"] == 'left' && $paramssld["ht_view4_sorting_float"] == 'none') { if($portfolioShowFiltering == "on") { echo "margin-left: 31%;"; } else { echo "margin-left: 1%;"; }  }
    else if($paramssld["ht_view4_filtering_float"] == 'right' && $paramssld["ht_view4_sorting_float"] == 'none' || ($sorting_block_width == '100%' && $filtering_block_width == "100%")) { echo "margin-left: 1%;"; } ?>
    overflow: hidden;
    margin-top: 5px;
    float: <?php echo $paramssld["ht_view4_sorting_float"]; ?>;
    width: <?php echo $sorting_block_width; ?>;
}

#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_options_<?php echo $portfolioID; ?> ul {
  margin: 0px !important;
  padding: 0px !important;
  list-style: none;
<?php if($paramssld["ht_view4_sorting_float"] == 'top') {
      echo "float:left;margin-left:1%;";
      } ?>
}

#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul {
  margin: 0px !important;
  padding: 0px !important;
  overflow: hidden;
  <?php if($paramssld["ht_view4_filtering_float"] == 'top') {
      echo "float:left;margin-left:1%;";
      } ?>
}

<?php if($paramssld["ht_view4_sorting_float"] == 'none') { ?>
            #huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_options_<?php echo $portfolioID; ?> ul {
                float: left;
            }
    <?php } ?>
            
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_options_<?php echo $portfolioID; ?> ul li {
    border-radius: <?php echo $paramssld["ht_view4_sortbutton_border_radius"];?>px;
    list-style-type: none;
    margin: 0px !important;
	padding: 0;
    <?php
        if($sorting_block_width == "100%" ) {
            echo "float:left !important;margin: 4px 8px 4px 0px !important;";
        }
        if($left_to_top == "ok")
        { echo "float:left !important;"; }
        if($paramssld["ht_view4_sorting_float"] == "left" || $paramssld["ht_view4_sorting_float"] == "right")
        { echo 'border-bottom: 1px solid #ccc;'; }
        else
        { echo 'border: 1px solid #ccc;'; }
    ?>
}

#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_options_<?php echo $portfolioID; ?> ul li a {
    background-color: #<?php echo $paramssld["ht_view4_sortbutton_background_color"];?> !important;
    font-size:<?php echo $paramssld["ht_view4_sortbutton_font_size"];?>px !important;
    color:#<?php echo $paramssld["ht_view4_sortbutton_font_color"];?> !important;
    text-decoration: none;
    cursor: pointer;
    margin: 0px !important;
    display: block;
    padding:3px;
}

/*#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_options_<?php echo $portfolioID; ?> ul li:hover {
    
}*/

#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_options_<?php echo $portfolioID; ?> ul li a:hover {
    background-color: #<?php echo $paramssld["ht_view4_sortbutton_hover_background_color"];?> !important;
    color:#<?php echo $paramssld["ht_view4_sortbutton_hover_font_color"];?> !important;
    cursor: pointer;
}

#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?> {
    margin-top: 5px;
    float: <?php echo $paramssld["ht_view4_filtering_float"]; ?>;
    width: <?php echo $filtering_block_width; ?>;
    <?php
        if ($paramssld["ht_view4_show_filtering"] == 'off') echo "display:none;";
        if($paramssld["ht_view4_filtering_float"] == 'none' && ($paramssld["ht_view4_sorting_float"] == 'left') ) { if($portfolioShowSorting == "on") { echo "margin-left: 31%;"; } else { echo "margin-left: 1%;"; } } 
        if(($paramssld["ht_view4_filtering_float"] == 'none' && ($paramssld["ht_view4_sorting_float"] == 'right')) || ($sorting_block_width == '100%' && $filtering_block_width == "100%")) { echo "margin-left: 1%";}
    ?>
}

#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul li {
    list-style-type: none;
    <?php
        if($filtering_block_width == "100%") { echo "float:left !important;margin: 4px 8px 4px 0px !important;"; }
        if($left_to_top == "ok") { echo "float:left !important;"; }
        if($paramssld["ht_view4_filtering_float"] == "left" || $paramssld["ht_view4_filtering_float"] == "right")
        { echo 'border-bottom: 1px solid #ccc;'; }
        else echo "border: 1px solid #ccc;";
    ?>
}

#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul li a {
    font-size:<?php echo $paramssld["ht_view4_filterbutton_font_size"];?>px !important;
    color:#<?php echo $paramssld["ht_view4_filterbutton_font_color"];?> !important;
    background-color: #<?php echo $paramssld["ht_view4_filterbutton_background_color"];?> !important;
    border-radius: <?php echo $paramssld["ht_view4_filterbutton_border_radius"];?>px;
    padding: 3px;
    display: block;
    text-decoration: none;
}

#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?>  ul li a:hover {
    color:#<?php echo $paramssld["ht_view4_filterbutton_hover_font_color"];?> !important;
    background-color: #<?php echo $paramssld["ht_view4_filterbutton_hover_background_color"];?> !important;
    cursor: pointer
}
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul li.active a,
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul li.active a:link,
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul li.active a:visited,
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?>  ul li.active a:hover,
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?>  ul li.active a:focus,
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?>  ul li.active a:active {
    color:#<?php echo $paramssld["ht_view4_filterbutton_hover_font_color"];?> !important;
    background-color: #<?php echo $paramssld["ht_view4_filterbutton_hover_background_color"];?> !important;
    cursor: pointer;
}
#huge_it_portfolio_content_<?php echo $portfolioID; ?> section {
    position:relative;
    display:block;
}

#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_container_<?php echo $portfolioID; ?> {
<?php if($paramssld["ht_view4_sorting_float"] == "left" && $paramssld["ht_view4_filtering_float"] == "right" ||
         $paramssld["ht_view4_sorting_float"] == "right" && $paramssld["ht_view4_filtering_float"] == "left")
       { echo "margin: 0px auto;"; }
       if((($paramssld["ht_view4_filtering_float"] == "left" || $paramssld["ht_view4_filtering_float"] == "right" && $paramssld["ht_view4_sorting_float"] == "top") || ($paramssld["ht_view4_sorting_float"] == "left" || $paramssld["ht_view4_sorting_float"] == "right" && $paramssld["ht_view4_filting_float"] == "top")) && $portfolioShowFiltering == "on" && $portfolioShowSorting == "on")
       {
?>
        width: <?php echo $width_middle; ?> !important;
 <?php } ?>
}
@media screen and (max-width: 768px) {
	
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul li a,
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul li a:link,
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul li a:visited {
		font-size: 2vw !important;
	}
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_options_<?php echo $portfolioID; ?> ul li a {
		font-size:2vw !important;
	}

}
@media screen and (max-width: 480px) {
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_options_<?php echo $portfolioID; ?> {
	float: left;
}
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_options_<?php echo $portfolioID; ?> #sort-by{
	float: left;
	width: 100% !important;
}
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_options_<?php echo $portfolioID; ?> #sort-direction{
    float: left;
    width: 100% !important;
    position: relative;
    padding-left: 31% !important;
	right: 31%;
}
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul li a,
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul li a:link,
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul li a:visited {
		font-size: 3vw !important;
	}
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_options_<?php echo $portfolioID; ?> ul li a {
		line-height: 3vw;
		font-size:3vw !important;
	}
}
@media screen and (max-width: 420px) {
	
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul li a,
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul li a:link,
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul li a:visited {
		font-size: 4vw !important;
	}
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_options_<?php echo $portfolioID; ?> ul li a {
		font-size:4vw !important;
	}
}
@media screen and (max-width: <?php echo $paramssld['ht_view0_block_width']+2*$paramssld['ht_view0_element_border_width']+40; ?>px) {
   .portelement_<?php echo $portfolioID; ?>  {
		width:98%;
		margin: 1% !important;
		float: left;
		overflow: hidden;
		outline:none;
		border:<?php echo $paramssld['ht_view0_element_border_width']; ?>px solid #<?php echo $paramssld['ht_view0_element_border_color']; ?>;
    }
	.wd-portfolio-panel_<?php echo $portfolioID; ?> {
		width: 100% !important;
	}
}
</style>

<section id="huge_it_portfolio_content_<?php echo $portfolioID; ?>">
    <?php if($portfolioShowSorting == "on")
        { ?>
          <div id="huge_it_portfolio_options_<?php echo $portfolioID; ?>" class="">
            <ul id="sort-by" class="option-set clearfix" data-option-key="sortBy">
                <li><a href="#sortBy=original-order" data-option-value="original-order" class="selected" data><?php echo $paramssld["ht_view4_sorting_name_by_default"]; ?></a></li>
                <li><a href="#sortBy=id" data-option-value="id"><?php echo $paramssld["ht_view4_sorting_name_by_id"]; ?></a></li>
                <li><a href="#sortBy=symbol" data-option-value="symbol"><?php echo $paramssld["ht_view4_sorting_name_by_name"]; ?></a></li>
                <li id="shuffle"><a href='#shuffle'><?php echo $paramssld["ht_view4_sorting_name_by_random"]; ?></a></li>
            </ul>
              
            <ul id="sort-direction" class="option-set clearfix" data-option-key="sortAscending">
                <li><a href="#sortAscending=true" data-option-value="true" class="selected"><?php echo $paramssld["ht_view4_sorting_name_by_asc"]; ?></a></li>
                <li><a href="#sortAscending=false" data-option-value="false"><?php echo $paramssld["ht_view4_sorting_name_by_desc"]; ?></a></li>
            </ul>
          </div>
  <?php }
   if($portfolioShowFiltering == "on")
      { ?>
         <div id="huge_it_portfolio_filters_<?php echo $portfolioID; ?>">
            <ul>
                <li rel="*"><a>All</a></li>
                <?php
                $portfolioCats = explode(",", $portfolioCats);
                foreach ($portfolioCats as $portfolioCatsValue) {
                    if(!empty($portfolioCatsValue))
                    {
                ?>
                <li rel=".<?php echo str_replace(" ","_",$portfolioCatsValue); ?>"><a><?php echo str_replace("_"," ",$portfolioCatsValue); ?></a></li>
                    <?php
                    }
                }
                ?>
            </ul>
        </div>
<?php } ?>
        <div id="huge_it_portfolio_container_<?php echo $portfolioID; ?>" class="super-list variable-sizes clearfix" <?php if($paramssld["ht_view4_sorting_float"] == "top" && $paramssld["ht_view4_filtering_float"] == "top") echo "style='clear: both;'";?>>
              <?php

              foreach($images as $key=>$row)
              {
                      $link = $row->sl_url;
                      $descnohtml=strip_tags($row->description);
                      $result = substr($descnohtml, 0, 50);
                      $catForFilter = explode(",", $row->category);
                      ?>
                      <div class="portelement_<?php echo $portfolioID; ?>  <?php foreach ($catForFilter as $catForFilterValue) { echo str_replace(" ","_",$catForFilterValue)." ";} ?> " data-symbol="<?php echo $row->name; ?>" data-category="alkaline-earth">
                              <div class="title-block_<?php echo $portfolioID; ?>">
                                      <h3 class="title"><?php echo $row->name; ?></h3>
                                      <div class="open-close-button"></div>
                              </div>

                              <div class="wd-portfolio-panel_<?php echo $portfolioID; ?>" id="panel<?php echo $key; ?>">
                                      <?php
                                      if($paramssld['ht_view4_show_description']=='on'){?>
                                              <div class="description-block_<?php echo $portfolioID; ?>">
                                                      <p><?php echo $row->description; ?></p>
                                              </div>
                                      <?php }
                                      if(isset($paramssld['ht_view4_show_thumbs']) && $paramssld['ht_view4_show_thumbs']=='on' and $paramssld['ht_view4_thumbs_position']=="after"){?>
                                              <div>
                                                      <ul class="thumbs-list_<?php echo $portfolioID; ?>">
                                                              <?php
                                                              $imgurl=explode(";",$row->image_url);
                                                              array_pop($imgurl);
                                                              foreach($imgurl as $key=>$img)
                                                              {
                                                              ?>
                                                              <li>
                                                                      <a href="<?php echo $img; ?>" class="group1"><img src="<?php echo $img; ?>"></a>
                                                              </li>
                                                              <?php
                                                              }
                                                              ?>
                                                      </ul>
                                              </div>
                                      <?php } 
                                      if($paramssld['ht_view4_show_linkbutton']=='on'){?>
                                              <div class="button-block">
                                                      <a href="<?php echo $link; ?>" <?php if ($row->link_target=="on"){echo 'target="_blank"';}?>><?php echo $paramssld['ht_view4_linkbutton_text']; ?></a>
                                              </div>
                                      <?php } ?>
                                      <div style="clear:both"></div>
                              </div>
                      </div>

                      <?php
              }
              ?>

        </div>
 </section>
<script>
jQuery(function(){

var defaultBlockWidth=<?php echo $paramssld['ht_view4_block_width']; ?>;

    var $container = jQuery('#huge_it_portfolio_container_<?php echo $portfolioID; ?>');
    
      // add randomish size classes
    $container.find('.portelement_<?php echo $portfolioID; ?>').each(function(){
	var $this = jQuery(this),
		number = parseInt( $this.find('.number').text(), 10 );
		//alert(number);
	if ( number % 7 % 2 === 1 ) {
	  $this.addClass('width2');
	}
	if ( number % 3 === 0 ) {
	  $this.addClass('height2');
	}
    });
    
    $container.hugeitmicro({
      itemSelector : '.portelement_<?php echo $portfolioID; ?>',
      masonry : {
        columnWidth : <?php echo $paramssld['ht_view4_block_width']; ?>+20+<?php echo (isset($paramssld['ht_view4_element_border_width'])?$paramssld['ht_view4_element_border_width']:1)*2; ?>
      },
      masonryHorizontal : {
        rowHeight: 300+20
      },
      cellsByRow : {
        columnWidth : 300+20,
        rowHeight : 240
      },
      cellsByColumn : {
        columnWidth : 300+20,
        rowHeight : 240
      },
      getSortData : {
        symbol : function( $elem ) {
	  return $elem.attr('data-symbol');
	},
	category : function( $elem ) {
	  return $elem.attr('data-category');
	},
	number : function( $elem ) {
	  return parseInt( $elem.find('.number').text(), 10 );
	},
	weight : function( $elem ) {
	  return parseFloat( $elem.find('.weight').text().replace( /[\(\)]/g, '') );
	},
        id : function ( $elem ) {
          return $elem.find('.id').text();
        }
      }
    });
    
    
      var $optionSets = jQuery('#huge_it_portfolio_options_<?php echo $portfolioID; ?> .option-set'),
	  $optionLinks = $optionSets.find('a');

  $optionLinks.click(function(){
	var $this = jQuery(this);

	if ( $this.hasClass('selected') ) {
	  return false;
	}
	var $optionSet = $this.parents('.option-set');
	$optionSet.find('.selected').removeClass('selected');
	$this.addClass('selected');


	var options = {},
		key = $optionSet.attr('data-option-key'),
		value = $this.attr('data-option-value');

	value = value === 'false' ? false : value;
	options[ key ] = value;
	if ( key === 'layoutMode' && typeof changeLayoutMode === 'function' ) {

	  changeLayoutMode( $this, options )
	} else {

	  $container.hugeitmicro( options );
	}
	
	return false;
  });


    

      var isHorizontal = false;
  function changeLayoutMode( $link, options ) {
	var wasHorizontal = isHorizontal;
	isHorizontal = $link.hasClass('horizontal');

	if ( wasHorizontal !== isHorizontal ) {

	  var style = isHorizontal ? 
		{ height: '100%', width: $container.width() } : 
		{ width: 'auto' };

	  $container.filter(':animated').stop();

	  $container.addClass('no-transition').css( style );
	  setTimeout(function(){
		$container.removeClass('no-transition').hugeitmicro( options );
	  }, 700)
	} else {
	  $container.hugeitmicro( options );
	}
  }


    

      $container.delegate( '.open-close-button', 'click', function(){		
		if(jQuery(this).parents('.portelement_<?php echo $portfolioID; ?>').hasClass("large")){
			jQuery(this).parents('.portelement_<?php echo $portfolioID; ?>').animate({
				height: "45px"
			}, 700, function() {
				jQuery(this).removeClass('large');
				$container.hugeitmicro('reLayout');
			});
			
			jQuery(this).parents('.portelement_<?php echo $portfolioID; ?>').removeClass("active");
			return false;
		}


		jQuery(this).parents('.portelement_<?php echo $portfolioID; ?>').css({height:45+jQuery(this).parents('.portelement_<?php echo $portfolioID; ?>').find('.wd-portfolio-panel_<?php echo $portfolioID; ?>').height()});
		jQuery(this).parents('.portelement_<?php echo $portfolioID; ?>').addClass('large');

		$container.hugeitmicro('reLayout');
		jQuery(this).parents('.portelement_<?php echo $portfolioID; ?>').css({height:"45px"});
		  
		 var strheight=(jQuery(this).parents('.portelement_<?php echo $portfolioID; ?>').find('.wd-portfolio-panel_<?php echo $portfolioID; ?>').height()+35);
		 
		 
		 jQuery(this).parents('.portelement_<?php echo $portfolioID; ?>').animate({
			height:strheight+"px",
		  }, 700,function(){	$container.hugeitmicro('reLayout');});
	});

    var $sortBy =  jQuery('#huge_it_portfolio_content_<?php echo $portfolioID; ?> #sort-by');
    jQuery('#huge_it_portfolio_content_<?php echo $portfolioID; ?> #shuffle a').click(function(){
      $container.hugeitmicro('shuffle');
      $sortBy.find('.selected').removeClass('selected');
      $sortBy.find('[data-option-value="random"]').addClass('selected');
      return false;
    });
    
    ////filteringgggggg

        // bind filter on select change
        jQuery(document).ready(function(){
            jQuery('#huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul li').click(function() {
              // get filter value from option value
              var filterValue = jQuery(this).attr('rel');
              // use filterFn if matches value
              filterValue = filterValue;//filterFns[ filterValue ] || 
              $container.hugeitmicro({ filter: filterValue });
            });
            <?php if(($paramssld["ht_view4_sorting_float"] == "left" || $paramssld["ht_view4_sorting_float"] == "right") && $paramssld["ht_view4_filtering_float"] == "none")
                  { ?>
                        var topmargin = jQuery("#huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul").height();
                        jQuery("#huge_it_portfolio_options_<?php echo $portfolioID; ?>").css({'margin-top':parseInt(topmargin) + 5});
            <?php }
            else  {
                    if(($paramssld["ht_view4_filtering_float"] == "left" || $paramssld["ht_view4_filtering_float"] == "right") && $paramssld["ht_view4_sorting_float"] == "none")
                      { ?>
                         var topmargin = jQuery("#huge_it_portfolio_options_<?php echo $portfolioID; ?>").height();
                         jQuery("#huge_it_portfolio_filters_<?php echo $portfolioID; ?>").css({'margin-top':'5px'});
                <?php }
                  } ?>
        });

  });
  jQuery(window).load(function(){
		jQuery(window).resize(function(){$container.hugeitmicro('reLayout');});
	});
</script>
	  
	  <?php
	  
	  
	  
        break;
/////////////////////////////////// VIEW 5 Slider ////////////////////////////////////
		case 5;
?>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.touchswipe/1.6.4/jquery.touchSwipe.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.0.0/animate.min.css">
  <link href="<?php echo plugins_url('../style/liquid-slider.css', __FILE__);?>" rel="stylesheet" type="text/css" />
 
<style>
/***<add>***/
#main-slider_<?php echo $portfolioID; ?> .play-icon.youtube-icon  {
	background: url(<?php echo  plugins_url( '../images/play.youtube.png' , __FILE__ );?>) center center no-repeat;
	background-size: 30% 30%;
}
#main-slider_<?php echo $portfolioID; ?> .play-icon.vimeo-icon  {
	background: url(<?php echo  plugins_url( '../images/play.vimeo.png' , __FILE__ );?>) center center no-repeat;
	background-size: 30% 30%;
}
#main-slider_<?php echo $portfolioID; ?> .play-icon {
    position: absolute;
    top: 0px;
    left: 0px;
    width: 100%;
    height: 100%;
}
#main-slider_<?php echo $portfolioID; ?>  .add-H-relative {
	position: relative;
}	
#main-slider_<?php echo $portfolioID; ?>  .add-H-block {
	display: block;
}	
/***</add>***/
#main-slider_<?php echo $portfolioID; ?>-wrapper a{
    border:none;
}

#main-slider_<?php echo $portfolioID; ?>-wrapper .ls-nav { display: none; }
#main-slider_<?php echo $portfolioID; ?> {background:#<?php echo $paramssld["ht_view5_slider_background_color"];?>;}

#main-slider_<?php echo $portfolioID; ?> div.slider-content {
	position:relative;
	width:100%;
	padding:0px 0px 0px 0px;
	position:relative;
	background:#<?php echo $paramssld["ht_view5_slider_background_color"];?>;
}



[class$="-arrow"] {
	background-image:url(<?php echo plugins_url('../images/arrow.'.$paramssld["ht_view5_icons_style"].'.png', __FILE__);?>);
}

.ls-select-box {
	background:url(<?php echo plugins_url('../images/menu.'.$paramssld["ht_view5_icons_style"].'.png', __FILE__);?>) right center no-repeat #<?php echo $paramssld["ht_view5_slider_background_color"];?>;
}

#main-slider_<?php echo $portfolioID; ?>-nav-select {
	color:#<?php echo $paramssld["ht_view5_title_font_color"];?>;
}

#main-slider_<?php echo $portfolioID; ?> div.slider-content .slider-content-wrapper {
	position:relative;
	width:100%;
	padding:0px;
	display:block;
}

#main-slider_<?php echo $portfolioID; ?> .slider-content-wrapper .image-block_<?php echo $portfolioID; ?> {
	width:<?php echo $paramssld["ht_view5_main_image_width"];?>px;
	display:table-cell;
	padding:0px 10px 0px 0px;
	float:left;
}

#main-slider_<?php echo $portfolioID; ?> .slider-content-wrapper .image-block_<?php echo $portfolioID; ?> img.main-image {
	position:relative;
	width:100%;
	height:auto;
	display:block;
}

#main-slider_<?php echo $portfolioID; ?> .slider-content-wrapper .image-block_<?php echo $portfolioID; ?> ul.thumbs-list_<?php echo $portfolioID; ?> {
	list-style:none;
	display:table;
	position:relative;
	clear:both;
	width:100%;
	margin:10px 0px 0px 0px;
	padding:0px;
	clear:both;
}

#main-slider_<?php echo $portfolioID; ?> .slider-content-wrapper .image-block_<?php echo $portfolioID; ?> ul.thumbs-list_<?php echo $portfolioID; ?> li {
	display:block;
	float:left;
	width:<?php echo $paramssld["ht_view5_thumbs_width"];?>px;
	height:<?php echo $paramssld["ht_view5_thumbs_height"];?>px;
	margin:0px 2% 5px 1%;
	opacity:0.45;
}

#main-slider_<?php echo $portfolioID; ?> .slider-content-wrapper .image-block_<?php echo $portfolioID; ?> ul.thumbs-list_<?php echo $portfolioID; ?> li.active,#main-slider_<?php echo $portfolioID; ?> .slider-content-wrapper .image-block_<?php echo $portfolioID; ?> ul.thumbs-list_<?php echo $portfolioID; ?> li:hover {
	opacity:1;
}

#main-slider_<?php echo $portfolioID; ?> .slider-content-wrapper .image-block_<?php echo $portfolioID; ?> ul.thumbs-list_<?php echo $portfolioID; ?> li a {
	display:block;
}

#main-slider_<?php echo $portfolioID; ?> .slider-content-wrapper .image-block_<?php echo $portfolioID; ?> ul.thumbs-list_<?php echo $portfolioID; ?> li img {
	margin:0px !important;
	padding:0px !important;
	width:<?php echo $paramssld["ht_view5_thumbs_width"];?>px !important;
	height:<?php echo $paramssld["ht_view5_thumbs_height"];?>px !important;
}

#main-slider_<?php echo $portfolioID; ?> .slider-content-wrapper .right-block {
	display:table-cell;
}

#main-slider_<?php echo $portfolioID; ?> .slider-content-wrapper .right-block > div {
	padding-bottom:10px;
	margin-top:10px;
	<?php if($paramssld['ht_view5_show_separator_lines']=="on") {?>
		background:url('<?php echo  plugins_url( '../images/divider.line.png' , __FILE__ ); ?>') center bottom repeat-x;
	<?php } ?>
}
#main-slider_<?php echo $portfolioID; ?> .slider-content-wrapper .right-block > div:last-child {background:none;}


#main-slider_<?php echo $portfolioID; ?> .slider-content-wrapper .right-block .title {
	position:relative;
	display:block;
	margin:-10px 0px 0px 0px;
	font-size:<?php echo $paramssld["ht_view5_title_font_size"];?>px !important;
	line-height:<?php echo $paramssld["ht_view5_title_font_size"]+4;?>px !important;
	color:#<?php echo $paramssld["ht_view5_title_font_color"];?>;
}

#main-slider_<?php echo $portfolioID; ?> .slider-content-wrapper .right-block .description {
	clear:both;
	position:relative;
	text-align:justify;
	font-size:<?php echo $paramssld["ht_view5_description_font_size"];?>px !important;
	line-height:<?php echo $paramssld["ht_view5_description_font_size"]+4;?>px !important;
	color:#<?php echo $paramssld["ht_view5_description_color"];?>;
}

#main-slider_<?php echo $portfolioID; ?> .slider-content-wrapper .right-block .description h1,
#main-slider_<?php echo $portfolioID; ?> .slider-content-wrapper .right-block .description h2,
#main-slider_<?php echo $portfolioID; ?> .slider-content-wrapper .right-block .description h3,
#main-slider_<?php echo $portfolioID; ?> .slider-content-wrapper .right-block .description h4,
#main-slider_<?php echo $portfolioID; ?> .slider-content-wrapper .right-block .description h5,
#main-slider_<?php echo $portfolioID; ?> .slider-content-wrapper .right-block .description h6,
#main-slider_<?php echo $portfolioID; ?> .slider-content-wrapper .right-block .description p, 
#main-slider_<?php echo $portfolioID; ?> .slider-content-wrapper .right-block .description strong,
#main-slider_<?php echo $portfolioID; ?> .slider-content-wrapper .right-block .description span {
	padding:2px !important;
	margin:0px !important;
}

#main-slider_<?php echo $portfolioID; ?> .slider-content-wrapper .right-block .description ul,
#main-slider_<?php echo $portfolioID; ?> .slider-content-wrapper .right-block .description li {
	padding:2px 0px 2px 5px;
	margin:0px 0px 0px 8px;
}



#main-slider_<?php echo $portfolioID; ?> .slider-content-wrapper .button-block {
	position:relative;
}

#main-slider_<?php echo $portfolioID; ?> .slider-content-wrapper .button-block a,#main-slider_<?php echo $portfolioID; ?> .slider-content-wrapper .button-block a:link,#main-slider_<?php echo $portfolioID; ?> .slider-content-wrapper .button-block a:visited{
	position:relative;
	display:inline-block;
	padding:6px 12px;
	background:#<?php echo $paramssld["ht_view5_linkbutton_background_color"];?>;
	color:#<?php echo $paramssld["ht_view5_linkbutton_color"];?>;
	font-size:<?php echo $paramssld["ht_view5_linkbutton_font_size"];?>;
	text-decoration:none;
}

#main-slider_<?php echo $portfolioID; ?> .slider-content-wrapper .button-block a:hover,#main-slider_<?php echo $portfolioID; ?> .slider-content-wrapper .button-block a:focus,#main-slider_<?php echo $portfolioID; ?> .slider-content-wrapper .button-block a:active {
	background:#<?php echo $paramssld["ht_view5_linkbutton_background_hover_color"];?>;
	color:#<?php echo $paramssld["ht_view5_linkbutton_font_hover_color"];?>;
}

@media only screen and (min-width:500px) {
	#main-slider-nav-ul {
		visibility:hidden !important;
		height:1px;
	}
}

@media only screen and (max-width:500px) {
	#main-slider_<?php echo $portfolioID; ?> .slider-content-wrapper .image-block_<?php echo $portfolioID; ?>,#main-slider_<?php echo $portfolioID; ?> .slider-content-wrapper .right-block {
		width:100%;
		display:block;
		float:none;
		clear:both;
	}
}
</style>
<div id="main-slider_<?php echo $portfolioID; ?>" class="liquid-slider">
	<?php
		$group_key = 0;
	foreach($images as $key=>$row)
	{	
	    $group_key++;
        $group_key1 = (string)$group_key;
		/*$portfolioID1 = (string)$portfolioID;
		$group_key1 =$group_key."-".$portfolioID;*/
		$imgurl=explode(";",$row->image_url);array_pop($imgurl);
		$link = $row->sl_url;
		$descnohtml=strip_tags($row->description);
		$result = substr($descnohtml, 0, 50);
		?>
		<div class="slider-content">
			<div class="slider-content-wrapper slide_number<?php echo $group_key1;?>" >
				<div class="image-block_<?php echo $portfolioID; ?>">
					<?php 	
					if($row->image_url != ';')
					{
						switch(youtube_or_vimeo_portfolio($imgurl[0])) {
							case 'image':
						
						?>
						<a class="portfolio-group-slider_<?php echo $portfolioID;?>_<?php  echo $group_key1; ?>" href="<?php echo $imgurl[0]; ?>" title="<?php echo $row->name; ?>"><img alt="<?php echo $row->name; ?>" class="main-image" src="<?php echo $imgurl[0]; ?>" /></a>
						<?php 
							break;
							case 'youtube':
							$videourl=get_video_id_from_url_portfolio($imgurl[0]);?>
								<a href="https://www.youtube.com/embed/<?php echo $videourl[0];?>" class="huge_it_portfolio_item youtube portfolio-group-slider_<?php echo $portfolioID;?>_<?php  echo $group_key1; ?> add-H-relative add-H-block"  title="<?php echo $row->name; ?>"">
									<img src="//img.youtube.com/vi/<?php echo $videourl[0]; ?>/mqdefault.jpg"><div class="play-icon <?php echo $videourl[1];?>-icon"></div>
								</a> 	
						<?php
							break;
							case 'vimeo':
							$videourl=get_video_id_from_url_portfolio($imgurl[0]);
							$hash = unserialize(file_get_contents("http://vimeo.com/api/v2/video/".$videourl[0].".php"));
							$imgsrc=$hash[0]['thumbnail_large'];
							?>
							<a class="huge_it_portfolio_item vimeo portfolio-group-slider_<?php echo $portfolioID;?>_<?php  echo $group_key1; ?>   add-H-relative add-H-block" href="http://player.vimeo.com/video/<?php echo $videourl[0]; ?>" title="<?php echo $row->name; ?>">
								<img src="<?php echo $imgsrc; ?>" alt="<?php echo $row->name; ?>" /><div class="play-icon <?php echo $videourl[1];?>-icon"></div>
							</a>
					<?php	break;	
						}
					}					
					else 
					{ ?>
					<img alt="<?php echo $row->name; ?>" class="main-image" src="images/noimage.jpg" />
					<?php
					} ?>
					
					<?php if($paramssld["ht_view5_show_thumbs"]){?>
					<div><ul class="thumbs-list_<?php echo $portfolioID; ?>">
						<?php  
						array_shift($imgurl);
								foreach($imgurl as $key=>$img)
								{
									switch(youtube_or_vimeo_portfolio($img)) {
										case 'image':
									?>
									<li><a class="portfolio-group-slider_<?php echo $portfolioID;?>_<?php  echo $group_key1; ?>" href="<?php echo $img; ?>" title = "<?php echo $row->name; ?>"><img src="<?php echo get_huge_image($img,$image_prefix); ?>"></a></li>
								<?php 
										break;
										case 'youtube':
										$videourl=get_video_id_from_url_portfolio($img);?>
										<li>
										<a href="https://www.youtube.com/embed/<?php echo $videourl[0];?>" class="huge_it_portfolio_item youtube portfolio-group-slider_<?php echo $portfolioID;?>_<?php  echo $group_key1; ?>  add-H-relative"  title = "<?php echo $row->name; ?>">
											<img src="//img.youtube.com/vi/<?php echo $videourl[0]; ?>/mqdefault.jpg"><div class="play-icon <?php echo $videourl[1];?>-icon"></div>
										</a>
										</li>	
									<?php
										break;
										case 'vimeo':
										$videourl = get_video_id_from_url_portfolio($img);
										$hash = unserialize(file_get_contents("http://vimeo.com/api/v2/video/".$videourl[0].".php"));
										$imgsrc=$hash[0]['thumbnail_large'];?>
										<li>
										<a class="huge_it_portfolio_item vimeo portfolio-group-slider_<?php echo $portfolioID;?>_<?php  echo $group_key1; ?>  add-H-relative" href="http://player.vimeo.com/video/<?php echo $videourl[0]; ?> " title="<?php echo $row->name; ?>"  style="position:relative">
											<img src="<?php echo $imgsrc; ?>" alt="<?php echo $row->name; ?>" /><div class="play-icon <?php echo $videourl[1];?>-icon"></div>
										</a>
										</li>
									<?php 
										break;	
									}
								} ?>
					</ul></div>
					<?php } ?>					
				</div>
				<div class="right-block">
					<div><h2 class="title"><?php echo $row->name; ?></h2></div>
					<?php if($paramssld["ht_view5_show_description"]=='on'){?><div class="description"><?php echo $row->description; ?></div><?php } ?>
					<?php if($paramssld["ht_view5_show_linkbutton"]=='on' && $paramssld["ht_view5_linkbutton_text"] != '' && $link != ''){?>
						<div class="button-block">
							<a class="" href="<?php echo $link; ?>"  <?php if ($row->link_target=="on"){echo 'target="_blank"';}?>><?php echo $paramssld["ht_view5_linkbutton_text"]; ?></a>
						</div>
					<?php } ?>
				</div>
			</div>
		</div>
		<?php
	} ?>
</div>
  <script src="<?php echo plugins_url('../js/jquery.liquid-slider.min.js', __FILE__);?>"></script>  
   <script>
    /**
     * If you need to access the internal property or methods, use this:
     * var api = $.data( jQuery('#main-slider_<?php echo $portfolioID; ?>')[0], 'liquidSlider');
     * console.log(api);
     */
	var slideEffect = '<?php echo $portfolio[0]->sl_position;?>'.split('_');
	var portfolioSliderOptons_<?php echo $portfolioID; ?> = {
		
		autoSlide : <?php if($portfolio[0]->pause_on_hover == 'on') echo 'true' ; else echo 'false';?>,
		slideEaseDuration : (+'<?php echo $portfolio[0]->param;?>'),
		autoSlideInterval : (+'<?php echo $portfolio[0]->description;?>'),
		animateOut : slideEffect[0],
		animateIn : slideEffect[1]
		
	}
	
///console.log(portfolioSliderOptons_<?php echo $portfolioID; ?>);
	jQuery('#main-slider_<?php echo $portfolioID; ?>').liquidSlider(portfolioSliderOptons_<?php echo $portfolioID; ?>);
  </script>
<?php  
        break;
/////////////////////////////// VIEW 6 Gallery /////////////////////////////
		
		case 6:
?>
<style type="text/css">

<?php
    if($paramssld["ht_view6_sorting_float"] == "left" && $paramssld["ht_view6_filtering_float"] == "right" ||
       $paramssld["ht_view6_sorting_float"] == "right" && $paramssld["ht_view6_filtering_float"] == "left" ||
       $paramssld["ht_view6_sorting_float"] == $paramssld["ht_view6_filtering_float"])
       { $sorting_block_width ="20%"; $filtering_block_width ="20%"; $width_middle = "56%"; }
    else if($paramssld["ht_view6_sorting_float"] == "left" || $paramssld["ht_view6_sorting_float"] == "right" && $paramssld["ht_view6_filtering_float"] == "top")
       { $sorting_block_width ="30%"; $filtering_block_width ="100%"; $paramssld["ht_view6_filtering_float"] = "none"; $width_middle = "65%"; }
    else if($paramssld["ht_view6_filtering_float"] == "left" || $paramssld["ht_view6_filtering_float"] == "right" && $paramssld["ht_view6_sorting_float"] == "top")
       { $sorting_block_width ="100%"; $filtering_block_width ="30%"; $paramssld["ht_view6_sorting_float"] = "none"; $width_middle = "65%"; }
    if($paramssld["ht_view6_sorting_float"] == "top" && $paramssld["ht_view6_filtering_float"] == "top")
       { $sorting_block_width ="100%"; $filtering_block_width ="100%"; $left_to_top = "ok"; }
?>
/***<add>***/
 .play-icon.youtube-icon  {
	background: url(<?php echo  plugins_url( '../images/play.youtube.png' , __FILE__ );?>) center center no-repeat;
	background-size: 30% 30%;
}
 .play-icon.vimeo-icon  {
	background: url(<?php echo  plugins_url( '../images/play.vimeo.png' , __FILE__ );?>) center center no-repeat;
	background-size: 30% 30%;
}
.play-icon {
    position: absolute;
    top: 0px;
    left: 0px;
    width: 100%;
    height: 100%;
}	
/***</add>***/
.portelement_<?php echo $portfolioID; ?> {
	max-width:<?php echo $paramssld['ht_view6_width']; ?>px;
	width: 100%;
	margin:0px 0px 10px 0px;
	border:<?php echo $paramssld['ht_view6_border_width']; ?>px solid #<?php echo $paramssld['ht_view6_border_color']; ?>;
	border-radius:<?php echo $paramssld['ht_view6_border_radius']; ?>px;
	outline:none;
	overflow:hidden;
}

.portelement_<?php echo $portfolioID; ?> .image-block_<?php echo $portfolioID; ?> {
	position:relative;
	max-width:<?php echo $paramssld['ht_view6_width']; ?>px;
	width:100%;
}
.portelement_<?php echo $portfolioID; ?> .image-block_<?php echo $portfolioID; ?> a {
	display: block;
	cursor: -webkit-zoom-in; cursor: -moz-zoom-in;
}
.portelement_<?php echo $portfolioID; ?> .image-block_<?php echo $portfolioID; ?> img {
	margin:0px !important;
	padding:0px !important;
	max-width:<?php echo $paramssld['ht_view6_width']; ?>px !important;
	width: 100%;
	height:auto;
	display:block;
	border-radius: 0px !important;
	box-shadow: 0 0px 0px rgba(0, 0, 0, 0) !important; 
}

.portelement_<?php echo $portfolioID; ?> .image-block_<?php echo $portfolioID; ?> img:hover {
	cursor: -webkit-zoom-in; cursor: -moz-zoom-in;
}

.portelement_<?php echo $portfolioID; ?> .title-block_<?php echo $portfolioID; ?> {
	position:absolute;
	text-overflow: ellipsis;
	overflow: hidden;	
	left:0px;
	width:100%;
	padding-top:5px;
	height: <?php echo $paramssld["ht_view6_title_font_size"] + 10; ?>px;
	bottom:-<?php echo $paramssld["ht_view6_title_font_size"] + 15; ?>px;
	background: <?php
			list($r,$g,$b) = array_map('hexdec',str_split($paramssld['ht_view6_title_background_color'],2));
				$titleopacity=$paramssld["ht_view6_title_background_transparency"]/100;						
				echo 'rgba('.$r.','.$g.','.$b.','.$titleopacity.')  !important'; 		
	?>;
	 -webkit-transition: bottom 0.3s ease-out 0.1s;
     -moz-transition: bottom 0.3s ease-out 0.1s;
     -o-transition: bottom 0.3s ease-out 0.1s;
     transition: bottom 0.3s ease-out 0.1s;
}

.portelement_<?php echo $portfolioID; ?>:hover .title-block_<?php echo $portfolioID; ?> {bottom:0px;}

.portelement_<?php echo $portfolioID; ?> .title-block_<?php echo $portfolioID; ?> a, .portelement_<?php echo $portfolioID; ?> .title-block_<?php echo $portfolioID; ?> a:link, .portelement_<?php echo $portfolioID; ?> .title-block_<?php echo $portfolioID; ?> a:visited {
	position:relative;
	margin:0px;
	padding:0px 1% 0px 2%;
	width:97%;
	text-decoration:none;
	text-overflow: ellipsis;
	overflow: hidden; 
	white-space:nowrap;
	z-index:20;
	font-size: <?php echo $paramssld["ht_view6_title_font_size"];?>px;
	color:#<?php echo $paramssld["ht_view6_title_font_color"];?>;
	font-weight:normal;
}



.portelement_<?php echo $portfolioID; ?> .title-block_<?php echo $portfolioID; ?> a:hover, .portelement_<?php echo $portfolioID; ?> .title-block_<?php echo $portfolioID; ?> a:focus, .portelement_<?php echo $portfolioID; ?> .title-block_<?php echo $portfolioID; ?> a:active {
	color:#<?php echo $paramssld["ht_view6_title_font_hover_color"];?>;
	text-decoration:none;
}
#huge_it_portfolio_content_<?php echo $portfolioID; ?> a{
    border:none;
}
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_options_<?php echo $portfolioID; ?> {
	position: relative;
    <?php if ($paramssld["ht_view6_show_sorting"] == 'off')
    echo "display:none;";
    if($paramssld["ht_view6_filtering_float"] == 'left' && $paramssld["ht_view6_sorting_float"] == 'none') {  if($portfolioShowFiltering == "on") { echo "margin-left: 30%;"; } else { echo "margin-left: 0%;"; }   }
//    else if($paramssld["ht_view6_filtering_float"] == 'right' && $paramssld["ht_view6_sorting_float"] == 'none' || ($sorting_block_width == '100%' && $filtering_block_width == "100%")) { echo "margin-left: 1%;"; } ?>
    overflow: hidden;
    /*margin-top: 5px;*/
    float: <?php echo $paramssld["ht_view6_sorting_float"]; ?>;
    width:<?php echo $sorting_block_width; ?>;
}

#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_options_<?php echo $portfolioID; ?> ul {
  margin: 0px !important;
  padding: 0px !important;
  list-style: none;
<?php if($paramssld["ht_view6_sorting_float"] == 'top') {
      echo "display:inline-block;margin-left:1%;";
      } ?>
}

#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul {
  margin: 0px !important;
  padding: 0px !important;
  overflow: hidden;
  <?php if($paramssld["ht_view6_filtering_float"] == 'top') {
      echo "display:inline-block;margin-left:1%;";
      } ?>
}

<?php if($paramssld["ht_view6_sorting_float"] == 'top') { ?>
            #huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_options_<?php echo $portfolioID; ?> ul {
                float: left;
            }
    <?php } ?>
            
    <?php if($paramssld["ht_view6_sorting_float"] == 'none') { ?>
            #huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_options_<?php echo $portfolioID; ?> ul {
                float: left;
            }
    <?php } ?>
    
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_options_<?php echo $portfolioID; ?> ul li {
    border-radius: <?php echo $paramssld["ht_view6_sortbutton_border_radius"];?>px;
    list-style-type: none;
    margin: 0px !important;
	padding: 0;
    <?php
        if($sorting_block_width == "100%" ) {
            echo "float:left !important;margin: 4px 8px 4px 0px !important;";
        }
        if($left_to_top == "ok")
        { echo "float:left !important;"; }
        if($paramssld["ht_view6_sorting_float"] == "left" || $paramssld["ht_view6_sorting_float"] == "right")
        { echo 'border-bottom: 1px solid #ccc;'; }
        else
        { echo 'border: 1px solid #ccc;'; }
    ?>
}

#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_options_<?php echo $portfolioID; ?> ul li a {
    background-color: #<?php echo $paramssld["ht_view6_sortbutton_background_color"];?> !important;
    font-size:<?php echo $paramssld["ht_view6_sortbutton_font_size"];?>px !important;
    color:#<?php echo $paramssld["ht_view6_sortbutton_font_color"];?> !important;
    text-decoration: none;
    cursor: pointer;
    margin: 0px !important;
    display: block;
    padding:3px;
}

#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_container_<?php echo $portfolioID; ?> {
    margin-top: 5px;
    <?php
        if($paramssld["ht_view6_sorting_float"] == "left" && $paramssld["ht_view6_filtering_float"] == "right" ||
           $paramssld["ht_view6_sorting_float"] == "right" && $paramssld["ht_view6_filtering_float"] == "left")
        { ?>
            margin-left: 21%;
  <?php } ?>
}

#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_options_<?php echo $portfolioID; ?> ul li a:hover {
    background-color: #<?php echo $paramssld["ht_view6_sortbutton_hover_background_color"];?> !important;
    color:#<?php echo $paramssld["ht_view6_sortbutton_hover_font_color"];?> !important;
    cursor: pointer;
}

#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?> {
    /*margin-top: 5px;*/
    float: <?php echo $paramssld["ht_view6_filtering_float"]; ?>;
    width: <?php echo $filtering_block_width; ?>;
    <?php
        if ($paramssld["ht_view6_show_filtering"] == 'off') echo "display:none;";
        if($paramssld["ht_view6_filtering_float"] == 'top' && $paramssld["ht_view6_sorting_float"] == 'left' ) {  if($portfolioShowSorting == 'on') { echo "margin-left: 30%;"; } else echo "margin-left: 0%"; } 
//        if(($paramssld["ht_view6_filtering_float"] == 'none' && ($paramssld["ht_view6_sorting_float"] == 'right')) || ($sorting_block_width == '100%' && $filtering_block_width == "100%")) { echo "margin-left: 1%";}
    ?>
}

#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul li {
    list-style-type: none;
    <?php
        if($filtering_block_width == "100%") { echo "float:left !important;margin: 4px 8px 4px 0px !important;"; }
        if($left_to_top == "ok") { echo "float:left !important;"; }
        if($paramssld["ht_view6_filtering_float"] == "left" || $paramssld["ht_view6_filtering_float"] == "right")
        { echo 'border-bottom: 1px solid #ccc;'; }
        else echo "border: 1px solid #ccc;";
    ?>
}

#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul li a {
    font-size:<?php echo $paramssld["ht_view6_filterbutton_font_size"];?>px !important;
    color:#<?php echo $paramssld["ht_view6_filterbutton_font_color"];?> !important;
    background-color: #<?php echo $paramssld["ht_view6_filterbutton_background_color"];?> !important;
    border-radius: <?php echo $paramssld["ht_view6_filterbutton_border_radius"];?>px;
    padding: 3px;
    display: block;
    text-decoration: none;
}

#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?>  ul li a:hover {
    color:#<?php echo $paramssld["ht_view6_filterbutton_hover_font_color"];?> !important;
    background-color: #<?php echo $paramssld["ht_view6_filterbutton_hover_background_color"];?> !important;
    cursor: pointer
}
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul li.active a,
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul li.active a:link,
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul li.active a:visited,
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?>  ul li.active a:hover,
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?>  ul li.active a:focus,
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?>  ul li.active a:active {
    color:#<?php echo $paramssld["ht_view6_filterbutton_hover_font_color"];?> !important;
    background-color: #<?php echo $paramssld["ht_view6_filterbutton_hover_background_color"];?> !important;
    cursor: pointer;
}
#huge_it_portfolio_content_<?php echo $portfolioID; ?> section {
    position:relative;
    display:block;
}

#huge_it_portfolio_content_<?php echo $portfolioID; ?> .huge_it_portfolio_container_styles_<?php echo $portfolioID; ?> {
<?php if($paramssld["ht_view6_sorting_float"] == "left" && $paramssld["ht_view6_filtering_float"] == "right" ||
         $paramssld["ht_view6_sorting_float"] == "right" && $paramssld["ht_view6_filtering_float"] == "left")
       { echo "margin: 0px auto;"; }
      if((($paramssld["ht_view6_filtering_float"] == "left" || $paramssld["ht_view6_filtering_float"] == "right" && $paramssld["ht_view6_sorting_float"] == "top") || ($paramssld["ht_view6_sorting_float"] == "left" || $paramssld["ht_view6_sorting_float"] == "right" && $paramssld["ht_view6_filtering_float"] == "top")) && $portfolioShowFiltering == "on" && $portfolioShowSorting == "on")
        { ?>
            width: <?php echo $width_middle; ?> !important;
  <?php } ?>
       overflow: hidden !important;
}

#sort-direction {
    <?php if($paramssld["ht_view6_sorting_float"] == "top")
       { echo "float: left !important;"; }
?>
}
@media screen and (max-width: 768px) {
	
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul li a,
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul li a:link,
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul li a:visited {
		font-size: 2vw !important;
	}
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_options_<?php echo $portfolioID; ?> ul li a {
		font-size:2vw !important;
	}

}
@media screen and (max-width: 480px) {
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_options_<?php echo $portfolioID; ?> {
	float: left;
}
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_options_<?php echo $portfolioID; ?> #sort-by{
	float: left;
	width: 100% !important;
}
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_options_<?php echo $portfolioID; ?> #sort-direction{
    float: left;
    width: 100% !important;
    position: relative;
    padding-left: 31% !important;
	right: 31%;
}
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul li a,
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul li a:link,
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul li a:visited {
		font-size: 3vw !important;
	}
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_options_<?php echo $portfolioID; ?> ul li a {
		line-height: 3vw;
		font-size:3vw !important;
	}
}
@media screen and (max-width: 420px) {
	
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul li a,
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul li a:link,
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul li a:visited {
		font-size: 4vw !important;
	}
#huge_it_portfolio_content_<?php echo $portfolioID; ?> #huge_it_portfolio_options_<?php echo $portfolioID; ?> ul li a {
		font-size:4vw !important;
	}
}
@media screen and (max-width: <?php echo $paramssld['ht_view0_block_width']+2*$paramssld['ht_view0_element_border_width']+40; ?>px) {
   .portelement_<?php echo $portfolioID; ?>  {
		width:98%;
		margin: 1% !important;
		float: left;
		overflow: hidden;
		outline:none;
		border:<?php echo $paramssld['ht_view0_element_border_width']; ?>px solid #<?php echo $paramssld['ht_view0_element_border_color']; ?>;
    }
	.wd-portfolio-panel_<?php echo $portfolioID; ?> {
		width: 100% !important;
	}
}
</style>

<section id="huge_it_portfolio_content_<?php echo $portfolioID; ?>" class="">
    <?php if($portfolioShowSorting == "on")
        { ?>
          <div id="huge_it_portfolio_options_<?php echo $portfolioID; ?>" class="">
            <ul id="sort-by" class="option-set clearfix" data-option-key="sortBy">
                <li><a href="#sortBy=original-order" data-option-value="original-order" class="selected" data><?php echo $paramssld["ht_view6_sorting_name_by_default"]; ?></a></li>
                <li><a href="#sortBy=id" data-option-value="id"><?php echo $paramssld["ht_view6_sorting_name_by_id"]; ?></a></li>
                <li><a href="#sortBy=symbol" data-option-value="symbol"><?php echo $paramssld["ht_view6_sorting_name_by_name"]; ?></a></li>
                <li id="shuffle"><a href='#shuffle'><?php echo $paramssld["ht_view6_sorting_name_by_random"]; ?></a></li>
            </ul>
              
            <ul id="sort-direction" class="option-set clearfix" data-option-key="sortAscending">
                <li><a href="#sortAscending=true" data-option-value="true" class="selected"><?php echo $paramssld["ht_view6_sorting_name_by_asc"]; ?></a></li>
                <li><a href="#sortAscending=false" data-option-value="false"><?php echo $paramssld["ht_view6_sorting_name_by_desc"]; ?></a></li>
            </ul>
          </div>
  <?php }
   if($portfolioShowFiltering == "on")
      { ?>
         <div id="huge_it_portfolio_filters_<?php echo $portfolioID; ?>">
            <ul>
                <li rel="*"><a><?php echo $paramssld["ht_view6_cat_all"];?></a></li>
                <?php
                $portfolioCats = explode(",", $portfolioCats);
                foreach ($portfolioCats as $portfolioCatsValue) {
                    if(!empty($portfolioCatsValue))
                    {
                ?>
                <li rel=".<?php echo str_replace(" ","_",$portfolioCatsValue); ?>"><a><?php echo str_replace("_"," ",$portfolioCatsValue); ?></a></li>
                    <?php
                    }
                }
                ?>
            </ul>
        </div>
<?php } ?>
        <div id="huge_it_portfolio_container_<?php echo $portfolioID; ?>" class="super-list variable-sizes clearfix" <?php if($paramssld["ht_view6_sorting_float"] == "top" && $paramssld["ht_view6_filtering_float"] == "top") echo "style='clear: both;'";?>>

    
          <?php
	
            foreach($images as $key=>$row)
            {
                    $link = $row->sl_url;
                    $descnohtml=strip_tags($row->description);
                    $result = substr($descnohtml, 0, 50);
                    $catForFilter = explode(",", $row->category);
                    ?>
                    <div class="portelement_<?php echo $portfolioID; ?> portfolio-lightbox <?php foreach ($catForFilter as $catForFilterValue) { echo str_replace(" ","_",$catForFilterValue)." ";} ?> " tabindex="0" data-symbol="<?php echo $row->name; ?>" data-category="alkaline-earth">
                        <p style="display: none;" class="id"><?php echo $row->id; ?></p>
                            <div class="image-block_<?php echo $portfolioID; ?>">
                                <?php //echo $row->id; ?>
                                    <?php $imgurl=explode(";",$row->image_url); ?>
                                            <?php 	
											if($row->image_url != ';'){
												switch(youtube_or_vimeo_portfolio($imgurl[0])) { 
													case 'image':	?>
														<a href="<?php echo $imgurl[0]; ?>" class=" portfolio-lightbox-group<?php echo $portfolioID; ?>" title = "<?php echo $row->name; ?>">
															<img alt="<?php echo $row->name; ?>" id="wd-cl-img<?php echo $key; ?>" src="<?php echo get_huge_image($imgurl[0],$image_prefix); ?>" />
														</a>
													<?php 
													break;
													case 'youtube':

														$videourl=get_video_id_from_url_portfolio($imgurl[0]);?>
														 <a href="https://www.youtube.com/embed/<?php echo $videourl[0];?>" class="huge_it_portfolio_item youtube portfolio-lightbox-group<?php echo $portfolioID; ?>"  title = "<?php echo $row->name;?>">
															<img alt="<?php echo $row->name; ?>" id="wd-cl-img<?php echo $key; ?>"  src="//img.youtube.com/vi/<?php echo $videourl[0]; ?>/mqdefault.jpg"  />
															<div class="play-icon <?php echo $videourl[1];?>-icon"></div>
														</a>
													<?php 
													break;
													case 'vimeo':
														$videourl=get_video_id_from_url_portfolio($imgurl[0]);
														$hash = unserialize(file_get_contents("http://vimeo.com/api/v2/video/".$videourl[0].".php"));
														$imgsrc=$hash[0]['thumbnail_large'];?>
														<a class="huge_it_portfolio_item vimeo portfolio-lightbox-group<?php echo $portfolioID; ?>" href="http://player.vimeo.com/video/<?php echo $videourl[0]; ?>" title="<?php echo $row->name; ?>">
															<img src="<?php echo $imgsrc; ?>" alt="<?php echo $row->name; ?>" /><div class="play-icon vimeo-icon"></div>
														</a>
													<?php
													break;
												} 
											} 
											
											else { ?>
                                            <img alt="<?php echo $row->name; ?>" id="wd-cl-img<?php echo $key; ?>" src="images/noimage.jpg" />
                                            <?php
                                            } ?>	
                            </div>
                            <?php if($row->name!=""){?>
                            <div class="title-block_<?php echo $portfolioID; ?>">
                                    <a href="<?php echo $link; ?>" <?php if ($row->link_target=="on"){echo 'target="_blank"';}?>><?php echo $row->name; ?></a>
                            </div>
                            <?php } ?>
                    </div>	
                    <?php
            }?>

            <div style="clear:both;"></div>
        </div>

    
</section>

<script>
jQuery(function(){
    
var defaultBlockWidth=<?php echo $paramssld['ht_view6_width']; ?>;
    
    var $container = jQuery('#huge_it_portfolio_container_<?php echo $portfolioID; ?>');
    
    
      // add randomish size classes
      $container.find('.portelement_<?php echo $portfolioID; ?>').each(function(){
        var $this = jQuery(this),
            number = parseInt( $this.find('.number').text(), 10 );
			//alert(number);
        if ( number % 7 % 2 === 1 ) {
          $this.addClass('width2');
        }
        if ( number % 3 === 0 ) {
          $this.addClass('height2');
        }
      });
    
    $container.hugeitmicro({
      itemSelector : '.portelement_<?php echo $portfolioID; ?>',
      masonry : {
        columnWidth : <?php echo $paramssld['ht_view6_width']; ?>+20+<?php echo $paramssld['ht_view6_border_width']*2; ?>
      },
      masonryHorizontal : {
        rowHeight: 300+20
      },
      cellsByRow : {
        columnWidth : 300+20,
        rowHeight : 240
      },
      cellsByColumn : {
        columnWidth : 300+20,
        rowHeight : 240
      },
      getSortData : {
        symbol : function( $elem ) {
          return $elem.attr('data-symbol');
        },
        category : function( $elem ) {
          return $elem.attr('data-category');
        },
        number : function( $elem ) {
          return parseInt( $elem.find('.number').text(), 10 );
        },
        weight : function( $elem ) {
          return parseFloat( $elem.find('.weight').text().replace( /[\(\)]/g, '') );
        },
        id : function ( $elem ) {
          return $elem.find('.id').text();
        }
      }
    });
    
    
      var $optionSets = jQuery('#huge_it_portfolio_options_<?php echo $portfolioID; ?> .option-set'),
          $optionLinks = $optionSets.find('a');

      $optionLinks.click(function(){
        var $this = jQuery(this);

        if ( $this.hasClass('selected') ) {
          return false;
        }
        var $optionSet = $this.parents('.option-set');
        $optionSet.find('.selected').removeClass('selected');
        $this.addClass('selected');
  

        var options = {},
            key = $optionSet.attr('data-option-key'),
            value = $this.attr('data-option-value');

        value = value === 'false' ? false : value;
        options[ key ] = value;
        if ( key === 'layoutMode' && typeof changeLayoutMode === 'function' ) {

          changeLayoutMode( $this, options )
        } else {

          $container.hugeitmicro( options );
        }
        
        return false;
      });


     jQuery(window).resize(function(){
			  $container.hugeitmicro('reLayout');
	});

      var isHorizontal = false;
      function changeLayoutMode( $link, options ) {
        var wasHorizontal = isHorizontal;
        isHorizontal = $link.hasClass('horizontal');

        if ( wasHorizontal !== isHorizontal ) {

          var style = isHorizontal ? 
            { height: '75%', width: $container.width() } : 
            { width: 'auto' };

          $container.filter(':animated').stop();

          $container.addClass('no-transition').css( style );
          setTimeout(function(){
            $container.removeClass('no-transition').hugeitmicro( options );
          }, 100 )
        } else {
          $container.hugeitmicro( options );
        }
      }


    

      $container.delegate('.default-block_<?php echo $portfolioID; ?>', 'click', function(){
          var strheight=0;
          jQuery(this).parents('.portelement_<?php echo $portfolioID; ?>').find('.wd-portfolio-panel_<?php echo $portfolioID; ?> > div').each(function(){
                strheight+=jQuery(this).outerHeight()+10;
                //alert(strheight);
          })
          strheight+=<?php echo (isset($paramssld['ht_view6_block_height'])?$paramssld['ht_view6_block_height']:0)+45; ?>;
	  			if(jQuery(this).parents('.portelement_<?php echo $portfolioID; ?>').hasClass("large")){
			jQuery(this).parents('.portelement_<?php echo $portfolioID; ?>').animate({
				height: "<?php echo (isset($paramssld['ht_view6_block_height'])?$paramssld['ht_view6_block_height']:0)+45; ?>px"
			}, 300, function() {
				jQuery(this).removeClass('large');
				$container.hugeitmicro('reLayout');
			});
			
			jQuery(this).parents('.portelement_<?php echo $portfolioID; ?>').removeClass("active");
			return false;
		}
		
	
		jQuery(this).parents('.portelement_<?php echo $portfolioID; ?>').css({height:strheight});
		jQuery(this).parents('.portelement_<?php echo $portfolioID; ?>').addClass('large');

		$container.hugeitmicro('reLayout');
		jQuery(this).parents('.portelement_<?php echo $portfolioID; ?>').css({height:"<?php echo (isset($paramssld['ht_view6_block_height'])?$paramssld['ht_view6_block_height']:0)+45; ?>px"});		 
		 
		//alert(strheight);
		 
		 jQuery(this).parents('.portelement_<?php echo $portfolioID; ?>').animate({
			height:strheight+"px",
		  }, 300,function(){	$container.hugeitmicro('reLayout');});
	});

    var $sortBy =  jQuery('#huge_it_portfolio_content_<?php echo $portfolioID; ?> #sort-by');
    jQuery('#huge_it_portfolio_content_<?php echo $portfolioID; ?> #shuffle a').click(function(){
      $container.hugeitmicro('shuffle');
      $sortBy.find('.selected').removeClass('selected');
      $sortBy.find('[data-option-value="random"]').addClass('selected');
      return false;
    });
    
    ////filteringgggggg

        // bind filter on select change
        jQuery(document).ready(function(){
            jQuery('#huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul li').click(function() {
              // get filter value from option value
              var filterValue = jQuery(this).attr('rel');
              // use filterFn if matches value
              filterValue = filterValue;//filterFns[ filterValue ] || 
              $container.hugeitmicro({ filter: filterValue });
            });
            <?php if(($paramssld["ht_view6_sorting_float"] == "left" || $paramssld["ht_view6_sorting_float"] == "right") && $paramssld["ht_view6_filtering_float"] == "none")
                  { ?>
                        var topmargin = jQuery("#huge_it_portfolio_filters_<?php echo $portfolioID; ?> ul").height();
                        jQuery("#huge_it_portfolio_options_<?php echo $portfolioID; ?>").css({'margin-top':parseInt(topmargin) + 5});
            <?php }
            else  {
                    if(($paramssld["ht_view6_filtering_float"] == "left" || $paramssld["ht_view6_filtering_float"] == "right") && $paramssld["ht_view6_sorting_float"] == "none")
                      { ?>
                         var topmargin = jQuery("#huge_it_portfolio_options_<?php echo $portfolioID; ?>").height();
                         jQuery("#huge_it_portfolio_filters_<?php echo $portfolioID; ?>").css({'margin-top':'5px'});
                <?php }
                  } ?>
        });
        
        jQuery(window).load(function(){

            $container.hugeitmicro({ filter: '*' });
        });

  });
</script>
<?php 
}
 ?>
   
	
      <?php   
	return ob_get_clean();
}
?>