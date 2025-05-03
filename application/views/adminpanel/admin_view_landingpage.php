<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landing Page</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            --sidebar-width: 280px;
            --sidebar-collapsed-width: 80px;
            --transition-speed: 0.3s;
            --primary-color: #4f46e5;
            --primary-hover: #4338ca;
            --bg-sidebar: #ffffff;
            --bg-sidebar-dark: #1e1e2d;
            --text-color: #374151;
            --text-muted: #6b7280;
            --border-color: #e5e7eb;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f9fafb;
            transition: background-color var(--transition-speed);
            margin: 0;
            padding: 0;
        }

        body.dark {
            background-color: #111827;
            color: #f3f4f6;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            width: var(--sidebar-width);
            background-color: var(--bg-sidebar);
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            transition: all var(--transition-speed) ease;
            z-index: 100;
            display: flex;
            flex-direction: column;
        }

        body.dark .sidebar {
            background-color: var(--bg-sidebar-dark);
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.3);
        }

        .sidebar.close {
            width: var(--sidebar-collapsed-width);
        }

       

        body.dark .sidebar-header {
            border-color: #2d3748;
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: 1rem;
            overflow: hidden;
        }

        .logo-icon {
            min-width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(45deg, var(--primary-color), #818cf8);
            color: white;
            font-size: 1.2rem;
        }

        .logo-text {
            font-weight: 600;
            font-size: 1.1rem;
            color: var(--text-color);
            white-space: nowrap;
            opacity: 1;
            transition: opacity var(--transition-speed);
        }

        body.dark .logo-text {
            color: #f3f4f6;
        }

        .sidebar.close .logo-text {
            opacity: 0;
            pointer-events: none;
        }

        .toggle-btn {
            position: absolute;
            top: 50%;
            right: -10px;
            transform: translateY(-50%);
            width: 25px;
            height: 25px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            background-color: var(--primary-color);
            color: white;
            transition: all var(--transition-speed);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            z-index: 10;
        }

        body.dark .toggle-btn {
            background-color: var(--primary-color);
            color: white;
        }

        .sidebar.close .toggle-btn {
            transform: translateY(-50%) rotate(180deg);
        }

        .toggle-btn:hover {
            background-color: var(--primary-hover);
        }

        body.dark .toggle-btn:hover {
            background-color: var(--primary-hover);
        }

        /* Adjust the sidebar header to make space for the toggle button */
        .sidebar-header {
            padding: 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--border-color);
            height: 70px;
            position: relative;
            /* Added to make absolute positioning work properly */
        }

        .user-info {
            padding: 1.25rem 1rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            border-bottom: 1px solid var(--border-color);
        }

        body.dark .user-info {
            border-color: #2d3748;
        }

        .profile-image {
            min-width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #e5e7eb;
        }

        body.dark .profile-image {
            border-color: #4b5563;
        }

        .user-details {
            display: flex;
            flex-direction: column;
            white-space: nowrap;
            opacity: 1;
            transition: opacity var(--transition-speed);
        }

        .sidebar.close .user-details {
            opacity: 0;
            pointer-events: none;
        }

        .user-name {
            font-weight: 600;
            color: var(--text-color);
        }

        body.dark .user-name {
            color: #f3f4f6;
        }

        .user-role {
            font-size: 0.75rem;
            color: var(--text-muted);
        }

        body.dark .user-role {
            color: #9ca3af;
        }

        .search-container {
            padding: 1rem;
            position: relative;
        }

        .sidebar.close .search-container {
            padding: 1rem 0.5rem;
        }

        .search-wrapper {
            display: flex;
            align-items: center;
            background-color: #f3f4f6;
            border-radius: 8px;
            padding: 0.5rem 0.75rem;
            transition: all var(--transition-speed);
        }

        body.dark .search-wrapper {
            background-color: #374151;
        }

        .search-icon {
            min-width: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-muted);
        }

        .search-input {
            flex: 1;
            border: none;
            outline: none;
            background: transparent;
            padding: 0 0.5rem;
            color: var(--text-color);
            opacity: 1;
            transition: opacity var(--transition-speed);
        }

        body.dark .search-input {
            color: #e5e7eb;
        }

        .sidebar.close .search-input {
            opacity: 0;
            pointer-events: none;
        }

        .sidebar.close .search-wrapper {
            padding: 0.5rem;
            justify-content: center;
        }

        .menu-container {
            flex: 1;
            padding: 0.5rem 0;
        }

        .menu-section {
            margin-bottom: 1rem;
        }

        .menu-title {
            padding: 0.5rem 1.25rem;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            color: var(--text-muted);
            letter-spacing: 0.05em;
            opacity: 1;
            transition: opacity var(--transition-speed);
        }

        body.dark .menu-title {
            color: #9ca3af;
        }

        .sidebar.close .menu-title {
            opacity: 0;
            pointer-events: none;
        }

        .menu-items {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .menu-item {
            position: relative;
        }

        .menu-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.25rem;
            text-decoration: none;
            color: var(--text-color);
            transition: all var(--transition-speed);
            border-left: 3px solid transparent;
            gap: 1rem;
        }

        body.dark .menu-link {
            color: #e5e7eb;
        }

        .menu-link:hover {
            background-color: #f3f4f6;
        }

        body.dark .menu-link:hover {
            background-color: #2d3748;
        }

        .menu-link.active {
            background-color: #ede9fe;
            border-left-color: var(--primary-color);
            color: var(--primary-color);
        }

        body.dark .menu-link.active {
            background-color: #374151;
            border-left-color: var(--primary-color);
            color: #a5b4fc;
        }

        .menu-icon {
            min-width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            transition: all var(--transition-speed);
        }

        .menu-text {
            white-space: nowrap;
            opacity: 1;
            transition: opacity var(--transition-speed);
        }

        .sidebar.close .menu-text,
        .sidebar.close .dropdown-icon {
            opacity: 0;
            pointer-events: none;
            width: 0;
            display: none;
        }

        .sidebar.close .menu-link {
            padding: 0.75rem;
            justify-content: center;
        }

        .menu-tooltip {
            position: absolute;
            left: calc(100% + 10px);
            top: 50%;
            transform: translateY(-50%);
            background-color: #374151;
            color: white;
            padding: 0.5rem 0.75rem;
            border-radius: 6px;
            font-size: 0.75rem;
            white-space: nowrap;
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.2s;
            z-index: 10;
        }

        .menu-tooltip::before {
            content: '';
            position: absolute;
            left: -5px;
            top: 50%;
            transform: translateY(-50%);
            border-top: 5px solid transparent;
            border-bottom: 5px solid transparent;
            border-right: 5px solid #374151;
        }

        body.dark .menu-tooltip {
            background-color: #4b5563;
        }

        body.dark .menu-tooltip::before {
            border-right-color: #4b5563;
        }

        .sidebar.close .menu-item:hover .menu-tooltip {
            opacity: 1;
        }

        .bottom-container {
            padding: 1rem;
            border-top: 1px solid var(--border-color);
        }

        body.dark .bottom-container {
            border-color: #2d3748;
        }

        .mode-switch {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.5rem 0;
            margin-bottom: 1rem;
        }

        .mode-text {
            color: var(--text-color);
            font-size: 0.875rem;
            white-space: nowrap;
            opacity: 1;
            transition: opacity var(--transition-speed);
        }

        body.dark .mode-text {
            color: #e5e7eb;
        }

        .sidebar.close .mode-text {
            opacity: 0;
            pointer-events: none;
        }

        .toggle-switch {
            position: relative;
            width: 40px;
            height: 22px;
            border-radius: 25px;
            background-color: #cbd5e1;
            transition: all var(--transition-speed);
        }

        body.dark .toggle-switch {
            background-color: var(--primary-color);
        }

        .switch {
            position: absolute;
            top: 2px;
            left: 2px;
            height: 18px;
            width: 18px;
            border-radius: 50%;
            background-color: white;
            transition: all var(--transition-speed);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        body.dark .switch {
            left: calc(100% - 20px);
        }

        .sun-icon,
        .moon-icon {
            position: absolute;
            font-size: 0.875rem;
            color: #f59e0b;
        }

        .sun-icon {
            opacity: 1;
        }

        .moon-icon {
            opacity: 0;
        }

        body.dark .sun-icon {
            opacity: 0;
        }

        body.dark .moon-icon {
            opacity: 1;
            color: #f3f4f6;
        }

        .logout-btn {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            background-color: #fee2e2;
            color: #dc2626;
            text-decoration: none;
            transition: all var(--transition-speed);
        }

        body.dark .logout-btn {
            background-color: #7f1d1d;
            color: #fecaca;
        }

        .logout-btn:hover {
            background-color: #fecaca;
        }

        body.dark .logout-btn:hover {
            background-color: #991b1b;
        }

        .logout-icon {
            min-width: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logout-text {
            white-space: nowrap;
            opacity: 1;
            transition: opacity var(--transition-speed);
        }

        .sidebar.close .logout-text {
            opacity: 0;
            pointer-events: none;
        }

        .sidebar.close .logout-btn {
            justify-content: center;
            padding: 0.75rem;
        }

        .home {
            position: relative;
            left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
            transition: all var(--transition-speed) ease;
            padding: 20px;
            min-height: 100vh;
        }

        .sidebar.close~.home {
            left: var(--sidebar-collapsed-width);
            width: calc(100% - var(--sidebar-collapsed-width));
        }

        .menu-item.has-dropdown .dropdown-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height var(--transition-speed) ease;
        }

        .menu-item.has-dropdown.open .dropdown-content {
            max-height: 500px;
        }

        .dropdown-trigger {
            position: relative;
        }

        .dropdown-icon {
            position: absolute;
            right: 1.25rem;
            top: 50%;
            transform: translateY(-50%) rotate(0deg);
            transition: transform var(--transition-speed);
        }

        .sidebar.close .dropdown-icon {
            opacity: 0;
            pointer-events: none;
        }

        .menu-item.has-dropdown.open .dropdown-icon {
            transform: translateY(-50%) rotate(90deg);
        }

        .submenu-item .menu-link {
            padding-left: 3.5rem;
            font-size: 0.875rem;
        }

        .sidebar.close .submenu-item .menu-link {
            padding-left: 0.75rem;
            justify-content: center;
        }

        /* Loading indicator */
        .loading-spinner {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1000;
        }

        .spinner {
            width: 40px;
            height: 40px;
            border: 4px solid rgba(0, 0, 0, 0.1);
            border-radius: 50%;
            border-top-color: var(--primary-color);
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Responsive */
        @media screen and (max-width: 768px) {
            .sidebar {
                width: 0;
                left: -280px;
            }

            .sidebar.close {
                width: 0;
                left: -280px;
            }

            .sidebar.mobile-open {
                left: 0;
                width: var(--sidebar-width);
            }

            .home {
                left: 0;
                width: 100%;
            }

            .sidebar.close~.home,
            .sidebar.mobile-open~.home {
                left: 0;
                width: 100%;
            }

            .mobile-overlay {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 99;
                opacity: 0;
                visibility: hidden;
                transition: all var(--transition-speed);
            }

            .sidebar.mobile-open~.mobile-overlay {
                opacity: 1;
                visibility: visible;
            }

            .mobile-toggle {
                display: block;
                position: fixed;
                top: 20px;
                left: 20px;
                width: 40px;
                height: 40px;
                border-radius: 8px;
                background-color: var(--primary-color);
                color: white;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                z-index: 98;
                box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
            }

            body.dark .mobile-toggle {
                background-color: var(--bg-sidebar-dark);
                color: #e5e7eb;
            }
        }
    </style>
</head>

<body>
    <!-- Mobile Menu Toggle Button -->
    <div class="mobile-toggle">
        <i class='bx bx-menu'></i>
    </div>

    <!-- Mobile Overlay -->
    <div class="mobile-overlay"></div>

    <!-- Loading Spinner -->
    <div class="loading-spinner">
        <div class="spinner"></div>
    </div>

    <!-- SIDEBAR -->
    <nav class="sidebar">
        <!-- Sidebar Header with Logo -->
        <div class="sidebar-header">
            <div class="logo-container">
                <div class="logo-icon">
                    <i class='bx bx-cube-alt'></i>
                </div>
                <div class="logo-text">Admin Panel</div>
            </div>
            <div class="toggle-btn">
                <i class='bx bx-chevron-left'></i>
            </div>
        </div>

        <!-- User Profile Info -->
        <div class="user-info">
            <?php if (!empty($user['uploaded_profile_image'])): ?>
                <img class="profile-image" src="<?= base_url($user['uploaded_profile_image']); ?>" alt="Profile">
            <?php else: ?>
                <img class="profile-image" src="https://ui-avatars.com/api/?name=User&background=4f46e5&color=fff"
                    alt="Profile">
            <?php endif; ?>
            <div class="user-details">
                <div class="user-name">
                    <?php if ($this->session->userdata('logged_in')): ?>
                        <?= htmlspecialchars($this->session->userdata('username')); ?>
                    <?php else: ?>
                        Guest
                    <?php endif; ?>
                </div>
                <div class="user-role">Administrator</div>
            </div>
        </div>

        <!-- Search Box -->
        <div class="search-container">
            <div class="search-wrapper">
                <div class="search-icon">
                    <i class='bx bx-search'></i>
                </div>
                <input type="search" class="search-input" placeholder="Search..." id="search-bar">
            </div>
        </div>

        <!-- Menu Container -->
        <div class="menu-container">
            <!-- Main Menu Section -->
            <div class="menu-section">
                <div class="menu-title">Main Menu</div>
                <ul class="menu-items">
                    <li class="menu-item">
                        <a href="<?= base_url('controller_admin_landing/loadView/Dashboard'); ?>"
                            class="menu-link ajax-link <?= ($active_view == 'Dashboard') ? 'active' : ''; ?>"
                            data-view="Dashboard">
                            <div class="menu-icon">
                                <i class='bx bxs-dashboard'></i>
                            </div>
                            <span class="menu-text">Dashboard</span>
                        </a>
                        <div class="menu-tooltip">Dashboard</div>
                    </li>

                    <li class="menu-item has-dropdown" id="properties-dropdown">
                        <a href="javascript:void(0)" class="menu-link dropdown-trigger">
                            <div class="menu-icon">
                                <i class='bx bx-building-house'></i>
                            </div>
                            <span class="menu-text">Properties</span>
                            <i class='bx bx-chevron-right dropdown-icon'></i>
                        </a>
                        <div class="menu-tooltip">Properties</div>
                        <ul class="dropdown-content menu-items">
                            <li class="submenu-item">
                                <a href="<?= base_url('controller_admin_landing/loadView/LandTax'); ?>"
                                    class="menu-link ajax-link <?= ($active_view == 'LandTax') ? 'active' : ''; ?>"
                                    data-view="LandTax">
                                    <span class="menu-text">Land Tax</span>
                                </a>
                                <div class="menu-tooltip">Land Tax</div>
                            </li>
                            <li class="submenu-item">
                                <a href="<?= base_url('controller_admin_landing/loadView/BackRoom'); ?>"
                                    class="menu-link ajax-link <?= ($active_view == 'BackRoom') ? 'active' : ''; ?>"
                                    data-view="BackRoom">
                                    <span class="menu-text">Backroom</span>
                                </a>
                                <div class="menu-tooltip">Backroom</div>
                            </li>
                        </ul>
                    </li>

                    <li class="menu-item">
                        <a href="<?= base_url('controller_admin_landing/loadView/Examiners'); ?>"
                            class="menu-link ajax-link <?= ($active_view == 'Examiners') ? 'active' : ''; ?>"
                            data-view="Examiners">
                            <div class="menu-icon">
                                <i class='bx bx-analyse'></i>
                            </div>
                            <span class="menu-text">Examiners</span>
                        </a>
                        <div class="menu-tooltip">Examiners</div>
                    </li>

                    <li class="menu-item">
                        <a href="<?= base_url('controller_admin_landing/loadView/BusinessTax'); ?>"
                            class="menu-link ajax-link <?= ($active_view == 'BusinessTax') ? 'active' : ''; ?>"
                            data-view="BusinessTax">
                            <div class="menu-icon">
                                <i class='bx bx-store'></i>
                            </div>
                            <span class="menu-text">Business Tax</span>
                        </a>
                        <div class="menu-tooltip">Business Tax</div>
                    </li>
                </ul>
            </div>

            <!-- Transactions Menu Section -->
            <div class="menu-section">
                <div class="menu-title">Transactions</div>
                <ul class="menu-items">
                    <li class="menu-item has-dropdown" id="transactions-dropdown">
                        <a href="javascript:void(0)" class="menu-link dropdown-trigger">
                            <div class="menu-icon">
                                <i class='bx bx-transfer'></i>
                            </div>
                            <span class="menu-text">Transactions</span>
                            <i class='bx bx-chevron-right dropdown-icon'></i>
                        </a>
                        <div class="menu-tooltip">Transactions</div>
                        <ul class="dropdown-content menu-items">
                            <li class="submenu-item">
                                <a href="<?= base_url('controller_admin_landing/loadView/Payment'); ?>"
                                    class="menu-link ajax-link <?= ($active_view == 'Payment') ? 'active' : ''; ?>"
                                    data-view="Payment">
                                    <span class="menu-text">Payment</span>
                                </a>
                                <div class="menu-tooltip">Payment</div>
                            </li>
                            <li class="submenu-item">
                                <a href="<?= base_url('controller_admin_landing/loadView/FireProtection'); ?>"
                                    class="menu-link ajax-link <?= ($active_view == 'FireProtection') ? 'active' : ''; ?>"
                                    data-view="FireProtection">
                                    <span class="menu-text">Fire Protection</span>
                                </a>
                                <div class="menu-tooltip">Fire Protection</div>
                            </li>
                            <li class="submenu-item">
                                <a href="<?= base_url('controller_admin_landing/loadView/Releasing'); ?>"
                                    class="menu-link ajax-link <?= ($active_view == 'Releasing') ? 'active' : ''; ?>"
                                    data-view="Releasing">
                                    <span class="menu-text">Releasing</span>
                                </a>
                                <div class="menu-tooltip">Releasing</div>
                            </li>
                        </ul>
                    </li>

                    <li class="menu-item">
                        <a href="#" class="menu-link">
                            <div class="menu-icon">
                                <i class='bx bx-chart'></i>
                            </div>
                            <span class="menu-text">Reports</span>
                        </a>
                        <div class="menu-tooltip">Reports</div>
                    </li>

                    <li class="menu-item">
                        <a href="#" class="menu-link">
                            <div class="menu-icon">
                                <i class='bx bx-cog'></i>
                            </div>
                            <span class="menu-text">Settings</span>
                        </a>
                        <div class="menu-tooltip">Settings</div>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Bottom Container with Mode Toggle and Logout -->
        <div class="bottom-container">
            <div class="mode-switch">
                <span class="mode-text">Dark Mode</span>
                <div class="toggle-switch">
                    <span class="switch">
                        <i class='bx bx-sun sun-icon'></i>
                        <i class='bx bx-moon moon-icon'></i>
                    </span>
                </div>
            </div>

            <a href="<?php echo base_url('controller_admin_landing/logout'); ?>" class="logout-btn" onclick="checker()">
                <div class="logout-icon">
                    <i class='bx bx-log-out'></i>
                </div>
                <span class="logout-text">Logout</span>
            </a>
        </div>
    </nav>

    <section class="home">
        <!-- Main Content -->
        <div id="content-container" class="flex-1 px-6">
            <div id="content" class="">
                <?php if ($this->session->flashdata('success')): ?>
                    <div class="bg-green-200 text-green-800 p-3 rounded mb-4 text-center">
                        <?= $this->session->flashdata('success'); ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($content))
                    echo $content; ?>
            </div>
        </div>
    </section>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const sidebar = document.querySelector(".sidebar");
            const toggleBtn = document.querySelector(".toggle-btn");
            const mobileToggle = document.querySelector(".mobile-toggle");
            const mobileOverlay = document.querySelector(".mobile-overlay");
            const toggleSwitch = document.querySelector(".toggle-switch");
            const modeText = document.querySelector(".mode-text");
            const dropdownTriggers = document.querySelectorAll(".dropdown-trigger");
            const contentContainer = document.getElementById("content-container");
            const loadingSpinner = document.querySelector(".loading-spinner");

            // Function to toggle dark mode
            function toggleDarkMode() {
                document.body.classList.toggle("dark");

                if (document.body.classList.contains("dark")) {
                    modeText.innerText = "Light Mode";
                    localStorage.setItem('darkMode', 'true');
                } else {
                    modeText.innerText = "Dark Mode";
                    localStorage.setItem('darkMode', 'false');
                }
            }

            // Sidebar toggle functionality
            toggleBtn.addEventListener("click", () => {
                sidebar.classList.toggle("close");
                const sidebarState = sidebar.classList.contains("close") ? "closed" : "open";
                localStorage.setItem("sidebarState", sidebarState);
            });

            // Mobile sidebar toggle
            mobileToggle.addEventListener("click", () => {
                sidebar.classList.add("mobile-open");
            });

            mobileOverlay.addEventListener("click", () => {
                sidebar.classList.remove("mobile-open");
            });

            // Dark mode toggle
            toggleSwitch.addEventListener("click", toggleDarkMode);

            // Dropdown menu functionality
            dropdownTriggers.forEach(trigger => {
                trigger.addEventListener("click", (e) => {
                    const parent = trigger.parentElement;
                    parent.classList.toggle("open");

                    // Store dropdown state in localStorage
                    const dropdownId = parent.id;
                    if (dropdownId) {
                        const isOpen = parent.classList.contains("open");
                        localStorage.setItem(dropdownId, isOpen ? "open" : "closed");
                    }

                    // Close other open dropdowns
                    document.querySelectorAll(".menu-item.has-dropdown.open").forEach(item => {
                        if (item !== parent) {
                            item.classList.remove("open");
                            // Also update localStorage for other dropdowns
                            const itemId = item.id;
                            if (itemId) {
                                localStorage.setItem(itemId, "closed");
                            }
                        }
                    });
                });
            });

            // Apply saved sidebar state
            const sidebarState = localStorage.getItem("sidebarState");
            if (sidebarState === "open") {
                sidebar.classList.remove("close");
            } else if (sidebarState === "closed") {
                sidebar.classList.add("close");
            }

            // Apply saved dark mode preference
            const darkModePreference = localStorage.getItem('darkMode');
            if (darkModePreference === 'true') {
                document.body.classList.add("dark");
                modeText.innerText = "Light Mode";
            } else {
                document.body.classList.remove("dark");
                modeText.innerText = "Dark Mode";
            }

            // Apply saved dropdown states
            document.querySelectorAll('.menu-item.has-dropdown').forEach(dropdown => {
                const dropdownId = dropdown.id;
                if (dropdownId) {
                    const savedState = localStorage.getItem(dropdownId);
                    if (savedState === "open") {
                        dropdown.classList.add('open');
                    } else if (savedState === "closed") {
                        dropdown.classList.remove('open');
                    }
                }
            });

            // Add active class to parent dropdown item if submenu item is active
            document.querySelectorAll('.submenu-item .menu-link.active').forEach(activeLink => {
                const dropdownParent = activeLink.closest('.has-dropdown');
                if (dropdownParent) {
                    dropdownParent.classList.add('open');
                    // Also update localStorage
                    const dropdownId = dropdownParent.id;
                    if (dropdownId) {
                        localStorage.setItem(dropdownId, "open");
                    }
                }
            });

            // Show tooltips on hover for collapsed sidebar
            const menuItems = document.querySelectorAll('.menu-item');
            menuItems.forEach(item => {
                item.addEventListener('mouseenter', () => {
                    if (sidebar.classList.contains('close')) {
                        const tooltip = item.querySelector('.menu-tooltip');
                        if (tooltip) {
                            tooltip.style.opacity = '1';
                        }
                    }
                });

                item.addEventListener('mouseleave', () => {
                    const tooltip = item.querySelector('.menu-tooltip');
                    if (tooltip) {
                        tooltip.style.opacity = '0';
                    }
                });
            });

            // Search functionality
            function filterData() {
                const searchValue = document.getElementById('search-bar').value.toLowerCase();
                const menuItems = document.querySelectorAll('.menu-item:not(.has-dropdown), .submenu-item');

                menuItems.forEach(item => {
                    const menuText = item.querySelector('.menu-text')?.textContent.toLowerCase();

                    if (menuText && menuText.includes(searchValue)) {
                        item.style.display = 'block';
                        // Open parent dropdown if this is a submenu item
                        const parentDropdown = item.closest('.has-dropdown');
                        if (parentDropdown && searchValue !== '') {
                            parentDropdown.classList.add('open');
                        }
                    } else {
                        // Don't hide dropdown parents
                        if (!item.classList.contains('has-dropdown')) {
                            item.style.display = searchValue === '' ? 'block' : 'none';
                        }
                    }
                });

                // If search is cleared, close all dropdowns
                if (searchValue === '') {
                    document.querySelectorAll('.menu-item.has-dropdown.open').forEach(item => {
                        if (!item.querySelector('.submenu-item .menu-link.active')) {
                            item.classList.remove('open');
                        }
                    });
                }
            }

            // Add search functionality
            document.getElementById('search-bar').addEventListener('input', filterData);

            // AJAX navigation - prevent page reload and fetch content via AJAX
            document.querySelectorAll('.ajax-link').forEach(link => {
                link.addEventListener('click', function (e) {
                    e.preventDefault(); // Prevent default link behavior

                    const url = this.getAttribute('href');
                    const view = this.getAttribute('data-view');

                    // Update active class
                    document.querySelectorAll('.menu-link').forEach(menuLink => {
                        menuLink.classList.remove('active');
                    });
                    this.classList.add('active');

                    // Show loading spinner
                    loadingSpinner.style.display = 'block';

                    // Make AJAX request
                    fetch(url)
                        .then(response => response.text())
                        .then(html => {
                            // Update browser URL without reloading
                            history.pushState({ view: view }, view, url);

                            // Extract content from response
                            const parser = new DOMParser();
                            const doc = parser.parseFromString(html, 'text/html');
                            const content = doc.getElementById('content');

                            if (content) {
                                document.getElementById('content').innerHTML = content.innerHTML;
                            } else {
                                document.getElementById('content').innerHTML = html;
                            }

                            // Hide loading spinner
                            loadingSpinner.style.display = 'none';
                        })
                        .catch(error => {
                            console.error('Error loading page:', error);
                            loadingSpinner.style.display = 'none';

                            // Show error message
                            Toastify({
                                text: "Error loading page. Please try again.",
                                duration: 3000,
                                close: true,
                                gravity: "top",
                                position: "right",
                                backgroundColor: "#f44336",
                            }).showToast();
                        });
                });
            });

            // Handle browser back/forward buttons
            window.addEventListener('popstate', function (event) {
                if (event.state && event.state.view) {
                    const link = document.querySelector(`.ajax-link[data-view="${event.state.view}"]`);
                    if (link) {
                        link.click();
                    }
                }
            });
        });

        // Confirmation dialog before logout
        function checker() {
            var result = confirm('Are you sure you want to logout?');
            if (result == false) {
                event.preventDefault();
            }
        }
    </script>