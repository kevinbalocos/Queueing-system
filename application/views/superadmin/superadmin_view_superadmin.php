<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-5">
    <div class="max-w-3xl mx-auto bg-white p-5 rounded shadow-md">
        <h2 class="text-2xl font-bold text-blue-900">Super Admin Panel</h2>

        <!-- Add User Form -->
        <form action="<?= base_url('superadmin/create_user'); ?>" method="post" class="mt-4">
            <input type="text" name="username" placeholder="Username" required class="border p-2 w-full rounded">
            <input type="password" name="password" placeholder="Password" required class="border p-2 w-full rounded mt-2">
            <select name="role" required class="border p-2 w-full rounded mt-2">
                <option value="admin">Admin</option>
                <option value="user">User</option>
            </select>
            <button type="submit" class="mt-2 w-full bg-green-500 text-white py-2 rounded">Create User</button>
        </form>

        <!-- Display Users -->
        <h3 class="text-xl font-bold text-blue-900 mt-5">User List</h3>
        <ul class="mt-3 space-y-2">
            <?php foreach ($users as $user): ?>
                <li class="p-3 bg-gray-50 border rounded-lg flex justify-between">
                    <?= $user->username; ?> (<?= $user->role; ?>)
                    <a href="<?= base_url('superadmin/delete_user/' . $user->id); ?>" class="text-red-500">Delete</a>
                </li>
            <?php endforeach; ?>
        </ul>

        <a href="<?= base_url('controller_admin_landing/logout'); ?>" class="mt-5 inline-block bg-red-500 text-white px-4 py-2 rounded">Logout</a>
    </div>
</body>
</html>
