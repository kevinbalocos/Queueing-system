<?php
defined('BASEPATH') or exit('No direct script access allowed');

class controller_admin_landing extends CI_Controller
{
    protected $user_role;
    protected $default_view = 'LandTax';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('model_queueing');
        $this->load->helper('url');

        // Check if user is logged in
        if (!$this->session->userdata('logged_in')) {
            redirect('controller_login');
            exit;
        }

        $this->user_role = strtolower($this->session->userdata('role'));
    }

    /**
     * Main landing page that loads with a specific view section
     * 
     * @param string $active_view The section to display (defaults to stored preference or LandTax)
     */
    public function index($active_view = null)
    {
        if ($this->user_role !== 'admin') {
            redirect('controller_login');
            exit;
        }

        // If no active view specified, check for stored preference or use default
        if ($active_view === null) {
            $active_view = $this->session->userdata('last_active_view') ?: $this->default_view;
        }

        // Store current view preference for future page loads
        $this->session->set_userdata('last_active_view', $active_view);

        // Prepare view data
        $view_data = $this->_get_section_data($active_view);
        $view_data['active_view'] = $active_view;
        $view_data['user'] = $this->_get_user_data();

        // Load the main template with the section content
        $this->load->view('adminpanel/admin_view_landingpage', $view_data);
    }

    /**
     * Admin landing page - redirects to index for consistency
     */
    public function AdminLandingPage()
    {
        redirect('controller_admin_landing/index');
    }

    /**
     * User landing page
     */
    public function UserLandingPage()
    {
        $this->load->view('userpanel/user_view_landingpage');
    }

    /**
     * Handle logout action
     */
    public function logout()
    {
        $this->session->sess_destroy();
        redirect('controller_login');
    }

    /**
     * Load a specific view section either via AJAX or direct page load
     * 
     * @param string $view The section name to load
     */


    public function Dashboard()
    {
        $data['landtax'] = $this->model_queueing->get_queue();
        $data['backroom'] = $this->model_queueing->get_backroom();
        $data['examiner'] = $this->model_queueing->get_examiners();
        $data['businesstax'] = $this->model_queueing->get_businesstax();
        $data['payment'] = $this->model_queueing->get_payment();
        $data['fireprotection'] = $this->model_queueing->get_fireprotection();
        $data['releasing'] = $this->model_queueing->get_releasing();
        $this->load->view('adminpanel/admin_view_dashboard', $data);
    }
    public function loadView($view)
    {
        // Check if valid section
        $valid_sections = [
            'LandTax',
            'BackRoom',
            'Examiners',
            'BusinessTax',
            'Payment',
            'FireProtection',
            'Releasing',
            'Dashboard'
        ];

        if (!in_array($view, $valid_sections)) {
            if ($this->input->is_ajax_request()) {
                echo "<p class='text-red-500'>Invalid section selected.</p>";
                return;
            } else {
                redirect('controller_admin_landing');
                return;
            }
        }

        // Get the data for the requested section
        $data = $this->_get_section_data($view);

        if ($this->input->is_ajax_request()) {
            // If AJAX request, return just the section content
            $this->load->view("adminpanel/admin_view_{$view}", $data);
        } else {
            // If direct URL access, store preference and redirect to main page
            $this->session->set_userdata('last_active_view', $view);
            redirect('controller_admin_landing/index/' . $view);
        }
    }

    /**
     * Get all data needed for a specific section
     * 
     * @param string $section The section name
     * @return array The data for the section
     */
    private function _get_section_data($section)
    {
        $data = [];

        switch (strtolower($section)) {
            case 'landtax':
                $data['queue'] = $this->model_queueing->get_queue();
                $data['first'] = $this->model_queueing->get_first_in_queue();
                break;
            case 'backroom':
                $data['backroom'] = $this->model_queueing->get_backroom();
                break;
            case 'examiners':
                $data['examiners'] = $this->model_queueing->get_examiners();
                break;
            case 'businesstax':
                $data['businesstax'] = $this->model_queueing->get_businesstax();
                break;
            case 'payment':
                $data['payment'] = $this->model_queueing->get_payment();
                break;
            case 'fireprotection':
                $data['fireprotection'] = $this->model_queueing->get_fireprotection();
                break;
            case 'releasing':
                $data['releasing'] = $this->model_queueing->get_releasing();
                break;
            case 'dashboard':
                // Any dashboard-specific data can go here
                // Example: $data['dashboard_stats'] = $this->model_queueing->get_dashboard_stats();
                break;

        }

        // Load content of specific view section
        $content = '';
        if ($section) {
            ob_start();
            $this->load->view("adminpanel/admin_view_" . strtolower($section), $data);
            $content = ob_get_clean();
        }

        $data['content'] = $content;
        return $data;
    }

    /**
     * Get current user data for the view
     * 
     * @return array User data
     */
    private function _get_user_data()
    {
        $user = [
            'username' => $this->session->userdata('username'),
            'role' => $this->user_role,
            'uploaded_profile_image' => $this->session->userdata('profile_image') ?: ''
        ];

        return $user;
    }
}