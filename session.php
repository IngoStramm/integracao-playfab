<?php

add_action('init', 'ipf_start_session', 1);

function ipf_start_session()
{
    if (!session_id()) {
        session_start();
    }
}

add_action('wp_logout', 'ipf_end_session');
add_action('wp_login', 'ipf_end_session');

function ipf_end_session()
{
    session_destroy();
}
