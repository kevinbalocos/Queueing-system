<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMIN LANDING PAGE</title>
    <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

        * {
            font-family: 'Poppins', sans-serif;
        }

        ::selection {
            background: linear-gradient(145deg, #a1ffce, #05eebb, #025240);
            color: #003d28;
            text-shadow: 0 0 10px rgba(86, 209, 183, 0.7);
        }

        ::-moz-selection {
            background: linear-gradient(145deg, #a1ffce, #05eebb, #025846);
            color: #003d28;
            text-shadow: 0 0 10px rgba(6, 61, 49, 0.7);
        }

        ::-webkit-scrollbar {
            width: 5px;
        }

        ::-webkit-scrollbar-thumb {
            background-color: #66cdaa;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-track {
            background-color: #66cdaa;
        }
    </style>
    <script>


        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#1E3A8A',
                        secondary: '#1E40AF',
                        accent: '#FACC15'
                    }
                }
            }
        }
    </script>
</head>


<body class="bg-gray-100 flex min-h-screen">
    <!-- Sidebar -->
    <div id="sidebar"
        class="w-56 bg-white text-black min-h-screen p-4 transition-all duration-300 shadow-lg rounded-r-lg relative">
        <h2 class="text-xl font-semibold tracking-wide text-primary mb-4">Admin Panel</h2>

        <ul class="space-y-2">
            <li>
                <a href="<?= base_url('controller_admin_landing/loadView/LandTax'); ?>" class="flex items-center p-2 text-gray-600 rounded-md hover:bg-blue-50 transition 
       <?= (uri_string() == 'controller_admin_landing/loadView/LandTax') ? 'bg-blue-100 text-blue-900' : '' ?>">
                    <span
                        class="material-icons-outlined text-base mr-2 
            <?= (uri_string() == 'controller_admin_landing/loadView/LandTax') ? 'text-blue-900' : 'text-primary' ?>">account_balance</span>
                    <span
                        class="text-sm font-medium <?= (uri_string() == 'controller_admin_landing/loadView/LandTax') ? 'text-blue-900' : '' ?>">Land
                        Tax</span>
                </a>
            </li>

            <li>
                <a href="<?= base_url('controller_admin_landing/loadView/BackRoom'); ?>" class="flex items-center p-2 text-gray-600 rounded-md hover:bg-blue-50 transition 
       <?= (uri_string() == 'controller_admin_landing/loadView/BackRoom') ? 'bg-blue-100 text-blue-900' : '' ?>">
                    <span
                        class="material-icons-outlined text-base mr-2 
            <?= (uri_string() == 'controller_admin_landing/loadView/BackRoom') ? 'text-blue-900' : 'text-primary' ?>">meeting_room</span>
                    <span
                        class="text-sm font-medium <?= (uri_string() == 'controller_admin_landing/loadView/BackRoom') ? 'text-blue-900' : '' ?>">Backroom</span>
                </a>
            </li>

            <li>
                <a href="<?= base_url('controller_admin_landing/loadView/Examiners'); ?>" class="flex items-center p-2 text-gray-600 rounded-md hover:bg-blue-50 transition 
       <?= (uri_string() == 'controller_admin_landing/loadView/Examiners') ? 'bg-blue-100 text-blue-900' : '' ?>">
                    <span
                        class="material-icons-outlined text-base mr-2 
            <?= (uri_string() == 'controller_admin_landing/loadView/Examiners') ? 'text-blue-900' : 'text-primary' ?>">fact_check</span>
                    <span
                        class="text-sm font-medium <?= (uri_string() == 'controller_admin_landing/loadView/Examiners') ? 'text-blue-900' : '' ?>">Examiners</span>
                </a>
            </li>

            <li>
                <a href="<?= base_url('controller_admin_landing/loadView/BusinessTax'); ?>" class="flex items-center p-2 text-gray-600 rounded-md hover:bg-blue-50 transition 
       <?= (uri_string() == 'controller_admin_landing/loadView/BusinessTax') ? 'bg-blue-100 text-blue-900' : '' ?>">
                    <span
                        class="material-icons-outlined text-base mr-2 
            <?= (uri_string() == 'controller_admin_landing/loadView/BusinessTax') ? 'text-blue-900' : 'text-primary' ?>">store</span>
                    <span
                        class="text-sm font-medium <?= (uri_string() == 'controller_admin_landing/loadView/BusinessTax') ? 'text-blue-900' : '' ?>">Business
                        Tax</span>
                </a>
            </li>

            <li>
                <a href="<?= base_url('controller_admin_landing/loadView/Payment'); ?>" class="flex items-center p-2 text-gray-600 rounded-md hover:bg-blue-50 transition 
       <?= (uri_string() == 'controller_admin_landing/loadView/Payment') ? 'bg-blue-100 text-blue-900' : '' ?>">
                    <span
                        class="material-icons-outlined text-base mr-2 
            <?= (uri_string() == 'controller_admin_landing/loadView/Payment') ? 'text-blue-900' : 'text-primary' ?>">payment</span>
                    <span
                        class="text-sm font-medium <?= (uri_string() == 'controller_admin_landing/loadView/Payment') ? 'text-blue-900' : '' ?>">Payment</span>
                </a>
            </li>

            <li>
                <a href="<?= base_url('controller_admin_landing/loadView/fireprotection'); ?>" class="flex items-center p-2 text-gray-600 rounded-md hover:bg-blue-50 transition 
       <?= (uri_string() == 'controller_admin_landing/loadView/fireprotection') ? 'bg-blue-100 text-blue-900' : '' ?>">
                    <span
                        class="material-icons-outlined text-base mr-2 
            <?= (uri_string() == 'controller_admin_landing/loadView/fireprotection') ? 'text-blue-900' : 'text-primary' ?>">local_fire_department</span>
                    <span
                        class="text-sm font-medium <?= (uri_string() == 'controller_admin_landing/loadView/fireprotection') ? 'text-blue-900' : '' ?>">Fire
                        Protection</span>
                </a>
            </li>

            <li>
                <a href="<?= base_url('controller_admin_landing/loadView/Releasing'); ?>" class="flex items-center p-2 text-gray-600 rounded-md hover:bg-blue-50 transition 
       <?= (uri_string() == 'controller_admin_landing/loadView/Releasing') ? 'bg-blue-100 text-blue-900' : '' ?>">
                    <span
                        class="material-icons-outlined text-base mr-2 
            <?= (uri_string() == 'controller_admin_landing/loadView/Releasing') ? 'text-blue-900' : 'text-primary' ?>">publish</span>
                    <span
                        class="text-sm font-medium <?= (uri_string() == 'controller_admin_landing/loadView/Releasing') ? 'text-blue-900' : '' ?>">Releasing</span>
                </a>
            </li>

        </ul>

        <!-- Sidebar Toggle Button -->
        <button id="toggleSidebar"
            class="absolute -right-4 top-6 w-8 h-8 flex items-center justify-center bg-primary text-white rounded-full shadow-md transition">
            <span id="toggleIcon" class="material-icons-outlined text-sm">chevron_left</span>
        </button>
    </div>

    <!-- Main Content -->
    <div class="flex-1 p-6">
        <div id="content" class="bg-white p-4 shadow-md rounded-md">
            <?php if ($this->session->flashdata('success')): ?>
  <div class="bg-green-200 text-green-800 p-3 rounded mb-4 text-center">
    <?= $this->session->flashdata('success'); ?>
  </div>
<?php endif; ?>

            <?php if (isset($content))
                echo $content; ?>
        </div>
    </div>

    <!-- Google Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">

    <script>
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('toggleSidebar');
        const toggleIcon = document.getElementById('toggleIcon');

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-64');
            toggleIcon.textContent = sidebar.classList.contains('-translate-x-64') ? 'chevron_right' : 'chevron_left';
        });
    </script>
    

</body>


</html>