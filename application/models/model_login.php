<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class model_login extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function check_login($username, $password)
    {
        $this->db->where('username', $username);
        $query = $this->db->get('users'); // Fetch user record
    
        if ($query->num_rows() > 0) {
            $user = $query->row();
    
            // ⚠ Temporarily use plain-text password check (For now)
            if ($password === $user->password) { 
                return $user; // Login success
            }
        }
    
        return false; // Login failed
    }    
}
