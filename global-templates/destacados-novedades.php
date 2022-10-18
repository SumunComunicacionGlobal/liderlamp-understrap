<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$args_destacados = array(
    'post_type'         => 'product',
    'posts_per_page'    => 6,
    'tax_query'         => array(
                                array(
                                    'taxonomy'      => 'product_visibility',
                                    'field'         => 'name',
                                    'terms'         => 'featured',
                                    'operator'      => 'IN',
                                ),
    ),
);

$args_slides = array(
    'post_type'         => 'slide',
    'posts_per_page'    => -1,
);

echo '<div class="row">';

    $q_slides = new WP_Query( $args_slides );

    if ( $q_slides->have_posts() ) {

        echo '<div class="col-md-7 mb-2">';

            echo '<div class="slick-slider slider-destacados slider-campaigns">';

                while ( $q_slides->have_posts() ) { $q_slides->the_post();

                    get_template_part( 'loop-templates/content', 'slide' );

                }
        
            echo '</div>';
        
        echo '</div>';

    }

    wp_reset_postdata();

    $q_destacados = new WP_Query( $args_destacados );

    if ( $q_destacados->have_posts() ) {

        $term_destacados = get_term_by( 'slug', 'featured', 'product_visibility' );
        $total_destacados = $term_destacados->count;

        echo '<div class="col-md-5 mb-2">';

            echo '<div class="slick-slider slider-destacados">';

                while ( $q_destacados->have_posts() ) { $q_destacados->the_post();

                    $current_destacado_count = $q_destacados->current_post + 1;

                    echo '<a href="'.get_the_permalink().'" title="'.get_the_title().'" class="destacado product featured">';

                        echo '<div class="d-flex justify-content-between align-items-center">';

                            echo '<span class="destacados-count">'. $current_destacado_count . '/' . $total_destacados . '</span>';

                            echo '<span class="label-destacados lead">' . __( 'Destacados', 'liderlamp' ) . '</span>';

                        echo '</div>';

                        echo '<div class="wp-post-image-wrapper">';

                            if ( has_post_thumbnail() ) {
                                the_post_thumbnail( 'woocommerce_thumbnail' );
                            } else {
                                echo wc_placeholder_img( 'woocommerce_thumbnail' );
                            }

                        echo '</div>';

                        echo '<div class="destacado-caption">';

                            the_title( '<p class="titulo-destacado">', '</p>' );

                            $product = wc_get_product( get_the_ID() );
                            echo '<p class="destacado-precio">'.$product->get_price_html().'</p>';

                        echo '</div>';

                    echo '</a>';

                }

            echo '</div>';

        echo '</div>';

    }

    wp_reset_postdata();

echo '</div>';