<?php
$left_items = array_slice($fireprotection, 0, 20);
$right_items = array_slice($fireprotection, 20);
$grid_cols = count($left_items) < 5 ? count($left_items) : 5;

$currentUser = isset($_SESSION['user_id']) ? strval($_SESSION['user_id']) : '';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Fire Protection Queue</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
  <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <style>
    #queue-container {
      height: 100vh;
    }
  </style>
</head>

<body class="bg-gray-100">
  <nav class="bg-white text-cyan-700 fixed top-0 left-0 w-full shadow-lg z-10">
    <div class=" px-4">
      <div class="flex justify-between items-center py-4">
        <div class="flex items-center space-x-3">
          <span class="text-xl font-bold uppercase tracking-widest">Fire Protection Queue</span>
        </div>

        <div class="hidden md:flex space-x-6 items-center">
          <?php if ($this->session->userdata('logged_in')): ?>
            <span class="text-lg font-semibold text-xs font-bold uppercase">Welcome,
              <?= htmlspecialchars($this->session->userdata('username')); ?>!</span>
          <?php else: ?>
            <span class="text-lg font-semibold">Guest</span>
          <?php endif; ?>
        </div>
        <!-- Hamburger Button -->
        <button id="menu-btn" class="md:hidden focus:outline-none">
          <i class="fas fa-bars text-2xl"></i>
        </button>
      </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="hidden md:hidden bg-blue-800 text-white py-2">
      <a href="#" class="block px-4 py-2 hover:bg-blue-600">Home</a>
      <a href="#" class="block px-4 py-2 hover:bg-blue-600">About</a>
      <a href="#" class="block px-4 py-2 hover:bg-blue-600">Services</a>
      <a href="<?= base_url('controller_admin_landing/logout'); ?>"
        class="block px-4 py-2 bg-red-500 text-center hover:bg-red-600">
        <i class="fas fa-sign-out-alt"></i> Logout
      </a>
    </div>
  </nav>
  <div class="flex min-h-screen p-5 pt-20">
    <!-- Left Section (Now Serving - Fire Protection) -->
    <div class="flex-1 bg-white p-5 rounded-lg shadow-md">
      <h2 class="text-2xl font-bold text-cyan-500 uppercase tracking-wider pb-5 text-center">
        Now Serving - Fire Protection
      </h2>
      <div id="queue-container" class="flex flex-wrap gap-1 bg-cyan-50 overflow-y-auto">
        <?php if ($fireprotection): ?>
          <?php foreach ($left_items as $item): ?>
            <?php
            $isProcessingByCurrentUser = ($item->processing_by && strval($item->processing_by) === $currentUser);
            ?>
            <div
              class="flex-1 min-w-56 queue-item p-3 bg-white border rounded-lg flex flex-col justify-center m-1 items-center shadow-md"
              id="queue-item-<?= $item->id; ?>" data-id="<?= $item->id; ?>">

              <h3 class="font-semibold text-xl"><?= $item->queue_number; ?> - <?= $item->name; ?></h3>
              <p class="text-gray-500 text-sm">Reason: <?= $item->reason; ?></p>

              <p class="text-gray-500 text-sm">
                Time Added: <span class="font-semibold">
                  <?= date("M d, Y, h:i A", strtotime($item->created_at)); ?>
                </span>
              </p>

              <p class="text-gray-500 text-sm">
                Status:
                <span class="status-text <?= $item->processing_by ? 'text-red-500' : 'text-green-500'; ?> font-semibold">
                  <?= $item->processing_by ? "Processing by {$item->processing_by}" : "Fire Protection"; ?>
                </span>
              </p>

              <!-- Mark as Processing Button -->
              <button
                class="processing-btn mt-3 bg-blue-500 py-2 px-3 text-white hover:bg-blue-600 rounded-full shadow-lg <?= $item->processing_by ? 'opacity-50 cursor-not-allowed' : '' ?>"
                data-id="<?= $item->id; ?>" data-type="fireprotection" <?= $item->processing_by ? 'disabled' : ''; ?>>
                <i class="fa-solid fa-hourglass-half"></i>
              </button>

              <!-- Proceed Button (Disabled by Default) -->
              <button
                class="proceed-btn mt-3 bg-white py-3 px-3 text-blue-900 hover:bg-gray-50 rounded-full border border-blue-50 shadow-lg <?= $isProcessingByCurrentUser ? '' : 'opacity-50 cursor-not-allowed' ?>"
                data-id="<?= $item->id; ?>"
                data-url="<?= base_url("controller_queueing/proceed_to_releasing/{$item->id}") ?>"
                <?= $isProcessingByCurrentUser ? '' : 'disabled'; ?>>
                <i class="fa-solid fa-user-check text-2xl"></i>
              </button>

            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>

    <!-- Right Section (Queue List & Add Form) -->
    <div class="ml-5 bg-white p-5 w-[400px] rounded-lg shadow-md flex flex-col">
      <h2 class="text-2xl font-bold text-blue-900 text-center">Fire Protection Queue List</h2>
      <ul class="mt-3 overflow-auto h-[600px] space-y-2" id="queueList">
        <?php foreach ($right_items as $item): ?>
          <li class="p-3 bg-gray-50 border rounded-lg" data-queue_id="<?= $item->id; ?>"
            data-queue_number="<?= $item->queue_number; ?>" data-name="<?= $item->name; ?>"
            data-reason="<?= $item->reason; ?>"
            data-proceed_url="<?= base_url('controller_queueing/proceed_to_releasing/' . $item->id); ?>">
            <?= $item->queue_number; ?> - <?= $item->name; ?> (<?= $item->reason; ?>) <br>
            <span class="text-gray-500 text-xs">Added on:
              <?= date("M d, Y h:i A", strtotime($item->created_at)); ?></span>
          </li>
        <?php endforeach; ?>
      </ul>

      <!-- Add to Fire Protection Queue Form -->
      <form action="<?= base_url('controller_queueing/add_to_fireprotection'); ?>" method="post" class="mt-5">
        <input type="text" name="name" placeholder="Enter Name" required class="border p-2 w-full rounded" />
        <select name="reason" required class="border p-2 w-full rounded mt-2">
          <option value="Fire Safety Inspection">Fire Safety Inspection</option>
          <option value="Permit Renewal">Permit Renewal</option>
        </select>
        <button type="submit"
          class="mt-2 w-full bg-blue-500 uppercase tracking-wider font-semibold text-white py-2 rounded">
          Add to Fire Protection Queue
        </button>
      </form>

      <div class="mt-10">
        <a href="<?= base_url('controller_admin_landing/logout'); ?>"
          class="flex items-center justify-center bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition">
          <i class="fas fa-sign-out-alt mr-2"></i> Logout
        </a>
      </div>
    </div>
  </div>

  <script>
    const socket = new WebSocket("ws://localhost:8080");

    socket.onopen = () => console.log("Connected to WebSocket server (Fire Protection)");

    socket.onmessage = (event) => {
      try {
        const data = JSON.parse(event.data);

        if (data.action === "proceed_to_fireprotection") {
          console.log("New Fire Protection queue item received:", data);
          addQueueItem(data, "Fire Protection");
        } else if (data.action === "proceed_to_releasing") {
          console.log("Queue item moved to Releasing:", data);
          removeQueueItem(data.queue_id);
        }
      } catch (error) {
        console.error("Error parsing WebSocket data:", error);
      }
    };

    socket.onerror = (error) => console.error("WebSocket Error: ", error);
    socket.onclose = () => console.log("Disconnected from WebSocket server");

    function formatTimestamp(dateString) {
      const date = new Date(dateString);
      return isNaN(date.getTime())
        ? "Invalid Date"
        : date.toLocaleString("en-US", {
          month: "short",
          day: "2-digit",
          year: "numeric",
          hour: "2-digit",
          minute: "2-digit",
          hour12: true,
        });
    }

    function createQueueItem(data, statusText, statusColor, processingDisabled) {
      return `
        <h3 class="font-semibold text-xl">${data.queue_number} - ${data.name}</h3>
        <p class="text-gray-500 text-sm">Reason: ${data.reason}</p>
        <p class="text-gray-500 text-sm">Time Added: <span class="font-semibold">${formatTimestamp(data.created_at)}</span></p>
        <p class="text-gray-500 text-sm">Status: <span class="status-text ${statusColor} font-semibold">${statusText}</span></p>
        <button class="processing-btn mt-3 bg-blue-500 py-2 px-3 text-white hover:bg-blue-600 rounded-full shadow-lg ${processingDisabled}"
            data-id="${data.queue_id}" data-type="fireprotection" ${processingDisabled ? "disabled" : ""}>
            <i class="fa-solid fa-hourglass-half"></i>
        </button>
        <button class="proceed-btn mt-3 bg-white py-3 px-3 text-blue-900 rounded-full border border-blue-50 shadow-lg opacity-50 cursor-not-allowed"
            data-id="${data.queue_id}"
            data-url="${data.proceed_url || `http://localhost/OJT/queueing-system/index.php/controller_queueing/proceed_to_releasing/${data.queue_id}`}"
            disabled>
            <i class="fa-solid fa-user-check text-2xl"></i>
        </button>
      `;
    }

    function addQueueItem(data, defaultStatus) {
      const queueContainer = document.getElementById("queue-container");
      const queueList = document.getElementById("queueList");

      if (!queueContainer || !queueList) {
        console.error("Error: Queue containers not found.");
        return;
      }

      const queueId = `queue-item-${data.queue_id}`;
      if (document.getElementById(queueId)) {
        console.warn(`Queue item ${queueId} already exists.`);
        return;
      }

      const isProcessing = data.processing_by ? true : false;
      const statusText = isProcessing ? `Processing by ${data.processing_by}` : defaultStatus;
      const statusColor = isProcessing ? "text-red-500" : "text-green-500";
      const processingDisabled = isProcessing ? "opacity-50 cursor-not-allowed" : "";

      const listItem = document.createElement("li");
      listItem.id = queueId;
      listItem.className = "flex-1 min-w-56 queue-item p-3 bg-white border rounded-lg flex flex-col justify-center m-1 items-center shadow-md";
      listItem.dataset.queue_id = data.queue_id;
      listItem.innerHTML = createQueueItem(data, statusText, statusColor, processingDisabled);

      if (queueContainer.children.length < 20) {
        queueContainer.appendChild(listItem);
    } else {
        listItem.className = "p-3 bg-gray-50 border rounded-lg";
        listItem.innerHTML = `
          ${data.queue_number} - ${data.name} (${data.reason})<br>
          <span class="text-gray-500 text-xs">Added on: ${formatTimestamp(data.created_at)}</span>
        `;

        // Store data attributes explicitly for later retrieval
        listItem.setAttribute("data-queue_id", data.queue_id);
        listItem.setAttribute("data-queue_number", data.queue_number);
        listItem.setAttribute("data-name", data.name);
        listItem.setAttribute("data-reason", data.reason);
        listItem.setAttribute("data-created_at", data.created_at);
        listItem.setAttribute("data-proceed_url", data.proceed_url || `http://localhost/OJT/queueing-system/index.php/controller_queueing/proceed_to_releasing/${data.queue_id}`);

        queueList.appendChild(listItem);
    }

      updateGridLayout();
    }

    function removeQueueItem(queueId) {
      let queueItem = document.getElementById(`queue-item-${queueId}`);

      if (queueItem) {
        queueItem.remove();
        console.log(`Queue ID ${queueId} removed from UI.`);
        moveFirstRightItemToLeft();
        updateGridLayout();
      } else {
        console.warn(`Queue item ${queueId} not found.`);
      }
    }

    function moveFirstRightItemToLeft() {
      const queueContainer = document.getElementById("queue-container");
      const queueList = document.getElementById("queueList");

      if (queueContainer.children.length < 20 && queueList.children.length > 0) {
        const firstRightItem = queueList.children[0];

        if (!firstRightItem) {
          console.error("❌ No item found in the queue list!");
          return;
        }

        console.log("First Right Item:", firstRightItem);
        console.log("Dataset Attributes:", firstRightItem.dataset);

        // Extract dataset properties, ensuring they exist
        const queueData = {
          queue_id: firstRightItem.getAttribute("data-queue_id") || null,
          queue_number: firstRightItem.getAttribute("data-queue_number") || "N/A",
          name: firstRightItem.getAttribute("data-name") || "Unknown",
          reason: firstRightItem.getAttribute("data-reason") || "No reason provided",
          created_at: firstRightItem.getAttribute("data-created_at") || new Date().toISOString(),
          proceed_url: firstRightItem.getAttribute("data-proceed_url") || `http://localhost/OJT/queueing-system/index.php/controller_queueing/proceed_to_releasing/${firstRightItem.getAttribute("data-queue_id")}`
        };

        if (!queueData.queue_id) {
          console.error("❌ Queue ID is undefined. Cannot move item.");
          return;
        }

        const movedTimestamp = new Date().toLocaleString("en-US", {
          month: "short",
          day: "2-digit",
          year: "numeric",
          hour: "2-digit",
          minute: "2-digit",
          hour12: true
        });

        queueData.created_at = movedTimestamp;

        const newQueueItem = document.createElement("div");
        newQueueItem.id = firstRightItem.id;
        newQueueItem.className = "flex-1 min-w-56 queue-item p-3 bg-white border rounded-lg flex flex-col justify-center m-1 items-center shadow-md";

        Object.keys(queueData).forEach(key => {
          newQueueItem.dataset[key] = queueData[key];
        });

        newQueueItem.innerHTML = createQueueItem(queueData, "Fire Protection", "text-green-500", "");

        firstRightItem.remove();
        queueContainer.appendChild(newQueueItem);

        console.log(`✅ Moved queue item (ID: ${queueData.queue_id}) to the left with timestamp: ${movedTimestamp}`);
      }
    }

    function updateGridLayout() {
      const queueContainer = document.getElementById("queue-container");
      queueContainer.style.gridTemplateColumns = `repeat(${Math.min(queueContainer.children.length, 5)}, 1fr)`;
    }
  </script>

  <script>
    document.querySelector('form').addEventListener('submit', function (e) {
      e.preventDefault(); // Prevent form submission

      let formData = new FormData(this); // Gather form data

      fetch("<?= base_url('controller_queueing/add_to_fireprotection'); ?>", {
        method: 'POST',
        body: formData
      })
        .then(response => response.json())
        .then(data => {
          if (data.status === 'success') {
            // Show success toast
            Toastify({
              text: data.message,
              duration: 3000,
              close: true,
              gravity: "top",
              position: "right",
              backgroundColor: "linear-gradient(to right, #00b09b,rgba(15, 156, 185, 0.53))"
            }).showToast();
          }
        })
        .catch(error => {
          console.error('Error:', error);
          Toastify({
            text: 'An error occurred. Please try again.',
            duration: 3000,
            close: true,
            gravity: "top",
            position: "right",
            backgroundColor: "linear-gradient(to right, #FF5F6D, #FFC371)"
          }).showToast();
        });
    });
  </script>

  <script>
    document.addEventListener('click', function (e) {
      if (e.target.closest('.proceed-btn')) {
        e.preventDefault();

        let button = e.target.closest('.proceed-btn');
        let url = button.getAttribute("data-url");
        let queueId = button.getAttribute("data-id");

        if (!url) {
          console.error("Error: Proceed URL is missing.");
          return;
        }

        button.disabled = true;
        button.classList.add("opacity-50", "cursor-not-allowed");

        fetch(url, { method: 'GET' })
          .then(response => response.json())
          .then(data => {
            if (data.status === 'success') {
              Toastify({
                text: data.message,
                duration: 3000,
                close: true,
                gravity: "top",
                position: "right",
                backgroundColor: "linear-gradient(to right, #00b09b, #1165b7)"
              }).showToast();

              console.log(`✅ Proceeded queue item ${queueId}, removing immediately.`);

              removeQueueItem(queueId);
            }
          })
          .catch(error => {
            console.error('Error:', error);
            Toastify({
              text: 'An error occurred. Please try again.',
              duration: 3000,
              close: true,
              gravity: "top",
              position: "right",
              backgroundColor: "linear-gradient(to right, #FF5F6D, #FFC371)"
            }).showToast();

            // Re-enable button on error
            button.disabled = false;
            button.classList.remove("opacity-50", "cursor-not-allowed");
          });
      }
    });
  </script>

  <script>
    document.addEventListener("DOMContentLoaded", function () {
      const socket = new WebSocket("ws://localhost:8080");

      socket.onopen = function () {
        console.log("✅ WebSocket connection established (Fire Protection)");
      };

      socket.onmessage = function (event) {
        try {
          const data = JSON.parse(event.data);
          console.log("📩 Received WebSocket Message:", data);

          if (data.action === "update_fireprotection_queue") {
            console.log(`Queue ${data.queue_id} marked as processing by ${data.processing_by}`);
            updateFireProtectionQueueStatus(
              data.queue_id,
              `Processing by ${data.processing_by}`,
              "text-red-500",
              data.processing_by
            );
          }
        } catch (error) {
          console.error("❌ WebSocket JSON Error:", error);
        }
      };

      socket.onerror = function (error) {
        console.error("❌ WebSocket Error:", error);
      };

      socket.onclose = function () {
        console.log("⚠️ WebSocket connection closed (Fire Protection)");
      };

      document.body.addEventListener("click", function (e) {
        const button = e.target.closest(".processing-btn");

        if (button) {
          e.preventDefault();
          const queueId = button.getAttribute("data-id");

          if (!queueId) {
            console.error("❌ Queue ID is undefined.");
            Swal.fire({ title: "Error!", text: "Invalid queue ID.", icon: "error", position: "top" });
            return;
          }

          fetch("<?= base_url('controller_queueing/mark_as_processing_fireprotection'); ?>", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: new URLSearchParams({ queue_id: queueId }),
          })
            .then(response => response.json())
            .then(data => {
              if (data.status === "success") {
                console.log("✅ Fire Protection queue marked as processing:", data);

                updateFireProtectionQueueStatus(queueId, `Processing by ${data.processing_by}`, "text-red-500", data.processing_by);

                socket.send(JSON.stringify({
                  action: "update_fireprotection_queue",
                  queue_id: queueId,
                  status: "processing",
                  processing_by: data.processing_by
                }));

                Swal.close();
              } else {
                throw new Error(data.message);
              }
            })
            .catch(error => {
              console.error("❌ Fetch Error:", error);
              Swal.fire({ title: "Error!", text: error.message, icon: "error", position: "top" });
            });
        }
      });

      function updateFireProtectionQueueStatus(queueId, statusText, textColor, processingBy) {
        let queueRow = document.querySelector(`#queue-item-${queueId}`);
        if (!queueRow) {
          console.warn(`⚠️ Fire Protection queue row with ID ${queueId} not found!`);
          return;
        }

        let statusTextElement = queueRow.querySelector(".status-text");
        let proceedBtn = queueRow.querySelector(".proceed-btn");
        let processingBtn = queueRow.querySelector(".processing-btn");

        if (statusTextElement) {
          statusTextElement.innerText = statusText;
          statusTextElement.classList.remove("text-green-500", "text-yellow-500", "text-blue-500", "text-red-500");
          statusTextElement.classList.add(textColor);
        }

        if (processingBtn) {
          processingBtn.classList.add("opacity-50", "cursor-not-allowed");
          processingBtn.setAttribute("disabled", "disabled");
        }

        if (processingBy && String(processingBy) === String(currentUser)) {
          console.log(`✅ User ${currentUser} is processing Queue ${queueId}, enabling proceed button.`);
          if (proceedBtn) {
            proceedBtn.classList.remove("opacity-50", "cursor-not-allowed");
            proceedBtn.removeAttribute("disabled");
          }
        } else {
          console.log(`❌ Queue ${queueId} is being processed by another user (${processingBy}). Disabling proceed button.`);
          if (proceedBtn) {
            proceedBtn.classList.add("opacity-50", "cursor-not-allowed");
            proceedBtn.setAttribute("disabled", "disabled");
          }
        }
      }

      let currentUser = "<?= $currentUser; ?>";
      console.log("📌 Current User (JavaScript):", currentUser);
    });
  </script>
</body>

</html>