<?php
// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

	$args = array(
				'post_type'			=> 'faq',
				'posts_per_page'	=> -1,
				'orderby'			=> 'rand',
	);



	$query = new WP_Query($args);
	if ($query->have_posts()) {

		echo '<div id="rank-math-faq">';

			echo '<div class="rank-math-list">';

					while ($query->have_posts()) { $query->the_post();
						
						$unique_id = uniqid();

						echo '<div id="faq-question-'. $unique_id .'" class="rank-math-list-item">';

							the_title('<h3 class="rank-math-question">', '</h3>');

							echo '<div class="rank-math-answer">';
								the_content();
							echo '</div>';

						echo '</div>';
						
					}

				echo '</div>';

		echo '</div>';

	}

	wp_reset_postdata();
