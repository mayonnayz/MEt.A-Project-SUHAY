<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold text-[#0e243a]">
        <?php echo e($title ?? 'Dashboard'); ?>

    </h1>

    <div class="flex items-center gap-3">
        <?php if(session('ngo_logo')): ?>
            <img
                src="<?php echo e(session('ngo_logo')); ?>"
                alt="<?php echo e(session('user_name', 'Guest')); ?>"
                class="w-10 h-10 rounded-full object-cover bg-gray-100"
                onerror="this.src='<?php echo e(asset('images/suhayLogo.png')); ?>'"
            >
        <?php else: ?>
            <div class="w-10 h-10 bg-gray-300 rounded-full"></div>
        <?php endif; ?>

        <p class="text-[#0e243a]">
            <?php echo e(session('user_name', 'Guest')); ?>

        </p>
    </div>
</div><?php /**PATH C:\Sysands\MEt.A-Project-SUHAY\resources\views/components/header.blade.php ENDPATH**/ ?>