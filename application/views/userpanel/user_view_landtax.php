<?php 
  // Determine how many items go to the left section (maximum 30)
  $left_items = array_slice($queue, 0, 30);
  // Determine grid columns: if less than 5 items, use that many columns; otherwise use 5 columns.
  $grid_cols = count($left_items) < 5 ? count($left_items) : 5;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Land Tax Queue</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
  <div class="flex min-h-screen p-5">
    <!-- Left Section (Now Serving) -->
    <div class="flex-1 bg-white p-5 rounded-lg shadow-md flex flex-col">
      <h2 class="text-2xl font-bold text-blue-900 text-center">Now Serving</h2>
      <?php if ($queue) : ?>
        <!-- Use a grid with a fixed number of columns -->
        <div class="mt-3 grid gap-3 h-full"
             style="grid-template-columns: repeat(<?= $grid_cols ?>, 1fr); grid-auto-rows: 1fr;">
          <?php foreach ($left_items as $item) : ?>
            <div class="p-3 bg-gray-50 border rounded-lg flex flex-col justify-center items-center">
              <h3 class="font-semibold
                <?php 
                  if(count($left_items) == 1) { echo 'text-4xl'; } 
                  elseif(count($left_items) == 2) { echo 'text-3xl'; }
                  elseif(count($left_items) == 3) { echo 'text-2xl'; }
                  elseif(count($left_items) == 4) { echo 'text-2xl'; }
                  else { echo 'text-xl'; }
                ?>">
                <?= $item->name; ?>
              </h3>
              <p class="text-gray-500
                <?php 
                  if(count($left_items) == 1) { echo 'text-2xl'; } 
                  elseif(count($left_items) == 2) { echo 'text-xl'; }
                  elseif(count($left_items) == 3) { echo 'text-lg'; }
                  elseif(count($left_items) == 4) { echo 'text-lg'; }
                  else { echo 'text-sm'; }
                ?>">
                Status: Waiting
              </p>
              <a href="<?= base_url('controller_queueing/proceed_to_backroom/' . $item->id); ?>"
                 class="mt-3 inline-block bg-blue-500 text-white px-5 py-2 rounded-md">
                Proceed
              </a>
            </div>
          <?php endforeach; ?>
        </div>
      <?php else : ?>
        <p class="text-gray-500 mt-3">No one in queue</p>
      <?php endif; ?>
    </div>

    <!-- Right Section (Queue List) -->
    <div class="ml-5 bg-white p-5 w-[400px] rounded-lg shadow-md flex flex-col">
      <h2 class="text-2xl font-bold text-blue-900 text-center">Queue List</h2>
      <ul class="mt-3 overflow-auto h-[600px] space-y-2">
        <?php 
          // Items with index 30 or more go into the right section.
          $right_items = array_slice($queue, 30);
          foreach($right_items as $item): 
        ?>
          <li class="p-3 bg-gray-50 border rounded-lg"><?= $item->name; ?></li>
        <?php endforeach; ?>
      </ul>
      <!-- Add to Queue Form -->
      <form action="<?= base_url('controller_queueing/add_to_queue'); ?>" method="post" class="mt-5">
        <input type="text" name="name" placeholder="Enter Name" required class="border p-2 w-full rounded" />
        <button type="submit" class="mt-2 w-full bg-green-500 text-white py-2 rounded">Add to Queue</button>
      </form>
    </div>
  </div>
</body>
</html>
