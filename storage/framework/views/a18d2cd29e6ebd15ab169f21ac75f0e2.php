<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Volunteer - Suhay</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        .fade-up {
            opacity: 0;
            transform: translateY(40px);
            animation: fadeUp 0.8s ease forwards;
        }

        @keyframes fadeUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .delay-1 { animation-delay: 0.2s; }
        .delay-2 { animation-delay: 0.4s; }
        .delay-3 { animation-delay: 0.6s; }
    </style>
</head>

<body class="bg-gray-100">

<?php echo $__env->make('components.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>


<!-- HEADER USING HERO IMAGE -->
<section class="relative h-[250px] flex items-center">
    <img src="<?php echo e(asset('images/hero.jpg')); ?>" class="absolute inset-0 w-full h-full object-cover">

    <div class="absolute inset-0 bg-gradient-to-r from-[#0e243a]/95 to-transparent"></div>

    <div class="relative z-10 pl-16 text-white">
        <h1 class="text-6xl font-extrabold fade-up">
            Categories
        </h1>
        <p class="text-[#f2c94c] text-3xl mt-3 fade-up delay-1">
            Join Us in Creating Change
        </p>
    </div>
</section>

<!-- TEXT-ONLY CARDS -->
<section class="py-14 px-12">
    <div class="grid grid-cols-3 gap-8">

            <?php $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="bg-white rounded-3xl shadow-md p-8 text-center fade-up">

                    <h2 class="text-2xl font-bold text-[#0e243a] mb-4">
                        <?php echo e($event['name']); ?>

                    </h2>

                    <p class="text-gray-600 mb-6">
                        <?php echo e($event['description']); ?>

                    </p>

                    <a href="/volunteer-application-form"
                    class="bg-[#d4a017] text-white px-10 py-3 rounded-full text-xl hover:bg-yellow-600 transition inline-block">
                        Volunteer Now
                    </a>

                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</section>

</body>
</html><?php /**PATH C:\sysands\MEt.A-Project-SUHAY-elisha\resources\views/volunteer_page.blade.php ENDPATH**/ ?>