<?php
defined('BASEPATH') or exit('No direct script access allowed');

class controller_login extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('model_login');
        $this->load->library('session');
        $this->load->helper('url');
    }

    public function index()
    {
        // Check if user is already logged in
        if ($this->session->userdata('logged_in')) {
            $role = strtolower($this->session->userdata('role'));

            switch ($role) {
                case 'superadmin':
                    redirect('SuperAdmin');
                    break;
                case 'admin':
                    redirect('controller_admin_landing/AdminLandingPage');
                    break;
                case 'backroom':
                    redirect('controller_queueing/BackRoom');
                    break;
                case 'examiners':
                    redirect('controller_queueing/Examiners');
                    break;
                case 'businesstax':
                    redirect('controller_queueing/BusinessTax');
                    break;
                case 'payment':
                    redirect('controller_queueing/Payment');
                    break;
                case 'fireprotection':
                    redirect('controller_queueing/FireProtection');
                    break;
                case 'landtax':
                    redirect('controller_queueing/LandTax');
                    break;
                case 'releasing':
                    redirect('controller_queueing/Releasing');
                    break;
                default:
                    redirect('controller_admin_landing/AdminLandingPage');
                    break;
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

            // Redirect based on role
            switch (strtolower($user->role)) {
                case 'superadmin':
                    redirect('SuperAdmin'); // Now redirects correctly to Super Admin dashboard
                    break;
                case 'admin':
                    redirect('controller_admin_landing/AdminLandingPage');
                    break;
                case 'landtax':
                    redirect('controller_queueing/LandTax');
                    break;
                case 'releasing':
                    redirect('controller_queueing/Releasing');
                    break;
                default:
                    redirect('controller_login'); // Default redirection
                    break;
            }
        } else {
            $this->session->set_flashdata('error', 'Invalid username or password.');
            redirect('controller_login');
        }
    }

    public function logout()
    {
        $this->session->sess_destroy();
        setcookie("logged_in", "", time() - 3600, "/");
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Pragma: no-cache");
        header("Expires: 0");
        redirect('controller_login');
    }
}
