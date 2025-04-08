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
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <script>
        const socket = new WebSocket("ws://localhost:8080");

        socket.onopen = () => console.log("✅ Connected to WebSocket server");

        socket.onmessage = (event) => {
            try {
                const data = JSON.parse(event.data);
                console.log("📩 Received WebSocket Data: ", data);

                // Infer target sector if status is missing
                const fallbackSector = getSectorFromAction(data.action);
                const rawStatus = data.status_text || data.status || fallbackSector;
                data.status = normalizeSector(rawStatus);

                // Handle various actions
                switch (data.action) {
                    case "add_to_queue":
                        handleAddOrMove(data, false); // false = not moved
                        break;

                    case "proceed_to_backroom":
                    case "proceed_to_examiners":
                    case "proceed_to_payment":
                    case "proceed_to_fireprotection":
                    case "proceed_to_businesstax":
                    case "proceed_to_releasing":
                        handleAddOrMove(data, true); // true = moved
                        break;

                    case "delete_queue":
                        removeQueueItem(data.queue_id);
                        break;

                    default:
                        console.warn("⚠️ Unknown WebSocket action:", data.action);
                }

            } catch (error) {
                console.error("⚠️ Error parsing WebSocket data:", error);
            }
        };

        socket.onerror = (error) => console.error("❌ WebSocket Error: ", error);
        socket.onclose = () => console.log("🔴 Disconnected from WebSocket server");

        const sectorColors = {
            landtax: 'green',
            backroom: 'teal',
            examiners: 'yellow',
            businesstax: 'purple',
            payment: 'blue',
            fireprotection: 'orange',
            releasing: 'green'
        };

        // Function to handle adding or moving a queue item
        function handleAddOrMove(data, isMoved = false) {
            const currentSector = data.status;

            // Remove item from its current sector (if it exists)
            removeQueueItem(data.queue_id);

            // After removal, add to the new sector
            addQueueItem(data, isMoved);
        }

        // Function to create the queue item HTML structure
        function createQueueItem(data) {
            const sector = (data.status || '').toLowerCase(); // normalize
            const color = sectorColors[sector] || 'gray';

            return `
                <div id="queue-item-${data.queue_id}" 
                    class="queue-item bg-${color}-100 border-l-4 border-${color}-600 p-4 rounded shadow-sm mb-2">
                    <h3 class="font-bold text-${color}-900 text-lg">
                        ${data.queue_number || 'N/A'}
                    </h3>
                    <p class="text-sm text-${color}-800">
                        ${data.name || 'Unknown'}
                    </p>
                </div>
            `;
        }   

        // Function to add a queue item to the respective sector
        function addQueueItem(data, isMoved = false) {
            const currentSector = data.status; // This will be the sector the queue item is currently in
            const sectorId = `${currentSector}-queue`; // Determine the sector container ID dynamically
            let sectorQueue = document.getElementById(sectorId);

            // Check if the sector queue exists, if not, create it
            if (!sectorQueue) {
                console.warn(`⚠️ Sector "${sectorId}" does not exist. Creating a new container.`);
                // Create a new container dynamically
                sectorQueue = document.createElement('div');
                sectorQueue.id = sectorId;
                sectorQueue.className = 'sector-queue'; // Optional: add classes for styling
                document.body.appendChild(sectorQueue);  // You might want to append this to a specific container
            }

            const queueId = `queue-item-${data.queue_id}`;
            if (document.getElementById(queueId)) {
                console.warn(`⚠️ Queue item ${queueId} already exists.`);
                return;
            }

            // Create the queue item HTML
            const newQueueItemHTML = createQueueItem(data, isMoved);
            sectorQueue.insertAdjacentHTML('beforeend', newQueueItemHTML);

            // Optional: Limit the number of items in the sector
            const items = sectorQueue.querySelectorAll('.queue-item');
            if (items.length > 10) {
                items[0].remove();
            }

            // Move the queue item if it's proceeding to a new sector
            if (isMoved) {
                const newSector = getSectorFromAction(data.action);
                if (newSector && newSector !== currentSector) {
                    moveQueueItemToNewSector(data, newSector);
                }
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

        // Function to move a queue item to a new sector
        function moveQueueItemToNewSector(data, newSector) {
            const currentSectorId = `${data.status}-queue`;
            const currentSectorQueue = document.getElementById(currentSectorId);
            const queueItem = document.getElementById(`queue-item-${data.queue_id}`);

            if (queueItem) {
                // Remove the queue item from its current sector
                currentSectorQueue.removeChild(queueItem);
                console.log(`✅ Queue ID ${data.queue_id} moved from ${data.status} to ${newSector}.`);

                // Update the queue item data's sector status
                data.status = newSector; // Update the sector to the new one
                data.status_text = newSector; // Optional: Update status text

                // Add the queue item to the new sector
                const newSectorQueue = document.getElementById(`${newSector}-queue`);
                if (!newSectorQueue) {
                    console.warn(`⚠️ New sector "${newSector}" not found. Creating a new container.`);
                    const newQueueContainer = document.createElement('div');
                    newQueueContainer.id = `${newSector}-queue`;
                    document.body.appendChild(newQueueContainer);
                }
                newSectorQueue.appendChild(queueItem); // Move the item to the new sector
            }
        }

        // Helper function to handle the status of moving a queue item
        function getSectorFromAction(action) {
            const map = {
                proceed_to_backroom: 'backroom',
                proceed_to_examiners: 'examiner',
                proceed_to_payment: 'payment',
                proceed_to_fireprotection: 'fireprotection',
                proceed_to_businesstax: 'businesstax',
                proceed_to_releasing: 'releasing'
            };
            return map[action] || null;
        }

        // Normalize sector names
        const sectorAlias = {
            examiner: "examiners"
        };

        function normalizeSector(sector) {
            return sectorAlias[sector] || sector;
        }
    </script>

</body>

</html>