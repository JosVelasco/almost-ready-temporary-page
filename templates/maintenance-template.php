<?php
/**
 * Template Name: Maintenance Page
 * 
 * A blank template that only shows the page content without header/footer.
 *
 * @package UnderConstructionWithBlocks
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
	<style>
		/* Remove any default body margin/padding */
		body {
			margin: 0;
			padding: 0;
		}
		/* Hide admin bar if shown */
		#wpadminbar {
			display: none !important;
		}
		html {
			margin-top: 0 !important;
		}
		/* Ensure full height */
		html, body {
			height: 100%;
		}
	</style>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<?php
// Output the page content.
while ( have_posts() ) :
	the_post();
	?>
	<div class="wp-site-blocks">
		<?php the_content(); ?>
	</div>
	<?php
endwhile;
?>
<?php wp_footer(); ?>
</body>
</html>
