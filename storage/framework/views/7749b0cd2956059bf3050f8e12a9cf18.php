<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NGOs | SUHAY</title>

    <link rel="icon" type="image/png" href="<?php echo e(asset('images/suhayLogo.png')); ?>">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- AOS ANIMATION -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        /* Card lift + glow */
        .ngo-card {
            position: relative;
            isolation: isolate;
        }

        .ngo-card::after {
            content: '';
            position: absolute;
            inset: -1px;
            border-radius: 1.25rem;
            padding: 1.5px;
            background: linear-gradient(
                135deg,
                #f2c94c,
                transparent 40%,
                transparent 60%,
                #f2c94c
            );

            -webkit-mask:
                linear-gradient(#fff 0 0) content-box,
                linear-gradient(#fff 0 0);

            -webkit-mask-composite: xor;
            mask-composite: exclude;

            opacity: 0;
            transition: opacity 0.35s ease;
            pointer-events: none;
            z-index: -1;
        }

        .ngo-card:hover::after {
            opacity: 1;
        }


        /* Logo frame */
        .ngo-logo-frame {
            background: radial-gradient(
                circle at 35% 30%,
                #fffdf5,
                #f5f6f8 70%
            );

            box-shadow:
                inset 0 0 0 1px rgba(14, 36, 58, 0.06);
        }


        /* Decorative dot pattern on card top */
        .ngo-card-pattern {
            background-image:
                radial-gradient(
                    #0e243a 0.5px,
                    transparent 0.5px
                );

            background-size: 10px 10px;
            opacity: 0.05;
        }


        /* Modal animations */
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


    <!-- =========================================================
         HERO SECTION
         ========================================================= -->

    <section
        class="bg-gray-100 relative h-[400px] overflow-hidden"
        data-aos="fade-up"
        data-aos-duration="1000"
    >

        <!-- IMAGE -->
        <div class="absolute inset-y-0 left-0 w-[50%]">

            <img
                src="<?php echo e(asset('images/hero.jpg')); ?>"
                class="w-full h-full object-cover"
            >

            <!-- SMOOTH FADE INTO BACKGROUND -->
            <div
                class="absolute inset-0 bg-gradient-to-r from-transparent via-[#f3f4f6]/60 to-[#f3f4f6]"
            ></div>

        </div>


        <!-- RIGHT TEXT -->
        <div
            class="relative z-10 w-full md:w-1/2 ml-auto px-8 md:px-16 h-full flex flex-col justify-center"
        >

            <h1
                class="text-5xl font-extrabold text-[#f2c94c] mb-4"
                data-aos="fade-up"
                data-aos-duration="800"
                data-aos-delay="200"
            >
                Our Partner NGOs
            </h1>

            <h2
                class="text-2xl text-[#0e243a] mb-4"
                data-aos="fade-up"
                data-aos-duration="800"
                data-aos-delay="300"
            >
                Working together to bring hope to those in need
            </h2>

            <p
                class="text-gray-600 text-lg max-w-xl"
                data-aos="fade-up"
                data-aos-duration="800"
                data-aos-delay="400"
            >
                Meet the trusted NGOs that strive to make a positive impact in our communities.
            </p>

        </div>

    </section>


    <!-- =========================================================
         NGO LIST SECTION
         ========================================================= -->

    <section class="bg-[#0e243a] py-16 px-6">

        <div class="max-w-6xl mx-auto">


            <!-- SECTION TITLE -->
            <div
                class="text-center mb-14"
                data-aos="fade-up"
                data-aos-duration="800"
            >

                <span
                    class="inline-block text-[#f2c94c] font-semibold tracking-[0.2em] text-xs uppercase mb-3"
                >
                    Our Network
                </span>

                <h2 class="text-3xl font-bold text-white">
                    Our Partner NGOs
                </h2>

                <p class="text-gray-300 mt-2">
                    Meet the organizations working with SUHAY
                </p>

                <div
                    class="mt-5 mx-auto w-20 h-1 rounded-full bg-[#f2c94c]"
                ></div>

            </div>


            <!-- NGO CARDS -->
            <div class="flex flex-wrap justify-center gap-8">

                <?php $__currentLoopData = $ngos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $ngo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                    <div
                        class="ngo-card group bg-white rounded-3xl shadow-xl p-8 flex flex-col text-left border border-gray-100 hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 w-full lg:w-[calc(50%-1rem)] xl:w-[calc(33.333%-1.5rem)] max-w-xl"

                        data-aos="fade-up"
                        data-aos-duration="800"
                        data-aos-delay="<?php echo e(($index % 3 + 1) * 100); ?>"
                    >

                        <!-- LOGO + DIVIDER + NAME ROW -->
                        <div class="flex items-center gap-4">

                            <!-- NGO LOGO -->
                            <div
                                class="ngo-logo-frame relative w-28 h-28 md:w-32 md:h-32 flex-shrink-0 flex items-center justify-center rounded-full ring-1 ring-gray-100 group-hover:ring-2 group-hover:ring-[#f2c94c] transition-all duration-300"
                            >

                                <div
                                    class="ngo-card-pattern absolute inset-0 rounded-full"
                                ></div>

                                <img
                                    src="<?php echo e($ngo['logo_url'] ?? asset('images/suhayLogo.png')); ?>"
                                    class="relative w-[96px] h-[96px] md:w-[108px] md:h-[108px] object-contain drop-shadow-sm"
                                    alt="<?php echo e($ngo['name']); ?> Logo"
                                    onerror="this.src='<?php echo e(asset('images/suhayLogo.png')); ?>'"
                                >

                            </div>


                            <!-- THIN DIVIDER -->
                            <div class="w-px h-16 bg-gray-200 flex-shrink-0"></div>


                            <!-- NGO NAME -->
                            <h3
                                class="flex-1 min-w-0 text-xl md:text-2xl font-bold text-[#0e243a] leading-snug"
                            >
                                <?php echo e($ngo['name']); ?>

                            </h3>

                        </div>


                        <!-- OPTIONAL CATEGORY BADGE -->
                        <?php if(isset($ngo['category'])): ?>

                            <span
                                class="mt-5 self-start text-xs font-semibold tracking-wide text-[#0e243a] bg-[#f2c94c]/20 px-3.5 py-1.5 rounded-full uppercase"
                            >
                                <?php echo e($ngo['category']); ?>

                            </span>

                        <?php endif; ?>


                        <!-- DESCRIPTION -->
                        <?php if(isset($ngo['description'])): ?>

                            <p class="text-base text-gray-500 mt-5 line-clamp-2">
                                <?php echo e($ngo['description']); ?>

                            </p>

                        <?php endif; ?>


                        <!-- DIVIDER -->
                        <div class="w-full h-px bg-gray-100 my-6"></div>


                        <!-- LEARN MORE BUTTON -->
                        <button
                            type="button"
                            onclick="openModal(<?php echo e($ngo['id']); ?>)"
                            class="mt-auto w-full bg-[#f2c94c] hover:bg-[#0e243a] text-[#0e243a] hover:text-[#f2c94c] px-6 py-3.5 rounded-full font-semibold text-base tracking-wide transition-all duration-300 flex items-center justify-center gap-2 shadow-md shadow-[#f2c94c]/20 hover:shadow-[#0e243a]/20"
                        >

                            Learn More

                            <svg
                                class="w-5 h-5 group-hover:translate-x-1 transition-transform duration-300"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 5l7 7-7 7"
                                />

                            </svg>

                        </button>

                    </div>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            </div>

        </div>

    </section>


    <!-- =========================================================
         NGO DETAILS MODAL
         ========================================================= -->

    <div
        id="ngoModal"
        class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50 p-4"
    >

        <div
            id="modalContent"
            class="bg-white w-full max-w-3xl max-h-[90vh] overflow-y-auto rounded-3xl shadow-xl"
        >
        </div>

    </div>


    <!-- =========================================================
         NGO DATA
         ========================================================= -->

    <script>

        window.ngoData = <?php echo json_encode($ngos, 15, 512) ?>;

        window.suhayAssets = {

            logo: <?php echo json_encode(asset('images/suhayLogo.png'), 15, 512) ?>,

            phoneIcon: <?php echo json_encode(
                asset('images/VolunteerIcons/VPhone.png')
            , 15, 512) ?>,

            locationIcon: <?php echo json_encode(
                asset('images/VolunteerIcons/VLocation.png')
            , 15, 512) ?>

        };

    </script>


    <!-- NGO JAVASCRIPT -->
    <script src="<?php echo e(asset('js/ngos.js')); ?>"></script>


    <!-- =========================================================
         AOS INITIALIZATION
         ========================================================= -->

    <script>
        AOS.init({
            duration: 800,
            once: true,
            offset: 100,
            easing: 'ease-out-cubic'
        });
    </script>


</body>
</html><?php /**PATH D:\Acads\MEt.A-Project-SUHAY\resources\views/ngos.blade.php ENDPATH**/ ?>