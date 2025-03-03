<?php
// Determine how many items go to the left section (maximum 20)
$left_items = array_slice($queue, 0, 20);
// Remaining queue items go to the right section
$right_items = array_slice($queue, 20);
// Determine grid columns: if less than 5 items, use that many columns; otherwise, use 5 columns.
$grid_cols = count($left_items) < 5 ? count($left_items) : 5;
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Land Tax Queue</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
  <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body class="bg-gray-100">
  <div class="flex min-h-screen p-5">
    <!-- Left Section (Now Serving) -->
    <div class="flex-1 bg-white p-5 rounded-lg shadow-md flex flex-col">
      <h2 class="text-2xl font-bold text-blue-900 text-center">Now Serving</h2>
      <div id="queue-container" class="mt-3 grid gap-3 bg-blue-50 h-full"
        style="grid-template-columns: repeat(<?= $grid_cols ?>, 1fr); grid-auto-rows: 1fr;">
        <?php if ($queue): ?>
          <?php foreach ($left_items as $item): ?>
            <div class="queue-item p-3 bg-white border rounded-lg flex flex-col justify-center my-3 mx-2 items-center"
              id="queue-item-<?= $item->id; ?>">
              <h3 class="font-semibold text-xl"><?= $item->queue_number; ?> - <?= $item->name; ?></h3>
              <p class="text-gray-500 text-sm">Reason: <?= $item->reason; ?></p>

              <p class="text-gray-500 text-sm">
                Status:
                <?php if ($item->processing_by): ?>
                  <span class="text-red-500 font-semibold">Processing by <?= $item->processing_by; ?></span>
                <?php else: ?>
                  <span class="text-green-500 font-semibold">Waiting</span>
                <?php endif; ?>
              </p>

              <!-- Mark as Processing Button -->
              <button
                class="processing-btn mt-3 bg-yellow-500 py-2 px-3 text-white hover:bg-yellow-600 rounded-full shadow-lg <?= $item->processing_by ? 'opacity-50 cursor-not-allowed' : '' ?>"
                data-id="<?= $item->id; ?>" <?= $item->processing_by ? 'disabled' : ''; ?>>
                <i class="fa-solid fa-hourglass-half"></i> Processing
              </button>

              <!-- Proceed Button -->
              <button
                class="proceed-btn mt-3 bg-white py-3 px-3 text-blue-900 hover:bg-gray-50 rounded-full border border-blue-50 shadow-lg"
                data-id="<?= $item->id; ?>"
                data-url="http://localhost/OJT/queueing-system/index.php/controller_queueing/proceed_to_backroom/<?= $item->id; ?>">
                <i class="fa-solid fa-user-check text-2xl"></i>
              </button>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
        <?php endif; ?>
      </div>

    </div>

    <!-- Right Section (Queue List) -->
    <div class="ml-5 bg-white p-5 w-[400px] rounded-lg shadow-md flex flex-col">
      <h2 class="text-2xl font-bold text-blue-900 text-center">Queue List</h2>
      <ul id="queueList" class="mt-3 overflow-auto h-[600px] space-y-2">
        <?php foreach ($right_items as $item): ?>
          <li class="p-3 bg-gray-50 border rounded-lg">
            <?= $item->queue_number; ?> - <?= $item->name; ?> (<?= $item->reason; ?>)
          </li>
        <?php endforeach; ?>
      </ul>

      <!-- Add to Queue Form -->
      <form action="<?= base_url('controller_queueing/add_to_queue'); ?>" method="post" class="mt-5">
        <input type="text" name="name" placeholder="Enter Name" required class="border p-2 w-full rounded" />
        <select name="reason" required class="border p-2 w-full rounded mt-2">
          <option value="Social Security System">Social Security System</option>
          <option value="Business Permit">Business Permit</option>
          <option value="Driver’s License Renewal">Driver’s License Renewal</option>
          <option value="Building Permit">Building Permit</option>
          <option value="Real Estate Tax">Real Estate Tax</option>
          <option value="Community Tax Certificate">Community Tax Certificate</option>
          <option value="Barangay Clearance">Barangay Clearance</option>
          <option value="Passport Processing">Passport Processing</option>
          <option value="Police Clearance">Police Clearance</option>
          <option value="Birth Certificate Request">Birth Certificate Request</option>
          <option value="Marriage License">Marriage License</option>
        </select>
        <button type="submit" class="mt-2 w-full bg-green-500 text-white py-2 rounded">Add to Queue</button>
      </form>

      <!-- Logout Button -->
      <div class="mt-10">
        <a href="<?= base_url('controller_admin_landing/logout'); ?>"
          class="flex items-center justify-center bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition">
          <i class="fas fa-sign-out-alt mr-2"></i> Logout
        </a>
      </div>
    </div>
  </div>
  <template id="queue-item-template">
  <div class="queue-item p-3 bg-white border rounded-lg flex flex-col justify-center my-3 mx-2 items-center">
    <h3 class="font-semibold text-xl queue-number-name"></h3>
    <p class="text-gray-500 text-sm queue-reason"></p>
    <p class="text-gray-500 text-sm">
      Status: <span class="status-label text-green-500 font-semibold">Waiting</span>
    </p>
    <button class="processing-btn mt-3 bg-yellow-500 py-2 px-3 text-white hover:bg-yellow-600 rounded-full shadow-lg" data-id="">
      <i class="fa-solid fa-hourglass-half"></i> Processing
    </button>
    <button class="proceed-btn mt-3 bg-white py-3 px-3 text-blue-900 hover:bg-gray-50 rounded-full border border-blue-50 shadow-lg" data-id="" data-url="">
      <i class="fa-solid fa-user-check text-2xl"></i>
    </button>
  </div>
</template>

  <script>
    document.addEventListener("DOMContentLoaded", function () {
      const socket = new WebSocket("ws://localhost:8080");

      socket.onopen = function () {
        console.log("Connected to WebSocket server (Land Tax)");
      };
      socket.onmessage = function (event) {
        console.log("📥 Raw WebSocket Message:", event.data); // Log before parsing

        try {
          const data = JSON.parse(event.data);
          console.log("📥 Parsed WebSocket Data:", data);

          if (!data.queue_id) {
            console.warn("Invalid queue data received:", data);
            return;
          }

          // Use left_items[0] if available, otherwise use data directly
          const queueData = data.left_items && data.left_items.length > 0 ? data.left_items[0] : data;

          console.log("🚀 Final Render Data:", queueData);
          addQueueItem(queueData);
        } catch (error) {
          console.error("WebSocket JSON Error:", error);
        }
      };


      function addQueueItem(data) {
  const queueContainer = document.getElementById("queue-container"); // Left section
  const queueList = document.getElementById("queueList"); // Right section
  const allQueueItems = document.querySelectorAll(".queue-item");

  if (!queueContainer || !queueList) {
    console.error("Error: Queue containers not found.");
    return;
  }

  const queueId = `queue-item-${data.queue_id}`;
  if (document.getElementById(queueId)) {
    console.warn(`Queue item ${queueId} already exists.`);
    return;
  }

  // Get the template
  const template = document.getElementById("queue-item-template");
  if (!template) {
    console.error("Queue item template not found.");
    return;
  }
  
  // Clone the template content
  const clone = template.content.cloneNode(true);
  const container = clone.querySelector(".queue-item");
  container.id = queueId;
  container.dataset.id = data.queue_id;

  // Populate the cloned template with data
  const nameHeader = clone.querySelector(".queue-number-name");
  nameHeader.textContent = `${data.queue_number} - ${data.name}`;

  const reasonText = clone.querySelector(".queue-reason");
  reasonText.textContent = `Reason: ${data.reason}`;

  // Set data attributes on the buttons
  const processingBtn = clone.querySelector(".processing-btn");
  processingBtn.setAttribute("data-id", data.queue_id);
  
  const proceedBtn = clone.querySelector(".proceed-btn");
  proceedBtn.setAttribute("data-id", data.queue_id);
  if (data.proceed_url) {
    proceedBtn.setAttribute("data-url", data.proceed_url);
  }

  // Append the cloned node to the correct container based on the count
  if (allQueueItems.length < 20) {
    queueContainer.appendChild(clone);
  } else {
    queueList.appendChild(clone);
  }

  updateGridLayout();
}

      function removeQueueItem(queueId) {
        let queueItem = document.getElementById(`queue-item-${queueId}`);

        if (queueItem) {
          queueItem.remove();
          updateGridLayout();
        } else {
          console.warn(`Queue item ${queueId} not found.`);
        }
      }

      function updateGridLayout() {
        const queueContainer = document.getElementById("queue-container");
        const queueList = document.getElementById("queueList");

        const leftItems = queueContainer.children.length;
        const rightItems = queueList.children.length;

        queueContainer.style.gridTemplateColumns = `repeat(${leftItems < 5 ? leftItems : 5}, 1fr)`;
        queueList.style.gridTemplateColumns = `repeat(${rightItems < 5 ? rightItems : 5}, 1fr)`;

        if (leftItems === 0) {
          queueContainer.innerHTML = `<p id="empty-queue-message-left" class="text-gray-500 mt-3 text-center">No one in queue</p>`;
        }
      }

      socket.onclose = function () {
        console.log("Disconnected from WebSocket server");
      };
    });
  </script>

  <script>
    document.querySelector('form').addEventListener('submit', function (e) {
      e.preventDefault(); // Prevent form submission

      let formData = new FormData(this); // Gather form data

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
              backgroundColor: "linear-gradient(to right, #00b09b,rgb(25, 187, 205))"
            }).showToast();
          } else {
            // Show failure toast
            Toastify({
              text: data.message,
              duration: 3000,
              close: true,
              gravity: "top",
              position: "right",
              backgroundColor: "linear-gradient(to right, #FF5F6D, #FFC371)"
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
  let currentBtn = e.target.closest(".proceed-btn"); // Ensure button is targeted
  if (!currentBtn) return;

  e.preventDefault();

  let queueItem = currentBtn.closest('.queue-item'); // Get the entire queue item
  let url = currentBtn.getAttribute("data-url");

  if (!url) {
    console.error("🚨 Error: No URL found for proceed action.");
    return;
  }

  fetch(url, { method: 'GET' })
    .then(response => response.json())
    .then(data => {
      if (data.status === 'success') {
        // ✅ Show success toast
        Toastify({
          text: data.message,
          duration: 3000,
          close: true,
          gravity: "top",
          position: "right",
          backgroundColor: "linear-gradient(to right, #00b09b, rgb(17, 69, 183))"
        }).showToast();

        // ✅ Remove the ENTIRE queue item from UI
        if (queueItem) {
          queueItem.remove();
        } else {
          console.warn("🚨 Warning: Queue item not found.");
        }
      } else {
        throw new Error(data.message || "Unknown error");
      }
    })
    .catch(error => {
      console.error('🚨 Fetch Error:', error);

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
    document.addEventListener("DOMContentLoaded", function () {
      const socket = new WebSocket("ws://localhost:8080");
      let hasRefreshed = false;

      socket.onopen = function () {
        console.log("WebSocket connection established");
      };

      socket.onmessage = function (event) {
        console.log("📥 Received WebSocket Message:", event.data);

        try {
          const data = JSON.parse(event.data);
          const queueList = document.getElementById("queueList");

          if (data.action === "update_queue") {
            console.log(`Queue ${data.queue_id} marked as processing`);

            let queueRow = document.querySelector(`[data-queue-id="${data.queue_id}"]`);
            if (queueRow) {
              queueRow.classList.add("bg-yellow-500");
              queueRow.querySelector(".status-text").innerText = "Processing";

              let button = queueRow.querySelector(".processing-btn");
              if (button) {
                button.classList.add("opacity-50", "cursor-not-allowed");
                button.setAttribute("disabled", "disabled");
              }
            } else {
              console.warn(`Queue row with ID ${data.queue_id} not found!`);
            }

            if (queueList && queueList.children.length === 0 && !hasRefreshed) {
              hasRefreshed = true;
              console.warn("Queue is empty. Refreshing page...");

              Swal.fire({
                title: "Updating...",
                text: "Please wait while the page refreshes.",
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                backdrop: false,
                position: "top",
                customClass: { popup: "swal-top-popup" },
                didOpen: () => Swal.showLoading(),
              });

              setTimeout(() => location.reload(), 1000);
            }
          }
        } catch (error) {
          console.error("WebSocket JSON Error:", error);
        }
      };

      socket.onerror = function (error) {
        console.error("WebSocket Error:", error);
      };

      socket.onclose = function () {
        console.log("🔌 WebSocket connection closed");
      };

      document.body.addEventListener("click", function (e) {
        if (e.target.closest(".processing-btn")) {
          e.preventDefault();
          const button = e.target.closest(".processing-btn");
          const queueId = button.getAttribute("data-id");

          if (!queueId) {
            console.error("Queue ID is undefined.");
            Swal.fire({ title: "Error!", text: "Invalid queue ID.", icon: "error", position: "top" });
            return;
          }

          Swal.fire({
            title: "Processing...",
            text: "Updating queue...",
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            backdrop: false,
            position: "top",
            customClass: { popup: "swal-top-popup" },
            didOpen: () => Swal.showLoading(),
          });

          fetch("<?php echo base_url('controller_queueing/mark_as_processing'); ?>", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: new URLSearchParams({ queue_id: queueId }),
          })
            .then(response => response.json())
            .then(data => {
              if (data.status === "success") {
                console.log("Queue marked as processing:", data);

                socket.send(JSON.stringify({
                  action: "update_queue",
                  queue_id: queueId,
                  status: "processing"
                }));

                setTimeout(() => location.reload(), 1000);
              } else {
                throw new Error(data.message);
              }
            })
            .catch(error => {
              console.error("Fetch Error:", error);
              Swal.fire({ title: "Error!", text: error.message, icon: "error", position: "top" });
            });
        }
      });
    });
  </script>

  <style>
    .mt-3.grid.right {
      position: absolute;
      right: 10px;
    }

    .swal-top-popup {
      margin-top: 10px !important;
      width: 300px !important;
      box-shadow: none !important;
      background: rgba(255, 255, 255, 0.9) !important;
    }
  </style>

</body>

</html>
