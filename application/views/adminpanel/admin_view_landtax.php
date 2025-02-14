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
  <link href="<?= base_url('assets/css/input.css'); ?>" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
  <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
  <script src="http://localhost:3000/socket.io/socket.io.js"></script>

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
              <h3
                class="font-semibold <?= count($left_items) == 1 ? 'text-4xl' : (count($left_items) <= 2 ? 'text-3xl' : (count($left_items) <= 4 ? 'text-2xl' : 'text-xl')) ?>">
                <?= $item->queue_number; ?> - <?= $item->name; ?>
              </h3>
              <p class="text-gray-500 text-sm">Reason: <?= $item->reason; ?></p>
              <p
                class="text-gray-500 <?= count($left_items) == 1 ? 'text-2xl' : (count($left_items) <= 2 ? 'text-xl' : (count($left_items) <= 4 ? 'text-lg' : 'text-sm')) ?>">
                Status: Waiting
              </p>
              <a href="<?= base_url('controller_queueing/proceed_to_backroom/' . $item->id); ?>"
                class="proceed-btn mt-3 inline-block bg-white py-3 px-3 text-blue-900 hover:bg-gray-50 rounded-full border border-blue-50 shadow-lg">
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
      <form id="queueForm" class="mt-5">
        <input type="text" name="name" id="name" placeholder="Enter Name" required class="border p-2 w-full rounded" />
        <select name="reason" id="reason" required class="border p-2 w-full rounded mt-2">
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
                  style: {
                    background: "linear-gradient(to right, #00b09b, rgb(25, 187, 205))" // ✅ Correct
                  }
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
        document.addEventListener("DOMContentLoaded", function () {
          var socket = io("http://localhost:3000"); // Connect to Socket.io server

          var queueForm = document.getElementById("queueForm");

          if (queueForm) {
            queueForm.addEventListener("submit", function (e) {
              e.preventDefault();

              let formData = new FormData(queueForm);

              fetch("<?= base_url('controller_queueing/add_to_queue'); ?>", {
                method: "POST",
                body: formData
              })
                .then(response => response.json())
                .then(data => {
                  if (data.status === "success") {
                    socket.emit("newQueueItem", data);
                    queueForm.reset();
                  }
                })
                .catch(error => console.error("Error:", error));
            });
          }

          socket.on("updateQueue", function (data) {
            let queueList = document.getElementById("queueList");
            let newItem = document.createElement("li");
            newItem.className = "p-3 bg-gray-50 border rounded-lg";
            newItem.textContent = data.queue_number + " - " + data.name;
            queueList.appendChild(newItem);
          });
        });
      </script>
    </div>
  </div>


</body>

</html>