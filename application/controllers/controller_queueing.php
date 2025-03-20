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

        $max_position = $this->db->select_max('position')
            ->where('status', 'landtax')
            ->get('queue')
            ->row()->position;

        $new_position = $max_position ? $max_position + 1 : 1;

        $queue_number = $this->model_queueing->add_to_queue($name, $reason, $new_position);

        if ($queue_number) {
            $new_item = $this->db->where('queue_number', $queue_number)->get('queue')->row();

            if (!$new_item || empty($new_item->id)) {
                echo json_encode(['status' => 'error', 'message' => 'Failed to fetch the new queue item.']);
                return;
            }

            $queue_id = $new_item->id;
            $proceed_url = base_url("index.php/controller_queueing/proceed_to_backroom/{$queue_id}");

            $queue_data = [
                'queue_id' => (string) $queue_id,
                'id' => $queue_id,
                'queue_number' => $queue_number,
                'name' => $name,
                'reason' => $reason,
                'status' => 'landtax',
                'position' => $new_position,
                'processing_by' => '',
                'proceed_url' => $proceed_url,
                'created_at' => $new_item->created_at
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

        // Ensure correct queue numbering
        $max_position = $this->db->select_max('position')
            ->where('status', 'backroom')
            ->get('queue')
            ->row()->position;

        $new_position = $max_position ? $max_position + 1 : 1;

        // Insert into the Backroom queue
        $queue_number = $this->model_queueing->add_to_backroom($name, $reason);

        if ($queue_number) {
            // Fetch newly inserted queue item with created_at
            $new_item = $this->db->where('queue_number', $queue_number)->get('queue')->row();

            if (!$new_item || empty($new_item->id)) {
                echo json_encode(['status' => 'error', 'message' => 'Failed to fetch the new queue item.']);
                return;
            }

            $queue_id = $new_item->id;
            $proceed_url = base_url("index.php/controller_queueing/proceed_to_examiners/{$queue_id}");

            $queue_data = [
                'queue_id' => (string) $queue_id,
                'id' => $queue_id,
                'queue_number' => $queue_number,
                'name' => $name,
                'reason' => $reason,
                'status' => 'backroom',  // Set status correctly
                'position' => $new_position,
                'processing_by' => '',
                'proceed_url' => $proceed_url,
                'created_at' => $new_item->created_at // ✅ Fetch timestamp from DB
            ];

            // 🔥 Send to WebSocket with the correct event name!
            $this->send_to_websocket($queue_data, 'proceed_to_backroom');

            echo json_encode([
                'status' => 'success',
                'message' => "Queue item added successfully to Backroom. Your queue number is $queue_number.",
                'queue_number' => $queue_number,
                'proceed_url' => $proceed_url
            ]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to add to the Backroom queue.']);
        }
    }

    public function add_to_examiners()
    {
        $name = $this->input->post('name');
        $reason = $this->input->post('reason');

        // Ensure correct queue numbering
        $max_position = $this->db->select_max('position')
            ->where('status', 'examiners')
            ->get('queue')
            ->row()->position;

        $new_position = $max_position ? $max_position + 1 : 1;

        // Insert into the Examiners queue
        $queue_number = $this->model_queueing->add_to_examiners($name, $reason);

        if ($queue_number) {
            // Fetch newly inserted queue item with created_at
            $new_item = $this->db->where('queue_number', $queue_number)->get('queue')->row();

            if (!$new_item || empty($new_item->id)) {
                echo json_encode(['status' => 'error', 'message' => 'Failed to fetch the new queue item.']);
                return;
            }

            $queue_id = $new_item->id;
            $proceed_url = base_url("index.php/controller_queueing/proceed_to_business_tax/{$queue_id}");

            $queue_data = [
                'queue_id' => (string) $queue_id,
                'id' => $queue_id,
                'queue_number' => $queue_number,
                'name' => $name,
                'reason' => $reason,
                'status' => 'examiners',  // Set status correctly
                'position' => $new_position,
                'processing_by' => '',
                'proceed_url' => $proceed_url,
                'created_at' => $new_item->created_at // ✅ Fetch timestamp from DB
            ];

            // 🔥 Send to WebSocket with the correct event name!
            $this->send_to_websocket($queue_data, 'proceed_to_examiners');

            echo json_encode([
                'status' => 'success',
                'message' => "Queue item added successfully to Examiners. Your queue number is $queue_number.",
                'queue_number' => $queue_number,
                'proceed_url' => $proceed_url
            ]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to add to the Examiners queue.']);
        }
    }

    public function add_to_businesstax()
    {
        $name = $this->input->post('name');
        $reason = $this->input->post('reason');

        // Ensure correct queue numbering
        $max_position = $this->db->select_max('position')
            ->where('status', 'businesstax')
            ->get('queue')
            ->row()->position;

        $new_position = $max_position ? $max_position + 1 : 1;

        // Insert into the Business Tax queue
        $queue_number = $this->model_queueing->add_to_businesstax($name, $reason);

        if ($queue_number) {
            // Fetch newly inserted queue item with created_at
            $new_item = $this->db->where('queue_number', $queue_number)->get('queue')->row();

            if (!$new_item || empty($new_item->id)) {
                echo json_encode(['status' => 'error', 'message' => 'Failed to fetch the new queue item.']);
                return;
            }

            $queue_id = $new_item->id;
            $proceed_url = base_url("index.php/controller_queueing/proceed_to_payment/{$queue_id}");

            $queue_data = [
                'queue_id' => (string) $queue_id,
                'id' => $queue_id,
                'queue_number' => $queue_number,
                'name' => $name,
                'reason' => $reason,
                'status' => 'businesstax',  // Set status correctly
                'position' => $new_position,
                'processing_by' => '',
                'proceed_url' => $proceed_url,
                'created_at' => $new_item->created_at // ✅ Fetch timestamp from DB
            ];

            // 🔥 Send to WebSocket with the correct event name!
            $this->send_to_websocket($queue_data, 'proceed_to_businesstax');

            echo json_encode([
                'status' => 'success',
                'message' => "Queue item added successfully to Business Tax. Your queue number is $queue_number.",
                'queue_number' => $queue_number,
                'proceed_url' => $proceed_url
            ]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to add to the Business Tax queue.']);
        }
    }

    public function add_to_payment()
    {
        $name = $this->input->post('name');
        $reason = $this->input->post('reason');

        $this->model_queueing->add_to_payment($name, $reason);

        echo json_encode(['status' => 'success', 'message' => 'Queue item added successfully to Payment.']);
    }

    public function add_to_fireprotection()
    {
        $name = $this->input->post('name');
        $reason = $this->input->post('reason');

        $this->model_queueing->add_to_fireprotection($name, $reason);

        echo json_encode(['status' => 'success', 'message' => 'Queue item added successfully to Fire Protection.']);
    }


    public function proceed_to_backroom($id)
    {
        header('Content-Type: application/json');

        // Fetch the queue item
        $queue_item = $this->db->select('id, queue_number, name, reason, created_at, processing_by')
            ->where('id', $id)
            ->get('queue')
            ->row();

        if (!$queue_item) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Queue item not found.']);
            exit;
        }

        // Determine new position for "backroom" status
        $max_position = $this->db->select_max('position')
            ->where('status', 'backroom')
            ->get('queue')
            ->row()->position;

        $new_position = $max_position ? $max_position + 1 : 1;

        // Update queue status to "backroom" and clear `processing_by`
        $this->db->where('id', $id)->update('queue', [
            'status' => 'backroom',
            'position' => $new_position,
            'processing_by' => NULL // ✅ Clear processing_by
        ]);

        // Fetch the updated item (ensures database update is reflected)
        $updated_item = $this->db->select('id, queue_number, name, reason, created_at, processing_by')
            ->where('id', $id)
            ->get('queue')
            ->row();

        if (!$updated_item) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Queue item not found after update.']);
            exit;
        }

        $proceed_url = base_url("controller_queueing/proceed_to_backroom/{$updated_item->id}");

        log_message('debug', "Generated proceed_url: {$proceed_url}");

        // ✅ Include `created_at` in the WebSocket message
        $queue_data = [
            'status' => 'success',
            'action' => 'proceed_to_backroom',
            'queue_id' => $updated_item->id,
            'queue_number' => $updated_item->queue_number,
            'name' => $updated_item->name,
            'reason' => $updated_item->reason,
            'status_text' => 'backroom',
            'processing_by' => NULL, // ✅ Set processing_by to NULL
            'created_at' => $updated_item->created_at,
            'proceed_url' => $proceed_url
        ];

        $this->send_to_websocket($queue_data, 'proceed_to_backroom');

        echo json_encode([
            'status' => 'success',
            'message' => 'Queue item successfully proceeded to Backroom.',
            'proceed_url' => $proceed_url,
            'queue_id' => $updated_item->id,
            'created_at' => $updated_item->created_at
        ]);
        exit;
    }

    public function proceed_to_examiners($id)
    {
        header('Content-Type: application/json');

        // Fetch the queue item
        $queue_item = $this->db->select('id, queue_number, name, reason, created_at, processing_by')
            ->where('id', $id)
            ->get('queue')
            ->row();

        if (!$queue_item) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Queue item not found.']);
            exit;
        }

        // Determine new position for "examiner" status
        $max_position = $this->db->select_max('position')
            ->where('status', 'examiner')
            ->get('queue')
            ->row()->position;

        $new_position = $max_position ? $max_position + 1 : 1;

        // Update queue status to "examiner" and clear `processing_by`
        $this->db->where('id', $id)->update('queue', [
            'status' => 'examiner',
            'position' => $new_position,
            'processing_by' => NULL // ✅ Clear processing_by
        ]);

        // Fetch the updated item
        $updated_item = $this->db->select('id, queue_number, name, reason, created_at, processing_by')
            ->where('id', $id)
            ->get('queue')
            ->row();

        if (!$updated_item) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Queue item not found after update.']);
            exit;
        }

        $proceed_url = base_url("controller_queueing/proceed_to_examiners/{$updated_item->id}");

        log_message('debug', "Generated proceed_url: {$proceed_url}");

        // ✅ Include `created_at` in the WebSocket message
        $queue_data = [
            'status' => 'success',
            'action' => 'proceed_to_examiners',
            'queue_id' => $updated_item->id,
            'queue_number' => $updated_item->queue_number,
            'name' => $updated_item->name,
            'reason' => $updated_item->reason,
            'status_text' => 'examiner',
            'processing_by' => NULL, // ✅ Set processing_by to NULL
            'created_at' => $updated_item->created_at,
            'proceed_url' => $proceed_url
        ];

        $this->send_to_websocket($queue_data, 'proceed_to_examiners');

        echo json_encode([
            'status' => 'success',
            'message' => 'Queue item successfully proceeded to Examiners.',
            'proceed_url' => $proceed_url,
            'queue_id' => $updated_item->id,
            'created_at' => $updated_item->created_at
        ]);
        exit;
    }
    public function proceed_to_businesstax($id)
    {
        header('Content-Type: application/json');

        // Fetch the queue item
        $queue_item = $this->db->select('id, queue_number, name, reason, created_at, processing_by')
            ->where('id', $id)
            ->get('queue')
            ->row();

        if (!$queue_item) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Queue item not found.']);
            exit;
        }

        // Determine new position for "businesstax" status
        $max_position = $this->db->select_max('position')
            ->where('status', 'businesstax')
            ->get('queue')
            ->row()->position;

        $new_position = $max_position ? $max_position + 1 : 1;

        // Update queue status to "businesstax" and clear `processing_by`
        $this->db->where('id', $id)->update('queue', [
            'status' => 'businesstax',
            'position' => $new_position,
            'processing_by' => NULL // ✅ Clear processing_by
        ]);

        // Fetch the updated item
        $updated_item = $this->db->select('id, queue_number, name, reason, created_at, processing_by')
            ->where('id', $id)
            ->get('queue')
            ->row();

        if (!$updated_item) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Queue item not found after update.']);
            exit;
        }

        $proceed_url = base_url("controller_queueing/proceed_to_businesstax/{$updated_item->id}");

        log_message('debug', "Generated proceed_url: {$proceed_url}");

        // ✅ Include `created_at` in the WebSocket message
        $queue_data = [
            'status' => 'success',
            'action' => 'proceed_to_businesstax',
            'queue_id' => $updated_item->id,
            'queue_number' => $updated_item->queue_number,
            'name' => $updated_item->name,
            'reason' => $updated_item->reason,
            'status_text' => 'businesstax',
            'processing_by' => NULL, // ✅ Set processing_by to NULL
            'created_at' => $updated_item->created_at,
            'proceed_url' => $proceed_url
        ];

        $this->send_to_websocket($queue_data, 'proceed_to_businesstax');

        echo json_encode([
            'status' => 'success',
            'message' => 'Queue item successfully proceeded to Business Tax.',
            'proceed_url' => $proceed_url,
            'queue_id' => $updated_item->id,
            'created_at' => $updated_item->created_at
        ]);
        exit;
    }

    public function proceed_to_payment($id)
    {
        header('Content-Type: application/json');

        // Fetch the queue item
        $queue_item = $this->db->select('id, queue_number, name, reason, created_at, processing_by')
            ->where('id', $id)
            ->get('queue')
            ->row();

        if (!$queue_item) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Queue item not found.']);
            exit;
        }

        // Determine new position for "payment" status
        $max_position = $this->db->select_max('position')
            ->where('status', 'payment')
            ->get('queue')
            ->row()->position;

        $new_position = $max_position ? $max_position + 1 : 1;

        // Update queue status to "payment" and clear `processing_by`
        $this->db->where('id', $id)->update('queue', [
            'status' => 'payment',
            'position' => $new_position,
            'processing_by' => NULL // ✅ Clear processing_by
        ]);

        // Fetch the updated item
        $updated_item = $this->db->select('id, queue_number, name, reason, created_at, processing_by')
            ->where('id', $id)
            ->get('queue')
            ->row();

        if (!$updated_item) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Queue item not found after update.']);
            exit;
        }

        $proceed_url = base_url("controller_queueing/proceed_to_payment/{$updated_item->id}");

        log_message('debug', "Generated proceed_url: {$proceed_url}");

        // ✅ Include `created_at` in the WebSocket message
        $queue_data = [
            'status' => 'success',
            'action' => 'proceed_to_payment',
            'queue_id' => $updated_item->id,
            'queue_number' => $updated_item->queue_number,
            'name' => $updated_item->name,
            'reason' => $updated_item->reason,
            'status_text' => 'payment',
            'processing_by' => NULL, // ✅ Set processing_by to NULL
            'created_at' => $updated_item->created_at,
            'proceed_url' => $proceed_url
        ];

        $this->send_to_websocket($queue_data, 'proceed_to_payment');

        echo json_encode([
            'status' => 'success',
            'message' => 'Queue item successfully proceeded to Payment.',
            'proceed_url' => $proceed_url,
            'queue_id' => $updated_item->id,
            'created_at' => $updated_item->created_at
        ]);
        exit;
    }

    public function proceed_to_fireprotection($id)
    {
        $this->model_queueing->proceed_queue($id, 'fireprotection');

        echo json_encode(['status' => 'success', 'message' => 'Queue item successfully proceeded to Fire Protection.']);
    }

    public function proceed_to_releasing($id)
    {
        $this->model_queueing->proceed_queue($id, 'releasing');

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

        $queue = $this->db->select('id, queue_number, name, reason, created_at, processing_by')
            ->get_where('queue', ['id' => $queue_id])
            ->row();

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
            $updatedQueue = $this->db->select('id, queue_number, name, reason, created_at, processing_by')
                ->get_where('queue', ['id' => $queue_id])
                ->row();

            // Log created_at before sending WebSocket message
            error_log("✅ Final Created At: " . json_encode($updatedQueue->created_at));

            $message = json_encode([
                'status' => 'success',
                'action' => 'update_queue',
                'queue_id' => $queue_id,
                'queue_number' => $updatedQueue->queue_number ?? '',
                'name' => $updatedQueue->name ?? '',
                'reason' => $updatedQueue->reason ?? '',
                'status_text' => 'processing',
                'processing_by' => $user_id,
                'created_at' => !empty($updatedQueue->created_at) ? $updatedQueue->created_at : date("Y-m-d H:i:s") // Ensure fallback timestamp
            ]);

            $this->sendWebSocketMessage($message);

            echo json_encode([
                'status' => 'success',
                'message' => 'Queue marked as processing',
                'queue_id' => $queue_id,
                'processing_by' => $user_id,
                'created_at' => $updatedQueue->created_at ?? date("Y-m-d H:i:s") // Ensure fallback
            ]);
            return;
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Database update failed']);
            return;
        }
    }

    public function mark_as_processing_backroom()
    {
        header('Content-Type: application/json');

        $queue_id = $this->input->post('queue_id', TRUE);
        $user_id = $this->session->userdata('user_id');

        if (!$queue_id) {
            echo json_encode(['status' => 'error', 'message' => 'Queue ID is missing']);
            return;
        }

        $queue = $this->db->select('id, queue_number, name, reason, created_at, processing_by')
            ->get_where('queue', ['id' => $queue_id])
            ->row();

        if (!$queue) {
            echo json_encode(['status' => 'error', 'message' => 'Queue not found']);
            return;
        }

        // ✅ Always overwrite processing_by with the new backroom user_id
        $this->db->where('id', $queue_id);
        $update = $this->db->update('queue', ['processing_by' => $user_id]);

        if ($update) {
            $updatedQueue = $this->db->select('id, queue_number, name, reason, created_at, processing_by')
                ->get_where('queue', ['id' => $queue_id])
                ->row();

            // Log created_at before sending WebSocket message
            error_log("✅ Final Created At: " . json_encode($updatedQueue->created_at));

            $message = json_encode([
                'status' => 'success',
                'action' => 'update_queue',
                'queue_id' => $queue_id,
                'queue_number' => $updatedQueue->queue_number ?? '',
                'name' => $updatedQueue->name ?? '',
                'reason' => $updatedQueue->reason ?? '',
                'status_text' => 'processing',
                'processing_by' => $user_id,
                'created_at' => !empty($updatedQueue->created_at) ? $updatedQueue->created_at : date("Y-m-d H:i:s") // Ensure fallback timestamp
            ]);

            $this->sendWebSocketMessage($message);

            echo json_encode([
                'status' => 'success',
                'message' => 'Queue marked as processing by backroom',
                'queue_id' => $queue_id,
                'processing_by' => $user_id,
                'created_at' => $updatedQueue->created_at ?? date("Y-m-d H:i:s") // Ensure fallback
            ]);
            return;
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Database update failed']);
            return;
        }
    }

    public function mark_as_processing_examiners()
    {
        header('Content-Type: application/json');

        $queue_id = $this->input->post('queue_id', TRUE);
        $user_id = $this->session->userdata('user_id');

        if (!$queue_id) {
            echo json_encode(['status' => 'error', 'message' => 'Queue ID is missing']);
            return;
        }

        $queue = $this->db->select('id, queue_number, name, reason, created_at, processing_by')
            ->get_where('queue', ['id' => $queue_id])
            ->row();

        if (!$queue) {
            echo json_encode(['status' => 'error', 'message' => 'Queue not found']);
            return;
        }

        // ✅ Assign the queue to the current examiner (user_id)
        $this->db->where('id', $queue_id);
        $update = $this->db->update('queue', ['processing_by' => $user_id]);

        if ($update) {
            $updatedQueue = $this->db->select('id, queue_number, name, reason, created_at, processing_by')
                ->get_where('queue', ['id' => $queue_id])
                ->row();

            // Log created_at before sending WebSocket message
            error_log("✅ Final Created At: " . json_encode($updatedQueue->created_at));

            $message = json_encode([
                'status' => 'success',
                'action' => 'update_queue',
                'queue_id' => $queue_id,
                'queue_number' => $updatedQueue->queue_number ?? '',
                'name' => $updatedQueue->name ?? '',
                'reason' => $updatedQueue->reason ?? '',
                'status_text' => 'processing',
                'processing_by' => $user_id,
                'created_at' => !empty($updatedQueue->created_at) ? $updatedQueue->created_at : date("Y-m-d H:i:s") // Ensure fallback timestamp
            ]);

            $this->sendWebSocketMessage($message);

            echo json_encode([
                'status' => 'success',
                'message' => 'Queue marked as processing by examiner',
                'queue_id' => $queue_id,
                'processing_by' => $user_id,
                'created_at' => $updatedQueue->created_at ?? date("Y-m-d H:i:s") // Ensure fallback
            ]);
            return;
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Database update failed']);
            return;
        }
    }

    public function mark_as_processing_business_tax()
    {
        header('Content-Type: application/json');

        $queue_id = $this->input->post('queue_id', TRUE);
        $user_id = $this->session->userdata('user_id');

        if (!$queue_id) {
            echo json_encode(['status' => 'error', 'message' => 'Queue ID is missing']);
            return;
        }

        $queue = $this->db->select('id, queue_number, name, reason, created_at, processing_by')
            ->get_where('queue', ['id' => $queue_id])
            ->row();

        if (!$queue) {
            echo json_encode(['status' => 'error', 'message' => 'Queue not found']);
            return;
        }

        // ✅ Assign the queue to the current Business Tax processor (user_id)
        $this->db->where('id', $queue_id);
        $update = $this->db->update('queue', ['processing_by' => $user_id]);

        if ($update) {
            $updatedQueue = $this->db->select('id, queue_number, name, reason, created_at, processing_by')
                ->get_where('queue', ['id' => $queue_id])
                ->row();

            // Log created_at before sending WebSocket message
            error_log("✅ Final Created At (Business Tax): " . json_encode($updatedQueue->created_at));

            $message = json_encode([
                'status' => 'success',
                'action' => 'update_queue',
                'queue_id' => $queue_id,
                'queue_number' => $updatedQueue->queue_number ?? '',
                'name' => $updatedQueue->name ?? '',
                'reason' => $updatedQueue->reason ?? '',
                'status_text' => 'processing',
                'processing_by' => $user_id,
                'created_at' => !empty($updatedQueue->created_at) ? $updatedQueue->created_at : date("Y-m-d H:i:s") // Ensure fallback timestamp
            ]);

            $this->sendWebSocketMessage($message);

            echo json_encode([
                'status' => 'success',
                'message' => 'Queue marked as processing by Business Tax processor',
                'queue_id' => $queue_id,
                'processing_by' => $user_id,
                'created_at' => $updatedQueue->created_at ?? date("Y-m-d H:i:s") // Ensure fallback
            ]);
            return;
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Database update failed']);
            return;
        }
    }

    private function sendWebSocketMessage($message)
    {
        $sock = fsockopen("localhost", 8080);

        if ($sock) {
            fwrite($sock, $message);
            fclose($sock);
        }
    }
}