<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Suhay</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        .fade-left {
            opacity: 0;
            transform: translateX(-60px);
            animation: fadeLeft 0.9s ease forwards;
        }

        @keyframes fadeLeft {
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .fade-right {
            opacity: 0;
            transform: translateX(60px);
            animation: fadeRight 1s ease forwards;
        }

        @keyframes fadeRight {
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .delay-1 { animation-delay: 0.2s; }
        .delay-2 { animation-delay: 0.4s; }
        .delay-3 { animation-delay: 0.6s; }
        .delay-4 { animation-delay: 0.8s; }
    </style>
</head>

<body class="bg-gray-100 overflow-hidden">

<nav class="bg-white shadow-md">
    <div class="w-full px-8 py-6 flex justify-between items-center">
        <div class="flex items-center gap-0 ml-2">
            <img src="<?php echo e(asset('images/suhayLogo.png')); ?>" class="h-16 w-auto">
        </div>

        <div class="hidden md:flex items-center gap-10 font-bold text-xl mr-2">
            <a href="/" class="text-[#0e243a] px-4 py-2 rounded-lg hover:bg-[#f2c94c] hover:text-white transition">Home</a>
            <a href="#" class="text-[#0e243a] px-4 py-2 rounded-lg hover:bg-[#f2c94c] hover:text-white transition">About</a>
            <a href="#" class="text-[#0e243a] px-4 py-2 rounded-lg hover:bg-[#f2c94c] hover:text-white transition">NGOs</a>
            <a href="#" class="text-[#0e243a] px-4 py-2 rounded-lg hover:bg-[#f2c94c] hover:text-white transition">Impact</a>
            <a href="#" class="text-[#0e243a] px-4 py-2 rounded-lg hover:bg-[#f2c94c] hover:text-white transition">Get Involved</a>
            <a href="#" class="text-[#0e243a] px-4 py-2 rounded-lg hover:bg-[#f2c94c] hover:text-white transition">Donate</a>
            <a href="/login-page" class="text-[#0e243a] px-4 py-2 rounded-lg hover:bg-[#f2c94c] hover:text-white transition">Log in</a>
        </div>
    </div>
</nav>

<section class="flex h-[calc(100vh-96px)]">
    <div class="w-1/2 bg-gray-100 flex justify-center items-center fade-left">
        <div class="text-center w-full max-w-lg">
            <img src="<?php echo e(asset('images/suhayLogo.png')); ?>" 
                 class="h-24 mx-auto mb-6 fade-left delay-1">
            <h1 class="text-5xl font-extrabold text-[#0e243a] mb-3 whitespace-nowrap fade-left delay-2">
                WELCOME TO SUHAY!
            </h1>

            <p class="text-gray-600 text-lg mb-10 fade-left delay-2">
                Please enter your details.
            </p>

            <form method="POST" action="/login" class="space-y-6 flex flex-col items-center fade-left delay-3">
                <?php echo csrf_field(); ?>

                <?php if(session('error')): ?>
                    <p class="text-red-500 text-sm"><?php echo e(session('error')); ?></p>
                <?php endif; ?>

                <div class="flex items-center border rounded-full px-6 py-4 bg-white shadow-sm w-[550px]">
                    <img src="<?php echo e(asset('images/LoginIcons/user.png')); ?>" 
                        class="w-7 h-7 mr-3 opacity-60">
                    <input type="email" name="email" placeholder="Email Address"
                        class="w-full outline-none text-lg" required>
                </div>

                <div class="flex items-center border rounded-full px-6 py-4 bg-white shadow-sm w-[550px]">
                    <img src="<?php echo e(asset('images/LoginIcons/lock.png')); ?>" 
                        class="w-7 h-7 mr-3 opacity-60">
                    <input id="passwordField" type="password" name="password" placeholder="Password"
                        class="w-full outline-none text-lg" required>
                    <img id="toggleEye"
                        src="<?php echo e(asset('images/LoginIcons/eyeClose.png')); ?>"
                        class="w-7 h-7 ml-3 cursor-pointer opacity-60">
                </div>

                <button type="submit"
                    class="bg-[#0e243a] text-yellow-400 py-4 rounded-full font-bold text-lg w-[350px] hover:opacity-90 transition fade-left delay-4">
                    LOGIN
                </button>
            </form>
        </div>
    </div>

    <div class="w-1/2 relative fade-right">
        <img src="<?php echo e(asset('images/loginBG.jpg')); ?>"
            class="w-full h-full object-cover">

        <div class="absolute inset-0 bg-gradient-to-l from-transparent to-gray-100"></div>
    </div>

</section>

<script>
const toggleEye = document.getElementById('toggleEye');
const passwordField = document.getElementById('passwordField');

let isVisible = false;

toggleEye.addEventListener('click', () => {
    if (isVisible) {
        passwordField.type = 'password';
        toggleEye.src = "<?php echo e(asset('images/LoginIcons/eyeClose.png')); ?>";
    } else {
        passwordField.type = 'text';
        toggleEye.src = "<?php echo e(asset('images/LoginIcons/eyeOpen.png')); ?>";
    }
    isVisible = !isVisible;
});
</script>

</body>
</html><?php /**PATH C:\xampp\htdocs\example-app\MEt.A-Project-SUHAY\resources\views/login.blade.php ENDPATH**/ ?>