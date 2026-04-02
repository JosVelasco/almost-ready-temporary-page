<?php
/**
 * Style Preview Class
 *
 * Handles the ?artp_style_preview=<slug> endpoint used by the settings page
 * to render iframe thumbnails. Renders the actual saved page content with the
 * requested style class applied, using wp_head() so the active theme's
 * stylesheet and block styles are included automatically.
 *
 * Only accessible to users with manage_options capability.
 *
 * @package AlmostReadyTemporaryPage
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class ARTP_Style_Preview
 */
class ARTP_Style_Preview {

	/**
	 * Query parameter name.
	 *
	 * @var string
	 */
	const QUERY_ARG = 'artp_style_preview';

	/**
	 * Hook the preview renderer.
	 */
	public static function init() {
		add_action( 'template_redirect', array( __CLASS__, 'maybe_render_preview' ) );
	}

	/**
	 * If the preview query arg is present and the user has permission,
	 * render a minimal HTML page with the requested style applied and exit.
	 */
	public static function maybe_render_preview() {
		if ( ! isset( $_GET[ self::QUERY_ARG ] ) ) {
			return;
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have permission to view this preview.', 'almost-ready-temporary-page' ) );
		}

		$slug = sanitize_key( $_GET[ self::QUERY_ARG ] );

		// Use the actual saved page content so editor customisations are shown.
		// Swap the is-style-artp-* class to the requested slug.
		$page = ARTP_Page_Creator::get_temporary_page();
		if ( $page && ! empty( $page->post_content ) ) {
			$content = preg_replace(
				'/\bis-style-artp-[\w-]+\b/',
				'is-style-artp-' . $slug,
				$page->post_content
			);
		} else {
			$content = ARTP_Style_Manager::get_content_for_style( $slug );
		}

		// Suppress the admin bar inside the preview iframe.
		add_filter( 'show_admin_bar', '__return_false' );

		// Render: wp_head() loads the active theme stylesheet, block library
		// CSS, and theme.json-derived CSS. No theme header/footer is output so
		// the full-page block fills the iframe without layout interference.
		?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width,initial-scale=1">
<?php wp_head(); ?>
<style>html,body{margin:0;padding:0;overflow:hidden}</style>
</head>
<body>
<?php echo do_blocks( $content ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
<?php wp_footer(); ?>
</body>
</html>
		<?php
		exit;
	}

	/**
	 * Return the preview URL for a given style slug.
	 *
	 * @param string $slug Style slug.
	 * @return string URL.
	 */
	public static function preview_url( $slug ) {
		return add_query_arg( self::QUERY_ARG, $slug, home_url( '/' ) );
	}
}
