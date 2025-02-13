<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class model_user extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Get all users
    public function get_all_users() {
        return $this->db->get('users')->result();
    }

    // Insert new user
    public function insert_user($data) {
        return $this->db->insert('users', $data);
    }

    // Delete user by ID
    public function delete_user($id) {
        return $this->db->delete('users', ['id' => $id]);
    }
}
?>
