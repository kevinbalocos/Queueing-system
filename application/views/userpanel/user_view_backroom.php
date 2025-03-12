<?php
// Limit the left section to a maximum of 20 items (6 rows × 5 columns)
$left_items = array_slice($backroom, 0, 20);
// Overflow items go to the right section
$right_items = array_slice($backroom, 20);
// Determine grid columns: if less than 5 items, use that number; otherwise, use 5 columns.
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
  <title>Backroom Queue</title>
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
  <div class="flex min-h-screen p-5">
    <!-- Left Section (Now Serving in Backroom) -->
    <div class="flex-1 bg-white p-5 rounded-lg shadow-md">
      <h2 class="text-2xl font-bold text-blue-900 text-center">Now Serving - Backroom</h2>
      <div id="queue-container" class="flex flex-wrap gap-1 bg-blue-50 overflow-y-auto">
        <?php if ($backroom): ?>
          <?php foreach ($left_items as $item): ?>
            <div
              class="flex-1 min-w-56 queue-item p-3 bg-white border rounded-lg flex flex-col justify-center m-1 items-center shadow-md"
              id="queue-item-<?= $item->id; ?>" data-id="<?= $item->id; ?>">

              <h3 class="font-semibold text-xl"><?= $item->queue_number; ?> - <?= $item->name; ?></h3>
              <p class="text-gray-500 text-sm">Reason: <?= $item->reason; ?></p>

              <p class="text-gray-500 text-sm">
                Status: <span class="text-green-500 font-semibold">Backroom</span>
              </p>

              <!-- Mark as Processing Button -->
              <button class="processing-btn mt-3 bg-blue-500 py-2 px-3 text-white hover:bg-blue-600 rounded-full shadow-lg"
                data-id="<?= $item->id; ?>">
                <i class="fa-solid fa-hourglass-half"></i>
              </button>

              <!-- Proceed Button (Disabled by Default) -->
              <button
                class="proceed-btn mt-3 bg-white py-3 px-3 text-blue-900 rounded-full border border-blue-50 shadow-lg opacity-50 cursor-not-allowed"
                data-id="<?= $item->id; ?>" disabled>
                <i class="fa-solid fa-user-check text-2xl"></i>
              </button>

            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>

    <!-- Right Section (Overflow Queue List + Add to Backroom Queue Form) -->
    <div class="ml-5 bg-white p-5 w-[400px] rounded-lg shadow-md flex flex-col">
      <h2 class="text-2xl font-bold text-blue-900 text-center">Backroom Queue List</h2>
      <ul class="mt-3 overflow-auto h-[600px] space-y-2" id="queueList">
        <?php foreach ($right_items as $item): ?>
          <li class="p-3 bg-gray-50 border rounded-lg" data-queue_id="<?= $item->id; ?>"
            data-queue_number="<?= $item->queue_number; ?>" data-name="<?= $item->name; ?>"
            data-reason="<?= $item->reason; ?>"
            data-proceed_url="<?= base_url('controller_queueing/proceed_to_examiners/' . $item->id); ?>">
            <?= $item->queue_number; ?> - <?= $item->name; ?> (<?= $item->reason; ?>)
          </li>
        <?php endforeach; ?>
      </ul>

      <!-- Add to Backroom Queue Form -->
      <form action="<?= base_url('controller_queueing/add_to_backroom'); ?>" method="post" class="mt-5">
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
        <button type="submit"
          class="mt-2 w-full bg-blue-500 uppercase tracking-wider font-semibold text-white py-2 rounded">Add to Backroom
          Queue</button>
      </form>

      <div class="mt-10">
        <a href="<?= base_url('controller_admin_landing/logout'); ?>"
          class="flex items-center justify-center bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition">
          <i class="fas fa-sign-out-alt mr-2"></i> Logout
        </a>
      </div>
    </div>
  </div>
</body>

<script>
  const socket = new WebSocket("ws://localhost:8080");

  socket.onopen = function () {
    console.log("Connected to WebSocket server (Backroom)");
  };

  socket.onmessage = function (event) {
    try {
      const data = JSON.parse(event.data);

      if (data.action === "proceed_to_backroom") {
        console.log("New backroom queue item received:", data);
        addNewBackroomQueue(data);
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

  function addNewBackroomQueue(data) {
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

    // Create a new queue item
    const listItem = document.createElement("li"); // Change to <li> for consistency with PHP
    listItem.id = queueId;
    listItem.dataset.queue_id = data.queue_id;
    listItem.dataset.queue_number = data.queue_number;
    listItem.dataset.name = data.name;
    listItem.dataset.reason = data.reason;
    listItem.dataset.proceed_url = data.proceed_url
      ? data.proceed_url
      : `http://localhost/OJT/queueing-system/index.php/controller_queueing/proceed_to_backroom/${data.queue_id}`;

    if (queueContainer.children.length < 20) {
      // Styled full card design for the first 20 items
      listItem.className =
        "flex-1 min-w-56 queue-item p-3 bg-white border rounded-lg flex flex-col justify-center m-1 items-center shadow-md";
      listItem.innerHTML = `
            <h3 class="font-semibold text-xl">${data.queue_number} - ${data.name}</h3>
            <p class="text-gray-500 text-sm">Reason: ${data.reason}</p>
            <p class="text-gray-500 text-sm">
                Status: <span class="text-green-500 font-semibold"> Backroom</span>
            </p>
            <button class="processing-btn mt-3 bg-blue-500 py-2 px-3 text-white hover:bg-blue-600 rounded-full shadow-lg">
                <i class="fa-solid fa-hourglass-half"></i>
            </button>
            <button class="proceed-btn mt-3 bg-white py-3 px-3 text-blue-900 rounded-full border border-blue-50 shadow-lg opacity-50 cursor-not-allowed"
                data-id="${data.queue_id}"
                data-url="${listItem.dataset.proceed_url}" disabled>
                <i class="fa-solid fa-user-check text-2xl"></i>
            </button>
        `;
      queueContainer.appendChild(listItem);
    } else {
      // **Match PHP list structure (for overflow items)**
      listItem.className =
        "p-3 bg-gray-50 border rounded-lg flex justify-between items-center shadow-sm";
      listItem.innerHTML = `
            <span>${data.queue_number} - ${data.name} (${data.reason})</span>
       
        `;
      queueList.appendChild(listItem);
    }

    updateGridLayout();
  }

  function createQueueItem(data) {
    const item = document.createElement("div");
    item.className =
      "flex-1 min-w-56 queue-item p-3 bg-white border rounded-lg flex flex-col justify-center m-1 items-center";
    item.innerHTML = `
        <h3 class="font-semibold text-xl">${data.queue_number} - ${data.name}</h3>
        <p class="text-gray-500 text-sm">Reason: ${data.reason}</p>
        <p class="text-gray-500 text-sm">
            Status: <span class="text-green-500 font-semibold">Backroom</span>
        </p>
        <button class="processing-btn mt-3 bg-blue-500 py-2 px-3 text-white hover:bg-blue-600 rounded-full shadow-lg" data-id="${data.id}">
            <i class="fa-solid fa-hourglass-half"></i>
        </button>
        <a href="${data.proceed_url}" class="proceed-btn mt-3 bg-white py-3 px-3 text-blue-900 hover:bg-gray-50 rounded-full border border-blue-50 shadow-lg">
            <i class="fa-solid fa-user-check text-2xl"></i>
        </a>
    `;
    return item;
  }
</script>

<script>
  document.querySelector('form').addEventListener('submit', function (e) {
    e.preventDefault(); // Prevent form submission

    let formData = new FormData(this); // Gather form data

    fetch("<?= base_url('controller_queueing/add_to_backroom'); ?>", {
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


</html>