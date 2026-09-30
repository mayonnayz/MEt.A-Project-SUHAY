<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>SUHAY - Dashboard</title>


    {{-- GOOGLE FONT --}}

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap"
        rel="stylesheet"
    >


    {{-- TAILWIND --}}


    <script src="https://cdn.tailwindcss.com"></script>


    <style>

        body {
            font-family: 'Poppins', sans-serif;
        }

    </style>

</head>


<body class="bg-gray-200">


<div class="flex">


    {{-- ================================================= --}}
    {{-- SIDEBAR                                           --}}
    {{-- ================================================= --}}

    @include('components.nav')


    {{-- ================================================= --}}
    {{-- MAIN CONTENT                                      --}}
    {{-- ================================================= --}}

    <div class="flex-1 p-8">


        {{-- ================================================= --}}
        {{-- HEADER                                            --}}
        {{-- ================================================= --}}

        @include('components.header', [
            'title' => 'Dashboard'
        ])


        {{-- ================================================= --}}
        {{-- NGO WELCOME SECTION                                --}}
        {{-- ================================================= --}}

      <div class="bg-[#0e243a] text-white rounded-2xl p-6 mb-6">

    <p class="text-sm text-gray-300">
        Welcome back,
        {{ $userName }}!
    </p>

    <h2 class="text-2xl font-bold mt-1">
        {{ $ngoName }}
    </h2>

    <p class="text-sm text-gray-300 mt-2">
        Here's an overview of your organization's current activities.
    </p>

</div>



        {{-- ================================================= --}}
        {{-- SUMMARY CARDS                                     --}}
        {{-- ================================================= --}}

        <div
            class="grid grid-cols-1
                   sm:grid-cols-2
                   lg:grid-cols-4
                   gap-5 mb-6"
        >


            {{-- ================================================= --}}
            {{-- DONATIONS                                         --}}
            {{-- ================================================= --}}

            <div
                class="bg-white rounded-2xl
                       p-5 shadow-sm"
            >

                <p class="text-sm text-gray-500">
                    Donations
                </p>


                <h2
                    class="text-2xl font-bold
                           text-[#0e243a] mt-2"
                >

                    ₱{{ number_format($monthlyDonations ?? 0, 2) }}

                </h2>


                <p class="text-xs text-gray-400 mt-1">
                    This Month
                </p>

            </div>



            {{-- ================================================= --}}
            {{-- INVENTORY                                         --}}
            {{-- ================================================= --}}

            <div
                class="bg-white rounded-2xl
                       p-5 shadow-sm"
            >

                <p class="text-sm text-gray-500">
                    Inventory Items
                </p>


                <h2
                    class="text-2xl font-bold
                           text-[#0e243a] mt-2"
                >

                    {{ $inventoryItems ?? 0 }}

                </h2>


                <p class="text-xs text-red-500 mt-1">

                    {{ $lowStockItems ?? 0 }}

                    items low in stock

                </p>

            </div>



            {{-- ================================================= --}}
            {{-- VOLUNTEERS                                        --}}
            {{-- ================================================= --}}

            <div
                class="bg-white rounded-2xl
                       p-5 shadow-sm"
            >

                <p class="text-sm text-gray-500">
                    Active Volunteers
                </p>


                <h2
                    class="text-2xl font-bold
                           text-[#0e243a] mt-2"
                >

                    {{ $activeVolunteers ?? 0 }}

                </h2>


                <p class="text-xs text-gray-400 mt-1">
                    Current volunteers
                </p>

            </div>



            {{-- ================================================= --}}
            {{-- APPLICATIONS                                      --}}
            {{-- ================================================= --}}

            <div
                class="bg-white rounded-2xl
                       p-5 shadow-sm"
            >

                <p class="text-sm text-gray-500">
                    Pending Applications
                </p>


                <h2
                    class="text-2xl font-bold
                           text-[#0e243a] mt-2"
                >

                    {{ $pendingApplications ?? 0 }}

                </h2>


                <p class="text-xs text-orange-500 mt-1">
                    Requires review
                </p>

            </div>


        </div>



        {{-- ================================================= --}}
        {{-- SECOND ROW                                        --}}
        {{-- ================================================= --}}

        <div
            class="grid grid-cols-1
                   lg:grid-cols-2
                   gap-6 mb-6"
        >


            {{-- ================================================= --}}
            {{-- VOLUNTEER APPLICATIONS                             --}}
            {{-- ================================================= --}}

            <div
                class="bg-white rounded-2xl
                       shadow-sm p-6"
            >

                <div
                    class="flex justify-between
                           items-center mb-5"
                >

                    <div>

                        <h2
                            class="text-lg font-bold
                                   text-[#0e243a]"
                        >
                            Volunteer Applications
                        </h2>


                        <p class="text-sm text-gray-400">
                            Application status
                        </p>

                    </div>


                    <a
                        href="/applications"
                        class="text-sm text-[#d39a11]
                               hover:underline"
                    >
                        View
                    </a>

                </div>


                @php

                    $totalApplications =
                        ($pendingApplications ?? 0)
                        +
                        ($approvedApplications ?? 0)
                        +
                        ($rejectedApplications ?? 0);


                    $pendingPercentage =
                        $totalApplications > 0
                        ? (
                            ($pendingApplications ?? 0)
                            / $totalApplications
                        ) * 100
                        : 0;


                    $approvedPercentage =
                        $totalApplications > 0
                        ? (
                            ($approvedApplications ?? 0)
                            / $totalApplications
                        ) * 100
                        : 0;


                    $rejectedPercentage =
                        $totalApplications > 0
                        ? (
                            ($rejectedApplications ?? 0)
                            / $totalApplications
                        ) * 100
                        : 0;

                @endphp



                {{-- PENDING --}}

                <div class="mb-4">

                    <div
                        class="flex justify-between
                               text-sm mb-2"
                    >

                        <span>
                            Pending
                        </span>


                        <span
                            class="font-semibold
                                   text-orange-500"
                        >
                            {{ $pendingApplications ?? 0 }}
                        </span>

                    </div>


                    <div
                        class="w-full bg-gray-200
                               rounded-full h-2"
                    >

                        <div
                            class="bg-orange-400
                                   h-2 rounded-full"
                            style="
                                width:
                                {{ $pendingPercentage }}%
                            "
                        ></div>

                    </div>

                </div>



                {{-- APPROVED --}}

                <div class="mb-4">

                    <div
                        class="flex justify-between
                               text-sm mb-2"
                    >

                        <span>
                            Approved
                        </span>


                        <span
                            class="font-semibold
                                   text-green-600"
                        >
                            {{ $approvedApplications ?? 0 }}
                        </span>

                    </div>


                    <div
                        class="w-full bg-gray-200
                               rounded-full h-2"
                    >

                        <div
                            class="bg-green-500
                                   h-2 rounded-full"
                            style="
                                width:
                                {{ $approvedPercentage }}%
                            "
                        ></div>

                    </div>

                </div>



                {{-- REJECTED --}}

                <div>

                    <div
                        class="flex justify-between
                               text-sm mb-2"
                    >

                        <span>
                            Rejected
                        </span>


                        <span
                            class="font-semibold
                                   text-red-500"
                        >
                            {{ $rejectedApplications ?? 0 }}
                        </span>

                    </div>


                    <div
                        class="w-full bg-gray-200
                               rounded-full h-2"
                    >

                        <div
                            class="bg-red-400
                                   h-2 rounded-full"
                            style="
                                width:
                                {{ $rejectedPercentage }}%
                            "
                        ></div>

                    </div>

                </div>

            </div>



            {{-- ================================================= --}}
            {{-- UPCOMING EVENTS                                   --}}
            {{-- ================================================= --}}

            <div
                class="bg-white rounded-2xl
                       shadow-sm p-6"
            >

                <div
                    class="flex justify-between
                           items-center mb-5"
                >

                    <div>

                        <h2
                            class="text-lg font-bold
                                   text-[#0e243a]"
                        >
                            Upcoming Events
                        </h2>


                        <p class="text-sm text-gray-400">
                            Volunteer events
                        </p>

                    </div>


                    <a
                        href="/events"
                        class="text-sm text-[#d39a11]
                               hover:underline"
                    >
                        View
                    </a>

                </div>



                {{-- REAL EVENTS --}}

                @forelse($upcomingEvents ?? [] as $event)

                    <div class="flex gap-4 mb-5">

                        {{-- DATE --}}

                        <div
                            class="w-12 h-12
                                   rounded-xl
                                   bg-[#f2c94c]
                                   flex flex-col
                                   items-center
                                   justify-center
                                   flex-shrink-0"
                        >

                            <span class="text-xs">

                                {{ \Carbon\Carbon::parse($event->date)->format('M') }}

                            </span>


                            <span class="text-lg font-bold">

                                {{ \Carbon\Carbon::parse($event->date)->format('d') }}

                            </span>

                        </div>



                        {{-- EVENT DETAILS --}}

                        <div class="min-w-0">

                            <h3
                                class="font-semibold
                                       text-[#0e243a]"
                            >

                                {{ $event->name }}

                            </h3>


                            <p
                                class="text-xs
                                       text-gray-500"
                            >

                                {{ $event->volunteer_count ?? 0 }}

                                volunteer(s) assigned

                            </p>

                        </div>

                    </div>


                @empty

                    <div class="py-5 text-center">

                        <p class="text-sm text-gray-400">

                            No upcoming events.

                        </p>

                    </div>

                @endforelse


            </div>


        </div>



        {{-- ================================================= --}}
        {{-- THIRD ROW                                         --}}
        {{-- ================================================= --}}

        <div
            class="grid grid-cols-1
                   lg:grid-cols-2
                   gap-6 mb-6"
        >


            {{-- ================================================= --}}
            {{-- INVENTORY ALERTS                                  --}}
            {{-- ================================================= --}}

            <div
                class="bg-white rounded-2xl
                       shadow-sm p-6"
            >

                <div
                    class="flex justify-between
                           items-center mb-5"
                >

                    <div>

                        <h2
                            class="text-lg font-bold
                                   text-[#0e243a]"
                        >
                            Inventory Alerts
                        </h2>


                        <p class="text-sm text-gray-400">
                            Items requiring attention
                        </p>

                    </div>


                    <a
                        href="/inventory-master-list"
                        class="text-sm text-[#d39a11]
                               hover:underline"
                    >
                        View
                    </a>

                </div>



                {{-- REAL INVENTORY ALERTS --}}

                @forelse($inventoryAlerts ?? [] as $item)

                    <div
                        class="flex justify-between
                               items-center
                               bg-red-50
                               rounded-xl
                               p-4 mb-3"
                    >

                        <div>

                            <p
                                class="font-semibold
                                       text-[#0e243a]"
                            >

                                {{ $item->name }}

                            </p>


                            <p
                                class="text-xs
                                       text-gray-500"
                            >

                                {{ $item->current_quantity }}

                                {{ $item->unit }}

                                remaining

                            </p>


                            <p
                                class="text-[11px]
                                       text-gray-400 mt-1"
                            >

                                Minimum:

                                {{ $item->minimum_threshold }}

                            </p>

                        </div>


                        <span
                            class="text-xs
                                   font-semibold
                                   text-red-600"
                        >
                            LOW STOCK
                        </span>

                    </div>


                @empty

                    <div class="py-5 text-center">

                        <p class="text-sm text-gray-400">

                            No inventory alerts.

                        </p>

                    </div>

                @endforelse


            </div>



            {{-- ================================================= --}}
            {{-- RECENT ACTIVITY                                   --}}
            {{-- ================================================= --}}

            <div
                class="bg-white rounded-2xl
                       shadow-sm p-6"
            >

                <h2
                    class="text-lg font-bold
                           text-[#0e243a] mb-5"
                >
                    Recent Activity
                </h2>



                {{-- REAL ACTIVITIES --}}

                @forelse($recentActivities ?? [] as $activity)

                    <div class="flex gap-4 mb-5">

                        {{-- ICON --}}

                        <div
                            class="
                                w-9 h-9
                                rounded-full
                                flex items-center
                                justify-center
                                flex-shrink-0

                                @if($activity->title === 'volunteer')
                                    bg-blue-100
                                @elseif($activity->title === 'donation')
                                    bg-green-100
                                @else
                                    bg-yellow-100
                                @endif
                            "
                        >

                            @if($activity->title === 'volunteer')

                                <span
                                    class="text-blue-600"
                                >
                                    V
                                </span>

                            @elseif($activity->title === 'donation')

                                <span
                                    class="text-green-600"
                                >
                                    ₱
                                </span>

                            @else

                                <span
                                    class="text-yellow-600"
                                >
                                    I
                                </span>

                            @endif

                        </div>



                        {{-- ACTIVITY DETAILS --}}

                        <div>

                            <p class="text-sm text-[#0e243a]">
                                <strong>
                                    {{ $activity->title }}
                                </strong>

                                {{ $activity->description }}
                            </p>

                         <p class="text-xs text-gray-400">
                            {{ $activity->date
                                ? \Carbon\Carbon::parse($activity->date, 'UTC')
                                    ->setTimezone('Asia/Manila')
                                    ->diffForHumans()
                                : 'No date'
                            }}
                        </p>

                        </div>

                    </div>


                @empty

                    <div class="py-5 text-center">

                        <p class="text-sm text-gray-400">

                            No recent activity.

                        </p>

                    </div>

                @endforelse


            </div>


        </div>


    </div>


</div>

@include('components.logout-modal')

</body>

</html>