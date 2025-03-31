<?php
$left_items = array_slice($fireprotection, 0, 20);
$right_items = array_slice($fireprotection, 20);
$grid_cols = count($left_items) < 5 ? count($left_items) : 5;

$currentUser = isset($_SESSION['username']) ? $_SESSION['username'] : '';
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
    <div class="px-4">
      <div class="flex justify-between items-center py-4">
        <div class="flex items-center space-x-3">
          <span class="text-xl font-bold uppercase tracking-widest">Fire Protection Queue</span>
        </div>

        <!-- User Dropdown -->
        <div class="relative flex">
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

  <div class="flex h-screen p-5 pt-20">
    <!-- Left Section (Now Serving - Fire Protection) -->
    <div class="flex-1 bg-white p-5 h-[calc(100vh-100px)] rounded-lg shadow-md overflow-y-auto">
      <h2 class="text-2xl font-bold text-cyan-500 uppercase tracking-wider pb-5 text-center">
        Now Serving - Fire Protection
      </h2>
      <div id="queue-container" class="flex flex-wrap gap-1 max-h-[calc(100vh-150px)] bg-cyan-100 overflow-y-auto">
        <?php if ($fireprotection): ?>
          <?php foreach ($left_items as $item): ?>
            <?php
            $isProcessingByCurrentUser = ($item->processing_by && strval($item->processing_by) === $currentUser);
            ?>
            <div class="flex flex-col flex-1 min-w-96 queue-item p-3 bg-white border rounded-lg m-1 shadow-md"
              id="queue-item-<?= $item->id; ?>" data-id="<?= $item->id; ?>">

              <div class="flex flex-col flex-grow space-y-4 max-w-full">
                <div class="flex justify-between">
                  <p class="text-gray-800 text-[calc(.8vw)] font-semibold uppercase"><?= $item->reason; ?></p>
                  <p class="text-gray-500 text-[calc(.8vw)] justify-end">
                    <?= date('M d Y, h:i A', strtotime($item->created_at)); ?>
                  </p>
                </div>
                <h3 class="flex flex-col justify-center items-center flex-grow">
                  <span class="font-semibold text-[calc(6vw)] leading-none"><?= $item->queue_number; ?></span>
                  <span class="text-gray-600 uppercase text-[calc(.7vw)] "><?= $item->name; ?></span>
                </h3>
              </div>

              <p class="text-gray-500 text-[calc(.7vw)] text-right pb-10">
                <span
                  class="status-text <?= $item->processing_by ? 'bg-cyan-100 text-red-500' : 'bg-cyan-100 text-cyan-500'; ?> rounded-full px-3 py-1 font-semibold">
                  <?= $item->processing_by ? "Processing by {$item->processing_by}" : "Waiting"; ?>
                </span>
              </p>

              <div class="flex justify-between items-center mt-auto pt-4 border-t">
                <div class="flex space-x-2">
                  <button
                    class="proceed-btn bg-white py-3 px-3 text-cyan-900 hover:bg-gray-50 rounded-full border border-cyan-50 shadow-lg <?= $isProcessingByCurrentUser ? '' : 'opacity-50 cursor-not-allowed' ?>"
                    data-id="<?= $item->id; ?>"
                    data-url="<?= base_url("controller_queueing/proceed_to_releasing/{$item->id}") ?>"
                    <?= $isProcessingByCurrentUser ? '' : 'disabled'; ?>>
                    <i class="fa-solid fa-circle-check text-[calc(1.2vw)]"></i>
                  </button>
                  <button
                    class="processing-btn bg-white py-3 px-3 text-cyan-900 hover:bg-gray-50 rounded-full border border-cyan-50 shadow-lg <?= $item->processing_by ? 'opacity-50 cursor-not-allowed' : '' ?>"
                    data-id="<?= $item->id; ?>" <?= $item->processing_by ? 'disabled' : ''; ?>>
                    <i class="fa-solid fa-clock text-[calc(1.2vw)]"></i>
                  </button>
                </div>
                <div>
                  <button
                    class="delete-btn bg-white py-3 px-3 text-cyan-900 hover:bg-gray-50 rounded-full border border-cyan-50 shadow-lg"
                    data-id="<?= $item->id; ?>" data-url="<?= base_url("controller_queueing/delete_queue/{$item->id}") ?>">
                    <i class="fa-solid fa-trash-can text-[calc(1.2vw)]"></i>
                  </button>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>

    <!-- Right Section (Fire Protection Queue List) -->
    <div class="ml-5 bg-white p-5 w-[400px] rounded-lg shadow-md flex flex-col">
      <h2 class="text-2xl font-bold text-cyan-900 uppercase tracking-widest text-center">Fire Protection Queue List</h2>
      <ul id="queueList" class="mt-3 overflow-auto h-[1000px] space-y-3 p-2 bg-white rounded-lg shadow-md border">
        <?php foreach ($right_items as $item): ?>
          <li class="p-5 bg-cyan-50 border border-cyan-400 rounded-xl shadow-md hover:shadow-lg 
                    transition-all duration-300 hover:bg-cyan-50 flex flex-col space-y-4"
            data-queue_id="<?= $item->id; ?>" data-queue_number="<?= $item->queue_number; ?>"
            data-name="<?= $item->name; ?>" data-reason="<?= $item->reason; ?>"
            data-created_at="<?= $item->created_at; ?>"
            data-proceed_url="<?= base_url("controller_queueing/proceed_to_releasing/{$item->id}") ?>"
            data-processing_by="<?= $item->processing_by ? $item->processing_by : '' ?>">

            <div class="flex items-center gap-4">
              <div class="w-12 h-12 flex items-center justify-center text-white font-bold text-xl 
                                bg-cyan-600 rounded-full shadow-md">
                <?= $item->queue_number; ?>
              </div>
              <div class="flex flex-col">
                <div class="text-lg font-semibold text-cyan-900">
                  <?= $item->name; ?>
                </div>
                <span class="text-xs text-gray-500">
                  <?= date('M d, Y, h:i A', strtotime($item->created_at)); ?>
                </span>
              </div>
            </div>

            <div class="mt-3 flex justify-end">
              <span class="text-sm px-4 py-2 rounded-full bg-cyan-100 text-cyan-800 font-medium shadow-sm">
                <?= $item->reason; ?>
              </span>
            </div>
          </li>
        <?php endforeach; ?>
      </ul>

      <!-- Add to Fire Protection Queue Form -->
      <form action="<?= base_url('controller_queueing/add_to_fireprotection'); ?>" method="post" class="mt-5">
        <input type="text" name="name" placeholder="Enter Name" required class="border p-2 w-full rounded" />
        <select name="reason" required class="border p-2 w-full rounded mt-2 text-cyan-800 font-semibold tracking-wide">
          <option class="text-cyan-800 font-semibold tracking-wide" value="Fire Safety Inspection">Fire Safety
            Inspection</option>
          <option class="text-cyan-800 font-semibold tracking-wide" value="Permit Renewal">Permit Renewal</option>
        </select>
        <button type="submit"
          class="mt-2 w-full bg-cyan-500 uppercase tracking-wider font-semibold text-white py-2 rounded">
          Add to Fire Protection Queue
        </button>
      </form>
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

    function createQueueItem(data, currentUser) {
      // Format created_at timestamp
      const formattedCreatedAt = new Date(data.created_at).toLocaleString('en-US', {
        month: 'short', day: '2-digit', year: 'numeric',
        hour: '2-digit', minute: '2-digit', hour12: true
      });

      // Set proceed and delete URLs
      const proceedUrl = `http://localhost/OJT/queueing-system/index.php/controller_queueing/proceed_to_releasing/${data.queue_id}`;
      const deleteUrl = `http://localhost/OJT/queueing-system/index.php/controller_queueing/delete_queue/${data.queue_id}`;

      // Determine processing state
      const isProcessingByCurrentUser = data.processing_by && String(data.processing_by) === String(currentUser);
      const isProcessing = !!data.processing_by;
      const processingText = isProcessing ? `Processing by ${data.processing_by}` : "Fire Protection";
      const statusColorClass = isProcessing ? "bg-cyan-100 text-red-500" : "bg-cyan-100 text-cyan-500";
      const processingClass = isProcessing ? "opacity-50 cursor-not-allowed" : "";

      return `
   
    <div class="flex flex-col flex-grow space-y-4">

      <!-- Header: Reason & Timestamp -->
      <div class="flex justify-between">
        <p class="text-gray-800 text-sm font-semibold uppercase">${data.reason}</p>
        <p class="text-gray-500 text-xs">${formattedCreatedAt}</p>
      </div>


        <h3 class="flex flex-col justify-center items-center">
          <span class="mt-20 font-semibold text-8xl">${data.queue_number}</span>
          <span class="text-gray-600">${data.name}</span>
        </h3>
      </div>
     </div>

              
    <div class="pt-2">
      <p class="text-gray-500 text-sm text-right pb-10">
        <span
          class="status-text ${statusColorClass ? 'bg-cyan-100 text-cyan-500 rounded-full px-3 py-1' : 'bg-cyan-100 text-cyan-500 rounded-full px-3 py-1'} font-semibold">
          ${processingText ? ` ${processingText}` : "Fire Protection"}
        </span>
      </p>
    </div>

      <!-- Action Buttons -->
      <div class="flex justify-between items-center mt-auto pt-4 border-t">
        <div class="flex space-x-2">
          <!-- Proceed Button -->
          <button class="proceed-btn bg-white p-3 text-cyan-900 hover:bg-gray-50 rounded-full border border-cyan-50 shadow-md ${isProcessingByCurrentUser ? '' : 'opacity-50 cursor-not-allowed'}"
                  data-id="${data.queue_id}" data-url="${proceedUrl}"
                  ${isProcessingByCurrentUser ? '' : 'disabled'}>
            <i class="fa-solid fa-circle-check text-2xl"></i>
          </button>

          <!-- Processing Button -->
          <button class="processing-btn bg-white p-3 text-cyan-900 hover:bg-gray-50 rounded-full border border-cyan-50 shadow-md ${processingClass}"
                  data-id="${data.queue_id}" ${isProcessing ? 'disabled' : ''}>
            <i class="fa-solid fa-clock text-2xl"></i>
          </button>
        </div>

        <!-- Delete Button -->
        <button class="delete-btn bg-white p-3 text-cyan-900 hover:bg-gray-50 rounded-full border border-cyan-50 shadow-md"
                data-id="${data.queue_id}" data-url="${deleteUrl}">
          <i class="fa-solid fa-trash-can text-2xl"></i>
        </button>
      </div>

    </div>
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
      listItem.className = "flex flex-col flex-1 min-w-96 queue-item p-3 bg-white border rounded-lg m-1 shadow-md";
      listItem.dataset.queue_id = data.queue_id;
      listItem.innerHTML = createQueueItem(data, statusText, statusColor, processingDisabled);

      if (queueContainer.children.length < 20) {
        queueContainer.appendChild(listItem);
      } else {
        listItem.className = "p-5 bg-white border border-cyan-400 rounded-xl shadow-md hover:shadow-lg transition-all duration-300 hover:bg-cyan-50 flex flex-col space-y-4";
        listItem.innerHTML = `
    <div class="flex items-center gap-4">
      <!-- Queue Number -->
      <div class="w-12 h-12 flex items-center justify-center text-white font-bold text-xl bg-cyan-600 rounded-full shadow-md">
        ${data.queue_number}
      </div>

      <!-- User Info -->
      <div class="flex flex-col">
        <div class="text-lg font-semibold text-cyan-900">
          ${data.name}
        </div>
        <span class="text-xs text-gray-500">
          ${new Date(data.created_at).toLocaleString()}
        </span>
      </div>
    </div>

    <!-- Queue Reason Badge -->
    <div class="mt-3 flex justify-end">
      <span class="text-sm px-4 py-2 rounded-lg bg-cyan-100 text-cyan-800 font-medium shadow-sm">
        ${data.reason}
      </span>
    </div>
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

        const movedTimestamp = new Date().toLocaleString();

        queueData.created_at = movedTimestamp;

        const newQueueItem = document.createElement("div");
        newQueueItem.id = firstRightItem.id;
        newQueueItem.className = "flex flex-col flex-1 min-w-96 queue-item p-3 bg-white border rounded-lg m-1 shadow-md";

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