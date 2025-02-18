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

</head>

<body class="bg-gray-100">
  <div class="flex min-h-screen p-5">
    <!-- Left Section (Now Serving) -->
    <div class="flex-1 bg-white p-5 rounded-lg shadow-md flex flex-col">
      <h2 class="text-2xl font-bold text-blue-900 text-center">Now Serving</h2>
      <?php if ($queue): ?>
        <div class="mt-3 grid gap-3 bg-blue-50 h-full"
          style="grid-template-columns: repeat(<?= $grid_cols ?>, 1fr); grid-auto-rows: 1fr;">
          <?php foreach ($left_items as $item): ?>
            <div class="p-3 bg-white border rounded-lg flex flex-col justify-center my-3 mx-2 items-center">
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
              <a href="<?= base_url('controller_queueing/proceed_to_backroom/' . $item->id); ?>"
                class="proceed-btn mt-3 bg-white py-3 px-3 text-blue-900 hover:bg-gray-50 rounded-full border border-blue-50 shadow-lg 
   <?= (!$item->processing_by || $item->processing_by !== $this->session->userdata('username')) ? 'opacity-50 pointer-events-none' : '' ?>">
                <i class="fa-solid fa-user-check text-2xl"></i>
              </a>

            </div>


          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <p class="text-gray-500 mt-3">No one in queue</p>
      <?php endif; ?>
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
  <script>
    const socket = new WebSocket('ws://localhost:8080'); // Connect to the WebSocket server

    socket.onopen = function () {
      console.log('Connected to WebSocket server');
    };

    socket.onmessage = function (event) {
      // When a message is received from the server (new queue item), update the UI
      const data = JSON.parse(event.data);
      updateQueue(data); // Custom function to handle the UI update
    };

    socket.onerror = function (error) {
      console.error('WebSocket Error: ', error);
    };

    socket.onclose = function () {
      console.log('Disconnected from WebSocket server');
    };

    // Function to update the queue display
    function updateQueue(data) {
      const leftSection = document.querySelector('.mt-3.grid');
      const rightSection = document.getElementById('queueList');

      // Check if there's space in the left section (less than 20 items)
      const leftItems = leftSection.querySelectorAll('.p-3');
      if (leftItems.length < 20) {
        // Add the new item to the left section if there's space
        const newItem = createQueueItem(data);
        leftSection.appendChild(newItem);
      } else {
        // If left section is full, add to the right section
        const newItem = createQueueItem(data);
        rightSection.appendChild(newItem);
      }
    }

    // Function to create a queue item element
    function createQueueItem(data) {
      const item = document.createElement('div');
      item.className = 'p-3 bg-white border rounded-lg flex flex-col justify-center my-3 mx-2 items-center';

      item.innerHTML = `
      <h3 class="font-semibold text-xl">${data.queue_number} - ${data.name}</h3>
      <p class="text-gray-500 text-sm">Reason: ${data.reason}</p>
      <p class="text-gray-500 text-sm">
        Status: <span class="text-green-500 font-semibold">Waiting</span>
      </p>
      <button class="processing-btn mt-3 bg-yellow-500 py-2 px-3 text-white hover:bg-yellow-600 rounded-full shadow-lg" data-id="${data.id}">
        <i class="fa-solid fa-hourglass-half"></i> Processing
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
  <script>
    document.querySelectorAll('.processing-btn').forEach(button => {
      button.addEventListener('click', function () {
        let id = this.getAttribute('data-id');
        let button = this;

        fetch(`<?= base_url('controller_queueing/mark_as_processing/'); ?>${id}`, {
          method: 'GET',
        })
          .then(response => response.json())
          .then(data => {
            if (data.status === 'success') {
              Toastify({
                text: data.message,
                duration: 3000,
                close: true,
                gravity: "top",
                position: "right",
                backgroundColor: "linear-gradient(to right, #FF9800, #F57C00)"
              }).showToast();

              // Disable button and update status
              button.classList.add("opacity-50", "cursor-not-allowed");
              button.disabled = true;
              button.parentElement.querySelector(".proceed-btn").classList.add("opacity-50", "pointer-events-none");
              button.parentElement.querySelector("p").innerHTML = `<span class="text-red-500 font-semibold">Processing by You</span>`;
            } else {
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
          });
      });
    });
  </script>


</body>

</html>