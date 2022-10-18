<?php
/**
 * Declaring widgets
 *
 * @package UnderStrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

add_action( 'widgets_init', 'understrap_widgets_init' );

if ( ! function_exists( 'understrap_widgets_init' ) ) {
	/**
	 * Initializes themes widgets.
	 */
	function understrap_widgets_init() {
		register_sidebar(
			array(
				'name'          => __( 'Top bar', 'understrap' ),
				'id'            => 'top-bar',
				'description'   => __( 'Top bar (para aviso destacado)', 'understrap' ),
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget'  => '</aside>',
				'before_title'  => '<p class="widget-title">',
				'after_title'   => '</p>',
			)
		);

		register_sidebar(
			array(
				'name'          => __( 'Right Sidebar', 'understrap' ),
				'id'            => 'right-sidebar',
				'description'   => __( 'Right sidebar widget area', 'understrap' ),
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget'  => '</aside>',
				'before_title'  => '<p class="widget-title">',
				'after_title'   => '</p>',
			)
		);

		register_sidebar(
			array(
				'name'          => __( 'Left Sidebar', 'understrap' ),
				'id'            => 'left-sidebar',
				'description'   => __( 'Left sidebar widget area', 'understrap' ),
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget'  => '</aside>',
				'before_title'  => '<p class="widget-title">',
				'after_title'   => '</p>',
			)
		);

		register_sidebar(
			array(
				'name'          => __( 'CTA Asesoramiento', 'understrap' ),
				'id'            => 'asesoramiento',
				'description'   => __( 'Aparece en diferentes partes de la web donde el cliente podría tener dudas', 'understrap' ),
				'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
				'after_widget'  => '</div><!-- .footer-widget -->',
				'before_title'  => '<p class="widget-title">',
				'after_title'   => '</p>',
			)
		);

		register_sidebar(
			array(
				'name'          => __( 'Introducción a los artículos relacionados de la trastienda', 'understrap' ),
				'id'            => 'intro-trastienda-interlinking',
				'description'   => __( 'Aparece al final de las páginas de la tienda cuando hay artículos de la trastienda relacionados con el producto o la categoría que se está viendo', 'understrap' ),
				'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
				'after_widget'  => '</div><!-- .footer-widget -->',
				'before_title'  => '<p class="widget-title">',
				'after_title'   => '</p>',
			)
		);

		register_sidebar(
			array(
				'name'          => __( 'Prefooter WooCommerce', 'understrap' ),
				'id'            => 'woocommerce-prefooter',
				'description'   => __( 'Aparece antes del footer en las fichas de producto, categorías y página de inicio', 'understrap' ),
				'before_widget' => '<div id="%1$s" class="footer-widget %2$s col-12">',
				'after_widget'  => '</div><!-- .footer-widget -->',
				'before_title'  => '<p class="widget-title">',
				'after_title'   => '</p>',
			)
		);

		register_sidebar(
			array(
				'name'          => __( 'Prefooter posts blog', 'understrap' ),
				'id'            => 'post-prefooter',
				'description'   => __( 'Aparece antes del footer en las entradas de Blog / Trastienda', 'understrap' ),
				'before_widget' => '<div id="%1$s" class="footer-widget %2$s dynamic-classes">',
				'after_widget'  => '</div><!-- .footer-widget -->',
				'before_title'  => '<p class="widget-title">',
				'after_title'   => '</p>',
			)
		);

		register_sidebar(
			array(
				'name'          => __( 'Prefooter productos', 'understrap' ),
				'id'            => 'product-prefooter',
				'description'   => __( 'Aparece antes del footer en las fichas de producto', 'understrap' ),
				'before_widget' => '<div id="%1$s" class="footer-widget %2$s dynamic-classes">',
				'after_widget'  => '</div><!-- .footer-widget -->',
				'before_title'  => '<p class="widget-title">',
				'after_title'   => '</p>',
			)
		);

		register_sidebar(
			array(
				'name'          => __( 'Prefooter páginas', 'understrap' ),
				'id'            => 'page-prefooter',
				'description'   => __( 'Aparece antes del footer en las páginas', 'understrap' ),
				'before_widget' => '<div id="%1$s" class="footer-widget %2$s dynamic-classes">',
				'after_widget'  => '</div><!-- .footer-widget -->',
				'before_title'  => '<p class="widget-title">',
				'after_title'   => '</p>',
			)
		);

		register_sidebar(
			array(
				'name'          => __( 'Prefooter global (newsletter)', 'understrap' ),
				'id'            => 'global-prefooter-newsletter',
				'description'   => __( 'Aparece antes del footer en todo el sitio con textura de fondo', 'understrap' ),
				'before_widget' => '<div id="%1$s" class="footer-widget %2$s dynamic-classes">',
				'after_widget'  => '</div><!-- .footer-widget -->',
				'before_title'  => '<p class="widget-title">',
				'after_title'   => '</p>',
			)
		);

		register_sidebar(
			array(
				'name'          => __( 'Prefooter global (social)', 'understrap' ),
				'id'            => 'global-prefooter-social',
				'description'   => __( 'Aparece antes del footer en todo el sitio. Para redes sociales y testimonios', 'understrap' ),
				'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
				'after_widget'  => '</div><!-- .footer-widget -->',
				'before_title'  => '<p class="widget-title">',
				'after_title'   => '</p>',
			)
		);

		register_sidebar(
			array(
				'name'          => __( 'Footer Full', 'understrap' ),
				'id'            => 'footerfull',
				'description'   => __( 'Full sized footer widget with 3 columns', 'understrap' ),
				'before_widget' => '<div id="%1$s" class="footer-widget %2$s col-md-12">',
				'after_widget'  => '</div><!-- .footer-widget -->',
				'before_title'  => '<p class="widget-title">',
				'after_title'   => '</p>',
			)
		);

		register_sidebar(
			array(
				'name'          => __( 'Copyright', 'liderlamp-admin' ),
				'id'            => 'copyright',
				'description'   => __( 'Full sized footer widget with 2 columns', 'understrap' ),
				'before_widget' => '<div id="%1$s" class="footer-widget %2$s col-sm-6">',
				'after_widget'  => '</div><!-- .footer-widget -->',
				'before_title'  => '<p class="widget-title">',
				'after_title'   => '</p>',
			)
		);

		//register MegaMenu widget if the Mega Menu is set as the menu location
		$location = 'primary';
		$css_class = 'mega-menu-parent';
		$locations = get_nav_menu_locations();
		if ( isset( $locations[ $location ] ) ) {
		  $menu = get_term( $locations[ $location ], 'nav_menu' );
		  if ( $items = wp_get_nav_menu_items( $menu->name ) ) {
		    foreach ( $items as $item ) {
		      if ( in_array( $css_class, $item->classes ) ) {
		        register_sidebar( array(
		          'id'   => 'mega-menu-item-' . $item->ID,
		          'description' => 'Mega Menu items',
		          'name' => $item->title . ' - Mega Menu',
		          'before_widget' => '<li id="%1$s" class="mega-menu-item">',
		          'after_widget' => '</li>', 

		        ));
		      }
		    }
		  }
		}

	}
} // End of function_exists( 'understrap_widgets_init' ).
