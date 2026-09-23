<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Events</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap"
        rel="stylesheet"
    >

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-200">

@if(session('success'))
    <div
        id="toastSuccess"
        class="fixed top-5 right-5 bg-green-500 text-white px-4 py-2 rounded shadow z-50"
    >
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div
        id="toastError"
        class="fixed top-5 right-5 bg-red-500 text-white px-4 py-2 rounded shadow z-50"
    >
        {{ session('error') }}
    </div>
@endif


<div class="flex">

    @include('components.nav')

    <div class="flex-1 p-8">

        @include('components.header', ['title' => 'Events'])


        <!-- NAV BUTTONS -->
        <div class="bg-[#0e243a] p-4 rounded-2xl flex gap-4 mb-6 flex-wrap">

            <a
                href="/service-management"
                class="bg-[#f2c94c] px-6 py-2 rounded-full font-semibold"
            >
                Volunteer Lists
            </a>

            <a
                href="/applications"
                class="bg-[#f2c94c] px-6 py-2 rounded-full font-semibold"
            >
                Applications
            </a>

            <a
                href="/assignments"
                class="bg-[#f2c94c] px-6 py-2 rounded-full font-semibold"
            >
                Assignments
            </a>

            <a
                href="/events"
                class="bg-gray-200 text-[#0e243a] px-6 py-2 rounded-full font-semibold"
            >
                Events
            </a>

            <a
                href="/track-activity"
                class="bg-[#f2c94c] px-6 py-2 rounded-full font-semibold"
            >
                Track Activity
            </a>

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


                <!-- RIGHT CONTROLS -->
                <div class="flex items-center gap-3">

                    <!-- FILTER -->
                    <form
                        method="GET"
                        action="/events"
                        class="flex items-center gap-3"
                    >

                        <select
                            name="filter"
                            onchange="this.form.submit()"
                            class="p-2 rounded-md text-[#0e243a]"
                        >

                            <option
                                value="all"
                                {{ request('filter') == 'all' ? 'selected' : '' }}
                            >
                                All Events
                            </option>

                            <option
                                value="active"
                                {{ request('filter') == 'active' ? 'selected' : '' }}
                            >
                                Active
                            </option>

                            <option
                                value="archived"
                                {{ request('filter') == 'archived' ? 'selected' : '' }}
                            >
                                Deactivate
                            </option>

                        </select>

                    </form>


                    <!-- ADD BUTTON -->
                    <button
                        type="button"
                        onclick="openAddModal()"
                        class="bg-green-500 hover:bg-green-600 text-white px-5 py-2 rounded-full font-bold"
                    >
                        + Add Events
                    </button>

                </div>

            </div>


            <!-- EVENTS TABLE -->
            <div class="bg-white rounded-xl overflow-x-auto text-[#0e243a]">

                <table class="w-full text-center border border-gray-300">

                    <thead class="bg-gray-200">

                        <tr>

                            <th class="p-3 border">
                                Name
                            </th>

                            <th class="p-3 border">
                                Date
                            </th>

                            <th class="p-3 border">
                                Status
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @foreach ($events as $event)

                            <tr
                                class="hover:bg-gray-100 cursor-pointer event-row"
                                data-id="{{ $event['id'] }}"
                                data-name="{{ $event['name'] }}"
                                data-description="{{ $event['description'] }}"
                                data-date="{{ $event['date'] }}"
                                data-status="{{ $event['status'] }}"
                                data-activities='@json($event["activities"] ?? [])'
                                onclick="openEventModal(this)"
                            >

                                <td class="p-3 border font-semibold">
                                    {{ $event['name'] }}
                                </td>

                                <td class="p-3 border">
                                    {{ $event['date'] }}
                                </td>

                                <td class="p-3 border">

                                    @if ($event['status'] == 1)

                                        <span class="text-green-600 font-bold">
                                            Active
                                        </span>

                                    @else

                                        <span class="text-gray-500 font-bold">
                                            Deactivated
                                        </span>

                                    @endif

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



<!-- ========================================================= -->
<!-- EVENT DETAILS MODAL -->
<!-- ========================================================= -->

<div
    id="eventModal"
    class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4"
>

    <div
        class="bg-[#0e243a] w-full max-w-[500px] max-h-[90vh] rounded-2xl p-6 text-white shadow-xl overflow-y-auto"
    >

        <h2 class="text-xl font-bold mb-6 text-center">
            Event Details
        </h2>


        <!-- EVENT NAME -->
        <div class="mb-4">

            <label class="text-sm text-gray-300">
                Event Name
            </label>

            <div
                id="eventModalName"
                class="bg-white text-[#0e243a] p-3 rounded-md font-semibold mt-1"
            ></div>

        </div>


        <!-- DATE -->
        <div class="mb-4">

            <label class="text-sm text-gray-300">
                Date
            </label>

            <div
                id="eventModalDate"
                class="bg-white text-[#0e243a] p-3 rounded-md mt-1"
            ></div>

        </div>


        <!-- STATUS -->
        <div class="mb-4">

            <label class="text-sm text-gray-300">
                Status
            </label>

            <div
                id="eventModalStatus"
                class="bg-white text-[#0e243a] p-3 rounded-md mt-1 font-semibold"
            ></div>

        </div>


        <!-- DESCRIPTION -->
        <div class="mb-4">

            <label class="text-sm text-gray-300">
                Description
            </label>

            <div
                id="eventModalDescription"
                class="bg-white text-[#0e243a] p-3 rounded-md mt-1 whitespace-pre-wrap"
            ></div>

        </div>


        <!-- ACTIVITIES -->
        <div class="mb-6">

            <label class="text-sm text-gray-300">
                Activities
            </label>

            <div
                id="eventModalActivities"
                class="mt-2"
            ></div>

        </div>


        <!-- ACTIONS -->
        <div
            id="eventModalActions"
            class="flex flex-wrap justify-end gap-2"
        >

            <!-- EDIT -->
            <button
                type="button"
                id="eventEditButton"
                class="bg-yellow-500 px-4 py-2 rounded-full text-white font-semibold"
            >
                Edit
            </button>


            <!-- ARCHIVE -->
            <form
                id="eventArchiveForm"
                method="POST"
                class="inline"
            >

                @csrf
                @method('PUT')

                <button
                    type="submit"
                    class="bg-red-500 px-4 py-2 rounded-full text-white font-semibold"
                >
                    Deactivate
                </button>

            </form>


            <!-- REACTIVATE -->
            <form
                id="eventReactivateForm"
                method="POST"
                class="inline hidden"
            >

                @csrf
                @method('PUT')

                <button
                    type="submit"
                    class="bg-orange-500 px-4 py-2 rounded-full text-white font-semibold"
                >
                    Reactivate
                </button>

            </form>


            <!-- CLOSE -->
            <button
                type="button"
                onclick="closeEventModal()"
                class="bg-gray-400 px-4 py-2 rounded-full text-white font-semibold"
            >
                Close
            </button>

        </div>

    </div>

</div>



<!-- ========================================================= -->
<!-- EDIT EVENT MODAL -->
<!-- ========================================================= -->

<div
    id="editModal"
    class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4"
>

    <div
        class="bg-[#0e243a] w-full max-w-[500px] max-h-[90vh] rounded-2xl p-6 text-white shadow-xl overflow-y-auto"
    >

        <h2 class="text-xl font-bold mb-4 text-center">
            Edit Event
        </h2>


        <form
            id="editForm"
            method="POST"
            action=""
        >

            @csrf
            @method('PUT')

            <input
                type="hidden"
                name="id"
                id="editId"
            >


            <!-- NAME -->
            <label class="text-sm">
                Event Name
            </label>

            <input
                type="text"
                name="name"
                id="editName"
                class="w-full p-2 rounded-md text-[#0e243a] mb-4"
                required
            >


            <!-- DATE -->
            <label class="text-sm">
                Date
            </label>

            <input
                type="date"
                name="date"
                id="editDate"
                class="w-full p-2 rounded-md text-[#0e243a] mb-4"
                required
            >


            <!-- DESCRIPTION -->
            <label class="text-sm">
                Description
            </label>

            <textarea
                name="description"
                id="editDescription"
                class="w-full p-2 rounded-md text-[#0e243a] mb-4"
                rows="4"
                required
            ></textarea>


            <!-- ACTIVITIES -->
            <div class="mb-4">

                <label class="text-sm font-semibold">
                    Activities
                </label>

                <div id="edit-activities-container">

                    <p class="text-gray-300 text-sm">
                        No activities loaded yet.
                    </p>

                </div>


                <button
                    type="button"
                    onclick="addEditActivity()"
                    class="mt-2 mb-4 bg-green-500 px-3 py-1 rounded text-white"
                >
                    + Add Activity
                </button>

            </div>


            <!-- BUTTONS -->
            <div class="flex justify-between">

                <button
                    type="button"
                    onclick="closeEditModal()"
                    class="bg-gray-400 px-4 py-2 rounded-full text-white"
                >
                    Close
                </button>

                <button
                    type="submit"
                    class="bg-green-500 px-4 py-2 rounded-full font-bold"
                >
                    Save Changes
                </button>

            </div>

        </form>

    </div>

</div>



<!-- ========================================================= -->
<!-- ADD EVENT MODAL -->
<!-- ========================================================= -->

<div
    id="addModal"
    class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4"
>

    <div
        class="bg-[#0e243a] w-full max-w-[500px] max-h-[90vh] rounded-2xl p-6 text-white shadow-xl overflow-y-auto"
    >

        <h2 class="text-xl font-bold mb-4 text-center">
            Add Events
        </h2>


        <form
            method="POST"
            action="/events"
            id="addEventForm"
        >

            @csrf


            <!-- NAME -->
            <label class="text-sm">
                Event Name
            </label>

            <input
                type="text"
                name="name"
                class="w-full p-2 rounded-md text-[#0e243a] mb-4"
                required
            >


            <!-- DATE -->
            <label class="text-sm">
                Date
            </label>

            <input
                type="date"
                name="date"
                id="eventDate"
                class="w-full p-2 rounded-md text-[#0e243a] mb-4"
                required
            >


            <!-- DESCRIPTION -->
            <label class="text-sm">
                Description
            </label>

            <textarea
                name="description"
                rows="4"
                class="w-full p-2 rounded-md text-[#0e243a] mb-2"
                required
            ></textarea>


            <!-- ACTIVITIES -->
            <div class="mt-2">

                <label class="text-sm font-semibold">
                    Activities
                </label>


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


                <button
                    type="button"
                    onclick="addActivity()"
                    class="mt-2 mb-4 bg-green-500 px-3 py-1 rounded text-white"
                >
                    + Add Activity
                </button>

            </div>


            <!-- BUTTONS -->
            <div class="flex justify-between">

                <button
                    type="button"
                    onclick="closeAddModal()"
                    class="bg-gray-400 px-4 py-2 rounded-full text-white"
                >
                    Cancel
                </button>

                <button
                    type="submit"
                    class="bg-green-500 px-4 py-2 rounded-full font-bold"
                >
                    Add Event
                </button>

            </div>

        </form>

    </div>

</div>



<!-- EXTERNAL JAVASCRIPT -->
<script src="{{ asset('js/events.js') }}"></script>

</body>
</html>