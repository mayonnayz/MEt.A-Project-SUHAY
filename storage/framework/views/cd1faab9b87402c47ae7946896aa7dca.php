<?php
    $role = strtolower(session('role'));

    function navActive($path) {
        return request()->is($path) 
            ? 'bg-[#f2c94c] text-[#0e243a]' 
            : 'hover:bg-[#f2c94c] hover:text-[#0e243a]';
    }

    $role = strtolower(session('role'));

    $active = 'bg-[#1a3554]';
    $inactive = 'hover:bg-[#f2c94c] hover:text-[#0e243a]';
?>

<div class="group w-28 hover:w-56 bg-[#0e243a] min-h-screen flex flex-col py-6 text-white rounded-r-3xl transition-all duration-300 overflow-hidden">

    <!-- 
    <a href="/sm-dashboard"
       class="flex items-center gap-4 px-4 py-4 rounded-xl mx-2 <?php echo e(navActive('sm-dashboard')); ?>">
        <img src="<?php echo e(asset('images/ServiceManagement/SMDash.png')); ?>" class="w-16 h-16 object-contain">
        <span class="opacity-0 group-hover:opacity-100">Dashboard</span>
    </a>

    
    <?php if($role === 'ngo head'): ?>
        <a href="/sm-ngos"
           class="flex items-center gap-4 px-4 py-4 rounded-xl mx-2 <?php echo e(navActive('sm-ngos')); ?>">
            <img src="<?php echo e(asset('images/ServiceManagement/SMNGOs.png')); ?>" class="w-16 h-16 object-contain">
            <span class="opacity-0 group-hover:opacity-100">NGOs</span>
        </a>
    <?php endif; ?>

    
    <?php if(in_array($role, ['ngo head', 'donation manager'])): ?>
        <a href="/donations"
           class="flex items-center gap-4 px-4 py-4 rounded-xl mx-2 <?php echo e(navActive('donations')); ?>">
            <img src="<?php echo e(asset('images/ServiceManagement/SMDonations.png')); ?>" class="w-16 h-16 object-contain">
            <span class="opacity-0 group-hover:opacity-100">Donations</span>
        </a>
    <?php endif; ?>

    
    <?php if(in_array($role, ['ngo head', 'donation manager'])): ?>
        <a href="/sm-inventory"
           class="flex items-center gap-4 px-4 py-4 rounded-xl mx-2 <?php echo e(navActive('sm-inventory')); ?>">
            <img src="<?php echo e(asset('images/ServiceManagement/SMInventory.png')); ?>" class="w-16 h-16 object-contain">
            <span class="opacity-0 group-hover:opacity-100">Inventory</span>
        </a>
    <?php endif; ?>

    
    <?php if(in_array($role, ['ngo head', 'volunteer manager'])): ?>
        <a href="/service-management"
           class="flex items-center gap-4 px-4 py-4 rounded-xl mx-2 <?php echo e(navActive('service-management')); ?>">
            <img src="<?php echo e(asset('images/ServiceManagement/SMVolunteers.png')); ?>" class="w-16 h-16 object-contain">
            <span class="opacity-0 group-hover:opacity-100">Volunteers</span>
        </a>
    <?php endif; ?>

    
    <a href="/sm-reports"
       class="flex items-center gap-4 px-4 py-4 rounded-xl mx-2 <?php echo e(navActive('sm-reports')); ?>">
        <img src="<?php echo e(asset('images/ServiceManagement/SMReports.png')); ?>" class="w-16 h-16 object-contain">
        <span class="opacity-0 group-hover:opacity-100">Reports</span>
    </a>

    
    <a href="#" onclick="openLogoutModal()" 
       class="mt-6 flex items-center gap-4 px-4 py-4 rounded-xl mx-2 hover:bg-red-500 hover:text-white">
        <img src="<?php echo e(asset('images/ServiceManagement/SMLogout.png')); ?>" class="w-16 h-16 object-contain">
        <span class="opacity-0 group-hover:opacity-100">Logout</span>
    </a> -->

    
    
    
    <?php if(in_array($role, ['ngo head', 'donation manager', 'volunteer manager'])): ?>

        
        <a href="/sm-dashboard"
           class="flex items-center gap-4 px-4 py-4 rounded-xl mx-2
           <?php echo e(request()->is('sm-dashboard') ? $active : $inactive); ?>">
            <img src="<?php echo e(asset('images/ServiceManagement/SMDash.png')); ?>" class="w-16 h-16 object-contain">
            <span class="opacity-0 group-hover:opacity-100">Dashboard</span>
        </a>

        
        <?php if($role === 'ngo head'): ?>
            <a href="/sm-ngos"
               class="flex items-center gap-4 px-4 py-4 rounded-xl mx-2
               <?php echo e(request()->is('sm-ngos') ? $active : $inactive); ?>">
                <img src="<?php echo e(asset('images/ServiceManagement/SMNGOs.png')); ?>" class="w-16 h-16 object-contain">
                <span class="opacity-0 group-hover:opacity-100">NGOs</span>
            </a>
        <?php endif; ?>

        
        <?php if(in_array($role, ['ngo head', 'donation manager'])): ?>
            <a href="/sm-donations"
               class="flex items-center gap-4 px-4 py-4 rounded-xl mx-2
               <?php echo e(request()->is('sm-donations') ? $active : $inactive); ?>">
                <img src="<?php echo e(asset('images/ServiceManagement/SMDonations.png')); ?>" class="w-16 h-16 object-contain">
                <span class="opacity-0 group-hover:opacity-100">Donations</span>
            </a>
        <?php endif; ?>

        
        <?php if(in_array($role, ['ngo head', 'donation manager'])): ?>
            <a href="/sm-inventory"
               class="flex items-center gap-4 px-4 py-4 rounded-xl mx-2
               <?php echo e(request()->is('sm-inventory') ? $active : $inactive); ?>">
                <img src="<?php echo e(asset('images/ServiceManagement/SMInventory.png')); ?>" class="w-16 h-16 object-contain">
                <span class="opacity-0 group-hover:opacity-100">Inventory</span>
            </a>
        <?php endif; ?>

        
        <?php if(in_array($role, ['ngo head', 'volunteer manager'])): ?>
            <a href="/service-management"
               class="flex items-center gap-4 px-4 py-4 rounded-xl mx-2
               <?php echo e(request()->is('service-management') ? $active : $inactive); ?>">
                <img src="<?php echo e(asset('images/ServiceManagement/SMVolunteers.png')); ?>" class="w-16 h-16 object-contain">
                <span class="opacity-0 group-hover:opacity-100">Volunteers</span>
            </a>
        <?php endif; ?>

        
        <a href="/sm-reports"
           class="flex items-center gap-4 px-4 py-4 rounded-xl mx-2
           <?php echo e(request()->is('sm-reports') ? $active : $inactive); ?>">
            <img src="<?php echo e(asset('images/ServiceManagement/SMReports.png')); ?>" class="w-16 h-16 object-contain">
            <span class="opacity-0 group-hover:opacity-100">Reports</span>
        </a>

    <?php endif; ?>


    
    <?php if($role === 'volunteer'): ?>

        
        <a href="/volunteer/dashboard"
        class="flex items-center gap-4 px-4 py-4 rounded-xl mx-2
        <?php echo e(request()->is('volunteer/dashboard') ? $active : $inactive); ?>">

            <img src="<?php echo e(asset('images/VolunteerIcons/VDash.png')); ?>" class="w-16 h-16 object-contain">
            <span class="opacity-0 group-hover:opacity-100">Dashboard</span>
        </a>

        
        <a href="/volunteer/events"
        class="flex items-center gap-4 px-6 py-4 rounded-xl mx-2
        <?php echo e(request()->is('volunteer/events') ? $active : $inactive); ?>">

            <img src="<?php echo e(asset('images/VolunteerIcons/VEvents.png')); ?>" class="w-16 h-16 object-contain">
            <span class="opacity-0 group-hover:opacity-100">Events</span>
        </a>

        
        <a href="/volunteer/applications"
        class="flex items-center gap-4 px-7 py-4 rounded-xl mx-2
        <?php echo e(request()->is('volunteer/applications') ? $active : $inactive); ?>">

            <img src="<?php echo e(asset('images/VolunteerIcons/VApplications.png')); ?>" class="w-16 h-16 object-contain">
            <span class="opacity-0 group-hover:opacity-100">Applications</span>
        </a>

    <?php endif; ?>

    
    <a href="#" onclick="openLogoutModal()" 
       class="mt-auto flex items-center gap-4 px-4 py-4 rounded-xl mx-2 <?php echo e($inactive); ?>">
        <img src="<?php echo e(asset('images/ServiceManagement/SMLogout.png')); ?>" class="w-16 h-16 object-contain">
        <span class="opacity-0 group-hover:opacity-100">Logout</span>
    </a>

</div><?php /**PATH D:\Acads\MEt.A-Project-SUHAY\resources\views/components/nav.blade.php ENDPATH**/ ?>