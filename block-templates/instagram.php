<?php

/**
 * Instagram Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'instagram-' . $block['id'];
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'wp-block-instagram instagram';
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
$links = get_field('instagram_links') ?: false;
$links_array = explode( PHP_EOL, $links );

?>

<div id="<?php echo $id; ?>" class="<?php echo $className; ?>" <?php echo $style; ?>>

<?php 

if ( $links_array ) :

	if( count($links_array) > 1 ) {
		echo '<div class="slick-instagram">';
	}

	foreach ($links_array as $key => $url) : 

		if ( $url ) :

			$url = strtok( $url, "?");
			$url = trim( $url );

			if( count($links_array) > 1 ) {
				echo '<div class="slick-instagram-post">';
			}

				echo instagram_embed( $url );

			if( count($links_array) > 1 ) {
				echo '</div>'; //.slick-instagram-post
			}

		endif;

	endforeach; 

	if( count($links_array) > 1 ) {
		echo '</div>'; // .slick-instagram
	}


endif; ?>

</div>