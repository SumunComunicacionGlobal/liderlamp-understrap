<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after
 *
 * @package UnderStrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$container = get_theme_mod( 'understrap_container_type' );
?>

<?php if ( is_singular() ) :

	$post_type = get_post_type();
	
	if ( is_active_sidebar( $post_type . '-prefooter' ) ) : ?>

		<div id="wrapper-<?php echo $post_type; ?>-prefooter" class="wrapper">

			<div class="container">

				<div class="row">

					<?php dynamic_sidebar( $post_type . '-prefooter' ); ?>

				</div>

			</div>

		</div>

	<?php endif; ?>

<?php endif; ?>

<?php if ( ( !class_exists( 'woocommerce') || ( !is_cart() && !is_checkout() && !is_account_page() ) && is_active_sidebar( 'global-prefooter-newsletter' ) ) ) : ?>

	<div id="wrapper-global-prefooter-newsletter" class="wrapper">

		<div class="container">

			<div class="row">

				<?php dynamic_sidebar( 'global-prefooter-newsletter' ); ?>

			</div>

		</div>

	</div>

<?php endif; ?>

<?php if ( ( !class_exists( 'woocommerce') || ( !is_cart() && !is_checkout() && !is_account_page() ) && is_active_sidebar( 'global-prefooter-social' ) ) ) : ?>

	<div id="wrapper-global-prefooter-social" class="wrapper">

		<div class="container">

			<!-- <div class="row"> -->

				<?php dynamic_sidebar( 'global-prefooter-social' ); ?>

			<!-- </div> -->

		</div>

	</div>

<?php endif; ?>

<?php if ( ( class_exists( 'woocommerce' ) && is_woocommerce() ) || is_front_page() ) :

	if ( is_active_sidebar( 'woocommerce-prefooter' ) ) : ?>

		<div id="wrapper-woocommerce-prefooter" class="wrapper">

			<div class="container">

				<div class="row">

					<?php dynamic_sidebar( 'woocommerce-prefooter' ); ?>

				</div>

			</div>

		</div>

	<?php endif; ?>

<?php endif; ?>


<?php get_template_part( 'sidebar-templates/sidebar', 'footerfull' ); ?>

<?php if ( is_active_sidebar( 'copyright' ) ) { ?>

	<div class="wrapper" id="wrapper-footer">

		<div class="<?php echo esc_attr( $container ); ?>">

			<div class="row">

				<?php dynamic_sidebar( 'copyright' ); ?>

			</div><!-- row end -->

		</div><!-- container end -->

	</div><!-- wrapper end -->

<?php } ?>

</div><!-- #page we need this extra closing tag here -->

<?php wp_footer(); ?>

</body>

</html>

