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

            <!-- FILTERS -->
            <div class="bg-gray-300 p-6 rounded-xl mb-4 flex justify-between flex-wrap gap-4">

                <div class="flex items-center gap-4">

                    <select id="skillFilter" class="p-2 border rounded-md" onchange="applyFilters()">
                        <option value="">All Skills</option>
                        @foreach($skills as $skill)
                            <option value="{{ strtolower($skill) }}">{{ $skill }}</option>
                        @endforeach
                    </select>

                    <select id="statusFilter" class="p-2 border rounded-md" onchange="applyFilters()">
                        <option value="">All Status</option>
                        <option value="0">Pending</option>
                        <option value="1">Approved</option>
                        <option value="2">Rejected</option>
                        <option value="3">Archived</option>
                    </select>

                </div>

            </div>

            <!-- TABLE (NOW INSIDE BLUE CONTAINER) -->
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

                        <tr class="bg-white border hover:bg-gray-100"
                            data-status="{{ $app['status'] }}"
                            data-skills="{{ strtolower($app['skills']) }}"
                            data-availability="{{ strtolower($app['availability']) }}">

                            <td class="p-3 border row-number"></td>

                            <td class="p-3 border">
                                {{ $app['first_name'] }} {{ $app['last_name'] }}
                            </td>

                            <td class="p-3 border">

                              @if($app['status'] == 0)
                                    <span class="px-3 py-1 rounded-full bg-yellow-200 text-yellow-800 text-sm">Pending</span>

                                @elseif($app['status'] == 1)
                                    <span class="px-3 py-1 rounded-full bg-green-200 text-green-800 text-sm">Approved</span>

                                @elseif($app['status'] == 2)
                                    <span class="px-3 py-1 rounded-full bg-red-200 text-red-800 text-sm">Rejected</span>

                                @elseif($app['status'] == 3)
                                    <span class="px-3 py-1 rounded-full bg-gray-200 text-gray-800 text-sm">Archived</span>
                                @endif

                            </td>

                            <td class="p-3 border space-x-2">

                                @php
                                    $appData = [
                                        "first_name" => $app['first_name'],
                                        "last_name" => $app['last_name'],
                                        "email" => $app['email'],
                                        "address" => $app['address'],
                                        "contact_number" => $app['contact_number'],
                                        "birth_date" => $app['birth_date'],
                                        "availability" => $app['availability'],
                                        "skills" => $app['skills'],
                                        "interests" => $app['interests'],
                                        "has_experience" => $app['has_experience'],
                                        "experience_details" => $app['experience_details'],
                                    ];
                                    @endphp

                                    <button 
                                        class="bg-blue-500 px-4 py-1 rounded-full text-white"
                                        onclick="openAppModal(this)"
                                        data-app='@json($appData, JSON_HEX_APOS | JSON_HEX_QUOT)'
                                    >
                                        View
                                    </button>

                                @if($app['status'] == 0)

                                    <button class="bg-green-500 px-4 py-1 rounded-full text-white"
                                        onclick="updateStatus({{ $app['id'] }}, 'approve')">
                                        Approve
                                    </button>

                                    <button class="bg-red-500 px-4 py-1 rounded-full text-white"
                                        onclick="updateStatus({{ $app['id'] }}, 'reject')">
                                        Reject
                                    </button>

                                @elseif($app['status']== 1)

                                    <button class="bg-gray-600 px-4 py-1 rounded-full text-white"
                                        onclick="archiveApplication({{ $app['id'] }})">
                                        Archive
                                    </button>

                                @elseif($app['status'] == 2)

                                    <button class="bg-yellow-500 px-4 py-1 rounded-full text-white"
                                        onclick="restoreApplication({{ $app['id'] }})">
                                        Restore
                                    </button>

                                    <button class="bg-gray-600 px-4 py-1 rounded-full text-white"
                                        onclick="archiveApplication({{ $app['id'] }})">
                                        Archive
                                    </button>

                                @elseif($app['status'] == 3)

                                    <button class="bg-yellow-500 px-4 py-1 rounded-full text-white"
                                        onclick="restoreApplication({{ $app['id'] }})">
                                        Restore
                                    </button>

                                @endif

                            </td>

                        </tr>

                    @empty
                    <tr>
                        <td colspan="4" class="p-4 text-gray-500 text-center">
                            No applications found.
                        </td>
                    </tr>
                    @endforelse

                    <tr id="noResultsRow" class="hidden">
                        <td colspan="4" class="p-4 text-gray-500 text-center">
                            No applications found.
                        </td>
                    </tr>   
                    </tbody>

                </table>

            </div>
        </div>

        
    </div>
</div>
@include('components.application-modal')
@include('components.logout-modal')

<script>
    function applyFilters() {

    let status = document.getElementById('statusFilter').value;
    let skill = document.getElementById('skillFilter').value.toLowerCase();

    const rows = document.querySelectorAll("tbody tr[data-status]");
    const noResultsRow = document.getElementById("noResultsRow");

    let visibleCount = 0;

    rows.forEach(row => {

        let rowStatus = row.dataset.status;
        let rowSkills = (row.dataset.skills || "").toLowerCase();

        let statusMatch = !status || rowStatus === status;
        let skillMatch = !skill || rowSkills.includes(skill);

        if (statusMatch && skillMatch) {
            row.classList.remove("hidden");
            visibleCount++;
        } else {
            row.classList.add("hidden");
        }
    });

    if (noResultsRow) {
        noResultsRow.classList.toggle("hidden", visibleCount !== 0);
    }

    renumberRows();
}


document.addEventListener("DOMContentLoaded", function () {
    const statusFilter = document.getElementById('statusFilter');
    if (statusFilter) {
       statusFilter.value = '';
       
    }
     applyFilters();
});
</script>

<script>
function renumberRows() {
    let count = 1;

    document.querySelectorAll("tbody tr:not(#noResultsRow)").forEach(row => {
        if (row.style.display !== "none") {
            const cell = row.querySelector(".row-number");
            if (cell) cell.innerText = count++;
        }
    });
}
</script>

<script>
function openAppModal(button) {

    const app = JSON.parse(button.dataset.app);

    const setText = (id, value) => {
        const el = document.getElementById(id);
        if (el) {
            el.innerText = (value !== null && value !== undefined && value !== '') 
                ? value 
                : '---';
        }
    };

    // ❌ REMOVE THIS ENTIRE RESET BLOCK
    // document.querySelectorAll('#appModal [id]') ... (DELETE IT)

    // ✅ JUST SET VALUES DIRECTLY
    setText('first_name', app.first_name);
    setText('last_name', app.last_name);
    setText('address', app.address);
    setText('contact', app.contact_number);
    setText('email', app.email);
    setText('dob', app.birth_date);

    setText('availability_text', app.availability);
    setText('experience', app.has_experience == 1 ? 'Yes' : 'No');
    setText('reason', app.experience_details);
    setText('skills_text', app.skills);
    setText('interests_text', app.interests);

    document.getElementById('appModal').classList.remove('hidden');
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

</script>

<script>
function updateStatus(id, action) {

    let message = '';

    if (action === 'approve') {
        message = "Are you sure you want to APPROVE this application?";
    } else {
        message = "Are you sure you want to REJECT this application?";
    }

    // ✅ confirmation step
    if (!confirm(message)) {
        return; // stop if user clicks Cancel
    }

    let url = '';

    if (action === 'approve') {
        url = `/applications/approve/${id}`;
    } else {
        url = `/applications/reject/${id}`;
    }

    fetch(url, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert('Status updated successfully');
            location.reload();
        } else {
            alert('Failed to update');
        }
    });
}
</script>

<script>

function archiveApplication(id) {
    if (!confirm("Archive this approved application?")) return;

    fetch(`/applications/archive/${id}`, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    }).then(() => location.reload());
}

function restoreApplication(id) {
    if (!confirm("Restore this application back to PENDING?")) return;

    fetch(`/applications/restore/${id}`, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert("Restored successfully");
            location.reload();
        } else {
            alert("Failed to restore");
        }
    });
}


</script>
</body>
</html>