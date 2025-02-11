<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class controller_queueing extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('model_queueing');
    }

    // Load Land Tax Queueing View
    public function LandTax() {
        $data['queue'] = $this->model_queueing->get_queue();
        $data['first'] = $this->model_queueing->get_first_in_queue();
        $this->load->view('userpanel/user_view_landtax', $data);
    }

    // Load Backroom View
    public function BackRoom() {
        $data['backroom'] = $this->model_queueing->get_backroom();
        $this->load->view('userpanel/user_view_backroom', $data);
    }

    // Load Examiners View
    public function Examiners() {
        $data['examiners'] = $this->model_queueing->get_examiners();
        $this->load->view('userpanel/user_view_examiners', $data);
    }

    public function BusinessTax() {
        $data['businesstax'] = $this->model_queueing->get_businesstax();
        $this->load->view('userpanel/user_view_businesstax', $data);
    }
    
    public function Payment() {
        $data['payment'] = $this->model_queueing->get_payment();
        $this->load->view('userpanel/user_view_payment', $data);
    }
    
    public function fireprotection() {
        $data['fireprotection'] = $this->model_queueing->get_fireprotection();
        $this->load->view('userpanel/user_view_fireprotection', $data);
    }
    
    public function Releasing() {
        $data['releasing'] = $this->model_queueing->get_releasing();
        $this->load->view('userpanel/user_view_releasing', $data);
    }
    
    // Add to Queue
    public function add_to_queue() {
        $name = $this->input->post('name');
        $this->model_queueing->add_to_queue($name);
        redirect('controller_queueing/LandTax');
    }

    // Proceed from queue to backroom
    public function proceed_to_backroom($id) {
        $this->model_queueing->proceed_queue($id, 'backroom');
        redirect('controller_queueing/LandTax');
    }

    // Proceed from backroom to examiners
    public function proceed_to_examiners($id) {
        $this->model_queueing->proceed_queue($id, 'examiner');
        redirect('controller_queueing/BackRoom');
    }
    // Proceed from Examiners to Business Tax
public function proceed_to_businesstax($id) {
    $this->model_queueing->proceed_queue($id, 'businesstax');
    redirect('controller_queueing/Examiners');
}

// Proceed from Business Tax to Payment
public function proceed_to_payment($id) {
    $this->model_queueing->proceed_queue($id, 'payment');
    redirect('controller_queueing/BusinessTax');
}

// Proceed from Payment to Fire Protection
public function proceed_to_fireprotection($id) {
    $this->model_queueing->proceed_queue($id, 'fireprotection');
    redirect('controller_queueing/Payment');
}

// Proceed from Fire Protection to Releasing
public function proceed_to_releasing($id) {
    $this->model_queueing->proceed_queue($id, 'releasing');
    redirect('controller_queueing/fireprotection');
}

}
