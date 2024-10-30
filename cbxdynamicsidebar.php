<?php
/**
 *
 * @link              https://codeboxr.com
 * @since             1.0.1
 * @package           Cbxdynamicsidebar
 *
 * @wordpress-plugin
 * Plugin Name:       CBX Sidebar - Custom Sidebar/Custom Widget area
 * Plugin URI:        https://codeboxr.com/product/cbx-dynamic-sidebar-for-wordpress/
 * Description:       Dynamic sidebar for wordpress using custom post type and shortcode
 * Tags: custom sidebars, unlimited sidebars, replace sidebars, dynamic sidebar, create sidebars, sidebar replacement, sidebar manager, widget area manager, widget area replacement, unlimited sidebar generator, custom widget areas, wordpress multiple sidebars, sidebar plugin for wordpress, wordpress sidebar plugin
 * Version:           1.0.5
 * Author:            Codeboxr
 * Author URI:        https://codeboxr.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       cbxdynamicsidebar
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


defined( 'CBXDYNAMICSIDEBAR_PLUGIN_NAME' ) or define( 'CBXDYNAMICSIDEBAR_PLUGIN_NAME', 'cbxdynamicsidebar' );
defined( 'CBXDYNAMICSIDEBAR_PLUGIN_VERSION' ) or define( 'CBXDYNAMICSIDEBAR_PLUGIN_VERSION', '1.0.5' );
defined( 'CBXDYNAMICSIDEBAR_ROOT_PATH' ) or define( 'CBXDYNAMICSIDEBAR_ROOT_PATH', plugin_dir_path( __FILE__ ) );
defined( 'CBXDYNAMICSIDEBAR_BASE_NAME' ) or define( 'CBXDYNAMICSIDEBAR_BASE_NAME', plugin_basename( __FILE__ ) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-cbxdynamicsidebar-activator.php
 */
function activate_cbxdynamicsidebar() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-cbxdynamicsidebar-activator.php';
	Cbxdynamicsidebar_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-cbxdynamicsidebar-deactivator.php
 */
function deactivate_cbxdynamicsidebar() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-cbxdynamicsidebar-deactivator.php';
	Cbxdynamicsidebar_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_cbxdynamicsidebar' );
register_deactivation_hook( __FILE__, 'deactivate_cbxdynamicsidebar' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-cbxdynamicsidebar.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_cbxdynamicsidebar() {

	$plugin = new Cbxdynamicsidebar();
	$plugin->run();

}

run_cbxdynamicsidebar();
