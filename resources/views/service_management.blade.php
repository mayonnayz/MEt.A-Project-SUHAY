<!DOCTYPE html> 

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SUHAY</title>

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
       @include('components.header', ['title' => 'Volunteer Management'])

        <div class="bg-[#0e243a] p-4 rounded-2xl flex gap-4 mb-6 flex-wrap">
            <a href="/service-management"
               class="bg-gray-200 text-[#0e243a] px-6 py-2 rounded-full font-semibold">
                Volunteer Lists
            </a>

            <a href="/applications"
               class="bg-[#f2c94c] px-6 py-2 rounded-full font-semibold">
                Applications
            </a>

            <a href="/assignments"
               class="bg-[#f2c94c] px-6 py-2 rounded-full font-semibold">
                Assignments
            </a>

            <a href="/events"
               class="bg-[#f2c94c] px-6 py-2 rounded-full font-semibold">
                Events
            </a>

            <a href="/track-activity"
               class="bg-[#f2c94c] px-6 py-2 rounded-full font-semibold">
                Track Activity
            </a>
        </div>

        <div>

            <div class="bg-gray-300 rounded-xl p-6 mb-6 flex justify-between flex-wrap gap-4">
                <div>
                    <p class="font-semibold text-gray-700">Total Volunteer/s:</p>
                    <p class="mt-2 font-semibold text-gray-700">Active Volunteers:</p>
                </div>
                <div>
                    <p class="font-semibold text-gray-700">Available Volunteers:</p>
                    <p class="mt-2 font-semibold text-gray-700">Total Hours Rendered:</p>
                </div>
            </div>

            <div class="flex justify-center mb-4">
                <input type="text" placeholder="Search Volunteer...."
                       class="w-full md:w-1/3 p-2 border rounded-l-md focus:outline-none focus:ring-2 focus:ring-[#0e243a]">
                <button class="bg-[#0e243a] text-white px-6 py-2 rounded-r-md hover:bg-[#1a3554] transition">
                    🔍
                </button>
            </div>

            <div class="flex items-center justify-center gap-4 mb-6 flex-wrap">
                <p class="font-semibold text-gray-700">Filter:</p>
                <input type="text" placeholder="Skills" class="border p-2 rounded-md">
                <input type="text" placeholder="Availability" class="border p-2 rounded-md">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">

    @foreach($volunteers as $volunteer)
    <div class="bg-[#4a5568] text-white rounded-2xl p-6 text-center 
                hover:scale-105 hover:shadow-xl transition-all duration-300">

        <div class="w-20 h-20 bg-white rounded-full mx-auto mb-3 flex items-center justify-center">
            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                </path>
            </svg>
        </div>

        <h3 class="font-bold text-lg">
            {{ $volunteer->first_name }} {{ $volunteer->last_name }}
        </h3>

        <div class="text-xs mt-4 text-left space-y-1 bg-[#3b4252] p-3 rounded-lg">
            <p><span class="font-semibold">Email:</span> {{ $volunteer->email }}</p>
            <p><span class="font-semibold">Role:</span> {{ $volunteer->roles }}</p>
            <p><span class="font-semibold">Status:</span> Active Volunteer</p>
        </div>

        <div class="flex justify-between mt-4 gap-2">
            <button class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-full text-xs font-semibold transition">
                Deactivate
            </button>
            <button class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-full text-xs font-semibold transition">
                Edit
            </button>
        </div>

    </div>
    @endforeach

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