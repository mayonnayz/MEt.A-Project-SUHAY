

<div class="flex justify-between items-center mb-6">

    
    <h1 class="text-3xl font-bold text-[#0e243a]">
        <?php echo e($title ?? 'Dashboard'); ?>

    </h1>


    
    <div class="flex items-center gap-5">

        
        <div
            class="relative"
            id="notificationContainer"
        >

            
            <button
                type="button"
                id="notificationButton"
                class="relative p-2 text-[#0e243a] hover:bg-gray-100 rounded-full transition focus:outline-none"
                aria-label="Notifications"
                aria-expanded="false"
            >

                
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="w-6 h-6"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"
                    />
                </svg>


                
                <?php if(($headerUnreadNotifications ?? 0) > 0): ?>

                    <span
                        id="notificationBadge"
                        class="absolute -top-1 -right-1 min-w-5 h-5 px-1 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center border-2 border-white"
                    >
                        <?php echo e($headerUnreadNotifications > 99 ? '99+' : $headerUnreadNotifications); ?>

                    </span>

                <?php endif; ?>

            </button>


            
            <div
                id="notificationDropdown"
                class="hidden absolute right-0 top-11 w-96 bg-white rounded-xl shadow-2xl border border-gray-200 z-[99999] overflow-hidden"
            >

                
                <div class="px-4 py-4 border-b border-gray-200">

                    <div class="flex items-center justify-between">

                        <div>
                            <h3 class="font-bold text-[#0e243a]">
                                Notifications
                            </h3>

                            <p class="text-xs text-gray-400 mt-1">
                                Recent updates
                            </p>
                        </div>


                        <?php if(($headerUnreadNotifications ?? 0) > 0): ?>

                            <span
                                class="text-xs font-semibold text-red-500"
                            >
                                <?php echo e($headerUnreadNotifications); ?>

                                unread
                            </span>

                        <?php endif; ?>

                    </div>

                </div>


                
                <?php
                    $headerNotifications = collect($headerNotifications ?? []);
                ?>
               

                
                <?php if($headerNotifications->isNotEmpty()): ?>

                    <div class="max-h-[400px] overflow-y-auto">

                        <?php $__currentLoopData = $headerNotifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                            <?php

                                $isUnread =
                                    (int) ($notification['is_read'] ?? 0) === 0;

                                $notificationId =
                                    $notification['id'] ?? null;

                            ?>


                            
                            <div
                                class="notification-item p-4 border-b border-gray-100 cursor-pointer transition hover:bg-gray-50 <?php echo e($isUnread ? 'bg-blue-50' : 'bg-white'); ?>"
                                data-notification-id="<?php echo e($notificationId); ?>"
                            >

                                <div class="flex items-start gap-3">

                                    
                                    <div
                                        class="flex-shrink-0 w-9 h-9 rounded-full flex items-center justify-center <?php echo e($isUnread ? 'bg-blue-100' : 'bg-gray-100'); ?>"
                                    >

                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="w-5 h-5 <?php echo e($isUnread ? 'text-blue-600' : 'text-gray-400'); ?>"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                            stroke-width="2"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5"
                                            />
                                        </svg>

                                    </div>


                                    
                                    <div class="flex-1 min-w-0">

                                        
                                        <p class="text-sm font-semibold text-[#0e243a]">
                                            <?php echo e($notification['title'] ?? 'Notification'); ?>

                                        </p>


                                        
                                        <p class="text-xs text-gray-500 mt-1 leading-relaxed">
                                            <?php echo e($notification['message'] ?? ''); ?>

                                        </p>


                                        
                                        <?php if(!empty($notification['created_at'])): ?>

                                            <?php
                                                try {
                                                    $notificationDate = \Carbon\Carbon::parse(
                                                        $notification['created_at']
                                                    );
                                                } catch (\Exception $e) {
                                                    $notificationDate = null;
                                                }
                                            ?>

                                            <?php if($notificationDate): ?>

                                                <p class="text-[11px] text-gray-400 mt-2">
                                                    <?php echo e($notificationDate->diffForHumans()); ?>

                                                </p>

                                            <?php endif; ?>

                                        <?php endif; ?>

                                    </div>


                                    
                                    <?php if($isUnread): ?>

                                        <span
                                            class="notification-unread-dot w-2 h-2 bg-red-500 rounded-full mt-1 flex-shrink-0"
                                        ></span>

                                    <?php endif; ?>

                                </div>

                            </div>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    </div>

                <?php else: ?>

                    
                    <div class="p-8 text-center">

                        <div class="flex justify-center mb-3">

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="w-10 h-10 text-gray-300"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="1.5"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5"
                                />
                            </svg>

                        </div>

                        <p class="text-sm font-medium text-gray-400">
                            No notifications yet.
                        </p>

                        <p class="text-xs text-gray-300 mt-1">
                            You're all caught up.
                        </p>

                    </div>

                <?php endif; ?>

            </div>

        </div>


        
        <div class="flex items-center gap-3">

            
            <?php if(session('ngo_logo')): ?>

                <img
                    src="<?php echo e(session('ngo_logo')); ?>"
                    alt="<?php echo e(session('user_name', 'Guest')); ?>"
                    class="w-10 h-10 rounded-full object-cover bg-gray-100 border border-gray-200"
                    onerror="this.onerror=null; this.src='<?php echo e(asset('images/suhayLogo.png')); ?>';"
                >

            <?php else: ?>

                <img
                    src="<?php echo e(asset('images/suhayLogo.png')); ?>"
                    alt="<?php echo e(session('user_name', 'Guest')); ?>"
                    class="w-10 h-10 rounded-full object-cover bg-gray-100 border border-gray-200"
                >

            <?php endif; ?>


            
            <p class="text-[#0e243a] font-medium">
                <?php echo e(session('user_name', 'Guest')); ?>

            </p>

        </div>

    </div>

</div>




<script>
document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | ELEMENTS
    |--------------------------------------------------------------------------
    */

    const notificationButton =
        document.getElementById('notificationButton');

    const notificationDropdown =
        document.getElementById('notificationDropdown');

    const notificationContainer =
        document.getElementById('notificationContainer');


    /*
    |--------------------------------------------------------------------------
    | SAFETY CHECK
    |--------------------------------------------------------------------------
    */

    if (
        !notificationButton ||
        !notificationDropdown ||
        !notificationContainer
    ) {
        console.warn(
            'Notification elements were not found.'
        );

        return;
    }


    /*
    |--------------------------------------------------------------------------
    | OPEN / CLOSE DROPDOWN
    |--------------------------------------------------------------------------
    */

    notificationButton.addEventListener(
        'click',
        function (event) {

            event.stopPropagation();

            const currentlyHidden =
                notificationDropdown.classList.contains('hidden');


            if (currentlyHidden) {

                notificationDropdown.classList.remove(
                    'hidden'
                );

                notificationButton.setAttribute(
                    'aria-expanded',
                    'true'
                );

            } else {

                notificationDropdown.classList.add(
                    'hidden'
                );

                notificationButton.setAttribute(
                    'aria-expanded',
                    'false'
                );

            }

        }
    );


    /*
    |--------------------------------------------------------------------------
    | CLOSE WHEN CLICKING OUTSIDE
    |--------------------------------------------------------------------------
    */

    document.addEventListener(
        'click',
        function (event) {

            if (
                !notificationContainer.contains(
                    event.target
                )
            ) {

                notificationDropdown.classList.add(
                    'hidden'
                );

                notificationButton.setAttribute(
                    'aria-expanded',
                    'false'
                );

            }

        }
    );


    /*
    |--------------------------------------------------------------------------
    | MARK NOTIFICATION AS READ
    |--------------------------------------------------------------------------
    */

    const notificationItems =
        document.querySelectorAll(
            '.notification-item'
        );


    notificationItems.forEach(
        function (item) {

            item.addEventListener(
                'click',
                function () {

                    const notificationId =
                        item.dataset.notificationId;


                    if (!notificationId) {
                        return;
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | PREVENT MULTIPLE CLICKS
                    |--------------------------------------------------------------------------
                    */

                    if (
                        item.dataset.markingRead === 'true'
                    ) {
                        return;
                    }

                    item.dataset.markingRead = 'true';


                    /*
                    |--------------------------------------------------------------------------
                    | CSRF TOKEN
                    |--------------------------------------------------------------------------
                    */

                    const csrfElement =
                        document.querySelector(
                            'meta[name="csrf-token"]'
                        );


                    if (!csrfElement) {

                        console.error(
                            'CSRF token not found.'
                        );

                        item.dataset.markingRead = 'false';

                        return;
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | SEND REQUEST
                    |--------------------------------------------------------------------------
                    */

                    fetch(
                        `/notifications/${notificationId}/read`,
                        {
                            method: 'POST',

                            headers: {

                                'X-CSRF-TOKEN':
                                    csrfElement.getAttribute(
                                        'content'
                                    ),

                                'Accept':
                                    'application/json',

                                'Content-Type':
                                    'application/json'
                            }
                        }
                    )

                    .then(function (response) {

                        if (!response.ok) {

                            throw new Error(
                                `HTTP error: ${response.status}`
                            );
                        }

                        return response.json();

                    })

                    .then(function (data) {

                        if (data.success) {

                            /*
                            |--------------------------------------------------------------------------
                            | REMOVE UNREAD BACKGROUND
                            |--------------------------------------------------------------------------
                            */

                            item.classList.remove(
                                'bg-blue-50'
                            );

                            item.classList.add(
                                'bg-white'
                            );


                            /*
                            |--------------------------------------------------------------------------
                            | REMOVE UNREAD DOT
                            |--------------------------------------------------------------------------
                            */

                            const unreadDot =
                                item.querySelector(
                                    '.notification-unread-dot'
                                );


                            if (unreadDot) {
                                unreadDot.remove();
                            }


                            /*
                            |--------------------------------------------------------------------------
                            | RELOAD DASHBOARD
                            |--------------------------------------------------------------------------
                            */

                            setTimeout(
                                function () {

                                    window.location.reload();

                                },
                                150
                            );

                        } else {

                            console.error(
                                'Failed to mark notification as read:',
                                data.message
                            );

                            item.dataset.markingRead =
                                'false';
                        }

                    })

                    .catch(function (error) {

                        console.error(
                            'Error marking notification as read:',
                            error
                        );

                        item.dataset.markingRead =
                            'false';
                    });

                }
            );

        }
    );

});
</script><?php /**PATH C:\sysands\MEt.A-Project-SUHAY\resources\views/components/header.blade.php ENDPATH**/ ?>