<?php

/**
 * Class for handling all the default styling.
 *
 * Class SiteOrigin_Panels_Default_Styling
 */
class SiteOrigin_Panels_Lite_Default_Styling {

	static function init() {
		// Filter the row style
		add_filter('siteorigin_panels_row_style_attributes', array('SiteOrigin_Panels_Lite_Default_Styling', 'row_style_attributes' ), 10, 2);
		add_filter('siteorigin_panels_cell_style_attributes', array('SiteOrigin_Panels_Lite_Default_Styling', 'cell_style_attributes' ), 10, 2);
		add_filter('siteorigin_panels_widget_style_attributes', array('SiteOrigin_Panels_Lite_Default_Styling', 'widget_style_attributes' ), 10, 2);

		// Main filter to add any custom CSS.
		add_filter('siteorigin_panels_css_object', array('SiteOrigin_Panels_Lite_Default_Styling', 'filter_css_object' ), 10, 3);

		// Filtering specific attributes
		add_filter('siteorigin_panels_css_row_margin_bottom', array('SiteOrigin_Panels_Lite_Default_Styling', 'filter_row_bottom_margin' ), 10, 2);
		add_filter('siteorigin_panels_css_row_gutter', array('SiteOrigin_Panels_Lite_Default_Styling', 'filter_row_gutter' ), 10, 2);
	}

	static function row_style_attributes( $attributes, $args ) {
		if( !empty( $args['row_stretch'] ) ) {
			$attributes['class'][] = 'siteorigin-panels-stretch';
			$attributes['data-stretch-type'] = $args['row_stretch'];

			$js_suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
			wp_enqueue_script('siteorigin-panels-front-styles', get_template_directory_uri() . '/inc/panels-lite/js/styling' . $js_suffix . '.js', array('jquery'), SITEORIGIN_PANELS_LITE_VERSION );
		}

		if( !empty( $args['class'] ) ) {
			$attributes['class'] = array_merge( $attributes['class'], explode(' ', $args['class']) );
		}

		if( !empty($args['row_css']) ){
			preg_match_all('/(.+?):(.+?);?$/', $args['row_css'], $matches);

			if(!empty($matches[0])){
				for($i = 0; $i < count($matches[0]); $i++) {
					$attributes['style'] .= $matches[1][$i] . ':' . $matches[2][$i] . ';';
				}
			}
		}

		if( !empty( $args['padding'] ) ) {
			$attributes['style'] .= 'padding: ' . esc_attr($args['padding']) . ';';
		}

		if( !empty( $args['background'] ) ) {
			$attributes['style'] .= 'background-color:' . $args['background']. ';';
		}

		if( !empty( $args['background_image_attachment'] ) ) {
			$url = wp_get_attachment_image_src( $args['background_image_attachment'], 'full' );

			if( !empty($url) ) {
				$attributes['style'] .= 'background-image: url(' . $url[0] . ');';
			}

			switch( $args['background_display'] ) {
				case 'tile':
					$attributes['style'] .= 'background-repeat: repeat;';
					break;
				case 'cover':
					$attributes['style'] .= 'background-size: cover;';
					break;
				case 'center':
					$attributes['style'] .= 'background-position: center center; background-repeat: no-repeat;';
					break;
			}
		}

		if( !empty( $args['border_color'] ) ) {
			$attributes['style'] .= 'border: 1px solid ' . $args['border_color']. ';';
		}

		return $attributes;
	}

	static function cell_style_attributes( $attributes, $row_args ) {
		if( !empty( $row_args['cell_class'] ) ) {
			if( empty($attributes['class']) ) $attributes['class'] = array();
			$attributes['class'] = array_merge( $attributes['class'], explode(' ', $row_args['cell_class']) );
		}

		return $attributes;
	}

	static function widget_style_attributes( $attributes, $args ) {
		if( !empty( $args['class'] ) ) {
			if( empty($attributes['class']) ) $attributes['class'] = array();
			$attributes['class'] = array_merge( $attributes['class'], explode(' ', $args['class']) );
		}

		if( !empty($args['widget_css']) ){
			preg_match_all('/(.+?):(.+?);?$/', $args['widget_css'], $matches);

			if(!empty($matches[0])){
				for($i = 0; $i < count($matches[0]); $i++) {
					$attributes['style'] .= $matches[1][$i] . ':' . $matches[2][$i] . ';';
				}
			}
		}

		if( !empty( $args['padding'] ) ) {
			$attributes['style'] .= 'padding: ' . esc_attr($args['padding']) . ';';
		}

		if( !empty( $args['background'] ) ) {
			$attributes['style'] .= 'background-color:' . $args['background']. ';';
		}

		if( !empty( $args['background_image_attachment'] ) ) {
			$url = wp_get_attachment_image_src( $args['background_image_attachment'], 'full' );

			if( !empty($url) ) {
				$attributes['style'] .= 'background-image: url(' . $url[0] . ');';
			}

			switch( $args['background_display'] ) {
				case 'tile':
					$attributes['style'] .= 'background-repeat: repeat;';
					break;
				case 'cover':
					$attributes['style'] .= 'background-size: cover;';
					break;
				case 'center':
					$attributes['style'] .= 'background-position: center center; background-repeat: no-repeat;';
					break;
			}
		}

		if( !empty( $args['border_color'] ) ) {
			$attributes['style'] .= 'border: 1px solid ' . $args['border_color']. ';';
		}

		if( !empty( $args['font_color'] ) ) {
			$attributes['style'] .= 'color: ' . $args['font_color']. ';';
		}

		return $attributes;
	}

	static function filter_css_object( $css, $panels_data, $post_id ) {
		return $css;
	}

	static function filter_row_bottom_margin( $margin, $grid ){
		if( !empty($grid['style']['bottom_margin']) ) {
			$margin = $grid['style']['bottom_margin'];
		}
		return $margin;
	}

	static function filter_row_gutter( $gutter, $grid ) {
		if( !empty($grid['style']['gutter']) ) {
			$gutter = $grid['style']['gutter'];
		}

		return $gutter;
	}

}

SiteOrigin_Panels_Lite_Default_Styling::init();