<nav class="bg-white shadow-md">
    <div class="w-full px-8 py-6 flex justify-between items-center">
        <div class="flex items-center gap-0 ml-2">
            <a href="/">
                <img src="<?php echo e(asset('images/suhayLogo.png')); ?>" class="h-16 w-auto">
                <h1 class="text-4xl font-bold text-[#0e243a]"></h1>
            </a>
        </div>

        <div class="hidden md:flex items-center gap-10 font-bold text-xl mr-2">

            <a href="/"
               class="px-4 py-2 rounded-lg transition
               <?php echo e(request()->is('/') ? 'bg-[#f2c94c] text-white' : 'text-[#0e243a] hover:bg-[#f2c94c] hover:text-white'); ?>">
               Home
            </a>

            <a href="/about"
               class="px-4 py-2 rounded-lg transition
               <?php echo e(request()->is('about') ? 'bg-[#f2c94c] text-white' : 'text-[#0e243a] hover:bg-[#f2c94c] hover:text-white'); ?>">
               About
            </a>

            <a href="/ngos"
               class="px-4 py-2 rounded-lg transition
               <?php echo e(request()->is('ngos') ? 'bg-[#f2c94c] text-white' : 'text-[#0e243a] hover:bg-[#f2c94c] hover:text-white'); ?>">
               NGOs
            </a>

            <a href="#"
               class="px-4 py-2 rounded-lg transition
               <?php echo e(request()->is('impact') ? 'bg-[#f2c94c] text-white' : 'text-[#0e243a] hover:bg-[#f2c94c] hover:text-white'); ?>">
               Impact
            </a>

            <a href="/volunteer-page"
               class="px-4 py-2 rounded-lg transition
               <?php echo e(request()->is('volunteer-page') ? 'bg-[#f2c94c] text-white' : 'text-[#0e243a] hover:bg-[#f2c94c] hover:text-white'); ?>">
               Get Involved
            </a>

            <a href="#"
               class="px-4 py-2 rounded-lg transition
               <?php echo e(request()->is('donate') ? 'bg-[#f2c94c] text-white' : 'text-[#0e243a] hover:bg-[#f2c94c] hover:text-white'); ?>">
               Donate
            </a>

            <a href="/login-page"
               class="px-4 py-2 rounded-lg transition
               <?php echo e(request()->is('login-page') ? 'bg-[#f2c94c] text-white' : 'text-[#0e243a] hover:bg-[#f2c94c] hover:text-white'); ?>">
               Log in
            </a>

        </div>
    </div>
</nav><?php /**PATH D:\Acads\MEt.A-Project-SUHAY\resources\views/components/navbar.blade.php ENDPATH**/ ?>