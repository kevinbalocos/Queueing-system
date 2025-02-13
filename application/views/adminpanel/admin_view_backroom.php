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
</head>
<body class="bg-gray-100">
  <div class="flex min-h-screen p-5">
    <!-- Left Section (Now Serving in Backroom) -->
    <div class="flex-1 bg-white p-5 rounded-lg shadow-md flex flex-col">
      <h2 class="text-2xl font-bold text-blue-900 text-center">Now Serving - Backroom</h2>
      <?php if ($backroom): ?>
        <!-- Grid container using inline style for dynamic columns and equal row heights -->
        <div class="mt-3 grid gap-3 bg-blue-50 h-full" 
             style="grid-template-columns: repeat(<?= $grid_cols ?>, 1fr); grid-auto-rows: 1fr;">
          <?php foreach ($left_items as $item): ?>
            <div class="p-3 bg-white border rounded-lg flex flex-col justify-center items-center my-3 mx-2">
              <h3 class="font-semibold <?php 
                      if(count($left_items) == 1) { echo 'text-4xl'; } 
                      elseif(count($left_items) <= 2) { echo 'text-3xl'; }
                      elseif(count($left_items) <= 4) { echo 'text-2xl'; }
                      else { echo 'text-xl'; }
                    ?>">
                <?= $item->queue_number; ?> - <?= $item->name; ?>
              </h3>
              <p class="text-gray-500 text-sm">Reason: <?= $item->reason; ?></p>
              <p class="text-gray-500 <?php 
                      if(count($left_items) == 1) { echo 'text-2xl'; } 
                      elseif(count($left_items) <= 2) { echo 'text-xl'; }
                      elseif(count($left_items) <= 4) { echo 'text-lg'; }
                      else { echo 'text-sm'; }
                    ?>">
                Status: Waiting
              </p>
              <a href="<?= base_url('controller_queueing/proceed_to_examiners/' . $item->id); ?>"
                 class="mt-3 inline-block bg-white py-3 px-3 text-blue-900 hover:bg-gray-50 rounded-full border border-blue-50 shadow-lg">
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
      <ul class="mt-3 overflow-auto h-[600px] space-y-2">
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
    </div>
  </div>
</body>
</html>
