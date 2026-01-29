<?php
/**
 * Maintenance Mode Class
 *
 * Handles displaying the temporary page to visitors.
 *
 * @package AlmostReadyTemporaryPage
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class ARTP_Maintenance_Mode
 */
class ARTP_Maintenance_Mode {

	/**
	 * Initialize the maintenance mode functionality.
	 */
	public static function init() {
		add_action( 'template_redirect', array( __CLASS__, 'show_temporary_page' ) );
		add_filter( 'wp_robots', array( __CLASS__, 'add_noindex_robots' ) );
		add_action( 'wp', array( __CLASS__, 'remove_header_footer' ) );
	}

	/**
	 * Show the temporary page to non-logged-in users.
	 */
	public static function show_temporary_page() {
		// Don't show to logged-in users.
		if ( is_user_logged_in() ) {
			return;
		}

		// Don't show on the temporary page itself to avoid loops.
		if ( is_page( ARTP_Page_Creator::PAGE_SLUG ) ) {
			return;
		}

		// Don't show in admin or login pages.
		if ( is_admin() || $GLOBALS['pagenow'] === 'wp-login.php' ) {
			return;
		}

		// Get the temporary page.
		$temporary_page = ARTP_Page_Creator::get_temporary_page();

		if ( ! $temporary_page || 'publish' !== $temporary_page->post_status ) {
			return;
		}

		// Redirect to the temporary page.
		wp_safe_redirect( get_permalink( $temporary_page->ID ), 302 );
		exit;
	}

	/**
	 * Remove header and footer from the temporary page.
	 */
	public static function remove_header_footer() {
		// Only apply to the temporary page.
		if ( ! is_page( ARTP_Page_Creator::PAGE_SLUG ) ) {
			return;
		}

		// Remove theme support for various features to prevent header/footer rendering.
		add_filter( 'show_admin_bar', '__return_false' );
		
		// Remove header and footer hooks for classic themes.
		add_filter( 'get_header', array( __CLASS__, 'blank_header' ) );
		add_filter( 'get_footer', array( __CLASS__, 'blank_footer' ) );
		
		// For block themes, remove template parts.
		add_filter( 'render_block', array( __CLASS__, 'remove_template_parts' ), 10, 2 );
		
		// Use a custom template.
		add_filter( 'template_include', array( __CLASS__, 'custom_template' ) );
	}

	/**
	 * Blank header function.
	 */
	public static function blank_header() {
		return true;
	}

	/**
	 * Blank footer function.
	 */
	public static function blank_footer() {
		return true;
	}

	/**
	 * Remove template parts (header/footer) from block themes.
	 *
	 * @param string $block_content The block content.
	 * @param array  $block The block array.
	 * @return string Modified block content.
	 */
	public static function remove_template_parts( $block_content, $block ) {
		// Remove header and footer template parts.
		if ( isset( $block['blockName'] ) && 'core/template-part' === $block['blockName'] ) {
			if ( isset( $block['attrs']['slug'] ) ) {
				$slug = $block['attrs']['slug'];
				// Remove header and footer template parts.
				if ( in_array( $slug, array( 'header', 'footer' ), true ) ) {
					return '';
				}
			}
		}
		return $block_content;
	}

	/**
	 * Use a custom blank template for the temporary page.
	 *
	 * @param string $template The path of the template to include.
	 * @return string Modified template path.
	 */
	public static function custom_template( $template ) {
		if ( is_page( ARTP_Page_Creator::PAGE_SLUG ) ) {
			// Create a custom template that only outputs the content.
			$custom_template = ARTP_PLUGIN_DIR . 'templates/temporary-page-template.php';
			
			if ( file_exists( $custom_template ) ) {
				return $custom_template;
			}
		}
		return $template;
	}

	/**
	 * Add noindex robots meta tag to temporary page.
	 *
	 * @param array $robots Current robots directives.
	 * @return array Modified robots directives.
	 */
	public static function add_noindex_robots( $robots ) {
		if ( is_page( ARTP_Page_Creator::PAGE_SLUG ) ) {
			$robots['noindex']  = true;
			$robots['nofollow'] = true;
		}

		return $robots;
	}
}
