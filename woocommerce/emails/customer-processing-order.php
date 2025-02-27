<?php
/**
 * Customer processing order email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/customer-processing-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates\Emails
 * @version 3.7.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * @hooked WC_Emails::email_header() Output the email header
 */
do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

<?php /* translators: %s: Customer first name */ ?>
<h2 style="text-align: center;"><?php printf( esc_html__( 'Hi %s,', 'woocommerce' ), esc_html( $order->get_billing_first_name() ) ); ?></h2>

<p><?php _e( "🎉 <b>¡Muchas gracias por dejar que Liderlamp te inspire e ilumine!.</b> Nos ponemos manos a la obra para que puedas disfrutar de tu pedido lo antes posible. 💡", "liderlamp" ); ?></p>

<p><?php _e( "📦 <b>Información Importante sobre tu Pedido</b>: Si tu pedido incluye productos con diferentes fechas de entrega, enviaremos todo junto en la fecha más tardía. Te enviaremos un correo electrónico en cuanto tu pedido esté en camino.", "liderlamp" ); ?></p>

<p><?php _e( "📧 <b>Importante: Seguimiento y Factura</b>: Recibirás automáticamente el seguimiento y la factura de tu pedido desde nuestro sistema. Por favor, revisa tu bandeja de spam para asegurarte de que recibes toda la información necesaria sin contratiempos. Si no encuentras los correos o tienes cualquier duda, no dudes en contactarnos.", "liderlamp" ); ?></p>

<p><?php _e( "🏠 <b>Verifica tu Dirección de Envío</b>: Por favor, revisa la dirección y el código postal que nos has proporcionado para la entrega. Es esencial que estos detalles sean correctos para evitar cualquier retraso. Si encuentras algún error o tienes alguna pregunta, no dudes en contactarnos. Estamos aquí para ayudarte.", "liderlamp" ); ?></p>

<p><?php _e( '📲 <b>Mantente Conectado</b>: No te pierdas las últimas tendencias, inspiración diaria y novedades. ¡Síguenos en Instagram y sé parte de nuestra creciente comunidad! <a href="https://www.instagram.com/liderlamp/" target="_blank">Haz clic aquí para seguirnos</a>.', 'liderlamp' ); ?></p><br>

<?php

/*
 * @hooked WC_Emails::order_details() Shows the order details table.
 * @hooked WC_Structured_Data::generate_order_data() Generates structured data.
 * @hooked WC_Structured_Data::output_structured_data() Outputs structured data.
 * @since 2.5.0
 */
do_action( 'woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email );

/*
 * @hooked WC_Emails::order_meta() Shows order meta data.
 */
do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email );

/*
 * @hooked WC_Emails::customer_details() Shows customer details
 * @hooked WC_Emails::email_address() Shows email address
 */
do_action( 'woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email );

/**
 * Show user-defined additional content - this is set in each email's settings.
 */
if ( $additional_content ) {
	echo wp_kses_post( wpautop( wptexturize( $additional_content ) ) );
}

/*
 * @hooked WC_Emails::email_footer() Output the email footer
 */
do_action( 'woocommerce_email_footer', $email );
