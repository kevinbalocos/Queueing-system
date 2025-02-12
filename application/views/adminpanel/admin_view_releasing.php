<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RELEASING</title>
</head>
<body>


<ul class="mt-3 space-y-2">
    <?php foreach ($releasing as $r) : ?>
        <li class="p-3 bg-gray-50 border rounded-lg">
            <?= $r->name; ?> - <span class="text-green-600 font-bold">Completed</span>
        </li>
    <?php endforeach; ?>
</ul>

    


</body>
</html>