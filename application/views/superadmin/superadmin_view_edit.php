<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit User - Super Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
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
    </script>
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
                        <h1 class="text-xl font-bold tracking-wide text-white">Super Admin</h1>
                    </div>
                </div>
                
                <!-- Navigation -->
                <nav class="flex-1 p-6 space-y-1 overflow-y-auto">
                    <a href="<?= base_url('SuperAdmin/dashboard'); ?>" class="flex items-center px-4 py-3 text-gray-300 hover:bg-primary-600 hover:text-white rounded-md transition group">
                        <i class="fas fa-tachometer-alt w-5 text-primary-500 group-hover:text-white"></i>
                        <span class="ml-3">Dashboard</span>
                    </a>
                    <a href="<?= base_url('SuperAdmin/user_management'); ?>" class="flex items-center px-4 py-3 bg-primary-700 rounded-md transition group">
                        <i class="fas fa-users w-5 text-white"></i>
                        <span class="ml-3 font-medium">User Management</span>
                    </a>
                    <a href="<?= base_url('SuperAdmin/logs'); ?>" class="flex items-center px-4 py-3 text-gray-300 hover:bg-primary-600 hover:text-white rounded-md transition group">
                        <i class="fas fa-clipboard-list w-5 text-primary-500 group-hover:text-white"></i>
                        <span class="ml-3">Logs</span>
                    </a>
                    <a href="<?= base_url('SuperAdmin/settings'); ?>" class="flex items-center px-4 py-3 text-gray-300 hover:bg-primary-600 hover:text-white rounded-md transition group">
                        <i class="fas fa-cog w-5 text-primary-500 group-hover:text-white"></i>
                        <span class="ml-3">Settings</span>
                    </a>
                </nav>
                
                <!-- User Profile -->
                <div class="p-6 border-t border-gray-700">
                    <div class="flex items-center space-x-3">
                        <div class="bg-gray-700 rounded-full p-2">
                            <i class="fas fa-user text-gray-300"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium"><?= $this->session->userdata('username'); ?></p>
                            <a href="<?= base_url('SuperAdmin/logout'); ?>" class="text-sm text-red-400 hover:text-red-300 flex items-center">
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
                        <button onclick="document.getElementById('sidebar').classList.toggle('hidden'); document.getElementById('sidebar').classList.toggle('block');" class="md:hidden text-gray-300 hover:text-white focus:outline-none">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <h1 class="ml-4 md:ml-0 text-lg font-semibold">Edit User</h1>
                    </div>
                    
                    <div class="flex items-center space-x-4">
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
                        <h2 class="text-2xl font-bold">Edit User</h2>
                        <p class="text-gray-400 mt-1">Modify user account details</p>
                    </div>
                    <a href="<?= base_url('SuperAdmin/user_management'); ?>" 
                        class="mt-4 md:mt-0 flex items-center px-4 py-2 bg-gray-700 hover:bg-gray-600 rounded-md text-white font-medium transition">
                        <i class="fas fa-arrow-left mr-2"></i> Back to Users
                    </a>
                </div>

                <!-- Flash Messages -->
                <?php if ($this->session->flashdata('success')): ?>
                    <div class="bg-green-600/20 border border-green-600/30 text-green-300 px-4 py-3 rounded-lg shadow-inner mb-6">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle mr-3 text-green-400"></i>
                            <span><?= $this->session->flashdata('success'); ?></span>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if ($this->session->flashdata('error')): ?>
                    <div class="bg-red-600/20 border border-red-600/30 text-red-300 px-4 py-3 rounded-lg shadow-inner mb-6">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle mr-3 text-red-400"></i>
                            <span><?= $this->session->flashdata('error'); ?></span>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Edit User Form -->
                <div class="bg-dark-800 rounded-lg shadow-lg p-6 border border-gray-700">
                    <div class="border-b border-gray-700 pb-4 mb-6">
                        <div class="flex items-center">
                            <div class="bg-primary-500/20 text-primary-500 rounded-full p-3 mr-4">
                                <i class="fas fa-user-edit text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold">User Details</h3>
                                <p class="text-gray-400 text-sm">ID: <?= $user->id ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <form action="<?= base_url('SuperAdmin/update_user'); ?>" method="post" class="space-y-6">
                        <input type="hidden" name="user_id" value="<?= $user->id ?>">
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Username Field -->
                            <div>
                                <label class="block text-sm font-medium text-gray-400 mb-1">Username</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-500">
                                        <i class="fas fa-user"></i>
                                    </span>
                                    <input type="text" name="username" value="<?= htmlspecialchars($user->username) ?>" required
                                        class="w-full bg-gray-700 pl-10 px-4 py-2 rounded-md focus:ring-2 ring-primary-400" />
                                </div>
                            </div>
                            
                            <!-- Password Field -->
                            <div>
                                <label class="block text-sm font-medium text-gray-400 mb-1">
                                    Password
                                    <span class="text-gray-500 text-xs">(Leave blank to keep current)</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-500">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input type="password" name="password" placeholder="Enter new password"
                                        class="w-full bg-gray-700 pl-10 px-4 py-2 rounded-md focus:ring-2 ring-primary-400 placeholder-gray-500" />
                                </div>
                            </div>
                            
                            <!-- Role Field -->
                            <div>
                                <label class="block text-sm font-medium text-gray-400 mb-1">Role</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-500">
                                        <i class="fas fa-id-badge"></i>
                                    </span>
                                    <select name="role" class="w-full bg-gray-700 pl-10 px-4 py-2 rounded-md text-white focus:ring-2 ring-primary-400 appearance-none">
                                        <option value="admin" <?= $user->role == 'admin' ? 'selected' : '' ?>>Admin</option>
                                        <option value="superadmin" <?= $user->role == 'superadmin' ? 'selected' : '' ?>>Super Admin</option>
                                        <option value="landtax" <?= $user->role == 'landtax' ? 'selected' : '' ?>>Land Tax</option>
                                        <option value="releasing" <?= $user->role == 'releasing' ? 'selected' : '' ?>>Releasing</option>
                                        <option value="payment" <?= $user->role == 'payment' ? 'selected' : '' ?>>Payment</option>
                                        <option value="backroom" <?= $user->role == 'backroom' ? 'selected' : '' ?>>Backroom</option>
                                        <option value="examiners" <?= $user->role == 'examiners' ? 'selected' : '' ?>>Examiners</option>
                                        <option value="businesstax" <?= $user->role == 'businesstax' ? 'selected' : '' ?>>Business Tax</option>
                                        <option value="fireprotection" <?= $user->role == 'fireprotection' ? 'selected' : '' ?>>Fire Protection</option>
                                    </select>
                                    <span class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-500">
                                        <i class="fas fa-chevron-down"></i>
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Status Field -->
                            <div>
                                <label class="block text-sm font-medium text-gray-400 mb-1">Status</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-500">
                                        <i class="fas fa-toggle-on"></i>
                                    </span>
                                    <select name="status" class="w-full bg-gray-700 pl-10 px-4 py-2 rounded-md text-white focus:ring-2 ring-primary-400 appearance-none">
                                        <option value="active" <?= isset($user->status) && $user->status == 'active' ? 'selected' : '' ?>>Active</option>
                                        <option value="inactive" <?= isset($user->status) && $user->status == 'inactive' ? 'selected' : '' ?>>Inactive</option>
                                        <option value="locked" <?= isset($user->status) && $user->status == 'locked' ? 'selected' : '' ?>>Locked</option>
                                    </select>
                                    <span class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-500">
                                        <i class="fas fa-chevron-down"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Additional Information Section -->
                        <div class="border-t border-gray-700 pt-6">
                            <h4 class="text-md font-medium mb-4">Additional Information</h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Full Name Field -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-400 mb-1">Full Name</label>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-500">
                                            <i class="fas fa-id-card"></i>
                                        </span>
                                        <input type="text" name="full_name" value="<?= isset($user->full_name) ? htmlspecialchars($user->full_name) : '' ?>"
                                            class="w-full bg-gray-700 pl-10 px-4 py-2 rounded-md focus:ring-2 ring-primary-400" />
                                    </div>
                                </div>
                                
                                <!-- Email Field -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-400 mb-1">Email</label>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-500">
                                            <i class="fas fa-envelope"></i>
                                        </span>
                                        <input type="email" name="email" value="<?= isset($user->email) ? htmlspecialchars($user->email) : '' ?>"
                                            class="w-full bg-gray-700 pl-10 px-4 py-2 rounded-md focus:ring-2 ring-primary-400" />
                                    </div>
                                </div>
                                
                                <!-- Contact Number Field -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-400 mb-1">Contact Number</label>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-500">
                                            <i class="fas fa-phone"></i>
                                        </span>
                                        <input type="text" name="contact" value="<?= isset($user->contact) ? htmlspecialchars($user->contact) : '' ?>"
                                            class="w-full bg-gray-700 pl-10 px-4 py-2 rounded-md focus:ring-2 ring-primary-400" />
                                    </div>
                                </div>
                                
                                <!-- Department Field -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-400 mb-1">Department</label>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-500">
                                            <i class="fas fa-building"></i>
                                        </span>
                                        <input type="text" name="department" value="<?= isset($user->department) ? htmlspecialchars($user->department) : '' ?>"
                                            class="w-full bg-gray-700 pl-10 px-4 py-2 rounded-md focus:ring-2 ring-primary-400" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Access Permissions (Optional) -->
                        <div class="border-t border-gray-700 pt-6">
                            <h4 class="text-md font-medium mb-4">Access Permissions</h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <?php 
                                // Define permissions - in a real application, these would come from your database
                                $permissions = [
                                    'view_dashboard' => 'View Dashboard',
                                    'manage_users' => 'Manage Users',
                                    'view_logs' => 'View Logs',
                                    'edit_settings' => 'Edit Settings',
                                    'view_reports' => 'View Reports',
                                    'create_records' => 'Create Records',
                                    'edit_records' => 'Edit Records',
                                    'delete_records' => 'Delete Records',
                                    'approve_transactions' => 'Approve Transactions'
                                ];
                                
                                // In a real application, you'd fetch the user's current permissions
                                $userPermissions = isset($user->permissions) ? json_decode($user->permissions, true) : [];
                                
                                foreach ($permissions as $key => $label):
                                ?>
                                <div class="flex items-center">
                                    <input type="checkbox" id="perm_<?= $key ?>" name="permissions[]" value="<?= $key ?>"
                                        <?= in_array($key, $userPermissions) ? 'checked' : '' ?>
                                        class="rounded bg-gray-700 border-gray-600 text-primary-600 focus:ring-primary-500" />
                                    <label for="perm_<?= $key ?>" class="ml-2 text-sm text-gray-300"><?= $label ?></label>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="border-t border-gray-700 pt-6 flex flex-col md:flex-row items-center justify-end space-y-4 md:space-y-0 md:space-x-4">
                            <button type="button" onclick="confirmReset()" class="w-full md:w-auto px-6 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-md transition flex items-center justify-center">
                                <i class="fas fa-undo mr-2"></i> Reset Form
                            </button>
                            
                            <a href="<?= base_url('SuperAdmin/user_management'); ?>" class="w-full md:w-auto px-6 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-md transition flex items-center justify-center">
                                <i class="fas fa-times mr-2"></i> Cancel
                            </a>
                            
                            <button type="submit" class="w-full md:w-auto px-6 py-2 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-md shadow transition flex items-center justify-center">
                                <i class="fas fa-save mr-2"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- Last Login Info -->
                <div class="mt-6 bg-dark-800 rounded-lg shadow-lg p-4 border border-gray-700">
                    <div class="flex items-center text-sm text-gray-400">
                        <i class="fas fa-clock mr-2"></i>
                        <span>Last login: <?= isset($user->last_login) ? date('F j, Y, g:i a', strtotime($user->last_login)) : 'Never' ?></span>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        function confirmReset() {
            if (confirm('Are you sure you want to reset the form? All unsaved changes will be lost.')) {
                window.location.reload();
            }
        }
    </script>
</body>

</html>