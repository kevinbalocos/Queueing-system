<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>viewHome</title>
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

    <script>
        //? Function to set the active link in the local storage
        function setActiveLink(linkName) {
            localStorage.setItem('activeLink', linkName);
        }

        //? Function to load the content based on the active link from the local storage
        function loadContent() {
            var activeLink = localStorage.getItem('activeLink');

            //?Add logic to load content based on the active link
            switch (activeLink) {
                case 'Home':
                    loadHomeContent();
                    break;
                case 'Order':
                    loadOrderContent();
                    break;
                case 'Notifications':
                    loadCLientNotification();
                    break;
                case 'Cart':
                    loadClientOrderContent();
                    break;
                case 'Profile':
                    loadProfileContent();
                    break;
            }
        }

        function handleLinkClick(linkName) {
            setActiveLink(linkName);
            loadContent();
        }

        //? Add an event listener to window.onload to load the content when the page loads
        window.onload = loadContent;
    </script>
    <style>

    </style>
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

                    <span class="profession">QUEUEING SYSTEM</span>

                </div>
            </div>


            <i class='bx bx-chevron-right toggle'></i>
        </header>
        <div class="menu-bar">
            <div class="menu">
                <li class="search-box">
                    <i class='bx bx-search-alt icon'></i>
                    <input class="text-left text-small font-semibold text-center tracking-wider" type="search"
                        id="search-bar" placeholder="SEARCH" oninput="filterData()">
                </li>
                <li class="nav-link">
                    <a href="<?= base_url('controller_admin_landing/loadView/LandTax'); ?>">
                        <i class='bx bx-home-alt icon'></i>
                        <span class="text nav-text">Landtax</span>
                    </a>
                </li>
                <li class="nav-link">
                    <a href="<?= base_url('controller_admin_landing/loadView/BackRoom'); ?>">
                        <i class='bx bx-purchase-tag icon'></i>
                        <span class="text nav-text">Backroom</span>
                    </a>
                </li>

                <li class="nav-link">
                    <a href="<?= base_url('controller_admin_landing/loadView/BusinessTax'); ?>">
                        <i class='bx bx-cart icon'></i>
                        <span class="text nav-text">Businesstax</span>
                    </a>
                </li>
                <li class="nav-link">
                    <a href="<?= base_url('controller_admin_landing/loadView/Payment'); ?>">
                        <i class='bx bxs-user-badge icon'></i>
                        <span class="text nav-text">Payment</span>
                    </a>
                </li>
                <li class="nav-link">
                    <a href="<?= base_url('controller_admin_landing/loadView/FireProtection'); ?>">
                        <i class='bx bxs-user-badge icon'></i>
                        <span class="text nav-text">Fire Protection</span>
                    </a>
                </li>

                <li class="nav-link">
                    <a href="<?= base_url('controller_admin_landing/loadView/Releasing'); ?>">
                        <i class='bx bxs-user-badge icon'></i>
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
    </section>

    <script>
        const body = document.querySelector("body"),
            sidebar = body.querySelector(".sidebar"),
            toggle = body.querySelector(".toggle"),
            searchBtn = body.querySelector(".search-box"),
            modeSwitch = body.querySelector(".toggle-switch"),
            modeText = body.querySelector(".mode-text");

        // Function to set the dark mode preference in local storage
        function setDarkModePreference(isDarkMode) {
            localStorage.setItem('darkMode', isDarkMode);
        }

        // Function to toggle dark mode
        function toggleDarkMode() {
            body.classList.toggle("dark");

            if (body.classList.contains("dark")) {
                modeText.innerText = "Light Mode";
                setDarkModePreference(true);
            } else {
                modeText.innerText = "Dark Mode";
                setDarkModePreference(false);
            }
        }

        toggle.addEventListener("click", () => {
            sidebar.classList.toggle("close");
        });

        modeSwitch.addEventListener("click", toggleDarkMode);

        // Check for dark mode preference in local storage on page load
        document.addEventListener("DOMContentLoaded", () => {
            const darkModePreference = localStorage.getItem('darkMode');
            if (darkModePreference === 'true') {
                body.classList.add("dark");
                modeText.innerText = "Light Mode";
            } else {
                body.classList.remove("dark");
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
            function loadPage(view, updateUrl = true) {
                $.ajax({
                    url: "<?= base_url('controller_admin_landing/loadView/'); ?>" + view,
                    type: "GET",
                    dataType: "html",
                    beforeSend: function () {
                        $("#content").html('<div class="text-center text-gray-600">Loading...</div>');
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

            // Handle sidebar clicks
            $(".sidebar-link").click(function () {
                let view = $(this).data("view");
                loadPage(view);
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