<?php
/**
 * Admin Notice Class
 *
 * Displays admin notice when maintenance mode is active.
 *
 * @package UnderConstructionWithBlocks
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class UCWB_Admin_Notice
 */
class UCWB_Admin_Notice {

	/**
	 * Initialize the admin notice functionality.
	 */
	public static function init() {
		add_action( 'admin_notices', array( __CLASS__, 'show_maintenance_notice' ) );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_admin_scripts' ) );
		add_action( 'wp_ajax_ucwb_deactivate_maintenance', array( __CLASS__, 'ajax_deactivate_maintenance' ) );
	}

	/**
	 * Show maintenance mode active notice.
	 */
	public static function show_maintenance_notice() {
		// Only show on admin pages.
		if ( ! is_admin() ) {
			return;
		}

		// Get fresh page status (bypass cache).
		$maintenance_page = get_page_by_path( UCWB_Page_Creator::PAGE_SLUG, OBJECT, 'page' );
		
		if ( ! $maintenance_page || 'publish' !== $maintenance_page->post_status ) {
			return;
		}

		// Get edit page URL.
		$edit_url = get_edit_post_link( $maintenance_page->ID );
		
		?>
		<div class="notice notice-warning is-dismissible ucwb-maintenance-notice">
			<p>
				<strong><?php esc_html_e( '🚧 Under Construction Mode is Active.', 'under-construction-with-blocks' ); ?></strong>
				<?php esc_html_e( 'Visitors see the maintenance page. Only logged-in users can access the site.', 'under-construction-with-blocks' ); ?>
			</p>
			<p>
				<button type="button" class="button button-primary ucwb-dropdown-toggle">
					<?php esc_html_e( 'Maintenance Options', 'under-construction-with-blocks' ); ?>
					<span class="dashicons dashicons-arrow-down-alt2" style="margin-left: 5px; margin-top: 3px;"></span>
				</button>
				<div class="ucwb-dropdown-menu" style="display: none;">
					<a href="<?php echo esc_url( $edit_url ); ?>" class="ucwb-dropdown-item">
						<span class="dashicons dashicons-edit"></span>
						<?php esc_html_e( 'Edit Under Construction Page', 'under-construction-with-blocks' ); ?>
					</a>
					<a href="#" class="ucwb-dropdown-item ucwb-deactivate-link">
						<span class="dashicons dashicons-hidden"></span>
						<?php esc_html_e( 'Deactivate Maintenance Mode', 'under-construction-with-blocks' ); ?>
					</a>
				</div>
			</p>
		</div>
		<?php
	}

	/**
	 * Enqueue admin scripts and styles.
	 */
	public static function enqueue_admin_scripts() {
		// Only enqueue on admin pages where the notice is shown.
		if ( ! is_admin() ) {
			return;
		}

		$maintenance_page = UCWB_Page_Creator::get_maintenance_page();
		if ( ! $maintenance_page || 'publish' !== $maintenance_page->post_status ) {
			return;
		}

		// Inline CSS for the dropdown.
		wp_add_inline_style(
			'common',
			'
			.ucwb-dropdown-menu {
				position: absolute;
				background: #fff;
				border: 1px solid #c3c4c7;
				border-radius: 4px;
				box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
				margin-top: 8px;
				z-index: 1000;
				min-width: 250px;
			}
			.ucwb-dropdown-item {
				display: block;
				padding: 10px 15px;
				color: #2271b1;
				text-decoration: none;
				border-bottom: 1px solid #f0f0f1;
			}
			.ucwb-dropdown-item:last-child {
				border-bottom: none;
			}
			.ucwb-dropdown-item:hover {
				background: #f6f7f7;
				color: #135e96;
			}
			.ucwb-dropdown-item .dashicons {
				margin-right: 8px;
				color: #50575e;
			}
			.ucwb-dropdown-toggle {
				position: relative;
			}
			'
		);

		// Inline JavaScript for dropdown functionality.
		wp_add_inline_script(
			'common',
			"
			jQuery(document).ready(function($) {
				// Toggle dropdown
				$('.ucwb-dropdown-toggle').on('click', function(e) {
					e.preventDefault();
					e.stopPropagation();
					$('.ucwb-dropdown-menu').slideToggle(200);
				});

				// Close dropdown when clicking outside
				$(document).on('click', function(e) {
					if (!$(e.target).closest('.ucwb-dropdown-toggle, .ucwb-dropdown-menu').length) {
						$('.ucwb-dropdown-menu').slideUp(200);
					}
				});

				// Handle deactivate link
				$('.ucwb-deactivate-link').on('click', function(e) {
					e.preventDefault();
					
					if (!confirm('" . esc_js( __( 'Are you sure you want to deactivate maintenance mode? Visitors will be able to access your site.', 'under-construction-with-blocks' ) ) . "')) {
						return;
					}

					$.ajax({
						url: ajaxurl,
						type: 'POST',
						data: {
							action: 'ucwb_deactivate_maintenance',
							nonce: '" . wp_create_nonce( 'ucwb_deactivate_maintenance' ) . "'
						},
						success: function(response) {
							if (response.success) {
								location.reload();
							} else {
								alert('" . esc_js( __( 'Error deactivating maintenance mode. Please try again.', 'under-construction-with-blocks' ) ) . "');
							}
						},
						error: function() {
							alert('" . esc_js( __( 'Error deactivating maintenance mode. Please try again.', 'under-construction-with-blocks' ) ) . "');
						}
					});
				});
			});
			"
		);
	}

	/**
	 * AJAX handler to deactivate maintenance mode.
	 */
	public static function ajax_deactivate_maintenance() {
		// Check nonce.
		check_ajax_referer( 'ucwb_deactivate_maintenance', 'nonce' );

		// Check user capabilities.
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => __( 'You do not have permission to perform this action.', 'under-construction-with-blocks' ) ) );
		}

		// Deactivate maintenance mode.
		UCWB_Page_Creator::deactivate_maintenance_page();

		wp_send_json_success( array( 'message' => __( 'Maintenance mode deactivated.', 'under-construction-with-blocks' ) ) );
	}
}
