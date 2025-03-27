<?php
// Determine how many items go to the left section (maximum 20)
$left_items = array_slice($queue, 0, 20);
// Remaining queue items go to the right section
$right_items = array_slice($queue, 20);
// Determine grid columns: if less than 5 items, use that many columns; otherwise, use 5 columns.
$grid_cols = count($left_items) < 5 ? count($left_items) : 5;
$currentUser = isset($_SESSION['username']) ? $_SESSION['username'] : '';

// $currentUser = isset($_SESSION['user_id']) ? strval($_SESSION['user_id']) : ''; // Ensure it's a string
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Land Tax Queue</title>
  <link rel="stylesheet"
    href="<?php echo base_url('assets/css/user_view_landtax.css?v=' . filemtime('assets/css/user_view_landtax.css')); ?>">
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
  <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <style>

  </style>
</head>

<body class="bg-gray-100">
  <!-- ✅ Navbar -->
  <!-- ✅ Navbar -->
  <nav class="bg-white text-cyan-700 fixed top-0 left-0 w-full shadow-lg z-10">
    <div class="px-4">
      <div class="flex justify-between items-center py-4">
        <div class="flex items-center space-x-3">
          <span class="text-xl font-bold uppercase tracking-widest">Land Tax Queue</span>
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



  <div class="flex min-h-screen p-5 pt-20">
    <!-- Left Section (Now Serving) -->
    <div class="flex-1 bg-white p-5 rounded-lg shadow-md ">
      <h2 class="text-2xl font-bold text-cyan-500 uppercase tracking-wider pb-5 text-center">Now Serving</h2>
      <div id="queue-container" class="flex flex-wrap gap-1 bg-cyan-50 overflow-y-auto">
        <?php if ($queue): ?>
          <?php foreach ($left_items as $item): ?>
            <?php
            $isProcessingByCurrentUser = ($item->processing_by && strval($item->processing_by) === $currentUser);
            ?>
            <div class="flex flex-col flex-1 min-w-96 queue-item p-3 bg-white border rounded-lg m-1"
              id="queue-item-<?= $item->id; ?>" data-id="<?= $item->id; ?>">
              <!-- Main Content Container (for reason, queue number, and name) -->
              <div class="flex flex-col flex-grow space-y-4">
                <div class="flex justify-between">
                  <p class="text-gray-800 text-sm w-56   font-semibold uppercase"><?= $item->reason; ?></p>
                  <p class="text-gray-500 text-xs"><?= date('M d Y, h:i A', strtotime($item->created_at)); ?></p>
                </div>
                <h3 class="flex flex-col justify-center  items-center">
                  <span class="mt-20 font-semibold text-8xl "><?= $item->queue_number; ?></span>
                  <span class="text-gray-600"><?= $item->name; ?></span>
                </h3>
              </div>

              <!-- Status Container: placed just above the buttons -->
              <div class="pt-2">
                <p class="text-gray-500 text-sm text-right pb-10">
                  <span
                    class="status-text <?= $item->processing_by ? 'bg-cyan-100 text-cyan-500 rounded-full px-3 py-1' : 'bg-cyan-100 text-cyan-500 rounded-full px-3 py-1'; ?> font-semibold">
                    <?= $item->processing_by ? "processing by {$item->processing_by}" : "Waiting"; ?>
                  </span>
                </p>
              </div>

              <!-- Button Container (pushed to the bottom via mt-auto) -->
              <div class="flex justify-between items-center mt-auto pt-4 border-t">
                <div class="flex space-x-2">
                  <button
                    class="proceed-btn bg-white py-3 px-3 text-cyan-900 hover:bg-gray-50 rounded-full border border-cyan-50 shadow-lg <?= $isProcessingByCurrentUser ? '' : 'opacity-50 cursor-not-allowed' ?>"
                    data-id="<?= $item->id; ?>"
                    data-url="<?= base_url("controller_queueing/proceed_to_backroom/{$item->id}") ?>"
                    <?= $isProcessingByCurrentUser ? '' : 'disabled'; ?>>
                    <!-- Replaced with a modern check circle icon -->
                    <i class="fa-solid fa-circle-check text-2xl"></i>
                  </button>
                  <button
                    class="processing-btn bg-white py-3 px-3 text-cyan-900 hover:bg-gray-50 rounded-full border border-cyan-50 shadow-lg <?= $item->processing_by ? 'opacity-50 cursor-not-allowed' : '' ?>"
                    data-id="<?= $item->id; ?>" <?= $item->processing_by ? 'disabled' : ''; ?>>
                    <!-- Replaced with a modern clock icon -->
                    <i class="fa-solid fa-clock text-2xl"></i>
                  </button>
                </div>
                <div>
                  <button
                    class="delete-btn bg-white py-3 px-3 text-cyan-900 hover:bg-gray-50 rounded-full border border-cyan-50 shadow-lg"
                    data-id="<?= $item->id; ?>" data-url="<?= base_url("controller_queueing/delete_queue/{$item->id}") ?>">
                    <!-- Replaced with a modern trash can icon -->
                    <i class="fa-solid fa-trash-can text-2xl"></i>
                  </button>
                </div>

              </div>
            </div>


          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>

    <!-- Right Section (Queue List) -->
    <div class="ml-5 bg-white p-5 w-[400px] rounded-lg shadow-md flex flex-col">
      <h2 class="text-2xl font-bold text-cyan-900 uppercase tracking-widest text-center">Queue List</h2>
      <ul id="queueList" class="mt-3 overflow-auto h-[1000px] space-y-3 p-2 bg-white rounded-lg shadow-md border">
        <?php foreach ($right_items as $item): ?>
          <li class="p-5 bg-white border border-cyan-400 rounded-xl shadow-md hover:shadow-lg 
           transition-all duration-300 hover:bg-cyan-50 flex flex-col space-y-4" data-queue_id="<?= $item->id; ?>"
            data-queue_number="<?= $item->queue_number; ?>" data-name="<?= $item->name; ?>"
            data-reason="<?= $item->reason; ?>" data-created_at="<?= $item->created_at; ?>"
            data-proceed_url="<?= base_url("controller_queueing/proceed_to_backroom/{$item->id}") ?>"
            data-processing_by="<?= $item->processing_by ? $item->processing_by : '' ?>">

            <!-- Queue Header -->
            <div class="flex items-center gap-4">
              <!-- Queue Number -->
              <div class="w-12 h-12 flex items-center justify-center text-white font-bold text-xl 
                    bg-cyan-600 rounded-full shadow-md">
                <?= $item->queue_number; ?>
              </div>

              <!-- User Info -->
              <div class="flex flex-col">
                <div class="text-lg font-semibold text-cyan-900">
                  <?= $item->name; ?>
                </div>
                <span class="text-xs text-gray-500">
                  <?= date('M d, Y, h:i A', strtotime($item->created_at)); ?>
                </span>
              </div>
            </div>

            <!-- Queue Reason Badge -->
            <div class="mt-3 flex justify-end">
              <span class="text-sm px-4 py-2 rounded-full bg-cyan-100 text-cyan-800 font-medium shadow-sm">
                <?= $item->reason; ?>
              </span>
            </div>

          </li>


        <?php endforeach; ?>
      </ul>


      <!-- Add to Queue Form -->
      <form action="<?= base_url('controller_queueing/add_to_queue'); ?>" method="post" class="mt-5">
        <input type="text" name="name" placeholder="Enter Name" required class="border p-2 w-full rounded" />
        <select name="reason" required class="border p-2 w-full rounded mt-2 text-cyan-800 font-semibold tracking-wide">
          <option class="text-cyan-800 font-semibold tracking-wide" value="Social Security System">Social
            Security System
          </option>
          <option class="text-cyan-800 font-semibold tracking-wide" value="Business Permit">Business Permit
          </option>
          <option class="text-cyan-800 font-semibold tracking-wide" value="Driver’s License Renewal">
            Driver’s License Renewal</option>
          <option class="text-cyan-800 font-semibold tracking-wide" value="Building Permit">Building Permit
          </option>
          <option class="text-cyan-800 font-semibold tracking-wide" value="Real Estate Tax">Real Estate Tax
          </option>
          <option class="text-cyan-800 font-semibold tracking-wide" value="Community Tax Certificate">
            Community Tax Certificate</option>
          <option class="text-cyan-800 font-semibold tracking-wide" value="Barangay Clearance">Barangay
            Clearance</option>
          <option class="text-cyan-800 font-semibold tracking-wide" value="Passport Processing">Passport
            Processing</option>
          <option class="text-cyan-800 font-semibold tracking-wide" value="Police Clearance">Police
            Clearance</option>
          <option class="text-cyan-800 font-semibold tracking-wide" value="Birth Certificate Request">Birth
            Certificate Request</option>
          <option class="text-cyan-800 font-semibold tracking-wide" value="Marriage License">Marriage
            License</option>
        </select>
        <button type="submit"
          class="mt-2 w-full bg-cyan-500 uppercase tracking-wider font-semibold text-white py-2 rounded">Add to
          Queue</button>
      </form>


    </div>
  </div>



  <script>
    document.addEventListener("DOMContentLoaded", function () {
      const socket = new WebSocket("ws://localhost:8080");

      socket.onopen = function () {
        console.log("Connected to WebSocket server (Land Tax)");
      };

      socket.onmessage = function (event) {
        console.log("Received WebSocket Message:", event.data);

        try {
          const data = JSON.parse(event.data);
          console.log("Parsed WebSocket Data:", data);

          if (!data.queue_id || !data.action) {
            console.warn("Invalid queue data received:", data);
            return;
          }

          if (data.action === "add_to_queue") {
            console.log(`New Queue Item Added: ${data.queue_id}`);
            addQueueItem(data);
          } else if (data.action === "proceed_to_backroom") {
            console.log(`Queue ID ${data.queue_id} proceeding to Backroom...`);

            removeQueueItem(data.queue_id);
          }
        } catch (error) {
          console.error("WebSocket JSON Error:", error);
        }
      };

      function addQueueItem(data) {
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

        // Format the created_at timestamp.
        let formattedCreatedAt = "";
        if (data.created_at) {
          let date = new Date(data.created_at);
          formattedCreatedAt = date.toLocaleString("en-US", {
            month: "short",
            day: "2-digit",
            year: "numeric",
            hour: "2-digit",
            minute: "2-digit",
            hour12: true
          }).replace(",", "");
        }

        // Set the proceed and delete URLs.
        const proceedUrl = data.proceed_url ? data.proceed_url :
          `http://localhost/OJT/queueing-system/index.php/controller_queueing/proceed_to_backroom/${data.queue_id}`;
        const deleteUrl = data.delete_url ? data.delete_url :
          `http://localhost/OJT/queueing-system/index.php/controller_queueing/delete_queue/${data.queue_id}`;

        const isProcessing = data.processing_by ? true : false;
        const processingText = isProcessing ? `processing by ${data.processing_by}` : "Waiting";
        // Using cyan for waiting and cyan for processing in this design. (You can adjust as needed.)
        const statusColor = isProcessing ? "text-cyan-500 bg-cyan-100" : "text-cyan-500 bg-cyan-100";

        // Build the card matching your HTML design.
        const listItem = document.createElement("div");
        listItem.id = queueId;
        listItem.dataset.queue_id = data.queue_id;
        listItem.dataset.queue_number = data.queue_number;
        listItem.dataset.name = data.name;
        listItem.dataset.reason = data.reason;
        listItem.dataset.created_at = data.created_at;
        listItem.dataset.proceed_url = proceedUrl;
        listItem.dataset.delete_url = deleteUrl;
        listItem.className = "flex flex-col flex-1 min-w-96 queue-item p-3 bg-white border rounded-lg m-1";

        listItem.innerHTML = `
    <!-- Main Content Container -->
    <div class="flex flex-col flex-grow space-y-4">
      <div class="flex justify-between">
        <p class="text-gray-800 text-sm font-semibold uppercase">${data.reason}</p>
        <p class="text-gray-500 text-xs">${formattedCreatedAt}</p>
      </div>
      <h3 class="flex flex-col justify-center items-center">
        <span class="mt-20 font-semibold text-8xl">${data.queue_number}</span>
        <span class="text-gray-600">${data.name}</span>
      </h3>
    </div>

    <!-- Status Container (placed just above the buttons) -->
    <div class="pt-2">
      <p class="text-gray-500 text-sm text-right pb-10">
        <span class="status-text ${statusColor} rounded-full px-3 py-1 font-semibold">
          ${processingText}
        </span>
      </p>
    </div>

    <!-- Button Container (pushed to the bottom) -->
    <div class="flex justify-between items-center mt-auto pt-4 border-t">
      <div class="flex space-x-2">
        <button class="proceed-btn bg-white py-3 px-3 text-cyan-900 hover:bg-gray-50 rounded-full border border-cyan-50 shadow-lg" 
                data-id="${data.queue_id}" data-url="${proceedUrl}" disabled>
          <i class="fa-solid fa-circle-check text-2xl"></i>
        </button>
        <button class="processing-btn bg-white py-3 px-3 text-cyan-900 hover:bg-gray-50 rounded-full border border-cyan-50 shadow-lg ${isProcessing ? 'opacity-50 cursor-not-allowed' : ''}" 
                data-id="${data.queue_id}" ${isProcessing ? 'disabled' : ''}>
          <i class="fa-solid fa-clock text-2xl"></i>
        </button>
      </div>
      <div>
        <button class="delete-btn bg-white py-3 px-3 text-cyan-900 hover:bg-gray-50 rounded-full border border-cyan-50 shadow-lg" 
                data-id="${data.queue_id}" data-url="${deleteUrl}">
          <i class="fa-solid fa-trash-can text-2xl"></i>
        </button>
      </div>
    </div>
  `;

        // Append the item: if the left container has less than 20 children, add here; otherwise, add to the right list.
        if (queueContainer.children.length < 20) {
          queueContainer.appendChild(listItem);
        } else {
          listItem.className = "p-5 bg-white border border-cyan-400 rounded-xl shadow-md hover:shadow-lg transition-all duration-300 hover:bg-cyan-50 flex flex-col space-y-4";
          listItem.innerHTML = `
          <div class="flex items-center gap-4">
     <div class="w-12 h-12 flex items-center justify-center text-white font-bold text-xl bg-cyan-600 rounded-full shadow-md">${data.queue_number}</div>
    <div class="flex flex-col"> <div class="text-lg font-semibold text-cyan-900">${data.name} </div>

      <span class="text-gray-500 text-xs"> ${formattedCreatedAt}</span>
    </div> 
    </div>
    <div class="mt-3 flex justify-end">
         <span class="text-sm px-4 py-2 rounded-full bg-cyan-100 text-cyan-800 font-medium shadow-sm">
           ${data.reason}
         </span>
       </div>
    `;
          queueList.appendChild(listItem);
        }

        updateGridLayout();
      }



      function removeQueueItem(queueId) {
        let queueItem = document.getElementById(`queue-item-${queueId}`);

        if (queueItem) {
          queueItem.remove();
          console.log(`Queue ID ${queueId} removed from UI.`);

          // Ensure queue-container remains filled with 20 items
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
          const queueId = firstRightItem.dataset.queue_id || null;
          if (!queueId) {
            console.error("❌ Queue ID is undefined. Cannot move item.");
            return;
          }
          let rawCreatedAt = firstRightItem.dataset.created_at || null;
          let movedTimestamp = "Unknown Time";
          if (rawCreatedAt) {
            let date = new Date(rawCreatedAt);
            movedTimestamp = date.toLocaleString("en-US", {
              month: "short",
              day: "2-digit",
              year: "numeric",
              hour: "2-digit",
              minute: "2-digit",
              hour12: true
            });
          }
          const processingBy = firstRightItem.dataset.processing_by || "";
          const isProcessing = processingBy ? true : false;
          const processingText = isProcessing ? `processing by ${processingBy}` : "Waiting";
          const statusColor = isProcessing ? "text-cyan-500 bg-cyan-100" : "text-cyan-500 bg-cyan-100";
          const deleteUrl = firstRightItem.dataset.delete_url ||
            `http://localhost/OJT/queueing-system/index.php/controller_queueing/delete_queue/${queueId}`;
          const proceedUrl = firstRightItem.dataset.proceed_url;

          const newQueueItem = document.createElement("div");
          newQueueItem.id = firstRightItem.id;
          newQueueItem.className = "flex flex-col flex-1 min-w-96 queue-item p-3 bg-white border rounded-lg m-1";
          newQueueItem.dataset.queue_id = queueId;
          newQueueItem.dataset.created_at = rawCreatedAt;
          newQueueItem.innerHTML = `
      <!-- Main Content Container -->
      <div class="flex flex-col flex-grow space-y-4">
        <div class="flex justify-between">
          <p class="text-gray-800 text-sm font-semibold uppercase">${firstRightItem.dataset.reason}</p>
          <p class="text-gray-500 text-xs">${movedTimestamp}</p>
        </div>
        <h3 class="flex flex-col justify-center items-center">
          <span class="mt-20 font-semibold text-8xl">${firstRightItem.dataset.queue_number}</span>
          <span class="text-gray-600">${firstRightItem.dataset.name}</span>
        </h3>
      </div>
      <!-- Status Container -->
      <div class="pt-2">
        <p class="text-gray-500 text-sm text-right pb-10">
          <span class="status-text ${statusColor} rounded-full px-3 py-1 font-semibold">
            ${processingText}
          </span>
        </p>
      </div>
      <!-- Button Container -->
      <div class="flex justify-between items-center mt-auto pt-4 border-t">
        <div class="flex space-x-2">
          <button class="proceed-btn bg-white py-3 px-3 text-cyan-900 hover:bg-gray-50 rounded-full border border-cyan-50 shadow-lg" 
                  data-id="${queueId}" data-url="${proceedUrl}" disabled>
            <i class="fa-solid fa-circle-check text-2xl"></i>
          </button>
          <button class="processing-btn bg-white py-3 px-3 text-cyan-900 hover:bg-gray-50 rounded-full border border-cyan-50 shadow-lg ${isProcessing ? 'opacity-50 cursor-not-allowed' : ''}" 
                  data-id="${queueId}" ${isProcessing ? 'disabled' : ''}>
            <i class="fa-solid fa-clock text-2xl"></i>
          </button>
        </div>
        <div>
          <button class="delete-btn bg-white py-3 px-3 text-cyan-900 hover:bg-gray-50 rounded-full border border-cyan-50 shadow-lg" 
                  data-id="${queueId}" data-url="${deleteUrl}">
            <i class="fa-solid fa-trash-can text-2xl"></i>
          </button>
        </div>
      </div>
    `;
          firstRightItem.remove();
          queueContainer.appendChild(newQueueItem);
          console.log(`✅ Moved queue item (ID: ${queueId}) to the left with timestamp: ${movedTimestamp}`);
        }
        updateGridLayout();
      }

      function updateGridLayout() {
        const queueContainer = document.getElementById("queue-container");
        const queueList = document.getElementById("queueList");
        const leftItems = queueContainer.children.length;
        const rightItems = queueList.children.length;
        queueContainer.style.gridTemplateColumns = `repeat(${leftItems < 5 ? leftItems : 5}, 1fr)`;
        queueList.style.gridTemplateColumns = `repeat(${rightItems < 5 ? rightItems : 5}, 1fr)`;
      }
    });
  </script>

  <script>
    document.querySelector('form').addEventListener('submit', function (e) {
      e.preventDefault();

      let formData = new FormData(this);

      fetch("<?= base_url('controller_queueing/add_to_queue'); ?>", {
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
              backgroundColor: "linear-gradient(to right,rgb(0, 0, 0),rgb(25, 187, 205))"
            }).showToast();
          } else {
            // Show failure toast
            Toastify({
              text: data.message,
              duration: 3000,
              close: true,
              gravity: "top",
              position: "right",
              backgroundColor: "linear-gradient(to right, rgb(0, 0, 0), rgb(208, 8, 175))"
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
            backgroundColor: "linear-gradient(to right, rgb(0, 0, 0), rgb(208, 8, 175))"
          }).showToast();
        });
    });

  </script>
  <script>
    document.addEventListener("click", function (e) {
      let deleteBtn = e.target.closest(".delete-btn");
      if (!deleteBtn) return;

      e.preventDefault();

      let queueItem = deleteBtn.closest(".queue-item"); // Find the parent queue item
      let queueId = deleteBtn.dataset.id;
      let url = deleteBtn.dataset.url;

      if (!url) {
        console.error("Error: No URL found for delete action.");
        return;
      }

      Swal.fire({
        title: "Are you sure?",
        text: "This will permanently delete this record.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "Cancel"
      }).then((result) => {
        if (result.isConfirmed) {
          fetch(url, { method: "POST" }) // Ensure the request is handled correctly in CodeIgniter
            .then(response => response.json())
            .then(data => {
              if (data.status === "success") {
                Toastify({
                  text: data.message,
                  duration: 3000,
                  close: true,
                  gravity: "top",
                  position: "right",
                  style: { background: "linear-gradient(to right, #000, #d008af)" }
                }).showToast();

                if (queueItem) {
                  queueItem.remove();
                  updateGridLayout(); // Ensure the layout updates properly
                  handleQueueShift(); // Move the next queue item if needed
                }
              } else {
                Swal.fire("Error", data.message, "error");
              }
            })
            .catch(error => {
              console.error("Delete Error:", error);
              Swal.fire("Error", "An error occurred. Please try again.", "error");
            });
        }
      });
    });

    /**
     * Moves the first queue item from the right list to the left if there's space.
     */
    function handleQueueShift() {
      let leftContainer = document.getElementById("queue-container");
      let rightList = document.getElementById("queueList");

      if (leftContainer.children.length < 20) {
        let firstRightItem = rightList.querySelector("li");
        if (firstRightItem) {
          firstRightItem.remove(); // Remove from the right section
          leftContainer.appendChild(firstRightItem); // Move to the left section
          updateGridLayout();
        }
      }
    }


  </script>
  <script>
    document.addEventListener('click', function (e) {
      const proceedBtn = e.target.closest(".proceed-btn");
      if (!proceedBtn) return;
      e.preventDefault();
      const url = proceedBtn.dataset.url;
      if (!url) {
        console.error("Error: No URL found for proceed action.");
        return;
      }
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
              style: { background: "linear-gradient(to right, #00b09b, rgb(17, 69, 183))" }
            }).showToast();
            const queueItem = document.querySelector(`[data-queue_id="${data.queue_id}"]`);
            if (queueItem) {
              queueItem.remove();
              moveFirstRightItemToLeft();
              updateGridLayout();
            }
          } else {
            throw new Error(data.message || "Unknown error");
          }
        })
        .catch(error => {
          console.error('Proceed Fetch Error:', error);
          Toastify({
            text: 'An error occurred. Please try again.',
            duration: 3000,
            close: true,
            gravity: "top",
            position: "right",
            style: { background: "linear-gradient(to right, #FF5F6D, #FFC371)" }
          }).showToast();
        });
    });


    // Function to update the queue grid dynamically
    function updateGridLayout() {
      const queueContainer = document.getElementById("queue-container");
      const queueList = document.getElementById("queueList");

      const leftItems = queueContainer.children.length;
      const rightItems = queueList.children.length;

      queueContainer.style.gridTemplateColumns = `repeat(${leftItems < 5 ? leftItems : 5}, 1fr)`;
      queueList.style.gridTemplateColumns = `repeat(${rightItems < 5 ? rightItems : 5}, 1fr)`;
    }
  </script>
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      const socket = new WebSocket("ws://localhost:8080");

      socket.onopen = function () {
        console.log("✅ WebSocket connection established");
      };

      socket.onmessage = function (event) {
        console.log("📩 Received WebSocket Message:", event.data);

        try {
          const data = JSON.parse(event.data);

          if (data.action === "update_queue") {
            console.log(`Queue ${data.queue_id} marked as processing by ${data.processing_by}`);

            // ✅ Ensure `updateQueueStatus` updates all elements correctly
            updateQueueStatus(
              data.queue_id,
              `Processing by ${data.processing_by}`,
              "text-red-500",
              data.processing_by,
              data.created_at
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
        console.log("⚠️ WebSocket connection closed");
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

          fetch("<?= base_url('controller_queueing/mark_as_processing'); ?>", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: new URLSearchParams({ queue_id: queueId }),
          })
            .then(response => response.json())
            .then(data => {
              if (data.status === "success") {
                console.log("✅ Queue marked as processing:", data);

                updateQueueStatus(queueId, `Processing by ${data.processing_by}`, "text-cyan-500", data.processing_by);

                socket.send(JSON.stringify({
                  action: "update_queue",
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

      function updateQueueStatus(queueId, statusText, textColor, processingBy, createdAt) {
        let queueRow = document.querySelector(`#queue-item-${queueId}`);

        // ✅ Try to locate the queue item even after refresh
        if (!queueRow) {
          queueRow = document.querySelector(`[data-queue_id="${queueId}"]`);
        }

        if (!queueRow) {
          console.warn(`⚠️ Queue row with ID ${queueId} not found!`);
          return;
        }

        let statusTextElement = queueRow.querySelector(".status-text");
        let proceedBtn = queueRow.querySelector(".proceed-btn");
        let processingBtn = queueRow.querySelector(".processing-btn");
        let createdAtElement = queueRow.querySelector(".queue-created-at");

        if (statusTextElement) {
          statusTextElement.innerText = statusText;
          statusTextElement.classList.remove("text-green-500", "text-yellow-500", "text-red-500");
          statusTextElement.classList.add(textColor);
        }

        if (processingBtn) {
          processingBtn.classList.add("opacity-50", "cursor-not-allowed");
          processingBtn.setAttribute("disabled", "disabled");
        }

        if (createdAtElement) {
          let formattedTime = createdAt ? new Date(createdAt).toLocaleString("en-US", {
            month: "short",
            day: "2-digit",
            year: "numeric",
            hour: "2-digit",
            minute: "2-digit",
            hour12: true
          }) : "Unknown Time";

          createdAtElement.innerText = `Time Added: ${formattedTime}`;
        }

        console.log(`👤 Current User: ${currentUser}, Processing By: ${processingBy}`);

        if (proceedBtn) {
          if (String(processingBy) === String(currentUser)) {
            console.log(`✅ User ${currentUser} is processing Queue ${queueId}, enabling proceed button.`);
            proceedBtn.classList.remove("opacity-50", "cursor-not-allowed");
            proceedBtn.removeAttribute("disabled");
          } else {
            console.log(`❌ Queue ${queueId} is being processed by another user (${processingBy}). Disabling proceed button.`);
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