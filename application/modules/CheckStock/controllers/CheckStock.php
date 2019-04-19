<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class CheckStock extends CI_Controller {

    function __construct() {

        parent::__construct();
        if (checkPermission('YoungPerson', 'view') == false) {
            redirect('/Dashboard');
        }
       
    }

}
