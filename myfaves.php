<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://myfaveswp.com
 * @since             1.0.0
 * @package           Myfaves
 *
 * @wordpress-plugin
 * Plugin Name:       My Faves
 * Plugin URI:        https://myfaveswp.com
 * Description:       Save your favorite posts, add tags, and list them.
 * Version:           1.2.5
 * Author:            A Website to Sparkle
 * Author URI:        https://awebsitetosparkle.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       myfaves
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'MYFAVES_VERSION', '1.2.5' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-myfaves-activator.php
 */
function activate_myfaves() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-myfaves-activator.php';
	Myfaves_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-myfaves-deactivator.php
 */
function deactivate_myfaves() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-myfaves-deactivator.php';
	Myfaves_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_myfaves' );
register_deactivation_hook( __FILE__, 'deactivate_myfaves' );

add_filter( 'plugin_row_meta', 'myfaves_plugin_row_meta', 10, 2 );
 
function myfaves_plugin_row_meta( $links, $file ) {    
    if ( plugin_basename( __FILE__ ) == $file ) {
        $row_meta = array(
          'feedback'    => '<a href="' . esc_url( 'https://awebsitetosparkle.typeform.com/to/OjG30t' ) . '" target="_blank" aria-label="' . esc_attr__( 'My Faves', 'domain' ) . '" style="color:blue;">' . esc_html__( 'Feedback', 'domain' ) . '</a>'
        );
 
        return array_merge( $links, $row_meta );
    }
    return (array) $links;
}



/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-myfaves.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_myfaves() {

	$plugin = new Myfaves();
	$plugin->run();

}
run_myfaves();
