<?php

// Determine how many items go to the left section (maximum 20)

$currentUser = isset($_SESSION['username']) ? $_SESSION['username'] : '';

// $currentUser = isset($_SESSION['user_id']) ? strval($_SESSION['user_id']) : ''; // Ensure it's a string
?>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- BOXICONS-->


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
                    <span class="name">
                    </span>

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
                    <a href="#" class="sidebar-link" data-view="LandTax">
                        <i class='bx bx-home-alt icon'></i>
                        <span class="text nav-text">Landtax</span>
                    </a>
                </li>

                <li class="nav-link">
                    <a href="#" class="sidebar-link" data-view="BackRoom">
                        <i class='bx bx-briefcase icon'></i>
                        
                        <span class="text nav-text">Backroom</span>
                    </a>
                </li>
                <li class="nav-link">
                    <a href="#" class="sidebar-link" data-view="Examiners">
                        <i class='bx bx-credit-card icon'></i>
                        <span class="text nav-text">Exam
                            iners</span>
                    </a>
                </li>

                <li class="nav-link">
                    <a href="#" class="sidebar-link" data-view="BusinessTax">
                        <i class='bx bx-cart icon'></i>
                        <span class="text nav-text">Businesstax</span>
                    </a>
                </li>


                <li class="nav-link">
                    <a href="#" class="sidebar-link" data-view="Payment">
                        <i class='bx bx-credit-card icon'></i>
                        <span class="text nav-text">Payment</span>
                    </a>
                </li>

                <li class="nav-link">
                    <a href="#" class="sidebar-link" data-view="FireProtection">
                        <i class='bx bx-credit-card icon'></i>
                        <span class="text nav-text">Fire Protection</span>
                    </a>
                </li>

                <li class="nav-link">
                    <a href="#" class="sidebar-link" data-view="Releasing">
                        <i class='bx bx-credit-card icon'></i>
                        <span class="text nav-text">Releasing</span>
                    </a>
                </li>


            </div>

            <div class="bottom-content">

                <li class="">
                    <a onclick="checker()" href="<?php echo base_url('Home/logout'); ?>">
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

                <?php if (isset($content))
                    echo $content; ?>
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
        });







        function checker() {
            var result = confirm('Are you sure na gusto mo kong iwan?');
            if (result == false) {
                event.preventDefault();
            }
        }



    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
    <script>
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('toggleSidebar');
        const toggleIcon = document.getElementById('toggleIcon');

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-64');
            toggleIcon.textContent = sidebar.classList.contains('-translate-x-64') ? 'chevron_right' : 'chevron_left';
        });
    </script>
    <script>
        $(document).ready(function () {
            // Function to load the page using AJAX
            function loadPage(view, updateUrl = true) {
                $.ajax({
                    url: "<?= base_url('controller_admin_landing/loadView/'); ?>" + view,
                    type: "GET",
                    dataType: "html",
                    beforeSend: function () {
                        // Get the current mode (light or dark)
                        const isDarkMode = document.body.classList.contains('dark');

                        // Update the loading screen
                        $("#content").html(`
                <div class="flex items-center justify-center w-full h-screen absolute top-0 left-0 z-50 ${isDarkMode ? 'bg-body-color/90' : 'bg-gray-50/90'}">
                    <div class="flex flex-col items-center justify-center space-y-4 p-6 rounded-lg shadow-lg ${isDarkMode ? 'bg-sidebar-color' : 'bg-white'}">
                        <div class="animate-spin border-t-4 ${isDarkMode ? 'border-primary-color' : 'border-primary-color'} w-16 h-16 border-solid rounded-full"></div>
                        <p class="${isDarkMode ? 'text-text-light-color' : 'text-text-color'} text-xl font-semibold">Loading...</p>
                    </div>
                </div>
            `);
                    },
                    success: function (response) {
                        $("#content").html(response);

                        // Update the URL without reloading the page
                        if (updateUrl) {
                            history.pushState({ view: view }, "", "<?= base_url('admin/'); ?>" + view);
                        }
                    },
                    error: function () {
                        $("#content").html('<div class="text-red-500">Failed to load content.</div>');
                    }
                });
            }



            // Prevent the default link behavior and load the corresponding content
            $(".sidebar-link").click(function (e) {
                e.preventDefault(); // Prevent page refresh
                let view = $(this).data("view"); // Get the view name from the data-view attribute
                loadPage(view); // Load the content dynamically
            });

            // Handle browser back/forward navigation
            window.onpopstate = function (event) {
                if (event.state && event.state.view) {
                    loadPage(event.state.view, false);
                }
            };

            // Load the correct page on refresh if a state exists
            let currentView = window.location.pathname.split("/").pop();
            if (currentView) {
                loadPage(currentView, false);
            }
        });

    </script>



</body>

</html>