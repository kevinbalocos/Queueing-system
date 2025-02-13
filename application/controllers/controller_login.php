<?php
defined('BASEPATH') or exit('No direct script access allowed');

class controller_login extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('model_login');
        $this->load->library('session');
    }

    public function index()
    {
        // Check if user is already logged in
        if ($this->session->userdata('logged_in')) {
            // Redirect based on their role
            $role = strtolower($this->session->userdata('role'));

            if ($role == 'admin') {
                redirect('controller_admin_landing/AdminLandingPage');
            } elseif ($role == 'backroom') {
                redirect('controller_queueing/BackRoom');
            } elseif ($role == 'examiners') {
                redirect('controller_queueing/Examiners');
            } elseif ($role == 'businesstax') {
                redirect('controller_queueing/BusinessTax');
            } elseif ($role == 'payment') {
                redirect('controller_queueing/Payment');
            } elseif ($role == 'fireprotection') {
                redirect('controller_queueing/fireprotection');
            } elseif ($role == 'landtax') {
                redirect('controller_queueing/LandTax');
            } elseif ($role == 'releasing') {
                redirect('controller_queueing/Releasing');
            } else {
                redirect('controller_admin_landing/AdminLandingPage'); // Default redirection
            }
        }

        // Show the login page if not logged in
        $this->load->view('userlogin/user_view_login');
    }


    public function login_process()
    {
        $username = $this->input->post('username');
        $password = $this->input->post('password');

        $user = $this->model_login->check_login($username, $password);

        if ($user) {
            $session_data = [
                'user_id' => $user->id,
                'username' => $user->username,
                'role' => $user->role,
                'logged_in' => TRUE
            ];
            $this->session->set_userdata($session_data);

            // Convert role to lowercase to prevent case-sensitive issues
            $role = strtolower($user->role);

            if ($role == 'admin') {
                redirect('controller_admin_landing/AdminLandingPage');
            } elseif ($role == 'backroom') {
                redirect('controller_queueing/BackRoom');
            } elseif ($role == 'examiners') {
                redirect('controller_queueing/Examiners');
            } elseif ($role == 'businesstax') {
                redirect('controller_queueing/BusinessTax');
            } elseif ($role == 'payment') {
                redirect('controller_queueing/Payment');
            } elseif ($role == 'fireprotection') {
                redirect('controller_queueing/fireprotection');
            } elseif ($role == 'landtax') {
                redirect('controller_queueing/LandTax');
            } elseif ($role == 'releasing') {
                redirect('controller_queueing/Releasing');
            } else {
                redirect('controller_login'); // Default redirection
            }
        } else {
            $this->session->set_flashdata('error', 'Invalid username or password.');
            redirect('controller_login');
        }
    }


    public function logout()
    {
        $this->session->sess_destroy();
        setcookie("logged_in", "", time() - 3600, "/"); // Expire the cookie
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Pragma: no-cache");
        header("Expires: 0");
        redirect('controller_login');
    }

}
