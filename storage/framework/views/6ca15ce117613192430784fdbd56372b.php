
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap"
          rel="stylesheet">

    <title>Donations</title>

    <style>
        .sidebar-gradient {
            background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
        }

        .active-nav {
            background-color: #d97706;
            color: white;
            border-radius: 9999px;
        }
    </style>
</head>

<body class="bg-slate-100" style="font-family: 'Poppins', sans-serif;">

<div class="flex min-h-screen">

    <?php echo $__env->make('components.nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <main class="flex-1 p-8">

        <?php echo $__env->make('components.header', ['title' => 'Donation Management'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-slate-200">

            <!-- FILTERS -->
            <div class="p-6 bg-slate-50 border-b border-slate-200">

                <div class="flex flex-wrap gap-4 items-center">

                    <!-- SEARCH -->
                    <div class="relative flex-1 min-w-[300px]">

                        <input
                            type="text"
                            id="searchInput"
                            placeholder="Search Donors..."
                            class="w-full pl-10 pr-4 py-2 rounded-full border border-slate-300
                                   focus:ring-2 focus:ring-amber-500 outline-none"
                        >

                        <i class="fa-solid fa-magnifying-glass
                                  absolute left-4 top-3 text-slate-400"></i>

                    </div>

                    <!-- STATUS FILTER -->
                    <select
                        id="statusFilter"
                        class="px-4 py-2 rounded-full border border-slate-300
                               bg-white text-slate-600 outline-none"
                    >
                        <option value="all">All Status</option>
                        <option value="Pending">Pending</option>
                        <option value="Confirmed">Confirmed</option>
                        <option value="Rejected">Rejected</option>
                    </select>

                    <!-- TYPE FILTER -->
                    <select
                        id="typeFilter"
                        class="px-4 py-2 rounded-full border border-slate-300
                               bg-white text-slate-600 outline-none"
                    >
                        <option value="all">All Types</option>
                        <option value="Monetary">Monetary</option>
                        <option value="Consumable">Consumable</option>
                        <option value="Non-Consumable">Non-Consumable</option>
                    </select>

                    <!-- DATE FILTER -->
                    <input
                        type="date"
                        id="dateFilter"
                        class="px-4 py-2 rounded-full border border-slate-300
                               text-slate-600 outline-none"
                    >

                </div>

                <!-- TOTAL -->
                <div class="mt-4 text-slate-500 text-sm font-semibold">

                    Total Number of Donations:

                    <span
                        id="totalDonations"
                        class="text-slate-800 text-lg font-bold"
                    >
                        <?php echo e($total_donations); ?>

                    </span>

                </div>

            </div>


            <!-- DONATION TABLE -->
            <div class="overflow-x-auto">

                <table class="w-full text-left">

                    <thead class="bg-white text-slate-800 uppercase text-sm border-b">

                        <tr>

                            <th class="px-6 py-4 font-bold">
                                #
                            </th>

                            <th class="px-6 py-4 font-bold">
                                Name
                            </th>

                            <th class="px-6 py-4 font-bold">
                                Donation Type
                            </th>

                            <th class="px-6 py-4 font-bold">
                                Description
                            </th>

                            <th class="px-6 py-4 font-bold">
                                Amount
                            </th>

                            <th class="px-6 py-4 font-bold">
                                Unit
                            </th>

                            <th class="px-6 py-4 font-bold">
                                Date
                            </th>

                            <th class="px-6 py-4 font-bold">
                                Status
                            </th>

                            <th class="px-6 py-4 font-bold text-center">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody
                        id="donationTableBody"
                        class="divide-y divide-slate-100"
                    >

                        <?php $__empty_1 = true; $__currentLoopData = $donations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $donation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                            <tr
                                class="donation-row hover:bg-slate-50 transition-colors"

                                data-name="<?php echo e(strtolower($donation->donor_name ?? 'Unknown')); ?>"

                                data-type="<?php echo e($donation->type ?? ''); ?>"

                                data-status="<?php echo e($donation->status ?? ''); ?>"

                                data-date="<?php echo e($donation->date ?? ''); ?>"
                            >

                                <!-- NUMBER -->
                                <td class="px-6 py-4 text-slate-500">
                                    <?php echo e($index + 1); ?>

                                </td>


                                <!-- DONOR NAME -->
                                <td class="px-6 py-4 font-medium text-slate-800">

                                    <?php echo e($donation->donor_name ?? 'Unknown'); ?>


                                </td>


                                <!-- DONATION TYPE -->
                                <td class="px-6 py-4 text-slate-600">

                                    <?php echo e($donation->type ?? 'N/A'); ?>


                                </td>


                                <!-- DESCRIPTION -->
                                <td class="px-6 py-4 text-slate-600">

                                    <?php echo e($donation->description ?? 'N/A'); ?>


                                </td>


                               <!-- AMOUNT -->
                                <td class="px-6 py-4 text-slate-600">

                                    <?php if(in_array(strtoupper($donation->type ?? ''), ['MONETARY', 'ONLINE_MONETARY'])): ?>

                                        <?php if(!is_null($donation->amount)): ?>
                                            ₱<?php echo e(number_format((float) $donation->amount, 2)); ?>

                                        <?php else: ?>
                                            N/A
                                        <?php endif; ?>

                                    <?php else: ?>

                                        <?php if(!is_null($donation->amount)): ?>
                                            <?php echo e(number_format((float) $donation->amount, 2)); ?>

                                        <?php else: ?>
                                            N/A
                                        <?php endif; ?>

                                    <?php endif; ?>

                                </td>

                                <!-- UNIT -->
                                <td class="px-6 py-4 text-slate-600">

                                    <?php echo e($donation->unit ?? 'N/A'); ?>


                                </td>


                                <!-- DATE -->
                                <td class="px-6 py-4 text-slate-600">

                                    <?php if($donation->date): ?>

                                        <?php echo e(\Carbon\Carbon::parse($donation->date)->format('Y-m-d')); ?>


                                    <?php else: ?>

                                        N/A

                                    <?php endif; ?>

                                </td>


                                <!-- STATUS -->
                                <td class="px-6 py-4">

                                    <?php if(($donation->status ?? '') === 'Pending'): ?>

                                        <span class="px-3 py-1 rounded-full text-xs font-bold
                                                     bg-yellow-100 text-yellow-700">
                                            Pending
                                        </span>

                                    <?php elseif(($donation->status ?? '') === 'Confirmed'): ?>

                                        <span class="px-3 py-1 rounded-full text-xs font-bold
                                                     bg-green-100 text-green-700">
                                            Confirmed
                                        </span>

                                    <?php elseif(($donation->status ?? '') === 'Rejected'): ?>

                                        <span class="px-3 py-1 rounded-full text-xs font-bold
                                                     bg-red-100 text-red-700">
                                            Rejected
                                        </span>

                                    <?php else: ?>

                                        <span class="px-3 py-1 rounded-full text-xs font-bold
                                                     bg-slate-100 text-slate-600">
                                            <?php echo e($donation->status ?? 'Unknown'); ?>

                                        </span>

                                    <?php endif; ?>

                                </td>


                                <!-- VIEW BUTTON -->
                                <td class="px-6 py-4 text-center">

                                    <button
                                        onclick="openModal(this)"

                                        data-name="<?php echo e($donation->donor_name ?? 'Unknown'); ?>"

                                        data-date="<?php echo e($donation->date
                                            ? \Carbon\Carbon::parse($donation->date)->format('Y-m-d')
                                            : 'N/A'); ?>"

                                        data-type="<?php echo e($donation->type ?? 'N/A'); ?>"

                                        data-description="<?php echo e($donation->description ?? 'N/A'); ?>"

                                        data-amount="<?php echo e($donation->amount ?? ''); ?>"

                                        data-unit="<?php echo e($donation->unit ?? 'N/A'); ?>"

                                        data-source="<?php echo e($donation->source ?? 'N/A'); ?>"

                                        data-status="<?php echo e($donation->status ?? 'N/A'); ?>"

                                        class="bg-amber-500 hover:bg-amber-600
                                               text-white px-6 py-1 rounded-lg font-bold"
                                    >
                                        View
                                    </button>

                                </td>

                            </tr>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                            <tr>

                                <td
                                    colspan="9"
                                    class="px-6 py-10 text-center text-slate-500"
                                >
                                    No donations found.
                                </td>

                            </tr>

                        <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </main>

</div>


<?php echo $__env->make('components.donation-view-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php echo $__env->make('components.logout-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>


<script>

    // =========================================================
    // OPEN DONATION MODAL
    // =========================================================

    function openModal(button) {

        const modal = document.getElementById('donationModal');

        if (!modal) {
            return;
        }

        const name = button.dataset.name || 'Unknown';
        const date = button.dataset.date || 'N/A';
        const type = button.dataset.type || 'N/A';
        const description = button.dataset.description || 'N/A';
        const amount = button.dataset.amount || '';
        const unit = button.dataset.unit || 'N/A';
        const source = button.dataset.source || 'N/A';
        const status = button.dataset.status || 'N/A';


        // DONOR
        const modalName = document.getElementById('modalName');

        if (modalName) {
            modalName.innerText = 'Donor: ' + name;
        }


        // DATE
        const modalDate = document.getElementById('modalDate');

        if (modalDate) {
            modalDate.innerText = 'Date: ' + date;
        }


        // TYPE
        const modalType = document.getElementById('modalType');

        if (modalType) {
            modalType.innerText = type;
        }


        // DESCRIPTION
        const modalDescription =
            document.getElementById('modalDescription');

        if (modalDescription) {
            modalDescription.innerText = description;
        }


        // AMOUNT
        const modalAmount =
            document.getElementById('modalAmount');

        if (modalAmount) {

            if (amount !== '') {

                modalAmount.innerText =
                    '₱' +
                    parseFloat(amount).toLocaleString('en-PH', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });

            } else {

                modalAmount.innerText = 'N/A';

            }

        }


        // UNIT
        const modalUnit =
            document.getElementById('modalUnit');

        if (modalUnit) {
            modalUnit.innerText = unit;
        }


        // SOURCE
        const modalSource =
            document.getElementById('modalSource');

        if (modalSource) {
            modalSource.innerText = source;
        }


        // STATUS
        const modalStatus =
            document.getElementById('modalStatus');

        if (modalStatus) {
            modalStatus.innerText = status;
        }


        // SHOW MODAL
        modal.classList.remove('hidden');

    }


    // =========================================================
    // CLOSE DONATION MODAL
    // =========================================================

    function closeModal() {

        const modal =
            document.getElementById('donationModal');

        if (!modal) {
            return;
        }

        modal.classList.add('hidden');

    }


    // =========================================================
    // FILTER DONATIONS
    // =========================================================

    function filterDonations() {

        const searchInput =
            document.getElementById('searchInput');

        const statusFilter =
            document.getElementById('statusFilter');

        const typeFilter =
            document.getElementById('typeFilter');

        const dateFilter =
            document.getElementById('dateFilter');


        const search =
            searchInput.value.toLowerCase().trim();

        const status =
            statusFilter.value;

        const type =
            typeFilter.value;

        const date =
            dateFilter.value;


        const rows =
            document.querySelectorAll('.donation-row');

        let visibleCount = 0;


        rows.forEach(row => {

            const rowName =
                row.dataset.name || '';

            const rowStatus =
                row.dataset.status || '';

            const rowType =
                row.dataset.type || '';

            const rowDate =
                row.dataset.date || '';


            const matchesSearch =
                rowName.includes(search);


            const matchesStatus =
                status === 'all' ||
                rowStatus === status;


            const matchesType =
                type === 'all' ||
                rowType === type;


            const matchesDate =
                date === '' ||
                rowDate === date;


            if (
                matchesSearch &&
                matchesStatus &&
                matchesType &&
                matchesDate
            ) {

                row.style.display = '';

                visibleCount++;

            } else {

                row.style.display = 'none';

            }

        });


        document.getElementById('totalDonations').innerText =
            visibleCount;

    }


    // =========================================================
    // FILTER EVENT LISTENERS
    // =========================================================

    document
        .getElementById('searchInput')
        .addEventListener('input', filterDonations);


    document
        .getElementById('statusFilter')
        .addEventListener('change', filterDonations);


    document
        .getElementById('typeFilter')
        .addEventListener('change', filterDonations);


    document
        .getElementById('dateFilter')
        .addEventListener('change', filterDonations);


    // =========================================================
    // LOGOUT MODAL
    // =========================================================

    function openLogoutModal() {

        const modal =
            document.getElementById('logoutModal');

        if (!modal) {
            return;
        }

        modal.classList.remove('hidden');
        modal.classList.add('flex');

    }


    function closeLogoutModal() {

        const modal =
            document.getElementById('logoutModal');

        if (!modal) {
            return;
        }

        modal.classList.add('hidden');
        modal.classList.remove('flex');

    }

</script>

</body>
</html>

<?php /**PATH C:\ProjectSuhay\MEt.A-Project-SUHAY\resources\views/donations.blade.php ENDPATH**/ ?>