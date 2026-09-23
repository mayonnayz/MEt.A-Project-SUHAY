<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Applications</title>

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
<div class="flex">

@include('components.nav')


<div class="flex-1 p-8">

    @include('components.header', [
        'title' => 'Volunteer Management'
    ])


    <div class="bg-[#0e243a] p-4 rounded-2xl flex gap-4 mb-6 flex-wrap">

        <a href="/service-management"
           class="bg-[#f2c94c] px-6 py-2 rounded-full font-semibold">

            Volunteer Lists

        </a>


        <a href="/applications"
           class="bg-gray-200 text-[#0e243a] px-6 py-2 rounded-full font-semibold">

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
           class="bg-[#f2c94c] px-6 py-2 rounded-full font-semibold">

            Track Activity

        </a>

    </div>


    <div class="bg-[#0e243a] p-6 rounded-2xl">


        <div class="bg-gray-300 p-6 rounded-xl mb-4 flex justify-between flex-wrap gap-4">

            <div class="flex items-center gap-4">


                <select
                    id="eventFilter"
                    class="p-2 border rounded-md"
                    onchange="applyFilters()">

                    <option value="">
                        All Events
                    </option>

                    @foreach($events as $event)

                        <option value="{{ strtolower($event['name']) }}">
                            {{ $event['name'] }}
                        </option>

                    @endforeach

                </select>

                <select
                    id="skillFilter"
                    class="p-2 border rounded-md"
                    onchange="applyFilters()">

                    <option value="">
                        All Skills
                    </option>


                    @foreach($skills as $skill)

                        <option value="{{ strtolower($skill) }}">

                            {{ $skill }}

                        </option>

                    @endforeach

                </select>



                <select
                    id="statusFilter"
                    class="p-2 border rounded-md"
                    onchange="applyFilters()">

                    <option value="">
                        All Status
                    </option>

                    <option value="0">
                        Pending
                    </option>

                    <option value="1">
                        Approved
                    </option>

                    <option value="2">
                        Rejected
                    </option>

                    <option value="3">
                        Deactivated
                    </option>

                </select>

            </div>

        </div>



        <div class="bg-gray-200 rounded-xl p-4 overflow-x-auto">

            <table class="w-full text-center border border-gray-400">


               <thead class="bg-gray-300">

                    <tr>

                        <th class="p-3 border">
                            #
                        </th>

                        <th class="p-3 border">
                            Volunteer
                        </th>

                        <th class="p-3 border">
                            Event Applied For
                        </th>

                        <th class="p-3 border">
                            Application Date
                        </th>

                        <th class="p-3 border">
                            Event Date
                        </th>

                        <th class="p-3 border">
                            Status
                        </th>

                    </tr>

                </thead>

                        <tbody>

                            @forelse($applications as $index => $app)

                                @php
                                    $appData = [
                                        'id' => $app['id'] ?? null,
                                        'volunteer_event_id' => $app['volunteer_event_id'] ?? null,
                                        'event_name' => $app['event_name'] ?? '',
                                        'event_date' => $app['event_date'] ?? '',
                                        'account_id' => $app['account_id'] ?? null,
                                        'application_date' => $app['application_date'] ?? '',
                                        'first_name' => $app['first_name'] ?? '',
                                        'last_name' => $app['last_name'] ?? '',
                                        'email' => $app['email'] ?? '',
                                        'address' => $app['address'] ?? '',
                                        'contact_number' => $app['contact_number'] ?? '',
                                        'birth_date' => $app['birth_date'] ?? '',
                                        'skills' => $app['skills'] ?? '',
                                        'remarks' => $app['remarks'] ?? '',
                                        'status' => $app['status'] ?? 0,
                                    ];
                                @endphp

                                <tr
                                    class="bg-white border hover:bg-gray-100 cursor-pointer transition"
                                    data-status="{{ $app['status'] }}"
                                    data-skills="{{ strtolower($app['skills'] ?? '') }}"
                                    data-event="{{ strtolower($app['event_name'] ?? '') }}"
                                    onclick="openAppModal(this)"
                                    data-app='@json($appData, JSON_HEX_APOS | JSON_HEX_QUOT)'
                                >

                                    <td class="p-3 border row-number"></td>

                                    <td class="p-3 border text-left">

                                        <div class="font-semibold text-[#0e243a]">
                                            {{ $app['first_name'] }}
                                            {{ $app['last_name'] }}
                                        </div>

                                        <div class="text-xs text-gray-500">
                                            {{ $app['email'] ?: '---' }}
                                        </div>

                                    </td>

                                    <td class="p-3 border text-left">

                                        <div class="font-semibold text-[#0e243a]">
                                            {{ $app['event_name'] ?: '---' }}
                                        </div>

                                    </td>

                                    <td class="p-3 border">

                                        {{ $app['application_date']
                                            ? \Carbon\Carbon::parse($app['application_date'])->format('M d, Y')
                                            : '---'
                                        }}

                                    </td>

                                    <td class="p-3 border">

                                        {{ $app['event_date']
                                            ? \Carbon\Carbon::parse($app['event_date'])->format('M d, Y')
                                            : '---'
                                        }}

                                    </td>

                                    <td class="p-3 border">

                                        @if($app['status'] == 0)

                                            <span class="inline-block px-3 py-1 rounded-full bg-yellow-100 text-yellow-800 text-sm font-medium">
                                                Pending
                                            </span>

                                        @elseif($app['status'] == 1)

                                            <span class="inline-block px-3 py-1 rounded-full bg-green-100 text-green-800 text-sm font-medium">
                                                Approved
                                            </span>

                                        @elseif($app['status'] == 2)

                                            <span class="inline-block px-3 py-1 rounded-full bg-red-100 text-red-800 text-sm font-medium">
                                                Rejected
                                            </span>

                                        @elseif($app['status'] == 3)

                                            <span class="inline-block px-3 py-1 rounded-full bg-gray-200 text-gray-800 text-sm font-medium">
                                                Deactivated
                                            </span>

                                        @endif

                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="6" class="p-4 text-gray-500 text-center">
                                        No applications found.
                                    </td>
                                </tr>

                            @endforelse

                            <tr id="noResultsRow" class="hidden">

                                <td colspan="6" class="p-4 text-gray-500 text-center">
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

    <script src="{{ asset('js/application-management.js') }}"></script>

</body>
</html>