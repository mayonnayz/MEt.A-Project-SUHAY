<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        .event-card {
            transition: all 0.2s ease;
        }

        .event-card:hover {
            transform: translateY(-2px);
        }

        .modal-enter {
            animation: zoomFadeIn 0.25s ease forwards;
        }

        .modal-exit {
            animation: zoomFadeOut 0.2s ease forwards;
        }

        @keyframes zoomFadeIn {
            0% {
                opacity: 0;
                transform: scale(0.92);
            }
            100% {
                opacity: 1;
                transform: scale(1);
            }
        }

        @keyframes zoomFadeOut {
            0% {
                opacity: 1;
                transform: scale(1);
            }
            100% {
                opacity: 0;
                transform: scale(0.92);
            }
        }
    </style>
</head>

<body class="bg-gray-200">

<div class="flex">

    @include('components.nav')

    <div class="flex-1 p-8">
        @include('components.header', ['title' => 'Events'])

        {{-- =========================================================
            EVENTS / ASSIGNMENTS TABS
        ========================================================== --}}
        <div class="bg-[#0e243a] p-4 rounded-2xl flex gap-4 mb-6 flex-wrap">

            <a href="/volunteer/events"
                class="bg-gray-200 text-[#0e243a] px-6 py-2 rounded-full font-semibold">
                Events
            </a>

            <a href="/volunteer/assignments"
                class="bg-[#f2c94c] text-[#0e243a] px-6 py-2 rounded-full font-semibold hover:opacity-90 transition">
                Assignments
            </a>

        </div>


        {{-- =========================================================
            MAIN EVENTS SECTION
        ========================================================== --}}
        <div class="bg-[#f5f5f5] rounded-[22px] border-[10px] border-[#0e243a] p-6 sm:p-8">


            {{-- =====================================================
                SEARCH BAR
            ====================================================== --}}
            <form method="GET"
                  action="/volunteer/events"
                  class="mb-7">

                <div class="flex items-center bg-white border-2 border-[#0e243a]
                            rounded-full px-5 py-2.5 shadow-sm
                            focus-within:ring-2 focus-within:ring-[#d39a11]/40">

                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5 text-[#0e243a] mr-3 flex-shrink-0"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2">

                        <circle cx="11" cy="11" r="7"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>

                    </svg>

                    <input
                        type="text"
                        id="searchInput"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search events or organizations..."
                        class="w-full outline-none bg-transparent text-sm font-medium
                               text-[#0e243a] placeholder:text-gray-400"
                    />

                </div>

            </form>


            {{-- =====================================================
                EVENTS LIST
            ====================================================== --}}
            <div id="eventsList" class="space-y-4">

                @forelse($events as $event)

                    <div
                        class="event-card rounded-2xl border-2 border-[#0e243a]
                               bg-white p-5 sm:p-6 shadow-sm
                               hover:shadow-md cursor-default"
                        data-event-id="{{ $event['id'] }}"
                    >

                        <div class="flex flex-col lg:flex-row lg:items-center
                                    lg:justify-between gap-5">

                            {{-- EVENT INFORMATION --}}
                            <div class="min-w-0 flex-1">

                                {{-- EVENT TITLE --}}
                                <div class="flex items-start gap-3 mb-3">

                                    <div class="w-10 h-10 rounded-xl bg-[#0e243a]
                                                flex items-center justify-center
                                                flex-shrink-0">

                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="h-5 w-5 text-white"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="2">

                                            <rect x="3" y="4" width="18" height="18"
                                                rx="2" ry="2"></rect>

                                            <line x1="16" y1="2" x2="16" y2="6"></line>
                                            <line x1="8" y1="2" x2="8" y2="6"></line>

                                            <line x1="3" y1="10" x2="21" y2="10"></line>

                                        </svg>

                                    </div>

                                    <div class="min-w-0">

                                        <h3 class="text-lg sm:text-xl font-bold
                                                   text-[#0e243a] leading-tight">

                                            {{ $event['name'] }}

                                        </h3>

                                        <p class="text-xs text-gray-400 mt-1">
                                            Volunteer Opportunity
                                        </p>

                                    </div>

                                </div>


                                {{-- DATE + NGO --}}
                                <div class="flex flex-col sm:flex-row
                                            sm:items-center gap-3 sm:gap-6
                                            text-sm text-gray-600 ml-0 sm:ml-[52px]">

                                    {{-- DATE --}}
                                    <div class="flex items-center gap-2">

                                        <img
                                            src="{{ asset('images/VolunteerIcons/VDate.png') }}"
                                            class="h-5 w-5 object-contain"
                                            alt="Date"
                                        >

                                        <span>
                                            {{ \Carbon\Carbon::parse($event['date'])->format('F d, Y') }}
                                        </span>

                                    </div>


                                    {{-- NGO --}}
                                    <div class="flex items-center gap-2">

                                        <img
                                            src="{{ asset('images/VolunteerIcons/VNGO.png') }}"
                                            class="h-5 w-5 object-contain"
                                            alt="Organization"
                                        >

                                        <span>
                                            {{ $event['ngo_name'] ?? 'Unknown NGO' }}
                                        </span>

                                    </div>

                                </div>

                            </div>


                            {{-- ACTION BUTTONS --}}
                            <div class="flex flex-col sm:flex-row
                                        lg:flex-col xl:flex-row
                                        gap-2 sm:gap-3 lg:items-stretch">

                                {{-- VIEW ACTIVITIES --}}
                                <button
                                    type="button"
                                    class="view-activities-btn px-6 py-2.5
                                           rounded-full bg-[#0e243a]
                                           text-white font-semibold text-sm
                                           hover:bg-[#183b5a] transition
                                           whitespace-nowrap"

                                    data-title="{{ $event['name'] }}"

                                    data-date="{{ \Carbon\Carbon::parse($event['date'])->format('F d, Y') }}"

                                    data-org="{{ $event['ngo_name'] ?? 'Unknown NGO' }}"

                                    data-activities='@json(
                                        collect($event["activities"] ?? [])
                                        ->map(function ($a) {
                                            return [
                                                "name" => $a["name"] ?? "",
                                                "remarks" => $a["remarks"] ?? ""
                                            ];
                                        })
                                        ->values()
                                    )'
                                >
                                    View Activities
                                </button>


                                {{-- APPLICATION STATUS --}}
                                @php
                                    $alreadyApplied = in_array(
                                        (string)$event['id'],
                                        $applications ?? []
                                    );
                                @endphp


                                @if($alreadyApplied)

                                    <button
                                        type="button"
                                        disabled
                                        class="px-6 py-2.5 rounded-full
                                               bg-gray-300 text-gray-500
                                               font-semibold text-sm
                                               cursor-not-allowed whitespace-nowrap">

                                        Already Applied

                                    </button>

                                @else

                                    <a
                                        href="/volunteer-application-form?event_id={{ $event['id'] }}"
                                        class="px-6 py-2.5 rounded-full
                                               bg-[#d39a11] text-white
                                               font-semibold text-sm
                                               hover:bg-[#c2870d]
                                               transition text-center
                                               whitespace-nowrap">

                                        Volunteer Now

                                    </a>

                                @endif

                            </div>

                        </div>

                    </div>

                @empty

                    {{-- NO EVENTS FROM DATABASE --}}
                    <div class="bg-white border-2 border-dashed
                                border-gray-300 rounded-2xl
                                p-10 text-center">

                        <div class="w-14 h-14 mx-auto mb-4
                                    rounded-full bg-gray-100
                                    flex items-center justify-center">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-7 w-7 text-gray-400"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2">

                                <rect x="3" y="4" width="18" height="18"
                                    rx="2" ry="2"></rect>

                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                <line x1="8" y1="2" x2="8" y2="6"></line>

                                <line x1="3" y1="10" x2="21" y2="10"></line>

                            </svg>

                        </div>

                        <h3 class="text-lg font-bold text-[#0e243a]">
                            No Events Available
                        </h3>

                        <p class="text-sm text-gray-500 mt-1">
                            There are currently no volunteer events available.
                        </p>

                    </div>

                @endforelse

            </div>


            {{-- =====================================================
                NO SEARCH RESULTS
            ====================================================== --}}
            <div
                id="noResults"
                class="hidden bg-white border-2 border-dashed
                       border-gray-300 rounded-2xl
                       p-10 text-center mt-4">

                <div class="w-14 h-14 mx-auto mb-4
                            rounded-full bg-gray-100
                            flex items-center justify-center">

                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-7 w-7 text-gray-400"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2">

                        <circle cx="11" cy="11" r="7"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>

                    </svg>

                </div>

                <h3 class="text-lg font-bold text-[#0e243a]">
                    No Matching Events
                </h3>

                <p class="text-sm text-gray-500 mt-1">
                    Try searching for a different event or organization.
                </p>

            </div>

        </div>
    </div>
</div>


@include('components.logout-modal')


{{-- =============================================================
    ACTIVITIES MODAL
============================================================= --}}
<div
    id="activitiesModal"
    class="hidden fixed inset-0 z-50
           items-center justify-center p-4"
>

    {{-- BACKDROP --}}
    <div
        class="absolute inset-0 bg-black/60"
        onclick="closeActivitiesModal()"
    ></div>


    {{-- MODAL --}}
    <div
        id="activitiesModalContent"
        class="relative w-full max-w-lg
               max-h-[90vh] overflow-y-auto
               bg-white rounded-3xl
               shadow-2xl"
    >

        {{-- CLOSE BUTTON --}}
        <button
            type="button"
            onclick="closeActivitiesModal()"
            aria-label="Close modal"
            class="absolute top-4 right-4 z-10
                   w-9 h-9 rounded-full
                   bg-gray-100 text-gray-500
                   hover:bg-gray-200 hover:text-[#0e243a]
                   flex items-center justify-center
                   transition text-xl"
        >
            &times;
        </button>


        {{-- MODAL HEADER --}}
        <div class="px-6 sm:px-8 pt-7 pb-5
                    border-b border-gray-100">

            <div class="flex items-center gap-4">

                <div class="w-14 h-14 rounded-2xl
                            bg-[#0e243a]
                            flex items-center justify-center
                            flex-shrink-0">

                    <img
                        src="{{ asset('images/suhayLogo.png') }}"
                        alt="SUHAY"
                        class="h-10 w-10 object-contain"
                    >

                </div>

                <div class="pr-8">

                    <p class="text-xs font-semibold uppercase
                              tracking-wider text-[#d39a11]">
                        Volunteer Event
                    </p>

                    <h2
                        id="modalEventTitle"
                        class="text-lg sm:text-xl font-bold
                               text-[#0e243a] leading-tight mt-1"
                    >
                    </h2>

                </div>

            </div>

        </div>


        {{-- MODAL BODY --}}
        <div class="px-6 sm:px-8 py-6">

            {{-- EVENT DETAILS --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-7">

                {{-- DATE --}}
                <div class="rounded-2xl bg-gray-50
                            border border-gray-200 p-4">

                    <div class="flex items-center gap-2 mb-2">

                        <img
                            src="{{ asset('images/VolunteerIcons/VDate.png') }}"
                            alt="Date"
                            class="h-5 w-5 object-contain"
                        >

                        <span class="text-xs font-semibold
                                     text-gray-400 uppercase">
                            Date
                        </span>

                    </div>

                    <p
                        id="modalEventDate"
                        class="text-sm font-semibold text-[#0e243a]"
                    >
                    </p>

                </div>


                {{-- ORGANIZATION --}}
                <div class="rounded-2xl bg-gray-50
                            border border-gray-200 p-4">

                    <div class="flex items-center gap-2 mb-2">

                        <img
                            src="{{ asset('images/VolunteerIcons/VNGO.png') }}"
                            alt="Organization"
                            class="h-5 w-5 object-contain"
                        >

                        <span class="text-xs font-semibold
                                     text-gray-400 uppercase">
                            Organization
                        </span>

                    </div>

                    <p
                        id="modalEventOrg"
                        class="text-sm font-semibold text-[#0e243a]"
                    >
                    </p>

                </div>

            </div>


            {{-- ACTIVITIES --}}
            <div>

                <div class="flex items-center justify-between mb-3">

                    <div>

                        <h3 class="font-bold text-[#0e243a]">
                            List of Activities
                        </h3>

                        <p class="text-xs text-gray-400 mt-0.5">
                            Activities included in this event
                        </p>

                    </div>

                </div>


                <ul
                    id="modalActivitiesList"
                    class="space-y-3"
                >
                </ul>

            </div>

        </div>


        {{-- MODAL FOOTER --}}
        <div class="px-6 sm:px-8 py-5
                    border-t border-gray-100
                    flex justify-end">

            <button
                type="button"
                onclick="closeActivitiesModal()"
                class="px-7 py-2.5 rounded-full
                       bg-[#d39a11] text-white
                       font-semibold text-sm
                       hover:bg-[#c2870d]
                       transition"
            >
                Close
            </button>

        </div>

    </div>

</div>


<script>

    /* =========================================================
       ACTIVITIES MODAL
    ========================================================== */

    function closeActivitiesModal() {

        const modal = document.getElementById('activitiesModal');
        const content = document.getElementById('activitiesModalContent');

        content.classList.remove('modal-enter');
        content.classList.add('modal-exit');

        setTimeout(() => {

            modal.classList.add('hidden');
            modal.classList.remove('flex');

            content.classList.remove('modal-exit');

        }, 180);
    }


    function openActivitiesModal({ title, date, org, activities }) {

        document.getElementById('modalEventTitle').textContent = title;
        document.getElementById('modalEventDate').textContent = date;
        document.getElementById('modalEventOrg').textContent = org;

        const list = document.getElementById('modalActivitiesList');

        list.innerHTML = "";


        if (!activities || activities.length === 0) {

            const empty = document.createElement('div');

            empty.className =
                "rounded-2xl bg-gray-50 border border-gray-200 p-5 text-center";

            empty.innerHTML = `
                <div class="text-sm font-semibold text-gray-500">
                    No activities available.
                </div>
            `;

            list.appendChild(empty);

        } else {

            activities.forEach((activity, index) => {

                const li = document.createElement('li');

                li.className =
                    "rounded-2xl border border-gray-200 bg-white p-4 " +
                    "shadow-sm";


                const number = document.createElement('div');

                number.className =
                    "w-8 h-8 rounded-xl bg-[#0e243a] " +
                    "text-white text-xs font-bold " +
                    "flex items-center justify-center flex-shrink-0";

                number.textContent = index + 1;


                const content = document.createElement('div');

                content.className = "min-w-0";


                const name = document.createElement('div');

                name.className =
                    "font-semibold text-[#0e243a] text-sm";

                name.textContent = activity.name || "Unnamed Activity";


                const remarks = document.createElement('div');

                remarks.className =
                    "text-gray-500 text-xs mt-1 leading-relaxed";

                remarks.textContent =
                    activity.remarks || "No additional details provided.";


                content.appendChild(name);
                content.appendChild(remarks);


                li.className += " flex items-start gap-3";

                li.appendChild(number);
                li.appendChild(content);

                list.appendChild(li);

            });

        }


        const modal = document.getElementById('activitiesModal');
        const modalContent =
            document.getElementById('activitiesModalContent');


        modal.classList.remove('hidden');
        modal.classList.add('flex');


        modalContent.classList.remove('modal-exit');
        modalContent.classList.add('modal-enter');

    }


    /* =========================================================
       ACTIVITY BUTTONS
    ========================================================== */

    document.querySelectorAll('.view-activities-btn').forEach(btn => {

        btn.addEventListener('click', function () {

            const title = this.dataset.title;
            const date = this.dataset.date;
            const org = this.dataset.org;

            let activities = [];

            try {
                activities = JSON.parse(
                    this.dataset.activities || '[]'
                );
            } catch (error) {
                activities = [];
            }


            openActivitiesModal({
                title,
                date,
                org,
                activities
            });

        });

    });


    /* =========================================================
       SEARCH EVENTS
    ========================================================== */

    const searchInput = document.getElementById('searchInput');
    const eventCards = document.querySelectorAll('.event-card');
    const noResults = document.getElementById('noResults');


    if (searchInput) {

        searchInput.addEventListener('input', function () {

            const search =
                this.value.toLowerCase().trim();

            let visibleCount = 0;


            eventCards.forEach(card => {

                const text =
                    card.innerText.toLowerCase();

                if (text.includes(search)) {

                    card.classList.remove('hidden');

                    visibleCount++;

                } else {

                    card.classList.add('hidden');

                }

            });


            if (noResults) {

                if (visibleCount === 0 && search !== "") {

                    noResults.classList.remove('hidden');

                } else {

                    noResults.classList.add('hidden');

                }

            }

        });

    }


    /* =========================================================
       ESC KEY
    ========================================================== */

    document.addEventListener('keydown', function (event) {

        if (event.key === 'Escape') {

            const modal =
                document.getElementById('activitiesModal');

            if (modal && !modal.classList.contains('hidden')) {
                closeActivitiesModal();
            }

        }

    });


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