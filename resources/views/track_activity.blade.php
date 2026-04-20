<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Activity</title>

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
    @include('components.nav')

    <div class="flex-1 p-8">
       @include('components.header', ['title' => 'Volunteer Management'])


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

            <a href="/events"
               class="bg-[#f2c94c] px-6 py-2 rounded-full font-semibold">
                Events
            </a>

            <a href="/track-activity"
               class="bg-gray-200 text-[#0e243a] px-6 py-2 rounded-full font-semibold">
                Track Activity
            </a>
        </div>

        <div class="bg-white rounded-2xl p-8 shadow-lg">

            <h2 class="text-2xl font-bold text-[#0e243a] mb-6">
                Track Volunteer Activity
            </h2>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Recent Activities</h3>

                    <div class="space-y-4">

                        <div class="flex items-center p-4 bg-gray-50 rounded-xl">
                            <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center mr-4">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>

                            <div>
                                <p class="font-semibold text-gray-900">Community Cleanup</p>
                                <p class="text-sm text-gray-600">Gabriel Tagaytay - 4 hours</p>
                                <p class="text-xs text-green-600">Completed - Jan 27, 2024</p>
                            </div>
                        </div>

                        <div class="flex items-center p-4 bg-gray-50 rounded-xl">
                            <div class="w-12 h-12 bg-yellow-500 rounded-full flex items-center justify-center mr-4">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>

                            <div>
                                <p class="font-semibold text-gray-900">Food Distribution</p>
                                <p class="text-sm text-gray-600">Zyann Lynn Mayo - 3.5 hours</p>
                                <p class="text-xs text-yellow-600">Ongoing - Jan 28, 2024</p>
                            </div>
                        </div>

                    </div>
                </div>

                <div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Activity Summary</h3>

                    <div class="grid grid-cols-2 gap-4">

                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-6 rounded-xl text-center">
                            <p class="text-2xl font-bold">156</p>
                            <p class="text-sm opacity-90">Total Hours</p>
                        </div>

                        <div class="bg-gradient-to-r from-green-500 to-green-600 text-white p-6 rounded-xl text-center">
                            <p class="text-2xl font-bold">12</p>
                            <p class="text-sm opacity-90">Activities</p>
                        </div>

                        <div class="bg-gradient-to-r from-purple-500 to-purple-600 text-white p-6 rounded-xl text-center">
                            <p class="text-2xl font-bold">28</p>
                            <p class="text-sm opacity-90">Volunteers Active</p>
                        </div>

                        <div class="bg-gradient-to-r from-orange-500 to-orange-600 text-white p-6 rounded-xl text-center">
                            <p class="text-2xl font-bold">95%</p>
                            <p class="text-sm opacity-90">Completion Rate</p>
                        </div>

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