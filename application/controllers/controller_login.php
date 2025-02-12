<?php
defined('BASEPATH') or exit('No direct script access allowed');

class controller_login extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('model_login');
    }

    
    public function index()
    {
        $this->load->view('welcome_message');
    }

}
