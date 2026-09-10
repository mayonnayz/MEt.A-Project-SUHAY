<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <title>Assignments</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">

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
           class="bg-[#f2c94c] px-6 py-2 rounded-full font-semibold">
                Volunteer Lists
            </a>

            <a href="/applications"
            class="bg-[#f2c94c] px-6 py-2 rounded-full font-semibold">
                Applications
            </a>

            <a href="/assignments"
            class="bg-gray-200 px-6 py-2 rounded-full font-semibold">
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

        <div class="bg-[#0e243a] p-6 rounded-3xl">
           <div class="flex justify-between items-center mb-4 flex-wrap gap-3">

    <!-- LEFT: Event Status + Search -->
    <div class="flex items-center gap-2">

        <!-- Event Status Filter -->
        <select
            id="eventStatusFilter"
            onchange="filterEventStatus()"
            class="p-2 rounded-md border font-semibold text-[#0e243a] bg-white"
        >
            <option value="future" selected>Upcoming Events</option>
            <option value="done">Completed Events</option>
            <option value="all">All Events</option>
        </select>

        <!-- Search -->
        <div class="flex items-center">
            <input
                id="searchInput"
                type="text"
                placeholder="Search Programs..."
                class="p-2 rounded-l-md border outline-none focus:ring-2 focus:ring-[#0e243a]"
            >

            <button
                type="button"
                class="bg-[#0e243a] px-4 py-2 rounded-r-md hover:bg-[#0e243a] flex items-center justify-center"
            >
                <img
                    src="/images/searchbar.png"
                    class="w-8 h-8"
                    alt="Search"
                    onmouseover="this.src='/images/searchbar_hover.png'"
                    onmouseout="this.src='/images/searchbar.png'"
                >
            </button>
        </div>

    </div>

    <!-- RIGHT: Date + Clear -->
    <div class="flex items-center gap-3">

        <input
            id="dateFilter"
            class="p-2 rounded-md border"
            placeholder="Select date"
        >

        <button
            type="button"
            onclick="clearFilters()"
            class="bg-[#f2c94c] text-[#0e243a] px-4 py-2 rounded-md hover:bg-[#edd07a] font-semibold"
        >
            Clear
        </button>

    </div>

</div>

            <div class="space-y-4">
<?php
    $today = \Carbon\Carbon::today();

    $futureEvents = collect($events)->filter(function ($event) use ($today) {
        return !empty($event['date']) &&
               \Carbon\Carbon::parse($event['date'])->startOfDay()->gte($today);
    });

    $doneEvents = collect($events)->filter(function ($event) use ($today) {
        return !empty($event['date']) &&
               \Carbon\Carbon::parse($event['date'])->startOfDay()->lt($today);
    });
?>

<div class="space-y-4" id="eventList">

    
    <?php $__empty_1 = true; $__currentLoopData = $futureEvents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

        <div
            class="event-card cursor-pointer bg-gray-300 p-5 rounded-xl flex justify-between items-center hover:scale-[1.02] hover:shadow-xl hover:bg-[#f2c94c]"
            data-name="<?php echo e(strtolower($event['name'])); ?>"
            data-date="<?php echo e($event['date']); ?>"
            data-event-status="future"
            data-event-id="<?php echo e($event['id']); ?>"
            onclick='openActivities(<?php echo e($event["id"]); ?>, <?php echo json_encode($event["name"], 15, 512) ?>)'
        >
            <div>
                <p class="font-bold text-lg text-[#0e243a] mb-1">
                    <?php echo e(strtoupper($event['name'])); ?>

                </p>

                <p class="text-sm text-gray-700">
                    <?php echo e($event['date']
                        ? \Carbon\Carbon::parse($event['date'])->format('F d, Y')
                        : 'No date set'); ?>

                </p>

                <p class="text-sm text-gray-700">
                    <?php echo e($event['description']); ?>

                </p>
            </div>

            <div class="text-right">
                <p class="mt-2">
                    Status:

                    <?php if($event['status'] == 1): ?>
                        <span class="bg-green-500 text-white px-4 py-1 rounded-full text-sm">
                            Active
                        </span>
                    <?php else: ?>
                        <span class="bg-gray-500 text-white px-4 py-1 rounded-full text-sm">
                            Archived
                        </span>
                    <?php endif; ?>
                </p>

                <p class="text-sm text-gray-700 mt-1">
                    👥
                    <span data-assigned-count>
                        <?php echo e($event['volunteer_count'] ?? 0); ?>

                    </span>
                    volunteers assigned
                </p>
            </div>
        </div>

    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <p id="noFutureEvents" class="text-white">
            No future events available.
        </p>
    <?php endif; ?>


    
    <?php $__currentLoopData = $doneEvents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

        <div
            class="event-card cursor-pointer bg-gray-300 p-5 rounded-xl flex justify-between items-center hover:scale-[1.02] hover:shadow-xl hover:bg-[#f2c94c]"
            data-name="<?php echo e(strtolower($event['name'])); ?>"
            data-date="<?php echo e($event['date']); ?>"
            data-event-status="done"
            data-event-id="<?php echo e($event['id']); ?>"
            style="display: none;"
            onclick='openActivities(<?php echo e($event["id"]); ?>, <?php echo json_encode($event["name"], 15, 512) ?>, "done")'
        >
            <div>
                <p class="font-bold text-lg text-[#0e243a] mb-1">
                    <?php echo e(strtoupper($event['name'])); ?>

                </p>

                <p class="text-sm text-gray-700">
                    <?php echo e(\Carbon\Carbon::parse($event['date'])->format('F d, Y')); ?>

                </p>

                <p class="text-sm text-gray-700">
                    <?php echo e($event['description']); ?>

                </p>
            </div>

            <div class="text-right">
                <p class="mt-2">
                    Status:

                    <?php if($event['status'] == 1): ?>
                        <span class="bg-green-500 text-white px-4 py-1 rounded-full text-sm">
                            Active
                        </span>
                    <?php else: ?>
                        <span class="bg-gray-500 text-white px-4 py-1 rounded-full text-sm">
                            Archived
                        </span>
                    <?php endif; ?>
                </p>

                <p class="text-sm text-gray-700 mt-1">
                    👥
                    <span data-assigned-count>
                        <?php echo e($event['volunteer_count'] ?? 0); ?>

                    </span>
                    volunteers assigned
                </p>
            </div>
        </div>

    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

</div>
        </div>
            </div>
        </div>
    </div>
</div>
<?php echo $__env->make('components.activity-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('components.logout-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<script>
    const eventDates = <?php echo json_encode($events->pluck('date')->filter()->values(), 15, 512) ?>;
</script>
<script src="<?php echo e(asset('js/assignment-management.js')); ?>"></script>

</body>
</html>

<?php /**PATH C:\sysands\MEt.A-Project-SUHAY-main\MEt.A-Project-SUHAY-main\resources\views/assignments.blade.php ENDPATH**/ ?>