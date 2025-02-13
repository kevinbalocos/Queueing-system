<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Set background image for the body */
        body {
            background-image: url('<?= base_url('uploads/login_image/login_background.jpg'); ?>');
            background-size: cover; /* Ensure the image covers the entire viewport */
            background-position: center; /* Center the background */
            background-attachment: fixed; /* Keep the background fixed during scroll */
        }
    </style>
</head>
<body class="flex items-center justify-center h-screen bg-gray-900 bg-opacity-50">
    <div class="bg-white p-8 rounded-lg shadow-lg w-96 max-w-md">
     
        <?php if ($this->session->flashdata('error')): ?>
            <div class="bg-red-200 text-red-800 p-2 rounded mb-4 text-center">
                <?= $this->session->flashdata('error'); ?>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('controller_login/login_process'); ?>" method="post">
            <div class="mb-5">
                <label for="username" class="block text-gray-700 text-sm font-medium">Username</label>
                <div class="relative mt-2">
                    <input type="text" id="username" name="username" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Enter your username" required>
                    <i class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 fas fa-user"></i>
                </div>
            </div>

            <div class="mb-5">
                <label for="password" class="block text-gray-700 text-sm font-medium">Password</label>
                <div class="relative mt-2">
                    <input type="password" id="password" name="password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Enter your password" required>
                    <i class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 fas fa-lock"></i>
                </div>
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg font-semibold focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">Login</button>
        </form>

        <div class="text-center mt-4">
            <a href="#" class="text-sm text-blue-600 hover:underline">Forgot your password?</a>
        </div>
    </div>

    <!-- FontAwesome icons -->
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</body>
</html>
