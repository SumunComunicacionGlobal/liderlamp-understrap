<?php

/**
 * Último post Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'incrustar-post-' . $block['id'];
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'incrustar-post';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}
if( !empty($block['align']) ) {
    $className .= ' align' . $block['align'];
}

$style = '';
if ( $is_preview ) {
	$style = ' style="padding: 30px; background-color: #f1f1f1;"';
}

// Load values and assign defaults.
$post_id = get_field('post_id') ?: false;
if ( $post_id ) {

    $post_a_incrustar = get_post( $post_id );

    if ( $post_a_incrustar && $post_a_incrustar->post_status == 'publish' ) { 

        $posicion_imagen = get_field('posicion_imagen') ?: 'left';
        $className .= ' image-' . $posicion_imagen;
        ?>

        <div id="<?php echo $id; ?>" class="<?php echo $className; ?>" <?php echo $style; ?>>

        <?php 
            global $post;
            $post = $post_a_incrustar;
            setup_postdata( $post );

            get_template_part( 'loop-templates/content' );

            wp_reset_postdata();

        ?>

        </div>

    <?php } 

} ?>