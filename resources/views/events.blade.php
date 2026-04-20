<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-200">

@if(session('success'))
    <div id="toastSuccess" class="fixed top-5 right-5 bg-green-500 text-white px-4 py-2 rounded shadow z-50">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div id="toastError" class="fixed top-5 right-5 bg-red-500 text-white px-4 py-2 rounded shadow z-50">
        {{ session('error') }}
    </div>
@endif
<div class="flex">
    @include('components.nav')

    <div class="flex-1 p-8">
       @include('components.header', ['title' => 'Events'])

        <!-- NAV BUTTONS -->
        <div class="bg-[#0e243a] p-4 rounded-2xl flex gap-4 mb-6 flex-wrap">
            <a href="/service-management" class="bg-[#f2c94c] px-6 py-2 rounded-full font-semibold">Volunteer Lists</a>
            <a href="/applications" class="bg-[#f2c94c] px-6 py-2 rounded-full font-semibold">Applications</a>
            <a href="/assignments" class="bg-[#f2c94c] px-6 py-2 rounded-full font-semibold">Assignments</a>
            <a href="/events" class="bg-gray-200 text-[#0e243a] px-6 py-2 rounded-full font-semibold">Events</a>
            <a href="/track-activity" class="bg-[#f2c94c] px-6 py-2 rounded-full font-semibold">Track Activity</a>
        </div>

        <!-- BLUE PANEL -->
        <div class="bg-[#0e243a] p-6 rounded-2xl text-white mb-6">

            <!-- TOP CONTROLS -->
            <div class="flex flex-wrap justify-between items-center gap-4 mb-4">

                <!-- ACTIVE COUNT -->
                    <div>
                        <p class="text-lg font-semibold">
                            Active Events:
                                <span class="text-[#f2c94c]">
                                    {{ collect($events)->where('status', 1)->count() }}
                                </span>
                        </p>
                    </div>

                <!-- RIGHT CONTROLS (FILTER + ADD BUTTON) -->
                <div class="flex items-center gap-3">

                    <!-- FILTER DROPDOWN -->
                    <form method="GET" action="/events" class="flex items-center gap-3">

                    <select name="filter"
                            onchange="this.form.submit()"
                            class="p-2 rounded-md text-[#0e243a]">

                        <option value="all" {{ request('filter') == 'all' ? 'selected' : '' }}>
                            All Events
                        </option>

                        <option value="active" {{ request('filter') == 'active' ? 'selected' : '' }}>
                            Active
                        </option>

                        <option value="archived" {{ request('filter') == 'archived' ? 'selected' : '' }}>
                            Archived
                        </option>

                    </select>

                </form>

                    <!-- ADD BUTTON -->
                    <button 
                        onclick="openAddModal()" 
                        class="bg-green-500 hover:bg-green-600 text-white px-5 py-2 rounded-full font-bold">
                        + Add Events
                    </button>

                </div>

            </div>

            <!-- TABLE INSIDE BLUE PANEL -->
            <div class="bg-white rounded-xl overflow-x-auto text-[#0e243a]">

                <table class="w-full text-center border border-gray-300">

                    <thead class="bg-gray-200">
                        <tr>
                            <th class="p-3 border">Name</th>
                            <th class="p-3 border">Date</th>
                            <th class="p-3 border">Status</th>
                            <th class="p-3 border">Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach ($events as $event)
                            <tr class="hover:bg-gray-100">

                                <td class="p-3 border font-semibold">
                                    {{ $event['name'] }}
                                </td>
                                <td class="p-3 border">
                                    {{ $event['date'] }}
                                </td>

                                <td class="p-3 border">
                                    @if ($event['status'] == 1)
                                        <span class="text-green-600 font-bold">Active</span>
                                    @else
                                        <span class="text-gray-500 font-bold">Archived</span>
                                    @endif
                                </td>

                                <td class="p-3 border">
                                    <div class="flex items-center justify-center gap-2">

                                        <button 
                                            data-id="{{ $event['id'] }}"
                                            data-name="{{ $event['name'] }}"
                                            data-description="{{ $event['description'] }}"
                                            data-date="{{ $event['date'] }}"
                                            data-activities='@json($event["activities"] ?? [])'
                                            onclick="openEditModal(this)"
                                            class="bg-yellow-500 px-3 py-1 rounded-full text-white">
                                            Edit
                                        </button>

                                        @if ($event['status'] == 1)

                                            <form method="POST" action="/events/{{ $event['id'] }}/archive" class="inline">
                                                @csrf
                                                @method('PUT')

                                                <button class="bg-red-500 px-3 py-1 rounded-full text-white">
                                                    Archive
                                                </button>
                                            </form>

                                        @else
                                            <form method="POST" action="/events/{{ $event['id'] }}/reactivate" class="inline">
                                                @csrf
                                                @method('PUT')

                                                <button class="bg-orange-500 px-3 py-1 rounded-full text-white">
                                                    Reactivate
                                                </button>
                                            </form>
                                        @endif

                                    </div>
                                </td>

                            </tr>
                            @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    </div>
</div>
@include('components.logout-modal')

<script>

const addForm = document.getElementById('addEventForm');

if (addForm) {
    addForm.addEventListener('submit', function (e) {

        const activityNames = document.querySelectorAll('#activities-container input[name*="[name]"]');
        const activityRemarks = document.querySelectorAll('#activities-container textarea[name*="[remarks]"]');

        let hasValidActivity = false;

        for (let i = 0; i < activityNames.length; i++) {
            if (
                activityNames[i].value.trim() !== '' &&
                activityRemarks[i].value.trim() !== ''
            ) {
                hasValidActivity = true;
                break;
            }
        }

        if (!hasValidActivity) {
            e.preventDefault();
            alert("Please add at least 1 activity with name and remarks.");
        }

    });
}

function openLogoutModal() {
    document.getElementById('logoutModal').classList.remove('hidden');
    document.getElementById('logoutModal').classList.add('flex');
}

function closeLogoutModal() {
    document.getElementById('logoutModal').classList.add('hidden');
}

let originalName = '';
let originalDescription = '';
let currentId = null;

function openEditModal(button) {

    const id = button.dataset.id;
    const name = button.dataset.name;
    const description = button.dataset.description;
    const date = button.dataset.date;

    const activities = JSON.parse(button.dataset.activities || "[]");

    document.getElementById('editId').value = id;
    document.getElementById('editName').value = name;
    document.getElementById('editDescription').value = description;
    document.getElementById('editDate').value = date;

    // BLOCK PAST DATES. pls do not remove this comment
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('editDate').setAttribute('min', today);

    document.getElementById('editForm').action = `/events/${id}`;

    // CLEAR OLD ACTIVITIES
    const container = document.getElementById('edit-activities-container');
    container.innerHTML = '';

    activities.forEach((act, index) => {



    container.insertAdjacentHTML('beforeend', `
    <div class="activity-item mb-3 flex gap-2 items-start">

        <!-- IMPORTANT: activity ID -->
        <input type="hidden" name="activities[${index}][id]" value="${act.id ?? ''}">

        <div class="flex-1">

            <input 
                type="text"
                name="activities[${index}][name]"
                value="${act.name ?? ''}"
                class="w-full p-2 rounded-md text-[#0e243a] mb-2"
                required
            >

            <textarea 
                name="activities[${index}][remarks]"
                class="w-full p-2 rounded-md text-[#0e243a]"
                required
            >${act.remarks ?? ''}</textarea>

        </div>

        <button 
            type="button"
            onclick="markDeleteActivity(this)"
            class="bg-red-500 px-3 py-2 rounded text-white"
        >
            X
        </button>

    </div>
`);

});

    document.getElementById('editModal').classList.remove('hidden');
    document.getElementById('editModal').classList.add('flex');
}


function closeEditModal() {
    const name = document.getElementById('editName').value;
    const desc = document.getElementById('editDescription').value;

    if (name === originalName && desc === originalDescription) {
        document.getElementById('editModal').classList.add('hidden');
        document.getElementById('editModal').classList.remove('flex');
        return;
    }

    if (confirm("You have unsaved changes. Close anyway?")) {
        document.getElementById('editModal').classList.add('hidden');
        document.getElementById('editModal').classList.remove('flex');
    }
}

function openAddModal() {
    document.getElementById('addModal').classList.remove('hidden');
    document.getElementById('addModal').classList.add('flex');
}

function closeAddModal() {
    document.getElementById('addModal').classList.add('hidden');
}

function markDeleteActivity(button) {
    const item = button.closest('.activity-item');

    const allItems = document.querySelectorAll('#edit-activities-container .activity-item');

    // 🚫 prevent deleting last activity
    if (allItems.length <= 1) {
        alert("At least 1 activity is required.");
        return;
    }

    // if it has an ID → mark for deletion
    const idInput = item.querySelector('input[type="hidden"]');

    if (idInput && idInput.value !== '') {
        // mark it as deleted instead of removing
        item.style.display = 'none';
        item.classList.add('marked-delete');

        // add hidden flag for backend
        let deleteInput = document.createElement('input');
        deleteInput.type = 'hidden';
        deleteInput.name = 'activities_delete[]';
        deleteInput.value = idInput.value;

        document.getElementById('editForm').appendChild(deleteInput);

    } else {
        // new unsaved activity → just remove
        item.remove();
    }
}

</script>


<!-- EDIT Event MODAL -->
<div id="editModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">

    <div class="bg-[#0e243a] w-full max-w-[500px] max-h-[90vh] rounded-2xl p-6 text-white shadow-xl overflow-y-auto">

        <h2 class="text-xl font-bold mb-4 text-center">Edit Event</h2>

        <form id="editForm" method="POST" action="">
            @csrf
            @method('PUT')

             <input type="hidden" name="id" id="editId">

            <!-- NAME -->
            <label class="text-sm">Event Name</label>
            <input 
                type="text" 
                name="name" 
                id="editName"
                class="w-full p-2 rounded-md text-[#0e243a] mb-4"
                value=""
                required
            >

                            <!-- DATE-->
                <label class="text-sm">Date</label>
                <input 
                    type="date"
                    name="date"
                    id="editDate"
                    class="w-full p-2 rounded-md text-[#0e243a] mb-4"
                    required
                >

            <!-- DESCRIPTION -->
            <label class="text-sm">Description</label>
                <textarea 
                    name="description"
                    id="editDescription"
                    class="w-full p-2 rounded-md text-[#0e243a] mb-4"
                    rows="4"
                    required>
                </textarea>

                <!-- ACTIVITIES -->
                <div class="mb-4">
                    <label class="text-sm font-semibold">Activities</label>

                    <div id="edit-activities-container">
    <p class="text-gray-300 text-sm">No activities loaded yet.</p>
</div>

                    <button type="button" onclick="addEditActivity()" class="mt-2 mb-4 bg-green-500 px-3 py-1 rounded text-white">
                        + Add Activity
                    </button>
                </div>

            <!-- BUTTONS -->
            <div class="flex justify-between">

                <button 
                    type="button" 
                    onclick="closeEditModal()"
                    class="bg-gray-400 px-4 py-2 rounded-full text-white">
                    Close
                </button>

                <button 
                    type="submit"
                    class="bg-green-500 px-4 py-2 rounded-full font-bold">
                    Save Changes
                </button>

            </div>

        </form>

    </div>
</div>

<!-- ADD Event MODAL -->
<div id="addModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4">

    <div class="bg-[#0e243a] w-full max-w-[500px] max-h-[90vh] rounded-2xl p-6 text-white shadow-xl overflow-y-auto">

        <h2 class="text-xl font-bold mb-4 text-center">Add Events</h2>

        <!-- <form method="POST" action="/events"> -->
            <form method="POST" action="/events" id="addEventForm">
            @csrf

            <!-- NAME -->
            <label class="text-sm">Event Name</label>
            <input type="text" name="name" class="w-full p-2 rounded-md text-[#0e243a] mb-4" required/>

            <!-- <input type="hidden" name="id" id="editId"> -->
                        <!-- DATE -->
            <label class="text-sm">Date</label>
            <input 
                type="date"
                name="date"
                id="eventDate"
                class="w-full p-2 rounded-md text-[#0e243a] mb-4"
                required
            >

            <!-- DESCRIPTION -->
            <label class="text-sm">Description</label>
            <textarea 
                name="description"
                rows="4"
                class="w-full p-2 rounded-md text-[#0e243a] mb-2"
                required
            ></textarea>

            <!-- ACTIVITIES -->
            <div class="mt-2">
                <label class="text-sm font-semibold">Activities</label>

                <div id="activities-container">
                    <div class="activity-item mb-3">
                        <input 
                            type="text" 
                            name="activities[0][name]" 
                            placeholder="Activity Name"
                            class="w-full p-2 rounded-md text-[#0e243a] mb-2"
                            required
                        >

                        <textarea 
                            name="activities[0][remarks]" 
                            placeholder="Remarks"
                            class="w-full p-2 rounded-md text-[#0e243a]"
                            required
                        ></textarea>
                        </div>
                </div>

                <button type="button" onclick="addActivity()" class="mt-2 mb-4 bg-green-500 px-3 py-1 rounded text-white">
                    + Add Activity
                </button>
            </div>

            <!-- BUTTONS -->
            <div class="flex justify-between">

                <button 
                    type="button"
                    onclick="closeAddModal()"
                    class="bg-gray-400 px-4 py-2 rounded-full text-white">
                    Cancel
                </button>

                <button 
                    type="submit"
                    class="bg-green-500 px-4 py-2 rounded-full font-bold">
                    Add Event
                </button>

            </div>

        </form>

    </div>
</div>

<script>
    // BLOCK PAST DATE. pls don't remove this comment
    document.addEventListener("DOMContentLoaded", function () {
    const dateInput = document.getElementById("eventDate");

    if (dateInput) {
        const today = new Date().toISOString().split('T')[0];
        dateInput.setAttribute("min", today);
    }
});

    setTimeout(() => {
        const err = document.getElementById('toastError');
        const ok = document.getElementById('toastSuccess');

        if (err) err.remove();
        if (ok) ok.remove();
    }, 3000);

    let activityIndex = 1;


function addActivity() {
    const container = document.getElementById('activities-container');

    const html = `
        <div class="activity-item mb-3 flex gap-2 items-start">
            
            <div class="flex-1">
                <input 
                    type="text" 
                    name="activities[${activityIndex}][name]" 
                    placeholder="Activity Name"
                    class="w-full p-2 rounded-md text-[#0e243a] mb-2"
                    required
                >

                <textarea 
                    name="activities[${activityIndex}][remarks]" 
                    placeholder="Remarks"
                    class="w-full p-2 rounded-md text-[#0e243a]"
                    required
                ></textarea>
            </div>

            <button 
                type="button"
                onclick="this.closest('.activity-item').remove()"
                class="bg-red-500 px-3 py-2 rounded text-white mb-2"
            >
                X
            </button>

        </div>
    `;

    container.insertAdjacentHTML('beforeend', html);
    activityIndex++;
}

let editActivityIndex = 1000;

function addEditActivity() {
    const container = document.getElementById('edit-activities-container');

    const html = `
        <div class="activity-item mb-3 flex gap-2 items-start">

            <div class="flex-1">
                <input 
                    type="text" 
                    name="activities[${editActivityIndex}][name]" 
                    placeholder="Activity Name"
                    class="w-full p-2 rounded-md text-[#0e243a] mb-2"
                    required
                >

                <textarea 
                    name="activities[${editActivityIndex}][remarks]" 
                    placeholder="Remarks"
                    class="w-full p-2 rounded-md text-[#0e243a]"
                    required
                ></textarea>
            </div>

            <!-- REMOVE BUTTON -->
            <button 
                type="button"
                onclick="this.closest('.activity-item').remove()"
                class="bg-red-500 px-3 py-2 rounded text-white mb-2"
            >
                X
            </button>

        </div>
    `;

    container.insertAdjacentHTML('beforeend', html);
    editActivityIndex++;
}
</script>

</body>
</html>