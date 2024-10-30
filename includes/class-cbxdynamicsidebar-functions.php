<?php
/**
 * Use for each and quick function call
 *
 * @link       https://codeboxr.com
 * @since      1.0.0
 *
 * @package    Cbxdynamicsidebar
 * @subpackage Cbxdynamicsidebar/includes
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! function_exists( 'cbxdynamicsidebar_display' ) ) {
	/**
	 * Custom function call
	 *
	 * @param $arr
	 */
	function cbxdynamicsidebar_display( $atts ) {
		$arr = shortcode_atts( array(
			'id'     => 0,
			'wclass' => 'cbxdynamicsidebar_wrapper',
			'wid'    => 'cbxdynamicsidebar_wrapper',
			'float'  => 'none',

		),
			$atts );

		extract( $arr );

		if ( intval( $id ) < 1 ) {
			return '';
		}

		$sidebar_custom = get_post_meta( $id, '_cbxdynamicsidebar', true );
		$active         = intval( $sidebar_custom['active'] );

		if ( ! $active ) {
			return '';
		}

		$wid_attr = ( $wid != '' ) ? ' id="' . $wid . $id . '" ' : '';

		if ( is_active_sidebar( 'cbxdynamicsidebar-' . $id ) ) {
			$sidebar_html = '<div style="float:' . $float . ';" ' . $wid_attr . ' class="' . $wclass . ' ' . $wclass . $id . '" role="complementary">';
			ob_start();
			dynamic_sidebar( 'cbxdynamicsidebar-' . $id );
			$sidebar_html .= ob_get_contents();
			ob_end_clean();
			//echo $sidebar_html;
			$sidebar_html .= '</div>';

			return $sidebar_html;
		}

	}
}

/*
//how to call function from ohter plugin or theme
if(function_exists('cbxdynamicsidebar_display')){
	$config_array = array(
		'id'       => '686', //custom sidebar post id
		'wclass'        => 'cbxdynamicsidebar_wrapper',
		'wid'           => 'cbxdynamicsidebar_wrapper',
		'float'         => 'none'
	);
	echo cbxdynamicsidebar_display($config_array);
}
*/