<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SUHAY</title>
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

<nav class="bg-white shadow-md">
    <div class="w-full px-8 py-6 flex justify-between items-center">
        <div class="flex items-center gap-0 ml-2">
            <img src="{{ asset('images/suhayLogo.png') }}" class="h-16 w-auto">
            <h1 class="text-4xl font-bold text-[#0e243a]"></h1>
        </div>

        <div class="hidden md:flex items-center gap-10 font-bold text-xl mr-2">
            <a href="/" class="text-[#0e243a] px-4 py-2 rounded-lg hover:bg-[#f2c94c] hover:text-white transition">Home</a>
            <a href="#" class="text-[#0e243a] px-4 py-2 rounded-lg hover:bg-[#f2c94c] hover:text-white transition">About</a>
            <a href="#" class="text-[#0e243a] px-4 py-2 rounded-lg hover:bg-[#f2c94c] hover:text-white transition">NGOs</a>
            <a href="#" class="text-[#0e243a] px-4 py-2 rounded-lg hover:bg-[#f2c94c] hover:text-white transition">Impact</a>
            <a href="/volunteer-page" class="text-[#0e243a] px-4 py-2 rounded-lg hover:bg-[#f2c94c] hover:text-white transition">Get Involved</a>
            <a href="#" class="text-[#0e243a] px-4 py-2 rounded-lg hover:bg-[#f2c94c] hover:text-white transition">Donate</a>
            <a href="/login-page" class="text-[#0e243a] px-4 py-2 rounded-lg hover:bg-[#f2c94c] hover:text-white transition">Log in</a>
        </div>
    </div>
</nav>

<section class="relative h-[650px] flex items-center">
    <img src="{{ asset('images/hero.jpg') }}" class="absolute inset-0 w-full h-full object-cover">

    <div class="absolute inset-0 bg-gradient-to-r from-[#0e243a]/95 to-transparent"></div>

    <div class="relative z-10 w-full pl-16 pt-44 text-white">
        <h2 class="text-7xl font-extrabold mb-4">Make a Difference.</h2>
        <h3 class="text-6xl text-[#f2c94c] mb-6">Donate Today!</h3>
        <p class="max-w-2xl mb-8 text-xl text-gray-200">
            Your support can save lives. Join us in helping those in need.
        </p>

        <div class="flex gap-8 mt-10">
            <button onclick="openModal()" class="bg-[#f2c94c] hover:bg-yellow-500 text-white px-12 py-5 rounded-full font-semibold text-xl transition duration-300 transform hover:scale-105">
                Donate Now
            </button>
            <a href="#" class="bg-blue-700 hover:bg-blue-800 px-12 py-5 rounded-full font-semibold text-xl border border-white transition duration-300 transform hover:scale-105">
                Learn More
            </a>
        </div>
    </div>
</section>

<div id="donateModal" class="fixed inset-0 hidden items-center justify-center z-50">
    <div id="backdrop" class="absolute inset-0 bg-black/0 transition duration-300"></div>

    <div id="modalBox" class="bg-white rounded-2xl w-full max-w-md p-8 relative transform scale-95 opacity-0 transition duration-300">
        <button onclick="closeModal()" class="absolute top-3 right-4 text-gray-500 text-xl">&times;</button>
        
        <h2 class="text-2xl font-bold mb-6 text-center text-[#0e243a]">Donation Form</h2>

        <form>
            <input type="text" placeholder="Full Name" class="w-full mb-4 p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400">
            <input type="email" placeholder="Email" class="w-full mb-4 p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400">
            <input type="number" placeholder="Amount (₱)" class="w-full mb-4 p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400">

            <button type="submit" class="w-full bg-[#f2c94c] hover:bg-yellow-500 text-white py-3 rounded-lg font-semibold transition duration-300">
                Donate
            </button>
        </form>
    </div>
</div>

<script>
function openModal() {
    const modal = document.getElementById('donateModal');
    const box = document.getElementById('modalBox');
    const backdrop = document.getElementById('backdrop');

    modal.classList.remove('hidden');
    modal.classList.add('flex');

    setTimeout(() => {
        backdrop.classList.remove('bg-black/0');
        backdrop.classList.add('bg-black/50');

        box.classList.remove('scale-95', 'opacity-0');
        box.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function closeModal() {
    const modal = document.getElementById('donateModal');
    const box = document.getElementById('modalBox');
    const backdrop = document.getElementById('backdrop');

    backdrop.classList.remove('bg-black/50');
    backdrop.classList.add('bg-black/0');

    box.classList.remove('scale-100', 'opacity-100');
    box.classList.add('scale-95', 'opacity-0');

    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }, 300);
}
</script>

</body>
</html>