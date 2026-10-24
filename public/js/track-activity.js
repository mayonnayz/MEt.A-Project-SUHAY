document.addEventListener("DOMContentLoaded", function () {
    const modal = document.getElementById("logActivityModal");
    const form = document.getElementById("logActivityForm");

    const modalTitle = document.getElementById("logActivityModalTitle");
    const submitButton = document.getElementById("logActivitySubmitButton");
    const removeButton = document.getElementById("removeAssignmentButton");

    const assignmentId = document.getElementById("activityAssignmentId");
    const volunteerSelect = document.getElementById("activityVolunteer");
    const activitySelect = document.getElementById("activitySelect");
    const timeInInput = document.getElementById("activityTimeIn");
    const timeOutInput = document.getElementById("activityTimeOut");
    const statusSelect = document.getElementById("activityStatus");

    const eventFilter = document.getElementById("activityFilter");
    const searchInput = document.getElementById("searchInput");

    const eventInput = document.getElementById("activityEvent");

    window.openLogActivityModal = function () {
        form.reset();
        assignmentId.value = "";
        modalTitle.textContent = "Log Volunteer Activity";
        submitButton.textContent = "Save";
        form.dataset.mode = "add";
        form.action = "/track-activity/store";

        if (removeButton) {
            removeButton.classList.add("hidden");
        }

        if (eventInput) {
            eventInput.value = "";
        }

        timeInInput.disabled = true;
        timeOutInput.disabled = true;

        timeInInput.value = "";
        timeOutInput.value = "";

        const message =
            document.getElementById("timeDisabledMessage");

        if (message) {
            message.textContent =
                "Time In and Time Out will be available on the event date.";
        }
        modal.classList.remove("hidden");
        modal.classList.add("flex");
    };

    window.openEditActivityModal = function (row) {
        const rowAssignmentId =
            row.dataset.assignmentId;

        const rowAccountId =
            row.dataset.accountId;

        const rowActivityId =
            row.dataset.activityId;

        const rowEvent =
            row.dataset.event;

        const rowEventDate =
            row.dataset.eventDate;

        const rowTimeIn =
            row.dataset.timeIn;

        const rowTimeOut =
            row.dataset.timeOut;

        const rowStatus =
            row.dataset.status;

        assignmentId.value =
            rowAssignmentId || "";

        volunteerSelect.value =
            rowAccountId || "";

        activitySelect.value =
            rowActivityId || "";

        if (eventInput) {
            eventInput.value = rowEvent || "";
        }

        timeInInput.value =
            formatTimeForInput(rowTimeIn);

        timeOutInput.value =
            formatTimeForInput(rowTimeOut);


        statusSelect.value =
            rowStatus || "0";

        updateTimeInputs(rowEventDate);

        modalTitle.textContent =
            "Edit Volunteer Activity";

        submitButton.textContent =
            "Update";

        form.dataset.mode =
            "edit";

        form.action =
            "/track-activity/update";

        if (removeButton) {
            if (rowAssignmentId && rowStatus !== "1") {

                removeButton.classList.remove("hidden");

            } else {

                removeButton.classList.add("hidden");

            }
        }

        modal.classList.remove("hidden");
        modal.classList.add("flex");
    };

    window.closeLogActivityModal = function () {

        modal.classList.add("hidden");
        modal.classList.remove("flex");
    };

    document.querySelectorAll(".activity-row").forEach(function (row) {

        row.addEventListener("click", function () {

            openEditActivityModal(row);

        });

    });

    function formatTimeForInput(value) {

        if (!value) {
            return "";
        }

        return value.substring(0, 5);
    }

    function updateTimeInputs(eventDate) {

        const message =
            document.getElementById("timeDisabledMessage");

        if (!eventDate) {

            timeInInput.disabled = true;
            timeOutInput.disabled = true;

            if (message) {
                message.textContent =
                    "Time In and Time Out will be available on the event date.";
            }

            return;
        }

        const today = new Date();

        const todayString =
            today.getFullYear() + "-" +
            String(today.getMonth() + 1).padStart(2, "0") + "-" +
            String(today.getDate()).padStart(2, "0");

        if (eventDate <= todayString) {

            timeInInput.disabled = false;
            timeOutInput.disabled = false;

            if (message) {
                message.textContent =
                    "Time In and Time Out are available.";
            }

        }

        else {

            timeInInput.disabled = true;
            timeOutInput.disabled = true;

            timeInInput.value = "";
            timeOutInput.value = "";

            if (message) {
                message.textContent =
                    "Time In and Time Out will be available on the event date.";
            }

        }

    }

    function filterTable() {

        const event =
            eventFilter.value.toLowerCase();

        const search =
            searchInput.value.toLowerCase().trim();


        document.querySelectorAll(".activity-row").forEach(function (row) {

            const rowEvent =
                (row.dataset.event || "").toLowerCase();

            const rowName =
                (row.dataset.name || "").toLowerCase();


            const matchEvent =
                event === "" || rowEvent === event;

            const matchSearch =
                search === "" || rowName.includes(search);


            row.style.display =
                (matchEvent && matchSearch)
                    ? ""
                    : "none";

        });

    }


    if (eventFilter) {

        eventFilter.addEventListener(
            "change",
            filterTable
        );

    }


    if (searchInput) {

        searchInput.addEventListener(
            "input",
            filterTable
        );

    }

    async function removeAssignment(assignmentIdValue, event) {
        if (event) {
            event.stopPropagation();
        }

        if (!assignmentIdValue) {

            alert("No assignment selected.");

            return;
        }

        if (!confirm(
            "Remove this volunteer from this activity?"
        )) {
            return;
        }


        try {

            const res = await fetch(
                `/remove-assignment/${assignmentIdValue}`,
                {
                    method: "DELETE",

                    headers: {
                        "X-CSRF-TOKEN":
                            document
                                .querySelector(
                                    'meta[name="csrf-token"]'
                                )
                                .getAttribute("content"),

                        "Accept": "application/json"
                    }
                }
            );


            const data =
                await res.json().catch(() => ({}));


            if (!res.ok) {

                throw new Error(
                    data.message ||
                    "Failed to remove assignment."
                );
            }


            alert(
                "Assignment removed successfully."
            );

            closeLogActivityModal();

            location.reload();


        } catch (error) {

            console.error(
                "Remove assignment error:",
                error
            );

            alert(
                error.message ||
                "Failed to remove assignment."
            );
        }
    }

    if (removeButton) {

        removeButton.addEventListener(
            "click",
            function (event) {
                const currentAssignmentId =
                    assignmentId.value;

                removeAssignment(
                    currentAssignmentId,
                    event
                );

            }
        );

    }

    window.openLogoutModal = function () {

        const logoutModal =
            document.getElementById("logoutModal");

        if (logoutModal) {

            logoutModal.classList.remove("hidden");

            logoutModal.classList.add("flex");

        }

    };


    window.closeLogoutModal = function () {

        const logoutModal =
            document.getElementById("logoutModal");

        if (logoutModal) {

            logoutModal.classList.add("hidden");

            logoutModal.classList.remove("flex");

        }

    };

});