<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assignments</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
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
            <span class="opacity-0 group-hover:opacity-100">NGOs</span>
        </a>

        <a href="/sm-donations"
           class="flex items-center gap-4 px-4 py-4 hover:bg-[#f2c94c] hover:text-[#0e243a] rounded-xl mx-2">
            <img src="{{ asset('images/ServiceManagement/SMDonations.png') }}" class="w-16 h-16 object-contain">
            <span class="opacity-0 group-hover:opacity-100">Donations</span>
        </a>

        <a href="/sm-inventory"
           class="flex items-center gap-4 px-4 py-4 hover:bg-[#f2c94c] hover:text-[#0e243a] rounded-xl mx-2">
            <img src="{{ asset('images/ServiceManagement/SMInventory.png') }}" class="w-16 h-16 object-contain">
            <span class="opacity-0 group-hover:opacity-100">Inventory</span>
        </a>

        <a href="/service-management"
           class="flex items-center gap-4 px-4 py-4 bg-[#1a3554] rounded-xl mx-2">
            <img src="{{ asset('images/ServiceManagement/SMVolunteers.png') }}" class="w-16 h-16 object-contain">
            <span class="opacity-0 group-hover:opacity-100">Volunteers</span>
        </a>

        <a href="/sm-reports"
           class="flex items-center gap-4 px-4 py-4 hover:bg-[#f2c94c] hover:text-[#0e243a] rounded-xl mx-2">
            <img src="{{ asset('images/ServiceManagement/SMReports.png') }}" class="w-16 h-16 object-contain">
            <span class="opacity-0 group-hover:opacity-100">Reports</span>
        </a>

        <a href="#" onclick="openLogoutModal()" class="mt-auto flex items-center gap-4 px-4 py-4 hover:bg-[#f2c94c] hover:text-[#0e243a] rounded-xl mx-2">
            <img src="{{ asset('images/ServiceManagement/SMLogout.png') }}" class="w-16 h-16 object-contain">
            <span class="opacity-0 group-hover:opacity-100">Logout</span>
        </a>
    </div>

    <div class="flex-1 p-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-[#0e243a]">Volunteer Management</h1>

            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gray-300 rounded-full"></div>
                <p class="text-[#0e243a]">Consuelo Mercado</p>
            </div>
        </div>

        <div class="bg-[#0e243a] p-4 rounded-2xl flex gap-4 mb-6 flex-wrap">
            <a href="/service-management"
           class="bg-[#f2c94c] px-6 py-2 rounded-full font-semibold">
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

        <div class="bg-[#0e243a] p-6 rounded-3xl">
            <div class="flex justify-between items-center mb-4 flex-wrap gap-3">
                <div class="flex items-center gap-2">
                    <input type="text"
                           placeholder="Search Programs..."
                           class="p-2 rounded-md border outline-none">
                    <button class="bg-[#0e243a] text-white px-3 py-2 rounded-full">🔍</button>
                </div>
                <input type="date" class="p-2 rounded-md border">
            </div>

            <div class="space-y-4">
                <div class="bg-gray-300 p-5 rounded-xl flex justify-between items-center transition-all duration-300 hover:scale-[1.02] hover:shadow-xl hover:bg-[#f2c94c]">
                    <div>
                        <p class="font-bold text-lg text-[#0e243a] mb-1">KALINGA PROGRAM</p>
                        <p class="text-sm text-gray-700">March 22, 2026</p>
                        <p class="text-sm text-gray-700">9:00 am – 4:00 pm</p>
                        <p class="text-sm text-gray-700">Tayuman, Sta. Cruz, Manila</p>
                    </div>
                    <div class="text-right">
                        <p>Required No. of Volunteers: <span class="border px-2">20</span></p>
                        <p class="mt-2">
                            Status:
                            <span class="bg-green-500 text-white px-4 py-1 rounded-full text-sm">Complete</span>
                        </p>
                    </div>
                </div>

                <div class="bg-gray-300 p-5 rounded-xl flex justify-between items-center transition-all duration-300 hover:scale-[1.02] hover:shadow-xl hover:bg-[#f2c94c]">
                    <div>
                        <p class="font-bold text-lg text-[#0e243a] mb-1">TUBIG KALINGA</p>
                        <p class="text-sm text-gray-700">March 30, 2026</p>
                        <p class="text-sm text-gray-700">7:00 am – 9:00 pm</p>
                        <p class="text-sm text-gray-700">Bambang, Sta. Cruz, Manila</p>
                    </div>
                    <div class="text-right">
                        <p>Required No. of Volunteers: <span class="border px-2">10</span></p>
                        <p class="mt-2">
                            Status:
                            <span class="bg-yellow-400 px-4 py-1 rounded-full text-sm">Open</span>
                        </p>
                    </div>
                </div>

                <div class="bg-gray-300 p-5 rounded-xl flex justify-between items-center transition-all duration-300 hover:scale-[1.02] hover:shadow-xl hover:bg-[#f2c94c]">
                    <div>
                        <p class="font-bold text-lg text-[#0e243a] mb-1">POST-DISASTER RELIEF</p>
                        <p class="text-sm text-gray-700">April 16, 2026</p>
                        <p class="text-sm text-gray-700">7:00 am – 1:00 pm</p>
                        <p class="text-sm text-gray-700">Bambang, Sta. Cruz, Manila</p>
                    </div>
                    <div class="text-right">
                        <p>Required No. of Volunteers: <span class="border px-2">17</span></p>
                        <p class="mt-2">
                            Status:
                            <span class="bg-[#0e243a] text-white px-4 py-1 rounded-full text-sm">Full</span>
                        </p>
                    </div>
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