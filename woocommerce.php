<?php

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

// limita o carrinho a um produto por compra
// Função removida, pois a função de limpar o carrinho toda vez que um novo produto é comprado já resolve o problema
// Mantida como referência 

// add_filter('woocommerce_add_to_cart_validation', 'ipf_limit_one_per_order', 10, 2);

function ipf_limit_one_per_order($passed_validation, $product_id)
{

    if (WC()->cart->get_cart_contents_count() >= 1) {
        wc_add_notice(__('Só é possível comprar um produto por pedido.', 'ipf'), 'error');
        return false;
    }

    return $passed_validation;
}

// Redireciona direto para o checkout
// Referência: https://quadlayers.com/skip-cart-page-in-woocommerce/

add_filter('add_to_cart_redirect', 'ipf_skip_cart_page');

function ipf_skip_cart_page()
{
    global $woocommerce;
    $redirect_checkout = $woocommerce->cart->get_checkout_url();
    return $redirect_checkout;
}

//Replace Add to Cart text with Buy Now! 
add_filter('woocommerce_product_single_add_to_cart_text', 'ipf_replace_add_to_cart_button_text');
add_filter('woocommerce_product_add_to_cart_text', 'ipf_replace_add_to_cart_button_text');

function ipf_replace_add_to_cart_button_text()
{
    return __('Comprar', 'ipf');
}

// Remove a mensagem de "adicionado ao carrinho"
add_filter('wc_add_to_cart_message_html', 'ipf_remove_add_to_cart_message');

function ipf_remove_add_to_cart_message($message)
{
    return '';
}

// Esvazia o carrinho toda vez que um novo produto é adicionado

add_filter('woocommerce_add_cart_item_data', '_empty_cart');

function _empty_cart($cart_item_data)
{

    WC()->cart->empty_cart();

    return $cart_item_data;
}
