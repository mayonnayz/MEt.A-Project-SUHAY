<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applications</title>

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
            <a href="/service-management" class="bg-[#f2c94c] px-6 py-2 rounded-full font-semibold">Volunteer Lists</a>
            <a href="/applications" class="bg-gray-200 text-[#0e243a] px-6 py-2 rounded-full font-semibold">Applications</a>
            <a href="/assignments" class="bg-[#f2c94c] px-6 py-2 rounded-full font-semibold">Assignments</a>
            <a href="/events"
               class="bg-[#f2c94c] px-6 py-2 rounded-full font-semibold">
                Events
            </a>
            <a href="/track-activity" class="bg-[#f2c94c] px-6 py-2 rounded-full font-semibold">Track Activity</a>

        </div>

        <div class="bg-[#0e243a] p-6 rounded-2xl">
            <div class="bg-gray-300 p-6 rounded-xl mb-4 flex justify-between flex-wrap gap-4">
                <div>
                    <p>Pending Application/s:</p>
                    <p>Approved Application/s:</p>
                    <p>Rejected Application/s:</p>
                </div>

                <div class="flex items-center gap-4">
                    <span>Filter:</span>
                    <input type="text" placeholder="Skills" class="p-2 border rounded-md">
                    <input type="date" class="p-2 border rounded-md">
                </div>
            </div>

            <div class="bg-gray-200 rounded-xl p-4 overflow-x-auto">
                <table class="w-full text-center border border-gray-400">
                    <thead class="bg-gray-300">
                        <tr>
                            <th class="p-3 border">#</th>
                            <th class="p-3 border">Name</th>
                            <th class="p-3 border">Status</th>
                            <th class="p-3 border">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                    @forelse($applications as $index => $app)

                    <tr class="bg-white border hover:bg-gray-100">

                        <td class="p-3 border">
                            {{ $index + 1 }}
                        </td>

                        <td class="p-3 border">
                            {{ $app->first_name }} {{ $app->last_name }}
                        </td>

                        <td class="p-3 border">
                            <span class="px-3 py-1 rounded-full bg-yellow-200 text-yellow-800 text-sm">
                                Pending
                            </span>
                        </td>

                        <td class="p-3 border space-x-2">

                            <button 
                                class="bg-blue-500 px-4 py-1 rounded-full text-white"
                                onclick='openAppModal(@json($app))'
                            >
                                View
                            </button>

                            <button class="bg-green-500 px-4 py-1 rounded-full text-white">
                                Approve
                            </button>

                            <button class="bg-red-500 px-4 py-1 rounded-full text-white">
                                Reject
                            </button>

                        </td>

                    </tr>

                    @empty
                    <tr>
                        <td colspan="4" class="p-4 text-gray-500">
                            No pending applications found.
                        </td>
                    </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@include('components.application-modal')
@include('components.logout-modal')
<script>
function openAppModal(data) {
    const modal = document.getElementById('appModal');
    const box = document.getElementById('modalBox');

    // show modal
    modal.classList.remove('hidden');
    modal.classList.add('flex');

    // reset animation
    box.classList.add('scale-95');
    setTimeout(() => {
        box.classList.remove('scale-95');
    }, 10);

    // ✅ RESET TO PAGE 1 EVERY TIME
    document.getElementById('page1').classList.remove('hidden');
    document.getElementById('page2').classList.add('hidden');

    // fill data
    document.getElementById('first_name').innerText = data.first_name ?? '';
    document.getElementById('last_name').innerText = data.last_name ?? '';
    document.getElementById('address').innerText = data.address ?? '';
    document.getElementById('contact').innerText = data.contact_number ?? '';
    document.getElementById('email').innerText = data.email ?? '';
    document.getElementById('dob').innerText = data.birth_date ?? '';
}

function closeModal() {
    const modal = document.getElementById('appModal');
    const box = document.getElementById('modalBox');

    // animate out
    box.classList.add('scale-95');

    setTimeout(() => {
        modal.classList.add('hidden');
    }, 150);
}

function nextPage() {
    document.getElementById('page1').classList.add('hidden');
    document.getElementById('page2').classList.remove('hidden');
}

function prevPage() {
    document.getElementById('page2').classList.add('hidden');
    document.getElementById('page1').classList.remove('hidden');
}
</script>
</body>
</html>