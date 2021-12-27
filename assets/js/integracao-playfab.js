// integracao-playfab

document.addEventListener('DOMContentLoaded', function () {
    function toggle_rebind_playfab_account_form() {
        const toggle_ipf_form = document.getElementById('toggle-rebind-playfab-account-form');
        if (typeof (toggle_ipf_form) === 'undefined' || toggle_ipf_form === null) {
            return;
        }
        const ipf_form = document.getElementById('rebind-playfab-account-form');
        if (typeof (ipf_form) === 'undefined' || ipf_form === null) {
            return;
        }
        const show_text = toggle_ipf_form.innerText;
        const hide_text = 'Cancelar';
        toggle_ipf_form.addEventListener('click', function (e) {
            e.preventDefault();
            if (ipf_form.style.display === 'none') {
                toggle_ipf_form.innerText = hide_text;
                ipf_form.style.display = 'block';
            } else {
                toggle_ipf_form.innerText = show_text;
                ipf_form.style.display = 'none';
            }
        });
    }

    function rename_wc_login_form_label() {
        const wc_login_form = document.querySelector('.woocommerce-form.woocommerce-form-login.login');
        // console.log(wc_login_form);
        if (typeof (wc_login_form) === 'undefined' || wc_login_form === null) {
            return;
        }
        const wc_login_form_labels = wc_login_form.querySelectorAll('label');
        for (i = 0; i < wc_login_form_labels.length; i++) {
            if (wc_login_form_labels[i].innerText === 'Nome de usuário ou e-mail *') {
                wc_login_form_labels[i].innerText = 'E-mail usado no cadastro do jogo *';
            }
        }

    }

    function change_grid_products_url() {
        const ipf_products_grid = document.getElementById('ipf-products-grid');
        if (typeof (ipf_products_grid) === 'undefined' || ipf_products_grid === null) {
            return;
        }
        const ipf_products_grid_product = ipf_products_grid.querySelectorAll('li.product');
        for (i = 0; i < ipf_products_grid_product.length; i++) {
            // console.log(ipf_products_grid_product[i]);
            const add_to_cart_button = ipf_products_grid_product[i].querySelector('a.add_to_cart_button');
            const new_product_url = add_to_cart_button.href;
            const product_links = ipf_products_grid_product[i].querySelectorAll('a');
            for (x = 0; x < product_links.length; x++) {
                // console.log(product_links[x]);
                product_links[x].href = new_product_url;
            }
        }
    }

    change_grid_products_url();
    toggle_rebind_playfab_account_form();
    rename_wc_login_form_label();

});