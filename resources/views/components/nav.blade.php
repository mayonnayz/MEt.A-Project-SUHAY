@php
    $role = strtolower(session('role'));

    function navActive($path) {
        return request()->is($path) 
            ? 'bg-[#f2c94c] text-[#0e243a]' 
            : 'hover:bg-[#f2c94c] hover:text-[#0e243a]';
    }
@endphp

<div class="group w-28 hover:w-56 bg-[#0e243a] min-h-screen flex flex-col py-6 text-white rounded-r-3xl transition-all duration-300 overflow-hidden">

    {{-- Dashboard --}}
    <a href="/sm-dashboard"
       class="flex items-center gap-4 px-4 py-4 rounded-xl mx-2 {{ navActive('sm-dashboard') }}">
        <img src="{{ asset('images/ServiceManagement/SMDash.png') }}" class="w-16 h-16 object-contain">
        <span class="opacity-0 group-hover:opacity-100">Dashboard</span>
    </a>

    {{-- NGOs --}}
    @if($role === 'ngo head')
        <a href="/sm-ngos"
           class="flex items-center gap-4 px-4 py-4 rounded-xl mx-2 {{ navActive('sm-ngos') }}">
            <img src="{{ asset('images/ServiceManagement/SMNGOs.png') }}" class="w-16 h-16 object-contain">
            <span class="opacity-0 group-hover:opacity-100">NGOs</span>
        </a>
    @endif

    {{-- Donations --}}
    @if(in_array($role, ['ngo head', 'donation manager']))
        <a href="/donations"
           class="flex items-center gap-4 px-4 py-4 rounded-xl mx-2 {{ navActive('donations') }}">
            <img src="{{ asset('images/ServiceManagement/SMDonations.png') }}" class="w-16 h-16 object-contain">
            <span class="opacity-0 group-hover:opacity-100">Donations</span>
        </a>
    @endif

    {{-- Inventory --}}
    @if(in_array($role, ['ngo head', 'donation manager']))
        <a href="/sm-inventory"
           class="flex items-center gap-4 px-4 py-4 rounded-xl mx-2 {{ navActive('sm-inventory') }}">
            <img src="{{ asset('images/ServiceManagement/SMInventory.png') }}" class="w-16 h-16 object-contain">
            <span class="opacity-0 group-hover:opacity-100">Inventory</span>
        </a>
    @endif

    {{-- Volunteers --}}
    @if(in_array($role, ['ngo head', 'volunteer manager']))
        <a href="/service-management"
           class="flex items-center gap-4 px-4 py-4 rounded-xl mx-2 {{ navActive('service-management') }}">
            <img src="{{ asset('images/ServiceManagement/SMVolunteers.png') }}" class="w-16 h-16 object-contain">
            <span class="opacity-0 group-hover:opacity-100">Volunteers</span>
        </a>
    @endif

    {{-- Reports --}}
    <a href="/sm-reports"
       class="flex items-center gap-4 px-4 py-4 rounded-xl mx-2 {{ navActive('sm-reports') }}">
        <img src="{{ asset('images/ServiceManagement/SMReports.png') }}" class="w-16 h-16 object-contain">
        <span class="opacity-0 group-hover:opacity-100">Reports</span>
    </a>

    {{-- Logout --}}
    <a href="#" onclick="openLogoutModal()" 
       class="mt-6 flex items-center gap-4 px-4 py-4 rounded-xl mx-2 hover:bg-red-500 hover:text-white">
        <img src="{{ asset('images/ServiceManagement/SMLogout.png') }}" class="w-16 h-16 object-contain">
        <span class="opacity-0 group-hover:opacity-100">Logout</span>
    </a>

</div>