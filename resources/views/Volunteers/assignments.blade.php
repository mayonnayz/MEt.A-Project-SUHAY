<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assignments</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        .assignment-row {
            transition: all 0.2s ease;
        }

        .assignment-row:hover {
            background-color: #f8fafc;
        }
    </style>
</head>

<body class="bg-gray-200">

<div class="flex">

    @include('components.nav')

    <div class="flex-1 p-8">

        @include('components.header', ['title' => 'Assignments'])


        {{-- =========================================================
            EVENTS / ASSIGNMENTS TABS
        ========================================================== --}}
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


        {{-- =========================================================
            MAIN ASSIGNMENTS SECTION
        ========================================================== --}}
        <div class="bg-[#f5f5f5] rounded-[22px] border-[10px] border-[#0e243a] p-6 sm:p-8">


         

            {{-- =====================================================
                SEARCH + FILTERS
            ====================================================== --}}
            <div class="flex flex-col lg:flex-row lg:items-center
                        lg:justify-between gap-4 mb-7">


                {{-- SEARCH --}}
                <div class="flex items-center
                            bg-white border-2 border-[#0e243a]
                            rounded-full px-5 py-2.5
                            w-full lg:flex-1
                            shadow-sm
                            focus-within:ring-2
                            focus-within:ring-[#d39a11]/40">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5 text-[#0e243a] mr-3 flex-shrink-0"
                         viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="2">

                        <circle cx="11" cy="11" r="7"></circle>
                        <line x1="21" y1="21"
                              x2="16.65" y2="16.65"></line>

                    </svg>

                    <input
                        id="searchInput"
                        type="text"
                        placeholder="Search NGO, event, or activity..."
                        class="w-full outline-none bg-transparent
                               text-sm font-medium
                               text-[#0e243a]
                               placeholder:text-gray-400"
                    >

                </div>


                {{-- FILTERS --}}
                <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">


                    {{-- STATUS FILTER --}}
                    <div class="relative">

                        <select
                            id="statusFilter"
                            class="appearance-none
                                   w-full sm:w-44
                                   bg-white
                                   border-2 border-gray-200
                                   rounded-full
                                   px-5 py-2.5 pr-10
                                   text-sm font-medium
                                   text-[#0e243a]
                                   outline-none
                                   cursor-pointer
                                   hover:border-[#0e243a]
                                   focus:border-[#0e243a]
                                   transition"
                        >

                            <option value="">
                                All Status
                            </option>

                            <option value="On Going">
                                On Going
                            </option>

                            <option value="Completed">
                                Completed
                            </option>

                        </select>

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="absolute right-4 top-1/2
                                    -translate-y-1/2
                                    h-4 w-4
                                    text-gray-500
                                    pointer-events-none"
                             viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="2">

                            <polyline points="6 9 12 15 18 9"></polyline>

                        </svg>

                    </div>


                    {{-- DATE FILTER --}}
                    <div class="relative">

                        <input
                            id="dateFilter"
                            type="date"
                            class="w-full sm:w-44
                                   bg-white
                                   border-2 border-gray-200
                                   rounded-full
                                   px-5 py-2.5
                                   text-sm font-medium
                                   text-[#0e243a]
                                   outline-none
                                   cursor-pointer
                                   hover:border-[#0e243a]
                                   focus:border-[#0e243a]
                                   transition"
                        >

                    </div>

                </div>

            </div>


            {{-- =====================================================
                ASSIGNMENTS TABLE
            ====================================================== --}}
            <div class="bg-white rounded-2xl border border-gray-200
                        overflow-hidden shadow-sm">


                {{-- TABLE HEADER --}}
                <div class="px-5 py-4 border-b border-gray-200
                            flex items-center justify-between">

                    <div>

                        <h3 class="text-base font-bold text-[#0e243a]">
                            Assignment List
                        </h3>

                        <p class="text-xs text-gray-400 mt-0.5">
                            Click an assignment to view its application.
                        </p>

                    </div>

                    <div
                        id="assignmentCount"
                        class="text-xs font-semibold
                               text-[#0e243a]
                               bg-gray-100
                               px-3 py-1.5
                               rounded-full"
                    >
                        {{ count($assignments) }}
                        {{ count($assignments) == 1 ? 'Assignment' : 'Assignments' }}
                    </div>

                </div>


                {{-- TABLE WRAPPER --}}
                <div class="overflow-x-auto">

                    <table class="w-full text-sm">

                        <thead class="bg-[#0e243a] text-white">

                            <tr>

                                <th class="px-5 py-4 text-left
                                           font-semibold whitespace-nowrap">
                                    No.
                                </th>

                                <th class="px-5 py-4 text-left
                                           font-semibold whitespace-nowrap">
                                    NGO
                                </th>

                                <th class="px-5 py-4 text-left
                                           font-semibold whitespace-nowrap">
                                    Event
                                </th>

                                <th class="px-5 py-4 text-left
                                           font-semibold whitespace-nowrap">
                                    Date
                                </th>

                                <th class="px-5 py-4 text-left
                                           font-semibold whitespace-nowrap">
                                    Activity Assigned
                                </th>

                                <th class="px-5 py-4 text-center
                                           font-semibold whitespace-nowrap">
                                    Status
                                </th>

                            </tr>

                        </thead>


                        <tbody id="assignmentTable"
                               class="divide-y divide-gray-100">


                        @forelse($assignments as $index => $a)

                            <tr
                                class="assignment-row cursor-pointer
                                       hover:bg-gray-50"
                                onclick="goToApplication('{{ $a['event_id'] }}')"
                            >


                                {{-- NUMBER --}}
                                <td class="px-5 py-4 text-gray-500
                                           font-medium whitespace-nowrap">

                                    {{ $index + 1 }}

                                </td>


                                {{-- NGO --}}
                                <td class="px-5 py-4
                                           font-semibold text-[#0e243a]
                                           whitespace-nowrap">

                                    {{ $a['ngo_name'] }}

                                </td>


                                {{-- EVENT --}}
                                <td class="px-5 py-4
                                           text-gray-700
                                           whitespace-nowrap">

                                    {{ $a['event_name'] }}

                                </td>


                                {{-- DATE --}}
                                <td class="px-5 py-4
                                           text-gray-600
                                           whitespace-nowrap">

                                    <div class="flex items-center gap-2">

                                        <img
                                            src="{{ asset('images/VolunteerIcons/VDate.png') }}"
                                            class="h-5 w-5 object-contain"
                                            alt="Date"
                                        >

                                        <span>
                                            {{ \Carbon\Carbon::parse($a['date'])->format('F d, Y') }}
                                        </span>

                                    </div>

                                </td>


                                {{-- ACTIVITY --}}
                                <td class="px-5 py-4
                                           text-gray-700
                                           max-w-xs">

                                    <div class="truncate"
                                         title="{{ $a['activity'] }}">

                                        {{ $a['activity'] }}

                                    </div>

                                </td>


                                {{-- STATUS --}}
                                <td class="px-5 py-4 text-center status">

                                    @if($a['status'] === 'Completed')

                                        <span
                                            class="inline-flex items-center
                                                   gap-2 px-3 py-1.5
                                                   rounded-full
                                                   bg-green-100
                                                   text-green-700
                                                   text-xs
                                                   font-bold"
                                        >

                                            <span
                                                class="w-1.5 h-1.5
                                                       rounded-full
                                                       bg-green-500">
                                            </span>

                                            Completed

                                        </span>

                                    @else

                                        <span
                                            class="inline-flex items-center
                                                   gap-2 px-3 py-1.5
                                                   rounded-full
                                                   bg-orange-100
                                                   text-orange-600
                                                   text-xs
                                                   font-bold"
                                        >

                                            <span
                                                class="w-1.5 h-1.5
                                                       rounded-full
                                                       bg-orange-500">
                                            </span>

                                            On Going

                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="6"
                                    class="px-5 py-12 text-center"
                                >

                                    <div
                                        class="w-14 h-14 mx-auto mb-4
                                               rounded-full
                                               bg-gray-100
                                               flex items-center
                                               justify-center"
                                    >

                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="h-7 w-7 text-gray-400"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="2"
                                        >

                                            <rect
                                                x="3"
                                                y="4"
                                                width="18"
                                                height="18"
                                                rx="2"
                                                ry="2">
                                            </rect>

                                            <line
                                                x1="16"
                                                y1="2"
                                                x2="16"
                                                y2="6">
                                            </line>

                                            <line
                                                x1="8"
                                                y1="2"
                                                x2="8"
                                                y2="6">
                                            </line>

                                            <line
                                                x1="3"
                                                y1="10"
                                                x2="21"
                                                y2="10">
                                            </line>

                                        </svg>

                                    </div>

                                    <h3 class="text-base font-bold
                                               text-[#0e243a]">

                                        No Assignments Found

                                    </h3>

                                    <p class="text-sm text-gray-500 mt-1">

                                        You currently have no assigned
                                        volunteer activities.

                                    </p>

                                </td>

                            </tr>

                        @endforelse


                        {{-- NO SEARCH/FILTER RESULTS --}}
                        <tr id="noResults" class="hidden">

                            <td
                                colspan="6"
                                class="px-5 py-12 text-center"
                            >

                                <div
                                    class="w-14 h-14 mx-auto mb-4
                                           rounded-full
                                           bg-gray-100
                                           flex items-center
                                           justify-center"
                                >

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-7 w-7 text-gray-400"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                    >

                                        <circle
                                            cx="11"
                                            cy="11"
                                            r="7">
                                        </circle>

                                        <line
                                            x1="21"
                                            y1="21"
                                            x2="16.65"
                                            y2="16.65">
                                        </line>

                                    </svg>

                                </div>

                                <h3 class="text-base font-bold
                                           text-[#0e243a]">

                                    No Matching Assignments

                                </h3>

                                <p class="text-sm text-gray-500 mt-1">

                                    Try changing your search or filter.

                                </p>

                            </td>

                        </tr>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

</div>


@include('components.logout-modal')


<script>

    /* =========================================================
       FILTER ELEMENTS
    ========================================================== */

    const searchInput =
        document.getElementById("searchInput");

    const statusFilter =
        document.getElementById("statusFilter");

    const dateFilter =
        document.getElementById("dateFilter");

    const assignmentRows =
        document.querySelectorAll(".assignment-row");

    const noResults =
        document.getElementById("noResults");

    const assignmentCount =
        document.getElementById("assignmentCount");


    /* =========================================================
       FILTER TABLE
    ========================================================== */

    function filterTable() {

        const search =
            searchInput.value.toLowerCase().trim();

        const status =
            statusFilter.value.toLowerCase();

        const date =
            dateFilter.value;

        let visibleCount = 0;


        assignmentRows.forEach(row => {

            const text =
                row.innerText.toLowerCase();

            const rowStatus =
                row.querySelector(".status")
                    .innerText
                    .toLowerCase()
                    .trim();

            const rowDate =
                row.children[3]
                    .innerText
                    .trim();


            /* SEARCH */
            const matchSearch =
                text.includes(search);


            /* STATUS */
            const matchStatus =
                status === "" ||
                rowStatus.includes(status);


            /* DATE */
            let matchDate = true;


            if (date) {

                const selectedDate =
                    new Date(date + "T00:00:00")
                        .toDateString();

                const rowDateObj =
                    new Date(rowDate)
                        .toDateString();

                matchDate =
                    selectedDate === rowDateObj;

            }


            /* FINAL RESULT */
            const visible =
                matchSearch &&
                matchStatus &&
                matchDate;


            if (visible) {

                row.style.display = "";

                visibleCount++;

            } else {

                row.style.display = "none";

            }

        });


        /* =====================================================
           NO RESULTS MESSAGE
        ====================================================== */

        if (visibleCount === 0 && assignmentRows.length > 0) {

            noResults.classList.remove("hidden");

        } else {

            noResults.classList.add("hidden");

        }


        /* =====================================================
           ASSIGNMENT COUNT
        ====================================================== */

        if (assignmentCount) {

            assignmentCount.textContent =
                `${visibleCount} ${
                    visibleCount === 1
                        ? "Assignment"
                        : "Assignments"
                }`;

        }

    }


    /* =========================================================
       GO TO APPLICATION
    ========================================================== */

    function goToApplication(eventId) {

        window.location.href =
            `/volunteer/applications?highlight=${eventId}`;

    }


    /* =========================================================
       FILTER EVENTS
    ========================================================== */

    searchInput.addEventListener(
        "input",
        filterTable
    );

    statusFilter.addEventListener(
        "change",
        filterTable
    );

    dateFilter.addEventListener(
        "change",
        filterTable
    );


    /* =========================================================
       LOGOUT MODAL
    ========================================================== */

    function openLogoutModal() {

        document.getElementById('logoutModal')
            .classList.remove('hidden');

        document.getElementById('logoutModal')
            .classList.add('flex');

    }


    function closeLogoutModal() {

        document.getElementById('logoutModal')
            .classList.add('hidden');

        document.getElementById('logoutModal')
            .classList.remove('flex');

    }

</script>

</body>
</html>