<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SuperAdmin extends CI_Controller
{

    public function __construct()
    {
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
    public function index()
    {
        $data['users'] = $this->model_user->get_all_users();
        $this->load->view('superadmin/superadmin_view_superadmin', $data); // Now loading the correct view
    }

    // Create a new user
    public function create_user()
    {
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
    public function delete_user($id)
    {
        if ($id == $this->session->userdata('user_id')) {
            $this->session->set_flashdata('error', 'You cannot delete your own account.');
        } else {
            $this->model_user->delete_user($id);
            $this->session->set_flashdata('success', 'User deleted successfully!');
        }
        redirect('SuperAdmin');
    }

    // Logout Super Admin
    public function logout()
    {
        $this->session->unset_userdata(['user_id', 'username', 'role', 'logged_in']);
        $this->session->sess_destroy();
        redirect('controller_login');
    }
    public function edit_user($user_id = null)
    {
        // Check if user is logged in and has proper permissions
        if (!$this->session->userdata('logged_in') || $this->session->userdata('role') !== 'superadmin') {
            redirect('login');
        }

        // Validate user ID
        if (!$user_id || !is_numeric($user_id)) {
            $this->session->set_flashdata('error', 'Invalid user ID');
            redirect('SuperAdmin');
        }

        // Load the user model
        $this->load->model('model_user');

        // Get user data
        $data['user'] = $this->model_user->get_user_by_id($user_id);

        // Check if user exists
        if (!$data['user']) {
            $this->session->set_flashdata('error', 'User not found');
            redirect('SuperAdmin');
        }

        // Load the view
        $this->load->view('view_superadmin_edit', $data);
    }
    public function update_user()
    {
        // Check if user is logged in and has proper permissions
        if (!$this->session->userdata('logged_in') || $this->session->userdata('role') !== 'superadmin') {
            redirect('login');
        }

        // Get form data
        $user_id = $this->input->post('user_id');
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        $role = $this->input->post('role');
        $status = $this->input->post('status');
        $full_name = $this->input->post('full_name');
        $email = $this->input->post('email');
        $contact = $this->input->post('contact');
        $department = $this->input->post('department');
        $permissions = $this->input->post('permissions');

        // Validate required fields
        if (!$user_id || !$username || !$role) {
            $this->session->set_flashdata('error', 'User ID, username and role are required');
            redirect('SuperAdmin/edit_user/' . $user_id);
        }

        // Load the user model
        $this->load->model('model_user');

        // Prepare user data
        $user_data = array(
            'username' => $username,
            'role' => $role,
            'status' => $status,
            'full_name' => $full_name,
            'email' => $email,
            'contact' => $contact,
            'department' => $department
        );

        // Add password only if it's provided
        if (!empty($password)) {
            $user_data['password'] = password_hash($password, PASSWORD_BCRYPT);
        }

        // Add permissions if provided
        if ($permissions) {
            $user_data['permissions'] = json_encode($permissions);
        }

        // Update user
        $result = $this->model_user->update_user($user_id, $user_data);

        if ($result) {
            $this->session->set_flashdata('success', 'User successfully updated');
        } else {
            $this->session->set_flashdata('error', 'Failed to update user');
        }

        redirect('SuperAdmin');
    }
    // Delete all records from the queue table
    public function delete_all_queue()
    {
        // Load your model that handles the queue (create it if you don't have one)
        $this->load->model('model_queue'); // Create this model if not yet existing

        $result = $this->model_queue->delete_all();

        if ($result) {
            $this->session->set_flashdata('success', 'All queue entries deleted successfully!');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete queue entries.');
        }

        redirect('SuperAdmin'); // Redirect back to your SuperAdmin dashboard
    }
}
