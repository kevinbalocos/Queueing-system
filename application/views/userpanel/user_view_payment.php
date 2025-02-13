<?php
$left_items = array_slice($payment, 0, 20);
$right_items = array_slice($payment, 20);
$grid_cols = count($left_items) < 5 ? count($left_items) : 5;
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Payment Queue</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
  <div class="flex min-h-screen p-5">
    <!-- Left Section (Now Serving - Payment) -->
    <div class="flex-1 bg-white p-5 rounded-lg shadow-md flex flex-col">
      <h2 class="text-2xl font-bold text-blue-900 text-center">Now Serving - Payment</h2>
      <?php if ($payment): ?>
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
              <a href="<?= base_url('controller_queueing/proceed_to_fireprotection/' . $item->id); ?>"
                class="mt-3 inline-block bg-white py-3 px-3 text-blue-900 hover:bg-gray-50 rounded-full border border-blue-50 shadow-lg">
                <i class="fa-solid fa-user-check text-2xl"></i>
              </a>
            </div>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <p class="text-gray-500 mt-3 text-center">No one in payment queue</p>
      <?php endif; ?>
    </div>
    <!-- Right Section (Overflow + Add Form) -->
    <div class="ml-5 bg-white p-5 w-[400px] rounded-lg shadow-md flex flex-col">
      <h2 class="text-2xl font-bold text-blue-900 text-center">Payment Queue List</h2>
      <ul class="mt-3 overflow-auto h-[600px] space-y-2">
        <?php foreach ($right_items as $item): ?>
          <li class="p-3 bg-gray-50 border rounded-lg">
            <?= $item->queue_number; ?> - <?= $item->name; ?> (<?= $item->reason; ?>)
          </li>
        <?php endforeach; ?>
      </ul>
      <!-- Add to Payment Queue Form -->
      <form action="<?= base_url('controller_queueing/add_to_payment'); ?>" method="post" class="mt-5">
        <input type="text" name="name" placeholder="Enter Name" required class="border p-2 w-full rounded" />
        <select name="reason" required class="border p-2 w-full rounded mt-2">
          <option value="Visa Payment">Visa Payment</option>
          <option value="Tax Payment">Tax Payment</option>
        </select>
        <button type="submit" class="mt-2 w-full bg-green-500 text-white py-2 rounded">Add to Payment Queue</button>
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

</html>