<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NGO Members</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>

<body class="bg-gray-200">

<div class="flex">
    @include('components.nav')

    <div class="flex-1 p-8">
        @include('components.header', ['title' => 'NGO Profile'])

        <div class="bg-[#0e243a] p-4 rounded-2xl flex gap-4 mb-10 flex-wrap">
            <a href="/sm-ngos"
               class="bg-[#f2c94c] text-[#0e243a] px-6 py-2 rounded-full font-semibold">
                Details
            </a>

            <a href="/ngo-members"
               class="bg-gray-200 text-[#0e243a] px-6 py-2 rounded-full font-semibold">
                Members
            </a>
        </div>

        <div class="text-center mb-16">
            <h1 class="text-3xl md:text-4xl font-extrabold text-[#0e243a]">
                {{ $ngo->name ?? 'NGO NAME' }} BOARD
            </h1>
        </div>

        <div class="flex flex-col items-center gap-16">

            <div class="text-center">
                <div class="w-36 h-36 bg-[#0e243a] rounded-full mx-auto flex items-center justify-center text-white text-4xl font-bold shadow-lg">
                    ZM
                </div>
                <h2 class="mt-4 text-xl font-bold text-[#0e243a]">Zyann Lynn C. Mayo</h2>
                <p class="text-gray-500 font-semibold">President</p>
            </div>

            <div class="text-center">
                <div class="w-32 h-32 bg-[#1a3554] rounded-full mx-auto flex items-center justify-center text-white text-3xl font-bold shadow">
                    EB
                </div>
                <h2 class="mt-3 text-lg font-bold text-[#0e243a]">Elisha Sophia S. Borromeo</h2>
                <p class="text-gray-500 font-semibold">Vice President</p>
            </div>

            <div class="flex flex-wrap justify-center gap-20">

                <div class="text-center">
                    <div class="w-28 h-28 bg-gray-600 rounded-full mx-auto flex items-center justify-center text-white text-2xl font-bold">
                        CM
                    </div>
                    <h2 class="mt-3 font-bold text-[#0e243a]">Consuelo B. Mercado</h2>
                    <p class="text-gray-500 text-sm font-semibold">Secretary</p>
                </div>

                <div class="text-center">
                    <div class="w-28 h-28 bg-gray-600 rounded-full mx-auto flex items-center justify-center text-white text-2xl font-bold">
                        GT
                    </div>
                    <h2 class="mt-3 font-bold text-[#0e243a]">Gabriel L. Tagaytay</h2>
                    <p class="text-gray-500 text-sm font-semibold">Treasurer</p>
                </div>

            </div>

            <div class="w-full mt-16">
                <h3 class="text-center text-xl font-bold text-[#0e243a] mb-10">
                    Members
                </h3>

                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-8 justify-items-center px-4">

                    <div class="text-center group hover:scale-105 transition-transform duration-300">
                        <div class="w-20 h-20 bg-gray-400 rounded-full mx-auto flex items-center justify-center text-white font-bold text-lg shadow-md group-hover:shadow-lg">
                            AS
                        </div>
                        <p class="mt-3 font-semibold text-[#0e243a] text-xs sm:text-sm leading-tight">Arvie M. Sinocruz</p>
                    </div>

                    <div class="text-center group hover:scale-105 transition-transform duration-300">
                        <div class="w-20 h-20 bg-gray-400 rounded-full mx-auto flex items-center justify-center text-white font-bold text-lg shadow-md group-hover:shadow-lg">
                            AS
                        </div>
                        <p class="mt-3 font-semibold text-[#0e243a] text-xs sm:text-sm leading-tight">Arvie M. Sinocruz</p>
                    </div>

                    <div class="text-center group hover:scale-105 transition-transform duration-300">
                        <div class="w-20 h-20 bg-gray-400 rounded-full mx-auto flex items-center justify-center text-white font-bold text-lg shadow-md group-hover:shadow-lg">
                            AS
                        </div>
                        <p class="mt-3 font-semibold text-[#0e243a] text-xs sm:text-sm leading-tight">Arvie M. Sinocruz</p>
                    </div>

                    <div class="text-center group hover:scale-105 transition-transform duration-300">
                        <div class="w-20 h-20 bg-gray-400 rounded-full mx-auto flex items-center justify-center text-white font-bold text-lg shadow-md group-hover:shadow-lg">
                            AS
                        </div>
                        <p class="mt-3 font-semibold text-[#0e243a] text-xs sm:text-sm leading-tight">Arvie M. Sinocruz</p>
                    </div>

                    <div class="text-center group hover:scale-105 transition-transform duration-300">
                        <div class="w-20 h-20 bg-gray-400 rounded-full mx-auto flex items-center justify-center text-white font-bold text-lg shadow-md group-hover:shadow-lg">
                            AS
                        </div>
                        <p class="mt-3 font-semibold text-[#0e243a] text-xs sm:text-sm leading-tight">Arvie M. Sinocruz</p>
                    </div>

                    <div class="text-center group hover:scale-105 transition-transform duration-300">
                        <div class="w-20 h-20 bg-gray-400 rounded-full mx-auto flex items-center justify-center text-white font-bold text-lg shadow-md group-hover:shadow-lg">
                            AS
                        </div>
                        <p class="mt-3 font-semibold text-[#0e243a] text-xs sm:text-sm leading-tight">Arvie M. Sinocruz</p>
                    </div>

                </div>
            </div>

        </div>

    </div>
</div>

@include('components.logout-modal')

<script>
function openLogoutModal() {
    document.getElementById('logoutModal').classList.remove('hidden');
    document.getElementById('logoutModal').classList.add('flex');
}

function closeLogoutModal() {
    document.getElementById('logoutModal').classList.add('hidden');
}
</script>

</body>
</html>