<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://codeboxr.com
 * @since      1.0.0
 *
 * @package    Cbxdynamicsidebar
 * @subpackage Cbxdynamicsidebar/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    CBXDynamicSidebar
 * @subpackage CBXDynamicSidebar/public
 * @author     Codeboxr <info@codeboxr.com>
 */
class CBXDynamicSidebar_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 *
	 * @param      string $plugin_name The name of the plugin.
	 * @param      string $version     The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Init all shortcodes here
	 */
	public function init_shortcodes() {
		add_shortcode( 'cbxdynamicsidebar', array( $this, 'cbxdynamicsidebar_shortcode' ) );
	}//end method init_shortcodes


	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_register_style( 'cbxdynamicsidebar-public',
			plugin_dir_url( __FILE__ ) . 'css/cbxdynamicsidebar-public.css',
			array(),
			$this->version,
			'all' );
		wp_enqueue_style( 'cbxdynamicsidebar-public' );
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_register_script( 'cbxdynamicsidebar-public',
			plugin_dir_url( __FILE__ ) . 'js/cbxdynamicsidebar-public.js',
			array( 'jquery' ),
			$this->version,
			true );
		wp_enqueue_script( 'cbxdynamicsidebar-public' );
	}

	/**
	 * Shortcode to display sidebar
	 *
	 * @param $atts
	 *
	 * @return string
	 */
	public function cbxdynamicsidebar_shortcode( $atts ) {
		$arr = shortcode_atts( array(
			'id' => 0,
			'wclass' => 'cbxdynamicsidebar_wrapper',
			'wid' => 'cbxdynamicsidebar_wrapper',
			'float' => 'none',

		),
			$atts );

		extract( $arr );


		if ( $wclass == '' ) {
			$wclass = 'cbxdynamicsidebar_wrapper';
		}

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

	}//end method cbxdynamicsidebar_shortcode
}//end class CBXDynamicSidebar_Public
