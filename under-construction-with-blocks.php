<?php
/**
 * Plugin Name: Under Construction with Blocks
 * Plugin URI: https://github.com/JosVelasco/under-construction-with-blocks
 * Description: A simple maintenance mode plugin that displays a customizable "under construction" page to visitors while allowing logged-in users to work on the site. Customize by editing the "Under Construction" page.
 * Version: 1.0.0
 * Author: Jos Velasco
 * Author URI: https://josvelasco.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: under-construction-with-blocks
 * Domain Path: /languages
 *
 * @package UnderConstructionWithBlocks
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Current plugin version.
 */
define( 'UCWB_VERSION', '1.0.0' );
define( 'UCWB_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'UCWB_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/**
 * Include required files.
 */
require_once UCWB_PLUGIN_DIR . 'includes/class-page-creator.php';
require_once UCWB_PLUGIN_DIR . 'includes/class-maintenance-mode.php';
require_once UCWB_PLUGIN_DIR . 'includes/class-admin-notice.php';

/**
 * Activation hook - Create the maintenance page.
 */
function ucwb_activate_plugin() {
	UCWB_Page_Creator::create_maintenance_page();
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'ucwb_activate_plugin' );

/**
 * Deactivation hook - Set page to draft.
 */
function ucwb_deactivate_plugin() {
	UCWB_Page_Creator::deactivate_maintenance_page();
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'ucwb_deactivate_plugin' );

/**
 * Initialize the maintenance mode functionality.
 */
function ucwb_init() {
	UCWB_Maintenance_Mode::init();
	UCWB_Admin_Notice::init();
}
add_action( 'plugins_loaded', 'ucwb_init' );

/**
 * Add plugin action links.
 *
 * @param array $links Plugin action links.
 * @return array Modified plugin action links.
 */
function ucwb_add_action_links( $links ) {
	$page = UCWB_Page_Creator::get_maintenance_page();
	
	if ( $page ) {
		$edit_link = sprintf(
			'<a href="%s">%s</a>',
			get_edit_post_link( $page->ID ),
			__( 'Edit Page', 'under-construction-with-blocks' )
		);
		
		// Add the link at the beginning of the array.
		array_unshift( $links, $edit_link );
	}
	
	return $links;
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'ucwb_add_action_links' );
