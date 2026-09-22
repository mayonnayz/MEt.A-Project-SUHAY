
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Donations | Volunteer</title>

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        .donation-card {
            transition: all 0.2s ease;
        }

        .donation-card:hover {
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

        .status-badge {
            letter-spacing: 0.02em;
        }
    </style>
</head>


<body class="bg-[#eef1f5] text-[#0e243a]">

<div class="flex min-h-screen">

    
    <?php echo $__env->make('components.nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>


    
    <div class="flex-1 min-w-0 p-5 sm:p-6 lg:p-8">

        
        <?php echo $__env->make('components.header', ['title' => 'Donations'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>


        
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
                           lg:items-center lg:justify-between
                           gap-4"
                >

                    <div>

                        <h2 class="font-bold text-lg text-[#0e243a]">
                            Your Donations
                        </h2>

                        <p class="text-xs text-gray-500 mt-1">
                            Search or filter your donation records.
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
                                   flex items-center justify-center"
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
                                    d="M20 7H4a2 2 0 00-2 2v8a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2z"
                                />

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"
                                />

                            </svg>

                        </div>


                        <div>

                            <p class="text-[11px] text-gray-500 font-medium">
                                Total Records
                            </p>

                            <p class="text-lg font-bold text-[#0e243a]">
                                <?php echo e(count($donations)); ?>

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
                                placeholder="Search by NGO, type, description, source..."
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
                                    All Donation Types
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

                    </div>

                </div>


                
                <div class="p-5 sm:p-7">

                    <div
                        id="donationList"
                        class="space-y-3"
                    >

                        <?php $__empty_1 = true; $__currentLoopData = $donations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $donation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                            <?php

                                $type = strtoupper(
                                    trim($donation['type'] ?? '')
                                );

                                $displayType = match($type) {

                                    'MONETARY'
                                        => 'Monetary',

                                    'ONLINE_MONETARY'
                                        => 'Online Monetary',

                                    'CONSUMABLE'
                                        => 'Consumable',

                                    'REUSABLE'
                                        => 'Reusable',

                                    default
                                        => $donation['type']
                                        ?? 'Unknown Type'

                                };

                                $status = strtoupper(
                                    trim($donation['status'] ?? 'UNKNOWN')
                                );

                                $displayStatus =
                                    str_replace('_', ' ', $status);

                            ?>


                            
                            <div
                                class="donation-card
                                       rounded-2xl
                                       border border-gray-200
                                       bg-white
                                       p-5
                                       hover:border-gray-300
                                       hover:shadow-md"

                                data-name="<?php echo e(strtolower($donation['ngo_name'] ?? '')); ?>"

                                data-description="<?php echo e(strtolower($donation['description'] ?? '')); ?>"

                                data-source="<?php echo e(strtolower($donation['source'] ?? '')); ?>"

                                data-type="<?php echo e(strtolower($type)); ?>"

                                data-reference="<?php echo e(strtolower($donation['reference_no'] ?? '')); ?>"
                            >

                                <div
                                    class="flex flex-col
                                           lg:flex-row
                                           lg:items-center
                                           lg:justify-between
                                           gap-5"
                                >


                                    
                                    <div class="flex items-start gap-4 min-w-0">


                                        
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
                                                    d="M12 21c4.97-4.97 8-8.37 8-12a8 8 0 10-16 0c0 3.63 3.03 7.03 8 12z"
                                                />

                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="M9 10h6M9 13h4"
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
                                                <?php echo e($donation['ngo_name'] ?? 'Unknown NGO'); ?>

                                            </h3>


                                            
                                            <div
                                                class="flex flex-wrap
                                                       items-center
                                                       gap-x-4 gap-y-2
                                                       mt-2
                                                       text-xs sm:text-sm
                                                       text-gray-500"
                                            >

                                                
                                                <span class="flex items-center gap-1.5">

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


                                                    <?php if(!empty($donation['date'])): ?>

                                                        <?php echo e(\Carbon\Carbon::parse($donation['date'])->format('F d, Y')); ?>


                                                    <?php else: ?>

                                                        No date

                                                    <?php endif; ?>

                                                </span>


                                                
                                                <span class="flex items-center gap-1.5">

                                                    <span
                                                        class="w-1.5 h-1.5
                                                               rounded-full
                                                               bg-[#d4a017]"
                                                    ></span>

                                                    <?php echo e($displayType); ?>


                                                </span>

                                            </div>


                                            
                                            <?php if(!empty($donation['description'])): ?>

                                                <p
                                                    class="mt-2
                                                           text-xs sm:text-sm
                                                           text-gray-500
                                                           line-clamp-1"
                                                >
                                                    <?php echo e($donation['description']); ?>

                                                </p>

                                            <?php endif; ?>

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


                                        
                                        <?php if($status === 'CONFIRMED'): ?>

                                            <span
                                                class="status-badge
                                                       px-3 py-1.5
                                                       rounded-full
                                                       bg-green-50
                                                       text-green-700
                                                       border border-green-200
                                                       text-[11px]
                                                       font-bold"
                                            >
                                                CONFIRMED
                                            </span>

                                        <?php elseif($status === 'AVAILABLE'): ?>

                                            <span
                                                class="status-badge
                                                       px-3 py-1.5
                                                       rounded-full
                                                       bg-blue-50
                                                       text-blue-700
                                                       border border-blue-200
                                                       text-[11px]
                                                       font-bold"
                                            >
                                                AVAILABLE
                                            </span>

                                        <?php elseif($status === 'PENDING'): ?>

                                            <span
                                                class="status-badge
                                                       px-3 py-1.5
                                                       rounded-full
                                                       bg-yellow-50
                                                       text-yellow-700
                                                       border border-yellow-200
                                                       text-[11px]
                                                       font-bold"
                                            >
                                                PENDING
                                            </span>

                                        <?php elseif($status === 'NOT_AVAILABLE'): ?>

                                            <span
                                                class="status-badge
                                                       px-3 py-1.5
                                                       rounded-full
                                                       bg-red-50
                                                       text-red-700
                                                       border border-red-200
                                                       text-[11px]
                                                       font-bold"
                                            >
                                                NOT AVAILABLE
                                            </span>

                                        <?php elseif($status === 'DUPLICATED'): ?>

                                            <span
                                                class="status-badge
                                                       px-3 py-1.5
                                                       rounded-full
                                                       bg-gray-100
                                                       text-gray-600
                                                       border border-gray-200
                                                       text-[11px]
                                                       font-bold"
                                            >
                                                DUPLICATED
                                            </span>

                                        <?php else: ?>

                                            <span
                                                class="status-badge
                                                       px-3 py-1.5
                                                       rounded-full
                                                       bg-gray-100
                                                       text-gray-600
                                                       border border-gray-200
                                                       text-[11px]
                                                       font-bold"
                                            >
                                                <?php echo e($displayStatus); ?>

                                            </span>

                                        <?php endif; ?>


                                        
                                        <button
                                            type="button"
                                            class="view-donation-btn
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

                                            data-ngo="<?php echo e($donation['ngo_name'] ?? 'Unknown NGO'); ?>"

                                            data-type="<?php echo e($type); ?>"

                                            data-description="<?php echo e($donation['description'] ?? 'N/A'); ?>"

                                            data-amount="<?php echo e($donation['amount'] ?? ''); ?>"

                                            data-unit="<?php echo e($donation['unit'] ?? ''); ?>"

                                            data-source="<?php echo e($donation['source'] ?? 'N/A'); ?>"

                                            data-reference="<?php echo e($donation['reference_no'] ?? 'N/A'); ?>"

                                            data-date="<?php echo e($donation['date'] ?? ''); ?>"

                                            data-status="<?php echo e($status); ?>"
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
                                            d="M20 7H4a2 2 0 00-2 2v8a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2z"
                                        />

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"
                                        />

                                    </svg>

                                </div>


                                <h3
                                    class="font-semibold
                                           text-gray-700"
                                >
                                    No donation history
                                </h3>


                                <p class="text-sm text-gray-400 mt-1">
                                    Your donation records will appear here.
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
                                No donations found
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



<div
    id="donationModalBackdrop"
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
        id="donationModalContent"
        class="bg-white
               rounded-3xl
               shadow-2xl
               w-full
               max-w-lg
               max-h-[90vh]
               overflow-y-auto"
    >

        
        <div
            class="relative
                   px-6 py-6
                   border-b border-gray-200
                   text-center"
        >

            <button
                type="button"
                onclick="closeDonationModal()"
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
                        d="M12 21c4.97-4.97 8-8.37 8-12a8 8 0 10-16 0c0 3.63 3.03 7.03 8 12z"
                    />

                </svg>

            </div>


            <h2 class="font-bold text-xl text-[#0e243a]">
                Donation Details
            </h2>


            <p class="text-xs text-gray-500 mt-1">
                Complete donation information
            </p>

        </div>


        
        <div
            id="donationModalBody"
            class="p-6 space-y-4"
        >
            <!-- Dynamic content -->
        </div>


        
        <div
            class="px-6 pb-6"
        >

            <button
                type="button"
                onclick="closeDonationModal()"
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



<?php echo $__env->make('components.logout-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>



<script src="<?php echo e(asset('js/donation-history.js')); ?>"></script>

</body>
</html>

<?php /**PATH C:\sysands\MEt.A-Project-SUHAY\resources\views/Volunteers/donation_history.blade.php ENDPATH**/ ?>