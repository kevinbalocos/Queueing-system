<?php
$left_items = array_slice($fireprotection, 0, 20);
$right_items = array_slice($fireprotection, 20);
$grid_cols = count($left_items) < 5 ? count($left_items) : 5;
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
</head>

<body class="bg-gray-100">
  <div class="flex min-h-screen p-5">
    <!-- Left Section (Now Serving - Fire Protection) -->
    <div class="flex-1 bg-white p-5 rounded-lg shadow-md flex flex-col">
      <h2 class="text-2xl font-bold text-blue-900 text-center">Now Serving - Fire Protection</h2>
      <?php if ($fireprotection): ?>
        <div class="mt-3 grid gap-3 bg-blue-50 h-full"
          style="grid-template-columns: repeat(<?= $grid_cols ?>, 1fr); grid-auto-rows: 1fr;">
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
              <a href="<?= base_url('controller_queueing/proceed_to_releasing/' . $item->id); ?>"
                class="proceed-btn mt-3 inline-block bg-white py-3 px-3 text-blue-900 hover:bg-gray-50 rounded-full border border-blue-50 shadow-lg">
                <i class="fa-solid fa-user-check text-2xl"></i>
              </a>
            </div>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <p class="text-gray-500 mt-3 text-center">No one in fire protection queue</p>
      <?php endif; ?>
    </div>
    <!-- Right Section (Overflow + Add Form) -->
    <div class="ml-5 bg-white p-5 w-[400px] rounded-lg shadow-md flex flex-col">
      <h2 class="text-2xl font-bold text-blue-900 text-center">Fire Protection Queue List</h2>
      <ul class="mt-3 overflow-auto h-[600px] space-y-2">
        <?php foreach ($right_items as $item): ?>
          <li class="p-3 bg-gray-50 border rounded-lg">
            <?= $item->queue_number; ?> - <?= $item->name; ?> (<?= $item->reason; ?>)
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
        <button type="submit" class="mt-2 w-full bg-green-500 text-white py-2 rounded">Add to Fire Protection
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
                backgroundColor: "linear-gradient(to right, #00b09b,rgb(60, 9, 188))"
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