<?php
/**
 * Plugin Name: Almost Ready Temporary Page
 * Plugin URI: https://github.com/JosVelasco/almost-ready-temporary-page
 * Description: A simple plugin that displays a customizable temporary page to visitors while allowing logged-in users to work on the site. Fully editable using native WordPress blocks.
 * Version: 1.0.0
 * Author: Jos Velasco
 * Author URI: https://josvelasco.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: almost-ready-temporary-page
 * Domain Path: /languages
 *
 * @package AlmostReadyTemporaryPage
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Current plugin version.
 */
define( 'ARTP_VERSION', '1.0.0' );
define( 'ARTP_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'ARTP_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/**
 * Include required files.
 */
require_once ARTP_PLUGIN_DIR . 'includes/class-page-creator.php';
require_once ARTP_PLUGIN_DIR . 'includes/class-maintenance-mode.php';
require_once ARTP_PLUGIN_DIR . 'includes/class-admin-notice.php';

/**
 * Activation hook - Create the temporary page.
 */
function artp_activate_plugin() {
	ARTP_Page_Creator::create_temporary_page();
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'artp_activate_plugin' );

/**
 * Deactivation hook - Set page to draft.
 */
function artp_deactivate_plugin() {
	ARTP_Page_Creator::deactivate_temporary_page();
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'artp_deactivate_plugin' );

/**
 * Initialize the temporary page functionality.
 */
function artp_init() {
	ARTP_Maintenance_Mode::init();
	ARTP_Admin_Notice::init();
}
add_action( 'plugins_loaded', 'artp_init' );

/**
 * Add plugin action links.
 *
 * @param array $links Plugin action links.
 * @return array Modified plugin action links.
 */
function artp_add_action_links( $links ) {
	$page = ARTP_Page_Creator::get_temporary_page();
	
	if ( $page ) {
		$edit_link = sprintf(
			'<a href="%s">%s</a>',
			esc_url( get_edit_post_link( $page->ID ) ),
			esc_html__( 'Edit Page', 'almost-ready-temporary-page' )
		);
		
		// Add the link at the beginning of the array.
		array_unshift( $links, $edit_link );
	}
	
	return $links;
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'artp_add_action_links' );
