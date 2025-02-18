<?php
defined('BASEPATH') or exit('No direct script access allowed');

class model_login extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    public function add_to_queue()
    {
        $name = $this->input->post('name');
        $reason = $this->input->post('reason');

        // Call the model to add to the queue
        $new_queue_number = $this->model_queueing->add_to_queue($name, $reason);

        if ($new_queue_number) {
            // Fetch the new queue item data
            $new_item = [
                'queue_number' => $new_queue_number,
                'name' => $name,
                'reason' => $reason
            ];

            // Send the new item to all connected WebSocket clients
            $this->send_to_websocket($new_item);

            // Check if the queue size exceeds the left section limit
            $queue = $this->model_queueing->get_queue(); // Adjust as per your model
            $left_items = array_slice($queue, 0, 20);
            $right_items = array_slice($queue, 20);

            // Re-render the view with updated sections
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

    public function check_login($username, $password)
    {
        $this->db->where('username', $username);
        $query = $this->db->get('users'); 

        if ($query->num_rows() > 0) {
            $user = $query->row();

            if (password_verify($password, $user->password)) {
                return $user; // Login success
            }
        }

        return false; // Login failed
    }
}
