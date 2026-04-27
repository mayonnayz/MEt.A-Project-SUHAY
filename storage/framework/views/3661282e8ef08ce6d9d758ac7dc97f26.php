<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applications</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Poppins', sans-serif; }
        td.label { background: #0c2d48; color: white; width: 35%; font-weight: 600; }
    </style>
</head>

<body class="bg-gray-200">
<div class="flex">

    <?php echo $__env->make('components.nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="flex-1 p-8">
        <?php echo $__env->make('components.header', ['title' => 'Applications'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <div class="bg-white rounded-[22px] border-[10px] border-[#0e243a] p-6 sm:p-8">

            <form method="GET" action="/volunteer/applications" class="mb-6 flex flex-col sm:flex-row gap-3">
                <div class="flex items-center bg-white border-2 border-[#0e243a] rounded-2xl px-4 py-3 flex-1">
                    <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Search Applications..." class="w-full outline-none text-[15px] font-medium placeholder:text-gray-500"/>
                    <div class="ml-3 w-10 h-10 rounded-xl bg-[#0e243a] flex items-center justify-center pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="7"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </div>
                </div>
                <div class="bg-white border-2 border-[#0e243a] rounded-2xl px-6 py-3 flex items-center">
                    <select name="filter" onchange="this.form.submit()" class="outline-none text-sm font-medium bg-transparent cursor-pointer w-full sm:w-40">
                        <option value="">All</option>
                        <option value="current" <?php echo e(request('filter')=='current' ? 'selected' : ''); ?>>Current</option>
                        <option value="past" <?php echo e(request('filter')=='past' ? 'selected' : ''); ?>>Past</option>
                    </select>
                </div>
            </form>

            <div class="space-y-5">
                <?php $__currentLoopData = $applications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $app): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="rounded-2xl border-2 border-[#0e243a] bg-white p-5">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                        <div>
                            <div class="text-lg font-bold text-[#0e243a] mb-2"><?php echo e($app['event_name'] ?? 'Unknown Event'); ?></div>
                            <div class="text-sm text-gray-700 space-y-1">
                                <div class="flex items-center gap-2">
                                    <img src="<?php echo e(asset('images/VolunteerIcons/VDate.png')); ?>" class="h-5 w-5">
                                    <span><?php echo e(isset($app['date']) ? \Carbon\Carbon::parse($app['date'])->format('F d, Y') : 'No date'); ?></span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <img src="<?php echo e(asset('images/VolunteerIcons/VNGO.png')); ?>" class="h-5 w-5">
                                    <span><?php echo e($app['ngo_name'] ?? 'Unknown NGO'); ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col items-end gap-2">
                            <?php if($app['status'] == 1): ?>
                                <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-bold">APPROVED</span>
                            <?php elseif($app['status'] == 0): ?>
                                <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-bold">REJECTED</span>
                            <?php else: ?>
                                <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs font-bold">PENDING</span>
                            <?php endif; ?>

                            <button
                                class="viewBtn px-6 py-2 rounded-full bg-[#d39a11] text-white font-bold text-sm hover:bg-[#c2870d] transition"
                                data-first_name="<?php echo e($app['first_name'] ?? ''); ?>"
                                data-last_name="<?php echo e($app['last_name'] ?? ''); ?>"
                                data-address="<?php echo e($app['address'] ?? ''); ?>"
                                data-contact="<?php echo e($app['contact'] ?? ''); ?>"
                                data-email="<?php echo e($app['email'] ?? ''); ?>"
                                data-dob="<?php echo e($app['dob'] ?? ''); ?>"
                                data-date="<?php echo e($app['date'] ?? ''); ?>"
                                data-availability="<?php echo e($app['availability'] ?? ''); ?>"
                                data-skills="<?php echo e($app['skills'] ?? ''); ?>"
                                data-interests="<?php echo e($app['interests'] ?? ''); ?>"
                                data-experience_details="<?php echo e($app['experience_details'] ?? ''); ?>"
                                data-has_experience="<?php echo e($app['has_experience'] ?? 0); ?>"
                                data-remarks="<?php echo e($app['remarks'] ?? ''); ?>"
                            >
                                View Application
                            </button>
                        </div>

                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
</div>

<?php echo $__env->make('components.logout-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<!-- VIEW APPLICATION MODAL -->
<div id="viewModal" style="display:none;" class="fixed inset-0 bg-black/40 items-center justify-center z-50">
    <div class="bg-[#f8f8f8] w-[650px] max-h-[90vh] overflow-y-auto rounded-2xl border-2 border-gray-700 p-6 relative">

        <div class="text-center mb-3">
            <div class="flex justify-center">
                <img src="<?php echo e(asset('images/suhayLogo.png')); ?>" alt="SUHAY" class="h-28 w-28 object-contain"/>
            </div>
            <div class="text-sm font-semibold">Volunteer Application Form</div>
        </div>

        <div class="mt-4">
            <div class="text-xs font-semibold mb-1">Personal Information</div>
            <table class="w-full text-xs border border-gray-400 border-collapse">
                <tbody id="modalContent"></tbody>
            </table>
        </div>

        <div class="mt-4">
            <div class="text-xs font-semibold mb-1">Application Details</div>
            <table class="w-full text-xs border border-gray-400 border-collapse">
                <tr><td class="label p-1">Application Date</td><td class="border p-1" id="modalDate"></td></tr>
                <tr><td class="label p-1">Availability</td><td class="border p-1" id="modalAvailability"></td></tr>
                <tr><td class="label p-1">Skills</td><td class="border p-1" id="modalSkills"></td></tr>
                <tr><td class="label p-1">Interests</td><td class="border p-1" id="modalInterests"></td></tr>
            </table>
        </div>

        <div class="mt-4">
            <div class="text-xs font-semibold mb-1">Volunteer Experience</div>
            <table class="w-full text-xs border border-gray-400 border-collapse">
                <tr><td class="label p-1">Has Experience?</td><td class="border p-1" id="modalHasExperience"></td></tr>
                <tr><td class="label p-1">Experience Details</td><td class="border p-1 whitespace-pre-wrap" id="modalExperience"></td></tr>
            </table>
        </div>

        <div class="mt-4">
            <div class="text-xs font-semibold mb-1">Motivation</div>
            <table class="w-full text-xs border border-gray-400 border-collapse">
                <tr><td class="label p-1">Why do you want to volunteer?</td><td class="border p-1 whitespace-pre-wrap" id="modalRemarks"></td></tr>
            </table>
        </div>

        <div class="mt-6 flex justify-center">
            <button onclick="closeModal()" class="px-8 py-2 rounded-full bg-[#0e243a] text-white font-bold text-sm hover:bg-[#091826] transition">Close</button>
        </div>
    </div>
</div>

<!-- SINGLE SCRIPT BLOCK — NO DUPLICATES -->
<script>
    function openLogoutModal() {
        document.getElementById('logoutModal').classList.remove('hidden');
        document.getElementById('logoutModal').classList.add('flex');
    }
    function closeLogoutModal() {
        document.getElementById('logoutModal').classList.add('hidden');
    }
    function closeModal() {
        document.getElementById('viewModal').style.display = 'none';
    }

    document.querySelectorAll('.viewBtn').forEach(button => {
        button.addEventListener('click', function () {
            document.getElementById('modalContent').innerHTML = `
                <tr><td class="label p-1">First Name</td><td class="border p-1">${this.dataset.first_name}</td></tr>
                <tr><td class="label p-1">Last Name</td><td class="border p-1">${this.dataset.last_name}</td></tr>
                <tr><td class="label p-1">Address</td><td class="border p-1">${this.dataset.address}</td></tr>
                <tr><td class="label p-1">Contact Number</td><td class="border p-1">${this.dataset.contact}</td></tr>
                <tr><td class="label p-1">Email Address</td><td class="border p-1">${this.dataset.email}</td></tr>
                <tr><td class="label p-1">Date of Birth</td><td class="border p-1">${this.dataset.dob}</td></tr>
            `;

            document.getElementById('modalDate').textContent         = this.dataset.date;
            document.getElementById('modalAvailability').textContent  = this.dataset.availability;
            document.getElementById('modalSkills').textContent        = this.dataset.skills;
            document.getElementById('modalInterests').textContent     = this.dataset.interests;
            document.getElementById('modalExperience').textContent    = this.dataset.experience_details;
            document.getElementById('modalHasExperience').textContent = this.dataset.has_experience == "1" ? "Yes" : "No";
            document.getElementById('modalRemarks').textContent       = this.dataset.remarks || 'N/A';

            document.getElementById('viewModal').style.display = 'flex';
        });
    });
</script>

</body>
</html><?php /**PATH D:\Acads\MEt.A-Project-SUHAY\resources\views/Volunteers/applications.blade.php ENDPATH**/ ?>