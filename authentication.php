<?php

add_filter('authenticate', 'ipf_playfab_auth', 10, 3);

function ipf_playfab_auth($user, $username, $password)
{
    global $ipf_playfab_api;

    // Make sure a username and password are present for us to work with
    if ($username == '' || $password == '')
        return;

    $ipf_allowed_roles = ipf_get_option('ipf_allowed_roles');
    $ipf_allowed_roles[] = 'administrator';

    $wp_users = get_users();

    foreach ($wp_users as $wp_user) {
        if ($wp_user->user_login == $username || $wp_user->user_email == $username) {
            $check_role = array_intersect($ipf_allowed_roles, $wp_user->roles);
            if (!empty($check_role)) {
                return $user;
            }
        }
    }

    // Daqui
    /*
    
    // $authentication_url = get_site_url() . "/auth_serv.php?user=$username&pass=$password";
    $ipf_title_id = ipf_get_option('ipf_title_id');

    if (!$ipf_title_id) {
        $error_msg = __('Title ID não definido', 'ipf');
        return new WP_Error('denied', $error_msg);
        // return $user;
    }

    $authentication_url = 'https://' . $ipf_title_id . '.playfabapi.com/Client/LoginWithEmailAddress?Email=' . $username . '&Password=' . $password . '&TitleId=' . $ipf_title_id;

    $args = array(
        'method' => 'POST',
        'headers' => array(
            'Content-Type' => 'application/json'
        )
    );

    $response = wp_remote_get($authentication_url, $args);
    $ext_auth = json_decode($response['body'], true);

    // if ($ext_auth['result']  == 0) {
    if ($ext_auth['status']  !== 'OK') {
        // ipf_debug($ext_auth);
        // User does not exist,  send back an error message
        $error_msg = !filter_var($username, FILTER_VALIDATE_EMAIL) ? sprintf(__('"%s" não é um e-mail válido.', 'ipf'), $username) : __($ext_auth['errorMessage']);

        $user = new WP_Error('denied', $error_msg);
    } else {

    */
    // Até aqui

    $ipf_authenticate_user = $ipf_playfab_api->login_with_email($username, $password);

    if (!$ipf_authenticate_user instanceof Ipf_Playfab_User) {
        $user = new WP_Error('denied', $ipf_authenticate_user);
    } else {

        $userobj = new WP_User();
        $user = $userobj->get_data_by('email', $username); // Does not return a WP_User object :(
        $user = new WP_User($user->ID); // Attempt to load up the user with that ID

        if ($user->ID == 0) {
            // The user does not currently exist in the WordPress user table.
            // You have arrived at a fork in the road, choose your destiny wisely

            // If you do not want to add new users to WordPress if they do not
            // already exist uncomment the following line and remove the user creation code
            //$user = new WP_Error( 'denied', __("ERROR: Not a valid user for this system") );

            // Setup the minimum required user information for this example
            $userdata = array(
                'user_email' => $username,
                'user_login' => $username,
            );
            $new_user_id = wp_insert_user($userdata); // A new user has been created

            // Load the new user info
            $user = new WP_User($new_user_id);
        }

        $get_user_info_url = 'https://' . $ipf_playfab_api->get_title_id() . '.playfabapi.com/Admin/GetUserAccountInfo?Email=' . $username;
        $args = array(
            'method' => 'POST',
            'headers' => array(
                'Content-Type'  => 'application/json',
                'X-SecretKey'   => $ipf_playfab_api->get_secret_key()
            )
        );

        $response = wp_remote_get($get_user_info_url, $args);
        $playfab_user_info = json_decode($response['body'], true);

        if ($playfab_user_info['status']  === 'OK') {
            $ipf_playfab_displayname = $playfab_user_info['data']['UserInfo']['TitleInfo']['DisplayName'];
            update_user_meta($user->ID, 'ipf_playfab_displayname', $ipf_playfab_displayname);
            wp_update_user(array('ID' => $user->ID, 'display_name' => $ipf_playfab_displayname));
        }

        update_user_meta($user->ID, 'ipf_playfabid', $ipf_authenticate_user->get_id());
        update_user_meta($user->ID, 'ipf_playfab_email', $username);
        update_user_meta($user->ID, 'ipf_sessionticket', $ipf_authenticate_user->get_session_ticket());
    }

    // Daqui

    /*
    $playfabId = $ext_auth['data']['PlayFabId'];
    $SessionTicket = $ext_auth['data']['SessionTicket'];
    if ($playfabId)
        update_user_meta($user->ID, 'ipf_playfabid', $playfabId);
    if ($SessionTicket)
        // $_SESSION['ipf_sessionticket'] = $SessionTicket;
        update_user_meta($user->ID, 'ipf_sessionticket', $SessionTicket);
    */

    // Até aqui

    // Comment this line if you wish to fall back on WordPress authentication
    // Useful for times when the external service is offline
    remove_action('authenticate', 'wp_authenticate_username_password', 20);

    return $user;
}

add_action('wp_logout', 'ipf_delete_sessionticket', 10, 1);

function ipf_delete_sessionticket($user_id)
{
    delete_user_meta($user_id, 'ipf_sessionticket');
}
