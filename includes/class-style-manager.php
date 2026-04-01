<?php
/**
 * Style Manager Class
 *
 * Manages visual style variations for the temporary page.
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
	 * Get all registered style variations.
	 *
	 * @return array Keyed by slug, each entry has label, description, and preview_gradient.
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
				'description'      => __( 'A golden sunset photo from the WordPress Photo Directory (CC0) as a full-screen background.', 'almost-ready-temporary-page' ),
				'preview_gradient' => 'linear-gradient(135deg, rgb(30,80,40) 0%, rgb(180,130,60) 100%)',
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
		$styles = self::get_styles();
		if ( ! array_key_exists( $slug, $styles ) ) {
			return false;
		}
		return update_option( self::OPTION_KEY, $slug );
	}

	/**
	 * Get the block content for a given style slug.
	 *
	 * @param string $slug Style slug.
	 * @return string Block content.
	 */
	public static function get_content_for_style( $slug ) {
		switch ( $slug ) {
			case 'dark':
				return self::get_dark_content();
			case 'minimal':
				return self::get_minimal_content();
			case 'warm':
				return self::get_warm_content();
			case 'nature':
				return self::get_nature_content();
			case 'forest':
				return self::get_forest_content();
			default:
				return self::get_default_content();
		}
	}

	/**
	 * Style: Purple Gradient (default).
	 * Bold purple gradient, white centered text.
	 *
	 * @return string
	 */
	public static function get_default_content() {
		$content = '<!-- wp:cover {"isUserOverlayColor":true,"minHeight":100,"minHeightUnit":"vh","customGradient":"linear-gradient(135deg,rgb(74,29,150) 0%,rgb(139,92,246) 100%)","align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}}}} -->
<div class="wp-block-cover alignfull" style="padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50);min-height:100vh"><span aria-hidden="true" class="wp-block-cover__background has-background-dim-100 has-background-dim has-background-gradient" style="background:linear-gradient(135deg,rgb(74,29,150) 0%,rgb(139,92,246) 100%)"></span><div class="wp-block-cover__inner-container"><!-- wp:group {"layout":{"type":"constrained","contentSize":"600px"}} -->
<div class="wp-block-group"><!-- wp:heading {"level":1,"style":{"typography":{"textAlign":"center"},"elements":{"link":{"color":{"text":"var:preset|color|white"}}}},"textColor":"white","fontSize":"x-large"} -->
<h1 class="wp-block-heading has-text-align-center has-white-color has-text-color has-link-color has-x-large-font-size"><strong>✨ Almost Ready!</strong></h1>
<!-- /wp:heading -->
<!-- wp:paragraph {"style":{"typography":{"textAlign":"center"}},"textColor":"white","fontSize":"medium"} -->
<p class="has-text-align-center has-white-color has-text-color has-medium-font-size">We\'re putting the finishing touches on something great. Check back soon!</p>
<!-- /wp:paragraph -->
<!-- wp:spacer {"height":"30px"} -->
<div style="height:30px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->
<!-- wp:paragraph {"style":{"typography":{"textAlign":"center"}},"textColor":"white","fontSize":"small"} -->
<p class="has-text-align-center has-white-color has-text-color has-small-font-size">In the meantime, feel free to reach out if you have any questions.</p>
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
	 * Style: Dark Mode.
	 * Near-black to deep navy gradient, white text.
	 *
	 * @return string
	 */
	private static function get_dark_content() {
		$content = '<!-- wp:cover {"isUserOverlayColor":true,"minHeight":100,"minHeightUnit":"vh","customGradient":"linear-gradient(135deg,rgb(10,10,10) 0%,rgb(26,26,46) 50%,rgb(22,33,62) 100%)","align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}}}} -->
<div class="wp-block-cover alignfull" style="padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50);min-height:100vh"><span aria-hidden="true" class="wp-block-cover__background has-background-dim-100 has-background-dim has-background-gradient" style="background:linear-gradient(135deg,rgb(10,10,10) 0%,rgb(26,26,46) 50%,rgb(22,33,62) 100%)"></span><div class="wp-block-cover__inner-container"><!-- wp:group {"layout":{"type":"constrained","contentSize":"600px"}} -->
<div class="wp-block-group"><!-- wp:heading {"level":1,"style":{"typography":{"textAlign":"center"},"elements":{"link":{"color":{"text":"var:preset|color|white"}}}},"textColor":"white","fontSize":"x-large"} -->
<h1 class="wp-block-heading has-text-align-center has-white-color has-text-color has-link-color has-x-large-font-size"><strong>✨ Almost Ready!</strong></h1>
<!-- /wp:heading -->
<!-- wp:paragraph {"style":{"typography":{"textAlign":"center"},"color":{"text":"#a0aec0"}},"fontSize":"medium"} -->
<p class="has-text-align-center has-text-color has-medium-font-size" style="color:#a0aec0">We\'re putting the finishing touches on something great. Check back soon!</p>
<!-- /wp:paragraph -->
<!-- wp:spacer {"height":"30px"} -->
<div style="height:30px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->
<!-- wp:paragraph {"style":{"typography":{"textAlign":"center"},"color":{"text":"#718096"}},"fontSize":"small"} -->
<p class="has-text-align-center has-text-color has-small-font-size" style="color:#718096">In the meantime, feel free to reach out if you have any questions.</p>
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
	 * Style: Minimal.
	 * Pure white background, bold uppercase heading, no imagery.
	 *
	 * @return string
	 */
	private static function get_minimal_content() {
		$content = '<!-- wp:cover {"isUserOverlayColor":true,"customOverlayColor":"#ffffff","dimRatio":100,"minHeight":100,"minHeightUnit":"vh","align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}}}} -->
<div class="wp-block-cover alignfull" style="padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50);min-height:100vh"><span aria-hidden="true" class="wp-block-cover__background has-background-dim-100 has-background-dim" style="background-color:#ffffff"></span><div class="wp-block-cover__inner-container"><!-- wp:group {"layout":{"type":"constrained","contentSize":"560px"}} -->
<div class="wp-block-group"><!-- wp:heading {"level":1,"style":{"typography":{"textAlign":"center","letterSpacing":"0.1em","textTransform":"uppercase"},"color":{"text":"#111111"}},"fontSize":"x-large"} -->
<h1 class="wp-block-heading has-text-align-center has-text-color has-x-large-font-size" style="color:#111111;letter-spacing:0.1em;text-transform:uppercase">Almost Ready</h1>
<!-- /wp:heading -->
<!-- wp:spacer {"height":"16px"} -->
<div style="height:16px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->
<!-- wp:separator {"style":{"color":{"background":"#dddddd"}},"className":"is-style-wide"} -->
<hr class="wp-block-separator has-text-color has-alpha-channel-opacity has-background is-style-wide" style="background-color:#dddddd;color:#dddddd">
<!-- /wp:separator -->
<!-- wp:spacer {"height":"24px"} -->
<div style="height:24px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->
<!-- wp:paragraph {"style":{"typography":{"textAlign":"center"},"color":{"text":"#555555"}},"fontSize":"medium"} -->
<p class="has-text-align-center has-text-color has-medium-font-size" style="color:#555555">We\'re putting the finishing touches on something great. Check back soon!</p>
<!-- /wp:paragraph -->
<!-- wp:spacer {"height":"30px"} -->
<div style="height:30px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->
<!-- wp:paragraph {"style":{"typography":{"textAlign":"center"},"color":{"text":"#999999"}},"fontSize":"small"} -->
<p class="has-text-align-center has-text-color has-small-font-size" style="color:#999999">In the meantime, feel free to reach out if you have any questions.</p>
<!-- /wp:paragraph -->
<!-- wp:social-links {"size":"has-normal-icon-size","iconColor":"#333333","iconColorValue":"#333333","align":"center","layout":{"type":"flex","justifyContent":"center"}} -->
<ul class="wp-block-social-links aligncenter has-icon-color has-normal-icon-size"><!-- wp:social-link {"url":"","service":"instagram"} /-->
<!-- wp:social-link {"service":"linkedin"} /-->
<!-- wp:social-link {"service":"mail"} /--></ul>
<!-- /wp:social-links --></div>
<!-- /wp:group --></div></div>
<!-- /wp:cover -->';

		return $content;
	}

	/**
	 * Style: Warm Sunset.
	 * Orange-to-pink gradient, white text. Energetic.
	 *
	 * @return string
	 */
	private static function get_warm_content() {
		$content = '<!-- wp:cover {"isUserOverlayColor":true,"minHeight":100,"minHeightUnit":"vh","customGradient":"linear-gradient(135deg,rgb(249,115,22) 0%,rgb(236,72,153) 100%)","align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}}}} -->
<div class="wp-block-cover alignfull" style="padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50);min-height:100vh"><span aria-hidden="true" class="wp-block-cover__background has-background-dim-100 has-background-dim has-background-gradient" style="background:linear-gradient(135deg,rgb(249,115,22) 0%,rgb(236,72,153) 100%)"></span><div class="wp-block-cover__inner-container"><!-- wp:group {"layout":{"type":"constrained","contentSize":"600px"}} -->
<div class="wp-block-group"><!-- wp:heading {"level":1,"style":{"typography":{"textAlign":"center"},"elements":{"link":{"color":{"text":"var:preset|color|white"}}}},"textColor":"white","fontSize":"x-large"} -->
<h1 class="wp-block-heading has-text-align-center has-white-color has-text-color has-link-color has-x-large-font-size"><strong>✨ Almost Ready!</strong></h1>
<!-- /wp:heading -->
<!-- wp:paragraph {"style":{"typography":{"textAlign":"center"}},"textColor":"white","fontSize":"medium"} -->
<p class="has-text-align-center has-white-color has-text-color has-medium-font-size">We\'re putting the finishing touches on something great. Check back soon!</p>
<!-- /wp:paragraph -->
<!-- wp:spacer {"height":"30px"} -->
<div style="height:30px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->
<!-- wp:paragraph {"style":{"typography":{"textAlign":"center"}},"textColor":"white","fontSize":"small"} -->
<p class="has-text-align-center has-white-color has-text-color has-small-font-size">In the meantime, feel free to reach out if you have any questions.</p>
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
	 * Style: Nature Photo.
	 * Full-screen landscape photo from the WordPress Photo Directory (CC0) as background.
	 * Photo: Tropical beach by Faisal Ahammad — wordpress.org/photos/photo/68669cd2a7/
	 *
	 * @return string
	 */
	private static function get_nature_content() {
		// CC0 landscape photo from the WordPress Photo Directory (2048×1536).
		// Replace this URL with any other photo from wordpress.org/photos if preferred.
		$photo_url = 'https://pd.w.org/2026/04/68669cd2a70648216.07059024-2048x1536.jpg';
		$photo_alt = 'A tropical beach with dense green trees and bushes beside white sand. Large gray rocks sit on the left, and sunlight casts soft shadows under a bright blue sky.';

		$content = '<!-- wp:cover {"url":"' . esc_url( $photo_url ) . '","dimRatio":60,"customOverlayColor":"#000000","isUserOverlayColor":true,"minHeight":100,"minHeightUnit":"vh","align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}}}} -->
<div class="wp-block-cover alignfull" style="padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50);min-height:100vh"><span aria-hidden="true" class="wp-block-cover__background has-background-dim-60 has-background-dim" style="background-color:#000000"></span><img class="wp-block-cover__image-background" alt="' . esc_attr( $photo_alt ) . '" src="' . esc_url( $photo_url ) . '" data-object-fit="cover"><div class="wp-block-cover__inner-container"><!-- wp:group {"layout":{"type":"constrained","contentSize":"600px"}} -->
<div class="wp-block-group"><!-- wp:heading {"level":1,"style":{"typography":{"textAlign":"center"},"elements":{"link":{"color":{"text":"var:preset|color|white"}}}},"textColor":"white","fontSize":"x-large"} -->
<h1 class="wp-block-heading has-text-align-center has-white-color has-text-color has-link-color has-x-large-font-size"><strong>✨ Almost Ready!</strong></h1>
<!-- /wp:heading -->
<!-- wp:paragraph {"style":{"typography":{"textAlign":"center"}},"textColor":"white","fontSize":"medium"} -->
<p class="has-text-align-center has-white-color has-text-color has-medium-font-size">We\'re putting the finishing touches on something great. Check back soon!</p>
<!-- /wp:paragraph -->
<!-- wp:spacer {"height":"30px"} -->
<div style="height:30px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->
<!-- wp:paragraph {"style":{"typography":{"textAlign":"center"}},"textColor":"white","fontSize":"small"} -->
<p class="has-text-align-center has-white-color has-text-color has-small-font-size">In the meantime, feel free to reach out if you have any questions.</p>
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
	 * Style: Forest.
	 * Deep emerald green gradient, white text. Calm and grounded.
	 *
	 * @return string
	 */
	private static function get_forest_content() {
		$content = '<!-- wp:cover {"isUserOverlayColor":true,"minHeight":100,"minHeightUnit":"vh","customGradient":"linear-gradient(135deg,rgb(6,78,59) 0%,rgb(4,120,87) 50%,rgb(16,185,129) 100%)","align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}}}} -->
<div class="wp-block-cover alignfull" style="padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50);min-height:100vh"><span aria-hidden="true" class="wp-block-cover__background has-background-dim-100 has-background-dim has-background-gradient" style="background:linear-gradient(135deg,rgb(6,78,59) 0%,rgb(4,120,87) 50%,rgb(16,185,129) 100%)"></span><div class="wp-block-cover__inner-container"><!-- wp:group {"layout":{"type":"constrained","contentSize":"600px"}} -->
<div class="wp-block-group"><!-- wp:heading {"level":1,"style":{"typography":{"textAlign":"center"},"elements":{"link":{"color":{"text":"var:preset|color|white"}}}},"textColor":"white","fontSize":"x-large"} -->
<h1 class="wp-block-heading has-text-align-center has-white-color has-text-color has-link-color has-x-large-font-size"><strong>✨ Almost Ready!</strong></h1>
<!-- /wp:heading -->
<!-- wp:paragraph {"style":{"typography":{"textAlign":"center"}},"textColor":"white","fontSize":"medium"} -->
<p class="has-text-align-center has-white-color has-text-color has-medium-font-size">We\'re putting the finishing touches on something great. Check back soon!</p>
<!-- /wp:paragraph -->
<!-- wp:spacer {"height":"30px"} -->
<div style="height:30px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->
<!-- wp:paragraph {"style":{"typography":{"textAlign":"center"}},"textColor":"white","fontSize":"small"} -->
<p class="has-text-align-center has-white-color has-text-color has-small-font-size">In the meantime, feel free to reach out if you have any questions.</p>
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
