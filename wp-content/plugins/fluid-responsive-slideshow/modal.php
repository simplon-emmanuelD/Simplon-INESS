<?php

function frs_modal($post_id)
{
	if($post_id!='false')
		$post = get_post($post_id);
	else{
		$post = new FRSPost();
		$post->post_content="";
		$post->ID=null;
	}

	/**
	 * callback response for left content
	 */
	tonjoo_slideshow_meta($post);
}
