<?php
/**
 * Style Manager Class
 *
 * Manages visual style variations for the temporary page.
 *
 * Styles are registered as block style variations on core/group using
 * register_block_style() with style_data (a theme.json-shaped PHP array,
 * requires WP 6.6+). This makes them appear in the block editor's Styles
 * panel whenever the outer Group block is selected — one click to switch.
 *
 * All six variations share the same block content structure. Only the
 * is-style-artp-* className on the outer Group differs between them.
 *
 * @package AlmostReadyTemporaryPage
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class ARTP_Style_Manager
 */
class ARTP_Style_Manager {

	/**
	 * WordPress option key for the active style.
	 *
	 * @var string
	 */
	const OPTION_KEY = 'artp_active_style';

	/**
	 * Default style slug.
	 *
	 * @var string
	 */
	const DEFAULT_STYLE = 'default';

	/**
	 * Hook block style registration on init.
	 */
	public static function init() {
		add_action( 'init', array( __CLASS__, 'register_block_styles' ) );
	}

	/**
	 * Get all registered style variations.
	 *
	 * @return array Keyed by slug. Each entry has label, description, preview_gradient, text_preview.
	 */
	public static function get_styles() {
		return array(
			'default' => array(
				'label'            => __( 'Purple Gradient', 'almost-ready-temporary-page' ),
				'description'      => __( 'Bold purple gradient with centered content. The original style.', 'almost-ready-temporary-page' ),
				'preview_gradient' => 'linear-gradient(135deg, rgb(74,29,150) 0%, rgb(139,92,246) 100%)',
				'text_preview'     => '#ffffff',
			),
			'dark'    => array(
				'label'            => __( 'Dark Mode', 'almost-ready-temporary-page' ),
				'description'      => __( 'Sleek dark charcoal-to-navy gradient. Minimal and sophisticated.', 'almost-ready-temporary-page' ),
				'preview_gradient' => 'linear-gradient(135deg, rgb(10,10,10) 0%, rgb(26,26,46) 50%, rgb(22,33,62) 100%)',
				'text_preview'     => '#ffffff',
			),
			'minimal' => array(
				'label'            => __( 'Minimal', 'almost-ready-temporary-page' ),
				'description'      => __( 'Clean white background with bold uppercase typography. No distractions.', 'almost-ready-temporary-page' ),
				'preview_gradient' => '#ffffff',
				'text_preview'     => '#111111',
			),
			'warm'    => array(
				'label'            => __( 'Warm Sunset', 'almost-ready-temporary-page' ),
				'description'      => __( 'Vibrant orange-to-pink gradient. Energetic and welcoming.', 'almost-ready-temporary-page' ),
				'preview_gradient' => 'linear-gradient(135deg, rgb(249,115,22) 0%, rgb(236,72,153) 100%)',
				'text_preview'     => '#ffffff',
			),
			'nature'  => array(
				'label'            => __( 'Nature Photo', 'almost-ready-temporary-page' ),
				'description'      => __( 'A landscape photo from the WordPress Photo Directory (CC0) as a full-screen background.', 'almost-ready-temporary-page' ),
				'preview_gradient' => 'linear-gradient(135deg, rgb(30,80,40) 0%, rgb(180,130,60) 100%)',
				'preview_bg'       => 'linear-gradient(rgba(0,0,0,0.55),rgba(0,0,0,0.55)),url("https://pd.w.org/2026/04/68669cd2a70648216.07059024-2048x1536.jpg") center/cover no-repeat',
				'text_preview'     => '#ffffff',
			),
			'forest'  => array(
				'label'            => __( 'Forest', 'almost-ready-temporary-page' ),
				'description'      => __( 'Deep emerald green gradient. Calm, grounded, and fresh.', 'almost-ready-temporary-page' ),
				'preview_gradient' => 'linear-gradient(135deg, rgb(6,78,59) 0%, rgb(4,120,87) 50%, rgb(16,185,129) 100%)',
				'text_preview'     => '#ffffff',
			),
		);
	}

	/**
	 * Get the active style slug.
	 *
	 * @return string
	 */
	public static function get_active_style() {
		return get_option( self::OPTION_KEY, self::DEFAULT_STYLE );
	}

	/**
	 * Set the active style slug.
	 *
	 * @param string $slug Style slug.
	 * @return bool Whether the option was updated.
	 */
	public static function set_active_style( $slug ) {
		if ( ! array_key_exists( $slug, self::get_styles() ) ) {
			return false;
		}
		return update_option( self::OPTION_KEY, $slug );
	}

	/**
	 * Get the block content for a given style slug.
	 *
	 * All styles share the same Group block structure. The only difference
	 * is the is-style-artp-* className on the outer Group block, which
	 * triggers the CSS registered via register_block_style().
	 *
	 * @param string $slug Style slug.
	 * @return string Block markup.
	 */
	public static function get_content_for_style( $slug ) {
		if ( ! array_key_exists( $slug, self::get_styles() ) ) {
			$slug = self::DEFAULT_STYLE;
		}
		return self::get_page_content( $slug );
	}

	/**
	 * Register all block style variations for core/group and enqueue
	 * the shared layout stylesheet.
	 *
	 * Hooked on init. Requires WordPress 6.6+ for style_data support.
	 */
	public static function register_block_styles() {
		// Shared layout CSS (vertical flex centering + nature photo background).
		// Loaded whenever a core/group block appears on the page.
		wp_enqueue_block_style(
			'core/group',
			array(
				'handle' => 'artp-block-styles',
				'src'    => ARTP_PLUGIN_URL . 'assets/css/artp-block-styles.css',
				'ver'    => ARTP_VERSION,
			)
		);

		foreach ( self::get_styles() as $slug => $style ) {
			register_block_style(
				'core/group',
				array(
					'name'       => 'artp-' . $slug,
					'label'      => $style['label'],
					'style_data' => self::get_style_data( $slug ),
				)
			);
		}
	}

	/**
	 * Return the theme.json-shaped style_data array for a given slug.
	 *
	 * color.gradient sets the CSS background property (gradient).
	 * color.background sets background-color (solid).
	 * color.text sets color, which cascades to all child blocks and
	 * SVG icons (via fill: currentColor) without needing per-element overrides.
	 *
	 * The nature style only sets color.text here; its background image
	 * and overlay are handled by assets/css/artp-block-styles.css.
	 *
	 * @param string $slug Style slug.
	 * @return array
	 */
	private static function get_style_data( $slug ) {
		switch ( $slug ) {
			case 'dark':
				return array(
					'color' => array(
						'gradient' => 'linear-gradient(135deg,rgb(10,10,10) 0%,rgb(26,26,46) 50%,rgb(22,33,62) 100%)',
						'text'     => '#a0aec0',
					),
				);

			case 'minimal':
				return array(
					'color' => array(
						'background' => '#ffffff',
						'text'       => '#111111',
					),
				);

			case 'warm':
				return array(
					'color' => array(
						'gradient' => 'linear-gradient(135deg,rgb(249,115,22) 0%,rgb(236,72,153) 100%)',
						'text'     => '#ffffff',
					),
				);

			case 'nature':
				// Background image + overlay are in artp-block-styles.css.
				return array(
					'color' => array(
						'text' => '#ffffff',
					),
				);

			case 'forest':
				return array(
					'color' => array(
						'gradient' => 'linear-gradient(135deg,rgb(6,78,59) 0%,rgb(4,120,87) 50%,rgb(16,185,129) 100%)',
						'text'     => '#ffffff',
					),
				);

			default: // 'default'
				return array(
					'color' => array(
						'gradient' => 'linear-gradient(135deg,rgb(74,29,150) 0%,rgb(139,92,246) 100%)',
						'text'     => '#ffffff',
					),
				);
		}
	}

	/**
	 * Build the block markup for the temporary page.
	 *
	 * Uses a core/group block as the full-page container instead of
	 * core/cover. The Group block owns layout (full-height, flex-center)
	 * while all visual styling (background, colors) is driven by the
	 * is-style-artp-* CSS class registered via register_block_style().
	 *
	 * Child blocks carry no inline color attributes so that color.text
	 * set in style_data cascades naturally through CSS inheritance.
	 *
	 * @param string $slug Style slug.
	 * @return string Serialized block markup.
	 */
	private static function get_page_content( $slug ) {
		$class = 'is-style-artp-' . $slug;

		return '<!-- wp:group {"align":"full","className":"' . esc_attr( $class ) . '","style":{"dimensions":{"minHeight":"100vh"},"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"center","verticalAlignment":"center"}} -->
<div class="wp-block-group alignfull ' . esc_attr( $class ) . ' is-layout-flex wp-block-group-is-layout-flex" style="min-height:100vh;padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50)"><!-- wp:group {"layout":{"type":"constrained","contentSize":"600px"}} -->
<div class="wp-block-group is-layout-constrained wp-block-group-is-layout-constrained"><!-- wp:heading {"level":1,"style":{"typography":{"textAlign":"center"}},"fontSize":"x-large"} -->
<h1 class="wp-block-heading has-text-align-center has-x-large-font-size"><strong>✨ Almost Ready!</strong></h1>
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
<!-- /wp:group --></div>
<!-- /wp:group -->';
	}
}
