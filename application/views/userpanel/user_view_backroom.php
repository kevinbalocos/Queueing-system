<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Backroom</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-5">

    <div class="bg-white p-5 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold text-blue-900">Backroom</h2>
        <ul class="mt-3 space-y-2">
            <?php foreach ($backroom as $b) : ?>
                <li class="p-3 bg-gray-50 border rounded-lg flex justify-between">
                    <?= $b->name; ?>
                    <a href="<?= base_url('controller_queueing/proceed_to_examiners/' . $b->id); ?>" class="bg-blue-500 text-white px-3 py-1 rounded-md">Proceed</a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

</body>
</html>
