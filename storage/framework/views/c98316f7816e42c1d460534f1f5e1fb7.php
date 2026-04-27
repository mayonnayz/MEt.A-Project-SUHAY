<!DOCTYPE html> 

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SUHAY</title>

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
       <?php echo $__env->make('components.header', ['title' => 'Volunteer Management'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <div class="bg-[#0e243a] p-4 rounded-2xl flex gap-4 mb-6 flex-wrap">
            <a href="/service-management"
               class="bg-gray-200 text-[#0e243a] px-6 py-2 rounded-full font-semibold">
                Volunteer Lists
            </a>

            <a href="/applications"
               class="bg-[#f2c94c] px-6 py-2 rounded-full font-semibold">
                Applications
            </a>

            <a href="/assignments"
               class="bg-[#f2c94c] px-6 py-2 rounded-full font-semibold">
                Assignments
            </a>

            <a href="/events"
               class="bg-[#f2c94c] px-6 py-2 rounded-full font-semibold">
                Events
            </a>

            <a href="/track-activity"
               class="bg-[#f2c94c] px-6 py-2 rounded-full font-semibold">
                Track Activity
            </a>
        </div>

        <div>

            <div class="bg-gray-300 rounded-xl p-6 mb-6 flex justify-between flex-wrap gap-4">
                <div>
                    <p class="font-semibold text-gray-700">Total Volunteer/s:</p>
                    <p class="mt-2 font-semibold text-gray-700">Active Volunteers:</p>
                </div>
                <div>
                    <p class="font-semibold text-gray-700">Available Volunteers:</p>
                    <p class="mt-2 font-semibold text-gray-700">Total Hours Rendered:</p>
                </div>
            </div>

            <div class="flex justify-center mb-4">
                <form method="GET" action="/service-management" class="flex w-full md:w-1/3">
                   <input type="text" id="searchInput" name="search" placeholder="Search Volunteer...."
                        class="w-full p-2 border rounded-l-md focus:outline-none focus:ring-2 focus:ring-[#0e243a]">

                    <button type="submit"
                        class="bg-white px-4 py-2 rounded-r-md flex items-center justify-center">
                        <img src="/images/searchbar.png" class="w-9 h-8">
                    </button>
                </form>
            </div>
            <div class="flex items-center justify-center gap-4 mb-6 flex-wrap">
                <p class="font-semibold text-gray-700">Filter:</p>
                <select id="skillFilter" class="border p-2 rounded-md">
                    <option value="">All Skills</option>
                    <?php $__currentLoopData = $skills; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $skill): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e(strtolower($skill)); ?>"><?php echo e($skill); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>

                <select id="availabilityFilter" class="border p-2 rounded-md">
                    <option value="">All Availability</option>
                    <?php $__currentLoopData = $availability; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e(strtolower($a)); ?>"><?php echo e($a); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">

    <?php $__currentLoopData = $volunteers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $volunteer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="bg-[#4a5568] text-white rounded-2xl p-6 text-center 
                hover:scale-105 hover:shadow-xl transition-all duration-300">

        <div class="w-20 h-20 bg-white rounded-full mx-auto mb-3 flex items-center justify-center">
            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                </path>
            </svg>
        </div>

        <h3 class="font-bold text-lg">
            <?php echo e($volunteer->first_name); ?> <?php echo e($volunteer->last_name); ?>

        </h3>

        <!-- UPDATED INFO -->
        <div class="text-xs mt-4 text-left space-y-1 bg-[#3b4252] p-3 rounded-lg">
            <p><span class="font-semibold">Email:</span> <?php echo e($volunteer->email); ?></p>
            <p><span class="font-semibold">Phone:</span> <?php echo e($volunteer->contact_number); ?></p>
        </div>

        <!-- UPDATED BUTTONS -->
        <div class="flex justify-between mt-4 gap-2">

           <button 
                onclick='openModal(<?php echo json_encode($volunteer, 15, 512) ?>)'
                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-full text-xs font-semibold">
                View
            </button>

            <button 
                onclick="deactivateVolunteer(<?php echo e($volunteer->account_id); ?>)"
                class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-full text-xs font-semibold">
                Deactivate
            </button>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

</div>
        </div>  
    </div>
</div>
<?php echo $__env->make('components.application-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('components.logout-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<script>
   function fetchVolunteers() {

    let skill = document.getElementById('skillFilter').value;
    let availability = document.getElementById('availabilityFilter').value;

    fetch(`/service-management?search_skill=${skill}&search_availability=${availability}`)
        .then(res => res.text())
        .then(html => {
            let doc = new DOMParser().parseFromString(html, 'text/html');

            document.querySelector('.grid').innerHTML =
                doc.querySelector('.grid').innerHTML;
        });
}

document.getElementById('skillFilter').addEventListener('change', fetchVolunteers);
document.getElementById('availabilityFilter').addEventListener('change', fetchVolunteers);
    document.getElementById('searchInput').addEventListener('input', function () {
        let search = this.value;

        fetch(`/service-management?search=${encodeURIComponent(search)}`)
            .then(res => res.text())
            .then(html => {
                let parser = new DOMParser();
                let doc = parser.parseFromString(html, 'text/html');

                document.querySelector('.grid').innerHTML =
                    doc.querySelector('.grid').innerHTML;
            });
    });

</script>
<script>
    
    function openLogoutModal() {
        document.getElementById('logoutModal').classList.remove('hidden');
        document.getElementById('logoutModal').classList.add('flex');
    }

    function closeLogoutModal() {
        document.getElementById('logoutModal').classList.add('hidden');
    }
</script>


<script>
function openModal(app) {

    const box = document.getElementById('modalBox');
    box.classList.remove('scale-95');

    // Personal Information
    document.getElementById('first_name').innerText = app.first_name ?? '---';
    document.getElementById('last_name').innerText = app.last_name ?? '---';
    document.getElementById('address').innerText = app.address ?? '---';
    document.getElementById('contact').innerText = app.contact_number ?? '---';
    document.getElementById('email').innerText = app.email ?? '---';
    document.getElementById('dob').innerText = app.birth_date ?? '---';

    // Availability
    document.getElementById('day').innerText = app.availability ?? '---';
    document.getElementById('time').innerText = app.availability ?? '---';

    document.getElementById('experience').innerText =
        Number(app.has_experience) === 1 ? 'Yes' : 'No';

    document.getElementById('reason').innerText =
        app.experience_details ?? '---';

    // Skills (RAW TEXT ONLY)
    document.getElementById('skills_text').innerText =
        app.skills ?? '---';

    // Interests (RAW TEXT ONLY)
    document.getElementById('interests_text').innerText =
        app.interests ?? '---';

    // Show modal
    document.getElementById('appModal').classList.remove('hidden');
}
</script>

<script>


function closeModal() {
    const modal = document.getElementById('appModal');
    const box = document.getElementById('modalBox');

    // animate out
    box.classList.add('scale-95');

    setTimeout(() => {
        modal.classList.add('hidden');
    }, 150);
}

function nextPage() {
    document.getElementById('page1').classList.add('hidden');
    document.getElementById('page2').classList.remove('hidden');
}

function prevPage() {
    document.getElementById('page2').classList.add('hidden');
    document.getElementById('page1').classList.remove('hidden');
}
</script>

<script>
function deactivateVolunteer(id) {

    console.log("Sending ID:", id);

    fetch(`/volunteers/deactivate/${id}`, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
            'Accept': 'application/json'
        }
    })
    .then(async res => {
        console.log("STATUS:", res.status);
        const text = await res.text();
        console.log("RAW RESPONSE:", text);
        return JSON.parse(text);
    })
    .then(data => {
        console.log("RESPONSE:", data);
        alert("Success");
        location.reload();
    })
    .catch(err => {
        console.error("ERROR:", err);
        alert("Request failed (check console)");
    });
}
</script>
</body>
</html><?php /**PATH D:\Acads\MEt.A-Project-SUHAY\resources\views/service_management.blade.php ENDPATH**/ ?>