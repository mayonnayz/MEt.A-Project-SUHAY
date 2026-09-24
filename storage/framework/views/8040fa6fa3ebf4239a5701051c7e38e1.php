<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Account</title>

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

        <div class="bg-[#0e243a] p-4 rounded-2xl flex gap-4 mb-10 flex-wrap">
            <a href="/sm-ngos"
               class="bg-[#f2c94c] text-[#0e243a] px-6 py-2 rounded-full font-semibold">
                Details
            </a>

            <a href="/ngo-members"
               class="bg-[#f2c94c] text-[#0e243a] px-6 py-2 rounded-full font-semibold">
                Members
            </a>

            <a href="/ngo-accounts"
               class="bg-[#f2c94c] text-[#0e243a] px-6 py-2 rounded-full font-semibold">
                Accounts
            </a>
        </div>

        <div class="bg-white rounded-2xl p-8">

            <div class="flex items-center justify-between mb-8">
                <h2 class="text-xl font-bold text-[#0e243a]">Add Account</h2>
                <a href="<?php echo e(route('ngo-accounts')); ?>"
                   class="bg-gray-200 text-[#0e243a] px-5 py-2.5 rounded-lg font-semibold text-sm">
                    Back to Accounts
                </a>
            </div>

            <?php if($errors->any()): ?>
                <div class="mb-6 bg-red-50 text-red-700 text-sm rounded-lg p-4">
                    <ul class="list-disc list-inside">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="<?php echo e(route('ngo-accounts.store')); ?>" method="POST" class="grid grid-cols-1 sm:grid-cols-2 gap-x-10 gap-y-6">
                <?php echo csrf_field(); ?>

                <div>
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1 block">First Name</label>
                    <input type="text" name="first_name" value="<?php echo e(old('first_name')); ?>" required
                           class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm text-[#0e243a] outline-none focus:border-indigo-400">
                </div>

                <div>
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1 block">Last Name</label>
                    <input type="text" name="last_name" value="<?php echo e(old('last_name')); ?>" required
                           class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm text-[#0e243a] outline-none focus:border-indigo-400">
                </div>

                <div>
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1 block">Email</label>
                    <input type="email" name="email" value="<?php echo e(old('email')); ?>" required
                           class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm text-[#0e243a] outline-none focus:border-indigo-400">
                </div>

                <div>
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1 block">Password</label>
                    <input type="password" name="password" required
                           class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm text-[#0e243a] outline-none focus:border-indigo-400">
                </div>

                <div>
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1 block">Role</label>
                    <select name="roles" required
                            class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm text-[#0e243a] bg-white">
                        <option value="" disabled selected>Select a role</option>
                        <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($value); ?>" <?php if(old('roles') == $value): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div>
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1 block">Contact Number</label>
                    <input type="text" name="contact_number" value="<?php echo e(old('contact_number')); ?>"
                           class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm text-[#0e243a] outline-none focus:border-indigo-400">
                </div>

                <div>
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1 block">Birth Date</label>
                    <input type="date" name="birth_date" value="<?php echo e(old('birth_date')); ?>"
                           class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm text-[#0e243a] outline-none focus:border-indigo-400">
                </div>

                <div class="sm:col-span-2 mt-2">
                    <button type="submit"
                            class="bg-[#f2c94c] text-[#0e243a] px-6 py-2.5 rounded-lg font-semibold text-sm">
                        Save Account
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

<?php echo $__env->make('components.logout-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<script>
function openLogoutModal() {
    document.getElementById('logoutModal').classList.remove('hidden');
    document.getElementById('logoutModal').classList.add('flex');
}

function closeLogoutModal() {
    document.getElementById('logoutModal').classList.add('hidden');
}
</script>

</body>
</html><?php /**PATH C:\ProjectSuhay\MEt.A-Project-SUHAY\resources\views/ngo-accounts-create.blade.php ENDPATH**/ ?>