<?php
class User_model extends CI_Model {

    public function get_all_users() {
        return $this->db->get('users')->result();
    }

    public function insert_user($data) {
        return $this->db->insert('users', $data);
    }

    public function delete_user($id) {
        return $this->db->delete('users', ['id' => $id]);
    }

    public function get_user_by_username($username) {
        return $this->db->get_where('users', ['username' => $username])->row();
    }
}
?>
