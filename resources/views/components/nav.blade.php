    <div class="group w-28 hover:w-56 bg-[#0e243a] min-h-screen flex flex-col py-6 text-white rounded-r-3xl transition-all duration-300 overflow-hidden">

        <a href="/sm-dashboard"
           class="flex items-center gap-4 px-4 py-4 hover:bg-[#f2c94c] hover:text-[#0e243a] rounded-xl mx-2">
            <img src="{{ asset('images/ServiceManagement/SMDash.png') }}" class="w-16 h-16 object-contain">
            <span class="opacity-0 group-hover:opacity-100">Dashboard</span>
        </a>

        <a href="/sm-ngos"
           class="flex items-center gap-4 px-4 py-4 hover:bg-[#f2c94c] hover:text-[#0e243a] rounded-xl mx-2">
            <img src="{{ asset('images/ServiceManagement/SMNGOs.png') }}" class="w-16 h-16 object-contain">
            <span class="opacity-0 group-hover:opacity-100">NGOs</span>
        </a>

        <a href="/sm-donations"
           class="flex items-center gap-4 px-4 py-4 hover:bg-[#f2c94c] hover:text-[#0e243a] rounded-xl mx-2">
            <img src="{{ asset('images/ServiceManagement/SMDonations.png') }}" class="w-16 h-16 object-contain">
            <span class="opacity-0 group-hover:opacity-100">Donations</span>
        </a>

        <a href="/sm-inventory"
           class="flex items-center gap-4 px-4 py-4 hover:bg-[#f2c94c] hover:text-[#0e243a] rounded-xl mx-2">
            <img src="{{ asset('images/ServiceManagement/SMInventory.png') }}" class="w-16 h-16 object-contain">
            <span class="opacity-0 group-hover:opacity-100">Inventory</span>
        </a>

        <a href="/service-management"
           class="flex items-center gap-4 px-4 py-4 bg-[#1a3554] rounded-xl mx-2">
            <img src="{{ asset('images/ServiceManagement/SMVolunteers.png') }}" class="w-16 h-16 object-contain">
            <span class="opacity-0 group-hover:opacity-100">Volunteers</span>
        </a>

        <a href="/sm-reports"
           class="flex items-center gap-4 px-4 py-4 hover:bg-[#f2c94c] hover:text-[#0e243a] rounded-xl mx-2">
            <img src="{{ asset('images/ServiceManagement/SMReports.png') }}" class="w-16 h-16 object-contain">
            <span class="opacity-0 group-hover:opacity-100">Reports</span>
        </a>

        <a href="#" onclick="openLogoutModal()" 
           class="mt-auto flex items-center gap-4 px-4 py-4 hover:bg-[#f2c94c] hover:text-[#0e243a] rounded-xl mx-2">
            <img src="{{ asset('images/ServiceManagement/SMLogout.png') }}" class="w-16 h-16 object-contain">
            <span class="opacity-0 group-hover:opacity-100">Logout</span>
        </a>
    </div>