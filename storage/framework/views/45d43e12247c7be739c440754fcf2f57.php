<?php
    $role = strtolower(trim(session('role')));

    $active = 'bg-[#1a3554] text-white';
    $inactive = 'hover:bg-[#f2c94c] hover:text-[#0e243a]';

    function navItemActive($path)
    {
        return request()->is($path);
    }
?>

<div
    class="group w-28 hover:w-56 bg-[#0e243a] min-h-screen flex flex-col py-6 text-white rounded-r-3xl transition-all duration-300 overflow-hidden"
>

    
    
    

    <?php if(in_array($role, ['ngo head', 'donation manager', 'volunteer manager'])): ?>

        
        <a
            href="/sm-dashboard"
            class="flex items-center gap-4 px-4 py-4 rounded-xl mx-2
            <?php echo e(navItemActive('sm-dashboard') ? $active : $inactive); ?>"
        >
            <img
                src="<?php echo e(asset('images/ServiceManagement/SMDash.png')); ?>"
                class="w-16 h-16 object-contain"
                alt="Dashboard"
            >

            <span class="opacity-0 group-hover:opacity-100 whitespace-nowrap">
                Dashboard
            </span>
        </a>


        
        <?php if($role === 'ngo head'): ?>

            <a
                href="/sm-ngos"
                class="flex items-center gap-4 px-4 py-4 rounded-xl mx-2
                <?php echo e(navItemActive('sm-ngos') ? $active : $inactive); ?>"
            >
                <img
                    src="<?php echo e(asset('images/ServiceManagement/SMNGOs.png')); ?>"
                    class="w-16 h-16 object-contain"
                    alt="NGOs"
                >

                <span class="opacity-0 group-hover:opacity-100 whitespace-nowrap">
                    NGOs
                </span>
            </a>

        <?php endif; ?>


        
        <?php if(in_array($role, ['ngo head', 'donation manager'])): ?>

            <a
                href="/donations"
                class="flex items-center gap-4 px-4 py-4 rounded-xl mx-2
                <?php echo e(navItemActive('donations') ? $active : $inactive); ?>"
            >
                <img
                    src="<?php echo e(asset('images/ServiceManagement/SMDonations.png')); ?>"
                    class="w-16 h-16 object-contain"
                    alt="Donations"
                >

                <span class="opacity-0 group-hover:opacity-100 whitespace-nowrap">
                    Donations
                </span>
            </a>


            
            <a
                href="/inventory-master-list"
                class="flex items-center gap-4 px-4 py-4 rounded-xl mx-2
                <?php echo e(navItemActive('inventory-master-list') ? $active : $inactive); ?>"
            >
                <img
                    src="<?php echo e(asset('images/ServiceManagement/SMInventory.png')); ?>"
                    class="w-16 h-16 object-contain"
                    alt="Inventory"
                >

                <span class="opacity-0 group-hover:opacity-100 whitespace-nowrap">
                    Inventory
                </span>
            </a>

        <?php endif; ?>


        
        <?php if(in_array($role, ['ngo head', 'volunteer manager'])): ?>

            <a
                href="/service-management"
                class="flex items-center gap-4 px-4 py-4 rounded-xl mx-2
                <?php echo e(navItemActive('service-management') ? $active : $inactive); ?>"
            >
                <img
                    src="<?php echo e(asset('images/ServiceManagement/SMVolunteers.png')); ?>"
                    class="w-16 h-16 object-contain"
                    alt="Volunteers"
                >

                <span class="opacity-0 group-hover:opacity-100 whitespace-nowrap">
                    Volunteers
                </span>
            </a>

        <?php endif; ?>


        
        <a
            href="/sm-reports"
            class="flex items-center gap-4 px-4 py-4 rounded-xl mx-2
            <?php echo e(navItemActive('sm-reports') ? $active : $inactive); ?>"
        >
            <img
                src="<?php echo e(asset('images/ServiceManagement/SMReports.png')); ?>"
                class="w-16 h-16 object-contain"
                alt="Reports"
            >

            <span class="opacity-0 group-hover:opacity-100 whitespace-nowrap">
                Reports
            </span>
        </a>

    <?php endif; ?>


    
    
    

    <?php if($role === 'volunteer'): ?>

        
        <a
            href="/volunteer/dashboard"
            class="flex items-center gap-4 px-4 py-4 rounded-xl mx-2
            <?php echo e(navItemActive('volunteer/dashboard') ? $active : $inactive); ?>"
        >
            <img
                src="<?php echo e(asset('images/VolunteerIcons/VDash.png')); ?>"
                class="w-12 h-12 object-contain"
                alt="Dashboard"
            >

            <span class="opacity-0 group-hover:opacity-100 whitespace-nowrap">
                Dashboard
            </span>
        </a>


        
        <a
            href="/volunteer/ngos"
            class="flex items-center gap-4 px-4 py-4 rounded-xl mx-2
            <?php echo e(navItemActive('volunteer/ngos') ? $active : $inactive); ?>"
        >
            <img
                src="<?php echo e(asset('images/VolunteerIcons/VNGOs.png')); ?>"
                class="w-12 h-12 object-contain"
                alt="NGOs"
            >

            <span class="opacity-0 group-hover:opacity-100 whitespace-nowrap">
                NGOs
            </span>
        </a>


        
        <a
            href="/volunteer/events"
            class="flex items-center gap-4 px-4 py-4 rounded-xl mx-2
            <?php echo e(navItemActive('volunteer/events') ? $active : $inactive); ?>"
        >
            <img
                src="<?php echo e(asset('images/VolunteerIcons/VEvents.png')); ?>"
                class="w-12 h-12 object-contain"
                alt="Events"
            >

            <span class="opacity-0 group-hover:opacity-100 whitespace-nowrap">
                Events
            </span>
        </a>


        
        <a
            href="/volunteer/applications"
            class="flex items-center gap-4 px-4 py-4 rounded-xl mx-2
            <?php echo e(navItemActive('volunteer/applications') ? $active : $inactive); ?>"
        >
            <img
                src="<?php echo e(asset('images/VolunteerIcons/VApplications.png')); ?>"
                class="w-12 h-12 object-contain"
                alt="Applications"
            >

            <span class="opacity-0 group-hover:opacity-100 whitespace-nowrap">
                Applications
            </span>
        </a>




<a
    href="<?php echo e(route('donations.history')); ?>"
    class="flex items-center gap-4 px-4 py-4 rounded-xl mx-2
    <?php echo e(request()->routeIs('donations.history') ? $active : $inactive); ?>"
>
    <img
        src="<?php echo e(asset('images/VolunteerIcons/VDonations.png')); ?>"
        class="w-12 h-12 object-contain"
        alt="Donations"
    >

    <span class="opacity-0 group-hover:opacity-100 whitespace-nowrap">
        Donations
    </span>
</a>



    <?php endif; ?>


    
    
    

    <a
        href="#"
        onclick="openLogoutModal(); return false;"
        class="mt-auto flex items-center gap-4 px-4 py-4 rounded-xl mx-2 <?php echo e($inactive); ?>"
    >
        <img
            src="<?php echo e(asset('images/ServiceManagement/SMLogout.png')); ?>"
            class="w-16 h-16 object-contain"
            alt="Logout"
        >

        <span class="opacity-0 group-hover:opacity-100 whitespace-nowrap">
            Logout
        </span>
    </a>

</div><?php /**PATH C:\sysands\MEt.A-Project-SUHAY\resources\views/components/nav.blade.php ENDPATH**/ ?>