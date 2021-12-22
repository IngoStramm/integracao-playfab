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

// Hook do WC
add_filter('woocommerce_login_redirect', 'ipf_wc_redirect_to_shop_page', 10, 2);

function ipf_wc_redirect_to_shop_page($redirect, $user)
{
    $shop_page_url = get_permalink(wc_get_page_id('shop'));
    if (!$shop_page_url)
        return home_url();
    else
        return $shop_page_url;
}

// Remove os campos do checkout

add_filter('woocommerce_checkout_fields', 'ipf_remove_checkout_fields');

function ipf_remove_checkout_fields($fields)
{

    // ipf_debug($fields);

    // billing
    unset($fields['billing']['billing_company']);
    unset($fields['billing']['billing_country']);
    unset($fields['billing']['billing_address_1']);
    unset($fields['billing']['billing_address_2']);
    unset($fields['billing']['billing_city']);
    unset($fields['billing']['billing_state']);
    unset($fields['billing']['billing_postcode']);
    unset($fields['billing']['billing_phone']);

    // shipping
    unset($fields['shipping']['shipping_company']);
    unset($fields['shipping']['shipping_country']);
    unset($fields['shipping']['shipping_address_1']);
    unset($fields['shipping']['shipping_address_2']);
    unset($fields['shipping']['shipping_city']);
    unset($fields['shipping']['shipping_state']);
    unset($fields['shipping']['shipping_postcode']);

    // order_comments
    unset($fields['order']['order_comments']);

    return $fields;
}

// Remove o titulo "Informação Adicional" que vem após os campos do checkout
add_filter('woocommerce_enable_order_notes_field', '__return_false');

// Remove o campo "display name" dos detalhes da conta
add_filter('woocommerce_save_account_details_required_fields', 'ipf_remove_required_fields');

function ipf_remove_required_fields($required_fields)
{
    unset($required_fields['account_display_name']);

    return $required_fields;
}

// remove wp version number from scripts and styles
function remove_css_js_version($src)
{
    if (strpos($src, '?ver='))
        $src = remove_query_arg('ver', $src);
    return $src;
}
add_filter('style_loader_src', 'remove_css_js_version', 9999);
add_filter('script_loader_src', 'remove_css_js_version', 9999);