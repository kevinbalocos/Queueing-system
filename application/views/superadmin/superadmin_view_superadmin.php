<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Super Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <scriptsrc="https: //cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js">
        </scriptsrc=>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/inter-ui/3.19.3/inter.min.css" rel="stylesheet">
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        fontFamily: {
                            sans: ['Inter', 'sans-serif'],
                        },
                        colors: {
                            primary: {
                                50: '#EFF6FF',
                                100: '#DBEAFE',
                                500: '#3B82F6',
                                600: '#2563EB',
                                700: '#1D4ED8',
                            },
                            dark: {
                                800: '#1E293B',
                                900: '#0F172A',
                            }
                        }
                    },
                }
            }


            function toggleMobileMenu() {
                const sidebar = document.getElementById('sidebar');
                sidebar.classList.toggle('hidden');
                sidebar.classList.toggle('flex');
            }

            function showToast(message, type = 'success') {
                const toast = document.createElement('div');
                const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';

                toast.className = `fixed top-4 right-4 ${bgColor} text-white px-6 py-3 rounded-md shadow-lg flex items-center z-50 animate-fade-in-down`;
                toast.innerHTML = `
                <span class="mr-2">${type === 'success' ? '✅' : '❌'}</span>
                <span>${message}</span>
            `;

                document.body.appendChild(toast);

                setTimeout(() => {
                    toast.classList.add('animate-fade-out');
                    setTimeout(() => {
                        document.body.removeChild(toast);
                    }, 500);
                }, 3000);
            }

            function confirmDelete(username, userId) {
                if (confirm(`Are you sure you want to delete user "${username}"?`)) {
                    window.location.href = `<?= base_url('SuperAdmin/delete_user/') ?>${userId}`;
                }
            }

            function openEditModal(userId, username, role) {
                const modal = document.getElementById('editModal');
                document.getElementById('editUserId').value = userId;
                document.getElementById('editUsername').value = username;
                document.getElementById('editRole').value = role;
                modal.classList.remove('hidden');
            }

            function closeModal() {
                document.getElementById('editModal').classList.add('hidden');
            }
        </script>
        <script>
            // Fade out the flash message
            setTimeout(() => {
                const flashMessage = document.getElementById('flash-message');
                if (flashMessage) {
                    flashMessage.style.opacity = '0';
                    setTimeout(() => flashMessage.remove(), 1000);
                }
            }, 4000);
        </script>
        <style>
            .animate-fade-in-down {
                animation: fadeInDown 0.5s ease forwards;
            }

            .animate-fade-out {
                animation: fadeOut 0.5s ease forwards;
            }

            @keyframes fadeInDown {
                0% {
                    opacity: 0;
                    transform: translateY(-20px);
                }

                100% {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            @keyframes fadeOut {
                0% {
                    opacity: 1;
                }

                100% {
                    opacity: 0;
                }
            }
        </style>
</head>

<body class="bg-dark-900 text-white font-sans h-screen overflow-hidden">
    <!-- Layout Wrapper -->
    <div class="flex h-full">
        <!-- Sidebar (Desktop) -->
        <aside id="sidebar" class="hidden md:block w-64 bg-dark-800 shadow-lg h-full">
            <div class="flex flex-col h-full">
                <!-- Logo and Brand -->
                <div class="p-6 border-b border-gray-700">
                    <div class="flex items-center space-x-3">
                        <span class="text-primary-500 text-2xl">
                            <i class="fas fa-shield-alt"></i>
                        </span>
                        <h1 class="text-xl font-bold tracking-wide text-white uppercase">Super Admin</h1>
                    </div>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 p-6 space-y-1 overflow-y-auto">

                </nav>

                <!-- User Profile -->
                <div class="p-6 border-t border-gray-700">
                    <div class="flex items-center space-x-3">
                        <div class="bg-gray-700 rounded-full p-2">
                            <i class="fas fa-user text-gray-300"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium">Administrator</p>
                            <a href="<?= base_url('SuperAdmin/logout'); ?>"
                                class="text-sm text-red-400 hover:text-red-300 flex items-center">
                                <i class="fas fa-sign-out-alt mr-1 text-xs"></i> Logout
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col overflow-hidden bg-gradient-to-br from-dark-900 to-dark-800">
            <!-- Top Navigation Bar -->
            <header class="bg-dark-800 shadow-md">
                <div class="flex items-center justify-between px-6 py-4">
                    <div class="flex items-center">
                        <!-- Mobile menu button -->
                        <button onclick="toggleMobileMenu()"
                            class="md:hidden text-gray-300 hover:text-white focus:outline-none">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                    </div>

                    <div class="flex items-center space-x-4">
                        <input type="text" id="liveSearch" name="search" placeholder="Search users..."
                            class="pl-10 pr-4 py-2 w-48 md:w-64 rounded-md bg-gray-700 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500" />
                        <form method="POST" action="<?= base_url('SuperAdmin/delete_all_queue') ?>"
                            onsubmit="return confirm('Are you sure you want to delete all queue entries?');">
                            <button type="submit"
                                class="mt-2 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md">
                                Delete All
                            </button>
                        </form>

                        <button class="p-2 rounded-full bg-gray-700 hover:bg-gray-600 transition">
                            <i class="fas fa-bell text-gray-300"></i>
                        </button>
                    </div>
                </div>
            </header>

            <!-- Content Area -->
            <div class="flex-1 overflow-y-auto p-6">
                <!-- Page Header -->
                <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
                    <div>
                        <h2 class="text-2xl font-bold">User Management</h2>
                        <p class="text-gray-400 mt-1">Create and manage system users</p>
                    </div>
                    <button onclick="document.getElementById('createUserForm').classList.toggle('hidden')"
                        class="mt-4 md:mt-0 flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 rounded-md text-white font-medium transition">
                        <i class="fas fa-plus mr-2"></i> New User
                    </button>
                </div>

                <!-- Flash Messages -->
                <?php if ($this->session->flashdata('success')): ?>
                    <div id="flash-message"
                        class="bg-green-600/20 border border-green-600/30 text-green-300 px-4 py-3 rounded-lg shadow-inner mb-6 transition-opacity duration-1000">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle mr-3 text-green-400"></i>
                            <span><?= $this->session->flashdata('success'); ?></span>
                        </div>
                    </div>
                    <script>
                        showToast("<?= $this->session->flashdata('success'); ?>", "success");
                    </script>
                <?php endif; ?>

                <?php if ($this->session->flashdata('error')): ?>
                    <div id="flash-message"
                        class="bg-red-600/20 border border-red-600/30 text-red-300 px-4 py-3 rounded-lg shadow-inner mb-6 transition-opacity duration-1000">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle mr-3 text-red-400"></i>
                            <span><?= $this->session->flashdata('error'); ?></span>
                        </div>
                    </div>
                    <script>
                        showToast("<?= $this->session->flashdata('error'); ?>", "error");
                    </script>
                <?php endif; ?>

                <!-- User Creation Form (collapsible) -->
                <div id="createUserForm" class="hidden mb-6">
                    <div class="bg-dark-800 rounded-lg shadow-lg p-6 border border-gray-700">
                        <h3 class="text-lg font-semibold mb-4 flex items-center">
                            <i class="fas fa-user-plus text-primary-500 mr-2"></i>
                            Create New User
                        </h3>

                        <form action="<?= base_url('SuperAdmin/create_user'); ?>" method="post" class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-400 mb-1">Username</label>
                                    <div class="relative">
                                        <span
                                            class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-500">
                                            <i class="fas fa-user"></i>
                                        </span>
                                        <input type="text" name="username" placeholder="Enter username" required
                                            class="w-full bg-gray-700 pl-10 px-4 py-2 rounded-md focus:ring-2 ring-primary-400 placeholder-gray-500" />
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-400 mb-1">Password</label>
                                    <div class="relative">
                                        <span
                                            class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-500">
                                            <i class="fas fa-lock"></i>
                                        </span>
                                        <input type="password" name="password" placeholder="Enter password" required
                                            class="w-full bg-gray-700 pl-10 px-4 py-2 rounded-md focus:ring-2 ring-primary-400 placeholder-gray-500" />
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-400 mb-1">Role</label>
                                    <div class="relative">
                                        <span
                                            class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-500">
                                            <i class="fas fa-id-badge"></i>
                                        </span>
                                        <select name="role"
                                            class="w-full bg-gray-700 pl-10 px-4 py-2 rounded-md text-white focus:ring-2 ring-primary-400 appearance-none">
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
                                        <span
                                            class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-500">
                                            <i class="fas fa-chevron-down"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-end space-x-3 mt-4">
                                <button type="button"
                                    onclick="document.getElementById('createUserForm').classList.add('hidden')"
                                    class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-md transition">
                                    Cancel
                                </button>
                                <button type="submit"
                                    class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-md shadow transition flex items-center">
                                    <i class="fas fa-save mr-2"></i> Create User
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-dark-800 rounded-lg shadow-lg p-4 border border-gray-700">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-blue-500/20 text-blue-500">
                                <i class="fas fa-users text-lg"></i>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-gray-400 text-sm">Total Users</h4>
                                <p class="text-2xl font-semibold"><?= count($users); ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-dark-800 rounded-lg shadow-lg p-4 border border-gray-700">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-purple-500/20 text-purple-500">
                                <i class="fas fa-user-shield text-lg"></i>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-gray-400 text-sm">Admins</h4>
                                <p class="text-2xl font-semibold">
                                    <?php
                                    $adminCount = 0;
                                    foreach ($users as $user) {
                                        if ($user->role === 'admin' || $user->role === 'superadmin') {
                                            $adminCount++;
                                        }
                                    }
                                    echo $adminCount;
                                    ?>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-dark-800 rounded-lg shadow-lg p-4 border border-gray-700">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-green-500/20 text-green-500">
                                <i class="fas fa-user-check text-lg"></i>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-gray-400 text-sm">Active Now</h4>
                                <p class="text-2xl font-semibold">3</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-dark-800 rounded-lg shadow-lg p-4 border border-gray-700">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-red-500/20 text-red-500">
                                <i class="fas fa-user-lock text-lg"></i>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-gray-400 text-sm">Locked Accounts</h4>
                                <p class="text-2xl font-semibold">0</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- User Table -->
                <div class="bg-dark-800 rounded-lg shadow-lg border border-gray-700 overflow-hidden">
                    <div class="p-4 border-b border-gray-700 bg-gray-800/40">
                        <h3 class="font-medium flex items-center">
                            <i class="fas fa-table text-primary-500 mr-2"></i>
                            System Users
                        </h3>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-700">
                            <thead class="bg-gray-800/60">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                        <div class="flex items-center">
                                            User
                                            <i class="fas fa-sort ml-1 text-gray-600"></i>
                                        </div>
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                        <div class="flex items-center">
                                            Role
                                            <i class="fas fa-sort ml-1 text-gray-600"></i>
                                        </div>
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                        <div class="flex items-center">
                                            Status
                                        </div>
                                    </th>
                                    <th
                                        class="px-6 py-3 text-center text-xs font-medium text-gray-400 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="resultsTable" class="divide-y divide-gray-700">
                                <?php if (!empty($users)): ?>
                                    <?php foreach ($users as $user): ?>
                                        <tr class="hover:bg-gray-700/50 transition">
                                            <td class="px-6 py-4">
                                                <div class="flex items-center">
                                                    <div class="bg-gray-700 rounded-full p-2 mr-3">
                                                        <i class="fas fa-user text-gray-400"></i>
                                                    </div>
                                                    <div>
                                                        <div class="font-medium text-white">
                                                            <?= htmlspecialchars($user->username); ?>
                                                        </div>
                                                        <div class="text-sm text-gray-400">ID: <?= $user->id; ?></div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <?php
                                                $roleColors = [
                                                    'superadmin' => 'bg-purple-500',
                                                    'admin' => 'bg-blue-500',
                                                    'landtax' => 'bg-green-500',
                                                    'releasing' => 'bg-yellow-500',
                                                    'payment' => 'bg-pink-500',
                                                    'backroom' => 'bg-indigo-500',
                                                    'examiners' => 'bg-red-500',
                                                    'businesstax' => 'bg-orange-500',
                                                    'fireprotection' => 'bg-teal-500'
                                                ];
                                                $roleColor = isset($roleColors[$user->role]) ? $roleColors[$user->role] : 'bg-gray-500';
                                                ?>
                                                <span
                                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium <?= $roleColor; ?>">
                                                    <?= ucfirst($user->role); ?>
                                                </span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span
                                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-500/20 text-green-400">
                                                    <span class="h-2 w-2 rounded-full bg-green-400 mr-1.5"></span>
                                                    Active
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <div class="flex justify-center space-x-3">
                                                    <?php if ($user->role !== 'superadmin'): ?>
                                                        <button
                                                            onclick="openEditModal(<?= $user->id; ?>, '<?= htmlspecialchars($user->username); ?>', '<?= $user->role; ?>')"
                                                            class="text-blue-400 hover:text-blue-300 transition text-sm font-medium flex items-center">
                                                            <i class="fas fa-edit mr-1"></i> Edit
                                                        </button>
                                                        <button
                                                            onclick="confirmDelete('<?= htmlspecialchars($user->username); ?>', <?= $user->id; ?>)"
                                                            class="text-red-400 hover:text-red-300 transition text-sm font-medium flex items-center">
                                                            <i class="fas fa-trash-alt mr-1"></i> Delete
                                                        </button>
                                                    <?php else: ?>
                                                        <span class="text-gray-500 text-sm italic">Protected</span>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr id="noResultsRow" style="display: none;">
                                        <td colspan="4" class="px-6 py-4 text-center text-gray-400">No users found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="px-6 py-3 flex items-center justify-between border-t border-gray-700">
                        <div class="text-sm text-gray-400">
                            Showing <span class="font-medium text-white"><?= count($users); ?></span> users
                        </div>
                        <div class="flex items-center space-x-2">
                            <button
                                class="px-3 py-1 rounded bg-gray-700 text-gray-300 hover:bg-gray-600 transition disabled:opacity-50 disabled:cursor-not-allowed"
                                disabled>
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <span class="px-3 py-1 rounded bg-primary-600 text-white">1</span>
                            <button
                                class="px-3 py-1 rounded bg-gray-700 text-gray-300 hover:bg-gray-600 transition disabled:opacity-50 disabled:cursor-not-allowed"
                                disabled>
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Edit User Modal -->
    <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-dark-800 rounded-lg shadow-xl border border-gray-700 w-full max-w-md">
            <div class="border-b border-gray-700 px-6 py-4 flex items-center justify-between">
                <h3 class="font-semibold text-lg flex items-center">
                    <i class="fas fa-user-edit text-primary-500 mr-2"></i>
                    Edit User
                </h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-200">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form action="<?= base_url('SuperAdmin/update_user'); ?>" method="post" class="p-6">
                <input type="hidden" id="editUserId" name="user_id">

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">Username</label>
                        <input type="text" id="editUsername" name="username"
                            class="w-full bg-gray-700 px-4 py-2 rounded-md focus:ring-2 ring-primary-400" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">New Password <span
                                class="text-gray-500 text-xs">(Leave blank to keep current)</span></label>
                        <input type="password" name="password"
                            class="w-full bg-gray-700 px-4 py-2 rounded-md focus:ring-2 ring-primary-400"
                            placeholder="Enter new password">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">Role</label>
                        <select id="editRole" name="role"
                            class="w-full bg-gray-700 px-4 py-2 rounded-md text-white focus:ring-2 ring-primary-400">
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
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeModal()"
                        class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-md transition">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-md shadow transition flex items-center">
                        <i class="fas fa-save mr-2"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script>
        document.getElementById('liveSearch').addEventListener('keyup', function () {
            let query = this.value;

            fetch('<?= base_url("superadmin/search_ajax") ?>?search=' + encodeURIComponent(query))
                .then(response => response.text())
                .then(data => {
                    document.getElementById('resultsTable').innerHTML = data;
                })
                .catch(error => console.error('Error:', error));
        });
    </script>
    <script>
        const socket = new WebSocket('ws://localhost:8080'); // Connect to your WebSocket server

        socket.onopen = function () {
            console.log("Connected to WebSocket server");
        };

        socket.onmessage = function (event) {
            const message = JSON.parse(event.data);
            if (message.status === 'success' && message.action === 'delete_all') {
                alert(message.message);  // Alert the user or update the UI accordingly
                // Optionally update the UI (e.g., clear the queue list) here
            }
        };

        socket.onerror = function (error) {
            console.error("WebSocket error:", error);
        };

        socket.onclose = function () {
            console.log("Disconnected from WebSocket server");
        };
    </script>
</body>

</html>