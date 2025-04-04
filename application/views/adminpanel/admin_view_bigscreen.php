<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BIG SCREEN - Queue Display</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <style>
        html,
        body {
            width: 100vw;
            height: 100vh;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f3f4f6;
        }

        .queue-container {
            width: 95vw;
            height: 95vh;
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .queue-header {
            font-size: clamp(1.5rem, 3vw, 2rem);
            font-weight: bold;
            text-align: center;
            color: #1e3a8a;
            margin-bottom: 1rem;
        }

        .queue-row {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1rem;
            background: #f9fafb;
        }

        .queue-item {
            flex: 1 1 250px;
            /* Ensures equal width */
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 1rem;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            font-size: clamp(0.8rem, 2vw, 1rem);
        }

        .queue-item h3 {
            font-size: clamp(1.2rem, 2.5vw, 1.5rem);
            font-weight: bold;
        }

        .queue-item p {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sector-title {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }
    </style>
</head>

<body>

    <div id="queue-container" class="queue-container overflow-y-auto">
        <h2 class="queue-header">Queue Display</h2>

        <!-- Sectors -->
        <?php
        $sectors = [
            'landtax' => 'green',
            'backroom' => 'teal',
            'examiners' => 'yellow',
            'businesstax' => 'purple',
            'payment' => 'blue',
            'fireprotection' => 'orange',
            'releasing' => 'green'
        ];
        foreach ($sectors as $sector => $color): ?>
            <div id="<?= $sector ?>-queue" class="queue-row">
                <h3 class="sector-title text-<?= $color ?>-600 w-full"><?= ucfirst($sector) ?></h3>
                <?php if (!empty($$sector)): ?>
                    <?php foreach (array_slice($$sector, 0, 10) as $item): ?>
                        <div id="queue-item-<?= htmlspecialchars($item->queue_id ?? $item->id); ?>"
                            class="queue-item bg-<?= $color ?>-100 border-l-4 border-<?= $color ?>-600 p-2">
                            <h3><?= htmlspecialchars($item->queue_number ?? 'N/A'); ?></h3>
                            <p><?= htmlspecialchars($item->name ?? 'Unknown'); ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <script>
        // Initialize WebSocket connection
        const socket = new WebSocket("ws://localhost:8080");

        socket.onopen = () => console.log("✅ Connected to WebSocket server");

        socket.onmessage = (event) => {
            try {
                const data = JSON.parse(event.data);
                console.log("📩 Received WebSocket Data: ", data);

                // Check the action and process accordingly
                if (data.action === "add_to_queue") {
                    addQueueItem(data);
                } else if (data.action === "delete_queue") {
                    removeQueueItem(data.queue_id);
                }
            } catch (error) {
                console.error("⚠️ Error parsing WebSocket data:", error);
            }
        };

        socket.onerror = (error) => console.error("❌ WebSocket Error: ", error);
        socket.onclose = () => console.log("🔴 Disconnected from WebSocket server");

        // Function to create the queue item HTML structure
        function createQueueItem(data) {
            return `
            <div id="queue-item-${data.queue_id}" class="queue-item bg-gray-50 border-l-4 border-gray-600 p-4 rounded-lg shadow">
                <h3>${data.queue_number}</h3>
                <p>${data.name}</p>
            </div>`;
        }

        // Function to add a queue item to the respective sector
        function addQueueItem(data) {
            // Get the correct sector container using `data.status`
            const sectorQueue = document.getElementById(`queue-container`);

            if (!sectorQueue) {
                console.error(`⚠️ Error: Queue container for sector ${data.status} not found.`);
                return;
            }

            // Find the container for queue items inside the sector
            const queueItemsContainer = sectorQueue.querySelector('.queue-row');
            if (!queueItemsContainer) {
                console.error(`⚠️ Error: Queue items container for ${data.status} not found.`);
                return;
            }

            // Check if the queue item already exists
            const queueId = `queue-item-${data.queue_id}`;
            if (document.getElementById(queueId)) {
                console.warn(`⚠️ Queue item ${queueId} already exists.`);
                return;
            }

            // Create the new queue item and append it
            const newQueueItemHTML = createQueueItem(data);
            queueItemsContainer.insertAdjacentHTML('beforeend', newQueueItemHTML);

            // Ensure the number of items in the sector is limited to 10
            const items = queueItemsContainer.querySelectorAll('.queue-item');
            if (items.length > 10) {
                items[0].remove(); // Remove the oldest item if there are more than 10
            }
        }

        // Function to remove a queue item
        function removeQueueItem(queueId) {
            const queueItem = document.getElementById(`queue-item-${queueId}`);
            if (queueItem) {
                queueItem.remove();
                console.log(`✅ Queue ID ${queueId} removed from UI.`);
            } else {
                console.warn(`⚠️ Queue item ${queueId} not found.`);
            }
        }
    </script>

</body>

</html>