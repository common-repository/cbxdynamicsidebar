<?php
/**
 * The file provides some helper static method
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
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
?>
<?php

class CBXDynamicSidebarHelper {
	/**
	 * Returns post types as array
	 *
	 * @return array
	 */
	public static function post_types( $plain = true ) {
		$post_type_args = array(
			'builtin' => array(
				'options' => array(
					'public'   => true,
					'_builtin' => true,
					'show_ui'  => true,
				),
				'label'   => esc_html__( 'Built in post types', 'cbxdynamicsidebar' ),
			),

		);

		$post_type_args = apply_filters( 'cbxdynamicsidebar_post_types_args', $post_type_args );

		$output   = 'objects'; // names or objects, note names is the default
		$operator = 'and'; // 'and' or 'or'

		$postTypes = array();

		if ( $plain ) {
			foreach ( $post_type_args as $postArgType => $postArgTypeArr ) {
				$types = get_post_types( $postArgTypeArr['options'], $output, $operator );

				if ( ! empty( $types ) ) {
					foreach ( $types as $type ) {
						//$postTypes[ $postArgType ]['label']                = $postArgTypeArr['label'];
						$postTypes[ $type->name ] = $type->labels->name;
					}
				}
			}
		} else {
			foreach ( $post_type_args as $postArgType => $postArgTypeArr ) {
				$types = get_post_types( $postArgTypeArr['options'], $output, $operator );

				if ( ! empty( $types ) ) {
					foreach ( $types as $type ) {
						//$postTypes[ $postArgType ]['label']                = $postArgTypeArr['label'];
						$postTypes[ esc_attr( $postArgTypeArr['label'] ) ][ $type->name ] = $type->labels->name;
					}
				}
			}
		}


		return apply_filters( 'cbxdynamicsidebar_post_types', $postTypes, $plain );
	}

	/**
	 * Get the user roles
	 *
	 * @param string $useCase
	 *
	 * @return array
	 */
	public static function user_roles( $plain = true, $include_guest = false ) {
		global $wp_roles;

		if ( ! function_exists( 'get_editable_roles' ) ) {
			require_once( ABSPATH . '/wp-admin/includes/user.php' );

		}

		$userRoles = array();
		if ( $plain ) {
			foreach ( get_editable_roles() as $role => $roleInfo ) {
				$userRoles[ $role ] = $roleInfo['name'];
			}
			if ( $include_guest ) {
				$userRoles['guest'] = esc_html__( "Guest", 'cbxdynamicsidebar' );
			}
		} else {
			//optgroup
			$userRoles_r = array();
			foreach ( get_editable_roles() as $role => $roleInfo ) {
				$userRoles_r[ $role ] = $roleInfo['name'];
			}

			$userRoles = array(
				'Registered' => $userRoles_r,
			);

			if ( $include_guest ) {
				$userRoles['Anonymous'] = array(
					'guest' => esc_html__( "Guest", 'cbxdynamicsidebar' ),
				);
			}
		}

		return apply_filters( 'cbxdynamicsidebar_userroles', $userRoles, $plain, $include_guest );
	}

	/**
	 * Return user plain user role keys
	 *
	 * @param bool $include_guest
	 *
	 * @return array
	 */
	public static function user_plain_role_keys( $include_guest = false ) {
		$all_user_roles = CBXDynamicSidebarHelper::user_roles( true, $include_guest );

		return array_keys( $all_user_roles );
	}//end method user_plain_role_keys

	/**
	 * List all global option name with prefix cbxpoll_
	 */
	public static function getAllOptionNames() {
		global $wpdb;

		$prefix       = 'cbxscratingreview_';
		$option_names = $wpdb->get_results( "SELECT * FROM {$wpdb->options} WHERE option_name LIKE '{$prefix}%'",
			ARRAY_A );

		return apply_filters( 'cbxscratingreview_option_names', $option_names );
	}

	/**
	 * @param $timestamp
	 *
	 * @return false|string
	 */
	public static function dateReadableFormat( $timestamp, $format = 'M j, Y' ) {
		$format = ( $format == '' ) ? 'M j, Y' : $format;

		return date( $format, strtotime( $timestamp ) );
	}


	/**
	 * HTML elements, attributes, and attribute values will occur in your output
	 * @return array
	 */
	public static function allowedHtmlTags() {
		$allowed_html_tags = [
			'a'      => array(
				'href'  => array(),
				'title' => array(),
				//'class' => array(),
				//'data'  => array(),
				//'rel'   => array(),
			),
			'br'     => array(),
			'em'     => array(),
			'ul'     => array(//'class' => array(),
			),
			'ol'     => array(//'class' => array(),
			),
			'li'     => array(//'class' => array(),
			),
			'strong' => array(),
			'p'      => array(
				//'class' => array(),
				//'data'  => array(),
				//'style' => array(),
			),
			'span'   => array(
				//					'class' => array(),
				//'style' => array(),
			),
		];

		return $allowed_html_tags;
	}


	/**
	 * Char Length check  thinking utf8 in mind
	 *
	 * @param $text
	 *
	 * @return int
	 */
	public static function utf8_compatible_length_check( $text ) {
		if ( seems_utf8( $text ) ) {
			$length = mb_strlen( $text );
		} else {
			$length = strlen( $text );
		}

		return $length;
	}

	/**
	 * Get Active Insta posts post types
	 */
	public static function get_active_instaposts() {
		$active_insta = array();

		$args = array(
			'post_type'      => 'cbxsidebar',
			'meta_key'       => '_cbxdynamicsidebar',
			'posts_per_page' => - 1,
			'post_status'    => array( 'publish' ),
		);


		$active_posts = get_posts( $args );

		global $post;
		foreach ( $active_posts as $post ) :
			CBXDynamicSidebarHelper::setup_admin_postdata( $post );
			$post_id    = $post->ID;
			$post_title = get_the_title( $post_id );

			$post_meta = get_post_meta( $post_id, '_cbxdynamicsidebar', true );

			if ( isset( $post_meta['active'] ) && $post_meta['active'] == 1 ) {
				$active_insta[ $post_id ] = $post_title;
			}
		endforeach;
		CBXDynamicSidebarHelper::wp_reset_admin_postdata();

		return $active_insta;
	}//end method get_active_instaposts

	/**
	 * Setup a post object and store the original loop item so we can reset it later
	 *
	 * @param obj $post_to_setup The post that we want to use from our custom loop
	 */
	public static function setup_admin_postdata( $post_to_setup ) {

		//only on the admin side
		if ( is_admin() ) {

			//get the post for both setup_postdata() and to be cached
			global $post;

			//only cache $post the first time through the loop
			if ( ! isset( $GLOBALS['post_cache'] ) ) {
				$GLOBALS['post_cache'] = $post;
			}

			//setup the post data as usual
			$post = $post_to_setup;
			setup_postdata( $post );
		} else {
			setup_postdata( $post_to_setup );
		}
	}


	/**
	 * Reset $post back to the original item
	 *
	 */
	public static function wp_reset_admin_postdata() {

		//only on the admin and if post_cache is set
		if ( is_admin() && ! empty( $GLOBALS['post_cache'] ) ) {

			//globalize post as usual
			global $post;

			//set $post back to the cached version and set it up
			$post = $GLOBALS['post_cache'];
			setup_postdata( $post );

			//cleanup
			unset( $GLOBALS['post_cache'] );
		} else {
			wp_reset_postdata();
		}
	}

	/**
	 * Show action links on the plugin screen.
	 *
	 * @param   mixed $links Plugin Action links.
	 *
	 * @return  array
	 */
	public static function plugin_action_links( $links ) {
		$action_links = array(
			'settings' => '<a href="' . admin_url( 'admin.php?page=cbxscratingreviewsettings' ) . '" aria-label="' . esc_attr__( 'View settings',
					'cbxscratingreview' ) . '">' . esc_html__( 'Settings', 'cbxscratingreview' ) . '</a>',
		);

		return array_merge( $action_links, $links );
	}


}//end class CBXDynamicSidebarHelper