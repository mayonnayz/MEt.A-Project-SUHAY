<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - Suhay</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap"
        rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        .auth-container {
            position: relative;
            width: 100%;
            height: calc(100vh - 96px);
            overflow: hidden;
        }

        .panel {
            position: absolute;
            top: 0;
            height: 100%;
            transition:
                transform 0.8s ease-in-out,
                opacity 0.8s ease-in-out;
        }

        .login-panel {
            left: 0;
            width: 50%;
            transform: translateX(0);
            z-index: 2;
        }

        .image-panel {
            left: 50%;
            width: 50%;
            transform: translateX(0);
            z-index: 1;

            opacity: 1;
            transition:
                transform 0.8s ease-in-out,
                opacity 0.4s ease-in-out;
        }

        .image-fade-out {
            opacity: 0 !important;
            transition: opacity 0.3s ease-in-out !important;
        }

        .image-fade-in {
            opacity: 1 !important;
            transition: opacity 0.8s ease-in-out !important;
        }

        .auth-container.signup-active .image-panel {
            transform: translateX(-100%);
        }

        .auth-container.signup-active .login-panel {
            transform: translateX(100%);
        }

        .auth-container.signup-active .image-panel {
            transform: translateX(-100%);
        }

        .form-content {
            transition:
                opacity 0.5s ease,
                transform 0.7s ease;
        }

        .signup-content {
            opacity: 0;
            transform: translateX(60px);
            pointer-events: none;
        }

        .login-content {
            opacity: 1;
            transform: translateX(0);
        }

        .signup-active .login-content {
            opacity: 0;
            transform: translateX(-60px);
            pointer-events: none;
        }

        .signup-active .signup-content {
            opacity: 1;
            transform: translateX(0);
            pointer-events: auto;
        }

        .signup-field {
            transition:
                transform 0.5s ease,
                opacity 0.5s ease;
        }

        .signup-field:nth-child(1) {
            transition-delay: 0.1s;
        }

        .signup-field:nth-child(2) {
            transition-delay: 0.15s;
        }

        .signup-field:nth-child(3) {
            transition-delay: 0.2s;
        }

        .signup-field:nth-child(4) {
            transition-delay: 0.25s;
        }

        .signup-field:nth-child(5) {
            transition-delay: 0.3s;
        }

        .signup-field:nth-child(6) {
            transition-delay: 0.35s;
        }

        .signup-field:nth-child(7) {
            transition-delay: 0.4s;
        }
    </style>
</head>

<body class="bg-gray-100 overflow-hidden">

    <?php echo $__env->make('components.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <section id="authContainer" class="auth-container">


        <div class="panel login-panel bg-gray-100 flex justify-center items-center">

            <div class="login-content form-content text-center w-full max-w-lg">

                <img src="<?php echo e(asset('images/suhayLogo.png')); ?>"
                    class="h-24 mx-auto mb-6">

                <h1 class="text-5xl font-extrabold text-[#0e243a] mb-3 whitespace-nowrap">
                    WELCOME TO SUHAY!
                </h1>

                <p class="text-gray-600 text-lg mb-10">
                    Please enter your details.
                </p>

                <form method="POST" action="/login"
                    class="space-y-6 flex flex-col items-center">

                    <?php echo csrf_field(); ?>

                    <?php if(session('error')): ?>
                        <p class="text-red-500 text-sm">
                            <?php echo e(session('error')); ?>

                        </p>
                    <?php endif; ?>

                    <div
                        class="flex items-center border rounded-full px-6 py-4 bg-white shadow-sm w-[550px]">

                        <img src="<?php echo e(asset('images/LoginIcons/user.png')); ?>"
                            class="w-7 h-7 mr-3 opacity-60">

                        <input type="email"
                            name="email"
                            placeholder="Email Address"
                            class="w-full outline-none text-lg"
                            required>
                    </div>

                    <div
                        class="flex items-center border rounded-full px-6 py-4 bg-white shadow-sm w-[550px]">

                        <img src="<?php echo e(asset('images/LoginIcons/lock.png')); ?>"
                            class="w-7 h-7 mr-3 opacity-60">

                        <input id="passwordField"
                            type="password"
                            name="password"
                            placeholder="Password"
                            class="w-full outline-none text-lg"
                            required>

                        <img id="toggleEye"
                            src="<?php echo e(asset('images/LoginIcons/eyeClose.png')); ?>"
                            class="w-7 h-7 ml-3 cursor-pointer opacity-60">
                    </div>

                    <button type="submit" class="bg-[#0e243a] text-yellow-400 py-4 rounded-full font-bold text-lg w-[350px] hover:opacity-90 transition">
                        LOGIN
                    </button>

                </form>

                <br>

                <button id="showSignup"
                    type="button"
                    class="bg-[#f2c94c] text-[#0e243a] py-4 rounded-full font-bold text-lg w-[300px] hover:opacity-90 transition">
                    SIGN IN
                </button>

            </div>



            <div class="signup-content form-content text-center w-full max-w-2xl absolute">

                <img src="<?php echo e(asset('images/suhayLogo.png')); ?>"
                    class="h-20 mx-auto mb-3">

                <h1 class="text-4xl font-extrabold text-[#0e243a] mb-2">
                    CREATE YOUR ACCOUNT
                </h1>

                <p class="text-gray-600 mb-5">
                    Please enter your information.
                </p>


                <form method="POST"
                    id="signupForm"
                    action="/register"
                    class="flex flex-col items-center">

                    <?php echo csrf_field(); ?>


                    <div class="flex gap-4 w-[650px] mb-3">

                        <div class="signup-field flex items-center border rounded-full px-5 py-3 bg-white shadow-sm w-1/2">
                            <input type="text"
                                id="firstName"
                                name="first_name"
                                placeholder="First Name"
                                class="w-full outline-none"
                                required>
                        </div>


                        <div class="signup-field flex items-center border rounded-full px-5 py-3 bg-white shadow-sm w-1/2">
                            <input type="text"
                                id="lastName"
                                name="last_name"
                                placeholder="Last Name"
                                class="w-full outline-none"
                                required>
                        </div>

                    </div>



                    <div class="signup-field flex items-center border rounded-full px-5 py-3 bg-white shadow-sm w-[650px] mb-3">
                        <input type="email"
                            id="signupEmail"
                            name="email"
                            placeholder="Email Address"
                            class="w-full outline-none"
                            required>
                    </div>

                    <div class="signup-field flex items-center border rounded-full px-5 py-3 bg-white shadow-sm w-[650px] mb-3">
                        <input id="signupPassword"
                            type="password"
                            name="password"
                            placeholder="Password"
                            class="w-full outline-none"
                            required>

                        <img id="signupToggleEye"
                            src="<?php echo e(asset('images/LoginIcons/eyeClose.png')); ?>"
                            class="w-6 h-6 ml-3 cursor-pointer opacity-60">
                    </div>


                    <div class="signup-field flex items-center border rounded-full px-5 py-3 bg-white shadow-sm w-[650px] mb-3">
                        <input type="text"
                            id="address"
                            name="address"
                            placeholder="Address"
                            class="w-full outline-none"
                            required>
                    </div>



                    <div class="signup-field flex items-center border rounded-full px-5 py-3 bg-white shadow-sm w-[650px] mb-3">
                        <input type="date"
                            id="birthdate"
                            name="birthdate"
                            class="w-full outline-none text-gray-500"
                            required>
                    </div>


                    <div class="signup-field flex items-center border rounded-full px-5 py-3 bg-white shadow-sm w-[650px] mb-5">
                        <input type="tel"
                            id="contactNumber"
                            name="contact_number"
                            placeholder="Contact Number"
                            class="w-full outline-none"
                            required>
                    </div>


                    <button type="submit" class="bg-[#0e243a] text-yellow-400 py-4 rounded-full font-bold text-lg w-[350px] hover:opacity-90 transition">
                        SIGN IN
                    </button>

                </form>

                <br>

                <button id="showLogin" type="button" class="bg-[#f2c94c] text-[#0e243a] py-4 rounded-full font-bold text-lg w-[300px] hover:opacity-90 transition">
                    LOGIN
                </button>

            </div>
        </div>


        <div class="panel image-panel relative">

            <img src="<?php echo e(asset('images/loginBG.jpg')); ?>"
                class="w-full h-full object-cover">

            <div id="imageGradient"
                class="absolute inset-0 bg-gradient-to-l from-transparent to-gray-100">
            </div>

        </div>

    </section>


    <div id="errorModal"
        class="fixed inset-0 bg-black/50 flex items-center justify-center z-[9999] hidden">

        <div class="bg-white rounded-2xl shadow-2xl w-[400px] p-8 text-center">
            <div class="text-red-500 text-5xl mb-4">
                !
            </div>

            <h2 class="text-2xl font-bold text-[#0e243a] mb-3">
                Please check your information
            </h2>

            <p id="errorMessage" class="text-gray-600 mb-6">
            </p>

            <button id="closeErrorModal"
                type="button"
                class="bg-[#f2c94c] text-[#0e243a] px-8 py-3 rounded-full font-bold hover:opacity-90 transition">
                OK
            </button>
        </div>
    </div>

    <script>
        const authContainer = document.getElementById('authContainer');

        const showSignup = document.getElementById('showSignup');
        const showLogin = document.getElementById('showLogin');

        const imagePanel = document.querySelector('.image-panel');
        const imageGradient = document.getElementById('imageGradient');

        showSignup.addEventListener('click', function () {
            imagePanel.classList.remove('image-fade-in');
            imagePanel.classList.add('image-fade-out');

            setTimeout(() => {
                authContainer.classList.add('signup-active');

                imageGradient.classList.remove('bg-gradient-to-l');
                imageGradient.classList.add('bg-gradient-to-r');

            }, 350);

            setTimeout(() => {

                imagePanel.classList.remove('image-fade-out');
                imagePanel.classList.add('image-fade-in');

            }, 850);

        });


        showLogin.addEventListener('click', function () {

            imagePanel.classList.remove('image-fade-in');
            imagePanel.classList.add('image-fade-out');

            setTimeout(() => {
                authContainer.classList.remove('signup-active');

                imageGradient.classList.remove('bg-gradient-to-r');
                imageGradient.classList.add('bg-gradient-to-l');
            }, 350);

            setTimeout(() => {
                imagePanel.classList.remove('image-fade-out');
                imagePanel.classList.add('image-fade-in');
            }, 850);

        });

        const toggleEye = document.getElementById('toggleEye');
        const passwordField = document.getElementById('passwordField');

        let isVisible = false;

        toggleEye.addEventListener('click', () => {

            if (isVisible) {
                passwordField.type = 'password';
                toggleEye.src =
                    "<?php echo e(asset('images/LoginIcons/eyeClose.png')); ?>";
            } else {
                passwordField.type = 'text';
                toggleEye.src = "<?php echo e(asset('images/LoginIcons/eyeOpen.png')); ?>";
            }

            isVisible = !isVisible;
        });


        const signupToggleEye =
            document.getElementById('signupToggleEye');

        const signupPassword =
            document.getElementById('signupPassword');

        let signupPasswordVisible = false;

        signupToggleEye.addEventListener('click', () => {

            if (signupPasswordVisible) {
                signupPassword.type = 'password';
                signupToggleEye.src =
                    "<?php echo e(asset('images/LoginIcons/eyeClose.png')); ?>";
            } else {
                signupPassword.type = 'text';
                signupToggleEye.src =
                    "<?php echo e(asset('images/LoginIcons/eyeOpen.png')); ?>";
            }

            signupPasswordVisible = !signupPasswordVisible;

        });


        const signupForm = document.getElementById('signupForm');

        const errorModal = document.getElementById('errorModal');
        const errorMessage = document.getElementById('errorMessage');
        const closeErrorModal = document.getElementById('closeErrorModal');

        function showError(message) {
            errorMessage.textContent = message;
            errorModal.classList.remove('hidden');
        }

        closeErrorModal.addEventListener('click', function () {
            errorModal.classList.add('hidden');
        });

        signupForm.addEventListener('submit', function (event) {

            const firstName = document.getElementById('firstName').value.trim();
            const lastName = document.getElementById('lastName').value.trim();
            const email = document.getElementById('signupEmail').value.trim();
            const password = document.getElementById('signupPassword').value;
            const address = document.getElementById('address').value.trim();
            const birthdate = document.getElementById('birthdate').value;
            const contactNumber = document.getElementById('contactNumber').value.trim();

            const nameRegex = /^[A-Za-z\s'-]+$/;

            if (firstName === '') {
                event.preventDefault();
                showError('Please enter your first name.');
                return;
            }

            if (!nameRegex.test(firstName)) {
                event.preventDefault();
                showError('First name should contain letters only.');
                return;
            }

            if (lastName === '') {
                event.preventDefault();
                showError('Please enter your last name.');
                return;
            }

            if (!nameRegex.test(lastName)) {
                event.preventDefault();
                showError('Last name should contain letters only.');
                return;
            }

            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (email === '') {
                event.preventDefault();
                showError('Please enter your email address.');
                return;
            }

            if (!emailRegex.test(email)) {
                event.preventDefault();
                showError('Please enter a valid email address.');
                return;
            }

            if (password === '') {
                event.preventDefault();
                showError('Please enter a password.');
                return;
            }

            if (password.length < 8) {
                event.preventDefault();
                showError('Password must be at least 8 characters long.');
                return;
            }

            if (address === '') {
                event.preventDefault();
                showError('Please enter your address.');
                return;
            }

            if (birthdate === '') {
                event.preventDefault();
                showError('Please select your birthdate.');
                return;
            }

            const phoneRegex = /^(09|\+639)\d{9}$/;

            if (contactNumber === '') {
                event.preventDefault();
                showError('Please enter your contact number.');
                return;
            }

            if (!phoneRegex.test(contactNumber)) {
                event.preventDefault();
                showError(
                    'Please enter a valid Philippine contact number, such as 09171234567.'
                );
                return;
            }

        });

    </script>



</body>
</html>
<?php /**PATH C:\Sysands\MEt.A-Project-SUHAY\resources\views/login.blade.php ENDPATH**/ ?>