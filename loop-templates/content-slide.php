<?php
/**
 * Post rendering content according to caller of get_template_part
 *
 * @package UnderStrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$image_size = 'large';
$post_type = get_post_type();

switch ($post_type) {
	case 'slide':
		$image_size = 'medium_large';
		break;
	
	case 'banner':
		$image_size = 'full';
		break;
		
	default:
		$image_size = 'large';
	break;
}
?>

<article <?php post_class( 'wp-block-cover has-black-background-color has-background-dim has-background-dim-20' ); ?> id="post-<?php the_ID(); ?>">

		<?php the_post_thumbnail( $image_size, array( 'class' => 'wp-block-cover__image-background' ) ); ?>

		<div class="wp-block-cover__inner-container">

			<?php the_title( '<p class="entry-title">', '</p>' ); ?>

			<?php the_content(); ?>

		</div>

</article><!-- #post-## -->