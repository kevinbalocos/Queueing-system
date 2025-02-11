<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BUSINESS TAX</title>
</head>
<body>
    
<ul class="mt-3 space-y-2">
    <?php foreach ($businesstax as $b) : ?>
        <li class="p-3 bg-gray-50 border rounded-lg flex justify-between">
            <?= $b->name; ?>
            <a href="<?= base_url('controller_queueing/proceed_to_payment/' . $b->id); ?>" class="bg-blue-500 text-white px-3 py-1 rounded-md">Proceed</a>
        </li>
    <?php endforeach; ?>
</ul>

</body>
</html>