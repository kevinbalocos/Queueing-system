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
    public function delete_all_queue() {
        $this->load->model('model_queueing'); // Ensure this matches your model
    
        // Attempt to delete all queue entries
        $result = $this->model_queueing->delete_all();
    
        if ($result) {
            // Send a real-time WebSocket message about the queue deletion
            $message = json_encode([
                'status' => 'success',
                'action' => 'delete_all', // Action to identify the broadcast message
                'message' => 'All queue entries have been deleted.'
            ]);
    
            // Send the WebSocket message to all connected clients
            $this->sendWebSocketMessage($message);
    
            // Redirect to the SuperAdmin page with a success message
            $this->session->set_flashdata('success', 'All queue entries have been deleted successfully.');
            redirect('SuperAdmin');
        } else {
            // Redirect to the SuperAdmin page with an error message
            $this->session->set_flashdata('error', 'Failed to delete queue entries.');
            redirect('SuperAdmin');
        }
    }
    
    // WebSocket message sender method
    private function sendWebSocketMessage($message)
    {
        $sock = fsockopen("localhost", 8080); // Ensure WebSocket server is running on port 8080
    
        if ($sock) {
            fwrite($sock, $message); // Send the message to the WebSocket server
            fclose($sock); // Close the connection
        }
    }    
    
    public function search_ajax()
    {
        $this->load->model('model_queueing'); // or your actual model
        $search = $this->input->get('search');
    
        $users = $this->model_queueing->search_users($search);
    
        if (empty($users)) {
            echo "<tr><td colspan='4' class='px-6 py-4 text-center text-gray-400'>No users found.</td></tr>";
            return; // stop further execution
        }
    
        $roleColors = [
            'superadmin' => 'bg-purple-500',
            'admin' => 'bg-blue-500',
            'landtax' => 'bg-green-500',
            'releasing' => 'bg-yellow-500',
            'payment' => 'bg-pink-500',
            'backroom' => 'bg-indigo-500',
            'examiners' => 'bg-red-500',
            'businesstax' => 'bg-orange-500',
            'fireprotection' => 'bg-teal-500'
        ];
    
        foreach ($users as $user) {
            $roleColor = isset($roleColors[$user->role]) ? $roleColors[$user->role] : 'bg-gray-500';
    
            echo "<tr class='hover:bg-gray-700/50 transition'>
                <td class='px-6 py-4'>
                    <div class='flex items-center'>
                        <div class='bg-gray-700 rounded-full p-2 mr-3'>
                            <i class='fas fa-user text-gray-400'></i>
                        </div>
                        <div>
                            <div class='font-medium text-white'>" . htmlspecialchars($user->username) . "</div>
                            <div class='text-sm text-gray-400'>ID: {$user->id}</div>
                        </div>
                    </div>
                </td>
                <td class='px-6 py-4'>
                    <span class='inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {$roleColor}'>
                        " . ucfirst($user->role) . "
                    </span>
                </td>
                <td class='px-6 py-4'>
                    <span class='inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-500/20 text-green-400'>
                        <span class='h-2 w-2 rounded-full bg-green-400 mr-1.5'></span>
                        Active
                    </span>
                </td>
                <td class='px-6 py-4 text-center'>
                    <div class='flex justify-center space-x-3'>";
            if ($user->role !== 'superadmin') {
                echo "<button onclick=\"openEditModal({$user->id}, '" . htmlspecialchars($user->username) . "', '{$user->role}')\" class='text-blue-400 hover:text-blue-300 transition text-sm font-medium flex items-center'>
                        <i class='fas fa-edit mr-1'></i> Edit
                    </button>
                    <button onclick=\"confirmDelete('" . htmlspecialchars($user->username) . "', {$user->id})\" class='text-red-400 hover:text-red-300 transition text-sm font-medium flex items-center'>
                        <i class='fas fa-trash-alt mr-1'></i> Delete
                    </button>";
            } else {
                echo "<span class='text-gray-500 text-sm italic'>Protected</span>";
            }
            echo "</div></td></tr>";
        }
    }    
}
