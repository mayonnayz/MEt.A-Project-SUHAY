<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold text-[#0e243a]">
        <?php echo e($title ?? 'Dashboard'); ?>

    </h1>

    <div class="flex items-center gap-3">
        <div class="w-10 h-10 bg-gray-300 rounded-full"></div>

        <p class="text-[#0e243a]">
            <?php echo e(session('user_name', 'Guest')); ?>

        </p>
    </div>
</div><?php /**PATH D:\Acads\MEt.A-Project-SUHAY\resources\views/components/header.blade.php ENDPATH**/ ?>