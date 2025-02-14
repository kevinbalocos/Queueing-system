<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RELEASING</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen p-5">

    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-5xl">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-4">Releasing Queue</h2>

        <!-- Responsive Grid Layout -->
        <div class="grid gap-4 grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5">
            <?php foreach ($releasing as $r): ?>
                <div class="p-4 bg-gray-50 border rounded-lg shadow flex flex-col items-center">
                    <span class="text-gray-700 font-medium text-lg"><?= $r->name; ?></span>
                    <span class="text-green-600 font-bold mt-2">Completed</span>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Logout Button -->
        <div class="mt-6 text-center">
            <a href="<?= base_url('controller_admin_landing/logout'); ?>"
                class="inline-flex items-center bg-red-500 text-white px-5 py-2 rounded-lg hover:bg-red-600 transition">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M3 10a1 1 0 011-1h8.586l-2.293-2.293a1 1 0 111.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 11-1.414-1.414L12.586 11H4a1 1 0 01-1-1z"
                        clip-rule="evenodd"></path>
                </svg>
                Logout
            </a>
        </div>
    </div>

</body>

</html>
