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

	<?php if ( has_post_thumbnail() ) : ?>

		<header class="entry-header row">

			<div class="col-md-6 mb-2">

				<?php echo get_the_post_thumbnail( $post->ID, 'large' ); ?>

			</div>

			<div class="col-md-6 mb-1 d-flex flex-column justify-content-between">

				<div>

					<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

					<div class="entry-meta">

						<?php // understrap_posted_on(); ?>

						<?php echo get_the_term_list( NULL, 'category', '<p class="cat-links">', ' · ', '</p>' ); ?>

					</div><!-- .entry-meta -->

				</div>

				<?php echo '<div class="single-post-excerpt">' . apply_filters( 'the_content', $post->post_excerpt ) . '</div>'; ?>

			</div>

		</header><!-- .entry-header -->

	<?php else: ?>

		<header class="entry-header">

			<div class="entry-meta">

				<?php //understrap_posted_on(); ?>

				<?php echo get_the_term_list( NULL, 'category', '<p class="cat-links">', ' · ', '</p>' ); ?>

			</div><!-- .entry-meta -->

			<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

		</header><!-- .entry-header -->


	<?php endif; ?>

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

	<?php liderlamp_posts_relacionados(); ?>

</article><!-- #post-## -->
