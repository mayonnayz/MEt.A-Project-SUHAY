function filterEventStatus() {
    filterEvents();
}

function clearFilters() {

    document.getElementById("searchInput").value = "";

    const fp = document.getElementById("dateFilter")._flatpickr;

    if (fp) {
        fp.clear();
    }

    document.getElementById("eventStatusFilter").value = "future";

    filterEvents();
}

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

const searchInput = document.getElementById("searchInput");
const dateInput = document.getElementById("dateFilter");

searchInput.addEventListener("input", filterEvents);
dateInput.addEventListener("change", filterEvents);

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

        const matchSearch =
            name.includes(search);

        const matchDate =
            !date || eventDate === date;

        const matchStatus =
            statusFilter === "all" ||
            eventStatus === statusFilter;

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

let currentEventId = null;
let currentEventStatus = "future";
let selectedActivityId = null;
let cachedVolunteers = {};

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

function openActivities(
    eventId,
    eventName,
    eventStatus
) {
    currentEventId = eventId;
    currentEventStatus = eventStatus;

    document.getElementById('modalTitle').innerText =
        eventName;
    document.getElementById('activityList').innerHTML =
        'Loading...';

    const modal =
        document.getElementById('activityModal');

    modal.classList.remove('hidden');
    modal.classList.add('flex');

    loadActivities();
}

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

function renderActivities(data) {

    let html = '';

    if (!data || data.length === 0) {

        html =
            '<p class="text-gray-600">No activities found.</p>';

    } else {

        data.forEach(act => {

            let volunteersHTML = '';

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

    document.getElementById('activityList').innerHTML =
        html;
}

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

function closeModal() {

    const modal =
        document.getElementById('activityModal');


    modal.classList.add('hidden');
    modal.classList.remove('flex');


    document.getElementById('activityList').innerHTML =
        '';
}

function assignVolunteer(activityId) {
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

    if (cachedVolunteers[currentEventId]) {

        renderVolunteers(
            cachedVolunteers[currentEventId]
        );

        return;
    }

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

function renderVolunteers(data) {
    let html = "";

    if (!data || data.length === 0) {
        html = `
            <p class="text-gray-500">
                No volunteers applied for this event.
            </p>
        `;
    } else {
        data.forEach(vol => {
            html += `
                <div class="border p-3 rounded-lg flex justify-between items-center">
                    <div>
                        <p class="font-semibold">${escapeHTML(vol.name)}</p>
                        <p class="text-sm text-gray-500">${escapeHTML(vol.email)}</p>
                    </div>

                    <button
                        type="button"
                        class="assign-btn bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600"
                        data-id="${escapeHTML(String(vol.id))}"
                    >
                        Select
                    </button>
                </div>
            `;
        });
    }

    document.getElementById("volunteerList").innerHTML = html;

    document.querySelectorAll(".assign-btn").forEach(btn => {
        btn.addEventListener("click", function (e) {
            e.stopPropagation();

            const volunteerId = this.dataset.id;

            const volunteer = data.find(
                vol => String(vol.id) === String(volunteerId)
            );

            if (!volunteer) return;

            showConfirmModal({
                title: "Confirm Volunteer Assignment",
                message: `Are you sure you want to assign ${volunteer.name} to this activity?`,
                confirmText: "Confirm Assignment",
                cancelText: "Cancel",
                onConfirm: () => confirmAssign(volunteerId)
            });
        });
    });
}

function escapeHTML(value) {
    return String(value ?? "").replace(/[&<>"']/g, char => ({
        "&": "&amp;",
        "<": "&lt;",
        ">": "&gt;",
        '"': "&quot;",
        "'": "&#39;"
    })[char]);
}

async function confirmAssign(volunteerId) {
    if (currentEventStatus === "done") {
        return {
            success: false,
            title: "Assignment Not Allowed",
            message: "Volunteers cannot be assigned to completed events."
        };
    }

    console.log("Volunteer:", volunteerId);
    console.log("Activity:", selectedActivityId);
    console.log("Event:", currentEventId);

    try {
        const res = await fetch("/assign-volunteer", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
                "Accept": "application/json"
            },
            body: JSON.stringify({
                activity_id: selectedActivityId,
                account_id: volunteerId,
                event_id: currentEventId,
                status: 0
            })
        });

        const data = await res.json().catch(() => ({}));

        if (res.status === 409) {
            return {
                success: false,
                title: "Already Assigned",
                message: data.message ||
                    "This volunteer is already assigned to this activity."
            };
        }

        if (!res.ok) {
            return {
                success: false,
                title: "Assignment Failed",
                message: data.message ||
                    "The assignment could not be completed. Please try again."
            };
        }
        closeVolunteerModal();

        delete cachedVolunteers[currentEventId];

        await refreshActivities();
        await refreshEventAssignmentCount(currentEventId);

        return {
            success: true,
            title: "Assignment Successful",
            message: "The volunteer has been assigned successfully."
        };

    } catch (err) {
        console.error("Assignment request failed:", err);

        return {
            success: false,
            title: "Request Failed",
            message: "Unable to complete the request. Please check your connection and try again."
        };
    }
}

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

window.openActivities =
    openActivities;

window.closeModal =
    closeModal;

window.assignVolunteer =
    assignVolunteer;

window.confirmAssign =
    confirmAssign;


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