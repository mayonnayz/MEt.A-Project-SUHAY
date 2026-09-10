<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Activity</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>

<body class="bg-gray-200">
<div class="flex">

    @include('components.nav')

    <div class="flex-1 p-8">

        @include('components.header', ['title' => 'Volunteer Management'])

        <!-- Tabs -->
        <div class="bg-[#0e243a] p-4 rounded-2xl flex gap-4 mb-6 flex-wrap">
            <a href="/service-management" class="bg-[#f2c94c] px-6 py-2 rounded-full font-semibold">Volunteer Lists</a>
            <a href="/applications" class="bg-[#f2c94c] px-6 py-2 rounded-full font-semibold">Applications</a>
            <a href="/assignments" class="bg-[#f2c94c] px-6 py-2 rounded-full font-semibold">Assignments</a>
            <a href="/events" class="bg-[#f2c94c] px-6 py-2 rounded-full font-semibold">Events</a>
            <a href="/track-activity" class="bg-gray-200 text-[#0e243a] px-6 py-2 rounded-full font-semibold">Track Activity</a>
        </div>

        <!-- Filters -->
        <div class="bg-gray-200 p-4 rounded-2xl flex items-center justify-between mb-6">
            <div class="flex gap-4 w-full">

                <select id="activityFilter" class="px-4 py-2 rounded-xl border w-64">
                    <option value="">All Events</option>

                    @foreach ($events as $event)
                        <option value="{{ strtolower($event['name']) }}">
                            {{ $event['name'] }}
                        </option>
                    @endforeach
                </select>

                <input id="searchInput" type="text" placeholder="Search Volunteer"
                    class="px-4 py-2 rounded-xl border flex-1">

            </div>

            <button onclick="openLogActivityModal()"
                class="ml-4 bg-[#f2c94c] px-6 py-2 rounded-full font-semibold">
                + Log Activity
            </button>
        </div>

        <!-- Table -->
        <div class="bg-[#0e243a] p-4 rounded-2xl">
            <div class="bg-gray-200 rounded-2xl overflow-hidden">

                <table class="w-full">

                    <thead class="bg-gray-300 text-left">
                        <tr>
                            <th class="p-4">#</th>
                            <th class="p-4">Volunteer</th>
                            <th class="p-4">Activity</th>
                            <th class="p-4">Event</th>
                            <th class="p-4">Time In</th>
                            <th class="p-4">Time Out</th>
                            <th class="p-4">Hours</th>
                            <th class="p-4">Status</th>
                            <th class="p-4">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($assignments as $item)
                        <tr class="border-t"
    data-event="{{ strtolower($item['event']['name'] ?? '') }}"
    data-name="{{ strtolower(($item['accounts']['first_name'] ?? '') . ' ' . ($item['accounts']['last_name'] ?? '')) }}">

    <td class="p-4">{{ $loop->iteration }}</td>

    <td class="p-4">
        {{ $item['accounts']['first_name'] ?? '' }}
        {{ $item['accounts']['last_name'] ?? '' }}
    </td>

    <!-- Activity -->
    <td class="p-4">
        {{ $item['activity']['name'] ?? 'N/A' }}
    </td>

    <!-- Event -->
    <td class="p-4">
        {{ $item['event']['name'] ?? 'N/A' }}
    </td>

    <td class="p-4">{{ $item['time_in'] ?? '-' }}</td>
    <td class="p-4">{{ $item['time_out'] ?? '-' }}</td>
    <td class="p-4">{{ $item['total_hours'] ?? '-' }}</td>

    <td class="p-4 font-semibold
        {{ ($item['status'] ?? 0) == 1 ? 'text-green-600' : 'text-yellow-500' }}">
        {{ ($item['status'] ?? 0) == 1 ? 'Completed' : 'On Going' }}
    </td>

    <td class="p-4">
        <button class="bg-blue-700 text-white px-5 py-2 rounded-full">
            View
        </button>
    </td>
</tr>
                        @empty
                        <tr>
                            <td colspan="9" class="p-4 text-center text-gray-500">
                                No activity records found
                            </td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>

            </div>
        </div>

    </div>
</div>

@include('components.log-activity-modal')
@include('components.logout-modal')

    <script src="{{ asset('js/application-management.js') }}"></script>


</body>
</html>