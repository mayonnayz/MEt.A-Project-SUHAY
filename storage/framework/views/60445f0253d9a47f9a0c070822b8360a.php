<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Activity</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>

<body class="bg-gray-200">
<div class="flex">

    <?php echo $__env->make('components.nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="flex-1 p-8">

        <?php echo $__env->make('components.header', ['title' => 'Volunteer Management'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <!-- Tabs -->
        <div class="bg-[#0e243a] p-4 rounded-2xl flex gap-4 mb-6 flex-wrap">
            <a href="/service-management" class="bg-[#f2c94c] px-6 py-2 rounded-full font-semibold">Volunteer Lists</a>
            <a href="/applications" class="bg-[#f2c94c] px-6 py-2 rounded-full font-semibold">Applications</a>
            <a href="/assignments" class="bg-[#f2c94c] px-6 py-2 rounded-full font-semibold">Assignments</a>
            <a href="/events" class="bg-[#f2c94c] px-6 py-2 rounded-full font-semibold">Events</a>
            <a href="/track-activity" class="bg-gray-200 text-[#0e243a] px-6 py-2 rounded-full font-semibold">Track Activity</a>
        </div>

        <!-- Filters -->
        <div class="bg-gray-200 p-4 rounded-2xl flex items-center justify-between mb-6">
            <div class="flex gap-4 w-full">
                <select class="px-4 py-2 rounded-xl border w-64">
                    <option>Feeding Program</option>
                    <option>Clean-Up Drive</option>
                </select>

                <input type="text" placeholder="Search Volunteer" class="px-4 py-2 rounded-xl border flex-1">
            </div>

            <button onclick="openLogActivityModal()"
                    class="ml-4 bg-[#f2c94c] px-6 py-2 rounded-full font-semibold">
                + Log Activity
            </button>         
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-4 gap-4 mb-6">
            <div class="bg-gray-200 p-4 rounded-2xl">
                <p>Total Volunteers</p>
                <h2 class="text-xl font-bold">2</h2>
            </div>

            <div class="bg-gray-200 p-4 rounded-2xl">
                <p>Completed</p>
                <h2 class="text-xl font-bold">1</h2>
            </div>

            <div class="bg-gray-200 p-4 rounded-2xl">
                <p>On Going</p>
                <h2 class="text-xl font-bold">1</h2>
            </div>

            <div class="bg-gray-200 p-4 rounded-2xl">
                <p>Absent</p>
                <h2 class="text-xl font-bold">0</h2>
            </div>
        </div>

        <!-- Table Container -->
        <div class="bg-[#0e243a] p-4 rounded-2xl">

            <div class="bg-gray-200 rounded-2xl overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-300 text-left">
                        <tr>
                            <th class="p-4">#</th>
                            <th class="p-4">Volunteer</th>
                            <th class="p-4">Activity</th>
                            <th class="p-4">Time In</th>
                            <th class="p-4">Time Out</th>
                            <th class="p-4">Hours</th>
                            <th class="p-4">Status</th>
                            <th class="p-4">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $assignments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="border-t">
                                <td class="p-4"><?php echo e($loop->iteration); ?></td>

                                <td class="p-4">
                                    <?php echo e($item['accounts']['first_name'] ?? ''); ?>

                                    <?php echo e($item['accounts']['last_name'] ?? ''); ?>

                                </td>

                                <td class="p-4">
                                    <?php echo e($item['activities']['name'] ?? 'N/A'); ?>

                                </td>

                                <td class="p-4">
                                    <?php echo e($item['time_in'] ?? '-'); ?>

                                </td>

                                <td class="p-4">
                                    <?php echo e($item['time_out'] ?? '-'); ?>

                                </td>

                                <td class="p-4">
                                    <?php echo e($item['hours'] ?? '-'); ?>

                                </td>

                                <td class="p-4 font-semibold
                                    <?php echo e(($item['status'] ?? '') == 'Completed' ? 'text-green-600' : 'text-yellow-500'); ?>">
                                    <?php echo e($item['status'] ?? 'On Going'); ?>

                                </td>

                                <td class="p-4">
                                    <button class="bg-blue-700 text-white px-5 py-2 rounded-full">
                                        View
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="8" class="p-4 text-center text-gray-500">
                                    No activity records found
                                </td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                </table>
            </div>

        </div>

    </div>
</div>
<?php echo $__env->make('components.log-activity-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('components.logout-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<script>
   function openLogActivityModal() {
    const modal = document.getElementById('logActivityModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeLogActivityModal() {
    const modal = document.getElementById('logActivityModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}
</script>
<script>
    function openLogoutModal() {
        document.getElementById('logoutModal').classList.remove('hidden');
        document.getElementById('logoutModal').classList.add('flex');
    }

    function closeLogoutModal() {
        document.getElementById('logoutModal').classList.add('hidden');
    }
</script>
</body>
</html><?php /**PATH C:\sysands\MEt.A-Project-SUHAY-elisha\resources\views/track_activity.blade.php ENDPATH**/ ?>