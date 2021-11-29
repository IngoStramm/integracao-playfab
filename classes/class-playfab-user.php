<?php

class Ipf_Playfab_User
{
    protected $playfab_user_id;
    protected $session_ticket;

    public function __construct($playfab_user_id, $session_ticket)
    {
        $this->playfab_user_id = $playfab_user_id;
        $this->session_ticket = $session_ticket;
    }

    public function get_id()
    {
        return $this->playfab_user_id;
    }

    public function get_session_ticket()
    {
        return $this->session_ticket;
    }
}
