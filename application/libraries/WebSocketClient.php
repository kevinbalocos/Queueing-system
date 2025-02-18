<?php
require_once FCPATH . 'vendor/autoload.php'; // Include Composer autoloader

use WebSocket\Client; // Make sure this is the correct namespace

class WebSocketClient
{
    private $client;

    public function __construct()
    {
        $this->client = new Client("ws://localhost:8080");
    }

    public function send($message)
    {
        $this->client->send($message);
    }
}
