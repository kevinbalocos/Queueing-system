<?php
require 'vendor/autoload.php';
require 'application/libraries/WebSocketServer.php';

use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use Ratchet\Server\IoServer;

$server = IoServer::factory(
    new HttpServer(new WsServer(new WebSocketServer())),
    8080
);

echo "WebSocket Server running on port 8080...\n";
$server->run();
