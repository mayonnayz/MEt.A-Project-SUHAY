
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Applications | Volunteer</title>

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

    <script src="https://cdn.tailwindcss.com"></script>

    <style>

        body {
            font-family: 'Poppins', sans-serif;
        }

        .application-card {
            transition: all 0.2s ease;
        }

        .application-card:hover {
            transform: translateY(-2px);
        }

        .modal-enter {
            animation: zoomFadeIn 0.25s ease forwards;
        }

        .modal-exit {
            animation: zoomFadeOut 0.2s ease forwards;
        }

        @keyframes zoomFadeIn {

            from {
                opacity: 0;
                transform: scale(0.94);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }

        }

        @keyframes zoomFadeOut {

            from {
                opacity: 1;
                transform: scale(1);
            }

            to {
                opacity: 0;
                transform: scale(0.94);
            }

        }

    </style>

</head>


<body class="bg-[#eef1f5] text-[#0e243a]">


<div class="flex min-h-screen">


    {{-- SIDEBAR --}}
    @include('components.nav')


    {{-- MAIN CONTENT --}}
    <div class="flex-1 min-w-0 p-5 sm:p-6 lg:p-8">


        {{-- HEADER --}}
        @include('components.header', ['title' => 'Applications'])


        <div class="max-w-[1400px] mx-auto">



            {{-- MAIN PANEL --}}
            <div
                class="bg-white rounded-3xl
                       border border-gray-200
                       shadow-sm overflow-hidden"
            >


                {{-- PANEL HEADER --}}
                <div
                    class="px-5 sm:px-7 py-5
                           border-b border-gray-200
                           flex flex-col lg:flex-row
                           lg:items-center
                           lg:justify-between
                           gap-4"
                >

                    <div>

                        <h2 class="font-bold text-lg text-[#0e243a]">
                            My Applications
                        </h2>

                        <p class="text-xs text-gray-500 mt-1">
                            Search or filter your volunteer applications.
                        </p>

                    </div>


                    {{-- TOTAL APPLICATIONS --}}
                    <div
                        class="flex items-center gap-3
                               bg-[#f7f9fb]
                               border border-gray-200
                               rounded-2xl px-4 py-3"
                    >

                        <div
                            class="w-10 h-10 rounded-xl
                                   bg-[#0e243a]
                                   flex items-center
                                   justify-center"
                        >

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="w-5 h-5 text-white"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                            >

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h6l5 5v11a2 2 0 01-2 2z"
                                />

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M13 3v5h5"
                                />

                            </svg>

                        </div>


                        <div>

                            <p class="text-[11px] text-gray-500 font-medium">
                                Total Applications
                            </p>

                            <p class="text-lg font-bold text-[#0e243a]">
                                {{ count($applications) }}
                            </p>

                        </div>

                    </div>

                </div>


                {{-- SEARCH + FILTER --}}
                <div class="px-5 sm:px-7 py-5 bg-[#fafbfc]">

                    <div class="flex flex-col md:flex-row gap-3">


                        {{-- SEARCH --}}
                        <div class="relative flex-1">

                            <div
                                class="absolute left-4 top-1/2
                                       -translate-y-1/2
                                       text-gray-400"
                            >

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="w-5 h-5"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="2"
                                >

                                    <circle
                                        cx="11"
                                        cy="11"
                                        r="7"
                                    />

                                    <line
                                        x1="20"
                                        y1="20"
                                        x2="16.65"
                                        y2="16.65"
                                    />

                                </svg>

                            </div>


                            <input
                                type="text"
                                id="searchInput"
                                placeholder="Search by event name or NGO..."
                                class="w-full h-12
                                       pl-11 pr-4
                                       bg-white
                                       border border-gray-300
                                       rounded-xl
                                       text-sm
                                       outline-none
                                       transition
                                       focus:border-[#0e243a]
                                       focus:ring-2
                                       focus:ring-[#0e243a]/10"
                            >

                        </div>


                        {{-- FILTER --}}
                        <div class="relative md:w-56">

                            <div
                                class="absolute left-4 top-1/2
                                       -translate-y-1/2
                                       text-gray-400
                                       pointer-events-none"
                            >

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="w-5 h-5"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="2"
                                >

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M3 5h18M6 12h12M10 19h4"
                                    />

                                </svg>

                            </div>


                            <select
                                id="filterType"
                                class="w-full h-12
                                       pl-11 pr-4
                                       bg-white
                                       border border-gray-300
                                       rounded-xl
                                       text-sm font-medium
                                       text-gray-700
                                       outline-none
                                       cursor-pointer
                                       focus:border-[#0e243a]
                                       focus:ring-2
                                       focus:ring-[#0e243a]/10"
                            >

                                <option value="">
                                    All Applications
                                </option>

                                <option value="current">
                                    Current
                                </option>

                                <option value="past">
                                    Past
                                </option>

                            </select>

                        </div>

                    </div>

                </div>


                {{-- APPLICATION LIST --}}
                <div class="p-5 sm:p-7">

                    <div
                        id="applicationList"
                        class="space-y-3"
                    >

                        @forelse($applications as $app)

                            @php

                                $status = (string) ($app['status'] ?? '');

                            @endphp


                            {{-- APPLICATION CARD --}}
                            <div
                                class="application-card
                                       rounded-2xl
                                       border border-gray-200
                                       bg-white
                                       p-5
                                       hover:border-gray-300
                                       hover:shadow-md"

                                data-event-id="{{ $app['volunteer_event_id'] ?? '' }}"

                                data-name="{{ strtolower($app['event_name'] ?? '') }}"

                                data-ngo="{{ strtolower($app['ngo_name'] ?? '') }}"

                                data-status="{{ $status }}"
                            >

                                <div
                                    class="flex flex-col
                                           lg:flex-row
                                           lg:items-center
                                           lg:justify-between
                                           gap-5"
                                >


                                    {{-- LEFT INFORMATION --}}
                                    <div
                                        class="flex items-start
                                               gap-4
                                               min-w-0"
                                    >


                                        {{-- APPLICATION ICON --}}
                                        <div
                                            class="w-12 h-12
                                                   shrink-0
                                                   rounded-xl
                                                   bg-[#0e243a]
                                                   flex items-center
                                                   justify-center"
                                        >

                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                class="w-6 h-6 text-white"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                                stroke-width="1.8"
                                            >

                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h6l5 5v11a2 2 0 01-2 2z"
                                                />

                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="M13 3v5h5"
                                                />

                                            </svg>

                                        </div>


                                        {{-- EVENT INFORMATION --}}
                                        <div class="min-w-0">

                                            <h3
                                                class="text-base sm:text-lg
                                                       font-bold
                                                       text-[#0e243a]
                                                       truncate"
                                            >

                                                {{ $app['event_name'] ?? 'Unknown Event' }}

                                            </h3>


                                            {{-- META --}}
                                            <div
                                                class="flex flex-wrap
                                                       items-center
                                                       gap-x-4
                                                       gap-y-2
                                                       mt-2
                                                       text-xs sm:text-sm
                                                       text-gray-500"
                                            >


                                                {{-- DATE --}}
                                                <span
                                                    class="flex items-center gap-1.5"
                                                >

                                                    <svg
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        class="w-4 h-4"
                                                        fill="none"
                                                        viewBox="0 0 24 24"
                                                        stroke="currentColor"
                                                        stroke-width="2"
                                                    >

                                                        <rect
                                                            x="3"
                                                            y="4"
                                                            width="18"
                                                            height="18"
                                                            rx="2"
                                                            ry="2"
                                                        />

                                                        <line
                                                            x1="16"
                                                            y1="2"
                                                            x2="16"
                                                            y2="6"
                                                        />

                                                        <line
                                                            x1="8"
                                                            y1="2"
                                                            x2="8"
                                                            y2="6"
                                                        />

                                                        <line
                                                            x1="3"
                                                            y1="10"
                                                            x2="21"
                                                            y2="10"
                                                        />

                                                    </svg>


                                                    @if(!empty($app['date']))

                                                        {{ \Carbon\Carbon::parse($app['date'])->format('F d, Y') }}

                                                    @else

                                                        No date

                                                    @endif

                                                </span>


                                                {{-- NGO --}}
                                                <span
                                                    class="flex items-center gap-1.5"
                                                >

                                                    <svg
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        class="w-4 h-4"
                                                        fill="none"
                                                        viewBox="0 0 24 24"
                                                        stroke="currentColor"
                                                        stroke-width="2"
                                                    >

                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            d="M3 21h18M5 21V9l7-4 7 4v12M9 21v-6h6v6M8 10h.01M12 10h.01M16 10h.01"
                                                        />

                                                    </svg>


                                                    {{ $app['ngo_name'] ?? 'Unknown NGO' }}

                                                </span>

                                            </div>

                                        </div>

                                    </div>


                                    {{-- RIGHT SIDE --}}
                                    <div
                                        class="flex flex-row
                                               lg:flex-col
                                               items-center
                                               lg:items-end
                                               justify-between
                                               lg:justify-center
                                               gap-3
                                               shrink-0"
                                    >


                                        {{-- STATUS --}}
                                        @if($status === '1')

                                            <span
                                                class="px-3 py-1.5
                                                       rounded-full
                                                       bg-green-50
                                                       text-green-700
                                                       border border-green-200
                                                       text-[11px]
                                                       font-bold"
                                            >
                                                APPROVED
                                            </span>

                                        @elseif($status === '0')

                                            <span
                                                class="px-3 py-1.5
                                                       rounded-full
                                                       bg-red-50
                                                       text-red-700
                                                       border border-red-200
                                                       text-[11px]
                                                       font-bold"
                                            >
                                                REJECTED
                                            </span>

                                        @else

                                            <span
                                                class="px-3 py-1.5
                                                       rounded-full
                                                       bg-yellow-50
                                                       text-yellow-700
                                                       border border-yellow-200
                                                       text-[11px]
                                                       font-bold"
                                            >
                                                PENDING
                                            </span>

                                        @endif


                                        {{-- VIEW BUTTON --}}
                                        <button
                                            type="button"
                                            class="viewBtn
                                                   px-5 py-2.5
                                                   rounded-xl
                                                   bg-[#d4a017]
                                                   text-white
                                                   font-semibold
                                                   text-xs sm:text-sm
                                                   hover:bg-[#c18f12]
                                                   transition
                                                   shadow-sm
                                                   hover:shadow"
                                            
                                            data-first_name="{{ $app['first_name'] ?? '' }}"

                                            data-last_name="{{ $app['last_name'] ?? '' }}"

                                            data-address="{{ $app['address'] ?? '' }}"

                                            data-contact="{{ $app['contact'] ?? '' }}"

                                            data-email="{{ $app['email'] ?? '' }}"

                                            data-dob="{{ $app['dob'] ?? '' }}"

                                            data-date="{{ $app['date'] ?? '' }}"

                                            data-availability="{{ $app['availability'] ?? '' }}"

                                            data-skills="{{ $app['skills'] ?? '' }}"

                                            data-interests="{{ $app['interests'] ?? '' }}"

                                            data-experience_details="{{ $app['experience_details'] ?? '' }}"

                                            data-has_experience="{{ $app['has_experience'] ?? 0 }}"

                                            data-remarks="{{ $app['remarks'] ?? '' }}"
                                        >

                                            View Details

                                        </button>

                                    </div>

                                </div>

                            </div>

                        @empty


                            {{-- EMPTY STATE --}}
                            <div class="py-16 text-center">

                                <div
                                    class="w-16 h-16
                                           mx-auto mb-4
                                           rounded-2xl
                                           bg-gray-100
                                           flex items-center
                                           justify-center"
                                >

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="w-8 h-8 text-gray-400"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="1.7"
                                    >

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h6l5 5v11a2 2 0 01-2 2z"
                                        />

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M13 3v5h5"
                                        />

                                    </svg>

                                </div>


                                <h3
                                    class="font-semibold
                                           text-gray-700"
                                >
                                    No applications found
                                </h3>


                                <p class="text-sm text-gray-400 mt-1">
                                    Your volunteer applications will appear here.
                                </p>

                            </div>

                        @endforelse


                        {{-- NO SEARCH RESULTS --}}
                        <div
                            id="noResults"
                            class="hidden py-12 text-center"
                        >

                            <div
                                class="w-14 h-14
                                       mx-auto mb-3
                                       rounded-2xl
                                       bg-gray-100
                                       flex items-center
                                       justify-center"
                            >

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="w-7 h-7 text-gray-400"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="2"
                                >

                                    <circle
                                        cx="11"
                                        cy="11"
                                        r="7"
                                    />

                                    <line
                                        x1="20"
                                        y1="20"
                                        x2="16.65"
                                        y2="16.65"
                                    />

                                </svg>

                            </div>


                            <p class="font-semibold text-gray-600">
                                No applications found
                            </p>


                            <p class="text-xs text-gray-400 mt-1">
                                Try adjusting your search or filter.
                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


{{-- LOGOUT MODAL --}}
@include('components.logout-modal')


{{-- VIEW APPLICATION MODAL --}}
<div
    id="viewModal"
    class="fixed inset-0
           bg-[#071827]/60
           backdrop-blur-sm
           hidden
           items-center
           justify-center
           z-50
           p-4"
>


    <div
        id="viewModalContent"
        class="bg-white
               rounded-3xl
               shadow-2xl
               w-full
               max-w-2xl
               max-h-[90vh]
               overflow-y-auto"
    >


        {{-- MODAL HEADER --}}
        <div
            class="relative
                   px-6 py-6
                   border-b border-gray-200
                   text-center"
        >

            <button
                type="button"
                onclick="closeModal()"
                class="absolute
                       top-4 right-5
                       w-9 h-9
                       rounded-full
                       bg-gray-100
                       text-gray-500
                       text-xl
                       hover:bg-gray-200
                       transition"
            >
                ×
            </button>


            <div
                class="w-14 h-14
                       mx-auto mb-3
                       rounded-2xl
                       bg-[#0e243a]
                       flex items-center
                       justify-center"
            >

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="w-7 h-7 text-white"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="1.8"
                >

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h6l5 5v11a2 2 0 01-2 2z"
                    />

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M13 3v5h5"
                    />

                </svg>

            </div>


            <h2 class="font-bold text-xl text-[#0e243a]">
                Volunteer Application
            </h2>


            <p class="text-xs text-gray-500 mt-1">
                Application details
            </p>

        </div>


        {{-- MODAL BODY --}}
        <div class="p-6 space-y-5">


            {{-- PERSONAL INFORMATION --}}
            <div>

                <div
                    class="flex items-center gap-2
                           mb-3"
                >

                    <div
                        class="w-2 h-6
                               rounded-full
                               bg-[#d4a017]"
                    ></div>

                    <h3
                        class="font-bold
                               text-sm
                               text-[#0e243a]"
                    >
                        Personal Information
                    </h3>

                </div>


                <div
                    class="rounded-2xl
                           border border-gray-200
                           overflow-hidden"
                >

                    <div
                        class="grid grid-cols-1
                               sm:grid-cols-2"
                    >

                        <div class="p-4 border-b sm:border-r border-gray-200">

                            <p class="text-[11px] text-gray-400 font-medium">
                                First Name
                            </p>

                            <p
                                id="modalFirstName"
                                class="text-sm font-semibold text-gray-700 mt-1"
                            ></p>

                        </div>


                        <div class="p-4 border-b border-gray-200">

                            <p class="text-[11px] text-gray-400 font-medium">
                                Last Name
                            </p>

                            <p
                                id="modalLastName"
                                class="text-sm font-semibold text-gray-700 mt-1"
                            ></p>

                        </div>


                        <div class="p-4 border-b sm:border-r border-gray-200">

                            <p class="text-[11px] text-gray-400 font-medium">
                                Address
                            </p>

                            <p
                                id="modalAddress"
                                class="text-sm font-semibold text-gray-700 mt-1"
                            ></p>

                        </div>


                        <div class="p-4 border-b border-gray-200">

                            <p class="text-[11px] text-gray-400 font-medium">
                                Contact
                            </p>

                            <p
                                id="modalContact"
                                class="text-sm font-semibold text-gray-700 mt-1"
                            ></p>

                        </div>


                        <div class="p-4 border-b sm:border-r border-gray-200">

                            <p class="text-[11px] text-gray-400 font-medium">
                                Email
                            </p>

                            <p
                                id="modalEmail"
                                class="text-sm font-semibold text-gray-700 mt-1 break-all"
                            ></p>

                        </div>


                        <div class="p-4 border-b border-gray-200">

                            <p class="text-[11px] text-gray-400 font-medium">
                                Date of Birth
                            </p>

                            <p
                                id="modalDob"
                                class="text-sm font-semibold text-gray-700 mt-1"
                            ></p>

                        </div>

                    </div>

                </div>

            </div>


            {{-- APPLICATION DETAILS --}}
            <div>

                <div
                    class="flex items-center gap-2
                           mb-3"
                >

                    <div
                        class="w-2 h-6
                               rounded-full
                               bg-[#d4a017]"
                    ></div>

                    <h3
                        class="font-bold
                               text-sm
                               text-[#0e243a]"
                    >
                        Application Details
                    </h3>

                </div>


                <div
                    class="grid grid-cols-1
                           sm:grid-cols-2
                           gap-3"
                >

                    <div
                        class="bg-gray-50
                               rounded-xl
                               p-4"
                    >

                        <p class="text-[11px] text-gray-400 font-medium">
                            Application Date
                        </p>

                        <p
                            id="modalDate"
                            class="text-sm font-semibold text-gray-700 mt-1"
                        ></p>

                    </div>


                    <div
                        class="bg-gray-50
                               rounded-xl
                               p-4"
                    >

                        <p class="text-[11px] text-gray-400 font-medium">
                            Availability
                        </p>

                        <p
                            id="modalAvailability"
                            class="text-sm font-semibold text-gray-700 mt-1"
                        ></p>

                    </div>


                    <div
                        class="bg-gray-50
                               rounded-xl
                               p-4"
                    >

                        <p class="text-[11px] text-gray-400 font-medium">
                            Skills
                        </p>

                        <p
                            id="modalSkills"
                            class="text-sm font-semibold text-gray-700 mt-1"
                        ></p>

                    </div>


                    <div
                        class="bg-gray-50
                               rounded-xl
                               p-4"
                    >

                        <p class="text-[11px] text-gray-400 font-medium">
                            Interests
                        </p>

                        <p
                            id="modalInterests"
                            class="text-sm font-semibold text-gray-700 mt-1"
                        ></p>

                    </div>

                </div>

            </div>


            {{-- EXPERIENCE --}}
            <div>

                <div
                    class="flex items-center gap-2
                           mb-3"
                >

                    <div
                        class="w-2 h-6
                               rounded-full
                               bg-[#d4a017]"
                    ></div>

                    <h3
                        class="font-bold
                               text-sm
                               text-[#0e243a]"
                    >
                        Experience
                    </h3>

                </div>


                <div
                    class="rounded-2xl
                           bg-gray-50
                           p-4"
                >

                    <div class="mb-4">

                        <p class="text-[11px] text-gray-400 font-medium">
                            Previous Experience
                        </p>

                        <p
                            id="modalHasExperience"
                            class="text-sm font-semibold text-gray-700 mt-1"
                        ></p>

                    </div>


                    <div>

                        <p class="text-[11px] text-gray-400 font-medium">
                            Experience Details
                        </p>

                        <p
                            id="modalExperience"
                            class="text-sm font-medium text-gray-700 mt-1 whitespace-pre-line"
                        ></p>

                    </div>

                </div>

            </div>


            {{-- MOTIVATION --}}
            <div>

                <div
                    class="flex items-center gap-2
                           mb-3"
                >

                    <div
                        class="w-2 h-6
                               rounded-full
                               bg-[#d4a017]"
                    ></div>

                    <h3
                        class="font-bold
                               text-sm
                               text-[#0e243a]"
                    >
                        Motivation
                    </h3>

                </div>


                <div
                    class="rounded-2xl
                           bg-gray-50
                           p-4"
                >

                    <p
                        id="modalRemarks"
                        class="text-sm
                               text-gray-700
                               leading-relaxed
                               whitespace-pre-line"
                    ></p>

                </div>

            </div>


        </div>


        {{-- MODAL FOOTER --}}
        <div class="px-6 pb-6">

            <button
                type="button"
                onclick="closeModal()"
                class="w-full
                       h-11
                       rounded-xl
                       bg-[#0e243a]
                       text-white
                       font-semibold
                       text-sm
                       hover:bg-[#162f4a]
                       transition"
            >

                Close

            </button>

        </div>

    </div>

</div>


<script>


// =========================================================
// SEARCH + FILTER
// =========================================================

const searchInput =
    document.getElementById('searchInput');

const filterType =
    document.getElementById('filterType');

const cards =
    document.querySelectorAll('.application-card');

const noResults =
    document.getElementById('noResults');


if (searchInput) {

    searchInput.addEventListener(
        'keyup',
        filterApplications
    );

}


if (filterType) {

    filterType.addEventListener(
        'change',
        filterApplications
    );

}


function filterApplications() {

    const search =
        searchInput.value
            .toLowerCase()
            .trim();

    const filter =
        filterType.value;

    let visible = 0;


    cards.forEach(card => {

        const name =
            card.dataset.name || "";

        const ngo =
            card.dataset.ngo || "";

        const status =
            card.dataset.status || "";


        // SEARCH
        const matchSearch =
            name.includes(search) ||
            ngo.includes(search);


        // FILTER
        let matchFilter = true;


        if (filter === "current") {

            matchFilter =
                status === "1";

        }


        else if (filter === "past") {

            matchFilter =
                status === "0";

        }


        if (matchSearch && matchFilter) {

            card.classList.remove('hidden');

            visible++;

        }

        else {

            card.classList.add('hidden');

        }

    });


    if (noResults) {

        noResults.style.display =
            visible === 0 && cards.length > 0
                ? 'block'
                : 'none';

    }

}


// =========================================================
// VIEW APPLICATION
// =========================================================

document
    .querySelectorAll('.viewBtn')
    .forEach(button => {

        button.addEventListener(
            'click',
            function (event) {

                event.stopPropagation();


                document.getElementById(
                    'modalFirstName'
                ).textContent =
                    this.dataset.first_name || 'N/A';


                document.getElementById(
                    'modalLastName'
                ).textContent =
                    this.dataset.last_name || 'N/A';


                document.getElementById(
                    'modalAddress'
                ).textContent =
                    this.dataset.address || 'N/A';


                document.getElementById(
                    'modalContact'
                ).textContent =
                    this.dataset.contact || 'N/A';


                document.getElementById(
                    'modalEmail'
                ).textContent =
                    this.dataset.email || 'N/A';


                document.getElementById(
                    'modalDob'
                ).textContent =
                    this.dataset.dob || 'N/A';


                document.getElementById(
                    'modalDate'
                ).textContent =
                    this.dataset.date || 'N/A';


                document.getElementById(
                    'modalAvailability'
                ).textContent =
                    this.dataset.availability || 'N/A';


                document.getElementById(
                    'modalSkills'
                ).textContent =
                    this.dataset.skills || 'N/A';


                document.getElementById(
                    'modalInterests'
                ).textContent =
                    this.dataset.interests || 'N/A';


                document.getElementById(
                    'modalExperience'
                ).textContent =
                    this.dataset.experience_details || 'N/A';


                document.getElementById(
                    'modalHasExperience'
                ).textContent =
                    this.dataset.has_experience === "1"
                        ? "Yes"
                        : "No";


                document.getElementById(
                    'modalRemarks'
                ).textContent =
                    this.dataset.remarks || 'N/A';


                openModal();

            }
        );

    });


// =========================================================
// OPEN MODAL
// =========================================================

function openModal() {

    const modal =
        document.getElementById('viewModal');

    const content =
        document.getElementById('viewModalContent');


    modal.classList.remove('hidden');

    modal.classList.add('flex');


    content.classList.remove('modal-exit');

    content.classList.add('modal-enter');


    document.body.style.overflow =
        'hidden';

}


// =========================================================
// CLOSE MODAL
// =========================================================

function closeModal() {

    const modal =
        document.getElementById('viewModal');

    const content =
        document.getElementById('viewModalContent');


    content.classList.remove('modal-enter');

    content.classList.add('modal-exit');


    setTimeout(() => {

        modal.classList.add('hidden');

        modal.classList.remove('flex');

        content.classList.remove('modal-exit');

        document.body.style.overflow =
            'auto';

    }, 200);

}


// =========================================================
// CLICK OUTSIDE MODAL
// =========================================================

const viewModal =
    document.getElementById('viewModal');


if (viewModal) {

    viewModal.addEventListener(
        'click',
        function (event) {

            if (event.target === viewModal) {

                closeModal();

            }

        }
    );

}


// =========================================================
// ESC KEY
// =========================================================

document.addEventListener(
    'keydown',
    function (event) {

        if (event.key === 'Escape') {

            closeModal();

        }

    }
);


// =========================================================
// HIGHLIGHT APPLICATION
// =========================================================

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const highlightId =
            new URLSearchParams(
                window.location.search
            ).get('highlight');


        if (!highlightId) {
            return;
        }


        const target =
            document.querySelector(
                `[data-event-id="${highlightId}"]`
            );


        if (target) {

            target.classList.add(
                'bg-yellow-50',
                'ring-2',
                'ring-[#d4a017]'
            );


            target.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });

        }

    }
);


// =========================================================
// LOGOUT MODAL
// =========================================================

function openLogoutModal() {

    const modal =
        document.getElementById('logoutModal');


    if (modal) {

        modal.classList.remove('hidden');

        modal.classList.add('flex');

    }

}


function closeLogoutModal() {

    const modal =
        document.getElementById('logoutModal');


    if (modal) {

        modal.classList.add('hidden');

        modal.classList.remove('flex');

    }

}

</script>


</body>
</html>

