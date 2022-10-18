<?php

/**
 * Home Trastienda Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'home-trastienda-' . $block['id'];
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'home-trastienda';
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
$post_apertura = get_field('post_apertura') ?: false;
$post_cierre = get_field('post_cierre') ?: false;


global $post;
?>

<div id="<?php echo $id; ?>" class="<?php echo $className; ?>" <?php echo $style; ?>>

	<?php

	if ( $post_apertura ) {
		$post = $post_apertura;
		setup_postdata( $post );
		echo '<div class="post-apertura">';
			get_template_part( 'loop-templates/content' );
		echo '</div>';
		wp_reset_postdata();
	}

		if ( have_rows( 'posts_intermedios' ) ) :

			echo '<div class="slick-carrusel-posts">';

				while ( have_rows ( 'posts_intermedios' ) ) : the_row();

					$tipo = get_sub_field( 'tipo_de_publicacion' );

					switch ( $tipo ) {
						case 'post':

							$post = get_sub_field( 'post' );
							if ( $post ) {
								setup_postdata( $post );
								get_template_part( 'loop-templates/content' );
								wp_reset_postdata();
							}

							break;
						
						case 'instagram':

							echo '<div class="embed-container">';

								instagram_embed( get_sub_field( 'instagram_url' ) );

							echo '</div>';

							break;
						
						case 'pinterest':

							echo '<div class="embed-container">';

								the_sub_field( 'pinterest_url' );

							echo '</div>';

							break;
						
						default:

							break;
					}

				endwhile;

			echo '</div>';
		
		else :


		endif;

		if ( $post_cierre ) {

			$post = $post_cierre;
			setup_postdata( $post_cierre );
			echo '<div class="post-cierre">';
				get_template_part( 'loop-templates/content' );
			echo '</div>';
			wp_reset_postdata();

		}
	?>

</div>