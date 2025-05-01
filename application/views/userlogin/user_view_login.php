<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Secure Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/zxcvbn/4.4.2/zxcvbn.js"></script>
    <style>
        body {
            background-image: url('<?= base_url('uploads/login_image/login_background.jpg'); ?>');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 32px rgba(31, 38, 135, 0.2);
        }

        .loader {
            border-top-color: #3B82F6;
            animation: spinner 1s linear infinite;
        }

        @keyframes spinner {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .animate-fade-in {
            animation: fadeIn 0.5s ease-in;
        }

        @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: translateY(10px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body class="flex items-center justify-center min-h-screen bg-gray-900 bg-opacity-60 py-12 px-4 sm:px-6 lg:px-8">
    <div class="glass-effect rounded-xl shadow-2xl w-full max-w-md p-8 animate-fade-in">
        <!-- Logo and Header -->
        <div class="text-center mb-8">
            <div class="mx-auto w-16 h-16 mb-4 bg-blue-600 rounded-full flex items-center justify-center">
                <i class="fas fa-shield-alt text-white text-2xl"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-800">Welcome Back</h2>
            <p class="text-gray-600 text-sm mt-1">Enter your credentials to access your account</p>
        </div>

        <!-- Alert Messages -->
        <div id="alertContainer" class="mb-4">
            <?php if ($this->session->flashdata('error')): ?>
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded flex items-center gap-2 mb-4"
                    role="alert">
                    <i class="fas fa-exclamation-circle"></i>
                    <span><?= $this->session->flashdata('error'); ?></span>
                    <button type="button" class="ml-auto text-red-700 hover:text-red-900"
                        onclick="this.parentElement.remove()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            <?php endif; ?>

            <?php if ($this->session->flashdata('success')): ?>
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded flex items-center gap-2 mb-4"
                    role="alert">
                    <i class="fas fa-check-circle"></i>
                    <span><?= $this->session->flashdata('success'); ?></span>
                    <button type="button" class="ml-auto text-green-700 hover:text-green-900"
                        onclick="this.parentElement.remove()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            <?php endif; ?>
        </div>

        <!-- Login Form -->
        <form id="loginForm" action="<?= base_url('controller_login/login_process'); ?>" method="post"
            class="space-y-6">
            <!-- Username Field -->
            <div>
                <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-user text-gray-400"></i>
                    </div>
                    <input type="text" id="username" name="username"
                        class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                        placeholder="Enter your username" required autofocus>
                </div>
            </div>

            <!-- Password Field -->
            <div>
                <div class="flex items-center justify-between mb-1">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <!-- <a href="#" id="forgotPasswordLink"
                        class="text-xs text-blue-600 hover:text-blue-800 hover:underline">
                        Forgot password?
                    </a> -->
                </div>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-lock text-gray-400"></i>
                    </div>
                    <input type="password" id="password" name="password"
                        class="block w-full pl-10 pr-10 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                        placeholder="Enter your password" required>
                    <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                        <i class="fas fa-eye text-gray-400 hover:text-blue-500"></i>
                    </button>
                </div>
                <div id="passwordStrength" class="mt-1 hidden">
                    <div class="flex space-x-1 h-1 mt-1">
                        <div class="w-1/4 rounded-full" id="strength-1"></div>
                        <div class="w-1/4 rounded-full" id="strength-2"></div>
                        <div class="w-1/4 rounded-full" id="strength-3"></div>
                        <div class="w-1/4 rounded-full" id="strength-4"></div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1" id="strengthText"></p>
                </div>
            </div>


            <!-- Login Button -->
            <div>
                <button type="submit" id="loginButton"
                    class="group relative w-full flex justify-center py-3 px-4 border border-transparent rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 font-medium shadow-sm transition-all duration-300">
                    <span id="buttonText">Sign in</span>
                    <span id="buttonLoader" class="hidden">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        Processing...
                    </span>
                </button>
            </div>
        </form>

        <!-- Divider -->
        <div class="relative my-6">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full"></div>
            </div>
            <!-- <div class="relative flex justify-center text-sm">
                <span class="px-2 bg-white text-gray-500">Or continue with</span>
            </div> -->
        </div>

        <!-- Social Logins -->
        <!-- <div class="flex gap-4 mb-6">
            <button type="button"
                class="flex-1 py-2 px-4 border border-gray-300 rounded-lg shadow-sm bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                <div class="flex items-center justify-center">
                    <i class="fab fa-google text-red-500"></i>
                    <span class="ml-2 text-sm font-medium text-gray-700">Google</span>
                </div>
            </button>
            <button type="button"
                class="flex-1 py-2 px-4 border border-gray-300 rounded-lg shadow-sm bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                <div class="flex items-center justify-center">
                    <i class="fab fa-microsoft text-blue-500"></i>
                    <span class="ml-2 text-sm font-medium text-gray-700">Microsoft</span>
                </div>
            </button>
        </div> -->


    </div>

    <!-- Modals -->
    <!-- Forgot Password Modal -->
    <div id="forgotPasswordModal" class="fixed inset-0 z-10 overflow-y-auto hidden">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-black opacity-50" onclick="toggleModal('forgotPasswordModal')"></div>
            <div class="glass-effect z-20 relative rounded-lg max-w-md w-full p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-gray-900">Reset Password</h3>
                    <button type="button" onclick="toggleModal('forgotPasswordModal')"
                        class="text-gray-400 hover:text-gray-500">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <p class="text-sm text-gray-600 mb-4">Enter your email address and we'll send you a link to reset your
                    password.</p>
                <form id="forgotPasswordForm" class="space-y-4">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-envelope text-gray-400"></i>
                            </div>
                            <input type="email" id="email" name="email"
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Enter your email" required>
                        </div>
                    </div>
                    <button type="submit"
                        class="w-full py-2 px-4 border border-transparent rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Send Reset Link
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        // Show loading state on form submission
        document.getElementById('loginForm').addEventListener('submit', function (e) {
            document.getElementById('buttonText').classList.add('hidden');
            document.getElementById('buttonLoader').classList.remove('hidden');
        });

        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function () {
            const passwordInput = document.getElementById('password');
            const icon = this.querySelector('i');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });



        // Password strength meter
        document.getElementById('password').addEventListener('input', function () {
            const password = this.value;
            if (password.length > 0) {
                document.getElementById('passwordStrength').classList.remove('hidden');
                const result = zxcvbn(password);
                updateStrengthMeter(result.score, 'strength', 'strengthText');
            } else {
                document.getElementById('passwordStrength').classList.add('hidden');
            }
        });

        // Register password strength meter
        document.getElementById('registerPassword')?.addEventListener('input', function () {
            const password = this.value;
            const result = zxcvbn(password);
            updateStrengthMeter(result.score, 'reg-strength', 'regStrengthText');
        });

        // Check if passwords match
        document.getElementById('confirmPassword')?.addEventListener('input', function () {
            const password = document.getElementById('registerPassword').value;
            const confirmPassword = this.value;
            const matchMessage = document.getElementById('passwordMatch');

            if (confirmPassword.length > 0) {
                matchMessage.classList.remove('hidden');

                if (password === confirmPassword) {
                    matchMessage.textContent = 'Passwords match';
                    matchMessage.classList.remove('text-red-500');
                    matchMessage.classList.add('text-green-500');
                } else {
                    matchMessage.textContent = 'Passwords do not match';
                    matchMessage.classList.remove('text-green-500');
                    matchMessage.classList.add('text-red-500');
                }
            } else {
                matchMessage.classList.add('hidden');
            }
        });

        // Update password strength meter
        function updateStrengthMeter(score, idPrefix, textId) {
            // Reset all strength bars
            for (let i = 1; i <= 4; i++) {
                const element = document.getElementById(`${idPrefix}-${i}`);
                element.className = 'w-1/4 rounded-full bg-gray-200';
            }

            // Set colors based on score
            const strengthText = document.getElementById(textId);
            let color = '';
            let message = '';

            switch (score) {
                case 0:
                    color = 'bg-red-500';
                    message = 'Very weak';
                    fillBars(idPrefix, 1, color);
                    break;
                case 1:
                    color = 'bg-red-400';
                    message = 'Weak';
                    fillBars(idPrefix, 1, color);
                    break;
                case 2:
                    color = 'bg-yellow-500';
                    message = 'Fair';
                    fillBars(idPrefix, 2, color);
                    break;
                case 3:
                    color = 'bg-green-400';
                    message = 'Good';
                    fillBars(idPrefix, 3, color);
                    break;
                case 4:
                    color = 'bg-green-500';
                    message = 'Strong';
                    fillBars(idPrefix, 4, color);
                    break;
            }

            strengthText.textContent = message;
            strengthText.className = `text-xs mt-1 ${color.replace('bg-', 'text-')}`;
        }

        // Fill strength meter bars
        function fillBars(idPrefix, count, color) {
            for (let i = 1; i <= count; i++) {
                document.getElementById(`${idPrefix}-${i}`).className = `w-1/4 rounded-full ${color}`;
            }
        }

        // Toggle modals
        function toggleModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal.classList.contains('hidden')) {
                modal.classList.remove('hidden');
            } else {
                modal.classList.add('hidden');
            }
        }

        // Modal triggers
        document.getElementById('forgotPasswordLink').addEventListener('click', function (e) {
            e.preventDefault();
            toggleModal('forgotPasswordModal');
        });

        document.getElementById('registerLink').addEventListener('click', function (e) {
            e.preventDefault();
            toggleModal('registerModal');
        });

        // Handle form submissions
        document.getElementById('forgotPasswordForm')?.addEventListener('submit', function (e) {
            e.preventDefault();
            const email = document.getElementById('email').value;
            // Simulate API call
            setTimeout(() => {
                toggleModal('forgotPasswordModal');
                showAlert('success', `Password reset link has been sent to ${email}`);
            }, 1000);
        });

        document.getElementById('registerForm')?.addEventListener('submit', function (e) {
            e.preventDefault();
            // Validate forms (simplified for example)
            const password = document.getElementById('registerPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;

            if (password !== confirmPassword) {
                showAlert('error', 'Passwords do not match');
                return;
            }

            // Simulate API call
            setTimeout(() => {
                toggleModal('registerModal');
                showAlert('success', 'Account created successfully! Please check your email to verify your account.');
            }, 1000);
        });

        // Show alert messages
        function showAlert(type, message) {
            const alertContainer = document.getElementById('alertContainer');
            const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
            const color = type === 'success' ? 'green' : 'red';

            const alert = document.createElement('div');
            alert.className = `bg-${color}-100 border-l-4 border-${color}-500 text-${color}-700 p-4 rounded flex items-center gap-2 mb-4 animate-fade-in`;
            alert.setAttribute('role', 'alert');

            alert.innerHTML = `
                <i class="fas ${icon}"></i>
                <span>${message}</span>
                <button type="button" class="ml-auto text-${color}-700 hover:text-${color}-900" onclick="this.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            `;

            alertContainer.appendChild(alert);

            // Remove alert after 5 seconds
            setTimeout(() => {
                if (alert.parentNode) {
                    alert.remove();
                }
            }, 5000);
        }


        // Detect browser capabilities for security warning
        window.addEventListener('load', function () {
            // Check if cookies are enabled
            if (!navigator.cookieEnabled) {
                showAlert('error', 'Cookies are disabled in your browser. Please enable them for proper functionality.');
            }

            // Check if using an old browser (simplified check)
            const isIE = /*@cc_on!@*/false || !!document.documentMode;
            if (isIE) {
                showAlert('error', 'You are using an outdated browser. Please update for better security and performance.');
            }
        });
    </script>
</body>

</html>