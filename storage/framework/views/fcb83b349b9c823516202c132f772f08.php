<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold text-[#0e243a]">
        <?php echo e($title ?? 'Dashboard'); ?>

    </h1>

    <div class="flex items-center gap-5">
        <div class="relative" id="notificationContainer">
            <button
                type="button"
                id="notificationButton"
                class="relative p-2 text-[#0e243a] hover:bg-gray-100 rounded-full transition focus:outline-none"
                aria-label="Notifications"
                aria-expanded="false"
                aria-controls="notificationDropdown"
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
            </button>

            <div
                id="notificationDropdown"
                class="hidden absolute right-0 top-11 w-96 max-w-[90vw] bg-white rounded-xl shadow-2xl border border-gray-200 z-[99999] overflow-hidden"
            >
                <div class="px-4 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-bold text-[#0e243a]">
                                Notifications
                            </h3>

                            <p class="text-xs text-gray-400 mt-1">
                                Most recent updates
                            </p>
                        </div>

                        <span
                            id="notificationUnreadLabel"
                            class="hidden text-xs font-semibold text-red-500"
                        >
                            0 unread
                        </span>
                    </div>
                </div>

                <div
                    id="notificationList"
                    class="max-h-[400px] overflow-y-auto"
                    aria-live="polite"
                >
                    <div class="p-8 text-center">
                        <p class="text-sm text-gray-400">
                            Open notifications to load updates.
                        </p>
                    </div>
                </div>

            </div>
        </div>

        <div class="flex items-center gap-3">

            <img
                src="<?php echo e(session('profile_picture') ?: asset('images/LoginIcons/user.png')); ?>"
                alt="<?php echo e(session('user_name', 'Guest')); ?>"
                class="w-10 h-10 rounded-full object-cover bg-gray-100 border border-gray-200"
                onerror="this.onerror=null; this.src='<?php echo e(asset('images/LoginIcons/user.png')); ?>';"
            >

            <p class="text-[#0e243a] font-medium">
                <?php echo e(session('user_name', 'Guest')); ?>

            </p>

        </div>

    </div>
</div>
<?php echo $__env->make('components.confirm-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>


<script>
(function () {
    if (window.suhayNotificationsInitialized) return;
    window.suhayNotificationsInitialized = true;

    function initializeNotifications() {
        const button = document.getElementById('notificationButton');
        const dropdown = document.getElementById('notificationDropdown');
        const container = document.getElementById('notificationContainer');
        const list = document.getElementById('notificationList');
        const unreadLabel = document.getElementById('notificationUnreadLabel');

        if (!button || !dropdown || !container || !list) return;

        let loading = false;
        let loaded = false;
        let notifications = [];

        function updateBadge(count) {
            count = Math.max(0, Number(count) || 0);

            let badge = document.getElementById('notificationBadge');

            if (count > 0) {
                if (!badge) {
                    badge = document.createElement('span');
                    badge.id = 'notificationBadge';
                    badge.className =
                        'absolute -top-1 -right-1 min-w-5 h-5 px-1 ' +
                        'bg-red-500 text-white text-[10px] font-bold ' +
                        'rounded-full flex items-center justify-center ' +
                        'border-2 border-white';
                    button.appendChild(badge);
                }

                badge.textContent = count > 99 ? '99+' : String(count);
            } else if (badge) {
                badge?.remove();
            }

            if (unreadLabel) {
                unreadLabel.textContent = count + ' unread';
                unreadLabel.classList.toggle('hidden', count === 0);
            }
        }

        function renderEmpty(message) {
            list.replaceChildren();

            const wrapper = document.createElement('div');
            wrapper.className = 'p-8 text-center';

            const text = document.createElement('p');
            text.className = 'text-sm text-gray-400';
            text.textContent = message;

            wrapper.appendChild(text);
            list.appendChild(wrapper);
        }

        function isUnread(notification) {
            return notification.is_read === false ||
                notification.is_read === 0 ||
                notification.is_read === 'false' ||
                notification.is_read === '0' ||
                notification.is_read == null;
        }

        function renderNotifications(items) {
            list.replaceChildren();

            if (!Array.isArray(items) || items.length === 0) {
                renderEmpty('No notifications yet. You’re all caught up.');
                return;
            }

            const sorted = [...items].sort((a, b) => {
                const dateA = Date.parse(a.created_at || '');
                const dateB = Date.parse(b.created_at || '');

                if (!Number.isFinite(dateA)) return 1;
                if (!Number.isFinite(dateB)) return -1;

                return dateB - dateA || Number(b.id) - Number(a.id);
            });

            sorted.forEach(notification => {
                const unread = isUnread(notification);
                const item = document.createElement('div');

                item.className =
                    'notification-item p-4 border-b border-gray-100 ' +
                    'cursor-pointer transition hover:bg-gray-50 ' +
                    (unread ? 'bg-blue-50' : 'bg-white');

                item.dataset.notificationId = notification.id;
                item.dataset.isUnread = String(unread);
                item.setAttribute('role', 'button');
                item.setAttribute('tabindex', '0');

                const row = document.createElement('div');
                row.className = 'flex items-start gap-3';

                const iconContainer = document.createElement('div');
                iconContainer.className =
                    'notification-icon flex-shrink-0 w-9 h-9 rounded-full ' +
                    'flex items-center justify-center ' +
                    (unread ? 'bg-blue-100' : 'bg-gray-100');

                const icon = document.createElementNS(
                    'http://www.w3.org/2000/svg', 'svg'
                );
                icon.setAttribute('viewBox', '0 0 24 24');
                icon.setAttribute('fill', 'none');
                icon.setAttribute('stroke', 'currentColor');
                icon.setAttribute('stroke-width', '2');
                icon.setAttribute('class',
                    'w-5 h-5 ' +
                    (unread ? 'text-blue-600' : 'text-gray-400')
                );

                const path = document.createElementNS(
                    'http://www.w3.org/2000/svg', 'path'
                );
                path.setAttribute('stroke-linecap', 'round');
                path.setAttribute('stroke-linejoin', 'round');
                path.setAttribute(
                    'd',
                    'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 ' +
                    '14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 ' +
                    '10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 ' +
                    '.538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 ' +
                    '0 11-6 0v-1m6 0H9'
                );

                icon.appendChild(path);
                iconContainer.appendChild(icon);

                const content = document.createElement('div');
                content.className = 'flex-1 min-w-0';

                const title = document.createElement('p');
                title.className =
                    'notification-title text-sm font-semibold text-[#0e243a]';
                title.textContent = notification.title || 'Notification';

                const message = document.createElement('p');
                message.className = 'text-xs text-gray-500 mt-1 leading-relaxed';
                message.textContent = notification.message || '';

                content.append(title, message);

                if (notification.created_at) {
                    const date = document.createElement('p');
                    date.className = 'text-[11px] text-gray-400 mt-2';

                    const parsedDate = new Date(notification.created_at);
                    date.textContent = Number.isNaN(parsedDate.getTime())
                        ? 'Invalid date'
                        : parsedDate.toLocaleString('en-PH', {
                            timeZone: 'Asia/Manila',
                            dateStyle: 'medium',
                            timeStyle: 'short'
                        });

                    content.appendChild(date);
                }

                row.append(iconContainer, content);

                if (unread) {
                    const dot = document.createElement('span');
                    dot.className =
                        'notification-unread-dot w-2 h-2 bg-red-500 ' +
                        'rounded-full mt-1 flex-shrink-0';
                    row.appendChild(dot);
                }

                    item.appendChild(row);

                    const deleteButton = document.createElement('button');

                    deleteButton.type = 'button';
                    deleteButton.textContent = 'Delete';
                    deleteButton.className =
                        'mt-2 text-xs font-semibold text-red-500 hover:text-red-700 ' +
                        'hover:underline focus:outline-none';

                    deleteButton.setAttribute(
                        'aria-label',
                        'Delete notification: ' + (notification.title || 'Notification')
                    );

deleteButton.addEventListener('click', event => {
    event.stopPropagation();

    showConfirmModal({
        title: 'Delete Notification',
        message: 'Are you sure you want to delete this notification? This action cannot be undone.',
        confirmText: 'Delete',
        cancelText: 'Cancel',
        onConfirm: async () => {
            try {
                const csrf = document.querySelector(
                    'meta[name="csrf-token"]'
                );

                if (!csrf) {
                    throw new Error('CSRF token is missing.');
                }

                const response = await fetch(
                    '/notifications/' +
                        encodeURIComponent(notification.id),
                    {
                        method: 'DELETE',
                        credentials: 'same-origin',
                        headers: {
                            'X-CSRF-TOKEN': csrf.content,
                            'Accept': 'application/json'
                        }
                    }
                );

                const data = await response.json();

                if (!response.ok || !data.success) {
                    throw new Error(
                        data.message || 'Unable to delete notification.'
                    );
                }

                notifications = notifications.filter(
                    n => String(n.id) !== String(notification.id)
                );

                renderNotifications(notifications);

                updateBadge(
                    notifications.filter(isUnread).length
                );

                return true;

            } catch (error) {
                console.error(
                    'Failed to delete notification:',
                    error
                );

                window.alert(
                    error.message || 'Unable to delete notification.'
                );
                return false;
            }
        }
    });
});

                    item.appendChild(deleteButton);

                    item.addEventListener('click', () => markAsRead(item));

                    item.addEventListener('keydown', event => {
                        if (
                            event.target === item &&
                            (event.key === 'Enter' || event.key === ' ')
                        ) {
                            event.preventDefault();
                            markAsRead(item);
                        }
                    });

                item.addEventListener('keydown', event => {
                    if (event.key === 'Enter' || event.key === ' ') {
                        event.preventDefault();
                        markAsRead(item);
                    }
                });

                list.appendChild(item);
            });
        }

        async function postRead(notificationId) {
            const csrf = document.querySelector(
                'meta[name="csrf-token"]'
            );

            if (!csrf) throw new Error('CSRF token is missing.');

            const response = await fetch(
                '/notifications/' + encodeURIComponent(notificationId) + '/read',
                {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'X-CSRF-TOKEN': csrf.content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({})
                }
            );

            const data = await response.json();

            if (!response.ok || !data.success) {
                throw new Error(data.message || 'Unable to mark as read.');
            }
        }

        async function markAllNotificationsAsRead() {
            const unread = notifications.filter(isUnread);

            if (unread.length === 0) {
                updateBadge(0);
                return;
            }

            const results = await Promise.allSettled(
                unread.map(notification => postRead(notification.id))
            );

            const failed = results.filter(result =>
                result.status === 'rejected'
            );

            unread.forEach((notification, index) => {
                if (results[index].status === 'fulfilled') {
                    notification.is_read = true;
                }
            });

            renderNotifications(notifications);
            updateBadge(notifications.filter(isUnread).length);

            if (failed.length > 0) {
                console.error(
                    failed.length + ' notification(s) could not be marked read.'
                );
            }
        }

        async function markAsRead(item) {
            if (
                item.dataset.isUnread !== 'true' ||
                item.dataset.markingRead === 'true'
            ) return;

            item.dataset.markingRead = 'true';

            try {
                await postRead(item.dataset.notificationId);

                const notification = notifications.find(
                    n => String(n.id) === item.dataset.notificationId
                );

                if (notification) notification.is_read = true;

                renderNotifications(notifications);
                updateBadge(notifications.filter(isUnread).length);
            } catch (error) {
                console.error('Failed to mark notification as read:', error);
            } finally {
                item.dataset.markingRead = 'false';
            }
        }

        async function loadNotifications(forceRefresh = false) {
            if (loading || (loaded && !forceRefresh)) return;

            loading = true;
            list.setAttribute('aria-busy', 'true');

            try {
                const response = await fetch('/notifications', {
                    headers: { 'Accept': 'application/json' },
                    credentials: 'same-origin',
                    cache: 'no-store'
                });

                const data = await response.json();

                if (!response.ok || !data.success) {
                    throw new Error(
                        data.message || 'Unable to load notifications.'
                    );
                }

                notifications = Array.isArray(data.notifications)
                    ? data.notifications
                    : [];

                renderNotifications(notifications);
                updateBadge(notifications.filter(isUnread).length);
                loaded = true;
            } catch (error) {
                console.error('Failed to load notifications:', error);
                renderEmpty('Unable to load notifications. Please try again.');
            } finally {
                loading = false;
                list.removeAttribute('aria-busy');
            }
        }

        button.addEventListener('click', async event => {
            event.stopPropagation();

            const opening = dropdown.classList.contains('hidden');
            dropdown.classList.toggle('hidden', !opening);
            button.setAttribute('aria-expanded', String(opening));

            if (opening) {
                await loadNotifications(true);

                if (loaded) {
                    await markAllNotificationsAsRead();
                }
            }
        });

        document.addEventListener('click', event => {
            if (!container.contains(event.target)) {
                dropdown.classList.add('hidden');
                button.setAttribute('aria-expanded', 'false');
            }
        });
        loadNotifications();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializeNotifications, {
            once: true
        });
    } else {
        initializeNotifications();
    }
})();
</script>

<?php /**PATH C:\Sysands\MEt.A-Project-SUHAY\resources\views/components/header.blade.php ENDPATH**/ ?>