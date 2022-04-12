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
    unset($fields['billing']['billing_number']);
    unset($fields['billing']['billing_city']);
    unset($fields['billing']['billing_neighborhood']);
    unset($fields['billing']['billing_state']);
    unset($fields['billing']['billing_postcode']);
    unset($fields['billing']['billing_phone']);

    // shipping
    unset($fields['shipping']['shipping_company']);
    unset($fields['shipping']['shipping_country']);
    unset($fields['shipping']['shipping_address_1']);
    unset($fields['shipping']['shipping_address_2']);
    unset($fields['shipping']['shipping_city']);
    unset($fields['shipping']['shipping_neighborhood']);
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

add_filter('woocommerce_add_cart_item_data', 'ipf_empty_cart');

function ipf_empty_cart($cart_item_data)
{

    WC()->cart->empty_cart();

    return $cart_item_data;
}

// Alterar o texto do status "processando" dos pedidos
// São 3 funções

// No próprio pedido
add_filter('wc_order_statuses', 'ipf_renaming_order_status');

function ipf_renaming_order_status($order_statuses)
{
    foreach ($order_statuses as $key => $status) {
        if ('wc-processing' === $key)
            $order_statuses['wc-processing'] = _x('Pronto para resgate', 'Status', 'ipf');
    }
    return $order_statuses;
}

// No "bulk actions"
add_filter('bulk_actions-edit-shop_order', 'ipf_dropdown_bulk_actions_shop_order', 20, 1);

function ipf_dropdown_bulk_actions_shop_order($actions)
{
    $actions['mark_processing'] = __('Mudar status para pronto para resgate', 'ipf');

    return $actions;
}

// No menu no topo da lista de pedidos
foreach (array('post', 'shop_order') as $hook)
    add_filter("views_edit-$hook", 'ipf_shop_order_modified_views');

function ipf_shop_order_modified_views($views)
{

    if (isset($views['wc-processing']))
        $views['wc-processing'] = str_replace('Processando', __('Pronto para resgate', 'ipf'), $views['wc-processing']);

    return $views;
}

add_filter('the_content', 'ipf_title_order_received', 10, 2);

function ipf_title_order_received($content)
{
    $ipf_confirmation_order_shortcode = ipf_get_option_submenu('ipf_confirmation_order_shortcode');
    // ipf_debug($ipf_confirmation_order_shortcode);
    if (
        function_exists('is_order_received_page') && is_order_received_page()
        && in_the_loop() && is_main_query()
    ) {
        if ($ipf_confirmation_order_shortcode)
            $content = do_shortcode($ipf_confirmation_order_shortcode) . $content;
    }
    return $content;
}
