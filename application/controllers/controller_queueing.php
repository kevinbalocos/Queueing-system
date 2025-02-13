<?php
defined('BASEPATH') or exit('No direct script access allowed');

class controller_queueing extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('model_queueing');
        // Load the session library so that $this->session is available
        $this->load->library('session');
    }

    // Load Land Tax Queueing View
    public function LandTax()
    {
        $data['queue'] = $this->model_queueing->get_queue();
        $data['first'] = $this->model_queueing->get_first_in_queue();
        $this->load->view('userpanel/user_view_landtax', $data);
    }

    // Load Backroom View
    public function BackRoom()
    {
        $data['backroom'] = $this->model_queueing->get_backroom();
        $this->load->view('userpanel/user_view_backroom', $data);
    }

    // Load Examiners View
    public function Examiners()
    {
        $data['examiners'] = $this->model_queueing->get_examiners();
        $this->load->view('userpanel/user_view_examiners', $data);
    }

    public function BusinessTax()
    {
        $data['businesstax'] = $this->model_queueing->get_businesstax();
        $this->load->view('userpanel/user_view_businesstax', $data);
    }

    public function Payment()
    {
        $data['payment'] = $this->model_queueing->get_payment();
        $this->load->view('userpanel/user_view_payment', $data);
    }

    public function fireprotection()
    {
        $data['fireprotection'] = $this->model_queueing->get_fireprotection();
        $this->load->view('userpanel/user_view_fireprotection', $data);
    }

    public function Releasing()
    {
        $data['releasing'] = $this->model_queueing->get_releasing();
        $this->load->view('userpanel/user_view_releasing', $data);
    }

    // Add to Land Tax Queue
    public function add_to_queue()
    {
        $name = $this->input->post('name');
        $reason = $this->input->post('reason'); // Get reason from form input

        $this->model_queueing->add_to_queue($name, $reason);
        $this->session->set_flashdata('success', 'Queue item added successfully to Land Tax.');
        // Assume the add form includes a hidden input or query parameter "current_view=LandTax"
        $current_view = $this->input->get('current_view') ? $this->input->get('current_view') : 'LandTax';
        redirect("controller_admin_landing/loadView/{$current_view}");
    }


    // Add to Backroom Queue
    public function add_to_backroom()
    {
        $name = $this->input->post('name');
        $reason = $this->input->post('reason');

        $this->model_queueing->add_to_backroom($name, $reason);
        $this->session->set_flashdata('success', 'Queue item added successfully to Backroom.');
        $current_view = $this->input->get('current_view') ? $this->input->get('current_view') : 'BackRoom';
        redirect("controller_admin_landing/loadView/{$current_view}");
    }

    // Add to Examiners Queue
    public function add_to_examiners()
    {
        $name = $this->input->post('name');
        $reason = $this->input->post('reason');

        $this->model_queueing->add_to_examiners($name, $reason);
        $this->session->set_flashdata('success', 'Queue item added successfully to Examiners.');
        $current_view = $this->input->get('current_view') ? $this->input->get('current_view') : 'Examiners';
        redirect("controller_admin_landing/loadView/{$current_view}");
    }

    // Add to Business Tax Queue
    public function add_to_businesstax()
    {
        $name = $this->input->post('name');
        $reason = $this->input->post('reason');

        $this->model_queueing->add_to_businesstax($name, $reason);
        $this->session->set_flashdata('success', 'Queue item added successfully to Business Tax.');
        $current_view = $this->input->get('current_view') ? $this->input->get('current_view') : 'BusinessTax';
        redirect("controller_admin_landing/loadView/{$current_view}");
    }

    // Add to Payment Queue
    public function add_to_payment()
    {
        $name = $this->input->post('name');
        $reason = $this->input->post('reason');

        $this->model_queueing->add_to_payment($name, $reason);
        $this->session->set_flashdata('success', 'Queue item added successfully to Payment.');
        $current_view = $this->input->get('current_view') ? $this->input->get('current_view') : 'Payment';
        redirect("controller_admin_landing/loadView/{$current_view}");
    }

    // Add to Fire Protection Queue
    public function add_to_fireprotection()
    {
        $name = $this->input->post('name');
        $reason = $this->input->post('reason');

        $this->model_queueing->add_to_fireprotection($name, $reason);
        $this->session->set_flashdata('success', 'Queue item added successfully to Fire Protection.');
        $current_view = $this->input->get('current_view') ? $this->input->get('current_view') : 'fireprotection';
        redirect("controller_admin_landing/loadView/{$current_view}");
    }

    // Proceed functions with flashdata notifications and redirect

    public function proceed_to_backroom($id)
    {
        $this->model_queueing->proceed_queue($id, 'backroom');
        $this->session->set_flashdata('success', 'Queue item successfully proceeded to Backroom.');
        $current_view = $this->input->get('current_view') ? $this->input->get('current_view') : 'LandTax';
        redirect("controller_admin_landing/loadView/{$current_view}");
    }


    public function proceed_to_examiners($id)
    {
        $this->model_queueing->proceed_queue($id, 'examiner');
        $this->session->set_flashdata('success', 'Queue item successfully proceeded to Examiners.');
        $current_view = $this->input->get('current_view') ? $this->input->get('current_view') : 'BackRoom';
        redirect("controller_admin_landing/loadView/{$current_view}");
    }

    public function proceed_to_businesstax($id)
    {
        $this->model_queueing->proceed_queue($id, 'businesstax');
        $this->session->set_flashdata('success', 'Queue item successfully proceeded to Business Tax.');
        $current_view = $this->input->get('current_view') ? $this->input->get('current_view') : 'Examiners';
        redirect("controller_admin_landing/loadView/{$current_view}");
    }

    public function proceed_to_payment($id)
    {
        $this->model_queueing->proceed_queue($id, 'payment');
        $this->session->set_flashdata('success', 'Queue item successfully proceeded to Payment.');
        $current_view = $this->input->get('current_view') ? $this->input->get('current_view') : 'BusinessTax';
        redirect("controller_admin_landing/loadView/{$current_view}");
    }

    public function proceed_to_fireprotection($id)
    {
        $this->model_queueing->proceed_queue($id, 'fireprotection');
        $this->session->set_flashdata('success', 'Queue item successfully proceeded to Fire Protection.');
        $current_view = $this->input->get('current_view') ? $this->input->get('current_view') : 'Payment';
        redirect("controller_admin_landing/loadView/{$current_view}");
    }

    public function proceed_to_releasing($id)
    {
        $this->model_queueing->proceed_queue($id, 'releasing');
        $this->session->set_flashdata('success', 'Queue item successfully proceeded to Releasing.');
        $current_view = $this->input->get('current_view') ? $this->input->get('current_view') : 'fireprotection';
        redirect("controller_admin_landing/loadView/{$current_view}");
    }
    // private function sendToWebSocket($data)
    // {
    //     $socket = fsockopen("localhost", 8080); // Connect to WebSocket server
    //     if ($socket) {
    //         fwrite($socket, json_encode($data));
    //         fclose($socket);
    //     }
    // }

}
