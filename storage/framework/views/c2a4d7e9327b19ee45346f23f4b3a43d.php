<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Account</title>

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
                <h2 class="text-xl font-bold text-[#0e243a]">Account Details</h2>
                <a href="<?php echo e(route('ngo-accounts')); ?>"
                   class="bg-gray-200 text-[#0e243a] px-5 py-2.5 rounded-lg font-semibold text-sm">
                    Back to Accounts
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-10 gap-y-6">

                <div>
                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">First Name</div>
                    <div class="text-[#0e243a] text-base"><?php echo e($account->first_name); ?></div>
                </div>

                <div>
                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Last Name</div>
                    <div class="text-[#0e243a] text-base"><?php echo e($account->last_name); ?></div>
                </div>

                <div>
                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Email</div>
                    <div class="text-[#0e243a] text-base"><?php echo e($account->email); ?></div>
                </div>

                <div>
                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Role</div>
                    <div class="text-[#0e243a] text-base"><?php echo e($account->role_label); ?></div>
                </div>

                <div>
                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Contact Number</div>
                    <div class="text-[#0e243a] text-base"><?php echo e($account->contact_number ?? '—'); ?></div>
                </div>

                <div>
                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Birth Date</div>
                    <div class="text-[#0e243a] text-base">
                        <?php echo e($account->birth_date ? \Carbon\Carbon::parse($account->birth_date)->format('M d, Y') : '—'); ?>

                    </div>
                </div>

                <div>
                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Status</div>
                    <div>
                        <?php if($account->status == 1): ?>
                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">Active</span>
                        <?php else: ?>
                            <span class="bg-gray-200 text-gray-600 px-3 py-1 rounded-full text-xs font-semibold">Archived</span>
                        <?php endif; ?>
                    </div>
                </div>

            </div>

            <div class="mt-10 flex gap-3">
                <?php if($account->status == 1): ?>
                    <form action="<?php echo e(route('ngo-accounts.archive', $account->id)); ?>" method="POST"
                          onsubmit="return confirm('Archive this account?')">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PATCH'); ?>
                        <button type="submit"
                                class="bg-[#f2c94c] text-[#0e243a] px-6 py-2.5 rounded-lg font-semibold text-sm">
                            Archive Account
                        </button>
                    </form>
                <?php endif; ?>
            </div>

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
</html><?php /**PATH C:\ProjectSuhay\MEt.A-Project-SUHAY\resources\views/ngo-accounts-show.blade.php ENDPATH**/ ?>