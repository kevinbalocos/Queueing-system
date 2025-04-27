<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RELEASING QUEUE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen p-5">
    <nav class="bg-white text-cyan-700 fixed top-0 left-0 w-full shadow-lg z-10">
        <div class="px-4">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-3">
                    <span class="text-xl font-bold uppercase tracking-widest">Releasing Queue</span>
                </div>

                <!-- User Dropdown -->
                <div class="relative flex ">
                    <div class="hidden md:flex space-x-6 items-center mx-5">
                        <?php if ($this->session->userdata('logged_in')): ?>
                            <span class="text-lg font-semibold text-xs font-bold uppercase">Welcome,
                                <?= htmlspecialchars($this->session->userdata('username')); ?>!</span>
                        <?php else: ?>
                            <span class="text-lg font-semibold">Guest</span>
                        <?php endif; ?>
                    </div>
                    <button id="user-menu-btn" class="focus:outline-none">
                        <i class="fas fa-user-circle text-2xl"></i>
                    </button>

                    <!-- Dropdown Menu -->
                    <div id="user-menu"
                        class="absolute right-0 mt-2 w-40 bg-white border border-gray-200 rounded-lg shadow-lg hidden">
                        <a href="<?= base_url('controller_admin_landing/logout'); ?>"
                            class="flex items-center px-4 py-2 text-red-600 hover:bg-gray-100">
                            <i class="fas fa-sign-out-alt mr-2"></i> Logout
                        </a>
                    </div>
                </div>

                <!-- Hamburger Button -->
                <button id="menu-btn" class="md:hidden focus:outline-none">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
            </div>
        </div>
    </nav>

    <!-- JavaScript for Dropdown -->
    <script>
        document.getElementById('user-menu-btn').addEventListener('click', function () {
            document.getElementById('user-menu').classList.toggle('hidden');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function (event) {
            const menu = document.getElementById('user-menu');
            const button = document.getElementById('user-menu-btn');
            if (!menu.contains(event.target) && !button.contains(event.target)) {
                menu.classList.add('hidden');
            }
        });
    </script>

    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-5xl mt-16">
        <!-- <h2 class="text-2xl font-bold text-center text-gray-800 mb-4">Releasing Queue</h2> -->

        <div class="max-h-[80vh] overflow-y-auto">
            <div id="queue-container"
                class="grid gap-4 grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5">
                <?php foreach ($releasing as $r): ?>
                    <div class="p-4 bg-gray-50 border rounded-lg shadow flex flex-col items-center">
                        <span class="text-gray-700 font-medium text-lg"><?= $r->name; ?></span>
                        <span class="text-green-600 font-bold mt-2">Completed</span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Logout Button -->
        <!-- <div class="mt-6 text-center">
            <a href="<?= base_url('controller_admin_landing/logout'); ?>"
                class="inline-flex items-center bg-red-500 text-white px-5 py-2 rounded-lg hover:bg-red-600 transition">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M3 10a1 1 0 011-1h8.586l-2.293-2.293a1 1 0 111.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 11-1.414-1.414L12.586 11H4a1 1 0 01-1-1z"
                        clip-rule="evenodd"></path>
                </svg>
                Logout
            </a>
        </div> -->
    </div>

    <script>
        // WebSocket initialization
        const socket = new WebSocket("ws://localhost:8080");

        socket.onopen = () => console.log("Connected to WebSocket server (Releasing)");

        socket.onmessage = (event) => {
            try {
                const data = JSON.parse(event.data);

                if (data.action === "proceed_to_releasing") {
                    console.log("New Releasing queue item received:", data);
                    addQueueItem(data, "Releasing");
                } else if (data.action === "delete_queue") {
                    console.log(`Queue ID ${data.queue_id} deleted.`);
                    removeQueueItem(data.queue_id);
                }
            } catch (error) {
                console.error("Error parsing WebSocket data:", error);
            }
        };

        socket.onerror = (error) => console.error("WebSocket Error: ", error);
        socket.onclose = () => console.log("Disconnected from WebSocket server");

        function createQueueItem(data, currentUser) {
            // Format timestamp
            const formattedCreatedAt = new Date(data.created_at).toLocaleString('en-US', {
                month: 'short', day: '2-digit', year: 'numeric',
                hour: '2-digit', minute: '2-digit', hour12: true
            });

            // Set URLs
            const proceedUrl = `http://localhost/OJT/queueing-system/index.php/controller_queueing/proceed_to_releasing/${data.queue_id}`;
            const deleteUrl = `http://localhost/OJT/queueing-system/index.php/controller_queueing/delete_queue/${data.queue_id}`;

            // Determine processing state
            const isProcessingByCurrentUser = data.processing_by && String(data.processing_by) === String(currentUser);
            const isProcessing = !!data.processing_by;
            const processingText = isProcessing ? `Processing by ${data.processing_by}` : "Completed";
            const statusColorClass = "bg-cyan-100 text-cyan-500"; // Same color for both states
            const processingClass = isProcessing ? "opacity-50 cursor-not-allowed" : "";

            return `
                <!-- Queue Item -->
                <div id="queue-item-${data.queue_id}" class="p-4 bg-gray-50 border rounded-lg shadow flex flex-col items-center" data-id="${data.queue_id}">
                    <span class="text-gray-700 font-medium text-lg">${data.name}</span>
                    <span class="text-green-600 font-bold mt-2">${processingText}</span>
                    <button class="delete-btn bg-white py-3 px-3 text-cyan-900 hover:bg-gray-50 rounded-full border border-cyan-50 shadow-lg" data-id="${data.queue_id}" data-url="${deleteUrl}">
                        <i class="fa-solid fa-trash-can text-[calc(1.2vw)]"></i>
                    </button>
                </div>
            `;
        }

        function addQueueItem(data, defaultStatus) {
            const queueContainer = document.getElementById("queue-container");

            if (!queueContainer) {
                console.error("Error: Queue container not found.");
                return;
            }

            const queueId = `queue-item-${data.queue_id}`;
            if (document.getElementById(queueId)) {
                console.warn(`Queue item ${queueId} already exists.`);
                return;
            }

            const newQueueItemHTML = createQueueItem(data, defaultStatus);
            queueContainer.insertAdjacentHTML('beforeend', newQueueItemHTML);
        }

        function removeQueueItem(queueId) {
            const queueItem = document.getElementById(`queue-item-${queueId}`);

            if (queueItem) {
                queueItem.remove();
                console.log(`Queue ID ${queueId} removed from UI.`);
            } else {
                console.warn(`Queue item ${queueId} not found.`);
            }
        }
    </script>

</body>

</html>