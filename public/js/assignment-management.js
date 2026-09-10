// =====================================================
// EVENT FILTERING
// =====================================================

function filterEventStatus() {
    // Re-apply all filters together
    filterEvents();
}


// =====================================================
// CLEAR FILTERS
// =====================================================

function clearFilters() {

    document.getElementById("searchInput").value = "";

    const fp = document.getElementById("dateFilter")._flatpickr;

    if (fp) {
        fp.clear();
    }

    // Reset to Upcoming Events
    document.getElementById("eventStatusFilter").value = "future";

    filterEvents();
}


// =====================================================
// DATE PICKER
// =====================================================

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

        filterEvents();
    }
});


// =====================================================
// SEARCH + DATE INPUT
// =====================================================

const searchInput = document.getElementById("searchInput");
const dateInput = document.getElementById("dateFilter");

searchInput.addEventListener("input", filterEvents);
dateInput.addEventListener("change", filterEvents);


// =====================================================
// FILTER EVENTS
// =====================================================

function filterEvents() {

    const search =
        searchInput.value.toLowerCase().trim();

    const date =
        dateInput.value;

    const statusFilter =
        document.getElementById("eventStatusFilter").value;


    document.querySelectorAll(".event-card").forEach(card => {

        const name =
            card.dataset.name || "";

        const eventDate =
            card.dataset.date || "";

        const eventStatus =
            card.dataset.eventStatus || "";


        // Search filter
        const matchSearch =
            name.includes(search);


        // Date filter
        const matchDate =
            !date || eventDate === date;


        // Event status filter
        const matchStatus =
            statusFilter === "all" ||
            eventStatus === statusFilter;


        // Show only if ALL filters match
        if (
            matchSearch &&
            matchDate &&
            matchStatus
        ) {

            card.style.display = "flex";

        } else {

            card.style.display = "none";
        }

    });
}


// =====================================================
// GLOBAL VARIABLES
// =====================================================

let currentEventId = null;
let currentEventStatus = "future";
let selectedActivityId = null;


// Cache volunteers PER EVENT
let cachedVolunteers = {};


// =====================================================
// LOGOUT MODAL
// =====================================================

function openLogoutModal() {

    const modal =
        document.getElementById('logoutModal');

    modal.classList.remove('hidden');
    modal.classList.add('flex');
}


function closeLogoutModal() {

    const modal =
        document.getElementById('logoutModal');

    modal.classList.add('hidden');
    modal.classList.remove('flex');
}


// =====================================================
// ACTIVITY MODAL
// =====================================================

function openActivities(
    eventId,
    eventName,
    eventStatus
) {

    // Store the current event
    currentEventId = eventId;

    // Store whether the event is upcoming or completed
    currentEventStatus = eventStatus;


    // Set modal title
    document.getElementById('modalTitle').innerText =
        eventName;


    // Loading message
    document.getElementById('activityList').innerHTML =
        'Loading...';


    // Open modal
    const modal =
        document.getElementById('activityModal');

    modal.classList.remove('hidden');
    modal.classList.add('flex');


    // Load activities
    loadActivities();
}


// =====================================================
// LOAD ACTIVITIES
// =====================================================

function loadActivities() {

    if (!currentEventId) {
        return;
    }


    fetch(`/events/${currentEventId}/activities`)

        .then(res => {

            if (!res.ok) {

                throw new Error(
                    "Failed to load activities"
                );
            }

            return res.json();
        })

        .then(data => {

            renderActivities(data);

        })

        .catch(err => {

            console.error(err);

            document.getElementById('activityList').innerHTML =
                '<p class="text-red-500">Failed to load activities.</p>';
        });
}


// =====================================================
// RENDER ACTIVITIES
// =====================================================

function renderActivities(data) {

    let html = '';


    // No activities
    if (!data || data.length === 0) {

        html =
            '<p class="text-gray-600">No activities found.</p>';

    } else {

        data.forEach(act => {

            let volunteersHTML = '';


            // =================================================
            // ASSIGNED VOLUNTEERS
            // =================================================

            if (
                act.volunteer_assignments &&
                act.volunteer_assignments.length > 0
            ) {

                volunteersHTML = `
                    <div class="mt-2 text-sm text-gray-600">

                        <p class="font-semibold">
                            Assigned Volunteers:
                        </p>

                        <ul class="list-disc ml-5">

                            ${act.volunteer_assignments.map(v => `

                                <li class="flex justify-between items-center gap-2 mb-2">

                                    <span>
                                        ${v.accounts.first_name}
                                        ${v.accounts.last_name}
                                    </span>

                                    ${
                                        // REMOVE BUTTON
                                        // Only show for UPCOMING events
                                        currentEventStatus !== "done"
                                            ? `
                                                <button
                                                    type="button"
                                                    onclick="removeAssignment(${v.id})"
                                                    class="bg-red-500 text-white px-3 py-1 rounded-full text-xs ml-2 hover:bg-red-600"
                                                >
                                                    Remove
                                                </button>
                                            `
                                            : ''
                                    }

                                </li>

                            `).join('')}

                        </ul>

                    </div>
                `;

            } else {

                volunteersHTML = `
                    <p class="text-xs text-gray-400 mt-2">
                        No volunteers assigned
                    </p>
                `;
            }


            // =================================================
            // ACTIVITY CARD
            // =================================================

            html += `

                <div
                    class="border p-3 rounded-lg flex justify-between items-start"
                >

                    <div class="flex-1">

                        <p class="font-semibold">
                            ${act.name}
                        </p>

                        <p class="text-sm text-gray-500">
                            ${act.remarks ?? ''}
                        </p>

                        ${volunteersHTML}

                    </div>


                    ${
                        // ASSIGN BUTTON
                        // Only show for UPCOMING events
                        currentEventStatus !== "done"
                            ? `
                                <button
                                    type="button"
                                    onclick="assignVolunteer(${act.id})"
                                    class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600 ml-3"
                                >
                                    Assign
                                </button>
                            `
                            : ''
                    }

                </div>

            `;
        });
    }


    // Display activities
    document.getElementById('activityList').innerHTML =
        html;
}


// =====================================================
// REMOVE ASSIGNMENT
// =====================================================

function removeAssignment(assignmentId) {

    // Extra protection:
    // Completed events cannot remove assignments
    if (currentEventStatus === "done") {

        alert(
            "Volunteers cannot be removed from completed events."
        );

        return;
    }


    if (
        !confirm(
            "Remove this volunteer from the activity?"
        )
    ) {

        return;
    }


    fetch(
        `/remove-assignment/${assignmentId}`,
        {
            method: 'DELETE',

            headers: {

                'X-CSRF-TOKEN':
                    document
                        .querySelector(
                            'meta[name="csrf-token"]'
                        )
                        .getAttribute('content'),

                'Accept': 'application/json'
            }
        }
    )

    .then(async res => {

        const data =
            await res.json().catch(() => ({}));


        if (!res.ok) {

            throw new Error(
                data.message ||
                "Failed to remove assignment."
            );
        }


        alert("Removed successfully!");


        // Refresh activities
        await refreshActivities();


        // Refresh event card count
        await refreshEventAssignmentCount(
            currentEventId
        );


        // Clear volunteer cache
        delete cachedVolunteers[currentEventId];

    })

    .catch(err => {

        console.error(err);

        alert(
            err.message ||
            "Error removing assignment."
        );
    });
}


// =====================================================
// REFRESH ACTIVITIES
// =====================================================

async function refreshActivities() {

    if (!currentEventId) {
        return;
    }


    document.getElementById('activityList').innerHTML =
        'Refreshing...';


    try {

        const res =
            await fetch(
                `/events/${currentEventId}/activities`
            );


        if (!res.ok) {

            throw new Error(
                "Failed to refresh activities."
            );
        }


        const data =
            await res.json();


        renderActivities(data);

    }

    catch (err) {

        console.error(err);

        document.getElementById('activityList').innerHTML =
            '<p class="text-red-500">Failed to refresh activities.</p>';
    }
}


// =====================================================
// CLOSE ACTIVITY MODAL
// =====================================================

function closeModal() {

    const modal =
        document.getElementById('activityModal');


    modal.classList.add('hidden');
    modal.classList.remove('flex');


    document.getElementById('activityList').innerHTML =
        '';
}


// =====================================================
// VOLUNTEER MODAL
// =====================================================

function assignVolunteer(activityId) {

    // Extra protection:
    // Completed events cannot assign volunteers
    if (currentEventStatus === "done") {

        alert(
            "Volunteers cannot be assigned to completed events."
        );

        return;
    }


    selectedActivityId = activityId;


    const modal =
        document.getElementById('volunteerModal');


    modal.classList.remove('hidden');
    modal.classList.add('flex');


    document.getElementById('volunteerList').innerHTML =
        'Loading...';


    // =================================================
    // USE CACHE IF AVAILABLE
    // =================================================

    if (cachedVolunteers[currentEventId]) {

        renderVolunteers(
            cachedVolunteers[currentEventId]
        );

        return;
    }


    // =================================================
    // FETCH VOLUNTEERS FOR THIS EVENT ONLY
    // =================================================

    fetch(
        `/api/volunteers?event_id=${currentEventId}`
    )

        .then(res => {

            if (!res.ok) {

                throw new Error(
                    "Failed to load volunteers."
                );
            }

            return res.json();
        })

        .then(data => {

            // Cache volunteers PER EVENT
            cachedVolunteers[currentEventId] =
                data;


            renderVolunteers(data);

        })

        .catch(err => {

            console.error(err);

            document.getElementById('volunteerList').innerHTML =
                '<p class="text-red-500">Failed to load volunteers.</p>';
        });
}


// =====================================================
// RENDER VOLUNTEERS
// =====================================================

function renderVolunteers(data) {

    let html = '';


    if (!data || data.length === 0) {

        html = `
            <p class="text-gray-500">
                No volunteers applied for this event.
            </p>
        `;

    } else {

        data.forEach(vol => {

            html += `

                <div
                    class="border p-3 rounded-lg flex justify-between items-center"
                >

                    <div>

                        <p class="font-semibold">
                            ${vol.name}
                        </p>

                        <p class="text-sm text-gray-500">
                            ${vol.email}
                        </p>

                    </div>


                    <button
                        type="button"
                        class="assign-btn bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600"
                        data-id="${vol.id}"
                    >
                        Select
                    </button>

                </div>

            `;
        });
    }


    document.getElementById('volunteerList').innerHTML =
        html;


    // =================================================
    // SELECT VOLUNTEER BUTTONS
    // =================================================

    document
        .querySelectorAll('.assign-btn')
        .forEach(btn => {

            btn.addEventListener(
                'click',
                function(e) {

                    e.stopPropagation();


                    const volunteerId =
                        this.getAttribute(
                            'data-id'
                        );


                    confirmAssign(
                        volunteerId
                    );
                }
            );
        });
}


// =====================================================
// ASSIGN VOLUNTEER
// =====================================================

async function confirmAssign(volunteerId) {

    // Extra protection
    if (currentEventStatus === "done") {

        alert(
            "Volunteers cannot be assigned to completed events."
        );

        return;
    }


    console.log("SELECT CLICKED");
    console.log(
        "Volunteer:",
        volunteerId
    );
    console.log(
        "Activity:",
        selectedActivityId
    );
    console.log(
        "Event:",
        currentEventId
    );


    try {

        const res =
            await fetch(
                `/assign-volunteer`,
                {

                    method: 'POST',

                    headers: {

                        'Content-Type':
                            'application/json',

                        'X-CSRF-TOKEN':
                            document
                                .querySelector(
                                    'meta[name="csrf-token"]'
                                )
                                .getAttribute(
                                    'content'
                                ),

                        'Accept':
                            'application/json'
                    },


                    body: JSON.stringify({

                        activity_id:
                            selectedActivityId,

                        account_id:
                            volunteerId,

                        event_id:
                            currentEventId,

                        status: 1

                    })
                }
            );


        const data =
            await res.json().catch(
                () => ({})
            );


        // Already assigned
        if (res.status === 409) {

            alert(
                data.message ||
                "This volunteer is already assigned to this activity."
            );

            return;
        }


        // Other errors
        if (!res.ok) {

            alert(
                data.message ||
                "Assign failed."
            );

            return;
        }


        alert(
            "Assigned successfully!"
        );


        // Close volunteer modal
        closeVolunteerModal();


        // Clear cache for this event
        delete cachedVolunteers[
            currentEventId
        ];


        // Refresh activities
        await refreshActivities();


        // Refresh assigned count
        await refreshEventAssignmentCount(
            currentEventId
        );

    }

    catch (err) {

        console.error(
            "FETCH FAILED:",
            err
        );

        alert(
            "Request failed."
        );
    }
}


// =====================================================
// REFRESH ASSIGNED VOLUNTEER COUNT
// =====================================================

async function refreshEventAssignmentCount(
    eventId
) {

    if (!eventId) {
        return;
    }


    try {

        const res =
            await fetch(
                `/events/${eventId}/assigned-count`
            );


        if (!res.ok) {

            throw new Error(
                "Failed to refresh assignment count."
            );
        }


        const data =
            await res.json();


        /*
         * Event card:
         *
         * data-event-id="{{ $event['id'] }}"
         *
         * Count:
         *
         * data-assigned-count
         */


        const countElements =
            document.querySelectorAll(
                `[data-event-id="${eventId}"] [data-assigned-count]`
            );


        countElements.forEach(
            element => {

                element.textContent =
                    data.count;
            }
        );

    }

    catch (err) {

        console.error(
            "Failed to refresh assignment count:",
            err
        );
    }
}


// =====================================================
// CLOSE VOLUNTEER MODAL
// =====================================================

function closeVolunteerModal() {

    const modal =
        document.getElementById(
            'volunteerModal'
        );


    modal.classList.add('hidden');
    modal.classList.remove('flex');


    document.getElementById(
        'volunteerList'
    ).innerHTML = '';
}


// =====================================================
// MAKE FUNCTIONS AVAILABLE TO HTML
// =====================================================

window.openActivities =
    openActivities;

window.closeModal =
    closeModal;

window.assignVolunteer =
    assignVolunteer;

window.confirmAssign =
    confirmAssign;

window.removeAssignment =
    removeAssignment;

window.closeVolunteerModal =
    closeVolunteerModal;

window.openLogoutModal =
    openLogoutModal;

window.closeLogoutModal =
    closeLogoutModal;

window.filterEventStatus =
    filterEventStatus;

window.filterEvents =
    filterEvents;

window.clearFilters =
    clearFilters;