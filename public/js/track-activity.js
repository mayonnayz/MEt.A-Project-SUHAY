document.addEventListener("DOMContentLoaded", function () {

    // =========================================================
    // LOG ACTIVITY MODAL
    // =========================================================

    window.openLogActivityModal = function () {

        const modal = document.getElementById("logActivityModal");

        if (!modal) {
            console.error("logActivityModal not found.");
            return;
        }

        modal.classList.remove("hidden");
        modal.classList.add("flex");
    };


    window.closeLogActivityModal = function () {

        const modal = document.getElementById("logActivityModal");

        if (!modal) {
            return;
        }

        modal.classList.add("hidden");
        modal.classList.remove("flex");
    };


    // =========================================================
    // LOGOUT MODAL
    // =========================================================

    window.openLogoutModal = function () {

        const modal = document.getElementById("logoutModal");

        if (!modal) {
            console.error("logoutModal not found.");
            return;
        }

        modal.classList.remove("hidden");
        modal.classList.add("flex");
    };


    window.closeLogoutModal = function () {

        const modal = document.getElementById("logoutModal");

        if (!modal) {
            return;
        }

        modal.classList.add("hidden");
        modal.classList.remove("flex");
    };


    // =========================================================
    // EVENT FILTER
    // =========================================================

    const eventFilter =
        document.getElementById("activityFilter");


    // =========================================================
    // VOLUNTEER SEARCH
    // =========================================================

    const searchInput =
        document.getElementById("searchInput");


    // =========================================================
    // FILTER TABLE
    // =========================================================

    function filterTable() {

        const selectedEvent =
            eventFilter
                ? eventFilter.value.toLowerCase().trim()
                : "";


        const search =
            searchInput
                ? searchInput.value.toLowerCase().trim()
                : "";


        const rows =
            document.querySelectorAll(".activity-row");


        rows.forEach(function (row) {

            const rowEvent =
                (row.dataset.event || "")
                    .toLowerCase()
                    .trim();


            const rowName =
                (row.dataset.name || "")
                    .toLowerCase()
                    .trim();


            // Event match
            const matchEvent =
                selectedEvent === "" ||
                rowEvent === selectedEvent;


            // Volunteer name match
            const matchSearch =
                search === "" ||
                rowName.includes(search);


            // Show / hide
            if (matchEvent && matchSearch) {

                row.style.display = "";

            } else {

                row.style.display = "none";

            }

        });

    }


    // =========================================================
    // EVENT FILTER LISTENER
    // =========================================================

    if (eventFilter) {

        eventFilter.addEventListener(
            "change",
            filterTable
        );

    }


    // =========================================================
    // SEARCH LISTENER
    // =========================================================

    if (searchInput) {

        searchInput.addEventListener(
            "input",
            filterTable
        );

    }

});