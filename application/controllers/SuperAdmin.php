<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SuperAdmin extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('model_user');
        $this->load->library(['session', 'form_validation']);
        $this->load->helper('url');

        // Check if the user is logged in and is a super admin
        if (!$this->session->userdata('logged_in') || strtolower($this->session->userdata('role')) !== 'superadmin') {
            redirect('controller_login'); // Redirect to login if unauthorized
        }
    }

    // Show Super Admin dashboard
    public function index() {
        $data['users'] = $this->model_user->get_all_users();
        $this->load->view('superadmin/superadmin_view_superadmin', $data); // Now loading the correct view
    }

    // Create a new user
    public function create_user() {
        $this->form_validation->set_rules('username', 'Username', 'required|is_unique[users.username]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
        $this->form_validation->set_rules('role', 'Role', 'required|in_list[admin,superadmin,landtax,releasing,payment,backroom,examiners,businesstax,fireprotection]');
    
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
        } else {
            $data = [
                'username' => $this->input->post('username'),
                'password' => password_hash($this->input->post('password'), PASSWORD_BCRYPT), // Hash password
                'role' => $this->input->post('role')
            ];
            $this->model_user->insert_user($data);
            $this->session->set_flashdata('success', 'User created successfully!');
        }
    
        redirect('SuperAdmin');
    }    

    // Delete a user
    public function delete_user($id) {
        if ($id == $this->session->userdata('user_id')) {
            $this->session->set_flashdata('error', 'You cannot delete your own account.');
        } else {
            $this->User_model->delete_user($id);
            $this->session->set_flashdata('success', 'User deleted successfully!');
        }
        redirect('SuperAdmin');
    }

    // Logout Super Admin
    public function logout() {
        $this->session->unset_userdata(['user_id', 'username', 'role', 'logged_in']);
        $this->session->sess_destroy();
        redirect('controller_login'); 
    }    
}
