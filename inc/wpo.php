<?php 

add_action( 'wp_print_scripts', 'liderlamp_dequeue_js', 10000 );
function liderlamp_dequeue_js() {
    global $post;

    if ( is_front_page() || is_archive() || is_search() ) {
        wp_dequeue_script( 'contact-form-7' );
    }

    if ( class_exists( 'woocommerce') && ( is_cart() || is_checkout() || is_account_page() )  ) {
        wp_dequeue_script( 'contact-form-7' );
    }

    if ( !is_singular( 'product' ) ) {
        wp_dequeue_script( 'bis-out-of-stock-notify' );
    }

    if ( is_front_page() ) {
        wp_dequeue_script( 'berocket_lmp_js' );
        wp_dequeue_style( 'berocket_lmp_style' );
    }

    if ( class_exists( 'woocommerce') && ( is_cart() || is_checkout() || is_account_page() )  ) {
        wp_dequeue_script( 'berocket_lmp_js' );
        wp_dequeue_style( 'berocket_lmp_style' );
    }

    wp_dequeue_script( 'the_champ_combined_script' );

}


add_action( 'wp_print_styles', 'liderlamp_dequeue_css', 10000 );
function liderlamp_dequeue_css() {

    wp_dequeue_style( 'font-awesome' );

    wp_dequeue_style( 'the_champ_sharing_svg' );
    wp_dequeue_style( 'the_champ_sharing_svg_hover' );

    if ( is_front_page() || is_archive() || is_search() ) {
        wp_dequeue_style( 'contact-form-7' );
    }

    if ( class_exists( 'woocommerce') && ( is_cart() || is_checkout() || is_account_page() )  ) {
        wp_dequeue_style( 'contact-form-7' );
    }
}

add_action( 'after_setup_theme', 'liderlamp_init_remove_scripts', 100 );
function liderlamp_init_remove_scripts() {



}

/** REMOVE DASHICONS FROM ADMIN BAR FOR NON LOGGED IN USERS **/
add_action( 'wp_print_styles', function() {
    if ( ! is_admin_bar_showing() && ! is_customize_preview() ) {
      wp_deregister_style( 'dashicons' );
    }
}, 100);

/** CONTROL HEARTBEAT API **/
function wpo_tweaks_control_heartbeat( $settings ) {
    $settings['interval'] = 60;
    return $settings;
}
add_filter( 'heartbeat_settings', 'wpo_tweaks_control_heartbeat' );

function wpo_tweaks_remove_script_version( $src ) {
    $parts = explode( '?ver', $src );

    return $parts[0];
}
// add_filter( 'script_loader_src', 'wpo_tweaks_remove_script_version', 15, 1 );
// add_filter( 'style_loader_src', 'wpo_tweaks_remove_script_version', 15, 1 );

/** REMOVE GRAVATAR QUERY STRINGS **/
function wpo_tweaks_avatar_remove_querystring( $url ) {
    $url_parts = explode( '?', $url );
    return $url_parts[0];
}
add_filter( 'get_avatar_url', 'wpo_tweaks_avatar_remove_querystring' );

function wpo_tweaks_clean_header() {
    remove_action( 'wp_head', 'wp_generator' ); // REMOVE WORDPRESS GENERATOR VERSION.
    remove_action( 'wp_head', 'wp_resource_hints', 2 ); // REMOVE S.W.ORG DNS-PREFETCH.
    remove_action( 'wp_head', 'wlwmanifest_link' ); // REMOVE wlwmanifest.xml.
    remove_action( 'wp_head', 'rsd_link' ); // REMOVE REALLY SIMPLE DISCOVERY LINK.
    remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 ); // REMOVE SHORTLINK URL.
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 ); // REMOVE EMOJI'S STYLES AND SCRIPTS.
    remove_action( 'wp_print_styles', 'print_emoji_styles' ); // REMOVE EMOJI'S STYLES AND SCRIPTS.
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' ); // REMOVE EMOJI'S STYLES AND SCRIPTS.
    remove_action( 'admin_print_styles', 'print_emoji_styles' ); // REMOVE EMOJI'S STYLES AND SCRIPTS.
    remove_action( 'wp_head', 'index_rel_link' ); // REMOVE LINK TO HOME PAGE.
    remove_action( 'wp_head', 'feed_links_extra', 3 ); // REMOVE EVERY EXTRA LINKS TO RSS FEEDS.
    remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10 ); // REMOVE PREV-NEXT LINKS FROM HEADER -NOT FROM POST-.
    remove_action( 'wp_head', 'adjacent_posts_rel_link', 10, 0 ); // REMOVE PREV-NEXT LINKS.
    remove_action( 'wp_head', 'start_post_rel_link', 10, 0 ); // REMOVE RANDOM LINK POST.
    remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 ); // REMOVE PARENT POST LINK.

    add_filter( 'the_generator', '__return_false' ); // REMOVE GENERATOR NAME FROM RSS FEEDS.
}
add_action( 'after_setup_theme', 'wpo_tweaks_clean_header' );
