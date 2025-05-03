<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RELEASING - Queue Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    animation: {
                        'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }

        .queue-item {
            transition: all 0.3s ease;
        }

        .queue-item:hover {
            transform: translateY(-3px);
        }

        .delete-btn {
            transition: all 0.2s ease;
        }

        .delete-btn:hover {
            transform: scale(1.1);
        }

        .badge-pulse {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(14, 165, 233, 0.7);
            }

            70% {
                box-shadow: 0 0 0 10px rgba(14, 165, 233, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(14, 165, 233, 0);
            }
        }
    </style>
</head>

<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <header class="mb-8">
            <nav class="bg-white rounded-xl shadow-md px-6 py-4 flex justify-between items-center">
                <div class="flex items-center space-x-3">
                    <div class="bg-primary-600 text-white p-2 rounded-lg">
                        <i class="fas fa-layer-group text-xl"></i>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-800">Queue Management</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="hidden md:flex items-center space-x-4">
                        <div class="text-right">
                            <p class="text-sm text-gray-500" id="current-date">Loading date...</p>
                            <p class="text-lg font-medium text-gray-700" id="current-time">Loading time...</p>
                        </div>
                        <div class="h-8 w-px bg-gray-200"></div>
                    </div>

                </div>
            </nav>
        </header>

        <!-- Main Content -->
        <main>
            <!-- Stats Overview -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-md p-6 flex items-center">
                    <div class="rounded-full bg-blue-100 p-3 mr-4">
                        <i class="fas fa-users text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Total Customers</p>
                        <h3 class="text-2xl font-bold text-gray-800" id="total-customers">
                            <?php echo count($releasing); ?>
                        </h3>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-md p-6 flex items-center">
                    <div class="rounded-full bg-green-100 p-3 mr-4">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Completed</p>
                        <h3 class="text-2xl font-bold text-gray-800" id="completed-count">
                            <?php echo count($releasing); ?>
                        </h3>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-md p-6 flex items-center">
                    <div class="rounded-full bg-purple-100 p-3 mr-4">
                        <i class="fas fa-clock text-purple-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Average Wait Time</p>
                        <h3 class="text-2xl font-bold text-gray-800">12 min</h3>
                    </div>
                </div>
            </div>

            <!-- Queue Section -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="border-b border-gray-200 bg-gray-50 px-6 py-4 flex justify-between items-center">
                    <div class="flex items-center">
                        <h2 class="text-xl font-semibold text-gray-800">Releasing Queue</h2>
                        <div
                            class="ml-3 bg-primary-100 text-primary-800 py-1 px-3 rounded-full text-sm font-medium flex items-center">
                            <span id="live-indicator"
                                class="h-2 w-2 rounded-full bg-primary-500 animate-pulse mr-2"></span>
                            Live
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <button id="refresh-btn"
                            class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-2 rounded-md transition duration-300 flex items-center">
                            <i class="fas fa-sync-alt mr-2"></i>
                            <span>Refresh</span>
                        </button>
                    </div>
                </div>

                <div class="px-6 py-6">
                    <div class="mb-4">
                        <input type="text" id="search-queue" placeholder="Search by name..."
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    </div>

                    <!-- Queue Grid -->
                    <div id="queue-container"
                        class="grid gap-4 grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5">
                        <?php foreach ($releasing as $r): ?>
                            <div id="queue-item-<?= $r->id; ?>"
                                class="queue-item bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                                <div class="p-4 flex flex-col items-center">
                                    <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center mb-3">
                                        <i class="fas fa-user text-green-600"></i>
                                    </div>
                                    <span class="text-gray-800 font-medium text-lg"><?= $r->name; ?></span>
                                    <div class="mt-2 flex items-center">
                                        <span
                                            class="bg-green-100 text-green-600 py-1 px-3 rounded-full text-sm font-medium">
                                            Completed
                                        </span>
                                    </div>
                                    <div class="mt-3 flex items-center justify-between w-full">
                                        <span class="text-xs text-gray-500">
                                            <i class="far fa-clock mr-1"></i>
                                            <?= date('h:i A', strtotime($r->created_at)); ?>
                                        </span>
                                        <button
                                            class="delete-btn bg-white text-red-500 hover:bg-red-50 p-2 rounded-full border border-gray-200 shadow-sm"
                                            data-id="<?= $r->id; ?>">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Empty State -->
                    <div id="empty-state" class="hidden text-center py-16">
                        <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-inbox text-gray-400 text-3xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-700">No items in queue</h3>
                        <p class="text-gray-500 mt-2">When new customers arrive, they will appear here.</p>
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="mt-8 text-center text-gray-500 text-sm">
            <p>&copy; 2025 Queue Management System. All rights reserved.</p>
        </footer>
    </div>

    <!-- Notification Toast -->
    <div id="notification-toast"
        class="fixed bottom-4 right-4 bg-white shadow-lg rounded-lg p-4 flex items-center max-w-md hidden animate__animated animate__fadeInUp">
        <div class="mr-3 flex-shrink-0">
            <div class="h-10 w-10 rounded-full bg-primary-100 flex items-center justify-center">
                <i id="toast-icon" class="fas fa-info-circle text-primary-600"></i>
            </div>
        </div>
        <div class="flex-1">
            <h4 id="toast-title" class="text-sm font-medium text-gray-900">New notification</h4>
            <p id="toast-message" class="text-sm text-gray-500">Notification message here</p>
        </div>
        <button id="close-toast" class="ml-4 text-gray-400 hover:text-gray-500">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <script>
        // WebSocket initialization
        const socket = new WebSocket("ws://localhost:8080");
        let queueItemsCount = document.querySelectorAll('#queue-container > div').length;
        updateEmptyState();

        // Initialize date and time
        function updateDateTime() {
            const now = new Date();
            document.getElementById('current-date').textContent = now.toLocaleDateString('en-US', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
            document.getElementById('current-time').textContent = now.toLocaleTimeString('en-US', {
                hour: '2-digit',
                minute: '2-digit'
            });
        }
        updateDateTime();
        setInterval(updateDateTime, 1000);

        // WebSocket event handlers
        socket.onopen = () => {
            console.log("Connected to WebSocket server (Releasing)");
            showNotification("Connection established", "Successfully connected to the queue system.", "success");
        };

        socket.onmessage = (event) => {
            try {
                const data = JSON.parse(event.data);

                if (data.action === "proceed_to_releasing") {
                    console.log("New Releasing queue item received:", data);
                    addQueueItem(data, "Releasing");
                    showNotification("New Customer", `${data.name} has been added to the queue.`, "info");
                    updateStats();
                } else if (data.action === "delete_queue") {
                    console.log(`Queue ID ${data.id} deleted.`);
                    removeQueueItem(data.id);
                    showNotification("Queue Updated", "An item has been removed from the queue.", "warning");
                    updateStats();
                }
            } catch (error) {
                console.error("Error parsing WebSocket data:", error);
                showNotification("Error", "Failed to process queue update.", "error");
            }
        };

        socket.onerror = (error) => {
            console.error("WebSocket Error: ", error);
            showNotification("Connection Error", "Failed to connect to the queue system.", "error");
        };

        socket.onclose = () => {
            console.log("Disconnected from WebSocket server");
            showNotification("Disconnected", "Lost connection to the queue system.", "warning");
        };

        function createQueueItem(data, currentUser) {
            // Format timestamp
            const formattedTime = moment(data.created_at).format('h:mm A');

            // Set URLs
            const deleteUrl = `http://localhost/OJT/queueing-system/index.php/controller_queueing/delete_queue/${data.id}`;

            // Determine processing state
            const isProcessingByCurrentUser = data.processing_by && String(data.processing_by) === String(currentUser);
            const isProcessing = !!data.processing_by;
            const processingText = isProcessing ? `Processing by ${data.processing_by}` : "Completed";
            const statusColorClass = isProcessing ? "bg-yellow-100 text-yellow-600" : "bg-green-100 text-green-600";
            const iconClass = isProcessing ? "fa-spinner fa-spin" : "fa-check";

            return `
                <div id="queue-item-${data.id}" class="queue-item bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden animate__animated animate__fadeIn">
                    <div class="p-4 flex flex-col items-center">
                        <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center mb-3">
                            <i class="fas fa-user text-green-600"></i>
                        </div>
                        <span class="text-gray-800 font-medium text-lg">${data.name}</span>
                        <div class="mt-2 flex items-center">
                            <span class="${statusColorClass} py-1 px-3 rounded-full text-sm font-medium">
                                <i class="fas ${iconClass} mr-1"></i>
                                ${processingText}
                            </span>
                        </div>
                        <div class="mt-3 flex items-center justify-between w-full">
                            <span class="text-xs text-gray-500">
                                <i class="far fa-clock mr-1"></i>
                                ${formattedTime}
                            </span>
                            <button class="delete-btn bg-white text-red-500 hover:bg-red-50 p-2 rounded-full border border-gray-200 shadow-sm" data-id="${data.id}" data-url="${deleteUrl}">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
        }

        function addQueueItem(data, defaultStatus) {
            const queueContainer = document.getElementById("queue-container");

            if (!queueContainer) {
                console.error("Error: Queue container not found.");
                return;
            }

            const queueId = `queue-item-${data.id}`;
            if (document.getElementById(queueId)) {
                console.warn(`Queue item ${queueId} already exists.`);
                return;
            }

            const newQueueItemHTML = createQueueItem(data, defaultStatus);
            queueContainer.insertAdjacentHTML('beforeend', newQueueItemHTML);
            queueItemsCount++;
            updateEmptyState();

            // Add event listener to the delete button
            const deleteBtn = document.querySelector(`#${queueId} .delete-btn`);
            if (deleteBtn) {
                deleteBtn.addEventListener('click', function () {
                    const id = this.getAttribute('data-id');
                    const url = this.getAttribute('data-url');
                    // Send delete request
                    fetch(url)
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'success') {
                                removeQueueItem(id);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showNotification("Error", "Failed to delete queue item.", "error");
                        });
                });
            }
        }

        function removeQueueItem(queueId) {
            const queueItem = document.getElementById(`queue-item-${queueId}`);

            if (queueItem) {
                queueItem.classList.add('animate__fadeOut');
                setTimeout(() => {
                    queueItem.remove();
                    queueItemsCount--;
                    updateEmptyState();
                    updateStats();
                    console.log(`Queue ID ${queueId} removed from UI.`);
                }, 500);
            } else {
                console.warn(`Queue item ${queueId} not found.`);
            }
        }

        function updateEmptyState() {
            const emptyState = document.getElementById('empty-state');
            const queueContainer = document.getElementById('queue-container');

            if (queueItemsCount === 0) {
                emptyState.classList.remove('hidden');
                queueContainer.classList.add('hidden');
            } else {
                emptyState.classList.add('hidden');
                queueContainer.classList.remove('hidden');
            }
        }

        function updateStats() {
            const totalCustomers = document.getElementById('total-customers');
            const completedCount = document.getElementById('completed-count');

            if (totalCustomers) {
                totalCustomers.textContent = queueItemsCount;
            }

            if (completedCount) {
                completedCount.textContent = queueItemsCount;
            }
        }

        function showNotification(title, message, type = 'info') {
            const toast = document.getElementById('notification-toast');
            const toastTitle = document.getElementById('toast-title');
            const toastMessage = document.getElementById('toast-message');
            const toastIcon = document.getElementById('toast-icon');

            // Set content
            toastTitle.textContent = title;
            toastMessage.textContent = message;

            // Set icon and color based on type
            toastIcon.className = 'fas';
            switch (type) {
                case 'success':
                    toastIcon.classList.add('fa-check-circle', 'text-green-600');
                    break;
                case 'warning':
                    toastIcon.classList.add('fa-exclamation-triangle', 'text-yellow-600');
                    break;
                case 'error':
                    toastIcon.classList.add('fa-times-circle', 'text-red-600');
                    break;
                default:
                    toastIcon.classList.add('fa-info-circle', 'text-primary-600');
            }

            // Show toast
            toast.classList.remove('hidden');

            // Auto hide after 5 seconds
            setTimeout(() => {
                toast.classList.add('animate__fadeOutDown');
                setTimeout(() => {
                    toast.classList.remove('animate__fadeOutDown');
                    toast.classList.add('hidden');
                }, 500);
            }, 5000);
        }

        // Event listeners
        document.getElementById('close-toast').addEventListener('click', function () {
            const toast = document.getElementById('notification-toast');
            toast.classList.add('animate__fadeOutDown');
            setTimeout(() => {
                toast.classList.remove('animate__fadeOutDown');
                toast.classList.add('hidden');
            }, 500);
        });

        document.getElementById('refresh-btn').addEventListener('click', function () {
            location.reload();
        });

        document.getElementById('search-queue').addEventListener('input', function () {
            const searchValue = this.value.toLowerCase();
            const queueItems = document.querySelectorAll('#queue-container > div');

            queueItems.forEach(item => {
                const name = item.querySelector('.text-gray-800').textContent.toLowerCase();
                if (name.includes(searchValue)) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        });

        // Add event listeners to existing delete buttons
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                const url = `http://localhost/OJT/queueing-system/index.php/controller_queueing/delete_queue/${id}`;

                // Send delete request
                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            removeQueueItem(id);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showNotification("Error", "Failed to delete queue item.", "error");
                    });
            });
        });
    </script>
</body>

</html>