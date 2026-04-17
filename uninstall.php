<?php
/**
 * Uninstall handler.
 *
 * Runs when the plugin is deleted from the WordPress admin. Only removes
 * plugin data if the user has opted in via the settings page. The temporary
 * page itself is never deleted.
 *
 * @package AlmostReadyTemporaryPage
 */

// If this file is called directly, abort.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	die;
}

if ( get_option( 'artp_delete_on_uninstall' ) ) {
	delete_option( 'artp_page_id' );
	delete_option( 'artp_delete_on_uninstall' );
}
