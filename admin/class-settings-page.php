<?php
/**
 * Settings Page Class
 *
 * Registers and renders the style picker settings page.
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
	 * Render the settings page.
	 */
	public static function render_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$styles       = ARTP_Style_Manager::get_styles();
		$active_style = ARTP_Style_Manager::get_active_style();
		$page         = ARTP_Page_Creator::get_temporary_page();
		?>
		<div class="wrap artp-settings">
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

			<p class="description">
				<?php esc_html_e( 'Choose a visual style for your temporary page. Applying a style will replace the page content with the selected design. If you have customized the page, make sure to back up your changes first.', 'almost-ready-temporary-page' ); ?>
			</p>

			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
				<input type="hidden" name="action" value="artp_apply_style">
				<?php wp_nonce_field( 'artp_apply_style' ); ?>

				<div class="artp-style-grid">
					<?php foreach ( $styles as $slug => $style ) : ?>
						<?php $is_active = ( $slug === $active_style ); ?>
						<label class="artp-style-card<?php echo $is_active ? ' artp-style-card--active' : ''; ?>">
							<input
								type="radio"
								name="artp_style"
								value="<?php echo esc_attr( $slug ); ?>"
								<?php checked( $is_active ); ?>
							>
							<span
								class="artp-style-preview"
								style="background: <?php echo esc_attr( $style['preview_gradient'] ); ?>; color: <?php echo esc_attr( $style['text_preview'] ); ?>;"
								aria-hidden="true"
							>
								<span class="artp-style-preview-bar"></span>
								<span class="artp-style-preview-bar artp-style-preview-bar--short"></span>
								<span class="artp-style-preview-dots">
									<span></span><span></span><span></span>
								</span>
							</span>
							<span class="artp-style-info">
								<strong class="artp-style-label"><?php echo esc_html( $style['label'] ); ?></strong>
								<?php if ( $is_active ) : ?>
									<span class="artp-style-badge"><?php esc_html_e( 'Active', 'almost-ready-temporary-page' ); ?></span>
								<?php endif; ?>
								<span class="artp-style-description"><?php echo esc_html( $style['description'] ); ?></span>
							</span>
						</label>
					<?php endforeach; ?>
				</div>

				<?php submit_button( __( 'Apply Selected Style', 'almost-ready-temporary-page' ), 'primary', 'submit', true ); ?>
			</form>
		</div>

		<style>
		.artp-settings .description {
			margin: 12px 0 24px;
			max-width: 680px;
		}
		.artp-style-grid {
			display: grid;
			grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
			gap: 16px;
			max-width: 900px;
			margin-bottom: 24px;
		}
		.artp-style-card {
			display: flex;
			flex-direction: column;
			border: 2px solid #c3c4c7;
			border-radius: 8px;
			cursor: pointer;
			overflow: hidden;
			transition: border-color 0.15s, box-shadow 0.15s;
			background: #fff;
		}
		.artp-style-card:hover {
			border-color: #2271b1;
			box-shadow: 0 0 0 1px #2271b1;
		}
		.artp-style-card--active {
			border-color: #2271b1;
			box-shadow: 0 0 0 1px #2271b1;
		}
		.artp-style-card input[type="radio"] {
			position: absolute;
			opacity: 0;
			pointer-events: none;
		}
		.artp-style-preview {
			display: flex;
			flex-direction: column;
			align-items: center;
			justify-content: center;
			height: 120px;
			gap: 8px;
			padding: 16px;
		}
		.artp-style-preview-bar {
			display: block;
			height: 10px;
			width: 60%;
			border-radius: 4px;
			background: currentColor;
			opacity: 0.85;
		}
		.artp-style-preview-bar--short {
			width: 40%;
			height: 7px;
			opacity: 0.55;
		}
		.artp-style-preview-dots {
			display: flex;
			gap: 6px;
			margin-top: 4px;
		}
		.artp-style-preview-dots span {
			display: block;
			width: 10px;
			height: 10px;
			border-radius: 50%;
			background: currentColor;
			opacity: 0.6;
		}
		.artp-style-info {
			display: flex;
			flex-direction: column;
			gap: 4px;
			padding: 12px 14px;
			border-top: 1px solid #f0f0f1;
		}
		.artp-style-label {
			font-size: 13px;
			color: #1d2327;
		}
		.artp-style-badge {
			display: inline-block;
			background: #2271b1;
			color: #fff;
			font-size: 10px;
			font-weight: 600;
			text-transform: uppercase;
			letter-spacing: 0.05em;
			padding: 2px 6px;
			border-radius: 3px;
			width: fit-content;
		}
		.artp-style-description {
			font-size: 12px;
			color: #646970;
			line-height: 1.4;
		}
		</style>
		<?php
	}
}
