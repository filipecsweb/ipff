<?php
/**
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @since       1.0.0
 * @version     1.0.0
 * @package     Ipff
 *
 * Plugin Name:       Instagram Pro for Free
 * Plugin URI:        #
 * Description:       Display a user Instagram feed (gallery) anywhere using shortcodes.
 * Version:           1.0.0
 * Author:            Filipe Seabra <filipe@seusobrinho.com.br>
 * Author URI:        http://seusobrinho.com.br
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       ipff
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'IPFF_PATH', plugin_dir_path( __FILE__ ) );
define( 'IPFF_URL', plugin_dir_url( __FILE__ ) );
define( 'IPFF_VERSION', '1.0.1' );
define( 'IPFF_SLUG', 'ipff' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-ipff-activator.php
 */
function activate_ipff() {
	require_once IPFF_PATH . 'includes/class-ipff-activator.php';
	Ipff_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-ipff-deactivator.php
 */
function deactivate_ipff() {
	require_once IPFF_PATH . 'includes/class-ipff-deactivator.php';
	Ipff_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_ipff' );
register_deactivation_hook( __FILE__, 'deactivate_ipff' );

add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), function ( $links ) {

	$url = admin_url( "options-general.php?page=ipff_settings_page" );

	$links[] = "<a href='$url'>" . __( "Settings" ) . "</a>";

	return $links;

} );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-ipff.php';

add_action( 'plugins_loaded', array( 'Ipff', 'run' ) );
