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

        .queue-item {
            transition: all 0.3s ease-in-out;
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
                            class="queue-item bg-<?= $color ?>-100 border-l-4 border-<?= $color ?>-600">
                            <h3 class="text-<?= $color ?>-900"><?= htmlspecialchars($item->queue_number ?? 'N/A'); ?></h3>
                            <p class="text-<?= $color ?>-800"><?= htmlspecialchars($item->name ?? 'Unknown'); ?></p>
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

            const fallbackSector = getSectorFromAction(data.action);
            const rawStatus = data.status_text || data.status || fallbackSector;

            data.status = normalizeSector(rawStatus);
            data.status_text = data.status;

            switch (data.action) {
                case "add_to_queue":
                    handleAddOrMove(data, false);
                    break;

                case "proceed_to_backroom":
                case "proceed_to_examiners":
                case "proceed_to_payment":
                case "proceed_to_fireprotection":
                case "proceed_to_businesstax":
                case "proceed_to_releasing":
                    handleAddOrMove(data, true);
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
        examiners: 'yellow', // Ensure yellow is used for examiners
        businesstax: 'purple',
        payment: 'blue',
        fireprotection: 'orange',
        releasing: 'green'
    };

    const sectorAlias = {
        examiner: "examiners",
        backroom: "backroom",
        businesstax: "businesstax",
        fireprotection: "fireprotection",
        releasing: "releasing",
        payment: "payment"
    };

    function normalizeSector(sector) {
        const mapping = {
            success: 'backroom',
            'landtax': 'landtax',
            'bfp/fire': 'fireprotection',
            payment: 'payment',
            'business tax': 'businesstax',
            examiners: 'examiners',
            examiner: 'examiners',
            backroom: 'backroom',
            releasing: 'releasing'
        };
        const normalized = mapping[sector] || sector;
        return normalized === 'success' ? 'backroom' : normalized;
    }

    function capitalize(word) {
        return word.charAt(0).toUpperCase() + word.slice(1);
    }

    function getSectorFromAction(action) {
        const map = {
            proceed_to_backroom: 'backroom',
            proceed_to_examiners: 'examiners',
            proceed_to_payment: 'payment',
            proceed_to_fireprotection: 'fireprotection',
            proceed_to_businesstax: 'businesstax',
            proceed_to_releasing: 'releasing'
        };
        return map[action] || null;
    }

    function handleAddOrMove(data, isMoved = false) {
        removeQueueItem(data.queue_id);
        addQueueItem(data, isMoved);
    }

    function createQueueItem(data) {
        const sector = (data.status || '').toLowerCase();
        const color = sectorColors[sector] || 'gray'; // Ensure color is selected properly based on sector

        return `
        <div id="queue-item-${data.queue_id}" 
            class="queue-item bg-${color}-100 border-l-4 border-${color}-600">
            <h3 class="text-${color}-900">${data.queue_number || 'N/A'}</h3>
            <p class="text-${color}-800">${data.name || 'Unknown'}</p>
        </div>
    `;
    }

    function addQueueItem(data, isMoved = false) {
        const currentSector = normalizeSector(data.status);

        // 🛑 If normalization still results in "success", block it here
        if (currentSector === "success") {
            console.warn(`❌ Prevented creation of invalid sector "success".`);
            return;
        }

        const sectorId = `${currentSector}-queue`;
        let sectorQueue = document.getElementById(sectorId);

        if (!sectorQueue) {
            console.warn(`⚠️ Sector "${sectorId}" does not exist. Creating a new container.`);
            sectorQueue = document.createElement('div');
            sectorQueue.id = sectorId;
            sectorQueue.className = 'queue-row';
            sectorQueue.innerHTML = `<h3 class="sector-title text-${sectorColors[currentSector] || 'gray'}-600 w-full">${capitalize(currentSector)}</h3>`;
            document.getElementById("queue-container").appendChild(sectorQueue);
        }

        const queueId = `queue-item-${data.queue_id}`;
        if (document.getElementById(queueId)) {
            console.warn(`⚠️ Queue item ${queueId} already exists.`);
            return;
        }

        const newQueueItemHTML = createQueueItem(data);
        sectorQueue.insertAdjacentHTML('beforeend', newQueueItemHTML);

        const items = sectorQueue.querySelectorAll('.queue-item');
        if (items.length > 10) {
            items[0].remove();
        }

        if (isMoved) {
            const rawNewSector = getSectorFromAction(data.action);
            const newSector = normalizeSector(rawNewSector);
            if (newSector && newSector !== currentSector) {
                moveQueueItemToNewSector(data, newSector);
            }
        }
    }

    function removeQueueItem(queueId) {
        const queueItem = document.getElementById(`queue-item-${queueId}`);
        if (queueItem) {
            queueItem.remove();
            console.log(`✅ Queue ID ${queueId} removed from UI.`);
        } else {
            console.warn(`⚠️ Queue item ${queueId} not found.`);
        }
    }

    function moveQueueItemToNewSector(data, newSectorRaw) {
        const newSector = normalizeSector(newSectorRaw);
        const currentSectorId = `${normalizeSector(data.status)}-queue`;
        const currentSectorQueue = document.getElementById(currentSectorId);
        const queueItem = document.getElementById(`queue-item-${data.queue_id}`);

        if (queueItem && currentSectorQueue) {
            currentSectorQueue.removeChild(queueItem);
            console.log(`✅ Queue ID ${data.queue_id} moved from ${data.status} to ${newSector}.`);

            data.status = newSector;
            data.status_text = newSector;

            let newSectorQueue = document.getElementById(`${newSector}-queue`);
            if (!newSectorQueue) {
                console.warn(`⚠️ New sector "${newSector}" not found. Creating a new container.`);
                newSectorQueue = document.createElement('div');
                newSectorQueue.id = `${newSector}-queue`;
                newSectorQueue.className = 'queue-row';
                newSectorQueue.innerHTML = `<h3 class="sector-title text-${sectorColors[newSector] || 'gray'}-600 w-full">${capitalize(newSector)}</h3>`;
                document.getElementById("queue-container").appendChild(newSectorQueue);
            }
            newSectorQueue.appendChild(queueItem);
        }
    }
</script>


</body>

</html>