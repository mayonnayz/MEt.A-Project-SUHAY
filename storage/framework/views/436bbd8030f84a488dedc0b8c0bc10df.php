<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>SUHAY - Volunteer Management</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap"
          rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-200">

<div class="flex">

    
     <?php echo $__env->make('components.nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="flex-1 p-8">

        
        <?php echo $__env->make('components.header', ['title' => 'Volunteer Management'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>


        
        
        

        <div class="bg-[#0e243a] p-4 rounded-2xl flex gap-4 mb-6 flex-wrap">

            <a href="/service-management"
               class="bg-gray-200 text-[#0e243a] px-6 py-2 rounded-full font-semibold">
                Volunteer Lists
            </a>

            <a href="/applications"
               class="bg-[#f2c94c] text-[#0e243a] px-6 py-2 rounded-full font-semibold">
                Applications
            </a>

            <a href="/assignments"
               class="bg-[#f2c94c] text-[#0e243a] px-6 py-2 rounded-full font-semibold">
                Assignments
            </a>

            <a href="/events"
               class="bg-[#f2c94c] text-[#0e243a] px-6 py-2 rounded-full font-semibold">
                Events
            </a>

            <a href="/track-activity"
               class="bg-[#f2c94c] text-[#0e243a] px-6 py-2 rounded-full font-semibold">
                Track Activity
            </a>

        </div>


        
        
        

        <div class="bg-gray-300 rounded-xl p-6 mb-6">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div>
                    <p class="font-semibold text-gray-700">
                        Total Volunteers:
                    </p>

                    <p class="text-2xl font-bold text-[#0e243a] mt-1">
                        <?php echo e($volunteers->count()); ?>

                    </p>
                </div>

                <div>
                    <p class="font-semibold text-gray-700">
                        Active Volunteers:
                    </p>

                    <p class="text-2xl font-bold text-[#0e243a] mt-1">
                        <?php echo e($volunteers->count()); ?>

                    </p>
                </div>

            </div>

        </div>


        
        
        

        <div class="flex justify-center mb-4">

            <form method="GET"
                  action="/service-management"
                  class="flex w-full md:w-1/3">

                <input
                    type="text"
                    id="searchInput"
                    name="search"
                    value="<?php echo e(request('search')); ?>"
                    placeholder="Search Volunteer...."
                    class="w-full p-2 border rounded-l-md focus:outline-none focus:ring-2 focus:ring-[#0e243a]"
                >

                <button
                    type="submit"
                    class="bg-white px-4 py-2 rounded-r-md flex items-center justify-center">

                    <img
                        src="/images/searchbar.png"
                        class="w-9 h-8"
                        alt="Search"
                    >

                </button>

            </form>

        </div>


        
        
        

        <div class="flex items-center justify-center gap-4 mb-6 flex-wrap">

            <p class="font-semibold text-gray-700">
                Filter:
            </p>

            <select
                id="skillFilter"
                class="border p-2 rounded-md">

                <option value="">
                    All Skills
                </option>

                <?php $__currentLoopData = $skills; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $skill): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                    <option
                        value="<?php echo e(strtolower($skill)); ?>"
                        <?php echo e(strtolower(request('search_skill')) == strtolower($skill) ? 'selected' : ''); ?>>

                        <?php echo e($skill); ?>


                    </option>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            </select>

        </div>


        
        
        

        <div
            id="volunteerGrid"
            class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">

            <?php $__empty_1 = true; $__currentLoopData = $volunteers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $volunteer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                <div
                    class="volunteer-card bg-[#4a5568] text-white rounded-2xl p-6 text-center
                           hover:scale-105 hover:shadow-xl transition-all duration-300">


                    
                    <div
                        class="w-20 h-20 bg-white rounded-full mx-auto mb-3 flex items-center justify-center">

                        <svg
                            class="w-10 h-10 text-gray-400"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0z
                                   M12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                            </path>

                        </svg>

                    </div>


                    
                    <h3 class="font-bold text-lg">

                        <?php echo e($volunteer->first_name); ?>

                        <?php echo e($volunteer->last_name); ?>


                    </h3>


                    
                    <div
                        class="text-xs mt-4 text-left space-y-2
                               bg-[#3b4252] p-3 rounded-lg">

                        <p>
                            <span class="font-semibold">
                                Email:
                            </span>

                            <?php echo e($volunteer->email ?: 'N/A'); ?>

                        </p>

                        <p>
                            <span class="font-semibold">
                                Phone:
                            </span>

                            <?php echo e($volunteer->contact_number ?: 'N/A'); ?>

                        </p>

                       

                    </div>


                    
                    <div class="flex justify-between mt-4 gap-2">

                        
                        <button
                            type="button"
                            onclick='openModal(<?php echo json_encode($volunteer, 15, 512) ?>)'
                            class="bg-blue-500 hover:bg-blue-600 text-white
                                   px-4 py-2 rounded-full text-xs font-semibold">

                            View

                        </button>


                        
                        <button
                            type="button"
                            onclick="deactivateVolunteer(<?php echo e($volunteer->account_id); ?>)"
                            class="bg-red-500 hover:bg-red-600 text-white
                                   px-4 py-2 rounded-full text-xs font-semibold">

                            Deactivate

                        </button>

                    </div>

                </div>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                <div class="col-span-full text-center py-12">

                    <p class="text-gray-600 text-lg font-semibold">
                        No volunteers found.
                    </p>

                </div>

            <?php endif; ?>

        </div>

    </div>

</div>



<?php echo $__env->make('components.application-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('components.logout-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<script src="<?php echo e(asset('js/volunteer-management.js')); ?>"></script>



</body>
</html><?php /**PATH C:\sysands\MEt.A-Project-SUHAY-main\MEt.A-Project-SUHAY-main\resources\views/service_management.blade.php ENDPATH**/ ?>