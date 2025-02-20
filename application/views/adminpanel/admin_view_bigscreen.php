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

        .queue-grid {
            flex-grow: 1;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); /* Ensures no empty spaces */
            grid-auto-rows: 1fr; /* Makes all items equal height */
            gap: 10px;
            padding: 1rem;
            background: #f9fafb;
            border-radius: 10px;
            overflow: hidden;
        }

        .queue-item {
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
    </style>
</head>

<body>

    <div class="queue-container">
        <h2 class="queue-header">Queue Display</h2>

        <div class="queue-grid">
            <!-- Waiting Queue -->
            <?php foreach (array_slice($queue, 0, 10) as $item): ?>
                <div class="queue-item bg-green-100 border-l-4 border-green-600">
                    <span class="font-bold text-green-600 uppercase">Waiting</span>
                    <h3><?= $item->queue_number; ?></h3>
                    <p><?= $item->name; ?></p>
                </div>
            <?php endforeach; ?>

            <!-- Other Sectors -->
            <?php 
            $sectors = ['backroom' => 'teal', 'examiners' => 'yellow', 'businesstax' => 'purple', 'payment' => 'blue', 'fireprotection' => 'orange'];
            foreach ($sectors as $sector => $color): 
                foreach (array_slice($$sector, 0, 10) as $item): ?>
                    <div class="queue-item bg-<?= $color ?>-100 border-l-4 border-<?= $color ?>-600">
                        <span class="font-bold text-<?= $color ?>-600 uppercase"><?= ucfirst($sector) ?></span>
                        <h3><?= $item->queue_number; ?></h3>
                        <p><?= $item->name; ?></p>
                    </div>
                <?php endforeach; 
            endforeach; ?>
        </div>
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
