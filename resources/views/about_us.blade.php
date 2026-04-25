<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us | SUHAY</title>

    <link rel="icon" type="image/png" href="{{ asset('images/suhayLogo.png') }}">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-100">

    {{-- NAVBAR --}}
    @include('components.navbar')
    <section class="bg-gray-100 pt-0 pb-0">
        <div class="w-full flex flex-col md:flex-row items-center">

            <!-- LEFT IMAGE (HALF) -->
            <div class="w-full md:w-1/2 relative h-[400px]">
                <img src="{{ asset('images/hero.jpg') }}" 
                    class="w-full h-full object-cover">

                <!-- Gradient fade -->
                <div class="absolute inset-0 bg-gradient-to-r from-white via-white/70 to-transparent"></div>
            </div>

            <!-- RIGHT TEXT (HALF) -->
            <div class="w-full md:w-1/2 px-8 md:px-16 mt-10 md:mt-0">
                <h1 class="text-5xl font-extrabold text-[#f2c94c] mb-4">
                    About Us
                </h1>

                <h2 class="text-3xl text-[#0e243a] mb-4">
                    Welcome to Project Suhay
                </h2>

                <p class="text-gray-600 leading-relaxed text-lg max-w-xl">
                    "Suhay", a Filipino word, means to support. Project Suhay is a centralized
                    platform dedicated to supporting non-governmental organizations (NGOs)
                    in their mission to help those in need.
                </p>
            </div>

        </div>
    </section>

    {{-- MISSION / VISION / VALUES --}}
    <section class="bg-[#0e243a] text-white py-16 px-0">
        <div class="max-w-7xl mx-auto grid md:grid-cols-3 gap-10 text-center">

            <!-- MISSION -->
            <div>
                <img src="{{ asset('images/mission.png') }}" class="mx-auto mb-4 h-12">
                <h3 class="text-xl font-bold mb-3">Our Mission</h3>
                <p class="text-gray-300">
                    To support NGOs through a centralized system that enhances 
                    transparency, efficiency, and impact.
                </p>
            </div>

            <!-- VISION -->
            <div>
                <img src="{{ asset('images/vision.png') }}" class="mx-auto mb-4 h-12">
                <h3 class="text-xl font-bold mb-3">Our Vision</h3>
                <p class="text-gray-300">
                    To become a platform that empowers communities through 
                    transparent and impactful giving.
                </p>
            </div>

            <!-- VALUES -->
            <div>
                <img src="{{ asset('images/values.png') }}" class="mx-auto mb-4 h-12">
                <h3 class="text-xl font-bold mb-3">Our Values</h3>
                <p class="text-gray-300">
                    Transparency, accountability, and collaboration in delivering 
                    impactful solutions for communities.
                </p>
            </div>

        </div>
    </section>

</body>
</html>