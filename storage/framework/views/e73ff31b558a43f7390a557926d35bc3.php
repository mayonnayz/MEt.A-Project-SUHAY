<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events</title>

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
       <?php echo $__env->make('components.header', ['title' => 'Events'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>


        <!-- Outer Panel -->
        <div class="bg-white rounded-[22px] border-[10px] border-[#0e243a] p-6 sm:p-8">
            <!-- Search -->
            <form method="GET" action="/volunteer/events" class="mb-6">
                <div class="flex items-center bg-white border-2 border-[#0e243a] rounded-2xl px-4 py-3">
                    
                    <input
                        type="text"
                        name="search"
                        value="<?php echo e(request('search')); ?>"
                        placeholder="Search Events...."
                        class="w-full outline-none text-[15px] font-medium placeholder:text-gray-500"
                    />

                    <!-- JUST ICON (not clickable) -->
                    <div class="ml-3 w-10 h-10 rounded-xl bg-[#0e243a] flex items-center justify-center pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="7"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </div>

                </div>
            </form>

            <!-- Event Cards Container -->
            <div class="space-y-5">
             
            <?php $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="rounded-2xl border-2 border-[#0e243a] bg-white p-5">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                            <!-- LEFT SIDE -->
                            <div class="min-w-0">
                                <div class="text-lg font-bold text-[#0e243a] mb-2">
                                    <?php echo e($event['name']); ?>

                                </div>

                                <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-5 text-sm text-gray-700">

                                    <div class="flex items-center gap-2">
                                        <img src="<?php echo e(asset('images/VolunteerIcons/VDate.png')); ?>"
                                            class="h-5 w-5 object-contain">
                                        <span>
                                            <?php echo e(\Carbon\Carbon::parse($event['date'])->format('F d, Y')); ?>

                                        </span>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <img src="<?php echo e(asset('images/VolunteerIcons/VNGO.png')); ?>"
                                            class="h-5 w-5 object-contain">
                                        <span>NGO: <?php echo e($event['ngo_name'] ?? 'Unknown NGO'); ?></span>
                                    </div>

                                </div>
                            </div>

                            <!-- RIGHT SIDE BUTTONS -->
                            <div class="flex gap-3 md:justify-end">
                                <button
                                    type="button"
                                    class="view-activities-btn px-6 py-2 rounded-full bg-[#d39a11] text-white font-bold text-sm hover:bg-[#c2870d] transition"

                                    data-title="<?php echo e($event['name']); ?>"
                                    data-date="<?php echo e(\Carbon\Carbon::parse($event['date'])->format('F d, Y')); ?>"
                                    data-org="<?php echo e($event['ngo_name'] ?? 'Unknown NGO'); ?>"
                                    data-activities='<?php echo json_encode(collect($event["activities"] ?? [])->map(function ($a) {
                                    return [
                                            "name" => $a["name"] ?? "", "remarks" => $a["remarks"] ?? ""
                                        ];
                                    })->values(), 512) ?>'
                                >
                                    View Activities
                                </button>

                                <?php
                                    $alreadyApplied = in_array((string)$event['id'], $applications ?? []);
                                ?>
                                
                                <?php if($alreadyApplied): ?>
                                    <button disabled
                                        class="px-6 py-2 rounded-full bg-gray-400 text-white font-bold text-sm cursor-not-allowed">
                                        Already Applied
                                    </button>
                                <?php else: ?>
                                    <a href="/volunteer-application-form?event_id=<?php echo e($event['id']); ?>"
                                        class="px-6 py-2 rounded-full bg-green-500 text-white font-bold text-sm hover:bg-green-600 transition">
                                        Volunteer Now
                                    </a>
                                <?php endif; ?>
                            </div>

                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            </div>
        </div>
    </div>
</div>

    <?php echo $__env->make('components.logout-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<script>

    function closeActivitiesModal() {
    const modal = document.getElementById('activitiesModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

    function openActivitiesModal({ title, date, org, activities }) {
    document.getElementById('modalEventTitle').textContent = title;
    document.getElementById('modalEventDate').textContent = date;
    document.getElementById('modalEventOrg').textContent = org;

    const list = document.getElementById('modalActivitiesList');
    list.innerHTML = "";

    if (activities.length === 0) {
        const li = document.createElement('li');
        li.className = "text-sm text-gray-500";
        li.textContent = "No activities available.";
        list.appendChild(li);
    } else {
        activities.forEach(a => {
            const li = document.createElement('li');
            li.className = "text-sm text-gray-700";

            li.innerHTML = `
                <div class="font-semibold">${a.name}</div>
                <div class="text-gray-500 text-xs">${a.remarks ?? ''}</div>
            `;

            list.appendChild(li);
        });
    }

    const modal = document.getElementById('activitiesModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

    function openLogoutModal() {
        document.getElementById('logoutModal').classList.remove('hidden');
        document.getElementById('logoutModal').classList.add('flex');
    }

    function closeLogoutModal() {
        document.getElementById('logoutModal').classList.add('hidden');
    }

    document.getElementById("searchInput").addEventListener("input", function () {
    let search = this.value.toLowerCase();
    let cards = document.querySelectorAll(".event-card");

    cards.forEach(card => {
        let text = card.innerText.toLowerCase();

        if (text.includes(search)) {
            card.style.display = "block";
        } else {
            card.style.display = "none";
        }
    });
});
</script>

<script>
document.querySelectorAll('.view-activities-btn').forEach(btn => {
    btn.addEventListener('click', function () {

        const title = this.dataset.title;
        const date = this.dataset.date;
        const org = this.dataset.org;
        const activities = JSON.parse(this.dataset.activities || '[]');

        openActivitiesModal({
            title,
            date,
            org,
            activities
        });
    });
});
</script>

<!-- View Activities Modal -->
<div id="activitiesModal" class="hidden fixed inset-0 z-50 items-center justify-center">
  <!-- Backdrop -->
  <div class="absolute inset-0 bg-black/50" onclick="closeActivitiesModal()"></div>

  <!-- Modal -->
  <div class="relative w-[90%] max-w-md bg-white border-2 border-[#0e243a] rounded-xl">
    <div class="p-6">
      <!-- Logo / Title block -->
      <div class="flex justify-center">
        <img
          src="<?php echo e(asset('images/suhayLogo.png')); ?>" 
          alt="SUHAY"
          class="h-28 w-28 object-contain"
        />
      </div>

      <h2 id="modalEventTitle" class=" text-center text-[#0e243a] font-bold text-lg">
        <!-- Filled by JS -->
      </h2>

      <div class="mt-4">
        <div class="text-sm text-gray-600 mb-2 flex items-center gap-2 justify-start">
          <img
            src="<?php echo e(asset('images/VolunteerIcons/VDate.png')); ?>"
            alt="Date"
            class="h-4 w-4 object-contain"
          />
          <span id="modalEventDate"></span>
        </div>

        <div class="text-sm text-gray-700 flex items-center gap-2 justify-start mb-4">
          <img
            src="<?php echo e(asset('images/VolunteerIcons/VNGO.png')); ?>"
            alt="Organization"
            class="h-4 w-4 object-contain"
          />
          <span id="modalEventOrg"></span>
        </div>

        <h3 class="font-bold text-[#0e243a] mb-3">List of Activities</h3>

        <ul id="modalActivitiesList" class="list-none space-y-3">
          <!-- Filled by JS -->
        </ul>
      </div>

      <!-- Close button -->
      <div class="flex justify-end mt-6">
        <button
          type="button"
          onclick="closeActivitiesModal()"
          class="px-6 py-2 rounded-full bg-[#d39a11] text-white font-bold text-sm hover:bg-[#c2870d] transition"
        >
          Close
        </button>
      </div>
    </div>
  </div>
</div>

</body>
</html><?php /**PATH D:\Acads\MEt.A-Project-SUHAY\resources\views/Volunteers/events.blade.php ENDPATH**/ ?>