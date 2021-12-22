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
        const hide_text = 'Cancelar.';
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
        console.log(wc_login_form);
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

    toggle_rebind_playfab_account_form();
    rename_wc_login_form_label();


});