<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class User_Controller extends Public_Controller {

    function __construct() {
        parent::__construct();
        if (!$this->secure->get_user_session()->id) {
            redirect(site_url('/users/login'));
        }
    }

}

/* The Public_Controller class is autoloaded as required */
