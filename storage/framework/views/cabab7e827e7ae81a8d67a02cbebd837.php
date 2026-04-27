<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applications</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-200">
<div class="flex">
    <?php echo $__env->make('components.nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="flex-1 p-8">
       <?php echo $__env->make('components.header', ['title' => 'Volunteer Management'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>


        <div class="bg-[#0e243a] p-4 rounded-2xl flex gap-4 mb-6 flex-wrap">
            <a href="/service-management" class="bg-[#f2c94c] px-6 py-2 rounded-full font-semibold">Volunteer Lists</a>
            <a href="/applications" class="bg-gray-200 text-[#0e243a] px-6 py-2 rounded-full font-semibold">Applications</a>
            <a href="/assignments" class="bg-[#f2c94c] px-6 py-2 rounded-full font-semibold">Assignments</a>
            <a href="/events"
               class="bg-[#f2c94c] px-6 py-2 rounded-full font-semibold">
                Events
            </a>
            <a href="/track-activity" class="bg-[#f2c94c] px-6 py-2 rounded-full font-semibold">Track Activity</a>

        </div>

        <div class="bg-[#0e243a] p-6 rounded-2xl">

            <!-- FILTERS -->
            <div class="bg-gray-300 p-6 rounded-xl mb-4 flex justify-between flex-wrap gap-4">

                <div class="flex items-center gap-4">

                    <select id="skillFilter" class="p-2 border rounded-md" onchange="applyFilters()">
                        <option value="">All Skills</option>
                        <?php $__currentLoopData = $skills; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $skill): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e(strtolower($skill)); ?>"><?php echo e($skill); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>

                    <select id="statusFilter" class="p-2 border rounded-md" onchange="applyFilters()">
                        <option value="">All Status</option>
                        <option value="0">Rejected</option>
                        <option value="1">Approved</option>
                        <option value="2">Pending</option>
                        <option value="3">Archived</option>
                    </select>

                </div>

            </div>

            <!-- TABLE (NOW INSIDE BLUE CONTAINER) -->
            <div class="bg-gray-200 rounded-xl p-4 overflow-x-auto">

                <table class="w-full text-center border border-gray-400">

                    <thead class="bg-gray-300">
                        <tr>
                            <th class="p-3 border">#</th>
                            <th class="p-3 border">Name</th>
                            <th class="p-3 border">Status</th>
                            <th class="p-3 border">Action</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php $__empty_1 = true; $__currentLoopData = $applications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $app): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                        <tr class="bg-white border hover:bg-gray-100"
                            data-status="<?php echo e($app['status']); ?>"
                            data-skills="<?php echo e(strtolower($app['skills'])); ?>"
                            data-availability="<?php echo e(strtolower($app['availability'])); ?>">

                            <td class="p-3 border row-number"></td>

                            <td class="p-3 border">
                                <?php echo e($app['first_name']); ?> <?php echo e($app['last_name']); ?>

                            </td>

                            <td class="p-3 border">

                                <?php if($app['status'] == 0): ?>
                                    <span class="px-3 py-1 rounded-full bg-red-200 text-red-800 text-sm">Rejected</span>

                                <?php elseif($app['status'] == 1): ?>
                                    <span class="px-3 py-1 rounded-full bg-green-200 text-green-800 text-sm">Approved</span>

                                <?php elseif($app['status'] == 2): ?>
                                    <span class="px-3 py-1 rounded-full bg-yellow-200 text-yellow-800 text-sm">Pending</span>

                                <?php elseif($app['status'] == 3): ?>
                                    <span class="px-3 py-1 rounded-full bg-gray-200 text-gray-800 text-sm">Archived</span>
                                <?php endif; ?>

                            </td>

                            <td class="p-3 border space-x-2">

                                <button
                                    class="bg-blue-500 px-4 py-1 rounded-full text-white view-btn"
                                    data-app='<?php echo json_encode($app, JSON_HEX_APOS | JSON_HEX_QUOT, 512) ?>'>
                                    View
                                </button>

                                <?php if($app['status'] == 0): ?>

                                    <button class="bg-yellow-500 px-4 py-1 rounded-full text-white restore-btn"
                                        data-id="<?php echo e($app['id']); ?>">
                                        Restore
                                    </button>

                                    <button class="bg-gray-600 px-4 py-1 rounded-full text-white archive-btn"
                                        data-id="<?php echo e($app['id']); ?>">
                                        Archive
                                    </button>

                                <?php elseif($app['status'] == 1): ?>

                                    <button class="bg-gray-600 px-4 py-1 rounded-full text-white archive-btn"
                                        data-id="<?php echo e($app['id']); ?>">
                                        Archive
                                    </button>

                                <?php elseif($app['status'] == 2): ?>

                                    <button class="bg-green-500 px-4 py-1 rounded-full text-white update-btn"
                                        data-id="<?php echo e($app['id']); ?>"
                                        data-action="approve">
                                        Approve
                                    </button>

                                    <button class="bg-red-500 px-4 py-1 rounded-full text-white update-btn"
                                        data-id="<?php echo e($app['id']); ?>"
                                        data-action="reject">
                                        Reject
                                    </button>

                                <?php elseif($app['status'] == 3): ?>

                                    <button class="bg-yellow-500 px-4 py-1 rounded-full text-white restore-btn"
                                        data-id="<?php echo e($app['id']); ?>">
                                        Restore
                                    </button>

                                <?php endif; ?>

                            </td>

                        </tr>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr id="noResultsRow" class="hidden">
                            <td colspan="4" class="p-4 text-gray-500 text-center">
                                No applications found.
                            </td>
                        </tr>
                    <?php endif; ?>

                    </tbody>

                </table>

            </div>
        </div>

        
    </div>
</div>
<?php echo $__env->make('components.application-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('components.logout-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<script>
// =======================
// INIT EVENTS (ONE SYSTEM)
// =======================
document.addEventListener("DOMContentLoaded", function () {

    // View modal
    document.querySelectorAll('.view-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const app = JSON.parse(this.getAttribute('data-app'));
            openAppModal(app);
        });
    });

    // Approve / Reject
    document.querySelectorAll('.update-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            updateStatus(this.dataset.id, this.dataset.action);
        });
    });

    // Archive
    document.querySelectorAll('.archive-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            archiveApplication(this.dataset.id);
        });
    });

    // Restore
    document.querySelectorAll('.restore-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            restoreApplication(this.dataset.id);
        });
    });

    filterStatus('');

    const statusFilter = document.getElementById('statusFilter');
    if (statusFilter) statusFilter.value = '';
});


// =======================
// FILTER SYSTEM (FIXED)
// =======================
function applyFilters() {

    let status = document.getElementById('statusFilter').value;
    let skill = document.getElementById('skillFilter').value.toLowerCase();

    const rows = document.querySelectorAll("tbody tr:not(#noResultsRow)");
    const noResultsRow = document.getElementById("noResultsRow");

    let visibleCount = 0;

    rows.forEach(row => {

        let rowStatus = row.dataset.status;
        let rowSkills = (row.dataset.skills || "").toLowerCase();

        let statusMatch = !status || rowStatus === status;
        let skillMatch = !skill || rowSkills.includes(skill);

        if (statusMatch && skillMatch) {
            row.style.display = "";
            visibleCount++;
        } else {
            row.style.display = "none";
        }
    });

    if (noResultsRow) {
        noResultsRow.classList.toggle("hidden", visibleCount !== 0);
    }

    renumberRows();
}


// =======================
// ROW NUMBERING
// =======================
function renumberRows() {
    let count = 1;

    document.querySelectorAll("tbody tr:not(#noResultsRow)").forEach(row => {
        if (row.style.display !== "none") {
            const cell = row.querySelector(".row-number");
            if (cell) cell.innerText = count++;
        }
    });
}


// =======================
// STATUS FILTER (FIXED)
// =======================
function filterStatus(status) {

    const rows = document.querySelectorAll("tbody tr:not(#noResultsRow)");
    const noResultsRow = document.getElementById("noResultsRow");

    let visibleCount = 0;

    rows.forEach(row => {

        if (!status) {
            row.style.display = "";
            visibleCount++;
            return;
        }

        if (row.dataset.status === status) {
            row.style.display = "";
            visibleCount++;
        } else {
            row.style.display = "none";
        }
    });

    if (noResultsRow) {
        noResultsRow.classList.toggle("hidden", visibleCount !== 0);
    }

    renumberRows();
}


// =======================
// MODAL (FIXED)
// =======================
function openAppModal(app) {

    const modal = document.getElementById('appModal');
    const box = document.getElementById('modalBox');

    box.classList.remove('scale-95');

    document.getElementById('first_name').innerText = app.first_name ?? '---';
    document.getElementById('last_name').innerText = app.last_name ?? '---';
    document.getElementById('address').innerText = app.address ?? '---';
    document.getElementById('contact').innerText = app.contact_number ?? '---';
    document.getElementById('email').innerText = app.email ?? '---';
    document.getElementById('dob').innerText = app.birth_date ?? '---';

    document.getElementById('availability_text').innerText =
        app.availability ?? '---';

    document.getElementById('experience').innerText =
        Number(app.has_experience) === 1 ? 'Yes' : 'No';

    document.getElementById('reason').innerText =
        app.experience_details ?? '---';

    document.getElementById('skills_text').innerText =
        app.skills ?? '---';

    document.getElementById('interests_text').innerText =
        app.interests ?? '---';

    modal.classList.remove('hidden');
    modal.classList.add('flex');
}


// =======================
// CLOSE MODAL
// =======================
function closeModal() {

    const modal = document.getElementById('appModal');
    const box = document.getElementById('modalBox');

    box.classList.add('scale-95');

    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }, 150);
}


// =======================
// STATUS ACTIONS
// =======================
function updateStatus(id, action) {

    let message = action === 'approve'
        ? "Are you sure you want to APPROVE this application?"
        : "Are you sure you want to REJECT this application?";

    if (!confirm(message)) return;

    let url = action === 'approve'
        ? `/applications/approve/${id}`
        : `/applications/reject/${id}`;

    fetch(url, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
            'Content-Type': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) location.reload();
        else alert("Failed to update");
    });
}


// =======================
// ARCHIVE
// =======================
function archiveApplication(id) {

    if (!confirm("Archive this approved application?")) return;

    fetch(`/applications/archive/${id}`, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
            'Content-Type': 'application/json'
        }
    }).then(() => location.reload());
}


// =======================
// RESTORE
// =======================
function restoreApplication(id) {

    if (!confirm("Restore this application back to PENDING?")) return;

    fetch(`/applications/restore/${id}`, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
            'Content-Type': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) location.reload();
        else alert("Failed to restore");
    });
}
</script>
</body>
</html><?php /**PATH D:\Acads\MEt.A-Project-SUHAY\resources\views/applications.blade.php ENDPATH**/ ?>