<?php 

add_filter( 'render_block', 'sumun_bootstrap_buttons', 10, 2 );
function sumun_bootstrap_buttons( $block_content, $block ) {


    // if ( current_user_can( 'manage_options' ) ) {
    //     echo '<pre>'; 
    //         print_r( $block ); 
    //     echo '</pre>';
    // }
    
    

    // if ( $block['blockName'] !== 'core/button' ) return $block_content;

    if ( $block['blockName'] == 'core/button' && isset( $block['attrs']['backgroundColor'] ) ) {
        $block_content = str_replace( 'wp-block-button__link', 'btn btn-' . $block['attrs']['backgroundColor'], $block_content);
        return $block_content;
    }

    global $post;

    if ( $post && $block['blockName'] == 'core/button' && ( 'banner' == $post->post_type || 'slide' == $post->post_type ) ) {
        $block_content = str_replace( 'wp-block-button__link', 'btn btn-outline-light', $block_content);
        return $block_content;
    }

    if ( $block['blockName'] == 'core/button' && isset( $block['attrs']['className'] ) && strpos( $block['attrs']['className'], 'is-style-outline') !== false ) {
        $block_content = str_replace( 'is-style-outline', '', $block_content);
        $block_content = str_replace( 'wp-block-button__link', 'btn btn-outline-dark', $block_content);
        return $block_content;
    }

    if ( 
        $block['blockName'] == 'woocommerce/handpicked-products' ||
        $block['blockName'] == 'woocommerce/product-best-sellers' ||
        $block['blockName'] == 'woocommerce/product-new' ||
        $block['blockName'] == 'woocommerce/product-on-sale' ||
        $block['blockName'] == 'woocommerce/product-category'
    ) {
        $block_content = str_replace( 'wp-block-button__link', 'btn btn-outline-primary add_to_cart_button single_add_to_cart_button', $block_content);
        return $block_content;
    }

    if ( 
        $block['blockName'] == 'woocommerce/featured-product' ||
        $block['blockName'] == 'woocommerce/featured-category'
    ) {
        $block_content = str_replace( 'wp-block-button__link', 'btn btn-outline-light', $block_content);
        return $block_content;
    }

    // $block_content = str_replace( 'wp-block-button__link', 'btn btn-dark', $block_content);
    return $block_content;

}


