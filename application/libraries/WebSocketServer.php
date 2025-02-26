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
        echo "📥 Received WebSocket Message: $msg\n"; // Log received messages

        // Validate JSON input
        $data = json_decode($msg, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            echo "❌ Invalid JSON received!\n";
            return;
        }

        // Log before broadcasting
        echo "📡 Broadcasting Message: " . json_encode($data) . "\n";

        // Broadcast message to all clients
        foreach ($this->clients as $client) {
            if ($from !== $client) { // Prevent sending message back to sender
                $client->send(json_encode([
                    "status" => "success",
                    "action" => $data['action'] ?? '',
                    "queue_id" => $data['queue_id'] ?? '',
                    "queue_number" => $data['queue_number'] ?? '',
                    "name" => $data['name'] ?? '',
                    "reason" => $data['reason'] ?? '',
                    "status_text" => $data['status'] ?? 'Waiting',
                    "processing_by" => $data['processing_by'] ?? ''
                ]));
            }
        }

        echo "✅ Message broadcasted to all clients!\n";
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
