<nav class="bg-white shadow-md">
    <div class="w-full px-8 py-6 flex justify-between items-center">

        <!-- LOGO -->
        <div class="flex items-center ml-2">
            <a href="<?php echo e(route('home')); ?>">
                <img src="<?php echo e(asset('images/suhayLogo.png')); ?>" class="h-16 w-auto">
            </a>
        </div>

        <!-- MENU -->
        <div class="hidden md:flex items-center gap-10 font-bold text-xl mr-2">

            <a href="<?php echo e(route('home')); ?>"
               class="px-4 py-2 rounded-lg transition
               <?php echo e(request()->is('/') ? 'bg-[#f2c94c] text-white' : 'text-[#0e243a] hover:bg-[#f2c94c] hover:text-white'); ?>">
               Home
            </a>

            <a href="<?php echo e(route('about')); ?>"
               class="px-4 py-2 rounded-lg transition
               <?php echo e(request()->is('about') ? 'bg-[#f2c94c] text-white' : 'text-[#0e243a] hover:bg-[#f2c94c] hover:text-white'); ?>">
               About
            </a>

            <a href="<?php echo e(route('ngos')); ?>"
               class="px-4 py-2 rounded-lg transition
               <?php echo e(request()->is('ngos') ? 'bg-[#f2c94c] text-white' : 'text-[#0e243a] hover:bg-[#f2c94c] hover:text-white'); ?>">
               NGOs
            </a>

            <a href="<?php echo e(route('impact')); ?>"
               class="px-4 py-2 rounded-lg transition
               <?php echo e(request()->is('impact') ? 'bg-[#f2c94c] text-white' : 'text-[#0e243a] hover:bg-[#f2c94c] hover:text-white'); ?>">
               Impact
            </a>

            <a href="<?php echo e(route('volunteer.page')); ?>"
               class="px-4 py-2 rounded-lg transition
               <?php echo e(request()->is('volunteer-page') ? 'bg-[#f2c94c] text-white' : 'text-[#0e243a] hover:bg-[#f2c94c] hover:text-white'); ?>">
               Get Involved
            </a>

            <a href="<?php echo e(route('donate')); ?>"
               class="px-4 py-2 rounded-lg transition
               <?php echo e(request()->is('donate') ? 'bg-[#f2c94c] text-white' : 'text-[#0e243a] hover:bg-[#f2c94c] hover:text-white'); ?>">
               Donate
            </a>

            <a href="<?php echo e(route('login.page')); ?>"
               class="px-4 py-2 rounded-lg transition
               <?php echo e(request()->is('login-page') ? 'bg-[#f2c94c] text-white' : 'text-[#0e243a] hover:bg-[#f2c94c] hover:text-white'); ?>">
               Log in
            </a>

        </div>
    </div>
</nav><?php /**PATH C:\sysands\MEt.A-Project-SUHAY-main\MEt.A-Project-SUHAY-main\resources\views/components/navbar.blade.php ENDPATH**/ ?>