<?php
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class WebSocketServer implements MessageComponentInterface
{
    protected $clients;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        echo "Received message: $msg\n"; // Log received messages

        // Validate JSON input
        $data = json_decode($msg, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            echo "Invalid JSON received!\n";
            return;
        }

        // Broadcast message to all clients
        foreach ($this->clients as $client) {
            if ($from !== $client) { // Prevent sending the message back to the sender
                $client->send(json_encode([
                    "status" => "success",
                    "action" => $data['action'] ?? '',
                    "queue_id" => $data['queue_id'] ?? '',
                    "processing_by" => $data['processing_by'] ?? ''
                ]));
            }
        }

        echo "Message broadcasted to all clients!\n"; // Log success
    }


    public function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        $conn->close();
    }
}
