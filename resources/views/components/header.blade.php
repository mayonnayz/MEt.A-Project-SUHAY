
<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold text-[#0e243a]">
        {{ $title ?? 'Dashboard' }}
    </h1>

    <div class="flex items-center gap-5">

        {{-- NOTIFICATION --}}
        <div class="relative">

            <button
                onclick="toggleNotifications()"
                class="relative p-2 text-[#0e243a] hover:bg-gray-100 rounded-full transition"
            >
                {{-- Bell Icon --}}
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
                        d="M15 17h5l-1.405-1.405A2.032
                           2.032 0 0118 14.158V11a6.002
                           6.002 0 00-4-5.659V5a2 2 0
                           10-4 0v.341C7.67 6.165
                           6 8.388 6 11v3.159c0
                           .538-.214 1.055-.595
                           1.436L4 17h5m6 0v1a3
                           3 0 11-6 0v-1m6 0H9"
                    />
                </svg>

                {{-- Notification Badge --}}
                <span
                    class="absolute -top-1 -right-1
                           w-5 h-5
                           bg-red-500 text-white
                           text-[10px] font-bold
                           rounded-full
                           flex items-center justify-center"
                >
                    3
                </span>
            </button>

            {{-- Notification Dropdown --}}
            <div
                id="notificationDropdown"
                class="hidden absolute right-0 mt-3 w-80
                       bg-white rounded-xl shadow-lg
                       border border-gray-100
                       z-50 overflow-hidden"
            >

                {{-- Header --}}
                <div class="p-4 border-b">
                    <h3 class="font-bold text-[#0e243a]">
                        Notifications
                    </h3>
                    <p class="text-xs text-gray-400 mt-1">
                        Recent updates
                    </p>
                </div>

                {{-- Notification 1 --}}
                <div class="p-4 border-b hover:bg-gray-50">
                    <p class="text-sm font-semibold text-[#0e243a]">
                        New Volunteer Application
                    </p>

                    <p class="text-xs text-gray-500 mt-1">
                        A new volunteer application requires your review.
                    </p>

                    <p class="text-[11px] text-gray-400 mt-2">
                        5 minutes ago
                    </p>
                </div>

                {{-- Notification 2 --}}
                <div class="p-4 border-b hover:bg-gray-50">
                    <p class="text-sm font-semibold text-[#0e243a]">
                        Low Inventory
                    </p>

                    <p class="text-xs text-gray-500 mt-1">
                        Rice is below the minimum stock threshold.
                    </p>

                    <p class="text-[11px] text-gray-400 mt-2">
                        1 hour ago
                    </p>
                </div>

                {{-- Notification 3 --}}
                <div class="p-4 hover:bg-gray-50">
                    <p class="text-sm font-semibold text-[#0e243a]">
                        Donation Received
                    </p>

                    <p class="text-xs text-gray-500 mt-1">
                        A monetary donation of ₱333 has been recorded.
                    </p>

                    <p class="text-[11px] text-gray-400 mt-2">
                        Today
                    </p>
                </div>

            </div>
        </div>


        {{-- USER PROFILE --}}
        <div class="flex items-center gap-3">

            @if(session('ngo_logo'))
                <img
                    src="{{ session('ngo_logo') }}"
                    alt="{{ session('user_name', 'Guest') }}"
                    class="w-10 h-10 rounded-full object-cover bg-gray-100"
                    onerror="this.src='{{ asset('images/suhayLogo.png') }}'"
                >
            @else
                <div class="w-10 h-10 bg-gray-300 rounded-full"></div>
            @endif

            <p class="text-[#0e243a]">
                {{ session('user_name', 'Guest') }}
            </p>

        </div>

    </div>
</div>


{{-- NOTIFICATION SCRIPT --}}
<script>
function toggleNotifications() {
    const dropdown = document.getElementById('notificationDropdown');

    dropdown.classList.toggle('hidden');
}
</script>

