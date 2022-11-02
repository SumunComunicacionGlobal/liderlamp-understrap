<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define('EDITORIALES_ID', 567);

require_once( 'inc/wp-bootstrap-collapse-navwalker.php' );
require_once( 'inc/liderlamp-widgets.php' );
require_once( 'inc/liderlamp-customizer.php' );
// require_once( 'inc/post-types.php' );
require_once( 'inc/blocks.php' );
require_once( 'inc/shortcodes.php' );
require_once( 'inc/wpo.php' );
require_once( 'inc/liderlamp-custom-comments.php' );

if ( class_exists( 'woocommerce') ) {
    require_once( 'inc/liderlamp-woocommerce.php' );
}

add_theme_support( 'align-wide' );
add_theme_support( 'align-full' );

function understrap_remove_scripts() {
    wp_dequeue_style( 'understrap-styles' );
    wp_deregister_style( 'understrap-styles' );

    wp_dequeue_script( 'understrap-scripts' );
    wp_deregister_script( 'understrap-scripts' );

    // Removes the parent themes stylesheet and scripts from inc/enqueue.php
}
add_action( 'wp_enqueue_scripts', 'understrap_remove_scripts', 20 );

add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );
function theme_enqueue_styles() {

	// Get the theme data
	$the_theme = wp_get_theme();

    // echo '<link rel="preconnect" href="https://fonts.googleapis.com">';
    // echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>';
    // echo '<link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@100;400;700;800&display=swap" rel="stylesheet">';

    wp_enqueue_style( 'block-styles', get_stylesheet_directory_uri() . '/css/blocks.css', array(), filemtime( get_stylesheet_directory() . '/css/blocks.css' ) );
    wp_enqueue_style( 'child-understrap-styles', get_stylesheet_directory_uri() . '/css/child-theme.min.css', array(), $the_theme->get( 'Version' ) );
    wp_enqueue_script( 'jquery');
    wp_enqueue_script( 'slick', get_stylesheet_directory_uri() . '/js/slick.min.js', array('jquery'), '1.8.1', true);
    // wp_enqueue_script( 'scrollbooster', get_stylesheet_directory_uri() . '/js/scrollbooster.min.js', array(), false, true );
    wp_enqueue_script( 'child-understrap-scripts', get_stylesheet_directory_uri() . '/js/child-theme.min.js', array(), $the_theme->get( 'Version' ), true );
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }

}

// add_action('wp_head', 'show_template');
function show_template() {
    if ( !current_user_can('administrator') ) return false;
    
    global $template;
    if (is_user_logged_in()){
        print_r($template);
    }

}

add_action( 'wp_footer', 'script_pinterest' );
function script_pinterest() {

    if ( ( class_exists( 'woocommerce') && ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() ) ) || is_front_page() ) return;

    echo '<script async defer src="//assets.pinterest.com/js/pinit.js"></script>';
}

function be_gutenberg_scripts() {
    wp_enqueue_script( 'sumun-editor', get_stylesheet_directory_uri() . '/js/editor.js', array( 'wp-blocks', 'wp-dom-ready', 'wp-edit-post' ), filemtime( get_stylesheet_directory() . '/js/editor.js' ), true );
    wp_enqueue_style(
    'sumun-blocks',
    get_stylesheet_directory_uri() . '/css/blocks.css',
    null,
    filemtime( get_stylesheet_directory() . '/css/blocks.css' ) );
}
add_action( 'enqueue_block_editor_assets', 'be_gutenberg_scripts' );

// add_action( 'wp_enqueue_scripts', 'deregistrar_estilos_integrados_en_child', 11 );
function deregistrar_estilos_integrados_en_child() {

}

register_nav_menus( array(
    'tienda' => __( 'Tienda', 'liderlamp-admin' ),
    'trastienda' =>  __( 'Trastienda', 'liderlamp-admin' ),
    'footer' =>  __( 'Footer', 'liderlamp-admin' ),
) );

// Register Custom Navigation Walker
require_once('inc/wp_bootstrap4-mega-navwalker.php');

function add_child_theme_textdomain() {
    load_child_theme_textdomain( 'understrap-child', get_stylesheet_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'add_child_theme_textdomain' );


add_filter( 'body_class', 'custom_class' );
function custom_class( $classes ) {

    if ( is_tax( 'product_cat') ) {
        $term = get_queried_object();
        if ( $term->parent == 0 ) {
            $classes[] = 'main-product-cat';
        } else {
            $classes[] = 'child-product-cat';
        }
    }

    if ( is_page_template( 'page-templates/trastienda.php' ) ) {
        $classes[] = 'archive';
    }

    return $classes;
}

function liderlamp_top_bar() {

    if ( is_active_sidebar( 'top-bar' ) ) {
        echo '<div id="top-bar" class="top-bar">';
            echo '<div class="container-fluid">';
                if( class_exists('woocommerce') ) echo liderlamp_elementos_topbar_cliente();
                dynamic_sidebar( 'top-bar' );
            echo '</div>';
        echo '</div>';
    }

    ?>

    <div id="brand-top-bar" class="navbar d-sm-none">

        <!-- Your site title as branding in the menu -->
        <?php if ( ! has_custom_logo() ) { ?>

                <a class="top-bar-navbar-brand" rel="home" href="<?php echo esc_url( home_url( '/' ) ); ?>" itemprop="url"><?php echo get_logo_svg(); ?></a>

            <?php
        } else {
            the_custom_logo();
        }

        ?>
        <!-- end custom logo -->
    </div>

    <?php

}

// add_action( 'wp_body_open', 'liderlamp_menu' );
add_action( 'liderlamp_after_navbar', 'liderlamp_menu' );
function liderlamp_menu() {
    get_template_part( 'global-templates/menu' );
}

function array_insert_after( array $array, $key, array $new ) {
    $keys = array_keys( $array );
    $index = array_search( $key, $keys );
    $pos = false === $index ? count( $array ) : $index + 1;

    return array_merge( array_slice( $array, 0, $pos ), $new, array_slice( $array, $pos ) );
}

function get_menu_toggler() {
    $r = '<span class="liderlamp-menu-toggle">';
        $r .= '<span class="slot slot-uno"></span>';
        $r .= '<span class="slot slot-dos"></span>';
    $r .= '</span>';

    return $r;
}

function menu_toggler() {
    echo get_menu_toggler();
}

function understrap_all_excerpts_get_more_link( $post_excerpt ) {
    if ( ! is_admin() ) {
        $post_excerpt = wp_trim_words( $post_excerpt, 30 );

        // $post_excerpt = $post_excerpt . '<p><a class="btn btn-outline-primary understrap-read-more-link" href="' . esc_url( get_permalink( get_the_ID() ) ) . '">' . __(
        //     'Read More...',
        //     'understrap'
        // ) . '</a></p>';
    }
    return $post_excerpt;
}

function get_logo_svg() {
    return '
<svg width="304px" height="24px" viewBox="0 0 304 24" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
    <g id="UI-DESKTOP" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <g id="Desktiop-HOME" transform="translate(-568.000000, -36.000000)" fill="#000000">
            <g id="RECURSOS" transform="translate(-1.000000, 36.000000)">
                <g id="LOGO-DEFINITIVO" transform="translate(569.000000, 0.000000)">
                    <path d="M298.924778,8.25550979 C298.924778,6.82014596 298.457692,5.74376761 297.524099,5.02521859 C296.590505,4.30782571 295.337813,3.94884023 293.767758,3.94884023 L288.882434,3.94884023 L288.882434,12.5616013 L293.767758,12.5616013 C295.360358,12.5616013 296.619409,12.1685093 297.540863,11.3823253 C298.464051,10.5961413 298.924778,9.55444758 298.924778,8.25550979 L298.924778,8.25550979 Z M303.179999,8.16995448 C303.179999,9.50935761 302.929691,10.6897897 302.429077,11.7106727 C301.928463,12.7321338 301.246911,13.5830624 300.382109,14.2640364 C299.517884,14.9450105 298.505094,15.4612327 297.345473,15.8127032 C296.184695,16.1647518 294.944721,16.340487 293.625551,16.340487 L288.882434,16.340487 L288.882434,24 L284.729532,24 L284.729532,0.170532553 L294.125009,0.170532553 C295.508924,0.170532553 296.76335,0.357829323 297.887131,0.731844786 C299.010333,1.10643833 299.963581,1.64578365 300.746297,2.34872462 C301.52959,3.05282174 302.13079,3.89218874 302.550473,4.86798179 C302.970157,5.84435292 303.179999,6.9444324 303.179999,8.16995448 L303.179999,8.16995448 Z M254.227329,11.4383987 L246.975938,0.170532553 L242.516656,0.170532553 L242.516656,24 L246.669557,24 L246.669557,6.97853891 L254.125587,18.1446636 L254.260857,18.1446636 L261.7851,6.91032589 L261.7851,24 L265.938001,24 L265.938001,0.170532553 L261.47872,0.170532553 L254.227329,11.4383987 Z M209.189392,14.5363104 L217.393453,14.5363104 L213.274659,5.00440783 L209.189392,14.5363104 Z M205.206446,24 L200.917118,24 L211.402847,0 L215.283474,0 L225.768047,24 L221.342294,24 L218.925934,18.2469832 L207.65749,18.2469832 L205.206446,24 Z M185.905051,20.2211142 L173.377556,20.2211142 L173.377556,0.170532553 L169.224077,0.170532553 L169.224077,24 L185.905051,24 L185.905051,20.2211142 Z M142.248139,11.9829467 C143.749404,11.9829467 144.945444,11.6187586 145.833369,10.8892261 C146.721295,10.1602717 147.165258,9.17985404 147.165258,7.94912927 C147.165258,6.62706843 146.7317,5.62988655 145.867476,4.95758364 C145.002096,4.28528073 143.78351,3.94884023 142.213455,3.94884023 L136.100874,3.94884023 L136.100874,11.9829467 L142.248139,11.9829467 Z M145.734518,14.9785389 L152.169665,24 L147.233471,24 L141.359636,15.6936195 L136.100874,15.6936195 L136.100874,24 L131.947973,24 L131.947973,0.170532553 L142.568972,0.170532553 C143.930342,0.170532553 145.161067,0.346267794 146.26288,0.697738276 C147.362382,1.04978683 148.293663,1.54924489 149.053833,2.19553436 C149.814004,2.84240191 150.398439,3.63725703 150.807139,4.57894357 C151.215261,5.52120818 151.4199,6.57041694 151.4199,7.72772599 C151.4199,8.7266421 151.278272,9.61687983 150.994436,10.3995953 C150.710022,11.1828889 150.318665,11.8754245 149.819785,12.476624 C149.320327,13.0784016 148.724908,13.5888431 148.03295,14.0085266 C147.339837,14.4287882 146.573885,14.7519329 145.734518,14.9785389 L145.734518,14.9785389 Z M114.279644,20.2211142 L100.628947,20.2211142 L100.628947,13.8553364 L112.577787,13.8553364 L112.577787,10.0770287 L100.628947,10.0770287 L100.628947,3.94884023 L114.109112,3.94884023 L114.109112,0.170532553 L96.4748898,0.170532553 L96.4748898,24 L114.279644,24 L114.279644,20.2211142 Z M74.3484356,12.1187947 C74.3484356,10.933738 74.1489992,9.84637618 73.7501265,8.85497507 C73.3518318,7.86357396 72.7951442,7.00281812 72.0789074,6.27386372 C71.3615146,5.54490931 70.4909314,4.97492593 69.4677361,4.56449165 C68.4422285,4.15405737 67.3051521,3.94884023 66.0536166,3.94884023 L61.3434497,3.94884023 L61.3434497,20.2211142 L66.0536166,20.2211142 C67.3051521,20.2211142 68.4422285,20.0222559 69.4677361,19.6233832 C70.4909314,19.2245104 71.3615146,18.6666667 72.0789074,17.9486957 C72.7951442,17.2301467 73.3518318,16.3757497 73.7501265,15.3843486 C74.1489992,14.3929475 74.3484356,13.3044295 74.3484356,12.1187947 L74.3484356,12.1187947 Z M77.8203627,7.35313245 C78.4331238,8.80583857 78.7395043,10.3718477 78.7395043,12.0511598 C78.7395043,13.7310499 78.4331238,15.3022617 77.8203627,16.7659513 C77.2076017,18.2296409 76.3393309,19.4950502 75.2161283,20.5616013 C74.0929258,21.6287304 72.7523665,22.4680974 71.1990751,23.0808584 C69.6440494,23.6936195 67.9364116,24 66.0750054,24 L57.1905485,24 L57.1905485,0.170532553 L66.0750054,0.170532553 C67.9364116,0.170532553 69.6440494,0.471132307 71.1990751,1.07233182 C72.7523665,1.6741094 74.0929258,2.50769564 75.2161283,3.57424669 C76.3393309,4.6419539 77.2076017,5.90100441 77.8203627,7.35313245 L77.8203627,7.35313245 Z M33.973264,24 L38.1273213,24 L38.1273213,0.170532553 L33.973264,0.170532553 L33.973264,24 Z M4.15376834,20.2211142 L16.680685,20.2211142 L16.680685,24 L-0.000289038225,24 L-0.000289038225,0.170532553 L4.15376834,0.170532553 L4.15376834,20.2211142 Z" id="Fill-1"></path>
                </g>
            </g>
        </g>
    </g>
</svg>';
}

function get_logo_serif_svg() {
    return '
<svg width="211px" height="27px" viewBox="0 0 211 27" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
    <defs>
        <polygon id="path-izzpecrmt3-1" points="0 0 210.958761 0 210.958761 27 0 27"></polygon>
    </defs>
    <g id="UI" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <g id="Desktop-HD-Op_4" transform="translate(-614.000000, -87.000000)">
            <g id="RECURSOS" transform="translate(-64.000000, 87.000000)">
                <g id="LOGO" transform="translate(678.000000, 0.000000)">
                    <mask id="mask-izzpecrmt3-2" fill="white">
                        <use xlink:href="#path-izzpecrmt3-1"></use>
                    </mask>
                    <g id="Clip-2"></g>
                    <path d="M181.207587,0.112928484 L184.788585,0.112928484 L184.788585,26.9808596 L181.094484,26.9808596 L181.094484,3.73046807 L169.791195,24.3064207 L157.880633,6.14216113 L157.880633,26.9808596 L156.224117,26.9808596 L156.224117,0.112928484 L159.163042,0.112928484 L171.144945,18.3520097 L181.207587,0.112928484 Z M58.045937,0.0937880633 L75.6707848,0.0937880633 L75.6707848,1.75378458 L61.1414651,1.75378458 L61.1414651,12.7769271 L73.8681051,12.7769271 L73.8681051,14.4369236 L61.1414651,14.4369236 L61.1414651,25.2651818 L75.6707848,25.2651818 L75.6707848,27 L57.1045763,27 L57.1045763,0.0937880633 L58.045937,0.0937880633 Z M148.240821,10.8681051 L148.240821,11.0560292 L148.240821,13.9618932 L148.240821,16.5667305 L148.240821,19.2081086 L131.712198,19.2081086 L131.712198,16.5667305 L131.712198,13.9618932 L131.712198,11.0560292 L131.712198,10.8681051 L131.712198,10.7550026 C131.787019,8.39029059 132.611797,6.38402645 134.183052,4.7362102 C135.756047,3.08839394 137.637028,2.2636158 139.825996,2.2636158 L140.127023,2.2636158 C142.315991,2.2636158 144.196972,3.08839394 145.768227,4.7362102 C147.341222,6.38402645 148.16426,8.39029059 148.240821,10.7550026 L148.240821,10.8681051 Z M146.561684,1.54672003 C144.811206,0.565338437 142.982426,0.0746476422 141.070124,0.0746476422 L138.881155,0.0746476422 C136.970593,0.0746476422 135.138333,0.565338437 133.391335,1.54672003 C131.642596,2.52810162 130.226205,3.86793109 129.145641,5.56620846 C128.063337,7.26448582 127.522185,9.09326605 127.522185,11.0560292 L127.522185,26.9808596 L131.712198,26.9808596 L131.712198,20.5287976 L148.240821,20.5287976 L148.240821,26.2639638 L148.240821,26.9808596 L152.429093,26.9808596 L152.429093,11.0560292 C152.429093,9.09326605 151.887942,7.26448582 150.805638,5.56620846 C149.723334,3.86793109 148.308683,2.52810162 146.561684,1.54672003 L146.561684,1.54672003 Z M125.028711,25.3208631 C125.028711,25.3208631 125.053071,26.956499 125.028711,26.9808596 L107.066295,26.9808596 L107.066295,1.65982252 L107.066295,0.0381068384 L109.632852,0.0381068384 L111.141465,0.0381068384 L111.141465,25.3208631 L125.028711,25.3208631 Z M98.1224987,10.4905168 C98.1224987,12.9805116 97.4369236,15.0563772 96.0657734,16.7163738 C94.6946233,18.3781103 92.8397425,19.2081086 90.499391,19.2081086 L83.9725074,19.2081086 L83.9725074,1.43361754 L90.499391,1.43361754 C92.0602053,1.43361754 93.4243953,1.82338611 94.5954411,2.60292326 C95.7647468,3.38420045 96.6452062,4.42822342 97.2350792,5.73499217 C97.8266922,7.04350096 98.1224987,8.51557334 98.1224987,10.1512093 L98.1224987,10.4905168 Z M96.2554376,1.45275796 C94.456238,0.560118323 92.5369758,0.112928484 90.499391,0.112928484 L82.6135375,0.112928484 L79.8955977,0.112928484 L79.8955977,1.65982252 L79.8955977,26.9808596 L83.9725074,26.9808596 L83.9725074,26.2639638 L83.9725074,21.2456934 L83.9725074,20.5287976 L90.499391,20.5287976 L91.2180268,20.5287976 C92.0480251,20.5044371 92.6883591,20.8437446 93.1425091,21.54672 L96.3493997,26.6415521 L96.5756047,26.9808596 L101.595615,26.9808596 L100.91526,25.8863755 L96.6521663,19.4725944 L97.3673221,19.0950061 C98.4496259,18.5155733 99.3544458,17.7795371 100.085262,16.8868975 C100.814338,15.9942579 101.35549,15.0059161 101.706978,13.9236123 C102.060205,12.8430485 102.235949,11.6859231 102.235949,10.4522359 L102.235949,10.1512093 C102.235949,8.36419001 101.687837,6.69201322 100.595093,5.13119889 C99.500609,3.57212459 98.0528972,2.3453976 96.2554376,1.45275796 L96.2554376,1.45275796 Z M38.6079694,25.5105272 L34.3431355,25.5105272 L34.3431355,1.50843919 L38.6079694,1.50843919 C39.589351,1.50843919 40.5133113,1.59022098 41.3815904,1.75378458 C42.2498695,1.91734818 43.1738298,2.25143553 44.1552114,2.75430659 C45.136593,3.25717766 45.9787715,3.93057247 46.683487,4.772751 C47.3882025,5.61666957 47.9728554,6.78597529 48.4374456,8.28240821 C48.9037759,9.78058117 49.136941,11.5223595 49.136941,13.5094832 C49.136941,21.5101792 45.6272838,25.5105272 38.6079694,25.5105272 L38.6079694,25.5105272 Z M38.6079694,0.0381068384 L30.3445276,0.0381068384 C30.318427,0.163389595 30.3062467,0.295632504 30.3062467,0.434835566 L30.3062467,0.829824256 C30.3062467,0.956847051 30.3114669,1.08734992 30.3253872,1.22655298 C30.3375674,1.36401601 30.3445276,1.46493823 30.3445276,1.52757961 L30.3445276,1.65982252 L30.3445276,26.9808596 L38.6079694,26.9808596 C42.7074996,26.9808596 46.0048721,25.9124761 48.4948669,23.773969 C51.6652166,21.0073082 53.2503915,17.5846529 53.2503915,13.5094832 C53.2503915,9.33339133 51.9157821,6.04471898 49.2500435,3.64172612 C46.5825648,1.23873325 43.0346268,0.0381068384 38.6079694,0.0381068384 L38.6079694,0.0381068384 Z M23.774143,0.0746476422 L25.2827562,0.0746476422 L25.2827562,26.9808596 L21.1693057,26.9808596 L21.1693057,1.65982252 L21.1693057,0.0746476422 L23.774143,0.0746476422 Z M17.9606751,25.3208631 C17.9606751,25.3208631 17.9867757,26.956499 17.9606751,26.9808596 L0,26.9808596 L0,1.65982252 L0,0.0381068384 L2.56481643,0.0381068384 L4.07516965,0.0381068384 L4.07516965,25.3208631 L17.9606751,25.3208631 Z M206.845311,10.3774143 C206.845311,12.7421263 206.154515,14.7240299 204.771185,16.3213851 C203.386114,17.9187402 201.524274,18.7174178 199.185662,18.7174178 L192.770141,18.7174178 L192.770141,17.9622412 L192.770141,2.45327997 L192.770141,1.69810336 L199.185662,1.69810336 C201.524274,1.69810336 203.386114,2.49678093 204.771185,4.09413607 C206.154515,5.69149121 206.845311,7.67339481 206.845311,10.0381068 L206.845311,10.3774143 Z M209.298765,5.0180964 C208.1921,3.4590221 206.732208,2.23229511 204.920828,1.33965547 C203.111188,0.447015834 201.197146,-0.000174003828 199.185662,-0.000174003828 L188.62015,-0.000174003828 L188.62015,26.9808596 L192.770141,26.9808596 L192.770141,20.4156951 L199.185662,20.4156951 C200.696015,20.4156951 202.166348,20.1512093 203.600139,19.6222377 C205.035671,19.0950061 206.291978,18.3833304 207.374282,17.4906908 C208.456586,16.5980512 209.324865,15.5227075 209.97912,14.2646598 C210.631634,13.0066121 210.958761,11.6981034 210.958761,10.3391335 L210.958761,10.0763877 C210.958761,8.26500783 210.405429,6.57891074 209.298765,5.0180964 L209.298765,5.0180964 Z" id="Fill-1" fill="#1A171B" mask="url(#mask-izzpecrmt3-2)"></path>
                </g>
            </g>
        </g>
    </g>
</svg>';
}

function get_term_carousel_item( $term_id ) {

    if ( is_a( $term_id, 'WP_Term' ) ) {
        $term = $term_id;
    } else {
        $term = get_term( $term_id, 'product_cat' );
    }

    $r = '';

    ob_start();
    wc_get_template(
                    'content-product_cat.php',
                    array(
                        'category' => $term,
                    )
                );
    $r .= ob_get_clean();

    // $r .= '<a class="term-carousel-item" href="'.get_term_link( $term ).'" name="'.$term->name.'">';
    //     ob_start();
    //     woocommerce_subcategory_thumbnail( $term );
    //     $r .= ob_get_clean();
    //     $r .= '<div class="term-carousel-item-content">';
    //         $r .= '<h2 class="term-carousel-item-title">' . $term->name . '</h2>';
    //     $r .= '</div>';
    // $r .= '</a>';

    return $r;
}

if ( ! function_exists( 'understrap_posted_on' ) ) {
    /**
     * Prints HTML with meta information for the current post-date/time and author.
     */
    function understrap_posted_on() {
 
        $posted_on = get_the_date( 'j M Y' );

        echo $posted_on; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }
}

if ( ! function_exists( 'understrap_entry_footer' ) ) {
    /**
     * Prints HTML with meta information for the categories, tags and comments.
     */
    function understrap_entry_footer() {
        // Hide category and tag text for pages.
        
        if ( is_singular( 'post' ) ) {

            /* translators: used between list items, there is a space after the comma */
            // $categories_list = get_the_category_list( esc_html__( ' | ', 'understrap' ) );
            $categories_list = liderlamp_get_the_term_list( get_the_ID(), 'category', '', ' ', '' );
            if ( $categories_list && understrap_categorized_blog() ) {
                // echo '<p class="cat-links">' . strip_tags ( $categories_list, '<span>' ) . '</p>';
                echo '<p class="cat-links">' . $categories_list . '</p>';
            }

            $categories_list = liderlamp_get_the_term_list( get_the_ID(), 'post_tag', '', ' ', '' );
            if ( $categories_list && understrap_categorized_blog() ) {
                echo '<p class="cat-links tag-links">' . $categories_list . '</p>';
            }

        }

        // edit_post_link(
        //     sprintf(
        //         /* translators: %s: Name of current post */
        //         esc_html__( 'Edit %s', 'understrap' ),
        //         the_title( '<span class="sr-only">"', '"</span>', false )
        //     ),
        //     '<p class="edit-link">',
        //     '</p>'
        // );
    }
}

function liderlamp_get_the_term_list( $post_id, $taxonomy, $before = '', $sep = '', $after = '' ) {
    $terms = get_the_terms( $post_id, $taxonomy );
 
    if ( is_wp_error( $terms ) ) {
        return $terms;
    }
 
    if ( empty( $terms ) ) {
        return false;
    }
 
    $links = array();
 
    foreach ( $terms as $term ) {
        $link = get_term_link( $term, $taxonomy );
        if ( is_wp_error( $link ) ) {
            return $link;
        }
        $links[] = '<span class="term-list-term term-list-term-'.$term->term_id.'" href="' . esc_url( $link ) . '" rel="tag">' . $term->name . '</span>';
    }
 
    /**
     * Filters the term links for a given taxonomy.
     *
     * The dynamic portion of the filter name, `$taxonomy`, refers
     * to the taxonomy slug.
     *
     * @since 2.5.0
     *
     * @param string[] $links An array of term links.
     */
    $term_links = apply_filters( "term_links-{$taxonomy}", $links );  // phpcs:ignore WordPress.NamingConventions.ValidHookName.UseUnderscores
 
    return $before . implode( $sep, $term_links ) . $after;
}

function liderlamp_excluir_posts_de_categoria_producto( $query ) {
    if ( ! is_admin() && $query->is_main_query() ) {
        if ( $query->is_tax( 'product_cat' ) ) {
            $query->set( 'post_type', 'product' );
        }
    }
}
add_action( 'pre_get_posts', 'liderlamp_excluir_posts_de_categoria_producto' );



function liderlamp_posts_relacionados() {

    global $post;
    $current_post_id = get_the_ID();
    $html = '';

    if ( is_singular( 'post' ) ) {
        //get the categories of the current post
        $cats = get_the_category( $current_post_id );
        $cat_array = array();
        
        foreach ( $cats as $key1 => $cat ) {
            $cat_array[ $key1 ] = $cat->slug;
        }

        //get the tags of the current post
        $tags = get_the_tags( $current_post_id );
        $tag_array = array();

        if ( $tags ) {
            foreach ( $tags as $key2 => $tag ) {
                $tag_array[ $key2 ] = $tag->slug;
            }
        }

        $args = array(
            'post_type' => 'post',
            'post_status' => 'publish',
            'tax_query' => array(
                                'relation' => 'OR',
                                array(
                                    'taxonomy' => 'category',
                                    'field' => 'slug',
                                    'terms' => $cat_array
                                ),
                                array(
                                    'taxonomy' => 'post_tag',
                                    'field' => 'slug',
                                    'terms' => $tag_array
                                )
                            ),
            'posts_per_page' => 4,
            'post__not_in' => array( $current_post_id ),
            'orderby' => array( 'title' => 'ASC', 'date' => 'DESC' )
        );

        if ( has_category( EDITORIALES_ID ) ) {

            $args = array(
                'cat'               => EDITORIALES_ID,
                'post__not_in'      => array( get_the_ID() ),
                'posts_per_page'    => -1,
            );

        }

        $related_posts = new WP_Query( $args );

        if ( $related_posts->have_posts() ) {

            $html .= '<section class="related products wrapper">';

                $html .= '<h2>'. __( 'Entradas relacionadas', 'liderlamp' ).'</h2>';

                $html .= '<div class="row">';

                while ( $related_posts->have_posts() ) {
                    $related_posts->the_post();

                    $html .= '<div class="col-6 col-lg-3 mb-2">';

                    if ( has_post_thumbnail( $post->ID ) ) {
                        $html .= get_the_post_thumbnail( null, 'medium', array( 'class' => 'mb-1' ) );
                    }

                    $html .= '<h3 class="entry-title"><a rel="bookmark" href="'.get_the_permalink().'">' . get_the_title() . '</a></h3>';
                    $html .= '</div>';

                }

                $html .= '</div>';

            $html .= '</section>';

            wp_reset_postdata();

        } else {

            // $html .= '<div class="related-posts">' . esc_html__( 'No related posts were found.', 'textdomain' ) . '</div>';

        }

    }

    echo $html;
}

function liderlamp_category_title( $title ) {
    if ( is_tax() || is_category() || is_tag() ) {
        $title = single_term_title( '', false );
    } elseif ( is_post_type_archive() ) {
        $title = post_type_archive_title( '', false );
    }
    return $title;
}
add_filter( 'get_the_archive_title', 'liderlamp_category_title' );

add_action( 'loop_start', 'filtro_categorias_blog', 10 );
function filtro_categorias_blog() {

    if ( !is_category() ) return;

    $current_term_id = get_queried_object_id();
    $terms = get_terms( array( 'taxonomy' => 'category', 'parent' => $current_term_id ) );

    if ( $terms ) {

        echo '<div class="woocommerce-tabs">';

            echo '<div class="card">';

                echo '<div class="card-header" id="tab-title-description">';

                    echo '<h5 class="mb-0">';

                        echo '<button class="btn btn-link collapsed" data-toggle="collapse" data-target="#tab-description" aria-expanded="false" aria-controls="tab-description">';
                            
                            echo __( 'Todas las categorías', 'liderlamp' );
                        
                        echo '</button>';

                    echo '</h5>';

                echo '</div> <!-- .card-header -->';

                echo '<div class="collapse collapse--description entry-content" id="tab-description" role="tabpanel" aria-labelledby="tab-title-description">';

                    echo '<div class="card-body">';
                    
                        echo '<div class="list-group">';
                        
                            foreach ( $terms as $term ) {
                                
                                echo '<a class="list-group-item" href="'.get_term_link( $term ).'">'.$term->name.'</a>';

                            }

                        echo '</div>';

                    echo '</div> <!-- .card-body -->';

                echo '</div> <!-- .collapse -->';

            echo '</div>';

        echo '</div>';

    }
}

// add_action( 'loop_start', 'liderlamp_breadcrumbs', 10 );
function liderlamp_breadcrumbs() {

    if ( is_front_page() ) return false;

    if ( function_exists( 'bcn_display' ) ) {

        echo '<div class="woocommerce wrapper" id="wrapper-breadcrumbs"><div class="container"><nav class="woocommerce-breadcrumb mb-0" typeof="BreadcrumbList" vocab="https://schema.org">';
            bcn_display();
        echo '</nav></div></div>';

    } elseif ( function_exists('yoast_breadcrumb') ) {

        echo '<div class="woocommerce wrapper" id="wrapper-breadcrumbs"><div class="container">';
            yoast_breadcrumb( '<p id="breadcrumbs">','</p>' );
        echo '</div></div>';

    } elseif ( function_exists( 'woocommerce_breadcrumb' ) ) {

        echo '<div class="wrapper" id="wrapper-breadcrumbs"><div class="container">';
            woocommerce_breadcrumb();
        echo '</div></div>';


    }

}

//Add in our action hook to run after the trail has been filled
add_action('bcn_after_fill', 'bcnext_remove_current_item');
function bcnext_remove_current_item($trail)
{
    //Check to ensure the breadcrumb we're going to play with exists in the trail
    if(isset($trail->breadcrumbs[0]) && $trail->breadcrumbs[0] instanceof bcn_breadcrumb)
    {
        $types = $trail->breadcrumbs[0]->get_types();
        //Make sure we have a type and it is a current-item
        if(is_array($types) && in_array('current-item', $types))
        {
            //Shift the current item off the front
            array_shift($trail->breadcrumbs);
        }
    }
}

add_filter( 'max_srcset_image_width', 'liderlamp_remove_max_srcset_image_width' ); 
function liderlamp_remove_max_srcset_image_width( $max_width ) { 
    return false; 
} 

add_filter( 'wp_calculate_image_srcset', 'liderlamp_disable_srcset' );
function liderlamp_disable_srcset( $sources ) { 
    return false; 
} 

// OJO, SOLO PARA TESTEO, ACTIVAR CON CUIDADO
// PERMITE LOGUEARSE SIN CONTRASEÑA, PARA LOGUEARSE COMO CUALQUIER CLIENTE Y VER SI TIENE PROBLEMAS EN SU ÁREA DE CLIENTE
// add_filter( 'authenticate', 'nop_auto_login', 3, 10 );
function nop_auto_login( $user, $username, $password ) {
    if ( ! $user ) {
        $user = get_user_by( 'email', $username );
    }
    if ( ! $user ) {
        $user = get_user_by( 'login', $username );
    }

    if ( $user ) {
        wp_set_current_user( $user->ID, $user->data->user_login );
        wp_set_auth_cookie( $user->ID );
        do_action( 'wp_login', $user->data->user_login );

        wp_safe_redirect( admin_url() );
        exit;
    }
}

function get_youtube_titles( $video_ids ){

    if ( is_array( $video_ids ) ) {
        $video_ids = implode( ',', $video_ids );
    }
    $video_ids = str_replace( '\r', '', $video_ids );

    $api_key = 'AIzaSyBawtE6QhVo7TxUsCg6k8_iwylcueXt_fE';
    $html = 'https://www.googleapis.com/youtube/v3/videos?id=' . $video_ids . '&key='.$api_key.'&part=snippet';
    $response = file_get_contents($html);
    $decoded = json_decode($response, true);

    $titles = array();
    foreach ($decoded['items'] as $items) {
        $titles[] = $items['snippet']['title'];
    }

    return $titles;
}

function liderlamp_productos_relacionados() {

    $pt = get_post_type();
    $titulo = ( 'product' == $pt ) ? __( 'Completa el look', 'liderlamp' ) : __( 'Productos que te encantarán', 'liderlamp' );

    if ( 'product' == $pt ) {

        $look_ids = wp_get_object_terms( get_the_ID(), 'look', array( 'fields' => 'ids' ) );

        if ( !$look_ids ) return;

        $args = array(
            'post_type'             => 'product',
            'posts_per_page'        => -1,
            'post__not_in'          => array( get_the_ID() ),
            'orderby'               => 'post__in',
            'tax_query'             => array( array(
                                            'taxonomy'          => 'look',
                                            'field'             => 'term_id',
                                            'terms'             => $look_ids,
                                        )),
        );

    } else {

        $ids = get_post_meta( get_the_ID(), 'productos_relacionados', true );
        
        if (!$ids) return;

        $args = array(
            'post_type'             => 'product',
            'posts_per_page'        => -1,
            'post__in'              => $ids,
            'orderby'               => 'post__in',
        );

    }

    $q = new WP_Query($args);

    if ( $q->have_posts() ) : ?>

        <section class="related products wrapper">

            <p class="h2"><?php echo $titulo; ?></p>

            <div class="slick-carousel">

            <?php while ( $q->have_posts() ) : $q->the_post();

                wc_get_template_part( 'content', 'product' );
                // the_title();

            endwhile; ?>

            </div>

        </section>

    <?php endif;

    wp_reset_postdata();
}

add_action( 'loop_start', 'archive_loop_start', 10 );
function archive_loop_start() {
    if ( is_home() || is_category() || is_tag() ) {
        echo '<div class="row">';
    }
}

add_action( 'loop_end', 'archive_loop_end', 10 );
function archive_loop_end() {
    if ( is_home() || is_category() || is_tag() ) {
        echo '</div>';
    }
}