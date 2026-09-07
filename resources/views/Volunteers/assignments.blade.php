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
        @include('components.header', ['title' => 'Assignments'])

        <div class="bg-[#0e243a] p-4 rounded-2xl flex gap-4 mb-6 flex-wrap">

            <a href="/volunteer/events"
               class="bg-[#f2c94c] text-[#0e243a] px-6 py-2 rounded-full font-semibold hover:opacity-90 transition">
                Events
            </a>

            <a href="/volunteer/assignments"
               class="bg-gray-200 text-[#0e243a] px-6 py-2 rounded-full font-semibold">
                Assignments
            </a>

        </div>

        <div class="bg-white rounded-[22px] border-[10px] border-[#0e243a] p-6 sm:p-8">

            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">

                <div class="flex items-center bg-white border-2 border-[#0e243a] rounded-2xl px-4 py-2 w-full md:w-1/2">
                    <input id="searchInput" type="text"
                        placeholder="Search NGO or Event..."
                        class="w-full outline-none text-sm font-medium placeholder:text-gray-500">
                </div>

                <div class="flex gap-3 flex-wrap justify-end">

                    <select id="statusFilter"
                        class="border-2 border-gray-300 rounded-xl px-4 py-2 text-sm">
                        <option value="">All Status</option>
                        <option value="On Going">On Going</option>
                        <option value="Completed">Completed</option>
                    </select>

                    <input id="dateFilter" type="date"
                        class="border-2 border-gray-300 rounded-xl px-4 py-2 text-sm">

                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full border border-gray-300 text-sm text-center">

                    <thead class="bg-gray-100 text-[#0e243a] font-semibold text-center">
                        <tr>
                            <th class="p-3 border text-center">No.</th>
                            <th class="p-3 border text-center">NGO</th>
                            <th class="p-3 border text-center">Event</th>
                            <th class="p-3 border text-center">Date</th>
                            <th class="p-3 border text-center">Activity Assigned</th>
                            <th class="p-3 border text-center">Status</th>
                        </tr>
                    </thead>

                    <tbody id="assignmentTable">

                    @forelse($assignments as $index => $a)
                    <tr class="assignment-row cursor-pointer hover:bg-gray-100 transition text-center"
                        onclick="goToApplication('{{ $a['event_id'] }}')">

                        <td class="p-3 border text-center">{{ $index + 1 }}</td>

                        <td class="p-3 border font-semibold text-[#0e243a] text-center">
                            {{ $a['ngo_name'] }}
                        </td>

                        <td class="p-3 border text-center">
                            {{ $a['event_name'] }}
                        </td>

                        <td class="p-3 border text-center">
                            {{ \Carbon\Carbon::parse($a['date'])->format('F d, Y') }}
                        </td>

                        <td class="p-3 border text-center">
                            {{ $a['activity'] }}
                        </td>

                        <td class="p-3 border status text-center font-semibold">
                            @if($a['status'] === 'Completed')
                                <span class="text-green-600">
                                    Completed
                                </span>
                            @else
                                <span class="text-orange-500">
                                    On Going
                                </span>
                            @endif
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center p-6 text-gray-500">
                            No assignments found.
                        </td>
                    </tr>
                    @endforelse

                    </tbody>
                </table>
            </div>

        </div>

    </div>
</div>

@include('components.logout-modal')

<script>

const searchInput = document.getElementById("searchInput");
const statusFilter = document.getElementById("statusFilter");
const dateFilter = document.getElementById("dateFilter");

function filterTable() {
    const search = searchInput.value.toLowerCase();
    const status = statusFilter.value.toLowerCase();
    const date = dateFilter.value;

    document.querySelectorAll(".assignment-row").forEach(row => {

        const text = row.innerText.toLowerCase();
        const rowStatus = row.querySelector(".status").innerText.toLowerCase();
        const rowDate = row.children[3].innerText;

        let matchSearch = text.includes(search);
        let matchStatus = status === "" || rowStatus.includes(status);
        let matchDate = true;

        if (date) {
            const selected = new Date(date).toDateString();
            const rowDateObj = new Date(rowDate).toDateString();
            matchDate = selected === rowDateObj;
        }

        row.style.display = (matchSearch && matchStatus && matchDate) ? "" : "none";
    });
}

function goToApplication(eventId) {
    window.location.href = `/volunteer/applications?highlight=${eventId}`;
}

searchInput.addEventListener("input", filterTable);
statusFilter.addEventListener("change", filterTable);
dateFilter.addEventListener("change", filterTable);

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