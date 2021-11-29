<?php

add_action('init', 'ipf_set_playfab_api');

function ipf_set_playfab_api()
{
    global $ipf_playfab_api;
    $ipf_title_id = ipf_get_option('ipf_title_id');
    if ($ipf_title_id)
        $ipf_playfab_api->set_title_id($ipf_title_id);
        
    $ipf_secret_key = ipf_get_option('ipf_secret_key');
    if ($ipf_secret_key)
        $ipf_playfab_api->set_secret_key($ipf_secret_key);
}
