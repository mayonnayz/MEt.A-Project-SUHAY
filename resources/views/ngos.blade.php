<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NGOs | SUHAY</title>

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

<!-- ================= HERO SECTION ================= -->
<section class="bg-gray-100">
    <div class="w-full flex flex-col md:flex-row items-center">

        <!-- LEFT TEXT -->
        <div class="w-full md:w-1/2 px-8 md:px-16 py-16">
            <h1 class="text-5xl font-extrabold text-[#f2c94c] mb-4">
                Our Partner NGOs
            </h1>

            <h2 class="text-2xl text-[#0e243a] mb-4">
                Working together to bring hope to those in need
            </h2>

            <p class="text-gray-600 text-lg max-w-xl">
                Meet the trusted NGOs that strive to make a positive impact in our communities.
            </p>
        </div>

        <!-- RIGHT IMAGE -->
        <div class="w-full md:w-1/2 relative h-[400px]">
            <img src="{{ asset('images/hero.jpg') }}"
                 class="w-full h-full object-cover">

            <div class="absolute inset-0 bg-gradient-to-l from-white via-white/70 to-transparent"></div>
        </div>

    </div>
</section>

<!-- ================= NGO LIST SECTION ================= -->
<section class="bg-[#0e243a] py-16 px-6">

    <div class="max-w-6xl mx-auto space-y-8">

        @foreach ($ngos as $ngo)

            <div class="bg-white rounded-2xl shadow-md flex flex-col md:flex-row items-center p-6 gap-6">

                <!-- NGO LOGO -->
                <img src="{{ $ngo['logo'] }}" 
                     class="w-24 h-24 object-cover rounded-full border">

                <!-- NGO INFO -->
                <div class="flex-1">
                    <h3 class="text-xl font-bold text-[#0e243a]">
                        {{ $ngo['name'] }}
                    </h3>

                    <p class="text-gray-600 mt-1">
                        {{ $ngo['description'] }}
                    </p>

                    <div class="text-sm text-gray-500 mt-2">
                        📍 {{ $ngo['address'] }} <br>
                        📞 {{ $ngo['contact_number'] }}
                    </div>
                </div>

                <!-- BUTTON -->
                <a href="#" 
                   class="bg-[#f2c94c] hover:bg-yellow-500 text-white px-6 py-3 rounded-full font-semibold transition">
                    Learn More
                </a>

            </div>

        @endforeach

    </div>

</section>

</body>
</html>