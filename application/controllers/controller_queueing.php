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
    public function BigScreen()
    {
        $data['queue'] = $this->model_queueing->get_queue();
        $data['first'] = $this->model_queueing->get_first_in_queue();
        $data['backroom'] = $this->model_queueing->get_backroom();
        $data['examiners'] = $this->model_queueing->get_examiners();
        $data['businesstax'] = $this->model_queueing->get_businesstax();
        $data['payment'] = $this->model_queueing->get_payment();
        $data['fireprotection'] = $this->model_queueing->get_fireprotection();

        $this->load->view('adminpanel/admin_view_bigscreen', $data);
    }



    //
    public function AdminLandTax()
    {
        $data['queue'] = $this->model_queueing->get_queue();
        $data['first'] = $this->model_queueing->get_first_in_queue();
        $this->load->view('adminpanel/admin_view_landtax', $data);
    }

    // Load Backroom View
    public function AdminBackRoom()
    {
        $data['backroom'] = $this->model_queueing->get_backroom();
        $this->load->view('adminpanel/admin_view_backroom', $data);
    }

    // Load Examiners View
    public function AdminExaminers()
    {
        $data['examiners'] = $this->model_queueing->get_examiners();
        $this->load->view('adminpanel/admin_view_examiners', $data);
    }

    public function AdminBusinessTax()
    {
        $data['businesstax'] = $this->model_queueing->get_businesstax();
        $this->load->view('adminpanel/admin_view_businesstax', $data);
    }

    public function AdminPayment()
    {
        $data['payment'] = $this->model_queueing->get_payment();
        $this->load->view('adminpanel/admin_view_payment', $data);
    }

    public function Adminfireprotection()
    {
        $data['fireprotection'] = $this->model_queueing->get_fireprotection();
        $this->load->view('adminpanel/admin_view_fireprotection', $data);
    }

    public function AdminReleasing()
    {
        $data['releasing'] = $this->model_queueing->get_releasing();
        $this->load->view('adminpanel/admin_view_releasing', $data);
    }

    // Add to Land Tax Queue
    public function add_to_queue()
    {
        $name = $this->input->post('name');
        $reason = $this->input->post('reason');

        // Add to the queue
        $new_queue_number = $this->model_queueing->add_to_queue($name, $reason);

        if ($new_queue_number) {
            // Fetch the updated queue items
            $queue = $this->model_queueing->get_queue();
            $left_items = array_slice($queue, 0, 20);
            $right_items = array_slice($queue, 20);

            // Send the updated data to the WebSocket clients
            $new_item = [
                'queue_number' => $new_queue_number,
                'name' => $name,
                'reason' => $reason,
                'left_items' => $left_items,
                'right_items' => $right_items
            ];
            $this->send_to_websocket($new_item);

            // Return a JSON response
            echo json_encode([
                'status' => 'success',
                'message' => 'Queue item added successfully.',
                'queue' => [
                    'left_items' => $left_items,
                    'right_items' => $right_items
                ]
            ]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to add to the queue.']);
        }
    }



    // Function to send the new queue item to WebSocket clients
    private function send_to_websocket($new_item)
    {
        // Create a new WebSocket client to send the message
        $ws_client = new WebSocketClient(); // You may need to implement or use a library to send the message
        $ws_client->send(json_encode($new_item));
    }


    
    public function add_to_backroom()
    {
        $name = $this->input->post('name');
        $reason = $this->input->post('reason');

        $this->model_queueing->add_to_backroom($name, $reason);

        // Send JSON response
        echo json_encode(['status' => 'success', 'message' => 'Queue item added successfully to Backroom.']);
    }

    public function add_to_examiners()
    {
        $name = $this->input->post('name');
        $reason = $this->input->post('reason');

        $this->model_queueing->add_to_examiners($name, $reason);

        // Send JSON response
        echo json_encode(['status' => 'success', 'message' => 'Queue item added successfully to Examiners.']);
    }

    // Add to Business Tax Queue (Updated to JSON response)
    public function add_to_businesstax()
    {
        $name = $this->input->post('name');
        $reason = $this->input->post('reason');

        $this->model_queueing->add_to_businesstax($name, $reason);

        // Send JSON response
        echo json_encode(['status' => 'success', 'message' => 'Queue item added successfully to Business Tax.']);
    }

    // Add to Payment Queue (Updated to JSON response)
    public function add_to_payment()
    {
        $name = $this->input->post('name');
        $reason = $this->input->post('reason');

        $this->model_queueing->add_to_payment($name, $reason);

        // Send JSON response
        echo json_encode(['status' => 'success', 'message' => 'Queue item added successfully to Payment.']);
    }

    // Add to Fire Protection Queue (Updated to JSON response)
    public function add_to_fireprotection()
    {
        $name = $this->input->post('name');
        $reason = $this->input->post('reason');

        $this->model_queueing->add_to_fireprotection($name, $reason);

        // Send JSON response
        echo json_encode(['status' => 'success', 'message' => 'Queue item added successfully to Fire Protection.']);
    }

    // Proceed functions with flashdata notifications and redirect
// Proceed functions with AJAX responses

    public function proceed_to_backroom($id)
    {
        $this->model_queueing->proceed_queue($id, 'backroom');

        // Send JSON response for AJAX success notification
        echo json_encode(['status' => 'success', 'message' => 'Queue item successfully proceeded to Backroom.']);
    }


    public function proceed_to_examiners($id)
    {
        $this->model_queueing->proceed_queue($id, 'examiner');

        // Send JSON response for AJAX success notification
        echo json_encode(['status' => 'success', 'message' => 'Queue item successfully proceeded to Examiners.']);
    }

    public function proceed_to_businesstax($id)
    {
        $this->model_queueing->proceed_queue($id, 'businesstax');

        // Send JSON response for AJAX success notification
        echo json_encode(['status' => 'success', 'message' => 'Queue item successfully proceeded to Business Tax.']);
    }

    public function proceed_to_payment($id)
    {
        $this->model_queueing->proceed_queue($id, 'payment');

        // Send JSON response for AJAX success notification
        echo json_encode(['status' => 'success', 'message' => 'Queue item successfully proceeded to Payment.']);
    }

    public function proceed_to_fireprotection($id)
    {
        $this->model_queueing->proceed_queue($id, 'fireprotection');

        // Send JSON response for AJAX success notification
        echo json_encode(['status' => 'success', 'message' => 'Queue item successfully proceeded to Fire Protection.']);
    }

    public function proceed_to_releasing($id)
    {
        $this->model_queueing->proceed_queue($id, 'releasing');

        // Send JSON response for AJAX success notification
        echo json_encode(['status' => 'success', 'message' => 'Queue item successfully proceeded to Releasing.']);
    }

}