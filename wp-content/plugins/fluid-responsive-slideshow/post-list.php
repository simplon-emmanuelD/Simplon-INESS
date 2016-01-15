<?php
/*
 * Taxonomy filter on post list
 */
if(is_admin())
{
	add_filter('parse_request','convert_slide_type_id_to_taxonomy_term_in_query');
	function convert_slide_type_id_to_taxonomy_term_in_query($query) {
	   	global $pagenow;
	    $qv = &$query->query_vars;

	    if ($pagenow=='edit.php' && isset($qv['slide_type']) && $qv['slide_type'] != 0 && isset($qv['post_type']) && $qv['post_type']=='pjc_slideshow') 
	    {
	    	$term = get_term_by('slug',$qv['slide_type'],'slide_type');

	    	if($term == null)
	    	{
	    		$term = get_term_by('id',$qv['slide_type'],'slide_type');
	        	
	    	}

	    	$qv['term'] = $term->term_id;
	        $qv['slide_type'] = $term->slug;
	    }
	    else
	    {
	    	$qv['term'] = 0;
	    }
	}
}

add_action('restrict_manage_posts','restrict_pjc_slideshow_by_slide_type');
function restrict_pjc_slideshow_by_slide_type() {
    global $typenow;
    global $wp_query;
    if ($typenow=='pjc_slideshow') {
        $taxonomy = 'slide_type';
        $slide_type_taxonomy = get_taxonomy($taxonomy);
        wp_dropdown_categories(array(
            'show_option_all' =>  __("Show All {$slide_type_taxonomy->label}"),
            'taxonomy'        =>  $taxonomy,
            'name'            =>  'slide_type',
            'orderby'         =>  'name',
            'selected'        =>  $wp_query->query['term'],
            'hierarchical'    =>  true,
            'depth'           =>  3,
            'show_count'      =>  true, // Show # listings in parens
            'hide_empty'      =>  true, // Don't show businesses w/o listings
        ));
    }
}


/**
 * Modify which columns display in the admin views 
 */
add_filter('manage_pjc_slideshow_posts_columns', 'pjc_slideshow_posts_columns');
function pjc_slideshow_posts_columns($posts_columns) {
	$tmp = array();

	// foreach ($posts_columns as $key => $value) {
	// 	$tmp[$key] = $value;
	// }

	$tmp['cb'] = '<input type="checkbox" />';

	$tmp['pjc_slideshow_title'] = '';
	$tmp['title'] = "Title";
	$tmp['pjc_slideshow_type'] = 'Shortcode';
	$tmp['pjc_slideshow_order'] = 'Order Number';
	$tmp['date'] = 'Date';

	return $tmp;
}


/**
 * Custom column output when admin is viewing the pjc_slideshow post type.
 */
add_action('manage_posts_custom_column', 'pjc_slideshow_custom_column');
function pjc_slideshow_custom_column($column_name) {
	global $post;

	if ($column_name == 'pjc_slideshow_title') {
		$thumb_img = get_the_post_thumbnail($post -> ID, array(60,60));		

		$thumb_img = str_replace('<img', '<img style="width:60px"', $thumb_img);

		if(! empty($thumb_img))
		{
			echo "<a href='" . get_edit_post_link($post -> ID) . "'>" . $thumb_img . "</a>";
		}
		else
		{
			$postmeta = get_post_meta($post->ID, 'tonjoo_frs_meta',true);

			if (!isset($postmeta["slider_bg"] )) $postmeta["slider_bg"] = "#000000";

			echo "<div style='height:60px;width:60px;background-color:{$postmeta["slider_bg"]};'></div>";
		}
	}
	if ($column_name == 'pjc_slideshow_order') {
		$order =  get_post_meta($post -> ID,'tonjoo_frs_order_number',true);
		
	

		if(!isset($order))
			echo "<b style='color:red'>no order no. ,slide will not be shown</b>";
		else
			echo "$order";

	}
	if ($column_name == 'pjc_slideshow_type') {

		$terms = get_the_terms($post -> ID, 'slide_type');

		if($terms){
			// Loop over each item since it's an array
			foreach ($terms as $term) {
				// Print the name method from $term which is an OBJECT
				print " [pjc_slideshow slide_type='{$term -> slug}'] <br />";
				// Get rid of the other data stored in the object, since it's not needed
				unset($term);
			}
		}
		else{
			print "<b style='color:red'>no slide type,slide will not be shown</b>";
		}
	}
}


/**
 * Make the "Featured Image" metabox front and center when editing a pjc_slideshow post.
 */
add_action('add_meta_boxes_pjc_slideshow', 'pjc_slideshow_metaboxes');
function pjc_slideshow_metaboxes($post) {
	global $wp_meta_boxes;

	remove_meta_box('postimagediv', 'pjc_slideshow', 'normal');
	add_meta_box('postimagediv', __('Featured Image'), 'post_thumbnail_meta_box', 'pjc_slideshow', 'side', 'low');
}