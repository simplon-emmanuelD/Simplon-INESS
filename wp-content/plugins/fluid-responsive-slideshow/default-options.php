<?php

$options = get_option('pjc_slideshow_options');

if (!isset($options[$current]["height"]))
	$options[$current]["height"] = '350';

if (!isset($options[$current]["width"]))
	$options[$current]["width"] = "650";

if (!isset($options[$current]["full_width"]))
	$options[$current]["full_width"] = "false";

if (!isset($options[$current]["min_height"]))
	$options[$current]["min_height"] = "300";

if (!isset($options[$current]["max_height"]))
	$options[$current]["max_height"] = "0";

if (!isset($options[$current]["show_textbox"]))
	$options[$current]["show_textbox"] = "true";

if (!isset($options[$current]["textbox_height"]))
	$options[$current]["textbox_height"] = "100";

if (!isset($options[$current]["fade_time"]))
	$options[$current]["fade_time"] = "2500";

if (!isset($options[$current]["textbox_p_size"]))
	$options[$current]["textbox_p_size"] = "16";

if (!isset($options[$current]["textbox_h4_size"]))
	$options[$current]["textbox_h4_size"] = "22";

if (!isset($options[$current]["textbox_padding"]))
	$options[$current]["textbox_padding"] = "10";

if (!isset($options[$current]["hover"]))
	$options[$current]["hover"] = "true";

if (!isset($options[$current]["navigation"]))
	$options[$current]["navigation"] = "true";

if (!isset($options[$current]["bullet"]))
	$options[$current]["bullet"] = "true";

if (!isset($options[$current]["bullet_thumbs"]))
	$options[$current]["bullet_thumbs"] = "false";

if (!isset($options[$current]["animation"]) || !in_array($options[$current]["animation"], array('horizontal-slide','vertical-slide','fade')))
	$options[$current]["animation"] = "horizontal-slide";

if (!isset($options[$current]["animation_time"]))
	$options[$current]["animation_time"] = "800";

if (!isset($options[$current]["show_timer"]))
	$options[$current]["show_timer"] = "true";

if (!isset($options[$current]["pause"]))
	$options[$current]["pause"] = "false";

if (!isset($options[$current]["start_mouseout"]))
	$options[$current]["start_mouseout"] = "false";

if (!isset($options[$current]["start_mouseout_after"] ))
	$options[$current]["start_mouseout_after"] = "800";

if (!isset($options[$current]["custom_css"] ))
	$options[$current]["custom_css"] = "";

/**
 * License Code
 */	
if(!isset($options['license_key'])){
	$options['license_key']="";
}
?>
