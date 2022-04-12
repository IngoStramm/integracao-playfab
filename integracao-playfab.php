<?php

/**
 * Plugin Name: Integração Playfab
 * Plugin URI: https://agencialaf.com
 * Description: Descrição do Integração Playfab.
 * Version: 0.2.14
 * Author: Ingo Stramm
 * Text Domain: integracao-playfab
 * License: GPLv2
 */

defined('ABSPATH') or die('No script kiddies please!');

define('IPF_DIR', plugin_dir_path(__FILE__));
define('IPF_URL', plugin_dir_url(__FILE__));

function ipf_debug($debug)
{
    echo '<pre>';
    var_dump($debug);
    echo '</pre>';
}

require_once 'session.php';
require_once 'tgm/tgm.php';
require_once 'classes/classes.php';
require_once 'scripts.php';
require_once 'cmb.php';
require_once 'functions.php';
require_once 'authentication.php';
require_once 'order-details.php';
require_once 'woocommerce.php';

// add_action('wp_head', 'ipf_test');

function ipf_test()
{
    $user_id = get_current_user_id();
    // if (!$user_id)
    //     return;
    // global $ipf_playfab_api;
    // $ipf_playfab_user = new Ipf_Playfab_User($user_id, 'teste');
    $ipf_sessionticket = get_user_meta($user_id, 'ipf_sessionticket', true);

    $shop_page_url = get_permalink(wc_get_page_id('shop'));
    ipf_debug($shop_page_url);
}

require 'plugin-update-checker-4.10/plugin-update-checker.php';
$updateChecker = Puc_v4_Factory::buildUpdateChecker(
    'https://raw.githubusercontent.com/IngoStramm/integracao-playfab/master/info.json',
    __FILE__,
    'integracao-playfab'
);
