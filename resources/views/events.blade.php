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
                                    @if ($event['status'] == 1)
                                        <span class="text-green-600 font-bold">Active</span>
                                    @else
                                        <span class="text-gray-500 font-bold">Archived</span>
                                    @endif
                                </td>

                                <td class="p-3 border">
                                    <div class="flex items-center justify-center gap-2">

                                        <button 
                                            onclick="openEditModal(@js($event['id']), @js($event['name']), @js($event['description']))"
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

                                            <button disabled class="bg-gray-400 px-3 py-1 rounded-full text-white opacity-60 cursor-not-allowed">
                                                Archived
                                            </button>

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

function openEditModal(id, name, description) {

    currentId = id;
    originalName = name;
    originalDescription = description;

    document.getElementById('editId').value = id;
    document.getElementById('editName').value = name;
    document.getElementById('editDescription').value = description;

    document.getElementById('editForm').action = `/events/${id}`;

    const modal = document.getElementById('editModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeEditModal() {
    const name = document.getElementById('editName').value;
    const desc = document.getElementById('editDescription').value;

    // IF NOTHING CHANGED → JUST CLOSE
    if (name === originalName && desc === originalDescription) {
        document.getElementById('editModal').classList.add('hidden');
        return;
    }

    //ask confirmation if changed
    if (confirm("You have unsaved changes. Close anyway?")) {
        document.getElementById('editModal').classList.add('hidden');
    }

    const errorBox = document.getElementById('errorBox');
    if (errorBox) {
        errorBox.remove();
    }
}

function openAddModal() {
    document.getElementById('addModal').classList.remove('hidden');
    document.getElementById('addModal').classList.add('flex');
}

function closeAddModal() {
    document.getElementById('addModal').classList.add('hidden');
}

</script>


<!-- EDIT Event MODAL -->
<div id="editModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">

    <div class="bg-[#0e243a] w-[450px] rounded-2xl p-6 text-white shadow-xl">

        <h2 class="text-xl font-bold mb-4 text-center">Edit Event</h2>

        <form id="editForm" method="POST" action="">
            @csrf
            @method('PUT')

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

            <!-- DESCRIPTION -->
            <label class="text-sm">Description</label>
                <textarea 
                    name="description"
                    id="editDescription"
                    class="w-full p-2 rounded-md text-[#0e243a] mb-6"
                    rows="4"
                    required>
                </textarea>

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
<div id="addModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">

    <div class="bg-[#0e243a] w-[450px] rounded-2xl p-6 text-white shadow-xl">

        <h2 class="text-xl font-bold mb-4 text-center">Add Events</h2>

        <form method="POST" action="/events">
            @csrf

            <!-- NAME -->
            <label class="text-sm">Event Name</label>
            <input type="text" name="name" class="w-full p-2 rounded-md text-[#0e243a] mb-4" />

            <input type="hidden" name="id" id="editId">

            <!-- DESCRIPTION -->
            <label class="text-sm">Description</label>
            <textarea 
                name="description"
                rows="4"
                class="w-full p-2 rounded-md text-[#0e243a] mb-6"
                required
            ></textarea>

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
    setTimeout(() => {
        const err = document.getElementById('toastError');
        const ok = document.getElementById('toastSuccess');

        if (err) err.remove();
        if (ok) ok.remove();
    }, 3000);
</script>

</body>
</html>