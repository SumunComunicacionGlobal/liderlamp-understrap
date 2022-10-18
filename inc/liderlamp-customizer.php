<?php 

/**
* Crear panel de opciones en el customizador
*/
function sumun_new_customizer_settings($wp_customize) {
    $web_title = get_bloginfo( 'name' );
    // create settings section
    $wp_customize->add_panel('sumun_opciones', array(
        'title'         => $web_title . ': ' . __( 'Opciones de configuración', 'sumun-admin' ),
        'description'   => __( 'Opciones para este sitio web', 'sumun-admin' ),
        'priority'      => 1,
    ));
    $wp_customize->add_section('sumun_redes_sociales', array(
        'title'         => __( 'Redes sociales', 'sumun-admin' ),
        'priority'      => 20,
        'panel'         => 'sumun_opciones',
    ));
    $wp_customize->add_section('sumun_ajustes', array(
        'title'         => __( 'Otros ajustes', 'sumun-admin' ),
        'priority'      => 20,
        'panel'         => 'sumun_opciones',
    ));



    $redes_sociales = array(
        'email',
        'whatsapp',
        'linkedin',
        'twitter',
        'facebook',
        'instagram',
        'youtube',
        'skype',
        'pinterest',
        'flickr',
        'blog',
    );
    foreach ($redes_sociales as $red) {
        // add a setting
        $wp_customize->add_setting($red);
        
        // Add a control
        $wp_customize->add_control( $red,   array(
            'type'      => 'text',
            'label'     => 'URL ' . $red,
            'section'   => 'sumun_redes_sociales',
        ) );
    }


    $paginas = array(
        'contacto',
    );
    foreach ($paginas as $pagina) {

        $wp_customize->add_setting( $pagina . '_id', array(
          'capability' => 'edit_theme_options',
          'sanitize_callback' => 'sumun_sanitize_dropdown_pages',
        ) );

        $wp_customize->add_control( $pagina . '_id', array(
          'type' => 'dropdown-pages',
          'section' => 'sumun_ajustes', // Add a default or your own section
          'label' => __( 'Página de ' . $pagina ),
        ) );

    }

}
add_action('customize_register', 'sumun_new_customizer_settings');

function sumun_sanitize_dropdown_pages( $page_id, $setting ) {
  // Ensure $input is an absolute integer.
  $page_id = absint( $page_id );

  // If $page_id is an ID of a published page, return it; otherwise, return the default.
  return ( 'publish' == get_post_status( $page_id ) ? $page_id : $setting->default );
}

/***/