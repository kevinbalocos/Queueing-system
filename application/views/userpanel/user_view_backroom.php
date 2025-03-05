<?php
// Limit the left section to a maximum of 20 items (6 rows × 5 columns)
$left_items = array_slice($backroom, 0, 20);
// Overflow items go to the right section
$right_items = array_slice($backroom, 20);
// Determine grid columns: if less than 5 items, use that number; otherwise, use 5 columns.
$grid_cols = count($left_items) < 5 ? count($left_items) : 5;
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


</head>

<body class="bg-gray-100">
  <div class="flex min-h-screen p-5">
    <!-- Left Section (Now Serving in Backroom) -->
    <div class="flex-1 bg-white p-5 rounded-lg shadow-md flex flex-col">
      <h2 class="text-2xl font-bold text-blue-900 text-center">Now Serving - Backroom</h2>
      <?php if ($backroom): ?>
        <!-- Grid container using inline style for dynamic columns and equal row heights -->
        <div class="mt-3 grid gap-3 bg-blue-50 h-full"
          style="grid-template-columns: repeat(<?= $grid_cols ?>, 1fr); grid-auto-rows: 1fr;" id="queue-container">
          <?php foreach ($left_items as $item): ?>
            <div class="p-3 bg-white border rounded-lg flex flex-col justify-center items-center my-3 mx-2">
              <h3 class="font-semibold <?php
              if (count($left_items) == 1) {
                echo 'text-4xl';
              } elseif (count($left_items) <= 2) {
                echo 'text-3xl';
              } elseif (count($left_items) <= 4) {
                echo 'text-2xl';
              } else {
                echo 'text-xl';
              }
              ?>">
                <?= $item->queue_number; ?> - <?= $item->name; ?>
              </h3>
              <p class="text-gray-500 text-sm">Reason: <?= $item->reason; ?></p>
              <p class="text-gray-500 <?php
              if (count($left_items) == 1) {
                echo 'text-2xl';
              } elseif (count($left_items) <= 2) {
                echo 'text-xl';
              } elseif (count($left_items) <= 4) {
                echo 'text-lg';
              } else {
                echo 'text-sm';
              }
              ?>">
                Status: Waiting
              </p>
              <a href="<?= base_url('controller_queueing/proceed_to_examiners/' . $item->id); ?>"
                class="proceed-btn mt-3 inline-block bg-white py-3 px-3 text-blue-900 hover:bg-gray-50 rounded-full border border-blue-50 shadow-lg">
                <i class="fa-solid fa-user-check text-2xl"></i>
              </a>
            </div>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <p class="text-gray-500 mt-3 text-center">No one in backroom queue</p>
      <?php endif; ?>
    </div>

    <!-- Right Section (Overflow Queue List + Add to Backroom Queue Form) -->
    <div class="ml-5 bg-white p-5 w-[400px] rounded-lg shadow-md flex flex-col">
      <h2 class="text-2xl font-bold text-blue-900 text-center">Backroom Queue List</h2>
      <ul class="mt-3 overflow-auto h-[600px] space-y-2" id="queueList">
        <?php foreach ($right_items as $item): ?>
          <li class="p-3 bg-gray-50 border rounded-lg">
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
        <button type="submit" class="mt-2 w-full bg-green-500 text-white py-2 rounded">Add to Backroom Queue</button>
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
  const socket = new WebSocket("ws://localhost:8080"); // Connect to WebSocket server

  socket.onopen = function () {
    console.log("Connected to WebSocket server (Backroom)");
  };

  socket.onmessage = function (event) {
    try {
      const data = JSON.parse(event.data);

      if (data.action === "proceed_to_backroom") {
        console.log("New backroom queue item:", data);
        addToBackroomQueue(data);
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

  // Function to add or update a queue item dynamically
  function addToBackroomQueue(data) {
    const leftSection = document.querySelector("#queue-container");
    const rightSection = document.querySelector("#queueList");

    if (!leftSection || !rightSection) {
      console.error("Error: Target elements missing in DOM.");
      return;
    }

    let existingItem = document.getElementById(`queue-item-${data.id}`);

    if (existingItem) {
      // ✅ If item exists, just update it
      updateQueueItem(existingItem, data);
    } else {
      // ✅ Otherwise, create a new queue item
      console.warn(`Queue item ${data.id} not found. Creating new item.`);
      const newItem = createQueueItem(data);

      if (leftSection.children.length < 20) {
        leftSection.appendChild(newItem);
      } else {
        rightSection.appendChild(newItem);
      }
    }
  }

  // Function to update an existing queue item
  function updateQueueItem(item, data) {
    item.innerHTML = `
      <h3 class="font-semibold text-xl">${data.queue_number} - ${data.name}</h3>
      <p class="text-gray-500 text-sm">Reason: ${data.reason}</p>
      <p class="text-gray-500 text-sm">
        Status: <span class="text-green-500 font-semibold">Backroom</span>
      </p>
      <a href="${data.proceed_url}" class="proceed-btn mt-3 bg-white py-3 px-3 text-blue-900 hover:bg-gray-50 rounded-full border border-blue-50 shadow-lg">
        <i class="fa-solid fa-user-check text-2xl"></i>
      </a>
    `;
  }

  // Function to create a new queue item
  function createQueueItem(data) {
    const item = document.createElement("div");
    item.id = `queue-item-${data.id}`;
    item.className = "p-3 bg-white border rounded-lg flex flex-col justify-center items-center my-3 mx-2";

    updateQueueItem(item, data); // Apply content to item

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