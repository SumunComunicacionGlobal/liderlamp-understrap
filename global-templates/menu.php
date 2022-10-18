<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$menus = array('tienda', 'trastienda' );
$has_any_menu = ( has_nav_menu( 'tienda' ) || has_nav_menu( 'trastienda' ) ) ? true : false;

if ( !$has_any_menu ) return false;
?>

<div id="menu-principal-wrapper" class="wrapper shadow">

    <div class="container">

        <nav class="nav nav-pills nav-justified">

                <?php if ( has_nav_menu( 'tienda' ) ) { ?>

                    <a class="nav-link active" id="menu-tienda-tab" data-toggle="pill" href="#menu-tienda" role="tab" aria-controls="menu-tienda" aria-selected="true">
                        <?php echo __( 'Producto', 'liderlamp' ); ?>
                        <span class="subtitulo-tab"><?php echo __( 'Tienda', 'liderlamp' ); ?></span>
                    </a>

                <?php } ?>

                <?php if ( has_nav_menu( 'trastienda' ) ) { ?>
                    
                    <?php if ( !has_nav_menu( 'tienda' ) ) { ?>

                        <a class="nav-link active" id="menu-trastienda-tab" data-toggle="pill" href="#menu-trastienda" role="tab" aria-controls="menu-trastienda" aria-selected="true">

                    <?php } else { ?>

                        <a class="nav-link" id="menu-trastienda-tab" data-toggle="pill" href="#menu-trastienda" role="tab" aria-controls="menu-trastienda" aria-selected="false">

                    <?php } ?>

                        <?php echo __( 'Inspiración', 'liderlamp' ); ?>
                        <span class="subtitulo-tab"><?php echo __( 'Trastienda', 'liderlamp' ); ?></span>
                    </a>

                <?php } ?>

        </nav>

        <div class="tab-content" id="menu-tabContent">

            <?php if ( has_nav_menu( 'tienda' ) ) { ?>

                <div class="tab-pane fade show active" id="menu-tienda" role="tabpanel" aria-labelledby="menu-tienda-tab">

                    <div class="navbar navbar-light">

                        <?php 
                            wp_nav_menu(
                                array(
                                    'theme_location'  => 'tienda',
                                    // 'container_class' => 'collapse navbar-collapse',
                                    // 'container_id'    => 'navbarNavDropdownTienda',
                                    'menu_class'      => 'navbar-nav',
                                    // 'fallback_cb'     => '',
                                    // 'menu_id'         => 'main-menu',
                                    // 'depth'           => 2,
                                    'walker'          => new Bootstrap_Collapse_NavWalker(),
                                )
                            );
                        ?>

                    </div>

                </div>

            <?php } ?>

            <?php if ( has_nav_menu( 'trastienda' ) ) { ?>

                    <?php if ( !has_nav_menu( 'tienda' ) ) { ?>

                        <div class="tab-pane fade show active" id="menu-trastienda" role="tabpanel" aria-labelledby="menu-trastienda-tab">

                    <?php } else { ?>

                        <div class="tab-pane fade" id="menu-trastienda" role="tabpanel" aria-labelledby="menu-trastienda-tab">

                    <?php } ?>

                    <div class="navbar navbar-light">

                        <?php 
                            wp_nav_menu(
                                array(
                                    'theme_location'  => 'trastienda',
                                    // 'container_class' => 'collapse navbar-collapse',
                                    // 'container_id'    => 'navbarNavDropdownTrastienda',
                                    'menu_class'      => 'navbar-nav',
                                    // 'fallback_cb'     => '',
                                    // 'menu_id'         => 'main-menu',
                                    // 'depth'           => 2,
                                    'walker'          => new Bootstrap_Collapse_NavWalker(),
                                )
                            );
                        ?>

                    </div>

                </div>

            <?php } ?>

        </div>

    </div>

    <div id="menu-principal-rrss" class="wrapper">

        <div class="container">

            <?php 
                $redes_sociales = get_redes_sociales(); 
                if ( $redes_sociales ) {
                    echo '<p class="lead font-weight-bold text-center">' . __( 'La inspiración sigue en:', 'liderlamp' ) . '</p>';
                    echo $redes_sociales;
                }
            ?>
        </div>

    </div>

</div>