<?php

/**
 * Carrusel posts Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'carrusel-posts-' . $block['id'];
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'carrusel-posts';
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
$categoria_id = get_field('categoria_id') ?: false;
?>

<div id="<?php echo $id; ?>" class="<?php echo $className; ?>" <?php echo $style; ?>>

<?php 

    $args = array(
        'post_type'         => 'post',
        'posts_per_page'    => 6,
    );

    if ( $categoria_id ) {
        $args['cat'] = $categoria_id;
    } 

    $q = new WP_Query($args);

    if ( $q->have_posts() ) {

    	echo '<div class="slick-carrusel-posts">';

	        while ( $q->have_posts() ) { $q->the_post();

	            get_template_part( 'loop-templates/content' );

	        }

	    echo '</div>';

    }

    wp_reset_postdata();

?>

</div>