<?php
/**
 * The header for our theme
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package UnderStrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$container = get_theme_mod( 'understrap_container_type' );
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?> <?php understrap_body_attributes(); ?>>
<?php do_action( 'wp_body_open' ); ?>
<div class="site" id="page">

	<?php do_action( 'liderlamp_before_top_bar' ); ?>

	<?php liderlamp_top_bar(); ?>

	<?php do_action( 'liderlamp_after_top_bar' ); ?>

	<!-- ******************* The Navbar Area ******************* -->
	<div id="wrapper-navbar" class="sticky-top">

		<a class="skip-link sr-only sr-only-focusable" href="#content"><?php esc_html_e( 'Skip to content', 'understrap' ); ?></a>

		<nav id="liderlamp-nav" class="navbar navbar-light bg-white">

			<div class="liderlamp-nav-left">

				<button class="navbar-toggler" type="button" aria-label="<?php esc_attr_e( 'Toggle navigation', 'understrap' ); ?>">
					<?php menu_toggler(); ?>
					<!-- <span class="navbar-toggler-icon"></span> -->
				</button>

			</div>

			<div class="liderlamp-nav-center d-none d-sm-block">

				<!-- Your site title as branding in the menu -->
				<?php if ( ! has_custom_logo() ) { ?>

						<a class="navbar-brand" rel="home" href="<?php echo esc_url( home_url( '/' ) ); ?>" itemprop="url"><?php echo get_logo_svg(); ?></a>

					<?php
				} else {
					the_custom_logo();
				}

				?>
				<!-- end custom logo -->

			</div>

			<div class="liderlamp-nav-right">

				<?php
					$wishlist_options = get_option( 'tinvwl-general', false );
					$contacto_id = get_theme_mod( 'contacto_id' );
				?>
				<div class="nav-icons">

					<a href="#search-collapse" data-toggle="collapse" title="<?php echo __( 'Search' ); ?>"><img src="<?php echo get_stylesheet_directory_uri(); ?>/img/search.svg" alt="<?php echo __( 'Search' ); ?>" width="20" height="20"></a>

					<?php if ( class_exists( 'WooCommerce') ) { ?>

						<a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>" title="<?php echo get_the_title( get_option('woocommerce_myaccount_page_id') ); ?>"><img src="<?php echo get_stylesheet_directory_uri(); ?>/img/login.svg" alt="<?php echo __( 'Login' ); ?>" width="19" height="20"></a>

					<?php } ?>

					<?php if ( class_exists( 'TInvWL' ) ) { 
						$my_wishlist_endpoint_slug = $wishlist_options['my_account_endpoint_slug'];
						$my_wishlist_title = $wishlist_options['default_title'];
						$wishlist_endpoint_url = wc_get_account_endpoint_url( $my_wishlist_endpoint_slug );
						?>
						
						<a href="<?php echo $wishlist_endpoint_url; ?>" title="<?php echo $my_wishlist_title; ?>"><img src="<?php echo get_stylesheet_directory_uri(); ?>/img/like.svg" alt="<?php echo get_the_title( $wishlist_options['page_wishlist'] ); ?>" width="21" height="20"></a>

					<?php } ?>

					<?php if ( class_exists( 'WooCommerce') ) { 

						$items_count = WC()->cart->get_cart_contents_count(); ?>

						<a href="<?php echo get_permalink( wc_get_page_id( 'cart' ) ); ?>" title="<?php echo get_the_title( wc_get_page_id( 'cart' ) ); ?>"><img src="<?php echo get_stylesheet_directory_uri(); ?>/img/shop.svg" alt="<<?php echo get_the_title( wc_get_page_id( 'cart' ) ); ?>" width="16" height="20">

    						<span class="cart-count"><?php echo $items_count ? $items_count : ''; ?></span>

						</a>

					<?php } ?>

					<?php if ( $contacto_id ) { ?>

						<a class="d-none d-sm-inline-block" href="<?php echo get_permalink( $contacto_id ); ?>" title="<?php echo get_the_title( $contacto_id ); ?>"><img src="<?php echo get_stylesheet_directory_uri(); ?>/img/phone.svg" alt="<?php echo get_the_title( $contacto_id ); ?>"></a>

					<?php } ?>

				</div>

			</div>

		</nav>
		
		<div class="collapse" id="search-collapse">

			<?php if ( class_exists( 'woocommerce') ) {
				get_product_search_form();
			} else {
				get_search_form();
			} ?>

		</div>

		<nav id="desktop-nav" class="d-none d-lg-flex navbar navbar-expand-lg navbar-light bg-white" aria-labelledby="desktop-nav-label">

			<p id="desktop-nav-label" class="sr-only">
				<?php esc_html_e( 'Desktop Navigation', 'understrap' ); ?>
			</p>

			<!-- <div class="container-fluid"> -->

				<!-- The WordPress Menu goes here -->
				<?php
				wp_nav_menu(
					array(
						'theme_location'  => 'primary',
						'container_class' => 'collapse navbar-collapse',
						'container_id'    => 'navbarNavDropdown',
						'menu_class'      => 'navbar-nav mx-auto container',
						'fallback_cb'     => '',
						'menu_id'         => 'desktop-menu',
						'depth'           => 2,
						'walker'          => new Understrap_WP_Bootstrap_Navwalker(),
					)
				);
				?>

			<!-- </div> -->

		</nav><!-- .site-navigation -->

		<?php do_action( 'liderlamp_after_navbar' ); ?>

	</div><!-- #wrapper-navbar end -->

	<?php liderlamp_breadcrumbs(); ?>
