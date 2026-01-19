<?php
/**
 * Page Creator Class
 *
 * Handles creation and management of the maintenance page.
 *
 * @package UnderConstructionWithBlocks
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class UCWB_Page_Creator
 */
class UCWB_Page_Creator {

	/**
	 * The page slug for the maintenance page.
	 *
	 * @var string
	 */
	const PAGE_SLUG = 'under-construction-blocks';

	/**
	 * Create the maintenance page on plugin activation.
	 */
	public static function create_maintenance_page() {
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
				'post_title'   => __( 'Under Construction', 'under-construction-with-blocks' ),
				'post_name'    => self::PAGE_SLUG,
				'post_content' => $content,
				'post_status'  => 'publish',
				'post_type'    => 'page',
				'post_author'  => get_current_user_id(),
				'meta_input'   => array(
					'_ucwb_maintenance_page' => '1',
					'_wp_page_template'      => 'blank', // Use blank template
				),
			)
		);

		return $page_id;
	}

	/**
	 * Set the maintenance page to draft on plugin deactivation.
	 */
	public static function deactivate_maintenance_page() {
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
	 * Get the default block content for the maintenance page.
	 *
	 * @return string Block content.
	 */
	private static function get_default_content() {
		$content = '<!-- wp:cover {"url":"","isUserOverlayColor":true,"minHeight":100,"minHeightUnit":"vh","customGradient":"linear-gradient(135deg,rgb(18,143,216) 0%,rgb(38,166,131) 100%)","align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}}}} -->
<div class="wp-block-cover alignfull" style="padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50);min-height:100vh"><span aria-hidden="true" class="wp-block-cover__background has-background-dim-100 has-background-dim has-background-gradient" style="background:linear-gradient(135deg,rgb(18,143,216) 0%,rgb(38,166,131) 100%)"></span><div class="wp-block-cover__inner-container"><!-- wp:group {"layout":{"type":"constrained","contentSize":"600px"}} -->
<div class="wp-block-group"><!-- wp:heading {"textAlign":"center","level":1,"fontSize":"x-large"} -->
<h1 class="wp-block-heading has-text-align-center has-x-large-font-size"><strong>🚧 Under Construction</strong></h1>
<!-- /wp:heading -->
<!-- wp:paragraph {"align":"center","fontSize":"medium"} -->
<p class="has-text-align-center has-medium-font-size">We\'re working hard to bring you something amazing. Check back soon!</p>
<!-- /wp:paragraph -->
<!-- wp:spacer {"height":"40px"} -->
<div style="height:40px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->
<!-- wp:paragraph {"align":"center","fontSize":"small"} -->
<p class="has-text-align-center has-small-font-size">In the meantime, feel free to reach out to us if you have any questions.</p>
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

	/**
	 * Get the maintenance page.
	 *
	 * @return WP_Post|null The maintenance page post object or null.
	 */
	public static function get_maintenance_page() {
		return get_page_by_path( self::PAGE_SLUG );
	}
}
