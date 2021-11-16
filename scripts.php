<?php

add_action('wp_enqueue_scripts', 'ipf_frontend_scripts');

function ipf_frontend_scripts()
{

    $min = (in_array($_SERVER['REMOTE_ADDR'], array('127.0.0.1', '::1', '10.0.0.3'))) ? '' : '.min';

    if (empty($min)) :
        wp_enqueue_script('integracao-playfab-livereload', 'http://localhost:35729/livereload.js?snipver=1', array(), null, true);
    endif;

    wp_register_script('integracao-playfab-script', IPF_URL . 'assets/js/integracao-playfab' . $min . '.js', array('jquery'), '1.0.0', true);

    wp_enqueue_script('integracao-playfab-script');

    wp_localize_script('integracao-playfab-script', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
    wp_enqueue_style('integracao-playfab-style', IPF_URL . 'assets/css/integracao-playfab.css', array(), false, 'all');
}
