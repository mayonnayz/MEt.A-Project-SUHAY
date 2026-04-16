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
    <div class="group w-28 hover:w-56 bg-[#0e243a] min-h-screen flex flex-col py-6 text-white rounded-r-3xl transition-all duration-300 overflow-hidden">
        <a href="/sm-dashboard"
           class="flex items-center gap-4 px-4 py-4 hover:bg-[#f2c94c] hover:text-[#0e243a] rounded-xl mx-2">
            <img src="<?php echo e(asset('images/ServiceManagement/SMDash.png')); ?>" class="w-16 h-16 object-contain">
            <span class="opacity-0 group-hover:opacity-100">Dashboard</span>
        </a>

        <a href="/sm-ngos"
           class="flex items-center gap-4 px-4 py-4 hover:bg-[#f2c94c] hover:text-[#0e243a] rounded-xl mx-2">
            <img src="<?php echo e(asset('images/ServiceManagement/SMNGOs.png')); ?>" class="w-16 h-16 object-contain">
            <span class="opacity-0 group-hover:opacity-100">NGOs</span>
        </a>

        <a href="/sm-donations"
           class="flex items-center gap-4 px-4 py-4 hover:bg-[#f2c94c] hover:text-[#0e243a] rounded-xl mx-2">
            <img src="<?php echo e(asset('images/ServiceManagement/SMDonations.png')); ?>" class="w-16 h-16 object-contain">
            <span class="opacity-0 group-hover:opacity-100">Donations</span>
        </a>

        <a href="/sm-inventory"
           class="flex items-center gap-4 px-4 py-4 hover:bg-[#f2c94c] hover:text-[#0e243a] rounded-xl mx-2">
            <img src="<?php echo e(asset('images/ServiceManagement/SMInventory.png')); ?>" class="w-16 h-16 object-contain">
            <span class="opacity-0 group-hover:opacity-100">Inventory</span>
        </a>

        <a href="/service-management"
           class="flex items-center gap-4 px-4 py-4 bg-[#1a3554] rounded-xl mx-2">
            <img src="<?php echo e(asset('images/ServiceManagement/SMVolunteers.png')); ?>" class="w-16 h-16 object-contain">
            <span class="opacity-0 group-hover:opacity-100">Volunteers</span>
        </a>

        <a href="#" onclick="openLogoutModal()" class="mt-auto flex items-center gap-4 px-4 py-4 hover:bg-[#f2c94c] hover:text-[#0e243a] rounded-xl mx-2">
            <img src="<?php echo e(asset('images/ServiceManagement/SMLogout.png')); ?>" class="w-16 h-16 object-contain">
            <span class="opacity-0 group-hover:opacity-100">Logout</span>
        </a>
    </div>

    <div class="flex-1 p-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-[#0e243a] ">Volunteer Management</h1>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gray-300 rounded-full"></div>
                <p class="text-[#0e243a]">Consuelo Mercado</p>
            </div>
        </div>

        <div class="bg-[#0e243a] p-4 rounded-2xl flex gap-4 mb-6 flex-wrap">
            <a href="/service-management" class="bg-[#f2c94c] px-6 py-2 rounded-full font-semibold">Volunteer Lists</a>
            <a href="/applications" class="bg-gray-200 text-[#0e243a] px-6 py-2 rounded-full font-semibold">Applications</a>
            <a href="/assignments" class="bg-[#f2c94c] px-6 py-2 rounded-full font-semibold">Assignments</a>
            <a href="/categories"
               class="bg-[#f2c94c] px-6 py-2 rounded-full font-semibold">
                Service Categories
            </a>
            <a href="/track-activity" class="bg-[#f2c94c] px-6 py-2 rounded-full font-semibold">Track Activity</a>

        </div>

        <div class="bg-[#0e243a] p-6 rounded-2xl">
            <div class="bg-gray-300 p-6 rounded-xl mb-4 flex justify-between flex-wrap gap-4">
                <div>
                    <p>Pending Application/s:</p>
                    <p>Approved Application/s:</p>
                    <p>Rejected Application/s:</p>
                </div>

                <div class="flex items-center gap-4">
                    <span>Filter:</span>
                    <input type="text" placeholder="Skills" class="p-2 border rounded-md">
                    <input type="date" class="p-2 border rounded-md">
                </div>
            </div>

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
                            <tr class="bg-white border hover:bg-gray-100">

                                <td class="p-3 border">
                                    <?php echo e($index + 1); ?>

                                </td>

                                <td class="p-3 border">
                                    <?php echo e($app->first_name); ?> <?php echo e($app->last_name); ?>

                                </td>

                                <td class="p-3 border">
                                    <span class="px-3 py-1 rounded-full bg-yellow-200 text-yellow-800 text-sm">
                                        Pending
                                    </span>
                                </td>

                                <td class="p-3 border space-x-2">

                                    <button onclick="openModal()" class="bg-blue-500 px-4 py-1 rounded-full text-white">
                                        View
                                    </button>

                                    <button class="bg-green-500 px-4 py-1 rounded-full text-white">
                                        Approve
                                    </button>

                                    <button class="bg-red-500 px-4 py-1 rounded-full text-white">
                                        Reject
                                    </button>

                                </td>

                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="4" class="p-4 text-gray-500">
                                    No pending applications found.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="appModal" class="fixed inset-0 bg-black/40 hidden items-start justify-center z-50 overflow-y-auto">

    <div id="modalBox"
    class="bg-white w-[750px] mt-[-500px] rounded-2xl shadow-xl p-6 transition-all duration-500">
        <div class="flex justify-center mb-2">
            <img src="<?php echo e(asset('images/suhayLogo.png')); ?>" class="w-[300px] mx-auto mb-2">
        </div>

        <h2 class="text-xl text-center mb-4">Volunteer Application Form</h2>

        <div id="page1">
            <p class="mb-2">Personal Information</p>
            <table class="w-full border text-sm mb-4 table-fixed">
                <tr>
                    <td class="bg-[#0e243a] text-white p-2 w-1/4">First Name</td>
                    <td class="border p-2">Consuelo</td>
                </tr>
                <tr>
                    <td class="bg-[#0e243a] text-white p-2">Last Name</td>
                    <td class="border p-2"></td>
                </tr>
                <tr>
                    <td class="bg-[#0e243a] text-white p-2">Address</td>
                    <td class="border p-2"></td>
                </tr>
                <tr>
                    <td class="bg-[#0e243a] text-white p-2">Contact Number</td>
                    <td class="border p-2"></td>
                </tr>
                <tr>
                    <td class="bg-[#0e243a] text-white p-2">Email Address</td>
                    <td class="border p-2"></td>
                </tr>
                <tr>
                    <td class="bg-[#0e243a] text-white p-2">Date of Birth</td>
                    <td class="border p-2"></td>
                </tr>
                <tr>
                    <td class="bg-[#0e243a] text-white p-2">Gender</td>
                    <td class="border p-2"></td>
                </tr>
                <tr>
                    <td class="bg-[#0e243a] text-white p-2">Occupation</td>
                    <td class="border p-2"></td>
                </tr>
            </table>

            <p class="mb-2">Availability</p>
            <table class="w-full border text-sm mb-4">
                <tr>
                    <td class="bg-[#0e243a] text-white p-2">DAY</td>
                    <td class="bg-[#0e243a] text-white p-2">TIME</td>
                </tr>
                <tr>
                    <td class="border p-2"></td>
                    <td class="border p-2"></td>
                </tr>
            </table>

            <p class="mb-2">General Information</p>
            <table class="w-full border text-sm">
                <tr>
                    <td class="bg-[#0e243a] text-white p-2">QUESTION</td>
                    <td class="bg-[#0e243a] text-white p-2">ANSWER</td>
                </tr>
                <tr><td class="border p-2">Do you have volunteering experience?</td><td class="border p-2"></td></tr>
                <tr><td class="border p-2">Why do you want to volunteer?</td><td class="border p-2"></td></tr>
            </table>

            <div class="flex justify-end gap-3 mt-6">
                <button onclick="closeModal()" class="bg-gray-400 px-4 py-2 rounded-lg text-white">Close</button>
                <button onclick="nextPage()" class="bg-[#f2c94c] px-4 py-2 rounded-lg">Next</button>
            </div>

        </div>

        <div id="page2" class="hidden">
            <p class="mb-2">Skills</p>
            <table class="w-full border text-sm mb-4">
                <tr>
                    <td class="bg-[#0e243a] text-white p-2">Skill (select all that apply)</td>
                    <td class="bg-[#0e243a] text-white p-2 text-center">Select</td>
                </tr>
                <tr><td class="border p-2">Teaching / Tutoring</td><td class="border text-center"><input type="checkbox"></td></tr>
                <tr><td class="border p-2">Medical / First Aid</td><td class="border text-center"><input type="checkbox"></td></tr>
                <tr><td class="border p-2">Cooking / Food Prep</td><td class="border text-center"><input type="checkbox"></td></tr>
                <tr><td class="border p-2">Event Organization</td><td class="border text-center"><input type="checkbox"></td></tr>
            </table>

            <p class="mb-2">Interests</p>
            <table class="w-full border text-sm">
                <tr>
                    <td class="bg-[#0e243a] text-white p-2">Interest (select all that apply)</td>
                    <td class="bg-[#0e243a] text-white p-2 text-center">Select</td>
                </tr>
                <tr><td class="border p-2">Community Outreach</td><td class="border text-center"><input type="checkbox"></td></tr>
                <tr><td class="border p-2">Disaster Relief</td><td class="border text-center"><input type="checkbox"></td></tr>
                <tr><td class="border p-2">Feeding Programs</td><td class="border text-center"><input type="checkbox"></td></tr>
            </table>

            <div class="flex justify-end gap-3 mt-6">
                <button onclick="prevPage()" class="bg-gray-400 px-4 py-2 rounded-lg text-white">Back</button>

                <div class="flex gap-2">
                    <button onclick="closeModal()" class="bg-red-500 px-4 py-2 rounded-lg text-white">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="logoutModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl p-8 w-[350px] text-center shadow-lg">
        <h2 class="text-xl font-bold mb-4">Are you sure you want to logout?</h2>

        <div class="flex justify-center gap-4 mt-6">
            <button onclick="closeLogoutModal()" 
                class="px-6 py-2 bg-gray-300 rounded-full font-semibold hover:bg-gray-400">
                No
            </button>

            <a href="/sm-logout" 
            class="px-6 py-2 bg-[#0e243a] text-yellow-400 rounded-full font-semibold hover:opacity-90">
                Yes
            </a>
        </div>
    </div>
</div>

<script>
function openModal() {
    const modal = document.getElementById('appModal');
    const box = document.getElementById('modalBox');

    modal.classList.remove('hidden');
    modal.classList.add('flex');

    setTimeout(() => {
        box.classList.remove('mt-[-500px]');
        box.classList.add('mt-20');
    }, 50);

    document.getElementById('page1').classList.remove('hidden');
    document.getElementById('page2').classList.add('hidden');
}

function closeModal() {
    const modal = document.getElementById('appModal');
    const box = document.getElementById('modalBox');

    box.classList.remove('mt-20');
    box.classList.add('mt-[-500px]');

    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
}

function nextPage() {
    document.getElementById('page1').classList.add('hidden');
    document.getElementById('page2').classList.remove('hidden');
}

function prevPage() {
    document.getElementById('page2').classList.add('hidden');
    document.getElementById('page1').classList.remove('hidden');
}

function openLogoutModal() {
    document.getElementById('logoutModal').classList.remove('hidden');
    document.getElementById('logoutModal').classList.add('flex');
}

function closeLogoutModal() {
    document.getElementById('logoutModal').classList.add('hidden');
}
</script>

</body>
</html><?php /**PATH C:\sysands\MEt.A-Project-SUHAY-elisha\resources\views/applications.blade.php ENDPATH**/ ?>