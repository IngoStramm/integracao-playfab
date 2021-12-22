<?php
class Ipf_Playfab_Api
{
    protected $title_id;
    protected $secret_key;
    protected $error_msg;

    public function __construct()
    {
        // $this->title_id = $title_id;
        // $this->secret_key = $secret_key;
        $this->error_msg = null;
    }

    public function get_title_id() {
        return $this->title_id;
    }

    public function set_title_id($title_id)
    {
        $this->title_id = $title_id;
    }

    public function get_secret_key()
    {
        return $this->secret_key;
    }

    public function set_secret_key($secret_key)
    {
        $this->secret_key = $secret_key;
    }

    public function login_with_email($username, $password)
    {
        if (!$this->title_id) {
            $this->error_msg = __('Title ID não definido', 'ipf');
            return $this->error_msg;
        }

        $args = array(
            'method' => 'POST',
            'headers' => array(
                'Content-Type' => 'application/json'
            )
        );

        $authentication_url = 'https://' . $this->title_id . '.playfabapi.com/Client/LoginWithEmailAddress?Email=' . $username . '&Password=' . $password . '&TitleId=' . $this->title_id;

        $response = wp_remote_get($authentication_url, $args);
        $ext_auth = json_decode($response['body'], true);

        if ($ext_auth['status']  !== 'OK') {
            // ipf_debug($ext_auth);
            // User does not exist,  send back an error message
            $this->error_msg = !filter_var($username, FILTER_VALIDATE_EMAIL) ? sprintf(__('"%s" não é um e-mail válido.', 'ipf'), $username) : __($ext_auth['errorMessage']);
            return $this->error_msg;
        }

        $returned_id = $ext_auth['data']['PlayFabId'];
        if (!$returned_id) {
            $this->error_msg = __('Não foi possível definir o ID do usuário Playfab.', 'ipf');
            return $this->error_msg;
        }

        $returned_session_ticket = $ext_auth['data']['SessionTicket'];
        if (!$returned_session_ticket) {
            $this->error_msg = __('Não foi possível definir o Session Ticket do usuário Playfab.', 'ipf');
            return $this->error_msg;
        }

        $playfab_user = new Ipf_Playfab_User($returned_id, $returned_session_ticket);
        return $playfab_user;
    }
}

$ipf_playfab_api = new Ipf_Playfab_Api;
