<?php
/**
 * Pattern Manager Class
 *
 * Registers each layout as a full-page block pattern under the "Almost Ready"
 * category so users can browse and apply them from the block editor's
 * Inserter → Patterns panel.
 *
 * Five distinct patterns cover the most common use cases:
 *   - Coming Soon      (new launch, energetic)
 *   - Under Construction (rebuild in progress, reassuring)
 *   - Scheduled Maintenance (planned downtime, professional)
 *   - We'll Be Back    (unplanned outage, calm/empathetic)
 *   - Holding Page     (new domain, neutral legitimacy)
 *
 * @package AlmostReadyTemporaryPage
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class ARTP_Pattern_Manager
 */
class ARTP_Pattern_Manager {

	/**
	 * Pattern category slug.
	 *
	 * @var string
	 */
	const CATEGORY_SLUG = 'almost-ready-temporary-page';

	/**
	 * Hook registrations.
	 */
	public static function init() {
		add_action( 'init', array( __CLASS__, 'register_category' ) );
		add_action( 'init', array( __CLASS__, 'register_patterns' ) );
	}

	/**
	 * Register the "Almost Ready" pattern category.
	 */
	public static function register_category() {
		register_block_pattern_category(
			self::CATEGORY_SLUG,
			array(
				'label'       => __( 'Almost Ready', 'almost-ready-temporary-page' ),
				'description' => __( 'Full-page layouts for your temporary page.', 'almost-ready-temporary-page' ),
			)
		);
	}

	/**
	 * Register one block pattern per use case.
	 *
	 * Also enqueues the shared layout stylesheet scoped to core/group blocks.
	 */
	public static function register_patterns() {
		wp_enqueue_block_style(
			'core/group',
			array(
				'handle' => 'artp-block-styles',
				'src'    => ARTP_PLUGIN_URL . 'assets/css/artp-block-styles.css',
				'ver'    => ARTP_VERSION,
			)
		);

		foreach ( self::get_patterns() as $slug => $pattern ) {
			register_block_pattern(
				'almost-ready-temporary-page/' . $slug,
				array(
					'title'       => $pattern['title'],
					'description' => $pattern['description'],
					'categories'  => array( self::CATEGORY_SLUG ),
					'content'     => $pattern['content'],
				)
			);
		}
	}

	/**
	 * Get the default block content for the temporary page.
	 *
	 * @return string Block markup.
	 */
	public static function get_default_content() {
		$patterns = self::get_patterns();
		return $patterns['coming-soon']['content'];
	}

	/**
	 * Return all pattern definitions.
	 *
	 * Each pattern is a self-contained full-page block layout. Colors are
	 * set directly as inline styles so patterns render correctly without
	 * depending on block style variations or any external CSS other than
	 * the shared .artp-page flex rule.
	 *
	 * Contrast ratios (WCAG AA ≥ 4.5:1 for normal text):
	 *   coming-soon:   white (#fff) on #4f46e5 — 4.56:1 ✓
	 *   construction:  #1a1a1a on #f5f0e8      — 14.2:1 ✓
	 *   maintenance:   white (#fff) on #0f4c81 — 7.8:1  ✓
	 *   well-be-back:  #1a1a1a on #fafafa      — 16.8:1 ✓
	 *   holding:       white (#fff) on #1e293b — 13.1:1 ✓
	 *
	 * @return array
	 */
	private static function get_patterns() {
		return array(

			// ----------------------------------------------------------------
			// 1. Coming Soon — new launch, energetic indigo gradient
			// ----------------------------------------------------------------
			'coming-soon' => array(
				'title'       => __( 'Coming Soon', 'almost-ready-temporary-page' ),
				'description' => __( 'Bold indigo gradient. Perfect for a new launch that is almost ready.', 'almost-ready-temporary-page' ),
				'content'     => '<!-- wp:group {"align":"full","className":"artp-page","style":{"dimensions":{"minHeight":"100vh"},"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}},"color":{"background":"#4f46e5","text":"#ffffff"},"elements":{"link":{"color":{"text":"#ffffff"}}}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"center","verticalAlignment":"center"}} -->
<div class="wp-block-group alignfull artp-page is-layout-flex wp-block-group-is-layout-flex has-background has-text-color" style="color:#ffffff;background-color:#4f46e5;min-height:100vh;padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50)"><!-- wp:group {"layout":{"type":"constrained","contentSize":"560px"}} -->
<div class="wp-block-group is-layout-constrained wp-block-group-is-layout-constrained"><!-- wp:heading {"level":1,"textAlign":"center","fontSize":"x-large"} -->
<h1 class="wp-block-heading has-text-align-center has-x-large-font-size"><strong>Coming Soon</strong></h1>
<!-- /wp:heading -->
<!-- wp:paragraph {"align":"center","fontSize":"medium"} -->
<p class="has-text-align-center has-medium-font-size">We are working on something exciting. Sign up to be the first to know when we launch.</p>
<!-- /wp:paragraph -->
<!-- wp:spacer {"height":"24px"} -->
<div style="height:24px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->
<!-- wp:social-links {"size":"has-normal-icon-size","align":"center","layout":{"type":"flex","justifyContent":"center"}} -->
<ul class="wp-block-social-links aligncenter has-normal-icon-size"><!-- wp:social-link {"url":"","service":"instagram"} /-->
<!-- wp:social-link {"service":"x"} /-->
<!-- wp:social-link {"service":"mail"} /--></ul>
<!-- /wp:social-links --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->',
			),

			// ----------------------------------------------------------------
			// 2. Under Construction — rebuild, warm cream background
			// ----------------------------------------------------------------
			'under-construction' => array(
				'title'       => __( 'Under Construction', 'almost-ready-temporary-page' ),
				'description' => __( 'Warm cream background with dark text. Reassuring tone for a site being rebuilt.', 'almost-ready-temporary-page' ),
				'content'     => '<!-- wp:group {"align":"full","className":"artp-page","style":{"dimensions":{"minHeight":"100vh"},"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}},"color":{"background":"#f5f0e8","text":"#1a1a1a"},"elements":{"link":{"color":{"text":"#1a1a1a"}}}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"center","verticalAlignment":"center"}} -->
<div class="wp-block-group alignfull artp-page is-layout-flex wp-block-group-is-layout-flex has-background has-text-color" style="color:#1a1a1a;background-color:#f5f0e8;min-height:100vh;padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50)"><!-- wp:group {"layout":{"type":"constrained","contentSize":"560px"}} -->
<div class="wp-block-group is-layout-constrained wp-block-group-is-layout-constrained"><!-- wp:heading {"level":1,"textAlign":"center","fontSize":"x-large"} -->
<h1 class="wp-block-heading has-text-align-center has-x-large-font-size"><strong>Under Construction</strong></h1>
<!-- /wp:heading -->
<!-- wp:paragraph {"align":"center","fontSize":"medium"} -->
<p class="has-text-align-center has-medium-font-size">We are giving this place a makeover. Hang tight — it will be worth the wait.</p>
<!-- /wp:paragraph -->
<!-- wp:spacer {"height":"24px"} -->
<div style="height:24px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->
<!-- wp:paragraph {"align":"center","fontSize":"small"} -->
<p class="has-text-align-center has-small-font-size">Questions? Reach out at <a href="mailto:hello@example.com">hello@example.com</a></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->',
			),

			// ----------------------------------------------------------------
			// 3. Scheduled Maintenance — planned downtime, deep navy
			// ----------------------------------------------------------------
			'maintenance' => array(
				'title'       => __( 'Scheduled Maintenance', 'almost-ready-temporary-page' ),
				'description' => __( 'Professional deep navy. Conveys planned, controlled downtime to active users.', 'almost-ready-temporary-page' ),
				'content'     => '<!-- wp:group {"align":"full","className":"artp-page","style":{"dimensions":{"minHeight":"100vh"},"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}},"color":{"background":"#0f4c81","text":"#ffffff"},"elements":{"link":{"color":{"text":"#ffffff"}}}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"center","verticalAlignment":"center"}} -->
<div class="wp-block-group alignfull artp-page is-layout-flex wp-block-group-is-layout-flex has-background has-text-color" style="color:#ffffff;background-color:#0f4c81;min-height:100vh;padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50)"><!-- wp:group {"layout":{"type":"constrained","contentSize":"560px"}} -->
<div class="wp-block-group is-layout-constrained wp-block-group-is-layout-constrained"><!-- wp:heading {"level":1,"textAlign":"center","fontSize":"x-large"} -->
<h1 class="wp-block-heading has-text-align-center has-x-large-font-size"><strong>Scheduled Maintenance</strong></h1>
<!-- /wp:heading -->
<!-- wp:paragraph {"align":"center","fontSize":"medium"} -->
<p class="has-text-align-center has-medium-font-size">We are performing planned maintenance to improve your experience. We will be back shortly.</p>
<!-- /wp:paragraph -->
<!-- wp:spacer {"height":"24px"} -->
<div style="height:24px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->
<!-- wp:paragraph {"align":"center","fontSize":"small"} -->
<p class="has-text-align-center has-small-font-size">Thank you for your patience.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->',
			),

			// ----------------------------------------------------------------
			// 4. We'll Be Back — unplanned outage, clean white, calm
			// ----------------------------------------------------------------
			'well-be-back' => array(
				'title'       => __( "We'll Be Back", 'almost-ready-temporary-page' ),
				'description' => __( 'Clean white with dark text. Calm and empathetic for unexpected downtime.', 'almost-ready-temporary-page' ),
				'content'     => '<!-- wp:group {"align":"full","className":"artp-page","style":{"dimensions":{"minHeight":"100vh"},"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}},"color":{"background":"#fafafa","text":"#1a1a1a"},"elements":{"link":{"color":{"text":"#1a1a1a"}}}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"center","verticalAlignment":"center"}} -->
<div class="wp-block-group alignfull artp-page is-layout-flex wp-block-group-is-layout-flex has-background has-text-color" style="color:#1a1a1a;background-color:#fafafa;min-height:100vh;padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50)"><!-- wp:group {"layout":{"type":"constrained","contentSize":"560px"}} -->
<div class="wp-block-group is-layout-constrained wp-block-group-is-layout-constrained"><!-- wp:heading {"level":1,"textAlign":"center","fontSize":"x-large"} -->
<h1 class="wp-block-heading has-text-align-center has-x-large-font-size"><strong>We\'ll Be Back Soon</strong></h1>
<!-- /wp:heading -->
<!-- wp:paragraph {"align":"center","fontSize":"medium"} -->
<p class="has-text-align-center has-medium-font-size">We are temporarily offline but working hard to resolve the issue. We appreciate your patience.</p>
<!-- /wp:paragraph -->
<!-- wp:spacer {"height":"24px"} -->
<div style="height:24px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->
<!-- wp:paragraph {"align":"center","fontSize":"small"} -->
<p class="has-text-align-center has-small-font-size">Need urgent help? Contact us at <a href="mailto:hello@example.com">hello@example.com</a></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->',
			),

			// ----------------------------------------------------------------
			// 5. Holding Page — new domain, dark slate, neutral legitimacy
			// ----------------------------------------------------------------
			'holding' => array(
				'title'       => __( 'Holding Page', 'almost-ready-temporary-page' ),
				'description' => __( 'Dark slate background. Neutral and professional for a newly registered domain.', 'almost-ready-temporary-page' ),
				'content'     => '<!-- wp:group {"align":"full","className":"artp-page","style":{"dimensions":{"minHeight":"100vh"},"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}},"color":{"background":"#1e293b","text":"#ffffff"},"elements":{"link":{"color":{"text":"#ffffff"}}}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"center","verticalAlignment":"center"}} -->
<div class="wp-block-group alignfull artp-page is-layout-flex wp-block-group-is-layout-flex has-background has-text-color" style="color:#ffffff;background-color:#1e293b;min-height:100vh;padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50)"><!-- wp:group {"layout":{"type":"constrained","contentSize":"560px"}} -->
<div class="wp-block-group is-layout-constrained wp-block-group-is-layout-constrained"><!-- wp:heading {"level":1,"textAlign":"center","fontSize":"x-large"} -->
<h1 class="wp-block-heading has-text-align-center has-x-large-font-size"><strong>Something Is Coming</strong></h1>
<!-- /wp:heading -->
<!-- wp:paragraph {"align":"center","fontSize":"medium"} -->
<p class="has-text-align-center has-medium-font-size">This domain is reserved. A new project is on its way — stay tuned.</p>
<!-- /wp:paragraph -->
<!-- wp:spacer {"height":"24px"} -->
<div style="height:24px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->
<!-- wp:social-links {"size":"has-normal-icon-size","align":"center","layout":{"type":"flex","justifyContent":"center"}} -->
<ul class="wp-block-social-links aligncenter has-normal-icon-size"><!-- wp:social-link {"url":"","service":"instagram"} /-->
<!-- wp:social-link {"service":"linkedin"} /-->
<!-- wp:social-link {"service":"mail"} /--></ul>
<!-- /wp:social-links --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->',
			),

		);
	}
}
