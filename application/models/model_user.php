<?php
defined('BASEPATH') or exit('No direct script access allowed');

class model_user extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    // Get all users
    public function get_all_users()
    {
        return $this->db->get('users')->result();
    }

    // Insert new user
    public function insert_user($data)
    {
        return $this->db->insert('users', $data);
    }

    // Delete user by ID
    public function delete_user($id)
    {
        return $this->db->delete('users', ['id' => $id]);
    }
    public function get_user_by_id($user_id)
    {
        $this->db->where('id', $user_id);
        $query = $this->db->get('users');
        return $query->row();
    }

    public function update_user($user_id, $data)
    {
        $this->db->where('id', $user_id);
        return $this->db->update('users', $data);
    }
}
?>