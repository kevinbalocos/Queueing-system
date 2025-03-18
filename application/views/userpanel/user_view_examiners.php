<?php
// Limit the left section to 30 items (max 6 rows × 5 columns)
$left_items = array_slice($examiners, 0, 20);
$right_items = array_slice($examiners, 20);
$grid_cols = count($left_items) < 5 ? count($left_items) : 5;

$currentUser = isset($_SESSION['user_id']) ? strval($_SESSION['user_id']) : '';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Font Awesome for icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Examiners Queue</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
  <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

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
          <span class="text-xl font-bold uppercase tracking-widest">Examiners Queue</span>
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
    <!-- Left Section (Now Serving - Examiners) -->
    <div class="flex-1 bg-white p-5 rounded-lg shadow-md">
      <h2 class="text-2xl font-bold text-cyan-500 uppercase tracking-wider pb-5 text-center">Now Serving - Examiners
      </h2>
      <div id="queue-container" class="flex flex-wrap gap-1 bg-cyan-50 overflow-y-auto">
        <?php if ($examiners): ?>
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
                  <?= $item->processing_by ? "Processing by {$item->processing_by}" : "Examiners"; ?>
                </span>
              </p>

              <!-- Mark as Processing Button -->
              <button
                class="processing-btn mt-3 bg-blue-500 py-2 px-3 text-white hover:bg-blue-600 rounded-full shadow-lg <?= $item->processing_by ? 'opacity-50 cursor-not-allowed' : '' ?>"
                data-id="<?= $item->id; ?>" data-type="examiners" <?= $item->processing_by ? 'disabled' : ''; ?>>
                <i class="fa-solid fa-hourglass-half"></i>
              </button>

              <!-- Proceed Button (Disabled by Default) -->
              <button
                class="proceed-btn mt-3 bg-white py-3 px-3 text-blue-900 hover:bg-gray-50 rounded-full border border-blue-50 shadow-lg <?= $isProcessingByCurrentUser ? '' : 'opacity-50 cursor-not-allowed' ?>"
                data-id="<?= $item->id; ?>"
                data-url="<?= base_url("controller_queueing/proceed_to_businesstax/{$item->id}") ?>"
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
      <h2 class="text-2xl font-bold text-blue-900 text-center">Examiners Queue List</h2>
      <ul class="mt-3 overflow-auto h-[600px] space-y-2" id="queueList">
        <?php foreach ($right_items as $item): ?>
          <li class="p-3 bg-gray-50 border rounded-lg" data-queue_id="<?= $item->id; ?>"
            data-queue_number="<?= $item->queue_number; ?>" data-name="<?= $item->name; ?>"
            data-reason="<?= $item->reason; ?>"
            data-proceed_url="<?= base_url('controller_queueing/proceed_to_businesstax/' . $item->id); ?>">
            <?= $item->queue_number; ?> - <?= $item->name; ?> (<?= $item->reason; ?>) <br>
            <span class="text-gray-500 text-xs">Added on:
              <?= date("M d, Y h:i A", strtotime($item->created_at)); ?></span>
          </li>
        <?php endforeach; ?>
      </ul>

      <!-- Add to Examiners Queue Form -->
      <form action="<?= base_url('controller_queueing/add_to_examiners'); ?>" method="post" class="mt-5">
        <input type="text" name="name" placeholder="Enter Name" required class="border p-2 w-full rounded" />
        <select name="reason" required class="border p-2 w-full rounded mt-2">
          <option value="Exam Scheduling">Exam Scheduling</option>
          <option value="Exam Registration">Exam Registration</option>
        </select>
        <button type="submit"
          class="mt-2 w-full bg-green-500 uppercase tracking-wider font-semibold text-white py-2 rounded">
          Add to Examiners Queue
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

    socket.onopen = function () {
      console.log("Connected to WebSocket server (Examiners)");
    };

    socket.onmessage = function (event) {
      try {
        const data = JSON.parse(event.data);

        if (data.action === "proceed_to_examiners") {
          console.log("Queue item moved to Examiners:", data);
          addNewExaminersQueue(data); // Add the item to the Examiners queue
        } else if (data.action === "proceed_to_businesstax") {
          console.log("Queue item moved to Business Tax:", data);
          removeExaminersQueueItem(data.queue_id); // Remove from Examiners queue
        }
      } catch (error) {
        console.error("Error parsing WebSocket data:", error);
      }
    };

    socket.onerror = function (error) {
      console.error("WebSocket Error: ", error);
    };

    socket.onclose = function () {
      console.log("Disconnected from WebSocket server");
    };

    function addNewExaminersQueue(data) {
      const queueContainer = document.querySelector("#queue-container");
      if (!queueContainer) {
        console.error("❌ Queue container not found.");
        return;
      }

      const queueId = `queue-item-${data.queue_id}`;
      if (document.getElementById(queueId)) {
        console.warn(`⚠️ Queue item ${queueId} already exists.`);
        return;
      }

      let timestamp = "Invalid Date";
      if (data.created_at) {
        let createdAt = new Date(data.created_at);
        if (!isNaN(createdAt.getTime())) {
          timestamp = createdAt.toLocaleString("en-US", {
            month: "short",
            day: "2-digit",
            year: "numeric",
            hour: "2-digit",
            minute: "2-digit",
            hour12: true
          });
        } else {
          console.error("⚠️ Invalid Date format received:", data.created_at);
        }
      } else {
        console.error("⚠️ Missing created_at value.");
      }

      const isProcessingByCurrentUser = data.processing_by === data.current_user;
      const statusClass = data.processing_by ? "text-red-500" : "text-green-500";
      const statusText = data.processing_by
        ? `Processing by ${data.processing_by}`
        : "Examiners";

      const listItem = document.createElement("div");
      listItem.id = queueId;
      listItem.className =
        "flex-1 min-w-56 queue-item p-3 bg-white border rounded-lg flex flex-col justify-center m-1 items-center shadow-md";
      listItem.dataset.queue_id = data.queue_id;

      listItem.innerHTML = `
    <h3 class="font-semibold text-xl">${data.queue_number} - ${data.name}</h3>
    <p class="text-gray-500 text-sm">Reason: ${data.reason}</p>
    <p class="text-gray-500 text-sm">Time Added: <span class="font-semibold">${timestamp}</span></p>
    <p class="text-gray-500 text-sm">
      Status: <span class="status-text ${statusClass} font-semibold">${statusText}</span>
    </p>

    <!-- Mark as Processing Button -->
    <button
      class="processing-btn mt-3 bg-blue-500 py-2 px-3 text-white hover:bg-blue-600 rounded-full shadow-lg ${data.processing_by ? "opacity-50 cursor-not-allowed" : ""
        }"
      data-id="${data.queue_id}" data-type="examiners" ${data.processing_by ? "disabled" : ""
        }>
      <i class="fa-solid fa-hourglass-half"></i>
    </button>

    <!-- Proceed Button (Disabled by Default) -->
    <button
      class="proceed-btn mt-3 bg-white py-3 px-3 text-blue-900 hover:bg-gray-50 rounded-full border border-blue-50 shadow-lg ${isProcessingByCurrentUser ? "" : "opacity-50 cursor-not-allowed"
        }"
      data-id="${data.queue_id}"
      data-url="${data.proceed_url}"
      ${isProcessingByCurrentUser ? "" : "disabled"}>
      <i class="fa-solid fa-user-check text-2xl"></i>
    </button>
  `;

      queueContainer.appendChild(listItem);
    }

    function removeExaminersQueueItem(queueId) {
      let queueItem = document.getElementById(`queue-item-${queueId}`);
      if (queueItem) {
        queueItem.remove();
        console.log(`Queue ID ${queueId} removed from Examiners.`);
      } else {
        console.warn(`Queue item ${queueId} not found.`);
      }
    }

  </script>

  <script>
    document.querySelector('form').addEventListener('submit', function (e) {
      e.preventDefault(); // Prevent form submission

      let formData = new FormData(this); // Gather form data

      fetch("<?= base_url('controller_queueing/add_to_examiners'); ?>", {
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
              backgroundColor: "linear-gradient(to right, #00b09b,rgb(25, 187, 205))"
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
    document.querySelectorAll('.proceed-btn').forEach(button => {
      button.addEventListener('click', function (e) {
        e.preventDefault(); // Prevent the default form action

        let url = this.href; // Get the URL from the button link
        let currentBtn = this; // Store the button reference

        fetch(url, {
          method: 'GET',
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
                backgroundColor: "linear-gradient(to right, #00b09b,rgb(17, 69, 183))"
              }).showToast();

              // Optionally, update the UI (like removing the item from the left section)
              currentBtn.closest('div').remove(); // Remove the item after it's processed
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
    });
  </script>
</body>

</html>