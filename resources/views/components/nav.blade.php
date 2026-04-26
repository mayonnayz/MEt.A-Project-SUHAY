@php
    $role = strtolower(session('role'));

    $active = 'bg-[#1a3554]';
    $inactive = 'hover:bg-[#f2c94c] hover:text-[#0e243a]';
@endphp

<div class="group w-28 hover:w-56 bg-[#0e243a] min-h-screen flex flex-col py-6 text-white rounded-r-3xl transition-all duration-300 overflow-hidden">

    {{-- ===================== --}}
    {{-- NGO / STAFF NAV --}}
    {{-- ===================== --}}
    @if(in_array($role, ['ngo head', 'donation manager', 'volunteer manager']))

        {{-- Dashboard --}}
        <a href="/sm-dashboard"
           class="flex items-center gap-4 px-4 py-4 rounded-xl mx-2
           {{ request()->is('sm-dashboard') ? $active : $inactive }}">
            <img src="{{ asset('images/ServiceManagement/SMDash.png') }}" class="w-16 h-16 object-contain">
            <span class="opacity-0 group-hover:opacity-100">Dashboard</span>
        </a>

        {{-- NGOs --}}
        @if($role === 'ngo head')
            <a href="/sm-ngos"
               class="flex items-center gap-4 px-4 py-4 rounded-xl mx-2
               {{ request()->is('sm-ngos') ? $active : $inactive }}">
                <img src="{{ asset('images/ServiceManagement/SMNGOs.png') }}" class="w-16 h-16 object-contain">
                <span class="opacity-0 group-hover:opacity-100">NGOs</span>
            </a>
        @endif

        {{-- Donations --}}
        @if(in_array($role, ['ngo head', 'donation manager']))
            <a href="/sm-donations"
               class="flex items-center gap-4 px-4 py-4 rounded-xl mx-2
               {{ request()->is('sm-donations') ? $active : $inactive }}">
                <img src="{{ asset('images/ServiceManagement/SMDonations.png') }}" class="w-16 h-16 object-contain">
                <span class="opacity-0 group-hover:opacity-100">Donations</span>
            </a>
        @endif

        {{-- Inventory --}}
        @if(in_array($role, ['ngo head', 'donation manager']))
            <a href="/sm-inventory"
               class="flex items-center gap-4 px-4 py-4 rounded-xl mx-2
               {{ request()->is('sm-inventory') ? $active : $inactive }}">
                <img src="{{ asset('images/ServiceManagement/SMInventory.png') }}" class="w-16 h-16 object-contain">
                <span class="opacity-0 group-hover:opacity-100">Inventory</span>
            </a>
        @endif

        {{-- Volunteers --}}
        @if(in_array($role, ['ngo head', 'volunteer manager']))
            <a href="/service-management"
               class="flex items-center gap-4 px-4 py-4 rounded-xl mx-2
               {{ request()->is('service-management') ? $active : $inactive }}">
                <img src="{{ asset('images/ServiceManagement/SMVolunteers.png') }}" class="w-16 h-16 object-contain">
                <span class="opacity-0 group-hover:opacity-100">Volunteers</span>
            </a>
        @endif

        {{-- Reports --}}
        <a href="/sm-reports"
           class="flex items-center gap-4 px-4 py-4 rounded-xl mx-2
           {{ request()->is('sm-reports') ? $active : $inactive }}">
            <img src="{{ asset('images/ServiceManagement/SMReports.png') }}" class="w-16 h-16 object-contain">
            <span class="opacity-0 group-hover:opacity-100">Reports</span>
        </a>

    @endif


    {{-- VOLUNTEER NAV --}}
    @if($role === 'volunteer')

        {{-- Dashboard --}}
        <a href="/volunteer/dashboard"
        class="flex items-center gap-4 px-4 py-4 rounded-xl mx-2
        {{ request()->is('volunteer/dashboard') ? $active : $inactive }}">

            <img src="{{ asset('images/VolunteerIcons/VDash.png') }}" class="w-16 h-16 object-contain">
            <span class="opacity-0 group-hover:opacity-100">Dashboard</span>
        </a>

        {{-- Events --}}
        <a href="/volunteer/events"
        class="flex items-center gap-4 px-6 py-4 rounded-xl mx-2
        {{ request()->is('volunteer/events') ? $active : $inactive }}">

            <img src="{{ asset('images/VolunteerIcons/VEvents.png') }}" class="w-16 h-16 object-contain">
            <span class="opacity-0 group-hover:opacity-100">Events</span>
        </a>

        {{-- Applications --}}
        <a href="/volunteer/applications"
        class="flex items-center gap-4 px-7 py-4 rounded-xl mx-2
        {{ request()->is('volunteer/applications') ? $active : $inactive }}">

            <img src="{{ asset('images/VolunteerIcons/VApplications.png') }}" class="w-16 h-16 object-contain">
            <span class="opacity-0 group-hover:opacity-100">Applications</span>
        </a>

    @endif

    {{-- Logout --}}
    <a href="#" onclick="openLogoutModal()" 
       class="mt-auto flex items-center gap-4 px-4 py-4 rounded-xl mx-2 {{ $inactive }}">
        <img src="{{ asset('images/ServiceManagement/SMLogout.png') }}" class="w-16 h-16 object-contain">
        <span class="opacity-0 group-hover:opacity-100">Logout</span>
    </a>

</div>