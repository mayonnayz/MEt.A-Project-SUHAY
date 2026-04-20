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

<!-- NAVBAR -->
<nav class="bg-white shadow-md">
    <div class="w-full px-8 py-6 flex justify-between items-center">
        <div class="flex items-center gap-0 ml-2">
            <img src="{{ asset('images/suhayLogo.png') }}" class="h-16 w-auto">
        </div>

        <div class="hidden md:flex items-center gap-10 font-bold text-xl mr-2">
            <a href="/" class="text-[#0e243a] px-4 py-2 rounded-lg hover:bg-[#f2c94c] hover:text-white transition">Home</a>
            <a href="#" class="text-[#0e243a] px-4 py-2 rounded-lg hover:bg-[#f2c94c] hover:text-white transition">About</a>
            <a href="#" class="text-[#0e243a] px-4 py-2 rounded-lg hover:bg-[#f2c94c] hover:text-white transition">NGOs</a>
            <a href="#" class="text-[#0e243a] px-4 py-2 rounded-lg hover:bg-[#f2c94c] hover:text-white transition">Impact</a>
            <a href="/volunteer-page" class="bg-[#f2c94c] text-white px-4 py-2 rounded-lg">Get Involved</a>
            <a href="#" class="text-[#0e243a] px-4 py-2 rounded-lg hover:bg-[#f2c94c] hover:text-white transition">Donate</a>
            <a href="/login-page" class="text-[#0e243a] px-4 py-2 rounded-lg hover:bg-[#f2c94c] hover:text-white transition">Log in</a>
        </div>
    </div>
</nav>

<!-- HEADER USING HERO IMAGE -->
<section class="relative h-[250px] flex items-center">
    <img src="{{ asset('images/hero.jpg') }}" class="absolute inset-0 w-full h-full object-cover">

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

            @foreach ($categories as $category)
                <div class="bg-white rounded-3xl shadow-md p-8 text-center fade-up">

                    <h2 class="text-2xl font-bold text-[#0e243a] mb-4">
                        {{ $category['name'] }}
                    </h2>

                    <p class="text-gray-600 mb-6">
                        {{ $category['description'] }}
                    </p>

                   <a href="/volunteer-application-form"
                    class="bg-[#d4a017] text-white px-10 py-3 rounded-full text-xl hover:bg-yellow-600 transition inline-block">
                        Volunteer Now
                    </a>

                </div>
            @endforeach
    </div>
</section>

</body>
</html>