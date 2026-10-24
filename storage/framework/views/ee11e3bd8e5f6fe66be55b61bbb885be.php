
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


    
    <?php echo $__env->make('components.nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>


    
    <div class="flex-1 min-w-0 p-5 sm:p-6 lg:p-8">


        
        <?php echo $__env->make('components.header', ['title' => 'Applications'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>


        <div class="max-w-[1400px] mx-auto">



            
            <div
                class="bg-white rounded-3xl
                       border border-gray-200
                       shadow-sm overflow-hidden"
            >


                
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
                                <?php echo e(count($applications)); ?>

                            </p>

                        </div>

                    </div>

                </div>


                
                <div class="px-5 sm:px-7 py-5 bg-[#fafbfc]">

                    <div class="flex flex-col md:flex-row gap-3">


                        
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


                
                <div class="p-5 sm:p-7">

                    <div
                        id="applicationList"
                        class="space-y-3"
                    >

                        <?php $__empty_1 = true; $__currentLoopData = $applications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $app): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                            <?php

                                $status = (string) ($app['status'] ?? '');

                            ?>


                            
                            <div
                                class="application-card
                                       rounded-2xl
                                       border border-gray-200
                                       bg-white
                                       p-5
                                       hover:border-gray-300
                                       hover:shadow-md"

                                data-event-id="<?php echo e($app['volunteer_event_id'] ?? ''); ?>"

                                data-name="<?php echo e(strtolower($app['event_name'] ?? '')); ?>"

                                data-ngo="<?php echo e(strtolower($app['ngo_name'] ?? '')); ?>"

                                data-status="<?php echo e($status); ?>"
                            >

                                <div
                                    class="flex flex-col
                                           lg:flex-row
                                           lg:items-center
                                           lg:justify-between
                                           gap-5"
                                >


                                    
                                    <div
                                        class="flex items-start
                                               gap-4
                                               min-w-0"
                                    >


                                        
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


                                        
                                        <div class="min-w-0">

                                            <h3
                                                class="text-base sm:text-lg
                                                       font-bold
                                                       text-[#0e243a]
                                                       truncate"
                                            >

                                                <?php echo e($app['event_name'] ?? 'Unknown Event'); ?>


                                            </h3>


                                            
                                            <div
                                                class="flex flex-wrap
                                                       items-center
                                                       gap-x-4
                                                       gap-y-2
                                                       mt-2
                                                       text-xs sm:text-sm
                                                       text-gray-500"
                                            >


                                                
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


                                                    <?php if(!empty($app['date'])): ?>

                                                        <?php echo e(\Carbon\Carbon::parse($app['date'])->format('F d, Y')); ?>


                                                    <?php else: ?>

                                                        No date

                                                    <?php endif; ?>

                                                </span>


                                                
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


                                                    <?php echo e($app['ngo_name'] ?? 'Unknown NGO'); ?>


                                                </span>

                                            </div>

                                        </div>

                                    </div>


                                    
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


                                        
                                        <?php if($status === '1'): ?>

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

                                        <?php elseif($status === '0'): ?>

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

                                        <?php else: ?>

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

                                        <?php endif; ?>


                                        
                                        <button
    type="button"
    onclick="openModal(<?php echo \Illuminate\Support\Js::from($app)->toHtml() ?>)"
    class="px-5 py-2.5
           rounded-xl
           bg-[#d4a017]
           text-white
           font-semibold
           text-xs sm:text-sm
           hover:bg-[#c18f12]
           transition
           shadow-sm
           hover:shadow"
>
    View Details
</button>

                                    </div>

                                </div>

                            </div>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>


                            
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

                        <?php endif; ?>


                        
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



<?php echo $__env->make('components.logout-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>


<div id="viewModal"
     class="fixed inset-0 hidden items-center justify-center bg-black/40 z-[9999] p-4">

    <div id="viewModalContent"
         class="relative bg-white w-full max-w-[750px] rounded-2xl overflow-hidden shadow-xl p-6 transform transition-all duration-200 scale-95 max-h-[90vh] overflow-y-auto">

        
        <button
            type="button"
            onclick="closeModal()"
            class="absolute top-4 right-4 text-gray-500 hover:text-gray-800 text-4xl font-bold leading-none transition">
            &times;
        </button>

        
        <div class="flex justify-center mb-2">
            <img
                src="<?php echo e(asset('images/suhayLogo.png')); ?>"
                class="w-[160px] mx-auto mb-2"
                alt="Suhay Logo">
        </div>

        
        <h2 class="text-xl text-center mb-4">
            Volunteer Information
        </h2>

        <table class="w-full border text-sm mb-4 table-fixed">

            <tr>
                <td class="bg-[#0e243a] text-white p-2 w-1/4">
                    First Name
                </td>
                <td class="border p-2" id="modalFirstName">---</td>
            </tr>

            <tr>
                <td class="bg-[#0e243a] text-white p-2">
                    Last Name
                </td>
                <td class="border p-2" id="modalLastName">---</td>
            </tr>

            <tr>
                <td class="bg-[#0e243a] text-white p-2">
                    Skills
                </td>
                <td class="border p-2" id="modalSkills">---</td>
            </tr>

            <tr>
                <td class="bg-[#0e243a] text-white p-2">
                    Address
                </td>
                <td class="border p-2" id="modalAddress">---</td>
            </tr>

            <tr>
                <td class="bg-[#0e243a] text-white p-2">
                    Contact Number
                </td>
                <td class="border p-2" id="modalContact">---</td>
            </tr>

            <tr>
                <td class="bg-[#0e243a] text-white p-2">
                    Email Address
                </td>
                <td class="border p-2" id="modalEmail">---</td>
            </tr>

            <tr>
                <td class="bg-[#0e243a] text-white p-2">
                    Date of Birth
                </td>
                <td class="border p-2" id="modalDob">---</td>
            </tr>

            <tr>
                <td class="bg-[#0e243a] text-white p-2">
                    Event
                </td>
                <td class="border p-2" id="modalInterests">---</td>
            </tr>

            <tr>
                <td class="bg-[#0e243a] text-white p-2">
                    Event Date
                </td>
                <td class="border p-2" id="modalAvailability">---</td>
            </tr>

            <tr>
                <td class="bg-[#0e243a] text-white p-2">
                    Application Date
                </td>
                <td class="border p-2" id="modalDate">---</td>
            </tr>

            <tr>
                <td class="bg-[#0e243a] text-white p-2">
                    Status
                </td>
                <td class="border p-2" id="modalStatus">---</td>
            </tr>

            <tr>
                <td class="bg-[#0e243a] text-white p-2">
                    Remarks
                </td>
                <td class="border p-2 whitespace-pre-line" id="modalRemarks">---</td>
            </tr>

        </table>

        
        <div class="flex justify-end">
            <button
                type="button"
                onclick="closeModal()"
                class="bg-[#0e243a] hover:bg-[#162f4a] text-white px-6 py-2 rounded-full transition">
                Close
            </button>
        </div>

    </div>
</div>


</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // =====================================================
    // ELEMENTS
    // =====================================================
    const searchInput = document.getElementById('searchInput');
    const filterType = document.getElementById('filterType');
    const applicationList = document.getElementById('applicationList');
    const noResults = document.getElementById('noResults');

    const viewModal = document.getElementById('viewModal');
    const viewModalContent = document.getElementById('viewModalContent');

    // =====================================================
    // SEARCH AND CURRENT / PAST FILTER
    // =====================================================
    function filterApplications() {
        if (!applicationList) return;

        const search = (searchInput?.value || '').trim().toLowerCase();
        const selectedFilter = (filterType?.value || '').toLowerCase();

        const cards = applicationList.querySelectorAll('.application-card');
        const today = new Date();
        today.setHours(0, 0, 0, 0);

        let visibleCount = 0;

        cards.forEach(function (card) {
            const eventName = (card.dataset.name || '').toLowerCase();
            const ngoName = (card.dataset.ngo || '').toLowerCase();
            const cardText = (card.textContent || '').toLowerCase();

            const matchesSearch =
                eventName.includes(search) ||
                ngoName.includes(search) ||
                cardText.includes(search);

            // Use the actual event date, not the application status,
            // to determine whether an event is current or past.
            const dateValue = card.dataset.eventDate || '';
            let matchesDate = true;

            if (selectedFilter === 'current' || selectedFilter === 'past') {
                if (dateValue) {
                    const eventDate = new Date(dateValue + 'T00:00:00');

                    if (!Number.isNaN(eventDate.getTime())) {
                        matchesDate = selectedFilter === 'current'
                            ? eventDate >= today
                            : eventDate < today;
                    }
                } else {
                    matchesDate = false;
                }
            }

            const visible = matchesSearch && matchesDate;

            card.classList.toggle('hidden', !visible);

            if (visible) visibleCount++;
        });

        if (noResults) {
            noResults.classList.toggle(
                'hidden',
                cards.length === 0 || visibleCount > 0
            );
        }
    }

    searchInput?.addEventListener('input', filterApplications);
    filterType?.addEventListener('change', filterApplications);

    // =====================================================
    // APPLICATION DETAILS MODAL
    // Uses your existing modalFirstName-style IDs.
    // =====================================================
    window.openModal = function (app) {
        if (!app || typeof app !== 'object') {
            console.error('Application data was not provided.');
            return;
        }

        if (!viewModal) {
            console.error('The #viewModal element was not found.');
            return;
        }


const fields = {
    modalFirstName: app.first_name,
    modalLastName: app.last_name,
    modalSkills: app.skills,
    modalAddress: app.address,
    modalContact: app.contact_number,
    modalEmail: app.email,
    modalDob: app.birth_date,
    modalInterests: app.event_name,
    modalAvailability: app.event_date,
    modalDate: app.application_date,
    modalStatus: ({
        0: 'Pending',
        1: 'Approved',
        2: 'Rejected',
        3: 'Archived'
    })[Number(app.status)] ?? 'Unknown',
    modalRemarks: app.remarks
};

Object.entries(fields).forEach(([id, value]) => {
    const element = document.getElementById(id);

    if (element) {
        element.textContent =
            value !== null && value !== undefined && value !== ''
                ? value
                : 'Not provided';
    }
});


        viewModal.classList.remove('hidden');
        viewModal.classList.add('flex');
        document.body.style.overflow = 'hidden';

        if (viewModalContent) {
            viewModalContent.classList.remove('modal-exit');
            viewModalContent.classList.add('modal-enter');
        }
    };

    // =====================================================
    // CLOSE APPLICATION MODAL
    // =====================================================
    window.closeModal = function () {
        if (!viewModal || viewModal.classList.contains('hidden')) return;

        if (viewModalContent) {
            viewModalContent.classList.remove('modal-enter');
            viewModalContent.classList.add('modal-exit');
        }

        window.setTimeout(function () {
            viewModal.classList.add('hidden');
            viewModal.classList.remove('flex');

            if (viewModalContent) {
                viewModalContent.classList.remove('modal-exit');
            }

            document.body.style.overflow = '';
        }, 200);
    };

    // Close by clicking the backdrop.
    viewModal?.addEventListener('click', function (event) {
        if (event.target === viewModal) {
            window.closeModal();
        }
    });

    // =====================================================
    // ESCAPE KEY
    // =====================================================
    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            window.closeModal();
            window.closeLogoutModal();
        }
    });

    // =====================================================
    // HIGHLIGHT APPLICATION
    // ?highlight=APPLICATION_ID
    // =====================================================
    const highlightId = new URLSearchParams(
        window.location.search
    ).get('highlight');

    if (highlightId && applicationList) {
        const target = Array.from(
            applicationList.querySelectorAll('.application-card')
        ).find(function (card) {
            return card.dataset.applicationId === highlightId;
        });

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

    // =====================================================
    // LOGOUT MODAL
    // =====================================================
    window.openLogoutModal = function () {
        const modal = document.getElementById('logoutModal');

        if (!modal) return;

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    };

    window.closeLogoutModal = function () {
        const modal = document.getElementById('logoutModal');

        if (!modal) return;

        modal.classList.add('hidden');
        modal.classList.remove('flex');
    };

    const logoutModal = document.getElementById('logoutModal');

    logoutModal?.addEventListener('click', function (event) {
        if (event.target === logoutModal) {
            window.closeLogoutModal();
        }
    });
});
</script>
<?php /**PATH C:\sysands\MEt.A-Project-SUHAY\resources\views/Volunteers/applications.blade.php ENDPATH**/ ?>