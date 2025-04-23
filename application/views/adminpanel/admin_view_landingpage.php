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
    <link rel="stylesheet"
        href="<?php echo base_url('assets/css/admin_view_landingpage.css?v=' . filemtime('assets/css/admin_view_landingpage.css')); ?>">
    <link rel="stylesheet"
        href="<?php echo base_url('assets/css/darkmode_landing.css?v=' . filemtime('assets/css/darkmode_landing.css')); ?>">
    <!-- BOXICONS-->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <!-- SIDE NAVBAR -->
    <nav class="sidebar close">
        <header>
            <div class="image-text">
                <span class="image">
                    <?php if (!empty($user['uploaded_profile_image'])): ?>
                        <img class="profile-image" src="<?= base_url($user['uploaded_profile_image']); ?>"
                            alt="Uploaded Profile Image">
                    <?php else: ?>
                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/b6/Image_created_with_a_mobile_phone.png/1200px-Image_created_with_a_mobile_phone.png"
                            alt="logo">
                    <?php endif; ?>
                </span>

                <div class="text header-text">
                    <span class="name"></span>
                    <?php if ($this->session->userdata('logged_in')): ?>
                        <span class="profession">Welcome,
                            <?= htmlspecialchars($this->session->userdata('username')); ?>!</span>
                    <?php else: ?>
                        <span class="text-lg font-semibold">Guest</span>
                    <?php endif; ?>
                </div>
            </div>

            <i class='bx bx-chevron-right toggle'></i>
        </header>
        <div class="menu-bar">
            <div class="menu">
                <li class="search-box flex items-center gap-2 bg-gray-100 rounded-lg p-2">
                    <i class='bx bx-search text-gray-600 icon'></i>
                    <input
                        class="text-left text-sm font-semibold text-center tracking-wider bg-transparent outline-none"
                        type="search" id="search-bar" placeholder="SEARCH" oninput="filterData()">
                </li>

                <li class="nav-link">
                    <a href="<?= base_url('controller_admin_landing/loadView/LandTax'); ?>" 
                       class="sidebar-link <?= $active_view == 'LandTax' ? 'active' : ''; ?>">
                        <i class='bx bx-home-alt icon'></i>
                        <span class="text nav-text">Landtax</span>
                    </a>
                </li>

                <li class="nav-link">
                    <a href="<?= base_url('controller_admin_landing/loadView/BackRoom'); ?>" 
                       class="sidebar-link <?= $active_view == 'BackRoom' ? 'active' : ''; ?>">
                        <i class='bx bx-briefcase icon'></i>
                        <span class="text nav-text">Backroom</span>
                    </a>
                </li>
                
                <li class="nav-link">
                    <a href="<?= base_url('controller_admin_landing/loadView/Examiners'); ?>" 
                       class="sidebar-link <?= $active_view == 'Examiners' ? 'active' : ''; ?>">
                        <i class='bx bx-credit-card icon'></i>
                        <span class="text nav-text">Examiners</span>
                    </a>
                </li>

                <li class="nav-link">
                    <a href="<?= base_url('controller_admin_landing/loadView/BusinessTax'); ?>" 
                       class="sidebar-link <?= $active_view == 'BusinessTax' ? 'active' : ''; ?>">
                        <i class='bx bx-cart icon'></i>
                        <span class="text nav-text">Businesstax</span>
                    </a>
                </li>

                <li class="nav-link">
                    <a href="<?= base_url('controller_admin_landing/loadView/Payment'); ?>" 
                       class="sidebar-link <?= $active_view == 'Payment' ? 'active' : ''; ?>">
                        <i class='bx bx-credit-card icon'></i>
                        <span class="text nav-text">Payment</span>
                    </a>
                </li>

                <li class="nav-link">
                    <a href="<?= base_url('controller_admin_landing/loadView/FireProtection'); ?>" 
                       class="sidebar-link <?= $active_view == 'FireProtection' ? 'active' : ''; ?>">
                        <i class='bx bx-credit-card icon'></i>
                        <span class="text nav-text">Fire Protection</span>
                    </a>
                </li>

                <li class="nav-link">
                    <a href="<?= base_url('controller_admin_landing/loadView/Releasing'); ?>" 
                       class="sidebar-link <?= $active_view == 'Releasing' ? 'active' : ''; ?>">
                        <i class='bx bx-credit-card icon'></i>
                        <span class="text nav-text">Releasing</span>
                    </a>
                </li>
                
            </div>

            <div class="bottom-content">
                <li class="">
                    <a onclick="checker()" href="<?php echo base_url('controller_admin_landing/logout'); ?>">
                        <i class='bx bx-log-out icon'></i>
                        <span class="text nav-text">Logout</span>
                    </a>
                </li>

                <li class="mode">
                    <div class="moon-sun">
                        <i class='bx bx-moon icon moon'></i>
                        <i class='bx bx-sun icon sun'></i>
                    </div>
                    <span class="mode-text text">Dark Mode</span>

                    <div class="toggle-switch">
                        <span class="switch"></span>
                    </div>
                </li>
            </div>
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

                <?php if (isset($content)) echo $content; ?>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const sidebar = document.querySelector(".sidebar");
            const toggle = document.querySelector(".toggle");
            const modeSwitch = document.querySelector(".toggle-switch");
            const modeText = document.querySelector(".mode-text");

            // Function to toggle dark mode
            function toggleDarkMode() {
                document.body.classList.toggle("dark");

                if (document.body.classList.contains("dark")) {
                    modeText.innerText = "Light Mode";
                    localStorage.setItem('darkMode', true);
                } else {
                    modeText.innerText = "Dark Mode";
                    localStorage.setItem('darkMode', false);
                }
            }

            // Sidebar toggle and state persistence
            toggle.addEventListener("click", () => {
                sidebar.classList.toggle("close");
                const sidebarState = sidebar.classList.contains("close") ? "closed" : "open";
                localStorage.setItem("sidebarState", sidebarState);
            });

            // Check and apply sidebar state on page load
            const sidebarState = localStorage.getItem("sidebarState");
            if (sidebarState === "open") {
                sidebar.classList.remove("close");
            } else {
                sidebar.classList.add("close");
            }

            // Dark mode toggle functionality
            modeSwitch.addEventListener("click", toggleDarkMode);

            // Set dark mode preference on page load
            const darkModePreference = localStorage.getItem('darkMode');
            if (darkModePreference === 'true') {
                document.body.classList.add("dark");
                modeText.innerText = "Light Mode";
            } else {
                document.body.classList.remove("dark");
                modeText.innerText = "Dark Mode";
            }
            
            // Add active class to current view in sidebar
            const activeView = '<?= $active_view ?>';
            if (activeView) {
                document.querySelectorAll('.sidebar-link').forEach(link => {
                    if (link.getAttribute('href').includes(activeView)) {
                        link.classList.add('active');
                    }
                });
            }
        });

        function checker() {
            var result = confirm('Are you sure na gusto mo kong iwan?');
            if (result == false) {
                event.preventDefault();
            }
        }
        
        function filterData() {
            const searchValue = document.getElementById('search-bar').value.toLowerCase();
            // Implement search functionality based on your needs
            console.log("Searching for:", searchValue);
            // You can add specific search implementation here
        }
    </script>
</body>
</html>