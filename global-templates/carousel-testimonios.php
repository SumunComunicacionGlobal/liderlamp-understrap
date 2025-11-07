<?php
// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

	$args = array(
				'post_type'			=> 'testimonio',
				'posts_per_page'	=> 5,
				'orderby'			=> 'rand',
	);



	$query = new WP_Query($args);
	
	if ($query->have_posts()) {

		echo '<div id="carousel-testimonios" class="slider-testimonios">';

				$indicators = '';

				while ($query->have_posts()) { $query->the_post();

					get_template_part( 'loop-templates/content', 'testimonio' );

				}

		echo '</div>'; // .carousel

	}

	wp_reset_postdata();
