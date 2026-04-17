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
	 * The default page slug for the temporary page.
	 *
	 * @var string
	 */
	const PAGE_SLUG = 'almost-ready-temporary';

	/**
	 * The option name used to store the temporary page ID.
	 *
	 * @var string
	 */
	const OPTION_PAGE_ID = 'artp_page_id';

	/**
	 * Get the stored temporary page ID.
	 *
	 * @return int Page ID, or 0 if not set.
	 */
	public static function get_temporary_page_id() {
		return (int) get_option( self::OPTION_PAGE_ID, 0 );
	}

	/**
	 * Store the temporary page ID in the database.
	 *
	 * @param int $page_id The page ID to store.
	 */
	public static function set_temporary_page_id( $page_id ) {
		update_option( self::OPTION_PAGE_ID, (int) $page_id );
	}

	/**
	 * Get the temporary page post object.
	 *
	 * Looks up by stored ID first. Falls back to slug lookup for migration
	 * from older installs that did not store the ID.
	 *
	 * @return WP_Post|null The temporary page post object or null.
	 */
	public static function get_temporary_page() {
		$page_id = self::get_temporary_page_id();

		if ( $page_id ) {
			$page = get_post( $page_id );
			if ( $page && 'page' === $page->post_type && 'trash' !== $page->post_status ) {
				return $page;
			}
		}

		// Migration fallback: look up by the original slug.
		$page = get_page_by_path( self::PAGE_SLUG );
		if ( $page ) {
			update_option( self::OPTION_PAGE_ID, $page->ID );
		}

		return $page;
	}

	/**
	 * Create the temporary page on plugin activation.
	 */
	public static function create_temporary_page() {
		// Re-use an already tracked page if possible.
		$existing_id = self::get_temporary_page_id();
		if ( $existing_id ) {
			$page = get_post( $existing_id );
			if ( $page && 'page' === $page->post_type && 'trash' !== $page->post_status ) {
				if ( 'draft' === $page->post_status ) {
					wp_update_post(
						array(
							'ID'          => $existing_id,
							'post_status' => 'publish',
						)
					);
				}
				return $existing_id;
			}
		}

		// Migration: check by slug for installs that pre-date ID storage.
		$existing_page = get_page_by_path( self::PAGE_SLUG );
		if ( $existing_page ) {
			if ( in_array( $existing_page->post_status, array( 'draft', 'trash' ), true ) ) {
				wp_update_post(
					array(
						'ID'          => $existing_page->ID,
						'post_status' => 'publish',
					)
				);
			}
			update_option( self::OPTION_PAGE_ID, $existing_page->ID );
			return $existing_page->ID;
		}

		// Create a fresh page.
		$page_id = wp_insert_post(
			array(
				'post_title'   => __( 'Almost Ready', 'almost-ready-temporary-page' ),
				'post_name'    => self::PAGE_SLUG,
				'post_content' => self::get_default_content(),
				'post_status'  => 'publish',
				'post_type'    => 'page',
				'post_author'  => get_current_user_id(),
				'meta_input'   => array(
					'_artp_temporary_page' => '1',
					'_wp_page_template'    => 'blank',
				),
			)
		);

		if ( $page_id && ! is_wp_error( $page_id ) ) {
			update_option( self::OPTION_PAGE_ID, $page_id );
		}

		return $page_id;
	}

	/**
	 * Publish the temporary page (activate maintenance mode).
	 */
	public static function activate_temporary_page() {
		$page = self::get_temporary_page();
		if ( $page && 'publish' !== $page->post_status ) {
			wp_update_post(
				array(
					'ID'          => $page->ID,
					'post_status' => 'publish',
				)
			);
		}
	}

	/**
	 * Set the temporary page to draft (deactivate maintenance mode).
	 */
	public static function deactivate_temporary_page() {
		$page = self::get_temporary_page();
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
	 *
	 * @return string Block content.
	 */
	private static function get_default_content() {
		$content = '<!-- wp:cover {"isUserOverlayColor":true,"minHeight":100,"minHeightUnit":"vh","customGradient":"linear-gradient(135deg,rgb(74,29,150) 0%,rgb(139,92,246) 100%)","align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}}}} -->
<div class="wp-block-cover alignfull" style="padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50);min-height:100vh"><span aria-hidden="true" class="wp-block-cover__background has-background-dim-100 has-background-dim has-background-gradient" style="background:linear-gradient(135deg,rgb(74,29,150) 0%,rgb(139,92,246) 100%)"></span><div class="wp-block-cover__inner-container"><!-- wp:group {"layout":{"type":"constrained","contentSize":"600px"}} -->
<div class="wp-block-group"><!-- wp:heading {"level":1,"style":{"typography":{"textAlign":"center"},"elements":{"link":{"color":{"text":"var:preset|color|white"}}}},"textColor":"white","fontSize":"x-large"} -->
<h1 class="wp-block-heading has-text-align-center has-white-color has-text-color has-link-color has-x-large-font-size"><strong>✨ Almost Ready!</strong></h1>
<!-- /wp:heading -->
<!-- wp:paragraph {"style":{"typography":{"textAlign":"center"}},"fontSize":"medium"} -->
<p class="has-text-align-center has-medium-font-size">We\'re putting the finishing touches on something great. Check back soon!</p>
<!-- /wp:paragraph -->
<!-- wp:spacer {"height":"30px"} -->
<div style="height:30px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->
<!-- wp:paragraph {"style":{"typography":{"textAlign":"center"}},"fontSize":"small"} -->
<p class="has-text-align-center has-small-font-size">In the meantime, feel free to reach out if you have any questions.</p>
<!-- /wp:paragraph -->
<!-- wp:social-links {"size":"has-normal-icon-size","align":"center","layout":{"type":"flex","justifyContent":"center"}} -->
<ul class="wp-block-social-links aligncenter has-normal-icon-size"><!-- wp:social-link {"url":"","service":"instagram"} /-->
<!-- wp:social-link {"service":"linkedin"} /-->
<!-- wp:social-link {"service":"mail"} /--></ul>
<!-- /wp:social-links --></div>
<!-- /wp:group --></div></div>
<!-- /wp:cover -->';

		return $content;
	}
}
