<?php
/**
 * Settings Page Class
 *
 * Registers and renders the plugin settings page.
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
	 * Initialize the settings page.
	 */
	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'add_settings_page' ) );
		add_action( 'admin_post_artp_save_settings', array( __CLASS__, 'save_settings' ) );
	}

	/**
	 * Register the settings page under Settings.
	 */
	public static function add_settings_page() {
		add_options_page(
			__( 'Almost Ready Settings', 'almost-ready-temporary-page' ),
			__( 'Almost Ready', 'almost-ready-temporary-page' ),
			'manage_options',
			'almost-ready-settings',
			array( __CLASS__, 'render_settings_page' )
		);
	}

	/**
	 * Get the URL to the settings page.
	 *
	 * @return string Settings page URL.
	 */
	public static function get_settings_url() {
		return admin_url( 'options-general.php?page=almost-ready-settings' );
	}

	/**
	 * Render the settings page.
	 */
	public static function render_settings_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$temporary_page   = ARTP_Page_Creator::get_temporary_page();
		$is_active        = $temporary_page && 'publish' === $temporary_page->post_status;
		$current_page_id  = ARTP_Page_Creator::get_temporary_page_id();

		// Get all pages (published and draft) for the selector dropdown.
		$pages = get_posts(
			array(
				'post_type'      => 'page',
				'post_status'    => array( 'publish', 'draft' ),
				'posts_per_page' => -1,
				'orderby'        => 'title',
				'order'          => 'ASC',
			)
		);

		$saved     = isset( $_GET['saved'] ) ? (int) $_GET['saved'] : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$activated = isset( $_GET['activated'] ) ? (int) $_GET['activated'] : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Almost Ready Settings', 'almost-ready-temporary-page' ); ?></h1>

			<?php if ( $saved ) : ?>
				<div class="notice notice-success is-dismissible">
					<p>
						<?php if ( $activated && $temporary_page ) : ?>
							<?php
							echo wp_kses(
								sprintf(
									/* translators: %s: URL to edit the temporary page */
									__( 'Settings saved. <a href="%s">Edit temporary page</a>', 'almost-ready-temporary-page' ),
									esc_url( get_edit_post_link( $temporary_page->ID ) )
								),
								array( 'a' => array( 'href' => array() ) )
							);
							?>
						<?php else : ?>
							<?php esc_html_e( 'Settings saved.', 'almost-ready-temporary-page' ); ?>
						<?php endif; ?>
					</p>
				</div>
			<?php endif; ?>

			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
				<?php wp_nonce_field( 'artp_save_settings', 'artp_settings_nonce' ); ?>
				<input type="hidden" name="action" value="artp_save_settings">

				<table class="form-table" role="presentation">
					<tr>
						<th scope="row"><?php esc_html_e( 'Temporary Page', 'almost-ready-temporary-page' ); ?></th>
						<td>
							<label>
								<input type="checkbox" name="artp_active" value="1" <?php checked( $is_active ); ?>>
								<?php esc_html_e( 'Activate — show the temporary page to all visitors', 'almost-ready-temporary-page' ); ?>
							</label>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="artp_page_id"><?php esc_html_e( 'Page', 'almost-ready-temporary-page' ); ?></label>
						</th>
						<td>
							<select name="artp_page_id" id="artp_page_id">
								<option value=""><?php esc_html_e( '— Select a page —', 'almost-ready-temporary-page' ); ?></option>
								<?php foreach ( $pages as $page ) : ?>
									<option value="<?php echo esc_attr( $page->ID ); ?>" <?php selected( $current_page_id, $page->ID ); ?>>
										<?php echo esc_html( $page->post_title ); ?>
										<?php if ( 'draft' === $page->post_status ) : ?>
											&mdash; <?php esc_html_e( 'Draft', 'almost-ready-temporary-page' ); ?>
										<?php endif; ?>
									</option>
								<?php endforeach; ?>
							</select>
							<p class="description">
								<?php esc_html_e( 'Choose which page visitors will see.', 'almost-ready-temporary-page' ); ?>
							</p>
						</td>
					</tr>
				</table>

				<?php submit_button( __( 'Save Settings', 'almost-ready-temporary-page' ) ); ?>
			</form>
		</div>
		<?php
	}

	/**
	 * Handle the settings form submission.
	 */
	public static function save_settings() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have permission to perform this action.', 'almost-ready-temporary-page' ) );
		}

		check_admin_referer( 'artp_save_settings', 'artp_settings_nonce' );

		$activate    = isset( $_POST['artp_active'] ) && '1' === $_POST['artp_active'];
		$page_id     = isset( $_POST['artp_page_id'] ) ? (int) $_POST['artp_page_id'] : 0;
		$old_page_id = ARTP_Page_Creator::get_temporary_page_id();
		$page_changed = $page_id && $page_id !== $old_page_id;

		if ( $page_id ) {
			ARTP_Page_Creator::set_temporary_page_id( $page_id );
		}

		if ( $activate ) {
			// Always publish when the user explicitly activates.
			ARTP_Page_Creator::activate_temporary_page();
		} elseif ( ! $page_changed ) {
			// Only deactivate when the page assignment itself didn't change.
			ARTP_Page_Creator::deactivate_temporary_page();
		}
		// If the page changed and activate is unchecked, leave the new page's status as-is.

		$redirect_args = array( 'saved' => '1' );
		if ( $activate ) {
			$redirect_args['activated'] = '1';
		}

		wp_safe_redirect( add_query_arg( $redirect_args, self::get_settings_url() ) );
		exit;
	}
}
