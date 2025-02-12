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
            } elseif ($role == 'landtax') {
                redirect('controller_queueing/LandTax');
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
        redirect('controller_login');
    }
}
