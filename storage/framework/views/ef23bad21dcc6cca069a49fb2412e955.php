<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>NGOs | Volunteer</title>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        /* =====================================================
           MODAL ANIMATIONS
        ====================================================== */

        .modal-enter {
            animation: zoomFadeIn 0.3s ease forwards;
        }

        .modal-exit {
            animation: zoomFadeOut 0.25s ease forwards;
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

        /* =====================================================
           NGO CARD
        ====================================================== */

        .ngo-card {
            transition: all 0.2s ease;
        }

        .ngo-card:hover {
            transform: translateY(-2px);
        }
    </style>
</head>

<body class="bg-gray-200">

<div class="flex">

    <?php echo $__env->make('components.nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="flex-1 p-6 md:p-8">

        <?php echo $__env->make('components.header', ['title' => 'NGOs'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <div class="bg-[#f5f5f5] rounded-[22px]
                    border-[10px] border-[#0e243a]
                    p-6 sm:p-8">

            <div class="mb-7">

                <div class="flex items-center
                            bg-white
                            border-2 border-[#0e243a]
                            rounded-full
                            px-5 py-2.5
                            shadow-sm
                            focus-within:ring-2
                            focus-within:ring-[#d39a11]/40">

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5 text-[#0e243a]
                               mr-3 flex-shrink-0"
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

                    <input
                        type="text"
                        id="searchInput"
                        placeholder="Search NGOs, location, or contact number..."
                        class="w-full outline-none
                               bg-transparent
                               text-sm font-medium
                               text-[#0e243a]
                               placeholder:text-gray-400"
                    >

                </div>

            </div>

            <div id="ngoList" class="space-y-4">


                <?php $__empty_1 = true; $__currentLoopData = $ngos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ngo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                    <div
                        class="ngo-card
                               rounded-2xl
                               border-2 border-[#0e243a]
                               bg-white
                               p-5 sm:p-6
                               shadow-sm
                               hover:shadow-md"
                        data-name="<?php echo e(strtolower($ngo['name'] ?? '')); ?>"
                        data-address="<?php echo e(strtolower($ngo['address'] ?? '')); ?>"
                        data-contact="<?php echo e(strtolower($ngo['contact_number'] ?? '')); ?>"
                    >

                        <div class="flex flex-col
                                    md:flex-row
                                    md:items-center
                                    md:justify-between
                                    gap-5">

                            <div class="flex items-center gap-4 min-w-0">


                                
                                <div class="w-16 h-16
                                            sm:w-20 sm:h-20
                                            rounded-2xl
                                            bg-gray-50
                                            border border-gray-200
                                            flex items-center
                                            justify-center
                                            flex-shrink-0
                                            overflow-hidden">

                                    <img
                                        src="<?php echo e($ngo['logo'] ?? asset('images/suhayLogo.png')); ?>"
                                        alt="<?php echo e($ngo['name'] ?? 'NGO Logo'); ?>"
                                        class="w-full h-full
                                               object-contain p-2"
                                    >

                                </div>


                                
                                <div class="min-w-0">

                                    <h3 class="text-lg sm:text-xl
                                               font-bold
                                               text-[#0e243a]
                                               truncate">

                                        <?php echo e($ngo['name'] ?? 'NGO Name'); ?>


                                    </h3>


                                    <div class="space-y-1.5 mt-2">


                                        
                                        <div class="flex items-center
                                                    gap-2
                                                    text-sm
                                                    text-gray-600">

                                            <img
                                                src="<?php echo e(asset('images/VolunteerIcons/VPhone.png')); ?>"
                                                class="w-4 h-4 object-contain
                                                       flex-shrink-0"
                                                alt="Contact"
                                            >

                                            <span class="truncate">
                                                <?php echo e($ngo['contact_number'] ?? 'N/A'); ?>

                                            </span>

                                        </div>


                                        
                                        <div class="flex items-start
                                                    gap-2
                                                    text-sm
                                                    text-gray-600">

                                            <img
                                                src="<?php echo e(asset('images/VolunteerIcons/VLocation.png')); ?>"
                                                class="w-4 h-4 object-contain
                                                       flex-shrink-0 mt-0.5"
                                                alt="Location"
                                            >

                                            <span>
                                                <?php echo e($ngo['address'] ?? 'N/A'); ?>

                                            </span>

                                        </div>

                                    </div>

                                </div>

                            </div>


                            <button
                                type="button"
                                onclick="openModal(<?php echo e($ngo['id']); ?>)"
                                class="px-6 py-2.5
                                       rounded-full
                                       bg-[#d39a11]
                                       text-white
                                       font-semibold
                                       text-sm
                                       hover:bg-[#c2870d]
                                       transition
                                       whitespace-nowrap
                                       w-full md:w-auto"
                            >
                                View NGO Details
                            </button>

                        </div>

                    </div>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                    <div
                        class="bg-white
                               rounded-2xl
                               border-2 border-dashed
                               border-gray-300
                               p-10 text-center"
                    >

                        <div
                            class="w-14 h-14
                                   mx-auto mb-4
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

                                <path
                                    d="M3 21h18">
                                </path>

                                <path
                                    d="M5 21V5l7-3 7 3v16">
                                </path>

                                <path
                                    d="M9 9h1">
                                </path>

                                <path
                                    d="M14 9h1">
                                </path>

                                <path
                                    d="M9 13h1">
                                </path>

                                <path
                                    d="M14 13h1">
                                </path>

                            </svg>

                        </div>

                        <h3 class="text-lg font-bold text-[#0e243a]">
                            No NGOs Available
                        </h3>

                        <p class="text-sm text-gray-500 mt-1">
                            There are currently no partner NGOs available.
                        </p>

                    </div>

                <?php endif; ?>
                <div
                    id="noResults"
                    class="hidden
                           bg-white
                           rounded-2xl
                           border-2 border-dashed
                           border-gray-300
                           p-10 text-center"
                >

                    <div
                        class="w-14 h-14
                               mx-auto mb-4
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

                    <h3 class="text-lg font-bold text-[#0e243a]">
                        No Matching NGOs
                    </h3>

                    <p class="text-sm text-gray-500 mt-1">
                        Try searching for another NGO, location,
                        or contact number.
                    </p>

                </div>

            </div>

        </div>

    </div>

</div>


<div
    id="ngoModal"
    class="fixed inset-0
           bg-black/60
           hidden
           items-center
           justify-center
           z-50
           p-4"
>

    <div
        id="modalContent"
        class="bg-white
               w-full
               max-w-3xl
               max-h-[90vh]
               overflow-y-auto
               rounded-3xl
               shadow-2xl"
    >
    </div>

</div>

<div
    id="donationModal"
    class="fixed inset-0
           bg-black/60
           hidden
           items-center
           justify-center
           z-50
           p-4"
>

    <div
        id="donationContent"
        class="bg-white
               w-full
               max-w-lg
               max-h-[90vh]
               overflow-y-auto
               rounded-3xl
               shadow-2xl
               relative"
    >

        
        <button
            type="button"
            onclick="closeDonationModal()"
            class="absolute top-4 right-5
                   w-9 h-9
                   rounded-full
                   bg-gray-100
                   text-gray-500
                   hover:bg-gray-200
                   hover:text-[#0e243a]
                   flex items-center
                   justify-center
                   text-xl
                   transition
                   z-10"
            aria-label="Close"
        >
            &times;
        </button>


        
        <div class="px-6 sm:px-8 pt-7 pb-5
                    border-b border-gray-100">

            <div class="flex items-center gap-4">

                <div
                    class="w-14 h-14
                           rounded-2xl
                           bg-[#fbfdff]
                           flex items-center
                           justify-center
                           flex-shrink-0"
                >

                    <img
                        src="<?php echo e(asset('images/suhayLogo.png')); ?>"
                        class="h-10 w-10 object-contain"
                        alt="SUHAY"
                    >

                </div>

                <div>

                    <p class="text-xs font-semibold
                              uppercase tracking-wider
                              text-[#d39a11]">
                        Support an Organization
                    </p>

                    <h2 class="text-xl font-bold
                               text-[#0e243a] mt-1">
                        Donation Form
                    </h2>

                </div>

            </div>

        </div>


        
        <div class="px-6 sm:px-8 py-6">


            
            <div class="mb-6">

                <label
                    class="block text-sm font-semibold
                           text-[#0e243a] mb-2"
                >
                    Donation Type
                </label>

               <select id="donationType"
        onchange="toggleDonationType()"
        class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm">

    <option value="" disabled selected>
        Select Donation Type
    </option>

    <option value="monetary">
        Monetary
    </option>

    <option value="online_monetary">
        Online Monetary
    </option>

    <option value="consumable">
        Consumable
    </option>

    <option value="reusable">
        Reusable
    </option>

</select>
            </div>

<div id="nonMonetaryForm">

    <div id="itemLabel" class="mb-3">

        <div class="flex items-center justify-between">

            <div>

                <h3 class="text-sm font-bold text-[#0e243a]">
                    Donation Items
                </h3>

                <p class="text-xs text-gray-400 mt-0.5">
                    Add the items you would like to donate.
                </p>

            </div>

        </div>

    </div>

    <div
        id="itemsContainer"
        class="space-y-3 mb-4"
    >
    </div>

    <button
        id="addItemBtn"
        type="button"
        onclick="addItem()"
        class="w-full
               border-2 border-dashed
               border-gray-300
               text-[#0e243a]
               px-4 py-2.5
               rounded-xl
               text-sm
               font-semibold
               hover:border-[#0e243a]
               hover:bg-gray-50
               transition"
    >
        + Add Another Item
    </button>

</div>

<div
    id="monetaryForm"
    class="hidden"
>

    <div>

        <label
            class="block text-sm font-semibold
                   text-[#0e243a] mb-2"
        >
            Amount
        </label>

        <div class="relative">

            <span
                class="absolute left-4 top-1/2
                       -translate-y-1/2
                       text-[#0e243a]
                       font-semibold"
            >
                ₱
            </span>

            <input
                id="donationAmount"
                type="number"
                min="1"
                step="0.01"
                placeholder="Enter amount"
                class="w-full
                       border-2 border-gray-200
                       rounded-xl
                       pl-10 pr-4 py-3
                       text-sm
                       text-[#0e243a]
                       outline-none
                       focus:border-[#0e243a]"
            >

        </div>

    </div>

</div>

<div
    id="onlineMonetaryForm"
    class="hidden"
>

    <div class="space-y-5">

        
        <div>

            <label
                class="block text-sm font-semibold
                       text-[#0e243a] mb-2"
            >
                Donation Channel
            </label>

            <select
                id="donationChannel"
                class="w-full
                       border-2 border-gray-200
                       rounded-xl
                       px-4 py-3
                       text-sm
                       outline-none
                       focus:border-[#0e243a]"
            >

                <option value="gcash">
                    GCash
                </option>

                <option value="bank">
                    Bank
                </option>

            </select>

        </div>

        
        <div>

            <label
                class="block text-sm font-semibold
                       text-[#0e243a] mb-2"
            >
                Amount
            </label>

            <div class="relative">

                <span
                    class="absolute left-4 top-1/2
                           -translate-y-1/2
                           text-[#0e243a]
                           font-semibold"
                >
                    ₱
                </span>

                <input
                    id="onlineDonationAmount"
                    type="number"
                    min="1"
                    step="0.01"
                    placeholder="Enter amount"
                    class="w-full
                           border-2 border-gray-200
                           rounded-xl
                           pl-10 pr-4 py-3
                           text-sm
                           text-[#0e243a]
                           outline-none
                           focus:border-[#0e243a]"
                >

            </div>

        </div>

        
        <div>

            <label
                class="block text-sm font-semibold
                       text-[#0e243a] mb-2"
            >
                Reference Number
            </label>

            <input
                id="referenceNumber"
                type="text"
                placeholder="Enter your reference number"
                class="w-full
                       border-2 border-gray-200
                       rounded-xl
                       px-4 py-3
                       text-sm
                       outline-none
                       focus:border-[#0e243a]"
            >

        </div>

    </div>

</div>

        </div>


        
        <div
            class="px-6 sm:px-8 py-5
                   border-t border-gray-100
                   flex gap-3"
        >

            <button
                type="button"
                onclick="goBackToNgoModal()"
                class="w-1/2
                       py-2.5
                       rounded-full
                       border-2 border-[#0e243a]
                       text-[#0e243a]
                       font-semibold
                       text-sm
                       hover:bg-gray-50
                       transition"
            >
                Back
            </button>

            <button
                type="button"
                onclick="openConfirmModal()"
                class="w-1/2
                       py-2.5
                       rounded-full
                       bg-[#d4a017]
                       text-white
                       font-semibold
                       text-sm
                       hover:bg-[#c29314]
                       transition"
            >
                Submit Donation
            </button>

        </div>

    </div>

</div>

<div
    id="confirmDonationModal"
    class="fixed inset-0
           bg-black/60
           hidden
           items-center
           justify-center
           z-50
           p-4"
>

    <div
        id="confirmContent"
        class="bg-white
               w-full
               max-w-md
               rounded-3xl
               shadow-2xl
               p-7
               text-center"
    >

        <div
            class="w-14 h-14
                   mx-auto mb-4
                   rounded-full
                   bg-yellow-100
                   flex items-center
                   justify-center"
        >

            <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-7 w-7 text-[#d39a11]"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
            >

                <path
                    d="M12 9v4">
                </path>

                <path
                    d="M12 17h.01">
                </path>

                <path
                    d="M10.3 3.5L2.7 17a2 2 0 001.7 3h15.2a2 2 0 001.7-3L13.7 3.5a2 2 0 00-3.4 0z">
                </path>

            </svg>

        </div>


        <h2 class="text-xl font-bold text-[#0e243a] mb-2">
            Confirm Donation
        </h2>

        <p class="text-sm text-gray-600 mb-3">
            Are you sure you want to proceed with your donation?
        </p>

        <p class="text-xs text-gray-400 leading-relaxed mb-7">
            Please make sure that all the information you entered
            is correct before continuing.
        </p>


        <div class="flex gap-3">

            <button
                type="button"
                onclick="closeConfirmModal()"
                class="w-1/2
                       py-2.5
                       rounded-full
                       border-2 border-[#0e243a]
                       text-[#0e243a]
                       font-semibold
                       text-sm
                       hover:bg-gray-50"
            >
                Cancel
            </button>

            <button
                type="button"
                onclick="startProcessing()"
                class="w-1/2
                       py-2.5
                       rounded-full
                       bg-[#4CAF50]
                       text-white
                       font-semibold
                       text-sm
                       hover:bg-[#43a047]"
            >
                Confirm
            </button>

        </div>

    </div>

</div>

<div
    id="loadingModal"
    class="fixed inset-0
           bg-black/60
           hidden
           items-center
           justify-center
           z-50
           p-4"
>

    <div
        id="loadingContent"
        class="bg-white
               rounded-3xl
               shadow-2xl
               p-8
               text-center
               w-full
               max-w-sm"
    >

        <div
            class="w-12 h-12
                   border-4
                   border-gray-200
                   border-t-[#d39a11]
                   rounded-full
                   animate-spin
                   mx-auto mb-5"
        ></div>

        <h2 class="text-lg font-bold text-[#0e243a] mb-2">
            Processing Your Donation
        </h2>

        <p class="text-sm text-gray-500">
            Please wait while we complete your request.
        </p>

    </div>

</div>

<div
    id="successModal"
    class="fixed inset-0
           bg-black/60
           hidden
           items-center
           justify-center
           z-50
           p-4"
>

    <div
        id="successContent"
        class="bg-white
               rounded-3xl
               shadow-2xl
               p-8
               text-center
               w-full
               max-w-sm"
    >

        <div
            class="w-16 h-16
                   mx-auto mb-4
                   rounded-full
                   bg-green-100
                   flex items-center
                   justify-center"
        >

            <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-8 w-8 text-green-600"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
            >

                <polyline
                    points="20 6 9 17 4 12">
                </polyline>

            </svg>

        </div>

        <h2 class="text-xl font-bold text-[#0e243a]">
            Donation Successful!
        </h2>

        <p class="text-sm text-gray-500 mt-2">
            Thank you for supporting our partner organization.
        </p>

        <button
            type="button"
            onclick="closeSuccessModal()"
            class="mt-6
                   px-8 py-2.5
                   rounded-full
                   bg-[#0e243a]
                   text-white
                   font-semibold
                   text-sm
                   hover:bg-[#183b5a]"
        >
            Close
        </button>

    </div>

</div>


<?php echo $__env->make('components.logout-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<script src="<?php echo e(asset('js/dovol-ngos.js')); ?>"></script>

</body>
</html><?php /**PATH C:\sysands\MEt.A-Project-SUHAY\resources\views/Volunteers/ngos.blade.php ENDPATH**/ ?>