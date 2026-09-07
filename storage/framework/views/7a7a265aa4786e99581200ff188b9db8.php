<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Impact | SUHAY</title>

    <link rel="icon" type="image/png" href="<?php echo e(asset('images/suhayLogo.png')); ?>">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        
        .hover-scale {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .hover-scale:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
        
        html {
            scroll-behavior: smooth;
        }
    </style>
</head>

<body class="bg-gray-100">

    <?php echo $__env->make('components.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <section class="relative h-[420px] w-full" data-aos="fade-up" data-aos-duration="1000">

        <img src="<?php echo e(asset('images/hero.jpg')); ?>" 
             class="w-full h-full object-cover">

        <div class="absolute inset-0 
                    bg-gradient-to-r 
                    from-[#0e243a] via-[#0e243a]/80 to-transparent">
        </div>

        <div class="absolute inset-0 flex items-center">
            <div class="px-8 md:px-16 max-w-xl">
                <h1 class="text-5xl font-extrabold text-white mb-4">
                    Our Impact
                </h1>

                <p class="text-xl text-[#f2c94c]">
                    See how your support is transforming lives and empowering communities.
                </p>
            </div>
        </div>

    </section>

    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto grid md:grid-cols-4 gap-8 text-center">
            
            <div data-aos="fade-up" data-aos-duration="800" data-aos-delay="100">
                <h2 class="text-4xl font-extrabold text-[#f2c94c] group">
                    <span class="relative inline-block">
                        10,245<span class="text-sm">+</span>
                        <span class="absolute -bottom-1 left-0 w-0 h-1 bg-[#f2c94c] group-hover:w-full transition-all duration-500"></span>
                    </span>
                </h2>
                <p class="text-gray-600 mt-2 font-medium">Lives Impacted</p>
            </div>

            <div data-aos="fade-up" data-aos-duration="800" data-aos-delay="200">
                <h2 class="text-4xl font-extrabold text-[#f2c94c] group">
                    <span class="relative inline-block">
                        ₱1.2M<span class="text-sm">+</span>
                        <span class="absolute -bottom-1 left-0 w-0 h-1 bg-[#f2c94c] group-hover:w-full transition-all duration-500"></span>
                    </span>
                </h2>
                <p class="text-gray-600 mt-2 font-medium">Donations Raised</p>
            </div>

            <div data-aos="fade-up" data-aos-duration="800" data-aos-delay="300">
                <h2 class="text-4xl font-extrabold text-[#f2c94c] group">
                    <span class="relative inline-block">
                        3,500<span class="text-sm">+</span>
                        <span class="absolute -bottom-1 left-0 w-0 h-1 bg-[#f2c94c] group-hover:w-full transition-all duration-500"></span>
                    </span>
                </h2>
                <p class="text-gray-600 mt-2 font-medium">Relief Goods Distributed</p>
            </div>

            <div data-aos="fade-up" data-aos-duration="800" data-aos-delay="400">
                <h2 class="text-4xl font-extrabold text-[#f2c94c] group">
                    <span class="relative inline-block">
                        25<span class="text-sm">+</span>
                        <span class="absolute -bottom-1 left-0 w-0 h-1 bg-[#f2c94c] group-hover:w-full transition-all duration-500"></span>
                    </span>
                </h2>
                <p class="text-gray-600 mt-2 font-medium">Partner NGOs</p>
            </div>

        </div>
    </section>

    <section class="py-16 bg-gray-100">
        <div class="max-w-7xl mx-auto text-center mb-10" data-aos="fade-up" data-aos-duration="800">
            <h2 class="text-4xl font-bold text-[#0e243a]">Success Stories</h2>
            <p class="text-gray-600 mt-2">Real stories from communities we've helped</p>
        </div>

        <div class="max-w-7xl mx-auto grid md:grid-cols-3 gap-8 px-6">
            
            <div class="bg-white p-8 rounded-2xl shadow-lg hover-scale cursor-pointer border border-gray-100" 
                 data-aos="fade-up" data-aos-duration="800" data-aos-delay="100">
                <div class="flex items-start space-x-3 mb-4">
                    <div class="w-2 h-12 bg-gradient-to-b from-[#f2c94c] to-[#f2c94c]/70 rounded-full"></div>
                    <p class="text-gray-600 italic flex-1">
                        "Through Suhay, we were able to provide meals to over 200 families in need."
                    </p>
                </div>
                <h4 class="font-semibold text-[#0e243a] text-lg">– TamsNGO</h4>
            </div>

            <div class="bg-white p-8 rounded-2xl shadow-lg hover-scale cursor-pointer border border-gray-100" 
                 data-aos="fade-up" data-aos-duration="800" data-aos-delay="200">
                <div class="flex items-start space-x-3 mb-4">
                    <div class="w-2 h-12 bg-gradient-to-b from-[#f2c94c] to-[#f2c94c]/70 rounded-full"></div>
                    <p class="text-gray-600 italic flex-1">
                        "Donations helped us rebuild homes after the typhoon."
                    </p>
                </div>
                <h4 class="font-semibold text-[#0e243a] text-lg">– SampleNGO</h4>
            </div>

            <div class="bg-white p-8 rounded-2xl shadow-lg hover-scale cursor-pointer border border-gray-100" 
                 data-aos="fade-up" data-aos-duration="800" data-aos-delay="300">
                <div class="flex items-start space-x-3 mb-4">
                    <div class="w-2 h-12 bg-gradient-to-b from-[#f2c94c] to-[#f2c94c]/70 rounded-full"></div>
                    <p class="text-gray-600 italic flex-1">
                        "Volunteers made our outreach programs successful and impactful."
                    </p>
                </div>
                <h4 class="font-semibold text-[#0e243a] text-lg">– Community Org</h4>
            </div>

        </div>
    </section>

    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto text-center mb-10" data-aos="fade-up" data-aos-duration="800">
            <h2 class="text-4xl font-bold text-[#0e243a]">NGO Contributions</h2>
        </div>

        <div class="max-w-5xl mx-auto space-y-6 px-6">
            
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 p-8 rounded-2xl shadow-lg hover-scale cursor-pointer border border-gray-200" 
                 data-aos="fade-up" data-aos-duration="800" data-aos-delay="100">
                <div class="flex items-center space-x-4 mb-4">
                    <div class="w-3 h-3 bg-[#f2c94c] rounded-full"></div>
                    <h3 class="text-2xl font-bold text-[#0e243a]">TamsNGO</h3>
                </div>
                <div class="space-y-2">
                    <p class="text-gray-700 font-medium flex items-center">
                        <span class="w-5 h-5 bg-green-400 rounded-full flex items-center justify-center text-white text-xs font-bold mr-3">✔</span>
                        500 families helped
                    </p>
                    <p class="text-gray-700 font-medium flex items-center">
                        <span class="w-5 h-5 bg-green-400 rounded-full flex items-center justify-center text-white text-xs font-bold mr-3">✔</span>
                        3 outreach programs completed
                    </p>
                </div>
            </div>

            <div class="bg-gradient-to-r from-gray-50 to-gray-100 p-8 rounded-2xl shadow-lg hover-scale cursor-pointer border border-gray-200" 
                 data-aos="fade-up" data-aos-duration="800" data-aos-delay="200">
                <div class="flex items-center space-x-4 mb-4">
                    <div class="w-3 h-3 bg-[#f2c94c] rounded-full"></div>
                    <h3 class="text-2xl font-bold text-[#0e243a]">SampleNGO</h3>
                </div>
                <div class="space-y-2">
                    <p class="text-gray-700 font-medium flex items-center">
                        <span class="w-5 h-5 bg-green-400 rounded-full flex items-center justify-center text-white text-xs font-bold mr-3">✔</span>
                        200 volunteers mobilized
                    </p>
                    <p class="text-gray-700 font-medium flex items-center">
                        <span class="w-5 h-5 bg-green-400 rounded-full flex items-center justify-center text-white text-xs font-bold mr-3">✔</span>
                        1,000 relief goods distributed
                    </p>
                </div>
            </div>

        </div>
    </section>

    <section class="py-20 bg-[#0e243a] text-center text-white" 
             data-aos="fade-up" data-aos-duration="1000">
        <div class="max-w-4xl mx-auto px-6">
            <h2 class="text-5xl font-bold mb-6 text-white">
                Be Part of the Impact
            </h2>
            <p class="mb-8 text-xl text-gray-200 max-w-2xl mx-auto leading-relaxed">
                Join us in making a difference in the lives of others.
            </p>

            <a href="/donate" 
               class="inline-flex items-center px-12 py-4 rounded-full font-bold text-lg bg-[#f2c94c] hover:bg-[#f2c94c]/90 text-[#0e243a] shadow-xl hover:shadow-2xl hover:-translate-y-1 transform transition-all duration-300 hover:scale-[1.02]">
                Donate Now
            </a>
        </div>
    </section>

    <script>
        AOS.init({
            duration: 800,
            once: true,
            offset: 100,
            easing: 'ease-out-cubic'
        });
    </script>

</body>
</html><?php /**PATH C:\sysands\MEt.A-Project-SUHAY-main\MEt.A-Project-SUHAY-main\resources\views/impact.blade.php ENDPATH**/ ?>