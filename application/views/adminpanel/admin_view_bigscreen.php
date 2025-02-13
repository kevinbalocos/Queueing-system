<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BIG SCREEN - Queue Display</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
</head>

<body class="bg-gray-100">
    <div class="min-h-screen p-5 flex flex-col items-center">
        <div class="w-3/4 bg-white p-5 rounded-lg shadow-md">
            <h2 class="text-3xl font-bold text-blue-900 text-center">Queue Display</h2>
            <div class="mt-5 grid grid-cols-3 gap-4 bg-gray-50 p-5 rounded-lg shadow-md">

                <!-- Now Serving -->
                <?php if ($first): ?>
                    <div class="p-4 bg-red-100 border-l-4 border-red-600 rounded-lg text-center shadow-md">
                        <span class="text-sm font-bold text-red-600 uppercase">Now Serving</span>
                        <h3 class="text-3xl font-bold text-red-600"><?= $first->queue_number; ?></h3>
                        <p class="text-lg font-semibold"><?= $first->name; ?></p>
                        <p class="text-gray-600 text-sm"><?= $first->reason; ?></p>
                    </div>
                <?php endif; ?>

                <!-- Queue List -->
                <?php foreach (array_slice($queue, 0, 10) as $item): ?>
                    <div class="p-4 bg-green-100 border-l-4 border-green-600 rounded-lg text-center shadow-md">
                        <span class="text-sm font-bold text-green-600 uppercase">Waiting</span>
                        <h3 class="text-3xl font-semibold"><?= $item->queue_number; ?></h3>
                        <p class="text-lg"><?= $item->name; ?></p>
                        <p class="text-gray-600 text-sm"><?= $item->reason; ?></p>
                    </div>
                <?php endforeach; ?>

                <!-- Backroom Queue -->
                <?php foreach ($backroom as $item): ?>
                    <div class="p-4 bg-blue-100 border-l-4 border-blue-600 rounded-lg text-center shadow-md">
                        <span class="text-sm font-bold text-blue-600 uppercase">Backroom</span>
                        <h3 class="text-3xl font-semibold"><?= $item->queue_number; ?></h3>
                        <p class="text-lg"><?= $item->name; ?></p>
                        <p class="text-gray-600 text-sm"><?= $item->reason; ?></p>
                    </div>
                <?php endforeach; ?>

                <!-- Examiners Queue -->
                <?php foreach (array_slice($examiners, 0, 10) as $item): ?>
                    <div class="p-4 bg-yellow-100 border-l-4 border-yellow-600 rounded-lg text-center shadow-md">
                        <span class="text-sm font-bold text-yellow-600 uppercase">Examiners</span>
                        <h3 class="text-3xl font-semibold"><?= $item->queue_number; ?></h3>
                        <p class="text-lg"><?= $item->name; ?></p>
                        <p class="text-gray-600 text-sm"><?= $item->reason; ?></p>
                    </div>
                <?php endforeach; ?>

                <!-- Business Tax Queue -->
                <?php foreach (array_slice($businesstax, 0, 10) as $item): ?>
                    <div class="p-4 bg-purple-100 border-l-4 border-purple-600 rounded-lg text-center shadow-md">
                        <span class="text-sm font-bold text-purple-600 uppercase">Business Tax</span>
                        <h3 class="text-3xl font-semibold"><?= $item->queue_number; ?></h3>
                        <p class="text-lg"><?= $item->name; ?></p>
                        <p class="text-gray-600 text-sm"><?= $item->reason; ?></p>
                    </div>
                <?php endforeach; ?>

                <!-- Payment Queue -->
                <?php foreach (array_slice($payment, 0, 10) as $item): ?>
                    <div class="p-4 bg-blue-100 border-l-4 border-blue-600 rounded-lg text-center shadow-md">
                        <span class="text-sm font-bold text-blue-600 uppercase">Payment</span>
                        <h3 class="text-3xl font-semibold"><?= $item->queue_number; ?></h3>
                        <p class="text-lg"><?= $item->name; ?></p>
                        <p class="text-gray-600 text-sm"><?= $item->reason; ?></p>
                    </div>
                <?php endforeach; ?>

                <!-- Fire Protection Queue -->
                <?php foreach (array_slice($fireprotection, 0, 10) as $item): ?>
                    <div class="p-4 bg-orange-100 border-l-4 border-orange-600 rounded-lg text-center shadow-md">
                        <span class="text-sm font-bold text-orange-600 uppercase">Fire Protection</span>
                        <h3 class="text-3xl font-semibold"><?= $item->queue_number; ?></h3>
                        <p class="text-lg"><?= $item->name; ?></p>
                        <p class="text-gray-600 text-sm"><?= $item->reason; ?></p>
                    </div>
                <?php endforeach; ?>

            </div>
        </div>
    </div>

    <script>
        setInterval(() => {
            location.reload();
        }, 5000);
    </script>
</body>

</html>