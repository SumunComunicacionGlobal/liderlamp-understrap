<?php
/**
 * Single Product tabs
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/tabs/tabs.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.8.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Filter tabs and allow third parties to add their own.
 *
 * Each tab is an array containing title, callback and priority.
 *
 * @see woocommerce_default_product_tabs()
 */
$product_tabs = apply_filters( 'woocommerce_product_tabs', array() );


global $post, $product;
$descripcion = $post->post_content;
$content_explode = explode('<h3', $descripcion);
$content_explode = array_reverse( $content_explode );
$title_html_tag = 'p';

foreach ($content_explode as $i => $content_fragment) {
	if( substr($content_fragment, 0, 1) == '>' ) {
		$content_fragment = '<h3' . $content_fragment;
		$titulo = explode( '</h3>', $content_fragment );
		$titulo = str_replace('<h3>', '', $titulo[0]);
		$titulo = trim( $titulo );

		if($titulo) {
			$sanitized_titulo = sanitize_title( $titulo );
			// $sanitized_titulo = 'test';
			$content = str_replace('<h3>'.$titulo.'</h3>', '', $content_fragment );
			$descripcion = str_replace( $content_fragment, '', $descripcion );

			$new_tab = array( $sanitized_titulo => array(
				'title'				=> $titulo,
				'priority'			=> 5,
				'content'			=> $content,
			) );

			$product_tabs = array_insert_after( $product_tabs, 'description', $new_tab );
		}
	}
}

if ( $descripcion && strpos( $post->post_excerpt, $descripcion ) === false ) {
	unset( $product_tabs['description']['callback'] );
	$product_tabs['description']['content'] = $descripcion;
} else {
	unset( $product_tabs['description'] );
}

		// echo '<div style="clear:both;"><pre>'; print_r($product_tabs); echo '</pre></div>';

if ( ! empty( $product_tabs ) ) : ?>

	<div class="woocommerce-tabs wc-tabs-wrapper">

		<?php foreach ( $product_tabs as $key => $product_tab ) : ?>

			<?php 
			$collapse_show_class = '';
			$collapsed_class = ' collapsed';
			$collapse_aria_expanded = 'false';

			if( $key == 'description' ) {
				$collapse_show_class = ' show';
				$collapsed_class = '';
				$collapse_aria_expanded = 'true';
			} 


			switch ( $key ) {
				case 'informacion-del-producto':
					$product_tab['title'] = sprintf( __( 'Información del producto %s', 'liderlamp' ), $product->get_title() );
					$title_html_tag = 'h2';
					break;
				
				case 'description':
				case 'reviews':
					$title_html_tag = 'h2';
					break;
				
				default:
					$title_html_tag = 'p';
					break;
			}
		


			?>

			<div class="card">

				<div class="card-header" id="tab-title-<?php echo esc_attr( $key ); ?>">

					<<?php echo $title_html_tag; ?> class="mb-0">

						<button class="btn btn-link<?php echo $collapsed_class; ?>" id="tab-button-<?php echo esc_attr( $key ); ?>" data-toggle="collapse" data-target="#tab-<?php echo esc_attr( $key ); ?>" aria-expanded="<?php echo $collapse_aria_expanded; ?>" aria-controls="tab-<?php echo esc_attr( $key ); ?>">
							
							<?php echo wp_kses_post( apply_filters( 'woocommerce_product_' . $key . '_tab_title', $product_tab['title'], $key ) ); ?>

						</button>

					</<?php echo $title_html_tag; ?>>

				</div> <!-- .card-header -->

				<div class="collapse<?php echo $collapse_show_class; ?> collapse--<?php echo esc_attr( $key ); ?> entry-content" id="tab-<?php echo esc_attr( $key ); ?>" role="tabpanel" aria-labelledby="tab-title-<?php echo esc_attr( $key ); ?>">

					<div class="card-body">
					
						<?php
						if ( isset( $product_tab['callback'] ) ) {
							call_user_func( $product_tab['callback'], $key, $product_tab );
						} elseif ( isset( $product_tab['content'] ) ) {
							echo apply_filters( 'the_content', $product_tab['content'] );
						}
						?>

					</div> <!-- .card-body -->

				</div> <!-- .collapse -->

			</div> <!-- .card -->

		<?php endforeach; ?>

		<div class="card">

			<div class="card-header" id="tab-title-regalo-button">

				<p class="mb-0">

					<?php
						$titulo = $post->post_title;
						$url = get_the_permalink();
						$imagen = get_the_post_thumbnail_url( null, 'full' );
						$imagen = str_replace( 
							array(
								'%C3%97',
								'×'
							),
							array(
								'x',
								'x'
							),
							$imagen
						);
					?>

					<a href="<?php echo get_the_permalink( QUIERO_QUE_ME_LO_REGALEN_ID ); ?>?titulo=<?php echo $titulo; ?>&url=<?php echo $url; ?>&imagen=<?php echo $imagen; ?>" class="btn btn-link collapsed" id="tab-button-regalo-button" rel="noopener noreferrer noindex nofollow" target="_blank">
						
						<?php echo __( 'Quiero que me lo regalen', 'liderlamp' ); ?>

					</a>

				</p>

			</div> <!-- .card-header -->

		</div> <!-- .card -->

	</div> <!-- .woocommerce-tabs -->

	<?php do_action( 'woocommerce_product_after_tabs' ); ?>


<?php endif; ?>
