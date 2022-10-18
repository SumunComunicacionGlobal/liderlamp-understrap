<?php
/**
 * Single post partial template
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
?>

<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">

	<header class="entry-header">

		<div class="entry-meta">

			<?php understrap_posted_on(); ?>

			<?php //echo get_the_term_list( NULL, 'category', '<p class="cat-links">', ' · ', '</p>' ); ?>

		</div><!-- .entry-meta -->

		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

	</header><!-- .entry-header -->

	<div class="entry-content">

		<?php
		the_content();
		understrap_link_pages();
		?>

	</div><!-- .entry-content -->

	<footer class="entry-footer">

		<?php understrap_entry_footer(); ?>

	</footer><!-- .entry-footer -->

	<?php liderlamp_productos_relacionados(); ?>

	<?php // liderlamp_posts_relacionados(); ?>

</article><!-- #post-## -->
