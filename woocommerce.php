<?php
// limita o carrinho a um produto por compra
add_filter('woocommerce_add_to_cart_validation', 'ipf_limit_one_per_order', 10, 2);

function ipf_limit_one_per_order($passed_validation, $product_id)
{

    if (WC()->cart->get_cart_contents_count() >= 1) {
        wc_add_notice(__('Só é possível comprar um produto por pedido.', 'ipf'), 'error');
        return false;
    }

    return $passed_validation;
}
