<?php
/**
 * Page Creator Class
 *
 * Handles creation and management of the temporary page.
 *
 * @package AlmostReadyTemporaryPage
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class ARTP_Page_Creator
 */
class ARTP_Page_Creator {

	/**
	 * The page slug for the temporary page.
	 *
	 * @var string
	 */
	const PAGE_SLUG = 'almost-ready-temporary';

	/**
	 * Create the temporary page on plugin activation.
	 */
	public static function create_temporary_page() {
		// Check if page already exists.
		$existing_page = get_page_by_path( self::PAGE_SLUG );

		if ( $existing_page ) {
			// If it exists but is in trash or draft, publish it.
			if ( 'trash' === $existing_page->post_status || 'draft' === $existing_page->post_status ) {
				wp_update_post(
					array(
						'ID'          => $existing_page->ID,
						'post_status' => 'publish',
					)
				);
			}
			return $existing_page->ID;
		}

		// Get default content.
		$content = self::get_default_content();

		// Create the page.
		$page_id = wp_insert_post(
			array(
				'post_title'   => __( 'Almost Ready', 'almost-ready-temporary-page' ),
				'post_name'    => self::PAGE_SLUG,
				'post_content' => $content,
				'post_status'  => 'publish',
				'post_type'    => 'page',
				'post_author'  => get_current_user_id(),
				'meta_input'   => array(
					'_artp_temporary_page'  => '1',
					'_wp_page_template'     => 'blank',
				),
			)
		);

		return $page_id;
	}

	/**
	 * Set the temporary page to draft on plugin deactivation.
	 */
	public static function deactivate_temporary_page() {
		$page = get_page_by_path( self::PAGE_SLUG );

		if ( $page && 'publish' === $page->post_status ) {
			wp_update_post(
				array(
					'ID'          => $page->ID,
					'post_status' => 'draft',
				)
			);
		}
	}

	/**
	 * Get the default block content for the temporary page.
	 * Delegates to the active style in ARTP_Style_Manager.
	 *
	 * @return string Block content.
	 */
	private static function get_default_content() {
		return ARTP_Style_Manager::get_content_for_style( ARTP_Style_Manager::get_active_style() );
	}

	/**
	 * Apply a style to the temporary page by updating its content.
	 *
	 * @param string $style_slug Style slug.
	 * @return int|false Updated post ID on success, false on failure.
	 */
	public static function apply_style( $style_slug ) {
		$page = self::get_temporary_page();

		if ( ! $page ) {
			return false;
		}

		$content = ARTP_Style_Manager::get_content_for_style( $style_slug );

		$result = wp_update_post(
			array(
				'ID'           => $page->ID,
				'post_content' => $content,
			)
		);

		return $result ? $result : false;
	}

	/**
	 * Get the temporary page.
	 *
	 * @return WP_Post|null The temporary page post object or null.
	 */
	public static function get_temporary_page() {
		return get_page_by_path( self::PAGE_SLUG );
	}
}
