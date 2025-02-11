<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class model_queueing extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Add to queue
    public function add_to_queue($name) {
        $data = array('name' => $name, 'status' => 'landtax');
        return $this->db->insert('queue', $data);
    }

    // Get all queueing people
    public function get_queue() {
        return $this->db->where('status', 'landtax')->order_by('created_at', 'ASC')->get('queue')->result();
    }

    // Get the first in queue
    public function get_first_in_queue() {
        return $this->db->where('status', 'landtax')->order_by('created_at', 'ASC')->limit(1)->get('queue')->row();
    }

    // Move to next status
    public function proceed_queue($id, $new_status) {
        $this->db->where('id', $id)->update('queue', ['status' => $new_status]);
    }

    // Get people in backroom
    public function get_backroom() {
        return $this->db->where('status', 'backroom')->order_by('created_at', 'ASC')->get('queue')->result();
    }

    // Get people in examiners
    public function get_examiners() {
        return $this->db->where('status', 'examiner')->order_by('created_at', 'ASC')->get('queue')->result();
    }
    // Get people in Business Tax
public function get_businesstax() {
    return $this->db->where('status', 'businesstax')->order_by('created_at', 'ASC')->get('queue')->result();
}

// Get people in Payment
public function get_payment() {
    return $this->db->where('status', 'payment')->order_by('created_at', 'ASC')->get('queue')->result();
}

// Get people in Fire Protection
public function get_fireprotection() {
    return $this->db->where('status', 'fireprotection')->order_by('created_at', 'ASC')->get('queue')->result();
}

// Get people in Releasing
public function get_releasing() {
    return $this->db->where('status', 'releasing')->order_by('created_at', 'ASC')->get('queue')->result();
}

}
