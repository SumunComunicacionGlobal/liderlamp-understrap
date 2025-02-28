<?php

function remove_image_zoom_support() {
    remove_theme_support( 'wc-product-gallery-zoom' );
}
add_action( 'wp', 'remove_image_zoom_support', 100 );

remove_action( 'wp_footer', 'woocommerce_demo_store' );
add_action( 'wp_body_open', 'woocommerce_demo_store' );

add_filter( 'woocommerce_product_post_type_link_parent_category_only', '__return_true' );

// add_action( 'registered_post_type', 'wpse_178112_permastruct_html', 1000, 2 );
// function wpse_178112_permastruct_html( $post_type, $args ) {
//     if ( $post_type === 'product' )
//         add_permastruct( $post_type, "{$args->rewrite['slug']}/%$post_type%.html", $args->rewrite );
// }


remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0);

add_filter( 'woocommerce_show_page_title', '__return_false' );
remove_action( 'woocommerce_archive_description', 'woocommerce_taxonomy_archive_description', 10 );
add_action( 'woocommerce_archive_description', 'liderlamp_woocommerce_taxonomy_description', 10 );

add_filter( 'woocommerce_product_description_heading', '__return_null' );
add_filter( 'woocommerce_product_additional_information_heading', '__return_null' );

// remove_action( 'woocommerce_no_products_found', 'wc_no_products_found', 10 );

remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);

// remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
// add_action( 'woocommerce_before_single_product_summary', 'woocommerce_template_single_title', 5 );

// remove_action ( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
// remove_action ( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
remove_action ( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
// add_action ( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 20 );
// add_action ( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 10 );
add_action ( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 15 );

// add_filter( 'woocommerce_product_related_products_heading', function() { return __( 'También te recomendamos...', 'liderlamp' ); } );
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );

// Quita el SKU del front
add_filter( 'wc_product_sku_enabled', 'sv_remove_product_page_skus' );
function sv_remove_product_page_skus( $enabled ) {
    if ( ! is_admin() && is_product() ) {
        return false;
    }

    return $enabled;
}

// Muestra categorías relacionadas en las páginas de categoría
// add_action( 'woocommerce_after_shop_loop', 'liderlamp_product_cat_interlinking', 20 );
add_action( 'woocommerce_after_main_content', 'liderlamp_product_cat_interlinking', 20 );
function liderlamp_product_cat_interlinking() {


    if ( !is_tax( 'product_cat' ) ) return;

    global $wp_query;
    if( count( $wp_query->query ) > 1 ) return;
    $term = get_queried_object();

    // if ( $term->parent == 0 ) return;

    $term_id = $term->term_id;
    $categorias_relacionadas_ids = get_term_meta( $term_id, 'categorias_relacionadas', true );

    // liderlamp_mas_vendidos( $term );

    if ( $categorias_relacionadas_ids ) {

        echo '<section class="related products categorias-relacionadas wrapper">';

            echo '<div class="container">';

                if (($key = array_search($term_id, $categorias_relacionadas_ids)) !== false) {
                    unset($categorias_relacionadas_ids[$key]);
                }
                echo '<p class="h3">'.__( 'También podría interesarte', 'liderlamp' ).'</p>';

                echo '<div class="slick-carousel">';

                    foreach ( $categorias_relacionadas_ids as $rel_term_id ) {
                        echo get_term_carousel_item( $rel_term_id );
                    }

                echo '</div>';

            echo '</div>';

        echo '</section>';
    }

}

// Muestra el footer de asesoriamiento pero solo en las categorías de más de primer nivel
// add_action( 'woocommerce_after_shop_loop', 'liderlamp_product_cat_cta_asesoramiento', 30 );
add_action( 'woocommerce_after_main_content', 'liderlamp_product_cat_cta_asesoramiento', 30 );
function liderlamp_product_cat_cta_asesoramiento() {
    
    if ( is_tax( 'product_cat' ) ) {
        $term = get_queried_object();
        if ( $term->parent == 0 ) return;
    }

    echo '<div class="container">';

    liderlamp_cta_asesoramiento();

    echo '</div>';

}

// Carga el footer de asesoriamiento
function liderlamp_cta_asesoramiento() {

    if ( is_active_sidebar( 'asesoramiento' ) ) {
        echo '<div class="asesoramiento">';
            dynamic_sidebar( 'asesoramiento' );
        echo '</div>';
    }

}

// To do
// add_action( 'woocommerce_after_shop_loop', 'liderlamp_product_cat_interlinking_trastienda', 32 );
// add_action( 'woocommerce_after_main_content', 'liderlamp_product_cat_interlinking_trastienda', 32 );
function liderlamp_product_cat_interlinking_trastienda() {

    echo '<div class="container">';


    echo '</div>';
}

// Inserta estilos dinámicos
// add_action( 'wp_head', 'liderlamp_dynamic_styles' );
function liderlamp_dynamic_styles() {

    echo '<style>';

        if ( is_tax( 'product_cat') ) {
            $term = get_queried_object();
            if ( $term->parent > 0 ) {
                $thumbnail_id = get_term_meta( $term->term_id, 'thumbnail_id', true );
                $image_url = wp_get_attachment_image_url( $thumbnail_id, 'large' );

                echo '.woocommerce-products-header { background-image: url('.$image_url.'); }';
            }
        }

    echo '</style>';
}

// Muestra la descripción secundaria de una categoría debajo de los productos
// add_action( 'woocommerce_after_shop_loop', 'liderlamp_product_cat_descripcion_secundaria', 25 );
add_action( 'woocommerce_after_main_content', 'liderlamp_product_cat_descripcion_secundaria', 25 );
function liderlamp_product_cat_descripcion_secundaria() {
    if ( is_woocommerce() && !is_tax() ) return;

    $term = get_queried_object();
    $term_id = $term->term_id;
    $descripcion_secundaria = get_term_meta( $term_id, 'details', true );

    if ( !$descripcion_secundaria ) return;

    global $wp_query;
    if( count( $wp_query->query ) > 1 ) return;

     
    echo '<div class="wrapper contenido-secundario">';
 
        echo '<div class="container">';

            echo apply_filters( 'the_content', $descripcion_secundaria );

        echo '</div>';
 
    echo '</div>';
    

}

// Muestra las entradas relacionadas en la página de categoría de producto
// add_action( 'woocommerce_after_shop_loop', 'liderlamp_product_cat_trastienda', 50 );
add_action( 'woocommerce_after_main_content', 'liderlamp_product_cat_trastienda', 50 );
function liderlamp_product_cat_trastienda() {

    if ( !is_tax( 'product_cat' ) ) return;

    $rel_posts = get_term_meta( get_queried_object_id(), 'posts_relacionados', true );
    
    if ( $rel_posts ) {

        echo '<div class="container">';
    
            liderlamp_relacionados_trastienda( $rel_posts );

        echo '</div>';

    }

}

// Carga las entradas relacionadas
function liderlamp_relacionados_trastienda( $post_ids = array() ) {

    if ( 0 !== absint( get_query_var( 'paged' ) ) ) return;

    if ( $post_ids ) {

        $args = array(
            'post_type'     => 'any',
            'posts_per_page'    => 8,
            'post__in'          => $post_ids,
            'orderby'           => 'rand',
        );

    } elseif ( is_tax() ) {

        $q_obj = get_queried_object();

        $args = array(
            'post_type'     => array('post', 'post_trastienda'),
            'posts_per_page'    => 8,
            'orderby'       => 'rand',
            'tax_query'     => array(
                                    array(
                                        'taxonomy'      => $q_obj->taxonomy,
                                        'field'         => 'term_id',
                                        'terms'         => $q_obj->term_id,
                                    )
                                ),
        );

    } else {
        return;
    }

    $q = new WP_Query($args);

    if ( $q->have_posts() ) { ?>


        <div class="interlinking-trastienda">

            <?php if ( is_active_sidebar( 'intro-trastienda-interlinking' ) ) {
                dynamic_sidebar( 'intro-trastienda-interlinking' );
            } ?>

        </div>

        <section class="related products wrapper">

            <div class="slick-carrusel-posts">

                <?php while ( $q->have_posts() ) { $q->the_post();
                    
                    get_template_part( 'loop-templates/content' );

                } ?>

            </div>

        </section>

    <?php }

    wp_reset_postdata();

}

// Muestra las subcategorías de la actual antes de los productos
add_action( 'woocommerce_archive_description', 'liderlamp_subcategorias_loop', 15 );
function liderlamp_subcategorias_loop() {

    if( !is_tax('product_cat') || 0 !== absint( get_query_var( 'paged' ) ) || isset($_GET['orderby']) ) return;
  
    // global $wp_query;
    // if( count( $wp_query->query ) > 1 ) return;

    $current_term = get_queried_object();

    $child_terms = get_terms( array(
        'taxonomy'          => 'product_cat',
        'parent'            => $current_term->term_id,
    ) );


    if ( $child_terms ) {

        echo '<section class="products">';

            if ( $current_term->slug == 'iluminacion' ) {

                echo '<p class="h2 text-left">'. sprintf( __( '%s por tipo', 'liderlamp') , $current_term->name ) .'</p>';

            }

            echo '<div class="slick-carousel">';

            foreach ( $child_terms as $child_term ) {
                echo get_term_carousel_item( $child_term );
            }

            echo '</div>';

        echo '</section>';

    }


    // remove_filter( 'get_terms', 'ts_get_subcategory_terms' );
    // $estilos_terms = get_terms( array(
    //     'taxonomy'          => 'product_cat',
    //     'parent'            => $current_term->term_id,
    //     'meta_query'        => array(array(
    //                                 'key'       => 'es_estilo',
    //                                 'value'     => 1,
    //                                 'type'      => 'BINARY',
    //     )),
    // ) );
    // add_filter( 'get_terms', 'ts_get_subcategory_terms', 10, 3 );


    // if ( $estilos_terms ) {

    //     echo '<section class="products">';

    //         if ( $current_term->slug == 'iluminacion' ) {

    //             echo '<p class="h2 text-left">'. sprintf( __( '%s por estilo', 'liderlamp') , $current_term->name ) .'</p>';

    //         }

    //         echo '<div class="slick-carousel">';

    //         foreach ( $estilos_terms as $estilo_term ) {
    //             echo get_term_carousel_item( $estilo_term );
    //         }

    //         echo '</div>';

    //     echo '</section>';

    // }

    $args_producto_destacado = array(
        'post_type'             => 'product',
        'posts_per_page'        => -1,
        'tax_query'             => array( 
                                        array(
                                            'taxonomy'      => 'product_cat',
                                            'field'         => 'term_id',
                                            'terms'         => $current_term->term_id,
                                        ),
                                        array(
                                            'taxonomy'      => 'product_visibility',
                                            'field'         => 'name',
                                            'terms'         => 'featured',
                                        ),
                                    ),
    );

    $q_destacados = new WP_Query($args_producto_destacado);

    if ( $q_destacados->have_posts() ) : ?>

        <section class="related products destacados wrapper alignfull">

            <div class="container">

                <p class="h3"><?php _e( 'Destacamos', 'liderlamp' ); ?></p>

                <div class="slick-carousel">

                <?php while ( $q_destacados->have_posts() ) : $q_destacados->the_post();

                    wc_get_template_part( 'content', 'product' );
                    // the_title();

                endwhile; ?>

                </div>

            </div>

        </section>

    <?php endif;

    wp_reset_postdata();


}


// Fuerza el modo de visualización de categorías de producto a "solo productos", ya que las subcategorías las mostramos más arriba de forma dinámica
add_filter( 'get_term_metadata', 'liderlamp_set_category_display', 10, 4 );
function liderlamp_set_category_display( $value, $object_id, $meta_key, $single ){

    if( 'display_type' === $meta_key ) {
        $display_type = 'products';
    } else {
        return $value;
    }
    
    return ( true === $single ) ? $display_type : array( $display_type );
}


add_filter( 'get_terms', 'ts_get_subcategory_terms', 10, 3 );
function ts_get_subcategory_terms( $terms, $taxonomies, $args ) {
    $new_terms = array();
    // if it is a product category and on the shop page
    if ( !$taxonomies || !is_array( $terms ) ) return $terms;
    if ( in_array( 'product_cat', $taxonomies ) && !is_admin() && is_tax() ) {


        remove_filter( 'get_terms', 'ts_get_subcategory_terms' );
        $estilos_ids = get_terms( array(
            'taxonomy'      => 'product_cat',
            'fields'        => 'ids',
            'hide_empty'    => false,
            'meta_query'    => array(
                                    array(
                                        'key'       => 'es_estilo',
                                        'value'     => 1,
                                        'type'      => 'BINARY',
                                    )
                                )
        ) );
        add_filter( 'get_terms', 'ts_get_subcategory_terms', 10, 3 );

        foreach( $terms as $key => $term ) {

            if ( !in_array( $term->term_id, $estilos_ids ) ) {
                $new_terms[] = $term;
            }
        }

        $terms = $new_terms;
    }
    return $terms;
}

function liderlamp_mas_vendidos( $term = false ) {

    if ( !$term ) $term = get_queried_object();

    $args = array(
        'post_type' => 'product',
        'meta_key' => 'total_sales',
        'orderby' => 'meta_value_num',
        'posts_per_page'    => 4,
        'posts_per_page' => 1,
        'tax_query'         => array( array(
                                    'taxonomy'      => $term->taxonomy,
                                    'field'         => 'term_id',
                                    'terms'         => $term->term_id,
        )),
    );

    $q = new WP_Query( $args );

    if ( $q->have_posts() ) : ?>

        <section class="related products mas-vendidos">

            <p class="h3"><?php _e( 'Los más vendidos', 'liderlamp' ); ?></p>

            <ul class="slick-carousel products">

                <?php while ( $q->have_posts() ) : $q->the_post(); 

                    wc_get_template_part( 'content', 'product' );
                    
                endwhile; ?>

            </ul>

        </section>

    <?php endif; ?>

    <?php wp_reset_postdata();

}

// add_action( 'woocommerce_product_after_tabs', 'liderlamp_product_after_tabs', 10 );
add_action( 'woocommerce_after_single_product_summary', 'liderlamp_product_after_tabs', 30 );
function liderlamp_product_after_tabs() {

    liderlamp_productos_relacionados();

    // To do - Opiniones del producto

    // To do - En vuestras casas

    $rel_posts = get_post_meta( get_the_ID(), 'posts_relacionados', true );
    if ( $rel_posts ) {

        liderlamp_relacionados_trastienda( $rel_posts );

    }
}

add_action( 'woocommerce_before_shop_loop_item_title', 'liderlamp_product_title_wrapper_before', 20 );
function liderlamp_product_title_wrapper_before() {
    echo '<div class="product-title-wrapper">';
}

add_action( 'woocommerce_after_shop_loop_item_title', 'liderlamp_product_title_wrapper_after', 50 );
function liderlamp_product_title_wrapper_after() {
    echo '</div>';
}

remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
add_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 30 );
add_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 20 );


// do_action( 'woocommerce_before_subcategory', $category );
// add_action( 'woocommerce_before_subcategory', $category );

function woocommerce_template_loop_category_link_open( $category ) { 
    if ( is_wp_error( $category ) || !is_a( $category, 'WP_Term' ) ) {
        return false;
    }

    if ( is_tax( 'product_cat' ) && 'estilo' == $category->taxonomy ) {
        $link = get_term_link( get_queried_object() );
        $link = add_query_arg( 'estilo', $category->slug, $link );
        echo '<a href="' . $link . '">'; 
    } else {
        echo '<a href="' . get_term_link( $category, 'product_cat' ) . '">'; 
    }
} 

add_filter( 'woocommerce_page_title', function( $page_title ) {
    
    global $wp_query;
    if ( isset( $wp_query->query['estilo']) ) {
        $estilo_term = get_term_by( 'slug', $wp_query->query['estilo'], 'estilo' );
        $page_title .= ' / ' . $estilo_term->name;
    }
    return $page_title;

}, 10, 1 );

add_action( 'woocommerce_shop_loop', 'liderlamp_intercalar_banner_entre_productos' );
function liderlamp_intercalar_banner_entre_productos() {
    global $wp_query;

    if ( $wp_query->current_post > 0 && $wp_query->current_post % 20 == 0 ) {

        $args = array(
            'post_type'         => 'banner',
            'posts_per_page'    => 1,
            'orderby'           => 'rand',
        );

        $q = new WP_Query( $args );

        if ( $q->have_posts() ) {

            while ( $q->have_posts() ) { $q->the_post();

                get_template_part( 'loop-templates/content', 'slide' );
                echo '<div></div>';

            }
        }

        wp_reset_postdata();

    }
}

add_filter( 'woocommerce_account_menu_items', 'liderlamp_mi_cuenta_menu_items', 99, 1 );
function liderlamp_mi_cuenta_menu_items( $items ) {
  
    $items['dashboard'] = __( 'Tu escritorio', 'liderlamp' );
    $items['orders'] = __( 'Tus compras', 'liderlamp' );
    if ( isset($items['downloads']) ) $items['downloads'] = __( 'Tus descargas', 'liderlamp' );
    $items['edit-address'] = __( 'Tus direcciones', 'liderlamp' );
    $items['edit-account'] = __( 'Tus datos de acceso', 'liderlamp' );
    // $items['tinv_wishlist'] = __( 'Tu lista de deseos', 'liderlamp' );
    $items['customer-logout'] = __( 'Cerrar sesión', 'liderlamp' );

    //$my_items = array(
    //  endpoint   => label
    //    '2nd-item' => __( '2nd Item', 'my_plugin' ),
    //    '3rd-item' => __( '3rd Item', 'my_plugin' ),
    //);

    // $my_items = array_slice( $items, 0, 1, true ) +
    //     $my_items +
    //     array_slice( $items, 1, count( $items ), true );

    return $items;
}

// add_filter( 'woocommerce_add_to_cart_fragments', 'wc_refresh_mini_cart_count');
function wc_refresh_mini_cart_count($fragments){
    ob_start();
    $items_count = WC()->cart->get_cart_contents_count();
    ?>
    <span class="cart-count"><?php echo $items_count ? $items_count : ''; ?></span>
    <?php
        $fragments['.cart-count'] = ob_get_clean();
    return $fragments;
}

/** Desactiva llamadas Ajax de WooCommerce*/
add_action( 'wp_enqueue_scripts', 'dequeue_woocommerce_cart_fragments', 11);
function dequeue_woocommerce_cart_fragments() {
    // if (is_front_page() || is_single() ) 
        wp_dequeue_script('wc-cart-fragments');
}

// Muestra solo productos de la subcategoría actual, no de las hijas (solo en las categorías de primer nivel)
function liderlamp_woocommerce_no_child_terms($wp_query) {  

    if ( !is_admin() && is_product_category() && $wp_query->is_main_query()) {

        $term = get_queried_object();
        if ( $term->parent !== 0 ) return;
       
        $queries = $wp_query->tax_query->queries;


        foreach ( $queries as $key => $query ) {

            if ( isset( $query['taxonomy'] ) && $query['taxonomy'] == 'product_cat' ) {

                $wp_query->tax_query->queries[$key]['include_children'] = 0;

            } 

        }

    }
  
}
add_action('parse_tax_query', 'liderlamp_woocommerce_no_child_terms');

function liderlamp_woocommerce_taxonomy_description() { 

    if ( 0 !== absint( get_query_var( 'paged' ) ) || isset($_GET['orderby']) ) { ?>

            <h1 class="woocommerce-products-header__title page-title"><?php woocommerce_page_title(); ?></h1>

    <?php } elseif ( is_woocommerce() && is_tax() ) {

        $term = get_queried_object();
        if ( $term->parent > 0 || !is_tax('product_cat') ) {

            $thumbnail_id = get_term_meta( $term->term_id, 'thumbnail_id', true );
            $galeria = get_term_meta( $term->term_id, 'galeria', true );
            if ( !$thumbnail_id && $galeria) $thumbnail_id = $galeria[0];

            if ( $thumbnail_id ) {
            ?>

                <div class="row liderlamp-product-cat-description">
                    
                    <div class="col-md-6 col-xl-5 mb-1">
                            
                            <?php if ( $galeria ) {

                                echo '<div class="slider-destacados">';

                                    echo wp_get_attachment_image( $thumbnail_id, 'woocommerce_single', false, '' );

                                    foreach ( $galeria as $img_id ) {
                                        echo wp_get_attachment_image( $img_id, 'woocommerce_single', false, '' ); 
                                    }

                                echo '</div>';

                            } else {

                                echo wp_get_attachment_image( $thumbnail_id, 'woocommerce_single', false, '' );

                            } ?>

                    </div>

                    <div class="col-md-6 col-xl-7">
                        
                        <h1 class="woocommerce-products-header__title page-title text-left"><?php woocommerce_page_title(); ?></h1>

                        <?php woocommerce_taxonomy_archive_description(); ?>
                        
                    </div>
                </div>

            <?php } else { ?>

                    <h1 class="woocommerce-products-header__title page-title"><?php woocommerce_page_title(); ?></h1>

                    <?php woocommerce_taxonomy_archive_description(); ?>

            <?php }

        } else { ?>

            <h1 class="woocommerce-products-header__title page-title"><?php woocommerce_page_title(); ?></h1>

            <?php woocommerce_taxonomy_archive_description(); ?>

        <?php }
    }

}

// add_action ( 'woocommerce_before_single_product_summary', 'liderlamp_compartir_producto', 20 );
function liderlamp_compartir_producto() {

    echo '<div class="liderlamp-compartir">';

        if ( is_plugin_active( 'super-socializer/super_socializer.php' ) ) {
            echo do_shortcode( '[TheChamp-Sharing style="color:#222222;"]' );
        }

        if ( is_plugin_active( 'ti-woocommerce-wishlist/ti-woocommerce-wishlist.php' ) ) {
            echo do_shortcode( '[ti_wishlists_addtowishlist]' );
        }


    echo '</div>';

}


add_action('woocommerce_email_header', 'add_css_to_email');

function add_css_to_email() {
 echo '
 <style type="text/css">
 /* Put CSS here */
 /*
 @font-face {
   font-family: Myfont;
   src: url(font_family_file.woff);
 }
*/
 p, h1, h2, h3, h4, h5 {
   font-family: "Century Gothic", CenturyGothic, Trebuchet MS, Arial, sans-serif;
 }
 </style>';
}

function liderlamp_replace_dismiss( $notice ){
    return str_replace( 'Descartar', __( 'Vale, oculta esto', 'liderlamp' ), $notice );
}
add_filter( 'woocommerce_demo_store','liderlamp_replace_dismiss' );


/** 
* Show the subcategory title in the product loop. 
*/ 
function woocommerce_template_loop_category_title( $category ) { 
    $tag = 'p';
  ?> 
  <<?php echo $tag; ?> class="woocommerce-loop-category__title"> 
      <?php 
          echo $category->name; 

          if ( $category->count > 0 ) 
              echo apply_filters( 'woocommerce_subcategory_count_html', ' <mark class="count">(' . $category->count . ')</mark>', $category ); 
      ?> 
  </<?php echo $tag; ?>> 
  <?php 
} 

/** 
* Ajustes SEO 
*/ 
function woocommerce_template_loop_product_title() { 
  echo '<p class="woocommerce-loop-product__title">' . get_the_title() . '</p>'; 
} 


add_filter ( 'woocommerce_product_description_tab_title', 'liderlamp_modificar_titulo_description_tab', 100, 2 );
function liderlamp_modificar_titulo_description_tab ( $title, $key ) {

    global $product;
    return sprintf( __( 'Descripción de %s', 'liderlamp' ), $product->get_title() );
}

add_filter ( 'woocommerce_product_reviews_tab_title', 'liderlamp_modificar_titulo_reviews_tab', 100, 2 );
function liderlamp_modificar_titulo_reviews_tab ( $title, $key ) {

    global $product;
    return sprintf( __( 'Valoraciones de %s (%d)', 'liderlamp' ), $product->get_title(), $product->get_review_count() );
}

add_filter( 'woocommerce_product_description_heading', '__return_null' );
add_filter( 'woocommerce_product_additional_information_heading', '__return_null' );

add_filter( 'woocommerce_product_tabs', 'liderlamp_remove_product_tabs', 98 );
 
function liderlamp_remove_product_tabs( $tabs ) {
  unset( $tabs['additional_information'] );
  unset( $tabs['reviews'] );
  return $tabs;
}

function smn_get_product_faqs() {

    $faqs = array();

    global $post;
    $product_faqs = get_field( 'faqs_relacionadas', $post );
    if ( $product_faqs ) {
        $faqs = array_merge( $faqs, $product_faqs );
    }
    
    $product_cats = get_the_terms( $post, 'product_cat' );
    $term_faqs = array();
    if ( $product_cats ) {
        foreach ( $product_cats as $product_cat ) {
            $term_faqs = get_field( 'faqs_relacionadas', $product_cat );
            if ( $term_faqs ) {
                $faqs = array_merge( $faqs, $term_faqs );
            }
        }
    }

    if ( $faqs ) {
        $faqs = array_unique($faqs, SORT_REGULAR);

        $r = '';

        foreach ( $faqs as $faq ) {
            $r .= '<h4>' . $faq->post_title . '</h4>';
            $r .= '<div class="mb-2">';
                $r .= apply_filters( 'the_content', $faq->post_content );
            $r .= '</div>';
        }

        return $r;
    }

    return false;

}

add_filter('woocommerce_product_tabs', 'liderlamp_add_faqs_tab');
function liderlamp_add_faqs_tab($tabs) {

    global $post;
    $faqs = smn_get_product_faqs();

    if ($faqs) {
        $tabs['faqs'] = array(
            'title'    => sprintf( __('Preguntas frecuentes sobre %s', 'liderlamp'), esc_html( $post->post_title ) ),
            'priority' => 50,
            'callback' => function() use ($faqs) {
                echo $faqs;
            }
        );
    }

    return $tabs;
}


// add_filter( 'woocommerce_product_tabs', 'quiero_que_me_lo_regalen_tab' );
function quiero_que_me_lo_regalen_tab( $tabs ) {
    // Adds the new tab
    $tabs['regalo'] = array(
        'title'     => __( 'Quiero que me lo regalen', 'liderlamp' ),
        'priority'  => 50,
        'callback'  => 'quiero_que_me_lo_regalen_tab_callback'
    );
    return $tabs;
}
function quiero_que_me_lo_regalen_tab_callback() {
    dynamic_sidebar( 'quiero-que-me-lo-regalen' );
}

// add_action( 'woocommerce_product_after_tabs', 'liderlamp_quiero_que_me_lo_regalen_button' );
function liderlamp_quiero_que_me_lo_regalen_button() {
    global $post;
    echo '<p class="text-center">';
        echo '<a target="_blank" rel="noopener noreferrer nofollow noindex" class="btn btn-outline-primary" href="' . get_the_permalink( QUIERO_QUE_ME_LO_REGALEN_ID ) . '?titulo='.$post->post_title.'&texto=' . $post->post_excerpt . '&url=' . get_the_permalink() . '&imagen=' . get_the_post_thumbnail_url( null, 'full' ) . '">' . __( 'Quiero que me lo regalen', 'liderlamp' ) . '</a>';
    echo '</p>';
}

add_filter('paginate_links', function($link) {
    $pos = strpos($link, 'page/1/');
    if($pos !== false) {
        $link = substr($link, 0, $pos);
    }
    return $link;
});

function zarabici_format_price_range( $price, $from, $to ) {
    return sprintf( '<span class="price-range-from">%s</span> %s', __( 'Desde', 'liderlamp' ), wc_price( $from ) );
}
add_filter( 'woocommerce_format_price_range', 'zarabici_format_price_range', 10, 3 );

add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_agotado', 15);
function woocommerce_template_loop_agotado() {
    global $product;
    if ( !$product->is_in_stock() ) {
        echo '<p class="aviso-producto-loop outofstock">' . $product->add_to_cart_text() . '</p>';
    }
}

add_filter( 'the_title', 'woo_personalize_order_received_title', 10, 2 );
function woo_personalize_order_received_title( $title, $id ) {
    if ( is_order_received_page() && get_the_ID() === $id ) {
        global $wp;

        // Get the order. Line 9 to 17 are present in order_received() in includes/shortcodes/class-wc-shortcode-checkout.php file
        $order_id  = apply_filters( 'woocommerce_thankyou_order_id', absint( $wp->query_vars['order-received'] ) );
        $order_key = apply_filters( 'woocommerce_thankyou_order_key', empty( $_GET['key'] ) ? '' : wc_clean( $_GET['key'] ) );

        if ( $order_id > 0 ) {
            $order = wc_get_order( $order_id );
            if ( $order->get_order_key() != $order_key ) {
                $order = false;
            }
        }

        if ( isset ( $order ) ) {
            //$title = sprintf( "You are awesome, %s!", esc_html( $order->billing_first_name ) ); // use this for WooCommerce versions older then v2.7
        $title = sprintf( __( 'Gracias, %s!', 'liderlamp' ), esc_html( $order->get_billing_first_name() ) );
        }
    }
    return $title;
}

add_filter('woocommerce_thankyou_order_received_text', 'woo_change_order_received_text', 10, 2 );
function woo_change_order_received_text( $str, $order ) {
    $new_str = $str . ' ' . __( 'Te hemos enviado un email confirmando tu compra.', 'liderlamp' );
    return $new_str;
}

/**
 * Add a message above the login / register form on my-account page
 */
add_action( 'woocommerce_before_customer_login_form', 'liderlamp_login_message' );
function liderlamp_login_message() {
    if ( get_option( 'woocommerce_enable_myaccount_registration' ) == 'yes' ) {

       echo '<p class="intro-text">' . __( 'Disfruta de Liderlamp en toda su esencia, crea tus listas de deseos y conoce nuestras novedades antes que nadie.', 'liderlamp' ) . '</p>';

    }
}

add_action( 'woocommerce_account_dashboard', 'liderlamp_dashboard_message' );
function liderlamp_dashboard_message() {
    echo '<p>' . __( 'Es todo tuyo, así que, <b>disfruta, que en Liderlamp estamos para eso</b> ;)', 'liderlamp' ). '</p>';
}


add_action( 'woocommerce_email_customer_details', 'liderlamp_anadir_nota_cliente_a_emails', 100, 4 );
function liderlamp_anadir_nota_cliente_a_emails( $order, $sent_to_admin, $plain_text, $email ) {

    $nota = $order->get_customer_note();
    if ( $nota ) {
        echo '<h3>' . __( 'Notas del pedido', 'liderlamp' ) . ': </h3>';    
        echo '<p>' . $nota . '</p>';
    }
    
}

// MOSTRAR SOLO EL ENVÍO MÁS BARATO (PARA QUE LOS NACIONALES VAYAN POR MRW Y LOS INTERNACIONALES POR ASM)
add_filter( 'woocommerce_package_rates', 'liderlamp_show_only_lowest_shipping_rate' , 10, 2 );
function liderlamp_show_only_lowest_shipping_rate( $rates, $package ) {
     // Only modify rates if more than one rates are available
   if ( isset( $rates ) && count( $rates ) > 1 ) {

        $lowest_rate = null;
        $lowest_rate_key = null;
        $lowest_rate_cost = 1000000;
        foreach ( $rates as $rate_key => $rate ) {
           if( $rate->cost < $lowest_rate_cost ){
                $lowest_rate_cost = $rate->cost;
                 $lowest_rate_key = $rate_key;
               $lowest_rate = $rate;
           }
       }
       // return array only containing the lowest rate
         if( isset( $lowest_rate ) )
             return array( $lowest_rate_key => $lowest_rate );
 
    }
   
    return $rates;
}

// MULTIPLICAR COSTES DE ENVÍO PARA EUROBUSINESS PARCEL SEGÚN CARRITO
add_filter( 'woocommerce_package_rates', 'woocommerce_package_rates' );
function woocommerce_package_rates( $rates ) {

    foreach ($rates as $rate_key => $rate) {

        if ( 'eurobusinessparcel' === $rate_key ) {

            $iva_productos = 0.21;
            $paso_coste = 400 / (1 + $iva_productos);
            $subtotal = WC()->cart->get_subtotal();
            $multiplicador = ceil( $subtotal / $paso_coste );

            if ( $multiplicador > 1 ) {
                
                $base_cost = $rate->cost;
                $new_cost = $base_cost * $multiplicador;
                // $rate_operand = $new_cost / $base_cost;
                $rates[$rate_key]->cost = $new_cost;

                $taxes = $rates[$rate_key]->taxes;

                foreach ( $rates[$rate_key]->taxes as $key => $tax ) {
                    if ( $tax > 0 ) {
                        $taxes[$key] = $tax * $multiplicador;
                    }
                }

                $rates[$rate_key]->taxes = $taxes;

            }

        }

        if ( 'mrw' === $rate_key && $rate->cost == 0 ) {
            $rates[$rate_key]->label .= ' <small class="tax_label">(' . __( 'Gratis', 'liderlamp' ) . ')</small>';
        }

    }

    return $rates;
}

// SACAR NUEVOS CAMPOS POR LA API DE WC
add_filter( 'woocommerce_rest_prepare_shop_order_object', 'liderlamp_add_data_to_order_response', 10, 3 );
function liderlamp_add_data_to_order_response( $response, $object, $request ) {

    if ( empty($response->data) ) return $response;

    $response->data['billing']['nif'] = get_post_meta($response->data['id'], '_billing_nif', true );
    $response->data['shipping']['email'] = get_post_meta($response->data['id'], '_shipping_email', true );

    return $response;

}

add_action( 'wpo_wcpdf_after_item_meta', 'liderlamp_imagen_factura', 10, 3 );
function liderlamp_imagen_factura( $type, $item, $order ) {
    echo '<span style="padding: 40px;">' . $item['thumbnail'] . '</span>';
}


// AÑADE UN BOTÓN EN CADA PEDIDO DE WOOCOMMERCE PARA VER LA FACTURA DE ODOO
add_filter( 'woocommerce_my_account_my_orders_actions', 'liderlamp_woocommerce_add_my_account_my_orders_view_odoo_invoice', 10, 2 );
function liderlamp_woocommerce_add_my_account_my_orders_view_odoo_invoice( $actions, $order ) {

    $url = get_post_meta( $order->ID, 'odoo_invoice', true );
    // $url = 'https://test.com';

    if ( $url ) {

        $action_slug = 'view_odoo_invoice';
        $actions[$action_slug] = array(
            'url'  => $url,
            'name' => __( 'Ver factura', 'liderlamp' ),
        );

    }

    return $actions;
}

// ABRE LA FACTURA EN PESTAÑA NUEVA
add_action( 'woocommerce_after_account_orders', 'action_after_account_orders_js');
function action_after_account_orders_js() {
    $action_slug = 'view_odoo_invoice';
    ?>
    <script>
    jQuery(function($){
        $('a.<?php echo $action_slug; ?>').each( function(){
            $(this).attr('target','_blank');
        })
    });
    </script>
    <?php
}

function liderlamp_elementos_topbar_cliente() {

    $r = '';

    if ( is_user_logged_in() ) {

        $current_user = wp_get_current_user();
        $r .= wpautop( sprintf( __( 'Hola, %s%s%s', 'liderlamp'), 
            '<a href="'. get_permalink( get_option('woocommerce_myaccount_page_id') ) .'">',
            $current_user->first_name,
            '</a>' ) );

    } else {

        $r .= wpautop( 
                sprintf( 
                    __( '¿Ya eres cliente? %s entra %s', 'liderlamp'), 
                        '<a href="'. get_permalink( get_option('woocommerce_myaccount_page_id') ) .'">',
                        '</a>' 
                ) 
            );

    }

    if ( !is_cart() && !is_checkout() && WC()->cart->get_cart_contents_count() > 0 ) {

        $r .= wpautop( 
                sprintf( 
                    __( 'Mira tu %s carrito %s', 'liderlamp'), 
                        '<a href="'. wc_get_cart_url() .'">',
                        '</a>' 
                ) 
            );

    }

    return $r;
    
}

// add_filter ( 'woocommerce_shipping_method_add_rate_args', 'liderlamp_iva_servicios_para_mrw_gratis' );
function liderlamp_iva_servicios_para_mrw_gratis( $args ) {

    if ( $args['id'] != 'mrw' ) return $args;
    if ( isset( $args['cost'] ) && $args['cost'] != 0 ) return $args;

    $args['cost'] = 0.000001;

    return $args;

}

add_filter( 'woocommerce_single_product_carousel_options', 'liderlamp_update_woo_flexslider_options' );
function liderlamp_update_woo_flexslider_options( $options ) {

    $options['directionNav'] = true;
    $options['controlNav'] = true;

    return $options;
}

add_action( 'woocommerce_single_product_summary', 'liderlamp_youtube_shorts', 30 );
function liderlamp_youtube_shorts() {

    $urls = get_post_meta( get_the_ID(), 'youtube_shorts', true);

    if ( $urls ) {

        $video_ids = array();


        echo '<div class="wrapper stories-wrapper">';

            echo '<p class="stories-title">'. __( 'Vídeos','liderlamp' ) . '</p>';

                echo '<div class="stories">';

                $urls_array = explode( PHP_EOL, $urls );

                foreach( $urls_array as $url ) {
                    
                    $url_components = parse_url( $url );
                    $video_id = false;

                    if ( isset( $url_components['query'] ) ) {

                        parse_str( $url_components['query'], $params );

                        if ( isset( $params['v'] ) ) {
                            $video_id = $params['v'];
                        }

                    }
                    if ( !$video_id ) {
                        $video_id = rtrim( basename( $url ) );
                    }

                    if ( $video_id ) {
                        $video_ids[] = $video_id;
                    }

                }

                // $titulos = get_youtube_titles( $video_ids );

                foreach( $urls_array as $index => $url ) {

                    $video_id = basename( $url );
                    $link_url = 'https://www.youtube.com/watch?v=' . $video_id;
                    // $titulo = $titulos[$index];


                    echo '<a class="story" href="'.$link_url.'" rel="lightbox">';
                        echo '<div class="story-image-wrapper">';
                            echo '<img class="story-image" src="https://img.youtube.com/vi/'.$video_id.'/0.jpg" alt="'.get_the_title().'" />';
                        echo '</div>';
                        // echo '<p class="story-title">'.$titulo.'</p>';
                    echo '</a>';

                }

            echo '</div>';

        echo '</div>';

    }

}

add_action( 'woocommerce_shop_loop_item_title', 'liderlamp_sticker_producto_variable', 5 );
// add_action( 'woocommerce_before_single_product_summary', 'liderlamp_sticker_producto_variable', 12 );
function liderlamp_sticker_producto_variable() {

    global $product;

    if( !$product->is_type( 'variable' ) ) return false;

    $product_attr = get_post_meta( get_the_ID(), '_product_attributes', true );

    if ( $product_attr ) {

        if ( count( $product_attr ) == 1 ) {

            $first_attr = reset( $product_attr );
            $nombre = $first_attr['name'];
            if ( $first_attr['is_taxonomy'] ) {
                $nombre = get_taxonomy( $first_attr['name'] )->labels->name;
            }
            // echo '<pre>'; print_r( $first_attr ); echo '</pre>';
            $texto = sprintf( __( 'Elige %s', 'liderlamp' ), strtolower( $nombre ) );

        } else {

            $texto = $product->add_to_cart_text();

        }

        echo '<span class="variations-label">' . $texto . '</span>';

    }
}

/**
 * Adds " - sold out" to the drop-down list for out-of-stock variatons.
 *
 * Make sure you check "Manage Stock" on each variation.
 * Set the stock level to zero and in the front-end drop-down variations list
 *
 * @param $option
 * @param $_
 * @param $attribute
 * @param $product
 *
 * @return mixed|string
 */
function liderlamp_add_sold_out_label_to_wc_product_dropdown( $option, $_, $attribute, $product ){

    if ( !is_singular( 'product' ) ) return $option;

    if( is_product() ) {
        global $product;

        $sold_out_text = ' (' . __( 'agotado - avísame si vuelve', 'liderlamp' ) . ')';
        $variations    = $product->get_available_variations();
        $attributes = $product->get_attributes();
        $attribute_slug = liderlamp_wc_get_att_slug_by_title( $attribute, $attributes );
        if( empty( $attribute_slug ) ) return $option;
    
        foreach ( $variations as $variation ) {
            if ( $variation['attributes']['attribute_' . $attribute_slug] === $option && $variation['is_in_stock'] === FALSE ) {
                $option .= $sold_out_text;
            }
        }
    }
    return $option;
  }

// Si no lo hago así, la página de la lista de deseos se rompe
add_action( 'wp_head', 'smn_add_sold_out_label_filter' );
function smn_add_sold_out_label_filter() {
    if ( !is_singular('product') ) return;
    add_filter( 'woocommerce_variation_option_name', 'liderlamp_add_sold_out_label_to_wc_product_dropdown', 10, 4 );
}

  /**
 * Returns the slug of the WooCommerce attribute taxonomy
 *
 * @param $attribute_title
 * @param $attributes
 *
 * @return int|string
 */
function liderlamp_wc_get_att_slug_by_title( $attribute_title, $attributes ){
	if ( empty( $attribute_title ) || empty( $attributes )) __return_empty_string();
	$att_slug = '';

	foreach( $attributes as $tax => $tax_obj ){

		if( $tax_obj[ 'name'] === $attribute_title ){
			$att_slug = $tax;
		}
	}

	return $att_slug;
}

// add_action( 'wp_enqueue_scripts', 'liderlamp_woocommerce_enqueue_assets' );
function liderlamp_woocommerce_enqueue_assets() {
    
    wp_enqueue_script( 'ajax-quiero-que-me-lo-regalen',  get_stylesheet_directory_uri() . '/js/ajax-quiero-que-me-lo-regalen.js', array( 'jquery' ), '1.0', true );
    
    wp_localize_script( 'ajax-quiero-que-me-lo-regalen', 'ajaxquieroquemeloregalen', array(
        'ajaxurl' => admin_url( 'admin-ajax.php' )
    ));
}



// add_action( 'wp_ajax_nopriv_ajax_quiero_que_me_lo_regalen', 'liderlamp_quiero_que_me_lo_regalen' );
// add_action( 'wp_ajax_ajax_quiero_que_me_lo_regalen', 'liderlamp_quiero_que_me_lo_regalen' );

function liderlamp_quiero_que_me_lo_regalen() {
    dynamic_sidebar( 'quiero-que-me-lo-regalen' );
    die();
}

// add_action( 'wp_footer', 'liderlamp_ocultar_precio_cuando_se_selecciona_una_variacion_de_producto', 999 );
function liderlamp_ocultar_precio_cuando_se_selecciona_una_variacion_de_producto() {
    ?>
    <script>
        jQuery(document).ready(function($) {
            $('.variations_form').on('woocommerce_variation_has_changed', function() {
                // Ejecutar una acción cuando cambia la variación
                $('.woocommerce-variation-description').each( function() {
                    var $description = $(this);
                    // Reemplazar la etiqueta <p> por <h1>
                    $description.replaceWith(function() {
                        return $(this).html().replace(/^<p( [^>]*)?>|<\/p>$/g, '<h1$1>');
                    });
                });

            });
        });
        
    </script>
    <?php
}

add_filter( 'get_post_metadata', 'liderlamp_custom_google_product_type', 10, 5 );
function liderlamp_custom_google_product_type( $value, $object_id, $meta_key, $single, $meta_type ) {

    switch ( $meta_key ) {
        case 'g_product_type_custom':
            $product_type = smn_get_custom_google_product_type( $object_id );
            $value = $product_type;
            break;

        case 'g_product_highlight':
            global $current_screen;
            if ( $current_screen && $current_screen->parent_base == 'edit' || !is_admin() ) {
                return $value;
            }
            remove_filter( 'get_post_metadata', 'liderlamp_custom_google_product_type', 10, 5 );
            $post_meta = get_post_meta( $object_id, $meta_key, $single );
            add_filter( 'get_post_metadata', 'liderlamp_custom_google_product_type', 10, 5 );
            if ( is_array($post_meta) ) $post_meta = reset( $post_meta );
            $post_meta = explode( PHP_EOL, $post_meta );
            $post_meta = implode( "</g:product_highlight><g:product_highlight>", $post_meta );
            $post_meta = html_entity_decode( $post_meta );
            $value = $post_meta;
            break;
        
        default:
            return $value;
            break;
    }

    
    return $value;
    
}

add_action( 'save_post_product', 'liderlamp_save_custom_google_product_type', 10, 3 );
function liderlamp_save_custom_google_product_type( $object_id, $post, $update ) {

    $product_type = smn_get_custom_google_product_type( $object_id );
    update_post_meta( $object_id, 'g_product_type_custom', $product_type );

}

function smn_get_custom_google_product_type( $object_id ) {

    $exclude_cats = array(
        NOVEDADES_ID,
        BAJA_ID,
        SIN_DESCUENTO_ID,
        VENTA_PRIVADA_ID,
        SIN_CATEGORIA_ID
    );

    $estilos_ids = get_terms( array(
        //'taxonomy'      => 'product_cat',
        'hide_empty'    => false,
        'fields'        => 'ids',
        'meta_query'    => array(
            array(
                'key'       => 'es_estilo',
                'value'     => 1,
                'type'      => 'BINARY',
            )
        )
    ));

    $exclude_cats = array_merge( $exclude_cats, $estilos_ids );

    $product_cats = wc_get_product_term_ids( $object_id, 'product_cat' );
    $product_cats_filtered = array_diff( $product_cats, $exclude_cats ); 
    
    $primary_cat_id = get_post_meta( $object_id, 'rank_math_primary_product_cat', true );
    if ( in_array( $primary_cat_id, $estilos_ids ) ) {
        $primary_cat_id = false;
    }

    if ( !$primary_cat_id ) {
        $primary_cat_id = reset( $product_cats_filtered );
    }

    if( intval( $primary_cat_id ) ) {

        $ancestors = get_ancestors( $primary_cat_id, 'product_cat', 'taxonomy' );
        $estilos_intersect = array_intersect( $estilos_ids, $product_cats );

        $ancestors = array_reverse( $ancestors );
        if ( count($ancestors) > 1 ) {
            // array_pop($ancestors);
            $ancestors = array( $ancestors[0] );
        }
        $ancestors[] = $primary_cat_id;

        if ( $estilos_intersect ) {
            $primer_estilo = array_shift( $estilos_intersect );
            $ancestors[] = $primer_estilo;
        }

        $ancestors_array = [];
        foreach( $ancestors as $term_id ) {
            $term = get_term( $term_id );
            if ( $term_id == $primer_estilo ) {
                $name = str_replace(
                    array(
                        'Lámparas ',
                        'Lámparas de ',
                        'Estilo '
                    ), 
                    array(
                        '',
                        '',
                        ''
                    ), 
                    $term->name
                );
                // $ancestors_array[] = sprintf( __( 'Estilo %s', 'liderlamp' ), ucwords($name) );
                $ancestors_array[] = ucwords($name);
            } else {
                $ancestors_array[] = $term->name;
            }
        }

        $product_type = implode( ' &gt; ', $ancestors_array );
        return $product_type;
        
    }

}


add_action( 'woocommerce_single_product_summary', 'liderlamp_preview_google_product_type', 25 );
function liderlamp_preview_google_product_type( $title ) {
    if ( !current_user_can( 'manage_options' ) || !is_main_query() ) return false;

    $product_highlight = get_post_meta( get_the_ID(), 'g_product_highlight', true );
    if ( $product_highlight ) {
        echo '<ul>';
            echo $product_highlight;
            // echo str_replace( 'g:product_highlight', 'li', $product_highlight );
        echo '</ul>';
    }

    $gpt = get_post_meta( get_the_ID(), 'g_product_type_custom', true );
    if ( $gpt ) {
       echo '<div class="shadow-sm mb-1 p-1 border"><b>Google Feed Product Type</b> (solo visible para administradores web): <br>' . $gpt . '</div>';
    }

}

add_filter( 'acf/load_field', 'smn_acf_read_only_field' );
function smn_acf_read_only_field( $field ) {

    if( 'g_product_type_custom' === $field['name'] ) {
      $field['disabled'] = true;	
    }
  
    return $field;
  
}

// add_action( 'wp_head', 'smn_update_post_meta' );
function smn_update_post_meta() {

    if ( is_admin() ) return false;

    if ( current_user_can( 'manage_options' ) ) :

        echo '<pre>';

            print_r( 'NO ASUSTARSE, ESTO SOLO LO VEN LOS ADMINISTRADORES' );


            $args = array(
                'post_type'         => 'product',
                'posts_per_page'    => -1,
            );

            $q = new WP_Query($args);

            if ( $q->have_posts() ) {

                while ( $q->have_posts() ) { $q->the_post();

                    $product_type = get_post_meta( get_the_ID(), 'g_product_type_custom', true );
                    print_r( get_the_ID() . ' - ' . get_the_title() . ' - ' . $product_type . '<br>' );

                    if ( !$product_type ) {
                        // update_post_meta( get_the_ID(), 'g_product_type_custom', '1' );
                    }


                }

            }

            wp_reset_postdata();

        echo '</pre>';
    endif;

}

add_filter( 'manage_product_posts_columns', 'smn_filter_posts_columns' );
function smn_filter_posts_columns( $columns ) {
    $columns['smn_main_product_cat'] = __( 'Cat. Principal' );
    $columns['g_product_type'] = __( 'Google Product Type' );
    return $columns;
}


add_action( 'manage_product_posts_custom_column', 'smn_product_column', 10, 2);
function smn_product_column( $column, $post_id ) {
  
    switch ($column) {
        case 'main_product_cat':
        case 'smn_main_product_cat':
                $main_cat_id = get_post_meta( $post_id, 'rank_math_primary_product_cat', true );
                if ( $main_cat_id ) {
                    $term = get_term( $main_cat_id );
                    echo $term->name;
                }
            break;
        
        case 'g_product_type':
            echo smn_get_custom_google_product_type( $post_id );
            break;

        default:
            # code...
            break;
    }

}
  
function liderlamp_mostrar_productos_rebajados_en_coleccion_rebajas( $query ) {
    if ( ! is_admin() && $query->is_main_query() ) {

        if ( $query->is_tax( 'coleccion', COLECCION_REBAJAS_ID ) ) {
            $sale_products_ids = wc_get_product_ids_on_sale();
            $products_count = 0;
            $product_variations_count = 0;
            foreach( $sale_products_ids as $id) {

                if ( 'product' == get_post_type( $id ) ) {
                    $products_count++;
                } else {
                    $product_variations_count++;
                }
            }
            // echo $products_count . ' productos - ' . $product_variations_count . ' variaciones';

            unset($query->query['coleccion']);
            unset($query->query_vars['coleccion']);
            unset($query->tax_query->queries[0]);
            $query->query_vars['post__in'] = array_merge( array(0), $sale_products_ids);

        }
    }
}
add_action( 'pre_get_posts', 'liderlamp_mostrar_productos_rebajados_en_coleccion_rebajas' );

// Insert a bootsrap4 collapse above the add to cart button for a calculator
add_action( 'woocommerce_before_add_to_cart_form', 'smn_add_calculator', 10 );
function smn_add_calculator() {

    $mostrar_calculadora_rollos = get_field( 'mostrar_calculadora_rollos' );
    if ( !$mostrar_calculadora_rollos ) return false;
    ?>

    <div class="woocommerce-tabs">

        <div class="card">

            <div class="card-header" id="tab-title-calculator">

                <p class="mb-0">

                    <button class="btn btn-link collapsed" id="tab-button-calculator" data-toggle="collapse" data-target="#tab-calculator" aria-expanded="false" aria-controls="tab-calculator">

                        <?php echo __( '¿Cuántos rollos necesito?', 'liderlamp' ); ?>

                    </button>

                </p>

            </div> <!-- .card-header -->

            <div class="collapse collapse--calculator entry-content" id="tab-calculator" role="tabpanel" aria-labelledby="tab-title-calculator">

               
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-6 mb-1">
                            <label><?php echo __( 'Longitud (metros)', 'liderlamp' ); ?></label>: 
                        </div>
                        <div class="col-6 mb-1">
                            <input type="number" class="form-control-sm" id="longitud" name="longitud" placeholder="Ej: 10" oninput="calcular()">
                        </div>
                        <div class="col-6 mb-1">
                            <?php echo __( 'Altura (metros)', 'liderlamp' ); ?>: 
                        </div>
                        <div class="col-6 mb-1">
                            <input type="number" class="form-control-sm" id="altura" name="altura" placeholder="Ej: 2,5" oninput="calcular()">
                        </div>
                        <div class="col-6 mb-1">
                            <?php echo __( 'Rollos necesarios', 'liderlamp' ); ?>: 
                        </div>
                        <div class="col-6 mb-1">
                            <input type="text" class="form-control-sm" id="resultado" name="resultado" disabled>
                        </div>
                        <?php if ( current_user_can( 'manage_options' ) ) { ?>
                            <div class="col-6 mb-1">
                                <?php echo __( 'Resultado exacto (solo admins)', 'liderlamp' ); ?>: 
                            </div>
                            <div class="col-6 mb-1">
                                <input type="text" class="form-control-sm" id="resultado-exacto" name="resultado-exacto" disabled>
                            </div>
                       <?php } ?>

                    </div>

                    <script>
                    function calcular() {
                        var longitud = document.getElementById('longitud').value * 100;
                        var altura = document.getElementById('altura').value * 100;
                        var resultado = Math.ceil( longitud / (1000 / ( altura + 52 ) * 54 ) );
                        document.getElementById('resultado').value = resultado;

                        <?php if ( current_user_can( 'manage_options' ) ) { ?>
                            var resultadoExacto = longitud / (1000 / ( altura + 52 ) * 54 );
                            document.getElementById('resultado-exacto').value = resultadoExacto;
                       <?php } ?>
                    }
                    </script>


                </div> <!-- .card-body -->

            </div> <!-- .collapse -->

        </div>
        
    </div>

<?php
}

add_action( 'admin_menu', 'liderlamp_add_submenu_page' );
function liderlamp_add_submenu_page() {
    add_submenu_page(
        'edit.php?post_type=product',
        'Productos Baja',
        'Productos Baja',
        'manage_options',
        'liderlamp-product-options',
        'liderlamp_product_options_page'
    );
}

function liderlamp_product_options_page() {
    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => -1,
        'tax_query'      => array(
            array(
                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => 'productos-baja',
            ),
        ),
    );

    $products = new WP_Query( $args );

    echo '<h1>Redirecciones de los productos en la categoría "productos-baja" a sus categorías de producto primarias</h1>';

    echo '<p>Si el producto no tiene establecida una categoría primaria, se redirige a la primera categoría que no sea "productos-baja" o "novedades".</p>';
    if ( $products->have_posts() ) {

        echo '<p>Estas redirecciones hay que copiarlas al archivo .htaccess de la web.</p>';

        echo '<p>Para editar el archivo .htaccess, accede a la raíz de la web mediante FTP y edita el archivo .htaccess con un editor de texto. Al final del archivo, pega las redirecciones.</p>';

        echo '<p>También puedes editar el archivo .htaccess <a href="https://liderlamp.es/wp-admin/admin.php?page=rank-math-options-general" target="_blank">desde aquí</a>.</p>';
        
        echo '<p>Copia las siguientes líneas, incluida la que empieza por # Redirecciones...</p>';
        
        echo '<textarea readonly style="width:98%; margin-right: 1rem; height: 300px;">';

            // fecha actual en formato DD/MM/AAAA
            echo '# Redirecciones ' . date('d/m/Y') . '&#13;&#10;&#13;&#10;';

            while ( $products->have_posts() ) {
                $products->the_post();
                // echo '<li><a href="' . get_permalink() . '">' . get_the_title() . '</a></li>';
                // get rank math primary category
                $destination = get_home_url() . '/';
                $primary_cat_id = get_post_meta( get_the_ID(), 'rank_math_primary_product_cat', true );
                if ( $primary_cat_id ) {
                    $primary_cat = get_term( $primary_cat_id );
                    if ( !is_wp_error( $primary_cat ) ) {
                        $destination = get_term_link( $primary_cat );
                    }
                } else {
                    $cats = wp_get_object_terms( get_the_ID(), 'product_cat' );
                    foreach( $cats as $cat ) {
                        if ( 
                            $cat->slug != 'productos-baja' && 
                            $cat->slug != 'novedades' &&
                            $cat->slug != 'sin-categoria' &&
                            $cat->slug != 'sin-descuento' &&
                            $cat->slug != 'venta-privada'
                        ) {
                            $destination = get_term_link( $cat );
                            break;
                        }
                    }
                }

                $origin = str_replace( get_home_url(), '', get_permalink() );
                echo 'Redirect 301 ' . $origin . ' ' . $destination . '&#13;&#10;';
            }

            wp_reset_postdata();

        echo '</textarea>';

    } else {
        echo 'No se encontraron productos en la categoría "productos-baja".';
    }
}
