<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <title>Assignments</title>

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
            <a href="/service-management"
           class="bg-[#f2c94c] px-6 py-2 rounded-full font-semibold">
                Volunteer Lists
            </a>

            <a href="/applications"
            class="bg-[#f2c94c] px-6 py-2 rounded-full font-semibold">
                Applications
            </a>

            <a href="/assignments"
            class="bg-gray-200 px-6 py-2 rounded-full font-semibold">
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

        <div class="bg-[#0e243a] p-6 rounded-3xl">
            <div class="flex justify-between items-center mb-4 flex-wrap gap-3">

                <div class="flex items-center">
                    <input id="searchInput" type="text"
                        placeholder="Search Programs..."
                        class="p-2 rounded-l-md border outline-none focus:ring-2 focus:ring-[#0e243a]">

                    <button class="bg-[#0e243a] px-4 py-2 rounded-r-md hover:bg-[#0e243a] flex items-center justify-center">
                    <img src="/images/searchbar.png"
                        class="w-8 h-8"
                        alt="Search"
                        onmouseover="this.src='/images/searchbar_hover.png'"
                        onmouseout="this.src='/images/searchbar.png'">
                    </button>
                </div>

              <!-- RIGHT: Date + Clear -->
                <div class="flex items-center gap-3">
                    <input id="dateFilter"
                        class="p-2 rounded-md border"
                        placeholder="Select date">

                    <button 
                        type="button"
                        onclick="clearFilters()"
                        class="bg-[#f2c94c] text-[#0e243a] px-4 py-2 rounded-md hover:bg-[#edd07a] font-semibold"
                    >
                        Clear
                    </button>
                </div>
            </div>

            <div class="space-y-4">
            <?php $__empty_1 = true; $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            
                <div 
                    class="event-card cursor-pointer bg-gray-300 p-5 rounded-xl flex justify-between items-center hover:scale-[1.02] hover:shadow-xl hover:bg-[#f2c94c]"
                    data-name="<?php echo e(strtolower($event['name'])); ?>"
                    data-date="<?php echo e($event['date']); ?>"
                    onclick='openActivities(<?php echo e($event["id"]); ?>, <?php echo json_encode($event["name"], 15, 512) ?>)'
                >
                    <div>
                        <p class="font-bold text-lg text-[#0e243a] mb-1">
                            <?php echo e(strtoupper($event['name'])); ?>

                        </p>

                        <p class="text-sm text-gray-700">
                            <?php echo e($event['date'] 
                                ? \Carbon\Carbon::parse($event['date'])->format('F d, Y') 
                                : 'No date set'); ?>

                        </p>

                        <p class="text-sm text-gray-700">
                            <?php echo e($event['description']); ?>

                        </p>
                    </div>

                    <div class="text-right">
                        <p class="mt-2">
                            Status:
                            <?php if($event['status'] == 1): ?>
                                <span class="bg-green-500 text-white px-4 py-1 rounded-full text-sm">
                                    Active
                                </span>
                            <?php else: ?>
                                <span class="bg-gray-500 text-white px-4 py-1 rounded-full text-sm">
                                    Archived
                                </span>
                            <?php endif; ?>
                        </p>

                        <p class="text-sm text-gray-700 mt-1">
                            👥 <?php echo e($event['volunteer_count'] ?? 0); ?> volunteers assigned
                        </p>
                    </div>

                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="text-white">No assignments available.</p>
            <?php endif; ?>
        </div>
            </div>
        </div>
    </div>
</div>
<?php echo $__env->make('components.activity-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('components.logout-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<script>
function clearFilters() {
    document.getElementById("searchInput").value = "";

    const fp = document.getElementById("dateFilter")._flatpickr;
    if (fp) fp.clear();

    document.querySelectorAll(".event-card").forEach(card => {
        card.style.display = "flex";
    });
}
    const eventDates = <?php echo json_encode($events->pluck('date')->filter()->values(), 15, 512) ?>;

    flatpickr("#dateFilter", {
    dateFormat: "Y-m-d",

    onDayCreate: function(dObj, dStr, fp, dayElem) {
        const d = dayElem.dateObj;

        const year = d.getFullYear();
        const month = String(d.getMonth() + 1).padStart(2, '0');
        const day = String(d.getDate()).padStart(2, '0');

        const date = `${year}-${month}-${day}`;

        if (eventDates.includes(date)) {
            dayElem.style.backgroundColor = "#f2c94c";
            dayElem.style.borderRadius = "50%";
            dayElem.style.fontWeight = "bold";
        }
    },

    onChange: function(selectedDates, dateStr) {
        filterEventsByDate(dateStr);
    }
});
</script>
<script>
    const searchInput = document.getElementById("searchInput");
    const dateInput = document.getElementById("dateFilter");

    searchInput.addEventListener("input", filterEvents);
    dateInput.addEventListener("change", filterEvents);

    function filterEventsByDate(date) {
        document.querySelectorAll(".event-card").forEach(card => {
            const match = !date || card.dataset.date === date;
            card.style.display = match ? "flex" : "none";
        });
    }
    function filterEvents() {
        const search = searchInput.value.toLowerCase();
        const date = dateInput.value;

        document.querySelectorAll(".event-card").forEach(card => {
            const name = card.dataset.name;
            const eventDate = card.dataset.date;

            let matchSearch = name.includes(search);
            let matchDate = !date || eventDate === date;

            if (matchSearch && matchDate) {
                card.style.display = "flex";
            } else {
                card.style.display = "none";
            }
        });
    }
</script>
<script>
let currentEventId = null;
// =====================
// LOGOUT MODAL
// =====================
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


// =====================
// ACTIVITY MODAL
// =====================
function openActivities(eventId, eventName) {
    currentEventId = eventId; // ✅ ADD THIS

    document.getElementById('modalTitle').innerText = eventName;
    document.getElementById('activityList').innerHTML = 'Loading...';

    const modal = document.getElementById('activityModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');

    fetch(`/events/${eventId}/activities`)
        .then(res => res.json())
        .then(data => {
            let html = '';

            if (!data || data.length === 0) {
                html = '<p class="text-gray-600">No activities found.</p>';
            } else {
                data.forEach(act => {

                    let volunteersHTML = '';

                    if (act.volunteer_assignments && act.volunteer_assignments.length > 0) {
                        volunteersHTML = `
                            <div class="mt-2 text-sm text-gray-600">
                                <p class="font-semibold">Assigned Volunteers:</p>
                                <ul class="list-disc ml-5">
                                    ${act.volunteer_assignments.map(v => `
                                        <li>
                                            ${v.accounts.first_name} ${v.accounts.last_name}
                                        </li>
                                    `).join('')}
                                </ul>
                            </div>
                        `;
                    } else {
                        volunteersHTML = `<p class="text-xs text-gray-400 mt-2">No volunteers assigned</p>`;
                    }

                    html += `
                        <div class="border p-3 rounded-lg flex justify-between items-start">
                            <div>
                                <p class="font-semibold">${act.name}</p>
                                <p class="text-sm text-gray-500">${act.remarks ?? ''}</p>

                                ${volunteersHTML}
                            </div>

                            <button onclick="assignVolunteer(${act.id})"
                                class="bg-green-500 text-white px-3 py-1 rounded">
                                Assign
                            </button>
                        </div>
                    `;
                });
            }

            document.getElementById('activityList').innerHTML = html;
        })
        .catch(err => {
            console.error(err);
            document.getElementById('activityList').innerHTML =
                '<p class="text-red-500">Failed to load activities.</p>';
        });
}
function refreshActivities() {
    if (!currentEventId) return;

    fetch(`/events/${currentEventId}/activities`)
        .then(res => res.json())
        .then(data => {

            let html = '';

            data.forEach(act => {

                let volunteersHTML = '';

                if (act.volunteer_assignments && act.volunteer_assignments.length > 0) {
                    volunteersHTML = `
                        <div class="mt-2 text-sm text-gray-600">
                            <p class="font-semibold">Assigned Volunteers:</p>
                            <ul class="list-disc ml-5">
                                ${act.volunteer_assignments.map(v => `
                                    <li>${v.accounts.first_name} ${v.accounts.last_name}</li>
                                `).join('')}
                            </ul>
                        </div>
                    `;
                } else {
                    volunteersHTML = `<p class="text-xs text-gray-400 mt-2">No volunteers assigned</p>`;
                }

                html += `
                    <div class="border p-3 rounded-lg flex justify-between items-start">
                        <div>
                            <p class="font-semibold">${act.name}</p>
                            <p class="text-sm text-gray-500">${act.remarks ?? ''}</p>
                            ${volunteersHTML}
                        </div>

                        <button onclick="assignVolunteer(${act.id})"
                            class="bg-green-500 text-white px-3 py-1 rounded">
                            Assign
                        </button>
                    </div>
                `;
            });

            document.getElementById('activityList').innerHTML = html;
        });
}

function closeModal() {
    const modal = document.getElementById('activityModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');

    document.getElementById('activityList').innerHTML = '';
}


// =====================
// VOLUNTEER MODAL
// =====================
let selectedActivityId = null;
let cachedVolunteers = null;

function assignVolunteer(activityId) {
    selectedActivityId = activityId;

    const modal = document.getElementById('volunteerModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');

    document.getElementById('volunteerList').innerHTML = 'Loading...';

    if (cachedVolunteers) {
        renderVolunteers(cachedVolunteers);
        return;
    }

   fetch(`/api/volunteers`)
        .then(res => res.json())
        .then(data => {
            cachedVolunteers = data;
            renderVolunteers(data);
        })
        .catch(err => {
            console.error(err);
            document.getElementById('volunteerList').innerHTML =
                '<p class="text-red-500">Failed to load volunteers.</p>';
        });
}

function renderVolunteers(data) {
    let html = '';

    if (!data || data.length === 0) {
        html = '<p>No volunteers available.</p>';
    } else {
        data.forEach(vol => {
            html += `
                <div class="border p-3 rounded-lg flex justify-between items-center">
                    <div>
                        <p class="font-semibold">${vol.name}</p>
                        <p class="text-sm text-gray-500">${vol.email}</p>
                    </div>

                    <button class="assign-btn bg-blue-500 text-white px-3 py-1 rounded"
                            data-id="${vol.id}">
                        Select
                    </button>           
                </div>
            `;
        });
    }

    document.getElementById('volunteerList').innerHTML = html;

    // ✅ attach events AFTER rendering
    document.querySelectorAll('.assign-btn').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.stopPropagation();
            const volunteerId = this.getAttribute('data-id');
            confirmAssign(volunteerId);
        });
    });
}

function confirmAssign(volunteerId) {
    console.log("SELECT CLICKED");
    console.log("Volunteer:", volunteerId);
    console.log("Activity:", selectedActivityId);

    fetch(`/assign-volunteer`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            activity_id: selectedActivityId,
            account_id: volunteerId,
            date: new Date().toISOString().split('T')[0],
            status: 1
        })
    })
    .then(async res => {
    const data = await res.json();

    if (res.status === 409) {
        alert("This volunteer is already assigned to this activity.");
        return;
    }

    if (!res.ok) {
        alert("Assign failed");
        return;
    }

    alert("Assigned successfully!");
    closeVolunteerModal();
})
    .catch(err => {
        console.error("FETCH FAILED:", err);
        alert("Request failed");
    });

      refreshActivities();
      location.reload();
}
function closeVolunteerModal() {
    const modal = document.getElementById('volunteerModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');

    document.getElementById('volunteerList').innerHTML = '';
}
window.confirmAssign = confirmAssign;

</script>
</body>
</html><?php /**PATH C:\sysands\MEt.A-Project-SUHAY-elisha\resources\views/assignments.blade.php ENDPATH**/ ?>