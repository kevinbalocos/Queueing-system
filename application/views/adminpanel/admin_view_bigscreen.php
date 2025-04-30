<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BIG SCREEN - Queue Display</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    <style>
        html,
        body {
            width: 100vw;
            height: 100vh;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #0f172a;
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
        }

        .queue-container {
            width: 100vw;
            height: 100vh;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            display: grid;
            grid-template-rows: auto auto 1fr auto;
            gap: 0.5rem;
            overflow: hidden;
            padding: 1rem;
        }

        .main-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 1.5rem;
            background: linear-gradient(90deg, #1e3a8a 0%, #3b82f6 100%);
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.25);
            color: white;
            margin-bottom: 0.5rem;
        }

        .header-left {
            display: flex;
            align-items: center;
        }

        .logo {
            font-size: 2rem;
            margin-right: 1rem;
            filter: drop-shadow(0 0 10px rgba(255, 255, 255, 0.5));
        }

        .header-title {
            font-size: clamp(1.75rem, 3vw, 2.75rem);
            font-weight: 800;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            background: linear-gradient(90deg, #ffffff 0%, #cbd5e1 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .header-right {
            display: flex;
            align-items: center;
        }

        .datetime {
            font-size: 1.2rem;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.9);
            text-align: right;
        }

        .subheader {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem 1rem;
            background: rgba(30, 41, 59, 0.8);
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            color: #e2e8f0;
        }

        .stats {
            display: flex;
            gap: 1.5rem;
            align-items: center;
        }

        .stat-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .stat-icon {
            font-size: 1rem;
        }

        .marquee-container {
            overflow: hidden;
            background: rgba(15, 23, 42, 0.5);
            border-radius: 6px;
            padding: 0.5rem 0;
            margin-top: 0.5rem;
        }

        .marquee-content {
            display: inline-block;
            white-space: nowrap;
            animation: marquee 40s linear infinite;
            color: #94a3b8;
            font-size: 1rem;
        }

        @keyframes marquee {
            0% {
                transform: translateX(100%);
            }

            100% {
                transform: translateX(-100%);
            }
        }

        .sectors-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            grid-auto-rows: minmax(0, 1fr);
            gap: 0.75rem;
            padding-right: 0.5rem;
        }

        .queue-row {
            display: flex;
            flex-direction: column;
            background: rgba(15, 23, 42, 0.85);
            border-radius: 10px;
            padding: 0.75rem;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .queue-row:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.4);
        }

        .sector-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            margin-bottom: 0.75rem;
            position: relative;
            overflow: hidden;
        }

        .sector-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0) 100%);
            opacity: 0.3;
            z-index: 0;
        }

        .sector-title {
            font-size: 1.25rem;
            font-weight: 700;
            letter-spacing: 0.03em;
            text-transform: uppercase;
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .sector-icon {
            font-size: 1.2rem;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            filter: drop-shadow(0 0 3px rgba(255, 255, 255, 0.3));
        }

        .sector-count {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            padding: 0.3rem 0.85rem;
            font-size: 0.9rem;
            font-weight: 600;
            position: relative;
            z-index: 1;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        .queue-items-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(min(30px, 100%), 1fr));
            gap: 0.6rem;
            overflow-y: auto;
            max-height: 100%;
            padding: 0.25rem;
        }

        .queue-item {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 0.75rem 0.5rem;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .queue-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0) 100%);
            opacity: 0.2;
            z-index: 0;
        }

        .queue-item:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 6px 14px rgba(0, 0, 0, 0.3);
        }

        .queue-item h3 {
            font-size: clamp(1.2rem, 2vw, 1.4rem);
            font-weight: 700;
            margin-bottom: 0.3rem;
            position: relative;
            z-index: 1;
        }

        .queue-item p {
            font-size: clamp(0.75rem, 1.5vw, 0.9rem);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            text-align: center;
            max-width: 100%;
            position: relative;
            z-index: 1;
            opacity: 0.9;
        }

        .hidden {
            display: none;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        /* Enhanced animations */
        @keyframes highlight {
            0% {
                transform: scale(1);
                box-shadow: 0 0 0 rgba(255, 255, 255, 0);
            }

            25% {
                transform: scale(1.08);
                box-shadow: 0 0 20px rgba(255, 255, 255, 0.4);
            }

            75% {
                transform: scale(1.05);
                box-shadow: 0 0 15px rgba(255, 255, 255, 0.3);
            }

            100% {
                transform: scale(1);
                box-shadow: 0 0 0 rgba(255, 255, 255, 0);
            }
        }

        .highlight-animation {
            animation: highlight 1s ease-in-out;
        }

        /* Footer */
        .footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 1rem;
            background: rgba(15, 23, 42, 0.8);
            border-radius: 8px;
            margin-top: 0.5rem;
            color: #94a3b8;
            font-size: 0.85rem;
        }

        .footer-left {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .connection-status {
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .status-indicator {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: #10b981;
        }

        .disconnected {
            background-color: #ef4444;
        }

        .footer-right {
            font-weight: 500;
        }

        /* Pulsating effect for live items */
        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.5);
            }

            70% {
                box-shadow: 0 0 0 5px rgba(59, 130, 246, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(59, 130, 246, 0);
            }
        }

        .pulse {
            animation: pulse 2s infinite;
        }
    </style>
</head>

<body>

    <div id="queue-container" class="queue-container">
        <!-- Main Header -->
        <div class="main-header">
            <div class="header-left">
                <div class="logo">
                    <i class="fas fa-display"></i>
                </div>
                <h1 class="header-title">QUEUE MANAGEMENT SYSTEM</h1>
            </div>
            <div class="header-right">
                <div class="datetime">
                    <div id="current-time">00:00:00</div>
                    <div id="current-date">Loading date...</div>
                </div>
            </div>
        </div>

        <!-- Subheader with Statistics -->
        <div class="subheader">
            <div class="stats">
                <div class="stat-item">
                    <span class="stat-icon"><i class="fas fa-users"></i></span>
                    <span>Total Waiting: <span id="total-waiting">0</span></span>
                </div>
                <div class="stat-item">
                    <span class="stat-icon"><i class="fas fa-clock"></i></span>
                    <span>Last Updated: <span id="last-updated">Just now</span></span>
                </div>
            </div>
            <div class="stat-item pulse">
                <span class="stat-icon"><i class="fas fa-broadcast-tower"></i></span>
                <span>LIVE UPDATE</span>
            </div>
        </div>

        <!-- News Ticker / Marquee -->
        <div class="marquee-container">
            <div class="marquee-content">
                WELCOME TO OUR QUEUE MANAGEMENT SYSTEM • PLEASE WAIT FOR YOUR NUMBER TO BE CALLED • FOR ASSISTANCE,
                PLEASE CONTACT OUR HELP DESK • THANK YOU FOR YOUR PATIENCE
            </div>
        </div>

        <!-- Main Content: Sectors Grid -->
        <div class="sectors-grid">
            <!-- Sectors -->
            <?php
            $sectors = [
                'landtax' => ['color' => 'green', 'icon' => 'fas fa-landmark'],
                'backroom' => ['color' => 'teal', 'icon' => 'fas fa-search'],
                'examiners' => ['color' => 'yellow', 'icon' => 'fas fa-file-signature'],
                'businesstax' => ['color' => 'purple', 'icon' => 'fas fa-briefcase'],
                'payment' => ['color' => 'blue', 'icon' => 'fas fa-money-bill-wave'],
                'fireprotection' => ['color' => 'orange', 'icon' => 'fas fa-fire-extinguisher'],
                'releasing' => ['color' => 'green', 'icon' => 'fas fa-file-export']
            ];
            foreach ($sectors as $sector => $details): ?>
                <div id="<?= $sector ?>-queue" class="queue-row">
                    <div class="sector-header bg-<?= $details['color'] ?>-900 bg-opacity-40">
                        <h3 class="sector-title text-<?= $details['color'] ?>-400">
                            <span class="sector-icon"><i class="<?= $details['icon'] ?>"></i></span> <?= ucfirst($sector) ?>
                        </h3>
                        <span class="sector-count text-<?= $details['color'] ?>-300">
                            <?php echo !empty($$sector) ? count($$sector) : '0'; ?> items
                        </span>
                    </div>
                    <div class="queue-items-container">
                        <?php if (!empty($$sector)): ?>
                            <?php foreach ($$sector as $index => $item): ?>
                                <div id="queue-item-<?= htmlspecialchars($item->queue_id ?? $item->id); ?>"
                                    class="queue-item bg-<?= $details['color'] ?>-900 bg-opacity-30 border-l-4 border-<?= $details['color'] ?>-500 <?= $index >= 10 ? 'hidden queue-hidden' : '' ?>"
                                    data-sector="<?= $sector ?>">
                                    <h3 class="text-<?= $details['color'] ?>-400">
                                        <?= htmlspecialchars($item->queue_number ?? 'N/A'); ?>
                                    </h3>
                                    <p class="text-<?= $details['color'] ?>-200">
                                        <?= htmlspecialchars($item->name ?? 'Unknown'); ?>
                                    </p>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="footer-left">
                <div class="connection-status">
                    <div id="status-indicator" class="status-indicator"></div>
                    <span id="connection-text">Connected</span>
                </div>
            </div>
            <div class="footer-right">
                © 2025 Queue Management System
            </div>
        </div>
    </div>

    <script>
        const socket = new WebSocket("ws://localhost:8080");

        socket.onopen = () => {
            console.log("✅ Connected to WebSocket server");
            document.getElementById('status-indicator').classList.remove('disconnected');
            document.getElementById('connection-text').textContent = 'Connected';
            showNotification("Connected to server", "success");
        };

        socket.onmessage = (event) => {
            try {
                const data = JSON.parse(event.data);
                console.log("📩 Received WebSocket Data: ", data);

                const fallbackSector = getSectorFromAction(data.action);
                const rawStatus = data.status_text || data.status || fallbackSector;

                data.status = normalizeSector(rawStatus);
                data.status_text = data.status;

                // Update last updated time
                document.getElementById('last-updated').textContent = 'Just now';

                switch (data.action) {
                    case "add_to_queue":
                        handleAddOrMove(data, false);
                        showNotification(`New ticket added: ${data.queue_number}`, "info");
                        break;

                    case "proceed_to_backroom":
                    case "proceed_to_examiners":
                    case "proceed_to_payment":
                    case "proceed_to_fireprotection":
                    case "proceed_to_businesstax":
                    case "proceed_to_releasing":
                        handleAddOrMove(data, true);
                        showNotification(`Ticket ${data.queue_number} moved to ${capitalize(data.status)}`, "info");
                        break;

                    case "delete_queue":
                        removeQueueItem(data.queue_id);
                        showNotification(`Ticket removed: ${data.queue_number || data.queue_id}`, "warning");
                        break;

                    default:
                        console.warn("⚠️ Unknown WebSocket action:", data.action);
                }

                // Update sector counts after any action
                updateSectorCounts();
                updateTotalWaiting();

            } catch (error) {
                console.error("⚠️ Error parsing WebSocket data:", error);
                showNotification("Error processing update", "error");
            }
        };

        socket.onerror = (error) => {
            console.error("❌ WebSocket Error: ", error);
            document.getElementById('status-indicator').classList.add('disconnected');
            document.getElementById('connection-text').textContent = 'Disconnected';
            showNotification("Connection error", "error");
        };

        socket.onclose = () => {
            console.log("🔴 Disconnected from WebSocket server");
            document.getElementById('status-indicator').classList.add('disconnected');
            document.getElementById('connection-text').textContent = 'Disconnected';
            showNotification("Disconnected from server", "warning");

            // Try to reconnect after 5 seconds
            setTimeout(() => {
                showNotification("Attempting to reconnect...", "info");
                // In a real implementation, we would recreate the WebSocket here
            }, 5000);
        };

        const sectorColors = {
            landtax: 'green',
            backroom: 'teal',
            examiners: 'yellow',
            businesstax: 'purple',
            payment: 'blue',
            fireprotection: 'orange',
            releasing: 'green'
        };

        const sectorIcons = {
            landtax: '<i class="fas fa-landmark"></i>',
            backroom: '<i class="fas fa-search"></i>',
            examiners: '<i class="fas fa-file-signature"></i>',
            businesstax: '<i class="fas fa-briefcase"></i>',
            payment: '<i class="fas fa-money-bill-wave"></i>',
            fireprotection: '<i class="fas fa-fire-extinguisher"></i>',
            releasing: '<i class="fas fa-file-export"></i>'
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

            // If this is a movement to a new sector based on action, update the status
            if (isMoved) {
                const newSector = getSectorFromAction(data.action);
                if (newSector) {
                    data.status = newSector;
                    data.status_text = newSector;
                }
            }

            addQueueItem(data, isMoved);
        }

        function createQueueItem(data) {
            const sector = normalizeSector(data.status || '').toLowerCase();
            const color = sectorColors[sector] || 'gray';

            return `
                <div id="queue-item-${data.queue_id}" 
                    class="queue-item bg-${color}-900 bg-opacity-30 border-l-4 border-${color}-500"
                    data-sector="${sector}">
                    <h3 class="text-${color}-400">${data.queue_number || 'N/A'}</h3>
                    <p class="text-${color}-200">${data.name || 'Unknown'}</p>
                </div>
            `;
        }

        function addQueueItem(data, isMoved = false) {
            const currentSector = normalizeSector(data.status);

            if (currentSector === "success") return;

            const sectorId = `${currentSector}-queue`;
            let sectorQueue = document.getElementById(sectorId);

            if (!sectorQueue) {
                sectorQueue = document.createElement('div');
                sectorQueue.id = sectorId;
                sectorQueue.className = 'queue-row';

                const color = sectorColors[currentSector] || 'gray';
                const icon = sectorIcons[currentSector] || '📋';

                sectorQueue.innerHTML = `
                    <div class="sector-header bg-${color}-900 bg-opacity-40">
                        <h3 class="sector-title text-${color}-400">
                            <span class="sector-icon">${icon}</span> ${capitalize(currentSector)}
                        </h3>
                        <span class="sector-count text-${color}-300">0 items</span>
                    </div>
                    <div class="queue-items-container"></div>
                `;

                document.querySelector(".sectors-grid").appendChild(sectorQueue);
            }

            const queueId = `queue-item-${data.queue_id}`;
            if (document.getElementById(queueId)) return;

            const newQueueItemHTML = createQueueItem(data);

            const tempDiv = document.createElement("div");
            tempDiv.innerHTML = newQueueItemHTML;
            const newQueueElement = tempDiv.firstElementChild;

            // Add a hidden class if more than 10 exist already
            const itemsContainer = sectorQueue.querySelector('.queue-items-container');
            const items = itemsContainer.querySelectorAll('.queue-item');
            if (items.length >= 10) {
                newQueueElement.classList.add("hidden");
            }

            // Add highlight animation if it's a moved item
            if (isMoved) {
                newQueueElement.classList.add('highlight-animation');
                setTimeout(() => {
                    newQueueElement.classList.remove('highlight-animation');
                }, 1000);
            }

            itemsContainer.appendChild(newQueueElement);
            updateSectorCounts();
            updateTotalWaiting();
        }

        function removeQueueItem(queueId) {
            const queueItem = document.getElementById(`queue-item-${queueId}`);
            if (queueItem) {
                const sectorQueue = queueItem.closest('.queue-row');
                const itemsContainer = queueItem.parentElement;

                queueItem.remove();
                console.log(`✅ Queue ID ${queueId} removed from UI.`);

                // Adjust the queue so 10 items are visible
                const visibleItems = itemsContainer.querySelectorAll('.queue-item:not(.hidden)');
                const hiddenItems = itemsContainer.querySelectorAll('.queue-item.hidden');

                const needed = 10 - visibleItems.length;
                for (let i = 0; i < needed && i < hiddenItems.length; i++) {
                    const hiddenItem = hiddenItems[i];
                    hiddenItem.classList.remove("hidden");
                    itemsContainer.appendChild(hiddenItem); // Move it to the end
                }

                updateSectorCounts();
                updateTotalWaiting();
            } else {
                console.warn(`⚠️ Queue item ${queueId} not found.`);
            }
        }

        function moveQueueItemToNewSector(data, newSectorRaw) {
            // This function is now obsolete since we handle color changes directly
            // in the handleAddOrMove function by removing and recreating the item
            // But keeping it for compatibility

            const newSector = normalizeSector(newSectorRaw);
            // Update data status before recreating
            data.status = newSector;
            data.status_text = newSector;

            // Remove and recreate with new sector colors
            removeQueueItem(data.queue_id);
            addQueueItem(data, true); // true indicates it was moved
        }

        function updateSectorCounts() {
            const sectors = document.querySelectorAll('.queue-row');
            sectors.forEach(sector => {
                const itemsCount = sector.querySelectorAll('.queue-items-container .queue-item').length;
                const countElement = sector.querySelector('.sector-count');
                if (countElement) {
                    countElement.textContent = `${itemsCount} items`;
                }
            });
        }

        function updateTotalWaiting() {
            const allItems = document.querySelectorAll('.queue-item');
            document.getElementById('total-waiting').textContent = allItems.length;
        }

        function showNotification(message, type = "info") {
            const colors = {
                success: { bg: "#10b981", text: "#ffffff" },
                error: { bg: "#ef4444", text: "#ffffff" },
                warning: { bg: "#f59e0b", text: "#ffffff" },
                info: { bg: "#3b82f6", text: "#ffffff" }
            };

            Toastify({
                text: message,
                duration: 3000,
                gravity: "top",
                position: "right",
                style: {
                    background: colors[type].bg,
                    color: colors[type].text,
                    borderRadius: "8px",
                    boxShadow: "0 4px 12px rgba(0, 0, 0, 0.15)"
                }
            }).showToast();
        }

        // Update date and time
        function updateDateTime() {
            const now = new Date();

            // Format time: HH:MM:SS
            const time = now.toLocaleTimeString('en-US', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: true
            });

            // Format date: Day, Month DD, YYYY
            const date = now.toLocaleDateString('en-US', {
                weekday: 'long',
                month: 'long',
                day: 'numeric',
                year: 'numeric'
            });

            document.getElementById('current-time').textContent = time;
            document.getElementById('current-date').textContent = date;
        }

        // Initial count update and setup
        window.addEventListener('load', () => {
            updateSectorCounts();
            updateTotalWaiting();
            updateDateTime();

            // Update date and time every second
            setInterval(updateDateTime, 1000);

            // Update "last updated" every minute
            setInterval(() => {
                const lastUpdated = document.getElementById('last-updated');
                if (lastUpdated.textContent === 'Just now') {
                    lastUpdated.textContent = '1 min ago';
                } else if (lastUpdated.textContent.includes('min')) {
                    const currentMins = parseInt(lastUpdated.textContent);
                    lastUpdated.textContent = (currentMins + 1) + ' mins ago';
                }
            }, 60000);

            // Run arrangement after a small delay
            setTimeout(arrangeSectors, 500);
        });
        function arrangeSectors() {
            const sectorsToOrder = [
                'landtax-queue',
                'backroom-queue',
                'examiners-queue',
                'businesstax-queue',
                'payment-queue',
                'fireprotection-queue',
                'releasing-queue'
            ];

            const sectorsGrid = document.querySelector('.sectors-grid');
            const currentSectors = document.querySelectorAll('.queue-row');

            // If we have all expected sectors, arrange them
            if (currentSectors.length >= sectorsToOrder.length) {
                sectorsToOrder.forEach(sectorId => {
                    const sector = document.getElementById(sectorId);
                    if (sector) {
                        sectorsGrid.appendChild(sector); // Move to the end in correct order
                    }
                });
            }
        }

        // Function to cycle through queue items if there are more than can be displayed
        function setupQueueRotation() {
            setInterval(() => {
                const sectors = document.querySelectorAll('.queue-row');

                sectors.forEach(sector => {
                    const itemsContainer = sector.querySelector('.queue-items-container');
                    const visibleItems = itemsContainer.querySelectorAll('.queue-item:not(.hidden)');
                    const hiddenItems = itemsContainer.querySelectorAll('.queue-item.hidden');

                    // Only rotate if we have hidden items to show
                    if (hiddenItems.length > 0 && visibleItems.length > 0) {
                        // Hide the first visible item
                        visibleItems[0].classList.add('hidden');

                        // Show the first hidden item
                        hiddenItems[0].classList.remove('hidden');

                        // Move the newly visible item to the end for a smooth transition
                        itemsContainer.appendChild(hiddenItems[0]);
                    }
                });
            }, 8000); // Rotate every 8 seconds
        }

        // Setup the queue rotation
        window.addEventListener('load', () => {
            // Previous load event handlers...
            setupQueueRotation();

            // Add attention-grabbing animation for new items
            setInterval(() => {
                const pulseElement = document.querySelector('.pulse');
                pulseElement.style.opacity = "0.7";
                setTimeout(() => {
                    pulseElement.style.opacity = "1";
                }, 1000);
            }, 2000);
        });

        // Function to update the news ticker content
        function updateMarqueeContent(messages) {
            const marqueeContent = document.querySelector('.marquee-content');
            if (marqueeContent) {
                const separator = ' • ';
                marqueeContent.textContent = messages.join(separator);
            }
        }

        // Default news messages
        const defaultMessages = [
            'WELCOME TO OUR QUEUE MANAGEMENT SYSTEM',
            'PLEASE WAIT FOR YOUR NUMBER TO BE CALLED',
            'FOR ASSISTANCE, PLEASE CONTACT OUR HELP DESK',
            'THANK YOU FOR YOUR PATIENCE'
        ];

        // Update with your news or announcements
        updateMarqueeContent(defaultMessages);

        // Simulated demo for testing without actual WebSocket data
        function runDemo() {
            const demoActive = false; // Set to true to activate demo mode

            if (demoActive) {
                console.log("🚀 Starting demo mode...");

                // Sample sectors and names
                const sectorNames = ['landtax', 'backroom', 'examiners', 'businesstax', 'payment', 'fireprotection', 'releasing'];
                const personNames = ['John Smith', 'Maria Garcia', 'Alex Johnson', 'Sarah Lee', 'Mohammed Ali', 'Emma Wilson', 'David Chen'];

                // Generate random queue ID
                const generateId = () => Math.floor(Math.random() * 10000);

                // Generate random queue number
                const generateQueueNumber = (sector) => {
                    const prefix = sector.charAt(0).toUpperCase();
                    return `${prefix}${Math.floor(Math.random() * 1000)}`;
                };

                // Add random items
                const addRandomItem = () => {
                    const randomSector = sectorNames[Math.floor(Math.random() * sectorNames.length)];
                    const randomName = personNames[Math.floor(Math.random() * personNames.length)];
                    const id = generateId();

                    const data = {
                        queue_id: id,
                        queue_number: generateQueueNumber(randomSector),
                        name: randomName,
                        status: randomSector,
                        action: 'add_to_queue'
                    };

                    handleAddOrMove(data, false);
                    showNotification(`Demo: Added ${data.queue_number} to ${randomSector}`, "info");
                };

                // Move random items
                const moveRandomItem = () => {
                    const allItems = document.querySelectorAll('.queue-item');
                    if (allItems.length > 0) {
                        const randomItem = allItems[Math.floor(Math.random() * allItems.length)];
                        const itemId = randomItem.id.replace('queue-item-', '');
                        const currentSector = randomItem.getAttribute('data-sector');

                        // Get random destination sector different from current
                        let newSector;
                        do {
                            newSector = sectorNames[Math.floor(Math.random() * sectorNames.length)];
                        } while (newSector === currentSector);

                        const data = {
                            queue_id: itemId,
                            queue_number: randomItem.querySelector('h3').textContent,
                            name: randomItem.querySelector('p').textContent,
                            status: newSector,
                            action: `proceed_to_${newSector}`
                        };

                        handleAddOrMove(data, true);
                        showNotification(`Demo: Moved to ${newSector}`, "info");
                    }
                };

                // Remove random items
                const removeRandomItem = () => {
                    const allItems = document.querySelectorAll('.queue-item');
                    if (allItems.length > 0) {
                        const randomItem = allItems[Math.floor(Math.random() * allItems.length)];
                        const itemId = randomItem.id.replace('queue-item-', '');

                        removeQueueItem(itemId);
                        showNotification(`Demo: Removed item ${randomItem.querySelector('h3').textContent}`, "warning");
                    }
                };

                // Add initial items
                for (let i = 0; i < 15; i++) {
                    addRandomItem();
                }

                // Schedule random events
                setInterval(addRandomItem, 10000);      // Add new item every 10 seconds
                setInterval(moveRandomItem, 15000);     // Move an item every 15 seconds
                setInterval(removeRandomItem, 20000);   // Remove an item every 20 seconds
            }
        }

        // Add these functions to your existing JavaScript code

        // Function to handle sector expansion
        function toggleSectorExpansion(sectorId) {
            const sector = document.getElementById(sectorId);
            const sectorsGrid = document.querySelector('.sectors-grid');
            const allSectors = document.querySelectorAll('.queue-row');

            // Check if this sector is already expanded
            const isExpanded = sector.classList.contains('expanded-sector');

            // Reset all sectors first
            allSectors.forEach(s => {
                s.classList.remove('expanded-sector');
                s.style.gridColumn = '';
                s.style.gridRow = '';
                s.style.height = '';
                s.style.zIndex = '';
            });

            // Reset grid template if we're collapsing
            if (isExpanded) {
                sectorsGrid.style.gridTemplateColumns = 'repeat(2, 1fr)';
                document.getElementById('expand-collapse-icon').className = 'fas fa-expand';
                document.getElementById('expand-collapse-text').textContent = 'Expand';
                return;
            }

            // Otherwise expand the clicked sector
            sector.classList.add('expanded-sector');
            sector.style.gridColumn = '1 / -1'; // Span all columns
            sector.style.gridRow = '1 / span 4'; // Take up most of the grid
            sector.style.height = 'calc(100vh - 220px)'; // Adjust height as needed
            sector.style.zIndex = '10';

            // Change grid to single column to ensure proper layout
            sectorsGrid.style.gridTemplateColumns = '1fr';

            // Update expand/collapse button
            document.getElementById('expand-collapse-icon').className = 'fas fa-compress';
            document.getElementById('expand-collapse-text').textContent = 'Collapse';
        }

        // Function to check for empty sectors and add a message
        function checkEmptySectors() {
            const sectors = document.querySelectorAll('.queue-row');

            sectors.forEach(sector => {
                const itemsContainer = sector.querySelector('.queue-items-container');
                const items = itemsContainer.querySelectorAll('.queue-item');
                const emptyMessage = sector.querySelector('.empty-sector-message');

                if (items.length === 0) {
                    // If no items and no message yet, add the empty message
                    if (!emptyMessage) {
                        const sectorName = sector.querySelector('.sector-title').textContent.trim();
                        const color = sector.id.split('-')[0];
                        const sectorColor = sectorColors[color] || 'gray';

                        const message = document.createElement('div');
                        message.className = 'empty-sector-message';
                        message.innerHTML = `
                    <div class="flex flex-col items-center justify-center p-6 text-center h-full">
                        <i class="fas fa-inbox text-${sectorColor}-400 text-4xl mb-3 opacity-60"></i>
                        <p class="text-${sectorColor}-300 text-lg">No queue items in ${sectorName} yet</p>
                        <p class="text-${sectorColor}-400 text-sm mt-2 opacity-70">New items will appear here</p>
                    </div>
                `;
                        itemsContainer.appendChild(message);
                    }
                } else if (emptyMessage) {
                    // If we have items but still have the message, remove it
                    emptyMessage.remove();
                }
            });
        }

        // Add click event listeners to sector headers
        function setupSectorExpansion() {
            const sectorHeaders = document.querySelectorAll('.sector-header');

            sectorHeaders.forEach(header => {
                header.style.cursor = 'pointer';
                header.addEventListener('click', function () {
                    const sectorId = this.closest('.queue-row').id;
                    toggleSectorExpansion(sectorId);
                });
            });

            // Add expand/collapse button to subheader
            const subheader = document.querySelector('.subheader');
            const expandButton = document.createElement('div');
            expandButton.className = 'stat-item cursor-pointer';
            expandButton.innerHTML = `
        <span class="stat-icon"><i id="expand-collapse-icon" class="fas fa-expand"></i></span>
        <span id="expand-collapse-text">Expand</span>
    `;
            expandButton.addEventListener('click', function () {
                const expandedSector = document.querySelector('.expanded-sector');
                if (expandedSector) {
                    toggleSectorExpansion(expandedSector.id); // Collapse
                } else {
                    // If none is expanded, expand the first non-empty sector
                    const sectors = document.querySelectorAll('.queue-row');
                    for (const sector of sectors) {
                        const items = sector.querySelectorAll('.queue-item');
                        if (items.length > 0) {
                            toggleSectorExpansion(sector.id);
                            break;
                        }
                    }
                }
            });
            subheader.appendChild(expandButton);

            // Initial check for empty sectors
            checkEmptySectors();
        }

        // Add CSS for expanded sectors
        function addExpandedSectorStyles() {
            const style = document.createElement('style');
            style.textContent = `
        .expanded-sector {
            transition: all 0.3s ease-in-out;
            overflow: auto;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .expanded-sector .queue-items-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 1rem;
            padding: 1rem;
        }
        
        .expanded-sector .queue-item {
            transition: all 0.3s ease;
            padding: 1rem;
        }
        
        .expanded-sector .sector-header {
            position: sticky;
            top: 0;
            z-index: 10;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
        
        .empty-sector-message {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
            min-height: 150px;
            opacity: 0.8;
            grid-column: 1 / -1;
        }
        
        /* Make all items visible when expanded */
        .expanded-sector .queue-item.hidden {
            display: flex !important;
        }
        
        .cursor-pointer {
            cursor: pointer;
        }
    `;
            document.head.appendChild(style);
        }

        // Call these functions after the page loads
        window.addEventListener('load', () => {
            // Add the new styles
            addExpandedSectorStyles();

            // Setup sector expansion
            setupSectorExpansion();

            // Check for empty sectors after any queue update
            const originalAddQueueItem = addQueueItem;
            addQueueItem = function (data, isMoved = false) {
                originalAddQueueItem(data, isMoved);
                checkEmptySectors();
            };

            const originalRemoveQueueItem = removeQueueItem;
            removeQueueItem = function (queueId) {
                originalRemoveQueueItem(queueId);
                checkEmptySectors();

                // If an expanded sector becomes empty, collapse it
                const expandedSector = document.querySelector('.expanded-sector');
                if (expandedSector) {
                    const items = expandedSector.querySelectorAll('.queue-item:not(.hidden)');
                    if (items.length === 0) {
                        toggleSectorExpansion(expandedSector.id);
                    }
                }
            };

            // Also hook into the demo function if it's running
            if (typeof runDemo === 'function') {
                const originalRunDemo = runDemo;
                runDemo = function () {
                    originalRunDemo();
                    checkEmptySectors();
                };
            }

            // Initial check
            checkEmptySectors();
        });
        // Run demo if needed
        runDemo();


    </script>
</body>

</html>