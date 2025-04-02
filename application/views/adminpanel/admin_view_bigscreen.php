<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BIG SCREEN - Queue Display</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <style>
        html, body {
            width: 100vw;
            height: 100vh;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f3f4f6;
        }

        .queue-container {
            width: 95vw;
            height: 95vh;
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .queue-header {
            font-size: clamp(1.5rem, 3vw, 2rem);
            font-weight: bold;
            text-align: center;
            color: #1e3a8a;
            margin-bottom: 1rem;
        }

        .queue-row {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1rem;
            background: #f9fafb;
        }

        .queue-item {
            flex: 1 1 250px; /* Ensures equal width */
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 1rem;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            font-size: clamp(0.8rem, 2vw, 1rem);
        }

        .queue-item h3 {
            font-size: clamp(1.2rem, 2.5vw, 1.5rem);
            font-weight: bold;
        }

        .queue-item p {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sector-title {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }
    </style>
</head>

<body>

    <div class="queue-container overflow-y-auto">Z`
        <h2 class="queue-header">Queue Display</h2>

        <!-- Waiting Queue -->
        <div class="queue-row ">
            <h3 class="sector-title text-green-600 w-full">Waiting Queue</h3>
            <?php foreach (array_slice($queue, 0, 10) as $item): ?>
                <div class="queue-item bg-green-100 border-l-4 border-green-600 ">
                    <h3><?= $item->queue_number; ?></h3>
                    <p><?= $item->name; ?></p>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Other Sectors -->
        <?php 
        $sectors = ['backroom' => 'teal', 'examiners' => 'yellow', 'businesstax' => 'purple', 'payment' => 'blue', 'fireprotection' => 'orange'];
        foreach ($sectors as $sector => $color): ?>
            <div class="queue-row ">
                <h3 class="sector-title text-<?= $color ?>-600 w-full"><?= ucfirst($sector) ?></h3>
                <?php foreach (array_slice($$sector, 0, 10) as $item): ?>
                    <div class="queue-item bg-<?= $color ?>-100 border-l-4 border-<?= $color ?>-600">
                        <h3><?= $item->queue_number; ?></h3>
                        <p><?= $item->name; ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>

    </div>

    <script>
        // Auto-refresh queue data without full page reload
        setInterval(() => {
            fetch(window.location.href)
                .then(response => response.text())
                .then(html => {
                    document.body.innerHTML = html;
                });
        }, 3000);
    </script>

</body>

</html>
