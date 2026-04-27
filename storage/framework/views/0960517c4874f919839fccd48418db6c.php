<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NGO Profile</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>

<body class="bg-gray-200">

<div class="flex">
    <?php echo $__env->make('components.nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="flex-1 p-8">
        <?php echo $__env->make('components.header', ['title' => 'NGO Profile'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <!-- 🔥 TABS -->
        <div class="bg-[#0e243a] p-4 rounded-2xl flex gap-4 mb-6 flex-wrap">

            <!-- DETAILS (ACTIVE) -->
            <a href="/sm-ngos"
               class="bg-gray-200 text-[#0e243a] px-6 py-2 rounded-full font-semibold">
                Details
            </a>

            <!-- MEMBERS -->
            <a href="/ngo-members"
               class="bg-[#f2c94c] text-[#0e243a] px-6 py-2 rounded-full font-semibold hover:opacity-90 transition">
                Members
            </a>

        </div>

        <div class="mt-10">

            <div class="text-center mb-12">

                <div class="flex justify-center items-center gap-16 mb-6 flex-wrap">
                    
                    <img src="<?php echo e(asset('images/suhayLogo.png')); ?>" class="w-40 h-40 object-contain">

                    <div class="w-px h-28 bg-gradient-to-b from-[#0e243a] to-[#f2c94c] hidden md:block"></div>

                    <img src="<?php echo e(asset('images/ngo-logo-placeholder.png')); ?>" class="w-40 h-40 object-contain">
                </div>

                <h1 class="text-3xl md:text-4xl font-extrabold text-[#0e243a] mb-2">
                    <?php echo e($ngo->name ?? '------'); ?>

                </h1>

                <p class="text-base md:text-lg text-gray-600 max-w-2xl mx-auto leading-relaxed">
                    <?php echo e($ngo->description ?? '------'); ?>

                </p>

            </div>

            <div class="max-w-5xl mx-auto space-y-12">

                <div>
                    <h3 class="text-xl font-bold text-[#0e243a] mb-4 border-b-2 border-[#0e243a]/20 pb-3">
                        Contact Information
                    </h3>

                    <div class="grid md:grid-cols-2 gap-8 text-lg">
                        <div>
                            <p class="text-gray-500">Email Address</p>
                            <p class="font-bold text-[#0e243a] text-xl">------</p>
                        </div>

                        <div>
                            <p class="text-gray-500">Contact Number</p>
                            <p class="font-bold text-[#0e243a] text-xl">
                                <?php echo e($ngo->contact_number ?? '------'); ?>

                            </p>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-xl font-bold text-[#0e243a] mb-4 border-b-2 border-[#0e243a]/20 pb-3">
                        Payment Details
                    </h3>

                    <div class="grid md:grid-cols-2 gap-8 text-lg">

                        <div>
                            <p class="text-gray-500">Bank Account</p>
                            <p class="font-bold text-[#0e243a] text-xl">
                                <?php echo e($ngo->bank_account ?? '------'); ?>

                            </p>
                            <p class="text-gray-700"><?php echo e($ngo->name ?? '------'); ?></p>
                        </div>

                        <div>
                            <p class="text-gray-500">GCash</p>
                            <p class="font-bold text-[#0e243a] text-xl">
                                <?php echo e($ngo->gcash ?? '------'); ?>

                            </p>
                            <p class="text-gray-700"><?php echo e($ngo->name ?? '------'); ?></p>
                        </div>

                    </div>
                </div>

                <div>
                    <h3 class="text-xl font-bold text-[#0e243a] mb-4 border-b-2 border-[#0e243a]/20 pb-3">
                        Complete Address
                    </h3>

                    <p class="font-bold text-[#0e243a] text-xl leading-relaxed">
                        <?php echo e($ngo->address ?? '------'); ?>

                    </p>
                </div>

            </div>

            <div class="flex justify-end mt-20">
                <button onclick="openEditNgoModal()"
                    class="bg-[#f2c94c] text-[#0e243a] px-10 py-4 rounded-xl text-lg font-bold shadow hover:bg-[#e6bd43] transition">
                    Edit Organization Details
                </button>
            </div>

        </div>
    </div>
</div>

<?php echo $__env->make('components.logout-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('components.edit-ngo-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<script>
function openEditNgoModal() {
    const modal = document.getElementById('editNgoModal');
    const box = document.getElementById('ngoModalBox');

    modal.classList.remove('hidden');
    modal.classList.add('flex');

    setTimeout(() => {
        modal.classList.remove('opacity-0');
        box.classList.remove('scale-95');
    }, 10);
}

function closeEditNgoModal() {
    const modal = document.getElementById('editNgoModal');
    const box = document.getElementById('ngoModalBox');

    box.classList.add('scale-95');
    modal.classList.add('opacity-0');

    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }, 150);
}

function openLogoutModal() {
    const modal = document.getElementById('logoutModal');

    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeLogoutModal() {
    const modal = document.getElementById('logoutModal');

    modal.classList.add('hidden');
    modal.classList.remove('flex');
}
</script>

</body>
</html><?php /**PATH D:\Acads\MEt.A-Project-SUHAY\resources\views/ngo_management.blade.php ENDPATH**/ ?>