<?php
defined('BASEPATH') or exit('No direct script access allowed');

class controller_admin_landing extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('model_queueing');

        // Check if the user is logged in
        if (!$this->session->userdata('logged_in')) {
            redirect('controller_login'); // Redirect to login page
            exit;
        }

        // Get user role from session
        $this->user_role = strtolower($this->session->userdata('role'));
    }

    // Admin Landing Page (Only Admins can access)
    public function AdminLandingPage()
    {
        if ($this->user_role !== 'admin') {
            redirect('controller_login'); // Prevent unauthorized access
            exit;
        }

        $this->load->view('adminpanel/admin_view_landingpage');
    }

    // Logout function
    public function logout()
    {
        $this->session->sess_destroy();
        setcookie("logged_in", "", time() - 3600, "/"); // Expire the cookie
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Pragma: no-cache");
        header("Expires: 0");
        redirect('controller_login');
    }


    // Load views dynamically based on the link clicked
    public function loadView($view)
    {
        $data = [];

        // Load data for each view
        if ($view == 'LandTax') {
            $data['queue'] = $this->model_queueing->get_queue();
            $data['first'] = $this->model_queueing->get_first_in_queue();
            $content = $this->load->view('userpanel/user_view_landtax', $data, TRUE);
        } elseif ($view == 'BackRoom') {
            $data['backroom'] = $this->model_queueing->get_backroom();
            $content = $this->load->view('userpanel/user_view_backroom', $data, TRUE);
        } elseif ($view == 'Examiners') {
            $data['examiners'] = $this->model_queueing->get_examiners();
            $content = $this->load->view('userpanel/user_view_examiners', $data, TRUE);
        } elseif ($view == 'BusinessTax') {
            $data['businesstax'] = $this->model_queueing->get_businesstax();
            $content = $this->load->view('userpanel/user_view_businesstax', $data, TRUE);
        } elseif ($view == 'Payment') {
            $data['payment'] = $this->model_queueing->get_payment();
            $content = $this->load->view('userpanel/user_view_payment', $data, TRUE);
        } elseif ($view == 'fireprotection') {
            $data['fireprotection'] = $this->model_queueing->get_fireprotection();
            $content = $this->load->view('userpanel/user_view_fireprotection', $data, TRUE);
        } elseif ($view == 'Releasing') {
            $data['releasing'] = $this->model_queueing->get_releasing();
            $content = $this->load->view('userpanel/user_view_releasing', $data, TRUE);
        } else {
            $content = "<p class='text-red-500'>Invalid section selected.</p>";
        }

        // Pass the dynamic content to the main admin page
        $this->load->view('adminpanel/admin_view_landingpage', ['content' => $content]);
    }
}
