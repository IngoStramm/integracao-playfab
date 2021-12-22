<?php

// Product
add_action('cmb2_admin_init', 'ipf_register_product_metabox');

function ipf_register_product_metabox()
{
    $cmb = new_cmb2_box(array(
        'id'            => 'ipf_product_metabox',
        'title'         => esc_html__('Opções Playfab', 'ipf'),
        'object_types'  => array('product'), // Post type
        'context'    => 'side',
    ));

    $cmb->add_field(array(
        'name'       => esc_html__('Moeda Virtual', 'ipf'),
        'id'         => 'ipf_product_virtual_currency_name',
        'type'       => 'select',
        'attributes'    => array(
            'required'  => 'required'
        ),
        'options'       => function () {
            $virtual_currencies = ipf_get_option('ipf_virtual_currencies');
            $return_array = [];

            foreach ($virtual_currencies as $virtual_currency) {
                $return_array[$virtual_currency] = $virtual_currency;
            }
            return $return_array ? $return_array : array('' => __('Nenhuma moeda virtual configurada.', 'ipf'));
        }
    ));

    $cmb->add_field(array(
        'name'       => esc_html__('Quantidade da Moeda Virtual', 'ipf'),
        'id'         => 'ipf_product_virtual_currency_qty',
        'type'       => 'text',
        'attributes'    => array(
            'type'      => 'number',
            'required'  => 'required'
        ),
    ));
}

// User profile
add_action('cmb2_admin_init', 'ipf_register_user_profile_metabox');

function ipf_register_user_profile_metabox()
{

    $cmb_user = new_cmb2_box(array(
        'id'               => 'ipf_user_edit',
        'title'            => esc_html__('Metaboxes do Perfil do Usuário', 'ipf'), // Doesn't output for user boxes
        'object_types'     => array('user'), // Tells CMB2 to use user_meta vs post_meta
        'show_names'       => true,
        'new_user_section' => 'add-new-user', // where form will show on new user page. 'add-existing-user' is only other valid option.
    ));

    $cmb_user->add_field(array(
        'name'     => esc_html__('Informação Extra', 'ipf'),
        'id'       => 'ipf_user_extra_info',
        'type'     => 'title',
        'on_front' => false,
    ));

    $cmb_user->add_field(array(
        'name'    => esc_html__('Playfab ID', 'ipf'),
        'id'      => 'ipf_playfabid',
        'type'    => 'text',
    ));

    $cmb_user->add_field(array(
        'name'    => esc_html__('Playfab Display Name', 'ipf'),
        'id'      => 'ipf_playfab_displayname',
        'type'    => 'text',
    ));

    $cmb_user->add_field(array(
        'name'    => esc_html__('Playfab E-mail', 'ipf'),
        'id'      => 'ipf_playfab_email',
        'type'    => 'text_email',
    ));

    $cmb_user->add_field(array(
        'name'    => esc_html__('SessionTicket', 'ipf'),
        'desc'    => esc_html__('Existe apenas se o usuário estiver logado', 'ipf'),
        'id'      => 'ipf_sessionticket',
        'type'    => 'textarea_small',
    ));
}

// Opções gerais
add_action('cmb2_admin_init', 'ipf_register_options_metabox');

function ipf_register_options_metabox()
{

    $cmb_options = new_cmb2_box(array(
        'id'           => 'ipf_option_metabox',
        'title'        => esc_html__('Integração Playfab', 'ipf'),
        'object_types' => array('options-page'),
        'option_key'      => 'ipf_options', // The option key and admin menu page slug.
        // 'icon_url'        => 'dashicons-palmtree', // Menu icon. Only applicable if 'parent_slug' is left empty.
        // 'menu_title'      => esc_html__( 'Options', 'ipf' ), // Falls back to 'title' (above).
        // 'parent_slug'     => 'themes.php', // Make options page a submenu item of the themes menu.
        // 'capability'      => 'manage_options', // Cap required to view options-page.
        // 'position'        => 1, // Menu position. Only applicable if 'parent_slug' is left empty.
        // 'admin_menu_hook' => 'network_admin_menu', // 'network_admin_menu' to add network-level options page.
        // 'display_cb'      => false, // Override the options-page form output (CMB2_Hookup::options_page_output()).
        // 'save_button'     => esc_html__( 'Save Theme Options', 'ipf' ), // The text for the options-page save button. Defaults to 'Save'.
    ));

    $cmb_options->add_field(
        array(
            'name' => __('Title ID', 'ipf'),
            'desc' => __('Title ID do jogo no Playfab', 'ipf'),
            'id'   => 'ipf_title_id',
            'type' => 'text',
            'attributes' => array(
                'required' => 'required'
            )
        )
    );

    $cmb_options->add_field(
        array(
            'name' => __('Secret Key', 'ipf'),
            'id'   => 'ipf_secret_key',
            'type' => 'text',
            'attributes' => array(
                'type'      => 'password',
                'required'  => 'required'
            )
        )
    );

    $cmb_options->add_field(
        array(
            'name' => __('Desativar autenticação pelo Playfab para as segunites funções de usuário:', 'ipf'),
            'desc' => __('Por padrão, a função de "administrador" não usa a autenticação do Playfab.', 'ipf'),
            'id'   => 'ipf_allowed_roles',
            'type' => 'multicheck',
            'options'  => function () {
                $roles = get_editable_roles();
                $return_array = [];
                foreach ($roles as $k => $role) {
                    if ($k !== 'administrator')
                        $return_array[$k] = translate_user_role($role['name']);
                }
                return $return_array;
            }
        )
    );


    $cmb_options->add_field(
        array(
            'name'          => __('Moedas Virtuais', 'ipf'),
            'desc'          => esc_html__('Nome da moeda virtual exatamente como está escrita no Playfab', 'ipf'),
            'id'            => 'ipf_virtual_currencies',
            'type'          => 'text',
            'repeatable'    => true
        )
    );
}

// Pega as opções gerais
function ipf_get_option($key = '', $default = false)
{
    if (function_exists('cmb2_get_option')) {
        // Use cmb2_get_option as it passes through some key filters.
        return cmb2_get_option('ipf_options', $key, $default);
    }

    // Fallback to get_option if CMB2 is not loaded yet.
    $opts = get_option('ipf_options', $default);

    $val = $default;

    if ('all' == $key) {
        $val = $opts;
    } elseif (is_array($opts) && array_key_exists($key, $opts) && false !== $opts[$key]) {
        $val = $opts[$key];
    }

    return $val;
}
