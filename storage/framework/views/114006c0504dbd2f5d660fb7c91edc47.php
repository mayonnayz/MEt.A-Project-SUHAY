<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
    <title>Donations</title>
    <style>
        .sidebar-gradient { background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%); }
        .active-nav { background-color: #d97706; color: white; border-radius: 9999px; }
    </style>
</head>
<body class="bg-slate-100" style="font-family: 'Poppins', sans-serif;">
    <div class="flex min-h-screen">
        <?php echo $__env->make('components.nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <main class="flex-1 p-8">
              <?php echo $__env->make('components.header', ['title' => 'Donation Management'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-slate-200">
                <div class="p-6 bg-slate-50 border-b border-slate-200">
                    <div class="flex flex-wrap gap-4 items-center">
                        <div class="relative flex-1 min-w-[300px]">
                            <input type="text" placeholder="Search Donors..." class="w-full pl-10 pr-4 py-2 rounded-full border border-slate-300 focus:ring-2 focus:ring-amber-500 outline-none">
                            <i class="fa-solid fa-magnifying-glass absolute left-4 top-3 text-slate-400"></i>
                        </div>
                        <select class="px-4 py-2 rounded-full border border-slate-300 bg-white text-slate-600 outline-none">
                            <option>Unconfirmed</option>
                            <option>Confirmed</option>
                        </select>
                        <select class="px-4 py-2 rounded-full border border-slate-300 bg-white text-slate-600 outline-none">
                            <option>All Types</option>
                            <option>Monetary</option>
                            <option>Non-Monetary</option>
                        </select>
                        <input type="date" class="px-4 py-2 rounded-full border border-slate-300 text-slate-600 outline-none">
                    </div>
                    <div class="mt-4 text-slate-500 text-sm font-semibold">
                        Total Number of Donations:
                        <span class="text-slate-800 text-lg font-bold">
                            <?php echo e($total_donations); ?>

                        </span>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-white text-slate-800 uppercase text-sm border-b">
                            <tr>
                                <th class="px-6 py-4 font-bold">#</th>
                                <th class="px-6 py-4 font-bold">Name</th>
                                <th class="px-6 py-4 font-bold">Donation Type</th>
                                <th class="px-6 py-4 font-bold">Date</th>
                                <th class="px-6 py-4 font-bold text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php $__currentLoopData = $donations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $donation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4 text-slate-500">
                                        <?php echo e($index + 1); ?>

                                    </td>

                                    <td class="px-6 py-4 font-medium text-slate-800">
                                       <?php echo e(($donation->accounts['first_name'] ?? '') . ' ' . ($donation->accounts['last_name'] ?? '') ?: 'Unknown'); ?>

                                      
                                    </td>

                                    <td class="px-6 py-4 text-slate-600">
                                        <?php echo e($donation->type); ?>

                                    </td>

                                    <td class="px-6 py-4 text-slate-600">
                                       <?php echo e(\Carbon\Carbon::parse($donation->date)->format('Y-m-d')); ?>

                                    </td>

                                    <td class="px-6 py-4 text-center">
                                        <button 
                                            onclick="openModal(this)"
                                            data-name="<?php echo e(($donation->accounts['first_name'] ?? '') . ' ' . ($donation->accounts['last_name'] ?? '')); ?>"
                                            data-date="<?php echo e(\Carbon\Carbon::parse($donation->date)->format('Y-m-d')); ?>"
                                            data-type="<?php echo e($donation->type); ?>"
                                            data-payment="<?php echo e($donation->payment_type); ?>"
                                            data-ref="<?php echo e($donation->reference_number); ?>"
                                            class="bg-amber-500 hover:bg-amber-600 text-white px-6 py-1 rounded-lg font-bold"
                                        >
                                            View
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
    <?php echo $__env->make('components.donation-view-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php echo $__env->make('components.logout-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<script>
function openModal(button) {
    const modal = document.getElementById('donationModal');
    if (!modal) return;

    document.getElementById('modalName').innerText = "Donor: " + button.dataset.name;
    document.getElementById('modalDate').innerText = "Date: " + button.dataset.date;
    document.getElementById('modalType').innerText = button.dataset.type;
    document.getElementById('modalPayment').innerText = button.dataset.payment;
    document.getElementById('modalRef').value = button.dataset.ref;

    modal.classList.remove('hidden');
}

function closeModal() {
    const modal = document.getElementById('donationModal');
    if (!modal) return;

    modal.classList.add('hidden');
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
</html><?php /**PATH C:\sysands\MEt.A-Project-SUHAY-main\MEt.A-Project-SUHAY-main\resources\views/donations.blade.php ENDPATH**/ ?>