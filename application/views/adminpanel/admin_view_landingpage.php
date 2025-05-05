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
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap');

        * {
            font-family: 'Poppins';
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --sidebar-width: 280px;
            --sidebar-collapsed-width: 80px;
            --transition-speed: 0.3s;
            --primary-color: #0891b2;
            /* Changed to teal-600 */
            --primary-hover: #0e7490;
            /* Changed to teal-700 */
            --bg-sidebar: #ffffff;
            --bg-sidebar-dark: #1e293b;
            /* Changed to slate-800 */
            --text-color: #334155;
            /* Changed to slate-700 */
            --text-muted: #64748b;
            /* Changed to slate-500 */
            --border-color: #e2e8f0;
            /* Changed to slate-200 */
        }

        body.dark {
            --body-color: #0f172a;
            /* slate-900 */
            --sidebar-color: #1e293b;
            /* slate-800 */
            --primary-color: #f8fafc;
            /* slate-50 */
            --primary-color-light: #334155;
            /* slate-700 */
            --toggle-color: #0f172a;
            /* slate-900 */
            --text-color: #94a3b8;
            /* slate-400 */
            --text-light-color: #f1f5f9;
            /* slate-100 */
            --hover-color: #164e63;
            /* teal-800 */
            --table-header-color: #475569;
            /* slate-600 */
            --border-color: rgba(255, 255, 255, 0.1);
            --card-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
            --input-bg: #1e293b;
            /* slate-800 */
            --input-text: #f8fafc;
            /* slate-50 */
            --scrollbar-thumb: #334155;
            /* slate-700 */
            --scrollbar-track: #0f172a;
            /* slate-900 */
            --accent-color: #0ea5e9;
            /* sky-500 */

            background-color: #0f172a;
            /* slate-900 */
            color: #f3f4f6;
            /* gray-100 */
        }





        body.dark .user-info {
            border-color: #2d3748;
            /* gray-800 */
        }


        body.dark .profile-image {
            border-color: #4b5563;
            /* gray-600 */
        }

        body.dark .user-name {
            color: #f3f4f6;
            /* gray-100 */
        }

        .user-role {
            font-size: 0.75rem;
            color: var(--text-muted);
        }

        body.dark .user-role {
            color: #9ca3af;
            /* gray-400 */
        }



        body.dark .search-wrapper {
            background-color: #374151;
            /* gray-700 */
        }



        body.dark .search-input {
            color: #e5e7eb;
            /* gray-200 */
        }


        body.dark .menu-tooltip {
            background-color: #475569;
            /* slate-600 */
        }

        body.dark .menu-tooltip::before {
            border-right-color: #475569;
            /* slate-600 */
        }



        .home {
            position: relative;
            left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
            transition: all var(--transition-speed) ease;
            padding: 20px;
            min-height: 100vh;
            background-color: #f9fafb;
            /* gray-50 */
        }

        body.dark .home {
            background-color: #0f172a;
            /* slate-900 */
        }




        body.dark .spinner {
            border: 4px solid rgba(255, 255, 255, 0.1);
            border-top-color: #0ea5e9;
            /* sky-500 */
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Additional dark mode styles */
        body.dark ::-webkit-scrollbar {
            width: 10px;
        }

        body.dark ::-webkit-scrollbar-track {
            background: var(--scrollbar-track);
        }

        body.dark ::-webkit-scrollbar-thumb {
            background: var(--scrollbar-thumb);
            border-radius: 10px;
        }

        body.dark #queue-container {
            background-color: #0f172a;
            /* slate-900 */
        }

        body.dark h5 {
            color: var(--primary-color) !important;
        }

        body.dark .searcbox_darkmode {
            background-color: var(--sidebar-color) !important;
        }

        body.dark .switch::before {
            background: #475569 !important;
            /* slate-600 */
        }

        body.dark .hover_darkmode_gray:hover {
            background-color: var(--hover-color) !important;
            color: var(--text-light-color) !important;
        }

        body.dark .bg-white {
            background-color: var(--sidebar-color) !important;
            color: var(--text-light-color) !important;
        }

        body.dark span {
            background-color: transparent !important;
            border-color: var(--primary-color-light) !important;
        }

        body.dark .table tr,
        body.dark .table td,
        body.dark .table th,
        body.dark .darkmode_h1,
        body.dark li {
            background-color: var(--sidebar-color) !important;
            color: var(--text-light-color) !important;
            border-color: var(--primary-color-light) !important;
        }

        body.dark .darkmode_lines {
            border-color: var(--border-color);
        }

        body.dark td:hover {
            background-color: var(--hover-color) !important;
            color: var(--text-light-color) !important;
        }

        body.dark .right-section {
            background-color: var(--sidebar-color) !important;
            color: var(--text-light-color) !important;
        }

        body.dark .darkmode {
            background-color: var(--sidebar-color) !important;
            color: var(--text-light-color) !important;
        }

        body.dark .shadow-lg {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.6) !important;
        }

        body.dark .divide-gray-200 {
            border-color: var(--primary-color-light) !important;
        }

        body.dark .text-gray-600,
        body.dark .text-gray-500 {
            color: var(--text-color) !important;
        }

        body.dark h2 {
            color: var(--text-color) !important;
        }

        body.dark .text-gray-800 {
            color: var(--text-light-color) !important;
        }

        body.dark .hover\:bg-gray-50:hover {
            background-color: var(--hover-color) !important;
        }

        body.dark table tr th {
            background-color: var(--table-header-color) !important;
            color: var(--text-light-color) !important;
        }

        body.dark td {
            color: var(--text-light-color) !important;
            border-color: var(--primary-color-light) !important;
        }

        body.dark a {
            color: var(--primary-color) !important;
        }

        body.dark p,
        body.dark h3,
        body.dark .darkmode_green {
            color: var(--primary-color) !important;
        }

        body.dark a:hover {
            color: #22d3ee !important;
            /* cyan-400 */
        }

        body.dark button {
            color: var(--text-light-color) !important;
            background-color: var(--primary-color-light) !important;
            border: none;
        }

        body.dark .toggle {
            color: black !important;
        }

        body.dark button:hover {
            color: var(--primary-color) !important;
            background-color: #164e63 !important;
            /* teal-800 */
        }

        body.dark select,
        body.dark input[type="text"],
        body.dark input[type="number"],
        body.dark .darkmode_text,
        body.dark input[type="date"] {
            background-color: var(--input-bg) !important;
            color: var(--input-text) !important;
            border: 1px solid var(--primary-color-light) !important;
            padding: 0.5rem;
            border-radius: 0.375rem;
            box-shadow: none;
            transition: background-color 0.3s ease;
        }

        body.dark select:focus {
            outline: none;
            border-color: #0ea5e9 !important;
            /* sky-500 */
        }

        body.dark select option {
            background-color: var(--sidebar-color) !important;
            color: var(--text-light-color) !important;
        }

        body.dark select option:hover {
            background-color: var(--hover-color) !important;
            color: var(--text-light-color) !important;
        }

        /* Responsive styles */
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
                /* gray-200 */
            }
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f9fafb;
            transition: background-color var(--transition-speed);
            margin: 0;
            padding: 0;
        }

        body.dark {
            background-color: #0f172a;
            /* Changed to slate-900 */
            color: #f8fafc;
            /* Changed to slate-50 */
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
            border-color: #334155;
            /* Changed to slate-700 */
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
            background: linear-gradient(45deg, var(--primary-color), #22d3ee);
            /* Changed second color to cyan-400 */
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
            color: #f8fafc;
            /* Changed to slate-50 */
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

        body.dark .toggle-btn {
            background-color: var(--primary-hover);
        }

        body.dark .toggle-switch {
            background-color: #0ea5e9;
            /* sky-500 */
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
            border-color: #334155;
            /* Changed to slate-700 */
        }

        .profile-image {
            min-width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #e2e8f0;
            /* Changed to slate-200 */
        }

        body.dark .profile-image {
            border-color: #475569;
            /* Changed to slate-600 */
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
            color: #f8fafc;
            /* Changed to slate-50 */
        }

        .user-role {
            font-size: 0.75rem;
            color: var(--text-muted);
        }

        body.dark .user-role {
            color: #94a3b8;
            /* Changed to slate-400 */
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
            background-color: #f1f5f9;
            /* Changed to slate-100 */
            border-radius: 8px;
            padding: 0.5rem 0.75rem;
            transition: all var(--transition-speed);
        }

        body.dark .search-wrapper {
            background-color: #334155;
            /* Changed to slate-700 */
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
            color: #e2e8f0;
            /* Changed to slate-200 */
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
            color: #94a3b8;
            /* Changed to slate-400 */
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
            color: #e2e8f0;
            /* Changed to slate-200 */
        }

        .menu-link:hover {
            background-color: #f1f5f9;
            /* Changed to slate-100 */
        }

        body.dark .menu-link:hover {
            background-color: #334155;
            /* Changed to slate-700 */
        }

        .menu-link.active {
            background-color: #ecfeff;
            /* Changed to cyan-50 */
            border-left-color: var(--primary-color);
            color: var(--primary-color);
        }

        body.dark .menu-link.active {
            background-color: #164e63;
            /* Changed to cyan-900 */
            border-left-color: var(--primary-color);
            color: #67e8f9;
            /* Changed to cyan-300 */
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
            background-color: #334155;
            /* Changed to slate-700 */
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
            border-right: 5px solid #334155;
            /* Changed to slate-700 */
        }

        body.dark .menu-tooltip {
            background-color: #475569;
            /* Changed to slate-600 */
        }

        body.dark .menu-tooltip::before {
            border-right-color: #475569;
            /* Changed to slate-600 */
        }

        .sidebar.close .menu-item:hover .menu-tooltip {
            opacity: 1;
        }

        .bottom-container {
            padding: 1rem;
            border-top: 1px solid var(--border-color);
        }

        body.dark .bottom-container {
            border-color: #334155;
            /* Changed to slate-700 */
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
            color: #e2e8f0;
            /* Changed to slate-200 */
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
            /* Changed to slate-300 */
            transition: all var(--transition-speed);
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
            color: #f8fafc;
            /* Changed to slate-50 */
        }

        .logout-btn {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            background-color: #fee2e2;
            /* Changed to red-100 */
            color: #dc2626;
            /* Changed to red-600 */
            text-decoration: none;
            transition: all var(--transition-speed);
        }

        body.dark .logout-btn {
            background-color: #7f1d1d;
            /* Changed to red-900 */
            color: #fecaca;
            /* Changed to red-200 */
        }

        .logout-btn:hover {
            background-color: #fecaca;
            /* Changed to red-200 */
        }

        body.dark .logout-btn:hover {
            background-color: #991b1b;
            /* Changed to red-800 */
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
                color: #e2e8f0;
                /* Changed to slate-200 */
            }
        }
    </style>
</head>

<body>

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
            <!-- Mobile Menu Toggle Button -->
            <div class="mobile-toggle">
            </div>

            <!-- Mobile Overlay -->
            <div class="mobile-overlay"></div>

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
                        <a href="<?= base_url('controller_admin_landing/index/Dashboard'); ?>"
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
                                <a href="<?= base_url('controller_admin_landing/index/LandTax'); ?>"
                                    class="menu-link ajax-link <?= ($active_view == 'LandTax') ? 'active' : ''; ?>"
                                    data-view="LandTax">
                                    <span class="menu-text">Land Tax</span>
                                </a>
                                <div class="menu-tooltip">Land Tax</div>
                            </li>
                            <li class="submenu-item">
                                <a href="<?= base_url('controller_admin_landing/index/BackRoom'); ?>"
                                    class="menu-link ajax-link <?= ($active_view == 'BackRoom') ? 'active' : ''; ?>"
                                    data-view="BackRoom">
                                    <span class="menu-text">Backroom</span>
                                </a>
                                <div class="menu-tooltip">Backroom</div>
                            </li>
                        </ul>
                    </li>

                    <li class="menu-item">
                        <a href="<?= base_url('controller_admin_landing/index/Examiners'); ?>"
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
                        <a href="<?= base_url('controller_admin_landing/index/BusinessTax'); ?>"
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
                                <a href="<?= base_url('controller_admin_landing/index/Payment'); ?>"
                                    class="menu-link ajax-link <?= ($active_view == 'Payment') ? 'active' : ''; ?>"
                                    data-view="Payment">
                                    <span class="menu-text">Payment</span>
                                </a>
                                <div class="menu-tooltip">Payment</div>
                            </li>
                            <li class="submenu-item">
                                <a href="<?= base_url('controller_admin_landing/index/FireProtection'); ?>"
                                    class="menu-link ajax-link <?= ($active_view == 'FireProtection') ? 'active' : ''; ?>"
                                    data-view="FireProtection">
                                    <span class="menu-text">Fire Protection</span>
                                </a>
                                <div class="menu-tooltip">Fire Protection</div>
                            </li>
                            <li class="submenu-item">
                                <a href="<?= base_url('controller_admin_landing/index/Releasing'); ?>"
                                    class="menu-link ajax-link <?= ($active_view == 'Releasing') ? 'active' : ''; ?>"
                                    data-view="Releasing">
                                    <span class="menu-text">Releasing</span>
                                </a>
                                <div class="menu-tooltip">Releasing</div>
                            </li>
                        </ul>
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

            <a href="#" onclick="confirmLogout(); return false;" class="logout-btn">
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
    <!-- Logout Confirmation Modal -->
    <div id="logoutModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg max-w-md w-full mx-4 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Confirm Logout</h3>
            </div>

            <div class="px-6 py-4">
                <p class="text-gray-700">Are you sure you want to log out?</p>
            </div>

            <div class="px-6 py-4 bg-gray-50 flex justify-end space-x-3">
                <button id="cancelLogout"
                    class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition focus:outline-none focus:ring-2 focus:ring-gray-400">
                    Cancel
                </button>
                <button id="confirmLogout"
                    class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 transition focus:outline-none focus:ring-2 focus:ring-red-500">
                    Logout
                </button>
            </div>
        </div>
    </div>
    <script>
        function confirmLogout() {
            const modal = document.getElementById('logoutModal');
            modal.classList.remove('hidden');

            document.getElementById('cancelLogout').onclick = function () {
                modal.classList.add('hidden');
            };

            document.getElementById('confirmLogout').onclick = function () {
                const baseUrl = "<?= base_url(); ?>";
                window.location.href = `${baseUrl}controller_admin_landing/logout`;
            };
        }

    </script>
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

            // Apply dropdown functionality
            function applyDropdownFunctionality() {
                document.querySelectorAll(".dropdown-trigger").forEach(trigger => {
                    // Remove any existing event listeners to avoid duplicates
                    const newTrigger = trigger.cloneNode(true);
                    trigger.parentNode.replaceChild(newTrigger, trigger);

                    newTrigger.addEventListener("click", (e) => {
                        const parent = newTrigger.parentElement;
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
            }

            // Apply dropdown functionality initially
            applyDropdownFunctionality();

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
            function applyDropdownStates() {
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
            }

            // Apply dropdown states initially
            applyDropdownStates();

            // Apply tooltips
            function applyTooltips() {
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
            }

            // Apply tooltips initially
            applyTooltips();

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

            // Function to initialize any scripts that need to be run after AJAX content is loaded
            function initializeAjaxContent() {
                // Execute any script tags in the loaded content
                const scripts = document.getElementById('content').querySelectorAll('script');
                scripts.forEach(script => {
                    if (script.textContent) {
                        // Execute inline scripts
                        eval(script.textContent);
                    } else if (script.src) {
                        // Load external scripts
                        const newScript = document.createElement('script');
                        newScript.src = script.src;
                        document.head.appendChild(newScript);
                    }
                });

                // Re-apply event listeners after content loads
                applyDropdownFunctionality();
                applyDropdownStates();
                applyTooltips();

                // Re-attach event listeners to any new elements
                attachAjaxLinksEventListeners();

                // Re-execute any custom functions that your views might need
                // For example, if you have data tables, form validation, etc.
                if (typeof initializeViewSpecificFunctions === 'function') {
                    initializeViewSpecificFunctions();
                }
            }

            // Function to attach event listeners to AJAX links
            function attachAjaxLinksEventListeners() {
                document.querySelectorAll('.ajax-link').forEach(link => {
                    // Remove existing listeners to avoid duplicates
                    const newLink = link.cloneNode(true);
                    link.parentNode.replaceChild(newLink, link);

                    newLink.addEventListener('click', function (e) {
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

                                // Initialize the new content
                                initializeAjaxContent();

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
            }

            // Initialize AJAX links
            attachAjaxLinksEventListeners();

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

        // Add a global function that can be called from AJAX-loaded views
        function initializeViewSpecificFunctions() {
            // This function will be called after AJAX content is loaded
            // Views can define their own initialization functions and call them here

            // Check if specific view functions exist and call them
            if (typeof initDashboard === 'function') {
                initDashboard();
            }

            if (typeof initLandTax === 'function') {
                initLandTax();
            }

            if (typeof initBackRoom === 'function') {
                initBackRoom();
            }

            if (typeof initExaminers === 'function') {
                initExaminers();
            }

            if (typeof initBusinessTax === 'function') {
                initBusinessTax();
            }

            if (typeof initPayment === 'function') {
                initPayment();
            }

            if (typeof initFireProtection === 'function') {
                initFireProtection();
            }

            if (typeof initReleasing === 'function') {
                initReleasing();
            }

            // Re-initialize any common UI components
            if (typeof reinitializeUIComponents === 'function') {
                reinitializeUIComponents();
            }
        }
    </script>