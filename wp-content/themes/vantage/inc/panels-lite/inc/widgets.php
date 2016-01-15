<?php

/**
 * Render any missing widgets that Page Builder will eventually provide
 *
 * @param $code
 * @param $widget
 * @param $args
 * @param $instance
 *
 * @return string
 */
function siteorigin_panels_lite_missing_widget($code, $widget, $args , $instance){
	switch($widget) {
		case 'SiteOrigin_Panels_Widgets_PostLoop':
			ob_start();
			SiteOrigin_Panels_Lite_Missing_Widgets::postloop($args, $instance);
			$code = ob_get_clean();
			break;
	}

	return $code;
}
add_filter('siteorigin_panels_missing_widget', 'siteorigin_panels_lite_missing_widget', 5, 4);

/**
 * Class that handles all the basic missing widget rendering.
 *
 * Class SiteOrigin_Panels_Lite_Missing_Widgets
 */
class SiteOrigin_Panels_Lite_Missing_Widgets {
	static function postloop( $args, $instance ){
		if( empty($instance['template']) ) return;
		if( is_admin() ) return;

		echo $args['before_widget'];

		$instance['title'] = apply_filters('widget_title', $instance['title'], $instance, 'siteorigin-panels-postloop');
		if ( !empty( $instance['title'] ) ) {
			echo $args['before_title'] . $instance['title'] . $args['after_title'];
		}

		if(strpos('/'.$instance['template'], '/content') !== false) {
			while(have_posts()) {
				the_post();
				locate_template($instance['template'], true, false);
			}
		}
		else {
			locate_template($instance['template'], true, false);
		}

		echo $args['after_widget'];

		// Reset everything
		rewind_posts();
		wp_reset_postdata();
	}
}