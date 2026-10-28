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


     

        
        
        
        

        <div class="bg-white rounded-2xl shadow-sm p-6 mb-6">

            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-5">
                <div>
                    <h2 class="text-lg font-bold text-[#0e243a]">
                        NGO Updates
                    </h2>
                    <p class="text-sm text-gray-400">
                        Share announcements and stories with the SUHAY community.
                    </p>
                </div>

                <button
                    type="button"
                    onclick="openUpdatePostModal()"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#0e243a] px-4 py-2.5 text-sm font-semibold text-white hover:bg-[#183b5d] transition"
                >
                    <span class="text-lg leading-none">+</span>
                    Create Post
                </button>
            </div>

            <?php $__empty_1 = true; $__currentLoopData = ($updatePosts ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                <div class="border border-gray-100 rounded-xl p-4 mb-3 last:mb-0">

                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">

                        
                        <div class="min-w-0 flex-1">

                            
                            <div class="flex flex-wrap items-center gap-2 mb-3">
                                <h3 class="font-semibold text-[#0e243a]">
                                    <?php echo e($post->title); ?>

                                </h3>

                                <?php if((int) $post->status === 1): ?>
                                    <span class="rounded-full bg-green-100 px-2.5 py-1 text-xs font-semibold text-green-700">
                                        Published
                                    </span>
                                <?php elseif((int) $post->status === 2): ?>
                                    <span class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-600">
                                        Archived
                                    </span>
                                <?php else: ?>
                                    <span class="rounded-full bg-yellow-100 px-2.5 py-1 text-xs font-semibold text-yellow-700">
                                        Draft
                                    </span>
                                <?php endif; ?>
                            </div>

                            
                            <?php if(!empty($post->image)): ?>
                                <div class="w-full max-w-2xl mb-4 overflow-hidden rounded-xl bg-gray-100">
                                    <img
                                        src="<?php echo e($post->image); ?>"
                                        alt="<?php echo e($post->title); ?>"
                                        loading="eager"
                                        class="block w-full h-48 sm:h-56 object-cover rounded-xl"
                                        onload="console.log('Post image loaded:', this.src);"
                                        onerror="console.error('Post image failed:', this.src);"
                                    >
                                </div>
                            <?php endif; ?>

                            
                            <p class="text-sm text-gray-600 whitespace-pre-line">
                                <?php echo e(\Illuminate\Support\Str::limit($post->description, 220)); ?>

                            </p>

                            
                            <?php if(isset($post->created_at) && $post->created_at): ?>
                                <p class="text-xs text-gray-400 mt-2">
                                    <?php echo e(\Carbon\Carbon::parse($post->created_at)->diffForHumans()); ?>

                                </p>
                            <?php endif; ?>

                        </div>

                        
                        <div class="flex flex-wrap items-center gap-2 sm:justify-end">

                            
                            <?php if((int) $post->status === 1): ?>

                                <form
                                    method="POST"
                                    action="<?php echo e(url('/update-posts/' . $post->id . '/status')); ?>"
                                    onsubmit="return false;"
                                    data-confirm-title="Unpublish Post"
                                    data-confirm-message="This post will no longer be published. Do you want to move it back to drafts?"
                                    data-confirm-text="Unpublish"
                                >
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PATCH'); ?>
                                    <input type="hidden" name="status" value="0">

                                    <button
                                        type="button"
                                        onclick="confirmUpdatePostAction(this)"
                                        class="rounded-lg border border-yellow-200 bg-yellow-50 px-3 py-2 text-xs font-semibold text-yellow-700 hover:bg-yellow-100 transition"
                                    >
                                        Unpublish
                                    </button>
                                </form>

                            <?php elseif((int) $post->status === 0): ?>

                                <form
                                    method="POST"
                                    action="<?php echo e(url('/update-posts/' . $post->id . '/status')); ?>"
                                    onsubmit="return false;"
                                    data-confirm-title="Publish Post"
                                    data-confirm-message="This post will be published and made available to the SUHAY community. Do you want to continue?"
                                    data-confirm-text="Publish"
                                >
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PATCH'); ?>
                                    <input type="hidden" name="status" value="1">

                                    <button
                                        type="button"
                                        onclick="confirmUpdatePostAction(this)"
                                        class="rounded-lg border border-green-200 bg-green-50 px-3 py-2 text-xs font-semibold text-green-700 hover:bg-green-100 transition"
                                    >
                                        Publish
                                    </button>
                                </form>

                            <?php endif; ?>

                            
                            <?php if((int) $post->status === 2): ?>

                                <form
                                    method="POST"
                                    action="<?php echo e(url('/update-posts/' . $post->id . '/status')); ?>"
                                    onsubmit="return false;"
                                    data-confirm-title="Restore Post"
                                    data-confirm-message="This archived post will be restored as a draft. Do you want to continue?"
                                    data-confirm-text="Restore"
                                >
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PATCH'); ?>
                                    <input type="hidden" name="status" value="0">

                                    <button
                                        type="button"
                                        onclick="confirmUpdatePostAction(this)"
                                        class="rounded-lg border border-blue-200 bg-blue-50 px-3 py-2 text-xs font-semibold text-blue-700 hover:bg-blue-100 transition"
                                    >
                                        Restore to Draft
                                    </button>
                                </form>

                            <?php else: ?>

                                <form
                                    method="POST"
                                    action="<?php echo e(url('/update-posts/' . $post->id . '/status')); ?>"
                                    onsubmit="return false;"
                                    data-confirm-title="Archive Post"
                                    data-confirm-message="This post will be archived and no longer treated as an active update. Do you want to continue?"
                                    data-confirm-text="Archive"
                                >
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PATCH'); ?>
                                    <input type="hidden" name="status" value="2">

                                    <button
                                        type="button"
                                        onclick="confirmUpdatePostAction(this)"
                                        class="rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs font-semibold text-gray-600 hover:bg-gray-50 transition"
                                    >
                                        Archive
                                    </button>
                                </form>

                            <?php endif; ?>

                            
                            <form
                                method="POST"
                                action="<?php echo e(url('/update-posts/' . $post->id)); ?>"
                                onsubmit="return false;"
                                data-confirm-title="Delete Post"
                                data-confirm-message="This post will be permanently deleted. This action cannot be undone. Are you sure?"
                                data-confirm-text="Delete Permanently"
                            >
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>

                                <button
                                    type="button"
                                    onclick="confirmUpdatePostAction(this)"
                                    class="rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-xs font-semibold text-red-600 hover:bg-red-100 transition"
                                >
                                    Delete
                                </button>
                            </form>

                        </div>

                    </div>
                </div>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                <div class="rounded-xl border border-dashed border-gray-300 px-4 py-8 text-center">
                    <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-yellow-50 text-[#b38720]">
                        <span class="text-2xl">✎</span>
                    </div>

                    <p class="font-semibold text-[#0e243a]">
                        No updates yet
                    </p>

                    <p class="mt-1 text-sm text-gray-400">
                        Create your first post to share your organization's news.
                    </p>
                </div>

            <?php endif; ?>

        </div>



        
        
        

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

            

            <div class="bg-white rounded-2xl shadow-sm p-6">

                <div class="flex justify-between items-center mb-5">
                    <div>
                        <h2 class="text-lg font-bold text-[#0e243a]">
                            Volunteer Applications
                        </h2>

                        <p class="text-sm text-gray-400">
                            Application status
                        </p>
                    </div>

                    <a href="/applications"
                       class="text-sm text-[#d39a11] hover:underline">
                        View
                    </a>
                </div>

                <?php
                    $totalApplications =
                        ($pendingApplications ?? 0) +
                        ($approvedApplications ?? 0) +
                        ($rejectedApplications ?? 0);

                    $pendingPercentage = $totalApplications > 0
                        ? (($pendingApplications ?? 0) / $totalApplications) * 100
                        : 0;

                    $approvedPercentage = $totalApplications > 0
                        ? (($approvedApplications ?? 0) / $totalApplications) * 100
                        : 0;

                    $rejectedPercentage = $totalApplications > 0
                        ? (($rejectedApplications ?? 0) / $totalApplications) * 100
                        : 0;
                ?>

                
                <div class="mb-4">
                    <div class="flex justify-between text-sm mb-2">
                        <span>Pending</span>
                        <span class="font-semibold text-orange-500">
                            <?php echo e($pendingApplications ?? 0); ?>

                        </span>
                    </div>

                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-orange-400 h-2 rounded-full"
                             style="width: <?php echo e($pendingPercentage); ?>%">
                        </div>
                    </div>
                </div>

                
                <div class="mb-4">
                    <div class="flex justify-between text-sm mb-2">
                        <span>Approved</span>
                        <span class="font-semibold text-green-600">
                            <?php echo e($approvedApplications ?? 0); ?>

                        </span>
                    </div>

                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-500 h-2 rounded-full"
                             style="width: <?php echo e($approvedPercentage); ?>%">
                        </div>
                    </div>
                </div>

                
                <div>
                    <div class="flex justify-between text-sm mb-2">
                        <span>Rejected</span>
                        <span class="font-semibold text-red-500">
                            <?php echo e($rejectedApplications ?? 0); ?>

                        </span>
                    </div>

                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-red-400 h-2 rounded-full"
                             style="width: <?php echo e($rejectedPercentage); ?>%">
                        </div>
                    </div>
                </div>

            </div>


            

            <div class="bg-white rounded-2xl shadow-sm p-6">

                <div class="flex justify-between items-center mb-5">
                    <div>
                        <h2 class="text-lg font-bold text-[#0e243a]">
                            Upcoming Events
                        </h2>

                        <p class="text-sm text-gray-400">
                            Volunteer events
                        </p>
                    </div>

                    <a href="/events"
                       class="text-sm text-[#d39a11] hover:underline">
                        View
                    </a>
                </div>

                <?php $__empty_1 = true; $__currentLoopData = $upcomingEvents ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                    <div class="flex gap-4 mb-5">

                        <div class="w-12 h-12 rounded-xl bg-[#f2c94c] flex flex-col items-center justify-center flex-shrink-0">
                            <span class="text-xs">
                                <?php echo e(\Carbon\Carbon::parse($event->date)->format('M')); ?>

                            </span>

                            <span class="text-lg font-bold">
                                <?php echo e(\Carbon\Carbon::parse($event->date)->format('d')); ?>

                            </span>
                        </div>

                        <div class="min-w-0">
                            <h3 class="font-semibold text-[#0e243a]">
                                <?php echo e($event->name); ?>

                            </h3>

                            <p class="text-xs text-gray-500">
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

<?php echo $__env->make('components.update-post-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php echo $__env->make('components.confirm-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('components.logout-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php echo $__env->make('components.confirm-modal-script', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<script src="<?php echo e(asset('js/dashboard.js')); ?>"></script>
</body>
</html>
<?php /**PATH C:\Sysands\MEt.A-Project-SUHAY\resources\views/dashboard.blade.php ENDPATH**/ ?>