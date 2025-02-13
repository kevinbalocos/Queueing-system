<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RELEASING</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100  min-h-screen p-5">

    <div class="bg-white p-6 rounded-lg justify-center items-center shadow-lg ">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-4">Releasing Queue</h2>

        <!-- Responsive Grid Layout -->
        <div class="grid gap-4  sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5">
            <?php foreach ($releasing as $r): ?>
                <div class="p-4 bg-gray-50 border rounded-lg shadow  flex flex-col items-center">
                    <span class="text-gray-700 font-medium text-lg"><?= $r->name; ?></span>
                    <span class="text-green-600 font-bold mt-2">Completed</span>
                </div>
            <?php endforeach; ?>
        </div>

    </div>

</body>

</html>