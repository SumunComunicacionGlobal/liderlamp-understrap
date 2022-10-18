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
function liderlamp_set_category_display( $value = null, $object_id, $meta_key, $single ){

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
    $items['tinv_wishlist'] = __( 'Tu lista de deseos', 'liderlamp' );
    $items['customer-logout'] = __( 'Cerrar sesión', 'liderlamp' );

    $my_items = array(
    //  endpoint   => label
        '2nd-item' => __( '2nd Item', 'my_plugin' ),
        '3rd-item' => __( '3rd Item', 'my_plugin' ),
    );

    // $my_items = array_slice( $items, 0, 1, true ) +
    //     $my_items +
    //     array_slice( $items, 1, count( $items ), true );

    return $items;
}

add_filter( 'woocommerce_add_to_cart_fragments', 'wc_refresh_mini_cart_count');
function wc_refresh_mini_cart_count($fragments){
    ob_start();
    $items_count = WC()->cart->get_cart_contents_count();
    ?>
    <span class="cart-count"><?php echo $items_count ? $items_count : ''; ?></span>
    <?php
        $fragments['.cart-count'] = ob_get_clean();
    return $fragments;
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

                        <?php echo '<div class="slider-destacados">';

                            echo wp_get_attachment_image( $thumbnail_id, 'woocommerce_single', false, '' ); ?>
                            
                            <?php if ( $galeria ) {

                                    foreach ( $galeria as $img_id ) {
                                        echo wp_get_attachment_image( $img_id, 'woocommerce_single', false, '' ); 
                                    }

                            } 

                        echo '</div>'; ?>

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

add_filter( 'woocommerce_product_tabs', 'quiero_que_me_lo_regalen_tab' );
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
    echo do_shortcode( '[contact-form-7 id="8899" title="Quiero que me lo regalen"]' );
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
                    $video_ids[] = rtrim( basename( $url ) );
                }

                $titulos = get_youtube_titles( $video_ids );

                foreach( $urls_array as $index => $url ) {

                    $video_id = basename( $url );
                    $link_url = 'https://www.youtube.com/watch?v=' . $video_id;
                    $titulo = $titulos[$index];


                    echo '<a class="story" href="'.$link_url.'" rel="lightbox">';
                        echo '<div class="story-image-wrapper">';
                            echo '<img class="story-image" src="https://img.youtube.com/vi/'.$video_id.'/0.jpg" alt="'.get_the_title().'" />';
                        echo '</div>';
                        echo '<p class="story-title">'.$titulo.'</p>';
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
  add_filter( 'woocommerce_variation_option_name', 'liderlamp_add_sold_out_label_to_wc_product_dropdown', 1, 4 );

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