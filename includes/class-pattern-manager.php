<?php
/**
 * Pattern Manager Class
 *
 * Registers each style variation as a full-page block pattern under the
 * "Almost Ready" category so users can browse and apply them directly from
 * the block editor's Inserter → Patterns panel.
 *
 * Also shows a one-time dismissible editor notice on the temporary page
 * pointing users to the Patterns panel.
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
		add_action( 'enqueue_block_editor_assets', array( __CLASS__, 'maybe_show_editor_notice' ) );
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
	 * Register one block pattern per style variation.
	 */
	public static function register_patterns() {
		foreach ( ARTP_Style_Manager::get_styles() as $slug => $style ) {
			register_block_pattern(
				'almost-ready-temporary-page/' . $slug,
				array(
					'title'       => $style['label'],
					'description' => $style['description'],
					'categories'  => array( self::CATEGORY_SLUG ),
					'content'     => ARTP_Style_Manager::get_content_for_style( $slug ),
				)
			);
		}
	}

	/**
	 * Show a dismissible block editor notice on the temporary page only,
	 * pointing users to Inserter → Patterns → Almost Ready.
	 *
	 * Uses wp.data.dispatch( 'core/notices' ) via inline script so no
	 * separate JS file is needed.
	 */
	public static function maybe_show_editor_notice() {
		$page = ARTP_Page_Creator::get_temporary_page();

		if ( ! $page || get_the_ID() !== $page->ID ) {
			return;
		}

		$message = wp_json_encode(
			__( 'Almost Ready: to change the page style, open the Inserter (+) → Patterns → Almost Ready and click any layout to apply it.', 'almost-ready-temporary-page' )
		);

		wp_add_inline_script(
			'wp-blocks',
			'wp.domReady( function() {
				wp.data.dispatch( "core/notices" ).createInfoNotice(
					' . $message . ',
					{ isDismissible: true, id: "artp-patterns-tip" }
				);
			} );'
		);
	}
}
