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
            class="bg-gray-200 px-6 py-2 rounded-full font-semibold">
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