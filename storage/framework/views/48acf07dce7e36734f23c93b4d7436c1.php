<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="csrf-token"
        content="<?php echo e(csrf_token()); ?>"
    >

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>SUHAY - Dashboard</title>


    

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


    
    
    

    <?php echo $__env->make('components.nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>


    
    
    

    <div class="flex-1 p-8">


        
        
        

        <?php echo $__env->make('components.header', [
            'title' => 'Dashboard'
        ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>


        
        
        

      <div class="bg-[#0e243a] text-white rounded-2xl p-6 mb-6">

    <p class="text-sm text-gray-300">
        Welcome back,
        <?php echo e($userName); ?>!
    </p>

    <h2 class="text-2xl font-bold mt-1">
        <?php echo e($ngoName); ?>

    </h2>

    <p class="text-sm text-gray-300 mt-2">
        Here's an overview of your organization's current activities.
    </p>

</div>



        
        
        

        <div
            class="grid grid-cols-1
                   sm:grid-cols-2
                   lg:grid-cols-4
                   gap-5 mb-6"
        >


            
            
            

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

                    ₱<?php echo e(number_format($monthlyDonations ?? 0, 2)); ?>


                </h2>


                <p class="text-xs text-gray-400 mt-1">
                    This Month
                </p>

            </div>



            
            
            

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

                    <?php echo e($inventoryItems ?? 0); ?>


                </h2>


                <p class="text-xs text-red-500 mt-1">

                    <?php echo e($lowStockItems ?? 0); ?>


                    items low in stock

                </p>

            </div>



            
            
            

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

                    <?php echo e($activeVolunteers ?? 0); ?>


                </h2>


                <p class="text-xs text-gray-400 mt-1">
                    Current volunteers
                </p>

            </div>



            
            
            

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

                    <?php echo e($pendingApplications ?? 0); ?>


                </h2>


                <p class="text-xs text-orange-500 mt-1">
                    Requires review
                </p>

            </div>


        </div>



        
        
        

        <div
            class="grid grid-cols-1
                   lg:grid-cols-2
                   gap-6 mb-6"
        >


            
            
            

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


                <?php

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

                ?>



                

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
                            <?php echo e($pendingApplications ?? 0); ?>

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
                                <?php echo e($pendingPercentage); ?>%
                            "
                        ></div>

                    </div>

                </div>



                

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
                            <?php echo e($approvedApplications ?? 0); ?>

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
                                <?php echo e($approvedPercentage); ?>%
                            "
                        ></div>

                    </div>

                </div>



                

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
                            <?php echo e($rejectedApplications ?? 0); ?>

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
                                <?php echo e($rejectedPercentage); ?>%
                            "
                        ></div>

                    </div>

                </div>

            </div>



            
            
            

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



                

                <?php $__empty_1 = true; $__currentLoopData = $upcomingEvents ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                    <div class="flex gap-4 mb-5">

                        

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

                                <?php echo e(\Carbon\Carbon::parse($event->date)->format('M')); ?>


                            </span>


                            <span class="text-lg font-bold">

                                <?php echo e(\Carbon\Carbon::parse($event->date)->format('d')); ?>


                            </span>

                        </div>



                        

                        <div class="min-w-0">

                            <h3
                                class="font-semibold
                                       text-[#0e243a]"
                            >

                                <?php echo e($event->name); ?>


                            </h3>


                            <p
                                class="text-xs
                                       text-gray-500"
                            >

                                <?php echo e($event->volunteer_count ?? 0); ?>


                                volunteer(s) assigned

                            </p>

                        </div>

                    </div>


                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                    <div class="py-5 text-center">

                        <p class="text-sm text-gray-400">

                            No upcoming events.

                        </p>

                    </div>

                <?php endif; ?>


            </div>


        </div>



        
        
        

        <div
            class="grid grid-cols-1
                   lg:grid-cols-2
                   gap-6 mb-6"
        >


            
            
            

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



                

                <?php $__empty_1 = true; $__currentLoopData = $inventoryAlerts ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

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

                                <?php echo e($item->name); ?>


                            </p>


                            <p
                                class="text-xs
                                       text-gray-500"
                            >

                                <?php echo e($item->current_quantity); ?>


                                <?php echo e($item->unit); ?>


                                remaining

                            </p>


                            <p
                                class="text-[11px]
                                       text-gray-400 mt-1"
                            >

                                Minimum:

                                <?php echo e($item->minimum_threshold); ?>


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


                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                    <div class="py-5 text-center">

                        <p class="text-sm text-gray-400">

                            No inventory alerts.

                        </p>

                    </div>

                <?php endif; ?>


            </div>



            
            
            

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



                

                <?php $__empty_1 = true; $__currentLoopData = $recentActivities ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                    <div class="flex gap-4 mb-5">

                        

                        <div
                            class="
                                w-9 h-9
                                rounded-full
                                flex items-center
                                justify-center
                                flex-shrink-0

                                <?php if($activity->title === 'volunteer'): ?>
                                    bg-blue-100
                                <?php elseif($activity->title === 'donation'): ?>
                                    bg-green-100
                                <?php else: ?>
                                    bg-yellow-100
                                <?php endif; ?>
                            "
                        >

                            <?php if($activity->title === 'volunteer'): ?>

                                <span
                                    class="text-blue-600"
                                >
                                    V
                                </span>

                            <?php elseif($activity->title === 'donation'): ?>

                                <span
                                    class="text-green-600"
                                >
                                    ₱
                                </span>

                            <?php else: ?>

                                <span
                                    class="text-yellow-600"
                                >
                                    I
                                </span>

                            <?php endif; ?>

                        </div>



                        

                        <div>

                            <p class="text-sm text-[#0e243a]">
                                <strong>
                                    <?php echo e($activity->title); ?>

                                </strong>

                                <?php echo e($activity->description); ?>

                            </p>

                         <p class="text-xs text-gray-400">
                            <?php echo e($activity->date
                                ? \Carbon\Carbon::parse($activity->date, 'UTC')
                                    ->setTimezone('Asia/Manila')
                                    ->diffForHumans()
                                : 'No date'); ?>

                        </p>

                        </div>

                    </div>


                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                    <div class="py-5 text-center">

                        <p class="text-sm text-gray-400">

                            No recent activity.

                        </p>

                    </div>

                <?php endif; ?>


            </div>


        </div>


    </div>


</div>

<?php echo $__env->make('components.logout-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

</body>

</html><?php /**PATH C:\Sysands\MEt.A-Project-SUHAY\resources\views/dashboard.blade.php ENDPATH**/ ?>