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

        // Get the highest position in the landtax queue
        $max_position = $this->db->select_max('position')
            ->where('status', 'landtax')
            ->get('queue')
            ->row()->position;

        $new_position = $max_position ? $max_position + 1 : 1; // Append to the end

        // Add the queue item with assigned position
        $queue_number = $this->model_queueing->add_to_queue($name, $reason, $new_position);

        if ($queue_number) {
            // Fetch the newly added queue item
            $new_item = $this->db->where('queue_number', $queue_number)->get('queue')->row();

            if (!$new_item || empty($new_item->id)) {
                echo json_encode(['status' => 'error', 'message' => 'Failed to fetch the new queue item.']);
                return;
            }

            $queue_id = $new_item->id;
            $proceed_url = base_url("index.php/controller_queueing/proceed_to_backroom/{$queue_id}");

            // WebSocket data
            $queue_data = [
                'queue_id' => (string) $queue_id,
                'id' => $queue_id,
                'queue_number' => $queue_number,
                'name' => $name,
                'reason' => $reason,
                'status' => 'landtax',
                'position' => $new_position,
                'processing_by' => '',
                'proceed_url' => $proceed_url
            ];
            $this->send_to_websocket($queue_data, 'add_to_queue');

            echo json_encode([
                'status' => 'success',
                'message' => "Queue item added successfully. Your queue number is $queue_number.",
                'queue_number' => $queue_number,
                'proceed_url' => $proceed_url
            ]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to add to the queue.']);
        }
    }

    // Function to send the new queue item to WebSocket clients
    private function send_to_websocket($data, $action)
    {
        $data['action'] = $action;

        log_message('debug', '📤 Sending WebSocket Data: ' . json_encode($data));

        $ws_client = new WebSocketClient();
        $ws_client->send(json_encode($data));
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
        header('Content-Type: application/json'); // Ensure JSON response

        // Get the highest position currently in the backroom
        $max_position = $this->db->select_max('position')->where('status', 'backroom')->get('queue')->row()->position;
        $new_position = $max_position ? $max_position + 1 : 1;

        // Update queue status
        $this->db->where('id', $id)->update('queue', [
            'status' => 'backroom',
            'position' => $new_position
        ]);

        // Fetch the updated queue item
        $updated_item = $this->db->where('id', $id)->get('queue')->row();

        if (!$updated_item) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Queue item not found.']);
            exit;
        }

        // Ensure site_url() returns a valid URL
        $proceed_url = base_url("queue/proceed_to_backroom/{$updated_item->id}");

        $queue_data = [
            'id' => $updated_item->id,
            'queue_number' => $updated_item->queue_number,
            'name' => $updated_item->name,
            'reason' => $updated_item->reason,
            'status' => 'backroom',
            'position' => $updated_item->position,
            'proceed_url' => $proceed_url
        ];

        // Send event to WebSocket
        $this->send_to_websocket($queue_data, 'proceed_to_backroom');

        // Ensure JSON response
        echo json_encode([
            'status' => 'success',
            'message' => 'Queue item successfully proceeded to Backroom.',
            'proceed_url' => $proceed_url
        ]);
        exit;
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
    public function mark_as_processing()
    {
        header('Content-Type: application/json');

        $queue_id = $this->input->post('queue_id', TRUE);
        $user_id = $this->session->userdata('user_id');

        if (!$queue_id) {
            echo json_encode(['status' => 'error', 'message' => 'Queue ID is missing']);
            return;
        }

        $queue = $this->db->get_where('queue', ['id' => $queue_id])->row();
        if (!$queue) {
            echo json_encode(['status' => 'error', 'message' => 'Queue not found']);
            return;
        }

        if ($queue->processing_by) {
            echo json_encode(['status' => 'error', 'message' => 'Already being processed']);
            return;
        }

        $this->db->where('id', $queue_id);
        $update = $this->db->update('queue', ['processing_by' => $user_id]);

        if ($update) {
            // WebSocket Message
            $message = json_encode([
                'status' => 'success',
                'action' => 'update_queue',
                'queue_id' => $queue_id,
                'processing_by' => $user_id
            ]);

            // Broadcast WebSocket Message
            $this->sendWebSocketMessage($message);

            echo json_encode(['status' => 'success', 'message' => 'Queue marked as processing']);
            return;
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Database update failed']);
            return;
        }
    }

    // Function to send WebSocket message
    private function sendWebSocketMessage($message)
    {
        $sock = fsockopen("localhost", 8080); // Ensure your WebSocket server is running on port 8080

        if ($sock) {
            fwrite($sock, $message);
            fclose($sock);
        }
    }
}