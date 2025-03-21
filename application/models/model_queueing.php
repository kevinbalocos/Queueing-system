<?php
defined('BASEPATH') or exit('No direct script access allowed');

class model_queueing extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    // Add to queue
    public function add_to_queue($name, $reason)
    {
        // Get the last queue number
        $last_queue = $this->db->select_max('queue_number')->get('queue')->row();
        $new_queue_number = $last_queue->queue_number ? $last_queue->queue_number + 1 : 1;

        // Get the highest position in the landtax queue
        $max_position = $this->db->select_max('position')->where('status', 'landtax')->get('queue')->row()->position;
        $new_position = $max_position ? $max_position + 1 : 1; // Append to the end

        $data = array(
            'queue_number' => $new_queue_number,
            'name' => $name,
            'reason' => $reason,
            'status' => 'landtax',
            'position' => $new_position
        );

        if ($this->db->insert('queue', $data)) {
            return $new_queue_number;
        }
        return false;
    }
    public function delete_queue($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('queue'); // Replace 'queue' with your actual table name if different
    }


    public function get_queue_item($queue_id)
    {
        return $this->db->get_where('queue', ['id' => $queue_id])->row();
    }

    public function update_processing_status($queue_id, $user_id)
    {
        $this->db->where('id', $queue_id);
        $this->db->update('queue', ['processing_user_id' => $user_id]);
    }


    public function add_to_backroom($name, $reason)
    {
        // Get the last queue number (ensuring sequence consistency)
        $last_queue = $this->db->select_max('queue_number')->get('queue')->row();
        $new_queue_number = $last_queue->queue_number ? $last_queue->queue_number + 1 : 1;

        // Get the highest position in the backroom queue
        $max_position = $this->db->select_max('position')->where('status', 'backroom')->get('queue')->row()->position;
        $new_position = $max_position ? $max_position + 1 : 1; // Append to the end

        $data = array(
            'queue_number' => $new_queue_number,
            'name' => $name,
            'reason' => $reason,
            'status' => 'backroom',  // Set status to backroom
            'position' => $new_position,  // Maintain correct queue order in backroom
        );

        if ($this->db->insert('queue', $data)) {
            return $new_queue_number;
        }
        return false;
    }


    public function add_to_examiners($name, $reason)
    {
        // Get the last queue number (ensuring sequence consistency)
        $last_queue = $this->db->select_max('queue_number')->get('queue')->row();
        $new_queue_number = $last_queue->queue_number ? $last_queue->queue_number + 1 : 1;

        // Get the highest position in the examiners queue
        $max_position = $this->db->select_max('position')->where('status', 'examiner')->get('queue')->row()->position;
        $new_position = $max_position ? $max_position + 1 : 1; // Append to the end

        $data = array(
            'queue_number' => $new_queue_number,
            'name' => $name,
            'reason' => $reason,
            'status' => 'examiner',  // Set status to examiner
            'position' => $new_position,  // Maintain correct queue order in examiners
        );

        if ($this->db->insert('queue', $data)) {
            return $new_queue_number;
        }
        return false;
    }

    public function add_to_businesstax($name, $reason)
    {
        // Get the last queue number (ensuring sequence consistency)
        $last_queue = $this->db->select_max('queue_number')->get('queue')->row();
        $new_queue_number = $last_queue->queue_number ? $last_queue->queue_number + 1 : 1;

        // Get the highest position in the Business Tax queue
        $max_position = $this->db->select_max('position')->where('status', 'businesstax')->get('queue')->row()->position;
        $new_position = $max_position ? $max_position + 1 : 1; // Append to the end

        $data = array(
            'queue_number' => $new_queue_number,
            'name' => $name,
            'reason' => $reason,
            'status' => 'businesstax',  // Set status to Business Tax
            'position' => $new_position, // Maintain correct queue order in Business Tax
        );

        if ($this->db->insert('queue', $data)) {
            return $new_queue_number;
        }
        return false;
    }


    public function add_to_payment($name, $reason)
    {
        $last_queue = $this->db->select_max('queue_number')->get('queue')->row();
        $new_queue_number = $last_queue->queue_number + 1;
        $data = array(
            'queue_number' => $new_queue_number,
            'name' => $name,
            'reason' => $reason,
            'status' => 'payment'
        );
        return $this->db->insert('queue', $data);
    }

    public function add_to_fireprotection($name, $reason)
    {
        $last_queue = $this->db->select_max('queue_number')->get('queue')->row();
        $new_queue_number = $last_queue->queue_number + 1;
        $data = array(
            'queue_number' => $new_queue_number,
            'name' => $name,
            'reason' => $reason,
            'status' => 'fireprotection'
        );
        return $this->db->insert('queue', $data);
    }



    // Get the first in queue
    public function get_first_in_queue()
    {
        return $this->db->where('status', 'landtax')->order_by('created_at', 'ASC')->limit(1)->get('queue')->row();
    }

    // Move to next status
    public function proceed_queue($id, $new_status)
    {
        $this->db->where('id', $id)->update('queue', ['status' => $new_status]);
    }
    // Get all queueing people
    public function get_queue()
    {
        return $this->db->where('status', 'landtax')
            ->order_by('created_at', 'ASC')
            ->get('queue')
            ->result();
    }
    // Get people in backroom
    public function get_backroom()
    {
        return $this->db->where('status', 'backroom')->order_by('position', 'ASC')->get('queue')->result();
    }


    // Get people in examiners
    public function get_examiners()
    {
        return $this->db->where('status', 'examiner')->order_by('created_at', 'ASC')->get('queue')->result();
    }
    // Get people in Business Tax
    public function get_businesstax()
    {
        return $this->db->where('status', 'businesstax')->order_by('created_at', 'ASC')->get('queue')->result();
    }

    // Get people in Payment
    public function get_payment()
    {
        return $this->db->where('status', 'payment')->order_by('created_at', 'ASC')->get('queue')->result();
    }

    // Get people in Fire Protection
    public function get_fireprotection()
    {
        return $this->db->where('status', 'fireprotection')->order_by('created_at', 'ASC')->get('queue')->result();
    }

    // Get people in Releasing
    public function get_releasing()
    {
        return $this->db->where('status', 'releasing')->order_by('created_at', 'ASC')->get('queue')->result();
    }
    public function mark_as_processing($queue_id, $user_id)
    {
        $this->db->where('id', $queue_id);
        return $this->db->update('queue', ['processing_by' => $user_id]);
    }

}
