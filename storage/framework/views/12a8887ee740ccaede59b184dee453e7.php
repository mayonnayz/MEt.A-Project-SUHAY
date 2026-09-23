<!DOCTYPE html>

<html lang="en">

<head>


<meta charset="UTF-8">
<meta
    name="csrf-token"
    content="<?php echo e(csrf_token()); ?>"
>
<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<title>Donate | SUHAY</title>

<link
    rel="icon"
    href="<?php echo e(asset('images/suhayLogo.png')); ?>"
>

<link
    href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap"
    rel="stylesheet"
>

<script src="https://cdn.tailwindcss.com"></script>

<link
    href="https://unpkg.com/aos@2.3.1/dist/aos.css"
    rel="stylesheet"
>

<style>

    body {
        font-family: 'Poppins', sans-serif;
    }

    .hover-scale {
        transition: all 0.25s ease;
    }

    .hover-scale:hover {
        transform: translateY(-6px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.15);
    }

    html {
        scroll-behavior: smooth;
    }

    .modal-enter {
        animation: zoomFadeIn 0.3s ease forwards;
    }

    .modal-exit {
        animation: zoomFadeOut 0.25s ease forwards;
    }

    @keyframes zoomFadeIn {

        0% {
            opacity: 0;
            transform: scale(0.7);
        }

        100% {
            opacity: 1;
            transform: scale(1);
        }

    }

    @keyframes zoomFadeOut {

        0% {
            opacity: 1;
            transform: scale(1);
        }

        100% {
            opacity: 0;
            transform: scale(0.7);
        }

    }

</style>

</head>

<body class="bg-gray-100">

<?php echo $__env->make('components.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php if(session('success') || $errors->any()): ?>

<div
    id="resultModal"
    class="fixed inset-0 bg-black/60 flex items-center justify-center z-[100] p-4"
>

    <div
        class="bg-white w-full max-w-md rounded-2xl shadow-2xl p-8 text-center"
    >

        <?php if(session('success')): ?>

            <!-- SUCCESS ICON -->

            <div
                class="mx-auto mb-5 w-20 h-20 rounded-full bg-green-100 flex items-center justify-center"
            >
                <span class="text-4xl text-green-600">
                    ✓
                </span>
            </div>


            <h2 class="text-xl font-bold text-[#0e243a]">
                Donation Successful!
            </h2>

            <p class="text-gray-600 mt-3">
                <?php echo e(session('success')); ?>

            </p>


            <button
                type="button"
                onclick="closeResultModal()"
                class="mt-6 px-8 py-3 rounded-full bg-[#f2c94c] text-[#0e243a] font-bold hover:bg-[#d39a11] transition"
            >
                Done
            </button>

        <?php else: ?>

            <!-- ERROR ICON -->

            <div
                class="mx-auto mb-5 w-20 h-20 rounded-full bg-red-100 flex items-center justify-center"
            >
                <span class="text-4xl text-red-600">
                    !
                </span>
            </div>


            <h2 class="text-xl font-bold text-[#0e243a]">
                Donation Failed
            </h2>


            <div class="text-left bg-red-50 border border-red-200 rounded-xl p-4 mt-4">

                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                    <p class="text-sm text-red-700 mb-1">
                        <?php echo e($error); ?>

                    </p>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            </div>


            <button
                type="button"
                onclick="closeResultModal()"
                class="mt-6 px-8 py-3 rounded-full bg-gray-400 text-white font-bold hover:bg-gray-500 transition"
            >
                Close
            </button>

        <?php endif; ?>

    </div>

</div>

<?php endif; ?>

<!-- ======================================== -->

<!-- HERO -->

<!-- ======================================== -->

<section
    class="relative h-[280px] w-full"
    data-aos="fade-up"
    data-aos-duration="1000"
>


<img
    src="<?php echo e(asset('images/hero.jpg')); ?>"
    class="w-full h-full object-cover"
    alt="Suhay"
>

<div class="absolute inset-0 bg-[#0e243a]/80"></div>

<div class="absolute inset-0 flex items-center px-10">

    <div>

        <h1 class="text-4xl md:text-5xl font-extrabold text-white">
            Donate Today
        </h1>

        <p class="text-[#f2c94c] text-lg mt-2">
            Your Peso Matters
        </p>

    </div>

</div>


</section>

<!-- ======================================== -->

<!-- NGO LIST -->

<!-- ======================================== -->

<section
    class="py-16 bg-white"
    data-aos="fade-up"
    data-aos-duration="800"
>

<div class="max-w-4xl mx-auto px-6">

    <div class="text-center mb-12">

        <h2 class="text-3xl font-bold text-[#0e243a]">
            Verified Partners
        </h2>

        <p class="text-gray-600">
            Support our trusted NGOs
        </p>

    </div>


    <div class="space-y-5">

        <?php $__currentLoopData = $ngos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ngo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

            <div
                onclick="openModal(<?php echo e($ngo['id']); ?>)"
                class="bg-white border-2 border-[#0e243a] rounded-2xl p-8 md:p-10 flex items-center justify-between hover-scale cursor-pointer"
            >

                <div class="flex items-center gap-6">

                    <!-- NGO LOGO -->

                    <div
                        class="w-16 h-16 md:w-20 md:h-20 flex items-center justify-center"
                    >

                        <?php if(!empty($ngo['logo'])): ?>

                            <img
                                src="https://vqywnoljhhcnybzbvhhh.supabase.co/storage/v1/object/public/profile-pictures/<?php echo e($ngo['logo']); ?>"
                                class="w-full h-full object-contain rounded-lg"
                                alt="<?php echo e($ngo['name']); ?> Logo"
                                onerror="this.src='<?php echo e(asset('images/suhayLogo.png')); ?>'"
                            >

                        <?php else: ?>

                            <img
                                src="<?php echo e(asset('images/suhayLogo.png')); ?>"
                                class="w-full h-full object-contain rounded-lg"
                                alt="Suhay Logo"
                            >

                        <?php endif; ?>

                    </div>


                    <!-- NGO INFORMATION -->

                    <div>

                        <h3
                            class="font-bold text-[#0e243a] text-xl md:text-2xl"
                        >
                            <?php echo e($ngo['name']); ?>

                        </h3>

                        <p class="text-base text-gray-600 mt-1">

                            <?php echo e($ngo['contact_number'] ?? 'No contact'); ?>


                            •

                            <?php echo e($ngo['address'] ?? 'No address'); ?>


                        </p>

                    </div>

                </div>


                <button
                    type="button"
                    class="bg-[#f2c94c] px-6 py-3 rounded-xl font-semibold text-[#0e243a] text-base hover:bg-[#d39a11] transition"
                >
                    View Details
                </button>

            </div>

        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    </div>

</div>

</section>

<!-- ======================================== -->

<!-- NGO MODAL -->

<!-- ======================================== -->

<div
    id="ngoModal"
    class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50 p-4"
>


<div
    id="modalContent"
    class="bg-white w-full max-w-4xl max-h-[90vh] overflow-y-auto rounded-2xl shadow-xl"
>
</div>


</div>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<script>
    window.ngoData = <?php echo json_encode($ngos, 15, 512) ?>;

    window.suhayAssets = {
        logo: <?php echo json_encode(asset('images/suhayLogo.png'), 15, 512) ?>,
        phoneIcon: <?php echo json_encode(asset('images/VolunteerIcons/VPhone.png'), 15, 512) ?>,
        locationIcon: <?php echo json_encode(asset('images/VolunteerIcons/VLocation.png'), 15, 512) ?>
    };
</script>

<script src="<?php echo e(asset('js/donate.js')); ?>"></script>

</body>
</html>
<?php /**PATH C:\sysands\MEt.A-Project-SUHAY\resources\views/donate.blade.php ENDPATH**/ ?>