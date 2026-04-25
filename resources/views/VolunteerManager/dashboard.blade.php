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
    <div class="group w-28 hover:w-56 bg-[#0e243a] min-h-screen flex flex-col py-6 text-white rounded-r-3xl transition-all duration-300 overflow-hidden">

        <a href="/sm-dashboard"
           class="flex items-center gap-4 px-4 py-4 hover:bg-[#f2c94c] hover:text-[#0e243a] rounded-xl mx-2">
            <img src="{{ asset('images/ServiceManagement/SMDash.png') }}" class="w-16 h-16 object-contain">
            <span class="opacity-0 group-hover:opacity-100">Dashboard</span>
        </a>

        <a href="/sm-ngos"
           class="flex items-center gap-4 px-4 py-4 hover:bg-[#f2c94c] hover:text-[#0e243a] rounded-xl mx-2">
            <img src="{{ asset('images/ServiceManagement/SMNGOs.png') }}" class="w-16 h-16 object-contain">
            <span class="opacity-0 group-hover:opacity-100">Volunteer Lists</span>
        </a>

        <a href="/sm-donations"
           class="flex items-center gap-4 px-4 py-4 hover:bg-[#f2c94c] hover:text-[#0e243a] rounded-xl mx-2">
            <img src="{{ asset('images/ServiceManagement/SMDonations.png') }}" class="w-16 h-16 object-contain">
            <span class="opacity-0 group-hover:opacity-100">Applications</span>
        </a>

        <a href="/sm-inventory"
           class="flex items-center gap-4 px-4 py-4 hover:bg-[#f2c94c] hover:text-[#0e243a] rounded-xl mx-2">
            <img src="{{ asset('images/ServiceManagement/SMInventory.png') }}" class="w-16 h-16 object-contain">
            <span class="opacity-0 group-hover:opacity-100">Activities</span>
        </a>

        <a href="/service-management"
           class="flex items-center gap-4 px-4 py-4 bg-[#1a3554] rounded-xl mx-2">
            <img src="{{ asset('images/ServiceManagement/SMVolunteers.png') }}" class="w-16 h-16 object-contain">
            <span class="opacity-0 group-hover:opacity-100">Categories</span>
        </a>

        <a href="/sm-reports"
           class="flex items-center gap-4 px-4 py-4 hover:bg-[#f2c94c] hover:text-[#0e243a] rounded-xl mx-2">
            <img src="{{ asset('images/ServiceManagement/SMReports.png') }}" class="w-16 h-16 object-contain">
            <span class="opacity-0 group-hover:opacity-100">Assign Tasks</span>
        </a>

        <a href="#" onclick="openLogoutModal()" 
           class="mt-auto flex items-center gap-4 px-4 py-4 hover:bg-[#f2c94c] hover:text-[#0e243a] rounded-xl mx-2">
            <img src="{{ asset('images/ServiceManagement/SMLogout.png') }}" class="w-16 h-16 object-contain">
            <span class="opacity-0 group-hover:opacity-100">Logout</span>
        </a>
    </div>

    <div class="flex-1 p-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-[#0e243a]">Volunteer Management</h1>

            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gray-300 rounded-full"></div>

                   <p class="text-[#0e243a]">
                        {{ session('user_name', 'Guest') }}
                    </p>
             </div>
            </div>

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

            <a href="/categories"
               class="bg-[#f2c94c] px-6 py-2 rounded-full font-semibold">
                Service Categories
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
                <div class="bg-[#4a5568] text-white rounded-2xl p-6 text-center 
                            hover:scale-105 hover:shadow-xl transition-all duration-300">

                    <div class="w-20 h-20 bg-white rounded-full mx-auto mb-3 flex items-center justify-center">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>

                    <h3 class="font-bold text-lg">Gabriel Tagaytay</h3>

                    <div class="text-xs mt-4 text-left space-y-1 bg-[#3b4252] p-3 rounded-lg">
                        <p><span class="font-semibold">Mobile No:</span> 0917-xxx-xxxx</p>
                        <p><span class="font-semibold">Email:</span> gabriel@email.com</p>
                        <p><span class="font-semibold">Total Hours:</span> 45 hrs</p>
                        <p><span class="font-semibold">Joined:</span> Jan 2024</p>
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

                <div class="bg-[#4a5568] text-white rounded-2xl p-6 text-center 
                            hover:scale-105 hover:shadow-xl transition-all duration-300">

                    <div class="w-20 h-20 bg-white rounded-full mx-auto mb-3 flex items-center justify-center">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>

                    <h3 class="font-bold text-lg">Zyann Lynn Mayo</h3>

                    <div class="text-xs mt-4 text-left space-y-1 bg-[#3b4252] p-3 rounded-lg">
                        <p><span class="font-semibold">Mobile No:</span> 0917-xxx-xxxx</p>
                        <p><span class="font-semibold">Email:</span> zyann@email.com</p>
                        <p><span class="font-semibold">Total Hours:</span> 32 hrs</p>
                        <p><span class="font-semibold">Joined:</span> Feb 2024</p>
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

                <div class="bg-[#4a5568] text-white rounded-2xl p-6 text-center 
                            hover:scale-105 hover:shadow-xl transition-all duration-300">
                    <h3 class="font-bold text-lg ">Elisha Sophia Borromeo</h3>
                </div>

                <div class="bg-[#4a5568] text-white rounded-2xl p-6 text-center 
                            hover:scale-105 hover:shadow-xl transition-all duration-300">
                    <h3 class="font-bold text-lg ">Consuelo Mercado</h3>
                </div>

                <div class="bg-[#4a5568] text-white rounded-2xl p-6 text-center 
                            hover:scale-105 hover:shadow-xl transition-all duration-300">
                    <h3 class="font-bold text-lg ">Arvie Sinocruz</h3>
                </div>
            </div>
        </div>  
    </div>
</div>

<div id="logoutModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl p-8 w-[350px] text-center shadow-lg">
        <h2 class="text-xl font-bold mb-4">Are you sure you want to logout?</h2>

        <div class="flex justify-center gap-4 mt-6">
            <button onclick="closeLogoutModal()" 
                class="px-6 py-2 bg-gray-300 rounded-full font-semibold hover:bg-gray-400">
                    No
            </button>

            <a href="/sm-logout" 
                class="px-6 py-2 bg-[#0e243a] text-yellow-400 rounded-full font-semibold hover:opacity-90">
                    Yes
            </a>
        </div>
    </div>
</div>

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