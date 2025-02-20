<?php
// defined('BASEPATH') or exit('No direct script access allowed');
// require APPPATH . '../vendor/autoload.php'; // Load MongoDB library

// class Mongo_db
// {
//     private $client;
//     private $db;

//     public function __construct()
//     {
//         $CI =& get_instance();
//         $CI->config->load('mongodb', TRUE); // Load MongoDB config file

//         $config = $CI->config->item('mongodb');

//         try {
//             $this->client = new MongoDB\Client($config['mongo_host']);
//             $this->db = $this->client->selectDatabase($config['mongo_database']);
//         } catch (Exception $e) {
//             die("Error connecting to MongoDB: " . $e->getMessage());
//         }
//     }

//     public function get_collection($collection)
//     {
//         return $this->db->$collection;
//     }
// }
