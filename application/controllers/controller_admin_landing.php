<?php
defined('BASEPATH') or exit('No direct script access allowed');

class controller_admin_landing extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('model_queueing');

        if (!$this->session->userdata('logged_in')) {
            redirect('controller_login'); 
            exit;
        }

        $this->user_role = strtolower($this->session->userdata('role'));
    }

    public function AdminLandingPage()
    {
        if ($this->user_role !== 'admin') {
            redirect('controller_login'); 
            exit;
        }

        $this->load->view('adminpanel/admin_view_landingpage');
    }

    public function UserLandingPage()
    {
        $this->load->view('userpanel/user_view_landingpage');
    }



    public function logout()
    {
        $this->session->sess_destroy(); // Destroy session
        redirect('controller_login');   // Redirect to login page
    }
    // Load views dynamically based on the link clicked
    public function loadView($view)
    {
        $data = [];
        $content = '';
    
        switch ($view) {
            case 'LandTax':
                $data['queue'] = $this->model_queueing->get_queue();
                $data['first'] = $this->model_queueing->get_first_in_queue();
                $content = $this->load->view('adminpanel/admin_view_landtax', $data, TRUE);
                break;
            case 'BackRoom':
                $data['backroom'] = $this->model_queueing->get_backroom();
                $content = $this->load->view('adminpanel/admin_view_backroom', $data, TRUE);
                break;
            case 'Examiners':
                $data['examiners'] = $this->model_queueing->get_examiners();
                $content = $this->load->view('adminpanel/admin_view_examiners', $data, TRUE);
                break;
            case 'BusinessTax':
                $data['businesstax'] = $this->model_queueing->get_businesstax();
                $content = $this->load->view('adminpanel/admin_view_businesstax', $data, TRUE);
                break;
            case 'Payment':
                $data['payment'] = $this->model_queueing->get_payment();
                $content = $this->load->view('adminpanel/admin_view_payment', $data, TRUE);
                break;
            case 'FireProtection':
                $data['fireprotection'] = $this->model_queueing->get_fireprotection();
                $content = $this->load->view('adminpanel/admin_view_fireprotection', $data, TRUE);
                break;
            case 'Releasing':
                $data['releasing'] = $this->model_queueing->get_releasing();
                $content = $this->load->view('adminpanel/admin_view_releasing', $data, TRUE);
                break;
            default:
                $content = "<p class='text-red-500'>Invalid section selected.</p>";
        }
    
        $this->load->view('adminpanel/admin_view_landingpage', ['content' => $content]);
    }
}    
