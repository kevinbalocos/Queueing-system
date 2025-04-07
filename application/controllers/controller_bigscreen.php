<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class BigScreen extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('model_queueing'); // Assume you already have a model
    }

    public function index() {
        // Define the sectors and their colors
        $data['sectors'] = [
            'landtax' => 'green',
            'backroom' => 'teal',
            'examiners' => 'yellow',
            'businesstax' => 'purple',
            'payment' => 'blue',
            'fireprotection' => 'orange',
            'releasing' => 'green'
        ];

        // Fetch top 10 queue entries per sector
        foreach ($data['sectors'] as $sector => $color) {
            $data[$sector] = $this->Queue_model->get_top_queue($sector, 10);
        }

        $this->load->view('bigscreen_view', $data);
    }
}
