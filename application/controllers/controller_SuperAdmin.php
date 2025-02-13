<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SuperAdmin extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->library('session');
        $this->load->helper('url');

        // Check if the user is logged in and is a super admin
        if (!$this->session->userdata('role') || $this->session->userdata('role') !== 'super_admin') {
            redirect('login');
        }
    }

    // Show all users
    public function index() {
        $data['users'] = $this->User_model->get_all_users();
        $this->load->view('superadmin_view', $data);
    }

    // Create new user
    public function create_user() {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('username', 'Username', 'required|is_unique[users.username]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
        $this->form_validation->set_rules('role', 'Role', 'required|in_list[super_admin,admin,user]');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
        } else {
            $data = [
                'username' => $this->input->post('username'),
                'password' => password_hash($this->input->post('password'), PASSWORD_BCRYPT),
                'role' => $this->input->post('role')
            ];
            $this->User_model->insert_user($data);
            $this->session->set_flashdata('success', 'User created successfully!');
        }

        redirect('superadmin');
    }

    // Delete user
    public function delete_user($id) {
        $this->User_model->delete_user($id);
        $this->session->set_flashdata('success', 'User deleted successfully!');
        redirect('superadmin');
    }
}
?>
