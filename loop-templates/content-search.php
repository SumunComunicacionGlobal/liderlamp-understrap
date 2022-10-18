<?php
/**
 * Search results partial template
 *
 * @package UnderStrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
?>

<article <?php post_class('my-2'); ?> id="post-<?php the_ID(); ?>">

	<div class="row">

		<div class="col-2">

			<?php the_post_thumbnail( 'thumbnail' ); ?>

		</div>

		<div class="col-10">

			<header class="entry-header">

				<?php
				the_title(
					sprintf( '<h2 class="entry-title h4"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ),
					'</a></h2>'
				);
				?>

				<div class="entry-meta">

					<?php if ( 'post' === get_post_type() ) :

						understrap_posted_on();

					else :
					
						$pto = get_post_type_object( get_post_type() );
						echo $pto->labels->singular_name;

					endif; ?>

				</div><!-- .entry-meta -->

			</header><!-- .entry-header -->

			<div class="entry-summary">

				<?php the_excerpt(); ?>

			</div><!-- .entry-summary -->

		</div>

	</div>

</article><!-- #post-## -->
