<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-5">

    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-5xl">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-4">Super Admin Dashboard</h2>

        <!-- Display Flash Messages -->
        <?php if ($this->session->flashdata('success')): ?>
            <p class="text-green-600"><?= $this->session->flashdata('success'); ?></p>
        <?php endif; ?>
        <?php if ($this->session->flashdata('error')): ?>
            <p class="text-red-600"><?= $this->session->flashdata('error'); ?></p>
        <?php endif; ?>

        <!-- User Creation Form -->
        <form action="<?= base_url('SuperAdmin/create_user'); ?>" method="post" class="mb-6">
            <div class="grid grid-cols-3 gap-4">
                <input type="text" name="username" placeholder="Username" class="p-2 border rounded-lg" required>
                <input type="password" name="password" placeholder="Password" class="p-2 border rounded-lg" required>
                <select name="role" class="p-2 border rounded-lg">
                    <option value="admin">Admin</option>
                    <option value="superadmin">Super Admin</option>
                    <option value="landtax">Land Tax</option>
                    <option value="releasing">Releasing</option>
                    <option value="payment">Payment</option>
                    <option value="backroom">Backroom</option>
                    <option value="examiners">Examiners</option>
                    <option value="businesstax">Business Tax</option>
                    <option value="fireprotection">Fire Protection</option>
                </select>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                    Create User
                </button>
            </div>
        </form>

        <!-- User List -->
        <h3 class="text-xl font-bold text-gray-700 mb-3">Existing Users</h3>
        <table class="w-full bg-white shadow-lg rounded-lg">
            <thead>
                <tr class="bg-gray-200 text-gray-700">
                    <th class="p-2">Username</th>
                    <th class="p-2">Role</th>
                    <th class="p-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr class="border-t">
                        <td class="p-2"><?= $user->username; ?></td>
                        <td class="p-2"><?= ucfirst($user->role); ?></td>
                        <td class="p-2">
                            <?php if ($user->role !== 'super_admin'): ?>
                                <a href="<?= base_url('SuperAdmin/delete_user/' . $user->id); ?>" 
                                   class="text-red-500 hover:underline">Delete</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Logout Button -->
        <div class="text-center mt-5">
            <a href="<?= base_url('SuperAdmin/logout'); ?>" class="text-red-600 hover:underline">Logout</a>
        </div>
    </div>

</body>
</html>
