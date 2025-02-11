<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Examiners</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-5">

<ul class="mt-3 space-y-2">
    <?php foreach ($examiners as $e) : ?>
        <li class="p-3 bg-gray-50 border rounded-lg flex justify-between">
            <?= $e->name; ?>
            <a href="<?= base_url('controller_queueing/proceed_to_businesstax/' . $e->id); ?>" class="bg-blue-500 text-white px-3 py-1 rounded-md">Proceed</a>
        </li>
    <?php endforeach; ?>
</ul>


</body>
</html>
