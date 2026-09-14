@php
    $role = strtolower(trim(session('role')));

    $active = 'bg-[#1a3554] text-white';
    $inactive = 'hover:bg-[#f2c94c] hover:text-[#0e243a]';

    function navItemActive($path)
    {
        return request()->is($path);
    }
@endphp

<div
    class="group w-28 hover:w-56 bg-[#0e243a] min-h-screen flex flex-col py-6 text-white rounded-r-3xl transition-all duration-300 overflow-hidden"
>

    {{-- ===================================================== --}}
    {{-- NGO / STAFF NAVIGATION                                --}}
    {{-- ===================================================== --}}

    @if(in_array($role, ['ngo head', 'donation manager', 'volunteer manager']))

        {{-- Dashboard --}}
        <a
            href="/sm-dashboard"
            class="flex items-center gap-4 px-4 py-4 rounded-xl mx-2
            {{ navItemActive('sm-dashboard') ? $active : $inactive }}"
        >
            <img
                src="{{ asset('images/ServiceManagement/SMDash.png') }}"
                class="w-16 h-16 object-contain"
                alt="Dashboard"
            >

            <span class="opacity-0 group-hover:opacity-100 whitespace-nowrap">
                Dashboard
            </span>
        </a>


        {{-- NGOs --}}
        @if($role === 'ngo head')

            <a
                href="/sm-ngos"
                class="flex items-center gap-4 px-4 py-4 rounded-xl mx-2
                {{ navItemActive('sm-ngos') ? $active : $inactive }}"
            >
                <img
                    src="{{ asset('images/ServiceManagement/SMNGOs.png') }}"
                    class="w-16 h-16 object-contain"
                    alt="NGOs"
                >

                <span class="opacity-0 group-hover:opacity-100 whitespace-nowrap">
                    NGOs
                </span>
            </a>

        @endif


        {{-- Donations --}}
        @if(in_array($role, ['ngo head', 'donation manager']))

            <a
                href="/donations"
                class="flex items-center gap-4 px-4 py-4 rounded-xl mx-2
                {{ navItemActive('donations') ? $active : $inactive }}"
            >
                <img
                    src="{{ asset('images/ServiceManagement/SMDonations.png') }}"
                    class="w-16 h-16 object-contain"
                    alt="Donations"
                >

                <span class="opacity-0 group-hover:opacity-100 whitespace-nowrap">
                    Donations
                </span>
            </a>


            {{-- Inventory --}}
            <a
                href="/inventory-master-list"
                class="flex items-center gap-4 px-4 py-4 rounded-xl mx-2
                {{ navItemActive('inventory-master-list') ? $active : $inactive }}"
            >
                <img
                    src="{{ asset('images/ServiceManagement/SMInventory.png') }}"
                    class="w-16 h-16 object-contain"
                    alt="Inventory"
                >

                <span class="opacity-0 group-hover:opacity-100 whitespace-nowrap">
                    Inventory
                </span>
            </a>

        @endif


        {{-- Volunteers --}}
        @if(in_array($role, ['ngo head', 'volunteer manager']))

            <a
                href="/service-management"
                class="flex items-center gap-4 px-4 py-4 rounded-xl mx-2
                {{ navItemActive('service-management') ? $active : $inactive }}"
            >
                <img
                    src="{{ asset('images/ServiceManagement/SMVolunteers.png') }}"
                    class="w-16 h-16 object-contain"
                    alt="Volunteers"
                >

                <span class="opacity-0 group-hover:opacity-100 whitespace-nowrap">
                    Volunteers
                </span>
            </a>

        @endif


        {{-- Reports --}}
        <a
            href="/sm-reports"
            class="flex items-center gap-4 px-4 py-4 rounded-xl mx-2
            {{ navItemActive('sm-reports') ? $active : $inactive }}"
        >
            <img
                src="{{ asset('images/ServiceManagement/SMReports.png') }}"
                class="w-16 h-16 object-contain"
                alt="Reports"
            >

            <span class="opacity-0 group-hover:opacity-100 whitespace-nowrap">
                Reports
            </span>
        </a>

    @endif


    {{-- ===================================================== --}}
    {{-- VOLUNTEER NAVIGATION                                  --}}
    {{-- ===================================================== --}}

    @if($role === 'volunteer')

        {{-- Dashboard --}}
        <a
            href="/volunteer/dashboard"
            class="flex items-center gap-4 px-4 py-4 rounded-xl mx-2
            {{ navItemActive('volunteer/dashboard') ? $active : $inactive }}"
        >
            <img
                src="{{ asset('images/VolunteerIcons/VDash.png') }}"
                class="w-12 h-12 object-contain"
                alt="Dashboard"
            >

            <span class="opacity-0 group-hover:opacity-100 whitespace-nowrap">
                Dashboard
            </span>
        </a>


        {{-- NGOs --}}
        <a
            href="/volunteer/ngos"
            class="flex items-center gap-4 px-4 py-4 rounded-xl mx-2
            {{ navItemActive('volunteer/ngos') ? $active : $inactive }}"
        >
            <img
                src="{{ asset('images/VolunteerIcons/VNGOs.png') }}"
                class="w-12 h-12 object-contain"
                alt="NGOs"
            >

            <span class="opacity-0 group-hover:opacity-100 whitespace-nowrap">
                NGOs
            </span>
        </a>


        {{-- Events --}}
        <a
            href="/volunteer/events"
            class="flex items-center gap-4 px-4 py-4 rounded-xl mx-2
            {{ navItemActive('volunteer/events') ? $active : $inactive }}"
        >
            <img
                src="{{ asset('images/VolunteerIcons/VEvents.png') }}"
                class="w-12 h-12 object-contain"
                alt="Events"
            >

            <span class="opacity-0 group-hover:opacity-100 whitespace-nowrap">
                Events
            </span>
        </a>


        {{-- Applications --}}
        <a
            href="/volunteer/applications"
            class="flex items-center gap-4 px-4 py-4 rounded-xl mx-2
            {{ navItemActive('volunteer/applications') ? $active : $inactive }}"
        >
            <img
                src="{{ asset('images/VolunteerIcons/VApplications.png') }}"
                class="w-12 h-12 object-contain"
                alt="Applications"
            >

            <span class="opacity-0 group-hover:opacity-100 whitespace-nowrap">
                Applications
            </span>
        </a>


{{-- Donations --}}

<a
    href="{{ route('donations.history') }}"
    class="flex items-center gap-4 px-4 py-4 rounded-xl mx-2
    {{ request()->routeIs('donations.history') ? $active : $inactive }}"
>
    <img
        src="{{ asset('images/VolunteerIcons/VDonations.png') }}"
        class="w-12 h-12 object-contain"
        alt="Donations"
    >

    <span class="opacity-0 group-hover:opacity-100 whitespace-nowrap">
        Donations
    </span>
</a>



    @endif


    {{-- ===================================================== --}}
    {{-- LOGOUT                                                 --}}
    {{-- ===================================================== --}}

    <a
        href="#"
        onclick="openLogoutModal(); return false;"
        class="mt-auto flex items-center gap-4 px-4 py-4 rounded-xl mx-2 {{ $inactive }}"
    >
        <img
            src="{{ asset('images/ServiceManagement/SMLogout.png') }}"
            class="w-16 h-16 object-contain"
            alt="Logout"
        >

        <span class="opacity-0 group-hover:opacity-100 whitespace-nowrap">
            Logout
        </span>
    </a>

</div>