<?php
/**
 * Settings Page Class
 *
 * Registers and renders the style picker settings page.
 *
 * The style grid is rendered by @wordpress/dataviews (JS) to match the same
 * UI as the Site Editor's Templates and Patterns screens. Style data and the
 * form action URL are passed from PHP via wp_localize_script.
 *
 * @package AlmostReadyTemporaryPage
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class ARTP_Settings_Page
 */
class ARTP_Settings_Page {

	/**
	 * Settings page slug.
	 *
	 * @var string
	 */
	const MENU_SLUG = 'artp-style-settings';

	/**
	 * Initialize the settings page.
	 */
	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'register_menu' ) );
		add_action( 'admin_post_artp_apply_style', array( __CLASS__, 'handle_apply_style' ) );
		// Priority 100 — run after Gutenberg registers its packages (priority 5).
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_assets' ), 100 );
	}

	/**
	 * Register the settings submenu under Settings.
	 */
	public static function register_menu() {
		add_options_page(
			__( 'Almost Ready — Style', 'almost-ready-temporary-page' ),
			__( 'Almost Ready', 'almost-ready-temporary-page' ),
			'manage_options',
			self::MENU_SLUG,
			array( __CLASS__, 'render_page' )
		);
	}

	/**
	 * Enqueue the DataViews style picker script on our settings page only.
	 *
	 * @param string $hook_suffix Current admin page hook suffix.
	 */
	public static function enqueue_assets( $hook_suffix ) {
		if ( 'settings_page_' . self::MENU_SLUG !== $hook_suffix ) {
			return;
		}

		// Use the generated asset file for correct dependency list and version hash.
		$asset_file = ARTP_PLUGIN_DIR . 'admin/js/build/style-picker.asset.php';
		$asset      = file_exists( $asset_file )
			? include $asset_file
			: array(
				'dependencies' => array( 'wp-element', 'wp-i18n', 'wp-dom-ready' ),
				'version'      => ARTP_VERSION,
			);

		wp_enqueue_script(
			'artp-style-picker',
			ARTP_PLUGIN_URL . 'admin/js/build/style-picker.js',
			$asset['dependencies'],
			$asset['version'],
			true
		);

		wp_enqueue_style(
			'artp-dataviews',
			ARTP_PLUGIN_URL . 'admin/js/build/dataviews.css',
			array( 'wp-components' ),
			ARTP_VERSION
		);

		$active_style = ARTP_Style_Manager::get_active_style();
		$styles_data  = array();

		foreach ( ARTP_Style_Manager::get_styles() as $slug => $style ) {
			$styles_data[] = array(
				'slug'        => $slug,
				'label'       => $style['label'],
				'description' => $style['description'],
				'previewUrl'  => ARTP_Style_Preview::preview_url( $slug ),
				'isActive'    => ( $slug === $active_style ),
			);
		}

		wp_localize_script(
			'artp-style-picker',
			'artpStylePicker',
			array(
				'styles'   => $styles_data,
				'applyUrl' => admin_url( 'admin-post.php' ),
				'nonce'    => wp_create_nonce( 'artp_apply_style' ),
			)
		);

		wp_enqueue_style( 'wp-components' );
	}

	/**
	 * Handle style application form submission.
	 */
	public static function handle_apply_style() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have permission to perform this action.', 'almost-ready-temporary-page' ) );
		}

		check_admin_referer( 'artp_apply_style' );

		$style = isset( $_POST['artp_style'] ) ? sanitize_key( $_POST['artp_style'] ) : '';

		if ( empty( $style ) ) {
			wp_safe_redirect(
				add_query_arg( 'artp_error', '1', admin_url( 'options-general.php?page=' . self::MENU_SLUG ) )
			);
			exit;
		}

		$saved = ARTP_Style_Manager::set_active_style( $style );

		if ( $saved ) {
			ARTP_Page_Creator::apply_style( $style );
			wp_safe_redirect(
				add_query_arg( 'artp_updated', '1', admin_url( 'options-general.php?page=' . self::MENU_SLUG ) )
			);
		} else {
			wp_safe_redirect(
				add_query_arg( 'artp_error', '1', admin_url( 'options-general.php?page=' . self::MENU_SLUG ) )
			);
		}

		exit;
	}

	/**
	 * Render the settings page shell.
	 *
	 * The style grid itself is rendered by artp-style-picker.js into
	 * #artp-style-picker-root via @wordpress/dataviews.
	 */
	public static function render_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$page = ARTP_Page_Creator::get_temporary_page();
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Almost Ready — Style', 'almost-ready-temporary-page' ); ?></h1>

			<?php if ( isset( $_GET['artp_updated'] ) ) : ?>
				<div class="notice notice-success is-dismissible">
					<p>
						<?php esc_html_e( 'Style applied! The temporary page content has been updated.', 'almost-ready-temporary-page' ); ?>
						<?php if ( $page ) : ?>
							<a href="<?php echo esc_url( get_permalink( $page->ID ) ); ?>" target="_blank">
								<?php esc_html_e( 'View page', 'almost-ready-temporary-page' ); ?>
							</a>
							&nbsp;|&nbsp;
							<a href="<?php echo esc_url( get_edit_post_link( $page->ID ) ); ?>">
								<?php esc_html_e( 'Edit page', 'almost-ready-temporary-page' ); ?>
							</a>
						<?php endif; ?>
					</p>
				</div>
			<?php endif; ?>

			<?php if ( isset( $_GET['artp_error'] ) ) : ?>
				<div class="notice notice-error is-dismissible">
					<p><?php esc_html_e( 'Could not apply style. Please try again.', 'almost-ready-temporary-page' ); ?></p>
				</div>
			<?php endif; ?>

			<div id="artp-style-picker-root"></div>
		</div>
		<?php
	}
}
