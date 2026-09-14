

document.addEventListener("DOMContentLoaded", function () {

    const searchInput = document.getElementById("searchInput");
    const filterType = document.getElementById("filterType");
    const cards = document.querySelectorAll(".donation-card");


    // =========================================================
    // SEARCH + FILTER
    // =========================================================

    function filterDonations() {

        const search = searchInput.value.toLowerCase().trim();
        const filter = filterType.value.toLowerCase();

        cards.forEach(card => {

            const name =
                card.dataset.name || "";

            const description =
                card.dataset.description || "";

            const source =
                card.dataset.source || "";

            const type =
                card.dataset.type || "";


            // SEARCH
            const matchSearch =
                name.includes(search) ||
                description.includes(search) ||
                source.includes(search) ||
                type.includes(search);


            // FILTER
            let matchFilter = true;

            if (filter !== "") {

                matchFilter =
                    type === filter;

            }


            // DISPLAY
            if (matchSearch && matchFilter) {

                card.classList.remove("hidden");

            } else {

                card.classList.add("hidden");

            }

        });

    }


    searchInput.addEventListener(
        "keyup",
        filterDonations
    );

    filterType.addEventListener(
        "change",
        filterDonations
    );


    // =========================================================
    // VIEW DONATION
    // =========================================================

    document.querySelectorAll(".viewBtn").forEach(button => {

        button.addEventListener("click", function (e) {

            e.stopPropagation();


            const type =
                (this.dataset.type || "").toUpperCase();


            // -------------------------------------------------
            // NGO
            // -------------------------------------------------

            document.getElementById("modalNgo")
                .textContent =
                this.dataset.ngo || "N/A";


            // -------------------------------------------------
            // TYPE
            // -------------------------------------------------

            let displayType = this.dataset.type || "N/A";

            if (type === "ONLINE_MONETARY") {

                displayType = "Online Monetary";

            } else if (type === "MONETARY") {

                displayType = "Monetary";

            } else if (type === "CONSUMABLE") {

                displayType = "Consumable";

            } else if (type === "REUSABLE") {

                displayType = "Reusable";

            }

            document.getElementById("modalType")
                .textContent = displayType;


            // -------------------------------------------------
            // DESCRIPTION
            // -------------------------------------------------

            document.getElementById("modalDescription")
                .textContent =
                this.dataset.description || "N/A";


            // -------------------------------------------------
            // AMOUNT
            // -------------------------------------------------

            const amountRow =
                document.getElementById("amountRow");

            const modalAmount =
                document.getElementById("modalAmount");


            /*
             * MONEY TYPES:
             * MONETARY
             * ONLINE_MONETARY
             */

            if (
                type === "MONETARY" ||
                type === "ONLINE_MONETARY"
            ) {

                amountRow.classList.remove("hidden");

                if (this.dataset.amount !== "") {

                    modalAmount.textContent =
                        "₱" +
                        Number(this.dataset.amount)
                            .toLocaleString(
                                "en-PH",
                                {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                }
                            );

                } else {

                    modalAmount.textContent = "N/A";

                }

            } else {

                amountRow.classList.add("hidden");

            }


            // -------------------------------------------------
            // UNIT
            // -------------------------------------------------

            const unitRow =
                document.getElementById("unitRow");


            if (
                type === "CONSUMABLE" ||
                type === "REUSABLE"
            ) {

                unitRow.classList.remove("hidden");

                document.getElementById("modalUnit")
                    .textContent =
                    this.dataset.unit || "N/A";

            } else {

                unitRow.classList.add("hidden");

            }


            // -------------------------------------------------
            // SOURCE
            // -------------------------------------------------

            document.getElementById("modalSource")
                .textContent =
                this.dataset.source || "N/A";


            // -------------------------------------------------
            // REFERENCE NUMBER
            // -------------------------------------------------

            document.getElementById("modalReference")
                .textContent =
                this.dataset.reference || "N/A";


            // -------------------------------------------------
            // DATE
            // -------------------------------------------------

            let date = this.dataset.date || "";

            if (date) {

                const parsedDate =
                    new Date(date + "T00:00:00");

                if (!isNaN(parsedDate)) {

                    date =
                        parsedDate.toLocaleDateString(
                            "en-US",
                            {
                                month: "long",
                                day: "2-digit",
                                year: "numeric"
                            }
                        );

                }

            }

            document.getElementById("modalDate")
                .textContent =
                date || "N/A";


            // -------------------------------------------------
            // STATUS
            // -------------------------------------------------

            let status =
                (this.dataset.status || "N/A")
                    .toUpperCase()
                    .replaceAll("_", " ");


            document.getElementById("modalStatus")
                .textContent = status;


            // -------------------------------------------------
            // SHOW MODAL
            // -------------------------------------------------

            document.getElementById("viewModal")
                .style.display = "flex";

        });

    });

});


// =========================================================
// CLOSE DONATION MODAL
// =========================================================

function closeModal() {

    document.getElementById("viewModal")
        .style.display = "none";

}


// =========================================================
// LOGOUT MODAL
// =========================================================

function openLogoutModal() {

    document.getElementById("logoutModal")
        .classList.remove("hidden");

    document.getElementById("logoutModal")
        .classList.add("flex");

}


function closeLogoutModal() {

    document.getElementById("logoutModal")
        .classList.add("hidden");

}

