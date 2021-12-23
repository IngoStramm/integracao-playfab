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

// Redirecionamento dos usuários para a loja do site
// São duas funções, um do WP e uma do WC (ambas neste arquivo)

// Hook do WP
add_filter('login_redirect', 'ipf_wp_redirect_to_shop_page', 10, 3);

function ipf_wp_redirect_to_shop_page($redirect_to, $request, $user)
{
    if (isset($user->roles) && is_array($user->roles)) {
        // verifica se não é um admin ou editor
        if (in_array('administrator', $user->roles) || in_array('editor', $user->roles)) {
            return $redirect_to;
        } else {
            $shop_page_url = get_permalink(wc_get_page_id('shop'));
            if (!$shop_page_url)
                return home_url();
            else
                return $shop_page_url;
        }
    } else {
        return $redirect_to;
    }
}

// Hook do WC (mantido no functions para manter as funções juntas)
add_filter('woocommerce_login_redirect', 'ipf_wc_redirect_to_shop_page', 10, 2);

function ipf_wc_redirect_to_shop_page($redirect, $user)
{
    $shop_page_url = get_permalink(wc_get_page_id('shop'));
    if (!$shop_page_url)
        return home_url();
    else
        return $shop_page_url;
}

// remove wp version number from scripts and styles
add_filter('style_loader_src', 'remove_css_js_version', 9999);
add_filter('script_loader_src', 'remove_css_js_version', 9999);

function remove_css_js_version($src)
{
    if (strpos($src, '?ver='))
        $src = remove_query_arg('ver', $src);
    return $src;
}
