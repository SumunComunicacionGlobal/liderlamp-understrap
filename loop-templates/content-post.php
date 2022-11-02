<?php
/**
 * Post rendering content according to caller of get_template_part
 *
 * @package UnderStrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
?>

<div class="adaptable-post col-lg-4">

	<a class="btn p-0 d-block d-lg-none" href="<?php the_permalink(); ?>">

		<article <?php post_class( 'wp-block-cover has-black-background-color has-background-dim' ); ?> id="post-<?php the_ID(); ?>">

				<?php the_post_thumbnail( 'woocommerce_single', array( 'class' => 'wp-block-cover__image-background' ) ); ?>

				<div class="wp-block-cover__inner-container">

					<?php if ( 'post' === get_post_type() ) : ?>

						<div class="entry-meta">
							<?php understrap_posted_on(); ?>
						</div><!-- .entry-meta -->

					<?php endif; ?>

					<header class="entry-header">

						<?php the_title( '<p class="entry-title">', '</p>' ); ?>

					</header><!-- .entry-header -->

					<div class="entry-content">

						<?php if ( $post->post_excerpt ) echo wp_trim_words( $post->post_excerpt, 15, '...' ); ?>

					</div><!-- .entry-content -->

					<footer class="entry-footer">

						<?php understrap_entry_footer(); ?>

					</footer><!-- .entry-footer -->

				</div>

		</article><!-- #post-## -->

	</a>

	<article <?php post_class( 'd-none d-lg-block position-relative mb-2' ); ?> id="post-<?php the_ID(); ?>">

		<?php echo get_the_post_thumbnail( $post->ID, 'medium_large', array( 'class' => 'mb-1 vertical-post-image' ) ); ?>

		<header class="entry-header position-static">

			<?php if ( 'post' === get_post_type() ) : ?>

				<div class="entry-meta mb-0">
					<?php understrap_posted_on(); ?>
				</div><!-- .entry-meta -->

			<?php endif; ?>

			<?php
			the_title(
				sprintf( '<p class="entry-title"><a class="stretched-link" href="%s" rel="bookmark">', esc_url( get_permalink() ) ),
				'</a></p>'
			);
			?>

		</header><!-- .entry-header -->

		<div class="entry-content">

			<?php the_excerpt(); ?>

			<?php
			wp_link_pages(
				array(
					'before' => '<div class="page-links">' . __( 'Pages:', 'understrap' ),
					'after'  => '</div>',
				)
			);
			?>

		</div><!-- .entry-content -->

		<footer class="entry-footer">

			<?php understrap_entry_footer(); ?>

		</footer><!-- .entry-footer -->

	</article><!-- #post-## -->

</div>
