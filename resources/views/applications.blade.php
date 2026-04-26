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
        td.label { background: #0c2d48; color: white; width: 35%; font-weight: 600; }
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
                            data-status="{{ $app->status }}"
                            data-skills="{{ strtolower($app->skills) }}"
                            data-availability="{{ strtolower($app->availability) }}">

                            <td class="p-3 border row-number"></td>

                            <td class="p-3 border">
                                {{ $app->first_name }} {{ $app->last_name }}
                            </td>

                            <td class="p-3 border">

                                @if($app->status == 0)
                                    <span class="px-3 py-1 rounded-full bg-yellow-200 text-yellow-800 text-sm">Pending</span>

                                @elseif($app->status == 1)
                                    <span class="px-3 py-1 rounded-full bg-green-200 text-green-800 text-sm">Approved</span>

                                @elseif($app->status == 2)
                                    <span class="px-3 py-1 rounded-full bg-red-200 text-red-800 text-sm">Rejected</span>

                                @elseif($app->status == 3)
                                    <span class="px-3 py-1 rounded-full bg-gray-200 text-gray-800 text-sm">Archived</span>
                                @endif

                            </td>

                            <td class="p-3 border space-x-2">

                                <button
                                    class="bg-blue-500 px-4 py-1 rounded-full text-white view-btn"
                                    data-first_name="{{ $app->first_name }}"
                                    data-last_name="{{ $app->last_name }}"
                                    data-address="{{ $app->address }}"
                                    data-contact="{{ $app->contact_number }}"
                                    data-email="{{ $app->email }}"
                                    data-dob="{{ $app->birth_date }}"
                                    data-availability="{{ $app->availability }}"
                                    data-skills="{{ $app->skills }}"
                                    data-interests="{{ $app->interests }}"
                                    data-has_experience="{{ $app->has_experience }}"
                                    data-experience_details="{{ $app->experience_details }}"
                                >
                                    View
                                </button>

                                @if($app->status == 0)

                                    <button class="bg-green-500 px-4 py-1 rounded-full text-white"
                                        data-id="{{ $app->id }}"
                                        onclick="updateStatus(this.dataset.id, 'approve')">
                                        Approve
                                    </button>

                                    <button class="bg-red-500 px-4 py-1 rounded-full text-white"
                                        data-id="{{ $app->id }}"
                                        onclick="updateStatus(this.dataset.id, 'reject')">
                                        Reject
                                    </button>

                                @elseif($app->status == 1)

                                    <button class="bg-gray-600 px-4 py-1 rounded-full text-white"
                                        data-id="{{ $app->id }}"
                                        onclick="archiveApplication(this.dataset.id)">
                                        Archive
                                    </button>

                                @elseif($app->status == 2)

                                    <button class="bg-yellow-500 px-4 py-1 rounded-full text-white"
                                        data-id="{{ $app->id }}"
                                        onclick="restoreApplication(this.dataset.id)">
                                        Restore
                                    </button>

                                    <button class="bg-gray-600 px-4 py-1 rounded-full text-white"
                                        data-id="{{ $app->id }}"
                                        onclick="archiveApplication(this.dataset.id)">
                                        Archive
                                    </button>

                                @elseif($app->status == 3)

                                    <button class="bg-yellow-500 px-4 py-1 rounded-full text-white"
                                        data-id="{{ $app->id }}"
                                        onclick="restoreApplication(this.dataset.id)">
                                        Restore
                                    </button>

                                @endif

                            </td>

                        </tr>

                    @empty
                        <tr id="noResultsRow" class="hidden">
                            <td colspan="4" class="p-4 text-gray-500 text-center">
                                No applications found.
                            </td>
                        </tr>
                    @endforelse

                    </tbody>

                </table>

            </div>
        </div>

        
    </div>
</div>

<div id="appModal" style="display:none;" class="fixed inset-0 bg-black/40 items-center justify-center z-50">

    <div id="modalBox"
         class="bg-[#f8f8f8] w-[650px] max-h-[90vh] overflow-y-auto rounded-2xl border-2 border-gray-700 p-6 relative">

        <!-- HEADER -->
        <div class="text-center mb-3">
            <div class="flex justify-center">
                <img src="{{ asset('images/suhayLogo.png') }}" alt="SUHAY" class="h-28 w-28 object-contain"/>
            </div>
            <div class="text-sm font-semibold">Volunteer Application Form</div>
        </div>

        <!-- PERSONAL INFO -->
        <div class="mt-4">
            <div class="text-xs font-semibold mb-1">Personal Information</div>
            <table class="w-full text-xs border border-gray-400 border-collapse">
                <tbody id="modalContent"></tbody>
            </table>
        </div>

        <!-- APPLICATION DETAILS -->
        <div class="mt-4">
            <div class="text-xs font-semibold mb-1">Application Details</div>
            <table class="w-full text-xs border border-gray-400 border-collapse">

                <tr>
                    <td class="label p-1">Availability</td>
                    <td class="border p-1" id="day"></td>
                </tr>

                <tr>
                    <td class="label p-1">Skills</td>
                    <td class="border p-1" id="skills_text"></td>
                </tr>

                <tr>
                    <td class="label p-1">Interests</td>
                    <td class="border p-1" id="interests_text"></td>
                </tr>

            </table>
        </div>

        <!-- VOLUNTEER EXPERIENCE -->
        <div class="mt-4">
            <div class="text-xs font-semibold mb-1">Volunteer Experience</div>
            <table class="w-full text-xs border border-gray-400 border-collapse">

                <tr>
                    <td class="label p-1">Has Experience?</td>
                    <td class="border p-1" id="experience"></td>
                </tr>

                <tr>
                    <td class="label p-1">Experience Details</td>
                    <td class="border p-1 whitespace-pre-wrap" id="reason"></td>
                </tr>

            </table>
        </div>

        <!-- ACTION -->
        <div class="mt-6 flex justify-center">
            <button onclick="closeModal()"
                    class="px-8 py-2 rounded-full bg-[#0e243a] text-white font-bold text-sm hover:bg-[#091826] transition">
                Close
            </button>
        </div>

    </div>
</div>

@include('components.logout-modal')

<script>
function renumberRows() {
    let count = 1;
    document.querySelectorAll("tbody tr:not(#noResultsRow)").forEach(row => {
        if (row.style.display !== "none") {
            const cell = row.querySelector(".row-number");
            if (cell) cell.textContent = count++;
        }
    });
}

function applyFilters() {
    let status = document.getElementById('statusFilter').value;
    let skill = document.getElementById('skillFilter').value.toLowerCase();

    const rows = document.querySelectorAll("tbody tr:not(#noResultsRow)");
    const noResultsRow = document.getElementById("noResultsRow");
    let visibleCount = 0;

    rows.forEach(row => {
        let rowStatus = row.dataset.status;
        let rowSkills = (row.dataset.skills || "").toLowerCase();
        let statusMatch = !status || rowStatus === status;
        let skillMatch = !skill || rowSkills.includes(skill);

        if (statusMatch && skillMatch) {
            row.style.display = "";
            visibleCount++;
        } else {
            row.style.display = "none";
        }
    });

    if (noResultsRow) {
        noResultsRow.classList.toggle("hidden", visibleCount !== 0);
    }

    renumberRows();
}

function closeModal() {
    document.getElementById('appModal').style.display = 'none';
}

function updateStatus(id, action) {
    let message = action === 'approve'
        ? "Are you sure you want to APPROVE this application?"
        : "Are you sure you want to REJECT this application?";

    if (!confirm(message)) return;

    let url = action === 'approve' ? `/applications/approve/${id}` : `/applications/reject/${id}`;

    fetch(url, {
        method: 'PATCH',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) { alert('Status updated successfully'); location.reload(); }
        else { alert('Failed to update'); }
    });
}

function archiveApplication(id) {
    if (!confirm("Archive this approved application?")) return;
    fetch(`/applications/archive/${id}`, {
        method: 'PATCH',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' }
    }).then(() => location.reload());
}

function restoreApplication(id) {
    if (!confirm("Restore this application back to PENDING?")) return;
    fetch(`/applications/restore/${id}`, {
        method: 'PATCH',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) { alert("Restored successfully"); location.reload(); }
        else { alert("Failed to restore"); }
    });
}

document.addEventListener("DOMContentLoaded", function () {
    // Initial filter state
    applyFilters();

    // View button listeners
    document.addEventListener('click', function (e) {
    if (e.target.classList.contains('view-btn')) {

        const btn = e.target;

        document.getElementById('modalContent').innerHTML = `
            <tr><td class="label p-1">First Name</td><td class="border p-1">${btn.dataset.first_name}</td></tr>
            <tr><td class="label p-1">Last Name</td><td class="border p-1">${btn.dataset.last_name}</td></tr>
            <tr><td class="label p-1">Address</td><td class="border p-1">${btn.dataset.address}</td></tr>
            <tr><td class="label p-1">Contact Number</td><td class="border p-1">${btn.dataset.contact}</td></tr>
            <tr><td class="label p-1">Email Address</td><td class="border p-1">${btn.dataset.email}</td></tr>
            <tr><td class="label p-1">Date of Birth</td><td class="border p-1">${btn.dataset.dob}</td></tr>
        `;

        document.getElementById('day').textContent        = btn.dataset.availability;
        document.getElementById('skills_text').textContent = btn.dataset.skills;
        document.getElementById('interests_text').textContent = btn.dataset.interests;

        document.getElementById('experience').textContent =
            btn.dataset.has_experience == "1" ? "Yes" : "No";

        document.getElementById('reason').textContent =
            btn.dataset.experience_details;

        document.getElementById('appModal').style.display = 'flex';
    }
});
});
</script>
</body>
</html>