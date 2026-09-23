document.addEventListener("DOMContentLoaded", function () {

    // =========================================================
    // ELEMENTS
    // =========================================================

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

    // Event field in the modal
    const eventInput = document.getElementById("activityEvent");


    // =========================================================
    // OPEN MODAL - ADD MODE
    // =========================================================

    window.openLogActivityModal = function () {

        // Reset form first
        form.reset();

        // Make sure assignment ID is empty
        assignmentId.value = "";

        // Change title
        modalTitle.textContent = "Log Volunteer Activity";

        // Change button
        submitButton.textContent = "Save";

        // Store mode
        form.dataset.mode = "add";

        // Store action
        form.action = "/track-activity/store";

        // -----------------------------------------------------
        // Hide Remove Assignment button in ADD mode
        // -----------------------------------------------------

        if (removeButton) {
            removeButton.classList.add("hidden");
        }

        // -----------------------------------------------------
        // Clear Event field
        // -----------------------------------------------------

        if (eventInput) {
            eventInput.value = "";
        }

        // -----------------------------------------------------
        // Time In / Time Out are disabled initially.
        // We don't know the event date yet.
        // -----------------------------------------------------

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

        // Open modal
        modal.classList.remove("hidden");
        modal.classList.add("flex");
    };


    // =========================================================
    // OPEN MODAL - EDIT MODE
    // =========================================================

    window.openEditActivityModal = function (row) {

        // -----------------------------------------------------
        // Get data from table row
        // -----------------------------------------------------

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


        // -----------------------------------------------------
        // Put row data into modal
        // -----------------------------------------------------

        assignmentId.value =
            rowAssignmentId || "";

        volunteerSelect.value =
            rowAccountId || "";

        activitySelect.value =
            rowActivityId || "";

        // Event
        if (eventInput) {
            eventInput.value = rowEvent || "";
        }


        // PostgreSQL TIME fields
        timeInInput.value =
            formatTimeForInput(rowTimeIn);

        timeOutInput.value =
            formatTimeForInput(rowTimeOut);


        statusSelect.value =
            rowStatus || "0";


        // -----------------------------------------------------
        // Enable/disable time inputs based on EVENT DATE
        // -----------------------------------------------------

        updateTimeInputs(rowEventDate);


        // -----------------------------------------------------
        // Change modal to EDIT mode
        // -----------------------------------------------------

        modalTitle.textContent =
            "Edit Volunteer Activity";

        submitButton.textContent =
            "Update";

        form.dataset.mode =
            "edit";

        form.action =
            "/track-activity/update";


        // -----------------------------------------------------
        // SHOW REMOVE ASSIGNMENT BUTTON
        // -----------------------------------------------------

        if (removeButton) {

            // Show Remove Assignment only when assignment exists
            // and the activity is not completed.

            if (rowAssignmentId && rowStatus !== "1") {

                removeButton.classList.remove("hidden");

            } else {

                removeButton.classList.add("hidden");

            }
        }


        // -----------------------------------------------------
        // Open modal
        // -----------------------------------------------------

        modal.classList.remove("hidden");
        modal.classList.add("flex");
    };


    // =========================================================
    // CLOSE MODAL
    // =========================================================

    window.closeLogActivityModal = function () {

        modal.classList.add("hidden");
        modal.classList.remove("flex");
    };


    // =========================================================
    // CLICK TABLE ROW
    // =========================================================

    document.querySelectorAll(".activity-row").forEach(function (row) {

        row.addEventListener("click", function () {

            openEditActivityModal(row);

        });

    });


    // =========================================================
    // FORMAT TIME FOR <input type="time">
    // =========================================================
    /*
     * Database:
     * 15:50:22
     *
     * HTML input:
     * 15:50
     */

    function formatTimeForInput(value) {

        if (!value) {
            return "";
        }

        return value.substring(0, 5);
    }


    // =========================================================
    // ENABLE / DISABLE TIME INPUTS
    // BASED ON EVENT DATE
    // =========================================================

    function updateTimeInputs(eventDate) {

        const message =
            document.getElementById("timeDisabledMessage");


        // -----------------------------------------------------
        // No event date
        // -----------------------------------------------------

        if (!eventDate) {

            timeInInput.disabled = true;
            timeOutInput.disabled = true;

            if (message) {
                message.textContent =
                    "Time In and Time Out will be available on the event date.";
            }

            return;
        }


        // -----------------------------------------------------
        // Get today's local date
        // -----------------------------------------------------

        const today = new Date();

        const todayString =
            today.getFullYear() + "-" +
            String(today.getMonth() + 1).padStart(2, "0") + "-" +
            String(today.getDate()).padStart(2, "0");


        // -----------------------------------------------------
        // Event date is today or already passed
        // -----------------------------------------------------

        if (eventDate <= todayString) {

            timeInInput.disabled = false;
            timeOutInput.disabled = false;

            if (message) {
                message.textContent =
                    "Time In and Time Out are available.";
            }

        }


        // -----------------------------------------------------
        // Event date is in the future
        // -----------------------------------------------------

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


    // =========================================================
    // FILTER TABLE
    // =========================================================

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


    // =========================================================
    // REMOVE ASSIGNMENT
    // =========================================================

    async function removeAssignment(assignmentIdValue, event) {

        // -----------------------------------------------------
        // Prevent the table row click from triggering
        // -----------------------------------------------------

        if (event) {
            event.stopPropagation();
        }


        // -----------------------------------------------------
        // Make sure an assignment ID exists
        // -----------------------------------------------------

        if (!assignmentIdValue) {

            alert("No assignment selected.");

            return;
        }


        // -----------------------------------------------------
        // Confirmation
        // -----------------------------------------------------

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


            // -------------------------------------------------
            // Close modal
            // -------------------------------------------------

            closeLogActivityModal();


            // -------------------------------------------------
            // Refresh page
            // -------------------------------------------------

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


    // =========================================================
    // REMOVE BUTTON CLICK
    // =========================================================

    if (removeButton) {

        removeButton.addEventListener(
            "click",
            function (event) {

                // Get assignment ID currently in modal
                const currentAssignmentId =
                    assignmentId.value;

                removeAssignment(
                    currentAssignmentId,
                    event
                );

            }
        );

    }


    // =========================================================
    // LOGOUT MODAL
    // =========================================================

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