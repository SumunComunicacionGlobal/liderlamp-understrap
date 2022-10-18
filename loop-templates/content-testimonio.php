<?php
/**
 * Post rendering content according to caller of get_template_part
 *
 * @package UnderStrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
?>

<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">

	<div class="testimonio-wrapper">

		<?php echo get_the_post_thumbnail( $post->ID, 'thumbnail', array('class' => 'rounded-circle') ); ?>

		<div class="contenido-testimonio"><?php the_content(); ?></div>

		<?php the_title( '<p class="titulo-testimonio">', '</p>' ); ?>

	</div>

</article><!-- #post-## -->
